<?php

/**
 * Description of ModelEntity
 *
 * @author intelWorX
 */
abstract class ModelEntity extends \Entity
{

    //put your code here
    private static $instances = array();

    public function __construct($rowId, $data = array(), \DbTable $parentTable = null)
    {
        parent::__construct($rowId, $data, $parentTable ?: static::table());
    }

    /**
     *
     * @return ModelEntityManager
     */
    public static function manager()
    {
        $instanceKey = static::getClass();

        if ($instanceKey === ModelEntity::getClass()) {
            throw new ModelException("Attempt to retrieve entity manager for a base ModelEntity object.");
        }

        $managerClass = GenericEntityManager::getManagerNamespace() . '\\' . static::getClassBasic() . "Manager";

        if (class_exists($managerClass)) {
            return call_user_func(array($managerClass, 'instance'));
        }

        if (!array_key_exists($instanceKey, self::$instances)) {
            self::$instances[$instanceKey] = new ModelEntityManager(static::getClassBasic(), static::getClass());
        }

        return self::$instances[$instanceKey];
    }

    /**
     *
     * @return \EntityTable
     */
    public static function table()
    {
        return static::manager()->getEntityTable();
    }

    /**
     *
     * @param mixed $id the identified
     * @param string $fld string to search with
     * @param bool $forceReload should a reload be forced?
     * @return static
     */
    public static function findOne($id, $fld = null, $forceReload = false)
    {
        return static::manager()->getEntity($id, $fld, null, $forceReload);
    }

    /**
     * Creates an entity
     * @param array $data
     * @param boolean $returnObject if  created entity object should be returned or not
     * @return null|mixed|ModelEntity null on failure, the ID of the entity if $returnObject is false or
     *   the new entity object otherwise
     */
    public static function saveEntity(array $data, $returnObject = false, $forceSave = false, $cascade = true)
    {
        $saveId = static::manager()->createEntity($data)
            ->save($forceSave, $cascade);

        if ($saveId) {
            return $returnObject ? static::manager()->getEntity($saveId) : $saveId;
        }

        return null;
    }

    /**
     *
     * @return array mapping of sort fields to their description
     */
    public static function sortFields()
    {
        return [];
    }

    /**
     *
     * @return array mapping filters to callbacks that accept $filter
     */
    public static function filterSetters()
    {
        return [];
    }

    /**
     *
     * @param string|DbTableWhere $where
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return static[]
     */
    public static function all($where = null, $order = null, $limit = 0, $offset = 0)
    {
        return static::manager()->getEntityTable()
            ->fetch(null, (string)$where, $order, $limit, $offset);
    }

    /**
     *
     * Returns a Where query builder attached to the entity manager for this entity.
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @return DbTableWhere Description
     */
    public static function where($field = null, $operator = null, $value = null)
    {
        $where = DbTableWhere::get(static::manager());
        if ($field && $operator) {
            $where->op($field, $value, $operator);
        }

        return $where;
    }

}

/**
 * Description of ModelEntityManager
 *
 * @author intelWorX
 */
class ModelEntityManager extends GenericEntityManager
{

    public function __construct($entityName, $entityClassname = null)
    {
        parent::__construct($entityName, $entityClassname);

        if (is_callable(array($entityClassname, 'sortFields'))) {
            $this->SORT_FIELDS = call_user_func(array($entityClassname, 'sortFields'));
        }

        if (is_callable(array($entityClassname, 'filterSetters'))) {
            $this->filterSetters = call_user_func(array($entityClassname, 'filterSetters'));
        }
    }

}

class ModelException extends RuntimeException
{

}
