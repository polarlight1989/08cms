<?php
include_once M_ROOT."./include/arcedit.cls.php";
include_once M_ROOT."./include/commu.fun.php";
load_cache('permissions,vcps,channels,cotypes,acatalogs,');
!defined('M_COM') && exit('No Permission');
$catalogs = &$acatalogs;
if(!$memberid) mcmessage('plogin');

$chidsarr = array();
foreach($channels as $k => $v){//当前会员允许报价的产品
	$v = read_cache('channel',$k);
	if($v['offer'] && $commu = read_cache('commu',$v['offer'])){
		if($curuser->pmbypmids('cuadd',$commu['setting']['apmid'])) $chidsarr[$k] = $v['cname'];
	}

}
if(!$chidsarr) mcmessage('no_product');
$u_chids = array_keys($chidsarr);

$page = !empty($page) ? max(1, intval($page)) : 1;
submitcheck('bfilter') && $page = 1;
$viewdetail = empty($viewdetail) ? 0 : 1;
$caid = empty($caid) ? 0 : max(0,intval($caid));
$chid = empty($chid) ? 0 : max(0,intval($chid));
$keyword = empty($keyword) ? '' : $keyword;
$wheresql = "a.checked=1 AND cu.cid IS NULL";
$fromsql = "FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}offers cu ON (cu.aid=a.aid AND cu.mid='$memberid')";

//栏目范围
if($caid){
	$caids = cnsonids($caid,$catalogs);
	$wheresql .= " AND a.caid ".multi_str($caids);
}

if($chid){
	if(!in_array($chid,$u_chids)) $no_list = true;
	$wheresql .= " AND a.chid='$chid'";
}else $wheresql .= " AND a.chid ".multi_str($u_chids);

//搜索关键词处理
$keyword && $wheresql .= " AND a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";

$filterstr = '';
foreach(array('caid','chid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));

$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');

if(!submitcheck('barcsedit')){
	echo form_str($action.'archivesedit',"?action=products&page=$page",2,0,0,0);
	tabheader_e();
	echo "<tr><td class=\"item2\">";
	echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
	$caidsarr = array('0' => lang('catalog')) + caidsarr($catalogs);
	echo "<select style=\"vertical-align: middle;\" name=\"caid\">".makeoption($caidsarr,$caid)."</select>&nbsp; ";
	echo "<select style=\"vertical-align: middle;\" name=\"chid\">".makeoption(array('0' => lang('arctype')) + $chidsarr,$chid)."</select>&nbsp; ";
	echo strbutton('bfilter','filter0').'</td></tr>';
	tabfooter();

	$pagetmp = $page;
	do{
		$query = $db->query("SELECT a.*,cu.cid $fromsql $wheresql ORDER BY a.aid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
		$pagetmp--;
	}while(!$db->num_rows($query) && $pagetmp);

	tabheader(lang('productlist'),'','',30);
	$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('productname'),);
	$cy_arr[] = lang('catalog');
	$cy_arr[] = lang('arctype');
	$cy_arr[] = lang('offer');
	$cy_arr[] = lang('pro_price');
	$cy_arr[] = lang('avg_price');
	$cy_arr[] = lang('updatetime');
	$cy_arr[] = lang('message');
	trcategory($cy_arr);

	$itemstr = '';
	while($row = $db->fetch_array($query)){
		$channel = read_cache('channel',$row['chid']);
		$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\">";
		$row['arcurl'] = view_arcurl($row);
		$subjectstr = ($row['thumb'] ? lang('imged') : '')." &nbsp;<a href=$row[arcurl] target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
		$catalogstr = @$catalogs[$row['caid']]['title'];
		$channelstr = @$channel['cname'];
		$offersstr = $row['offers'];
		$propricestr = @$row['proprice'];
		$avgpricestr = @$row['bus_avg_price'];
		$updatedatestr = $row['updatedate'] ? date('Y-m-d',$row['updatedate']) : '-';
		$viewstr = "<a id=\"{$action}_info_$row[aid]\" href=\"?action=arcview&aid=$row[aid]\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";

		$itemstr .= "<tr><td class=\"item\">$selectstr</td><td class=\"item2\">$subjectstr</td>\n";
		$itemstr .= "<td class=\"item\">$catalogstr</td>\n";
		$itemstr .= "<td class=\"item\">$channelstr</td>\n";
		$itemstr .= "<td class=\"item\">$offersstr</td>\n";
		$itemstr .= "<td class=\"item\">$propricestr</td>\n";
		$itemstr .= "<td class=\"item\">$avgpricestr</td>\n";
		$itemstr .= "<td class=\"item\">$updatedatestr</td>\n";
		$itemstr .= "<td class=\"item\">$viewstr</td>\n";
		$itemstr .= "</tr>\n";


	}
	$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
	$multi = multi($counts,$mrowpp,$page,"?action=products$filterstr");
	echo $itemstr;
	tabfooter();
	echo $multi;
	echo '<br><br>'.strbutton('barcsedit','addtooffer');
}else{
	if(empty($selectid)) mcmessage('selectarchive',axaction(2,M_REFERER));
	$aedit = new cls_arcedit;
	foreach($selectid as $aid){
		$aedit->init();
		$aedit->set_aid($aid);
		$aedit->newoffer();
	}
	unset($aedit);
	mcmessage('productadded',axaction(6,"?action=products$filterstr&page=$page"));
}
?>
