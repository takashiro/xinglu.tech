<?php

if(!defined('S_ROOT')) exit('access denied');

class Reservation extends DBObject{

    const TABLE_NAME = 'reservation';

    public static $Status;
    const Pending = 0;
    const Accepted = 1;
    const Rejected = 2;
    const Completed = 3;

    public function __construct(int $id = 0){
        parent::__construct();
        if($id > 0){
            $this->fetch('*', 'id='.$id);
        }
    }

    public static function CheckConflict($deviceid, $time_start, $time_end){
        global $db, $tpre;
        $accepted = self::Accepted;
        return $db->fetch_first("SELECT id,time_start,time_end
            FROM {$tpre}reservation
            WHERE deviceid=$deviceid
                AND status=$accepted
                AND ((time_start<$time_end AND $time_start<time_end)
                    OR ($time_start<time_end AND time_start<$time_end))
            LIMIT 1");
    }

}

Reservation::$Status = array(
    lang('common', 'reservation_pending'),
    lang('common', 'reservation_accepted'),
    lang('common', 'reservation_rejected'),
    lang('common', 'reservation_completed'),
);
