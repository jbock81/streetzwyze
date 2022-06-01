<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

use Respect\Validation\Validator as v;

/**
 * Description of Url
 *
 * @author intelWorX
 */
class Url extends ScalarValidator {

    public function __construct($sanitizeFlags = FILTER_SANITIZE_URL, $errorMsgCode = 'errors.invalid_url', $errorMsg = null) {
        //$sanitizeFlags |= FILTER_SANITIZE_URL;
        parent::__construct($sanitizeFlags, $errorMsgCode, $errorMsg);
    }

    public function validate($origInput) {
        $inputValue = $this->sanitize($origInput);
        return !trim($origInput) ||
                v::call(
                        'parse_url', v::arrayVal()->key('scheme', v::startsWith('http'))
                                ->key('host', v::domain())
                        //->key('path', v::stringType())
                        //->key('query', v::notEmpty())
                )->validate($inputValue);
    }

}
