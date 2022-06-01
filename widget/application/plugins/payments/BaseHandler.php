<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace plugins\payments;

use main\controllers\BaseController;
use models\BankEntry;
use models\entity\Reservation;
use models\entity\Transaction;
use plugins\payments\quickteller\PayWithQuickteller;

/**
 * Description of BaseHandler
 *
 * 
 */
abstract class BaseHandler extends \IdeoObject
{

    const SESSION_REQUERY_NS = '_TxnRequery_';


    public function __construct()
    {
    }

    /**
     * @param Reservation $reservation
     * @param null $ref
     * @return mixed|Transaction|null
     */
    protected function createTransaction(Reservation $reservation, $ref = null, $gatewayRef = null)
    {
        return Transaction::saveEntity([
            'our_ref' => $ref ?: Transaction::generateRef(),
            'memo' => \Strings::get('payment.reservation_memo', [
                'reservationId' => $reservation->id,
            ]),
            'amount' => $reservation->getDueAmount(),
            'reservation_id' => $reservation->id,
            'ip_address' => ip2long(explode(':', getRealIpAddress())[0]),
            'gateway_ref' => $gatewayRef,
        ], true);
    }

    /**
     * @param Reservation $reservation
     * @param BaseController $controller
     * @return mixed
     */
    abstract public function process(Reservation $reservation, BaseController $controller);

    /**
     * @param array $data the data received from gateway notification
     * @param BaseController $controller calling controller.
     * @return mixed
     */
    public function notify($data, BaseController $controller)
    {
        $request = $controller->getRequest();
        $reservation = $controller->getReservation($request->getExtras(0));
        $response = $this->_verify($data, $reservation);

        $transaction = $response->getTransaction();
        if (!$transaction) {
            throw new \RuntimeException(get_class($this) . '->_verify() did not set the transaction.');
        }

        if (!$response->isSuccessful()) {
            //mark as failed, redirect to failure.
            $transaction->setFailed($response->getResponseCode(), $response->getStatusText());
            $controller->redirectTxnFailed($transaction);
        } else {
            //mark as completed, redirect to success.
            $transaction->setCompleted($response->getResponseCode(), $response->getStatusText(), true);
            $controller->redirectTxnSuccess($transaction);
        }
    }

    /**
     *
     * Verifies the response from the gateway,
     * @param $data the data received from the Gateway
     * @param Reservation $reservation the reservation
     * @return PaymentResponse the generated response.
     */
    abstract protected function _verify($data, Reservation $reservation);

    /**
     *
     * @param string $ref
     * @return Transaction transaction or null if not found.
     */
    public function getTransaction($ref)
    {
        return Transaction::findOne($ref, 'our_ref', true);
    }

    abstract public function getName();

    /**
     *
     * @staticvar BaseHandler $current
     * @return BaseHandler
     */
    final public static function getDefault()
    {
        static $current = null;
        if (!$current) {
            $current = new PayWithQuickteller();
        }

        return $current;
    }

    /**
     * @return PaymentResponse response from gateway.
     */
    public function requery(Transaction $transaction, $reload = false)
    {
        $ref = $transaction->our_ref;
        $session = \Session::getInstance();
        if ($reload || !($response = $session->get($ref, self::SESSION_REQUERY_NS))) {
            if (($response = $this->_reQuery($transaction))) {
                $session->set($ref, $response, self::SESSION_REQUERY_NS);
            }
        }

        return $response;
    }

    /**
     * Requery gateway for transaction information
     * @param Transaction $transaction
     * @return PaymentResponse
     */
    abstract protected function _reQuery(Transaction $transaction);

    /**
     * Guesses the BankRegistry entry for this transaction
     * @param Transaction $transaction
     * @return BankEntry
     */
    abstract public function getBank(Transaction $transaction);
}
