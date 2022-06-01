<?php

/**
 * Description of MultivaluedAttributeEntityManager
 *
 * The use of this class is discouraged, it is left here for legacy purpose
 * This class was created back when an entity could only be owned by one parent entity.
 * @deprecated since version 1.0.1
 * @author intelWorX
 */
abstract class MultivaluedAttributeEntityManager extends EntityManager
{

    protected $parentObjectReference = array();

    //put your code here
    abstract public function getParentReferenceColumn();

    public function getParentReference($parentEntityClass = null)
    {
        if (!is_null($parentEntityClass)) {
            return parent::getParentReference($parentEntityClass);
        }
        return $this->getParentReferenceColumn();
    }

}
