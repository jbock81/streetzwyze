<?php
    include_once ("../../include/dbconnect.php");
    include_once ("./api_class/config.php");
    include_once ("../api_class/clsCashVaultAPI.php");

    if (isset($_POST['account_num']) && !empty($_POST['account_num']))
        $account_num = mysqli_real_escape_string($conn, $_POST['account_num']);

    if (isset($_POST['bank_code']) && !empty($_POST['bank_code']))
        $cbn_code = mysqli_real_escape_string($conn, $_POST['bank_code']);

    if (isset($_POST['OId']) && !empty($_POST['OId']))
        $OId = mysqli_real_escape_string($conn, $_POST['OId']);

    $sql = "select * from orders where OId ='" . $OId . "'";
    $result = mysqli_query($conn,$sql);
    
    $MId = FIXEDMERCHANTID;
    $order_amount = 0;
    $reservation_tag = "";
    
    while ($row = $result->fetch_assoc())
    {
        $MId = ltrim(substr($row['Reservationid'], 0, 6));
        $order_amount = $row['Orderamount'];
        $reservation_tag = $row['ReservationTag'];
    }

    /*$cv=new CashVaultAPI();
    $response = $cv->release_fund(
        $MId, 
        $order_amount, 
        $account_num, 
        $cbn_code, 
        $reservation_tag
    );
    
    $payout_amount = $response->PayoutAmount;*/
    $payout_amount = 200;

    $sql = "update orders set Retry5 = 1 where OId='" . $OId . "'";
    $result = mysqli_query($conn,$sql);

    $penalty_fee = 0;
    $sql = "select * from dashboard_tiles where t_id=1";
    $result = mysqli_query($conn,$sql);
    while ($row = $result->fetch_assoc())
    {
        $penalty_fee = $row['t_value'];
    }

    $val_dh3 = floatval($payout_amount) - floatval($penalty_fee);
    $sql = "update dashboard_tiles set t_value=" . $val_dh3 . " where t_id=4";
    $result = mysqli_query($conn,$sql);
    
    echo $val_dh3;
    
?>