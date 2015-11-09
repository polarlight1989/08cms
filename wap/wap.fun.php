<?php
defined('WAP_MODE') || exit();
error_reporting(0);
include_once M_ROOT.'./include/parse.fun.php';
load_cache('clangs,cmsgs');
$langs = &$clangs;


$wap_suffix = "&z=" . rawurlencode($z ? ($z{0} != '_' ? $z : "{$curuser->info['msid']}$z") : $curuser->info['msid']);
function wap_header($title = '', $id = 'WML_08CMS', $cache = 180){
	global $cmsname, $hostname;
	static $done = 0;if($done)return;$done = 1;//防止重复调用
	header('Content-type: text/vnd.wap.wml; charset=utf-8');
	echo '<?xml version="1.0" encoding="UTF-8"?>'
		.'<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.3//EN" "http://www.wapforum.org/DTD/wml13.dtd">'
		.'<wml><head>'
		.'<meta http-equiv="cache-control" content="max-age='.$cache.',private" />'
		.'</head>'
		.'<card id="' . $id . '" title="' . wap_encode("$title - $cmsname - $hostname") . '">';
}

function wap_footer(){
	global $mcharset, $wap_charset, $gzipenable;
	echo '<br/>'.date('m-d H:i').'<br/><small>Powered by 08CMS</small></card></wml>';
	if($mcharset != 'utf-8'){
		include_once(M_ROOT.'include/chinese.cls.php');
		$str = ob_get_contents();
		ob_end_clean();
		$gzipenable && function_exists('ob_gzhandler') && ob_start('ob_gzhandler');
		$iconv=new Chinese();
		echo $iconv->Convert($mcharset == 'big5' ? 'BIG5' : 'GB2312', $wap_charset ? 'UTF8' : 'UNICODE', $str);
	}
	exit;
}

function wap_layout($id, $title = ''){
	echo '<br/>'.date('m-d H:i').'<br/><small>Powered by 08CMS</small></card>'
		.'<card id="' . $id . '" title="' . wap_encode($title) . '">';
}

function wap_encode($string){
	return str_replace(array('&nbsp;', '&', '"', '<', '>'), array(' ', '&amp;', '&quot;', '&lt;', '&gt;'), strip_tags($string, '<br><a><p><img>'));
}
function html2wml($string){
	static $re;
	$re || $re = create_function('$tag, $a', 'switch($tag=strtolower($tag)){case\'img\':return preg_replace("/.+?\bsrc\s*=\s*&quot;(.+?)&quot;.*?&gt;/","<a href=\"$1\">'.lang('img').'</a>",$a);case\'a\':return str_replace(array(\'&amp;\',\'&quot;\'),array(\'&\',\'"\'),\'<\'.substr($a,4,-4).\'>\');case\'/a\':return\'</a>\';default:return"<br/>";}');

	$string = str_replace(array('&nbsp;', '&', '"', '<', '>'), array(' ', '&amp;', '&quot;', '&lt;', '&gt;'), strip_tags($string, '<br><a><p><img>'));
	return preg_replace('/&lt;(\/?\w+).*?&gt;/e', '$re("$1","$0")', $string);
}

function wap_lang($str){
	return str_replace(array("\r\n", "\r", "\n"), '<br/>', wap_encode(lang($str)));
}

function if_siteclosed($sid){
	global $cmsclosed,$subsites,$cmsclosedreason,$wap_status;
	$closed = empty($sid) ? $cmsclosed : $subsites[$sid]['closed'];
	$closed && message(empty($cmsclosedreason) ? wap_lang('wap_defaultclosedreason') : mnl2br($cmsclosedreason));
	$wap_status || message('wap_close');
}

function message($key, $url = ''){
	global $link, $cmsgs;
	wap_header(wap_lang('wap_infotip'));
	$str = $cmsgs[$key] ? $cmsgs[$key] : $key;
	if(($num = func_num_args())>2){
		$ars = func_get_args();
		array_splice($ars, 1, 1);
		$ars[0] = &$str;
		$str = call_user_func_array('sprintf',$ars);
	}
	echo str_replace(array("\r\n", "\r", "\n"), '<br/>', wap_encode($str)).'<br/>';
	if($url){
		switch($url){
		case 'back':
			echo '<onevent type="ontimer"><prev/><timer value="20"/></onevent>'
#				.'<anchor>'.wap_lang($btn ? $btn : 'prev_page').'<prev/></anchor><br/>';
				.'<anchor>'.wap_lang('wap_rightnowgoback').'<prev/></anchor><br/>';
			break;
		default:
			echo '<onevent type="ontimer"><go href="'.wap_encode($url).'"/><timer value="20"/></onevent>'
#				.'<a href="'.wap_encode($url).'">'.wap_lang($btn ? $btn : 'looked_and_next').'</a><br/>';
				.'<a href="'.wap_encode($url).'">'.wap_lang('wap_rightnowgoback').'</a><br/>';
		}
	}
	echo $link;
	wap_footer();
}

function wap_exit($str = ''){
	global $gzipenable, $mcharset, $wap_charset;
	$content = ob_get_contents();
	ob_end_clean();
	$gzipenable ? ob_start('ob_gzhandler') : ob_start();
	header('Content-type: text/vnd.wap.wml; charset=utf-8');
	echo $content.$str;
	if($mcharset != 'utf-8'){
		include_once(M_ROOT.'include/chinese.cls.php');
		$str = ob_get_contents();
		ob_end_clean();
		$gzipenable && function_exists('ob_gzhandler') && ob_start('ob_gzhandler');
		$iconv=new Chinese();
		echo str_replace('&#x;', '?', $iconv->Convert($mcharset == 'big5' ? 'BIG5' : 'GB2312', $wap_charset ? 'UTF8' : 'UNICODE', $str));
	}
	exit;
}

function wap_strip($string){
	return str_replace(array('&nbsp;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&middot;', '&hellip;'), array(' ', "'", '“', '”', '—', '·', '…'), strip_tags($string,'<br><p><img>'));
}
?>