<?php
    include_once ("../api_class/clsCashVaultAPI.php");
    include ("../../include/dbconnect.php");

    $dd_from = "";
    $dd_to = "";
    if (isset($_POST['dd_from']) && !empty($_POST['dd_from']))
    {
        $dd_from = $_POST['dd_from'];
        $arr_date_from = explode("/", $dd_from);
        $dd_from = $arr_date_from[2] . "-" . $arr_date_from[0] . "-" . $arr_date_from[1];
    }
    
    if (isset($_POST['dd_to']) && !empty($_POST['dd_to']))
    {
        $dd_to = $_POST['dd_to'];
        $arr_date_to = explode("/", $dd_to);
        $dd_to = $arr_date_to[2] . "-" . $arr_date_to[0] . "-" . $arr_date_to[1];
    }

    $sql = "select * from orders where MId='" . $_SESSION["MId"] . "' and MID(Reservedate,1,10)>='" . $dd_from . "' and MID(Reservedate,1,10)<='" . $dd_to . "' and DeliveryStatus='Success'";
    $result = mysqli_query($conn,$sql);

    $ret_data = array(array());
    $k = 0;

    
    if (mysqli_num_rows($result) > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $ret_data[$k]["OId"] = $row['OId'];
            $ret_data[$k]["ReservationID"] = $row['Reservationid'];
            $ret_data[$k]["OrderTag"] = $row['ReservationTag'];
            $ret_data[$k]["Amount"] = $row['Orderamount'];
            $ret_data[$k]["Reservedate"] = substr($row['Reservedate'],0,10);
            //$retry_cnt = $row['Retry5'];
            $status = $row['DisburseStatus'];
            
            $ret_data[$k]["Status"] = $status;
            $k++;
        }
    }
    
    if (count($ret_data[0]) > 0)
    {
        $cv=new CashVaultAPI();

        foreach ($ret_data as $item)
        {
            $reservation_id = $item["ReservationID"];
            $response = $cv->disbursements($reservation_id);
            $response=json_decode($response);

            $response_code = $response->ResponseCode;
            
            if ($response_code == "00")
            {
                $retry_cnt = $response->RetryCount;
                $status = $response->PayoutStatus;
                if ($status != "Success" && $retry_cnt < 4)
                {
                    $sql2 = "update orders set DisburseStatus='Processing' where OId='" . $item['OId'] . "'";
                    $result2 = mysqli_query($conn,$sql2);
                    $status = "Processing";
                }
                if ($status != "Success" && $retry_cnt == 4)
                {
                    $sql2 = "update orders set DisburseStatus='Failed' where OId='" . $item['OId'] . "'";
                    $result2 = mysqli_query($conn,$sql2);
                    $status = "Failed";
                }
                $item["Status"] = $status;
            }
        }
        echo json_encode($ret_data);
    }
    else
        echo "";
?>