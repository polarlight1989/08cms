<?php
!defined('M_COM') && exit('No Permission');
load_cache('cotypes,channels,currencys,permissions,inmurls,acatalogs,ucotypes,rfields');
//分析页面设置
$nimuid = empty($nimuid) ? 0 : $nimuid;
$u_checked = -1;
if($nimuid && $u_url = read_cache('inmurl',$nimuid)){
	$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
	$u_mtitle = @$u_url['mtitle'];
	$u_guide = @$u_url['guide'];
	$u_checked = $u_url['setting']['checked'];
	foreach(array('filters','lists','operates',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_lists) && $u_lists = array('mname','check','adddate','edit',);
if(empty($u_tplname) || !empty($u_onlyview)){
	include_once M_ROOT."./include/parse.fun.php";
	include_once M_ROOT."./include/arcedit.cls.php";
	include_once M_ROOT."./include/cuedit.cls.php";
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
	$fromsql = "FROM {$tblprefix}replys cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";
	
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
			echo form_str($action.'arcsedit',"?action=inreplys&aid=$aid&page=$page");
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			if(empty($u_filters) || in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			echo strbutton('bfilter','filter0').'</td></tr>';
			trhidden('nimuid',$nimuid);
			tabfooter();
		
		
			//列表区	
			tabheader(empty($u_mtitle) ? lang('replylist') : $u_mtitle,'','',9);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('title'),'item2'),lang('member'),);
			foreach($ucotypes as $k => $v) if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'reply') $cy_arr["ccid$k"] = $v['cname'];
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('aread',$u_lists)) $cy_arr[] = lang('read');
			if(in_array('areply',$u_lists)) $cy_arr[] = lang('areply');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('addtime');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('updatetime');
			if(in_array('refreshdate',$u_lists)) $cy_arr[] = lang('retime');
			$cy_arr[] = lang('edit');
			trcategory($cy_arr);
	
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);
	
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">".mhtmlspecialchars($row['asubject'])."</a>";
				$mnamestr = $row['mname'];
				foreach($ucotypes as $k => $v){
					if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'reply'){
						$ucoclasses = read_cache('ucoclasses',$k);
						${'uccid'.$k.'str'} = @$ucoclasses[$row['uccid'.$k]]['title'];
					}
				}
				$checkstr = $row['checked'] ? 'Y' : '-';
				$readstr = $row['aread'] ? 'Y' : '-';
				$areplystr = $row['areply'] ? 'Y' : '-';
				$adddatestr = $row['ucreatedate'] ? date('Y-m-d',$row['ucreatedate']) : '-';
				$updatedatestr = $row['updatedate'] ? date('Y-m-d',$row['updatedate']) : '-';
				$refreshdatestr = $row['refreshdate'] ? date('Y-m-d',$row['refreshdate']) : '-';
				$editstr = "<a href=\"?action=reply&cid=$row[cid]&amode=1\" onclick=\"return floatwin('open_reply',this)\">".lang('detail')."</a>";
	
	
				$itemstr .= "<tr><td class=\"item\">$selectstr</td><td class=\"item2\">$subjectstr</td><td class=\"item\">$mnamestr</td>\n";
				foreach($ucotypes as $k => $v) if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'reply') $itemstr .= "<td class=\"item\">".${'uccid'.$k.'str'}."</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"item\">$checkstr</td>\n";
				if(in_array('aread',$u_lists)) $itemstr .= "<td class=\"item\">$readstr</td>\n";
				if(in_array('areply',$u_lists)) $itemstr .= "<td class=\"item\">$areplystr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"item\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"item\">$updatedatestr</td>\n";
				if(in_array('refreshdate',$u_lists)) $itemstr .= "<td class=\"item\">$refreshdatestr</td>\n";
				$itemstr .= "<td class=\"item\">$editstr</td>\n";
				$itemstr .= "</tr>\n";
			}
	
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $mrowpp, $page, "?action=inreplys&aid=$aid$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			//操作区
			tabheader(lang('operateitem'));
			$s_arr = array();
			if(empty($u_operates) || in_array('delete',$u_operates)) $s_arr['delete'] = lang('delete');
			if(empty($u_operates) || in_array('check',$u_operates)) $s_arr['check'] = lang('check');
			if(empty($u_operates) || in_array('uncheck',$u_operates)) $s_arr['uncheck'] = lang('uncheck');
			if($s_arr){
				$soperatestr = '';
				foreach($s_arr as $k => $v) $soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
				trbasic(lang('choose_item'),'',$soperatestr,'');
			}
			tabfooter('barcsedit');
			m_guide(@$u_guide);
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($arcdeal)) mcmessage('selectopeitem',M_REFERER);
		if(empty($selectid)) mcmessage('conoffer',M_REFERER);
		$uedit = new cls_cuedit;
		foreach($selectid as $cid){
			if($errno = $uedit->read($cid,'reply')) continue;
			if(!empty($arcdeal['delete'])){
				$uedit->delete(0);
				continue;
			}
			if(!empty($arcdeal['check'])){
				$uedit->updatefield('checked',1);
			}elseif(!empty($arcdeal['uncheck'])){
				$uedit->updatefield('checked',0);
			}
			$uedit->updatedb();
			$uedit->init();
		}
		mcmessage('replysetsucceed',"?action=inreplys&aid=$aid$filterstr&page=$page");
	}
}else include(M_ROOT.$u_tplname);
?>
