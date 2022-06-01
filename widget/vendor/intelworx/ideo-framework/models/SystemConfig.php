<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author torilogbon
 */
final class SystemConfig extends ArrayIterator
{

    /**
     *
     * @var SystemConfig
     */
    protected static $instance;
    protected $config;
    protected static $fromWithin = false;
    protected static $appEnvironment = 'live';
    protected static $configDir = DEFAULT_CONFIG_DIR;

    public function __construct()
    {
        $this->parseConfig();
        //debug_opt($this->config, true);
        parent::__construct($this->config);
    }

    private function parseConfig()
    {
        if (!self::$fromWithin) {
            throw new BadMethodCallException("Call from within with " . __CLASS__ . "::getInstance()");
        }
        /**
         * @todo cache the config file
         */
        $configs = array();
        $setup = self::$configDir . 'config.ini';

        if (file_exists($setup)) {
            //$configs[$setup] = parse_ini_file($setup, true, INI_SCANNER_RAW);
            $configs[] = self::parseIniFile($setup);
        }

        $setup_env = self::$configDir . 'config.' . self::$appEnvironment . '.ini';
        if (file_exists($setup_env)) {
            $configs[] = self::parseIniFile($setup_env);
        }

        if (count($configs) < 1) {
            trigger_error(
                sprintf("No usable config file found for Environment: %s ", self::$appEnvironment), E_USER_ERROR);
        }

        $this->merge($configs);
        $this->config['system']['env'] = SYSTEM_ENV;

        if (count($this->config) < 1) {
            throw new SystemConfigException("Errors occured while parsing config files... \nConfig Array: " . print_r($configs, true));
        }
    }

    public static function parseIniFile($filePath)
    {
        $iniString = file_get_contents($filePath);
        $matches = [];
        if (preg_match_all('/\$\{([^:[:cntrl:]]+)(:([^:[:cntrl:]]+)?)?\}/', $iniString, $matches)) {
            foreach ($matches[0] as $idx => $literal) {
                $envVar = $matches[1][$idx];
                $defValue = $matches[3][$idx];
                $replacement = ($envValue = getenv($envVar)) !== false ? $envValue : $defValue;
                $iniString = str_replace($literal, $replacement, $iniString);
            }
        }
        return parse_ini_string($iniString, true);
    }

    private function merge(array $configs)
    {
        $this->config = array();
        //perform merging
        foreach ($configs as $config) {
            $this->config = array_replace_recursive($this->config, $config);
        }
    }

    public static function init($env = ENVIRONMENT_DEV, $configDir = DEFAULT_CONFIG_DIR)
    {
        self::$appEnvironment = $env;

        if ($configDir) {
            self::$configDir = $configDir;
        }
    }

    /**
     *
     * @return SystemConfig
     */
    public static function getInstance($reinit = false)
    {
        if (!self::$instance || $reinit) {
            self::$fromWithin = true;
            self::$instance = new static();
            self::$fromWithin = false;
        }
        return self::$instance;
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     *
     * Override a section variable in System Config at runtime
     * @param string $section the section under which the variable exists
     * @param string $variableName the name of the variable to override
     * @param mixed $variableValue the new value of the variable
     * @return \SystemConfig for fluent interface.
     */
    public function overrideConfig($section, $variableName, $variableValue)
    {
        $sectionVals = (array)$this->offsetGet($section);
        $sectionVals[$variableName] = $variableValue;
        $this->offsetSet($section, $sectionVals);
        return $this;
    }

}

class SystemConfigException extends Exception
{

}
