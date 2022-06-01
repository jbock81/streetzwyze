<?php

/**
 * DbTableRow
 *  * @package
 * @author MyFw
 * @copyright Taiwo J
 * @version 2010
 * @access public
 */
class DbTableRow extends IdeoObject implements ArrayAccess, JsonSerializable
{

    use JsonSerializableTrait;

    /**
     *
     * @var array
     */
    protected $_data;

    /**
     *
     * @var DbTable
     */
    protected $_parentTable;
    protected $_id;

    /**
     *
     * @var string
     */
    protected $_pk_field;

    /**
     * DbTableRow::__construct()
     *
     * @param mixed $rowId
     * @param mixed $data
     * @param mixed $parentTable
     * @return
     */
    public function __construct($rowId = null, $data = array(), DbTable $parentTable = null)
    {
        $this->_id = $rowId;
        $this->_parentTable = $parentTable;
        $this->_data = (array)$data;
    }

    /**
     * DbTableRow::__get()
     *
     * @param mixed $name
     * @return
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        } else {
            trigger_error("Invalid column [{$name}] requested", E_USER_WARNING);
        }
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __unset($name)
    {
        unset($this->_data[$name]);
    }

    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * DbTableRow::toArray()
     *
     * @return
     */
    public function toArray()
    {
        return $this->_data;
    }

    /**
     * DbTableRow::updateMe()
     *
     * @param mixed $data
     * @param string $pkey
     * @return
     */
    public function update($data = array(), $pkey = "")
    {
        $data = (array)$data;
        $where = array();
        //find primary key field(s)
        if (!$pkey) {
            $keys = (array)$this->getParentTable()->getPrimaryKeyField();
            foreach ($keys as $pkfld) {
                $pkVal = (string)$this->_data[$pkfld];
                $where[] = "`$pkfld` = '{$pkVal}'";
            }

            if (!count($where)) {
                return 0;
            }

            $where = join(' AND ', $where);
        } else {
            if (!isset($this->_data[$pkey])) {
                return 0;
            }

            $pkVal = (string)$this->_data[$pkey];
            $where = "{$pkey} = '{$pkVal}'";
        }

        //filter out unnecessary fields
        $saveData = array();
        $fields = $this->getParentTable()->getFields(true);
        foreach (array_merge($this->_data, $data) as $field => $value) {
            if (in_array($field, $fields)) {
                $saveData[$field] = $value;
            }
        }

        if (empty($saveData)) {
            return false;
        }

        $result = $this->_parentTable->update($saveData, $where, 1);
        if ($result) {
            $this->setRowData($data);
        }

        return $result;
    }

    public function offsetExists($name)
    {
        return array_key_exists($name, $this->_data);
    }

    public function offsetGet($name)
    {
        return $this->__get($name);
    }

    public function offsetSet($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function offsetUnset($name)
    {
        unset($this->_data[$name]);
    }

    public function getParentTable()
    {
        return $this->_parentTable;
    }

    public function setRowData($data = array())
    {
        if (is_array($data)) {
            $this->_data = array_merge($this->_data, $data);
        }
        return $this;
    }

    public function mergeWithRowData(array $data)
    {
        $this->_data = $this->_data + $data;
        return $this;
    }

    public function __toString()
    {
        return "table: {$this->getParentTable()} __row_id__ : " . $this->_id;
    }

    public function save($forceReplace = false)
    {
        if (!count($this->_data)) {
            throw new RuntimeException("Data set can't be empty");
        } elseif (!$this->getParentTable()) {
            throw new RuntimeException("Parent table object not set");
        } else {
            $saveData = array();
            $fields = $this->getParentTable()->getFields(true);
            foreach ($this->_data as $field => $value) {
                if (in_array($field, $fields)) {
                    $saveData[$field] = $value;
                }
            }

            $insertId = $this->getParentTable()->insert($saveData, $forceReplace);
            if (!is_bool($insertId)) {
                $this->_id = $insertId;
                $pks = $this->_parentTable->getPrimaryKeyField();
                $firstPk = current($pks);
                if ($firstPk && !isset($this->_data[$firstPk])) {
                    $this->_data[$firstPk] = $insertId;
                }
            }

            return $insertId;
        }
    }

    public function setRowID($id = 0)
    {
        $this->_id = $id;
    }

    /**
     * Deletes Row
     * @return boolean
     */
    public function delete()
    {
        $primaryKeys = $this->_parentTable->getPrimaryKeyField();
        if (!empty($primaryKeys)) {
            $where = array();
            foreach ($primaryKeys as $pkField) {
                $where[] = "{$pkField} = '" . DbTable::escapeString($this->_data[$pkField]) . "'";
            }
            $whereCond = join(' AND ', $where);
            return $this->_parentTable->delete($whereCond, 1);
        }
        return false;
    }

    public function __ideoIgnoredJsonFields()
    {
        return ['parentTable', '_parentTable'];
    }

}
