<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of Integer
 *
 * @author JosephT
 */
class Integer extends \models\forms\ValidationRule
{

    //put your code here
    protected $min;
    protected $max;

    public function __construct($min = null, $max = null)
    {
        $this->min = $min;
        $this->max = $max;

        $this->errorCodes = array(
            'min' => 'validator.min_val',
            'max' => 'validator.max_val',
            'invalid' => 'validator.not_integer',
        );
    }

    public function validate($inputValue)
    {
        $inputValue = $this->sanitized = strlen($inputValue) ? intval($inputValue) : null;
        $this->setErrorbyCode('invalid');
        return is_null($inputValue) || \Respect\Validation\Validator::create()
            ->intType()
            ->validate($inputValue) &&
        $this->validateMin($inputValue) && $this->validateMax($inputValue);
    }

    protected function validateMin($inputVal)
    {
        $this->setErrorbyCode('min');
        return is_null($this->min) || $inputVal >= $this->min;
    }

    protected function validateMax($inputVal)
    {
        $this->setErrorbyCode('max');
        return is_null($this->max) || $inputVal <= $this->max;
    }

}
