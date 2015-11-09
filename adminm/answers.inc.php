<?
//会员提出的所有答案管理
!defined('M_COM') && exit('No Permission');
include_once M_ROOT."./include/arcedit.cls.php";
load_cache('currencys,acatalogs');
$catalogs = &$acatalogs;
$cid = empty($cid) ? 0 : max(0,intval($cid));
$page = empty($page) ? 1 : max(1, intval($page));
$viewdetail = empty($viewdetail) ? '' : $viewdetail;
$checked = isset($checked) ? $checked : '-1';
$caid = empty($caid) ? '0' : $caid;
$keyword = empty($keyword) ? '' : $keyword;
$filterstr = '';
foreach(array('viewdetail','checked','caid','keyword',) as $k){
	$filterstr .= "&$k=".urlencode($$k);
}
$wheresql = "WHERE cu.mid='$memberid'";
if($checked != '-1') $wheresql .= " AND cu.checked='$checked'";
if(!empty($caid)){
	$caids = cnsonids($caid,$catalogs);
	$wheresql .= " AND a.caid ".multi_str($caids);
}
$checkedarr = array('-1' => lang('nolimit'),'0' => lang('noadopt'),'1' => lang('adopted'));
$keyword && $wheresql .= " AND (cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";

echo form_str($action.'archivesedit',"?action=answers&page=$page");
tabheader_e();
echo "<tr><td class=\"item2\">";
echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
$checkedarr = array('-1' => lang('nolimit').lang('adopt'),'0' => lang('adopt'),'1' => lang('noadopt'));
echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
$caidsarr = array('0' => lang('catalog')) + caidsarr($catalogs);
echo "<select style=\"vertical-align: middle;\" name=\"caid\">".makeoption($caidsarr,$caid)."</select>&nbsp; ";
echo strbutton('bfilter','filter0').'</td></tr>';
tabfooter();

$pagetmp = $page;
do{
	$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject FROM {$tblprefix}answers cu LEFT JOIN {$tblprefix}archives a ON (a.aid=cu.aid) $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
	$pagetmp--;
} while(!$db->num_rows($query) && $pagetmp);

tabheader(lang('myanswerlist'),'','',10);
trcategory(array(lang('id'),lang('questiontitle'),lang('adopt'),lang('currency'),lang('answerdate'),lang('edit')));

$itemstr = '';
while($row = $db->fetch_array($query)){
	$idstr = $row['cid'];
	$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">$row[asubject]</a>";
	$currencystr = $row['currency'];
	$adddatestr = date('Y-m-d',$row['ucreatedate']);
	$checkstr = $row['checked'] ? 'Y' : '-';
	$editstr = "<a href=\"?action=answer&cid=$row[cid]\" onclick=\"return floatwin('open_answer',this)\">".lang('edit')."</a>";
	$itemstr .= "<tr><td class=\"item\" width=\"40\">$idstr</td>\n".
		"<td class=\"item2\">$subjectstr</td>\n".
		"<td class=\"item\">$checkstr</td>\n".
		"<td class=\"item\">$currencystr</td>\n".
		"<td class=\"item\">$adddatestr</td>\n".
		"<td class=\"item\">$editstr</td></tr>\n";
}
$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}answers cu LEFT JOIN {$tblprefix}archives a ON (a.aid=cu.aid) $wheresql");
$multi = multi($counts, $mrowpp, $page, "?action=answers$filterstr");

echo $itemstr;
tabfooter();
echo $multi;
?>