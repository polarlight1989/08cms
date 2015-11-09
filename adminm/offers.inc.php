<?
!defined('M_COM') && exit('No Permission');
load_cache('channels,acatalogs,currencys,commus,ucotypes,ofields,inmurls');
//分析页面设置
$nmuid = empty($nmuid) ? 0 : max(0,intval($nmuid));
$u_checked = $u_valid = -1;
if($nmuid && $u_url = read_cache('murl',$nmuid)){
	$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
	$u_mtitle = @$u_url['mtitle'];
	$u_guide = @$u_url['guide'];
	foreach(array('checked','valid',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
	$vars = array('caids','chids','cuids','filters','lists','operates','imuids',);
	foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_filters) && $u_filters = array('check','catalog',);
empty($u_lists) && $u_lists = array('catalog','uclass','channel','check',);
if(empty($u_tplname) || !empty($u_onlyview)){
	include_once M_ROOT."./include/cuedit.cls.php";
	$catalogs = &$acatalogs;
	//关于文档的个人分类
	$uclasses = loaduclasses($curuser->info['mid']);
	$ocuids = $ucidsarr = array();
	foreach($commus as $k => $v) if($v['cclass'] == 'offer') $ocuids[] = $k;
	foreach($uclasses as $k => $v) if(in_array($v['cuid'],$ocuids)) $ucidsarr[$k] = $v['title'];
	
	$page = empty($page) ? 1 : max(1, intval($page));
	submitcheck('bfilter') && $page = 1;
	$caid = empty($caid) ? 0 : max(0,intval($caid));
	$checked = isset($checked) ? $checked : '-1';
	$valid = isset($valid) ? $valid : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	
	$wheresql = "cu.mid='$memberid'";
	$fromsql = "FROM {$tblprefix}offers cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";
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
	//有效期状态范围
	if($valid != -1){
		if(($u_valid != -1) && $valid != $u_valid) $no_list = true;
		else $wheresql .= $valid ? " AND (cu.enddate='0' OR cu.enddate>'$timestamp')" : " AND cu.enddate>'0' AND cu.enddate<'$timestamp'";
	}elseif($u_valid != -1) $wheresql .= $u_valid ? " AND (cu.enddate='0' OR cu.enddate>'$timestamp')" : " AND cu.enddate>'0' AND cu.enddate<'$timestamp'";
	//搜索关键词处理
	$keyword && $wheresql .= " AND a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	$filterstr = '';
	foreach(array('nmuid','caid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked','valid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	//处理ucotype的筛选
	foreach($ucotypes as $k => $v){
		if(!empty(${'uccid'.$k}) && $v['cclass'] == 'offer'){
			$filterstr .= "&uccid$k=".${'uccid'.$k};
			$wheresql .= " AND cu.uccid$k='".${'uccid'.$k}."'";
		}
	}
	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($action.'archivesedit',"?action=offers&nmuid=$nmuid&page=$page");
			tabheader_e();
			echo "<tr><td class=\"item2\">";
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//审核状态
			if(in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			//有效状态
			if(in_array('valid',$u_filters)){
				$validarr = array('-1' => lang('nolimit').lang('available'),'0' => lang('invalid'),'1' => lang('available'));
				echo "<select style=\"vertical-align: middle;\" name=\"valid\">".makeoption($validarr,$valid)."</select>&nbsp; ";
			}
			//栏目搜索
			if(in_array('catalog',$u_filters)){
				$caidsarr = array('0' => lang('catalog')) + caidsarr($catalogs);
				echo "<select style=\"vertical-align: middle;\" name=\"caid\">".makeoption($caidsarr,$caid)."</select>&nbsp; ";
			}
			foreach($ucotypes as $k => $v){//报价类系的搜索
				if(in_array('uccid'.$k,$u_filters) && $v['cclass'] == 'offer'){
					$ucoclasses = read_cache('ucoclasses',$k);
					$uccidsarr = array(0 => lang('nolimit').$v['cname']);
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
			tabheader(empty($u_mtitle) ? lang('offerlist') : $u_mtitle,'','',30);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('product'),);
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('uclass',$u_lists)) $cy_arr[] = lang('mycoclass');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
			foreach($ucotypes as $k => $v) if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'offer') $cy_arr["ccid$k"] = $v['cname'];
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('valid',$u_lists)) $cy_arr[] = lang('available');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('addtime');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('updatetime');
			if(in_array('refreshdate',$u_lists)) $cy_arr[] = lang('retime');
			if(in_array('enddate',$u_lists)) $cy_arr[] = lang('endtime');
			if(in_array('storage',$u_lists)) $cy_arr[] = lang('stock');
			$cy_arr[] = lang('price');
			$cy_arr[] = lang('admin');
			trcategory($cy_arr);
	
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				cu_checkend($row,'offer');
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">".mhtmlspecialchars($row['asubject'])."</a>";
				$catalogstr = @$catalogs[$row['caid']]['title'];
				$uclassstr = $row['ucid'] ? $ucidsarr[$row['ucid']] : '-';
				$channelstr = @$channels[$row['chid']]['cname'];
				foreach($ucotypes as $k => $v){
					if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'offer'){
						$ucoclasses = read_cache('ucoclasses',$k);
						${'uccid'.$k.'str'} = @$ucoclasses[$row['uccid'.$k]]['title'];
					}
				}
				$checkstr = $row['checked'] ? 'Y' : '-';
				$validstr = !$row['enddate'] || $row['enddate'] > $timestamp ? 'Y' : '-';
				$adddatestr = $row['ucreatedate'] ? date('Y-m-d',$row['ucreatedate']) : '-';
				$updatedatestr = $row['updatedate'] ? date('Y-m-d',$row['updatedate']) : '-';
				$refreshdatestr = $row['refreshdate'] ? date('Y-m-d',$row['refreshdate']) : '-';
				$enddatestr = $row['enddate'] ? date('Y-m-d',$row['enddate']) : '-';
				$storagestr = "<input type=\"text\" size=\"4\" name=\"itemsnew[".$row['cid']."][storage]\" value=\"$row[storage]\">";
				$opricestr = "<input type=\"text\" size=\"4\" name=\"itemsnew[".$row['cid']."][oprice]\" value=\"$row[oprice]\">";
				$adminstr = '';
				if(empty($u_imuids)){
					$adminstr .= "<a href=\"?action=offer&cid=$row[cid]\" onclick=\"return floatwin('open_inarchive',this)\">".lang('edit')."</a>&nbsp; ";
				}else{
					foreach($u_imuids as $k) if(!empty($inmurls[$k])) $adminstr .= "<a href=\"".$inmurls[$k]['url']."$row[cid]\" onclick=\"return floatwin('open_inarchive',this)\">".$inmurls[$k]['cname']."</a>&nbsp; ";
				}
				$itemstr .= "<tr><td class=\"item\">$selectstr</td><td class=\"item2\">$subjectstr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"item\">$catalogstr</td>\n";
				if(in_array('uclass',$u_lists)) $itemstr .= "<td class=\"item\">$uclassstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"item\">$channelstr</td>\n";
				foreach($ucotypes as $k => $v) if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'offer') $itemstr .= "<td class=\"item\">".${'uccid'.$k.'str'}."</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"item\">$checkstr</td>\n";
				if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"item\">$validstr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"item\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"item\">$updatedatestr</td>\n";
				if(in_array('refreshdate',$u_lists)) $itemstr .= "<td class=\"item\">$refreshdatestr</td>\n";
				if(in_array('enddate',$u_lists)) $itemstr .= "<td class=\"item\">$enddatestr</td>\n";
				if(in_array('storage',$u_lists)) $itemstr .= "<td class=\"item\">$storagestr</td>\n";
				$itemstr .= "<td class=\"item\">$opricestr</td>\n";
				$itemstr .= "<td class=\"item\">$adminstr</td>\n";
				$itemstr .= "</tr>\n";
			}
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts,$mrowpp,$page,"?action=offers$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
	
			//操作区
			tabheader(lang('operateitem'));
			$s_arr = array();
			if(empty($u_operates) || in_array('readd',$u_operates)) $s_arr['readd'] = lang('readd');
			if(empty($u_operates) || in_array('delete',$u_operates)) $s_arr['delete'] = lang('delete');
			if($s_arr){
				$soperatestr = '';
				foreach($s_arr as $k => $v) $soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
				trbasic(lang('choose_item'),'',$soperatestr,'');
			}
			if(empty($u_operates) || in_array('uclass',$u_operates)){
				trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[ucid]\" value=\"1\">&nbsp;".lang('set').lang('mycoclass'),'arcucid',makeoption(array('0' => lang('cancelcoclass')) + $ucidsarr),'select');
			}
			tabfooter('barcsedit');
			m_guide(@$u_guide);
		}else include(M_ROOT.$u_tplname);
	}else{
		if(empty($arcdeal) && empty($itemsnew)) mcmessage('selectopeitem',M_REFERER);
		if(empty($selectid)) mcmessage('conoffer',M_REFERER);
		$uedit = new cls_cuedit;
		foreach($selectid as $cid){
			if($errno = $uedit->read($cid,'offer')) continue;
			if(!empty($arcdeal['delete'])){
				$uedit->delete(1);
				continue;
			}
			if($curuser->pmbypmids('cuadd',$uedit->commu['setting']['apmid'])){
				if(!empty($arcdeal['readd'])){
					$uedit->updatefield('refreshdate',$timestamp);
					$uedit->updatefield('enddate',empty($uedit->commu['setting']['vdays']) ? 0 : $timestamp + 86400 * $uedit->commu['setting']['vdays']);
				}
				if(isset($itemsnew[$cid]['oprice'])){//更新报价算一次重发
					$uedit->updatefield('oprice',max(0,round($itemsnew[$cid]['oprice'],2)));
					$uedit->updatefield('refreshdate',$timestamp);
					$uedit->updatefield('enddate',empty($uedit->commu['setting']['vdays']) ? 0 : $timestamp + 86400 * $uedit->commu['setting']['vdays']);
				}
			}
			if(isset($itemsnew[$cid]['storage'])){
				$uedit->updatefield('storage',max(-1,intval($itemsnew[$cid]['storage'])));
			}
			if(!empty($arcdeal['ucid'])){
				$uedit->updatefield('ucid',$arcucid);
			}
			$uedit->updatedb();
			$uedit->init();
		}
		mcmessage('offopesucce',"?action=offers$filterstr&page=$page");
	}
}else include(M_ROOT.$u_tplname);
?>