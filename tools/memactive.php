<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'include/cheader.inc.php';
$forward = empty($forward) ? M_REFERER : $forward;
empty($action) && $action=0;
empty($mid) && $mid=0;
empty($id) && $id=0;
if($action == 'emailactive' && $mid && $id){
	_header();
	$cmember = $db->fetch_one("SELECT m.mid,s.confirmstr FROM {$tblprefix}members m,{$tblprefix}members_sub s WHERE m.mid='$mid' AND s.mid=m.mid AND m.checked=0");
	if(!$cmember || !$cmember['confirmstr']) mcmessage('invalidoperate');
	list($dateline,$deal,$confirmid) = explode("\t",$cmember['confirmstr']);
	if($deal == 2 && $confirmid == $id){
		$db->query("UPDATE {$tblprefix}members SET checked=1 WHERE mid='$mid'");
		$db->query("UPDATE {$tblprefix}members_sub SET confirmstr='' WHERE mid='$mid'");
		mcmessage('memactivesucceed');
	}else mcmessage('invalidoperate');
}elseif($action == 'memcert'){
	load_cache('memcerts');
	_header();
	(empty($crid) || empty($confirm) || !($record = $db->fetch_one("SELECT mcid,certdata FROM {$tblprefix}mcrecords WHERE crid='$crid' AND checktime=0 LIMIT 0,1"))) && mcmessage('memcert_link_bad');
	$certdata = unserialize($record['certdata']);
	if(!($k = $memcerts[0][$record['mcid']]['email']) || $certdata['codes'][$k]['v'] != $confirm || $certdata['codes'][$k]['e'] >= 3){
		$k && $certdata['codes'][$k]['e']++ < 3 && $db->query("UPDATE {$tblprefix}mcrecords SET certdata='".addslashes(serialize($certdata))."' WHERE crid=$crid");
		mcmessage($k && $certdata['codes'][$k]['e'] >= 3 ? 'memcert_link_more' : 'memcert_link_bad');
	}else{
		if(empty($certdata['flags'][$k])){
			$certdata['flags'][$k] = 1;
			$db->query("UPDATE {$tblprefix}mcrecords SET certdata='".addslashes(serialize($certdata))."' WHERE crid=$crid");
		}
		mcmessage('memcert_link_ok');
	}
}
_header(lang('activeoutsitemember'),'curbox');
load_cache('mchannels');
//最好是将资料带入，但是还是可以修改的性质，跟登录差不多了。
$ppt = empty($ppt) ? 0 : 1;
if(!$ppt && !$enable_uc)  mcmessage('ucenterdisabled',$forward);
if(!submitcheck('bmemactive')){
	tabheader(lang('activeoutsitemember'),'memberpwd','memactive.php'.($ppt ? '?ppt=1' : ''),2,0,1);
	trbasic(lang('membercname'),'username',$username);
	trbasic(lang('memberpwd'),'password',$password,'password');
	trbasic(lang('memberchannel'),'mchid',makeoption(mchidsarr()),'select');
	echo "<input type=\"hidden\" name=\"forward\" value=\"$forward\">";
	$submitstr = '';
	$submitstr .= makesubmitstr('username',1,0,0,15);
	$submitstr .= makesubmitstr('password',1,0,0,15);
	$submitstr .= tr_regcode('login');
	tabfooter('bmemactive');
	check_submit_func($submitstr);
}else{
	if(!($mchid = max(0,intval($mchid))) || !($mchannel = $mchannels[$mchid])) mcmessage('choosememchal');
	if(!regcode_pass('login',empty($regcode) ? '' : trim($regcode))) mcmessage('regcodeerror',$forward);
	$username = trim($username);
	if(strlen($username) < 3) mcmessage('membercnameillegal',$forward);
	$guestexp = '\xA1\xA1|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
	if(preg_match("/^\s*$|^c:\\con\\con$|[%,\*\"\s\t\<\>\&]|$guestexp/is", $username)) {
		mcmessage('membercnameillegal',$forward);
	}
	if(!$password || $password != addslashes($password)) mcmessage('memberpwdillegal',$forward);
	$autocheck = $mchannel['autocheck'];
	if($ppt){
		if($mid = $db->result("SELECT mid FROM {$tblprefix}members WHERE mname='$username' AND password='".md5(md5($password))."' AND checked='2'")){
			$comstr = "mtcid=1,mchid='$mchid'";
			$comstr .= $autocheck == 1 ? "checked=1" : "checked=0";
			foreach($currencys as $crid => $currency){
				($currency['available'] && $currency['initial']) && $comstr .= ",currency".$crid."='".$currency['initial']."'";
			}
			$db->query("UPDATE {$tblprefix}members SET $comstr WHERE mid='$mid'");
			if($autocheck == 2){
				$confirmid = random(6);
				$confirmstr = "$timestamp\t2\t$confirmid";
				$db->query("UPDATE {$tblprefix}members_sub SET confirmstr='$confirmstr' WHERE mid='$mid'");
			}
			$db->query("INSERT INTO {$tblprefix}members_$chid SET mid='$mid'");//将模型表记录加上
			if($autocheck == 1){
				msetcookie('userauth',authcode(md5(md5($password))."\t$mid",'ENCODE'));
//				msetcookie('userauth',authcode(md5(md5($password))."\t$mid",'ENCODE'),31536000);
			}elseif($autocheck == 2){
				mailto($email,'member_active_subject','member_active_content',array('mid' => $mid,'mname' => $username,'confirmid' => $confirmid));
			}
			mcmessage(!$autocheck ? 'userchecking' : ($autocheck == 2 ? 'emailactiving' : 'memactivesucceed'),$forward);
		}else mcmessage('memactfai',$forward);
	}else{
		if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}members WHERE mname='$username'")){
			mcmessage('impdra',$forward);
		}
		include_once M_ROOT.'./include/ucenter/uc.inc.php';
		$comstr = "mname='$username'";
		$comstr .= ",password='".md5(md5($password))."'";
		$comstr .= ",email='$email'";
		$comstr .= ",mtcid=1";
		$comstr .= ",mchid='$mchid'";
		foreach($currencys as $crid => $currency){
			($currency['available'] && $currency['initial']) && $comstr .= ",currency".$crid."='".$currency['initial']."'";
		}
		$autocheck == 1 && $comstr .= ",checked='1'";
		$comstr .= ",regip='$onlineip'";
		$comstr .= ",regdate='$timestamp'";
		$db->query("INSERT INTO {$tblprefix}members SET $comstr");
		if($mid = $db->insert_id()){
			$substr = "mid='$mid'";
			if($autocheck == 2){
				$confirmid = random(6);
				$confirmstr = "$timestamp\t2\t$confirmid";
				$substr .= ",confirmstr='".$confirmstr."'";
			}
			$db->query("INSERT INTO {$tblprefix}members_sub SET $substr");
			$db->query("INSERT INTO {$tblprefix}members_$mchid SET mid='$mid'");
			if($autocheck == 1){
				msetcookie('userauth', authcode(md5(md5($password))."\t$mid",'ENCODE'));
			}elseif($autocheck == 2){
				mailto($email,'member_active_subject','member_active_content',array('mid' => $mid,'mname' => $mname,'url' => "{$cms_abs}tools/memactive.php?action=emailactive&mid=$mid&id=$confirmid"));
			}
			mcmessage(!$autocheck ? 'userchecking' : ($autocheck == 2 ? 'emailactiving' : 'memactivesucceed'),$forward);
		}else mcmessage('memactfai',$forward);
	}
}
?>
