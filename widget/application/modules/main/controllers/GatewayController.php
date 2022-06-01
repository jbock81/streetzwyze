<?php

/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 03/11/2015
 * Time: 11:43 AM
 */

namespace main\controllers;

use plugins\payments\BaseHandler;
use models\entity\Transaction;

class GatewayController extends BaseController {

    /**
     *
     * @var BaseHandler
     */
    protected $paymentHandler;

    protected function _initPaymentHandler() {
        $this->paymentHandler = BaseHandler::getDefault();
    }

    public function doProcess() {
        $rid = $this->_request->getExtras(0);
        $reservation = $this->getReservation($rid);
        if (!$reservation->customer_mobile_no) {
            //redirect to start
            $this->_request->redirect('pay/?rid=' . $reservation->id);
        }

        if (\Application::currentInstance()->isProd()) {
            //redirect to payment page
            $this->paymentHandler->process($reservation, $this);
        } else {
            $testPaymentGateway = $this->_request->buildURL('test-payment') . '?'
                    . http_build_query([
                        'rid' => $rid
            ]);
            $this->_request->redirect($testPaymentGateway);
        }
    }

    public function doTestPayment() {
        $rid = $this->_request->getQueryParam('rid') ? : NULL;
        $reservation = $this->getReservation($rid);
        if (!$rid || !$reservation) {
            //redirect to start
            $this->_request->redirect('pay/?rid=' . $reservation->id);
        }
        if ($this->_request->isPost() && $this->_request->getPostData('action') === 'otpInput') {
            $this->_forward('otp-input');
        }
        $this->_use_layout = FALSE;
        $this->_view->reservation = $reservation;
    }

    public function doOTPInput() {
        if (!$this->_request->isPost()) {
            $this->_request->redirect404('INVALID_ACTION_CALL');
        }
        $rid = $this->_request->getQueryParam('rid') ? : NULL;
        $reservation = $this->getReservation($rid);
        if (!$rid || !$reservation) {
            //redirect to start
            $this->_request->redirect('pay/?rid=' . $reservation->id);
        }
        if ($this->_request->getPostData('action') === 'testPaymentSuccess') {
            $this->_forward('test-payment-success');
        }
        $this->_use_layout = FALSE;
        $this->_view->reservation = $reservation;
    }

    public function doTestPaymentSuccess() {
        if (!$this->_request->isPost()) {
            $this->_request->redirect404('INVALID_ACTION_CALL');
        }
        $rid = $this->_request->getQueryParam('rid') ? : NULL;
        $reservation = $this->getReservation($rid);
        if (!$rid || !$reservation) {
            //redirect to start
            $this->_request->redirect('pay/?rid=' . $reservation->id);
        }
        $this->_use_layout = FALSE;
        $this->_view->reservation = $reservation;


        if ($this->_request->getPostData('action') === 'PaymentSuccessRedirect') {
            if ($reservation['customer_cbn_codes']) {
                $bank_qt_code = $this->get_bank_qtcode(explode('|', $reservation['customer_cbn_codes'])[0]);
            } else {
                $bank_qt_code = $this->get_bank_qtcode();
            }
            $transaction = Transaction::saveEntity([
                        'our_ref' => $ref ? : Transaction::generateRef(),
                        'memo' => \Strings::get('payment.reservation_memo', [
                            'reservationId' => $reservation->id,
                        ]),
                        'amount' => $reservation->getDueAmount(),
                        'reservation_id' => $reservation->id,
                        'ip_address' => ip2long(explode(':', getRealIpAddress())[0]),
                        'gateway_ref' => $bank_qt_code . '|' . rand(999999, 9999999),
                        'gateway_status_code' => '00',
                        'status' => 1,
                            ], true);
            $transaction->bank = $this->paymentHandler->getBank($transaction);
            $transaction->reservation->setPaid($transaction);
            $this->redirectTxnSuccess($transaction);
        }
    }

    public function doTestPaymentFailed() {

        $rid = $this->_request->getQueryParam('rid') ? : NULL;
        $reservation = $this->getReservation($rid);
        if (!$rid || !$reservation) {
            //redirect to start
            $this->_request->redirect('pay/?rid=' . $reservation->id);
        }
        $this->_use_layout = FALSE;
        $this->_view->reservation = $reservation;

        if ($reservation['customer_cbn_codes']) {
            $bank_qt_code = $this->get_bank_qtcode(explode('|', $reservation['customer_cbn_codes'])[0]);
        } else {
            $bank_qt_code = $this->get_bank_qtcode();
        }
        $transaction = Transaction::saveEntity([
                    'our_ref' => $ref ? : Transaction::generateRef(),
                    'memo' => \Strings::get('payment.reservation_memo', [
                        'reservationId' => $reservation->id,
                    ]),
                    'amount' => $reservation->getDueAmount(),
                    'reservation_id' => $reservation->id,
                    'ip_address' => ip2long(explode(':', getRealIpAddress())[0]),
                    'gateway_ref' => $bank_qt_code . '|' . rand(999999, 9999999),
                    'gateway_status_code' => 'G3',
                    'gateway_status_text' => 'User cancelled the transaction.',
                    'status' => 2,
                        ], true);
        $transaction->bank = $this->paymentHandler->getBank($transaction);
        $transaction->reservation->setFailed($transaction);
        $this->redirectTxnFailed($transaction);
    }

    protected function get_bank_qtcode($cbn_code = '') {
        $bank_array = array(
            '057' => array(
                'ZIB', 'Zenith Bank'
            ), '044' => array(
                'ABP', 'Access Bank'
            ), '058' => array(
                'GTB', 'Guaranty Trust Bank'
            ), '039' => array(
                'STANBIC', 'Stanbic IBTC'
            ), '011' => array(
                'FBN', 'First Bank'
            ), '100' => array(
                'SUN', 'Suntrust Bank'
            ),
        );
        if ($cbn_code) {
            return $bank_array[$cbn_code][0];
        } else {
            $key = array_rand($bank_array);
            return $bank_array[$key][0];
        }
    }

    public
            function doNotify() {
        if (!$this->_request->isPost()) {
            $this->_request->redirect404('INVALID_REQ_METHOD');
        }

        $this->paymentHandler->notify($this->_request->getPostData(), $this);
    }

}
