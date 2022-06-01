<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of ScalarValidator
 *
 * @author intelWorX
 */
abstract class ScalarValidator extends \models\forms\ValidationRule
{

    protected $sanitizeFlags = 0;

    public function __construct($sanitizeFlags = 0, $errorMsgCode = null, $errorMsg = null)
    {
        //FILTER_REQUIRE_SCALAR
        $this->sanitizeFlags |= $sanitizeFlags;
        $this->error = $errorMsg ?: \Strings::get($errorMsgCode ?: 'errors.unknown');
    }

    protected function sanitize($inputValue)
    {
        return $this->sanitized = $this->sanitizeFlags ? filter_var($inputValue, $this->sanitizeFlags) : $inputValue;
    }
}
