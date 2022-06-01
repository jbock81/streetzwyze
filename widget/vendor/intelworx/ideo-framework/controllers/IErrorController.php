<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IErrorController
 *
 * @author intelWorX
 */
interface IErrorController
{

    public function doError404();

    public function doError500();
}
