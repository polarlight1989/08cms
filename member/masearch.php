<?php
define('NOROBOT', TRUE);
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT."./include/fields.cls.php";
load_cache('acatalogs');
$catalogs = &$acatalogs;

if(!$nousersearch && !$curuser->check_allow('searchpermit')) message('beusenoseapermis');
if($search_repeat){
	if($timestamp - @$curuser->info['lastsearch'] < $search_repeat) message('searchoverquick');
	$db->query("UPDATE {$tblprefix}msession SET lastsearch='$timestamp' WHERE msid='".@$curuser->info['msid']."'",'SILENT');
}

$page = empty($page) ? 1 : max(1, intval($page));
$matid = empty($matid) ? 0 : max(0,intval($matid));
if(!$matype = read_cache('matype',$matid)) message('choosematype');

//搜索资料初始化
$wherestr = "WHERE ma.checked=1";
$filterstr = '';//分页链接的附加参数字串
$fromstr = "FROM {$tblprefix}marchives_$matid AS ma LEFT JOIN {$tblprefix}members AS m ON (m.mid=ma.mid)";//只有区分模型才可以查找模型字段
$_da = array();

//处理会员名称
$mname = empty($mname) ? '' : trim($mname);
$_da['mname'] = stripslashes($mname);
if($mname){
	$wherestr .= " AND ma.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($mname,'%_'))."%'";
	$filterstr .= ($filterstr ? '&' : '').'mname='.rawurlencode(stripslashes($mname));
}
//处理indays多少天以内注册
$indays = empty($indays) ? 0 : max(0,intval($indays));
$_da['indays'] = $indays;
if($indays){
	$wherestr .= " AND ma.createdate>'".($timestamp - 86400 * $indays)."'";
	$filterstr .= ($filterstr ? '&' : '')."indays=$indays";
}

//处理outdays多少天以前注册
$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
$_da['outdays'] = $outdays;
if($outdays){
	$wherestr .= " AND ma.createdate<'".($timestamp - 86400 * $outdays)."'";
	$filterstr .= ($filterstr ? '&' : '')."outdays=$outdays";
}

//处理mchid信息
$_da['mchid'] = 0;
$_da['mchannel'] = '';

$a_field = new cls_field;
$fields = read_cache('mafields',$matid);
foreach($fields as $k => $field){
	$field = read_cache('mafield',$matid,$k);
	if($field['available'] && $field['issearch']){
		$a_field->init();
		$a_field->field = $field;
		$a_field->deal_search('ma.');
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

$orderstr = '';
$orderby = empty($orderby) ? 'maid' : $orderby;
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
		$orderstr .= ($orderstr ? ',ma.' : 'ORDER BY ma.').$$obyvar.($ordermode ? ' ASC' : ' DESC');
	}
}
unset($_exist_ob);

//汇总查询字串
$sqlstr = "$fromstr $wherestr $orderstr";

$tplname = @$matype['srhtpl'];
if(!$tplname) message('definereltem');

$_da['filterstr'] = $filterstr;
$_da['sqlstr'] = $sqlstr;
$_da['nowpage'] = $page;

$_mp = array();
$_mp['durlpre'] = $memberurl.'masearch.php?page={$page}'.($filterstr ? "&$filterstr" : '');
$_mp['static'] = 0;
$_mp['nowpage'] = max(1,intval($page));
_aenter($_da,1);
@extract($btags);
extract($_da,EXTR_OVERWRITE);
tpl_refresh($tplname);
@include M_ROOT."template/$templatedir/pcache/$tplname.php";
mexit();

?>