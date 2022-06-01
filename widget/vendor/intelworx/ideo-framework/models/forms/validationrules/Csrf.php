<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

use models\forms\ValidationRule;
use Session;
use Strings;
use Utilities;

/**
 * Description of Csrf
 *
 * @author JosephT
 */
class Csrf extends ValidationRule
{

    protected $sessionNs;

    public function __construct($errorMsgCode = 'errors.crsf', $errorMsg = null, $sessionNs = '_XSS_')
    {
        $this->error = $errorMsg ?: Strings::get($errorMsgCode ?: 'errors.unknown');
        $this->sessionNs = $sessionNs;
    }

    public function validate($inputValue)
    {
        $session = Session::getInstance();
        $code = $session->get($inputValue, $this->sessionNs);

        \SystemLogger::info('CSRF Value: ', $inputValue);
        \SystemLogger::info('Namespace : ', $this->sessionNs);
        \SystemLogger::info('Code : ', $code);

        if (!$code || $code !== $inputValue) {
            return false;
        }

        $session->unsetFromNamespace($inputValue, $this->sessionNs);
        return true;
    }

    public function create()
    {
        $code = Utilities::getRandomCode(10);
        Session::getInstance()->set($code, $code, $this->sessionNs);
        \SystemLogger::info('saved in session', $code);
        return $code;
    }

}
