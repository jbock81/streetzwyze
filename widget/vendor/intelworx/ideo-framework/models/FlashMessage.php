<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author intelWorX
 */
class FlashMessage extends IdeoObject
{

//put your code here
    public static function create($msg)
    {
        $id = time();
        $_SESSION['FLASH'][$id] = $msg;
        return $id;
    }

    public static function createFromCode($msg, $data = array())
    {
        return self::create(Strings::get($msg, $data));
    }

    public static function getMessage($id)
    {
        $msg = $_SESSION['FLASH'][$id];
        unset($_SESSION['FLASH'][$id]);
        return $msg;
    }

}
