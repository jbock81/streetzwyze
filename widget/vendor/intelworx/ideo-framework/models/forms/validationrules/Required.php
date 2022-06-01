<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of Required
 *
 * @author intelWorX
 */
class Required extends ScalarValidator
{

    public function __construct($sanitizeFlags = 0, $errorMsgCode = 'validator.required', $errorMsg = null)
    {
        parent::__construct($sanitizeFlags, $errorMsgCode, $errorMsg);
    }

    public function validate($inputValue)
    {
        $inputValue = $this->sanitize($inputValue);
        return strlen((strval($inputValue))) > 0;
    }

}
