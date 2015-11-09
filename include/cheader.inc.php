<?php
include_once M_ROOT."./include/admin.fun.php";
include_once M_ROOT."./include/adminm.fun.php";
load_cache('mlangs,mmsgs');
$no_mcfooter = 1;
$message_class = 'msgbox';
$langs = &$mlangs;

function _footer(){
	echo <<<EOT
<div class="blank9"></div>
</div>
</body>
</html>
EOT;
}

function _header($title = '', $class = 'main_area'){
	global $hostname,$mcharset,$cmsname,$mallowfloatwin,$mfloatwinwidth,$mfloatwinheight,$cms_abs;
	$title || $title = $hostname;
	echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$mcharset" />
<title>$title - $cmsname</title>
<link href="{$cms_abs}images/adminm/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var CMS_ABS='$cms_abs',IXDIR = 'images/admina/', charset = '$mcharset',allowfloatwin = '$mallowfloatwin',floatwidth='$mfloatwinwidth',floatheight='$mfloatwinheight'</script>
<script type="text/javascript" src="{$cms_abs}include/js/langs.js"></script>
<script type="text/javascript" src="{$cms_abs}include/js/common.js"></script>
<script type="text/javascript" src="{$cms_abs}include/js/floatwin.js"></script>
<script type="text/javascript" src="{$cms_abs}include/js/calendar.js"></script>
<script type="text/javascript" src="{$cms_abs}include/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="{$cms_abs}include/js/tree.js"></script>
<script type="text/javascript" src="{$cms_abs}include/js/listbox.js"></script>
</head>
<body>
<div id="append_parent"></div>
<div class="$class">
EOT;
}
?>
