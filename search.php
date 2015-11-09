<?php
define('NOROBOT',TRUE);
include_once dirname(__FILE__).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT."./include/fields.cls.php";
load_cache('acatalogs,initfields');
$catalogs = &$acatalogs;

if(!$nousersearch && !$curuser->check_allow('searchpermit')) message('beusenoseapermis');//分析搜索权限
if($search_repeat){
	if($timestamp - @$curuser->info['lastsearch'] < $search_repeat) message('searchoverquick');
	$db->query("UPDATE {$tblprefix}msession SET lastsearch='$timestamp' WHERE msid='".@$curuser->info['msid']."'",'SILENT');
}
$page = empty($page) ? 1 : max(1, intval($page));
$chid = empty($chid) ? 0 : max(0,intval($chid));
if($chid && empty($channels[$chid])) $chid =0;
$allsite = empty($allsite) ? 0 : 1;//用于区别主站与整站的搜索

//搜索资料初始化
$wherestr = "WHERE a.checked=1";
$filterstr = '';//分页链接的附加参数字串
$fromstr = "FROM {$tblprefix}archives AS a";//只有区分模型才可以查找模型字段
$_da = array('sid' => $sid,'allsite' => $allsite);

//处理子站及整站
$wherestr .= $allsite ? '' : " AND a.sid=$sid";
if($allsite){
	$filterstr .= ($filterstr ? '&' : '')."allsite=$allsite";
}elseif($sid){
	switch_cache($sid);
	$filterstr .= ($filterstr ? '&' : '')."sid=$sid";
}

//栏目因素
$caid = empty($caid) ? 0 : max(0,intval($caid));
$_da['caid'] = $caid;
$_da['catalog'] = '';
if($caid){
	$_da['catalog'] = $catalogs[$caid]['title'];
	if($cnsql = cnsql(0,cnsonids($caid,$catalogs),'a.')) $wherestr .= " AND $cnsql";
	$filterstr .= ($filterstr ? '&' : '')."caid=$caid";
}

//分类因素
foreach($cotypes as $k => $v){
	${"ccid$k"} = empty(${"ccid$k"}) ? 0 : max(0,intval(${"ccid$k"}));
	$_da["ccid$k"] = ${"ccid$k"};
	$_da['ccid'.$k.'title'] = '';
	if(${"ccid$k"}){
		$coclasses = read_cache('coclasses',$k);
		$_da['ccid'.$k.'title'] = $coclasses[${"ccid$k"}]['title'];
		if($cnsql = cnsql($k,cnsonids(${"ccid$k"},$coclasses),'a.')) $wherestr .= " AND $cnsql";
		$filterstr .= ($filterstr ? '&' : '')."ccid$k=".${"ccid$k"};
	}
}

//处理indays多少天以内添加的
$indays = empty($indays) ? 0 : max(0,intval($indays));
$_da['indays'] = $indays;
if($indays){
	$wherestr .= " AND a.createdate>'".($timestamp - 86400 * $indays)."'";
	$filterstr .= ($filterstr ? '&' : '')."indays=$indays";
}

//处理outdays多少天以前添加的
$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
$_da['outdays'] = $outdays;
if($outdays){
	$wherestr .= " AND a.createdate<'".($timestamp - 86400 * $outdays)."'";
	$filterstr .= ($filterstr ? '&' : '')."outdays=$outdays";
}

//搜索词预处理
$searchword = empty($searchword) ? '' : cutstr(trim($searchword),50,'');
$_da['searchword'] = $searchword;
if($searchword){
	$filterstr .= ($filterstr ? '&' : '').'searchword='.rawurlencode(stripslashes($searchword));
}
//预处理搜索模式：标题subject、关键词keywords、内容fulltxt、会员mname
$searchmode = empty($searchmode) ? 'subject' : trim($searchmode);
if(!in_array($searchmode,array('subject','keywords','fulltxt','mname'))) $searchmode = 'subject';

