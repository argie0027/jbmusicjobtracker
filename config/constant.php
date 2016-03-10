<?php

define('SITE_URL', trim($site['url'], '/') . '/' . ($site['subDir'] ? trim($site['subDir'], '/') . '/' : ''));
define('SITE_NAME', $site['name']);
define('SITE_RESOURCES', $site['resources'] . '/');

define('SITE_CSS_DIR', SITE_URL . SITE_RESOURCES . trim($site['css'], '/') . '/');
define('SITE_JS_DIR', SITE_URL . SITE_RESOURCES . trim($site['js'], '/') . '/');
define('SITE_IMAGES_DIR', SITE_URL . SITE_RESOURCES . trim($site['img'], '/') . '/');

define('NOTIF', 'created new job,
approved job, 
generate new SOA ,
set delivery date for job, 
set item arrived,
set repaired job,
set unclaimed job,
set cant repair job,
set claimed job');

define('ACT_NOTIF', 
	'Job Order Created,Job Order Edited,Job Order Deleted,Set Job Order Delivery Date To Service Department,Customer Approved Job Order,Customer Disapproved Job Order,Job Order Arrived,Item Claimed,Job Order Arrived Service Department,Statement of Account Generated,Statement of Account Edited,Job Order Ongoing Repair,Cant Repair Job Order,Repair Done,Set Job Order Delivery Date To Branch'
);

define('HASH_AUSER', 'U{:IB- 0@j3Azb4*=tPmeLIPVq{2');

$path = $_SERVER['DOCUMENT_ROOT'] . $site['subDir'] . '/';
$path = str_replace("\\", "/", $path);
$path = $path;
define("ROOT_DIR", $path);

?>