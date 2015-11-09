<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>08CMS Admin Control</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>">
<link rel="stylesheet" type="text/css" id="css" href="./images/admina/admina.css">
<script src="include/js/iframe.js" type="text/javascript"></script>
<script type="text/javascript">
function $(id) {
	return document.getElementById(id);
}
function change_menu(menuid) {
	if($('dvShow' + menuid).style.display == 'none') {
		$('dv' + menuid).className = 'left2_on';
		$('dvShow' + menuid).style.display = 'block';
	} else {
		$('dv' + menuid).className = 'left2_out';
		$('dvShow' + menuid).style.display = 'none';
	}
}
</script>
</head>

<body style="margin:4px;">
<?
$menuid = 0;
$menu = empty($menu) ? ($sid ? 39 : 1) : $menu;
if($curuser->info['isfounder']){
	$a_usergroup = lang('founder');
}else{
	$a_usergroups = read_cache('usergroups',2);
	$a_usergroup = $a_usergroups[$curuser->info['grouptype2']]['cname'];
	unset($a_usergroups);
}
echo '<div class="left2">'.
	"<a href=\"###\" onclick=\"change_menu('".$menuid."')\"><div class=\"left2_out\" id=\"dv$menuid\" style=\"cursor:pointer;\"><b>".$a_usergroup.lang('backarea')."</b></div></a>".
	"<div class=\"left2_break\" style=\"display:none;\" id=\"dvShow$menuid\">\n".
	"<div class=\"left2_break_2\">".lang('member').'&nbsp; '.$curuser->info['mname']."</div>\n".
	"<div class=\"left2_break_2\"><a href=\"".view_siteurl($sid)."\" target=\"_blank\">".lang($sid ? 'subsiteindex' : 'websiteindex')."</a>&nbsp; &nbsp;<a href=\"adminm.php\" target=\"_blank\">".lang('membercenter1')."</a></div>\n".
	"<div class=\"left2_break_2\"><a href=\"#\"  onClick=\"parent.menu.location='?entry=menu$param_suffix'; parent.main.location='?entry=home$param_suffix';return false;\">".lang('adminindex')."</a>&nbsp; &nbsp;<a href=\"?entry=logout\" target=\"_top\">".lang('logoutadmin')."</a></div>\n".
	'</div></div>';
$menuid ++;
foreach($a_menus as $k =>$v) {
	echo '<div class="left2" id="mheader_'.$k.'" style="display:'.($k == $menu ? 'block' : 'none').';">';
	showamenus ($k);
	echo '</div>';
}
function showamenus($headermenu='1') {
	global $a_menus,$menuid,$param_suffix;
	foreach($a_menus[$headermenu] as $k0 => $v0){
		$menuid++;
		echo "<a href=\"###\" onclick=\"change_menu('".$menuid."')\"><div class=\"left2_on\" id=\"dv$menuid\" style=\"cursor:pointer;\"><b>".lang('menutype_'.$k0)."</b></div></a>".
			"<div class=\"left2_break\" style=\"display:block;\" id=\"dvShow$menuid\">\n";
		foreach($v0 as $k1 => $v1) {
			echo "<div class=\"left2_break_2\" id=\"$k1\"><a href=\"$v1$param_suffix\" target=\"main\">".lang('menuitem_'.$k1)."</a></div>\n";
		}
		echo "</div>";
	}
}
?>

</body>
</html>