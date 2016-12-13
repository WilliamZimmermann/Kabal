<?php
namespace Application\Services;

class SystemFunctions
{
    public static function dateInvert($date, $format="brazilian"){
        if($format=="brazilian"){
            $hour = substr($date, 10, 6);
            $date = substr($date, 0, 10);
            $date = implode('/', array_reverse(explode('-', $date)));
            return $date."".$hour;
        }else{
            $hour = substr($date, 10, 6);
            $date = substr($date, 0, 10);
            $date = implode('-', array_reverse(explode('/', $date)));
            return $date."".$hour;
        }
    }
}

