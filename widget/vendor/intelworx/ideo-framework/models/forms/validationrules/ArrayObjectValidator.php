<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

use models\forms\ValidationRule;

/**
 * Description of ArrayObjectValidator
 *
 * @author intelWorX
 */
class ArrayObjectValidator extends ValidationRule {

    protected $rules;
    protected $values = array();
    protected $allowEmpty;
    protected $arrayOfArrays;

    public function __construct(array $rules = array(), $allowEmpty = false, $errorCode = 'validator.empty_array', $arrayOfArrays = null) {
        $this->rules = $rules;
        $this->allowEmpty = $allowEmpty;
        $this->setErrorbyCode($errorCode);
        $this->arrayOfArrays = $arrayOfArrays;
    }

    public function validate($inputValue) {
        //array of arrays or what
        if (empty($inputValue)) {
            return $this->allowEmpty;
        }

        if (is_null($this->arrayOfArrays)) {
            $arrayOfArrays = !array_is_assoc($inputValue) || array_has_numeric_keys($inputValue);
        } else {
            $arrayOfArrays = $this->arrayOfArrays;
        }

        $value = $arrayOfArrays ? $inputValue : array($inputValue);
        foreach ($value as $idx => $arrayObj) {
            $validator = new \models\forms\Validator($this->rules, $arrayObj, true);
            if (!$validator->validate()) {
                $errors = $validator->getErrors();
                $this->errorCode = key($errors) . '.' . $idx;
                $this->error = current($errors);
                return false;
            }

            if ($arrayOfArrays) {
                $this->values[$idx] = $validator->getOutData();
            } else {
                $this->values = $validator->getOutData();
            }
        }

        return true;
    }

    public function getSanitized() {
        return $this->values;
    }

    public function getAllowEmpty() {
        return $this->allowEmpty;
    }

    public function setAllowEmpty($allowEmpty) {
        $this->allowEmpty = $allowEmpty;
        return $this;
    }

}
