<?php

if(!defined('S_ROOT')) exit('access denied');

if(!$_G['user']->isLoggedIn()){
    redirect('index.php?mod=user:login');
}

if(!empty($_POST['old_password']) && !empty($_POST['new_password'])){
    $result = $_G['user']->changePassword($_POST['old_password'], $_POST['new_password']);
    if($result == User::OLD_PASSWORD_WRONG){
        showmsg('incorrect_old_password', 'back');
    }

    showmsg('successfully_update_password', 'back');
}
