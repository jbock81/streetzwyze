<?php

class Session extends IdeoObject
{

    protected static $_instance;
    private $namespace;
    private $_session_array;

    const DEFAULT_NAMESPACE = 'DEFAULT';

    private function __construct()
    {
        if (!session_id()) {
            session_start();
        }
        $this->_session_array = &$_SESSION;
    }

    /**
     *
     * @return Session;
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Session;
        }
        return self::$_instance;
    }

    /**
     * @param String $namespace
     * @return Session , fluent interface;
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function set($name, $value, $namespace = null)
    {
        $namespace = $namespace ?: $this->namespace;
        if ($namespace) {
            $this->_session_array[$namespace][$name] = $value;
            $this->namespace = null;
        } else {
            $this->_session_array[self::DEFAULT_NAMESPACE][$name] = $value;
        }
        return $this;
    }

    public function &get($name, $namespace = null)
    {
        $namespace = $namespace ?: $this->namespace;
        $value = $namespace ? $this->_session_array[$namespace][$name] :
            $this->_session_array[self::DEFAULT_NAMESPACE][$name];
        $this->namespace = '';
        return $value;
    }

    public function &getNamespaceData($namespace = null)
    {
        $namespace = $namespace ?: $this->namespace;
        if ($namespace) {
            $value = &$this->_session_array[$namespace];
        } else {
            $value = &$this->_session_array[self::DEFAULT_NAMESPACE];
        }

        $this->namespace = '';
        return $value;
    }

    public function __set($name, $value)
    {
        if ($this->namespace) {
            $this->_session_array[$this->namespace][$name] = $value;
            $this->namespace = '';
        } else {
            $this->_session_array[self::DEFAULT_NAMESPACE][$name] = $value;
        }
    }

    public function __get($name)
    {
        $namespace = $this->namespace;
        $this->namespace = false;
        return $namespace ? $this->_session_array[$namespace][$name] :
            $this->_session_array[self::DEFAULT_NAMESPACE][$name];
    }

    public function __isset($name)
    {
        return isset($this->_session_array[$name]);
    }

    public function __unset($name)
    {
        unset($this->_session_array[$name]);
    }

    public function clearNameSpace($namespace = '')
    {
        if (!$namespace) {
            $namespace = $this->namespace;
        }
        unset($this->_session_array[$namespace]);
    }

    public function unsetFromNamespace($name, $ns = self::DEFAULT_NAMESPACE)
    {
        $namespace = $ns ?: $this->namespace;
        $this->namespace = '';
        unset($this->_session_array[$namespace][$name]);
    }

}
