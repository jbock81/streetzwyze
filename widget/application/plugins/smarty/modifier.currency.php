<?php

define('CURRENCY_DEFAULT', '₦');

function smarty_modifier_currency($amount, $prefix = CURRENCY_DEFAULT)
{

    return $prefix . number_format($amount, 2);
}