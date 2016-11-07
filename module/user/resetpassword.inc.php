<?php

if(!defined('S_ROOT')) exit('access denied');

if($_G['user']->isLoggedIn()){
	showmsg('you_have_logged_in_do_not_need_reset_password');
}

$action = &$_GET['action'];
switch($action){
case 'sendmail':
	if(isset($_POST['email'])){
		$email = addslashes($_POST['email']);
		if(!User::IsEmail($email)){
			showmsg('invalid_email_format', 'back');
		}

		$userid = $db->result_first("SELECT id FROM {$tpre}user WHERE email='$email'");
		if(!$userid){
			showmsg('user_does_not_exist');
		}

		$authkey = Authkey::Generate('resetpw_'.$userid);
		$link = $_G['site_url'].'index.php?mod=user:resetpassword&userid='.$userid.'&authkey='.$authkey;

		$title = lang('common', 'reset_password_email_title');
		$content = lang('common', 'reset_password_email_content', array($link));
		$mail = new Mail($title, $content);
		if($mail->send($email)){
			showmsg('an_email_is_sent_to_reset_your_password', 'index.php?mod=user');
		}else{
			showmsg('failed_to_send_email');
		}

	}else{
		exit('illegal operation, no userid');
	}
	break;

case 'reset':
	if(empty($_POST['userid']) || empty($_POST['authkey']) || empty($_POST['new_password'])){
		exit('illegal operation');
	}

	$userid = intval($_POST['userid']);
	$key = new Authkey('resetpw_'.$userid);
	if(!$key->exists() || $key->isExpired()){
		showmsg('reset_password_authkey_expired', 'index.php?mod=user:resetpassword');
	}
	if($key->matchOnce($_POST['authkey'])){
		$pwmd5 = rmd5($_POST['new_password']);
		$db->query("UPDATE {$tpre}user SET pwmd5='$pwmd5' WHERE id=$userid");
		showmsg('your_password_is_successfully_reset', 'index.php?mod=user:login');
	}else{
		showmsg('reset_password_authkey_expired', 'index.php?mod=user:resetpassword');
	}


default:
	if(empty($_GET['userid']) || empty($_GET['authkey'])){
		include view('resetpassword_step1');
	}else{
		$userid = intval($_GET['userid']);
		$key = new Authkey('resetpw_'.$userid);
		if(!$key->exists() || $key->isExpired()){
			showmsg('reset_password_authkey_expired', 'index.php?mod=user:resetpassword');
		}

		$authkey = trim($_GET['authkey']);
		include view('resetpassword_step2');
	}
}
