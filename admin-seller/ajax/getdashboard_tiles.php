<?php
    include_once ("../../include/dbconnect.php");
    include_once ("../api_class/clsCashVaultAPI.php");

    $flags = array('dh0' => 0, 'dh1' => 0, 'dh2' => 0, 'dh3' => 0);
    $imgs = array('dh0' => 'Penalty-fee-idle.png','dh1' => 'Secured-Fund-idle.png','dh2' => 'Successful-Delivery-idle.png','dh3' => 'remitttance-idle.png');
    $values = array('dh0' => 0, 'dh1' => 0, 'dh2' => 0, 'dh3' => 0);

    $MId = $_SESSION["MId"];
    $OId = 0;
    $full_name = "";
    $delivery_status = "";
    $ReservationID = "";
    $order_amount = 0;
    $due_date = "";

    $sql = "select * from merchant where MId='" . $_SESSION["MId"] . "'";
    $result = mysqli_query($conn,$sql);
    while ($row = $result->fetch_assoc())
    {
        $full_name = $row['Firstname'] . " " . $row['Lastname'];
    }

    $sql = "select * from orders where MId='" . $_SESSION["MId"] . "' order by Reservedate desc limit 1";
    $result = mysqli_query($conn,$sql);

    
    if (mysqli_num_rows($result) > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $OId = $row['OId'];
            $ReservationID = $row['Reservationid'];   
            $delivery_status = $row['DeliveryStatus'];
            $order_amount = $row['Orderamount'];
        }
    }

    //Dashboard Tile 1

    if ($delivery_status == "Success")
    {
        
    }
    else
    {
        
    }

    $cv=new CashVaultAPI();
    $response = $cv->dashboard_tile($ReservationID);
    $response=json_decode($response);
    $due_date = $response->DeliveryTimeElapse;

    if ($response->DeliveryCodeStatus == "0" && $response->DurationRemaining == "00.00.00")
    {
        $imgs['dh0'] = "Penalty-fee-active.png";
        $flags['dh0'] = 1;
        $sql = "update dashboard_tiles set t_flag = 1, t_imgname='Penalty-fee-active.png' where id=1";
        $result = mysqli_query($conn,$sql);

        $sql = "update orders set DeliveryStatus='Failed' where OId='" . $OId . "'";
        $result = mysqli_query($conn, $sql);

        $penalty_fee = 0;
        //Calculate Penalty fee.
        if (floatval($order_amount) <= 50000)
        {
            $penalty_fee = round(rand(20, 25) * floatval($order_amount) / 1000);
        }
        if (floatval($order_amount) > 50000 && floatval($order_amount) <= 150000)
        {
            $penalty_fee = round(rand(15, 20) * floatval($order_amount) / 1000);
        }
        if (floatval($order_amount) > 150000)
        {
            $penalty_fee = round(0.018 * floatval($order_amount));
        }

        $sql = "update dashboard_tiles set t_value = " . $penalty_fee . " where t_id=1";
        $result = mysqli_query($conn, $sql);

        $sql = "update merchant set Penaltyfee = '" . $penalty_fee . "' where MId = '" . $MId . "'";
        $result = mysqli_query($conn, $sql);
    }
    else
    {
        $sql = "update dashboard_tiles set t_flag = 0, t_imgname='Penalty-fee-idle.png' where id=1";
        $result = mysqli_query($conn,$sql);
    }
    
    $sql = "select * from dashboard_tiles where t_id=1";
    $result = mysqli_query($conn, $sql);
    while($row = $result->fetch_assoc())
    {
        $values["dh0"] = floatval($row['t_value']);
    }
    

    $sql = "update dashboard_tiles set t_value = " . floatval($response->MerchantOrderAmount) . " where t_id=2";
    $result = mysqli_query($conn, $sql);

    $sql = "update dashboard_tiles set t_value = " . $order_amount . " where t_id=3";
    $result = mysqli_query($conn, $sql);

    $values["dh1"] = floatval($response->MerchantOrderAmount);
    $values["dh2"] = $order_amount;

    $sql = "select * from dashboard_tiles where t_id=4";
    $result = mysqli_query($conn, $sql);
    while($row = $result->fetch_assoc())
    {
        $values["dh3"] = floatval($row['t_value']);
    }

    //Dashboard Tile 2

    if ($response->FundSecured == "00" && $response->DurationRemaining != "00.00.00")
    {
        $imgs['dh1'] = "Secured-Fund-active.png";
        $flags['dh1'] = 1;
        $sql = "update dashboard_tiles set t_flag = 1, t_imgname='Secured-Fund-active.png' where id=2";
        $result = mysqli_query($conn,$sql);
        $sql = "update orders set OrdStatus = 'Secured' where OId='" . $OId . "'";
        $result = mysqli_query($conn, $sql);
    }
    else
    {
        $sql = "update dashboard_tiles set t_flag = 0, t_imgname='Secured-Fund-idle.png' where id=2";
        $result = mysqli_query($conn,$sql);
    }
    
    //Dashboard Tile 3

    if ($response->FundSecured == "00" && $response->DeliveryCodeStatus == "1")
    {
        $imgs['dh2'] = "Successful-Delivery-active.png";
        $flags['dh2'] = 1;
        $sql = "update dashboard_tiles set t_flag = 1, t_imgname='Successful-Delivery-active.png' where id=3";
        $result = mysqli_query($conn,$sql);
        $sql = "update orders set DeliveryStatus = 'Success' where OId='" . $OId . "'";
        $result2 = mysqli_query($conn, $sql);
    }
    else
    {
        $sql = "update dashboard_tiles set t_flag = 0, t_imgname='Successful-Delivery-idle.png' where id=3";
        $result = mysqli_query($conn,$sql);
    }

    //Dashboard Tile 4

    if ($delivery_status == 'Failed')
    {
        
    }
    if ($delivery_status == "Success")
    {
        $imgs['dh3'] = "remittance-active.png";
        $flags['dh3'] = 1;
        $sql = "update dashboard_tiles set t_flag = 1, t_imgname='remittance-active.png' where id=4";
        $result = mysqli_query($conn,$sql);
        $sql2 = "update orders set DisburseStatus = 'Success', Payoutdate = '" . date("Y-m-d H:i:s") . "' where OId='" . $OId . "'";
        $result2 = mysqli_query($conn, $sql2);
    }
    else
    {
        $sql = "update dashboard_tiles set t_flag = 0, t_imgname='remittance-idle.png' where id=4";
        $result = mysqli_query($conn,$sql);
    }

    $ret_data = array('flags' => $flags, 'imgs' => $imgs, 'values' => $values, 'reservationID' => $ReservationID, 'orderAmount' => $order_amount, 'dueDate' => $due_date, 'MId' => $MId);
    echo json_encode($ret_data);
?>