<?php
    include_once ("../include/dbconnect.php");

    if (isset($_POST['email']) && !empty($_POST['email']))
        $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (isset($_POST['password']) && !empty($_POST['password']))
        $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "select * from merchant where (Emailaddress='" . $email . "' or  Mobilenumber='" . $email . "') and Mpassword='" . md5($password) . "' and Approve=1";
    $result = mysqli_query($conn,$sql);

    $MId = 0;
    $referral_id = 0;
    if ($result)
    {
        if (mysqli_num_rows($result) > 0)
        {
            while($row = $result->fetch_assoc()) 
            {
                $MId = $row['MId'];
                session_destroy();
                session_start();
                $_SESSION['MId'] = $MId;
                $_SESSION['Firstname'] = $row['Firstname'];
                $_SESSION['Lastname'] = $row['Lastname'];                
                if ($row['Photo'] != "")
                    $_SESSION['Photo'] = "/admin-seller/profile-pics/" . $row['Photo'];
                $referral_id = intval($row['ReferralId']);
            }
            if ($referral_id == 0)
            {
                $sql = "update merchant set Configduration=12, Configpayvalue=35000, Lastlogin='" . date("Y-m-d H:i:s") . "' where MId='" . $MId . "'";
                $result = mysqli_query($conn, $sql);
            }
            else
            {
                $sql = "update merchant set Configduration=19, Configpayvalue=38750, Lastlogin='" . date("Y-m-d H:i:s") . "' where MId='" . $MId . "'";
                $result = mysqli_query($conn, $sql);
                $sql = "update merchant set Configduration=19, Configpayvalue=38750, Lastlogin='" . date("Y-m-d H:i:s") . "' where MId='" . $referral_id . "'";
                $result = mysqli_query($conn, $sql);
            }

            header("Location: ../reservation.php");
        }
        else
        {
            echo "<script>location.href='../signin.php';</script>";   
        }
    }
    else
    {
        echo "<script>location.href='../signin.php';</script>";
    }
?>