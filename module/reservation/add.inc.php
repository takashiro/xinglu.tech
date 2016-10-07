<?php

if(!defined('S_ROOT')) exit('access denied');

if(!$_G['user']->isLoggedIn()){
    showmsg('please_login_before_reserving_a_device', 'index.php?mod=user:login');
}elseif($_G['user']->groupid <= 0){
    showmsg('you_are_not_permitted_to_make_a_reservation_yet', 'back');
}

$deviceid = intval($_GET['deviceid'] ?? 0);
$device = new Device($deviceid);
if(!$device->exists()){
    showmsg('device_does_not_exist', 'refresh');
}

if($_POST){
    $year = intval(rdate(TIMESTAMP, 'Y'));
    $today_month = intval(rdate(TIMESTAMP, 'm'));
    $today_date = intval(rdate(TIMESTAMP, 'd'));

    $month = intval($_POST['month'] ?? $today_month);
    $month = max(1, $month);
    $date = intval($_POST['date'] ?? $today_date);
    $date = max(1, $date);

    $hour_start = intval($_POST['hour_start'] ?? 0);
    $hour_start = max(1, min($hour_start, 23));
    $hour_end = intval($_POST['hour_end'] ?? 1);
    $hour_end = min(24, max($hour_start + 1, $hour_end));

    $time_start = rmktime($hour_start, 0, 0, $month, $date, $year);
    $time_end = rmktime($hour_end, 0, 0, $month, $date, $year);

    if($time_start <= TIMESTAMP){
        showmsg('please_input_a_date_in_the_future', 'back');
    }

    $r = Reservation::CheckConflict($deviceid, $time_start, $time_end);
    if($r){
        $hour_start = intval(rdate($r['time_start'], 'H'));
        $hour_end = $hour_start + round(($r['time_end'] - $r['time_start']) / 3600);
        showmsg(array('reservation_is_conflicted', $hour_start, $hour_end), 'back');
    }

    $reservation = new Reservation;
    $reservation->userid = $_G['user']->id;
    $reservation->deviceid = $deviceid;
    $reservation->time_start = $time_start;
    $reservation->time_end = $time_end;
    $reservation->sample_number = intval($_POST['sample_number'] ?? 0);
    $reservation->sample_description = htmlspecialchars(trim($_POST['sample_description'] ?? ''));

    $reservation->insert();
    showmsg('successfully_requested_a_new_reservation', 'index.php?mod=reservation');
}

$d = $device->toReadable();
include view('add');
