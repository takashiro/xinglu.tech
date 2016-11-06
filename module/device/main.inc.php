<?php

if(!defined('S_ROOT')) exit('access denied');

$condition = array('d.deleted=0');

$condition = '('.implode(') AND (', $condition).')';
$devices = $db->fetch_all("SELECT d.*,a.realname AS admin
	FROM {$tpre}device d
		LEFT JOIN {$tpre}administrator a ON a.id=d.adminid
	WHERE $condition");

$admins = array();
$query = $db->query("SELECT id,realname FROM {$tpre}administrator WHERE deleted=0");
while($a = $query->fetch_assoc()){
	$admins[$a['id']] = $a['realname'];
}

include view('list');
