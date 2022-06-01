<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of Date
 *
 * @author JosephT
 */
class Date extends \models\forms\ValidationRule
{

    protected $min;
    protected $max;
    protected $format;

    function __construct($min = null, $max = null, $format = "d/m/Y")
    {
        $this->min = $min;
        $this->max = $max;
        $this->format = $format;
    }

    public function validate($inputValue)
    {
        if ($inputValue) {
            $valid = \Respect\Validation\Validator::create()
                ->date($this->format)
                ->validate($inputValue);

            if (!$valid) {
                $this->setErrorbyCode('validator.date_format');
            }

            if ($valid && $this->min) {
                $valid = $this->dateToTimestamp($inputValue) >= $this->dateToTimestamp($this->min);
                if (!$valid) {
                    $this->setErrorbyCode('validator.date_min');
                }
            }

            if ($valid && $this->max) {
                $valid = $this->dateToTimestamp($inputValue) <= $this->dateToTimestamp($this->max);
                if (!$valid) {
                    $this->setErrorbyCode('validator.date_max');
                }
            }
            return $valid;
        }
        return true;
    }

    private function dateToTimestamp($val)
    {
        return \GeneralUtils::dateToTimestamp($val, $this->format);
    }

}
