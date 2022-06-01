<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 21/12/2015
 * Time: 6:00 PM
 */

namespace models\forms\validationrules;


if (version_compare(PHP_VERSION, '7.0.0') < 0) {
    /**
     * Class Float
     * @package models\forms\validationrules
     * @deprecated since version 2.0.0-dev
     * Please use FloatType instead
     */
    class Float extends FloatType
    {

    }
} else {
    throw new \InvalidArgumentException('The class no longer exists in PHP 7.');
}
