<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author intelworx
 */
class GeneralUtils
{

    const CASE_LOWER = -1;
    const CASE_NORMAL = 0;
    const CASE_UPPER = 1;
    const CAMEL_CASE_STYLE_UCFIRST = 1;
    const CAMEL_CASE_STYLE_LCFIRST = 2;

    protected static $allowedTags = "<a><b><blockquote><code><del><dd><dl><dt><em><h1><h2><h3><i><img><kbd><li><ol><p><u><pre><s><sup><sub><strike><span><font><strong><ul><br/><hr/>";

    /**
     *
     * @param string $str
     * @param string $with
     * @return string the new string
     */
    public static function replaceNonAlphaNumeric($str, $with = '_')
    {
        return preg_replace('/[^A-Za-z0-9_]/', $with, $str);
    }

    /**
     * convert camelCasedString to delimeted_string
     * @param string $str camel cased string
     * @param type $delimeter
     * @param int $case to use for pieces (class::CASE_LOWER, class::CASE_UPPER, class::CASE_NORMAL),
     * defaults to class::CASE_LOWER
     * @return string delimeted_string
     *
     */
    public static function camelCaseToDelimited($str, $delimeter = '_', $case = self::CASE_LOWER)
    {
        $pieces = preg_split('/([[:upper:]][[:lower:]]+)/', $str, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        foreach ($pieces as $i => $piece) {
            if ($case == self::CASE_LOWER) {
                $pieces[$i] = strtolower($piece);
            } elseif ($case == self::CASE_UPPER) {
                $pieces[$i] = strtoupper($piece);
            }
        }
        return join($delimeter, $pieces);
    }

    /**
     * convert delimeted_string to camelCasedString
     * @param string $string the delimeted string to convert
     * @param string $delimeterPattern delimeter pattter to use to split string, must be compatible with preg_spit
     * defaults to _
     * @param string $style Style to use class::CAMEL_CASE_STYLE_LCFIRST (e.g. functionName, varName) or
     * class::CAMEL_CASE_STYLE_UCFIRST (e.g. ClassName)
     * defaults to class::CAMEL_CASE_STYLE_LCFIRST
     * @return string camelCasedString
     */
    public static function delimetedToCamelCased($string, $delimeterPattern = '/_/', $style = self::CAMEL_CASE_STYLE_LCFIRST)
    {
        $parts = preg_split($delimeterPattern, $string);

        foreach ($parts as $i => $part) {
            if ($i == 0 && $style == self::CAMEL_CASE_STYLE_LCFIRST) {
                $parts[$i] = strtolower($part);
            } else {
                $parts[$i] = ucfirst(strtolower($part));
            }
        }

        return join('', $parts);
    }

    /**
     * remove prefix from string
     * @param str $string
     * @param str $prefix
     * @return string
     */
    public static function removePrefixFromString($string, $prefix)
    {
        return substr($string, strlen($prefix));
    }

    public static function getAllowedTags()
    {
        return self::$allowedTags;
    }

    public static function stripTags($str)
    {
        return strip_tags($str, self::$allowedTags);
    }

    public static function sanitize($str, $separator = '-', $style = self::CASE_LOWER)
    {
        $sanitised = preg_replace('/[^a-zA-Z0-9-_]+/', $separator, $str);
        if ($style == self::CASE_UPPER) {
            return strtoupper($sanitised);
        } else if ($style == self::CASE_LOWER) {
            return strtolower($sanitised);
        } else {
            return $sanitised;
        }
    }

    static public function startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

    static public function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    public static function generateUUID()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }


