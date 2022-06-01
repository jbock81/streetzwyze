<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntitySearchParamIsNull
 *
 * @author intelWorX
 */
class EntitySearchParamIsNull extends EntitySearchParam
{

    protected $field;
    protected $negate = false;

    public function __construct($field = null, $negate = false)
    {
        $this->field = $field;
        $this->negate = $negate;
    }

    public function addToWhere(DbTableWhere $where, $field = null, $having = false)
    {
        $field = $field ?: $this->field;
        if ($field) {
            $str = "{$field} IS ";
            $str .= ($this->negate) ? "NOT NULL" : "NULL";
            if ($having) {
                $where->havingCondition($str);
            } else {
                $where->whereString($str);
            }
        }
        return $this;
    }

}
