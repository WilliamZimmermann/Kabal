<?php
namespace Application\Services;

class SystemFunctions
{
    public static function dateInvert($date, $format="brazilian", $showSeconds=false){
        if($format=="brazilian"){
            if($showSeconds){
                $hour = substr($date, 10, 9);
            }else{
                $hour = substr($date, 10, 6);
            }
            $date = substr($date, 0, 10);
            $date = implode('/', array_reverse(explode('-', $date)));
            return $date."".$hour;
        }else{
            if($showSeconds){
                $hour = substr($date, 10, 9);
            }else{
                $hour = substr($date, 10, 6);
            }
            $date = substr($date, 0, 10);
            $date = implode('-', array_reverse(explode('/', $date)));
            return $date."".$hour;
        }
    }
}

