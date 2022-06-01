<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 02/11/2015
 * Time: 9:34 PM
 */

namespace models\utils;


use models\forms\validationrules\PhoneNumber;
use models\forms\validationrules\Text;
use Respect\Validation\Rules\Phone;

class MobileNumberUtils extends \IdeoObject
{
    public static function normalize($number)
    {
        $numberV = Text::rule(0, 11, 11, '/^\d+$/');
        if (!$numberV->validate($number)) {
            return null;
        }

        if (strlen($number) < 10) {
            return $number;
        }

        return '0' . substr($number, -10);
    }
}