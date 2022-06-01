<?php

class ErrorController extends BaseController implements IErrorController
{

    public function doDefault()
    {
        $this->_forward('error404');
    }

    public function doError404()
    {
        header("HTTP/1.1 404 Not Found", true);
        $this->_view->assign('showErrors', SystemConfig::getInstance()->system['display_errors']);
        $this->_view->assign('request', print_r($this->_request->getParams(), 1));
        $this->_view->error_type = $this->_request->getParam('ERROR_TYPE');
        $this->_view->assign('title', $this->_titles['error']['error404']);
        if ($this->_isAjax) {
            $this->display(1, 1);
            exit;
        }
    }

    public function doError500()
    {
        header("HTTP/1.1 500 Server Error");
        //$this->_request->setIsModule(false);
        $this->_view->assign('showErrors', SystemConfig::getInstance()->system['display_errors']);
        if ($this->_isAjax) {
            $this->display(1, 1);
            exit;
        } elseif (BaseController::$rendering_started) {
            $this->display(1, 1);
        }
    }

}