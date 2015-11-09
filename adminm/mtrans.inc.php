<?php
!defined('M_COM') && exit('No Permission');
load_cache('mchannels,mprojects,mtconfigs,grouptypes,rprojects,acatalogs,cotypes');
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
if(empty($mtran['toid'])){
	$toidsarr = array();
	foreach($mprojects as $k => $v){
		if($v['smchid'] == $curuser->info['mchid']) $toidsarr[$v['tmchid']] = $mchannels[$v['tmchid']]['cname'];
	}
	if($toidsarr){
		$isold = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}mtrans WHERE mid='$memberid' AND checked='0'");
		tabheader(lang('needmembertypealter'),'mtrans',"?action=mtrans");
		trbasic(lang('membercurrenttype'),'',$mchannels[$curuser->info['mchid']]['cname'],'');
		trbasic(lang('altertargettype'),'mtran[toid]',makeoption($toidsarr),'select');
		tabfooter('submit',lang($isold ? 'modify' : 'need'));
	}else mcmessage(lang('notranpro'));
}else{
	if($curuser->isadmin()) mcmessage(lang('adminnopm'));
	foreach($mprojects as $k => $v){
		if($v['ename'] == $curuser->info['mchid'].'_'.$mtran['toid']) $mproject = $v;
	}
	if(empty($mproject)) mcmessage('ycnpmt');
	//分析是已有更新申请还是新的申请
	$isold = false;
	if($minfos = $db->fetch_one("SELECT * FROM {$tblprefix}mtrans WHERE mid='$memberid' AND checked='0'")){
		$isold = true;
		$minfos = array_merge($minfos,$minfos['contentarr'] ? unserialize($minfos['contentarr']) : array());
		unset($minfos['contentarr']);
	}else{
		$curuser->detail_data();
		$minfos = &$curuser->info;
	}
	$mchid = $mtran['toid'];
	$mchannel = $mchannels[$mchid];
	$mfields = read_cache('mfields',$mchid);
	foreach(array('additems') as $var) $$var = $mchannel[$var] ? explode(',',$mchannel[$var]) : array();
	if(!submitcheck('bmtran')){
		$a_field = new cls_field;
		$submitstr = '';
		$muststr = '<span style="color:red">*</span>';

		tabheader(lang('membertypeneedoption'),'mtrans',"?action=mtrans",2,1,1);
		trhidden('mtran[toid]',$mtran['toid']);
		trbasic(lang('needtime'),'',date("Y-m-d H:m",$isold ? $minfos['createdate'] : $timestamp),'');
		trbasic(lang('remark'),'mtran[remark]',empty($minfos['remark']) ? '' : $minfos['remark'],'textarea');
		$isold && trbasic(lang('masterreply').@noedit(1),'',$minfos['reply'],'textarea');
		tabfooter();

		tabheader(lang('inputmembermessage'));
		if(in_array('mtcid',$additems)){
			trbasic(lang('spacetemplateproject'),'minfosnew[mtcid]',makeoption(mtcidsarr($mchid),$minfos['mtcid']),'select');
		}
		foreach($grouptypes as $k => $v){
			if(!$v['mode'] && !in_array($mchid,explode(',',$v['mchids'])) && in_array("grouptype$k",$additems)){
				trbasic($v['cname'],"minfosnew[grouptype$k]",makeoption(ugidsarr($k,$mchid),$minfos["grouptype$k"]),'select');
			}
		}
		foreach($mfields as $k => $field){
			if($field['available'] && !$field['issystem'] && !$field['isfunc'] && !$field['isadmin'] && in_array($k,$additems)){
				$a_field->init();
				$a_field->field = $field;
				if($curuser->pmbypmids('field',$a_field->field['pmid'])){//字段附加权限设置
					$a_field->oldvalue = !isset($minfos[$k]) ? '' : $minfos[$k];
					$a_field->trfield('minfosnew','','m',$mchid);
					$submitstr .= $a_field->submitstr;
				}
			}
		}
		unset($a_field);
		tabfooter('bmtran');
		check_submit_func($submitstr);
	}else{
		if(in_array('mtcid',$additems)){
			$minfosnew['mtcid'] = empty($minfosnew['mtcid']) ? 1 : $minfosnew['mtcid'];
			if($mproject['autocheck']) $curuser->updatefield('mtcid',$minfosnew['mtcid'],'main');
		}
		foreach($grouptypes as $k => $v){
			if(!$v['mode'] && in_array("grouptype$k",$additems)){
				$minfosnew["grouptype$k"] = empty($minfosnew["grouptype$k"]) ? 0 : $minfosnew["grouptype$k"];
				if($mproject['autocheck']) $curuser->updatefield("grouptype$k",$minfosnew["grouptype$k"],'main');
			}
		}

		$c_upload = new cls_upload;	
		$mfields = fields_order($mfields);
		$a_field = new cls_field;
		foreach($mfields as $k => $v){
			if($v['available'] && !$v['issystem'] && !$v['isfunc'] && !$v['isadmin'] && in_array($k,$additems)){
				if(!$curuser->pmbypmids('field',$v['pmid'])) continue;
				$a_field->init();
				$a_field->field = $v;
				$a_field->oldvalue = !isset($minfos[$k]) ? '' : $minfos[$k];
				$a_field->deal('minfosnew');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					mcmessage($a_field->error,M_REFERER);
				}
				if($mproject['autocheck']){
					$curuser->updatefield($k,$a_field->newvalue,$v['tbl']);
					if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $curuser->updatefield($k.'_'.$x,$y,$v['tbl']);
				}else{
					$minfosnew[$k] = $a_field->newvalue;
					if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";$minfosnew[$k.'_'.$x] = $y;
				}
			}
		}
		unset($a_field);
		$omchid = $curuser->info['mchid'];//原模型
		if($mproject['autocheck']){
			$curuser->updatefield('mchid',$mchid,'main');
			//在更新会员资料之前一定要将原模型表中的记录删除，插入新模型表中的记录。
			$db->query("DELETE FROM {$tblprefix}members_$omchid WHERE mid='$memberid'");
			$db->query("INSERT INTO {$tblprefix}members_$mchid SET mid='$memberid'");
			$curuser->gtidbymchid();//检查因模型改变是否有不生效会员组
			$curuser->updatedb();
			if($isold){
				$db->query("UPDATE {$tblprefix}mtrans SET toid='$mchid',fromid='$omchid',contentarr='',remark='',reply='',checked='1' WHERE mid='$memberid' AND checked='0'");
			}else{
				$db->query("INSERT INTO {$tblprefix}mtrans SET mid='$memberid',mname='".$curuser->info['mname']."',toid='$mchid',fromid='$omchid',contentarr='',remark='',checked='1',createdate='$timestamp'");
			}
		}else{
			$mtran['remark'] = trim($mtran['remark']);
			$mtran['contentarr'] = empty($minfosnew) ? '' : addslashes(serialize($minfosnew));
			if($isold){
				$db->query("UPDATE {$tblprefix}mtrans SET fromid='$omchid',toid='$mchid',contentarr='$mtran[contentarr]',remark='$mtran[remark]' WHERE mid='$memberid' AND checked='0'");
			}else{
				$db->query("INSERT INTO {$tblprefix}mtrans SET mid='$memberid',mname='".$curuser->info['mname']."',fromid='$omchid',toid='$mchid',contentarr='$mtran[contentarr]',remark='$mtran[remark]',checked='0',createdate='$timestamp'");
			}
		}
		$c_upload->closure(1, $memberid, 'members');
		$c_upload->saveuptotal(1);
		mcmessage($mproject['autocheck'] ? 'membertypealter' : 'waitcheck',"?action=mtrans");
	}
}
?>
