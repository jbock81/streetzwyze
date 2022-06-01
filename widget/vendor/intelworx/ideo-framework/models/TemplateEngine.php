<?php

/**
 * Extended smarty
 */
class TemplateEngine extends Smarty
{

    /**
     *
     * @var TemplateEngine
     */
    private static $instance;
    private static $fromWithin = false;

    const TEMPLATE_DIR_APPEND = 1;
    const TEMPLATE_DIR_PREPEND = 2;
    const TEMPLATE_DIR_ONLY = 3;
    const RESOURCE_CSS = 'css';
    const RESOURCE_JS = 'js';
    const RESOURCE_IMG = 'img';

    private $staticResources = array(
        self::RESOURCE_CSS => array(),
        self::RESOURCE_JS => array(),
        self::RESOURCE_IMG => array(),
    );
    private $allStaticResources = array();

    public function __construct()
    {
        if (!self::$fromWithin) {
            throwException(new Exception("Use Singleton approach of TemplateEngine::getInstance()"));
        }

        if (self::$instance !== null) {
            throwException(new Exception("Instance Already created for Template Engine"));
        }
        parent::__construct();
        $this->assignByRef('staticResources', $this->staticResources);
    }

    public function __set($name, $value = null)
    {
        @parent::__set($name, $value);
        $this->assign($name, $value);
    }

    public function __get($name)
    {
        if (($value = @parent::__get($name))) {
            return $value;
        }
        return $this->getTemplateVars($name);
    }

    public function initThemeFolder($theme, $templatesDir = "", $templateCompileDir = "", $resourcesUrl = null, $setType = self::TEMPLATE_DIR_PREPEND)
    {
        if (!$theme) {
            return;
        }

        if (!$templatesDir) {
            $templatesDir = APP_DIR . 'views' . DIRECTORY_SEPARATOR;
        }

        $themeFolder = $templatesDir . DS . $theme . DS;

        if ($templateCompileDir) {
            if (!file_exists($templateCompileDir) || !is_readable($templateCompileDir)) {
                mkdir($templateCompileDir, 0755, true);
            }
            if (!is_writable($templateCompileDir)) {
                throw new TemplateEngineException("Specified Compile cache directory [{$templateCompileDir}] is unwritable");
            }
        }


        if (!$resourcesUrl) {
            $themeResources = $themeFolder . 'static' . DS;
            if (is_readable($themeResources) && strstr($themeResources, APP_ROOT) !== false) {
                $resourcesUrl = str_replace(array(APP_ROOT, DS), array(BASE_URL, '/'), $themeResources);
            } else {
                $resourcesUrl = BASE_URL;
            }
        }

        if ($setType == self::TEMPLATE_DIR_APPEND) {
            $this->addTemplateDir($themeFolder);
        } elseif ($setType == self::TEMPLATE_DIR_PREPEND) {
            $this->prependTemplateDir($themeFolder);
        } else {
            $this->setTemplateDir($themeFolder);
        }

        if (is_dir($templateCompileDir) && is_writable($templateCompileDir)) {
            $this->setCompileDir($templateCompileDir);
        }

        SmartyMiscFunctions::addAssetsUrl($theme, $resourcesUrl);
        return $this;
    }

    /**
     *
     * @return TemplateEngine
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$fromWithin = true;
            self::$instance = new self();
            self::$fromWithin = FALSE;
        }

        return self::$instance;
    }

    public function prependTemplateDir($templateDir)
    {
        $this->setTemplateDir(array_merge((array)$templateDir, $this->getTemplateDir()));
        return $this;
    }

    public function addResource($type, $path, $key = null)
    {
        if (!array_key_exists($type, $this->staticResources)) {
            $this->staticResources[$type] = array();
        }

        if (!array_key_exists($path, $this->allStaticResources)) {
            //avoid duplication of resources
            if (is_null($key)) {
                $this->staticResources[$type][] = $path;
            } else {
                $this->staticResources[$type][$key] = $path;
            }

            //add to list of resources
            $this->allStaticResources[$path] = true;
        }

        return $this;
    }

    public function getResources($group)
    {
        return array_key_exists($group, $this->staticResources) ? $this->staticResources[$group] : [];
    }

    public function __isset($name)
    {
        return isset($this->tpl_vars[$name]);
    }

    public function __unset($name)
    {
        unset($this->tpl_vars[$name]);
    }

}