    /**
     * Gets the URL of a file or folder based on its location in the source.
     * Uses a combination of `APP_ROOT` and `BASE_URL` to determine file URL
     * @param string $file apath to file or folder
     * @param bool $relative specifies if the returned URL should be relative to BASE_URL or not
     * @return string the URL if the file is accessible over the web, empty string if not.
     */
    public static function getFolderUrl($file, $relative = false)
    {
        $file = realpath($file) . DIRECTORY_SEPARATOR;
        if (strpos($file, APP_ROOT) === false) {
            return '';
        }

        return str_replace(array(APP_ROOT, '\\'), array($relative ? '' : BASE_URL, '/'), $file);
    }

    /**
     * Generates random string of specified length
     * @param int $length the length of the string, maximum of 32
     * @return string the generated string.
     */
    public static function getRandomCode($length = 15)
    {
        return substr(base64_encode(sha1(uniqid('random_code'))), 0, $length);
    }


    /**
     * Gets the extension from file name
     * @param string $filename path to the file
     * @return string the lowercase extension of the file
     */
    public static function getFileExtension($filename)
    {
        $tmp = explode('.', $filename);
        return strtolower(end($tmp));
    }

    /**
     * Capitalizes a given string
     * @param string $str the string to capitalize
     * @param bool $lc_rest if other characters should be lowercased.
     * @return string the capitalized string.
     */
    public static function capitalize($str, $lc_rest = true)
    {
        TemplateEngine::getInstance()->loadPlugin('smarty_modifier_capitalize');
        return smarty_modifier_capitalize($str, false, $lc_rest);
    }


    /**
     * Converts date of the specified format into timestamp
     * @param string $date date string
     * @param string $format date format
     * @return long the timestamp
     */
    public static function dateToTimestamp($date, $format)
    {
        $dateTime = DateTime::createFromFormat($format, $date);
        /* @var $dateTime DateTime */
        return $dateTime->getTimestamp();
    }

    /**
     * Normalizes a given array so that inumeric values are converted to numeric values
     *  while dates are covnverted to ISO standard
     * @param mixed $object the variable to normalize. Passed by reference,
     *  its value is altered
     */
    public static function filterObject(&$object)
    {
        if (is_array($object)) {
            foreach ($object as &$val) {
                self::filterObject($val);
            }
        } else {
            if (preg_match('/^\d+\.\d+$/', $object)) {
                $object = doubleval($object);
            } elseif (is_numeric($object) && $object < PHP_INT_MAX) {
                $object = intval($object);
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $object)) {
                $object = date("c", strtotime($object));
            }
        }
    }

    /**
     *
     * Gets date from a very large Timestamp
     * @param string $format format
     * @param long $longTs Timestamp that, can exceeed PHP_INT_MAX
     * @return string formatted date
     */
    public static function date($format, $longTs = null)
    {
        if (is_null($longTs)) {
            $longTs = time();
        }

        $date = DateTime::createFromFormat('U', $longTs);
        return $date->format($format);
    }

    /**
     * Converts any date or time to the specified format.
     *  - If there's a specified `$from_format`, it will be used in generating the date object
     *  - If the input is all numeric, then the date will be converted using the number as TS
     *  - if the date can be passed through strtotime() function, it will be used,
     * Otherwise, the date is not valid.
     *
     * @param mixed $dateOrTime date string or timestamp
     * @param string $format format to format the date
     * @param string $from_format source date format if any, otherwise, it will be guessed
     * @return string|null formatted date if date is valid, null if not.
     */
    public static function anyDateToFormat($dateOrTime, $format = 'd-m-Y', $from_format = null)
    {
        $dateOrTime = trim($dateOrTime);

        if ($dateOrTime && strstr($dateOrTime, '0000-00-00') === false) {
            if (preg_match('/^\d+$/', $dateOrTime)) {
                $ts = DateTime::createFromFormat('U', $dateOrTime);
            } else if ($from_format) {
                $ts = DateTime::createFromFormat($from_format, $dateOrTime);
            } else {
                try {
                    $ts = new DateTime($dateOrTime);
                } catch (Exception $ex) {
                    $ts = false;
                }
            }
        }

        return $ts ? $ts->format($format) : null;
    }
}
