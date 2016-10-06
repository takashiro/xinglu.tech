<?php

if(!defined('S_ROOT')) exit('access denied');

$_G['user']->logout();
showmsg('you_logged_out', 'refresh');
