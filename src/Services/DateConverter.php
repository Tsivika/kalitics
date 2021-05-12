<?php

namespace App\Services;

class DateConverter
{
    /**
     * @param $date
     * @return false|string
     */
    public function DateFR2DateSQL ($date)
    {
        $day    = substr($date,0,2);
        $month  = substr($date,3,2);
        $year   = substr($date,6,4);
        $hour   = substr($date,12,2);
        $minute = substr($date,15,2);
        $second = substr($date,18,2);
        $timestamp= mktime($hour,$minute,$second,$month,$day,$year);

        return date('Y-m-d',$timestamp);
    }
}
