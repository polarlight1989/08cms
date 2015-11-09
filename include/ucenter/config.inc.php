<?php
define('UC_CONNECT', 'mysql');
define("UC_DBHOST", $uc_dbhost) ;
define("UC_DBUSER", $uc_dbuser) ;
define("UC_DBPW", $uc_dbpwd) ;
define("UC_DBNAME", $uc_dbname) ;
define('UC_DBCHARSET', $dbcharset ? $dbcharset : str_replace('-', '', $mcharset));
define("UC_DBTABLEPRE", '`'.$uc_dbname.'`.'.$uc_dbpre) ;
define('UC_DBCONNECT', '0');
define("UC_KEY", $uc_key) ;
define("UC_API", $uc_api) ;
define('UC_CHARSET', $mcharset);
define("UC_IP", $uc_ip) ;
define('UC_APPID', $uc_appid) ;
define('UC_PPP', '20');
?>