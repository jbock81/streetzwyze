<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of TextValidator
 *
 * @author JosephT
 */
class Text extends ScalarValidator
{

    protected $minLength;
    protected $maxLength;
    protected $regEx;

    public function __construct($sanitizeFlags = 0, $minLength = 0, $maxLength = PHP_INT_MAX, $regEx = null, $errorMsgCode = null, $errorMsg = null)
    {
        parent::__construct($sanitizeFlags, $errorMsgCode, $errorMsg);

        $this->minLength = (int)$minLength;
        $this->maxLength = (int)$maxLength;

        if ($this->minLength < 0) {
            $this->minLength = 0;
        }

        if ($this->minLength > $this->maxLength) {
            $this->maxLength = PHP_INT_MAX;
        }

        $this->regEx = $regEx;
        if (!$errorMsg && !$errorMsgCode) {
            $this->error = null;
        }
    }

    public function validate($inputValue)
    {
        $value = $this->sanitize($inputValue);

        $validator = \Respect\Validation\Validator::create()
            ->length($this->minLength, $this->maxLength, true);

        if ($this->regEx) {
            $validator->regex($this->regEx);
        }

        try {
            $validator->check($value);
            return true;
        } catch (\Respect\Validation\Exceptions\ValidationException $ex) {
            $this->error = $this->error ?: $ex->getMainMessage();
            return false;
        }
    }

}
