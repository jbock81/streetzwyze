<?php

use models\forms\validationrules\JustSanitized;
use models\forms\validationrules\Required;
use models\forms\Validator;

/**
 * Description of AbstractFormHandler
 *
 * @author intelWorX
 */
abstract class AbstractFormHandler extends IdeoObject implements FormHandler {

    protected $validData = array();
    //put your code here
    protected $errors = array();
    protected $is_updating = false;
    protected $current_object_id;
    protected $synthesizeFields = true;
    protected $rules = array();
    protected $breakValidateOnError = true;
    private $rulesSet = false;

    /**
     *
     * @var JustSanitized
     */
    protected $justSanitized;

    /**
     *
     * @var Required
     */
    protected $required;

    public function __construct($object_id = null, $is_updating = false) {
        $this->is_updating = $is_updating;
        $this->current_object_id = $object_id;
        $this->justSanitized = new JustSanitized();
        $this->required = new Required(DEFAULT_FILTER_STRING_FIELDS);
    }

    public function setCurrentObjectId($id) {
        $this->current_object_id = $id;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return count($this->errors) > 0;
    }

    public function getValidData() {
        return $this->validData;
    }

    protected function addError($error, $error_code = NULL) {
        if ($error_code === NULL) {
            $this->errors[] = $error;
        } else {
            $this->errors[$error_code] = $error;
        }
        return false;
    }

    protected function addErrorByCode($code, array $vars = []) {
        $this->addError(Strings::get($code, $vars), $code);
        return false;
    }

    public function setIsUpdating($set = TRUE) {
        $this->is_updating = $set;
    }

    public function clearErrors() {
        $this->errors = array();
        return $this;
    }

    public function addErrors(array $errors) {
        $this->errors = array_merge($this->errors, $errors);
        return false;
    }

    public function setFieldRules(array $rules, $field = null) {
        if ($field === null) {
            $this->rules = array_merge($this->rules, $rules);
        } else {
            $this->rules[$field] = $rules;
        }

        return $this;
    }

    public function validate($data, Validator &$validator = null, $remove = array()) {
        if (!$this->rulesSet && empty($this->rules)) {
            $this->setRules();
            $this->rulesSet = true;
        }

        $validator = $validator ? : new Validator($this->rules, $data, $this->breakValidateOnError);

        if (!$validator->validate()) {
            $this->errors = $validator->getErrors();
            return false;
        }

        $this->validData = array_merge($data, $validator->getOutData());
        foreach ($remove as $fld) {
            unset($this->validData[$fld]);
        }
        return true;
    }

    public function setRules() {

    }

    public function process($data = array()) {
        return $this->saveData($data);
    }

}
