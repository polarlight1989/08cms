<?
!defined('M_COM') && exit('No Permission');
load_cache('mchannels,currencys,mcommus,mrfields');
include_once M_ROOT."./include/mcuedit.cls.php";
//分析页面设置
$nmuid = empty($nmuid) ? 0 : max(0,intval($nmuid));
$u_checked = -1;
if($nmuid && $u_url = read_cache('murl',$nmuid)){
	$u_tplname = @$u_url['tplname'];
	$u_mtitle = @$u_url['mtitle'];
	$u_guide = @$u_url['guide'];
	foreach(array('checked',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
	$vars = array('chids','cuids','filters','lists','operates',);
	foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_filters) && $u_filters = array('check','channel',);
empty($u_lists) && $u_lists = array('uclass','channel','check',);
$page = empty($page) ? 1 : max(1, intval($page));
submitcheck('bfilter') && $page = 1;
$checked = isset($checked) ? $checked : '-1';
$aread = isset($aread) ? $aread : '-1';
$areply = isset($areply) ? $areply : '-1';
$keyword = empty($keyword) ? '' : $keyword;
$chid = empty($chid) ? 0 : max(0,intval($chid));

$wheresql = "cu.mid='$memberid'";
$fromsql = "FROM {$tblprefix}mreplys cu LEFT JOIN {$tblprefix}members m ON m.mid=cu.fromid";
if(!empty($u_cuids)) $wheresql .= " AND cu.cuid ".multi_str($u_cuids);
if($chid){
	if(!empty($u_chids) && !in_array($chid,$u_chids)) $no_list = true;
	else $wheresql .= " AND m.mchid='$chid'";
}elseif(!empty($u_chids)) $wheresql .= " AND m.mchid ".multi_str($u_chids);
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
$keyword && $wheresql .= " AND cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";

$filterstr = '';
foreach(array('nmuid','chid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
foreach(array('checked','aread','areply',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
if(!submitcheck('barcsedit')){
	if(empty($u_tplname)){
		echo form_str($action.'archivesedit',"?action=amreplys&nmuid=$nmuid&page=$page");
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
			$areplyarr = array('-1' => lang('areply_state'),'0' => lang('noareply'),'1' => lang('areplyed'));
			echo "<select style=\"vertical-align: middle;\" name=\"areply\">".makeoption($areplyarr,$areply)."</select>&nbsp; ";
		}
		//栏目搜索
		if(in_array('channel',$u_filters)){
			$mchidsarr = array('0' => lang('memberchannel')) + mchidsarr();
			echo "<select style=\"vertical-align: middle;\" name=\"chid\">".makeoption($mchidsarr,$chid)."</select>&nbsp; ";
		}
		echo strbutton('bfilter','filter0').'</td></tr>';
		tabfooter();
	
		$pagetmp = $page;
		do{
			$query = $db->query("SELECT cu.*,m.mchid $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
			$pagetmp--;
		}while(!$db->num_rows($query) && $pagetmp);
		tabheader(empty($u_mtitle) ? lang('replylist') : $u_mtitle,'','',30);
		$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('member'),'item2'),);
		if(in_array('channel',$u_lists)) $cy_arr[] = lang('memberchannel');//模型与合辑类型综合在一起
		if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
		if(in_array('aread',$u_lists)) $cy_arr[] = lang('read');
		if(in_array('areply',$u_lists)) $cy_arr[] = lang('areply');
		if(in_array('adddate',$u_lists)) $cy_arr[] = lang('addtime');
		$cy_arr[] = lang('edit');
		trcategory($cy_arr);

		$itemstr = '';
		while($row = $db->fetch_array($query)){
			$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
			$mnamestr = $row['fromname'];
			$channelstr = @$mchannels[$row['mchid']]['cname'];
			$checkstr = $row['checked'] ? 'Y' : '-';
			$readstr = $row['aread'] ? 'Y' : '-';
			$areplystr = $row['areply'] ? 'Y' : '-';
			$adddatestr = $row['createdate'] ? date('Y-m-d',$row['createdate']) : '-';
			$editstr = "<a href=\"?action=mreply&cid=$row[cid]&amode=1\" onclick=\"return floatwin('open_inarchive',this)\">".lang('edit')."</a>&nbsp; ";

			$itemstr .= "<tr><td class=\"item\">$selectstr</td><td class=\"item2\">$mnamestr</td>\n";
			if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"item\">$channelstr</td>\n";
			if(in_array('check',$u_lists)) $itemstr .= "<td class=\"item\">$checkstr</td>\n";
			if(in_array('aread',$u_lists)) $itemstr .= "<td class=\"item\">$readstr</td>\n";
			if(in_array('areply',$u_lists)) $itemstr .= "<td class=\"item\">$areplystr</td>\n";
			if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"item\">$adddatestr</td>\n";
			$itemstr .= "<td class=\"item\">$editstr</td>\n";
			$itemstr .= "</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=amreplys$filterstr");
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
	if(empty($selectid)) mcmessage('confirmselectreply',M_REFERER);
	$uedit = new cls_mcuedit;
	foreach($selectid as $cid){
		if($errno = $uedit->read($cid,'reply')) continue;
		if(!empty($arcdeal['delete'])){
			$uedit->delete(1);
			continue;
		}
		if(!empty($arcdeal['uncheck'])){
			$uedit->updatefield('checked',0);
		}elseif(!empty($arcdeal['check'])){
			$uedit->updatefield('checked',1);
		}
		$uedit->updatedb();
		$uedit->init();
	}
	mcmessage('replysetsucceed',"?action=amreplys$filterstr&page=$page");
}
?>