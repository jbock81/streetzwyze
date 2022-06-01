<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Authenticator
 *
 * @author intelWorX
 */
abstract class Authenticator extends IdeoObject
{
    //put your code here

    /**
     * @var IdentityManager
     */
    protected $identityManager;

    const STOTAGE_IDENTITY_OWNER = 'owner';

    protected $identity;
    protected $password;
    protected $ignore_password = false;
    protected $password_encrypted;
    protected $errors;

    const AUTH_EMPTY_CREDENTIALS = 'auth.empty';

    /**
     *
     * @var array
     */
    protected $verifiedOwner;
    protected static $storage;
    protected $is_authenticated = false;

    public function __construct($identity = null, $password = null, $is_encrypted = FALSE)
    {
        $this->identity = $identity;
        $this->password = $password;
        $this->password_encrypted = $is_encrypted;
        $this->setIdentityManager();
        if (!($this->identityManager instanceof IdentityManager)) {
            throwException(new AuthenticatorInvalidIdenityManagerException('Invalid ID Manager, instance of ' . get_class($this->identityManager) . ' was set, please use an instance of IdentityManager'));
        }
    }

    /**
     * @return Session
     */
    public static function getStorage($namespace = Session::DEFAULT_NAMESPACE)
    {
        if (!self::$storage) {
            self::$storage = Session::getInstance();
        }
        self::$storage->setNamespace($namespace);
        return self::$storage;
    }

    public function setIgnorePassword($bool = TRUE)
    {
        $this->ignore_password = $bool;
        return $this;
    }

    abstract public function setIdentityManager();

    /**
     *
     * @param type $identity
     * @return \Authenticator
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     *
     * @param type $password
     * @param type $is_encrypted
     * @return \Authenticator
     */
    public function setPassword($password, $is_encrypted = FALSE)
    {
        $this->password = $password;
        $this->password_encrypted = $is_encrypted;
        return $this;
    }

    public function authenticate($store = true)
    {
        if (!$this->identity || (!$this->password && !$this->ignore_password)) {
            $this->errors[self::AUTH_EMPTY_CREDENTIALS] = Strings::getString(self::AUTH_EMPTY_CREDENTIALS);
            return false;
        }

        if ($this->identityManager->verifyIdentity($this->identity, $this->password, $this->isPasswordEncrypted(), $this->ignore_password)) {
            $this->verifiedOwner = $this->identityManager->getIdentityOwner();
            if ($store) {
                $this->storeVerifiedIdentity();
            }
            return true;
        } else {
            $this->errors = $this->identityManager->getIdentityErrors();
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function getVerifiedOwner()
    {
        return $this->verifiedOwner;
    }

    /**
     *
     * @return Entity
     */
    public function getVerifiedOwnerObject()
    {
        return $this->identityManager->getIdentityOwnerObject($this->verifiedOwner);
    }

    public function isPasswordEncrypted()
    {
        return $this->password_encrypted;
    }

    public function storeVerifiedIdentity()
    {
        self::getStorage($this->identityManager->getStorageNameSpace())
            ->set(self::STOTAGE_IDENTITY_OWNER, $this->verifiedOwner);
    }

    public function &getStoredIdentity()
    {
        return self::getStorage($this->identityManager->getStorageNameSpace())
            ->get(self::STOTAGE_IDENTITY_OWNER);
    }

    //public static function updateStoredIden

    /**
     *
     * @return IdentityManager
     */
    public function getIdentityManager()
    {
        return $this->identityManager;
    }

    public function isAuthenticated()
    {
        $stored = $this->getStoredIdentity();
        return !empty($stored);
    }

    public function clearIdentity()
    {
        self::getStorage()->setNamespace($this->identityManager->getStorageNameSpace())
            ->clearNameSpace();
    }

    public function updateStoredIdentity($data)
    {
        $currentIdentity = $this->getStoredIdentity();
        $newIdentity = $data + $currentIdentity;
        self::getStorage($this->identityManager->getStorageNameSpace())
            ->set(self::STOTAGE_IDENTITY_OWNER, $newIdentity);
    }

    public function reloadIdentity()
    {
        $identity = $this->getStoredIdentity();
        if (empty($identity)) {
            return null;
        }
        $id_fields = $this->getIdentityManager()->getPrimaryKey();
        $owner = $this->getIdentityManager()->getEntity($identity[$id_fields[0]], $id_fields[0], null, true);
        if (!empty($owner)) {
            $this->verifiedOwner = $owner->toArray();
        }
        return $this->storeVerifiedIdentity();
    }

    abstract public function updatePassword($password, $encrypted = false);

    public function encryptPassword($password)
    {
        return $this->identityManager->encryptPassword($password);
    }

    /**
     *
     * @return Entity
     */
    public function getStoredIdentityAsEntity()
    {
        $identity = $this->getStoredIdentity();
        return !empty($identity) ? $this->identityManager->createEntity($identity) : null;
    }

}

class AuthenticatorInvalidIdenityManagerException extends Exception
{

}
