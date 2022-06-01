<?php

namespace main\controllers;

class HomeController extends BaseController
{

    public function doDefault()
    {

    }

    public function  doTest()
    {
        $response = $this->webServiceClient->GetCustomerBio([
            'MobileNumber' => 90000
        ]);

        var_dump($response);
        exit;
    }
}