<?php

if(!defined('IN_ADMINCP')) exit('access denied');

class DeviceMainModule extends AdminControlPanelModule{

    public function defaultAction(){
        global $db;
        $table = $db->select_table('device');
        $devices = $table->fetch_all('*', 'deleted=0');

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

        if(isset($_POST['status'])){
            $new_status = intval($_POST['status']);
            if(isset(Device::$Status[$new_status])){
                if($device->status != Device::Reserved){
                    $device->status = $new_status;
                }
            }
        }
        foreach(array('name', 'model', 'admin', 'location') as $var){
            if(isset($_POST[$var])){
                $device->$var = htmlspecialchars(trim($_POST[$var]));
            }
        }

        if($id <= 0){
			if(!isset($device->kindly_reminder)){
				$device->kindly_reminder = '';
			}
            $device->insert();
        }

        echo json_encode($device->toReadable());
        exit;
    }

    public function deleteAction(){
        if(empty($_REQUEST['id'])) exit;

        $id = intval($_REQUEST['id']);
        $device = new Device($id);
        if($device->exists()){
            $device->deleted = 1;

            global $db, $tpre;
            $rejected = Reservation::Rejected;
            $timestamp = TIMESTAMP;
            $db->query("UPDATE {$tpre}reservation SET status=$rejected WHERE deviceid=$id AND time_start>={$timestamp}");
        }

        showmsg('device_is_deleted', 'refresh');
    }

}
