<?
//集中展示会员发表的回复性的文档
!defined('M_COM') && exit('No Permission');
include_once M_ROOT."./include/arcedit.cls.php";
load_cache('channels,acatalogs,currencys,commus');
$catalogs = &$acatalogs;
$cuid = empty($cuid) ? 0 : max(0,intval($cuid));
$caid = empty($caid) ? '0' : $caid;
$forward = empty($forward) ? M_URI : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
$nchannels = array();
foreach($channels as $k => $v){
	if(@$commus[$v['cuid']]['cclass'] == 'reply' && (!$cuid || $cuid == $v['cuid'])) $nchannels[$k] = $v;
}
empty($nchannels) && mcmessage('withoutarchiveoralbum');
$page = !empty($page) ? max(1, intval($page)) : 1;
submitcheck('bfilter') && $page = 1;
$viewdetail = empty($viewdetail) ? '' : $viewdetail;
$checked = isset($checked) ? $checked : '-1';
$subject = empty($subject) ? '' : $subject;
$indays = empty($indays) ? 0 : max(0,intval($indays));
$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
$filterstr = '';
foreach(array('viewdetail','cuid','caid','subject','checked','indays','outdays') as $k){
	$filterstr .= "&$k=".urlencode($$k);
}
$wheresql = "WHERE a.chid ".multi_str(array_keys($nchannels))." AND a.mid='".$curuser->info['mid']."'";
if($checked != '-1') $wheresql .= " AND a.checked='$checked'";
if(!empty($caid)){
	$caids = cnsonids($caid,$catalogs);
	$wheresql .= " AND a.caid ".multi_str($caids);
}
if($subject) $wheresql .= " AND a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($subject,'%_'))."%'";
if($indays) $wheresql .= " AND a.createdate>'".($timestamp - 86400 * $indays)."'";
if($outdays) $wheresql .= " AND a.createdate<'".($timestamp - 86400 * $outdays)."'";
//类型选择框
$cuidsarr = array(0 => lang('nolimittype'));
foreach($commus as $k => $v){
	if($v['cclass'] == 'reply') $cuidsarr[$k] = $v['cname'].lang('related');
}
if(count($cuidsarr) > 2){
	tabheader_e();
	echo "<tr align=\"center\">\n";
	foreach($cuidsarr as $k => $v) echo "<td class=\"item".($cuid == $k ? 5 : '')."\">".($cuid == $k ? "<b>$v</b>" : "<a href=\"?action=rarchives&cuid=$k\">$v</a>")."</td>\n";
	echo "</tr>\n";
	tabfooter();
}

$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheckquestion'),'1' => lang('checkedquestion'));
tabheader(lang('filtersetting').viewcheck('viewdetail',$viewdetail,'tbodyfilter').'&nbsp; &nbsp; '.strbutton('bfilter','filter0'),'arcsedit',"?action=rarchives&page=$page");
$caidsarr = array('0' => lang('allcatalog')) + caidsarr($catalogs);
echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
trhidden('cuid',$cuid);
trbasic(lang('belongcatalog'),'caid',makeoption($caidsarr,$caid),'select');
trbasic(lang('weatherchecked'),'',makeradio('checked',$checkedarr,$checked),'');
trbasic(lang('archivetitle'),'subject',$subject);
trrange(lang('adddate'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('daybefore').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('dayin'),5));
echo "</tbody>";
tabfooter();

$pagetmp = $page;
do{
	$query = $db->query("SELECT a.*,s.* FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid $wheresql ORDER BY a.aid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
	$pagetmp--;
} while(!$db->num_rows($query) && $pagetmp);
$itemstr = '';
while($item = $db->fetch_array($query)){
	$arcurl = view_arcurl($item);
	$createdate = date("$dateformat", $item['createdate']);
	$checkedstr = $item['checked'] ? 'Y' : '-';
	$itemstr .= "<tr>".
	"<td class=\"item\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$item[aid]]\" value=\"$item[aid]\"></td>\n".
	"<td class=\"item2\"><a href=$arcurl target=\"_blank\">".mhtmlspecialchars($item['subject'])."</a></td>\n".
	"<td class=\"item\" width=\"36\">$checkedstr</td>\n".
	"<td class=\"item\" width=\"66\">$createdate</td>\n".
	"<td class=\"item\" width=\"36\"><a href=\"?action=".($item['atid'] ? 'albumsedit' : 'archivesedit')."&aid=$item[aid]$forwardstr\">".lang('edit')."</a></td>".
	"<td class=\"item\" width=\"46\">$item[replys]</td>\n".
	"<td class=\"item\" width=\"36\"><a href=\"?action=areplys&aid=$item[aid]$forwardstr\">".lang('admin')."</a></td>".
	"</tr>\n";
}
$counts = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid $wheresql");
$multi = multi($counts,$mrowpp,$page,"?action=rarchives$filterstr");

tabheader(lang('contentlist'),'','',11);
trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),lang('check'),lang('adddate'),lang('content'),lang('replys'),lang('reply')));
echo $itemstr;
tabfooter();
echo $multi;
echo '</form>';
?>