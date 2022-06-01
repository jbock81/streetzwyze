<?php
    include ("../../include/dbconnect.php");

    $token = "";
    if (isset($_GET['token']) && !empty($_GET['token']))
        $token = $_GET['token'];

    $sql = "select * from merchant where Token='" . $token . "'";
    $result = mysqli_query($conn,$sql);

    if (mysqli_num_rows($result) > 0)
    {
        $sql = "update merchant set Approve=1 where Token='" . $token . "'";
        $result = mysqli_query($conn,$sql);
        $ret = "verified";
        #================================ CCG
        $cur_id = $_SESSION['MId'];
        $sql = "select * from merchant where MId='" . $cur_id. "'";
        $result = mysqli_query($conn,$sql);

        $result = mysqli_query($conn,$sql);

        $ret_data = array();
        if (mysqli_num_rows($result) > 0)
        {
            while($row = $result->fetch_assoc()) 
            {
                $ret_data["MId"] = $row['MId'];
                $ret_data["Firstname"] = $row['Firstname'];
                $ret_data["Lastname"] = $row['Lastname'];
                $ret_data["Emailaddress"] = $row['Emailaddress'];
                $ret_data["Photo"] = $row["Photo"];
            }
        }
        include ("../old-app/PHPMailer/PHPMailerAutoload.php");
        $message = file_get_contents('../../templates/seller-verification.html');
        $content = explode("\n", $message);
        $message= strtr($message, array( "first_name" => $ret_data['Firstname']));
        $from_email ='noreply@streetzwyze.com';
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($from_email, 'Streetzwyze');
        $mail->addAddress($seller_email);
        $mail->Subject = 'Welcome to Streetzwyze';
        $mail->msgHTML($message);
        $mail->SMTPDebug  = true;
        $mail->send();
        header("Location: ../../signin.php");
    }
    else
    {
        $ret = "no_verified";
        header("Location: ../../seller-signup.php");
    }
        
    
?>