<?php
!defined('M_COM') && exit('No Permission');
function fcn_static($fid=0){//静态是在确定的子站环境下
	global $db,$tblprefix,$cms_abs,$sid,$timestamp,$freeinfos,$infohtmldir,$subsites,$templatedir,$G,$_no_dbhalt,
	$btags,$mconfigs,$_mp,$_actid,$_midarr,$_a_vars,$_a_var,$mpnav,$mptitle,$mpstart,$mpend,$mppre,$mpnext,$mppage,$mpcount,$mpacount;
	@extract($mconfigs,EXTR_SKIP);
	@extract($btags);

	if(!$fid || empty($freeinfos[$fid])) return;
	if(!($tplname = $freeinfos[$fid]['tplname'])) return;
	$namepre = (empty($freeinfos[$fid]['sid']) ? $infohtmldir : $subsites[$freeinfos[$fid]['sid']]['dirname']).'/f-'.$fid.'-';

	$_da = array('sid' => $sid,'fid' => $fid);
	$_o_content = ob_get_contents();
	ob_clean();
	$_no_dbhalt = true;
	$pcount = 1;
	for($_pp = 1;$_pp<=$pcount;$_pp ++){
		$_mp = $G = array();
		$_mp['surlpre'] = $cms_abs.$namepre.'{$page}.html';
		$_mp['static'] = 1;
		$_mp['nowpage'] = max(1,intval($_pp));
		
		_aenter($_da,1);
		extract($_da,EXTR_OVERWRITE);
		tpl_refresh($tplname);
		@include M_ROOT."template/$templatedir/pcache/$tplname.php";
		$_content = ob_get_contents();
		ob_clean();
		@str2file($_content,M_ROOT.$namepre."$_pp.html");
		unset($_content);
		$pcount = @$_mp['pcount'];
	}
	echo $_o_content;
	unset($_o_content,$_da);
	$_no_dbhalt = false;
	$db->query("UPDATE {$tblprefix}freeinfos SET arcurl='f-$fid-1.html' WHERE fid=$fid");
	return true;
}
function fcn_unstatic($fid=0){
	global $db,$tblprefix,$freeinfos,$infohtmldir,$subsites;
	if(!$fid || empty($freeinfos[$fid]) || empty($freeinfos[$fid]['arcurl'])) return;
	m_unlink((empty($freeinfos[$fid]['sid']) ? $infohtmldir : $subsites[$freeinfos[$fid]['sid']]['dirname']).'/'.substr($freeinfos[$fid]['arcurl'],0,-6).'{$page}.html');
	$db->query("UPDATE {$tblprefix}freeinfos SET arcurl='' WHERE fid=$fid");
	return;
}

?>