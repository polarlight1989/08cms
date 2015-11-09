<?php
error_reporting(0);
//error_reporting(2047);
define('M_COM',TRUE);
define('M_ROOT','');
$timestamp = time();
include_once M_ROOT.'./base.inc.php';
include_once M_ROOT.'./include/general.fun.php';
load_cache('sitemaps');
empty($sitemaps['google']) && exit();
$sitemap = $sitemaps['google'];
$cachefile = M_ROOT.'./google.xml';
if(empty($sitemap['setting']['life']) || ($timestamp - @filemtime($cachefile) > $sitemap['setting']['life'] * 3600) || (!$datastr = file2str($cachefile))){
	require M_ROOT.'./dynamic/cache/mconfigs.cac.php';
	@extract($mconfigs_0);
	include_once M_ROOT.'./include/mysql.cls.php';
	$db = new cls_mysql;
	$db->connect($dbhost,$dbuser,$dbpw,$dbname,$pconnect,true,$dbcharset);
	include_once M_ROOT.'./include/common.fun.php';
	include_once M_ROOT.'./include/sitemap.inc.php';
	str2file($datastr,$cachefile);
}
echo $datastr;
header("Content-type: application/xml");
exit;

?>