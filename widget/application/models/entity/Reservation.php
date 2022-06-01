<?php

namespace models\entity;

/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 02/11/2015
 * Time: 1:40 PM
 */
class Reservation extends \ModelEntity
{

    const PAY_STATUS_PENDING = 0;
    const PAY_STATUS_PAID = 1;
    const PAY_STATUS_FAILED = 2;
    const PAY_STATUS_CANCELED = 3;

    protected function initRelations()
    {
        $this->setOneToMany('transactions', Transaction::manager());
    }

    public function isPaid()
    {
        return intval($this->_data['pay_status']) === self::PAY_STATUS_PAID;
    }

    public function isPending()
    {
        return intval($this->_data['pay_status']) === self::PAY_STATUS_PENDING;
    }

    public function isCanceled()
    {
        return intval($this->_data['pay_status']) === self::PAY_STATUS_CANCELED;
    }

    public function isFailed()
    {
        return intval($this->_data['pay_status']) === self::PAY_STATUS_FAILED;
    }

    protected function _setId()
    {
        $this->_data['id'] = &$this->_data['reservation_id'];
    }

    public function getDueAmount()
    {
        return floatval($this->_data['order_amount']);
    }

    public function setPaid(Transaction $transaction)
    {
        //set paid
        $this->update(['pay_status' => self::PAY_STATUS_PAID]);
        return true;
    }

    public function setCanceled()
    {
        //set paid
        $this->update(['pay_status' => self::PAY_STATUS_CANCELED]);
        return true;
    }

    public function setFailed()
    {
        return $this->update(['pay_status' => self::PAY_STATUS_FAILED]);
    }

    public function hasCustomerInfo()
    {
        return !!$this->_data['customer_id'];
    }

    public function getCustomerCBNCodes()
    {
        return array_unique(explode('|', $this->_data['customer_cbn_codes']));
    }

    public function setSecured(array $extraData = [])
    {
        return $this->update(array_merge($extraData, [
            'secured' => 1,
            'fund_secure_date' => \DbExpr::make('now()')
        ]));
    }

    public function isSecured()
    {
        return !!$this->_data['secured'];
    }

    public function hasCancelCharge()
    {
        return !!$this->_data['refund_delivery'];
    }
}