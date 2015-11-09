<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/admin.fun.php';
include_once M_ROOT.'./include/archive.fun.php';
load_cache('acatalogs,channels,alangs');
$langs = &$alangs;
$mode = 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=lang('albumchoose')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>" />
<link href="<?=$cms_abs?>images/common/slcarea/slcarea.css" rel="stylesheet" type="text/css" />
<link href="<?=$cms_abs?>images/admina/contentsAdmin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$cms_abs?>include/js/langs.js"></script>
<script type="text/javascript" src="<?=$cms_abs?>images/common/slcarea/slcarea.js"></script>
<script type="text/javascript">
var retval,res_limit = <?=empty($limit) ? 5 : $limit?>, p = window.floatwinParams, mode = <?=empty($mode) ? 0 : 1?>, field_id = '<?=str_replace("'","\\'",empty($field_id) ? '' : $field_id)?>', show_id = '<?=str_replace("'","\\'",empty($show_id) ? '' : $show_id)?>', win_id = '<?=str_replace("'","\\'",empty($win_id) ? '' : $win_id)?>',
	$WE = parent.$WE || opener.$WE || {elements:{}};
if(p){
	win_id = p.wid;
	field_id = p.fid;
	show_id = p.sid;
}
if(!$WE.elements[field_id]){
	loaderror();
}else{
	window.onload = initalbum;
}
</script>
</head>
<body style="overflow-x:hidden; background:#f7f7f7;">
<div id="loading">Loading...</div>
<div id="content" style="display:none">
<div class="blank9"></div>
<!--<div class="conlist1"><?=lang('selectedalbum')?></div>-->
<div id="selectedalbum" class="selectitem"></div>
<?php
$page = !empty($page) ? max(1, intval($page)) : 1;
submitcheck('bfilter') && $page = 1;
$chid = empty($chid) ? 0 : max(0,intval($chid));
$nchid = empty($nchid) ? 0 : max(0,intval($nchid));//用来筛选
$isopen = empty($isopen) ? 0 : 1;
$keyword = empty($keyword) ? '' : $keyword;

$nchids = array();//当前页允许的所有模型
foreach($channels as $k => $v) if(($v = read_cache('channel',$k)) && ($v['oneuser'] != $isopen) && !$v['onlyload'] && in_array($chid,explode(',',$v['inchids']))) $nchids[] = $k;
if(!$nchids) $no_list = 1;

$filterstr = '';
foreach(array('chid','nchid','isopen','keyword','field_id','show_id','win_id',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));

$wheresql = "WHERE checked=1 AND abover=0";
$fromsql = "FROM {$tblprefix}archives";

if($nchid){
	if(!in_array($nchid,$nchids)) $no_list = 1;
	else $wheresql .= " AND chid=$nchid";
}else $wheresql .= " AND chid ".multi_str($nchids);

if(!$isopen){
	if($memberid) $wheresql .= " AND mid='$memberid'";
	else $no_list = 1;
}
$keyword && $wheresql .= " AND (mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
$wheresql = empty($no_list) ? $wheresql : 'WHERE 1=0';

echo form_str('choosealbum',"?win_id=$win_id&field_id=$field_id&show_id=$show_id&page=$page");
tabheader_e();
echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
$chidsarr = array('0' => lang('all_channel'));
foreach($channels as $k => $v) if(in_array($k,$nchids)) $chidsarr[$k] = $v['cname'];
echo "<select style=\"vertical-align: middle;\" name=\"nchid\">".makeoption($chidsarr,$nchid)."</select>&nbsp; ";
//某些固定页面参数
trhidden('chid',$chid);
trhidden('isopen',$isopen);
echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
echo "</td></tr></table>";

tabheader(lang('album_list'),'','',9);
$cy_arr = array(lang('choose'),lang('title'),lang('member'),lang('catalog'),lang('channel'));
trcategory($cy_arr);
$pagetmp = $page;
$atpp = 10;
do{
	$query = $db->query("SELECT * $fromsql $wheresql ORDER BY aid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
	$pagetmp--;
} while(!$db->num_rows($query) && $pagetmp);

$itemstr = '';
while($row = $db->fetch_array($query)){
	$channel = read_cache('channel',$row['chid']);
	$itemstr .= "<tr class=\"txt\"><td class=\"txtC w50\" ><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\"></td>";
	$itemstr .= "<td class=\"txtL\"><a href=\"".view_arcurl($row)."\" target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a></td>\n";
	$itemstr .= "<td class=\"txtC\">$row[mname]</td>\n";
	$itemstr .= "<td class=\"txtC\">".@$acatalogs[$row['caid']]['title']."</td>\n";
	$itemstr .= "<td class=\"txtC\">".@$channel['cname']."</td>\n";
	$itemstr .= "</tr>\n";
}

$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
$multi = multi($counts, $atpp, $page, "?win_id=$win_id&field_id=$field_id&show_id=$show_id$filterstr");
echo $itemstr;
tabfooter();
echo $multi;
echo '</form>'
?>

</div>
<button id="btn_ok" onclick="setretval()" class="btn" style="display:none"><?=lang('confirm')?></button>
<div class="blank9"></div>
</body>
</html>
