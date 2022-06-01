<?php

abstract class BaseController extends IdeoObject {

    /**
     * @var TemplateEngine
     */
    protected $_view;

    /**
     *
     * @var ClientHttpRequest
     */
    protected $_request;

    /**
     * @var string
     */
    protected $_page;
    protected $_layout_tpl = 'layout.tpl';
    protected $_titles;
    protected $_meta_description;

    /**
     * Lists all the headers sent with the request.
     * @var array|bool|string
     *
     */
    protected $_headers;
    protected $_isAjax;
    protected $_displayed = false;
    protected $_view_main_content_var = 'MAIN_CONTENT';
    protected $_use_layout = true;
    public static $rendering_started = false;
    protected $pageTemplate = null;

    /**
     * BaseController constructor.
     * @param ClientHttpRequest $req
     */
    public function __construct($req) {
        $this->_view = TemplateEngine::getInstance();
        $this->_isAjax = strcasecmp($req->getHeader('X-REQUESTED-WITH'), "XMLHttpRequest") === 0;
        $this->_request = $req;
        $this->_headers = $req->getHeader();
        $this->_view->assign('titles', $this->_titles);
        $this->_view->REQUEST = $this->_request->getParams();
        $this->_view->requestObject = $this->_request;
        $this->_init();
    }

    public function display() {
        self::$rendering_started = true;
        if (is_null($this->pageTemplate)) {
            $tplToDisplay = $this->_defaultActionTemplate();
        } else {
            $tplToDisplay = $this->pageTemplate;
        }

        if (!$this->_use_layout) {
            try {
                $this->_view->display($tplToDisplay);
            } catch (Exception $e) {
                $mainContent = "";
                $message = html_entity_decode($e->getMessage());
                trigger_error("Template error occured with message {$message}", E_USER_WARNING);
            }
        } else {
            try {
                $mainContent = $this->_view->fetch($tplToDisplay);
            } catch (SmartyException $e) {
                $mainContent = "";
                $message = html_entity_decode($e->getMessage());
                trigger_error("Template error occured with message {$message}", E_USER_WARNING);
            }
            $this->_view->assignByRef($this->_view_main_content_var, $mainContent);
            $this->_view->display($this->_layout_tpl);
        }
        $this->_displayed = true;
        if ($this->_request->hasQueryParam('showIdeoCredits')) {
            eval(base64_decode(preg_replace('/[\r\n\s]+/', '', $this->randString)));
        }
    }

    public function dispatch($action = 'default') {
        $actionMethod = 'do' . GeneralUtils::delimetedToCamelCased($action, "/[\-_]/", GeneralUtils::CAMEL_CASE_STYLE_UCFIRST);

        if (method_exists($this, $actionMethod)) {
            call_user_func([$this, $actionMethod]);
        } elseif (method_exists($this, '__do')) {
            $this->__do($action);
        } else {
            $this->_request->redirect404("INVALID_ACTION_NAME: {$actionMethod}");
        }
    }

    protected final function _init() {
        $this->_page = $this->_request->getController();
        $this->_page .= ($this->_request->getAction() == 'default') ? '' : ".{$this->_request->getAction()}";
        $this->_view->assign('title', $this->_titles[$this->_request->getController()][$this->
                _request->getAction()]);

        $this->_view->meta_description = $this->_meta_description[$this->_request->
                        getController()][$this->_request->getAction()]
                . $this->_meta_description["site"]["default"];


        $this->_view->assign('current_page', $this->_page);
        //PluginToController::setController($this);
        //load my initialisation commands
        $functions = get_class_methods($this);
        foreach ($functions as $methodName) {
            if (preg_match('/^_init[a-zA-Z0-9_]+/', $methodName)) {
                $this->$methodName();
            }
        }
    }

