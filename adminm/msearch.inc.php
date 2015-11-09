<?php
!defined('M_COM') && exit('No Permission');
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/fields.fun.php";
load_cache('acatalogs,mchannels,cotypes,initmfields');
$catalogs = &$acatalogs;
!$curuser->check_allow('searchpermit') && mcmessage('usernosearchpermi');
$page = empty($page) ? 1 : max(1, intval($page));
$search_max && $page = min($page,@ceil($search_max / $mrowpp));
$mchid = empty($mchid) ? 0 : max(0,intval($mchid));
if($mchid && empty($mchannels[$mchid])) $mchid =0;

//搜索资料初始化
$wherestr = "WHERE m.checked=1";
$filterstr = '';//分页链接的附加参数字串
$fromstr = "FROM {$tblprefix}members AS m LEFT JOIN {$tblprefix}members_sub AS s ON (s.mid=m.mid)";//只有区分模型才可以查找模型字段
$item = array();

//会员组//暂时不区分模型//只取出非系统组系
foreach($grouptypes as $k => $v){
	if(!$v['issystem']){
		${'grouptype'.$k} = empty(${'grouptype'.$k}) ? 0 : max(0,intval(${'grouptype'.$k}));
		$item['grouptype'.$k] = ${'grouptype'.$k};
		$item['grouptype'.$k.'name'] = '';
		if(${"grouptype$k"}){
			$item['grouptype'.$k.'name'] = $v['cname'];
			$wherestr .= " AND m.grouptype$k = '".${'grouptype'.$k}."'";
			$filterstr .= ($filterstr ? '&' : '')."grouptype$k=".${'grouptype'.$k};
		}
	}
}

//栏目关联//暂时不区分模型
$caid = empty($caid) ? 0 : max(0,intval($caid));
$item['caid'] = $caid;
$item['catalog'] = '';
if($caid){
	$item['catalog'] = $catalogs[$caid]['title'];
	$tempids = array($caid);
	$tempids = son_ids($catalogs,$caid,$tempids);
	$wherestr .= " AND m.caid ".multi_str($tempids);
	$filterstr .= ($filterstr ? '&' : '')."caid=$caid";
}

//分类关联//暂时不区分模型//需要兼容类目节点页，所以要用ccid来表达不同的类系
foreach($cotypes as $k => $v){
	if(!$v['self_reg']){
		${"ccid$k"} = empty(${"ccid$k"}) ? 0 : max(0,intval(${"ccid$k"}));
		$item["ccid$k"] = ${"ccid$k"};
		$item['ccid'.$k.'title'] = '';
		if(${"ccid$k"}){
			$coclasses = read_cache('coclasses',$k);
			$item['ccid'.$k.'title'] = $coclasses[${"ccid$k"}]['title'];
			$tempids = array(${"ccid$k"});
			$tempids = son_ids($coclasses,${"ccid$k"},$tempids);
			$wherestr .= " AND m.ccid$k ".multi_str($tempids);
			$filterstr .= ($filterstr ? '&' : '')."ccid$k=".${"ccid$k"};
		}
	}
}

//处理会员名称
$mname = empty($mname) ? '' : trim($mname);
$item['mname'] = stripslashes($mname);
if($mname){
	$wherestr .= " AND m.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($mname,'%_'))."%'";
	$filterstr .= ($filterstr ? '&' : '').'mname='.rawurlencode(stripslashes($mname));
}
//处理indays多少天以内注册
$indays = empty($indays) ? 0 : max(0,intval($indays));
$item['indays'] = $indays;
if($indays){
	$wherestr .= " AND m.regdate>'".($timestamp - 86400 * $indays)."'";
	$filterstr .= ($filterstr ? '&' : '')."indays=$indays";
}

//处理outdays多少天以前注册
$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
$item['outdays'] = $outdays;
if($outdays){
	$wherestr .= " AND m.regdate<'".($timestamp - 86400 * $outdays)."'";
	$filterstr .= ($filterstr ? '&' : '')."outdays=$outdays";
}

