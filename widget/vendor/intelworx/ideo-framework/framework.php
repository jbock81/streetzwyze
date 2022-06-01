<?php

if (!defined('APP_DIR') || !defined('APP_ROOT')) {
    die("Bootstrapping failed!, App directory is not defined, define APP_DIR (application directory) and APP_ROOT (Root folder of your project)");
}

define('ENVIRONMENT_LIVE', 'live');
define('ENVIRONMENT_DEV', 'dev');
define('ENVIRONMENT_TEST', 'test');

define('SYSTEM_ENV', getenv('CONFIG') ?: ENVIRONMENT_LIVE);

define('DEFAULT_CONFIG_DIR', APP_DIR . 'config' . DIRECTORY_SEPARATOR);
define("FRAMEWORK_DIR", realpath(__DIR__) . DIRECTORY_SEPARATOR);

define('DEFAULT_FILTER_STRING_FIELDS', FILTER_SANITIZE_STRIPPED | FILTER_SANITIZE_STRING);

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);

require_once "functions.php";
require_once "models/Autoloader.php";
require_once "models/ClassLoader.php";

$app_main_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

if (array_key_exists('HTTP_HOST', $_SERVER) && $_SERVER['HTTP_HOST']) {
    $scheme = isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"]) ? "https://" : "http://";
    define('BASE_URL', $scheme . $_SERVER['HTTP_HOST'] . $app_main_dir . '/');
}

Autoloader::init();
ClassLoader::addIncludePath(APP_DIR);
Autoloader::register("ClassLoader::autoload");
