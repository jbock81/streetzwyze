<?php

/**
 * Description of DbConnectionFactory
 *
 * @author JosephT
 */
class DbConnectionFactory extends IdeoObject
{

    /**
     *
     * @var DbConnectionFactory
     */
    protected static $current;
    protected $_log_queries = false;
    protected $_count = 0;
    protected $inTransaction = false;
    protected $throwExcpetions = false;

    const DEFAULT_CHARSET = "UTF8";

    /**
     *
     * @var mysqli
     *
     */
    protected $_current_connection;

    /**
     *
     * @var array
     */
    protected $db_config;
    protected $timezone;
    protected $charset;
    protected $transactionCommitCallbacks = array();
    protected $transactionRollbackCallbacks = array();

    public function __construct($config)
    {
        if (!is_array($config) && func_num_args() > 1) {
            $this->db_config = array();
            $this->db_config['host'] = func_get_arg(0);
            $this->db_config['user'] = func_get_arg(1);
            $this->db_config['password'] = func_get_arg(2);
            $this->db_config['database'] = func_get_arg(3);
        } else {
            $this->db_config = (array)$config;
        }

        $this->charset = isset($this->db_config['charset']) ? $this->db_config['charset'] : self::DEFAULT_CHARSET;
        $this->timezone = isset($this->db_config['timezone']) ? $this->db_config['timezone'] : "";

        self::$current = $this;
    }

    /**
     *
     * @param string $q
     * @return mysqli_result
     */
    public function query($q)
    {
        $started = microtime(true);
        $db = $this->getCurrentConnection();
        $result = $db->query($q);

        $time_span = microtime(true) - $started;

        if ($db->error) {
            SystemLogger::error("\nQuery : $q", "Error: ", $db->error);
        }
        if ($this->throwExcpetions && !$result && $db->error) {
            throw new Exception("Mysql Error: " . $db->error . "(Error No: {$db->errno})\nQuery: {$q}");
        }

        if ($this->_log_queries) {
            $report = "Query Count: " . (++$this->_count);
            $report .= "\n\t\tTime Used: {$time_span}";
            $report .= "\n\t\tQuery: " . $q;
            SystemLogger::info("[QUERY]", $report);
        }

        return $result;
    }

    public function logQueries($bool = true)
    {
        $this->_log_queries = $bool;
    }

    public function startTransaction()
    {
        $this->getCurrentConnection()->autocommit(false);
        $this->inTransaction = true;
        return $this;
    }

    public function doInTransactionOrRun($callback, array $params = array())
    {
        if (!$this->isInTransaction()) {
            return $this->doInTransaction($callback, $params);
        } else {
            return call_user_func_array($callback, $params);
        }
    }

    /**
     *
     * @param callable $callback
     * @param array $params
     * @return boolean
     * @throws RuntimeException
     */
    public function doInTransaction($callback, array $params = array())
    {
        if (!is_callable($callback)) {
            throw new RuntimeException("Call back specified cannot be executed.");
        }

        if ($this->isInTransaction()) {
            SystemLogger::warn("System in transaction already!");
            return false;
        }

        $this->startTransaction();
        if (($result = call_user_func_array($callback, $params))) {
            $this->commit();
        } else {
            $this->rollback();
        }

        return $result;
    }

    public function commit()
    {
        $db = $this->getCurrentConnection();
        $db->commit();
        $db->autocommit(true);
        $this->_runCallbacks($this->transactionCommitCallbacks);
        $this->inTransaction = false;
        return true;
    }

    public function rollback()
    {
        $db = $this->getCurrentConnection();
        $rollback = $db->rollback();
        $db->autocommit(true);
        $this->_runCallbacks($this->transactionRollbackCallbacks);
        $this->inTransaction = false;
        return $rollback;
    }

    public function isInTransaction()
    {
        return $this->inTransaction;
    }

