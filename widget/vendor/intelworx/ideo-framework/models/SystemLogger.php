<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SystemLogger
 *
 * @author intelWorX
 */
class SystemLogger
{

    //put your code here

    const LOG_NONE = 0;
    const LOG_DEBUG = 10;
    const LOG_INFO = 15;
    const LOG_WARN = 20;
    const LOG_ERROR = 30;
    const LOG_FATAL = 40;

    protected static $fileSizeLimit = 1048576;

    protected static $logLevels = array(
        self::LOG_WARN => "WARN",
        self::LOG_DEBUG => "DEBUG",
        self::LOG_INFO => "INFO",
        self::LOG_ERROR => "ERROR",
        self::LOG_FATAL => "FATAL",
    );
    protected static $logLevel = self::LOG_INFO;
    protected static $logs = array();
    protected static $logger_handle;
    protected static $start_time;
    protected static $verbose = false;

    public static function setLogFile($file = null)
    {
        if (is_resource($file)) {
            self::$logger_handle = $file;
        } else {
            $file = $file ? $file : APP_DIR . 'system.log';
            if (file_exists($file) && filesize($file) > self::$fileSizeLimit) {
                $newFileName = $file . "." . microtime(true);
                rename($file, $newFileName);
            }

            self::$logger_handle = fopen($file, "a+");
        }
    }

    public static function getFileSizeLimit()
    {
        return self::$fileSizeLimit;
    }

    public static function setFileSizeLimit($fileSizeLimit)
    {
        self::$fileSizeLimit = $fileSizeLimit;
    }

    public static function setLogLevel($level = self::LOG_WARN)
    {
        self::$logLevel = $level;
    }

    public static function addLog($data, $level = self::LOG_DEBUG)
    {
        if (is_array($data)) {
            if (empty($data)) {
                self::setLogLevel($level);
                return;
            } else {
                $message = "";
                foreach ($data as $msg) {
                    if (!is_scalar($msg)) {
                        $msg = print_r($msg, true);
                    }
                    $message .= $msg . " ";
                }
            }
        }


        if (self::$logLevel && $level >= self::$logLevel) {
            if (!is_resource(self::$logger_handle)) {
                self::setLogFile();
            }
            $level = self::$logLevels[$level];
            $time = date('Y-m-d H:i:s');

            $messageLine = "[{$time}]\t[{$level}]\t" . $message;
            if (self::$verbose) {
                fwrite(self::$logger_handle, $messageLine . PHP_EOL);
            } else {
                self::$logs[] = "[{$time}]\t[{$level}]\t" . $message;
            }
        }
    }

    public static function debug()
    {
        self::addLog(func_get_args(), self::LOG_DEBUG);
    }

    public static function info()
    {
        self::addLog(func_get_args(), self::LOG_INFO);
    }

    public static function warn()
    {
        self::addLog(func_get_args(), self::LOG_WARN);
    }

    public static function error()
    {
        self::addLog(func_get_args(), self::LOG_ERROR);
    }

    public static function fatal()
    {
        self::addLog(func_get_args(), self::LOG_FATAL);
    }

    public static function flush()
    {
        if (count(self::$logs)) {
            $message = "Logs for " . $_SERVER['REQUEST_URI'] . PHP_EOL;
            $message .= join(PHP_EOL, self::$logs) . PHP_EOL;
            $message .= "Finished at " . date("Y-m-d H:i:s") . PHP_EOL;
            fwrite(self::$logger_handle, $message);
        }
        @fclose(self::$logger_handle);
    }

    public static function setVerbose($bool = true)
    {
        self::$verbose = $bool;
    }

}