if(!$mchid){
	//处理mchid信息
	$item['mchid'] = 0;
	$item['mchannel'] = '';

	$a_field = new cls_field;
	$fields = &$initmfields;
	foreach($fields as $k => $field){
		if($field['available'] && $field['issearch']){
			$a_field->init(1);
			$a_field->field = $field;
			$a_field->deal_search($a_field->field['tbl'] == 'sub' ? 's.' : 'c.');
			$wherestr .= $a_field->searchstr ? (' AND '.$a_field->searchstr) : '';
			$a_field->filterstr && $filterstr .= ($filterstr ? '&' : '').$a_field->filterstr;
			if($field['issearch'] == 1 || $field['datatype'] == 'text'){
				$item[$k] = stripslashes($$k);
			}elseif(in_array($field['datatype'],array('select','mselect'))){
				$item[$k.'str'] = ${$k.'str'};
			}else{
				$item[$k.'from'] = ${$k.'from'};
				$item[$k.'to'] = ${$k.'to'};
			}
		}
	}
	unset($a_field);
}else{
	//处理mchid信息
	$mchannel = $mchannels[$mchid];
	$item['mchid'] = $mchid;
	$item['mchannel'] = $mchannel['cname'];
	$wherestr .= " AND m.mchid='$mchid'";
	$filterstr .= ($filterstr ? '&' : '')."mchid=".$mchid;
	$fromstr .= " LEFT JOIN {$tblprefix}members_$mchid AS c ON (c.mid=m.mid)";

	$a_field = new cls_field;
	$fields = read_cache('mfields',$mchid);
	foreach($fields as $k => $field){
		$field = read_cache('mfield',$mchid,$k);
		if($field['available'] && !$field['issystem'] && $field['issearch']){
			$a_field->init(1);
			$a_field->field = $field;
			$a_field->deal_search($a_field->field['tbl'] == 'sub' ? 's.' : 'c.');
			$wherestr .= $a_field->searchstr ? (' AND '.$a_field->searchstr) : '';
			$a_field->filterstr && $filterstr .= ($filterstr ? '&' : '').$a_field->filterstr;
			if($field['issearch'] == 1 || $field['datatype'] == 'text'){
				$item[$k] = stripslashes($$k);
			}elseif(in_array($field['datatype'],array('select','mselect'))){
				$item[$k.'str'] = ${$k.'str'};
			}else{
				$item[$k.'from'] = ${$k.'from'};
				$item[$k.'to'] = ${$k.'to'};
			}
		}
	}
	unset($a_field);
}

//处理排序项
$orderby = empty($orderby) ? 'regdate' : $orderby;
$item['orderby'] = $orderby;
if($orderby != 'regdate'){
	$filterstr .= ($filterstr ? '&' : '')."orderby=".rawurlencode($orderby);
}

//处理排序模式
$ordermode = empty($ordermode) ? 0 : 1;
$item['ordermode'] = $ordermode;
if($ordermode){
	$filterstr .= ($filterstr ? '&' : '')."ordermode=$ordermode";
}
//排序字串
$orderstr = "ORDER BY $orderby ".($ordermode ? 'ASC' : 'DESC');

//汇总查询字串
$sqlstr = "$fromstr $wherestr $orderstr";

//页面部分
//选择不同的模型进行搜索
$mchidsarr = array('0' => lang('allchannel')) + mchidsarr();
mtabheader_e();
echo "<tr align=\"center\">\n";
foreach($mchidsarr as $k => $v) echo "<td class=\"item".($mchid == $k ? 1 : 2)."\">".($mchid == $k ? "<b>$v</b>" : "<a href=\"?action=msearch&mchid=$k\">$v</a>")."</td>\n";
echo "</tr>\n";
mtabfooter();

