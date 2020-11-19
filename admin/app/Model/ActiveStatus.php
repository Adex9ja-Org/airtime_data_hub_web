<?php


namespace App\Model;


class ActiveStatus
{
    const InActive = 0;
    const Active = 1;

    public static function getTitle($status)
    {
        switch ($status){
            case self::InActive:
                return "DeActivated";
            case self::Active:
                return "Active";
            default:
                return "Unknown";
        }
    }

    public static function getPill($status)
    {
        switch ($status){
            case self::InActive:
                return "badge-danger";
            case self::Active:
                return "badge-success";
            default:
                return "";
        }
    }
}
