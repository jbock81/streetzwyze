<?php
    include_once ("../api_class/clsCashVaultAPI.php");
    include_once ("../../include/dbconnect.php");

    if (isset($_POST['dispatch_mobile']) && !empty($_POST['dispatch_mobile']))
        $dispatch_mobile = $_POST['dispatch_mobile'];

    $cv=new CashVaultAPI();
    $response = $cv->getReservationStatusV2($dispatch_mobile);

    $response = json_decode($response);

    $ret_data = array("reservationID" => "", "orderNum" => 0, "orderAmount" => "", "durationRemaining" => "");

    if ($response->ResponseCode == "00")
    {
        $ret_data["reservationID"] = $response->ReservationStatusV2->ReservationId;
        $ret_data["orderNum"] = $response->ReservationStatusV2->OrderNo;
        $ret_data["orderAmount"] = $response->ReservationStatusV2->OrderAmount;
        $ret_data["durationRemaining"] = $response->ReservationStatusV2->DeliveryDurationRemaining;
    }

    echo json_encode($ret_data);

?>