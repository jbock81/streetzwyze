<?php

/**
 * DbTable
 *
 * @package
 * @author MyFw
 * @copyright Taiwo J
 * @version 2010
 * @access public
 */
abstract class DbTable extends IdeoObject implements QueryTargetI
{

    /**
     *
     * @var DbConnectionFactory
     */
    protected static $_db;

    /**
     *
     * @var String
     */
    protected $_name;
    protected $_prefix;
    protected $_fetch_as_object = true;
    protected $_row_class;
    protected $primaryKeyField = null;
    protected $fields = null;

    /**
     *
     * @var DbTableSpec : 'name', 'alias', 'fields'
     */
    protected $selectFrom;
    protected $joins = array();

    /**
     *
     * @var DbTableWhere
     */
    protected $selectWhere = null;

    const JOIN_LEFT = 'LEFT';
    const JOIN_RIGHT = 'RIGHT';
    const JOIN_INNER = 'INNER';
    const JOIN_DEFAULT = self::JOIN_INNER;

    /**
     * DbTable::__construct()
     *
     * @param mixed $table
     * @param bool $ignorePrefix
     * @return
     */
    public function __construct($table = null, $ignorePrefix = false, $rowClass = "DbTableRow")
    {
        $Config = SystemConfig::getInstance();

        if (!static::$_db) {
            if (!is_object($GLOBALS['db'])) {
                throwException(new DbTableException("\$GLOBALS['db'] is not a valid object"));
            }
            static::$_db = &$GLOBALS['db'];
        }

        if ($table === null) {
            $tmp = explode("\\", get_class($this));
            $className = array_pop($tmp);
            $table = preg_replace('/_table$/', '', GeneralUtils::camelCaseToDelimited($className));
        }

        $this->_prefix = $Config->system['table_prefix'];
        $this->_name = ($this->_prefix && !$ignorePrefix) ? $this->_prefix . $table : $table;
        $this->_row_class = $rowClass;
    }

    /**
     * DbTable::getDBAdapter()
     *
     * @return DbConnectionFactory
     */
    public function getDBAdapter()
    {
        return static::$_db;
    }

    /**
     *
     * Fetches a set of rows from the database table.
     * @param array|string $fields list of fields to fetch, all fields are fecthed by default.
     * @param string $where db safe where clasue
     * @param string $order the order
     * @param int $limit limit
     * @param int $offset offset
     * @param string $group_by field to group by
     * @param boolean $fetch_as_object set to true if you want table to fetch as objects. False otherwise.
     * @param boolean $forUpdate should the rows be locked for update?
     * @return mixed|DbTableRow[] an array of arrays if not fetching as objects, otherwise an array of DBTable rows
     */
    public function fetch($fields = null, $where = '', $order = '', $limit = 0, $offset = 0, $group_by = null, $fetch_as_object = null, $forUpdate = false)
    {
        if (!$fields) {
            $fields = "*";
        } elseif (is_array($fields)) {
            $fields = join(', ', $fields);
        } else {
            $fields = trim($fields);
        }

        $q = "select {$fields} from `{$this->_name}`";

        if ($where) {
            $q .= " WHERE {$where}";
        }

        if ($group_by) {
            $q .= " GROUP BY {$group_by} ";
        }

        if ($order) {
            $q .= " ORDER BY {$order}";
        }

        if ($limit > 0) {
            $q .= " LIMIT ";
            if ($offset) {
                $q .= " {$offset}, ";
            }
            $q .= " $limit";
        }

        if ($forUpdate) {
            $q .= " FOR UPDATE";
        }

        $result = static::$_db->query($q);
        //if(!$result->)
        if (method_exists($result, "fetch_all")) {
            $rowset = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $rowset = mysqli_result_fetch_all($result);
        }

        if (is_null($fetch_as_object)) {
            $fetch_as_object = $this->_fetch_as_object;
        }

        if ($fetch_as_object && count($rowset)) {
            $rowsetObjs = array();
            $idFld = $result->fetch_field_direct(0)->name;
            foreach ($rowset as $row) {
                $rowsetObjs[] = new $this->_row_class($row[$idFld], $row, $this);
            }

            $result->free();
            return $rowsetObjs;
        } else {
            $result->free();
            return $rowset;
        }
    }