    public function setThrowExceptions($bool = true)
    {
        $this->throwExcpetions = $bool;
        return $this;
    }

    public function setGlobal()
    {
        $GLOBALS['db'] = $this;
        DbTable::setDb($this);
        return $this;
    }

    public function isThrowExcpetions()
    {
        return $this->throwExcpetions;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->_current_connection, $name], $arguments);
    }

    public function __get($name)
    {
        if ($this->_current_connection) {
            return $this->_current_connection->$name;
        } else {
            return $this->getCurrentConnection()->$name;
        }
    }

    public function __set($name, $value)
    {
        if ($this->_current_connection) {
            $this->_current_connection->$name = $value;
        } else {
            $this->getCurrentConnection()->$name = $value;
        }
    }

    /**
     * @return mysqli the current connection being used.
     */
    public function getCurrentConnection()
    {
        $connect = false;
        if ($this->_current_connection) {
            if (!$this->_current_connection->ping()) {
                if ($this->isInTransaction()) {
                    SystemLogger::warn("Connection was lost while system was in a transaction");
                }
                $connect = true;
                @$this->_current_connection->close();
            }
        } else {
            $connect = true;
        }

        if ($connect) {
            //echo "connecting...<br>"
            $this->_current_connection = new mysqli($this->db_config['host'], $this->db_config['user'], $this->db_config['password'], '', $this->db_config['port'] ?: 3306);
            if (!$this->_current_connection->connect_error) {
                if (!$this->_current_connection->select_db($this->db_config['database'])) {
                    throw new Exception("Database connection could not be initialised: {$this->_current_connection->error}");
                    //exit(-1);
                }
            } else {
                throw new Exception("Database connection could not be initialised: {$this->_current_connection->connect_error}", E_USER_ERROR);
            }

            $this->_current_connection->set_charset($this->charset);
            if ($this->timezone) {
                $tz = $this->_current_connection->real_escape_string($this->timezone);
                $offset = timezone_offset_get(new DateTimeZone($tz), new DateTime());
                $tz_offset = sprintf("%s%02d:%02d", ($offset >= 0) ? '+' : '-', abs($offset / 3600), abs($offset % 3600));
                $this->_current_connection->query("SET time_zone = '{$tz_offset}'");
            }
        }


        return $this->_current_connection;
    }

    public function setDbConfig(array $config)
    {
        $this->db_config = $config;
        return $this;
    }

    public function getDbConfig()
    {
        return $this->db_config;
    }

    protected function _addCallbacks(&$callbackList, $callback, $params = array())
    {
        $callbackList[] = array(
            'callback' => $callback,
            'params' => $params,
        );

        return $this;
    }

    public function addCommitCallback($callback, $params = array())
    {
        return $this->_addCallbacks($this->transactionCommitCallbacks, $callback, $params);
    }

    public function addRollbackCallback($callback, $params = array())
    {
        return $this->_addCallbacks($this->transactionRollbackCallbacks, $callback, $params);
    }

    protected function _runCallbacks(&$list, $clear = true)
    {
        $success = 0;

        foreach ($list as $callback) {
            if (is_callable($callback['callback'])) {
                if (call_user_func_array($callback['callback'], $callback['params'])) {
                    $success++;
                }
            }
        }

        if ($clear) {
            $this->clearCommitCallbacks()
                ->clearRollbackCallbacks();
        }
        return $success;
    }

    protected function clearCommitCallbacks()
    {
        $this->transactionCommitCallbacks = array();
        return $this;
    }

    protected function clearRollbackCallbacks()
    {
        $this->transactionRollbackCallbacks = array();
        return $this;
    }

    public static function current()
    {
        return self::$current;
    }

}

function mysqli_result_fetch_all($result)
{
    $all = array();
    if (!$result) {
        throw new Exception('No result was found, check last error');
    }

    while ($row = $result->fetch_assoc()) {
        $all[] = $row;
    }
    return $all;
}
