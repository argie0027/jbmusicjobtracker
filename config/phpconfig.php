<?php
/*
 * set error reporting in php
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

/*
 * set the time zone
 */
date_default_timezone_set("Asia/Manila");

/*
 * Regular Expression Format
 */
define("REG_EMAIL", "/^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/");
define("REG_MOBILE", "/^[0-9]{11}$/");
define("REG_LANDLINE", "/^\(?[0-9]{3}\)?|[0-9]{3}[-. ]? [0-9]{3}[-. ]?[0-9]{4}$/");
define("REG_FAX", "/([\(\+])?([0-9]{1,3}([\s])?)?([\+|\(|\-|\)|\s])?([0-9]{2,4})([\-|\)|\.|\s]([\s])?)?([0-9]{2,4})?([\.|\-|\s])?([0-9]{4,8})/");
?>