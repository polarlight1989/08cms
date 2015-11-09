<?php
define('NOROBOT', TRUE);
include_once dirname(__FILE__).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
if(empty($forward))$forward = empty($enable_pptout) || $pptout_file != 'phpwind' ? M_REFERER : $cms_abs;
$mode = empty($mode) ? '' : $mode;
$action = empty($action) ? 'login' : $action;
$getval = empty($enable_pptout) || $pptout_file != 'phpwind' ? 'forward' : 'jumpurl';
if($action == 'login'){
	$memberid && $mode != 'js' && message('dontrepeatlogin','','<a href="?action=logout">' . lang('logout') . '</a>');
	if($memberid && $enable_uc){
		require_once M_ROOT.'./include/ucenter/config.inc.php';
		require_once M_ROOT.'./uc_client/client.php';
		$ucresult = uc_get_user($curuser->info['mname']);
		if(is_array($ucresult)){
			list($uid,$username) = uc_get_user($curuser->info['mname']);
			$uc_synlogin = uc_user_synlogin($uid);
			echo $ucsynlogin;
		}
	}
	if(!submitcheck('cmslogin')){
		$temparr = array('forward' => rawurlencode($forward));
		if($mode == 'js'){
			js_write(template(empty($curuser->info['mid']) ? 'jslogin' : 'jsloginok',$temparr));
			mexit();
		}else{
			if($enable_pptin && !empty($pptin_url) && $pptin_login){
				$url = $pptin_url.$pptin_login;
				$url .= (strpos($url,'?') ? '&' : '?')."$getval=".rawurlencode($forward);
				header('location:'.$url);
				exit;
			}
			if(empty($_sys) && $html = template('login',$temparr)){
				mexit($html);
			}else{
				include_once M_ROOT."./include/cheader.inc.php";
				_header(lang('memberlogin'));
				$cookiedef = '1m';
				$cookiearr = array('0' => lang('inbrowser'), '1h' => '1'.lang('hours'), '1d' => '1'.lang('days'), '1w' => '1'.lang('weeks'), '1m' => '1'.lang('month'), '-1' => lang('saveforever'));
				echo '<form name="cmslogin" id="cmslogin" method="post" action="?forward='.rawurlencode($forward).($infloat?"&infloat=$infloat&handlekey=$handlekey":'').'" onsubmit="return checklogin(this)">';
				tabheader_e();
				echo '<tr class="header"><td colspan="2"><b>'.lang('memberlogin').'&nbsp; &nbsp; >><a href="tools/lostpwd.php"'.(empty($infloat)?'':" onclick=\"return floatwin('open_$handlekey',this)\"").'>'.lang('getpwd').'</a></b></td></tr>';
				trbasic(lang('membercname'),'username');
				trbasic(lang('loginpwd'),'password','','password');
				tr_regcode('login');
				trbasic('Cookie','expires',makeoption($cookiearr, $cookiedef),'select');
				trhidden('client_t','');
				$infloat && trhidden('infloat',1);
				tabfooter('cmslogin',lang('login'));
				mexit('</div></body></html>');
			}
		}
	}else{
		switch(empty($expires) ? '0' : strtolower($expires)){
		case '-1':
			$expires = 3650 * 86400;
			break;
		case '1m':
			$expires = 30 * 86400;
			break;
		case '1w':
			$expires = 7 * 86400;
			break;
		case '1d':
			$expires = 86400;
			break;
		case '1h':
			$expires = 3600;
		default:
			$expires = 0;
			break;
		}
		$expires && !empty($client_t) && $expires = intval(floatval($client_t) / 1000) - $timestamp + $expires;
		$expires < 0 && $expires = 0;
		if($enable_pptin && !empty($pptin_url) && $pptin_login){
			$url = $pptin_url.$pptin_login;
			$url .= (strpos($url,'?') ? '&' : '?')."$getval=".rawurlencode($forward);
			header('location:'.$url);
			exit;
		}
		include_once M_ROOT."./include/admin.fun.php";
		$username = trim($username);
		regcode_pass('login',empty($regcode) ? '' : trim($regcode)) || message('safecodeerr',axaction(1,$forward));
		strlen($username) < 3 && message('membercnameillegal',axaction(1,$forward));
		if(!$password || $password != addslashes($password)) message('pwdillegal',axaction(1,$forward));
		$guestexp = '\xA1\xA1|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		preg_match("/^\s*$|^c:\\con\\con$|[%,\*\"\s\t\<\>\&]|$guestexp/is", $username) && message('membercnameillegal',axaction(1,$forward));
		$errtimes = login_safecheck($username);
		$errtimes++ < $maxerrtimes || message('mloginerrtimes');
		$cantimes = $maxerrtimes - $errtimes;
		$md5_password = md5(md5($password));
		$enable_uc && include_once M_ROOT.'./include/ucenter/uc.inc.php';
		$curuser->activeuserbyname($username);
		if($curuser->info['mid'] && ($enable_uc || $curuser->info['password'] == $md5_password)){//是本站会员，检查更新密码
			if($curuser->info['password'] != $md5_password)$curuser->updatefield('password', $md5_password);
			if($curuser->info['checked'] == 1){
				$curuser->updatefield('lastvisit', $timestamp);
				$curuser->updatefield('lastip', $onlineip);
				$curuser->updatedb();
				$memberid = $curuser->info['mid'];
				msetcookie('userauth', authcode("$md5_password\t".$curuser->info['mid'],'ENCODE'),$expires);
				if($enable_pptout && !empty($pptout_file) && !empty($pptout_url)){
					include M_ROOT.'./include/pptout/'.$pptout_file.'.php';
					header('location:'.$url);
					exit;
				}
				login_safecheck($username, 0, 1);
				if(!$forward || preg_match('/\b(?:login|register).php(\?|#|$)/i', $forward))$forward = 'adminm.php';
				message('loginsucceed',axaction(2,$forward));
			}elseif($curuser->info['checked'] == 2){//需要重新激活的会员
				message('outmemberactive',axaction(0,'tools/memactive.php?ppt=1&username='.rawurlencode($username).'&password='.rawurlencode($password).'&forward='.rawurlencode($forward)));
			}else message('nocheckmember',axaction(1,$forward));
		}elseif($enable_uc){//UC帐号需要激活
			message('outmemberactive',axaction(0,'tools/memactive.php?username='.rawurlencode($username).'&password='.rawurlencode($password).'&forward='.rawurlencode($forward)));
		}
		login_safecheck($username, $errtimes);
		$password = preg_replace("/^(.{".round(strlen($password) / 4)."})(.+?)(.{".round(strlen($password) / 6)."})$/s", "\\1***\\3", $password);
		$record = mhtmlspecialchars(
			$timestamp."\t".
			stripslashes($username)."\t".
			$password."\t".
			$onlineip);
		record2file('badlogin',$record);
		message($cantimes ? 'loginfailed' : 'mloginerrtimes', axaction(1, $forward), $cantimes);
	}
}elseif($action == 'logout'){
	if($enable_uc){
		include_once M_ROOT.'./include/ucenter/uc.inc.php';
	}
	$cmember = $curuser->info;
	if($enable_pptin && !empty($pptin_url) && $pptin_logout){
		$url = $pptin_url.$pptin_logout;
		$url .= (strpos($url,'?') ? '&' : '?').'verify='.substr(md5("$onlineip$pptin_key$_SERVER[HTTP_USER_AGENT]"),8,8)."&$getval=".rawurlencode($forward);#PHPWind
		header('location:'.$url);
		exit;
	}
	mclearcookie();
	if($enable_pptout && !empty($pptout_file) && !empty($pptout_url)){
		include M_ROOT.'./include/pptout/'.$pptout_file.'.php';
		header('location:'.$url);
		exit;
	}
	if(!$forward || preg_match('/\blogin.php(\?|#|$)/i', $forward))$forward = 'index.php';
	message('memlogoutsucce',$forward);
}
?>
