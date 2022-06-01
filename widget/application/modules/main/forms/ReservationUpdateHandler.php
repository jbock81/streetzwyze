<?php

/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 03/11/2015
 * Time: 10:24 PM
 */

namespace main\forms;

use models\entity\Transaction;
use models\forms\ValidationRule;
use models\forms\validationrules\ArrayObjectValidator;
use models\forms\validationrules\Email;
use service\WebServiceClient;
use Smarty;

class ReservationUpdateHandler extends \AbstractFormHandler {

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var WebServiceClient
     */
    private $serviceClient;

    /**
     * PaymentSuccessHandler constructor.
     * @param Transaction $transaction
     * @param WebServiceClient $serviceClient
     */
    public function __construct(Transaction $transaction, WebServiceClient $serviceClient) {
        parent::__construct();
        $this->transaction = $transaction;
        $this->serviceClient = $serviceClient;
    }

    /**
     * Prepares view for form display, usually where you'll want to assign
     * data needed for dropdown, checkboxes and radio options, as well as
     * some other data needed by your form
     *
     * @param Smarty $view
     * @return void
     */
    public function bootStrap(&$view) {
        $view->transaction = $this->transaction;
        $view->reservation = $this->transaction->reservation;
    }

    public function setRules() {
        $this->rules = [];

        if (!$this->transaction->reservation->hasCustomerInfo()) {
            $this->rules['customer'] = [
                ArrayObjectValidator::rule([
                    'email' => [$this->required, Email::rule()],
                        //'firstName' => [$this->required],
                        //'lastName' => [$this->required]
                ])
            ];
        }

        if ($this->transaction->isNewBank()) {
            $this->rules['bank'] = ArrayObjectValidator::rule([
                        'account' => [
                            $this->required,
                            ValidationRule::text(0, 10, 10, '/^\d+$/', 'errors.invalid_account_number')
                        ]
            ]);
        }
    }

    /**
     * Process for data, this should be called only if the data validation was
     * successful.
     *
     * @param array $data form data
     *
     * @return mixed true-ish value to indicate success and false-ish otherwise.
     */
    public function saveData($data = null) {
        if (!$this->validate($data)) {
            return false;
        }

        try {
            if (!$this->transaction->reservation->hasCustomerInfo()) {
                $customer = $this->createCustomerAndSecureUpdate();
            } else {
                $customer = true;
                return $this->sendSecureFundUpdate();
            }
        } catch (\Exception $e) {
            $this->addErrorByCode('errors.reg_service_error');
        }
        return !!$customer;
    }

    private function sendNewSource() {
        $bankData = $this->validData['bank'];
        $reservation = $this->transaction->reservation;
        $newSourceData = [
            'ReservationId' => $reservation->id,
            'CustomerAccountNo' => $bankData['account'],
            'CustomerCBNCode' => $this->transaction->bank->getCBNCode(),
            'CustomerId' => $reservation->customer_id,
            'FundSecureDate' => date('Y-m-d H:i:s'),
            'FundSecured' => WebServiceClient::ERR_SUCCESS,
        ];

        $this->serviceClient->SendNewCustomerSource($newSourceData);
        $reservation->setSecured();
        return true;
    }

    private function createCustomerAndSecureUpdate() {
        $customer = $this->validData['customer'];
        $reservation = $this->transaction->reservation;

        $bankData = $this->validData['bank'];
        $customerData = [
            'CustomerEmail' => $customer['email'],
            'CustomerAccNo' => $bankData['account'],
            'CustomerCbnCode' => $this->transaction->bank->getCBNCode(),
            'CustomerMobile' => $reservation->customer_mobile_no,
            'FundSecureDate' => date('Y-m-d H:i:s'),
            'FundSecured' => WebServiceClient::ERR_SUCCESS,
            'ReservationId' => $reservation->id,
        ];
        $customerObj = $this->serviceClient
                ->UpdateUserInfo($customerData);
        $reservation->setSecured([
            'customer_id' => $customerObj->CustomerID,
        ]);

        return true;
    }

    public function sendSecureFundUpdate() {
        if ($this->transaction->isFailed() || !$this->transaction->isNewBank()) {
            $reservation = $this->transaction->reservation;
            try {
                //inform service
                $this->serviceClient->SendSecureFundUpdate([
                    'CustomerId' => $reservation->customer_id,
                    'FundSecureDate' => date('Y-m-d H:i:s'),
                    'FundSecured' => $this->transaction->gateway_status_code,
                    'PayRefBank' => $this->transaction->bank ? $this->transaction->bank->getCBNCode() : null,
                    'ReservationId' => $reservation->id
                ]);
                //next update
                $reservation->setSecured();

                return true;
            } catch (\Exception $e) {
                \SystemLogger::error('Could not get secure update', $e->getMessage());
            }
        } else {
            return $this->sendNewSource();
        }

        return false;
    }

}
