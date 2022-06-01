<?php

/**
 *
 * Description of JsonSerializaleConfigClass
 * Stores configuration for a class that uses the JsonSerializableTrait
 * This is because configurations have to be set at per class level.
 *
 * @author JosephT
 */
class JsonSerializableObjectConfig extends IdeoObject
{

    const PROP_IGNORE_FIELD = 'ignoreFields';
    const PROP_FILTER_MAP = 'filterMap';

    private $config = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function setConfig($name, $value)
    {
        $this->config[$name] = $value;
        return $this;
    }

    public function getConfig($name, $default = null)
    {
        return array_key_exists($name, $this->config) ? $this->config[$name] : $default;
    }

    public function __get($name)
    {
        return $this->getConfig($name);
    }

    public function __set($name, $value)
    {
        $this->setConfig($name, $value);
    }

}
