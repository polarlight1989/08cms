<?php
!defined('M_COM') && exit('No Permission');
$arc = new cls_farchive();
function farc_static($aid=0){
	global $db,$tblprefix,$arc,$timestamp,$cms_abs,$infohtmldir,$templatedir,$G,$_no_dbhalt,
	$btags,$mconfigs,$_mp,$_actid,$_midarr,$_a_vars,$_a_var,$mpnav,$mptitle,$mpstart,$mpend,$mppre,$mpnext,$mppage,$mpcount,$mpacount;
	@extract($mconfigs,EXTR_SKIP);

	if($aid){
		$arc->init();
		$arc->arcid($aid);
	}
	if(empty($arc->aid)) return false;
	if(!$arc->archive['checked'] || !$arc->archive['startdate'] || ($arc->archive['enddate'] && $arc->archive['enddate'] < $timestamp)){//失效信息，取消静态
		farc_unstatic(0);
		return false;
	}
	if(!($tplname = $arc->catalog['arctpl'])) return false;
	$namepre = $infohtmldir.'/a-'.$arc->aid.'-';
	$_da = &$arc->archive;
	$_o_content = ob_get_contents();
	ob_clean();
	$_no_dbhalt = true;
	
	$pcount = 1;
	for($_pp = 1;$_pp<=$pcount;$_pp ++){
		$_mp = $G = array();
		$_mp['surlpre'] = $cms_abs.$namepre.'{$page}.html';
		$_mp['static'] = 1;
		$_mp['nowpage'] = $_pp;
	
		_aenter($_da,1);
		@extract($btags);
		extract($_da,EXTR_OVERWRITE);
		tpl_refresh($tplname);
		@include M_ROOT."template/$templatedir/pcache/$tplname.php";
		$_content = ob_get_contents();
		ob_clean();
		str2file($_content,M_ROOT.$namepre."$_pp.html");
		unset($_content);
		$pcount = @$_mp['pcount'];
	}
	echo $_o_content;
	unset($_o_content);
	$_no_dbhalt = false;
	$db->query("UPDATE {$tblprefix}farchives SET arcurl='a-".$arc->aid."-1.html' WHERE aid='".$arc->aid."'");
	return true;
}
function farc_unstatic($aid=0){
	global $db,$tblprefix,$arc,$infohtmldir;
	if($aid){
		$arc->init();
		$arc->arcid($aid);
	}
	if(empty($arc->aid)) return false;
	if(!$arc->archive['arcurl']) return true;
	m_unlink($infohtmldir.'/'.substr($arc->archive['arcurl'],0,-6).'{$page}.html');
	$db->query("UPDATE {$tblprefix}farchives SET arcurl='' WHERE aid='".$arc->aid."'");
	return true;
}

?>