if(!$chid){
	$nochids = empty($nochids) ? '' : trim($nochids);
	if($nochids){//排除的模型
		$_da['nochids'] = $nochids;
		$filterstr .= ($filterstr ? '&' : '')."nochids=".$nochids;
		$nochids = explode(',',$nochids);
		if($nochids) $wherestr .= " AND a.chid ".multi_str($nochids,1);
	}

	$_da['chid'] = 0;
	$_da['channel'] = '';
	if($searchmode == 'fulltxt') $searchmode = 'subject';//不区分模型时不允许全文搜索
	$a_field = new cls_field;
	$fields = &$initfields;
	foreach($fields as $k => $field){
		if($field['available'] && $field['issearch']){
			$a_field->init();
			$a_field->field = $field;
			$a_field->deal_search($a_field->field['tbl'] == 'main' ? "a." : "c.");
			$wherestr .= $a_field->searchstr ? (' AND '.$a_field->searchstr) : '';
			$a_field->filterstr && $filterstr .= ($filterstr ? '&' : '').$a_field->filterstr;
			if($field['datatype'] == 'map'){
				foreach(array('_0','_1','diff',) as $var) $_da[$k.$var] = ${$k.$var};
			}elseif($field['issearch'] == 1 || $field['datatype'] == 'text'){
				$_da[$k] = stripslashes($$k);
			}elseif(in_array($field['datatype'],array('select','mselect'))){
				$_da[$k.'str'] = ${$k.'str'};
			}else{
				foreach(array('from','to',) as $var) $_da[$k.$var] = ${$k.$var};
			}
		}
	}
	unset($a_field);
}else{
	//处理chid信息
	$channel = read_cache('channel',$chid);
	cache_merge($channel,'channel',$sid);
	$_da['chid'] = $chid;
	$_da['channel'] = $channel['cname'];
	$wherestr .= " AND a.chid='$chid'";
	$filterstr .= ($filterstr ? '&' : '')."chid=".$chid;
	$fromstr .= " LEFT JOIN {$tblprefix}archives_$chid AS c ON (a.aid=c.aid)";

	$a_field = new cls_field;
	$fields = read_cache('fields',$chid);
	if($searchmode == 'fulltxt'){//处理全文搜索
		if($channel['fulltxt'] && isset($fields[$channel['fulltxt']])) $fulltxt = $channel['fulltxt'];//该模型的全文搜索字段
		if(!empty($fulltxt)){
			$searchword && $wherestr .= ' AND '.($fields[$fulltxt]['tbl'] == 'main' ? 'a' : 'c').".$fulltxt LIKE '%".str_replace(array(' ','*'),'%',addcslashes($searchword, '%_'))."%'";
		}else{//如果全文搜索字段没有定义，则返回为标题搜索
			$searchmode = 'subject';
		}
	}
	foreach($fields as $k => $field){
		if($field['available'] && $field['issearch']){
			$a_field->init();
			$a_field->field = $field;
			$a_field->deal_search($a_field->field['tbl'] == 'main' ? "a." : "c.");
			$wherestr .= $a_field->searchstr ? (' AND '.$a_field->searchstr) : '';
			$a_field->filterstr && $filterstr .= ($filterstr ? '&' : '').$a_field->filterstr;
			if($field['datatype'] == 'map'){
				foreach(array('_0','_1','diff',) as $var) $_da[$k.$var] = ${$k.$var};
			}elseif($field['issearch'] == 1 || $field['datatype'] == 'text'){
				$_da[$k] = stripslashes($$k);
			}elseif(in_array($field['datatype'],array('select','mselect'))){
				$_da[$k.'str'] = ${$k.'str'};
			}else{
				foreach(array('from','to',) as $var) $_da[$k.$var] = ${$k.$var};
			}
		}
	}
	unset($a_field);
}

//最终处理searchmode,会在以上某些环节经过修正，所以放在最后来处理
$_da['searchmode'] = $searchmode;
if($searchmode != 'subject'){
	$filterstr .= ($filterstr ? '&' : '')."searchmode=$searchmode";
}
if($searchword){//非文全搜索下的查询字串处理
	$searchmode != 'fulltxt' && $wherestr .= " AND a.$searchmode LIKE '%".str_replace(array(' ','*'),'%',addcslashes($searchword, '%_'))."%'";
}

$orderstr = '';
$orderby = empty($orderby) ? 'aid' : $orderby;
$_exist_ob = array();
foreach(array('','1','2') as $k){
	$obyvar = 'orderby'.$k;
	$modevar = 'ordermode'.$k;
	if(!empty($$obyvar) && !in_array($$obyvar,$_exist_ob)){
		//处理排序项
		$_da[$obyvar] = $$obyvar;
		$filterstr .= ($filterstr ? '&' : '').$obyvar.'='.$$obyvar;
		$_exist_ob[] = $$obyvar;
		
		//处理排序模式
		$$modevar = empty($$modevar) ? 0 : 1;
		$_da[$modevar] = $$modevar;
		if($$modevar) $filterstr .= ($filterstr ? '&' : '').$modevar.'='.$$modevar;
		//排序字串
		$orderstr .= ($orderstr ? ',a.' : 'ORDER BY a.').$$obyvar.($ordermode ? ' ASC' : ' DESC');
	}
}
unset($_exist_ob);

//汇总查询字串
$sqlstr = "$fromstr $wherestr";
$tplname = @$sptpls['search'];//不区分模型的搜索模板
if($chid && $channel['srhtpl']) $tplname = $channel['srhtpl'];
if(!$tplname) message('definereltem');

$_da['filterstr'] = $filterstr;
$_da['sqlstr'] = $sqlstr;
$_da['orderstr'] = $orderstr;
$_da['nowpage'] = $page;

$_mp = array();
$_mp['durlpre'] = $cms_abs.'search.php?page={$page}'.($filterstr ? "&$filterstr" : '').'';
$_mp['static'] = 0;
$_mp['nowpage'] = max(1,intval($page));
_aenter($_da,1);
@extract($btags);
extract($_da,EXTR_OVERWRITE);
tpl_refresh($tplname);
@include M_ROOT."template/$templatedir/pcache/$tplname.php";

mexit();
?>