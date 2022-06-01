<?php

/**
 * Description of DbTableSelect
 *
 * @author intelWorX
 * @method DbTableWhere nWhere(string $field, mixed $value, bool $negate = false) Description
 *
 */
class DbTableWhere extends IdeoObject
{

    protected $where = array();
    protected $whereOr = array();
    protected $limit = 0;
    protected $offset = 0;
    protected $groupBy = array();
    protected $having = array();
    protected $orderBy = array();

    /**
     *
     * @var QueryTargetI
     */
    protected $queryTarget;

    const LIKE_PREFIX = 0x1;
    const LIKE_SUFFIX = 0x2;
    const LIKE_IN_TEXT = 0x3;

    public function __construct(QueryTargetI $queryTarget = null)
    {
        $this->queryTarget = $queryTarget;
    }

    /**
     * @deprecated since version 2.0.dev
     * @param array $fields
     * @return \DbTableWhere
     */
    public function addFields($fields)
    {

    }

    /**
     *
     * @param string|DbExpr $field
     * @param string|DbExpr $value
     * @param boolean $negate
     * @return DbTableWhere
     */
    public function where($field, $value, $negate = false)
    {
        return $this->_where($field, $value, $negate ? " != " : " = ");
    }

    public function op($field, $value, $operator = '=')
    {
        return $this->_where($field, $value, $operator);
    }

    public function whereIsNull($field, $negate = false)
    {
        return $this->_where($field, new DbTableFunction("NULL"), $negate ? " IS NOT " : " IS ");
    }

    /**
     *
     * @param string|DbExpr $field
     * @param mixed $value
     * @param bool $orEqual
     * @return \DbTableWhere
     */
    public function whereGreaterThan($field, $value, $orEqual = false)
    {
        return $this->_where($field, $value, $orEqual ? " >= " : " > ");
    }

    /**
     *
     * @param string|DbExpr $field
     * @param mixed $value
     * @param bool $orEqual
     * @return \DbTableWhere
     */
    public function whereLessThan($field, $value, $orEqual = false)
    {
        return $this->_where($field, $value, $orEqual ? " <= " : " < ");
    }

    /**
     *
     * @param string|DbExpr $field
     * @param mixed $lowValue
     * @param mixed $highValue
     * @return DbTableWhere
     */
    public function whereBetween($field, $lowValue, $highValue)
    {
        return $this->whereLessThan($field, $highValue, true)
            ->whereGreaterThan($field, $lowValue, true);
    }

    /**
     *
     * @param string|DbExpr $field
     * @param mixed $value
     * @param string $operator
     * @return \DbTableWhere
     */
    protected function _where($field, $value, $operator = '=')
    {
        if (is_object($value)) {
            $this->where[] = self::escapeField($field) . " {$operator} " . $value;
        } else {
            $this->where[] = self::escapeField($field) . " {$operator} '" . self::escapeValue($value) . "'";
        }
        return $this;
    }

    public function whereLike($field, $text, $mode = self::LIKE_IN_TEXT, $negate = false)
    {
        $this->where[] = $this->_like($field, $text, $mode, $negate);
        return $this;
    }

    public function whereOrLike($field, $text, $mode = self::LIKE_IN_TEXT, $negate = false)
    {
        $this->whereOr[] = $this->_like($field, $text, $mode, $negate);
        return $this;
    }

    public function havingLike($field, $text, $mode = self::LIKE_IN_TEXT, $negate = false)
    {
        $this->having[] = $this->_like($field, $text, $mode, $negate);
        return $this;
    }

    public function _like($field, $text, $mode = self::LIKE_IN_TEXT, $negate = false)
    {
        $str = self::escapeField($field);
        if ($negate) {
            $str .= ' NOT';
        }

        $str .= " LIKE '";

        if (self::LIKE_SUFFIX & $mode) {
            $str .= '%';
        }

        $str .= self::escapeValue($text);

        if (self::LIKE_PREFIX & $mode) {
            $str .= '%';
        }

        $str .= "'";

        return $str;
    }

    /**
     *
     * @param type $condition
     * @return \DbTableWhere
     */
    public function havingCondition($condition)
    {
        $this->having[] = $condition;
        return $this;
    }

    public function having($field, $value, $negate = false)
    {
        return $this->_having($field, $value, $negate ? " != " : " = ");
    }

    public function havingGreaterThan($field, $value, $orEqual = false)
    {
        return $this->_having($field, $value, $orEqual ? " >= " : " > ");
    }

    public function havingLessThan($field, $value, $orEqual = false)
    {
        return $this->_having($field, $value, $orEqual ? " <= " : " < ");
    }

    public function havingBetween($field, $lowValue, $highValue)
    {
        return $this->havingLessThan($field, $highValue, true)
            ->havingGreaterThan($field, $lowValue, true);
    }

    public function havingInArray($field, array $values, $negate = FALSE)
    {
        $not = $negate ? ' NOT ' : '';
        if (count($values)) {
            $this->having[] = self::escapeField($field) . $not . ' IN (' . DbTable::generateInList($values) . ')';
        }
        return $this;
    }

    protected function _having($field, $value, $operator)
    {
        if (is_object($value)) {
            $this->having[] = self::escapeField($field) . " {$operator} " . $value;
        } else {
            $this->having[] = self::escapeField($field) . " {$operator} '" . self::escapeValue($value) . "'";
        }
        return $this;
    }

    public function whereString($string)
    {
        $this->where[] = (string)$string;
        return $this;
    }

