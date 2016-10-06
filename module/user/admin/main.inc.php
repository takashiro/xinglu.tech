<?php

if(!defined('IN_ADMINCP')) exit('access denied');

class UserMainModule extends AdminControlPanelModule{

    public function defaultAction(){
        global $db, $tpre;
        $users = $db->fetch_all("SELECT *
            FROM {$tpre}user
            WHERE 1");

        extract($GLOBALS, EXTR_SKIP | EXTR_REFS);
        include view('list');
    }

}