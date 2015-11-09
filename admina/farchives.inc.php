<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('farchive') || amessage('no_apermission');
load_cache('fcatalogs,fchannels,currencys,');
include_once M_ROOT."./include/farcedit.cls.php";
include_once M_ROOT."./include/farchive.cls.php";
include_once M_ROOT."./include/farc_static.fun.php";
if($action == 'farchivesedit'){
	//分析页面设置
	$nauid = empty($nauid) ? 0 : $nauid;
	$u_checked = $u_valid = -1;
	if($nauid && $u_url = read_cache('aurl',$nauid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		foreach(array('checked','valid','consult',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
		$vars = array('filters','lists','operates');
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
		if($u_consult){
			if(in_array(-1,$a_fcaids)){
				$a_fcaids = array();
				foreach($fcatalogs as $k => $v) if($v['cumode']) $a_fcaids[] = $k;
			}else{
				foreach($fcatalogs as $k => $v) if(!$v['cumode'] || !in_array($k,$a_fcaids)) unset($a_fcaids[$k]);
			}
		}
	}
	empty($u_lists) && $u_lists = array('catalog','mname','check','vieworder','adddate','qadmin','edit',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$page = !empty($page) ? max(1, intval($page)) : 1;
		submitcheck('bfilter') && $page = 1;
		$fcaid = empty($fcaid) ? 0 : max(0,intval($fcaid));
		$checked = isset($checked) ? $checked : '-1';
		$valid = isset($valid) ? $valid : '-1';
		$keyword = empty($keyword) ? '' : $keyword;
		$qstate = empty($qstate) ? '' : trim($qstate);
	
		$wheresql = '';
		$fromsql = "FROM {$tblprefix}farchives";
	
		//栏目范围
		if(!empty($fcaid)){
			if(!in_array(-1,$a_fcaids) && !in_array($fcaid,$a_fcaids)) $no_list = 1;
			else $wheresql .= ($wheresql ? ' AND ' : '')."fcaid='$fcaid'";
		}elseif(!in_array(-1,$a_fcaids)){
			if(!$a_fcaids) $nolist = 1;
			else $wheresql .= ($wheresql ? ' AND ' : '')."fcaid ".multi_str($a_fcaids);
		}
		//审核状态范围
		if($checked != -1){
			if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
			else $wheresql .= ($wheresql ? ' AND ' : '')."checked='$checked'";
		}elseif($u_checked != -1) $wheresql .= ($wheresql ? ' AND ' : '')."checked='$u_checked'";
		//有效期状态范围
		if($valid != -1){
			if(($u_valid != -1) && $valid != $u_valid) $no_list = true;
			else $wheresql .= ($wheresql ? ' AND ' : '').($valid ? "startdate<'$timestamp' AND (enddate='0' OR enddate>'$timestamp')" : "(startdate>'$timestamp' OR (enddate!='0' AND enddate<'$timestamp'))");
		}elseif($u_valid != -1) $wheresql .= ($wheresql ? ' AND ' : '').($u_valid ? "startdate<'$timestamp' AND (enddate='0' OR enddate>'$timestamp')" : "(startdate>'$timestamp' OR (enddate!='0' AND enddate<'$timestamp'))");
		//咨询状态
		if($qstate) $wheresql .= ($wheresql ? ' AND ' : '')."qstate='$qstate'";
		//搜索关键词处理
		$keyword && $wheresql .= ($wheresql ? ' AND ' : '')."(mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
		$wheresql = empty($no_list) ? ($wheresql ? "WHERE $wheresql" : '') : 'WHERE 1=0';
	
		$filterstr = '';
		foreach(array('nauid','fcaid','qstate','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
		foreach(array('checked','valid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
		if(!submitcheck('barcsedit')){
			if(empty($u_tplname)){
				echo form_str($actionid.'arcsedit',"?entry=farchives&action=farchivesedit&page=$page");
				//搜索区块
				tabheader_e();
				echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
				trhidden('fcaid',$fcaid);
				trhidden('nauid',$nauid);
				//关键词固定显示
				echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
				//审核状态
				if(empty($u_filters) || in_array('check',$u_filters)){
					$checkedarr = array('-1' => lang('check_state'),'0' => lang('nocheck'),'1' => lang('checked'));
					echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
				}
				//有效状态
				if(empty($u_filters) || in_array('valid',$u_filters)){
					$validarr = array('-1' => lang('validperiod_state'),'0' => lang('invalid'),'1' => lang('available'));
					echo "<select style=\"vertical-align: middle;\" name=\"valid\">".makeoption($validarr,$valid)."</select>&nbsp; ";
				}
				//有效状态
				if(empty($u_filters) || in_array('qstate',$u_filters)){
					$qstatearr = array('' => lang('qstate'),'new' => lang('nosettle'),'dealing' => lang('dealing0'),'end' => lang('settled'),'close' => lang('closed'),);
					echo "<select style=\"vertical-align: middle;\" name=\"qstate\">".makeoption($qstatearr,$qstate)."</select>&nbsp; ";
				}
				echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
				echo "</td></tr>";
				tabfooter();
	
				//列表区	
				tabheader(@$fcatalogs[$fcaid]['title'].lang('content'),'','',10);
				$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('id'),lang('title'),);
				if(in_array('catalog',$u_lists)) $cy_arr[] = lang('coclass');
				if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
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
					$query = $db->query("SELECT * $fromsql $wheresql ORDER BY aid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
					$pagetmp--;
				} while(!$db->num_rows($query) && $pagetmp);
	
				$itemstr = '';
				while($row = $db->fetch_array($query)){
					$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\">";
					$aidstr = $row['aid'];
					$arcurl = view_farcurl($row['aid'],$row['arcurl']);
					$subjectstr = "<a href=\"$arcurl\" target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
					$catalogstr = @$fcatalogs[$row['fcaid']]['title'];
					$mnamestr = $row['mname'];
					$checkstr = $row['checked'] ? 'Y' : '-';
					$validstr = ($row['startdate'] < $timestamp) && (!$row['enddate'] || $row['enddate'] > $timestamp) ? 'Y' : '-';
					$qstatestr = @$qstatearr[$row['qstate']];
					$orderstr = $row['vieworder'];
					$adddatestr = $row['createdate'] ? date('Y-m-d',$row['createdate']) : '-';
					$updatedatestr = $row['updatedate'] ? date('Y-m-d',$row['updatedate']) : '-';
					$startdatestr = $row['startdate'] ? date('Y-m-d',$row['startdate']) : '-';
					$enddatestr = $row['enddate'] ? date('Y-m-d',$row['enddate']) : '-';
					$adminstr = empty($fcatalogs[$row['fcaid']]['cumode']) ? '-' : ("<a href=\"?entry=farchive&action=fconsult&aid=$row[aid]\" onclick=\"return floatwin('open_inarchive',this)\">".lang('admin')."</a>");
					$editstr = "<a href=\"?entry=farchive&action=farchivedetail&aid=$row[aid]\" onclick=\"return floatwin('open_farchive',this)\">".lang('detail')."</a>";
	
					$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\">$selectstr</td><td class=\"txtC w40\">$aidstr</td><td class=\"txtL\">$subjectstr</td>\n";
					if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
					if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
					if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$checkstr</td>\n";
					if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$validstr</td>\n";
					if(in_array('qstate',$u_lists)) $itemstr .= "<td class=\"txtC\">$qstatestr</td>\n";
					if(in_array('vieworder',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$orderstr</td>\n";
					if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
					if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$updatedatestr</td>\n";
					if(in_array('startdate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$startdatestr</td>\n";
					if(in_array('enddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$enddatestr</td>\n";
					if(in_array('qadmin',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$adminstr</td>\n";
					if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
					$itemstr .= "</tr>\n";
				}
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$multi = multi($counts,$atpp,$page,"?entry=farchives&action=farchivesedit$filterstr");
		
				echo $itemstr;
				tabfooter();
				echo $multi;
	
				tabheader(lang('operate_item'));
				$s_arr = array();
				if(empty($u_operates) || in_array('delete',$u_operates)) $s_arr['delete'] = lang('delete');
				if(empty($u_operates) || in_array('check',$u_operates)) $s_arr['check'] = lang('check');
				if(empty($u_operates) || in_array('uncheck',$u_operates)) $s_arr['uncheck'] = lang('uncheck');
				if(empty($u_operates) || in_array('static',$u_operates)) $s_arr['static'] = lang('create_static');
				if(empty($u_operates) || in_array('unstatic',$u_operates)) $s_arr['unstatic'] = lang('unstatic');
				if($s_arr){
					$soperatestr = '';
					foreach($s_arr as $k => $v) $soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
					trbasic(lang('choose_item'),'',$soperatestr,'');
				}
				tabfooter('barcsedit');
			}else include(M_ROOT.$u_tplname);
		}else{
			if(empty($arcdeal)) amessage('selectoperateitem',"?entry=farchives&action=farchivesedit&page=$page$filterstr");
			if(empty($selectid)) amessage('mselectmes',"?entry=farchives&action=farchivesedit&page=$page$filterstr");
			$aedit = new cls_farcedit;
			$arc = new cls_farchive();
			foreach($selectid as $aid){
				$aedit->set_aid($aid);
				if(!empty($arcdeal['delete'])){
					$aedit->arc_delete();
					continue;
				}
				if(!empty($arcdeal['check'])){
					$aedit->arc_check(1);
				}elseif(!empty($arcdeal['uncheck'])){
					$aedit->arc_check(0);
				}
				if(!empty($arcdeal['static'])){
					farc_static($aid);
				}elseif(!empty($arcdeal['unstatic'])){
					farc_unstatic($aid);
				}
				$aedit->updatedb();
				$aedit->init();
				$arc->init();
			}
			unset($aedit,$arc);
	
			adminlog(lang('freeinfo_admin'),lang('freeinfo_list_admin'));
			amessage('freeopefin',"?entry=farchives&action=farchivesedit&page=$page$filterstr");
		
		}
	}else include(M_ROOT.$u_tplname);
	
}

?>