    public function whereInSql($field, $sql, $negate = FALSE)
    {
        $not = $negate ? ' NOT ' : '';
        $this->where[] = self::escapeField($field) . $not . ' IN (' . $sql . ')';
        return $this;
    }

    public function whereInArray($field, array $values, $negate = FALSE)
    {
        $not = $negate ? ' NOT ' : '';
        if (count($values)) {
            $this->where[] = self::escapeField($field) . $not . ' IN (' . DbTable::generateInList($values) . ')';
        }
        return $this;
    }

    public function whereOr($field, $value, $negate = false)
    {
        $op = $negate ? "!=" : "=";
        if (is_object($value)) {
            $this->whereOr[] = self::escapeField($field) . " {$op} " . self::escapeValue($value);
        } else {
            $this->whereOr[] = self::escapeField($field) . " {$op} '" . self::escapeValue($value) . "'";
        }
        return $this;
    }

    public function whereOrString($string)
    {
        $this->whereOr[] = $string;
        return $this;
    }

    public static function escapeValue($str)
    {
        if (is_object($str)) {
            return (string)$str;
        } else {
            return DbTable::escapeString($str);
        }
    }

    public function setLimitAndOffset($limit, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    public function setGroupBy($field)
    {
        $this->groupBy[] = self::escapeField($field);
        return $this;
    }

    public function setOrderBy($field, $dir = 'ASC')
    {
        $this->orderBy[] = self::escapeField($field) . ' ' . $dir;
        return $this;
    }

    public static function escapeField($str)
    {
        if (is_object($str)) {
            return (string)$str;
        } else {
            $parts = explode('.', $str);
            $op = "";
            foreach ($parts as $part) {
                if (!preg_match('/`/', $part)) {
                    $part = "`{$part}`";
                }
                $op .= "{$part}.";
            }
            return trim($op, ".");
        }
    }

    public function getWhereString()
    {
        $where = [];
        if (count($this->whereOr)) {
            $where[] = '(' . join(' OR ', $this->whereOr) . ')';
        }

        if (count($this->where)) {
            $where[] = '(' . join(' AND ', $this->where) . ')';
        }


        return empty($where) ? "1" : join(' AND ', $where);
    }

    public function getOrderBy()
    {
        return join(', ', (array)$this->orderBy);
    }

    public function hasOrderBy()
    {
        return count($this->orderBy) > 0;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getGroupBy()
    {
        return join(', ', (array)$this->groupBy);
    }

    public function hasGroupBy()
    {
        return count($this->groupBy) > 0;
    }

    public function getHaving()
    {
        return $this->having;
    }

    public function getHavingString()
    {
        return join(' AND ', (array)$this->having);
    }

    public function hasHaving()
    {
        return count($this->having) > 0;
    }

    public function __toString()
    {
        return $this->getWhereString();
    }

    public function getLimitString()
    {
        $str = "";
        if ($this->limit) {
            $str = " LIMIT ";
            if ($this->offset) {
                $str .= " {$this->offset}, ";
            }
            $str .= " {$this->limit}";
        }
        return $str;
    }

    public function hasWhere()
    {
        return count($this->where) > 0;
    }

    public function hasWhereOr()
    {
        return count($this->whereOr) > 0;
    }

    /**
     *
     * Combines two DbTableWhere conditions, returns a new Object, neither object is modified
     * @param DbTableWhere $where
     * @return \DbTableWhere
     *
     */
    public function combineWith(DbTableWhere $where)
    {
        $newWhere = clone $this;
        $newWhere->having = array_merge($newWhere->having, $where->having);
        $newWhere->where = array_merge($newWhere->where, $where->where);
        $newWhere->whereOr = array_merge($newWhere->whereOr, $where->whereOr);
        $newWhere->groupBy = array_merge($newWhere->groupBy, $where->groupBy);
        $newWhere->orderBy = array_merge($newWhere->orderBy, $where->orderBy);
        //$newWhere->fields = array_merge($newWhere->fields, $where->fields);
        return $newWhere;
    }

    /**
     *
     * @return self
     */
    public static function get(QueryTargetI $target = null)
    {
        return new self($target);
    }

    /**
     *
     * @param string $name
     * @param array $arguments
     * @return static returns a new where
     */
    public static function __callStatic($name, $arguments)
    {
        $where = static::get();
        $nameNew = preg_replace('/^n/', '', $name);
        if (!method_exists($where, $nameNew)) {
            throw new Exception("Invalid clause [$nameNew] on wehre object");
        }
        return call_user_func_array(array($where, $nameNew), $arguments);
    }

    /**
     * Returns the result from executing this.
     * @return array of mixed results.
     * @throws QueryTargetException
     */
    public function results()
    {
        if (!$this->queryTarget) {
            throw new QueryTargetException("There is no query target specified.");
        }

        return $this->queryTarget->results($this);
    }

    public function count()
    {
        if (!$this->queryTarget) {
            throw new QueryTargetException("There is no query target specified.");
        }

        return $this->queryTarget->resultsCount($this);
    }

    public function getQueryTarget()
    {
        return $this->queryTarget;
    }

    public function setQueryTarget(QueryTargetI $queryTarget)
    {
        $this->queryTarget = $queryTarget;
        return $this;
    }

    public function hash()
    {
        $pack = [
            'where' => $this->getWhereString(),
            'having' => $this->getHavingString(),
            'order' => $this->getOrderBy(),
            'limit' => $this->getLimitString(),
            'groupBy' => $this->getGroupBy()
        ];

        return sha1(json_encode($pack));
    }

}

class QueryTargetException extends RuntimeException
{

}
