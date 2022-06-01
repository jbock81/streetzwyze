<?php
    include ("../include/dbconnect.php");

    if (isset($_POST['token']) && !empty($_POST['token']))
        $token = $_POST['token'];
    else
        $token = "";

    if (isset($_POST['new_pwd']) && !empty($_POST['new_pwd']))
        $new_pwd = $_POST['new_pwd'];
    else
        $new_pwd = "";
    
    //$email = mysqli_real_escape_string($conn, $_GET['email']);
    //$token = mysqli_real_escape_string($conn, $_GET['token']);

    
    $sql = "select * from merchant where Token='" . $token . "'";

    $result = mysqli_query($conn,$sql);

    if (mysqli_num_rows($result) > 0)
    {
        $sql2 = "update merchant set Mpassword='" . md5($new_pwd) . "' where Token='" . $token . "'";
        $result2 = mysqli_query($conn, $sql2);
        
        //header("Location: ../reset-password.php?token=" . $token);
        // include("../old-app/PHPMailer/PHPMailerAutoload.php");
        // $forgot_pass_url = "https://staging.streetzwyze.com/admin-seller/reset_pwd.php?token=$token"."&email=$email";
        // $message = file_get_contents('../templates/user-forgotten-pwd.html');
        // $content = explode("\n", $message);
        // $message= strtr($message, array( "forgot_pass_url" => $forgot_pass_url, ));
        // $message= strtr($message, array( "user_full_name" => $first_name, ));
        // $from_email ='noreply@streetzwyze.com';
        // $mail = new PHPMailer();
        // $mail->CharSet = 'UTF-8';
        // $mail->setFrom($from_email, 'Streetzwyze');
        // $mail->addAddress($email);
        // $mail->Subject = 'Email Verification';
        // $mail->msgHTML($message);
        // $mail->SMTPDebug  = true;
        // $mail->send();

        header("Location: ../index.php");
    }
    else
        header("Location: ../forgotten-password.php");

?>