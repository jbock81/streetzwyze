<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DefaultTable
 *
 * @author intelWorX
 */
class EntityTable extends DbTable
{

    /**
     *
     * @param string $entityClass name of the entity.
     * @param bool $ignorePrefix
     * @param string $tableName
     */
    public function __construct($entityClass, $ignorePrefix = true, $tableName = null)
    {
        if (is_null($tableName)) {
            $tmp = explode("\\", $entityClass);
            $className = array_pop($tmp);
            $tableName = preg_replace('/_table$/', '', GeneralUtils::camelCaseToDelimited($className));
        }
        parent::__construct($tableName, $ignorePrefix, $entityClass);
    }

}
