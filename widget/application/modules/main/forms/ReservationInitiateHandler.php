<?php

/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 02/11/2015
 * Time: 11:19 AM
 */

namespace main\forms;

use models\entity\Reservation;
use models\forms\ValidationRule;
use models\forms\validationrules\ArrayObjectValidator;
use models\forms\validationrules\Callback;
use models\forms\validationrules\Date;
use models\forms\validationrules\Float as FloatType;
use models\forms\validationrules\OptionValidator;
use models\forms\validationrules\Required;
use models\forms\validationrules\Text;
use models\forms\validationrules\Url;
use service\WebServiceClient;
use Smarty;

class ReservationInitiateHandler extends \AbstractFormHandler {

    /**
     * @var WebServiceClient
     */
    private $webServiceClient;

    public function __construct(WebServiceClient $client) {
        parent::__construct();
        $this->webServiceClient = $client;
    }

    /**
     * Prepares view for form display, usually where you'll want to assign
     * data needed for drop downs, checkboxes and radio options, as well as
     * some other data needed by your form
     *
     * @param Smarty $view
     * @return void
     */
    public function bootStrap(&$view) {

    }

    public function setRules() {

        $this->rules = [
            'CustomerLink' => [ValidationRule::phoneNumber()],
            'DeliveryDuration' => [$this->required, ValidationRule::integer(1)],
            'DispatchEmail' => [ValidationRule::email()],
            'DispatchMobile' => [ValidationRule::phoneNumber()],
            'OrderAmount' => [$this->required, ValidationRule::float(50)],
            'OrderNumber' => [$this->justSanitized],
            'RefundDelivery' => [Required::rule(0, 'errors.refund_delivery_required'), OptionValidator::rule([0, 1])
                        ->setErrorCodes([
                            'validator.option_invalid' => 'errors.refund_delivery_required'
                        ])],
            'DeliveryFee' => [ValidationRule::float(0)],
            'ReservationDate' => [$this->required],
            'ReservationId' => [$this->required],
            'ReturnUrl' => [Url::rule()]
        ];

        $dateValidator = new Date(date('Y-m-d 00:00:00'), null, 'Y-m-d H:i:s');
        $dateValidator->setErrorCodes([
            'validator.date_min' => 'errors.reserve_date_passed',
            'validator.date_format' => 'errors.invalid_reserve_date'
        ]);

        $this->rules['ReservationDate'][] = $dateValidator;

        $splitValidator = [
            'AccountNo' => [$this->required, Text::rule(0, 10, 10, '/^\d+$/', 'errors.invalid_account_number')],
            'CbnCode' => [$this->required, ValidationRule::text(0, 3, 3, '/^\d+$/', 'errors.invalid_cbn_code')],
            'SplitPercentage' => [$this->required, ValidationRule::float(0.01, 100)->setErrorCodes([
                    'min' => 'errors.split_min',
                    'max' => 'errors.split_max',
                    'invalid' => 'errors.split_invalid'
                ])]
        ];

        $this->rules['ReservationRequestSplits'] = [
            new ArrayObjectValidator($splitValidator, true, 'errors.split_empty', true),
            new Callback(function ($splits) {
                        \SystemLogger::info('Splits are being validated');
                        $total = array_reduce($splits, function ($carry, $item) {
                                    return $carry + floatval($item['SplitPercentage']);
                                }, 0);

                        return round($total) == 100 || round($total) == 0;
                    }, null, 'errors.split_aggregate')
        ];
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
        $this->setRules();
        if ($data['RefundDelivery']) {
            $this->rules['DeliveryFee'] = [
                Required::rule(0, 'errors.delivery_fee_missing'),
                ValidationRule::float(85, floatval($data['OrderAmount']))->setErrorCodes([
                    'min' => 'errors.delivery_fee_min',
                    'max' => 'errors.delivery_fee_max',
            ])];
        }

        if (!$this->validate($data)) {
            return false;
        }

        $this->removeEmptyInput($this->validData);


        //now post to server to initiate, then cache the result.
        $reservationSuccess = $this->webServiceClient
                ->ReservationRequest($this->validData, function (\Exception $exception) {
            $this->addError($exception->getMessage());
        });

        if (!$reservationSuccess) {
            //save reservation to DB
            return null;
        }

        return $this->cacheReservation();
    }

    private function cacheReservation() {
        $keys = [
            'reservation_id',
            'order_amount',
            'delivery_fee',
            'order_number',
            'reservation_date',
            'delivery_duration',
            'refund_delivery',
        ];
        $data = [];

        foreach ($keys as $aKey) {
            $tr = ucfirst(\GeneralUtils::delimetedToCamelCased($aKey));
            $data[$aKey] = $this->validData[$tr];
        }

        if (isset($this->validData['ReturnUrl'])) {
            $data['site_return_url'] = $this->validData['ReturnUrl'];
        }

        return Reservation::saveEntity($data, true);
    }

    private function removeEmptyInput($data) {
        foreach ($data as $field => $value) {
            if (is_array($value) && count($value) == 0) {
                unset($data[$field]);
            }
        }
        $this->validData = $data;
    }

}
