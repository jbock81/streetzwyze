<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of CustomRule
 *
 * @author intelWorX
 */
class Callback extends \models\forms\ValidationRule {

    /**
     *
     * @var callable
     */
    protected $validationCallback;

    /**
     *
     * @param callable $callback
     */
    public function __construct($callback, $error = null, $errorCode = null) {
        if (!is_callable($callback)) {
            throw new \Respect\Validation\Exceptions\ValidationException("The specified callback is not usable.");
        }

        $this->validationCallback = $callback;
        $this->errorCode = $errorCode;
        $this->error = !$error && $errorCode ? \Strings::get($errorCode) : $error;
    }

    public function validate($inputValue) {
        $this->sanitized = $inputValue;
        return \Respect\Validation\Validator::create()
                        ->callback($this->validationCallback)
                        ->validate($inputValue);
    }

}
