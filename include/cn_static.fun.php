<?php
!defined('M_COM') && exit('No Permission');
function index_static($cnstr = '',$addno = 0,$needwri = 1){
	global $db,$tblprefix,$cms_abs,$liststaticnum,$sid,$enablestatic,$timestamp,$templatedir,$G,$_no_dbhalt,
	$btags,$mconfigs,$_mp,$_actid,$_midarr,$_a_vars,$_a_var,$mpnav,$mptitle,$mpstart,$mpend,$mppre,$mpnext,$mppage,$mpcount,$mpacount;
	@extract($mconfigs,EXTR_SKIP);
	@extract($btags);
	$nenablestatic = $enablestatic;
	$_da = array();
	if(!$cnstr){
		if($addno) return false;
		$tplname = !$sid ? $hometpl : $btags['hometpl'];
		if(!$nenablestatic || !$tplname || !($template = load_tpl($tplname))){//子站的各种配置切换
			index_unstatic($cnstr,$addno,$needwri);
			return false;
		}
		$cnformat = idx_format($sid);
		$_da['rss'] = $cms_abs.'rss.php'.($sid ? "?sid=$sid" : '');
	}else{
		if(!$cnode = cnodearr($cnstr,$sid)) return false;
		$statics = empty($cnode['statics']) ? array() : explode(',',$cnode['statics']);
		$nenablestatic = empty($statics[$addno]) ? $enablestatic : ($statics[$addno] == 1 ? 0 : 1);
		if(!$nenablestatic || !cn_allowstatic($cnstr,$sid) || !($tplname = cn_tplname($cnstr,$cnode,$addno))){
			index_unstatic($cnstr,$addno,$needwri);
			return false;
		}
		$cnformat = cn_format($cnstr,$addno,$cnode);
		$_da = cn_parse($cnstr,$sid,-1);
		re_cnode($_da,$cnstr,$cnode);
	}
	
	$_o_content = ob_get_contents();
	ob_clean();
	$_no_dbhalt = true;
	$pcount = 1;
	for($_pp = 1;$_pp<=$pcount;$_pp ++){
		$G = array();
		$_mp = array(
		'durlpre' => view_url(en_virtual("index.php?".substr(($cnstr ? "&$cnstr" : '').($addno ? "&addno=$addno" : '').($sid ? "&sid=$sid" : '').'&page={$page}',1),1)),
		'surlpre' => view_url($cnformat),
		'static' => 1,
		'nowpage' => $_pp,
		's_num' => $liststaticnum,
		);
		_aenter($_da,1);
		extract($_da,EXTR_OVERWRITE);
		tpl_refresh($tplname);
		@include M_ROOT."template/$templatedir/pcache/$tplname.php";
		$_content = ob_get_contents();
		ob_clean();
		$_content .= "<script language=\"javascript\" src=\"".$cms_abs."tools/static.php?mode=cnindex".($sid ? "&sid=$sid" : '').($cnstr ? "&$cnstr" : '').($addno ? "&addno=$addno" : '')."\"></script>";
		@str2file($_content,M_ROOT.m_parseurl($cnformat,array('page' =>$_pp)));
		unset($_content);
		$pcount = empty($liststaticnum) ? @$_mp['pcount'] : min(@$_mp['pcount'],$liststaticnum);
		
	}
	echo $_o_content;
	unset($_o_content,$_da,$cnode);
	$_no_dbhalt = false;
	
	if($needwri) cn_update_needstatic($cnstr,$sid,$addno,1);
	return true;
}
function cn_update_needstatic($cnstr,$sid,$addnos = array(),$next = 0){
	global $cn_periods,$timestamp,$db,$tblprefix,$indexcircle;
	if(!is_array($addnos)) $addnos = array($addnos);
	if(!$addnos) return;
	if($cnstr){
		if(!$r = $db->fetch_one("SELECT needstatics,periods,addnum FROM {$tblprefix}cnodes WHERE ename='$cnstr' AND sid='$sid'")) return;
		$needstatics = explode(',',$r['needstatics']);
		$periods = explode(',',$r['periods']);
		$nneedstatics = '';
		for($i = 0;$i <= $r['addnum'];$i++){
			$period = $next ? (empty($periods[$i]) ? (empty($cn_periods[$i]) ? 86400*365 : $cn_periods[$i] * 60) : $periods[$i] * 60) : 0;
			$nneedstatics .= (in_array($i,$addnos) ? $timestamp + $period : @$needstatics[$i]).',';
		}
		$db->query("UPDATE {$tblprefix}cnodes SET needstatics='$nneedstatics' WHERE ename='$cnstr' AND sid='$sid'");
	}elseif($sid){
		$db->query("UPDATE {$tblprefix}subsites SET ineedstatic='".($timestamp + ($next ? (!$indexcircle ? 86400*365 : $indexcircle * 60) : 0))."' WHERE sid='$sid'");
	}else $db->query("UPDATE {$tblprefix}mconfigs SET value='".($timestamp + ($next ? (!$indexcircle ? 86400*365 : $indexcircle * 60) : 0))."' WHERE varname='ineedstatic'");
}
function index_unstatic($cnstr='',$addno,$needwri=1){
	global $sid;
	if($cnstr){
		if(!$cnode = read_cnode($cnstr,$sid)) return;
		$cnformat = cn_format($cnstr,$addno,$cnode);
	}else $cnformat = idx_format($sid);
	m_unlink($cnformat);
	if($cnstr || $sid) cn_blank($cnstr,$sid,$addno,1);
	$needwri && cn_update_needstatic($cnstr,$sid,$addno,1);
	return true;
}

?>