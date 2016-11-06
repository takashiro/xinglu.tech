<?php

if(!defined('IN_ADMINCP')) exit('access denied');

class DeviceMainModule extends AdminControlPanelModule{

    public function defaultAction(){
		global $_G, $db, $tpre;

		$condition = array('d.deleted=0');
		if(!$_G['admin']->isSuperAdmin()){
			$condition[] = 'd.adminid='.$_G['admin']->id;
		}

		$condition = '('.implode(') AND (', $condition).')';
        $devices = $db->fetch_all("SELECT d.*,a.realname AS admin
			FROM {$tpre}device d
				LEFT JOIN {$tpre}administrator a ON a.id=d.adminid
			WHERE $condition");

		$admins = array();
		$query = $db->query("SELECT id,realname FROM {$tpre}administrator WHERE deleted=0");
		while($a = $query->fetch_assoc()){
			$admins[$a['id']] = $a['realname'];
		}

        extract($GLOBALS, EXTR_REFS | EXTR_SKIP);
        include view('list');
    }

    public function editAction(){
        $id = intval($_REQUEST['id'] ?? 0);
        if($id <= 0){
            $device = new Device;
        }else{
            $device = new Device($id);
            if(!$device->exists()){
                exit;
            }
        }

		if($_POST){
			if(isset($_POST['status'])){
				$new_status = intval($_POST['status']);
				if(isset(Device::$Status[$new_status])){
					if($device->status != Device::Reserved){
						$device->status = $new_status;
					}
				}
			}
			foreach(array('name', 'model', 'location', 'kindly_reminder') as $var){
				if(isset($_POST[$var])){
					$device->$var = htmlspecialchars(trim($_POST[$var]));
				}
			}

			global $_G;
			if($_G['admin']->isSuperAdmin() && isset($_POST['adminid'])){
				if(Administrator::Exist($_POST['adminid'])){
					$device->adminid = intval($_POST['adminid']);
				}
			}

			if($id <= 0){
				if(!isset($device->kindly_reminder)){
					$device->kindly_reminder = '';
				}
				$device->insert();
			}

			if(!empty($_GET['ajax'])){
				echo json_encode($device->toReadable());
				exit;
			}else{
				showmsg('device_is_updated', 'index.php?mod=device');
			}
		}

		extract($GLOBALS, EXTR_REFS | EXTR_SKIP);
		$d = $device->toReadable();
		include view('edit');
    }

    public function deleteAction(){
        if(empty($_REQUEST['id'])) exit;

        $id = intval($_REQUEST['id']);
        $device = new Device($id);
        if($device->exists()){
			if($device->adminid && $device->adminid != $_G['admin']->id){
				showmsg('no_permission_to_delete_device', 'back');
			}

            $device->deleted = 1;

            global $db, $tpre;
            $rejected = Reservation::Rejected;
            $timestamp = TIMESTAMP;
            $db->query("UPDATE {$tpre}reservation SET status=$rejected WHERE deviceid=$id AND time_start>={$timestamp}");
        }

        showmsg('device_is_deleted', 'refresh');
    }

}
