<?php

function debug_op($var, $exit = 0)
{
    $isCli = ApplicationFrontEnd::getApplication()->isCli();
    if (!ApplicationFrontEnd::getApplication()->isProd()) {
        if (!$isCli) {
            echo "<pre>";
        }
        var_dump($var);
        if (!$isCli) {
            echo "</pre>";
        }
        if ($exit) {
            exit;
        }
    }
}

function secure($value)
{
    if (is_array($value)) {
        $value = array_map('secure', $value);
    } else {
        if (function_exists('get_magic_quotes_gpc') && !get_magic_quotes_gpc()) {
            $value = htmlspecialchars($value, 0, 'UTF-8');
        } else {
            $value = htmlspecialchars(stripslashes($value), 0, 'UTF-8');
        }
        $value = str_replace("\\", "\\\\", $value);
    }
    return $value;
}

function resamplePhoto($photo, $photo_dest, $quality = 80, $file_maxwidth = "600", $file_maxheight = "600")
{
    $file_dimensions = @getimagesize($photo);
    $width = $file_dimensions[0];
    $height = $file_dimensions[1];

    if ($height > $file_maxheight) {
        $width = floor($width * $file_maxheight / $height);
        $height = $file_maxheight;
    }

    if ($width > $file_maxwidth) {
        $height = floor($height * $file_maxwidth / $width);
        $width = $file_maxwidth;
    }

    //width & height are the resccaled dimensions

    $file = imagecreatetruecolor($width, $height);
    //$file2 = imagerectangle()

    $new = imagecreatefromstring(file_get_contents($photo));

    for ($i = 0; $i < 256; $i++) {
        imagecolorallocate($file, $i, $i, $i);
    }
    imagecopyresampled($file, $new, 0, 0, 0, 0, $width, $height, $file_dimensions[0], $file_dimensions[1]);

    $result = imagejpeg($file, $photo_dest, $quality);
    ImageDestroy($new);
    ImageDestroy($file);
    return $result;
}

function createDateEntry(& $data, $fld = 'Date')
{
    $yrFld = $fld . '_Year';
    $mnFld = $fld . '_Month';
    $dayFld = $fld . '_Day';

    $hrFld = $fld . '_Hour';
    $minFld = $fld . '_Minute';

    $data[$yrFld] = $data[$yrFld] ? $data[$yrFld] : 0;
    $data[$mnFld] = $data[$mnFld] ? $data[$mnFld] : 0;
    $data[$dayFld] = $data[$dayFld] ? $data[$dayFld] : 0;
    if (!$data[$dayFld] && $data[$yrFld]) {
        $data[$dayFld] = 1;
    }

    $data[$hrFld] = $data[$hrFld] ? $data[$hrFld] : 0;
    $data[$minFld] = $data[$minFld] ? $data[$minFld] : 0;

    $data[$fld] = "{$data[$yrFld]}-{$data[$mnFld]}-{$data[$dayFld]} {$data[$hrFld]}:{$data[$minFld]}:00";
    unset($data[$yrFld], $data[$mnFld], $data[$dayFld], $data[$hrFld], $data[$minFld]);
}

function my_range($start, $stop, $step = 1)
{
    if ($start > $stop && $step > 0) {
        $tmp = $start;
        $start = $stop;
        $stop = $tmp;
    }

    $myArr = array();

    for ($i = $start; $i <= $stop; $i += $step) {
        $myArr[] = $i;
    }
    return $myArr;
}

function throwException(Exception $exception)
{
//    if (SYSTEM_ENV === ENVIRONMENT_LIVE) {
//        trigger_error("exception thrown with trace:\n " . $exception->getTraceAsString(), E_USER_WARNING);
//    }

    throw $exception;
}

function getDateForDb($date_with_slash = '')
{
    $pieces = explode('/', $date_with_slash);
    if (!$date_with_slash) {
        return false;
    }

    list($d, $m, $y) = $pieces;
    $ts = mktime(0, 0, 0, $m, $d, $y);
    return date('Y-m-d', $ts);
}

function searchArrayNoCase($needle, $array)
{
    $key = array_search($needle, $array);
    if ($key !== false) {
        return $key;
    }

    foreach ($array as $i => $val) {
        if (strcasecmp($val, $needle) === 0) {
            return $i;
        }
    }
    return false;
}

function change_to_int(&$val, $key)
{
    $val = (int)$val;
}

function grep_img($text = '')
{
    if (preg_match('/<img.+src=\s*[\'"]([a-zA-Z0-9_\.\/:\s]+)[\'"].*>/i', $text, $match)) {
        return $match[1];
    }
    return '';
}

function summarise_time($time = '')
{
    return TimeSummary::summariseStringTime($time);
}

function validate_email($string)
{
    if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,3})$/i", $string)) {
        return true;
    } else {
        return false;
    }
}

function copyElementsAtKey($keys, $sourceArray, &$outArray, $setOnly = false)
{
    foreach ($keys as $key) {
        if ($setOnly) {
            if (array_key_exists($key, $sourceArray)) {
                $outArray[$key] = $sourceArray[$key];
            }
        } else {
            $outArray[$key] = $sourceArray[$key];
        }
    }
}

function getRealIpAddress()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $iparray = explode(',', $ip);
    return $iparray[0];
}

function array_prepend(&$array, $var)
{
    $array = array_merge((array)$var, $array);
}

function array_is_assoc($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}

function array_has_numeric_keys($arr)
{
    $op = array_filter(array_keys($arr), function ($val) {
        return is_numeric($val);
    });

    return count($op) === count($arr);
}

function array_same_keys($arr1, $arr2)
{
    return array_contains_key($arr1, $arr2) && array_contains_key($arr2, $arr1);
}

function array_contains_key($smallerArray, $biggerArray)
{
    return count(array_diff_key($smallerArray, $biggerArray)) === 0;
}
