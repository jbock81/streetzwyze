<?php
    $localhost = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "l3n9m1k1_stmerchant_data";
    /*mysql_connect($localhost, $username, $password) or die(mysql_error()); // Connect to database server(localhost) with username and password.
    mysql_select_db($dbname) or die(mysql_error()); // Select database.    */

    // Create connection
    $conn = new mysqli($localhost, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    session_start();

    define('BASEPATH', dirname(__FILE__));

?>