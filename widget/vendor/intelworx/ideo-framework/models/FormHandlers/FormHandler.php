<?php

interface FormHandler
{
    /**
     * Validates input data and return the status of validation.
     * @param array $data
     * @return bool true if data is valid and false otherwise
     */
    public function validate($data);

    /**
     * Prepares view for form display, usually where you'll want to assign
     * data needed for dropdowns, checkboxes and radio options, as well as
     * some other data needed by your form
     *
     * @param Smarty $view
     * @return void
     */
    public function bootStrap(&$view);

    /**
     *
     * Checks if the handler has errors, usually called after
     * FormHandler::validate() has been called
     *
     * @return bool
     */
    public function hasErrors();

    /**
     * Returns erros that occured during validation
     * @return arrar list of errors, mapping of error code to error message.
     */
    public function getErrors();

    /**
     * Process for data, this should be called only if the data validation was
     * successful.
     *
     * @param array $data form data
     *
     * @return mixed true-ish value to indicate success and false-ish otherwise.
     */
    public function saveData($data = null);
}