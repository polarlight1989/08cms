<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('commu') || amessage('no_apermission');
load_cache('channels,currencys,commus');
load_cache('catalogs',$sid);
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/arcedit.cls.php";
if($action == 'answersedit'){
	//分析页面设置
	$nauid = empty($nauid) ? 0 : $nauid;
	$u_checked = -1;
	if($nauid && $u_url = read_cache('aurl',$nauid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$u_checked = $u_url['setting']['checked'];
		foreach(array('cuids','chids','filters','lists',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('mname','catalog','channel','commu','check','edit',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$caid = empty($caid) ? 0 : $caid;
		$page = !empty($page) ? max(1, intval($page)) : 1;
		submitcheck('bfilter') && $page = 1;
		$viewdetail = empty($viewdetail) ? 0 : 1;
		$cuid = empty($cuid) ? 0 : $cuid;
		$chid = empty($chid) ? 0 : max(0,intval($chid));
		$checked = isset($checked) ? $checked : '-1';
		$keyword = empty($keyword) ? '' : $keyword;
		$indays = empty($indays) ? 0 : max(0,intval($indays));
		$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
		$wheresql = "a.sid='$sid'";
		$fromsql = "FROM {$tblprefix}answers cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";
	
		//栏目范围
		$caids = array(-1);
		if(!empty($caid)) $caids = cnsonids($caid,$catalogs);
		if(!in_array(-1,$a_caids)) $caids = in_array(-1,$caids) ? $a_caids : array_intersect($caids,$a_caids);
		if(!$caids) $no_list = true;
		elseif(!in_array(-1,$caids)) $wheresql .= " AND a.caid ".multi_str($caids);
	
		//模型范围
		if($chid){
			if(!empty($u_chids) && !in_array($chid,$u_chids)) $no_list = true;
			else $wheresql .= " AND a.chid='$chid'";
		}elseif(!empty($u_chids)) $wheresql .= " AND a.chid ".multi_str($u_chids);
	
		//交互项目范围
		if($cuid){
			if(!empty($u_cuids) && !in_array($cuid,$u_cuids)) $no_list = true;
			else $wheresql .= " AND cu.cuid='$cuid'";
		}elseif(!empty($u_cuids)) $wheresql .= " AND cu.cuid ".multi_str($u_cuids);
	
		//审核状态范围
		if($checked != -1){
			if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
			else $wheresql .= " AND cu.checked='$checked'";
		}elseif($u_checked != -1) $wheresql .= " AND cu.checked='$u_checked'";
	
		//搜索关键词处理
		$keyword && $wheresql .= " AND (cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
	
		$indays && $wheresql .= " AND cu.createdate>'".($timestamp - 86400 * $indays)."'";
		$outdays && $wheresql .= " AND cu.createdate<'".($timestamp - 86400 * $outdays)."'";
	
		$filterstr = '';
		foreach(array('nauid','viewdetail','caid','cuid','chid','keyword','indays','outdays',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
		foreach(array('checked',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	
		$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
		if(!submitcheck('barcsedit')){
			if(empty($u_tplname)){
				echo form_str($actionid.'arcsedit',"?entry=answers&action=answersedit&page=$page$param_suffix");
				//搜索区块
				tabheader_e();
				echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
				//关键词固定显示
				echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
				//合辑类型搜索
				if(empty($u_filters) || in_array('commu',$u_filters)){
					$cuidsarr = array('0' => lang('all_cuitem'));
					foreach($commus as $k => $v) if($v['cclass'] == 'answer') $cuidsarr[$k] = $v['cname'];
					echo "<select style=\"vertical-align: middle;\" name=\"cuid\">".makeoption($cuidsarr,$cuid)."</select>&nbsp; ";
				}
				//审核状态
				if(empty($u_filters) || in_array('check',$u_filters)){
					$checkedarr = array('-1' => lang('nolimit').lang('adopt'),'0' => lang('noadopt'),'1' => lang('adopted'));
					echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
				}
				echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">".viewcheck('viewdetail',$viewdetail,$actionid.'tbodyfilter');
				echo "</td></tr>";
	
	
				//某些固定页面参数
				trhidden('caid',$caid);
				trhidden('nauid',$nauid);
	
				//隐藏区块
				echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
				//模型搜索
				if(empty($u_filters) || in_array('channel',$u_filters)){
					$chidsarr = array('0' => lang('all_channel')) + chidsarr();
					trbasic(lang('achannel'),"chid",makeoption($chidsarr,$chid),'select');
				}
				//日期筛选
				if(empty($u_filters) || in_array('date',$u_filters)){
					trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
				}
				echo "</tbody>";
				tabfooter();
		
		
				//列表区	
				tabheader(lang('answer_list')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);
				$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('title'),'txtL'),);
				if(in_array('commu',$u_lists)) $cy_arr[] = lang('type');
				if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
				if(in_array('check',$u_lists)) $cy_arr[] = lang('adopt');
				if(in_array('award',$u_lists)) $cy_arr[] = lang('award');
				if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
				if(in_array('channel',$u_lists)) $cy_arr[] = lang('achannel');
				if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_date');
				if(in_array('edit',$u_lists)) $cy_arr[] = lang('edit');
				trcategory($cy_arr);
	
				$pagetmp = $page;
				do{
					$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
					$pagetmp--;
				} while(!$db->num_rows($query) && $pagetmp);
	
				$itemstr = '';
				while($row = $db->fetch_array($query)){
					$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
					$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">$row[asubject]</a>";
					$mnamestr = $row['mname'];
					$awardstr = $row['currency'].($row['currency'] ? $currencys[$row['crid']]['cname'] : '');
					$catalogstr = @$catalogs[$row['caid']]['title'];
					$commustr = @$commus[$row['cuid']]['cname'];
					$channelstr = @$channels[$row['chid']]['cname'];
					$checkstr = $row['checked'] ? 'Y' : '-';
					$adddatestr = date('Y-m-d',$row['ucreatedate']);
					$editstr = "<a href=\"?entry=answers&action=answerdetail&cid=$row[cid]$param_suffix\" onclick=\"return floatwin('open_answersedit',this)\">".lang('detail')."</a>";
	
					$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
					if(in_array('commu',$u_lists)) $itemstr .= "<td class=\"txtC\">$commustr</td>\n";
					if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
					if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$checkstr</td>\n";
					if(in_array('award',$u_lists)) $itemstr .= "<td class=\"txtC\">$awardstr</td>\n";
					if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
					if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
					if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
					if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
					$itemstr .= "</tr>\n";
				}
	
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$multi = multi($counts, $atpp, $page, "?entry=answers&action=answersedit$param_suffix$filterstr");
				echo $itemstr;
				tabfooter();
				echo $multi;
				echo '<br><br>'.strbutton('barcsedit',lang('delete')).'</form>';
				a_guide('answersedit');
			
			}else include(M_ROOT.$u_tplname);
			
		}else{
			if(empty($selectid) && empty($select_all)) amessage('pchoosecontent',axaction(1,M_REFERER));
			$dealstr = $parastr = '';
			if(!empty($select_all)){
				$selectid = array();
				$npage = empty($npage) ? 1 : $npage;
				if(empty($pages)){
					$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
					$pages = @ceil($counts / $atpp);
				}
				if($npage <= $pages){
					$fromstr = empty($fromid) ? "" : "cu.cid<$fromid";
					$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
					$query = $db->query("SELECT cu.cid $fromsql $nwheresql ORDER BY cu.cid DESC LIMIT 0,$atpp");
					while($item = $db->fetch_array($query)) $selectid[] = $item['cid'];
				}
			}
	
			$aedit = new cls_arcedit;
			$actuser = new cls_userinfo;
			$query = $db->query("SELECT aid,mid,checked FROM {$tblprefix}answers WHERE cid ".multi_str($selectid));
			while($row = $db->fetch_array($query)){
				$aedit->set_aid($row['aid']);
				$row['checked'] && $aedit->arc_nums('adopts',-1,0);
				$aedit->arc_nums('answers',-1,1);
				$aedit->init();
				$actuser->activeuser($row['mid']);
				$actuser->basedeal('answer',0,1,1);
				$actuser->init();
			}
			$db->query("DELETE FROM {$tblprefix}answers WHERE cid ".multi_str($selectid),'UNBUFFERED');
	
			if(!empty($select_all)){
				$npage ++;
				if($npage <= $pages){
					$fromid = min($selectid);
					$transtr = '';
					$transtr .= "&select_all=1";
					$transtr .= "&pages=$pages";
					$transtr .= "&npage=$npage";
					$transtr .= "&barcsedit=1";
					$transtr .= "&fromid=$fromid";
					amessage('operating',"?entry=answers&action=answersedit&page=$page$param_suffix$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=answers&action=answersedit&page=$page$param_suffix$filterstr\">",'</a>');
				}
			}
			adminlog(lang('answersetsucceed'),lang('answersetsucceed'));
			amessage('contentsetsucceed',"?entry=answers&action=answersedit$param_suffix&page=$page$filterstr");
		}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'answerdetail'){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(!$answer = $db->fetch_one("SELECT * FROM {$tblprefix}answers WHERE cid='$cid'")) amessage('chooseanswer');
	$commu = read_cache('commu',$answer['cuid']);
	$aedit = new cls_arcedit;
	$aedit->set_aid($answer['aid']);
	$aedit->detail_data();
	if(!submitcheck('banswerdetail')){
		tabheader(lang('edit_answer'),'answerdetail',"?entry=answers&action=answerdetail&cid=$cid$param_suffix$forwardstr");
		trbasic(lang('question_state'),'',($aedit->archive['closed'] || $aedit->archive['finishdate'] < $timestamp) ? lang('question_closed') : lang('question_noclose'),'');
		trbasic(lang('look_question'),'',"<a href=\"".view_arcurl($aedit->archive)."\" target=\"_blank\">>>".mhtmlspecialchars($aedit->archive['subject'])."</a>",'');
		trbasic(lang('answer_content'),'answernew[answer]',br2nl($answer['answer']),'btextarea');
		tabfooter('banswerdetail');
		a_guide('answerdetail');
	}else{
		($aedit->archive['closed'] || $aedit->archive['finishdate'] < $timestamp) && amessage('questionclosed',axaction(2,M_REFERER));
		$answernew['answer'] = empty($answernew['answer']) ? '' : trim($answernew['answer']);
		empty($answernew['answer']) && amessage('inputanswercontent',axaction(2,M_REFERER));
		(!empty($commu['setting']['minlength']) && strlen($answernew['answer']) < $commu['setting']['minlength']) && amessage('answerovermin',axaction(2,M_REFERER));
		!empty($commu['setting']['maxlength']) && $answernew['answer'] = cutstr($answernew['answer'],$commu['setting']['maxlength']);
		$answernew['answer'] = mnl2br(mhtmlspecialchars($answernew['answer']));	
		$db->query("UPDATE {$tblprefix}answers SET answer='$answernew[answer]' WHERE cid='$cid'");
		adminlog(lang('answer_content_edit'),lang('edit_answer_content'));
		amessage('updatesucceed',axaction(10,$forward),$commu['cname']);
	}
}
?>