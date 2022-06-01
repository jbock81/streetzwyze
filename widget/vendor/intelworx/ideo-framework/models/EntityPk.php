<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntityPk
 *
 * @author intelWorX
 */
class EntityPk extends IdeoObject implements ComparableInterface
{

    const PK_JOINER = '$';

    protected $pk;

    public function __construct($primaryKeys)
    {
        $this->pk = join(self::PK_JOINER, (array)$primaryKeys);
    }

    public function compare(\ComparableInterface $compareTo)
    {
        return strcasecmp($this->pk, $compareTo->pk);
    }

    public function __toString()
    {
        return $this->pk;
    }
}
