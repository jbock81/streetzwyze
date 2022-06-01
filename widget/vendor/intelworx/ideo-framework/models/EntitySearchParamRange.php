<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntitySearchParamRange
 *
 * @author intelWorX
 */
class EntitySearchParamRange extends EntitySearchParam
{

    protected $high;
    protected $low;
    protected $inclusive;

    public function __construct($low, $high = null, $inclusive = true, $field = null)
    {
        $this->low = $low;
        $this->high = $high;
        $this->inclusive = $inclusive;
        $this->tableField = $field;
    }

    public function addToWhere(\DbTableWhere $where, $field = null, $having = false)
    {
        $field = $field ?: $this->tableField;
        if ($field) {
            if ($this->high !== null) {
                $having ?
                    $where->havingLessThan($field, $this->high, $this->inclusive) :
                    $where->whereLessThan($field, $this->high, $this->inclusive);
            }

            if ($this->low !== null) {
                $having ?
                    $where->havingGreaterThan($field, $this->low, $this->inclusive) :
                    $where->whereGreaterThan($field, $this->low, $this->inclusive);
            }
        }

        return $this;
    }

}

