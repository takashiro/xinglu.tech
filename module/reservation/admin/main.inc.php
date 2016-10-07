<?php

if(!defined('IN_ADMINCP')) exit('access denied');

class ReservationMainModule extends AdminControlPanelModule{

    public function defaultAction(){
        $condition = array();

        if(isset($_GET['deviceid'])){
            $deviceid = intval($_GET['deviceid']);
            $condition[] = 'r.deviceid='.$deviceid;
        }

        $condition = $condition ? '('.implode(') AND (', $condition).')' : '1';

        global $db, $tpre;
        $reservations = $db->fetch_all("SELECT r.*,d.name AS devicename, u.account, u.realname
            FROM {$tpre}reservation r
                LEFT JOIN {$tpre}device d ON d.id=r.deviceid
                LEFT JOIN {$tpre}user u ON u.id=r.userid
            WHERE $condition
            ORDER BY r.deviceid,r.status,r.time_start,r.time_end");
        unset($r);

        extract($GLOBALS, EXTR_REFS | EXTR_SKIP);
        include view('list');
    }

    public function acceptAction(){
        global $db, $tpre;
        $id = intval($_GET['id'] ?? 0);

        $reservation = new Reservation($id);
        if(!$reservation->exists()){
            showmsg('reservation_does_not_exist', 'back');
        }

        $r = Reservation::CheckConflict($reservation->deviceid, $reservation->time_start, $reservation->time_end);
        if($r){
            $hour_start = intval(rdate($r['time_start'], 'H'));
            $hour_end = $hour_start + round(($r['time_end'] - $r['time_start']) / 3600);
            showmsg(array('reservation_can_not_be_accepted_due_to_conflict', $hour_start, $hour_end), 'back');
        }

        $new_status = Reservation::Accepted;
        $db->query("UPDATE {$tpre}reservation SET status=$new_status WHERE id=$id");
        showmsg('reservation_is_accepted', 'refresh');
    }

    public function rejectAction(){
        global $db, $tpre;
        $id = intval($_GET['id'] ?? 0);
        $new_status = Reservation::Rejected;
        $db->query("UPDATE {$tpre}reservation SET status=$new_status WHERE id=$id");
        showmsg('reservation_is_rejected', 'refresh');
    }

}
