<?php

if(!defined('S_ROOT')) exit('access denied');

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
    if($month < $today_month || $date <= $today_date){
        showmsg('please_input_a_date_in_the_future', 'back');
    }

    $hour_start = intval($_POST['hour_start'] ?? 0);
    $hour_start = max(1, min($hour_start, 23));
    $hour_end = intval($_POST['hour_end'] ?? 1);
    $hour_end = max(24, min($hour_start + 1, $hour_end));

    $time_start = rmktime($hour_start, 0, 0, $month, $date, $year);
    $time_end = rmktime($hour_end, 0, 0, $month, $date, $year);

    $reservation = new Reservation;
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