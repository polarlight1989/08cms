<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
load_cache('mconfigs,currencys,commus,channels');
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
if($action == 'bkparams'){
	backallow('bkconfig') || amessage('no_apermission');
	$url_type = 'backarea';include 'urlsarr.inc.php';
	url_nav(lang('backareaconfig'),$urlsarr,'bkparam',10);
	if(!submitcheck('bmconfigs')){
		tabheader(lang('admbacrelset'),'cfview',"?entry=backparams&action=bkparams$param_suffix");
		$curuser->info['isfounder'] && trbasic(lang('foundercontent'),'mconfigsnew[foundercontent]',empty($mconfigs['foundercontent']) ? 0 : $mconfigs['foundercontent'],'radio',lang('agfoundercontent'));
		trbasic(lang('atpp'),'mconfigsnew[atpp]',$mconfigs['atpp']);
		trbasic(lang('admbackamsgforw'),'mconfigsnew[amsgforwordtime]',$mconfigs['amsgforwordtime']);
		trbasic(lang('cnprow'),'mconfigsnew[cnprow]',$mconfigs['cnprow']);
		trbasic(lang('enablefloatwin'),'mconfigsnew[aallowfloatwin]',empty($mconfigs['aallowfloatwin']) ? 0 : $mconfigs['aallowfloatwin'],'radio');
		trbasic(lang('floatwinwidth'),'mconfigsnew[afloatwinwidth]',empty($mconfigs['afloatwinwidth']) ? 0 : $mconfigs['afloatwinwidth']);
		trbasic(lang('floathei'),'mconfigsnew[afloatwinheight]',empty($mconfigs['afloatwinheight']) ? 0 : $mconfigs['afloatwinheight']);
		tabfooter('bmconfigs');
	}else{
		$mconfigsnew['atpp'] = max(5,intval($mconfigsnew['atpp']));
		$mconfigsnew['amsgforwordtime'] = max(0,intval($mconfigsnew['amsgforwordtime']));
		$mconfigsnew['cnprow'] = max(1,intval($mconfigsnew['cnprow']));
		$mconfigsnew['afloatwinwidth'] = min(1200,max(400,intval($mconfigsnew['afloatwinwidth'])));
		$mconfigsnew['afloatwinheight'] = min(1000,max(300,intval($mconfigsnew['afloatwinheight'])));
		saveconfig('view');
		adminlog(lang('websiteset'),lang('pagandtemset'));
		amessage('websitesetfinish',"?entry=backparams&action=bkparams$param_suffix");
	}
}elseif($action == 'mcparams'){
	backallow('mcconfig') || amessage('no_apermission');
	$url_type = 'mcenter';include 'urlsarr.inc.php';
	url_nav(lang('mcenterconfig'),$urlsarr,'mcparam',10);
	if(!submitcheck('bmconfigs')){
		tabheader(lang('memcentrelaset'),'cfview',"?entry=backparams&action=mcparams$param_suffix");
		trbasic(lang('memcenmsgfor'),'mconfigsnew[mmsgforwordtime]',$mconfigs['mmsgforwordtime']);
		trbasic(lang('mrowpp'),'mconfigsnew[mrowpp]',$mconfigs['mrowpp']);
		trbasic(lang('uclmaxamolim'),'mconfigsnew[maxuclassnum]',empty($mconfigs['maxuclassnum']) ? 0 : $mconfigs['maxuclassnum']);
		trbasic(lang('uclbytlenlim'),'mconfigsnew[uclasslength]',$mconfigs['uclasslength']);
		trspecial(lang('memcenterlogo'),'mconfigsnew[mcenterlogo]',$mconfigs['mcenterlogo'],'image',0,lang('agmclogo'));
		trbasic(lang('enablefloatwin'),'mconfigsnew[mallowfloatwin]',empty($mconfigs['mallowfloatwin']) ? 0 : $mconfigs['mallowfloatwin'],'radio');
		trbasic(lang('floatwinwidth'),'mconfigsnew[mfloatwinwidth]',empty($mconfigs['mfloatwinwidth']) ? 0 : $mconfigs['mfloatwinwidth']);
		trbasic(lang('floathei'),'mconfigsnew[mfloatwinheight]',empty($mconfigs['mfloatwinheight']) ? 0 : $mconfigs['mfloatwinheight']);
		tabfooter('bmconfigs');
	}else{
		$mconfigsnew['mmsgforwordtime'] = max(0,intval($mconfigsnew['mmsgforwordtime']));
		$mconfigsnew['mrowpp'] = max(5,intval($mconfigsnew['mrowpp']));
		$mconfigsnew['uclasslength'] = min(30,max(4,intval($mconfigsnew['uclasslength'])));
		$mconfigsnew['mfloatwinwidth'] = min(1200,max(400,intval($mconfigsnew['mfloatwinwidth'])));
		$mconfigsnew['mfloatwinheight'] = min(1000,max(300,intval($mconfigsnew['mfloatwinheight'])));
		$mconfigsnew['maxuclassnum'] = max(0,intval($mconfigsnew['maxuclassnum']));
		$c_upload = new cls_upload;	
		$mconfigsnew['mcenterlogo'] = upload_s($mconfigsnew['mcenterlogo'],$mconfigs['mcenterlogo'],'image');
		if($k = strpos($mconfigsnew['mcenterlogo'],'#')) $mconfigsnew['mcenterlogo'] = substr($mconfigsnew['mcenterlogo'],0,$k);
		$c_upload->saveuptotal(1);
		unset($c_upload);
		saveconfig('view');
		adminlog(lang('websiteset'),lang('pagandtemset'));
		amessage('websitesetfinish',"?entry=backparams&action=mcparams$param_suffix");
	}
}
function saveconfig($cftype){
	global $mconfigs,$mconfigsnew,$db,$tblprefix;
	foreach($mconfigsnew as $k => $v){
		if(!isset($mconfigs[$k]) || $mconfigs[$k] != $v) $db->query("REPLACE INTO {$tblprefix}mconfigs (varname,value,cftype) VALUES ('$k','$v','$cftype')");
	}
	updatecache('mconfigs');
}

?>
