<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Strings::setLanguageFile(__DIR__ . '/error_codes.ini');
if (!Application::currentInstance()->isProd()) {
    //testing configuration
    $envConfig = [
        'paymentCode' => '99401',
        'baseUrl' => 'https://stageserv.interswitchng.com/quicktellercheckout/',
        'clientId' => 'cashvaultng.com',//explode(':', $_SERVER['HTTP_HOST'])[0],
        'secretKey' => 'E9300DJLXKJLQJ2993N1190023',
    ];
} else {
    $envConfig = [
        'paymentCode' => '04249201',
        'baseUrl' => 'https://paywith.quickteller.com/',
        'clientId' => 'cashvaultng.com',
        'secretKey' => 'B890wMY43BUYJSUD285WF789UYT',
    ];
}


return array_merge($envConfig, [
    'name' => 'Quickteller',
    'supportEmail' => 'info@cashvaultng.com',
	'Customermobile' => '07472804346'
]);
