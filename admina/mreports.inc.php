<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('member') || amessage('no_apermission');
$backamember = backallow('amember');
if($action == 'mreportsedit'){
	//分析页面设置
	$nauid = empty($nauid) ? 0 : $nauid;
	$u_checked = -1;
	if($nauid && $u_url = read_cache('aurl',$nauid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$vars = array('filters','lists','operates',);
		foreach($grouptypes as $k => $v) $vars[] = 'ugids'.$k;
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
//	empty($u_lists) && 
	$u_lists = array('edit','usergroup','reportor','reporttime');
//	empty($u_filters) && 
	$u_filters = array('check','ugid1','date',);

	$mchid = empty($mchid) ? 0 : max(0,intval($mchid));
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : 1;
	$keyword = empty($keyword) ? '' : $keyword;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
	$wheresql = '';//非管理员
	$fromsql = "FROM {$tblprefix}mreports";

	//类型范围
//	if(!empty($mchid)){
//		if(!array_intersect(array(-1,$mchid),$a_mchids)) $no_list = 1;
//		else $wheresql .= ($wheresql ? ' AND ' : '')."mchid='$mchid'";
//	}elseif(empty($a_mchids)){
//		$no_list = 1;
//	}elseif(!in_array(-1,$a_mchids) && $a_mchids) $wheresql .= ($wheresql ? ' AND ' : '')."mchid ".multi_str($a_mchids);
	//搜索关键词处理
	$keyword && $wheresql .= ($wheresql ? ' AND ' : '')."mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
//	$indays && $wheresql .= ($wheresql ? ' AND ' : '')."regdate>'".($timestamp - 86400 * $indays)."'";
//	$outdays && $wheresql .= ($wheresql ? ' AND ' : '')."regdate<'".($timestamp - 86400 * $outdays)."'";

	$filterstr = '';
	$keyword && $filterstr .= "&keyword=".rawurlencode(stripslashes($keyword));

//	foreach($grouptypes as $gtid => $grouptype){
//		${"ugid$gtid"} = empty(${"ugid$gtid"}) ? 0 : ${"ugid$gtid"};
//		if(${"ugid$gtid"}){
//			$filterstr .= "&ugid$gtid=".${"ugid$gtid"};
//			if(!empty(${"u_ugids$k"}) && !in_array(${"ugid$gtid"},${"u_ugids$k"})) $no_list = 1;
//			$wheresql .= ($wheresql ? ' AND ' : '')."grouptype$gtid='".${"ugid$gtid"}."'";
//		}elseif(!empty(${"u_ugids$k"})) $wheresql .= ($wheresql ? ' AND ' : '')."grouptype$gtid ".multi_str(${"ugid$gtid"});
//	}
	$wheresql = empty($no_list) ? ($wheresql ? "WHERE $wheresql" : '') : 'WHERE 1=0';
	//echo $wheresql;

	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'memberedit',"?entry=$entry&action=$action&page=$page");
			//搜索区块
			tabheader_e();
//			trhidden('mchid',$mchid);
//			trhidden('nauid',$nauid);
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//类系筛选
//			foreach($grouptypes as $gtid => $grouptype){
//				if(in_array("ugid$gtid",$u_filters)){
//					$ugidsarr = array('0' => $grouptype['cname']) + ugidsarr($gtid);
//					echo "<select style=\"vertical-align: middle;\" name=\"ugid$gtid\">".makeoption($ugidsarr,${"ugid$gtid"})."</select>&nbsp; ";
//				}
//			}
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
//			.viewcheck('viewdetail',$viewdetail,$actionid.'tbodyfilter');
//			echo "</td></tr>";
//			//隐藏区块
//			echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
//			//日期筛选
//			if(empty($u_filters) || in_array('date',$u_filters)){
//				trrange(lang('register_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
//			}
//			echo "</tbody>";
			tabfooter();
			//列表区	
			tabheader(($mchid ? @$mchannels[$mchid]['cname'] : lang('member')).lang('list')."&nbsp; &nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',10);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('member_cname'), 'txtL'),);
			if(in_array('reportor',$u_lists)) $cy_arr[] = array(lang('reportor'),'txtL');
			if(in_array('reporttime',$u_lists)) $cy_arr[] = lang('reporttime');
			$cy_arr[] = array(lang('admin'),'txtR');
			trcategory($cy_arr);

			$pagetmp = $page;
			do{
				$query = $db->query("SELECT * $fromsql $wheresql ORDER BY cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$reporttime = date('Y-m-d',$row['createdate']);

				$editstr = "<a href=\"?entry=$entry&action=mreportsdetail&cid=$row[cid]\" onclick=\"return floatwin('open_memberedit',this)\">".lang('reason')."</a>\n";
				if(in_array('edit',$u_lists)) $editstr .= "&nbsp; <a href=\"?entry=member&action=memberdetail&mid=$row[mid]\" onclick=\"return floatwin('open_memberedit',this)\">".lang('detail')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\"><a href=\"{$mspaceurl}index.php?mid=$row[mid]\" target=\"_blank\">$row[mname]</a></td>\n";
				foreach($grouptypes as $k => $v) if(in_array("ugid$k",$u_lists)) $itemstr .= "<td class=\"txtC\">".${'ugid'.$k.'str'}."</td>\n";
				if(in_array('regip',$u_lists)) $itemstr .= "<td class=\"txtC\">$regipstr</td>\n";
				if(in_array('regdate',$u_lists)) $itemstr .= "<td class=\"txtC\">$regdatestr</td>\n";
				if(in_array('reportor',$u_lists)) $itemstr .= "<td class=\"txtL\"><a href=\"?entry=member&action=memberdetail&mid=$row[fromid]\" onclick=\"return floatwin('open_memberedit',this)\">$row[fromname]</a></td>\n";
				if(in_array('reporttime',$u_lists)) $itemstr .= "<td class=\"txtC\">$reporttime</td>\n";
				if($editstr) $itemstr .= "<td class=\"txtR\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=$entry&action=$action$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			
			//操作区
			tabheader(lang('operate_item'));
			trbasic(lang('choose_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[delete]\" value=\"1\">".lang('delete')." &nbsp;",'');
			tabfooter('barcsedit');
		}else include(M_ROOT.$u_tplname);
	}else{
		if(empty($arcdeal) && empty($dealstr)) amessage('selectoperateitem',"?entry=$entry&action=$action&page=$page$filterstr");
		if(empty($selectid) && empty($select_all)) amessage('selectmember',"?entry=$entry&action=$action&page=$page$filterstr");
		if(!empty($select_all)){
			if(empty($dealstr)){
				$dealstr = implode(',',array_keys(array_filter($arcdeal)));
			}else{
				$arcdeal = array();
				foreach(array_filter(explode(',',$dealstr)) as $k) $arcdeal[$k] = 1;
			}

			$parastr = "";
			foreach($grouptypes as $k => $v) if($v['mode'] < 2) $parastr .= "&arcugid$k=".@${"arcugid$k"};
			foreach(array('arcarcallowance','arccuallowance',) as $k) $parastr .= "&$k=".@$$k;
			
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$arccount = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$pages = @ceil($arccount / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "cid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? ' AND ' : '').$fromstr);
				$query = $db->query("SELECT cid,mname $fromsql $nwheresql ORDER BY cid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)) $selectid[] = $item['cid'];
			}
		}
		$db->query("DELETE FROM {$tblprefix}mreports WHERE cid ".multi_str($selectid),'UNBUFFERED');

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
				amessage('operating',"?entry=$entry&action=$action&page=$page$filterstr$transtr$parastr&dealstr=$dealstr",
							$pages,$npage,"<a href=\"?entry=$entry&action=$action&page=$page$filterstr\">",'</a>');
			}
		}
		adminlog(lang('member_admin'),lang('member_list_admin'));
		amessage('memberoperatefinish',"?entry=$entry&action=$action&page=$page$filterstr");
	}
}elseif($action == 'mreportsdetail' && !empty($cid)){
	($result = $db->fetch_one("SELECT * FROM {$tblprefix}mreports WHERE cid='$cid' LIMIT 0,1")) || amessage('selectoperateitem');
	load_cache('mbfields');
	tabheader(lang('memberreport') . ' - ' . $result['mname']);
	include_once M_ROOT."./include/fields.cls.php";
	$a_field = new cls_field;
	foreach($mbfields as $k => $v){
		if(!$v['isadmin'] && !$v['isfunc']){
//			trbasic($v['cname'],'',$result[$k],'');
			$a_field->init();
			$a_field->field = $v;
			$a_field->oldvalue = $result[$k];
			$a_field->trfield('','','mb');
		}
	}
	unset($a_field);
	tabfooter();
}
?>
