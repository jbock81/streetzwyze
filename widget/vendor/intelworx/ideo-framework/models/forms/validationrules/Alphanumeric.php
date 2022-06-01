<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of Alphanumeric
 *
 * @author intelWorX
 */
class Alphanumeric extends ScalarValidator
{

    protected $extraXters;

    public function __construct($sanitizeFlags = DEFAULT_FILTER_STRING_FIELDS, $extras = '', $errorMsgCode = 'errors.not_alnum', $errorMsg = null)
    {
        parent::__construct($sanitizeFlags, $errorMsgCode, $errorMsg);
        $this->extraXters = $extras;
    }

    public function validate($inputValue)
    {
        $inputValue = $this->sanitize($inputValue);
        return \Respect\Validation\Validator::create()
            ->alnum($this->extraXters)
            ->noWhitespace()
            ->validate($inputValue);
    }

}
