<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractMultiFormHandler
 *
 * @author intelWorX
 */
abstract class AbstractMultiFormHandler extends AbstractFormHandler
{

    //put your code here
    protected $subforms = array();

    public function bootStrap(&$view)
    {
        foreach ($this->subforms as $subform) {
            $this->getSubformHandler($subform)->bootStrap($view);
        }
    }

    public function validate($data)
    {
        $this->validData = array();
        $this->errors = array();
        foreach ($this->subforms as $subform) {
            $handler = $this->getSubformHandler($subform);
            $handler->validate($data);
            $this->errors = $this->errors + $handler->getErrors();
            $this->validData = $this->validData + $handler->getValidData();
        }
        return !$this->hasErrors();
    }

    /**
     *
     * @param string $subform
     * @return AbstractFormHandler
     */
    public function getSubformHandler($subform)
    {
        return FormHandlerFactory::getHandler($subform);
    }

    public function addSubform($subform)
    {
        if (($key = array_search($subform, $this->subforms)) === FALSE) {
            return array_push($this->subforms, $subform);
        } else {
            return $key;
        }
    }

    public function removeSubform($subform)
    {
        if (($key = array_search($subform, $this->subforms)) !== FALSE) {
            unset($this->subforms[$key]);
            return true;
        }
        return FALSE;
    }

}

