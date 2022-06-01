<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of EntityValidator
 *
 * @author intelWorX
 */
class EntityValidator extends ScalarValidator
{

    /**
     *
     * @var \EntityManager
     */
    protected $entityManager;
    protected $field;
    protected $required;
    protected $extraWhere = null;

    /**
     *
     * @var \Entity
     */
    protected $entity;

    public function __construct($entityManager, $field = null, $required = false, $sanitizeFlags = DEFAULT_FILTER_STRING_FIELDS, $errorMsgCode = 'validator.entity_not_found', $errorMsg = null)
    {
        parent::__construct($sanitizeFlags, $errorMsgCode, $errorMsg);
        if (is_string($entityManager) && class_exists($entityManager)) {
            $this->entityManager = call_user_func(array($entityManager, 'instance'));
        } elseif ($entityManager instanceof \EntityManager) {
            $this->entityManager = $entityManager;
        } else {
            throw new \Exception("The specified entity manager is not valid");
        }
        $this->field = $field ?: $this->entityManager->getPrimaryKey()[0];
        $this->required = $required;
    }

    public function setExtraWhere($where)
    {
        $this->extraWhere = $where;
        return $this;
    }

    public function validate($inputValue)
    {
        $inputValue = $this->sanitize($inputValue);
        if (!$this->required && !$inputValue) {
            return true;
        }

        $where = (new \DbTableWhere())
            ->where($this->field, $inputValue);

        if ($this->extraWhere) {
            $where->whereString($this->extraWhere);
        }

        return ($this->entity = $this->entityManager->getEntityWhere($where)) !== null;
    }

    public function getEntity()
    {
        return $this->entity;
    }

}
