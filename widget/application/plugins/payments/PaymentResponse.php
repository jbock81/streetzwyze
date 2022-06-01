<?php

namespace plugins\payments;

use models\entity\Transaction;

/**
 * Description of PaymentResponse
 *
 * @author intelWorX
 */
class PaymentResponse extends \IdeoObject
{

    protected $responseCode;
    protected $amount;
    protected $statusText;
    protected $successful;
    protected $transactionId;
    /**
     * @var Transaction
     */
    protected $transaction;

    public function __construct($responseCode, $amount = 0, $statusText = null, $successful = false)
    {
        $this->setSynthesizeFields();
        $this->responseCode = $responseCode;
        $this->amount = $amount;
        $this->statusText = $statusText;
        $this->successful = $successful;
    }

    public function isSuccessful()
    {
        return $this->successful;
    }

    public function wasSuccessful()
    {
        return $this->isSuccessful();
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param mixed $responseCode
     * @return PaymentResponse
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return PaymentResponse
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return null
     */
    public function getStatusText()
    {
        return $this->statusText;
    }

    /**
     * @param null $statusText
     * @return PaymentResponse
     */
    public function setStatusText($statusText)
    {
        $this->statusText = $statusText;
        return $this;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        if (!$this->transaction && $this->transactionId) {
            $this->transaction = Transaction::findOne($this->transactionId);
        }
        return $this->transaction;
    }

    /**
     * @param Transaction $transaction
     * @return PaymentResponse
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     *
     */
    public function __sleep()
    {
        $this->transactionId = $this->transaction->id;
        $this->transaction = null;
        return array_keys(get_object_vars($this));
    }


}

