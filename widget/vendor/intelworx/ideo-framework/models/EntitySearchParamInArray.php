<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntitySearchParamInArray
 *
 * @author intelWorX
 */
class EntitySearchParamInArray extends EntitySearchParam
{

    protected $values = array();
    protected $negate;

    public function __construct(array $values, $negate = false, $field = null)
    {
        $this->values = $values;
        $this->negate = $negate;
        $this->tableField = $field;
    }

    public function addToWhere(\DbTableWhere $where, $field = null, $having = false)
    {
        $field = $field ?: $this->tableField;
        if ($field) {
            $having ?
                $where->havingInArray($field, $this->values, $this->negate) :
                $where->whereInArray($field, $this->values, $this->negate);
        }
        return $this;
    }

}

