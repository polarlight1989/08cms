<?php
!defined('M_COM') && exit('No Permission');
load_cache('mchannels,uprojects,grouptypes');
if(!isset($utran['toid'])){
	$notranspro = true;
	foreach($grouptypes as $gtid => $grouptype){
		if(!$grouptype['issystem'] && $grouptype['mode'] == 1){
			$toidsarr = array();
			$usergroups = read_cache('usergroups',$gtid);
			foreach($uprojects as $k => $v){
				if(($v['sugid'] == $curuser->info["grouptype$gtid"]) && ($v['gtid'] == $gtid)){
					if($v['tugid'] && empty($usergroups[$v['tugid']])) continue;
					$toidsarr[$v['tugid']] = $v['tugid'] ? $usergroups[$v['tugid']]['cname'] : lang('user0');
				}
			}
			if($toidsarr){
				$isold = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}utrans WHERE mid='$memberid' AND checked='0' AND gtid='$gtid'");
				$nowugstr = '&nbsp; '.lang('groupcurrentuser').'&nbsp;:&nbsp;'.($curuser->info["grouptype$gtid"] ? $usergroups[$curuser->info["grouptype$gtid"]]['cname'] : lang('user0'));
				tabheader(lang('needusergroupalter',$grouptype['cname']).$nowugstr,"utrans$gtid","?action=utrans");
				trhidden('gtid',$gtid);
				trbasic(lang('altertargetusergroup'),'utran[toid]',makeoption($toidsarr),'select');
				tabfooter('submit',lang($isold ? 'modify' : 'need'));
				$notranspro = false;
			}
		}
	}	
	$notranspro && mcmessage(lang('notranpro'));
}else{
	if(empty($gtid)) mcmessage('choosegrouptype');
	foreach($uprojects as $k => $v){
		if($v['ename'] == $curuser->info["grouptype$gtid"].'_'.$utran['toid']) $uproject = $v;
	}
	if(empty($uproject)) mcmessage('ycnpu');
	$sugid = $curuser->info["grouptype$gtid"];
	$tugid = $utran['toid'];
	$mchid = $curuser->info['mchid'];
	if(in_array($mchid,explode(',',$grouptypes[$gtid]['mchids']))) mcmessage('ybomccntu');
	if($tugid && (!($usergroup = read_cache('usergroup',$gtid,$tugid)) || !in_array($mchid,explode(',',$usergroup['mchids'])))) mcmessage('ybomcnntu');
	//分析是已有更新申请还是新的申请
	$isold = false;
	//仅需要读出上次申请时间，备注与回复出来
	if($minfos = $db->fetch_one("SELECT * FROM {$tblprefix}utrans WHERE mid='$memberid' AND checked='0' AND gtid='$gtid'")){
		$isold = true;
	}
	$minfos['fromid'] = $curuser->info["grouptype$gtid"];
	$minfos['toid'] = $utran['toid'];
	if(!submitcheck('butran')){
		$usergroups = read_cache('usergroups',$gtid);
		$submitstr = '';
		tabheader(lang('usergroupneedoption').'&nbsp; -&nbsp; '.$grouptypes[$gtid]['cname'],'utrans',"?action=utrans",2,1,1);
		trbasic(lang('usergroupaltermodel'),'',(!$sugid ? lang('user0') : $usergroups[$sugid]['cname']).'&nbsp; ->&nbsp; '.(!$tugid ? lang('user0') : $usergroups[$tugid]['cname']),'');
		trhidden('utran[toid]',$tugid);
		trhidden('gtid',$gtid);
		trbasic(lang('applytime'),'',date("Y-m-d H:i",$isold ? $minfos['createdate'] : $timestamp),'');
		trbasic(lang('remark'),'utran[remark]',empty($minfos['remark']) ? '' : $minfos['remark'],'textarea');
		$isold && trbasic(lang('adminreply').@noedit(1),'',$minfos['reply'],'textarea');
		tabfooter('butran');
		check_submit_func($submitstr);
	}else{
		//需要检查一下，当前会员是否允许加入到新的会员组
		$omchid = $curuser->info['mchid'];//原模型
		if($uproject['autocheck']){
			$curuser->updatefield("grouptype$gtid",$tugid,'main');
			$curuser->updatedb();
			if($isold){
				$db->query("UPDATE {$tblprefix}utrans SET toid='$tugid',fromid='$sugid',remark='',reply='',checked='1' WHERE mid='$memberid' AND checked='0' AND gtid='$gtid'");
			}else{
				$db->query("INSERT INTO {$tblprefix}utrans SET mid='$memberid',mname='".$curuser->info['mname']."',gtid='$gtid',toid='$tugid',fromid='$sugid',remark='',checked='1',createdate='$timestamp'");
			}
		}else{
			$utran['remark'] = trim($utran['remark']);
			if($isold){
				$db->query("UPDATE {$tblprefix}utrans SET toid='$tugid',fromid='$sugid',remark='$utran[remark]' WHERE mid='$memberid' AND checked='0' AND gtid='$gtid'");
			}else{
				$db->query("INSERT INTO {$tblprefix}utrans SET mid='$memberid',mname='".$curuser->info['mname']."',gtid='$gtid',toid='$tugid',fromid='$sugid',remark='$utran[remark]',checked='0',createdate='$timestamp'");
			}
		}
		mcmessage($uproject['autocheck'] ? 'usergroupalterfinish' : 'waitcheck',"?action=utrans");
	}
}
?>
