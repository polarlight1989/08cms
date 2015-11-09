<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('other') || amessage('no_apermission');
load_cache('grouptypes');
$url_type = 'pms';include 'urlsarr.inc.php';

if($action == 'batchpms'){
	if(!submitcheck('bbatchpms')){
		url_nav(lang('insitepm'),$urlsarr,'batch');

		tabheader(lang('acceptmemberfilter'),'batchpms','?entry=pms&action=batchpms');
		$enable_uc || trbasic(lang('pmtoids'),'pmnew[toids]');
		trbasic(lang('pmtonames'),'pmnew[tonames]');
		if(!$enable_uc){
			$limitarr = array('0' => lang('nolimitusergroup'),'1' => lang('handworkchoose'));
			foreach($grouptypes as $gtid => $grouptype){
				sourcemodule($grouptype['cname'].lang('limited'),
							"pmnew[limit$gtid]",
							$limitarr,
							'0',
							'1',
							"pmnew[ugids$gtid][]",
							ugidsarr($gtid),
							array()
							);
			}
		}
		tabfooter();
		tabheader(lang('pmcontentset'));
		trbasic(lang('pmtitle'),'pmnew[title]');
		trbasic(lang('pmcontent'),'pmnew[content]','','textarea');
		tabfooter('bbatchpms');
		a_guide('pmsbatch');
	}else{
		if(empty($pmnew['title']) || empty($pmnew['content'])){
			amessage('pmmiss','?entry=pms&action=batchpms');
		}
		if($enable_uc){
			require_once M_ROOT.'./include/ucenter/config.inc.php';
			require_once M_ROOT.'./uc_client/client.php';
			uc_pm_send(0,$pmnew['tonames'],$pmnew['title'],$pmnew['content'],1,0,1) ? amessage(lang('pmsendfin'),'?entry=pms&action=batchpms') : amessage(lang('pmsenderr'),M_REFERER);
		}
		$wheresql = '';
		if(!empty($pmnew['toids'])){
			$toids = array_filter(explode(',',$pmnew['toids']));
			$toids = mimplode($toids);
			$wheresql = empty($toids) ? "" : "mid IN ($toids)";
		}
		if(!empty($pmnew['tonames'])){
			$tonames = array_filter(explode(',',$pmnew['tonames']));
			$tonames = mimplode($tonames);
			$wheresql .= empty($tonames) ? "" : ((empty($wheresql) ? "" : " OR ")."mname IN ($tonames)");
		}
		!empty($wheresql) && ($wheresql = "(".$wheresql.")");
		foreach($grouptypes as $gtid => $grouptype){
			if(!empty($pmnew['limit'.$gtid]) && !empty($pmnew['ugids'.$gtid])){
				$ugids = mimplode($pmnew['ugids'.$gtid]);
				$fieldname = 'grouptype'.$gtid;
				$wheresql .= empty($ugids) ? "" : ((empty($wheresql) ? "" : " AND ")."$fieldname IN ($ugids)");
			}
		}
		$wheresql = empty($wheresql) ? "" : "WHERE $wheresql";
		$query = $db->query("SELECT mid FROM {$tblprefix}members $wheresql ORDER BY mid");
		while($user = $db->fetch_array($query)){
			//收信数量限制分析
			$db->query("INSERT INTO {$tblprefix}pms SET
						title = '$pmnew[title]',
						content = '$pmnew[content]',
						toid = '$user[mid]',
						fromid = '$memberid',
						fromuser = '".$curuser->info['mname']."',
						pmdate = '$timestamp'
						");
		}
		amessage('pmsendfin','?entry=pms&action=batchpms');
	}
}elseif($action == 'clearpms'){
	$enable_uc && amessage('goucpmadmin','',"<a href=\"$uc_api\" target=\"_blank\">".lang('gotopage').'</a>');
	if(!submitcheck('bclearpms')){
		url_nav(lang('insitepm'),$urlsarr,'clear');

		tabheader(lang('pmclearfilter'),'clearpms','?entry=pms&action=clearpms');
		trbasic(lang('pmfromids'),'pmnew[fromids]');
		trbasic(lang('mnamestxt'),'pmnew[fromnames]');
		trbasic(lang('onlyclearreadpm'),'pmnew[viewed]','0','radio');
		trbasic(lang('indays'),'pmnew[days]');
		tabfooter('bclearpms');
		a_guide('pmsclear');
	}else{
		$wheresql = '';
		if(!empty($pmnew['fromids'])){
			$fromids = array_filter(explode(',',$pmnew['fromids']));
			$fromids = mimplode($fromids);
			$wheresql = empty($fromids) ? "" : "fromid IN ($fromids)";
		}
		if(!empty($pmnew['fromnames'])){
			$fromnames = array_filter(explode(',',$pmnew['fromnames']));
			$fromnames = mimplode($fromnames);
			$wheresql .= empty($fromnames) ? "" : ((empty($wheresql) ? "" : " OR ")."fromuser IN ($fromnames)");
		}
		!empty($wheresql) && ($wheresql = "(".$wheresql.")");
		if(!empty($pmnew['viewed'])){
			$wheresql .= (empty($wheresql) ? "" : " AND ")."viewed='1'";
		}
		if(!empty($pmnew['days'])){
			$wheresql .= (empty($wheresql) ? "" : " AND ")."pmdate<".($timestamp-86400*$pmnew['days']);
		}
		$wheresql = empty($wheresql) ? "" : "WHERE $wheresql";
		$db->query("DELETE FROM {$tblprefix}pms $wheresql",'UNBUFFERED');
		amessage('pmclearfin','?entry=pms&action=clearpms');
	}
}
?>