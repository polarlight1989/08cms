<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('commu') || amessage('no_apermission');
include_once M_ROOT."./include/arcedit.cls.php";
load_cache('channels,commus');
load_cache('catalogs',$sid);
$chids = array();
foreach($channels as $chid => $channel){
	if(@$commus[$channel['cuid']]['cclass'] == 'answer'){
		$chids[] = $chid;
		$commu = read_cache('commu',$channel['cuid']);
		if(!empty($commu['setting']['nota'])) $notaenabled = 1;
	}
}
empty($chids) && amessage('undefanswerchannel');
empty($notaenabled) && amessage('notaclose');
$page = !empty($page) ? max(1, intval($page)) : 1;
submitcheck('bfilter') && $page = 1;

if($action == 'awnotasedit'){
	//分析页面设置
	$nauid = empty($nauid) ? 0 : $nauid;
	if($nauid && $aurl = read_cache('aurl',$nauid)){
		$u_tplname = $aurl['tplname'];
		foreach(array('lists',) as $var) if($aurl['setting'][$var] !== '') ${'u_'.$var} = explode(',',$aurl['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('mname','appeals','award','spare','answers','adopts','admin',);

	$caid = empty($caid) ? 0 : $caid;
	$keyword = empty($keyword) ? '' : $keyword;
	$appealed = isset($appealed) ? $appealed : '-1';

	$filterstr = '';
	foreach(array('caid','keyword','nauid',) as $k) $$k && $filterstr .= "&$k=".rawurlencode($$k);
	foreach(array('appealed',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;

	$wheresql = "WHERE a.sid=$sid AND a.checked=1 AND a.chid ".multi_str($chids)." AND s.notaclosed=0 AND s.appealdate<$timestamp";

	//栏目范围
	$caids = array(-1);
	if(!empty($caid)){
		$caids = array($caid);
		$tempids = array();
		$tempids = son_ids($catalogs,$caid,$tempids);
		$caids = array_merge($caids,$tempids);
	}
	if(!in_array(-1,$a_caids)) $caids = in_array(-1,$caids) ? $a_caids : array_intersect($caids,$a_caids);
	if(!$caids) $no_list = true;
	elseif(!in_array(-1,$caids)) $wheresql .= " AND a.caid ".multi_str($caids);

	if($appealed != -1) $wheresql .= " AND s.appeals".($appealed ? '!' : '')."='0'";

	$fromsql = "FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid";
	//搜索关键词处理
	$keyword && $wheresql .= " AND (a.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
	
	!empty($no_list) && $wheresql = "WHERE 1=0";
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'arcsedit',"?entry=awnotas&action=awnotasedit$param_suffix&page=$page");
			//搜索区块
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			$appealedarr = array('-1' => lang('nolimit').lang('appeal'),'0' => lang('noappeal'),'1' => lang('appealed'));
			echo "<select style=\"vertical-align: middle;\" name=\"appealed\">".makeoption($appealedarr,$appealed)."</select>&nbsp; ";
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";
			//某些固定页面参数
			trhidden('caid',$caid);
			trhidden('nauid',$nauid);
			tabfooter();
			//列表区	
			tabheader(lang('nota_item_list')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('appeals',$u_lists)) $cy_arr[] = lang('appeal');
			if(in_array('award',$u_lists)) $cy_arr[] = lang('award');
			if(in_array('spare',$u_lists)) $cy_arr[] = lang('spare');
			if(in_array('answers',$u_lists)) $cy_arr[] = lang('answer0');
			if(in_array('adopts',$u_lists)) $cy_arr[] = lang('adopt');
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('achannel');
			if(in_array('admin',$u_lists)) $cy_arr[] = lang('admin');
			trcategory($cy_arr);

			$pagetmp = $page;
			do{
				$query = $db->query("SELECT a.*,s.* $fromsql $wheresql ORDER BY a.aid ASC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\">";
				$row['arcurl'] = view_arcurl($row);
				$subjectstr = "<a href=$row[arcurl] target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
				$mnamestr = $row['mname'];
				$awardstr = $row['currency'];
				$sparestr = $row['spare'];
				$appealstr = $row['appeals'];
				$answerstr = $row['answers'];
				$adoptstr = $row['adopts'];
				$catalogstr = @$catalogs[$row['caid']]['title'];
				$commustr = @$commus[$row['cuid']]['cname'];
				$channelstr = @$channels[$row['chid']]['cname'];
				$adminstr = "<a href=\"?entry=awnotas&action=awnotadetail&aid=$row[aid]$param_suffix\" onclick=\"return floatwin('open_awnotasedit',this)\">".lang('admin')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('appeals',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$appealstr</td>\n";
				if(in_array('award',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$awardstr</td>\n";
				if(in_array('spare',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$sparestr</td>\n";
				if(in_array('answers',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$answerstr</td>\n";
				if(in_array('adopts',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$adoptstr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
				if(in_array('admin',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$adminstr</td>\n";;
				$itemstr .= "</tr>\n";
			}
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=awnotas&action=awnotasedit$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			echo '<br><br>'.strbutton('barcsedit',lang('nota_checkout')).'</form>';
		}else @include(M_ROOT."./adminp/admina/$u_tplname");
	}else{
		if(empty($selectid) && empty($select_all)){
			amessage('selectnotaitem',M_REFERER);
		}
		if(!empty($select_all)){
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$pages = @ceil($counts / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "a.aid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT a.aid $fromsql $nwheresql ORDER BY a.aid ASC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)){
					$selectid[] = $item['aid'];
				}
			}
		}
		
		$auser = new cls_userinfo;
		$aids = array();	
		$query = $db->query("SELECT a.*,s.* $fromsql WHERE a.aid ".multi_str($selectid));
		while($row = $db->fetch_array($query)){
			$commu = read_cache('commu',$channels[$row['chid']]['cuid']);
			if(!empty($commu['setting']['nota'])){
				$aid = $row['aid'];
				$query1 = $db->query("SELECT * FROM {$tblprefix}answers WHERE aid='$aid' AND currency>0");
				while($row1 = $db->fetch_array($query1)){
					$auser->activeuser($row1['mid']);
					$auser->updatecrids(array($row1['crid'] => $row1['currency']),1,lang('answer_reward'));
					$auser->init();
				}
				$auser->activeuser($row['mid']);
				if(!empty($commu['setting']['credit'])){
					$auser->sub_data();
					$auser->updatefield('credits',$auser->info['credits'] + ($row['currency'] - $row['spare']) * $commu['setting']['credit'],'sub');
				}
				$auser->updatecrids(array($row['crid'] => $row['spare']),1,lang('answer_reward'));
				$auser->init();
				$aids[] = $aid;
			}
		}
		unset($auser,$row,$row1,$commu);
		if(!empty($aids)){
			$db->query("DELETE FROM {$tblprefix}notaanswer WHERE aid ".multi_str($aids),'SILENT');
			$db->query("UPDATE {$tblprefix}answers SET end=1,appeal=0 WHERE aid ".multi_str($aids),'SILENT');
			$db->query("UPDATE {$tblprefix}archives_sub SET spare=0,appeals=0,notaclosed=1 WHERE aid ".multi_str($aids),'SILENT');
		}
		
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
				amessage('operating',"?entry=awnotas&action=awnotasedit$param_suffix&page=$page$filterstr$transtr",$pages,$npage,"<a href=\"?entry=awnotas&action=awnotasedit$param_suffix&page=$page$filterstr\">",'</a>');
			}
		}
		adminlog(lang('nota_admin'),lang('nota_list_aoperate'));
		amessage('notafinish',"?entry=awnotas&action=awnotasedit$param_suffix&page=$page$filterstr");
	}
}
elseif($action == 'awnotadetail' && $aid){
	empty($aid) && amessage('choosenotaitem','history.go(-1)');
	$forward = empty($forward) ? M_REFERER : $forward;
	$aedit = new cls_arcedit;
	$aedit->set_aid($aid);
	$aedit->basic_data();
	empty($aid) && amessage('choosenotaitem');
	if(!submitcheck('bawnotadetail')){
		tabheader(lang('nota_item_base_msg'),'answerslist',"?entry=awnotas&action=awnotadetail&aid=$aid$param_suffix&forward=".urlencode($forward));
		$sourcestr = "<a href=\"".view_arcurl($aedit->archive)."\" target=\"blank\">>>".lang('source')."</a>";
		$recordstr = "<a href=\"?entry=awnotas&action=notarecord&aid=$aid$param_suffix\">>>".lang('alter_record')."</a>";
		trbasic(lang('question_title_state'),'',$aedit->archive['subject'].'&nbsp;&nbsp;('.(empty($aedit->archive['closed']) ? lang('noclose') : lang('closed')).')','');
		trbasic(lang('question_det_content'),'',$sourcestr."&nbsp;&nbsp;&nbsp;".$recordstr,'');
		trbasic(lang('reward_spare_appeal'),'',$aedit->archive['currency'].'&nbsp;/&nbsp;'.$aedit->archive['spare'].'&nbsp;/&nbsp;'.$aedit->archive['appeals'],'');
		trbasic(lang('question_adddate'),'',date("$dateformat $timeformat", $aedit->archive['createdate']),'');
		trbasic(lang('answer_enddate'),'',date("$dateformat $timeformat", $aedit->archive['finishdate']),'');
		trbasic(lang('appeal_enddate'),'',date("$dateformat $timeformat", $aedit->archive['appealdate']),'');
		trbasic(lang('nota_checkout'),'archivenew[notaclosed]',$aedit->archive['notaclosed'],'radio');
		tabfooter();

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * FROM {$tblprefix}answers WHERE aid=$aid ORDER BY cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($item = $db->fetch_array($query)){
			$cid = $item['cid'];
			$item['answer'] = cutstr(br2nl($item['answer']),50);
			$arcurl = view_arcurl($aedit->archive);
			$createdate = date("$dateformat", $item['createdate']);
			$itemstr .= "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"itemsnew[$cid][checked]\" value=\"1\"".(empty($item['checked']) ? '' : ' checked')."></td>\n".
				"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"itemsnew[$cid][currency]\" value=\"$item[currency]\"></td>\n".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"itemsnew[$cid][appeal]\" value=\"1\"".(empty($item['appeal']) ? '' : ' checked')."></td>\n".
				"<td class=\"txtL\">$item[answer]</td>\n".
				"<td class=\"txtC w60\"><a href=\"?entry=awnotas&action=notarecord&aid=$aid&cid=$cid$param_suffix\">".lang('look')."</a></td>\n".
				"<td class=\"txtC w80\">$item[mname]</td>\n".
				"<td class=\"txtC w60\">$createdate</td></tr>\n";
		}
		$itemcount = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}answers WHERE aid=$aid");
		$multi = multi($itemcount,$atpp,$page,"?entry=awnotas&action=awnotadetail&aid=$aid&forward=".urlencode($forward));
		tabheader(lang('answer_nota_manager'),'','','9');
		trcategory(array(lang('adopt'),lang('award'),lang('appeal'),lang('answer0'),lang('alter_record'),lang('member'),lang('add_date')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"bawnotadetail\" value=\"".lang('submit')."\"></form>";
		a_guide('awnotadetail');
	}else{
		$sql_arc = $sql_arcs = $sql_an = '';
		$adoptsnew = $aedit->archive['adopts'];
		$sparenew = $aedit->archive['spare'];
		if(!empty($itemsnew)){
			$query = $db->query("SELECT * FROM {$tblprefix}answers WHERE aid=$aid AND cid ".multi_str(array_keys($itemsnew)));
			while($item = $db->fetch_array($query)){
				$items[$item['cid']] = $item;
				$sparenew += $item['currency'];//将会重新分配积分
			}
			foreach($itemsnew as $cid => $itemnew){
				$sql_asw = '';
				$itemnew['checked'] = empty($itemnew['checked']) ? 0 : 1;
				if($itemnew['checked'] != $items[$cid]['checked']){
					$sql_asw .= "checked=".$itemnew['checked'];
					$adoptsnew += $itemnew['checked'] - $items[$cid]['checked'];
				}
				$itemnew['currency'] = max(0,intval($itemnew['currency']));
				if(min($itemnew['currency'],$sparenew) != $items[$cid]['currency']){
					$sql_asw .= ($sql_asw ? ',' : '')."currency=".min($itemnew['currency'],$sparenew);
				}
				$sql_asw && $db->query("UPDATE {$tblprefix}answers SET $sql_asw WHERE aid=$aid AND cid=$cid");
				$sparenew = max(0,$sparenew - $itemnew['currency']);
			}
			$sql_arc .= ($sql_arc ? ',' : '')."adopts=$adoptsnew";
		}
		if(!empty($archivenew['notaclosed'])){
			$auser = new cls_userinfo;
			$query = $db->query("SELECT * FROM {$tblprefix}answers WHERE aid=$aid");
			while($item = $db->fetch_array($query)){
				if($item['currency']){
					$auser->activeuser($item['mid']);
					$auser->updatecrids(array($item['crid'] => $item['currency']),1,lang('answer_reward'));
					$auser->init();
				}
			}
			$auser->activeuser($aedit->archive['mid']);
			$commu = read_cache('commu',$channels[$aedit->archive['chid']]['cuid']);
			if($commu['setting']['credit']){
				$auser->sub_data();
				$auser->updatefield('credits',$auser->info['credits'] + ($aedit->archive['currency'] - $sparenew) * $commu['setting']['credit'],'sub');
			}
			$auser->updatecrids(array($aedit->archive['crid'] => $sparenew),1,lang('answer_reward'));
			unset($auser);
			$sql_an .= ($sql_an ? ',' : '')."end=1";
			$sql_arcs .= ($sql_arcs ? ',' : '')."notaclosed=1";
			$sparenew = 0;
			$db->query("DELETE FROM {$tblprefix}notaanswer WHERE aid=$aid");
		}
		//更新数据库
		if($sparenew != $aedit->archive['spare']) $sql_arcs .= ($sql_arcs ? ',' : '')."spare=$sparenew";
		$sql_arc && $db->query("UPDATE {$tblprefix}archives SET $sql_arc WHERE aid=$aid");
		$sql_arcs && $db->query("UPDATE {$tblprefix}archives_sub SET $sql_arcs WHERE aid=$aid");
		$sql_an && $db->query("UPDATE {$tblprefix}answers SET $sql_an WHERE aid=$aid");
		amessage('questionadminsucceed',axaction(10,$forward));
	}
}
elseif($action == 'notarecord' && $aid){
	$cid = empty($cid) ? 0 : max(0,intval($cid));
	tabheader(empty($cid) ? lang('question_alter_record') : lang('answer_alter_record'));
	trcategory(array(lang('modify_date'),lang('content')));
	$query = $db->query("SELECT * FROM {$tblprefix}notaanswer WHERE aid=$aid AND cid=$cid ORDER BY createdate");
	while($item = $db->fetch_array($query)){
		trbasic(date("$dateformat $timeformat", $item['createdate']),'',$item['content'],'');
	}
	tabfooter();
	echo "<input class=\"button\" type=\"submit\" name=\"\" value=\"".lang('goback')."\" onclick=\"history.go(-1);\">";
	
}

?>
