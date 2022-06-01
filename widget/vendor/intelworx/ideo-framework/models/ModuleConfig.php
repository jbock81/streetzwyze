<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModuleConfig
 *
 * @author intelWorX
 */
class ModuleConfig extends IdeoObject
{

    //put your code here

    protected $moduleName;
    protected $config;
    protected $moduleDir = null;
    protected $isDefault = false;
    protected $bootstrapped = false;

    public function __construct($moduleName, $config = array(), $moduleDir = null)
    {
        $this->moduleName = $moduleName;
        $this->config = $config;
        $this->moduleDir = $moduleDir;
    }

    public function get($varName, $default = null)
    {
        return array_key_exists($varName, $this->config) ? $this->config[$varName] : $default;
    }

    /**
     *
     * @param string $varName
     * @param mixed $value
     * @return \ModuleConfig
     */
    public function set($varName, $value)
    {
        $this->config[$varName] = $value;
        return $this;
    }

    public function getModuleName()
    {
        return $this->moduleName;
    }

    public function getPath()
    {
        return $this->get('path', $this->moduleName);
    }

    /**
     * Set if this is the config for the default module.
     * @param bool $bool
     * @return \ModuleConfig
     */
    public function setIsDefault($bool = true)
    {
        $this->isDefault = $bool;
        return $this;
    }

    /**
     *
     * @return bool true if this is the default module, false otherwise
     */
    public function isDefault()
    {
        return $this->isDefault;
    }

    public function getControllerNamespace()
    {
        if (isset($this->config['controllersNamespace'])) {
            return $this->config['controllersNamespace'];
        } elseif ($this->moduleName == 'default') {
            return '';
        } else {
            return "\\{$this->moduleName}\\controllers";
        }
    }

    public function bootstrap(Application $application, $force = false)
    {
        //call bootstrap class.
        if ($force || !$this->bootstrapped) {
            $defaultClass = "\\" . $this->getModuleName() . "\\Bootstrap";
            $bootstrap = $this->get('bootstrapClass', $defaultClass);
            if (class_exists($bootstrap)) {
                $moduleBootstrap = new $bootstrap($application);
                if ($moduleBootstrap instanceof ApplicationBootstrap) {
                    $moduleBootstrap->init();
                } else {
                    throw new ApplicationException("Bootstrap is defined but not instance of " . ApplicationBootstrap::getClass());
                }
            }

            $this->bootstrapped = true;
        }
    }

    public function getModuleDir()
    {
        return $this->moduleDir;
    }

    public function setModuleDir($moduleDir)
    {
        $this->moduleDir = $moduleDir;
        return $this;
    }

    public function __toString()
    {
        return $this->getPath();
    }

}
