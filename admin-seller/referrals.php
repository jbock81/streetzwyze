<?php
    include ("../include/dbconnect.php");
    include ("./api_class/config.php");
    include ("./api_class/clsCashVaultAPI.php");

    
    if (isset($_POST['email']) && !empty($_POST['email']))
        $email = $_POST['email'];

    $cur_id = $_SESSION['MId'];
    $sql = "select * from merchant where MId='" . $cur_id. "'";
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

    include("../old-app/PHPMailer/PHPMailerAutoload.php");
    $signup_url = "https://staging.streetzwyze.com/seller-signup.php?mid=$cur_id";
    $user_avatar_url = "https://staging.streetzwyze.com/img/".$ret_data["Photo"];
    $message = file_get_contents('../templates/refferral.html');
    $content = explode("\n", $message);
    $message= strtr($message, array( "invite_url" => $signup_url, ));
    $message= strtr($message, array( "user_full_name" => $ret_data['Firstname'].$ret_data['Lastname'], ));
    $message= strtr($message, array( "user_avatar_url" => $user_avatar_url, ));
    $from_email ='noreply@streetzwyze.com';
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($from_email, 'Streetzwyze');
    $mail->addAddress($email);
    $mail->Subject = 'Email Verification';
    $mail->msgHTML($message);
    $mail->SMTPDebug  = true;
    $mail->send();
    
?>