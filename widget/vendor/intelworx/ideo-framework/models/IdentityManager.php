<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IdentityManager
 *
 * @author intelWorX
 */
abstract class IdentityManager extends EntityManager
{

    const AUTH_ERROR_INVALID_CREDENTIALS = 'MEM_100';
    const AUTH_ERROR_UNACTIVATED = 'MEM_101';
    /**
     *
     * @var array
     */
    protected $identityOwner;
    //put your code here
    protected $identityErrors = array();

    abstract public function encryptPassword($password);

    abstract public function getStorageNameSpace();

    abstract public function verifyIdentity($identity, $password, $is_encrypted = false, $ignorePassword = false);

    /**
     * @param $owner array of owner data
     * @return Entity
     */
    abstract public function getIdentityOwnerObject(array $owner);

    public function getIdentityErrors()
    {
        return $this->identityErrors;
    }

    public function getIdentityOwner()
    {
        return $this->identityOwner;
    }

    abstract public function getIdentityOwnerEmail();

    abstract public function getIdentity();

    public function resetPassword($id)
    {
        //retained for bc.
    }

    public function loadIdentityOwnerById($id)
    {
        $identityObject = $this->getEntity($id);
        if (!$identityObject) {
            return FALSE;
        }

        return $this->identityOwner = $identityObject->toArray();
    }

    protected function addIdentityError($error, $error_code = NULL)
    {
        if ($error_code === NULL) {
            $this->identityErrors[] = $error;
        } else {
            $this->identityErrors[$error_code] = $error;
        }
        return $this;
    }

    protected function addIdentityErrorByCode($code)
    {
        $this->addIdentityError(Strings::getString($code), $code);
    }


}

