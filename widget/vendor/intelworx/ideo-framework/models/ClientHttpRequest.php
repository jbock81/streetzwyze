<?php

class ClientHttpRequest extends IdeoObject
{

    protected $controller;
    protected $action;
    protected $_params = array();
    protected $_extras = array();
    protected $requestParams;
    protected $_url_path;
    protected $_is_mobile;
    /**
     * @var HttpRequestDelegate
     */
    protected $requestDelegate;

    /**
     *
     * @var Mobile_Detect
     */
    protected $mobileDeviceDetector;

    /**
     *
     * @var ModuleConfig
     */
    protected $module;
    protected $useModuleNamespace = false;

    /**
     *
     * @var ClientRequestHook[][]
     */
    protected $hooks = array();
    protected $lastHook = '';
    protected $baseUrl = BASE_URL;
    protected static $_runs = 0;

    /**
     *
     * @var BaseController
     */
    protected $controllerObject;

    const REQUEST_DEFAULT_ACTION = 'default';
    const REQUEST_DEFAULT_MODULE = 'default';
    const REQUEST_DEFAULT_CONTROLLER = 'home';

    /**
     *
     * @var ModuleManager
     */
    protected $moduleManager;
    protected $defaultModule = self::REQUEST_DEFAULT_MODULE;

    /**
     *
     * @var ClientHttpRequest
     */
    private static $currentRequest;

    public function __construct($req, $url = "", $is_mobile = null)
    {
        $this->moduleManager = ModuleManager::instance();
        $this->requestDelegate = new HttpRequestDelegate();
        if (defined("APP_DEFAULT_MODULE")) {
            $this->defaultModule = APP_DEFAULT_MODULE;
        }

        //load module information
        $this->requestParams = $req;

        $module = $this->moduleManager->matchingModule(isset($req[0]) ? $req[0] : '') ?: $this->moduleManager->getModule($this->defaultModule);

        if (!$module) {
            throw new ApplicationException("Default module could not be loaded.");
        }

        $this->module = $module;
        /* @var $module ModuleConfig */
        if ($module->getModuleName() !== $this->defaultModule) {
            $req['module'] = array_shift($req);
        } else {
            $this->module->setIsDefault(true);
        }

        $this->_params['module'] = $this->module->getPath();
        $this->_params['moduleKey'] = $this->module->getModuleName();

        $this->_url_path = $url;
        $defaultController = defined("APP_DEFAULT_CONTROLLER") ? APP_DEFAULT_CONTROLLER : self::REQUEST_DEFAULT_CONTROLLER;

        $defaultAction = defined("APP_DEFAULT_ACTION") ? APP_DEFAULT_ACTION : self::REQUEST_DEFAULT_ACTION;

        $this->controller = $req[0] ? $req[0] : $defaultController;
        $this->_params['controller'] = &$this->controller;
        $this->action = $req[1] ? $req[1] : $defaultAction;
        $this->_params['action'] = &$this->action;

        //build parameters on URL
        for ($i = 2; $i < count($req); $i += 2) {
            $this->_params[$req[$i]] = $req[$i + 1];
            $this->_extras[] = $req[$i];
            if (!is_null($req[$i + 1])) {
                $this->_extras[] = $req[$i + 1];
            }
        }

        $this->_params['extras'] = &$this->_extras;

        $this->mobileDeviceDetector = new Mobile_Detect();

        $this->_is_mobile = is_null($is_mobile) ? $this->mobileDeviceDetector->isMobile() || $this->mobileDeviceDetector->isTablet() : $is_mobile;
        self::$currentRequest = $this;
    }

