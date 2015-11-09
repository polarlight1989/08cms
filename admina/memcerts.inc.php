<?php
(defined('M_COM') && defined('M_ADMIN')) || exit('No Permission');
aheader();
backallow('member') || amessage('no_apermission');
$backamember = backallow('amember');
$modes = array(0 => lang('general_cert'), 1 => lang('email_cert'), 2 => lang('mobile_cert'));
load_cache('mchannels,memcerts');
foreach($mchannels as $k => $v)$mchannels[$k] = $v['cname'];
empty($action) && $action = '';

$url_type = 'memcert';include 'urlsarr.inc.php';
$action !='check' && $action !='detail' && url_nav(lang('memcert_manage'),$urlsarr, $action);
switch($action){
case ''://审核列表
	if(!submitcheck('bmemcertlist')){
		$wheresql = '';
		if(empty($keyword)){
			$keyword = '';
		}else{
			$wheresql = " AND mname like '%$keyword%'";
		}
		if(empty($mcid)){
			$mcid = '';
		}else{
			$wheresql = " AND mcid='$mcid'";
		}
		echo form_str('memcert_list', "?entry=$entry&action=$action");
		//搜索区块
		tabheader_e();
		echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
		//关键词固定显示
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
		//认证类型
		$mcids = array('0' => lang('nolimit'));
		if(!empty($memcerts))foreach($memcerts as $k => $v)$mcids[$k] = $v['title'];
		echo "<select style=\"vertical-align: middle;\" name=\"mcid\">".makeoption($mcids, $mcid)."</select>&nbsp; "
			."<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">"
			."</td></tr>";
		tabfooter();

		tabheader(lang('memcert_list'));
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">", array(lang('member_cname'), 'txtL'), array(lang('memcert_title'), 'txtL'), lang('needtime'), lang('detail')));
		$query = $db->query("SELECT * FROM {$tblprefix}mcrecords WHERE checktime=0$wheresql");
		while($row = $db->fetch_array($query)){
			echo "<tr class=\"txtC\">"
				."<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[]\" value=\"$row[crid]\"></td>\n"
				."<td class=\"txtL\">$row[mname]</td>\n"
				."<td class=\"txtL\">{$mcids[$row['mcid']]}</td>\n"
				."<td class=\"txtC\">".date('Y-m-d', $row['needtime'])."</td>\n"
				."<td class=\"txtC w40\"><a href=\"?entry=$entry&action=check&crid=$row[crid]\" onclick=\"return floatwin('open_memcert_check',this)\">".lang('detail')."</a></td>\n"
				."</tr>\n";
		}
		tabfooter();
		tabheader(lang('operate_item'));
		trbasic('', '', '<input class="checkbox" type="checkbox" name="mcrecord[delete]" id="mcrecord[delete]" value="1" /><label for="mcrecord[delete]">'.lang('delete').'</label>', '');
		tabfooter('bmemcertlist');
	}else{
		(empty($selectid) || empty($mcrecord)) && amessage('selectoperateitem', M_REFERER);
		$delete = array_key_exists('delete', $mcrecord);
		foreach($selectid as $v){
			if($delete){
				$db->query("DELETE FROM {$tblprefix}mcrecords WHERE crid='$v'");
				continue;
			}
		}
		amessage('mcrecord_finish', M_REFERER);
	}
	break;
