<?php

if(!defined('IN_ADMINCP')) exit('access denied');

class DeviceMainModule extends AdminControlPanelModule{

    public function defaultAction(){
        global $db;
        $table = $db->select_table('device');
        $devices = $table->fetch_all('*');

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
        foreach(array('name', 'type', 'admin', 'location') as $var){
            if(isset($_POST[$var])){
                $device->$var = htmlspecialchars(trim($_POST[$var]));
            }
        }

        if($id <= 0){
            $device->insert();
        }

        echo json_encode($device->toReadable());
        exit;
    }

}