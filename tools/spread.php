<?php if(empty($_GET['uid'])){?>var s = document.getElementsByTagName('SCRIPT'), x = document.createElement('SCRIPT'), u = location.href.match(/\buid=[^&]*/i);if(u){s = s[s.length -1].src;x.type = 'text/javascript';x.src = s + (s.indexOf('?') < 0 ? '?' : '&') + u[0] + '&url=' + encodeURIComponent(location.href) + '&referrer=' + encodeURIComponent(document.referrer);document.getElementsByTagName('HEAD')[0].appendChild(x);}
<?php }else{
	include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
	include_once M_ROOT.'/include/common.fun.php';
	cms_spread(stripslashes($uid));
	echo "'<script type=\"text/javascript\">location.href='$cms_abs';</script>'";
}?>