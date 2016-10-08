<?php

if(!defined('S_ROOT')) exit('access denied');

if(!$_G['user']->isLoggedIn()){
    redirect('index.php?mod=user:login');
}

if($_POST){
    if(isset($_POST['realname'])){
        $_G['user']->realname = htmlspecialchars(trim($_POST['realname']));
    }
    if(isset($_POST['email'])){
        $email = trim($_POST['email']);
        if(!User::IsEmail($email)){
            showmsg('invalid_email', 'back');
        }

        if($_G['user']->email != $email && User::Exist($email, 'email')){
            showmsg('duplicated_email', 'back');
        }

        $_G['user']->email = $email;
    }
    if(isset($_POST['mobile'])){
        $mobile = trim($_POST['mobile']);
        if(!User::IsMobile($mobile)){
            showmsg('incorrect_mobile_number', 'back');
        }
        if($_G['user']->mobile != $mobile && User::Exist($mobile, 'mobile')){
            showmsg('duplicated_mobile', 'back');
        }

        $_G['user']->mobile = $mobile;
    }

    showmsg('successfully_update_profile', 'refresh');
}

include view('edit');
