<?php
!defined('M_COM') && exit('No Permission');
function mindex_static($cnstr = '',$addno = 0,$needwri = 1){
	global $db,$tblprefix,$cms_abs,$liststaticnum,$homedefault,$sid,$enablestatic,$timestamp,$templatedir,$G,$_no_dbhalt,
	$btags,$mconfigs,$_mp,$_actid,$_midarr,$_a_vars,$_a_var,$mpnav,$mptitle,$mpstart,$mpend,$mppre,$mpnext,$mppage,$mpcount,$mpacount;
	
	@extract($mconfigs,EXTR_SKIP);
	@extract($btags);
	$nenablestatic = $enablestatic;
	if($cnstr){
		if(!($cnode = read_mcnode($cnstr)) || $addno > $cnode['addnum']) return false;
		$statics = empty($cnode['statics']) ? array() : explode(',',$cnode['statics']);
		$nenablestatic = empty($statics[$addno]) ? $enablestatic : ($statics[$addno] == 1 ? 0 : 1);
	}elseif($addno) return false;//频道首页不能带附加页
	if(!$nenablestatic || !($tplname = mcn_tplname($cnstr,$addno))){
		mindex_unstatic($cnstr,$addno,$needwri);
		return false;
	}
	if($cnstr){
		parse_str($cnstr,$_da);
		$_da += m_cnparse($cnstr) + mcnodearr($cnstr);
	}else $_da = array();
	$cnformat = mcn_format($cnstr,$addno);

	$_o_content = ob_get_contents();
	ob_clean();
	$_no_dbhalt = true;
	$pcount = 1;
	for($_pp = 1;$_pp<=$pcount;$_pp ++){
		$_mp = $G = array();
		$_mp['durlpre'] = $memberurl.en_virtual($cnstr ? "index.php?$cnstr".($addno ? "&addno=$addno" : '').'&page={$page}' : 'index.php?page={$page}',1);
		$_mp['surlpre'] = view_url($cnformat);
		$_mp['static'] = 1;
		$_mp['nowpage'] = max(1,intval($_pp));
		$_mp['s_num'] = $liststaticnum;
		
		_aenter($_da,1);
		extract($_da,EXTR_OVERWRITE);
		$sid = 0;//????????????????????????????????????????
		tpl_refresh($tplname);
		@include M_ROOT."template/$templatedir/pcache/$tplname.php";
		$_content = ob_get_contents();
		ob_clean();
		$_content .= "<script language=\"javascript\" src=\"".$cms_abs."tools/static.php?mode=mcnode".($cnstr ? "&$cnstr" : '').($addno ? "&addno=$addno" : '')."\"></script>";
		@str2file($_content,M_ROOT.m_parseurl($cnformat,array('page' =>$_pp)));
		unset($_content);
		$pcount = empty($liststaticnum) ? @$_mp['pcount'] : min(@$_mp['pcount'],$liststaticnum);
		
	}
	echo $_o_content;

	unset($_o_content,$_da,$cnode);
	$_no_dbhalt = false;
	if($needwri) mcn_update_needstatic($cnstr,$addno,1);
	return true;
}
function mcn_update_needstatic($cnstr,$addnos = array(),$next = 0){
	global $cnindexcircle,$timestamp,$db,$tblprefix;
	if(!$cnstr || !($cnode = read_mcnode($cnstr))) return;
	if(!is_array($addnos)) $addnos = array($addnos);
	if(!$addnos) return;
	$needstatics = $db->result_one("SELECT needstatics FROM {$tblprefix}mcnodes WHERE ename='$cnstr'");
	$needstatics = explode(',',$needstatics);
	$nneedstatics = '';
	for($i = 0;$i <= $cnode['addnum'];$i++) $nneedstatics .= (in_array($i,$addnos) ? ($next ? ($timestamp + (!$cnindexcircle ? 86400*365 : $cnindexcircle * 60)) : $timestamp) : @$needstatics[$i]).',';
	$db->query("UPDATE {$tblprefix}mcnodes SET needstatics='$nneedstatics' WHERE ename='$cnstr'");
}

function mindex_unstatic($cnstr='',$addno=0,$needwri=1){
	m_unlink(mcn_format($cnstr,$addno));
	$cnstr && mcn_blank($cnstr,$addno,1);
	$needwri && mcn_update_needstatic($cnstr,$addno,1);
}
function mcn_blank($cnstr,$addnos=array(),$force=0){//force:强行覆盖第一个文件，为0时为修复链接
	global $enablestatic,$memberdir;
	if(!$cnstr || !($cnode = read_mcnode($cnstr))) return;
	if(!is_array($addnos)) $addnos = array($addnos);
	if(!$addnos) return;
	$statics = empty($cnode['statics']) ? array() : explode(',',$cnode['statics']);
	for($i = 0;$i <= $cnode['addnum'];$i++){
		if(in_array($i,$addnos)){
			if(empty($statics[$i]) ? $enablestatic : ($statics[$i] == 1 ? 0 : 1)){
				$cnfile = M_ROOT.m_parseurl(mcn_format($cnstr,$i),array('page' => 1));
				if($force || !is_file($cnfile)) @str2file(direct_html("$memberdir/index.php?$cnstr".($i ? "&&addno=$i" : '')),$cnfile);
			}
		}
	}
}

?>