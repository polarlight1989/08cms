<?php
defined('M_COM') || exit('No Permission');

load_cache('mchannels,memcerts');
$modes = array(0 => lang('general_cert'), 1 => lang('email_cert'), 2 => lang('mobile_cert'));
empty($option) && $option = '';
switch($option){
case ''://允许的认证列表
	if($curuser->info['memcert'] && $memcert = $memcerts[$curuser->info['memcert']]){
		#当前认证
		echo '<table border="0" cellpadding="0" cellspacing="1" class="tabmain">'
			.'<tr class="header"><td colspan="2"><b>'.lang('memcert_current').'</b></td></tr>'
			.'<tr><td width="25%" class="item">'.($memcert['icon'] ? "<img src=\"$memcert[icon]\"/><br/>" : '').'<b>'.$memcert['title'].'</b></td>'
			.'<td class="item2">'.$memcert['remark'].'</td></tr>'
			.'</table>';
		unset($memcerts[$curuser->info['memcert']]);
	}
	$flag = 0;
	if($row = $db->fetch_one("SELECT mcid,crid FROM {$tblprefix}mcrecords WHERE mid=$memberid AND checktime=0 LIMIT 0,1")){
		#认证中的认证
		if($memcert = $memcerts[$row['mcid']]){
			$flag = 1;
			echo '<table border="0" cellpadding="0" cellspacing="1" class="tabmain">'
				.'<tr class="header"><td colspan="2"><b>'.lang('memcert_checkimg').'</b> -- <a href="?action=memcerts&option=delete&crid='.$row['crid'].'" onclick="if(!confirm(window.lang(\'confirm_operate\')))return false">'.lang('delete').'</a></td></tr>'
				.'<tr><td width="25%" class="item">'.($memcert['icon'] ? "<img src=\"$memcert[icon]\"/><br/>" : '').'<b>'.$memcert['title'].'</b></td>'
				.'<td class="item2">'.$memcert['remark'].'</td></tr>'
				.'</table>';
			unset($memcerts[$row['mcid']]);
		}else{
			$db->query("DELETE FROM {$tblprefix}mcrecords WHERE crid='$row[crid]' AND mid=$memberid AND checktime=0");
		}
	}
	echo '<script type="text/javascript">function need_memcert(){var flag=!'.$flag.';flag||alert(lang("memcert_checking"));return flag}</script>';
	$flag = 0;
	$string = '';
	$str = ','.$curuser->info['mchid'].',';
	foreach($memcerts as $k => $v){
		if(strpos($v['mchids'], $str) === false)continue;
		elseif($flag){
			#认证过的认证
			if(!$flag == 1){
				$flag = $string ? 2 : 3;
				$string =($string ? $string . '</table>' : '')
						.'<table border="0" cellpadding="0" cellspacing="1">'
						.'<tr class="header"><td colspan="2"><b>'.lang('memcert_checked').'</b></td></tr>';
			}
			$string .=	'<tr><td width="25%" class="item">'.($v['icon'] ? "<img src=\"$v[icon]\"/><br/>" : '')."<b>$v[title]</b></td>"
					.	"<td class=\"item2\">$v[remark]</td></tr>";
		}else{
			#可以申请的认证
			if($k == $curuser->info['memcert']){
				$flag = 1;
			}else{
				$string = '<tr><td width="25%" class="item"><a href="?action=memcerts&option=need&mcid='.$k.'" title="'.lang('memcert_click').'" onclick="return need_memcert()">'.($v['icon'] ? "<img src=\"$v[icon]\" border=\"0\"/><br/>" : '')."<b>$v[title]</b></a></td>"
						. "<td class=\"item2\">$v[remark]</td></tr>"
						. $string;
			}
		}
	}
	$string || mcmessage('memcert_need_fail');

	if(!$flag || $flag != 3)$string = '<table border="0" cellpadding="0" cellspacing="1" class="tabmain">'
									. '<tr class="header"><td colspan="2"><b>'.lang('memcert_can_check').'</b></td></tr>' . $string;
	echo $string . '</table>';
	break;
case 'need'://认证申请
	$db->result_one("SELECT COUNT(*) FROM {$tblprefix}mcrecords WHERE mid='$memberid' AND checktime=0") && mcmessage('memcert_exists');

	$flag = 0;
	foreach($memcerts as $k => $v){
		if($k == $curuser->info['memcert'])break;
		if($k == $mcid){
			$flag = 1;
			break;
		}
	}
	$mchid = $curuser->info['mchid'];
	$flag || mcmessage('memcert_need_fail');
	if($flags = $db->result_one("SELECT certdata FROM {$tblprefix}mcrecords WHERE mid='$memberid' AND checktime!=0 ORDER BY crid DESC LIMIT 0,1")){
		$flags = unserialize($flags);
		$flags = $flags['flags'];
	}else{
		$flags = array();
	}
	$memcert = $memcerts[$mcid];
	$fields = explode(',', $memcert['fields']);
	$mfields = read_cache('mfields', $mchid);
	foreach($fields as $k)(array_key_exists($k, $mfields) && $mfields[$k]['available'] && !$mfields[$k]['isfunc'] && $curuser->pmbypmids('field', $mfields[$k]['pmid'])) || mcmessage('memcert_no_field');
	
	empty($msgcode_mode) && $msgcode_mode = '';

	include_once M_ROOT."./include/upload.cls.php";
	include_once M_ROOT."./include/arcedit.cls.php";
	include_once M_ROOT."./include/fields.fun.php";
	include_once M_ROOT."./include/commu.fun.php";
	include_once M_ROOT."./include/fields.cls.php";

	$curuser->detail_data();
	if(!submitcheck('barchivedetail')){
	
		tabheader(lang('memcert_need') . ' - ' . $memcert['title'], 'memcert_need', "?action=$action&option=$option&mcid=$mcid&t=$timestamp", 2, 1, 1);
		$submitstr = '';
		$a_field = new cls_field;
		foreach($fields as $k){
			$a_field->init();
			$a_field->field = read_cache('mfield', $mchid, $k);
			$a_field->oldvalue = $curuser->info[$k];
			if($k == $memcert['mobile']){
				if($msgcode_mode == 1 || $msgcode_mode == 2){
					$lang_msg_code = lang('msg_code');
					$lang_click_get_mcode = lang('click_get_mcode');
					$input = $msgcode_mode == 1 ? '' : '<input type="text" size="10" id="msgcode" name="msgcode"/>&nbsp;&nbsp;';
					$mobile = empty($flags[$k]) ? '' : '
if(mob.defaultValue == mob.value)return alert(lang(\'repeat_mobile_not_modify\'));';
					$a_field->field['guide'] .=<<< EOT
<tr><td width="25%" class="item1"><b>$lang_msg_code</b></td>
<td class="item2"><script type="text/javascript">//?><script>
function getMsgcode(mob){
	var aj, tmp, step = 1;
	if(!mob.value.match(/^1[358]\d{9}$/))return alert(lang('mobile_format_fail'));$mobile;
	if(getMsgcode.status && (tmp = (getMsgcode.status - (new Date).getTime()) / 1000) > 0){
		if(getMsgcode.status == 1 || getMsgcode.info)return alert(lang(getMsgcode.info || 'donot_repeat_operate', step + lang('minutes')));
		if(tmp >= 50){
			tmp = Math.ceil(tmp / 60) + lang('minutes');
		}else{
			tmp = Math.ceil(tmp / 10) * 10 + lang('seconds');
		}
		return alert(lang('msgcode_send_ok', tmp));
	}
	getMsgcode.status = 1;
	tmp = setTimeout(function(){getMsgcode.status = 0}, step * 60000);
	aj = new Ajax('XML');
	aj.get('{$cms_abs}tools/ajax.php?action=memcert&option=msgcode&mobile='+mob.value, function(info){
		clearTimeout(tmp);
		if(info.time > 0)step = info.time;
		getMsgcode.status = (new Date).getTime() + step * 60000;
		if(info.time < 0)getMsgcode.info = info.text;
		alert(lang(info.text || 'msgcode_send_ok', step + lang('minutes')));
	});
}
</script>
$input<a href="javascript:" onclick="getMsgcode(\$id('memcertnew[$k]'));">$lang_click_get_mcode</a><span id="alert_msgcode" style="color:red"></span></td></tr>
EOT;
//<?
						}
					}
					$a_field->trfield('memcertnew','','m',$mchid);
					if($k == $memcert['mobile'] && $msgcode_mode == 2){
						$mobile = empty($flags[$k]) ? 1 : 0;
						$submitstr .=<<< EOT
//?><script>
var _i = i,mob = \$id('memcertnew[$k]');
i = 1;
{$a_field->submitstr}
if(i && !empty(mob.value) && ($mobile || mob.defaultValue != mob.value)){
	i = _i;
	rmsg = checktext('msgcode',1,'','/^\\\\d{6}$/');
	if(dom=\$id('alert_msgcode'))dom.innerHTML = rmsg ? rmsg : '';
	if(rmsg)i = false;
}
//<?

EOT;
			}else{
				$submitstr .= $a_field->submitstr;
			}
		}
		tabfooter('barchivedetail');
		check_submit_func($submitstr);
		break;
	}else{
		$email = '';
		foreach($fields as $k)empty($memcertnew[$k]) && mcmessage('memcert_empty_field', M_REFERER);
		$certdata = array('values' => $memcertnew, 'flags' => $flags);
		foreach($fields as $k){
			if(empty($flags[$k]) || stripslashes($memcertnew[$k]) != $curuser->info[$k]){
				if($k == $memcert['mobile'] && ($msgcode_mode == 1 || $msgcode_mode == 2)){
					$curuser->detail_data();
					@list($inittime, $initcode) = maddslashes(explode("\t", authcode($m_cookie['08cms_msgcode'],'DECODE')),1);
					if($msgcode_mode == 1){
						$certdata['codes'][$k] = $initcode;
						if(!empty($flags[$k]))unset($certdata['flags'][$k]);
					}else{
						($timestamp - $inittime > 1800 || $initcode != $msgcode) && mcmessage('memcert_msgcode_err', M_REFERER);
						$certdata['flags'][$k] = 1;
						$certdata['codes'][$k] = $initcode;
					}
				}else{
					if($k == $memcert['email']){
						$email = stripslashes($memcertnew[$k]);
						$confirm = random(6);
						$certdata['codes'][$k] = array('e' => 0, 'v' => $confirm);
					}
					if(!empty($flags[$k]))unset($certdata['flags'][$k]);
				}
			}
		}
		$db->query("INSERT INTO {$tblprefix}mcrecords(mid,mname,mcid,needtime,certdata)"
				  ." VALUES($memberid,'".addslashes($curuser->info['mname'])."','$mcid',$timestamp,"
				  ."'".addslashes(serialize($certdata))."')");
		if($crid = $db->insert_id()){
			if(empty($email)){
				mcmessage('memcert_upload_ok', M_REFERER);
			}else{
				mailto($email,'memcert_subject','memcert_content',array('mid' => $curuser->info['mid'],'mname' => $curuser->info['mname'],'url' => "{$cms_abs}tools/memactive.php?action=memcert&crid=$crid&confirm=$confirm"));
				mcmessage('memcert_email_sent', M_REFERER);
			}
		}else{
			mcmessage('memcert_upload_bad', M_REFERER);
		}
	}
	break;
case 'delete':
	if($db->query("DELETE FROM {$tblprefix}mcrecords WHERE crid='$crid' AND mid=$memberid AND checktime=0")){
			mcmessage('memcert_delete_ok', M_REFERER);
	}else{
			mcmessage('memcert_delete_bad', M_REFERER);
	}
	break;
}
?>