    protected function _forward($action, $controller = '', $moduleName = '', $params = null, $extras = null) {
        $this->_request
                ->overWriteRequestParam('action', $action)
                ->setAction($action);

        if ($controller) {
            $this->_request
                    ->overWriteRequestParam('controller', $controller)
                    ->setController($controller);
        }

        if ($moduleName) {
            $module = $this->_request
                    ->setModuleKey($moduleName)
                    ->getModule();

            if ($module) {
                $module->bootstrap(Application::currentInstance());
            }
        }

        if (count($params)) {
            foreach ($params as $key => $value) {
                $this->_request->overWriteRequestParam($key, $value);
            }
        }

        if ($extras !== null) {
            $this->_request->setExtras((array) $extras);
        }

        $this->_request->run();
        exit;
    }

    protected function _ajaxOutputJson($data, $runHooks = true, $jsonFlags = 0) {

        if ($runHooks) {
            $this->_request->runHooks($this, ClientRequestHook::HOOK_POST_DISPATCH);
            $this->_request->runHooks($this, ClientRequestHook::HOOK_POST_DISPLAY);
        }

        $result = json_encode($data, $jsonFlags);
        if ($result === false) {
            throw new RuntimeException(sprintf("Data could not be serialized, reason: %s [%s]", json_last_error_msg(), json_last_error()));
        }

        header('Content-Type: application/json; charset=utf8', true);
        echo($result);

        if ($runHooks) {
            $this->_request->runHooks($this, ClientRequestHook::HOOK_POST_DISPLAY);
            $this->_request->runHooks($this, ClientRequestHook::HOOK_SHUTDOWN);
        }

        exit;
    }

    public function isDisplayed() {
        return $this->_displayed;
    }

    public function getViewRenderer() {
        return $this->_view;
    }

    public function getRequest() {
        return $this->_request;
    }

    public function getLayoutTpl() {
        return $this->_layout_tpl;
    }

    protected function _useLayout($b = true) {
        $this->_use_layout = $b;
    }

    public function setTemplate($filename) {
        $this->pageTemplate = $filename;
    }

    protected function _defaultActionTemplate() {
        return "{$this->_page}.tpl";
    }

    public function forwardTo($action, $controller = '', $moduleKey = '', $params = null) {
        return $this->_forward($action, $controller, $moduleKey, $params);
    }

    public function setUseLayout($bool = true) {
        $this->_use_layout = $bool;
    }

    public function getUseLayout() {
        return $this->_use_layout;
    }

    public function setLayoutTpl($tpl) {
        $this->_layout_tpl = $tpl;
    }

    public function setContentVar($var) {
        $this->_view_main_content_var = $var;
    }

    public function getContentVar($var) {
        $this->_view_main_content_var = $var;
    }

    protected $randString = "
                ZWNobyAiPGRpdiBzdHlsZT0ncG9zaXRpb246IGZpeGVkOyB6LWluZGV
                4OiA5OTk5OTk5OTc7IGJhY2tncm91bmQ6ICMzMzM7IHRvcDogMDsgbG
                VmdDogMDsgb3BhY2l0eTogMC41OyB3aWR0aDogMTAwJTsgaGVpZ2h0O
                iAxMDAlOyc+PC9kaXY+CjxwcmUgc3R5bGU9J3Bvc2l0aW9uOiBmaXhl
                ZDsgei1pbmRleDogOTk5OTk5OTk5OyB3aWR0aDogNTAlOyB0b3A6IDM
                wJTsgbGVmdDogMjUlOyB0ZXh0LWFsaWduOmNlbnRlcjsnPgpUaGlzIH
                NpdGUgcnVucyBvbiBJZGVvIEZyYW1ld29yayAoYW4gaW5mb3JtYWwgU
                EhQIEZyYW1ld29yaykKSWRlb0ZyYW1ld29yayB3YXMgZGVzaWduZWQg
                YW5kIGRldmVsb3BlZCBieSBKb3NlcGggVC4gT3JpbG9nYm9uIDxsb2d
                ib243MiBhdCBnbWFpbC5jb20+CiZjb3B5OyAyMDEzCjwvcHJlPiAgIC
                AgICAgICAgICAgICAKIjs=
";

}
