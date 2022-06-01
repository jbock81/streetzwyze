<?php

/**
 * Description of EntityRelationshipSpec
 *
 * @author JosephT
 */
abstract class EntityRelationshipSpec extends IdeoObject
{

    /**
     *
     * @var EntityManager
     */
    protected $managerClass;
    protected $fkField;
    protected $referencedField;

    /**
     * This variable holds several configuration properties of a relationship
     * Some config include:
     *  order: order by which relashionship should be sorted
     * @var array
     */
    protected $config = array();

    public function __construct($managerClass, $fkField, $referencedField = null, array $config = array())
    {
        $this->setManagerClass($managerClass);
        $this->fkField = $fkField;
        $this->referencedField = $referencedField ?: $this->getManagerClass()->getPrimaryKey()[0];
        $this->config = $config;
    }

    /**
     *
     * @return EntityManager
     */
    public function getManagerClass()
    {
        return $this->managerClass;
    }

    final public function setManagerClass($managerClass)
    {
        $this->managerClass = self::extractManager($managerClass);
        return $this;
    }

    /**
     *
     * @param mixed $manager an EntityManager object or class or a ModelEntity class
     * @return EntityManager
     * @throws RuntimeException
     */
    final public static function extractManager($manager)
    {
        //an instance of entity manager?
        if (is_object($manager) && (EntityManager::aClassOf($manager))) {
            return $manager;
        }

        //an existent class?
        if (is_string($manager) && class_exists($manager)) {
            //an entity manager class?
            if (EntityManager::isAncesstorOf($manager)) {
                return call_user_func([$manager, 'instance']);
            }

            //a ModelEntity class?
            if (method_exists($manager, 'manager')) {
                if (($managerObj = call_user_func([$manager, 'manager'])) && EntityManager::aClassOf($managerObj)) {
                    return $managerObj;
                }
            }
        }

        throw new RuntimeException("The specified manager is not valid");
    }

    public function getFkField()
    {
        return $this->fkField;
    }

    public function getReferencedField()
    {
        return $this->referencedField;
    }

    public function setFkField($fkField)
    {
        $this->fkField = $fkField;
        return $this;
    }

    public function setReferencedField($referencedField)
    {
        $this->referencedField = $referencedField;
        return $this;
    }

    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
        return $this;
    }

    public function getConfig($key, $default = null)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

}
