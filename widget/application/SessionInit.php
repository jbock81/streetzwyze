<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SessionInit
 *
 * @author intelWorX
 */
class SessionInit extends IdeoObject {
    //const SESSION_TI
    public static function start() {
        $sysConfig = SystemConfig::getInstance();
        session_name($sysConfig->system['cookie']);
        $rootDomain = $sysConfig['system']['root_domain'];
        if (strstr($_SERVER['HTTP_HOST'], $rootDomain) === $rootDomain) {
            session_set_cookie_params(0, '/', "." . $rootDomain);
        }
        session_start();
    }
}

