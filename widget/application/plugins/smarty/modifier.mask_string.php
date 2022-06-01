<?php
/**
 *
 * This modifies masks a string, by replacing some of the characters with the specified $maskStr
 * @param $str the string to mask. Must be supplied
 * @param string $maskStr the mask string to use
 * @param int $start characters to show at the beginning
 * @param int $end characters to show at the end.
 * @param int $maskLength the length of the mask
 * @return string
 */
function smarty_modifier_mask_string($str, $maskStr = '*', $start = 1, $end = 2, $maskLength = -1)
{
    $strLength = strlen($str);
    if ($strLength < 1) {
        return $maskStr;
    }

    while (($start + $end) > ($strLength - 1)) {
        if ($start > 0) {
            --$start;
        } else {
            --$end;
        }
    }

    if ($maskLength < 1) {
        $maskLength = $strLength - $start - $end;
    }

    return substr($str, 0, $start)
    . str_repeat($maskStr, $maskLength)
    . substr($str, -$end);
}