<?php

/**
 * Description of GenericFormHandler
 *
 * @author JosephT
 */
abstract class BaseFormHandler extends AbstractFormHandler
{

    public function process($data = array())
    {
        if (!$this->validate($data)) {
            return null;
        }

        return $this->_process($data);
    }


    abstract protected function _process($rawData = array());

    public function saveData($data = null)
    {
        return $this->process($data);
    }

}
