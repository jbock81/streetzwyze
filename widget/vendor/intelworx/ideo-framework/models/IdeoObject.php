<?php

/**
 * Description of IdeoObject
 *
 * @author intelWorX
 */
abstract class IdeoObject
{

    private static $constants = array();
    protected $synthesizeFields = false;

    public static function getClass()
    {
        return get_called_class();
    }

    /**
     * Obtains an object class name without namespaces
     */
    public static function getClassBasic()
    {
        $classname = get_called_class();
        $matches = array();
        if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
            $classname = $matches[1];
        }

        return $classname;
    }

    public static function listConstants($namesOnly = false)
    {
        $class = get_called_class();
        if (!array_key_exists($class, self::$constants)) {
            $reflection = new ReflectionClass($class);
            self::$constants[$class] = $reflection->getConstants();
        }

        return $namesOnly ? array_keys(self::$constants[$class]) : self::$constants[$class];
    }

    public function setSynthesizeFields($bool = true)
    {
        $this->synthesizeFields = $bool;
    }

    public function __call($name, $arguments)
    {
        $matches = array();
        if ($this->synthesizeFields && preg_match('/^(set|get)([A-Z][0-9A-Za-z_]+)$/i', $name, $matches)) {
            $varName1 = lcfirst($matches[2]);
            $varName = null;
            $classVars = get_object_vars($this);
            if (count($classVars) > 1) {
                $varName2 = GeneralUtils::camelCaseToDelimited($varName1);
                if (!array_key_exists($varName1, $classVars)) {
                    if (in_array($varName2, $classVars)) {
                        $varName = $varName2;
                    }
                } else {
                    $varName = $varName1;
                }
            }

            if ($varName) {
                $callType = strtolower($matches[1]);
                if ($callType == 'set') {
                    $this->$varName = $arguments[0];
                    return $this;
                } else {
                    return $this->$varName;
                }
            }
        }

        $class = static::getClass();
        trigger_error("Call to undefined class method {$class}::{$name}()", E_USER_ERROR);
    }

    public function __hash()
    {
        return spl_object_hash($this);
    }

    public function __toString()
    {
        return print_r($this, true);
    }

    /**
     * Checks of this object is an instance of this class.
     * @param object $object
     * @return bool true if object is an instance of object
     */
    public static function aClassOf($object)
    {
        return is_a($object, static::getClass());
    }

    /**
     *
     * @param type $className
     * @return bool true if this class is an ancesstor of the specified class name
     */
    public static function isAncesstorOf($className)
    {
        return in_array(static::getClass(), class_parents($className));
    }

    /**
     * Checks if a class uses a particular trait.
     * @param string $trait fully qualified name of trait.
     * @return boolean
     */
    public static function hasTrait($trait)
    {
        return array_key_exists($trait, class_uses(static::getClass()));
    }


}
