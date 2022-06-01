<?php

/**
 * Description of GenericEntityManager
 *
 * @author JosephT
 */
class GenericEntityManager extends EntityManager
{

    protected static $entityNamespace = "\\";
    protected static $dbTableNamespace = "\\";
    protected static $managerNamespace = "\\";

    public static function getEntityNamespace()
    {
        return static::$entityNamespace;
    }

    public static function getDbTableNamespace()
    {
        return static::$dbTableNamespace;
    }

    public static function getManagerNamespace()
    {
        return static::$managerNamespace;
    }

    public static function setEntityNamespace($entityNamespace)
    {
        static::$entityNamespace = $entityNamespace;
    }

    public static function setDbTableNamespace($dbTableNamespace)
    {
        static::$dbTableNamespace = $dbTableNamespace;
    }

    public static function configure($entityNs = "\\", $dbTableNs = "\\", $managerNs = "\\")
    {
        static::setEntityNamespace($entityNs);
        static::setDbTableNamespace($dbTableNs);
        static::setManagerNamespace($managerNs);
    }

    public static function setManagerNamespace($managerNamespace)
    {
        static::$managerNamespace = $managerNamespace;
        return self;
    }

    public function __construct($entityName = null, $entityClass = null)
    {
        if (!$entityName) {
            $managerName = static::getClassBasic();
            $entityName = preg_replace('/Manager$/', '', $managerName);
        }

        $entityTableClass = static::$dbTableNamespace . "\\" . $entityName . 'Table';
        $entityClass = $entityClass ?: static::$entityNamespace . "\\" . $entityName;

        if (!class_exists($entityClass)) {
            throw new Exception("Could not find a matching entity [{$entityClass}] for this class.");
        }

        if (!class_exists($entityTableClass)) {
            $entityTableClass = new EntityTable($entityClass);
        }

        //call parent
        parent::__construct($entityTableClass, $entityClass);
    }

}
