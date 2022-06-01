<?php

/**
 * Description of SmartyMiscFunctions
 *
 * @author intelWorX
 */
class SmartyMiscFunctions extends IdeoObject
{

    const ASSET_URL_SEPARATOR = "|";

    protected static $stripTrailingUrlSlash = false;


    protected static $assetsUrl = array();

    public static function setStripTrailingUrlSlash($stripTrailingUrlSlash = true)
    {
        self::$stripTrailingUrlSlash = $stripTrailingUrlSlash;
    }

    public static function isStripTrailingUrlSlash()
    {
        return self::$stripTrailingUrlSlash;
    }
    //put your code here
    //const NA_DISPLAY = "--No"
    public static function smartyFunctionIsAvailable($params, $smarty)
    {
        $str = $params['value'];
        $na = $params['na'] ? $params['na'] : AppConfig::getInstance()->nA;
        if (trim($str)) {
            return $str;
        } else {
            return $na;
        }
    }

    /**
     *
     * @param array $params
     * @param MySmarty $smarty
     */
    public static function smartyFunctionBuildUrl($params, $smarty)
    {
        $request = ClientHttpRequest::getCurrent();
        $parsedStr = array();
        $action = $params['action'];
        $controller = $params['controller'];
        $module = $params['module'];
        $assign = $params['assign'];
        parse_str($params['params'], $parsedStr);
        $query_string = $params['query_string'];
        unset($params['params'], $params['module'], $params['controller'], $params['action'], $params['assign'], $params['query_string']);
        $parsedStr1 = $parsedStr + (array)$params;
        $url = $request->buildURL($action, $controller, $module, $parsedStr1, $query_string);

        if (self::$stripTrailingUrlSlash) {
            $url = trim($url, '/');
        }

        if ($assign) {
            $smarty->assign($assign, $url);
        } else {
            return $url;
        }
    }

    public static function registerPlugins()
    {
        $className = self::getClass();
        $methods = get_class_methods($className);
        $smarty = TemplateEngine::getInstance();
        foreach ($methods as $method) {
            $matches = array();
            if (preg_match('/^smartyFunction([a-zA-Z0-9_]+)/', $method, $matches)) {
                $smarty->registerPlugin('function', GeneralUtils::camelCaseToDelimited($matches[1]), array($className, $method));
            } elseif (preg_match('/^smartyModifier([a-zA-Z0-9_]+)/', $method, $matches)) {
                $smarty->registerPlugin('modifier', GeneralUtils::camelCaseToDelimited($matches[1]), array($className, $method));
            }
        }
    }

    public static function addAssetsUrl($key, $url)
    {
        $assetUrls = explode(self::ASSET_URL_SEPARATOR, $url);
        if (!isset(self::$assetsUrl[$key])) {
            self::$assetsUrl[$key] = array();
        }

        /* @var $assetUrl string */
        foreach ($assetUrls as $assetUrl) {
            $assetUrl = $assetUrl ? rtrim($assetUrl, '/') . '/' : '';
            if (preg_match('/^(https?:)?\/\//', $assetUrl)) {
                self::$assetsUrl[$key][] = $assetUrl;
            } else {
                self::$assetsUrl[$key][] = BASE_URL . $assetUrl;
            }
        }
    }

    public static function clearAssetsUrl($key = null)
    {
        if ($key === null) {
            self::$assetsUrl = array();
        } else {
            self::$assetsUrl[$key] = array();
        }
    }

    public static function getAssetsUrl($key, $index = 0)
    {
        return self::$assetsUrl[$key][$index];
    }

    /**
     *
     * @param array $params {key (optional), assign (optional), index (optional)}
     * @param Smarty $smarty
     * @return $url if assign is not set
     * @todo Add rotation mechanism
     *
     */
    public static function smartyFunctionAssetsUrl($params, $smarty)
    {
        $params = array_merge(array(
            'key' => 'default',
            'assign' => '',
            'index' => 0,
            'random' => true,
        ), $params
        );

        if (array_key_exists($params['key'], self::$assetsUrl)) {
            $urls = self::$assetsUrl[$params['key']];
        } else {
            $urls = (array)current(self::$assetsUrl);
        }

        $url = $urls[$params['random'] ? rand(0, count($urls) - 1) : $params['index']];

        if ($params['assign']) {
            $smarty->assign($params['assign'], $url);
        } else {
            return $url;
        }
    }

    /**
     *
     * @param array $params
     * @param TemplateEngine $smarty
     */
    public static function smartyFunctionDisplayJs($params, $smarty)
    {
        $params = array_merge(array(
            'type' => 'js',
        ), $params);

        $type = $params['type'];
        unset($params['type']);
        $attributes = self::buildAttributes($params);
        $js = "";
        foreach ($smarty->getResources($type) as $key => $script) {
            $js .= "<script src='{$script}' id='{$key}' {$attributes}></script>";
        }

        return $js;
    }

    /**
     * Displays CSS within a template.
     * @param array $params
     * @param TemplateEngine $smarty
     */
    public static function smartyFunctionDisplayCss($params, $smarty)
    {
        $params = array_merge(array(
            'type' => 'css',
        ), $params);

        $type = $params['type'];
        unset($params['type']);
        $attributes = self::buildAttributes($params);
        $css = "";
        foreach ($smarty->getResources($type) as $key => $sheet) {
            $css .= "<link href=\"{$sheet}\" rel=\"stylesheet\" id=\"{$key}\" {$attributes}/>";
        }

        return $css;
    }

    private static function buildAttributes($attrs)
    {
        $attributes = [];
        foreach ($attrs as $name => $value) {
            $attributes[] = "{$name}=\"" . str_replace('"', '\"', htmlentities($value)) . "\"";
        }
        return join(' ', $attributes);
    }

    public static function smartyFunctionAddResource($params, $smarty)
    {
        if (!isset($params['type'], $params['url'])) {
            throw new SmartyException("Paremeters 'type' and 'url' are required for {add_resource}");
        }
        $smarty->addResource($params['type'], $params['url'], $params['key']);
    }

    public static function smartyFunctionStr($params, $smarty)
    {
        $key = $params['key'] ?: '';

        if (!$key) {
            throw new SmartyException('Key must be specified!');
        }

        $assign = $params['assign'] ?: '';

        unset($params['assign'], $params['key']);

        $str = Strings::get($key, $params, false);

        if ($assign) {
            $smarty->assign($assign, $str);
        } else {
            return $str;
        }
    }

    public static function smartyModifierStrVal($value, $map = [])
    {
        return Strings::get($value, $map, false);
    }

    /**
     *
     * @param array $params
     * @param Smarty $smarty
     * @throws SmartyException
     */
    public static function smartyFunctionGlobalAssign($params, $smarty)
    {
        if (!isset($params['var'], $params['value'])) {
            if (count($params) != 1) {
                throw new SmartyException("Paremeters 'var' and 'value' are required for {global_assign}");
            }
            list($varName, $value) = each($params);
            TemplateEngine::getInstance()->assign($varName, $value);
            $smarty->assign($varName, $value);
        } else {
            TemplateEngine::getInstance()->assign($params['var'], $params['value']);
            $smarty->assign($params['var'], $params['value']);
        }
    }

}
