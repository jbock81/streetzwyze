<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 01/11/2015
 * Time: 10:03 PM
 */

namespace main\controllers;


use models\entity\Reservation;
use models\entity\Transaction;
use service\WebServiceClient;

class BaseController extends \BaseController
{
    /**
     * @var WebServiceClient
     */
    protected $webServiceClient;

    protected function _initWebService()
    {
        $this->webServiceClient = new WebServiceClient();
    }


    public function unAuthorized()
    {
        header('HTTP/1.1 401 Unauthorized', true);
        header('WWW-Authenticate: OAuth realm=""');
    }

    public function forbidden()
    {
        header('HTTP/1.1 403 Access denied', true);
    }

    public function notFound()
    {
        header('HTTP/1.1 404 Not Found', true);
    }

    public function badRequest()
    {
        header('HTTP/1.1 400 Bad Request', true);
    }

    public function serverError()
    {
        header('HTTP/1.1 503 Internal Server Error', true);
    }

    public function success()
    {
        header('HTTP/1.1 200 OK', true);
    }

    public function setAsJSON()
    {
        header('Content-Type: application/json; charset=utf8', true);
    }

    public function setAsHTML()
    {
        header('Content-Type: text/html; charset=utf8', true);
    }

    public function setAsXML()
    {
        header('Content-Type: text/xml; charset=utf8', true);
    }

    public function setAsPlain()
    {
        header('Content-Type: text/plain; charset=utf8', true);
    }

    protected function _displayAlert($error = null, $errorCode = null, $type = 'error')
    {
        $this->_view->message = $error ?: \Strings::get($errorCode);
        $this->_forward($type, 'pay');
    }

    /**
     * @param string|null $reservationId
     * @return Reservation
     */
    public function getReservation($reservationId = null)
    {
        $reservationId = $reservationId ?: $this->_request->getQueryParam('rid');

        if ($reservationId) {
            $reservation = Reservation::findOne($reservationId);
        }

        if (!$reservationId || !$reservation) {
            $this->_displayAlert(null, 'payment.reservation_not_found');
        }

        if (!$reservation->isPending()) {
            $this->_displayAlert(null, $reservation->isPaid() ? 'payment.reservation_paid' : 'payment.reservation_canceled', 'info');
        }

        return $reservation;
    }

    public function redirectTxnSuccess(Transaction $transaction) {
        $data = array(
            'ref' => $transaction->our_ref,
            'id' => $transaction->id,
        );
        $this->_request->redirect('pay/tx-success/?' . http_build_query($data));
    }

    public function redirectTxnFailed(Transaction $transaction) {
        $data = array(
            'ref' => $transaction->our_ref,
            'id' => $transaction->id,
        );
        $this->_request->redirect('pay/tx-failed/?' . http_build_query($data));
    }
}
