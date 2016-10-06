<?php

if(!defined('S_ROOT')) exit('access denied');

class Reservation extends DBObject{

    const TABLE_NAME = 'reservation';

    public function __construct(int $id = 0){
        parent::__construct();
        if($id > 0){
            $this->fetch('*', 'id='.$id);
        }
    }

}
