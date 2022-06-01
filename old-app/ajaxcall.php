<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

define('BASEPATH', dirname(__FILE__));

include_once BASEPATH . '/classes/config.php';
include_once BASEPATH . '/classes/clsCashVaultAPI.php';



$key_verify = hash_hmac('md5', SALT, $_SESSION['token']);
if ($_POST['key'] == $key_verify) {
    if ($_POST) {
        if ($_POST['action'] == 'generate_URL') {
			$rid=FIXEDMERCHANTID.strtotime(date("Y-m-d H:i:s"));
			$cv=new CashVaultAPI();
			$response = $cv->reservation($_POST['email'],$_POST['mobile'],$_POST['account_number'],$_POST['bank_code'],$_POST['amount'],$_POST['duration'],$_POST['payeemobile'],150,1,date('Y-m-d H:i:s', strtotime(TIMEDIFF)),$rid,100);
            
			$response=json_encode($response);
			
            echo $response;
			
        } 
		else if ($_POST['action'] == 'validate_URL') 
		{
           $cv=new CashVaultAPI();
		   $response =$cv->getReservationStatusV2(FIXEDMERCHANTID,$_POST["validate_number"]);
		   $response=json_encode($response);
            echo $response;
        } 
		else if ($_POST['action'] == 'vault_code') 
		{
           $cv=new CashVaultAPI();
		   $response =$cv->sendVaultCode($_POST["amt"],$_POST["vault_code"],$_POST["rid"]);
		   $response=json_encode($response);
           echo $response;
        } 
		else if ($_POST['action'] == 'send_mail') {
            
			include_once("PHPMailer/PHPMailerAutoload.php");
			
			$message = file_get_contents('templates/email.php');
            $message = sprintf($message, $_POST['fund_url']);
			
			$mail = new PHPMailer();
			$mail->CharSet = 'UTF-8';
			$mail->setFrom($_POST['from_email'], 'Streetzwyze');
			$toaddr=explode(';',$_POST['to_email']);
			$mail->addAddress($toaddr[0]);
			if(count($toaddr)>1)
			{
				for($i=1;$i<count($toaddr);$i++)
				{
					$mail->addBCC($toaddr[$i]);
				}
			}
		    $mail->Subject = 'Email Verification';
            $mail->msgHTML($message);
		    $mail->SMTPDebug  = true;
		    $mail->send();
			
			echo json_encode(json_encode(array("status" => 202, "message" => "OK")));
        }
    }
} else {
    exit('No direct script access allowed');
}