case 'check'://单个审核
	(empty($crid) || !($record = $db->fetch_one("SELECT * FROM {$tblprefix}mcrecords WHERE crid='$crid' AND checktime=0"))) && amessage('memcert_check_fail');
	$user = new cls_userinfo;
	$user->activeuser($record['mid']);
	$memcert = $memcerts[$record['mcid']];
	strpos($memcert['mchids'], ','.$user->info['mchid'].',') === false && amessage('memcert_check_fail');
	$certdata = unserialize($record['certdata']);
	$values = $certdata['values'];

	empty($msgcode_mode) && $msgcode_mode = '';

	include_once M_ROOT."./include/upload.cls.php";
	include_once M_ROOT."./include/fields.cls.php";

	if(!submitcheck('bmemcertcheck')){
		tabheader(lang('memcert_check'), 'memcert_check', "?entry=$entry&action=$action&crid=$crid");
		trbasic(lang('member_cname'), '', $record['mname'], '');
		trbasic(lang('memcert_title'), '', $memcert['title'], '');
		trbasic(lang('needtime'), '', date('Y-m-d', $record['needtime']), '');
//		trbasic(lang('memcert_and_checked'), '', makeradio('memberset[check]', array(1 => lang('yes'), 0 => lang('no')), $memcerts[$record['mcid']]['check']), '');
		tabfooter();
		tabheader(lang('memcert_info'));
		$a_field = new cls_field;
		foreach($values as $k => $v){
			$a_field->init();
			$a_field->field = read_cache('mfield', $user->info['mchid'], $k);
			if(!empty($certdata['flags'][$k])){
				$a_field->field['cname'] .= ' [<i title="'.lang('memcert_ok').'">ok</i>]';
			}elseif($k == $memcert['mobile'] && $msgcode_mode == 1){
				$a_field->field['cname'] .= ' [<i title="'.lang('msg_code').'">'.$certdata['codes'][$k].'</i>]';
			}
			$a_field->oldvalue = $v;
			$a_field->trfield('memcertnew','','m',$user->info['mchid']);
		}
		tabfooter('bmemcertcheck', lang('memcert_modify_cert'));
	}else{
		unset($certdata['values']);
		$user->updatefield('memcert', $record['mcid']);
		$c_upload = new cls_upload;
		$a_field = new cls_field;
		foreach($values as $k => $v){
			$a_field->init();
			$a_field->field = read_cache('mfield',$user->info['mchid'],$k);
			if($curuser->pmbypmids('field',$a_field->field['pmid'])){
				$a_field->deal('memcertnew');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					message($a_field->error, M_REFERER);
				}
				$user->updatefield($k, $a_field->newvalue, $a_field->field['tbl']);
				$certdata['flags'][$k] = 1;
			}
		}
		$user->updatedb();
		$db->query("UPDATE {$tblprefix}mcrecords SET checktime='$timestamp',certdata='".addslashes(serialize($certdata))."' WHERE crid='$crid'");
		amessage('memcert_check_finish', axaction(6, "?entry=$entry"));
	}
	break;
case 'memcerts'://类别管理
	if(!submitcheck('bmemcertedit')){
		tabheader(lang('memcert_admin')."&nbsp; >>&nbsp; <a href=\"?entry=$entry&action=add\">".lang('memcert_add').'</a>', 'memcert_admin', "?entry=$entry&action=$action");
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">", array(lang('ID'), 'txtL'), array(lang('title'), 'txtL'), lang('memcert_level'), lang('detail')));
		$query = $db->query("SELECT mcid,title,level FROM {$tblprefix}memcerts ORDER BY level DESC");
		while($row = $db->fetch_array($query)){
			echo "<tr class=\"txtC\">"
				."<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[]\" value=\"$row[mcid]\"></td>\n"
				."<td class=\"txtL w60\">$row[mcid]</td>\n"
				."<td class=\"txtL\"><input type=\"text\" size=\"40\" name=\"memcertnew[$row[mcid]][title]\" value=\"$row[title]\"></td>\n"
				."<td class=\"txtC w80\"><input type=\"text\" size=\"5\" name=\"memcertnew[$row[mcid]][level]\" value=\"$row[level]\"></td>\n"
				."<td class=\"txtC w40\"><a href=\"?entry=$entry&action=detail&mcid=$row[mcid]\" onclick=\"return floatwin('open_memcert_detail',this)\">".lang('setting')."</a></td>\n"
				."</tr>\n";
		}
		tabfooter();
		tabheader(lang('operate_item'));
		trbasic('', '', '<input class="checkbox" type="checkbox" name="memcertedit[del]" id="memcertedit[del]" value="1" /><label for="memcertedit[del]">'.lang('memcert_delete').'</label>', '');
//		trbasic('<label for="memcertedit[chk]">'.lang('memcert_and_checked').'</label><input class="checkbox" type="checkbox" name="memcertedit[chk]" id="memcertedit[chk]" value="1" />', '', makeradio('memcertedit[check]', array(1 => lang('yes'), 0 => lang('no'))), '');
		tabfooter('bmemcertedit');
	}else{
#		(empty($selectid) || !array_filter($memcertedit)) && amessage('selectoperateitem', M_REFERER);
		$levels = array();
		foreach($memcertnew as $k => $v){
			$levels[$k] = $v['level'];
			$db->query("UPDATE {$tblprefix}memcerts SET title='$v[title]' WHERE mcid=$k");
		}
		memcertlevel($levels);
		if(!empty($selectid)){
			if(array_key_exists('del', $memcertedit)){
				$db->query("DELETE FROM {$tblprefix}mcrecords WHERE mcid IN (" . join(',', $selectid) . ')');
				$db->query("DELETE FROM {$tblprefix}memcerts WHERE mcid IN (" . join(',', $selectid) . ')');
			}
		}
		updatecache('memcerts');
		amessage('memcertfinish', "?entry=$entry&action=$action");
	}
	break;
