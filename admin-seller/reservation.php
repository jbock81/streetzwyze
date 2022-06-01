<?php

    include_once ("../include/dbconnect.php");
    include_once ("./api_class/config.php");
    include_once ("./api_class/clsCashVaultAPI.php");

    function makeReservationId($mId)
    {
        $nMId = 100000 + (int)$mId;
        $str_Mid = sprintf("%d", $nMId);
        $str_Mid = "A" . substr($str_Mid, 1) . time();
        return $str_Mid;
    }

    if (isset($_POST['reservation_date']) && !empty($_POST['reservation_date']))
        $reservation_date = mysqli_real_escape_string($conn, $_POST['reservation_date']);

    if (isset($_POST['delivery_duration']) && !empty($_POST['delivery_duration']))
        $delivery_duration = mysqli_real_escape_string($conn, $_POST['delivery_duration']);

    if (isset($_POST['order_amount']) && !empty($_POST['order_amount']))
        $order_amount = mysqli_real_escape_string($conn, $_POST['order_amount']);

    if (isset($_POST['reserve_tag']) && !empty($_POST['reserve_tag']))
        $reserve_tag = mysqli_real_escape_string($conn, $_POST['reserve_tag']);

    if (isset($_POST['customer_phone']) && !empty($_POST['customer_phone']))
        $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
    
    /*$arr_date = explode(".", $reservation_date);
    $reservation_date = $arr_date[2] . '-' . $arr_date[1] . '-' . $arr_date[0];
    $reservation_date = $reservation_date . " " . date("H:i:s");*/

    $sql = "select * from merchant where MId = '" . $_SESSION['MId'] . "'";
    $result = mysqli_query($conn,$sql);
    while($row = $result->fetch_assoc()) 
    {
        $email_addr = $row['Emailaddress'];
        $mobile_num = $row['Mobilenumber'];
        $business_name = $row['Businessname'];
        $refund_delivery = 0;
        $delivery_fee = 0;
        $reservationId = FIXEDMERCHANTID . time();
        $account_num = ltrim(substr($row['PaymentTag'], 0, 10));
        $cbn_code = rtrim(substr($row['PaymentTag'], 10));
        $split_percent = 100;

        $cv=new CashVaultAPI();
		$response = $cv->reservation(
            $email_addr,
            $mobile_num,
            $business_name,
            $refund_delivery,
            $delivery_fee,
            $delivery_duration,
            $customer_phone,
            $reservationId,
            $order_amount,
            $reserve_tag,
            $reservation_date,
            $account_num,
            $cbn_code,
            100
        );

        $response=json_decode($response);

        $response_code = $response->ResponseCode;	
        $payment_url = $response->PaymentUrl;
        
        if ($response_code == "00")
            $ordstatus = "Success";
        else
            $ordstatus = "";

        $sql2 = "select * from orders where MId = '" . $_SESSION['MId'] . "' and Reservedate = '" . $reservation_date . "'";
        $result2 = mysqli_query($conn,$sql2);
        if (mysqli_num_rows($result2) > 0)
        {
            while($row2 = $result->fetch_assoc())
            {
                $OId = $row2['OId'];
                $sql3 = "update orders set ReservationTag = '" . $reserve_tag . "', Orderamount = '" . $order_amount . "',OrdStatus = '" . $ordstatus . "', Reservedate = '" . $reservation_date . "', DeliveryStatus='Success', DisburseStatus='Processing' where OId='" . $OId . "'";
                $result3 = mysqli_query($conn,$sql3);
            }
        }
        else
        {
            $sql3 = "insert into `orders`(`MId`,`Reservationid`,`ReservationTag`,`Orderamount`,`Ordstatus`,`Reservedate`,`DeliveryStatus`,`DisburseStatus`,`Payoutdate`) values ('";
            $sql3 .= $_SESSION["MId"] . "','" . $reservationId . "','" . $reserve_tag . "','" . $order_amount . "','" . $ordstatus . "','" . $reservation_date . "','Success','Processing','')";
            $result3 = mysqli_query($conn, $sql3);

        }
        header("Location: ../orders.php");
    }



?>