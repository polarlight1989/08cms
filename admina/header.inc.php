<?php
@header('Content-Type: text/html; charset='.$mcharset);
$menu = empty($menu) ? ($sid ? 39 : 1) : $menu;
$headerstr = mimplode(array_keys($a_mheaders));
$itemstr = '';
$i = 0;
foreach($a_mheaders as $k => $v){
	$itemstr .= "<li><a href=\"#\" onclick=\"highlight($i); togglemenu('$k'); parent.main.location='$v$param_suffix';return false;\"><span>".lang('menutype_'.$k)."</span></a></li>\n";
	$i ++;
}
$logotxt = "<a href=\"http://www.08cms.com\" target=\"_blank\">08cms &nbsp;v$cms_version</a>&nbsp;&nbsp;".(!$sid ? lang('msite_backarea') : $subsites[$sid]['sitename']);
$sidsarr = array(0 => lang('msite')) + sidsarr(1);
$sitetxt = '';
foreach($sidsarr as $k => $v){
	$sitetxt .= $sid == $k ? "&nbsp; <b>$v</b>&nbsp; |" : "&nbsp; <a href=\"?sid=$k\" target=\"_blank\">$v</a>&nbsp; |";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" id="css" href="./images/admina/admina.css">
<script type="text/javascript">
var menus = new Array(<?=$headerstr?>);
function togglemenu(id) {
	if(parent.menu){
		for(k in menus){
			if(parent.menu.document.getElementById('mheader_' + menus[k])){
				parent.menu.document.getElementById('mheader_' + menus[k]).style.display = menus[k] == id ? 'block' : 'none';
			}
		}
	}
}
function findtags(parentobj, tag) {
	if(typeof parentobj.getElementsByTagName != 'undefined'){
		return parentobj.getElementsByTagName(tag);
	}else if(parentobj.all && parentobj.all.tags) {
		return parentobj.all.tags(tag);
	}else {
		return null;
	}
}

function highlight(n) {
	var lis = findtags(document, 'li');
	for(var i = 0; i < lis.length; i++){
		lis[i].id = '';
	}
	lis[n].id = 'headon';
}
togglemenu('<?=$menu?>');

</script>
</head>
<script src="include/js/iframe.js" type="text/javascript"></script>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="headmenubg">
<tr>
<td width="200px">
<div class="headtext"><?=$logotxt?></div>
</td><td>
<div class="headmenu"><ul><?=$itemstr?></ul></div>
</td></tr>
<tr><td colspan="2" height="25" class="headbottom" align="right"><?=$sitetxt?></td></tr>

</table>
</body>
</html>
