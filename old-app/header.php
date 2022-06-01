<?php

/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */

session_start();

$token = md5(rand(1000, 9999)); //you can use any encryption

$_SESSION['token'] = $token; //store it as session variable

define('BASEPATH', dirname(__FILE__));

//include_once BASEPATH . '/library/config.php';

$key_verify = hash_hmac('md5', SALT, $token);

?>

<!DOCTYPE html>

<html>

    <head>

        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon"/>
        <meta http-equiv="X-UA-Compatible" content="IE=9">    
        
        <!-- SEO -->
        <meta name="referrer" content="always">
    	<meta name="robots" content="noindex">
   	    <meta name="keywords" content="Streetzwyze, secure payment, buyer proctection, seller proctection, secure exchange, payment on delivery, cash on delivery">
   	    <title>Streetzwyze | Payment on delivery redefined</title>
    	<meta name="description" content="Payment on delivery reinvented to support purchases across social media, classifieds or retail outlets ">
    	<meta name="generator" content="SEOmatic">
    	<meta name="geo.region" content="Nigeria">
    	<meta name="geo.placename" content="Streetzwyze">
    	<script type="application/ld+json" src="js/stseo.JSON"></script>
    	<!-- SEO -->

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <link rel="stylesheet" href="css/bootstrap.min.css">

        <link rel="stylesheet" href="css/main.css">

        <link rel="stylesheet" href="css/slick.css">

        <link rel="stylesheet" href="css/custom.css">

        <!-- REVOLUTION STYLE SHEETS -->

        <link rel="stylesheet" type="text/css" href="css/settings.css">

        <!-- REVOLUTION LAYERS STYLES -->

        <link rel="stylesheet" type="text/css" href="css/layers.css">

        <!-- REVOLUTION NAVIGATION STYLES -->

        <link rel="stylesheet" type="text/css" href="css/navigation.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.toast.min.css">

        <!-- LOAD JQUERY LIBRARY -->

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>

        <!-- LOADING FONTS AND ICONS -->

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400" rel="stylesheet">

        <script type="text/javascript" src="js/jquery.themepunch.tools.min.js"></script>

        <script type="text/javascript" src="js/jquery.themepunch.revolution.min.js"></script>

        <script type="text/javascript" src="js/jquery.blockUI.js"></script>

        <script type="text/javascript" src="js/revolution.extension.actions.min.js"></script>

        <script type="text/javascript" src="js/revolution.extension.carousel.min.js"></script>

        <script type="text/javascript" src="js/revolution.extension.kenburn.min.js"></script>

	    <script type="text/javascript" src="js/revolution.extension.layeranimation.min.js"></script>
	   
	    <script type="text/javascript" src="js/revolution.extension.navigation.min.js"></script>

    	<script type="text/javascript" src="js/revolution.extension.parallax.min.js"></script>

	    <script type="text/javascript" src="js/revolution.extension.slideanims.min.js"></script>

        <script type="text/javascript" src="js/clipboard.min.js"></script>
        
        <script type="text/javascript" src="js/jquery.toast.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js" type="text/javascript" charset="utf-8"></script>
        
        <script type="text/javascript" src="js/main.js"></script>

        <style type="text/css">

        	#logoslider.row {

        		background-color: #fff;

        		background-color: #fff;

        		padding: 2% 0;

                        margin: 0;

        	}

        </style>

    </head>

    <body>

        <!-- <header class="navbar navbar-default navbar-fixed-top"> -->

        <header class="navbar navbar-default navbar-fixed-top">

            <div class="container">

                <div class="navbar-header">

                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">

                        <span class="sr-only">Toggle navigation</span>

                        <span class="icon-bar"></span>

                        <span class="icon-bar"></span>

                        <span class="icon-bar"></span>

                    </button>

                    <h1  id="logo">

                        <a href="/">

                        </a>

                    </h1>

                </div>

                <div id="navbar" class="navbar-collapse collapse">

                    <ul class="nav navbar-nav navbar-right">
                        
                        <li><a href="create-reservation.php">BLOG</a>
                        </li>
                        
                        <li><a href="faq.php">FAQs</a></li>

                        <li><a href="seller-payment-on-delivery-method.php">SELLER</a>
                        </li>

                        <li><a href=" ">SIGN IN</a>
                        </li>

                    </ul>

                </div>

            </div>

        </header>