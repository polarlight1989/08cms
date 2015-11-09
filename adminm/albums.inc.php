<?php
!defined('M_COM') && exit('No Permission');
include_once M_ROOT."./include/arcedit.cls.php";
load_cache('channels,cotypes,acatalogs,');
//分析页面设置
$nmuid = empty($nmuid) ? 0 : max(0,intval($nmuid));
$u_checked = $u_valid = -1;
if($nmuid && $u_url = read_cache('murl',$nmuid)){
	$u_tplname = $u_url['tplname'];
	$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
	$u_mtitle = $u_url['mtitle'];
	$u_guide = $u_url['guide'];
	foreach(array('checked','valid',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
	$u_url['setting']['sids'] = str_replace('m','0',$u_url['setting']['sids']);
	$vars = array('caids','chids','sids','filters','lists',);
	foreach($cotypes as $k => $v) $vars[] = 'ccids'.$k;
	foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_filters) && $u_filters = array('check','catalog',);
empty($u_lists) && $u_lists = array('catalog','channel','check','view','add',);

if(empty($u_tplname) || !empty($u_onlyview)){
	$catalogs = &$acatalogs;
	$ochids = array();
	foreach($channels as $k => $v){
		$v = read_cache('channel',$k);
		if($v['isalbum'] && !$v['oneuser'] && !$v['onlyload'] && $curuser->pmbypmids('aadd',$v['apmid'])) $ochids[] = $k;
	}
	$u_chids = empty($u_chids) ? $ochids : array_intersect($u_chids,$ochids);
	if(!$u_chids) mcmessage('noopenalbum');
	
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$caid = empty($caid) ? 0 : max(0,intval($caid));
	$valid = isset($valid) ? $valid : '-1';
	$checked = isset($checked) ? $checked : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "chid ".multi_str($u_chids)." AND checked='1' AND abover=0";
	$fromsql = "FROM {$tblprefix}archives";
	
	//栏目范围
	$caids = array();
	if($caid){
		$caids = cnsonids($caid,$catalogs);
		if(!empty($u_caids)) $caids = array_intersect($caids,$u_caids);
	}elseif(!empty($u_caids)) $caids = $u_caids;
	if($caids && ($cnsql = cnsql(0,$caids,''))) $wheresql .= " AND $cnsql";
	elseif(!empty($u_caids)) $no_list = true;
	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= " AND checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= " AND checked='$u_checked'";
	//有效期状态范围
	if($valid != -1){
		if(($u_valid != -1) && $valid != $u_valid) $no_list = true;
		else $wheresql .= $valid ? " AND (enddate='0' OR enddate>'$timestamp')" : " AND enddate>'0' AND enddate<'$timestamp'";
	}elseif($u_valid != -1) $wheresql .= $u_valid ? " AND (enddate='0' OR enddate>'$timestamp')" : " AND enddate>'0' AND enddate<'$timestamp'";
	//搜索关键词处理
	$keyword && $wheresql .= " AND (mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
	
	//子站范围
	if(!empty($u_sids)) $wheresql .= " AND sid ".multi_str($u_sids);
	
	$filterstr = '';
	foreach(array('nmuid','caid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked','valid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	
	foreach($cotypes as $coid => $cotype){
		if(!empty(${'u_ccids'.$coid})){
			$ccids = ${'u_ccids'.$coid};
			if($cnsql = cnsql($coid,$ccids,'')) $wheresql .= " AND $cnsql";
		}
	}
	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	
	if(empty($u_tplname)){
		echo form_str($action.'archivesedit',"?action=albums&nmuid=$nmuid&page=$page");
		tabheader_e();
		echo "<tr><td class=\"item2\">";
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
		//审核状态
		if(empty($u_filters) || in_array('check',$u_filters)){
			$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
			echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
		}
		//有效状态
		if(empty($u_filters) || in_array('valid',$u_filters)){
			$validarr = array('-1' => lang('nolimit').lang('available'),'0' => lang('invalid'),'1' => lang('available'));
			echo "<select style=\"vertical-align: middle;\" name=\"valid\">".makeoption($validarr,$valid)."</select>&nbsp; ";
		}
		//栏目搜索
		if(empty($u_filters) || in_array('catalog',$u_filters)){
			echo '<span>'.cn_select('caid',$caid,-1,0,0,lang('p_choose'),1,0,1).'</span>';
		}
		echo strbutton('bfilter','filter0').'</td></tr>';
		tabfooter();
	
		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * $fromsql $wheresql ORDER BY aid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
			$pagetmp--;
		}while(!$db->num_rows($query) && $pagetmp);
	
		tabheader(empty($u_mtitle) ? lang('publicalbumlist') : $u_mtitle,'','',30);
		$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('title'),'item2'),);
		if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
		if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
		if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
		if(in_array('valid',$u_lists)) $cy_arr[] = lang('available');
		if(in_array('clicks',$u_lists)) $cy_arr[] = lang('click');
		if(in_array('comments',$u_lists)) $cy_arr[] = lang('comment');
		if(in_array('replys',$u_lists)) $cy_arr[] = lang('reply');
		if(in_array('praises',$u_lists)) $cy_arr[] = lang('praise');
		if(in_array('debases',$u_lists)) $cy_arr[] = lang('debase');
		if(in_array('adddate',$u_lists)) $cy_arr[] = lang('addtime');
		if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('updatetime');
		if(in_array('refreshdate',$u_lists)) $cy_arr[] = lang('retime');
		if(in_array('enddate',$u_lists)) $cy_arr[] = lang('endtime');
		if(in_array('view',$u_lists)) $cy_arr[] = lang('message');
		$cy_arr[] = lang('add');
	
		trcategory($cy_arr);
	
		$itemstr = '';
		while($row = $db->fetch_array($query)){
			$channel = read_cache('channel',$row['chid']);
			$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\">";
			$row['arcurl'] = view_arcurl($row);
			$subjectstr = ($row['thumb'] ? lang('imged') : '')." &nbsp;<a href=$row[arcurl] target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
			$catalogstr = @$catalogs[$row['caid']]['title'];
			$channelstr = @$channel['cname'];
			foreach($cotypes as $k => $v){
				${'ccid'.$k.'str'} = '';
				if(!$v['self_reg'] && in_array('ccid'.$k,$u_lists) && $row['ccid'.$k]){
					$coclasses = read_cache('coclasses',$k);
					${'ccid'.$k.'str'} = cnstitle($row['ccid'.$k],$v['asmode'],$coclasses);
				}
			}
			$checkstr = $row['checked'] ? 'Y' : '-';
			$validstr = !$row['enddate'] || $row['enddate'] > $timestamp ? 'Y' : '-';
			$clicksstr = $row['clicks'];
			$commentsstr = $row['comments'];
			$replysstr = $row['replys'];
			$praisesstr = $row['praises'];
			$debasesstr = $row['debases'];
			$adddatestr = $row['createdate'] ? date('Y-m-d',$row['createdate']) : '-';
			$updatedatestr = $row['updatedate'] ? date('Y-m-d',$row['updatedate']) : '-';
			$refreshdatestr = $row['refreshdate'] ? date('Y-m-d',$row['refreshdate']) : '-';
			$enddatestr = $row['enddate'] ? date('Y-m-d',$row['enddate']) : '-';
			$viewstr = "<a id=\"{$action}_info_$row[aid]\" href=\"?action=arcview&aid=$row[aid]\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";
			$adminstr = "<a href=\"tools/addpre.php?aid=$row[aid]\" onclick=\"return floatwin('open_inarchive',this)\">".lang('add')."</a>";
	
			$itemstr .= "<tr><td class=\"item\">$selectstr</td><td class=\"item2\">$subjectstr</td>\n";
			if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"item\">$catalogstr</td>\n";
			if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"item\">$channelstr</td>\n";
			foreach($cotypes as $k => $v) if(!$v['self_reg'] && in_array('ccid'.$k,$u_lists)) $itemstr .= "<td class=\"item\">".${'ccid'.$k.'str'}."</td>\n";
			if(in_array('check',$u_lists)) $itemstr .= "<td class=\"item\">$checkstr</td>\n";
			if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"item\">$validstr</td>\n";
			if(in_array('clicks',$u_lists)) $itemstr .= "<td class=\"item\">$clicksstr</td>\n";
			if(in_array('comments',$u_lists)) $itemstr .= "<td class=\"item\">$commentsstr</td>\n";
			if(in_array('replys',$u_lists)) $itemstr .= "<td class=\"item\">$replysstr</td>\n";
			if(in_array('praises',$u_lists)) $itemstr .= "<td class=\"item\">$praisesstr</td>\n";
			if(in_array('debases',$u_lists)) $itemstr .= "<td class=\"item\">$debasesstr</td>\n";
			if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"item\">$adddatestr</td>\n";
			if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"item\">$updatedatestr</td>\n";
			if(in_array('refreshdate',$u_lists)) $itemstr .= "<td class=\"item\">$refreshdatestr</td>\n";
			if(in_array('enddate',$u_lists)) $itemstr .= "<td class=\"item\">$enddatestr</td>\n";
			if(in_array('view',$u_lists)) $itemstr .= "<td class=\"item\">$viewstr</td>\n";
			$itemstr .= "<td class=\"item\">$adminstr</td>\n";;
			$itemstr .= "</tr>\n";
	
	
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=albums$filterstr");
		echo $itemstr;
		tabfooter();
		echo $multi;
		m_guide(@$u_guide);
	}else include(M_ROOT.$u_tplname);
}else include(M_ROOT.$u_tplname);
?>