    public function run($controllerClass = null)
    {
        //run this hook before controller is called
        $this->runHooks(null, ClientRequestHook::HOOK_PRE_CONTROLLER);

        if (!$controllerClass) {//was controller class specified? Autoload
            if ($this->useModuleNamespace) {
                $prefix = $this->module->getControllerNamespace() . '\\';
            } else {
                $prefix = GeneralUtils::delimetedToCamelCased($this->module->getModuleName(), "/[\-_]/", GeneralUtils::CAMEL_CASE_STYLE_UCFIRST);
            }


            $className = GeneralUtils::delimetedToCamelCased($this->controller, "/[\-_]/", GeneralUtils::CAMEL_CASE_STYLE_UCFIRST);
            $controllerClass = $prefix . $className . "Controller";
        }


        if (!class_exists($controllerClass)) {
            if (!$this->runHooks(null, ClientRequestHook::HOOK_NO_CONTROLLER)) {
                $this->redirect404("ControllerNotFound::{$controllerClass}");
            } else {
                return;
            }
        }

        if (!in_array(BaseController::getClass(), class_parents($controllerClass))) {
            throw new RuntimeException("Controller class: [{$controllerClass}] does not extend BaseController");
        }


        $this->controllerObject = $controller = new $controllerClass($this);
        /**
         * @todo call predispatch
         */
        $this->runHooks($controller, ClientRequestHook::HOOK_PRE_DISPATCH);
        $controller->dispatch($this->action);
        $this->runHooks($controller, ClientRequestHook::HOOK_POST_DISPATCH);
        /**
         * @todo Call post dispatch
         * @todo Call pre-display
         *
         */
        if (!$controller->isDisplayed()) {
            $this->runHooks($controller, ClientRequestHook::HOOK_PRE_DISPLAY);
            $controller->display();
            $this->runHooks($controller, ClientRequestHook::HOOK_POST_DISPLAY);
        }
        $this->runHooks($controller, ClientRequestHook::HOOK_SHUTDOWN);
    }

    public function redirect404($error_type)
    {
        $this->controller = 'error';
        $this->action = 'error404';
        $this->_params['ERROR_TYPE'] = $error_type;
        $this->run($this->getErrorController());
        exit;
    }

    private function isValidErrorController($className)
    {
        return class_exists($className) && in_array('\IErrorController', class_implements($className));
    }

    protected function getErrorController()
    {
        if (($controllerName = $this->getModule()->get('errorController')) && $this->isValidErrorController($controllerName)) {
            return $controllerName;
        }

        $controllersNs = $this->useModuleNamespace ? $this->module->getControllerNamespace() . '\\' : '';
        $controllerName = $controllersNs . 'ErrorController';
        return $this->isValidErrorController($controllerName) ? $controllerName : ErrorController::getClass();
    }

    public function redirect500(Exception $e)
    {
        $this->controller = 'error';
        $this->action = 'error500';
        $smarty = TemplateEngine::getInstance();
        $smarty->assign('is_live', (SYSTEM_ENV == ENVIRONMENT_LIVE));
        $smarty->assign('message', $e->getMessage());
        $smarty->assign('stackTrace', print_r($e->getTraceAsString(), 1));
        $this->run($this->getErrorController());
        exit;
    }

    public function redirect($to = '', $status = '302 Moved Temporarily')
    {
        if ($this->lastHook != ClientRequestHook::HOOK_SHUTDOWN) {
            $this->runHooks($this->controllerObject, ClientRequestHook::HOOK_SHUTDOWN);
        }
        if (!$to) {
            $to = $this->baseUrl;
        } elseif (!preg_match('/^https?:\/\//i', $to)) {
            $to = ltrim($to, '/');
            if ($this->module->getPath()) {
                $to = $this->module->getPath() . '/' . $to;
            }
            $to = $this->baseUrl . $to;
        }
        header('Location: ' . $to);
        header("HTTP/1.1 {$status}");
        exit(0);
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function getParam($paramKey = '')
    {
        return $this->_params[$paramKey];
    }

    public function getOriginalUrl($includeQuery = false)
    {
        $url = $this->baseUrl . $this->_url_path;
        if (!$includeQuery) {
            return $url;
        } else {
            $queryString = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
            return $url . $queryString;
        }
    }

    public function getPostData($key = "", $secure = true, $default = null)
    {
        return $this->_get($_POST, $key, $secure, $default);
    }

    public function getRequest($key = "", $secure = true, $default = null)
    {
        return $this->_get($_REQUEST, $key, $secure, $default);
    }

    public function getCookie($key = "", $secure = true, $default = null)
    {
        return $this->_get($_COOKIE, $key, $secure, $default);
    }

    public function isPost()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
    }

