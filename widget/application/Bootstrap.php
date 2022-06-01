<?php

/**
 * Description of Bootstrap
 *
 * @author intelWorX
 */
class Bootstrap extends \ApplicationBootstrap
{

    protected function _initSmartyPlugins()
    {
        $smarty = TemplateEngine::getInstance();
        $smarty->addPluginsDir(__DIR__ . DS . 'plugins/smarty');
    }

    protected function _initBaseUrl(){
        $baseUrl = SystemConfig::getInstance()->system['site_base_url']?:BASE_URL;
        $this->application->getClientRequest()->setBaseUrl($baseUrl);
        SmartyMiscFunctions::clearAssetsUrl('default');
        SmartyMiscFunctions::addAssetsUrl('default', $baseUrl . 'static/');
    }
}
