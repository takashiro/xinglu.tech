<?php

if(!defined('S_ROOT')) exit('access denied');

class Device extends DBObject{

    const TABLE_NAME = 'device';

    public static $Status;
    const Normal = 0;
    const Reserved = 1;
    const Maintained = 2;

    public function __construct(int $id = 0){
        parent::__construct();
        if($id > 0){
            $this->fetch('*', 'id='.$id);
        }
    }
}

Device::$Status = array(
    0 => lang('common', 'device_normal'),
    1 => lang('common', 'device_reserved'),
    2 => lang('common', 'device_maintained'),
);
