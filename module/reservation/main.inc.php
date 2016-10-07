<?php

if(!defined('S_ROOT')) exit('access denied');

$condition = array('r.status='.Reservation::Accepted);

if(isset($_GET['time_start'])){
    $time_start = rstrtotime($_GET['time_start']);
    $condition[] = 'r.time_start>='.$time_start;
}else{
    $condition[] = 'r.time_start>='.TIMESTAMP;
}

if(isset($_GET['deviceid'])){
    $condition[] = 'r.deviceid='.intval($_GET['deviceid']);
}

$condition = $condition ? '('.implode(') AND (', $condition).')' : '1';
$reservations = $db->fetch_all("SELECT r.*,d.name AS devicename, d.model AS devicemodel, d.location AS devicelocation
    FROM {$tpre}reservation r
        LEFT JOIN {$tpre}device d ON d.id=r.deviceid
    WHERE $condition
    ORDER BY r.time_start");

include view('list');
