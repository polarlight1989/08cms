<?php
!defined('M_COM') && exit('No Permission');
load_cache('cotypes,channels,currencys,permissions,inmurls,acatalogs');
//分析页面设置
$nimuid = empty($nimuid) ? 0 : $nimuid;
$u_checked = -1;
if($nimuid && $u_url = read_cache('inmurl',$nimuid)){
	$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
	$u_mtitle = @$u_url['mtitle'];
	$u_guide = @$u_url['guide'];
	$u_checked = $u_url['setting']['checked'];
	foreach(array('filters','lists',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_lists) && $u_lists = array('mname','check','adddate','edit',);
if(empty($u_tplname) || !empty($u_onlyview)){
	include_once M_ROOT."./include/parse.fun.php";
	include_once M_ROOT."./include/arcedit.cls.php";
	include_once M_ROOT."./include/commu.fun.php";
	
	$aid = empty($aid) ? 0 : max(0,intval($aid));
	$aedit = new cls_arcedit;
	$aedit->set_aid($aid);
	$aedit->basic_data(0);
	$channel = &$aedit->channel;
	if(!$aedit->aid || $aedit->archive['mid'] != $memberid) mcmessage('confchoosarchi');
	$commu = read_cache('commu',$channel['cuid']);
	
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$checked = isset($checked) ? $checked : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "a.aid='$aid'";
	$fromsql = "FROM {$tblprefix}answers cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";
	
	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= " AND cu.checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= " AND cu.checked='$u_checked'";
	
	//搜索关键词处理
	$keyword && $wheresql .= " AND (cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
	
	$filterstr = '';
	foreach(array('nimuid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	
	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($action.'arcsedit',"?action=inanswers&aid=$aid&page=$page");
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			if(empty($u_filters) || in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('adopt'),'0' => lang('noadopt'),'1' => lang('adopted'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			echo strbutton('bfilter','filter0').'</td></tr>';
			trhidden('nimuid',$nimuid);
			tabfooter();
		
		
			//列表区	
			tabheader((empty($u_mtitle) ? lang('answerlist') : $u_mtitle).'&nbsp; -&nbsp; '.lang('spare').':'.$aedit->archive['spare'].$currencys[$aedit->archive['crid']]['cname'],'','',9);
			$cy_arr = array(array(lang('questiontitle'),'item2'),lang('adopt'),lang('award'),lang('awarded'),);
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('adddate');
			if(in_array('edit',$u_lists)) $cy_arr[] = lang('edit');
			trcategory($cy_arr);
	
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);
	
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">$row[asubject]</a>";
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\"".(empty($row['checked']) ? '' : ' checked').">";
				$awardstr = "<input type=\"text\" name=\"currencynew[$row[cid]]\" value=\"\" size=\"4\">";
				$awardedstr = $row['currency'].($row['currency'] ? $currencys[$row['crid']]['cname'] : '');
				$mnamestr = $row['mname'];
				$adddatestr = date('Y-m-d',$row['ucreatedate']);
				$editstr = "<a href=\"?action=answer&cid=$row[cid]&amode=1\" onclick=\"return floatwin('open_answer',this)\">".lang('detail')."</a>";
	
				$itemstr .= "<tr><td class=\"item2\">$subjectstr</td><td class=\"item\" >$selectstr</td><td class=\"item\" >$awardstr</td><td class=\"item\" >$awardedstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"item\">$mnamestr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"item\">$adddatestr</td>\n";
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"item\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}
	
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $mrowpp, $page, "?action=inanswers&aid=$aid$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			echo '<br><br>'.strbutton('barcsedit','submit').'</form>';
			m_guide(@$u_guide);
		
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if($aedit->archive['closed'] || ($aedit->archive['finishdate'] && $aedit->archive['finishdate'] < $timestamp)) mcmessage('questionclose',M_REFERER);
		if(empty($currencynew)) mcmessage('chooseanswer',M_REFERER);
		$auser = new cls_userinfo;
		$sparenew = $aedit->archive['spare'];
		foreach($currencynew as $k =>$v){
			$v = max(0,intval($v));
			$v = $sparenew > $v ? $v : max($sparenew,$v);
			$sqlstr = "checked='".(empty($selectid[$k]) ? 0 : 1)."'";
			if($v){
				$row = $db->fetch_one("SELECT * FROM {$tblprefix}answers WHERE cid=$k");
				$auser->activeuser($row['mid']);
				$auser->updatecrids(array($row['crid'] => $v),1,'answer');
				$sqlstr .= ",currency=currency+$v";
				$sparenew -= $v; 
				$auser->init();
			}
			$db->query("UPDATE {$tblprefix}answers SET $sqlstr WHERE cid=$k");
		}
		unset($auser);
		$adopts = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}answers WHERE aid='$aid' AND checked=1");
		$aedit->updatefield('adopts',$adopts,'main');
		$aedit->updatefield('spare',$sparenew,'sub');
		$aedit->updatedb();
		mcmessage('answereditfinish',M_REFERER);
	}
}else include(M_ROOT.$u_tplname);
?>
