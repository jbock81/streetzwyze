<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 03/11/2015
 * Time: 9:50 PM
 */

namespace models;


interface BankEntry
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getCBNCode();
}