<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ApplicationFrontEnd extends IdeoObject
{

    /**
     *
     * @var ClientHttpRequest
     */
    protected static $request;

    /**
     *
     * @var Application
     *
     */
    protected static $currentApplication;

    /**
     *
     * @param type $params
     * @return ClientHttpRequest
     */
    public static function getRequest()
    {
        return self::$request;
    }

    public static function registerRequest(ClientHttpRequest $request)
    {
        self::$request = $request;
    }

    /**
     *
     * @return Application
     */
    public static function getApplication()
    {
        return self::$currentApplication;
    }

    public static function registerApplication(Application $app)
    {
        self::$currentApplication = $app;
    }

    public static function getModuleDir($moduleName)
    {
        $moduleIn = self::getApplication()->getOptions('modules.dir');
        if ($moduleName) {
            $moduleIn .= $moduleName . DIRECTORY_SEPARATOR;
        }
        return $moduleIn;
    }

}