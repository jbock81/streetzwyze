<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntitySearchParamEquals
 *
 * @author intelWorX
 */
class EntitySearchParamEquals extends EntitySearchParam
{

    protected $value;
    protected $negate;

    public function __construct($value, $negate = false, $field = null)
    {
        $this->tableField = $field;
        $this->value = $value;
        $this->negate = $negate;
    }

    public function addToWhere(\DbTableWhere $where, $field = null, $having = false)
    {
        $field = $field ?: $this->tableField;
        if ($field) {
            $having ? $where->having($field, $this->value, $this->negate) : $where->where($field, $this->value, $this->negate);
        }
        return $this;
    }

}

