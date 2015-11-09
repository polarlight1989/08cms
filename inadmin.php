<?php
define('M_ADMIN', TRUE);
define('NOROBOT', TRUE);
include_once dirname(__FILE__).'/include/general.inc.php';
include_once M_ROOT.'./include/admin.fun.php';
load_cache('alangs,amsgs,langs,mnlangs');
$langs = $alangs + $mnlangs;
$langs = $langs + $mnlangs;
$urlsarr = array(
	'ex_langs' => array('导出语言包',''),
	'atpl' => array('制作安装包',''),
#	'kkkkkkk' => array('导出语言包',"&action=edit"),
);
empty($entry) && $entry = key($urlsarr);
$file = M_ROOT."inadmin/$entry.inc.php";
(!$curuser->isadmin() || !array_key_exists($entry, $urlsarr) || !file_exists($file)) && exit('No Permission');
aheader();
foreach($urlsarr as $k => &$v)$v[1] = "?entry=$k$v[1]";#PHP5
url_nav('程序管理', $urlsarr, $entry);
include_once($file);
?>