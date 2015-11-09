<?
!defined('M_COM') && exit('No Permission');
load_cache('channels,acatalogs,ucotypes,currencys,commus,rfields,inmurls');
//分析页面设置
$nmuid = empty($nmuid) ? 0 : max(0,intval($nmuid));
$u_checked = $u_valid = -1;
if($nmuid && $u_url = read_cache('murl',$nmuid)){
	$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
	$u_mtitle = @$u_url['mtitle'];
	$u_guide = @$u_url['guide'];
	foreach(array('checked',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
	$vars = array('chids','cuids','filters','lists','operates','imuids',);
	foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_filters) && $u_filters = array('check','catalog',);
empty($u_lists) && $u_lists = array('catalog','uclass','channel','check',);
if(empty($u_tplname) || !empty($u_onlyview)){
	include_once M_ROOT."./include/cuedit.cls.php";
	$catalogs = &$acatalogs;
	$page = empty($page) ? 1 : max(1, intval($page));
	submitcheck('bfilter') && $page = 1;
	$caid = empty($caid) ? 0 : max(0,intval($caid));
	$checked = isset($checked) ? $checked : '-1';
	$aread = isset($aread) ? $aread : '-1';
	$areply = isset($areply) ? $areply : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	
	$wheresql = "a.mid='$memberid'";
	$fromsql = "FROM {$tblprefix}replys cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";
	if(!empty($u_cuids)) $wheresql .= " AND cu.cuid ".multi_str($u_cuids);
	//栏目范围
	$caids = array();
	if($caid){
		$caids = cnsonids($caid,$catalogs);
		if(!empty($u_caids)) $caids = array_intersect($caids,$u_caids);
	}elseif(!empty($u_caids)) $caids = $u_caids;
	if($caids) $wheresql .= " AND a.caid ".multi_str($caids);
	elseif(!empty($u_caids)) $no_list = true;
	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= " AND cu.checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= " AND cu.checked='$u_checked'";
	//已读状态范围
	if($aread != -1) $wheresql .= " AND cu.aread='$aread'";
	//反馈状态范围
	if($areply != -1) $wheresql .= " AND cu.areply='$areply'";
	//搜索关键词处理
	$keyword && $wheresql .= " AND a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	$filterstr = '';
	foreach(array('nmuid','caid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked','aread','areply',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	//处理ucotype的筛选
	foreach($ucotypes as $k => $v){
		if(!empty(${'uccid'.$k}) && $v['uclass'] == 'reply'){
			$filterstr .= "&uccid$k=".${'uccid'.$k};
			$wheresql .= " AND cu.uccid$k='".${'uccid'.$k}."'";
		}
	}
	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($action.'archivesedit',"?action=areplys&nmuid=$nmuid&page=$page");
			tabheader_e();
			echo "<tr><td class=\"item2\">";
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//审核状态
			if(in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			//已读状态
			if(in_array('aread',$u_filters)){
				$areadarr = array('-1' => lang('read_state'),'0' => lang('noread'),'1' => lang('read'));
				echo "<select style=\"vertical-align: middle;\" name=\"aread\">".makeoption($areadarr,$aread)."</select>&nbsp; ";
			}
			//反馈状态
			if(in_array('areply',$u_filters)){
				$ureadarr = array('-1' => lang('areply_state'),'0' => lang('noareply'),'1' => lang('areplyed'));
				echo "<select style=\"vertical-align: middle;\" name=\"areply\">".makeoption($areplyarr,$areply)."</select>&nbsp; ";
			}
			//栏目搜索
			if(in_array('catalog',$u_filters)){
				$caidsarr = array('0' => lang('catalog')) + caidsarr($catalogs);
				echo "<select style=\"vertical-align: middle;\" name=\"caid\">".makeoption($caidsarr,$caid)."</select>&nbsp; ";
			}
			foreach($ucotypes as $k => $v){//报价类系的搜索
				if(in_array('uccid'.$k,$u_filters) && $v['cclass'] == 'reply'){
					$ucoclasses = read_cache('ucoclasses',$k);
					$uccidsarr = array();
					foreach($ucoclasses as $k1 => $v2) $uccidsarr[$k1] = $v1['title'];
					echo "<select style=\"vertical-align: middle;\" name=\"uccid$k\">".makeoption($uccidsarr,empty(${"uccid$k"}) ? 0 : ${"uccid$k"})."</select>&nbsp; ";
				}
			}
			echo strbutton('bfilter','filter0').'</td></tr>';
			tabfooter();
		
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.refreshdate DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
				$pagetmp--;
			}while(!$db->num_rows($query) && $pagetmp);
			tabheader(empty($u_mtitle) ? lang('replylist') : $u_mtitle,'','',30);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),lang('member'),);
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
			foreach($ucotypes as $k => $v) if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'reply') $cy_arr["ccid$k"] = $v['cname'];
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('aread',$u_lists)) $cy_arr[] = lang('read');
			if(in_array('areply',$u_lists)) $cy_arr[] = lang('areply');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('addtime');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('updatetime');
			if(in_array('refreshdate',$u_lists)) $cy_arr[] = lang('retime');
			$cy_arr[] = lang('edit');
			trcategory($cy_arr);
	
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				cu_checkend($row,'reply');
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">$row[asubject]</a>";
				$catalogstr = @$catalogs[$row['caid']]['title'];
				$mnamestr = $row['mname'];
				$channelstr = @$channels[$row['chid']]['cname'];
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
				$adminstr = '';
				if(empty($u_imuids)){
					$adminstr .= "<a href=\"?action=reply&cid=$row[cid]&amode=1\" onclick=\"return floatwin('open_inarchive',this)\">".lang('edit')."</a>&nbsp; ";
				}else{
					foreach($u_imuids as $k) if(!empty($inmurls[$k])) $adminstr .= "<a href=\"".$inmurls[$k]['url']."$row[cid]&amode=1\" onclick=\"return floatwin('open_inarchive',this)\">".$inmurls[$k]['cname']."</a>&nbsp; ";
				}
				$itemstr .= "<tr><td class=\"item\">$selectstr</td><td class=\"item2\">$subjectstr</td><td class=\"item\">$mnamestr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"item\">$catalogstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"item\">$channelstr</td>\n";
				foreach($ucotypes as $k => $v) if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'reply') $itemstr .= "<td class=\"item\">".${'uccid'.$k.'str'}."</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"item\">$checkstr</td>\n";
				if(in_array('aread',$u_lists)) $itemstr .= "<td class=\"item\">$readstr</td>\n";
				if(in_array('areply',$u_lists)) $itemstr .= "<td class=\"item\">$areplystr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"item\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"item\">$updatedatestr</td>\n";
				if(in_array('refreshdate',$u_lists)) $itemstr .= "<td class=\"item\">$refreshdatestr</td>\n";
				$itemstr .= "<td class=\"item\">$adminstr</td>\n";
				$itemstr .= "</tr>\n";
			}
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts,$mrowpp,$page,"?action=areplys$filterstr");
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
		mcmessage('replysetsucceed',"?action=areplys$filterstr&page=$page");
	}
}else include(M_ROOT.$u_tplname);
?>