<?php

if(!defined('S_ROOT')) exit('access denied');

if($_G['user']->isLoggedIn()){
	showmsg('you_have_logged_in', 'back');
}

if($_POST){
	$uid = User::Register($_POST);
	if($uid > 0){
		$_G['user']->login($_POST['account'], $_POST['password'], 'account');
		showmsg('register_succesfully', 'index.php');
	}elseif($uid == User::INVALID_ACCOUNT){
		showmsg('account_too_short_or_too_long', 'back');
	}elseif($uid == User::INVALID_PASSWORD){
		showmsg('password_too_short', 'back');
	}elseif($uid == User::DUPLICATED_ACCOUNT){
		showmsg('duplicated_account', 'back');
	}else{
		showmsg('unknown_error_period', 'back');
	}
}