$caidsarr = array('0' => lang('allcatalog')) + caidsarr();
$orderbyarr = array(
	'm.regdate' => lang('registertime'),
	's.archives' => lang('archiveamount'),
	's.comments' => lang('commentamount'),
);
mtabheader(($mchid ? $mchannels[$mchid]['cname'] : lang('allmember')).'&nbsp;&nbsp;'.lang('searchsetting'),'search',"adminm.php?action=msearch&mchid=$mchid");
mtrbasic(lang('membercname'),'mname',$mname);
mtrbasic(lang('belongcatalog'),'caid',makeoption($caidsarr,$caid),'select');
$omodestr = "&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"ordermode\" value=\"1\"".(empty($ordermode) ? '' : ' checked').">".lang('asc');
mtrbasic(lang('ordertype').$omodestr,'orderby',makeoption($orderbyarr,$orderby),'select');
foreach($cotypes as $k => $v){
	if(!$v['self_reg']){
		$ccidsarr = array('0' => lang('nolimit')) + ccidsarr($k);
		mtrbasic("$v[cname]","ccid$k",makeoption($ccidsarr,${"ccid$k"}),'select');
	}
}

foreach($grouptypes as $k => $v){
	if(!$v['issystem']){
		$ugidsarr = array('0' => lang('nolimit')) + ugidsarr($k,$mchid);
		mtrbasic("$v[cname]","grouptype$k",makeoption($ugidsarr,${"grouptype$k"}),'select');
	}
}


if($mchid){
	$a_field = new cls_field;
	foreach($fields as $k => $field){
		$field = read_cache('mfield',$mchid,$k);
		if($field['available'] && !$field['issystem'] && $field['issearch']){
			$a_field->init(1);
			$a_field->field = $field;
			$a_field->trsearch();
		}
	}
	unset($a_field);
}else{//不区分模型时使用通用字段中的可搜索选项
	$a_field = new cls_field;
	$fields = &$initmfields;
	foreach($fields as $k => $field){
		if($field['available'] && !$field['issystem'] && $field['issearch']){
			$a_field->init(1);
			$a_field->field = $field;
			$a_field->trsearch();
		}
	}
	unset($a_field);
}
mtrbasic(lang('indays'),'indays',$indays);
mtrbasic(lang('outdays'),'outdays',$outdays);
mtabfooter('searchsubmit',lang('search'));

if(submitcheck('searchsubmit')){
	if($search_repeat){
		empty($m_cookie['08cms_msearch_time']) ? msetcookie('08cms_msearch_time','1',$search_repeat) : mcmessage('searchoverquick');
	}

	$pagetmp = $page;
	do{
		$query = $db->query("SELECT m.*,s.* $fromstr $wherestr $orderstr LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
		$pagetmp--;
	}while(!$db->num_rows($query) && $pagetmp);
	$strrow = '';
	$no = $pagetmp * $mrowpp;
	while($row = $db->fetch_array($query)){
		$no ++;
		$spacestr = "<a href=\"mspace/index.php?mid=$row[mid]\" target=\"_blank\">".lang('look')."</a>";
		$row['regdate'] = date("$dateformat $timeformat", $row['regdate']);
		$strrow .= "<tr><td align=\"center\" class=\"item1\" width=\"40\">$no</td>\n".
			"<td align=\"center\" class=\"item2\" width=\"50\">$row[mid]</td>\n".
			"<td class=\"item1\">$row[mname]</td>\n".
			"<td align=\"center\" class=\"item2\" width=\"80\">$spacestr</td>\n".
			"<td align=\"center\" class=\"item1\" width=\"120\">$row[regdate]</td></tr>\n";
	}
	$counts = $db->result_one("SELECT count(*) $fromstr $wherestr");
	$search_max && $counts = min($counts,$search_max);
	$multi = multi($counts,$mrowpp,$page,"adminm.php?action=msearch&mchid=$mchid&$filterstr&searchsubmit=1");

	mtabheader(lang('searchresultlist'),'','',9);
	mtrcategory(array(lang('sn'),lang('memberid'),lang('membercname'),lang('space'),lang('registertime')));
	echo $strrow;
	mtabfooter();
	echo $multi;
}
?>