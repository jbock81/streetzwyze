<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 03/11/2015
 * Time: 11:51 AM
 */

namespace plugins\payments\quickteller;


use main\controllers\BaseController;
use models\entity\BankRegistry;
use models\entity\Reservation;
use models\entity\Transaction;
use plugins\payments\BaseHandler;
use plugins\payments\PaymentResponse;
use Strings;
use SystemLogger;

class PayWithQuickteller extends BaseHandler
{

    private $config = [];

    const MAX_REQUERIES = 3;
    const REQUERY_INTERVAL = 2;
    const URL_PATH_CHECKOUT = 'checkout';
    const URL_PATH_CALLBACK = 'api/v2/transaction.json';

    public static $successCodes = array('00');


    /**
     * PayWithQuickteller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config = include(__DIR__ . '/config.php');
    }


    /**
     * @param Reservation $reservation
     * @param BaseController $controller
     * @return void
     */
    public function process(Reservation $reservation, BaseController $controller)
    {
        $request = $controller->getRequest();
        //we have transaction, so Redirect
        $checkoutData = [
            'site' => $this->config['clientId'],
            'paymentCode' => $this->config['paymentCode'],
            'amount' => ceil($reservation->getDueAmount() * 100),
            'customerId' => $reservation->id,
            'mobileNumber' => $this->config['Customermobile'],
            'redirectUrl' => $request->buildURL('notify', 'gateway', 'main', [$reservation->id => '']),
            'emailAddress' => $this->config['supportEmail']
        ];

        $paymentUrl = $this->url(self::URL_PATH_CHECKOUT, $checkoutData);
        \SystemLogger::info('Redirecting to payment URL: ', $paymentUrl);

//        if (\Application::currentInstance()->isProd()) {
//            $request->redirect($paymentUrl);
//        } else {
            $page = <<<HTML
<html>
<head>
<title>Loading Quickteller</title>
<meta name="referrer" content="no-referrer" />
</head>
<body>
<script>window.location.href='{$paymentUrl}'</script>
</body>
</html>
HTML;
            die($page);
//        }


        //
        //this hack should work

    }


    protected function _verify($data, Reservation $reservation)
    {
        $gatewayRef = $data['tx_ref'];
        $responseData = [];
        $transaction = null;

        $success = $this->_verifyResponse($gatewayRef, $reservation, $responseData, $transaction, false);
        $statusText = $responseData['ResponseDescription'] ?: Strings::getValue('quickteller', $responseData['ResponseCode']);
        $response = new PaymentResponse($responseData['ResponseCode'], $responseData['Amount'] / 100.0, $statusText, $success);
        $response->setTransaction($transaction);
        return $response;
    }


    private function _verifyResponse($gatewayRef, Reservation $reservation, &$responseData = array(), &$transaction = null, $reQuery = false)
    {
        if (!$gatewayRef && !$transaction) {
            \SystemLogger::error("Un-reconcilable transaction, no reference and input transaction is not valid");
            $responseData = array(
                'ResponseCode' => 'GW_CONFIG_ERR',
                'ResponseDescription' => \Strings::get('payment.no_transaction_found')
            );
            return false;
        }

        $responseData = json_decode($this->callRestApi($gatewayRef), true);

        if (!$reQuery) {
            $transaction = $this->createTransaction($reservation, null, $gatewayRef);
            if (!$transaction) {
                throw new \RuntimeException('Payment transaction could not be created. Halting execution.');
            }
        }

        //gateway not available or returned un-parse-able data
        if (!$responseData) {
            $responseData = array(
                'ResponseCode' => 'NO_GW_RESP',
                'ResponseDescription' => \Strings::get('payment.no_gateway_response')
            );
            return false;
        }

        $statusCode = $responseData['ResponseCode'];
        if (!in_array($statusCode, self::$successCodes, true)) {
            return false;
        }

        //verify amount
        $transactionAmount = floor(floatval($responseData['Amount']) / 100);
        if ($transactionAmount != floor($transaction->amount)) {
            //security violation
            $responseData['ResponseDescription'] = \Strings::get('payment.invalid_amount', [
                'amountExpected' => $transaction->amount,
                'amountPaid' => ($responseData['Amount'] / 100.0)
            ]);
            return false;
        }

        return true;
    }


    public function getName()
    {
        return $this->config['name'];
    }

    /**
     * @param Transaction $transaction
     * @return PaymentResponse
     */
    protected function _reQuery(Transaction $transaction)
    {
        $success = $this->_verifyResponse($transaction->gateway_ref, $transaction->reservation, $responseData, $transaction, true);
        return (new PaymentResponse($responseData['ResponseCode'], $responseData['Amount'] / 100.0, $responseData['ResponseDescription'], $success))
            ->setTransaction($transaction);
    }

    private function url($path, array $q = [])
    {
        $url = $this->config['baseUrl'] . $path;
        if (!empty($q)) {
            $url .= ('?' . http_build_query($q));
        }
        return $url;
    }

    private function callRestApi($txnRefFromQt)
    {
        $retries = 0;
        do {
            $params = array("transRef" => $txnRefFromQt); //set it to tx_ref
            $_url = $this->url(self::URL_PATH_CALLBACK, $params);
            $opts = array(
                'http' => array(
                    'method' => "GET",
                    'header' => "clientid: {$this->config['clientId']}\r\n" .
                        "Hash: " . strtoupper(hash("sha512", "{$txnRefFromQt}{$this->config['secretKey']}")) . "\r\n"
                        . "User-Agent: CashVault UA/1.0\r\n",
                    'ignore_errors' => true
                )
            );
            //Send your request here
            $context = stream_context_create($opts);
            $response = file_get_contents($_url, false, $context);
        } while (!$response && ($retries++ < self::MAX_REQUERIES) && (sleep(self::REQUERY_INTERVAL) === 0));
        \SystemLogger::info("After : ", $retries, " trial(s)", "Response is: ", $response);
        return $response;
    }

    /**
     * @param Transaction $transaction
     * @return BankRegistry
     */
    public function getBank(Transaction $transaction)
    {
        if (!$transaction->gateway_ref) {
            return null;
        }

        $qtCode = explode('|', $transaction->gateway_ref)[0];
        return BankRegistry::findByQtCode($qtCode);
    }


}