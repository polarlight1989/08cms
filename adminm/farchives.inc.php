<?
!defined('M_COM') && exit('No Permission');
load_cache('grouptypes,currencys,fcatalogs,fchannels');
//分析页面设置
$nmuid = empty($nmuid) ? 0 : $nmuid;
$u_checked = $u_valid = -1;
if($nmuid && $u_url = read_cache('murl',$nmuid)){
	$u_tplname = $u_url['tplname'];
	$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
	$u_mtitle = @$u_url['mtitle'];
	$u_guide = @$u_url['guide'];
	foreach(array('checked','valid',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
	$vars = array('caids','filters','lists','operates');
	foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_lists) && $u_lists = array('catalog','check','valid','adddate','qadmin','edit',);
empty($u_filters) && $u_filters = array('catalog','check',);
if(empty($u_tplname) || !empty($u_onlyview)){
	include_once M_ROOT."./include/farcedit.cls.php";
	if(!empty($u_caids)) foreach($fcatalogs as $k => $v) if(!in_array($k,$u_caids)) unset($fcatalogs[$k]); 
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$fcaid = empty($fcaid) ? 0 : max(0,intval($fcaid));
	$checked = isset($checked) ? $checked : '-1';
	$valid = isset($valid) ? $valid : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$qstate = empty($qstate) ? '' : trim($qstate);
	
	$wheresql = "mid='$memberid'";
	$fromsql = "FROM {$tblprefix}farchives";
	
	//栏目范围
	if($fcaid){
		if(!empty($u_caids) && !in_array($fcaid,$u_caids)) $no_list = 1;
		else $wheresql .= " AND fcaid='$fcaid'";
	}elseif(!empty($u_caids)) $wheresql .= " AND fcaid ".multi_str($u_caids);
	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= " AND checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= " AND checked='$u_checked'";
	//有效期状态范围
	if($valid != -1){
		if(($u_valid != -1) && $valid != $u_valid) $no_list = true;
		else $wheresql .= ($valid ? " AND startdate<'$timestamp' AND (enddate='0' OR enddate>'$timestamp')" : " AND (startdate>'$timestamp' OR (enddate!='0' AND enddate<'$timestamp'))");
	}elseif($u_valid != -1) $wheresql .= ($u_valid ? " AND startdate<'$timestamp' AND (enddate='0' OR enddate>'$timestamp')" : " AND (startdate>'$timestamp' OR (enddate!='0' AND enddate<'$timestamp'))");
	//咨询状态
	if($qstate) $wheresql .= " AND qstate='$qstate'";
	//搜索关键词处理
	$keyword && $wheresql .= " AND subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	$wheresql = empty($no_list) ? "WHERE $wheresql" : 'WHERE 1=0';
	
	$filterstr = '';
	foreach(array('nmuid','fcaid','qstate','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked','valid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($action.'arcsedit',"?action=farchives&nmuid=$nmuid&page=$page");
			//搜索区块
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//分类
			if(empty($u_filters) || in_array('catalog',$u_filters)){
				echo "<select style=\"vertical-align: middle;\" name=\"fcaid\">".makeoption(array(0 => lang('coclass')) + fcaidsarr(),$fcaid)."</select>&nbsp; ";
			}
			//审核状态
			if(empty($u_filters) || in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('checkstate'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			//有效状态
			if(empty($u_filters) || in_array('valid',$u_filters)){
				$validarr = array('-1' => lang('validperiodstate'),'0' => lang('invalid'),'1' => lang('available'));
				echo "<select style=\"vertical-align: middle;\" name=\"valid\">".makeoption($validarr,$valid)."</select>&nbsp; ";
			}
			//有效状态
			if(empty($u_filters) || in_array('qstate',$u_filters)){
				$qstatearr = array('' => lang('qstate'),'new' => lang('nosettle'),'dealing' => lang('dealing'),'end' => lang('settled'),'close' => lang('closed'),);
				echo "<select style=\"vertical-align: middle;\" name=\"qstate\">".makeoption($qstatearr,$qstate)."</select>&nbsp; ";
			}
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";
			tabfooter();
	
			//列表区	
			tabheader(empty($u_mtitle) ? lang('contentlist') : $u_mtitle,'','',10);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('id'),lang('title'),);
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('coclass');
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('valid',$u_lists)) $cy_arr[] = lang('available');
			if(in_array('qstate',$u_lists)) $cy_arr[] = lang('qstate');
			if(in_array('vieworder',$u_lists)) $cy_arr[] = lang('order');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_time');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('update_time');
			if(in_array('startdate',$u_lists)) $cy_arr[] = lang('startdate');
			if(in_array('enddate',$u_lists)) $cy_arr[] = lang('end1_time');
			if(in_array('qadmin',$u_lists)) $cy_arr[] = lang('consult');
			if(in_array('edit',$u_lists)) $cy_arr[] = lang('edit');
			trcategory($cy_arr);
	
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT * $fromsql $wheresql ORDER BY aid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);
	
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\">";
				$aidstr = $row['aid'];
				$arcurl = view_farcurl($row['aid'],$row['arcurl']);
				$subjectstr = "<a href=\"$arcurl\" target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
				$catalogstr = @$fcatalogs[$row['fcaid']]['title'];
				$checkstr = $row['checked'] ? 'Y' : '-';
				$validstr = ($row['startdate'] < $timestamp) && (!$row['enddate'] || $row['enddate'] > $timestamp) ? 'Y' : '-';
				$qstatestr = @$qstatearr[$row['qstate']];
				$orderstr = $row['vieworder'];
				$adddatestr = $row['createdate'] ? date('Y-m-d',$row['createdate']) : '-';
				$updatedatestr = $row['updatedate'] ? date('Y-m-d',$row['updatedate']) : '-';
				$startdatestr = $row['startdate'] ? date('Y-m-d',$row['startdate']) : '-';
				$enddatestr = $row['enddate'] ? date('Y-m-d',$row['enddate']) : '-';
				$adminstr = empty($fcatalogs[$row['fcaid']]['cumode']) ? '-' : ("<a href=\"?action=fconsult&aid=$row[aid]\" onclick=\"return floatwin('open_inarchive',this)\">".lang('admin')."</a>");
				$editstr = "<a href=\"?action=farchive&aid=$row[aid]\" onclick=\"return floatwin('open_farchive',this)\">".lang('detail')."</a>";
	
				$itemstr .= "<tr class=\"txt\"><td class=\"item\">$selectstr</td><td class=\"item\">$aidstr</td><td class=\"item2\">$subjectstr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"item\">$catalogstr</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"item\">$checkstr</td>\n";
				if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"item\">$validstr</td>\n";
				if(in_array('qstate',$u_lists)) $itemstr .= "<td class=\"item\">$qstatestr</td>\n";
				if(in_array('vieworder',$u_lists)) $itemstr .= "<td class=\"item\">$orderstr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"item\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"item\">$updatedatestr</td>\n";
				if(in_array('startdate',$u_lists)) $itemstr .= "<td class=\"item\">$startdatestr</td>\n";
				if(in_array('enddate',$u_lists)) $itemstr .= "<td class=\"item\">$enddatestr</td>\n";
				if(in_array('qadmin',$u_lists)) $itemstr .= "<td class=\"item\">$adminstr</td>\n";
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"item\">$editstr</td>\n";
				$itemstr .= "</tr>\n";
			}
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts,$mrowpp,$page,"?action=farchives$filterstr");
	
			echo $itemstr;
			tabfooter();
			echo $multi;
			echo "<br><br><input class=\"btn\" type=\"submit\" name=\"barcsedit\" id=\"barcsedit\" value=\"".lang('delete')."\">";
			m_guide(@$u_guide);
		}else include(M_ROOT.$u_tplname);
	}else{
		if(empty($selectid)) mcmessage('mselectmes',"?action=farchives$filterstr&page=$page");
		$aedit = new cls_farcedit;
		foreach($selectid as $aid){
			$aedit->set_aid($aid);
			$aedit->arc_delete(1);
		}
		unset($aedit,$arc);
		mcmessage('messagefinish',"?action=farchives$filterstr&page=$page");
	
	}
}else include(M_ROOT.$u_tplname);
?>
