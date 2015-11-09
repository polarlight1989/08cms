<?
!defined('M_COM') && exit('No Permission');
include_once M_ROOT.'./include/parse.fun.php';

load_cache('clangs,cmsgs');
$langs = &$clangs;
function message($key,$url = ''){
	global $cmsgs,$msgforwordtime,$mcharset,$sptpls,$inajax,$infloat,$handlekey;
	$dtime = $msgforwordtime ? $msgforwordtime : 1250;
	$str = @$cmsgs[$key] ? $cmsgs[$key] : $key;
	if(($num = func_num_args())>2){
		$ars = func_get_args();
		array_splice($ars, 1, 1);
		$ars[0] = &$str;
		$str = call_user_func_array('sprintf',$ars);
	}
	$str .= '<br><br><br>';
	if($url) {
		if($infloat){
			$str .= '<script type="text/javascript" src="include/js/floatwin.js"></script>';
			if(preg_match('/^javascript:/',$url)){
				$str .= "<script type=\"text/javascript\" reload=\"1\">var t = $dtime;".substr($url,11)."</script>";
			}else{
				$str .= "<br><br><br><a href=\"$url\" onclick=\"return floatwin('update_$handlekey', this);\">".lang('rightnowgoback')."</a><script type=\"text/javascript\" reload=\"1\">floatOut=setDelay(\"floatwin('update_$handlekey', '$url');\",$dtime);</script>";
			}
		}elseif(strpos($str,lang('goback')) === false){
			$str .= "<a href=\"$url\">[".lang('rightnowjump')."]</a><script>setTimeout(\"window.location.replace('$url');\", ".$dtime.");</script>&nbsp; ";
		}else $str .= "<a href=\"javascript:history.go(-1);\" class=\"mediumtxt\">[ ".lang('rightnowgoback')."]</a>&nbsp; ";
	}
	$str .= "<a href=\"javascript:\" onclick=\"return top.floatwin?top.floatwin('close_$handlekey'):window.close()\">[".lang('closewindow').']</a>';
	$str = "<br><br>$str<br><br>";
	$inajax && ajax_info('<table width="98%" border="0" cellpadding="0" cellspacing="0"><tr><td align="center">'.$str.'</td></tr></table>');
	$temparr['message'] = $str;
	if(!empty($sptpls['message'])){
		mexit(template('message',$temparr));
	}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>"></head><body>
<table width="98%" border="0" cellpadding="0" cellspacing="0"><tr><td align="center"><?=$str?></td></tr></table>
</body></html>
<?	
		mexit();
	}
}
function if_siteclosed($sid){
	global $cmsclosed,$subsites,$cmsclosedreason;
	$closed = empty($sid) ? $cmsclosed : $subsites[$sid]['closed'];
	if($closed) message(empty($cmsclosedreason) ? lang('defaultclosedreason') : mnl2br($cmsclosedreason));
}
function ajax_info($str){
	global $mcharset,$callback;
	if($callback){
		ob_clean();
		mexit("js_callback(" . jsonEncode($str) . ",'$callback')");
	}
	@header("Expires: -1");
	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	header("Content-type: application/xml");
	echo "<?xml version=\"1.0\" encoding=\"$mcharset\"?>\n<root><![CDATA[";
	echo $str;
	echo ']]></root>';
	die();
}
function cumessage($msg = '',$forward=''){//修改为这样
	global $inajax,$cmsgs,$callback;
	if(!$callback && empty($inajax)){
		message($msg,!$forward ? M_REFERER : $forward);
	}else{
		$str = @$cmsgs[$msg] && $msg != 'succeed' ? $cmsgs[$msg] : $msg;
		if(($num = func_num_args())>2){
			$ars = func_get_args();
			array_splice($ars, 1, 1);
			$ars[0] = &$str;
			$str = call_user_func_array('sprintf',$ars);
		}
		ajax_info($str);
	}
}

function template($spname='',$_da=array()){
	global $sptpls,$templatedir,$btags,$mconfigs;
	if(!($tplname = @$sptpls[$spname]) || !($template = load_tpl($tplname))) return '';
	extract($mconfigs,EXTR_SKIP);
	extract($btags);
	_aenter($_da,1);
	extract($_da,EXTR_OVERWRITE);
	tpl_refresh($tplname);
	@include M_ROOT."template/$templatedir/pcache/$tplname.php";
	$_content = ob_get_contents();
	ob_clean();
	return $_content;
}
function cms_spread($uid, $mode = 0){
	global $db, $tblprefix, $onlineip, $timestamp, $curuser, $m_cookie;
	if(empty($uid) && $mode == 1)
		$uid = empty($m_cookie['spread_uid']) ? '' : $m_cookie['spread_uid'];
	else
		$uid = trim($uid);
	if(empty($uid) || !($commu = read_cache('commu',9)) || empty($commu['available']) || empty($commu['setting'][$mode]))return;
	$user = new cls_userinfo;
	$user->activeuserbyname($uid);
	if(!($mid = $user->info['mid']) || $mid == $curuser->info['mid'])return;
	$s = &$commu['setting'][$mode];
	$ip = ip2long($onlineip);
	$time = getdate($timestamp);
	$time = mktime(0,0,0,$time['mon'],$time['mday'],$time['year']);
	$record = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}spreads WHERE mid='$mid' AND ip=$ip AND mode=$mode AND time>$time");
	if($s['value'] && (!$s['count'] || $s['count'] > $db->result_one("SELECT COUNT(*) FROM {$tblprefix}spreads WHERE mid='$mid' AND time>$time")) && !$record)
		$user->updatecrids(array($s['crid'] => $s['value']), 1);
	$record || $db->query("INSERT INTO {$tblprefix}spreads(mid,ip,mode,time) VALUES('$mid',$ip,$mode,$timestamp)");
	$mode ? mclearcookie('spread_uid') : msetcookie('spread_uid', $uid);
}
function jsonEncode($var, $is_a = 0, $idx = 0){
	if(is_string($var)){
		return "'" . addcslashes($var, "\\\r\n'") . "'";
	}elseif(is_numeric($var)){
		return $var;
	}elseif(is_bool($var)){
		return $var ? 'true' : 'false';
	}elseif(is_null($var)){
		return 'null';
	}else{
		is_object($var) && $var = get_object_vars($var);
		if(is_array($var)){
			$keys = array_keys($var);
			$val = implode('', $keys);
			if(!$is_a && (!$val || is_numeric($val))){
				$let = '';
				if($idx){
					for($k = 0, $v = max($keys); $k < $v; $k++)$let .= ',' . (isset($var[$k]) ? jsonEncode($var[$k]) : '');
				}else{
					foreach($keys as $k)$let .= ',' . jsonEncode($var[$k]);
				}
				return '[' . substr($let, 1) . ']';
			}else{
				$let = '';
				foreach($var as $k => $v)$let .= ',' . jsonEncode($k) . ':' . jsonEncode($v, $is_a);
				return '{' . substr($let, 1) . '}';
			}
		}
	}
	return '"unknow"';
}
function js_callback($var = 'succeed'){
	global $callback;
	if($callback){
		ob_clean();
		mexit("js_callback(" . jsonEncode($var) . ",'$callback')");
	}
}

?>
