<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntitySearchParamLike
 *
 * @author intelWorX
 */
class EntitySearchParamLike extends EntitySearchParamEquals
{

    const LIKE_MODE_PREFIX = 0x1;
    const LIKE_MODE_SUFFIX = 0x2;
    const LIKE_MODE_IN_TEXT = 0x3;

    protected $likeMode = self::LIKE_MODE_IN_TEXT;

    public function __construct($value, $negate = false, $field = null, $likeMode = self::LIKE_MODE_IN_TEXT)
    {
        parent::__construct($value, $negate, $field);
        $this->likeMode = $likeMode;
    }

    protected function getLikeVal($value)
    {
        $likeVal = '';
        if ($this->likeMode & self::LIKE_MODE_SUFFIX) {
            $likeVal .= '%';
        }

        $likeVal .= DbTable::escapeString($value ?: $this->value);

        if ($this->likeMode & self::LIKE_MODE_PREFIX) {
            $likeVal .= '%';
        }
        return $likeVal;
    }

    public function addToWhere(\DbTableWhere $where, $field = null, $having = false)
    {
        $field = $field ?: $this->tableField;
        if ($field) {
            $whereString = DbTableWhere::escapeField($field) . " LIKE '" . $this->getLikeVal($this->value) . "'";
            $having ? $where->havingCondition($whereString) : $where->whereString($whereString);
        }
        return $this;
    }

}
