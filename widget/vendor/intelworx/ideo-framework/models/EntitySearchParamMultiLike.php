<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntitySerchParamMultiLike
 *
 * @author JosephT
 */
class EntitySearchParamMultiLike extends EntitySearchParamLike
{

    public function __construct(array $value, $negate = false, $field = null, $likeMode = self::LIKE_MODE_IN_TEXT)
    {
        parent::__construct($value, $negate, $field, $likeMode);
    }

    public function addToWhere(\DbTableWhere $where, $field = null, $having = false)
    {
        $field = $field ?: $this->tableField;
        if ($field) {
            $fld = DbTableWhere::escapeField($field);
            $whereStringArr = [];
            foreach ($this->value as $value) {
                $whereStringArr[] = $fld . " LIKE '" . $this->getLikeVal($value) . "'";
            }
            $whereString = '(' . join(' OR ', $whereStringArr) . ')';
            $having ? $where->havingCondition($whereString) : $where->whereString($whereString);
        }
        return $this;
    }

}
