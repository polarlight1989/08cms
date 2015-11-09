<?php
!defined('M_COM') && exit('No Permission');
include_once M_ROOT."./include/marchive.cls.php";
$arc = new cls_marchive();
function marc_static($maid=0,$matid=0){//只是将公示页生成静态，受限页不生成静态
	global $db,$tblprefix,$arc,$cms_abs,$marchtmldir,$timestamp,$templatedir,$G,$_no_dbhalt,
	$btags,$mconfigs,$_mp,$_actid,$_midarr,$_a_vars,$_a_var,$mpnav,$mptitle,$mpstart,$mpend,$mppre,$mpnext,$mppage,$mpcount,$mpacount;
	@extract($mconfigs,EXTR_SKIP);
	@extract($btags);

	if($maid){
		$arc->init();
		$arc->arcid($maid,$matid);
	}
	if(empty($arc->maid)) return false;
	if(!$arc->archive['checked']){
		marc_un_static(0);
		return false;
	}
	if(!($tplname = $arc->matype['arctpl'])) return false;

	$subpath = $arc->archive['matid'].'/'.date('Ym',$arc->archive['createdate']).'/';
	mmkdir(M_ROOT.$marchtmldir.'/'.$subpath);
	$namepre = $subpath.$arc->maid.'_';
	$arc->archive['arcurl'] = $cms_abs.$marchtmldir.'/'.$namepre.'1.html';
	
	$_da = &$arc->archive;
	marc_parse($_da);
	$_o_content = ob_get_contents();
	ob_clean();
	$_no_dbhalt = true;
	
	$pcount = 1;
	for($_pp = 1;$_pp<=$pcount;$_pp ++){
		$_mp = $G = array();
		$_mp['surlpre'] = $cms_abs.$marchtmldir.'/'.$namepre.'{$page}.html';
		$_mp['static'] = 1;
		$_mp['nowpage'] = $_pp;
	
		_aenter($_da,1);
		extract($_da,EXTR_OVERWRITE);
		tpl_refresh($tplname);
		@include M_ROOT."template/$templatedir/pcache/$tplname.php";
		$_content = ob_get_contents();
		ob_clean();
		
		str2file($_content,M_ROOT.$marchtmldir.'/'.$namepre."$_pp.html");
		unset($_content);
		$pcount = @$_mp['pcount'];
	}
	
	echo $_o_content;
	unset($_o_content);
	$_no_dbhalt = false;
	$db->query("UPDATE {$tblprefix}marchives_$matid SET arcurl='".$namepre."1.html' WHERE maid='".$arc->maid."'");
	return true;
}
function marc_unstatic($maid=0,$matid=0){
	global $db,$tblprefix,$arc,$marchtmldir;
	if($maid){
		$arc->init();
		$arc->arcid($maid,$matid);
	}
	if(empty($arc->maid)) return false;
	if(!$arc->archive['arcurl']) return true;
	m_unlink($marchtmldir.'/'.substr($arc->archive['arcurl'],0,-6).'{$page}.html');
	$db->query("UPDATE {$tblprefix}marchives_{$arc->matid} SET arcurl='' WHERE maid='".$arc->maid."'");
	return true;
}

?>