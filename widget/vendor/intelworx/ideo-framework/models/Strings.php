<?php

/**
 * Description of Strings
 *
 * @author intelWorX
 */
class Strings extends IdeoObject
{

    protected static $strings = array();

    public static function setLanguageFile($file, $merge = true)
    {
        if (file_exists($file)) {
            $parsed = parse_ini_file($file, TRUE);
            static::$strings = $merge ? array_replace_recursive(static::$strings, $parsed) : $parsed;
        } else {
            trigger_error("Strings file [{$file}] could not be loaded");
        }
    }

    public static function getString($key)
    {
        return self::get($key);
    }

    public static function get($key, $args = array(), $sectionJoin = true)
    {
        @list($section, $name) = explode('.', $key);
        if (!array_key_exists($section, self::$strings)) {
            return '';
        }

        if ($name !== null) {
            $value = isset(self::$strings[$section][$name]) ? self::$strings[$section][$name] : '';
        } else {
            $value = is_array(self::$strings[$section]) && $sectionJoin ? join(',', self::$strings[$section]) : self::$strings[$section];
        }

        return self::map($value, $args);
    }

    private static function map($value, array $vars = [])
    {
        if (is_array($value)) {
            $vals = [];
            foreach ($value as $key => $str) {
                $vals[$key] = self::map($str, $vars);
            }
            return $vals;
        } else {
            return preg_replace_callback('/:([a-z0-9_]+)\b/i', function ($matches) use ($vars) {
                return array_key_exists($matches[1], $vars) ? $vars[$matches[1]] : '';
            }, $value);
        }
    }

    public static function getValue($section, $name = null)
    {
        return $name === null ? self::$strings[$section] : self::$strings[$section][$name];
    }

    public static function format()
    {
        $args = func_get_args();
        $format = self::$strings[$args[0]][$args[1]];
        if ($format != null) {
            return call_user_func_array("sprintf", array_merge((array)$format, array_slice($args, 2)));
        }
        return "";
    }

    public static function addStrings($section, $values, $override = true)
    {
        if ($override) {
            self::$strings[$section] = $values;
        } else {
            self::$strings[$section] = array_merge((array)self::$strings[$section], $values);
        }
    }

}
