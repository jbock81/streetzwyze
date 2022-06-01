<?php
    include ("../../include/dbconnect.php");

    if (isset($_POST['ddate_from']) && !empty($_POST['ddate_from']))
        $ddate_from = mysqli_real_escape_string($conn, $_POST['ddate_from']);
    
    if (isset($_POST['ddate_to']) && !empty($_POST['ddate_to']))
        $ddate_to = mysqli_real_escape_string($conn, $_POST['ddate_to']);
    
    $arr_date_from = explode("/", $ddate_from);
    $ddate_from = $arr_date_from[2] . "-" . $arr_date_from[0] . "-" . $arr_date_from[1];

    $arr_date_to = explode("/", $ddate_to);
    $ddate_to = $arr_date_to[2] . "-" . $arr_date_to[0] . "-" . $arr_date_to[1];
    
    $sql = "select * from orders where MId='" . $_SESSION["MId"] . "'  and OrdStatus <> '' and MID(Reservedate,1,10)>='" . $ddate_from . "' and MID(Reservedate,1,10)<='" . $ddate_to . "'";
    $result = mysqli_query($conn,$sql);

    $ret_data = array(array());
    $k = 0;
    if (mysqli_num_rows($result) > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $ret_data[$k]["OId"] = $row['OId'];
            $ret_data[$k]["OrderTag"] = $row['ReservationTag'];
            $ret_data[$k]["Amount"] = $row['Orderamount'];
            $ret_data[$k]["Reservedate"] = substr($row['Reservedate'],0,10);
            $ret_data[$k]["Status"] = $row["OrdStatus"];
            $k++;
        }
    }
    
    echo json_encode($ret_data);
?>