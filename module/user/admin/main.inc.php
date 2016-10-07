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

    public function verifyAction(){
        if(empty($_GET['id'])) exit;
        global $db, $tpre;
        $id = intval($_GET['id']);
        $db->query("UPDATE {$tpre}user SET groupid=1 WHERE id=$id");
        showmsg('user_is_now_verified', 'refresh');
    }

    public function banAction(){
        if(empty($_GET['id'])) exit;
        global $db, $tpre;
        $id = intval($_GET['id']);
        $db->query("UPDATE {$tpre}user SET groupid=0 WHERE id=$id");
        showmsg('user_is_now_banned', 'refresh');
    }

}
