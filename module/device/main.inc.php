<?php

if(!defined('S_ROOT')) exit('access denied');

$table = $db->select_table('device');
$devices = $table->fetch_all('*', 'deleted=0');

include view('list');
