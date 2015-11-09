<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
load_cache('clangs,cotypes');
$langs = &$clangs;
$nsid = empty($nsid) ? 0 : max(-1,intval($nsid));
$coid = empty($coid) ? 0 : max(0,intval($coid));
$chid = empty($chid) ? 0 : max(0,intval($chid));
$mode = empty($mode) ? 0 : 1;
$ids = empty($select) ? array() : explode(',', $select);#已选项
empty($mode) && !empty($ids) && $ids = array($ids[0]);#单选
$limit = $mode ? 5 : 1;
if($coid){
	$sarr = read_cache('coclasses',$coid);
	$limit = $cotypes[$coid]['asmode'];
}elseif($nsid == -1){
	load_cache('acatalogs');
	$sarr = &$acatalogs;
}else $sarr = read_cache('catalogs','','',$nsid);
$limit = max(1,intval($limit));
$i=0;$catastr='';
foreach($sarr as $caid => $catalog){
	$enable = 1;
	if(($chid && !in_array($chid,!empty($catalog['chids']) ? explode(',',$catalog['chids']) : array()))) $enable = 0;
	if($catalog['level']){
		$catastr .= "<li class=\"cl$catalog[level]" . (!$enable ? ' nLower' : (in_array($caid, $ids) ? ' selecting' : '')) . "\">" . ($enable ? "<a href=\"javascript:\" rev=\"$caid\" title=\"$catalog[title]\">$catalog[title]</a>" : $catalog['title']) . "<span class=\"cGray\">" . ($catalog['level'] + 1) . '</span></li>';
	}else{
		$catastr .= '</ul></div>' . ($i && $i % 4 == 0 ? '<div class="blank"></div>' : '') . "<div class=\"cataarea\"><h1" . (!$enable ? ' class="nLower"' : (in_array($caid, $ids) ? ' class="h1selecting"' : '')) . ">" . ($enable ? "<a href=\"javascript:\"  rev=\"$caid\" title=\"$catalog[title]\">$catalog[title]</a>" : $catalog['title']) . "<span class=\"cGray\">" . ($catalog['level'] + 1) . '</span></h1><ul>';
		$i++;
	}
}
$catastr = substr($catastr,11) . '</ul></div>';
$buttonstr = $mode ? '<br style="clear:both" /><button id="btn_ok" onclick="setretval()" style="display:none">'.lang('confirm').'</button>' : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>" />
<title><?=lang('catachoose')?></title>
<link href="<?=$cms_abs?>images/common/slcarea/slcarea.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$cms_abs?>include/js/langs.js"></script>
<script type="text/javascript" src="<?=$cms_abs?>images/common/slcarea/slcarea.js"></script>
<script type="text/javascript">
var retval = [<?=join(', ', $ids)?>],res_limit = <?=$limit?>, p = window.floatwinParams, mode = <?=empty($mode) ? 0 : 1?>, field_id = '<?=str_replace("'","\\'",empty($field_id) ? '' : $field_id)?>', show_id = '<?=str_replace("'","\\'",empty($show_id) ? '' : $show_id)?>', win_id = '<?=str_replace("'","\\'",empty($win_id) ? '' : $win_id)?>',
	$WE = parent.$WE || opener.$WE || {elements:{}};
if(p){
	win_id = p.wid;
	field_id = p.fid;
	show_id = p.sid;
}
if(!$WE.elements[field_id]){
	loaderror();
}else{
	window.onload = initcata;
}
</script>
</head>
<body>
<div id="loading">Loading...</div>
<div id="content" style="display:none">
<?=$catastr?>
</div>
<?=$buttonstr?>
</body>
</html>