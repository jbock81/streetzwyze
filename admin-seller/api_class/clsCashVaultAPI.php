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
	
	function getReservationStatusV2($DispatchMobile)
	{
		$params=array(
		);
		//$this->clientID="1CV09NB6CT";
		//$this->clientSecretKey="X0F6LUVSLWF62TMW7B31JANLVY0N74C08ZLLO44MJOC5076L0Q9AW1SD";
		return $this->callAPI(BASEAPIURLV2."GetReservationStatusV2?DispatchMobile=".$DispatchMobile,$params,"GET");
	}
	
	
	function sendVaultCode(/*$amt,*/$CustomerVaultCode,$ReservationId)
	{
		//$this->clientID="1CV09NB6CT";
		//$this->clientSecretKey="X0F6LUVSLWF62TMW7B31JANLVY0N74C08ZLLO44MJOC5076L0Q9AW1SD";
		$params=array(
			'CustomerVaultCode' => $CustomerVaultCode,
			//'OrderAmount' => $amt,
			'ReservationId' =>$ReservationId
		);
		return $this->callAPI(BASEAPIURLV2.'SendDeliveryCode',$params,"POST");
	}

	function reservation($DispatchEmail,$DispatchMobile,$DispatchName,$RefundDelivery,$DeliveryFee,$Duration,$CustomerLink,$ReservationID,$OrderAmount,$ReservationTag,$ReservationDate,$AccountNo,$CBN,$SplitPercentage)
	{
		/*$params=array(
			'DispatchName' => $DispatchName,
			'DeliveryDuration' => $Duration,
			'CustomerLink' => $CustomerLink,
			'DeliveryFee' =>$DeliveryFee,
			'DispatchEmail' =>$DispatchEmail,
			'DispatchMobile' => $DispatchMobile,
			'OrderAmount' => $OrderAmount,
			'OrderNumber'=>$ReservationTag,
			'RefundDelivery'=>$RefundDelivery, 
			'ReservationDate'=>$ReservationDate, 
			'ReservationId'=>$ReservationID,
			'ReservationRequestSplits'=>[array(
												'AccountNo' => $AccountNo,
												'CbnCode' =>$CBN,
												'SplitPercentage' =>$SplitPercentage
											 )],
			'ReturnUrl'=>'https://staging.streetzwyze.com/'
		);*/

		$params=array(
			"CustomerLink"=>$CustomerLink,
			"DeliveryDuration"=>$Duration,
			"DeliveryFee"=>$DeliveryFee,
			"dispatchemail"=>$DispatchEmail,
			"DispatchMobile"=>$DispatchMobile,
			"DispatchName"=>$DispatchName,
			"OrderAmount"=>$OrderAmount,
			"OrderNumber"=>$ReservationTag,
			"RefundDelivery"=>$RefundDelivery, 
			"ReservationDate"=>$ReservationDate, 
			"ReservationId"=>$ReservationID,
			"ReservationRequestSplits"=>[array(
											"AccountNo"=>$AccountNo,
											"CbnCode"=>$CBN,
											"SplitPercentage"=>$SplitPercentage,
										)],
			"ReturnUrl"=>'https://staging.streetzwyze.com/'
			);
		return $this->callAPI(BASEAPIURL.'reservation',$params,"POST");
	}

	public function dashboard_tile($ReservationID)
	{
		/*$params = array(
			'ReservationID' => $ReservationID,
			'ReturnUrl'=>'https://staging.streetzwyze.com'
		);*/
		$params = array();
		$api_url = "https://api.cashvaultng.com/stagmerce/CVSoapRESTService.svc/REST/GetReservationStatus?ReservationID=" . $ReservationID;
		return $this->callAPI($api_url, $params, "GET");
	}

	public function disbursements($ReservationID)
	{
		/*$params = array(
			'ReservationID' => $ReservationID,
			'ReturnUrl'=>'https://staging.streetzwyze.com/'
		);*/
		$params = array();
		$api_url = "https://api.cashvaultng.com/stagmerce/CVSoapRestService.svc/REST/GetDisburseStatus?ReservationID=" . $ReservationID;
		return $this->callAPI($api_url, $params, "GET");
	}

	public function release_fund($MerchantId, $ReleaseAmount, $AccountNumber, $CBNCode, $Narration)
	{
		$params = array(
			'AccountNumber' => $AccountNumber,
			'BankCbnCode' => $CBNCode,
			'MerchantId' => $MerchantId,
			'Narration' => $Narration,
			'ReleaseAmount' => $ReleaseAmount
			//'ReturnUrl'=>'https://staging.streetzwyze.com/'
		);
		$api_url = "https://api.cashvaultng.com/stagmerce/CVSoapRestService.svc/REST/ReleaseFunds";
		return $this->callAPI($api_url, $params, "POST");
	}

	public function penalty_fee($PenaltyFee, $ReservationID)
	{
		$params = array();
		$payment_url = "https://paywith.quickteller.com/checkout?paymentCode=04249201&Amount=" . $PenaltyFee . "&CustomerId=" . $ReservationID . "&EmailAddress=info@streetzwyze.com&MobileNumber=08028849294&site=cashvaultng.com&redirectUrl=https://streetzwyze.com/admin-seller/penaltyfees.php";
		return $this->callAPI($payment_url, $params, "GET");
	}

	public function penalty_deposit($penaltyFee, $reservationID)
	{
		$params = array(
			'Amount' => $penaltyFee,
			'ReservationId' => $reservationID
		);
		$api_url = "https://api.cashvaultng.com/stagmerce/CVSoapRestService.svc/REST/PenaltyDeposit";
		return $this->callAPI($api_url, $params, "POST");
	}
}
?>
