<?php

if(!defined('S_ROOT')) exit('access denied');

if($_G['user']->isLoggedIn()){
	showmsg('you_have_logged_in', 'back');
}

if($_POST){
	$account = isset($_POST['account']) ? trim($_POST['account']) : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$result = $_G['user']->login($account, $password);
	if($result){
		if(empty($_POST['http_referer'])){
			showmsg('successfully_logged_in', 'index.php');
		}else{
			showmsg('successfully_logged_in', $_POST['http_referer']);
		}
	}else{
		showmsg('invalid_account_or_password', 'back');
	}
}

include view('login');
