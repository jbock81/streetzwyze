<?php

/**
 * Description of ModuleLoader
 *
 * @author intelWorX
 */
class ModuleManager extends IdeoObject
{

    /**
     *
     * @var ModuleManager
     */
    private static $instance;

    const CONFIG_MODULE_DIR = 'modules.dir';

    /**
     *
     * @var ModuleConfig[]
     */
    protected $moduleList = array();

    /**
     *
     * @var array
     */
    protected $config = array();

    private function __construct()
    {

    }

    /**
     * @return ModuleManager instance
     */
    static public function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     *
     * @param string $configName
     * @param mixed $configValue
     * @return \ModuleManager
     */
    public function configure($configName, $configValue)
    {
        $this->config[$configName] = $configValue;
        return $this;
    }

    public function loadModules()
    {
        if (isset($this->config[self::CONFIG_MODULE_DIR])) {
            foreach ($this->config[self::CONFIG_MODULE_DIR] as $modulesDir) {
                $modulesDir = realpath($modulesDir) . DIRECTORY_SEPARATOR;
                $modules = glob($modulesDir . '*', GLOB_ONLYDIR | GLOB_MARK);
                foreach ($modules as $moduleDir) {
                    if (is_readable($modulesDir)) {
                        $configFile = $moduleDir . 'config.php';
                        $moduleName = basename($moduleDir);
                        $defaultConfig = $this->getDefaultModuleConfig($moduleName);
                        $config = file_exists($configFile) ? array_merge($defaultConfig, include($configFile)) : $defaultConfig;
                        $this->registerModule($moduleName, $config, $moduleDir);
                    }
                }
            }
        }

        return $this;
    }

    public function getDefaultModuleConfig($moduleName)
    {
        return array(
            'path' => $moduleName,
            'controllerNamespace' => "\\{$moduleName}\\controllers",
            'bootstrapClass' => "\\{$moduleName}\\Bootstrap",
        );
    }

    /**
     *
     * @param string $moduleName
     * @param array $config
     * @param string $moduleDir path to the modulke
     * @return \ModuleManager
     */
    public function registerModule($moduleName, array $config, $moduleDir = null)
    {
        $this->moduleList[$moduleName] = new ModuleConfig($moduleName, $config, $moduleDir);
        return $this;
    }

    /**
     *
     * @param string $name
     * @return ModuleConfig|null null if the module does not exist, ModuleConfig object for that module if it does.
     *
     */
    public function getModule($name)
    {
        return array_key_exists($name, $this->moduleList) ? $this->moduleList[$name] : null;
    }

    /**
     *
     * @param type $path
     * @return null|ModuleConfig
     */
    public function matchingModule($path)
    {
        foreach ($this->moduleList as $module) {
            if ($module->get('path') == $path) {
                return $module;
            }
        }
        return null;
    }

}