case 'add'://添加类别
	if(!submitcheck('bmemcertadd')){
		tabheader(lang('memcert_add'), 'memcert_add', "?entry=$entry&action=$action");
		trbasic(lang('memcert_title'), 'memcertadd[title]');
		tabfooter('bmemcertadd');
	}else{
		$db->num_rows($db->query("SELECT mcid FROM {$tblprefix}memcerts WHERE title='$memcertadd[title]'")) ?
		amessage('memcert_exists', "?entry=$entry&action=$action") : (
		$db->query("INSERT INTO {$tblprefix}memcerts SET title='$memcertadd[title]'") && ($mcid = $db->insert_id()) ?
		amessage('memcert_add_modify', "?entry=$entry&action=detail&mcid=$mcid") :
		amessage('memcert_add_error', "?entry=$entry&action=$action") );
	}
	break;
case 'detail'://修改类别
	(empty($mcid) || !($memcert = $db->fetch_one("SELECT * FROM {$tblprefix}memcerts WHERE mcid='$mcid'"))) && amessage('memcert_modify_fail');
	if(!submitcheck('bmemcertmodify')){
		include_once M_ROOT."./include/fields.cls.php";
		
		tabheader(lang('memcert_modify'), 'memcert_modify', "?entry=$entry&action=$action&mcid=$mcid");
		trbasic(lang('memcert_title'), '', $memcert['title'], '');
		trspecial(lang('memcert_icon'), 'memcertnew[icon]', $memcert['icon'], 'image');
		trbasic(lang('memcert_remark'), 'memcertnew[remark]', $memcert['remark'], 'textarea');
		trbasic(lang('memcert_level'), 'memcertnew[level]', $memcert['level'], 'text', lang('memcert_level_tip'));
		trbasic(lang('memcert_mchid')."<br/><input class=\"checkbox\" type=\"checkbox\" id=\"chkall_mchid\" onclick=\"checkall(this.form,'memcertnew[mchids]','chkall_mchid')\"><label for=\"chkall_mchid\">".lang('selectall')."</label>", '', makecheckbox('memcertnew[mchids][]', $mchannels, explode(',', $memcert['mchids'])), '');
		trbasic(lang('memcert_mobile_field'), 'memcertnew[mobile]', $memcert['mobile'], 'text', lang('memcert_special_tip'));
		trbasic(lang('memcert_email_field'), 'memcertnew[email]', $memcert['email'], 'text', lang('memcert_special_tip'));
		trbasic(lang('memcert_fields'), 'memcertnew[fields]', $memcert['fields'], 'textarea', lang('memcert_fields_tip'));
//		trbasic(lang('memcert_and_checked'), '', makeradio('memcertnew[check]', array(1 => lang('yes'), 0 => lang('no')), $memcert['check']), '');
		tabfooter('bmemcertmodify');
	}else{
		!empty($memcertnew['mobile']) && preg_match("/\W/", $memcertnew['mobile']) && amessage('memcert_mode_fail', "?entry=$entry&action=$action&mcid=$mcid");
		!empty($memcertnew['email']) && preg_match("/\W/", $memcertnew['email']) && amessage('memcert_mode_fail', "?entry=$entry&action=$action&mcid=$mcid");
		((empty($memcertnew['mobile']) && empty($memcertnew['email']) && empty($memcertnew['fields'])) || (!empty($memcertnew['fields']) && !preg_match("/^\w+(?:,\w+)*$/", $memcertnew['fields']))) && amessage('memcert_fields_fail', "?entry=$entry&action=$action&mcid=$mcid");

		include_once M_ROOT."./include/upload.cls.php";

		$memcertnew['mchids'] = join(',', array_filter($memcertnew['mchids']));

		$db->query("UPDATE {$tblprefix}memcerts SET "
					."icon='$memcertnew[icon]',"
					."remark='$memcertnew[remark]',"
					."mchids='$memcertnew[mchids]',"
					."mobile='$memcertnew[mobile]',"
					."email='$memcertnew[email]',"
					."fields='$memcertnew[fields]'"
					." WHERE mcid=$mcid"
		);
		if($memcertnew['level'] != $memcert['level']){
			$levels = array($mcid => $memcertnew['level']);	
			$query = $db->query("SELECT mcid,level FROM {$tblprefix}memcerts WHERE mcid!='$mcid' ORDER BY level DESC");
			while($row = $db->fetch_array($query))$levels[$row['mcid']] = $row['level'];
			memcertlevel($levels);
		}
		updatecache('memcerts');
		amessage('memcertfinish', axaction(6, "?entry=$entry&action=memcerts"));
	}
	break;
}
function memcertlevel($levels){
	global $db, $tblprefix;
	natsort($levels);
	$i = 0;
	foreach($levels as $k => $v){
		$db->query("UPDATE {$tblprefix}memcerts SET level=$i WHERE mcid='$k'");
		$i++;
	}
}
?>