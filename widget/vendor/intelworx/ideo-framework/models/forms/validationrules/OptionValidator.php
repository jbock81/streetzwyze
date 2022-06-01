<?php

namespace models\forms\validationrules;
use models\forms\ValidationRule;

/**
 * Description of OptionValidator
 *
 * @author JosephT
 */
class OptionValidator extends ValidationRule
{

    protected $options;
    protected $strict;
    protected $minSelection;
    protected $maxSelection;

    public function __construct($options, $strict = false, $minSel = 0, $maxSel = 0, $useKeys = true)
    {
        $this->options = $useKeys ? array_keys($options) : $options;
        $this->strict = $strict;
        $this->minSelection = $minSel;
        $this->maxSelection = $maxSel;
    }

    public function validate($inputValue)
    {
        if (!is_array($inputValue)) {
            $inputValue = array($inputValue);
        }

        if ($this->minSelection > 0 && count($inputValue) < $this->minSelection) {
            $this->setErrorbyCode('validator.option_min_selection');
            return FALSE;
        }

        if ($this->maxSelection > 0 && count($inputValue) > $this->maxSelection) {
            $this->setErrorbyCode('validator.option_max_selection');
            return FALSE;
        }

        if (!empty($inputValue)) {
            return $this->checkOption($inputValue);
        }
        return true;
    }

    public function checkOption($inputValue)
    {
        if (is_array($inputValue)) {
            $valid = true;
            foreach ($inputValue as $val) {
                $valid = $valid && $this->checkOption($val);
            }
        } else {
            $valid = in_array($inputValue, $this->options, $this->strict);
        }

        if (!$valid) {
            $this->setErrorbyCode('validator.option_invalid');
        }

        return $valid;
    }

}
