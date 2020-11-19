<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2/19/2020
 * Time: 12:38 AM
 */

namespace App\Model;


class TransactionType
{
    const DR = "DR";
    const CR = "CR";

    public static function getPill($trans_type)
    {
        switch ($trans_type){
            case "DR":
                return "badge-danger";
            case "CR":
                return "badge-success";
            default:
                return null;
        }
    }

    public static function getTitle($trans_type)
    {
        switch ($trans_type){
            case "DR":
                return "Debit";
            case "CR":
                return "Credit";
            default:
                return null;
        }
    }
}
