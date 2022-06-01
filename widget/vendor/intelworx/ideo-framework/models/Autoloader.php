<?php

/**
 * Class to manage autoloaders
 * Can register more autoloaders and unregister autoloader
 *
 *
 */
class Autoloader
{

    /**
     * High priority for autoloader
     */
    const PRIORITY_HIGH = 3;

    /**
     * Default/Normal priority for autoloader
     */
    const PRIORITY_NORMAL = 2;


    /**
     * The array of registered autoloaders
     * @var array
     */
    protected static $autoloaders = array(
        self::PRIORITY_HIGH => array(),
        self::PRIORITY_NORMAL => array(),
    );

    /**
     * Initializas the autoloader. Unsets any other designated autoloaders
     * Should be called before attempting to set any autoloader
     */
    public static function init()
    {
        // Unset any preset autoloaders
//        $currentFuncs = spl_autoload_functions();
//        if (!empty($currentFuncs)) {
//            foreach ($currentFuncs as $aFunction) {
//                spl_autoload_unregister($aFunction);
//            }
//        }

        spl_autoload_register("Autoloader::load", FALSE, TRUE);
    }

    /**
     * Returns the registered autoloaders
     *
     * @return array
     */
    public static function getFunctions()
    {
        return self::$autoloaders;
    }

    /**
     * Attempts to load the class file for the $classname by iterating over the registered autoloader
     *
     * @param string $classname The name of the class to be loaded
     */
    public static function load($classname)
    {
        // Iterate over the high priority autoloaders first
        foreach (self::$autoloaders as $autoloaders) {
            if (empty($autoloaders)) {
                continue;
            }
            foreach ($autoloaders as $anAutoloader) {
                if (self::isClassLoaded($classname)) {
                    return;
                }
                if (is_callable($anAutoloader)) {
                    call_user_func($anAutoloader, $classname);
                }
            }
        }
    }

    /**
     * Registers an autoloader. If already previously registered, it overrides it
     *
     * @param string $function The name of the autoload function to be added
     * @param integer $priority The priority of the autoload function
     */
    public static function register($function, $priority = self::PRIORITY_NORMAL)
    {
        if (!array_key_exists($priority, self::$autoloaders)) {
            $priority = self::PRIORITY_NORMAL;
        }

        // Remove the function from the list of autoloader 
        self::unregister($function);
        array_push(self::$autoloaders[$priority], $function);
    }

    /**
     * Unregisters an existing autoloader
     *
     * @param string $function The function to be removed from the autoloaders list
     * @return boolean TRUE if removed, FALSE otherwise
     */
    public static function unregister($function)
    {

        foreach (self::$autoloaders as $autoloaders) {
            $pos = array_search($function, $autoloaders);
            if ($pos !== FALSE) {
                unset($autoloaders[$pos]);
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Checks if a class/interface has been loaded to avoid calling autoload
     *
     * @param string $classname The name of the class to check for
     * @return boolean TRUE if the class has been loaded
     */
    public static function isClassLoaded($classname)
    {
        return (class_exists($classname, FALSE) || interface_exists($classname, FALSE));
    }

    /**
     * Takes the appropriate action when the autoload fails
     *
     * @param string $classname The name of the class that was to be loaded
     */
    protected static function autoloadFailAction($classname, $isClassTest = false)
    {
        if (!$isClassTest) {
            trigger_error("Class {$classname} was not found in the available autoloaders", E_USER_NOTICE);
        }
    }
}