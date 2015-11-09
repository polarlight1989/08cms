<?
include_once dirname(__FILE__).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/archive.cls.php';

//分析基本信息
parse_str(un_virtual($_SERVER['QUERY_STRING']));
$page = empty($page) ? 1 : max(1, intval($page));
empty($aid) && message('choosearchive');
$aid = max(0,intval($aid));
$arc = new cls_archive();
if(!$arc->arcid($aid)) message('choosearchive');
if(!$arc->archive['checked'] && !$curuser->isadmin()) message('poinarcnoche'); 
$addno = empty($addno) ? 0 : max(0,intval($addno));
if($addno > $arc->channel['addnum']) $addno = 0;

//分析所在子站
switch_cache($arc->archive['sid']);
$sid = $arc->archive['sid'];
if_siteclosed($sid);


//分析权限与扣积分，文章出售
$ispre = 0;//是否启动前导页
$pretpl = $arc->channel['pretpl'];
if(!arc_allow($arc->archive,'aread')){//分析权限，如果有备用页，则进入备用页。
	if(!$pretpl) message('noarchbrowspermis');
	$ispre = 1;
}
if($crids = $arc->arc_crids()){//需要对当前用户扣值
	$cridstr = '';
	foreach($crids['total'] as $k => $v) $cridstr .= ($cridstr ? ',' : '').abs($v).$currencys[$k]['unit'].$currencys[$k]['cname'];
	$commu = read_cache('commu',8);
	if(!empty($commu['setting']['autoarc'])){//不自动扣值的情况：如有前导页，进前导页，否则提示出订阅链接，选择是否订阅
		if(!$pretpl) message('purarcwantpaycur'.$cridstr."<br><br><a href=\"tools/subscribe.php?aid=$aid\">>>".lang('subscribe')."</a>");
		$ispre = 1;
	}else{//自动扣值,当前会员扣值及向出售者支付积分
		if(!$curuser->crids_enough($crids['total'])) message(lang('subarcwantpaycur').$cridstr.lang('younosubsarchivewantenoughcur'));
		$curuser->updatecrids($crids['total'],0,lang('subscribearchive'));
		$curuser->payrecord($arc->aid,0,$cridstr,1);
		if(!empty($crids['sale'])){
			$actuser = new cls_userinfo;
			$actuser->activeuser($arc->archive['mid']);
			foreach($crids['sale'] as $k => $v) $crids['sale'][$k] = -$v;
			$actuser->updatecrids($crids['sale'],1,lang('salearchive'));
			unset($actuser);
		}
	}
}

//读取缓存页面
if(!$enablestatic && $cache1circle){
	$cachefile = htmlcac_dir($ispre ? 'pre' : 'arc',date('Ym',$arc->archive['createdate']),1).cac_namepre($arc->aid).'_'.$page.'.php';
	if(is_file($cachefile) && (filemtime($cachefile) > ($timestamp - $cache1circle * 60))) mexit(read_htmlcac($cachefile));
}

//分析模板来源
$tplname = $ispre ? $pretpl : arc_tplname($addno,$arc->archive['arctpls'],$arc->channel['arctpls']);
!$tplname && message('definereltem');

$arc->detail_data();
$durlpre = $arc->urlpre($addno);
$_da = &$arc->archive;
arc_parse($_da);
$_mp = array();
$_mp['durlpre'] = $durlpre;
$_mp['static'] = 0;
$_mp['nowpage'] = max(1,intval($page));
$_mp['s_num'] = 0;//静态页数不限
_aenter($_da,1);
@extract($btags);
extract($_da,EXTR_OVERWRITE);
tpl_refresh($tplname);
include M_ROOT."template/$templatedir/pcache/$tplname.php";

$_content = ob_get_contents();
ob_clean();
if($enablestatic){
	$_content .= "<script language=\"javascript\" src=\"".$cms_abs."tools/static.php?mode=arc&aid=$aid".($sid ? "&sid=$sid" : '').($addno ? "&addno=$addno" : '')."\"></script>";
}elseif($cache1circle) save_htmlcac($_content,$cachefile);
mexit($_content);

?>

