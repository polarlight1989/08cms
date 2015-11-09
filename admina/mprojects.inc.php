<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cftrans') || amessage('no_apermission');
load_cache('mchannels,mprojects,uprojects,grouptypes');
if($action == 'mprojectsedit'){
	tabheader(lang('membchaaltpro')."&nbsp; &nbsp; >><a href=\"?entry=mprojects&action=mprojectadd\" onclick=\"return floatwin('open_mprojects',this)\">".lang('addproject').'</a>','','','10');
	trcategory(array(lang('sn'),lang('projectname'),lang('sourcechannel'),lang('targetchannel'),lang('autocheck'),lang('edit'),lang('delete')));
	$i = 0;
	foreach($mprojects as $k => $v){
		$i ++;
		echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\">$i</td>\n".
			"<td class=\"txtL\">$v[cname]</td>\n".
			"<td class=\"txtC\">".$mchannels[$v['smchid']]['cname']."</td>\n".
			"<td class=\"txtC\">".$mchannels[$v['tmchid']]['cname']."</td>\n".
			"<td class=\"txtC w60\">".(empty($v['autocheck']) ? '-' : 'Y')."</td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=mprojects&action=mprojectdetail&mpid=$k\" onclick=\"return floatwin('open_mprojects',this)\">".lang('detail')."</a></td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=mprojects&action=mprojectdel&mpid=$k\">".lang('delete')."</a></td>\n".
			"</tr>\n";
	}
	tabfooter();
	foreach($grouptypes as $gtid => $grouptype){
		if(!$grouptype['issystem'] && $grouptype['mode'] == 1){
			$ugidsarr = array(0 => lang('user0')) + ugidsarr($gtid);
			$nuprojects = array();
			foreach($uprojects as $k => $v){
				if($v['gtid'] == $gtid) $nuprojects[$k] = $v;
			}
			tabheader(lang('useltpro')."&nbsp; -&nbsp; $grouptype[cname]"."&nbsp; &nbsp; >><a href=\"?entry=mprojects&action=uprojectadd&gtid=$gtid\" onclick=\"return floatwin('open_mprojects',this)\">".lang('addproject').'</a>','','','10');
			trcategory(array(lang('sn'),lang('projectname'),lang('sourceuser'),lang('targetusergroup'),lang('autocheck'),lang('edit'),lang('delete')));
			$i = 0;
			foreach($nuprojects as $k => $v){
				$i ++;
				echo "<tr class=\"txt\">".
					"<td class=\"txtC w30\">$i</td>\n".
					"<td class=\"txtL\">$v[cname]</td>\n".
					"<td class=\"txtC\">".$ugidsarr[$v['sugid']]."</td>\n".
					"<td class=\"txtC\">".$ugidsarr[$v['tugid']]."</td>\n".
					"<td class=\"txtC w60\">".(empty($v['autocheck']) ? '-' : 'Y')."</td>\n".
					"<td class=\"txtC w30\"><a href=\"?entry=mprojects&action=uprojectdetail&gtid=$gtid&upid=$k\" onclick=\"return floatwin('open_mprojects',this)\">".lang('detail')."</a></td>\n".
					"<td class=\"txtC w30\"><a href=\"?entry=mprojects&action=uprojectdel&gtid=$gtid&upid=$k\">".lang('delete')."</a></td>\n".
					"</tr>\n";
			}
			tabfooter();
		}	
	}	
	a_guide('mprojectsedit');


}elseif($action == 'mprojectadd'){
	!($mchidsarr = mchidsarr()) && amessage('conmemcha');
	if(!submitcheck('bmprojectadd')){
		tabheader(lang('addmemchaaltpro'),'mprojectadd','?entry=mprojects&action=mprojectadd',2,0,1);
		trbasic(lang('projectname'),'mprojectnew[cname]');
		trbasic(lang('sourmemcha'),'mprojectnew[smchid]',makeoption($mchidsarr),'select');
		trbasic(lang('tarmemcha'),'mprojectnew[tmchid]',makeoption($mchidsarr),'select');
		trbasic(lang('memaltautche'),'mprojectnew[autocheck]',0,'radio');
		tabfooter('bmprojectadd',lang('add'));
		$submitstr = '';
		$submitstr .= makesubmitstr('mprojectnew[cname]',1,0,3,30);
		check_submit_func($submitstr);
		a_guide('mprojectadd');
	}else{
		$mprojectnew['cname'] = trim(strip_tags($mprojectnew['cname']));
		if(!$mprojectnew['cname']) amessage('inpprocna',axaction(2,M_REFERER));
		if($mprojectnew['smchid'] == $mprojectnew['tmchid']) amessage('souchatarchasam',axaction(2,M_REFERER));
		$mprojectnew['ename'] = $mprojectnew['smchid'].'_'.$mprojectnew['tmchid'];
		$usedcnames = array();
		foreach($mprojects as $v) $usedcnames[] = $v['ename'];
		if(in_array($mprojectnew['ename'],$usedcnames)) amessage('prorepdef',axaction(2,M_REFERER));
		$db->query("INSERT INTO {$tblprefix}mprojects SET 
					cname='$mprojectnew[cname]', 
					ename='$mprojectnew[ename]', 
					smchid='$mprojectnew[smchid]', 
					tmchid='$mprojectnew[tmchid]', 
					autocheck='$mprojectnew[autocheck]'
					");
		updatecache('mprojects');
		adminlog(lang('addmemchaaltpro'),lang('addmemchaaltpro'));
		amessage('memchaalproaddfin',axaction(6,M_REFERER));
	}
}elseif($action == 'mprojectdetail' && $mpid){
	!($mchidsarr = mchidsarr()) && amessage('conmemcha');
	!($mproject = $mprojects[$mpid]) && amessage('choosememchaaltpro');
	if(!submitcheck('bmprojectdetail')){
		tabheader(lang('edmemchaaltpro'),'mprojectdetail',"?entry=mprojects&action=mprojectdetail&mpid=$mpid",2,0,1);
		trbasic(lang('projectname'),'mprojectnew[cname]',$mproject['cname']);
		trbasic(lang('sourmemcha'),'mprojectnew[smchid]',makeoption($mchidsarr,$mproject['smchid']),'select');
		trbasic(lang('tarmemcha'),'mprojectnew[tmchid]',makeoption($mchidsarr,$mproject['tmchid']),'select');
		trbasic(lang('memaltautche'),'mprojectnew[autocheck]',$mproject['autocheck'],'radio');
		tabfooter('bmprojectdetail');
		$submitstr = '';
		$submitstr .= makesubmitstr('mprojectnew[cname]',1,0,3,30);
		check_submit_func($submitstr);
		a_guide('mprojectdetail');
	}else{
		$mprojectnew['cname'] = trim(strip_tags($mprojectnew['cname']));
		if(!$mprojectnew['cname']) amessage('inpprocna',M_REFERER);
		if($mprojectnew['smchid'] == $mprojectnew['tmchid']) amessage('souchatarchasam',axaction(2,M_REFERER));
		$mprojectnew['ename'] = $mprojectnew['smchid'].'_'.$mprojectnew['tmchid'];
		$usedcnames = array();
		foreach($mprojects as $v) $usedcnames[] = $v['ename'];
		if(($mprojectnew['ename'] != $mproject['ename']) && in_array($mprojectnew['ename'],$usedcnames)) amessage('prorepdef',axaction(2,M_REFERER));
		$db->query("UPDATE {$tblprefix}mprojects SET 
					cname='$mprojectnew[cname]', 
					ename='$mprojectnew[ename]', 
					smchid='$mprojectnew[smchid]', 
					tmchid='$mprojectnew[tmchid]', 
					autocheck='$mprojectnew[autocheck]'
					WHERE mpid='$mpid'
					");
		updatecache('mprojects');
		adminlog(lang('modmemchaaltpro'),lang('modmemchaaltpro'));
		amessage('memchaalpromodfin',axaction(6,'?entry=mprojects&action=mprojectsedit'));
	}
}elseif($action == 'uprojectadd' && $gtid){
	!($ugidsarr = ugidsarr($gtid)) && amessage('confirmadduser');
	if(!submitcheck('buprojectadd')){
		$ugidsarr = array(0 => lang('user0')) + $ugidsarr;
		tabheader(lang('addusergaltpro'),"uprojectadd","?entry=mprojects&action=uprojectadd&gtid=$gtid",2,0,1);
		trbasic(lang('projectname'),'uprojectnew[cname]');
		trbasic(lang('sourceuser'),'uprojectnew[sugid]',makeoption($ugidsarr),'select');
		trbasic(lang('targetusergroup'),'uprojectnew[tugid]',makeoption($ugidsarr),'select');
		trbasic(lang('useraltautch'),'uprojectnew[autocheck]',0,'radio');
		tabfooter('buprojectadd',lang('add'));
		$submitstr = '';
		$submitstr .= makesubmitstr('uprojectnew[cname]',1,0,3,30);
		check_submit_func($submitstr);
		a_guide('uprojectadd');
	}else{
		$uprojectnew['cname'] = trim(strip_tags($uprojectnew['cname']));
		if(!$uprojectnew['cname']) amessage('inpprocna',axaction(2,M_REFERER));
		if($uprojectnew['sugid'] == $uprojectnew['tugid']) amessage('souuserandtar',axaction(2,M_REFERER));
		$uprojectnew['ename'] = $uprojectnew['sugid'].'_'.$uprojectnew['tugid'];
		$usedcnames = array();
		foreach($uprojects as $v) $usedcnames[] = $v['ename'];
		if(in_array($uprojectnew['ename'],$usedcnames)) amessage('prorepdef',axaction(2,M_REFERER));
		$db->query("INSERT INTO {$tblprefix}uprojects SET 
					cname='$uprojectnew[cname]', 
					ename='$uprojectnew[ename]', 
					gtid='$gtid', 
					sugid='$uprojectnew[sugid]', 
					tugid='$uprojectnew[tugid]', 
					autocheck='$uprojectnew[autocheck]'
					");
		updatecache('uprojects');
		adminlog(lang('addusergaltpro'),lang('addusergaltpro'));
		amessage('useraltproaddfin',axaction(6,'?entry=mprojects&action=mprojectsedit'));
	}
}elseif($action == 'uprojectdetail' && $gtid && $upid){
	!($ugidsarr = ugidsarr($gtid)) && amessage('confirmadduser');
	!($uproject = $uprojects[$upid]) && amessage('choosememchaaltpro');
	if(!submitcheck('buprojectdetail')){
		$ugidsarr = array(0 => lang('user0')) + $ugidsarr;
		tabheader(lang('ediusergaltpro'),"uprojectdetail","?entry=mprojects&action=uprojectdetail&gtid=$gtid&upid=$upid",2,0,1);
		trbasic(lang('projectname'),'uprojectnew[cname]',$uproject['cname']);
		trbasic(lang('sourceuser'),'uprojectnew[sugid]',makeoption($ugidsarr,$uproject['sugid']),'select');
		trbasic(lang('targetusergroup'),'uprojectnew[tugid]',makeoption($ugidsarr,$uproject['tugid']),'select');
		trbasic(lang('useraltautch'),'uprojectnew[autocheck]',$uproject['autocheck'],'radio');
		tabfooter('buprojectdetail');
		$submitstr = '';
		$submitstr .= makesubmitstr('uprojectnew[cname]',1,0,3,30);
		check_submit_func($submitstr);
		a_guide('uprojectdetail');
	}else{
		$uprojectnew['cname'] = trim(strip_tags($uprojectnew['cname']));
		if(!$uprojectnew['cname']) amessage('inpprocna',axaction(2,M_REFERER));
		if($uprojectnew['sugid'] == $uprojectnew['tugid']) amessage('souchatarchasam',axaction(2,M_REFERER));
		$uprojectnew['ename'] = $uprojectnew['sugid'].'_'.$uprojectnew['tugid'];
		$usedcnames = array();
		foreach($uprojects as $v) $usedcnames[] = $v['ename'];
		if(($uprojectnew['ename'] != $uproject['ename']) && in_array($uprojectnew['ename'],$usedcnames)) amessage('prorepdef',axaction(2,M_REFERER));
		$db->query("UPDATE {$tblprefix}uprojects SET 
					cname='$uprojectnew[cname]', 
					ename='$uprojectnew[ename]', 
					sugid='$uprojectnew[sugid]', 
					tugid='$uprojectnew[tugid]', 
					autocheck='$uprojectnew[autocheck]'
					WHERE upid='$upid'
					");
		updatecache('uprojects');
		adminlog(lang('modusealtpro'),lang('modusealtpro'));
		amessage('usealtpromodfin',axaction(6,'?entry=mprojects&action=mprojectsedit'));
	}
}elseif($action == 'mprojectdel' && $mpid){
	$db->query("DELETE FROM {$tblprefix}mprojects WHERE mpid='$mpid'");
	updatecache('mprojects');
	adminlog(lang('delmemchaaltpro'),lang('delmemchaaltpro'));
	amessage('memchanaltprodelfin','?entry=mprojects&action=mprojectsedit');
}elseif($action == 'uprojectdel' && $upid){
	$db->query("DELETE FROM {$tblprefix}uprojects WHERE upid='$upid'");
	updatecache('uprojects');
	adminlog(lang('delusealtpro'),lang('delusealtpro'));
	amessage('usealtprodelfin','?entry=mprojects&action=mprojectsedit');
}
?>
