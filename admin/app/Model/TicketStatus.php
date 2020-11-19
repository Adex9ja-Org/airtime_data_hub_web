<?php


namespace App\Model;


class TicketStatus
{
    const opened = "opened";
    const closed = "closed";

    public static function getBadge($ticket_status)
    {
        switch ($ticket_status){
            case self::opened:
                return 'badge-success';
            case self::closed:
                return 'badge-danger';
        }
        return '';
    }
}
