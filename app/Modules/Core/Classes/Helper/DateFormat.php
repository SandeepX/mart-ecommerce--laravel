<?php

namespace App\Modules\Core\Classes\Helper;


use Carbon\Carbon;

class DateFormat
{
    public static function dateSample($date)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $date);
        $new = $date->format('Y/m/d');
        return $new;
    }

    public static function formatDate($date, $time = null)
    {
        return ($time == null) ? date('M d, Y ', strtotime($date)) : date('M d, Y H:i:s A', strtotime($date));
    }

    public static function dateDifference($from, $to)
    {
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);
        return $from->diffInDays($to);
    }

    public static function dateTimeToDate($date)
    {
        return explode(' ', $date)[0];
    }

    public static function dateDiff($to)
    {
        $datetime1 = new \DateTime();
        $datetime2 = new \DateTime($to);
        $interval = $datetime1->diff($datetime2);
        $elapsed_for = $interval->format(' %a days %h hours %i mins');

        return ['elapsed'=> $interval->invert,'elapsed_for'=>$elapsed_for];
    }

    public static function ago($timestamp)
    {
        return ($timestamp->diffInSeconds() < 30)
                 ? 'Just Now' : Carbon::createFromTimeStamp(strtotime($timestamp))->diffForHumans() ;
    }

}