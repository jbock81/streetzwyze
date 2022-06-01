<?php

/**
 * Description of class
 *
 * @author kunle
 */
class Registry
{

    protected static $_cache = array();

    public static function set($key, &$item)
    {
        self::$_cache[$key] = &$item;
    }

    public static function &get($key)
    {
        return self::$_cache[$key];
    }

    public static function &getArrayElement($arrKey, $elementKey)
    {
        return is_array(self::$_cache[$arrKey]) ? self::$_cache[$arrKey][$elementKey] : null;
    }

    public static function setArrayElement($arrKey, $elementKey)
    {
        is_array(self::$_cache[$arrKey]) ? self::$_cache[$arrKey][$elementKey] : null;
    }

    public static function is_set($key)
    {
        return array_key_exists($key, self::$_cache);
    }

}