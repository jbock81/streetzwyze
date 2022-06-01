<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\forms\validationrules;

/**
 * Description of UniqueField
 *
 * @author intelWorX
 */
class UniqueField extends ScalarValidator
{

    protected $entityManager;
    protected $isUpdating;
    protected $field;
    protected $currentPk;
    protected $extraWhere;

    /**
     *
     * @param \EntityManager $entityManager
     * @param string $field primary key field
     * @param bool $isUpdating is this an update process
     * @param string|int $currentId primary key of the object being updated
     * @param type $errorMsgCode
     */
    public function __construct(\EntityManager $entityManager, $field, $isUpdating = false, $currentId = null, $errorMsgCode = 'errors.existing', $extraWhere = null)
    {
        parent::__construct(DEFAULT_FILTER_STRING_FIELDS, $errorMsgCode, null);
        $this->isUpdating = $isUpdating;
        $this->field = $field;
        $this->entityManager = $entityManager;
        $this->currentPk = $currentId;
        $this->extraWhere = $extraWhere;
    }

    public function validate($inputValue)
    {
        $value = $this->sanitize($inputValue);
        $where = \DbTableWhere::get()
            ->where($this->field, $value);

        if ($this->extraWhere) {
            $where->whereString($this->extraWhere);
        }

        $existing = $this->entityManager->getEntityWhere($where);
        if ($existing) {
            if (!$this->isUpdating ||
                strcasecmp((string)$existing->getPrimaryKey(), (string)$this->currentPk)
            ) {
                return false;
            }
        }

        return true;
    }

    public function setExtraWhere($extraWhere)
    {
        $this->extraWhere = $extraWhere;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getIsUpdating()
    {
        return $this->isUpdating;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getCurrentPk()
    {
        return $this->currentPk;
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function setIsUpdating($isUpdating)
    {
        $this->isUpdating = $isUpdating;
        return $this;
    }

    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    public function setCurrentPk($currentPk)
    {
        $this->currentPk = $currentPk;
        return $this;
    }

}
