<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2/18/2020
 * Time: 10:06 PM
 */

namespace App\Model;


class RequestStatus
{
    const Declined = -1;
    const Pending = 0;
    const Approved = 1;
    const Cancelled = 2;
    const Insufficient = 3;
    const Failed = -2;

    public static function getReqTitle($status)
    {
        switch ($status){
            case self::Declined:
                return "Declined";
            case self::Cancelled:
                return "Cancelled";
            case self::Failed:
                return "Failed";
            case self::Pending:
                return "Processing";
            case self::Approved:
                return "Successful/Approved";
            case self::Insufficient:
                return "Insufficient Balance";
            default:
                return "";
        }
    }

    public static function getPill($status)
    {
        switch ($status){
            case self::Declined:
            case self::Failed:
                return "badge-danger";
            case self::Cancelled:
                return "badge-dark";
            case self::Pending:
            case self::Insufficient:
                return "badge-warning";
            case self::Approved:
                return "badge-success";
            default:
                return "";
        }
    }
}
