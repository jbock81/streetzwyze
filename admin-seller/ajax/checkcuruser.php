<?php
    include ("../../include/dbconnect.php");

    if (isset($_POST['seller_email']) && !empty($_POST['seller_email']))
        $seller_email = mysqli_real_escape_string($conn, $_POST['seller_email']);
    
    $sql = "select * from merchant where Emailaddress='" . $seller_email  . "'";
    $result = mysqli_query($conn,$sql);

    if (mysqli_num_rows($result) > 0)
        $ret = "exist";
    else
        $ret = "no_exist";
    
    echo $ret;
?>