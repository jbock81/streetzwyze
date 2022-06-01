<?php

if (version_compare(PHP_VERSION, '5.4.0') < 0) {
    die("Deployment environment requires PHP 5.4 and above...");
}

/* This is needed if you wish to map host to environments */
$hostConfigMap = array(
    '/^localhost$/' => "dev",
);

$__envConfig = false;

if (isset($_SERVER['HTTP_HOST'])) {
    $__host = $_SERVER['HTTP_HOST'];
    foreach ($hostConfigMap as $pattern => $appEnv) {
        if (preg_match($pattern, $__host)) {
            $__envConfig = $appEnv;
            break;
        }
    }
}

if ($__envConfig === false) {
    $__envConfig = getenv('DEFAULT_CONFIG') ?: 'live';
}

putenv("CONFIG={$__envConfig}");
$_ENV['CONFIG'] = $__envConfig;

/**
 * Root of the application
 */
define('APP_ROOT', __DIR__ . DIRECTORY_SEPARATOR);

/**
 * Directory ao application folder, where all the magic happens
 */
define('APP_DIR', APP_ROOT . 'application' . DIRECTORY_SEPARATOR);
/**
 * Path to Libraries
 */
define('LIB_DIR', APP_DIR . 'library' . DIRECTORY_SEPARATOR);

//You can also load environmental valriables from .environment file in your application root.
__init_env();

function __init_env()
{
    if (file_exists(__DIR__ . '/.environment')) {
        $envLines = file(__DIR__ . '/.environment');
        foreach ($envLines as $envLine) {
            if ($envLine = trim($envLine)) {
                list($var, $value) = explode('=', $envLine);
                if ($var && $value) {
                    putenv("{$var}={$value}");
                    $_ENV[$var] = $value;
                }
            }
        }
    }
}

//phpinfo(); 
//exit;