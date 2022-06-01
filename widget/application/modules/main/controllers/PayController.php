<?php

/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 02/11/2015
 * Time: 4:06 PM
 */

namespace main\controllers;

use main\forms\ReservationUpdateHandler;
use models\entity\Reservation;
use models\entity\Transaction;
use models\forms\validationrules\Url;
use models\PaymentUtils;
use models\utils\MobileNumberUtils;
use service\BadResponseException;
use service\ServiceException;

class PayController extends BaseController {

    const SESSION_BIO_CHECK = 'nameBioCheck';
    const MAX_NAME_CHECKS = 3;

    private function getProcessUrl(Reservation $reservation) {
        return $this->_request->buildURL('process', 'gateway', 'main', [
                    $reservation->id => ''
        ]);
    }

    public function doDefault() {
        $reservation = $this->getReservation();

        if ($this->_request->hasQueryParam('returnUrl') && ($url = $this->_request->getQueryParam('returnUrl', false)) && Url::rule()->validate($url)) {
            $reservation->update(['site_return_url' => $url]);
        }
        //start
        if ($this->_request->isPost() && $this->_request->getPostData('action') === 'begin') {
            $phoneNumber = MobileNumberUtils::normalize($this->_request->getPostData('mobile_number'));
            if ($phoneNumber) {
                try {
                    $customerBio = $this->webServiceClient->GetCustomerBio(['MobileNumber' => $phoneNumber]);
                    PaymentUtils::setCustomerBio($reservation->id, $customerBio, $phoneNumber);
                    $this->_view->customer = $customerBio;
                    if (!empty($customerBio)) {
                        if (!$customerBio->CustomerFlagged) {
                            $this->_forward('confirm-name');
                        } else {
                            $this->_displayAlert(null, 'payment.suspected_fraud');
                        }
                    }
                } catch (BadResponseException $e2) {
                    //new customer, set customer phone, redirect to gateway
                    PaymentUtils::updateCustomerBio($reservation, $phoneNumber);
                    //redirect to payment!!
                    //$this->_request->redirect($this->getProcessUrl($reservation));
                    $this->_forward('confirm-name');
                } catch (ServiceException $e) {
                    \SystemLogger::warn('ServiceException', $e->getMessage());
                    $this->_view->error = \Strings::get('errors.service_error');
                }
            } else {
                $this->_view->error = \Strings::get('errors.phone_invalid');
                $this->_view->data = $this->_request->getPostData();
            }
        }


        if ($this->_request->hasQueryParam('errMsg')) {
            $this->_view->error = \Strings::get($this->_request->getQueryParam('errMsg'));
        }
    }

    public function doConfirmName() {
        if (!$this->_request->isPost()) {
            $this->_request->redirect404('INVALID_ACTION_CALL');
        }

        $reservation = $this->getReservation();

        if ($this->_request->getPostData('action') === 'confirmName') {
            $bio = PaymentUtils::getCustomerBio($reservation->id);
            //then update, redirect to final payment
            if ($bio) {
                PaymentUtils::updateCustomerBioFromSession($reservation);
            }
            $this->_request->redirect('gateway/process/' . $reservation->id);
            //if($bio->CustomerLastName)
        }

        $this->_view->reservation = $reservation;
    }

    private function nameCheck($reservationId, $clear = false) {
        $session = \Session::getInstance();
        if ($clear) {
            $session->unsetFromNamespace($reservationId, self::SESSION_BIO_CHECK);
        } else {
            $checks = (int) $session->get($reservationId, self::SESSION_BIO_CHECK);
            if (++$checks >= self::MAX_NAME_CHECKS) {
                //redirect
                $session->unsetFromNamespace($reservationId, self::SESSION_BIO_CHECK);
                $this->_request->redirect(sprintf("pay?rid=%s&errMsg=%s", $reservationId, 'errors.name_check_failed'));
            } else {
                $session->set($reservationId, $checks, self::SESSION_BIO_CHECK);
            }
        }
    }

    public function doError() {

    }

    public function doInfo() {

    }

    public function doTxSuccess() {
        //send success
        $transaction = $this->_retrieveTransaction(Transaction::STATUS_SUCCESS);
        $reservation = $transaction->reservation;
        $handler = new ReservationUpdateHandler($transaction, $this->webServiceClient);
        $finalizeUrl = $this->_request->buildURL('tx-finalize') . '?'
                . http_build_query([
                    'id' => $this->_request->getQueryParam('id'),
                    'ref' => $this->_request->getQueryParam('ref'),
        ]);

        if ($reservation->isSecured()) {
            $this->_request->redirect($finalizeUrl);
        } else if ($reservation->hasCustomerInfo() && !$transaction->isNewBank()) {
            //post the money for update
            $handler->sendSecureFundUpdate();
            $this->_request->redirect($finalizeUrl);
        } else {
            //display form for sign-up and bank info entry
            if ($this->_request->isPost()) {
                //clear previously stored user data, as it is no longer needed.
                $data = $this->_request->getPostData();
                if ($handler->process($data)) {
                    //set processed
                    $this->_request->redirect($finalizeUrl);
                } else {
                    $errors = $handler->getErrors();
                    $this->_view->error = current($errors);
                    $this->_view->data = $data;
                }
            }
        }

        $handler->bootStrap($this->_view);
        $this->_view->transaction = $transaction;
        $this->_view->reservation = $reservation;
    }

    public function doTxFinalize() {
        $this->_view->transaction = $this->_retrieveTransaction(Transaction::STATUS_SUCCESS);
    }

    public function doTxFailed() {
        $transaction = $this->_retrieveTransaction(Transaction::STATUS_FAILED);
        $handler = new ReservationUpdateHandler($transaction, $this->webServiceClient);
        $handler->sendSecureFundUpdate();
        $this->_view->transaction = $transaction;
    }

    /**
     *
     * @param int $status
     * @return Transaction transaction
     */
    protected function _retrieveTransaction($status) {
        $where = \DbTableWhere::get()
                ->where('id', $this->_request->getQueryParam('id'))
                ->where('our_ref', $this->_request->getQueryParam('ref'))
                ->where('status', $status);

        $transaction = Transaction::manager()->getEntityWhere($where);
        if (!$transaction) {
            $this->_request->redirect404('TransactionNotFound');
        }

        return $transaction;
    }

}
