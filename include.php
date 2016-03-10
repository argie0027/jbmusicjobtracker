<?php
session_start();

// include 'config/phpconfig.php';
include 'config/config.php';
include 'config/constant.php';

spl_autoload_register(function ($class) {
    include_once 'classes/class.' . $class . '.inc';
});

$db = new MySQLiDBManager;
$db->Connect();
$utility = new Utility;

require('config/Pusher.php');
$app_id = '150424';
$app_key = 'abd83e79f848c8679917';
$app_secret = '04dcecff0918a8969fa4';

$pusher = new Pusher(
  $app_key,
  $app_secret,
  $app_id,
  array('encrypted' => true)
);

function clearspaces($str) {
	return str_replace(' ', '', $str);
}

function checkspecialchars($str) {
	if( preg_match('/[#@$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $str) ){
		return true;
	}
}