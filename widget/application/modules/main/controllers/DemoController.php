<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 04/11/2015
 * Time: 8:09 PM
 */

namespace main\controllers;


class DemoController extends BaseController
{

    protected function _initCheckDev()
    {
        if (\Application::currentInstance()->isProd()) {
            $this->_request->redirect404('ONLY_AVAILABLE_IN_TESTING_OR_DEV');
            exit;
        }
        $this->_layout_tpl = 'layout.demo.tpl';
    }


    public function doDefault()
    {

    }

    public function doPlugin()
    {
        $this->_view->reservationId = $this->_request->getQueryParam('rid') ? : '{RESERVATION_ID}';
        $this->_view->returnUrl = $this->_request->getQueryParam('returnUrl', false, $this->_request->getOriginalUrl
        (true));
        if ($this->_request->isPost()) {
            $this->_view->responseData = $this->_request->getPostData();
        }
        $this->_view->sourceCode = htmlentities($this->_view->fetch('_plugin.tpl'));
    }
}