<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 02/11/2015
 * Time: 1:40 PM
 */

namespace models\entity;

use models\BankEntry;
use plugins\payments\BaseHandler;


/**
 * Class Transaction
 *
 * @property Reservation $reservation the reservation
 * @property BankEntry $bank
 *
 * @package models\entity
 */
class Transaction extends \ModelEntity
{
    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 2;

    protected function initRelations()
    {
        $this->setManyToOne('reservation', Reservation::manager());
    }

    public static function generateRef()
    {
        return 'CVRS' . time() . rand(1000, 9999);
    }

    public function setFailed($errorCode, $description = null)
    {
        return \DbTable::getDB()->doInTransactionOrRun(function () use ($errorCode, $description) {
            $this->update([
                'gateway_status_code' => $errorCode,
                'gateway_status_text' => $description,
                'status' => self::STATUS_FAILED,
            ]);
            return $this->reservation->setFailed();
        });
    }

    public function setCompleted($code, $description = null, $setReservationPaid = true)
    {

        $callback = function () use ($code, $description, $setReservationPaid) {
            $this->lockForUpdate();
            $updated = $this->update(array(
                'gateway_status_code' => $code,
                'gateway_status_text' => $description,
                'status' => self::STATUS_SUCCESS,
            ));


            if ($updated && $this->reservation) {
                if ($setReservationPaid) {
                    return $this->reservation->setPaid($this);
                } else {
                    return true;
                }
            }

            return false;
        };

        return \DbTable::getDB()->doInTransactionOrRun($callback);
    }

    protected function _setBank()
    {
        //@TODO change this to auto-detect payment handler when more payment methods are added.
        $this->_data['bank'] = BaseHandler::getDefault()->getBank($this);
    }

    public function isNewBank()
    {
        if ($this->bank) {
            return !in_array($this->bank->getCBNCode(), $this->reservation->getCustomerCBNCodes());
        }

        return false;
    }

    public function isFailed()
    {
        return intval($this->_data['status']) === self::STATUS_FAILED;
    }

    public function isSuccessful()
    {
        return intval($this->_data['status']) === self::STATUS_SUCCESS;
    }

    public function isPending()
    {
        return intval($this->_data['status']) === self::STATUS_PENDING;
    }
}