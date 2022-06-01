<?php

/**
 *
 * This is the heart of Ideo
 * Description of Application
 *
 * @author intelWorX
 */
require_once 'framework.php';

class Application
{

    /**
     *
     * @var SystemConfig
     */
    protected $sysConfig;

    /**
     *
     * @var array application options.
     */
    protected $options;

    /**
     *
     * @var DbConnectionFactory
     */
    protected $_db;

    /**
     * @var TemplateEngine
     */
    protected $templateEngine;

    /**
     *
     * @var AppConfig
     */
    protected $appConfig;

    /**
     *
     * @var ClientHttpRequest
     */
    protected $clientRequest;
    protected $isCli = false;

    /**
     *
     * @var Application
     */
    protected static $currentInstance;

    /**
     *
     * @param array $options list of application configurations:
     * config.dir => DEFAULT_CONFIG_DIR,
     *  templates.dir => APP_DIR . views/,
     *  templates.cache_dir => APP_DIR . views/cache/,
     *  modules.dir => APP_DIR . modules . DIRECTORY_SEPARATOR,
     *  modules.use_ns => true,
     *  session.start => true,
     *  session.callback => null,
     * @param bool $isCli if application is CLI application
     */
    public function __construct(array $options = array(), $isCli = false)
    {
        $this->options = array_merge(array(
            'config.dir' => DEFAULT_CONFIG_DIR,
            'templates.dir' => APP_DIR . 'views/',
            'templates.cache_dir' => APP_DIR . 'views/_cache/',
            'modules.dir' => array(
                APP_DIR . 'modules' . DIRECTORY_SEPARATOR
            ),
            'modules.use_ns' => true,
            'session.start' => true,
            'session.callback' => null,
        ), (array)$options);

        $this->isCli = $isCli || strtolower(php_sapi_name()) === 'cli';

        ApplicationFrontEnd::registerApplication($this);
        SystemConfig::init(SYSTEM_ENV, $options['config.dir']);
        $this->sysConfig = SystemConfig::getInstance();
        ini_set('display_errors', $this->sysConfig->system['display_errors']);
        date_default_timezone_set($this->sysConfig->system['timezone']);

        if (!is_array($this->options['modules.dir'])) {
            $this->options['modules.dir'] = (array)$this->options['modules.dir'];
        }

        ClassLoader::addIncludePath($this->options['modules.dir']);
        //cli
        if ($this->isCli) {
            $_SERVER['HTTP_HOST'] = $this->sysConfig->system['root_domain'];
            $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
            if (!defined("BASE_URL")) {
                define("BASE_URL", $this->sysConfig->system['site_url'] ?: "http://{$this->sysConfig->system['root_domain']}/");
            }
        }

        self::$currentInstance = $this;

        //initialize modules
        $moduleManager = ModuleManager::instance()
            ->configure(ModuleManager::CONFIG_MODULE_DIR, $this->options['modules.dir'])
            ->loadModules();

        if (!$moduleManager->getModule('default')) {
            $defaultConfig = array(
                'path' => '',
            );
            $moduleManager->registerModule('default', $defaultConfig, APP_DIR);
        }

        //compatibility
        if (!empty($this->sysConfig->modules)) {
            foreach ($this->sysConfig->modules as $moduleName => $modulePath) {
                $module = $moduleManager->getModule($moduleName);
                /* @var $module ModuleConfig */
                if ($module && !$module->getPath()) {
                    $module->set('path', $modulePath);
                }
            }
        }

        $this->init();
    }

    /**
     *
     * @return Application
     * @throws ApplicationException
     */
    public static function currentInstance()
    {
        if (!self::$currentInstance) {
            throw new ApplicationException("Application is yet to be initialized.");
        }

        return self::$currentInstance;
    }

    protected final function init()
    {
        $this->initLogging()
            ->initTemplateEngine()
            ->initRequest()
            ->initDb()
            ->initSession()
            ->initAppConfig()
            ->initViews();

        $this->templateEngine->assign('BASE_URL', BASE_URL);
        $this->templateEngine->assign('MAIN_BASE_URL', BASE_URL);
        $this->templateEngine->assign('appConfig', $this->appConfig);

        $this->bootstrap();
    }

    protected function bootstrap()
    {
        $bootstraps = array(
            'Bootstrap' => APP_DIR . 'Bootstrap.php', //default bootstrap file
        );

        foreach ($bootstraps as $bootstrapClass => $bootstrapFile) {
            if (file_exists($bootstrapFile)) {
                require_once $bootstrapFile;
                $bootStrapObj = new $bootstrapClass($this);
                /* @var  $bootStrapObj ApplicationBootstrap */
                $bootStrapObj->init();
            }
        }

        $this->clientRequest->getModule()->bootstrap($this);
    }