    public function isGet()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
    }

    public function getGetData($key = "", $secure = true, $default = null)
    {
        return $this->_get($_GET, $key, $secure, $default);
    }

    public function getQueryParam($key = null, $secure = true, $default = null)
    {
        return $this->_get($_GET, $key, $secure, $default);
    }

    protected function _get(&$var, $key, $secure = true, $default = null)
    {
        if (!is_null($key) && strlen($key)) {
            if (!isset($var[$key])) {
                return $default;
            }
            return $secure ? secure($var[$key]) : $var[$key];
        } else {
            return $secure ? secure($var) : $var;
        }
    }

    public function isMobile()
    {
        return $this->_is_mobile;
    }

    public function overWriteRequestParam($paramName, $paramValue)
    {
        $this->_params[$paramName] = $paramValue;
        return $this;
    }

    public function clearParams()
    {
        $this->_params = array();
        return $this;
    }

    public function buildQueryFromCurrent($exclude = '', $add = null)
    {
        $currentQuery = $this->getGetData();
        $exclude = explode(',', $exclude);
        foreach ($exclude as $key) {
            unset($currentQuery[$key]);
        }
        $query = http_build_query($currentQuery);


        if ($add) {
            $query = $query ? "$query&" : "";
            $query .= $add;
        }
        return $query;
    }

    public function removeQueryKeyFromUrl($url, $query_key = '')
    {
        if (!strstr($url, '?')) {
            $url .= '?';
        } elseif (strstr($url, $query_key)) {
            $url = preg_replace('/' . $query_key . '\=.*\&?/', '', $url);
        }

        return $url;
    }

    /**
     *
     * @param String $action
     * @param String $controller
     * @param String $moduleName
     * @param String $params
     * @param String $queryString
     * @return String, url
     *
     */
    public function buildURL($action = '', $controller = '', $moduleName = '', $params = null, $queryString = null, $baseUrl = null)
    {
        $url = $baseUrl ?: $this->baseUrl;
        unset($params['controller'], $params['action'], $params['module'], $params['moduleKey']);

        if (count($params) && !$action) {
            $action = $this->action ?: self::REQUEST_DEFAULT_ACTION;
        }

        if (!$controller && (!$moduleName || $action)) {
            $controller = $this->controller ?: self::REQUEST_DEFAULT_CONTROLLER;
        }

        if (!$moduleName) {
            $moduleName = $this->module->getModuleName();
        }


        if ($moduleName) {
            //try this as a module name, 
            $module = $this->moduleManager->getModule($moduleName);
            //if not, use it as a module path
            $modulePath = $module ? $module->getPath() : $moduleName;
            if ($modulePath) {
                //this is not an empty path, like in default.
                $url .= $modulePath . '/';
            }
        }

        if ($controller) {
            $url .= $controller . '/';
            if ($action && ($action != self::REQUEST_DEFAULT_ACTION || count($params))) {
                $url .= $action . '/';
                if (count($params)) {
                    if (count($params['extras'])) {
                        $url .= join('/', (array)$params['extras']) . '/';
                        unset($params['extras']);
                    }

                    foreach ($params as $key => $value) {
                        $url .= "$key/";
                        if ($value) {
                            $url .= "$value/";
                        }
                    }
                }
            }
        }

        if ($queryString) {
            /* @var $queryString string */
            $url .= "?$queryString";
        }
        return $url;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getModuleKey()
    {
        return $this->module->getModuleName();
    }

    public function isModule()
    {
        trigger_error("This method has been deprecated, use ClientHttpRequest::isDefaultModule() instead", E_USER_DEPRECATED);
        return $this->isDefaultModule();
    }

    public function isDefaultModule()
    {
        return $this->module->getModuleName() === $this->defaultModule;
    }

    /**
     *
     * @param type $action
     * @return \ClientHttpRequest
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     *
     * @param string $controller
     * @return ClientHttpRequest
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     *
     * @param string|ModuleConfig $module
     * @return \ClientHttpRequest
     */
    public function setModule($module)
    {
        $moduleConfig = null;
        if (is_string($module)) {
            $moduleConfig = $this->moduleManager->getModule($module);
        } else {
            $moduleConfig = $module;
        }

        if (!$moduleConfig || !($moduleConfig instanceof ModuleConfig)) {
            throw new RuntimeException("Module can either be a string or an instance of : "
                . ModuleConfig::getClass());
        }

        $this->module = $moduleConfig;

        $this->_params['module'] = $this->module->getPath();
        $this->_params['moduleKey'] = $this->module->getModuleName();

        return $this;
    }

    /**
     *
     * @param type $moduleKey
     * @return \ClientHttpRequest
     */
    public function setModuleKey($moduleKey)
    {
        return $this->setModule($moduleKey);
    }

    /**
     *
     * @param bool $bool
     * @return \ClientHttpRequest
     */
    public function setIsModule()
    {
        trigger_error("Method is deprecated, you should use ClientHttoRequest::setModule() instead", E_USER_DEPRECATED);
        return $this;
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public function hasQueryParam($key)
    {
        return isset($_GET[$key]);
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public function hasPost($key)
    {
        return isset($_POST[$key]);
    }

    public function setIsMobile($bool = true)
    {
        $this->_is_mobile = $bool;
        return $this;
    }

    public function setResponseHeader($header, $value, $replace = true)
    {
        header($header . ': ' . $value, $replace);
    }

    public function getUseModuleNamespace()
    {
        return $this->useModuleNamespace;
    }

    public function setUseModuleNamespace($bool = true)
    {
        $this->useModuleNamespace = $bool;
    }

    public function addHook(ClientRequestHook $hook, $priority = 0)
    {
        $priority = (int)$priority;
        if (!isset($this->hooks[$priority])) {
            $this->hooks[$priority] = array();
        }

        $this->hooks[$priority][] = $hook;
        krsort($this->hooks);
        return $this;
    }

    public function clearAllHooks()
    {
        $this->hooks = array();
        return $this;
    }

    public function runHooks($controller, $hookType)
    {
        $results = 0;
        $this->lastHook = $hookType;
        foreach ($this->hooks as $hooks) {
            /* @var $hook ClientRequestHook */
            foreach ($hooks as $hook) {
                if (method_exists($hook, $hookType)) {
                    if ($hookType == ClientRequestHook::HOOK_PRE_CONTROLLER || $hookType == ClientRequestHook::HOOK_NO_CONTROLLER) {
                        $results += $hook->$hookType($this);
                    } else {
                        $results += $hook->$hookType($this, $controller);
                    }
                }
            }
        }
        return $results;
    }

    public function removeHook(ClientRequestHook $hook)
    {
        foreach ($this->hooks as &$hooksAtPriority) {
            foreach ($hooksAtPriority as $idx => $aHook) {
                if ($hook == $aHook) {
                    unset($hooksAtPriority[$idx]);
                }
            }
        }

        return $this;
    }

    public function getExtras($index = null, $default = null)
    {
        return $this->_get($this->_extras, $index, true, $default);
    }

    public function setExtras(array $extras, $merge = false)
    {
        $this->_extras = $merge ? array_merge($this->_extras, $extras) : $extras;
        return $this;
    }

    public function setBaseUrl($baseUrl = BASE_URL)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @return mixed
     */
    public function getRequestParams()
    {
        return $this->requestParams;
    }

    /**
     * Get URL Path
     * @return string
     */
    public function getUrlPath()
    {
        return $this->_url_path;
    }

    /**
     *
     * Returns the last initialized request.
     * @return ClientHttpRequest
     */
    public static function getCurrent()
    {
        return self::$currentRequest;
    }

    /**
     *
     * Gets request body
     * @return string
     */
    public function getBody()
    {
        return $this->requestDelegate->body();
    }

    /**
     *
     * Get a request header, if name is null, all headers will be returned.
     * @param string $name
     * @return array|bool|string
     */
    public function getHeader($name = null)
    {
        return $name === null ? $this->requestDelegate->headers() : $this->requestDelegate->header($name);
    }

    /**
     * Returns the request method
     * @return mixed
     */
    public function getMethod(){
        return $this->requestDelegate->method();
    }
}
