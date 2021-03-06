<?php

if(!defined('IN_ADMINCP')) exit('access denied');

class ReservationMainModule extends AdminControlPanelModule{

    public function defaultAction(){
        extract($GLOBALS, EXTR_REFS | EXTR_SKIP);

        $condition = array();
        $query_string = array();

		if(!$_G['admin']->isSuperAdmin()){
			$condition[] = 'd.adminid='.$_G['admin']->id;
		}

        if(isset($_GET['deviceid'])){
            $deviceid = intval($_GET['deviceid']);
            $condition[] = 'r.deviceid='.$deviceid;
            $query_string['deviceid'] = $deviceid;
        }

        if(isset($_GET['userid'])){
            $userid = intval($_GET['userid']);
            $condition[] = 'r.userid='.$userid;
            $query_string['userid'] = $userid;
        }

        if(isset($_GET['time_start'])){
            $time_start = rstrtotime($_GET['time_start']);
        }else{
            if(isset($deviceid) || isset($userid)){
                $time_start = '';
            }else{
                $time_start = TIMESTAMP;
            }
        }
        if($time_start){
            $condition[] = 'r.time_start>='.$time_start;
            $time_start = rdate($time_start, 'Y-m-d H:i');
            $query_string['time_start'] = $time_start;
        }

        if(isset($_GET['time_end'])){
            $time_end = $_GET['time_end'] ? rstrtotime($_GET['time_end']) : '';
        }else{
            $time_end = '';
        }
        if($time_end){
            $condition[] = 'r.time_start<='.$time_end;
            $time_end = rdate($time_end, 'Y-m-d H:i');
            $query_string['time_end'] = $time_end;
        }

        $status = array();
        foreach(Reservation::$Status as $statusid => $text){
            $status[$statusid] = false;
        }
        if(isset($_GET['status']) && is_array($_GET['status'])){
            foreach($_GET['status'] as $statusid => $checked){
                $status[$statusid] = true;
            }
        }else{
            $status[Reservation::Pending] = true;
        }
        $display_status = array();
        foreach($status as $statusid => $checked){
            if($checked){
                $display_status[] = $statusid;
            }
        }
        if($display_status){
            $condition[] = 'r.status IN ('.implode(',', $display_status).')';
        }else{
            foreach($status as &$checked){
                $checked = true;
            }
            unset($checked);
        }

        $limit = 20;
        $offset = ($page - 1) * $limit;

        $condition = $condition ? '('.implode(') AND (', $condition).')' : '1';
        $query_string = $query_string ? '&'.http_build_query($query_string) : '';

        $totalnum = $db->result_first("SELECT COUNT(*)
            FROM {$tpre}reservation r
                LEFT JOIN {$tpre}device d ON d.id=r.deviceid
                LEFT JOIN {$tpre}user u ON u.id=r.userid
            WHERE $condition");

        global $db, $tpre;
        $reservations = $db->fetch_all("SELECT r.*,d.name AS devicename, d.deleted AS devicedeleted, u.account, u.realname
            FROM {$tpre}reservation r
                LEFT JOIN {$tpre}device d ON d.id=r.deviceid
                LEFT JOIN {$tpre}user u ON u.id=r.userid
            WHERE $condition
            ORDER BY r.deviceid,r.status,r.time_start,r.time_end
            LIMIT $offset,$limit");
        unset($r);

        include view('list');
    }

    public function acceptAction(){
        global $db, $tpre, $_G;
        $id = intval($_GET['id'] ?? 0);

        $reservation = new Reservation($id);
        if(!$reservation->exists()){
            showmsg('reservation_does_not_exist', 'back');
        }

		if(!$_G['admin']->isSuperAdmin()){
			$has_permission = $db->result_first("SELECT 1 FROM {$tpre}device WHERE id={$reservation->deviceid} AND adminid={$_G['admin']->id}");
			if(!$has_permission){
				showmsg('no_permission_to_accept_reservation', 'back');
			}
		}

        $r = Reservation::CheckConflict($reservation->deviceid, $reservation->time_start, $reservation->time_end);
        if($r){
            $hour_start = intval(rdate($r['time_start'], 'H'));
            $hour_end = $hour_start + round(($r['time_end'] - $r['time_start']) / 3600);
            showmsg(array('reservation_can_not_be_accepted_due_to_conflict', $hour_start, $hour_end), 'back');
        }

        if(!Device::Exist($reservation->deviceid)){
            showmsg('device_does_not_exist', 'refresh');
        }

        $new_status = Reservation::Accepted;
        $db->query("UPDATE {$tpre}reservation SET status=$new_status WHERE id=$id");
        showmsg('reservation_is_accepted', 'refresh');
    }

    public function rejectAction(){
        global $db, $tpre, $_G;
        $id = intval($_GET['id'] ?? 0);

		if(!$_G['admin']->isSuperAdmin()){
			$deviceid = $db->result_first("SELECT deviceid FROM {$tpre}reservation WHERE id=$id");
			$has_permission = $db->result_first("SELECT 1 FROM {$tpre}device WHERE id={$deviceid} AND adminid={$_G['admin']->id}");
			if(!$has_permission){
				showmsg('no_permission_to_reject_reservation', 'back');
			}
		}

        $new_status = Reservation::Rejected;
        $db->query("UPDATE {$tpre}reservation SET status=$new_status WHERE id=$id");
        showmsg('reservation_is_rejected', 'refresh');
    }

}
