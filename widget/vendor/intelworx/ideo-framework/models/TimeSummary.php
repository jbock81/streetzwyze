<?php

/**
 * @author Taiwo J
 * @copyright 2011
 */
class TimeSummary
{

    protected $_second = 1;
    protected $_minute;
    protected $_hour;
    protected $_day;
    protected $_week;
    protected $_month;
    protected $_year;
    protected static $instance;
    protected $_units = array();

    public function __construct()
    {
        $this->_minute = 60.0;
        $this->_hour = $this->_minute * 60;
        $this->_day = $this->_hour * 24;
        $this->_week = $this->_day * 7;
        $this->_month = $this->_day * 30;
        $this->_year = $this->_day * 365;

        $this->_units[$this->_second] = "second";
        $this->_units[$this->_minute] = "minute";
        $this->_units[$this->_hour] = "hour";
        $this->_units[$this->_day] = "day";
        $this->_units[$this->_week] = "week";
        $this->_units[$this->_month] = "month";
        $this->_units[$this->_year] = "year";
    }

    /**
     *
     * @param int $time specify time in seconds
     * @return type
     */
    public function summariseTime($time = 0)
    {
        if ($time < 2) {
            return "few seconds";
        }

        $time = (float)$time;

        switch ($time) {

            case $time >= $this->_year:
                $denominator = $this->_year;
                break;

            case $time >= $this->_month;
                $denominator = $this->_month;
                break;

            case $time >= $this->_week;
                $denominator = $this->_week;
                break;

            case $time >= $this->_day;
                $denominator = $this->_day;
                break;

            case $time >= $this->_hour;
                $denominator = $this->_hour;
                break;

            case $time >= $this->_minute;
                $denominator = $this->_minute;
                break;

            default:
                $denominator = $this->_second;
                break;
        }
        echo "";
        $mantissa = round($time / $denominator, 0);
        $unit = $mantissa > 1 ? $this->_units[$denominator] . "s" : $this->_units[$denominator];
        //special cases 
        return $mantissa . " " . $unit;
    }

    public static function summariseStringTime($str)
    {
        if (!self::$instance) {
            self::$instance = new TimeSummary();
        }

        $time_to_secs = strtotime($str);
        if (!$time_to_secs)
            return '';
        //die((string) (time() - $time_to_secs));
        return self::$instance->summariseTime(time() - $time_to_secs);
    }

    public function summariseUsingRelativeDays($time = '', $future = false)
    {
        if (is_numeric($time)) {
            $time = date('Y-m-d g:i:a', $time);
        }

        $today = new DateTime("today");
        $date_posted = new DateTime($time);
        $daysDifference = $date_posted->diff($today)
            ->format('%r%a');

        //echo $daysDifference;
        if ($daysDifference == 0 || ($future && $daysDifference > 0) || (!$future && $daysDifference < 0)) {
            return "Today";
        } elseif ($daysDifference == 1 && !$future) {
            return "Yesterday";
        } elseif ($daysDifference == -1 && $future) {
            return "Tomorrow";
        } else {
            $suffix = $future ? " from now" : " ago";
            return $this->summariseTime(abs(time() - strtotime($time))) . $suffix;
        }
    }

}

?>