    protected function initTemplateEngine()
    {
        $this->templateEngine = TemplateEngine::getInstance();
        $this->templateEngine->setTemplateDir($this->options['templates.dir']);
        $this->templateEngine->setCompileDir($this->options['templates.cache_dir']);
        $this->templateEngine->addTemplateDir(__DIR__ . '/views');
        foreach ((array)$this->sysConfig->assets_url as $key => $url) {
            SmartyMiscFunctions::addAssetsUrl($key, $url);
        }
        SmartyMiscFunctions::registerPlugins();
        return $this;
    }

    protected function initLogging()
    {
        SystemLogger::setLogLevel($this->sysConfig->system['debug'] ? SystemLogger::LOG_DEBUG :
            SystemLogger::LOG_WARN);
        register_shutdown_function("SystemLogger::flush");
        return $this;
    }

    protected function initSession()
    {
        if ($this->options['session.start'] && !session_id()) {
            if ($this->options['session.callback'] && is_callable($this->options['session.callback'])) {
                call_user_func($this->options['session.callback'], $this);
            } else {
                session_start();
            }
        }
        return $this;
    }

    protected function initDb()
    {
        // Setup database connection
        $dbConfig = $this->sysConfig->db;
        if (!empty($dbConfig)) {
            $dbConfig['timezone'] = $this->sysConfig->system['timezone'];
            $this->_db = new DbConnectionFactory($dbConfig);
            $this->_db->logQueries((bool)$dbConfig['log_queries']);
            $this->_db->setThrowExceptions((bool)$dbConfig['exceptions']);
            $this->_db->setGlobal();
        }
        return $this;
    }

    protected function initAppConfig()
    {
        if ($this->_db && $this->_db->getCurrentConnection() && !$this->_db->getCurrentConnection()->connect_error && !$this->sysConfig->system['no_app_config']) {
            try {
                $this->appConfig = AppConfig::getInstance();
            } catch (Exception $e) {
                throw new ApplicationException($e->getMessage());
            }
        }
        return $this;
    }

    public function initRequest()
    {
        $app_main_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        $newUrl = str_replace('/', '\/', $app_main_dir);
        $pattern = '/' . $newUrl . '/';
        $_url = preg_replace($pattern, '', $_SERVER['REQUEST_URI'], 1);
        $_tmp = explode('?', $_url);
        $originalUrl = $_tmp[0];
        $Req = array();
        if (($_url = explode('/', $originalUrl))) {
            foreach ($_url as $tag) {
                if ($tag) {
                    $Req[] = $tag;
                }
            }
        }

        $urlPath = join("/", $Req);
        $this->clientRequest = new ClientHttpRequest($Req, $urlPath);
        $this->clientRequest->setResponseHeader('X-Powered-By', $this->appConfig->siteName);
        ApplicationFrontEnd::registerRequest($this->clientRequest);
        $this->clientRequest->setUseModuleNamespace($this->options['modules.use_ns']);
        return $this;
    }

    protected function initViews()
    {
        $currentTheme = $this->sysConfig->system['theme'];
        if ($currentTheme) {
            $this->templateEngine->initThemeFolder($currentTheme);
        }

        if ($this->clientRequest) {
            $directories = [];
            $moduleViewsDefault = realpath($this->options['templates.dir'])
                . DIRECTORY_SEPARATOR
                . $this->clientRequest->getModule()->getModuleName();

            //module views
            if (is_dir($moduleViewsDefault) && is_readable($moduleViewsDefault)) {
                $directories[] = $moduleViewsDefault;
            }

            //module views in theme
            $moduleViewsInTheme = realpath($this->options['templates.dir'])
                . DIRECTORY_SEPARATOR
                . $currentTheme
                . DIRECTORY_SEPARATOR
                . $this->clientRequest->getModule()->getModuleName();

            if (is_dir($moduleViewsInTheme) && is_readable($moduleViewsInTheme)) {
                $directories[] = $moduleViewsInTheme;
            }

            $viewsDir = $this->clientRequest
                ->getModule()
                ->get('viewsDirectory');

            if (!empty($viewsDir)) {
                $directories = array_merge((array)$viewsDir, $directories);
            }

            if (!empty($directories)) {
                $this->templateEngine->prependTemplateDir($directories);
            }
        }

        return $this;
    }

    public function run()
    {
        $this->clientRequest->run();
    }

    /**
     *
     * @return AppConfig
     */
    public function getAppConfig()
    {
        return $this->appConfig;
    }

    /**
     *
     * @return ClientHttpRequest
     */
    public function getClientRequest()
    {
        return $this->clientRequest;
    }

    public function &getDb()
    {
        return $this->_db;
    }

    public function getOptions($key = null)
    {
        return $key === null ? $this->options : $this->options[$key];
    }

    public function isCli()
    {
        return $this->isCli;
    }

    public function isProd()
    {
        return SYSTEM_ENV === ENVIRONMENT_LIVE;
    }

}

class ApplicationException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message, null, null);
    }

}
