<?php

class AppConfig extends DbTable
{

    //private $configs = array();
    private $data;
    private static $instance;

    public function __construct()
    {
        if (!self::$instance) {
            parent::__construct('appconfig');
            $this->fetchAsObject(false);
            $rows = $this->fetch();
            foreach ($rows as $config) {
                //$this->configs[$config['configName']] = $config['configValue'];
                $this->data[$config['configName']] = $config;
            }
            $this->fetchAsObject();
        } else {
            throw new Exception('An instance of AppConifg is already defined, please use AppConfig::getInstance()');
        }
    }

    public function hasConfig($name)
    {
        return array_key_exists($name, $this->data);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name]['configValue'];
        } else {
            return null;
        }
    }

    public function updateConfig($name, $value = '')
    {
        $name = DbTable::escapeString($name);
        $result = $this->update(array('configValue' => $value), "configName =  '{$name}'", 1);
        if ($result) {
            $this->data[$name]['configValue'] = $value;
        }
        return $result;
    }

    public function setConfig($name, $value = '', $description = '', $isNumeric = false)
    {
        $row = array(
            'configValue' => $value,
            "configName" => $name,
            "configDescription" => $description,
            'configIsNumeric' => $isNumeric
        );

        $result = $this->insert($row, true);
        if ($result) {
            $this->data[$name]['configValue'] = $value;
        }
        return $result;
    }

    //public function
    /**
     *
     * @return AppConfig
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new AppConfig;
        }

        return self::$instance;
    }

    public static function isInitialized()
    {
        return self::$instance !== null;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function setRunTimeConfig($configName, $value = null)
    {
        $this->data[$configName]['configValue'] = $value;
        return $this;
    }

    //public 
}
