<?php

if(!defined('IN_ADMINCP')) exit('access denied');

class ReservationMainModule extends AdminControlPanelModule{

    public function defaultAction(){
        global $db, $tpre;
        $reservations = $db->fetch_all("SELECT r.*,d.name AS devicename
            FROM {$tpre}reservation r
                LEFT JOIN {$tpre}device d ON d.id=r.deviceid
            WHERE 1");
        unset($r);

        extract($GLOBALS, EXTR_REFS | EXTR_SKIP);
        include view('list');
    }

}