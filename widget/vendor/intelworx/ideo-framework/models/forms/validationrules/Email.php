<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace models\forms\validationrules;
use Respect\Validation\Validator;

/**
 * Description of Email
 *
 * @author JosephT
 */
class Email extends ScalarValidator
{

    public function __construct($sanitizeFlags = 0, $errorMsgCode = 'validator.email_invalid', $errorMsg = null)
    {
        parent::__construct($sanitizeFlags, $errorMsgCode, $errorMsg);
    }

    public function validate($inputValue)
    {
        return Validator::create()
            ->email()
            ->validate($inputValue);
    }

//put your code here
}
