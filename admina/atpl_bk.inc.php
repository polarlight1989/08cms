<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
//所有的具体资料会涉及到会员，所以配置打包时最好还是不要具体的资料。
$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if(in_str('/hbcms/',$php_self)) amessage('开发源程序不能使用此功能<br>请先复制开发包');
load_cache('channels,fchannels,mchannels');
include_once M_ROOT."./include/database.fun.php";
if(empty($action)){
	tabheader('制作安装包','','',3);
	trcategory(array('操作项目','演示数据版','无演示数据版','模板安装包'));
	echo "<tr class=\"txt\"><td class=\"txtL w200\">清理演示数据</td>\n".
		"<td class=\"txtC\">-</td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=dbclear&ver=init\">>>执行</a></td>\n".
		"</tr>\n";
	echo "<tr class=\"txt\"><td class=\"txtL w200\">当前数据库打包</td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=dbpack&ver=init\">>>执行</a></td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=dbpack&ver=conf\">>>执行</a></td>\n".
		"</tr>\n";
	echo "<tr class=\"txt\"><td class=\"txtL w200\">生成简体中文GBK版本</td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=mpack&ver=conf&lan=scgbk\">>>执行</a></td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=mpack&ver=init&lan=scgbk\">>>执行</a></td>\n".
		"</tr>\n";
	echo "<tr class=\"txt\"><td class=\"txtL w200\">生成简体中文UTF8版本</td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=mpack&ver=conf&lan=scutf8\">>>执行</a></td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=mpack&ver=init&lan=scutf8\">>>执行</a></td>\n".
		"</tr>\n";
	echo "<tr class=\"txt\"><td class=\"txtL w200\">生成繁体中文BIG5版本</td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=mpack&ver=conf&lan=tcbig5\">>>执行</a></td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=mpack&ver=init&lan=tcbig5\">>>执行</a></td>\n".
		"</tr>\n";
	echo "<tr class=\"txt\"><td class=\"txtL w200\">生成繁体中文UTF8版本</td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=mpack&ver=conf&lan=tcutf8\">>>执行</a></td>\n".
		"<td class=\"txtC\"><a href=\"?entry=atpl_bk&action=mpack&ver=init&lan=tcutf8\">>>执行</a></td>\n".
		"</tr>\n";
	tabfooter();
}elseif($action == 'dbclear'){
	if($ver == 'init'){
		$cleartables = array(//留下了会员资料
		'albums','answers','archives','archives_sub','archives_rec','arecents','comments','consults','cradminlogs','favorites','farchives',
		'gurls','members','members_sub','keywords','logerrortimes','mcomments','mfavorites','mflinks','mfriends','mreplys','mreports','mtrans',
		'offers','orders','pays','pms','purchases','replys','reports','subscribes','userfiles',
		'voptions','votes','utrans','wordlinks','repus','vols','domains','mcrecords',
		);
		foreach($channels as $v) $cleartables[] = 'archives_'.$v['chid'];
		foreach($fchannels as $v) $cleartables[] = 'farchives_'.$v['chid'];
		foreach($mchannels as $v) $cleartables[] = 'members_'.$v['mchid'];
		foreach($cleartables as $table){
			$db->query("TRUNCATE {$tblprefix}$table",'SILENT');
		}
		//临时恢复当前用户
		$db->query("INSERT INTO {$tblprefix}members (mid, mname, isfounder, password, email, checked) VALUES ('".$curuser->info['mid']."','".$curuser->info['mname']."','1','".$curuser->info['password']."','".$curuser->info['email']."','1')");
		$db->query("INSERT INTO {$tblprefix}members_1 (mid) VALUES ('".$curuser->info['mid']."')",'SILENT');
		$db->query("INSERT INTO {$tblprefix}members_sub (mid) VALUES ('".$curuser->info['mid']."')",'SILENT');
		
	}elseif($ver == 'conf'){//应该不需要此功能
	
	}
	rebuild_cache(-1);
}elseif($action == 'dbpack'){
	$sqlcompat = 'MYSQL40';
	$sqlcharset = 'gbk';
	$usehex = 0;

	$structables = array('cradminlogs','msession',);
	$datatables = array();
	$query = $db->query("SHOW TABLES FROM $dbname");
	while($dbtable = $db->fetch_row($query)){
		$dbtable[0] = preg_replace("/^".$tblprefix."(.*?)/s","\\1",$dbtable[0]);
		!in_array($dbtable[0],$structables) && $datatables[] = $dbtable[0];
	}

	$db->query('SET SQL_QUOTE_SHOW_CREATE=0', 'SILENT');
	$idstring = '# DatafileID: '.base64_encode("$timestamp,08CMS,$cms_version,installsql")."\n";

	$dumpcharset = $sqlcharset ? $sqlcharset : str_replace('-', '',$mcharset);
	
	$setnames = ($sqlcharset && $db->version() > '4.1' && (!$sqlcompat || $sqlcompat == 'MYSQL41')) ? "SET NAMES '$dumpcharset';\n\n" : '';
	if($db->version() > '4.1'){
		if($sqlcharset) {
			$db->query("SET NAMES '".$sqlcharset."';\n\n");
		}
		if($sqlcompat == 'MYSQL40'){
			$db->query("SET SQL_MODE='MYSQL40'");
		}elseif($sqlcompat == 'MYSQL41') {
			$db->query("SET SQL_MODE=''");
		}
	}
	$dumpfile = M_ROOT.'./install/08cms.sql';
	$sqldump = '';
	foreach($structables as $table){
		$sqldump .= pack_sqldump($table,0);
	}
	foreach($datatables as $table){
		$sqldump .= pack_sqldump($table,1);
	}
	$sqldump = "$idstring".
			"# <?exit();?>\n".
			"# 08cms InstallPack Data Dump\n".
			"# Version: 08cms v$cms_version\n".
			"# Date: ".date("Y-m-d",$timestamp)."\n".
			"# --------------------------------------------------------\n".
			"# Home: www.08cms.com\n".
			"# --------------------------------------------------------\n\n\n".
			"$setnames".$sqldump;
			
	@$fp = fopen($dumpfile, 'wb');
	@flock($fp, 2);
	if(@!fwrite($fp, $sqldump)){
		@fclose($fp);
		amessage('data_export_failed','?entry=atpl_bk');
	}else{
		@fclose($fp);
		amessage('data_export_finish','?entry=atpl_bk');
	}

}elseif($action == 'mpack'){
	include_once M_ROOT."./include/charset.fun.php";
	$droot = M_ROOT."./../mpack/".date('ymdHis').'_'.$lan.'_'.$ver.'/';
	$icharset = $lan == 'scgbk' ? 'gbk' : ($lan == 'tcbig5' ? 'big5' : 'utf-8');
	$lan_ver = substr($lan,0,2);
	$qcharset = str_replace('-','',$icharset);//使用于sql的charset
	if(is_dir($droot)) dirclear($droot,1);
	if(!dir_copy(M_ROOT,$droot,1,0)) amessage('复制'.M_ROOT.'时出错','?entry=atpl_bk');
	
	$dirarr = array(
		'admina' => array(1,0),
		'adminm' => array(1,0),
		'api' => array(1,0),
		'dynamic' => array(0,0),
		'dynamic/aguides' => array(1,0),
		'dynamic/cache' => array(0,0),
		'dynamic/function' => array(1,0),
		'dynamic/htmlcac' => array(0,0),
		'dynamic/htmltxt' => array(0,0),
		'html' => array(0,0),
		'images' => array(1,1),
		'include' => array(1,1),
		'info' => array(0,0),
		'install' => array(1,1),
		'member' => array(1,1),
		'mspace' => array(1,1),
		'paygate' => array(1,1),
		'template' => array(1,1),
		'tools' => array(1,1),
		'uc_client' => array(1,1),
		'updatedata' => array(1,0),
		'userfiles' => array(0,0),//不带演示数据时
//		'userfiles' => array(1,1),//带演示数据
		'wap' => array(1,1),
	);
	foreach($dirarr as $k => $v){
		if(!dir_copy(M_ROOT.$k,$droot.$k,$v[0],$v[1])) amessage('复制'.M_ROOT.$k.'时出错','?entry=atpl_bk');
	}
	//将文件内部的相关内容作置换/////////////////////////////////////////////////////////////////
	//base.inc.php
	if(!is_writable($droot.'base.inc.php')) amessage('配置文件base.inc.php不可写','?entry=atpl_bk');
	$fp = fopen($droot.'base.inc.php','r');
	$configfile = fread($fp, filesize($droot.'base.inc.php'));
	fclose($fp);
	$configfile = preg_replace("/[$]dbname\s*\=\s*[\"'].*?[\"'];/is", "\$dbname = 'db08cms';", $configfile);
	$configfile = preg_replace("/[$]dbpw\s*\=\s*[\"'].*?[\"'];/is", "\$dbpw = '';", $configfile);
	$configfile = preg_replace("/[$]mcharset\s*\=\s*[\"'].*?[\"'];/is", "\$mcharset = '".$icharset."';", $configfile);
	$configfile = preg_replace("/[$]tblprefix\s*\=\s*[\"'].*?[\"'];/is", "\$tblprefix = 'cms_';", $configfile);
	$configfile = preg_replace("/[$]ckpre\s*\=\s*[\"'].*?[\"'];/is", "\$ckpre = '';", $configfile);
	$configfile = preg_replace("/[$]lan_version\s*\=\s*[\"'].*?[\"'];/is", "\$lan_version = '".$lan_ver."';", $configfile);
	$fp = fopen($droot.'base.inc.php','w');
	fwrite($fp, trim($configfile));
	fclose($fp);
	//安装sql文件
	if($icharset != 'gbk'){
		$sqlfile = $droot.'install/08cms.sql';
		$sqlstr = file2str($sqlfile);
		$sqlstr = str_replace("CHARSET=gbk","CHARSET=".$qcharset,$sqlstr);
		$sqlstr = str_replace("SET NAMES 'gbk'","SET NAMES '".$qcharset."'",$sqlstr);
		str2file($sqlstr,$sqlfile);
		unset($sqlstr,$sqlfile);
	}
	//fckconfig.js
	$fcklang = in_str('sc',$lan) ? 'zh-cn' : 'zh';
	if($fcklang != 'zh-cn'){
		$fckfile = $droot.'include/fckeditor/fckconfig.js';
		$fckstr = file2str($fckfile);
		$fckstr = str_replace('zh-cn','zh',$fckstr);
		str2file($fckstr,$fckfile);
		unset($fckstr,$fckfile);
	}
	//*************************对整个文件作编码转换处理//////////////////////////////////////////////////////////////////////
	//系统内置缓存，包含部分语言包
	$ckeeps = array('index.htm','index.html',);
	clear_files($droot.'dynamic/cache/',$ckeeps);
	$convs = array();
	$convs[] = $droot.'admina/home.inc.php';//管理后台首页
	$convs[] = $droot.'admina/tools/upload.php';
	$convs[] = $droot.'tools/taghelp.html';
	foreach($ckeeps as $v) $convs[] = $droot.'dynamic/cache/'.$v;//系统缓存文件
	//管理后台注释文件
	$agarr = findfiles($droot.'dynamic/aguides/','php');
	foreach($agarr as $v) $convs[] = $droot.'dynamic/aguides/'.$v;
	//会员中心注释文件
	$mgarr = findfiles($droot.'dynamic/mguides/','php');
	foreach($mgarr as $v) $convs[] = $droot.'dynamic/mguides/'.$v;
	//安装文件
	$convs[] = $droot.'install/08cms.sql';
	$convs[] = $droot.'install/langs/blangs.cac.php';
	$convs[] = $droot.'install/langs/ilangs.cac.php';
	//模板文件
	$tplkeys = array(
		array('template/default/','htm'),
		array('template/default/','html'),
		array('template/default/js/','js'),
		array('template/default/css/','css'),
		array('template/default/function/','php'),
		array('template/default/cache/','php'),
	);
	foreach($tplkeys as $v){
		$tplarr = findfiles($droot.$v[0],$v[1]);
		foreach($tplarr as $u) $convs[] = $droot.$v[0].$u;
	}
	//后台或会员中心用的js文件
	$jsarr = findfiles($droot.'include/js/','js');
	foreach($jsarr as $v) $convs[] = $droot.'include/js/'.$v;
	//处理...
	foreach($convs as $v){
		if($lan == 'tcutf8'){
			convert_file('gbk','big5',$v);
			convert_file('big5','utf-8',$v);
		}else{
			convert_file('gbk',$icharset,$v);
		}
	}
	//**************清除多余或开发用的文件及设置/////////////////////////////////////////////////////////////
	foreach(array('index.htm','index.html','google.xml','baidu.xml','sitemap.xml','init.php') as $k) @unlink($droot.$k);
	$delarr = array('aguides','amenus','btagnames','inscopy','inspack','langs','tablestr','test','certificate','atpl',);
	foreach($delarr as $k) @unlink($droot."admina/$k.inc.php");
	
	amessage('安装包生成成功','?entry=atpl_bk');

}
function convert_file($scode,$tcode,$sfile=''){//gbk,big5,utf-8
	if(!$sfile || !is_file($sfile)) return;
	if(empty($scode) || empty($tcode) || $scode == $tcode) return;
	$str = @file2str($sfile);
	$str && $str = convert_encoding($scode,$tcode,$str);
	str2file($str,$sfile);
}
function clear_files($dir,$keeps = array()){
	if(!is_dir($dir)) return;
	$handle = dir($dir);
	while($entry = $handle->read()){
		if($entry != '.' && $entry != '..' && is_file($dir.'/'.$entry)){
			if(!$keeps || !in_array($entry,$keeps)) @unlink($dir.'/'.$entry);
		}
	}
	$handle->close();
}
function dirclear($dir,$mode = 1){//$mode:0 只清除文件/1 清除文件及文件夹
	if(!is_dir($dir)) return;
	$directory = dir($dir);
	while($entry = $directory->read()){
		$filename = $dir.'/'.$entry;
		if(is_file($filename)){
			@unlink($filename);
		}elseif(is_dir($filename) && $entry != '.' && $entry != '..'){
			dirclear($filename,$mode);
			if($mode) @rmdir($filename);
		}
	}
	$directory->close();
}
function dir_copy($source,$destination,$f = 0,$d = 0){//$f-是否复制文件夹下文件，$d是否复制搜索下级文件夹
	if(!is_dir($source)) return false;
	mmkdir($destination,0);
	if($f || $d){
		$handle = dir($source);
		while($entry = $handle->read()){
			if(($entry != ".") && ($entry != "..")){
				if(is_dir($source."/".$entry)){
					$d && dir_copy($source."/".$entry,$destination."/".$entry,$f,$d);
				}else{
					$f && copy($source."/".$entry,$destination."/".$entry);
				}
			}
		}
		$handle->close();
	}
	return true;
}
function ocache_read($rname,$dir){
	if(!@include $dir.$rname.'.cac.php') return array();
	return ${$rname.'_0'};
}
function ocache_save($carr,$cname,$dir){
	if(!is_array($carr) || empty($cname)) return;
	$cacstr = var_export($carr,TRUE);
	mmkdir($dir);
	if(@$fp = fopen($dir.$cname.'.cac.php','wb')){
		$cname .= '_0';
		fwrite($fp,"<?php\n\$$cname = $cacstr ;\n?>");
		fclose($fp);
	}
}
?>
