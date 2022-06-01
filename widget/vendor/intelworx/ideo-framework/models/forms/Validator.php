<?php

namespace models\forms;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validatable;

/**
 * Description of Validator
 *
 * @author intelWorX
 */
class Validator extends \IdeoObject {

    /**
     * Rules with this Key will the applied to the entire data set as input
     */
    const GLOBAL_RULE = '__global__';

    /**
     * No global rule should be run at a priority lower than this
     */
    const GLOBAL_RULE_PRIORITY_MIN = 100;

    protected $inputData = array();
    protected $rules = array();
    protected $outputData = array();
    protected $breakOnError;
    protected $errors = array();

    public function __construct(array $rules, array $data, $breakOnError = true) {
        $this->inputData = $data;
        $this->breakOnError = $breakOnError;
        $this->rules = $this->groupByPriority($rules);
    }

    private function groupByPriority(array $rules) {
        $rulesByPriority = array();
        foreach ($rules as $field => $validators) {
            $validators = is_array($validators) ? $validators : array($validators);
            $isGlobalRule = $field === self::GLOBAL_RULE;
            foreach ($validators as $priority => $validator) {
                if ($validator === null || !is_object($validator)) {
                    throw new ValidationRuleException("One of the rules specified for field [{$field}] at priority ({$priority}) is NULL");
                }
                //ensures that for a global rule, it is executed at a mininum of
                //rule set at GLOBAL_RULE_PRIORITY_MIN
                $priority = $isGlobalRule && $priority < self::GLOBAL_RULE_PRIORITY_MIN ? self::GLOBAL_RULE_PRIORITY_MIN : $priority;
                if (!array_key_exists($priority, $rulesByPriority)) {
                    $rulesByPriority[$priority] = [];
                }
                $rulesByPriority[$priority][$field] = $validator;
            }
        }

        ksort($rulesByPriority);
        return $rulesByPriority;
    }

    public function validate() {
        $this->outputData = $this->inputData;
        $this->errors = [];
        //validate
        foreach ($this->rules as $priority => $rules) {
            \SystemLogger::debug("Processing rules with priority,", $priority);
            foreach ($rules as $field => $rule) {
                $isGlobalRule = $field === self::GLOBAL_RULE;
                if ($isGlobalRule) {
                    //for global rules, pass the whole data set.
                    $inValue = $this->outputData;
                } else {
                    $inValue = array_key_exists($field, $this->outputData) ? $this->outputData[$field] : null;
                }

                $outValue = $errorCode = $error = null;

                if (ValidationRule::aClassOf($rule)) {
                    $valid = $this->validateWithRule($rule, $inValue, $outValue, $error, $errorCode);
                } elseif (is_a($rule, '\Respect\Validation\Validatable')) {
                    $valid = $this->validateWithRespect($rule, $inValue, $outValue, $error, $errorCode);
                } else {
                    throw new ValidationRuleException("Invalid validation rule, class of: " . get_class($rule) . " for field: [{$field}] at priority: [{$priority}]");
                }

                if ($valid) {
                    //since global rules use evert data, simply merge output into existing data.
                    if ($isGlobalRule) {
                        $this->outputData = array_merge($this->outputData, (array) $outValue);
                    } else {
                        $this->outputData[$field] = $outValue;
                    }
                } else {
                    $this->errors["{$field}.{$errorCode}"] = $error;
                    \SystemLogger::warn("Validator failed : [", get_class($rule), "] for ", $field, "reason: {$error}");
                    if ($this->breakOnError) {
                        return false;
                    }
                }
            }
        }

        return count($this->errors) < 1;
    }

    protected function validateWithRespect(Validatable $validatable, $inValue, &$outValue, &$error, &$errorCode = null) {
        try {
            $validatable->check($inValue);
            $outValue = $inValue;
            return true;
        } catch (ValidationException $exception) {
            $error = $exception->getMainMessage();
            $tmp = explode('\\', get_class($validatable));
            $errorCode = $exception->getCode() ? : array_pop($tmp);
            return false;
        }
    }

    protected function validateWithRule(ValidationRule $rule, $inValue, &$outValue, &$error, &$errorCode = null) {
        if ($rule->validate($inValue)) {
            $outValue = $rule->getSanitized() === false ? $inValue : $rule->getSanitized();
            return true;
        } else {
            $error = $rule->getError();
            $errorCode = $rule->getErrorCode() ? : $rule->getClassBasic();
            return false;
        }
    }

    public function getErrors() {
        return $this->errors;
    }

    public function clearErrors() {
        $this->errors = array();
        return $this;
    }

    public function getOutData() {
        return $this->outputData;
    }

    public function getRules() {
        return $this->rules;
    }

}
