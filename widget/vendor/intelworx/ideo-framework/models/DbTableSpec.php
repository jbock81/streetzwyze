<?php

/**
 * Description of DbTableSpec
 *
 * @author intelWorX
 */
final class DbTableSpec extends IdeoObject
{

    protected $fields = array();

    const ALL_FIELDS = '*';

    protected $tableName;
    protected $alias;
    protected $onCondition;

    /**
     *
     * @param string $tableSpec
     * @param array $fields
     * @param string $on
     */
    public function __construct($tableSpec, $fields = self::ALL_FIELDS, $on = '')
    {
        $fields = (array)$fields;
        if (is_array($tableSpec)) {
            $this->alias = key($tableSpec);
            $this->tableName = (string)current($tableSpec);
        } else {
            $this->tableName = (string)$tableSpec;
            $this->alias = $this->tableName;
        }


        foreach ($fields as $alias => $field) {
            $this->addField($field, $alias);
        }

        $this->onCondition = $on;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getOnCondition()
    {
        return $this->onCondition;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    public function addField($field, $alias = null)
    {
        if (!is_object($field)) {
            if (strpos($field, "{$this->alias}.") === FALSE) {
                $field = "{$this->alias}.{$field}";
            }
            $field = DbTableWhere::escapeField($field);
        }

        if (is_null($alias) || is_numeric($alias) || preg_match('/\s+AS\s+/i', strval($field))) {
            $this->fields[] = $field;
        } else {
            $this->fields[] = $field . " AS " . DbTableWhere::escapeField($alias);
        }

        return $this;
    }

}
