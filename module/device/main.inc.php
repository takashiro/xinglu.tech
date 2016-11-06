<?php

if(!defined('S_ROOT')) exit('access denied');

$condition = array('delete=0');

if(!$_G['admin']->isSuperAdmin()){
	$condition[] = 'adminid='.$_G['admin']->id;
}

$condition = '('.implode(') AND (', $condition).')';
$table = $db->select_table('device');
$devices = $table->fetch_all('*', $condition);

$admins = array();
$query = $db->query("SELECT id,realname FROM {$tpre}administrator WHERE deleted=0");
while($a = $query->fetch_assoc()){
	$admins[$a['id']] = $a['realname'];
}

include view('list');