    /**
     * DbTable::insert()
     *
     * @param mixed $rows
     * @param bool replace into incase of key duplicates or for updating
     * @param bool $is_multiple if it's multiple inserts
     * @return
     *
     */
    public function insert($rows = null, $replace = false, $is_multiple = false)
    {
        if (!count($rows)) {
            return null;
        }

        $action = $replace ? "REPLACE" : "INSERT";
        $q = "{$action} INTO `{$this->_name}`";
        $fields = $is_multiple ? array_keys(end($rows)) : array_keys($rows);
        $rows = $is_multiple ? $rows : array($rows);
        $valueSet = array();
        foreach ($rows as $row) {
            $k = array();
            foreach ($row as $val) {
                if (is_object($val)) {
                    $k[] = (string)$val;
                } elseif ($val === null) {
                    $k[] = 'NULL';
                } else {
                    $k[] = '\'' . static::$_db->real_escape_string($val) . '\'';
                }
            }
            $valueSet[] = '(' . join(',', $k) . ')';
        }

        $values = join(', ', $valueSet);
        if (is_numeric($fields[0])) {
            //treat as values only
            $q .= " VALUES {$values}";
        } else {
            $fields = join(',', $fields);
            $q .= " ({$fields}) VALUES {$values}";
        }

        $successful = static::$_db->query($q);
        if (!$successful) {
            return 0;
        }

        if ($is_multiple) {
            return $successful;
        }

        $insertId = static::$_db->insert_id;
        $primaryKey = (array)$this->getPrimaryKeyField();
        $firstPrimaryKeyField = $primaryKey[0];
        return $row[$firstPrimaryKeyField] ? $row[$firstPrimaryKeyField] : $insertId;
    }

    /**
     * DbTable::update()
     *
     * @param mixed $data
     * @param mixed $where
     * @param integer $limit
     * @return
     */
    public function update($data = null, $where = null, $limit = 0)
    {
        if (!count($data)) {
            return null;
        }

        $q = "UPDATE `{$this->_name}` SET ";
        foreach ($data as $fld => $val) {
            if (is_object($val)) {
                $q .= "{$fld}= " . $val . ", ";
            } elseif ($val === null) {
                $q .= "{$fld}= NULL, ";
            } else {
                $q .= "{$fld}= '" . static::$_db->real_escape_string($val) . "', ";
            }
        }

        $q = trim($q, ", ");
        //debug_op($q, 1);

        if ($where) {
            $q .= " WHERE {$where}";
        }

        if ($limit) {
            $q .= " LIMIT {$limit}";
        }
        //debug_op($q, 1);

        return static::$_db->query($q);
    }

    /**
     * DbTable::delete()
     *
     * @param mixed $where
     * @param integer $limit
     * @return
     */
    public function delete($where, $limit = 0)
    {
        $q = "delete from `{$this->_name}` where $where";
        if ($limit) {
            $q .= " LIMIT {$limit}";
        }

        static::$_db->query($q);
        return static::$_db->affected_rows >= 0 ? static::$_db->affected_rows : 0;
    }

    /**
     *
     * @param string $where DB safe where clause
     * @param string $order order string
     * @param boolean $lockRow should row be locked ? uses FOR UPDATE
     * @return null|DbTableRow
     */
    public function fetchRow($where = "", $order = "", $lockRow = false)
    {
        $rows = $this->fetch(null, $where, $order, 1, 0, null, null, $lockRow);
        return current($rows);
    }

    /**
     * DbTable::__sleep()
     *
     * @return
     */
    public function __sleep()
    {
        return array('_name');
    }

    /**
     * DbTable::__wakeup()
     *
     * @return
     */
    public function __wakeup()
    {
        global $db;
        static::$_db = $db;
    }

    /**
     * DbTable::getTablePrefix()
     *
     * @return
     */
    public function getTablePrefix()
    {
        return $this->_prefix;
    }

    /**
     * DbTable::getCardinality()
     *
     * @param string $where
     * @return
     */
    public function getCardinality($where = "")
    {
        $copy = $this->_fetch_as_object;
        $this->fetchAsObject(false);
        $countField = new DbTableFunction("count(*) as c");
        $trStat = $this->fetch(array($countField), $where);
        $this->fetchAsObject($copy);
        return (int)$trStat[0]['c'];
    }

