<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace main;

/**
 * Description of DefaultBootstrap
 *
 * @author intelWorX
 */
class Bootstrap extends \ApplicationBootstrap {
    //put your code here

    protected function _initStrings(){
        \Strings::setLanguageFile(DEFAULT_CONFIG_DIR . 'en.strings.ini');
    }
}
