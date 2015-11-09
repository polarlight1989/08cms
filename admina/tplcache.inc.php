<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('tpl') || amessage('no_apermission');
$url_type = 'othertpl';include 'urlsarr.inc.php';
url_nav(lang('tplrelated'),$urlsarr,'tcah');
if(!isset($confirm) || $confirm != 'ok') {
	$message = lang('rebuld_tplcache')."<br><br>";
	$message .= lang('confirmclick').">><a href=?entry=tplcache&confirm=ok$param_suffix><b>".lang('rebuild')."</b></a><br>";
	$message .= lang('giveupclick').">><a href=?entry=tplconfig&action=tplbase$param_suffix>".lang('goback')."</a>";
	amessage($message);
}
clear_dir(M_ROOT."./template/$templatedir/pcache/");
amessage(lang('tplcachefin'),"?entry=tplconfig&action=tplbase$param_suffix");

?>
