<?php

namespace models\forms\validationrules;

/**
 * Description of Float
 *
 * @author JosephT
 */
class FloatType extends Integer
{

    public function __construct($min = null, $max = null)
    {
        parent::__construct($min, $max);
        $this->errorCodes['invalid'] = 'validator.not_float';
    }

    public function validate($inputValue)
    {
        $inputValue = $this->sanitized = strlen($inputValue) ? floatval($inputValue) : null;
        return is_null($inputValue) || \Respect\Validation\Validator::create()
            ->floatType()
            ->validate($inputValue)
        && $this->validateMax($inputValue)
        && $this->validateMin($inputValue);
    }

}
