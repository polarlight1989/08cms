<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('extract') || amessage('no_apermission');
include_once(M_ROOT . '/include/extract/extract.cls.php');

$ex = new extract_cash();
$ex->isadmin = 1;
$ex->showlist();
?>