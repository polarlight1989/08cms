<?php
!defined('M_COM') && exit('No Permission');
include_once M_ROOT."./include/archive.cls.php";
$arc = new cls_archive();
function arc_static($aid=0,$addno=0,$needwri=1){//可能需要多个页面同时进行
	global $db,$tblprefix,$arc,$sid,$timestamp,$cms_abs,$enablestatic,$archivecircle,$templatedir,$G,$_no_dbhalt,
	$btags,$mconfigs,$_mp,$_actid,$_midarr,$_a_vars,$_a_var,$mpnav,$mptitle,$mpstart,$mpend,$mppre,$mpnext,$mppage,$mpcount,$mpacount;
	if($aid) $arc->arcid($aid);
	if(empty($arc->aid) || $addno > $arc->channel['addnum']) return false;
	@extract($mconfigs,EXTR_SKIP);

	switch_cache($arc->archive['sid']);
	$sid = $arc->archive['sid'];
	@extract($btags);

	$tplname = arc_tplname($addno,$arc->archive['arctpls'],$arc->channel['arctpls']);
	$staticarr = empty($arc->channel['statics']) ? array() : explode(',',$arc->channel['statics']);
	$nenablestatic = empty($staticarr[$addno]) ? $enablestatic : ($staticarr[$addno] == 1 ? 0 : 1);
	if(!$nenablestatic || !arc_allowstatic($arc->archive) || !$tplname){
		arc_un_static(0,$addno,$needwri,1);
		return false;
	}
	$arc->detail_data();
	$surlpre = $arc->urlpre($addno,1);
	$filepre = $arc->filepre($addno);
	
	$_da = &$arc->archive;
	arc_parse($_da);
	$_o_content = ob_get_contents();
	ob_clean();
	$_no_dbhalt = true;
	$pcount = 1;
	for($_pp = 1;$_pp<=$pcount;$_pp ++){
		$_mp = $G = array();
		$_mp['surlpre'] = $surlpre;
		$_mp['static'] = 1;
		$_mp['nowpage'] = $_pp;
		_aenter($_da,1);
		extract($_da,EXTR_OVERWRITE);
		tpl_refresh($tplname);
		@include M_ROOT."template/$templatedir/pcache/$tplname.php";
		$_content = ob_get_contents();
		ob_clean();
		$_content .= "<script language=\"javascript\" src=\"".$cms_abs."tools/static.php?mode=arc&aid=$aid".($addno ? "&addno=$addno" : '').($sid ? "&sid=$sid" : '')."\"></script>";
		$arcfile = m_parseurl($filepre,array('page' => $_pp));
		str2file($_content,M_ROOT.$arcfile);
		unset($_content);
		$pcount = @$_mp['pcount'];
	}
	echo $_o_content;
	unset($_o_content);
	$_no_dbhalt = false;
	if($needwri) $arc->update_needstatic($addno,1);
	return true;
}
function arc_un_static($aid=0,$addno=0,$needwri=1,$clearold=0){
	global $db,$tblprefix,$arc,$archivecircle,$timestamp;
	if($aid) $arc->arcid($aid);
	if(empty($arc->aid)) return false;
	$filepre = $arc->filepre($addno);
	$clearold && m_unlink($filepre);
	arc_blank($arc->aid,$addno,M_ROOT.m_parseurl($filepre,array('page' => 1)),1);//强制将初始文件写入
	if($needwri) $arc->update_needstatic($addno,1);
	return true;
}
?>