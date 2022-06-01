<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApplicationBootstrap
 *
 * @author intelWorX
 */
abstract class ApplicationBootstrap extends IdeoObject
{

    /**
     *
     * @var Application
     */
    protected $application;

    public final function __construct(Application $application)
    {
        $this->application = $application;
    }

    //put your code here
    public function init()
    {
        $methods = get_class_methods($this);
        foreach ($methods as $methodName) {
            if (preg_match('/^_init[a-zA-Z0-9_]+/', $methodName)) {
                $this->$methodName();
            }
        }
    }

}

