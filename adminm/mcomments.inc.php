<?
!defined('M_COM') && exit('No Permission');
load_cache('mchannels,currencys,mcommus,mcfields');
include_once M_ROOT."./include/mcuedit.cls.php";
//个人分类
$uclasses = loaduclasses($curuser->info['mid']);
$ocuids = $ucidsarr = array();
foreach($mcommus as $k => $v) if($v['cclass'] == 'comment') $ocuids[] = $k;
foreach($uclasses as $k => $v) if(in_array(-$v['cuid'],$ocuids)) $ucidsarr[$k] = $v['title'];
//分析页面设置
$nmuid = empty($nmuid) ? 0 : max(0,intval($nmuid));
$u_checked = -1;
if($nmuid && $u_url = read_cache('murl',$nmuid)){
	$u_tplname = @$u_url['tplname'];
	$u_mtitle = @$u_url['mtitle'];
	$u_guide = @$u_url['guide'];
	foreach(array('checked',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
	$vars = array('chids','cuids','filters','lists',);
	foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_filters) && $u_filters = array('check','channel',);
empty($u_lists) && $u_lists = array('uclass','channel','check',);
$page = empty($page) ? 1 : max(1, intval($page));
submitcheck('bfilter') && $page = 1;
$checked = isset($checked) ? $checked : '-1';
$keyword = empty($keyword) ? '' : $keyword;
$chid = empty($chid) ? 0 : max(0,intval($chid));

$wheresql = "cu.fromid='$memberid'";
$fromsql = "FROM {$tblprefix}mcomments cu LEFT JOIN {$tblprefix}members m ON m.mid=cu.mid";
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
//搜索关键词处理
$keyword && $wheresql .= " AND cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";

$filterstr = '';
foreach(array('nmuid','chid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
foreach(array('checked',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
if(!submitcheck('barcsedit')){
	if(empty($u_tplname)){
		echo form_str($action.'archivesedit',"?action=mcomments&nmuid=$nmuid&page=$page");
		tabheader_e();
		echo "<tr><td class=\"item2\">";
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
		if(in_array('check',$u_filters)){
			$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
			echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
		}
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
		tabheader(empty($u_mtitle) ? lang('commentlist') : $u_mtitle,'','',30);
		$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('member'),'item2'),);
		if(in_array('uclass',$u_lists)) $cy_arr[] = lang('mycoclass');
		if(in_array('channel',$u_lists)) $cy_arr[] = lang('memberchannel');//模型与合辑类型综合在一起
		if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
		if(in_array('adddate',$u_lists)) $cy_arr[] = lang('addtime');
		$cy_arr[] = lang('edit');
		trcategory($cy_arr);

		$itemstr = '';
		while($row = $db->fetch_array($query)){
			$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
			$mnamestr = $row['mname'];
			$uclassstr = $row['ucid'] ? $ucidsarr[$row['ucid']] : '-';
			$channelstr = @$mchannels[$row['mchid']]['cname'];
			$checkstr = $row['checked'] ? 'Y' : '-';
			$adddatestr = $row['createdate'] ? date('Y-m-d',$row['createdate']) : '-';
			$editstr = "<a href=\"?action=mcomment&cid=$row[cid]\" onclick=\"return floatwin('open_inarchive',this)\">".lang('edit')."</a>&nbsp; ";

			$itemstr .= "<tr><td class=\"item\">$selectstr</td><td class=\"item2\">$mnamestr</td>\n";
			if(in_array('uclass',$u_lists)) $itemstr .= "<td class=\"item\">$uclassstr</td>\n";
			if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"item\">$channelstr</td>\n";
			if(in_array('check',$u_lists)) $itemstr .= "<td class=\"item\">$checkstr</td>\n";
			if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"item\">$adddatestr</td>\n";
			$itemstr .= "<td class=\"item\">$editstr</td>\n";
			$itemstr .= "</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=mcomments$filterstr");
		echo $itemstr;
		tabfooter();
		echo $multi;

		//操作区
		tabheader(lang('operateitem'));
		$s_arr = array();
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
	if(empty($arcdeal)) mcmessage('selectopeitem',M_REFERER);
	if(empty($selectid)) mcmessage('confirmselectcomment',M_REFERER);
	$uedit = new cls_mcuedit;
	foreach($selectid as $cid){
		if($errno = $uedit->read($cid,'comment')) continue;
		if(!empty($arcdeal['delete'])){
			$uedit->delete(1);
			continue;
		}
		if(!empty($arcdeal['ucid'])){
			$uedit->updatefield('ucid',$arcucid);
		}
		$uedit->updatedb();
		$uedit->init();
	}
	mcmessage('commentsetsucceed',"?action=mcomments$filterstr&page=$page");
}
?>