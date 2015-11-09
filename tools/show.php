<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'include/common.fun.php';
if(empty($by) || empty($sn) || empty($id)){
	$message = '';
}else{
	$pages = array(
		'orders'	=> 'orders',
		'pays'		=> 'pays',
	);
	$names = array(
		'orders'	=> 'oid',
		'pays'		=> 'pid',
	);
	$action	= empty($pages) || !is_array($pages) || !array_key_exists($by, $pages) ? $by : $pages[$by];
	$name	= empty($names) || !is_array($names) || !array_key_exists($by, $names) ? 'id' : $names[$by];
	$adminm	= 'adminm.php';
	$message = lang('payfinish')."<br /><br /><a href=\"$cms_abs$adminm?action=$action&$name=$id\">>>".lang('look')."</a>";
}
message($message);
?>