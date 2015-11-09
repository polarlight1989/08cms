<?
!defined('M_COM') && exit('No Permission');
load_cache('mchannels,currencys,mcommus,mrfields');
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/upload.cls.php";
$forward = empty($forward) ? M_URI : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
$page = empty($page) ? 1 : max(1, intval($page));

//收到的回复的个人分类
$uclasses = loaduclasses($curuser->info['mid']);
$ocuids = $ucidsarr = array();
foreach($mcommus as $k => $v) if($v['cclass'] == 'comment') $ocuids[] = -$k;
foreach($uclasses as $k => $v) if(in_array($v['cuid'],$ocuids)) $ucidsarr[$k] = $v['title'];

submitcheck('bfilter') && $page = 1;
$viewdetail = empty($viewdetail) ? '' : $viewdetail;
$checked = isset($checked) ? $checked : '-1';
$fromid = empty($fromid) ? '0' : $fromid;
$fromname = empty($fromname) ? '' : $fromname;
$cuid = empty($cuid) ? 0 : $cuid;
$ucid = empty($ucid) ? 0 : $ucid;
$indays = empty($indays) ? 0 : max(0,intval($indays));
$outdays = empty($outdays) ? 0 : max(0,intval($outdays));

$listname = lang('comment');
if($cuid && $mcommu = read_cache('mcommu',$cuid)){//交互分类进行一下处理
	$listname = $mcommu['cname'];
}

$filterstr = '';
foreach(array('viewdetail','fromid','fromname','checked','cuid','ucid','indays','outdays') as $k){
	$filterstr .= "&$k=".urlencode($$k);
}

$fromsql = "FROM {$tblprefix}mcomments";
$wheresql = "WHERE mid='$memberid'";
if($cuid) $wheresql .= " AND cuid='$cuid'";
if($ucid) $wheresql .= " AND ucid='$ucid'";
if($fromid) $wheresql .= " AND fromid='$fromid'";
if($fromname) $wheresql .= " AND fromname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($fromname,'%_'))."%'";
if($checked != '-1') $wheresql .= " AND checked='$checked'";
if($indays) $wheresql .= " AND updatedate>'".($timestamp - 86400 * $indays)."'";
if($outdays) $wheresql .= " AND updatedate<'".($timestamp - 86400 * $outdays)."'";

$urlsarr = array(0 => array(lang('nolimittype'),"?action=$action"));
foreach($mcommus as $k => $v) if($v['cclass'] == 'comment') $urlsarr[$k] = array($v['cname'], "?action=$action&cuid=$k");
count($urlsarr) > 2 && murl_nav($urlsarr,$cuid,6);

if(!submitcheck('barcsedit')){
	$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheckcomment'),'1' => lang('checkedcomment'));
	tabheader(lang('filtersetting').viewcheck('viewdetail',$viewdetail,'tbodyfilter').'&nbsp; &nbsp; '.strbutton('bfilter','filter0'),'arcsedit',"?action=amcomments&page=$page");
	echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
	trhidden('cuid',$cuid);
	trbasic(lang('checkstate'),'',makeradio('checked',$checkedarr,$checked),'');
	trbasic(lang('uclass'),'ucid',makeoption(array(0 => lang('allcoclass')) + $ucidsarr,$ucid),'select');
	trbasic(lang('srcmemberid'),'fromid',$fromid);
	trbasic(lang('srcmembercname'),'fromname',$fromname,'text',lang('agsearchkey'));
	trrange(lang('commentdate'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('daybefore').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('dayin'),5));
	echo "</tbody>";
	tabfooter();

	$pagetmp = $page;
	do{
		$query = $db->query("SELECT * $fromsql $wheresql ORDER BY cid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
		$pagetmp--;
	} while(!$db->num_rows($query) && $pagetmp);
	$itemstr = '';
	while($item = $db->fetch_array($query)){
		$mcommu = read_cache('mcommu',$item['cuid']);
		$createdatestr = date("$dateformat", $item['createdate']);
		$checkedstr = $item['checked'] ? 'Y' : '-';
		$areplystr = $item['areply'] ? 'Y' : '-';
		$areadstr = $item['aread'] ? 'Y' : '-';
		$uclassstr = empty($ucidsarr[$item['ucid']]) ? '-' : $ucidsarr[$item['ucid']];
		$itemstr .= "<tr><td class=\"item\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$item[cid]]\" value=\"$item[cid]\"></td>\n".
			"<td class=\"item\" width=\"30\">$item[fromid]</td>\n".
			"<td class=\"item2\"><a href=\"{$mspaceurl}index.php?mid=$item[fromid]\" target=\"_blank\">$item[fromname]</a></td>\n".
			"<td class=\"item\">$uclassstr</td>\n".
			"<td class=\"item\" width=\"30\">$checkedstr</td>\n".
			"<td class=\"item\" width=\"60\">$createdatestr</td>\n".
			"<td class=\"item\" width=\"30\">$areplystr</td>\n".
			"<td class=\"item\" width=\"30\">$areadstr</td>\n".
			"<td class=\"item\" width=\"36\"><a href=\"?action=mcomment&mid=$item[mid]&cid=$item[cid]&amode=1\" onclick=\"return floatwin('open_mcomment',this)\">".lang('look')."</a></td></tr>\n";
	}
	$counts = $db->result_one("SELECT COUNT(*) $fromsql $wheresql");
	$multi = multi($counts,$mrowpp,$page,"?action=amcomments$filterstr");

	tabheader($listname.lang('list'),'','',11);
	trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('id'),lang('srcmember'),lang('uclass'),lang('check'),lang('addtime'),lang('reply0'),lang('read'),lang('detail')));
	echo $itemstr;
	tabfooter();
	echo $multi;

	$ucidsarr = array('0' => lang('cancelcoclass')) + $ucidsarr;
	$checkedarr = array('0' => lang('uncheck'),'1' => lang('check'));
	tabheader(lang('operateitem'));
	trbasic(lang('choose_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[delete]\" value=\"1\">&nbsp;".lang('delete').$listname,'');
	trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[check]\" value=\"1\">&nbsp;".lang('check'),'arcchecked',makeradio('arcchecked',$checkedarr,1),'');
	trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[ucid]\" value=\"1\">&nbsp;".lang('uclass'),'arcucid',makeoption($ucidsarr),'select');
	tabfooter('barcsedit');
}else{
	if(empty($arcdeal)) mcmessage('selectoperateitem',"?action=amcomments&page=$page$filterstr");
	if(empty($selectid)) mcmessage('selectcomment',"?action=amcomments&page=$page$filterstr");
	if(!empty($arcdeal['delete'])){
		$db->query("DELETE FROM {$tblprefix}mcomments WHERE mid='$memberid' AND cid ".multi_str($selectid));
	}else{
		if(!empty($arcdeal['check'])){
			$db->query("UPDATE {$tblprefix}mcomments SET checked='$arcchecked' WHERE mid='$memberid' AND cid ".multi_str($selectid));
		}
		if(!empty($arcdeal['ucid'])){
			$db->query("UPDATE {$tblprefix}mcomments SET ucid='$arcucid' WHERE mid='$memberid' AND cid ".multi_str($selectid));
		}
	}
	mcmessage('commentadminfinish',"?action=amcomments&page=$page$filterstr");
}

?>