    /**
     * DbTable::fetchAll()
     *
     * @param mysqli_result $result
     * @return array
     */
    public function fetchAll($result = null)
    {
        if (method_exists($result, "fetch_all")) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return mysqli_result_fetch_all($result);
        }
    }

    /**
     * DbTable::fetchAsObject()
     * @uses fetch the row as object
     * @param bool $b
     * @return
     */
    public function fetchAsObject($b = true)
    {
        //debug_op(debug_backtrace());  
        $this->_fetch_as_object = $b;
        return $this;
    }

    /**
     * DbTable::setRowClass()
     * @uses sets teh class to be used as the row of the table.
     * className mus be a descendant of DbTableRow
     * @param string $className
     * @return fluent interface
     */
    public function setRowClass($className = "")
    {
        $this->_row_class = $className;
        return $this;
    }

    /**
     * DbTable::getRowClass()
     *
     * @return the name of the class used to handle rows
     */
    public function getRowClass()
    {
        return $this->_row_class;
    }

    public function __toString()
    {
        return $this->_name;
    }

    public function fetchUsingIDAsKey($fields = null, $where = '', $order = '', $limit = 0, $offset = 0)
    {
        //assumes primary key is column 1
        $rowset = $this->fetch($fields, $where, $order, $limit, $offset);
        $rowArray = array();
        if (count($rowset)) {
            $primary_key_field = $this->getPrimaryKeyField();
            if (is_array($primary_key_field)) {
                $primary_key_field = $primary_key_field[0];
            }
            foreach ($rowset as $row) {
                $rowArray[$row[$primary_key_field]] = $row;
            }
        }
        return $rowArray;
    }

    public function fetchRowsGroupedAsFieldArray($key_field = null, $fields = null, $where = '', $order = '', $limit = 0, $offset = 0, $group_by = null)
    {
        //assumes primary key is column 1
        $rowset = $this->fetch($fields, $where, $order, $limit, $offset, $group_by);
        $rowArray = array();
        if (count($rowset)) {
            if (!$key_field) {
                $primary_key_field = (array)$this->getPrimaryKeyField();
                $key_field = array_shift($primary_key_field);
            }
            foreach ($rowset as $row) {
                $key = $row[$key_field] ? $row[$key_field] : 0;
                if (!isset($rowArray[$key])) {
                    $rowArray[$key] = array();
                }
                $rowArray[$key][] = $row;
            }
        }
        return $rowArray;
    }

    public function fetchFieldUsingIDAsKey($field, $id = "id", $where = '', $order = '', $limit = 0, $offset = 0, $group_by = null)
    {
        $fields = array_unique(array($id, $field));
        if (!$order && !$field instanceof DbTableFunction) {
            $order = "{$field} ASC";
        }

        $rowset = $this->fetch($fields, $where, $order, $limit, $offset, $group_by, false);
        $rowArray = array();

        if (count($rowset)) {
            if (($field instanceof DbTableFunction) || ($id instanceof DbTableFunction)) {
//                $field = (string) $field;
                $fields = array_keys($rowset[0]);
                $id = $fields[0];
                $field = $fields[1] ?: $id;
            }

            foreach ($rowset as $row) {
                $rowArray[$row[$id]] = $row[$field];
            }
        }
        return $rowArray;
    }

    public function getPrimaryKeyField()
    {
        $pKeys = array();
        if ($this->primaryKeyField) {
            return $this->primaryKeyField;
        }
        $metadata = $this->getMetaData();

        foreach ($metadata as $column) {
            if ($column['Key'] == "PRI") {
                $pKeys[] = $column['Field'];
            }
        }


        return $this->primaryKeyField = $pKeys; //;count($pKeys) > 1? $pKeys: $pKeys[0];
    }

    public function getMetaData()
    {
        $result = static::$_db->query("DESCRIBE {$this}");
        $descriptions = $this->fetchAll($result);
        return $descriptions;
    }

    public static function escapeString($str)
    {
        return static::$_db->real_escape_string($str);
    }

    public static function generateInList(array $values)
    {

        $in = array();
        foreach ($values as $val) {
            $in[] = "'" . self::escapeString($val) . "'";
        }

        if (!count($in)) {
            $in[] = '"' . uniqid('db_rand') . '"';
        }


        return join(', ', $in);
    }

    public static function getDbConfig()
    {
        return SystemConfig::getInstance()->db;
    }

    public function lockTableForRead()
    {
        return static::$_db->query("LOCK TABLES `{$this->_name}` READ");
    }

    public function lockTableForWrite()
    {
        return static::$_db->query("LOCK TABLES `{$this->_name}` WRITE");
    }

    public function unlockTable()
    {
        return static::$_db->query("UNLOCK TABLES");
    }

    /**
     *
     * @return \DbConnectionFactory
     */
    public static function getDB()
    {
        return static::$_db;
    }

    /**
     * Get a full description of fields from mysql table
     *
     * @return array hash map of 'FieldName'=>'MetadataArray'
     */
    public function getFields($namesOnly = false)
    {
        if (!isset($this->fields)) {
            $meta = $this->getMetaData();
            $this->fields = array();
            foreach ($meta as $fieldRow) {
                $this->fields[$fieldRow['Field']] = $fieldRow;
            }
        }
        return $namesOnly ? array_keys($this->fields) : $this->fields;
    }

    public function selectFrom($fields = DbTableSpec::ALL_FIELDS, $alias = '')
    {
        $this->reset();
        $spec = $alias ? array($alias => $this->_name) : $this->_name;
        $this->selectFrom = new DbTableSpec($spec, $fields);
        return $this;
    }

    public function reset()
    {
        $this->joins = array();
        $this->selectWhere = null;
        $this->selectFrom = null;
        return $this;
    }

    /**
     *
     * @param string|DbTableWhere $where
     * @param bool $merge Decides if the new where should be merged with the previous
     *
     */
    public function where($where, $merge = false)
    {
        if (!is_a($where, DbTableWhere::getClass())) {
            $whereObj = new DbTableWhere($this);
            $whereObj->whereString((string)$where);
        } else {
            $whereObj = $where;
            if (!$whereObj->getQueryTarget()) {
                $whereObj->setQueryTarget($this);
            }
        }

        $this->selectWhere = $merge && $this->selectWhere ? $this->selectWhere->combineWith($whereObj) : $whereObj;
        return $this;
    }

    /**
     *
     * @return DbTableWhere
     */
    public function selectWhere($field = null, $value = null, $op = null)
    {
        if (!$this->selectWhere) {
            $this->selectWhere = DbTableWhere::get()->setQueryTarget($this);
        }

        if ($field !== null) {
            $this->selectWhere->op($field, $value, $op);
        }

        return $this->selectWhere;
    }

    public function join($spec, $on = '', $fields = null, $joinType = self::JOIN_DEFAULT)
    {
        $this->joins[] = array(
            'type' => $joinType,
            'spec' => new DbTableSpec($spec, $fields, $on),
        );
        return $this;
    }

    public function leftJoin($spec, $on = '', $fields = null)
    {
        return $this->join($spec, $on, $fields, self::JOIN_LEFT);
    }

    public function rightJoin($spec, $on = '', $fields = null)
    {
        return $this->join($spec, $on, $fields, self::JOIN_RIGHT);
    }

    public function innerJoin($spec, $on, $fields = null)
    {
        return $this->join($spec, $on, $fields, self::JOIN_INNER);
    }

    /**
     *
     * @param boolean $countFieldOnly if the count field alone is needed
     * This saves alot of time if the query does have groupBy or having clauses
     * Please note that setting this variable to TRUE automatically ignores
     * GROUP BY and HAVING clauses in the WHERE object
     *
     * @return string
     */
    public function generateSQL($countFieldOnly = false)
    {
        if (!$this->selectFrom) {
            return "";
        }

        $sql = "SELECT ";
        if ($countFieldOnly) {
            $sql .= " COUNT(*) as c ";
        } else {
            $sql .= join(', ', $this->selectFrom->getFields()) . " ";
            foreach ($this->joins as $joinGroup) {
                /* @var $joinSpec DbTableSpec */
                $joinSpec = $joinGroup['spec'];
                if (count($joinSpec->getFields())) {
                    $sql .= ", " . join(', ', $joinSpec->getFields());
                }
            }
        }

        $sql .= " FROM ";
        $sql .= " " . $this->selectFrom->getTableName() . " AS " . $this->selectFrom->getAlias();
        foreach ($this->joins as $joinGroup) {
            /* @var $joinSpec DbTableSpec */
            $joinSpec = $joinGroup['spec'];
            $sql .= " {$joinGroup['type']} JOIN {$joinSpec->getTableName()} AS {$joinSpec->getAlias()}";
            if (($joinCond = $joinSpec->getOnCondition())) {
                $sql .= " ON " . $joinCond;
            }
        }

        if ($this->selectWhere) {
            $sql .= " WHERE " . $this->selectWhere->getWhereString();
            if (!$countFieldOnly && $this->selectWhere->hasGroupBy()) {
                $sql .= " GROUP BY " . $this->selectWhere->getGroupBy();
            }

            if (!$countFieldOnly && $this->selectWhere->hasHaving()) {
                $sql .= " HAVING " . $this->selectWhere->getHavingString();
            }
            if ($this->selectWhere->hasOrderBy()) {
                $sql .= " ORDER BY " . $this->selectWhere->getOrderBy();
            }

            $sql .= $this->selectWhere->getLimitString();
        }

        return $sql;
    }

    public function query($countOnly = false)
    {
        $useCountFieldOnly = $countOnly &&
            (!$this->selectWhere ||
                ($this->selectWhere && !$this->selectWhere->hasHaving() && !$this->selectWhere->hasGroupBy()));

        if (!($sql = $this->generateSQL($useCountFieldOnly))) {
            return $countOnly ? 0 : array();
        }

        $queryResult = static::$_db->query($sql);
        if (!$queryResult) {
            return $countOnly ? 0 : array();
        }

        if ($useCountFieldOnly) {
            $row = $queryResult->fetch_assoc();
            return $row['c'];
        }

        if ($countOnly) {
            $num_rows = $queryResult->num_rows;
            $queryResult->free();
            return $num_rows;
        }


        return mysqli_result_fetch_all($queryResult);
    }

    /**
     *
     * @return DbTableSpec
     */
    public function getSelectFrom()
    {
        return $this->selectFrom;
    }

    public function isJoinedWith($alias)
    {
        foreach ($this->joins as $join) {
            /* @var $join  DbTableSpec[] */
            if ($join['spec']->getAlias() == $alias) {
                return true;
            }
        }
        return false;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function countRows()
    {
        return $this->query(true);
    }

    public function groupBy($expr)
    {
        $this->selectWhere()
            ->setGroupBy($expr);

        return $this;
    }

    public function orderBy($field, $dir = 'ASC')
    {
        $this->selectWhere()
            ->setOrderBy($field, $dir);

        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->selectWhere()
            ->setLimitAndOffset($limit, $offset);

        return $this;
    }

    /**
     *
     * Gets the union of one or more queries as long as the queries have same field
     * @param DbTable[] $queries
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @param bool $count
     */
    public static function union(array $queries, $order = null, $limit = 0, $offset = 0, $count = false)
    {
        $toJoin = array();

        if (empty($queries)) {
            SystemLogger::warn("Empty Queries specified for Union!");
            return $count ? 0 : array();
        }

        foreach ($queries as $query) {
            $toJoin[] = sprintf("(%s)", ($query instanceof DbTable) ? $query->generateSQL(false) : $query);
        }

        $unionQuery = join(' UNION ', $toJoin);
        if ($count) {
            $sql = "SELECT COUNT(*) AS C FROM ({$unionQuery}) as union_table";
            $result = self::$_db->query($sql);
            if ($result) {
                return current($result->fetch_assoc());
            } else {
                return 0;
            }
        }

        if ($order) {
            $unionQuery .= " ORDER BY " . $order;
        }

        $unionQuery .= self::getLimitOffsetString($limit, $offset);
        return mysqli_result_fetch_all(self::$_db->query($unionQuery));
    }

    public static function getLimitOffsetString($limit = 0, $offset = 0)
    {
        $string = "";
        $limit = (int)$limit;
        $offset = (int)$offset;
        if ($limit) {
            $string .= " LIMIT ";
            if ($offset) {
                $string .= $offset . ", ";
            }
            $string .= $limit;
        }
        return $string;
    }

    public function results(DbTableWhere $where)
    {
        return $this->where($where)
            ->query(false);
    }

    public function resultsCount(DbTableWhere $where)
    {
        return $this->where($where)
            ->query(true);
    }

    public static function setDb(&$db)
    {
        static::$_db = &$db;
    }

}

class DbTableException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message, null, null);
    }

}
