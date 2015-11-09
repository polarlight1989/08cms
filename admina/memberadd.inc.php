<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('member') || amessage('no_apermission');
load_cache('mchannels,catalogs,acatalogs,cotypes,mtconfigs,channels,grouptypes,currencys,rprojects');
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
$backamember = backallow('amember');
if(empty($mchid)){
	$num = 4;
	tabheader(lang('channel_add_member'),'','',$num);
	$i = 0;
	foreach($mchannels as $k => $v){
		if(!($i % $num)) echo "<tr class=\"txt\">";
		echo "<td class=\"txtL w25B\"> &nbsp;>> &nbsp;<a href=\"?entry=memberadd&mchid=$k\" onclick=\"return floatwin('open_memberadd',this)\">$v[cname]</a></td>\n";
		$i ++;
		if(!($i % $num)) echo "</tr>\n";
	}
	if($i % $num){
		while($i % $num){
			echo "<td class=\"txtL w25B\"></td>\n";
			$i ++;
		}
		echo "</tr>\n";
	}
	tabfooter();
	a_guide('memberadd0');
}else{
	$mchid = empty($mchid) ? 1 : max(1,intval($mchid));
	if(!($mchannel = $mchannels[$mchid])) amessage('choosememberchannel');
	$mfields = read_cache('mfields',$mchid);
	if(!submitcheck('bmemberadd')){

		$a_field = new cls_field;
		$submitstr = '';
		tabheader(lang('base_option').'&nbsp;- &nbsp; '.lang('add').$mchannels[$mchid]['cname'],'memberadd','?entry=memberadd&mchid='.$mchid,2,1,1,1);
		trbasic('*&nbsp;'.lang('member_cname'),'minfosadd[mname]');
		trbasic('*&nbsp;'.lang('password'),'minfosadd[password]','','password');
		trbasic('&nbsp;'.lang('email'),'minfosadd[email]');
		$submitstr .= makesubmitstr('minfosadd[mname]',1,0,0,15);
		$submitstr .= makesubmitstr('minfosadd[password]',1,0,0,15);
		#$submitstr .= makesubmitstr('minfosadd[email]',1,'email',0,50);

		//个人空间模板
		#trbasic(lang('space_tpl_prj'),'minfosadd[mtcid]',makeoption(mtcidsarr($mchid)),'select');

		foreach($mfields as $k => $field){
			if($field['available'] && !$field['issystem'] && !$field['isfunc']){
				$a_field->init();
				$a_field->field = $field;
				$a_field->isadd = 1;
				$a_field->trfield('minfosadd','','m',$mchid);
				$submitstr .= $a_field->submitstr;
			}
		}
		tabfooter();
		tabheader(lang('usergroup_msg'),'','',4);
		foreach($grouptypes as $gtid => $grouptype) {
			if(!in_array($mchid,explode(',',$grouptype['mchids'])) && ($grouptype['mode'] < 2) && ($backamember || $gtid != 2)){
				$ugidsarr = array('0' => lang('noset')) + ugidsarr($grouptype['gtid'],$mchid);
				echo "<tr class=\"txt\">\n".
					"<td class=\"txtL w15B\">$grouptype[cname]</td>\n".
					"<td class=\"txtL w35B\"><select style=\"vertical-align: middle;\" name=\"minfosadd[grouptype".$gtid."]\">".makeoption($ugidsarr)."</select></td>\n".
					"<td class=\"txtL w15B\">".lang('enddate')."</td>\n".
					"<td class=\"txtL w35B\"><input type=\"text\" size=\"20\" id=\"minfosadd[grouptype".$gtid."date]\" name=\"minfosadd[grouptype".$gtid."date]\" value=\"\" onclick=\"ShowCalendar(this.id);\"></td>\n".
					"</tr>";
			}
		}
		tabfooter('bmemberadd');
		check_submit_func($submitstr);
		a_guide('memberadd');	
	}else{
		$minfosadd['mname'] = trim(strip_tags($minfosadd['mname']));
		$minfosadd['password'] = trim($minfosadd['password']);
		$minfosadd['email'] = trim(strip_tags($minfosadd['email']));
		if(strlen($minfosadd['mname']) < 3 || strlen($minfosadd['mname']) > 15) amessage('mnamelengthillegal',axaction(2,M_REFERER));
		$guestexp = '\xA1\xA1|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$censorexp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($censoruser = trim($censoruser)), '/')).')$/i';
		if(preg_match("/^\s*$|^c:\\con\\con$|[%,\*\"\s\t\<\>\&]|$guestexp/is",$minfosadd['mname']) || ($censoruser && @preg_match($censorexp,$minfosadd['mname']))) amessage('membernameillegal',axaction(2,M_REFERER));
		$query = $db->query("SELECT mid FROM {$tblprefix}members WHERE mname='$minfosadd[mname]'");
		if($db->num_rows($query)) amessage('membernamerepeat',axaction(2,M_REFERER));
		if(!$minfosadd['password'] || strlen($minfosadd['password']) > 15 || $minfosadd['password'] != addslashes($minfosadd['password'])) amessage('memberpwdillegal',axaction(2,M_REFERER));
		$md5_password = md5(md5($minfosadd['password']));
		#if(!$minfosadd['email'] || !isemail($minfosadd['email'])) amessage('memberemailillegal',axaction(2,M_REFERER));
		$autocheck = $mchannel['autocheck'];
		$mainarr = $subarr = $customarr = array();
		$mainarr['mname'] = $minfosadd['mname'];
		$mainarr['password'] = $md5_password;
		$mainarr['email'] = $minfosadd['email'];
		
		$mainarr['mtcid'] = empty($minfosadd['mtcid']) ? 1 : $minfosadd['mtcid'];
		foreach($grouptypes as $gtid => $grouptype) {
			if(empty($grouptype['mode']) && !in_array($mchid,explode(',',$grouptype['mchids'])) && ($backamember || $gtid != 2)){
				$usergroups = read_cache('usergroups',$gtid);
				if(!$minfosadd['grouptype'.$gtid] || in_array($mchid,explode(',',@$usergroups[$minfosadd['grouptype'.$gtid]]['mchids']))){
					$mainarr["grouptype$gtid"] = $minfosadd['grouptype'.$gtid];
					$mainarr['grouptype'.$gtid.'date'] = !$minfosadd['grouptype'.$gtid] || !isdate($minfosadd['grouptype'.$gtid.'date']) ? '0' : strtotime($minfosadd['grouptype'.$gtid.'date']);
				}
			}
		}
		foreach($currencys as $crid => $currency){
			if($currency['available'] && $currency['initial']) $mainarr["currency$crid"] = $currency['initial'];
		}
		$mainarr['checked'] = 1;
		$mainarr['regip'] = $onlineip;
		$mainarr['regdate'] = $timestamp;
		
		$c_upload = new cls_upload;	
		$mfields = fields_order($mfields);
		$a_field = new cls_field;
		$substr = $customstr = '';
		foreach($mfields as $k => $v){
			if($v['available'] && !$v['issystem'] && !$v['isfunc']){
				$a_field->init();
				$a_field->field = $v;
				$a_field->deal('minfosadd');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					amessage($a_field->error,axaction(2,M_REFERER));
				}
				${$v['tbl'].'arr'}[$k] = $a_field->newvalue;
				if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) ${$v['tbl'].'arr'}[$k.'_'.$x] = $y;
			}
		}
		unset($a_field);
		$newuser = new cls_userinfo;
		if(!$newuser->useradd($mainarr['mname'],$mainarr['password'],$mainarr['email'],$mchid)) amessage('memberaddfailed',axaction(2,M_REFERER));
		$mid = $newuser->info['mid'];
		foreach(array('main','sub','custom') as $var){
			foreach(${$var.'arr'} as $k => $v) $newuser->updatefield($k,$v,$var);
		}
		$newuser->autoinit();
		$newuser->updatedb();
		unset($newuser);
		$c_upload->closure(1, $mid, 'members');
		$c_upload->saveuptotal(1);

		if($enable_uc){//首先应该分析本地注册
			include_once M_ROOT.'./include/ucenter/config.inc.php';
			include_once M_ROOT.'./uc_client/client.php';
			$uid = uc_user_register($minfosadd['mname'],$minfosadd['password'],$minfosadd['email']);
			if($uid <= 0) {
				if($uid == -1) {
					amessage('membernameillegal');
				} elseif($uid == -2) {
					amessage('membernameillegal');
				} elseif($uid == -3) {
					amessage('membernamerepeat');
				} elseif($uid == -4) {
					amessage('memberemailillegal');
				} elseif($uid == -5) {
					amessage('memberemailillegal');
				} elseif($uid == -6) {
					amessage('memberemailillegal');
				} else {
					amessage('erroroperate');
				}
			}
		}

		adminlog(lang('add_member'));
		amessage('memberaddfinish',axaction(6,'?entry=memberadd&mchid='.$mchid));
	}
}
?>
