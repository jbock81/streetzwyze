<?php
    include_once ("../../include/dbconnect.php");
    include_once ("../api_class/clsCashVaultAPI.php");

    if (isset($_POST['orderAmount']) && !empty($_POST['orderAmount']))
        $order_amount = $_POST['orderAmount'];
    else
        $order_amount = 0;

    if (isset($_POST['reservationID']) && !empty($_POST['reservationID']))
        $reservation_id = $_POST['reservationID'];
    else
        $reservation_id = "";

    if (isset($_POST['dueDate']) && !empty($_POST['dueDate']))
        $due_date = $_POST['dueDate'];
    else
        $due_date = "";
    
    if (isset($_POST['MId']) && !empty($_POST['MId']))
        $MId = $_POST['MId'];
    else
        $MId = 0;

    $penalty_status = 0;
    $sql = "select * from dashboard_tiles where t_id=1";
    $result = mysqli_query($conn, $sql);
    while($row = $result->fetch_assoc())
    {
        $penalty_status = $row['t_flag'];
    }

    $penalty_fee = 0;
    //Calculate Penalty fee.
    if (floatval($order_amount) <= 50000)
    {
        $penalty_fee = floatval(rand(20, 25) * floatval($order_amount) / 1000);
    }
    if (floatval($order_amount) > 50000 && floatval($order_amount) <= 150000)
    {
        $penalty_fee = floatval(rand(15, 20) * floatval($order_amount) / 1000);
    }
    if (floatval($order_amount) > 150000)
    {
        $penalty_fee = floatval(0.018 * floatval($order_amount));
    }

    $sql = "update merchant set Penaltyfee = " . $penalty_fee . " where MId ='" . $MId . "'";
    $result = mysqli_query($conn, $sql);

    /*$payment_url = "https://paywith.quickteller.com/checkout?paymentCode=04249201&Amount=" . $penalty_fee . "&CustomerId=" . $reservation_id . "&EmailAddress=info@streetzwyze.com&MobileNumber=08028849294&site=cashvaultng.com&redirectUrl=https://streetzwyze.com/admin-seller/penaltyfees.php";
    $contents = file_get_contents($payment_url);*/
    
    $cv=new CashVaultAPI();
    $response = $cv->penalty_deposit($penalty_fee, $reservation_id);

    //$response_code = $response->ResponseCode;
    $ret_data = array("penaltyStatus" => $penalty_status, "orderTag" => $reservation_id, "penaltyFee" => $penalty_fee, "dueDate" => $due_date);

    echo json_encode($ret_data);
?>