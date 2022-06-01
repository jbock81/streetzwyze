<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClassLoader
 *
 * @author intelWorX
 */
class ClassLoader
{

    protected static $includePaths = array(
        FRAMEWORK_DIR,
    );

    public static function autoload($className)
    {
        $isController = false;

        $matches = array();
        $className = ltrim($className, '\\');
        if (preg_match_all('@\\\@', $className, $matches) > 0) {
            //using name space
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        } else {
            if (preg_match('/Controller$/', $className)) {
                $file = "controllers/{$className}.php";
                $isController = true;
            } else {
                if (preg_match('/Table$/', $className)) {
                    $file = "models/DbTables/{$className}.php";
                } elseif (preg_match('/FormHandler$/', $className)) {
                    $file = "models/FormHandlers/{$className}.php";
                } else {
                    $file = "models/{$className}.php";
                }
            }
        }

        foreach (self::$includePaths as $path) {
            $classFile = $path . $file;
            if (file_exists($classFile)) {
                require_once $classFile;
                return;
            }
        }

        return null;
    }

    public static function addIncludePath($path)
    {
        self::$includePaths = array_merge(self::$includePaths, (array)$path);
    }

}

