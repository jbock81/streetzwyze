<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of JustSanitized
 *
 * @author intelWorX
 */
class JustSanitized extends ScalarValidator
{

    public function __construct($sanitizeFlags = DEFAULT_FILTER_STRING_FIELDS)
    {
        parent::__construct($sanitizeFlags, null, null);
    }

    public function validate($inputValue)
    {
        $inputValue = $this->sanitize($inputValue);
        return true;
    }

}
