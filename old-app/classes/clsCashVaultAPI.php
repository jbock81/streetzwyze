<?php
include_once("config.php");
class CashVaultAPI
{
	private $clientID=CLIENTID;
	private $clientSecretKey=CLIENTSECRETKEY;
	
	function __construct() {
   		
	}

	private function callAPI($apiURL,$param,$method)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'accept:JSON',
			'Content-Type: application/json',
			'ClientID:'.$this->clientID,
			'ClientSecretKey:'.$this->clientSecretKey
		));
		
		if($method=="POST")
			curl_setopt($curl, CURLOPT_POST, true);
		else
			curl_setopt($curl, CURLOPT_HTTPGET, true);
			
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $apiURL);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		if($method=="POST")
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));
			
		$return = curl_exec($curl);
		if(curl_errno($curl)){
			$err=true;
    		die( 'Curl error: ' . curl_error($curl) );
			}
		curl_close($curl);
		
		return $return;
	}
	
	function getTwitterFollowers()
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Access Token:3055919992-aVCkoS3R5w9955muHKcOzszzQGD0QZ11BdncKLB',
			'Access TokenSecret:PdZ7QKtyi5DxZq2kZMcxQ5XJkuXyCkqMd83S777G7WH1t',
			'Access Level:Read, write, and direct messages',
			'Owner:abeazy3T',
			'Owner ID:3055919992',
			'Accept:JSON',
			'Content-Type:application/json'
		));
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, 'https://api.twitter.com/1.1/followers/list.json');
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$return = curl_exec($curl);
		if(curl_errno($curl)){
			$err=true;
    		die( 'Curl error: ' . curl_error($curl) );
			}
		curl_close($curl);
		
		return $return;
	}
	
	function getReservationStatusV2($MerchantId,$DispatchMobile)
	{
		$params=array(
		);
		//$this->clientID="1CV09NB6CT";
		//$this->clientSecretKey="X0F6LUVSLWF62TMW7B31JANLVY0N74C08ZLLO44MJOC5076L0Q9AW1SD";
		return $this->callAPI(BASEAPIURLV2."GetReservationStatusV2?MerchantId=".$MerchantId."&DispatchMobile=".$DispatchMobile,$params,"GET");
	}
	
	
	function sendVaultCode($amt,$CustomerVaultCode,$ReservationId)
	{
		//$this->clientID="1CV09NB6CT";
		//$this->clientSecretKey="X0F6LUVSLWF62TMW7B31JANLVY0N74C08ZLLO44MJOC5076L0Q9AW1SD";
		$params=array(
			'CustomerVaultCode' => $CustomerVaultCode,
			'OrderAmount' => $amt,
			'ReservationId' =>$ReservationId
		);
		return $this->callAPI(BASEAPIURLV2.'SendVaultCode',$params,"POST");
	}
	
	function reservation($DispatchEmail,$DispatchMobile,$AccountNo,$CBN,$Amount,$Duration,$Tag,$DeliveryFee,$RefundDelivery,$Date,$ReservationID,$SplitPercentage)
	{
		$params=array(
			'DeliveryDuration' => $Duration,
			'DeliveryFee' =>$DeliveryFee,
			'DispatchEmail' =>$DispatchEmail,
			'DispatchMobile' => $DispatchMobile,
			'OrderAmount' => $Amount,
			'OrderNumber'=>$Tag,
			'RefundDelivery'=>$RefundDelivery, 
			'ReservationDate'=>$Date, 
			'ReservationId'=>$ReservationID,
			'ReservationRequestSplits'=>[array(
												'AccountNo' => $AccountNo,
												'CbnCode' =>$CBN,
												'SplitPercentage' =>$SplitPercentage
											 )],
			'ReturnUrl'=>'https://staging.streetzwyze.com/'
		);
		return $this->callAPI(BASEAPIURL.'reservation',$params,"POST");
	}

}
?>
