<?php
    include ("../include/dbconnect.php");
    include ("./api_class/config.php");
    include ("./api_class/clsCashVaultAPI.php");

    if (isset($_POST['fname']) && !empty($_POST['fname']))
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);

    if (isset($_POST['lname']) && !empty($_POST['lname']))
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    
    if (isset($_POST['seller_email']) && !empty($_POST['seller_email']))
        $seller_email = mysqli_real_escape_string($conn, $_POST['seller_email']);
    
    if (isset($_POST['seller_phone']) && !empty($_POST['seller_phone']))
        $seller_phone = mysqli_real_escape_string($conn, $_POST['seller_phone']);
    
    if (isset($_POST['seller_pwd']) && !empty($_POST['seller_pwd']))
        $seller_pwd = mysqli_real_escape_string($conn, $_POST['seller_pwd']);

    if (isset($_POST['referral_id']) && !empty($_POST['referral_id']))
        $referral_id = mysqli_real_escape_string($conn, $_POST['referral_id']);
    else
        $referral_id = 0;

    $date = new DateTime();
    $timestamp= strval($date->getTimestamp());
    $token = $fname.$seller_email.$seller_phone.$timestamp;
    $secret_key= "this is secret key for our business.";

    $key_verify = hash_hmac('md5', $token, $secret_key, false);

    include ("../old-app/PHPMailer/PHPMailerAutoload.php");
    $verify_url = "http://staging.streetzwyze.com/admin-seller/ajax/checkemail.php?token=$key_verify";
    $message = file_get_contents('../templates/email-verification.html');
    $content = explode("\n", $message);
    $message= strtr($message, array( "verify_url" => $verify_url));
    $from_email ='noreply@streetzwyze.com';
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($from_email, 'Streetzwyze');
    $mail->addAddress($seller_email);
    $mail->Subject = 'Email Verification';
    $mail->msgHTML($message);
    $mail->SMTPDebug  = true;
    $mail->send();
    $referralcode_str = $seller_email.$timestamp.$fname;
    $referralcode = hash_hmac('md5', $referralcode_str, $secret_key, false);
    $sql = "insert into `merchant`(`Firstname`,`Lastname`,`Emailaddress`,`Mobilenumber`,`Businessname`,`PaymentTag`,`Mpassword`,`Configduration`,`Configpayvalue`,`Penaltyfee`,`Referralcode`,`Signupdate`,`Lastlogin`,`Token`,`Approve`,`ReferralId`) values ('";
    $sql .= $fname . "','" . $lname . "','" . $seller_email . "','" . $seller_phone . "','','','" . md5($seller_pwd) . "',0,0,0.0,'". $referralcode ."','" . date("Y-m-d H:i:s") . "','','" . $key_verify . "', 0, " . $referral_id . ")";

    $result = mysqli_query($conn,$sql);
    if ($result)
    {
        $MId = mysqli_insert_id($conn);
        //header("Location: ../signin.php?email=" . $seller_email);
        header("Location: ../seller-signup.php?reg_success=1");
    }
?>