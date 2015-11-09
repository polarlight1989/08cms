<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('commu') || amessage('no_apermission');
load_cache('channels,rcatalogs,currencys,commus,bfields');
load_cache('catalogs',$sid);
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/arcedit.cls.php";
if($action == 'reportsedit'){
	//分析页面设置
	$nauid = empty($nauid) ? 0 : $nauid;
	if($nauid && $aurl = read_cache('aurl',$nauid)){
		$u_tplname = $aurl['tplname'];
		foreach(array('cuids','chids','filters','lists',) as $var) if(!empty($aurl['setting'][$var])) ${'u_'.$var} = explode(',',$aurl['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('mname','catalog','channel','adddate','edit',);

	$caid = empty($caid) ? 0 : $caid;
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : 1;
	$cuid = empty($cuid) ? 0 : $cuid;
	$chid = empty($chid) ? 0 : max(0,intval($chid));
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "a.sid='$sid'";
	$fromsql = "FROM {$tblprefix}reports cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";

	//栏目范围
	$caids = array(-1);
	if(!empty($caid)){
		$caids = cnsonids($caid,$catalogs);
	}
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

	//搜索关键词处理
	$keyword && $wheresql .= " AND (cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";

	$filterstr = '';
	foreach(array('nauid','viewdetail','caid','cuid','chid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));

	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'arcsedit',"?entry=reports&action=reportsedit&page=$page$param_suffix");
			//搜索区块

			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//合辑类型搜索
			if(empty($u_filters) || in_array('commu',$u_filters)){
				$cuidsarr = array('0' => lang('all_cuitem'));
				foreach($commus as $k => $v) if($v['cclass'] == 'report') $cuidsarr[$k] = $v['cname'];
				echo "<select style=\"vertical-align: middle;\" name=\"cuid\">".makeoption($cuidsarr,$cuid)."</select>&nbsp; ";
			}
			//模型搜索
			if(empty($u_filters) || in_array('channel',$u_filters)){
				$chidsarr = array('0' => lang('all_channel')) + chidsarr();
				echo "<select style=\"vertical-align: middle;\" name=\"chid\">".makeoption($chidsarr,$chid)."</select>&nbsp; ";
			}
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";
			//某些固定页面参数
			trhidden('caid',$caid);
			trhidden('nauid',$nauid);
			tabfooter();

	
			//列表区	
			tabheader(lang('report_list')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);

			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('commu',$u_lists)) $cy_arr[] = lang('type');
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
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
				$catalogstr = @$catalogs[$row['caid']]['title'];
				$commustr = @$commus[$row['cuid']]['cname'];
				$channelstr = @$channels[$row['chid']]['cname'];
				$adddatestr = date('Y-m-d',$row['ucreatedate']);
				$editstr = "<a href=\"?entry=reports&action=reportdetail&cid=$row[cid]$param_suffix\" onclick=\"return floatwin('open_reportsedit',this)\">".lang('detail')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('commu',$u_lists)) $itemstr .= "<td class=\"txtC\">$commustr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=reports&action=reportsedit$param_suffix$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			echo '<br><br>'.strbutton('barcsedit',lang('delete')).'</form>';
			a_guide('reportsedit');
		
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($selectid) && empty($select_all)) amessage('pchoosecontent',axaction(1,M_REFERER));
		if(!empty($select_all)){
			$parastr = $dealstr = "";
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
		$db->query("DELETE FROM {$tblprefix}reports WHERE cid ".multi_str($selectid),'UNBUFFERED');
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
				amessage('operating',"?entry=reports&action=reportsedit&page=$page$param_suffix$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=reports&action=reportsedit&page=$page$param_suffix$filterstr\">",'</a>');
			}
		}
		adminlog(lang('reportsetsucceed'),lang('reportsetsucceed'));
		amessage('contentsetsucceed',"?entry=reports&action=reportsedit$param_suffix&page=$page$filterstr");
	}
}elseif($action == 'reportdetail'){
	load_cache('bfields,ucotypes');
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(!$report = $db->fetch_one("SELECT * FROM {$tblprefix}reports WHERE cid='$cid'")) amessage('choosereport');
	$commu = read_cache('commu',$report['cuid']);
	//$fieldsarr = empty($commu['setting']['fields']) ? array() : explode(',',$commu['setting']['fields']);
	$citems = empty($commu['setting']['citems']) ? array() : explode(',',$commu['setting']['citems']);
	$aedit = new cls_arcedit;
	$aedit->set_aid($report['aid']);
	$aedit->detail_data();
	if(!submitcheck('newcommu')){
		tabheader($commu['cname'].'&nbsp; -&nbsp; '.lang('based_msg'),'commudetail',"?entry=reports&action=reportdetail&cid=$cid$param_suffix$forwardstr",2,1,1);

		$archive = $db->fetch_one("SELECT * FROM {$tblprefix}archives WHERE aid='$report[aid]'");
		trbasic(lang('lookrelatedsource'),'',"<a href=\"".view_arcurl($aedit->archive)."\" target=\"_blank\">>>&nbsp; ".$aedit->archive['subject']."</a>",'');
		trbasic(lang('add_time'),'',date('Y-m-d H:i',$report['createdate']),'');
		tabfooter();

		$a_field = new cls_field;
		tabheader($commu['cname'].'&nbsp; -&nbsp; '.lang('submitmessage'));
		$submitstr = '';
		foreach($ucotypes as $k => $v){
			if(in_array('uccid'.$k,$citems) && $v['umode'] != 1){
				trbasic($v['cname'],'',mu_cnselect("communew[uccid$k]",$report["uccid$k"],$k,lang('p_choose'),$v['emode'],"communew[uccid{$k}date]",@$report["uccid{$k}date"] ? date('Y-m-d',$report["uccid{$k}date"]) : ''),'');
				$submitstr .= makesubmitstr("communew[uccid$k]",$v['notblank'],0,0,0,'common');
				$v['emode'] == 2 && $submitstr .= makesubmitstr("communew[uccid{$k}date]",1,0,0,0,'date');
			}
		}
		$a_field = new cls_field;
		foreach($bfields as $k => $v){
			if(!$v['isfunc'] && in_array($k,$citems)){
				$a_field->init();
				$a_field->field = $v;
				$a_field->oldvalue = isset($report[$k]) ? $report[$k] : '';
				$a_field->trfield('communew','','b');
				$submitstr .= $a_field->submitstr;
			}
		}
		unset($a_field);
		tabfooter('newcommu');
		check_submit_func($submitstr);
	}else{
		$c_upload = new cls_upload;	
		$bfields = fields_order($bfields);
		$sqlstr = '';
		$a_field = new cls_field;
		foreach($ucotypes as $k => $v){
			if(in_array('uccid'.$k,$citems) && $v['umode'] != 1){
				if($v['notblank'] && empty($communew['uccid'.$k])) amessage('notnull',axaction(2,M_REFERER),$v['cname']);
				$sqlstr .= ",uccid$k='".$communew['uccid'.$k]."'";
				if($v['emode']){
					$communew["uccid{$k}date"] = !isdate($communew["uccid{$k}date"]) ? 0 : strtotime($communew["uccid{$k}date"]);
					if($communew["uccid$k"] && !$communew["uccid{$k}date"] && $v['emode'] == 2) amessage('setcoclass',axaction(2,M_REFERER),$v['cname']);
					!$communew["uccid$k"] && $communew["uccid{$k}date"] = 0;
					$sqlstr .= ",uccid{$k}date='".$communew["uccid{$k}date"]."'";
				}
			}
		}
		foreach($bfields as $k => $v){
			if(!$v['isfunc'] && in_array($k,$citems)){
				$a_field->init();
				$a_field->field = $v;
				$a_field->oldvalue = isset($report[$k]) ? $report[$k] : '';
				$a_field->deal('communew');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					amessage($a_field->error,axaction(2,M_REFERER));
				}
				$sqlstr .= ",$k='".$a_field->newvalue."'";
				if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
			}
		}
		unset($a_field);
		$c_upload->closure(1, $cid, 'reports');
		$c_upload->saveuptotal(1);
		$db->query("UPDATE {$tblprefix}reports SET updatedate='$timestamp' $sqlstr WHERE cid='$cid'");

		//处理函数字段
		$sqlstr = '';
		foreach($bfields as $k => $v){
			if($v['isfunc'] && in_array($k,$fieldsarr)){
				//得到原始数据的资料，带上当前文档资料
				if(!isset($sourcearr)){
					$sourcearr = $db->fetch_one("SELECT * FROM {$tblprefix}reports WHERE cid='$cid'");
					$sourcearr = array_merge($a_edit->archive,$sourcearr);
				}
				$sqlstr .= ($sqlstr ? ',' : '')."$k='".field_func($v['func'],$sourcearr,$arr2='')."'";
			}
		}
		unset($sourcearr);
		$sqlstr && $db->query("UPDATE {$tblprefix}reports SET $sqlstr WHERE cid='$cid'");

		//处理自定义函数
		if(!empty($commu['func'])){//可以处理所有参数的变更
			$sourcearr = $db->fetch_one("SELECT * FROM {$tblprefix}reports WHERE cid='$cid'");
			$sourcearr = array_merge($aedit->archive,$sourcearr);
			field_func($commu['func'],$sourcearr,$arr2='');
			unset($sourcearr);
		}		

		amessage('updatesucceed',axaction(10,$forward),$commu['cname']);
	}
}
?>