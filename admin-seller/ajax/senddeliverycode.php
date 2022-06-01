<?php
    include_once ("../api_class/clsCashVaultAPI.php");
    include_once ("../../include/dbconnect.php");

    if (isset($_POST['customerCode']) && !empty($_POST['customerCode']))
        $customer_code = $_POST['customerCode'];

    if (isset($_POST['reservationID']) && !empty($_POST['reservationID']))
        $reservation_id = $_POST['reservationID'];

    $cv=new CashVaultAPI();
    $response = $cv->sendVaultCode($customer_code, $reservation_id);

    $response = json_decode($response);

    $ret_data = "";

    if ($response->ResponseCode == "00")
    {
        $ret_data = "You can do a lot more if you signed up, learn more";
    }

    echo $ret_data;

?>