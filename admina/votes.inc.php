<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('other') || amessage('no_apermission');
load_cache('vcatalogs');
$url_type = 'vote';include 'urlsarr.inc.php';
if($action == 'voteadd'){
	url_nav(lang('voteadmin'),$urlsarr,'add');
	!vcaidsarr() && amessage('pleaddvotcoc');
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.urlencode($forward);
	if(!submitcheck('bvoteadd')){
		tabheader(lang('addvote'),'voteadd',"?entry=votes&action=voteadd$forwardstr");
		trbasic(lang('votetitle'),'votenew[subject]');
		trbasic(lang('votecoc'),'votenew[caid]',makeoption(vcaidsarr()),'select');
		trbasic(lang('voteexpl'),'votenew[content]','','textarea');
		trbasic(lang('endtime'),'votenew[enddate]','','calendar');
		trbasic(lang('weathermsel'),'votenew[ismulti]','','radio');
		trbasic(lang('fornouservote'),'votenew[onlyuser]','','radio');
		trbasic(lang('limitedrevo'),'votenew[norepeat]','','radio');
		trbasic(lang('repvotetimemin'),'votenew[timelimit]');
		tabfooter('bvoteadd',lang('add'));
		a_guide('voteadd');
	}else{
		empty($votenew['subject']) && amessage('datamissing','?entry=votes&action=voteadd$forwardstr');
		$votenew['timelimit'] = max(0,intval($votenew['timelimit']));
		$votenew['enddate'] = empty($votenew['enddate']) ? 0 : strtotime($votenew['enddate']);
		$db->query("INSERT INTO {$tblprefix}votes SET 
					subject='$votenew[subject]',
					caid='$votenew[caid]',
					content='$votenew[content]',
					enddate='$votenew[enddate]',
					ismulti='$votenew[ismulti]',
					onlyuser='$votenew[onlyuser]',
					norepeat='$votenew[norepeat]',
					timelimit='$votenew[timelimit]',
					mid='$memberid',
					mname='".$curuser->info['mname']."',
					createdate='$timestamp'
					");
		amessage('voteaddfinish',$forward);
	}
}elseif($action == 'votesedit'){
	url_nav(lang('voteadmin'),$urlsarr,'admin');
	!vcaidsarr() && amessage('pleaddvotcoc');
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? '' : $viewdetail;
	$caid = empty($caid) ? '0' : $caid;
	$checked = isset($checked) ? $checked : '-1';
	$subject = empty($subject) ? '' : $subject;
	$overdated = !isset($overdated) ? '-1' : $overdated;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
	$filterstr = '';
	foreach(array('viewdetail','caid','overdated','checked','subject','indays','outdays') as $k){
		$filterstr .= "&$k=".urlencode($$k);
	}
	if(!submitcheck('barcsedit')){
		$wheresql = '';
		$caid && $wheresql .= ($wheresql ? " AND " : "")."caid = '$caid'";
		if($checked != '-1') $wheresql .= ($wheresql ? " AND " : "")."checked='$checked'";
		if($overdated != '-1') $wheresql .= ($wheresql ? " AND " : "").($overdated ? "enddate>0 AND enddate<$timestamp" : "(endate=0 OR enddate>$timestamp)");
		$subject && $wheresql .= ($wheresql ? " AND " : "")."subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($subject,'%_'))."%'";
		$indays && $wheresql .= ($wheresql ? " AND " : "")."createdate>'".($timestamp - 86400 * $indays)."'";
		$outdays && $wheresql .= ($wheresql ? " AND " : "")."createdate<'".($timestamp - 86400 * $outdays)."'";
		$wheresql = empty($wheresql) ? '' : "WHERE ".$wheresql;

		$caidsarr = array('0' => lang('allcoclass')) + vcaidsarr();
		$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheckvote'),'1' => lang('checkvote'));
		$overdatedarr = array('-1' => lang('nolimit'),'0' => lang('noovervote'),'1' => lang('noovervote'));
		tabheader(lang('filvote').viewcheck('viewdetail',$viewdetail,'tbodyfilter').'&nbsp; &nbsp; '.strbutton('bfilter','filter0'),'arcsedit',"?entry=votes&action=votesedit&page=$page");
		echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
		trbasic(lang('belongcocl'),'caid',makeoption($caidsarr,$caid),'select');
		trbasic(lang('ischeckvo'),'',makeradio('checked',$checkedarr,$checked),'');
		trbasic(lang('isovevo'),'overdated',makeoption($overdatedarr,$overdated),'select');
		trbasic(lang('votetitle'),'subject',$subject);
		trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
		echo "</tbody>";
		tabfooter();


		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * FROM {$tblprefix}votes $wheresql ORDER BY vieworder,vid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemvote = '';
		while($vote = $db->fetch_array($query)) {
			$vid = $vote['vid'];
			$vote['enddate'] = empty($vote['enddate']) ? '-' : date("$dateformat", $vote['enddate']);
			$vote['ismulti'] = empty($vote['ismulti']) ? lang('schoise') : lang('mchoise');
			$vote['subject'] = mhtmlspecialchars($vote['subject']);
			$itemvote .= "<tr class=\"txt\"><td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$vid]\" value=\"$vid\">\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"votesnew[$vid][checked]\" value=\"1\"".(!empty($vote['checked']) ? ' checked' : '')."></td>\n".
				"<td class=\"txtL\">$vote[subject]</td>\n".
				"<td class=\"txtC\">".$vcatalogs[$vote['caid']]['title']."</td>\n".
				"<td class=\"txtC w40\">$vote[ismulti]</td>\n".
				"<td class=\"txtC 1080\">$vote[enddate]</td>\n".
				"<td class=\"txtC w40\"><input type=\"text\" name=\"votesnew[$vid][vieworder]\" value=\"$vote[vieworder]\" size=\"3\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=votes&action=votedetail&vid=$vid\">".lang('detail')."</a></td></tr>\n";
		}
		$votecount = $db->result_one("SELECT count(*) FROM {$tblprefix}votes $wheresql");
		$multi = multi($votecount, $atpp, $page, "?entry=votes&action=votesedit$filterstr");

		tabheader(lang('votmanlis')."&nbsp;&nbsp;&nbsp;&nbsp;[<a href=\"?entry=votes&action=voteadd\">".lang('add')."</a>]",'','',8);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),lang('check'),lang('votetitle'),lang('coclass'),lang('type'),lang('enddate'),lang('order'),lang('edit')));
		echo $itemvote;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"barcsedit\" value=\"".lang('submit')."\">".
			"</form>\n";
	}else{
		if(!empty($delete)){
			foreach($delete as $vid){
				$db->query("DELETE FROM {$tblprefix}voptions WHERE vid='$vid'");
				$db->query("DELETE FROM {$tblprefix}votes WHERE vid='$vid'");
				unset($votesnew[$vid]);
			}
		}
		if(!empty($votesnew)){
			foreach($votesnew as $vid => $votenew){
				$votenew['checked'] = empty($votenew['checked']) ? 0 : $votenew['checked'];
				$votenew['vieworder'] = max(0,intval($votenew['vieworder']));
				$db->query("UPDATE {$tblprefix}votes SET
					checked='$votenew[checked]',
					vieworder='$votenew[vieworder]'
					WHERE vid='$vid'");
			}
		}
		amessage('votmodfin', "?entry=votes&action=votesedit&page=$page$filterstr");
	}
}elseif($action == 'votedetail' && $vid){
	url_nav(lang('voteadmin'),$urlsarr,'admin');
	!vcaidsarr() && amessage('pleaddvotcoc');
	if(!($vote = $db->fetch_one("SELECT * FROM {$tblprefix}votes WHERE vid=".$vid))) amessage('poivotenoe', "?entry=votes&action=votesedit");
	$voptions = array();
	$query = $db->query("SELECT * FROM {$tblprefix}voptions WHERE vid=".$vid." ORDER BY vieworder,vopid");
	while($voption = $db->fetch_array($query)){
		$voptions[$voption['vopid']] = $voption;
	}
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.urlencode($forward);
	if(!submitcheck('bvotedetail') && !submitcheck('bvoptionadd')){
		tabheader(lang('editvote'),'votedetail',"?entry=votes&action=votedetail&vid=$vid$forwardstr");
		trbasic(lang('votetitle'),'votenew[subject]',$vote['subject']);
		trbasic(lang('votecoc'),'votenew[caid]',makeoption(vcaidsarr(),$vote['caid']),'select');
		trbasic(lang('voteexpl'),'votenew[content]',$vote['content'],'textarea');
		trbasic(lang('voteenddate'),'votenew[enddate]',(!empty($vote['enddate']) ? date('Y-n-j', $vote['enddate']) : ''),'calendar');
		trbasic(lang('weathermsel'),'votenew[ismulti]',$vote['ismulti'],'radio');
		trbasic(lang('fornouservote'),'votenew[onlyuser]',$vote['onlyuser'],'radio');
		trbasic(lang('cannotrepevote'),'votenew[norepeat]',$vote['norepeat'],'radio');
		trbasic(lang('reptimintmin'),'votenew[timelimit]',$vote['timelimit']);
		tabfooter();
		tabheader(lang('voteoption'),'','',4);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),lang('optiontitle'),lang('votenum'),lang('order')));
		foreach($voptions as $vopid => $voption){
			echo "<tr class=\"txt\"><td class=\"txtC w50\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$vopid]\" value=\"$vopid\">\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"voptionsnew[$vopid][title]\" value=\"$voption[title]\" size=\"40\"></td>\n".
				"<td class=\"txtC w150\"><input type=\"text\" name=\"voptionsnew[$vopid][votenum]\" value=\"$voption[votenum]\" size=\"10\"></td>\n".
				"<td class=\"txtC w50\"><input type=\"text\" name=\"voptionsnew[$vopid][vieworder]\" value=\"$voption[vieworder]\" size=\"3\"></td></tr>\n";
		}
		tabfooter('bvotedetail');
		tabheader(lang('addvoteopt'),'voptionadd',"?entry=votes&action=votedetail&vid=$vid$forwardstr");
		trbasic(lang('optiontitle'),'voptionadd[title]');
		trbasic(lang('optioorder'),'voptionadd[vieworder]');
		tabfooter('bvoptionadd',lang('add'));
		a_guide('votedetail');
	}elseif(submitcheck('bvotedetail')){
		$votenew['timelimit'] = max(0,intval($votenew['timelimit']));
		$votenew['enddate'] = !empty($votenew['enddate']) ? strtotime($votenew['enddate']) : 0;
		$votenew['subject'] = !empty($votenew['subject']) ? $votenew['subject'] : $vote['subject'];
		$db->query("UPDATE {$tblprefix}votes SET 
					subject='$votenew[subject]',
					caid='$votenew[caid]',
					enddate='$votenew[enddate]',
					content='$votenew[content]',
					ismulti='$votenew[ismulti]',
					onlyuser='$votenew[onlyuser]',
					norepeat='$votenew[norepeat]',
					timelimit='$votenew[timelimit]'
					WHERE vid='$vid'");
		if(!empty($delete)){
			foreach($delete as $vopid){
				$db->query("DELETE FROM {$tblprefix}voptions WHERE vopid='$vopid'");
				unset($voptionsnew[$vopid]);
			}
		}
		if(!empty($voptionsnew)){
			foreach($voptionsnew as $vopid => $voptionnew){
				$voptionnew['title'] = !empty($voptionnew['title']) ? $voptionnew['title'] : $voptions[$vopid]['title'];
				$voptionnew['vieworder'] = max(0,intval($voptionnew['vieworder']));
				$voptionnew['votenum'] = max(0,intval($voptionnew['votenum']));
				$db->query("UPDATE {$tblprefix}voptions SET
					title='$voptionnew[title]',
					vieworder='$voptionnew[vieworder]',
					votenum='$voptionnew[votenum]'
					WHERE vopid='$vopid'");
			}
			$counts = $db->result_one("SELECT SUM(votenum) FROM {$tblprefix}voptions WHERE vid='$vid'");
			$db->query("UPDATE {$tblprefix}votes SET totalnum='$counts' WHERE vid='$vid'");
		}
		amessage('voteedifin',$forward);
	}elseif(submitcheck('bvoptionadd')){
		empty($voptionadd['title']) && amessage('pleinpopttit',"?entry=votes&action=votedetail&vid=$vid$forwardstr");
		$voptionadd['vieworder'] = max(0,intval($voptionadd['vieworder']));
		$db->query("INSERT INTO {$tblprefix}voptions SET 
					title='$voptionadd[title]',
					vieworder='$voptionadd[vieworder]',
					vid=$vid
					");
		amessage('optaddfin',"?entry=votes&action=votedetail&vid=$vid$forwardstr");
	}
}
?>