<?php

namespace models;

use models\entity\Reservation;

/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 03/11/2015
 * Time: 12:18 AM
 */
class PaymentUtils extends \IdeoObject
{

    const SESSION_NS_UTILS = '_PayUtils_';

    public static function setCustomerBio($reservationId, $customerBio, $mobileNumber)
    {
        $key = self::bioKey($reservationId);
        $customerBio->CustomerMobile = $mobileNumber;
        \Session::getInstance()->set($key, $customerBio, self::SESSION_NS_UTILS);
    }

    private static function bioKey($reservationId)
    {
        return 'bio_' . $reservationId;
    }

    private static function custKey($reservationId)
    {
        return 'cust_' . $reservationId;
    }

    public static function getCustomerBio($reservationId)
    {
        $bioKey = self::bioKey($reservationId);
        return \Session::getInstance()->get($bioKey, self::SESSION_NS_UTILS);
    }

    public static function updateCustomerBioFromSession(Reservation $reservation)
    {
        $bio = self::getCustomerBio($reservation->reservation_id);
        if (!empty($bio)) {
            return self::updateCustomerBio($reservation, $bio->CustomerMobile, "{$bio->CustomerFirstName} {$bio->CustomerLastName}", $bio->CustomerId, array_filter($bio->CustomerCbnCode));
            self::clearCustomerBio($reservation->id);
        }
        return false;
    }

    public static function updateCustomerBio(Reservation $reservation, $mobileNumber = null, $name = null, $custId = null, array $bankCodes = [])
    {
        return $reservation->update([
            'customer_mobile_no' => $mobileNumber,
            'customer_name' => $name,
            'customer_id' => $custId,
            'customer_cbn_codes' => join('|', $bankCodes)
        ]);
    }

    public static function setPassedData($reservationId, array $data)
    {
        $toStore = [];
        foreach ($data as $key => $value) {
            $matches = [];
            if (preg_match('/^Customer([a-zA-Z0-9]+)$/', $key, $matches)) {
                $toStore[lcfirst($matches[1])] = $value;
            }
        }

        if (!empty($toStore)) {
            \Session::getInstance()->set(self::custKey($reservationId), $toStore, self::SESSION_NS_UTILS);
        }

    }

    public static function getPassedData($reservationId)
    {
        $key = self::custKey($reservationId);
        $session = \Session::getInstance();
        return $session->get($key, self::SESSION_NS_UTILS) ?: [];
    }

    public static function clearCustomerBio($reservationId)
    {
        $key = self::bioKey($reservationId);
        \Session::getInstance()->unsetFromNamespace($key, self::SESSION_NS_UTILS);
    }

    public static function clearPassedData($reservationId)
    {
        $key = self::custKey($reservationId);
        \Session::getInstance()->unsetFromNamespace($key, self::SESSION_NS_UTILS);
    }
}
