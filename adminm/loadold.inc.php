<?php
!defined('M_COM') && exit('No Permission');
load_cache('cotypes,channels,currencys,permissions,inmurls,acatalogs');
//分析页面设置
$nimuid = empty($nimuid) ? 0 : $nimuid;
if($nimuid && $u_url = read_cache('inmurl',$nimuid)){
	$u_tplname = $u_url['tplname'];
	$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
	$u_mtitle = @$u_url['mtitle'];
	$u_guide = @$u_url['guide'];
	$vars = array('sids','chids','filters','lists',);
	$u_url['setting']['sids'] = str_replace('m','0',$u_url['setting']['sids']);
	foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_lists) && $u_lists = array('catalog','channel','view',);
if(empty($u_tplname) || !empty($u_onlyview)){
	include_once M_ROOT."./include/parse.fun.php";
	include_once M_ROOT."./include/arcedit.cls.php";
	include_once M_ROOT."./include/commu.fun.php";
	
	$aid = empty($aid) ? 0 : max(0,intval($aid));
	if(!$aid) mcmessage('confchoosarchi');
	$aedit = new cls_arcedit;
	$aedit->set_aid($aid);
	$aedit->basic_data(0);
	$channel = &$aedit->channel;
	if(!$aedit->aid) mcmessage('confchoosarchi');
	
	
	if(!$channel['isalbum']) mcmessage('choosealbum');
	if($aedit->archive['abover']) mcmessage('albumisover');
	$catalogs = &$acatalogs;
	$caid = empty($caid) ? 0 : $caid;
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$chid = empty($chid) ? 0 : max(0,intval($chid));
	$nsid = isset($nsid) ? intval($nsid) : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	
	//模型范围
	if($chid){
		if(!empty($u_chids) && !in_array($chid,$u_chids)) $no_list = true;
		$u_chids = array($chid);
	}
	if(!empty($no_list) || !($wheresql = $aedit->inalbumsqlstr('a.',@$u_chids))) mcmessage('noarcoralbumload');
	$fromsql = "FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}albums b ON (b.aid=a.aid AND b.pid='$aid')";
	$wheresql .= " AND b.aid IS NULL";//已经在合辑中的内容不再列出
	
	//栏目范围
	$caids = array(-1);
	if(!empty($caid)){
		$caids = cnsonids($caid,$catalogs);
		$wheresql .= " AND a.caid ".multi_str($caids);
	}
	
	//子站范围
	if($nsid != -1){
		if(!empty($u_sids) && !in_array($nsid,$u_sids)) $no_list = true;
		else $wheresql .= " AND a.sid='$nsid'";
	}elseif(!empty($u_sids)) $wheresql .= " AND a.sid ".multi_str($u_sids);
	
	//搜索关键词处理
	$keyword && $wheresql .= " AND (a.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
	
	$filterstr = '';
	foreach(array('nimuid','caid','chid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('nsid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	
	$wheresql = empty($no_list) ? $wheresql : 'WHERE 1=0';
	//echo $wheresql;
	if(!submitcheck('bloadold')){
		if(empty($u_tplname)){
			//需要归入的合辑管理区***********************************************************
			echo form_str($action.'albumadmin',"?action=loadold&aid=$aid&page=$page$param_suffix");
			//搜索区块
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"item2\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
	
			//模型搜索
			if(empty($u_filters) || in_array('channel',$u_filters)){
				$chidsarr = array('0' => lang('all_channel')) + chidsarr(1);
				echo "<select style=\"vertical-align: middle;\" name=\"chid\">".makeoption($chidsarr,$chid)."</select>&nbsp; ";
			}
			//所在子站搜索
			if(empty($u_filters) || in_array('subsite',$u_filters)){
				$sidsarr = array('-1' => lang('nolimit').lang('subsite'),'0' => lang('msite')) + sidsarr();
				echo "<select style=\"vertical-align: middle;\" name=\"nsid\">".makeoption($sidsarr,$nsid)."</select>&nbsp; ";
			}
			//栏目搜索
			if(empty($u_filters) || in_array('catalog',$u_filters)){
				$caidsarr = array('0' => lang('all_catalog')) + caidsarr($catalogs);
				echo "<select style=\"vertical-align: middle;\" name=\"caid\">".makeoption($caidsarr,$caid)."</select>&nbsp; ";
			}
			echo strbutton('bfilter','filter0').'</td></tr>';
	
			//某些固定页面参数
			trhidden('nimuid',$nimuid);
			tabfooter();
	
			//列表区	
			tabheader((empty($u_mtitle) ? lang('content_load_list') : $u_mtitle).'&nbsp; -&nbsp; '.$aedit->archive['subject'],'','',9);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('view',$u_lists)) $cy_arr[] = lang('message');
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
			if(in_array('subsite',$u_lists)) $cy_arr[] = lang('subsite');
			trcategory($cy_arr);
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT a.aid,a.sid,a.createdate,a.caid,a.chid,a.subject,a.customurl,a.mname $fromsql $wheresql ORDER BY a.aid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);
	
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$channel = read_cache('channel',$row['chid']);
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\">";
				$mnamestr = $row['mname'];
				$subsitestr = $row['sid'] ? $subsites[$row['sid']]['sitename'] : lang('msite');
				$catalogstr = @$acatalogs[$row['caid']]['title'];
				$channelstr = @$channel['cname'];
				$viewstr = "<a id=\"{$action}_info_$row[aid]\" href=\"?action=arcview&aid=$row[aid]$param_suffix\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";
	
				$itemstr .= "<tr class=\"txt\"><td class=\"item\" >$selectstr</td><td class=\"item2\">$subjectstr</td>\n";
				if(in_array('view',$u_lists)) $itemstr .= "<td class=\"item\">$viewstr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"item\">$catalogstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"item\">$mnamestr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"item\">$channelstr</td>\n";
				if(in_array('subsite',$u_lists)) $itemstr .= "<td class=\"item\">$subsitestr</td>\n";
				$itemstr .= "</tr>\n";
			}
	
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $mrowpp, $page, "?action=loadold&aid=$aid$param_suffix$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			echo '<br><br>'.strbutton('bloadold',lang('load')).'</form>';
			m_guide(@$u_guide);
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($selectid)) mcmessage('selectalbum');
		$aedit = new cls_arcedit;
		foreach($selectid as $k){
			$aedit->set_aid($k);
			$aedit->set_album($aid,1);
			$aedit->init();
		}
		mcmessage('setalbumfinish',"?action=loadold&aid=$aid$param_suffix&page=$page$filterstr");
	}
}else include(M_ROOT.$u_tplname);

?>
