<?php
    include ("../include/dbconnect.php");
    include ("./api_class/config.php");
    include ("./api_class/clsCashVaultAPI.php");

    
    if (isset($_POST['seller_email']) && !empty($_POST['seller_email']))
        $email = $_POST['seller_email'];
    
    $sql = "select * from merchant where Emailaddress='" . $email. "'";
    $result = mysqli_query($conn,$sql);

    $result = mysqli_query($conn,$sql);
    $first_name = "";
    $token = "";
    $ret_data = array();
    if (mysqli_num_rows($result) > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $first_name = $row['Firstname'];
            $token = $row['Token'];
        }
        header("Location: ../reset-password.php?token=" . $token);
    }
    
?>