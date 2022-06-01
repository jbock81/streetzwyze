<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 16/11/2015
 * Time: 11:48 AM
 */

function smarty_modifier_delivery_duration($duration)
{

    $unit = 'hour';
    $mantissa = $duration;
    $leftover = 0;

    if ($duration >= 24) {
        $unit = 'day';
        $mantissa = floor($duration / 24);
        $leftover = $duration % 24;
    }

    $dur = $mantissa . ' ' . $unit . ($mantissa > 1 ? 's' : '');
    if ($leftover) {
        $dur .= ' and ' . smarty_modifier_delivery_duration($leftover);
    }

    return $dur;
}