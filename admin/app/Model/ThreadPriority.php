<?php


namespace App\Model;


class ThreadPriority
{
    const Low = "Low";
    const Medium = "Medium";
    const High = "High";

    public static function getBadge($priority)
    {
        switch ($priority){
            case self::Low:
                return 'badge-success';
            case self::Medium:
                return 'badge-warning';
            case self::High:
                return 'badge-danger';
        }
    }
}
