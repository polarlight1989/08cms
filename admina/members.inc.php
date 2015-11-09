<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('member') || amessage('no_apermission');
load_cache('mchannels,catalogs,acatalogs,cotypes,mtconfigs,channels,grouptypes,currencys,rprojects');
$backamember = backallow('amember');
if($action == 'membersedit'){
	//分析页面设置
	$nauid = empty($nauid) ? 0 : $nauid;
	$u_checked = -1;
	if($nauid && $u_url = read_cache('aurl',$nauid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		foreach(array('checked',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
		$vars = array('filters','lists','operates',);
		foreach($grouptypes as $k => $v) $vars[] = 'ugids'.$k;
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('mchannel','check','regdate','view','edit','usergroup','allowance');
	empty($u_filters) && $u_filters = array('check','ugid1','date',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$mchid = empty($mchid) ? 0 : max(0,intval($mchid));
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : 1;
	$checked = isset($checked) ? $checked : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
	$wheresql = '';//非管理员
	$fromsql = "FROM {$tblprefix}members";

	//类型范围
	if(!empty($mchid)){
		if(!array_intersect(array(-1,$mchid),$a_mchids)) $no_list = 1;
		else $wheresql .= ($wheresql ? ' AND ' : '')."mchid='$mchid'";
	}elseif(empty($a_mchids)){
		$no_list = 1;
	}elseif(!in_array(-1,$a_mchids) && $a_mchids) $wheresql .= ($wheresql ? ' AND ' : '')."mchid ".multi_str($a_mchids);
	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= ($wheresql ? ' AND ' : '')."checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= ($wheresql ? ' AND ' : '')."checked='$u_checked'";
	//搜索关键词处理
	$keyword && $wheresql .= ($wheresql ? ' AND ' : '')."mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	$indays && $wheresql .= ($wheresql ? ' AND ' : '')."regdate>'".($timestamp - 86400 * $indays)."'";
	$outdays && $wheresql .= ($wheresql ? ' AND ' : '')."regdate<'".($timestamp - 86400 * $outdays)."'";

	$filterstr = '';
	foreach(array('nauid','viewdetail','mchid','keyword','indays','outdays',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;

	foreach($grouptypes as $k => $v){
		${"ugid$k"} = empty(${"ugid$k"}) ? 0 : ${"ugid$k"};
		if(${"ugid$k"}){
			$filterstr .= "&ugid$k=".${"ugid$k"};
			if(!empty(${"u_ugids$k"}) && !in_array(${"ugid$k"},${"u_ugids$k"})) $no_list = 1;
			$wheresql .= ($wheresql ? ' AND ' : '')."grouptype$k='".${"ugid$k"}."'";
		}elseif(!empty(${"u_ugids$k"})) $wheresql .= ($wheresql ? ' AND ' : '')."grouptype$k ".multi_str(${"u_ugids$k"});
	}
	$wheresql = empty($no_list) ? ($wheresql ? "WHERE $wheresql" : '') : 'WHERE 1=0';
	//echo $wheresql;

	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'memberedit',"?entry=members&action=membersedit&page=$page");
			//搜索区块
			tabheader_e();
			trhidden('mchid',$mchid);
			trhidden('nauid',$nauid);
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//审核状态
			if(empty($u_filters) || in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('check_state'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			//类系筛选
			foreach($grouptypes as $gtid => $grouptype){
				if(in_array("ugid$gtid",$u_filters)){
					$ugidsarr = array('0' => $grouptype['cname']) + ugidsarr($gtid);
					echo "<select style=\"vertical-align: middle;\" name=\"ugid$gtid\">".makeoption($ugidsarr,${"ugid$gtid"})."</select>&nbsp; ";
				}
			}
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">".viewcheck('viewdetail',$viewdetail,$actionid.'tbodyfilter');
			echo "</td></tr>";
			//隐藏区块
			echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
			//日期筛选
			if(empty($u_filters) || in_array('date',$u_filters)){
				trrange(lang('register_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
			}
			echo "</tbody>";
			tabfooter();
			//列表区	
			tabheader(($mchid ? @$mchannels[$mchid]['cname'] : lang('member')).lang('list')."&nbsp; &nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',10);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('member_cname'),);
			if(in_array('mchannel',$u_lists)) $cy_arr[] = lang('memtype');
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			foreach($grouptypes as $k => $v) if(in_array("ugid$k",$u_lists)) $cy_arr["ugid$k"] = $v['cname'];
			if(in_array('regip',$u_lists)) $cy_arr[] = lang('regip');
			if(in_array('regdate',$u_lists)) $cy_arr[] = lang('reg_date');
			if(in_array('lastvisit',$u_lists)) $cy_arr[] = lang('recentvisit');
			if(in_array('edit',$u_lists) || in_array('usergroup',$u_lists) || in_array('allowance',$u_lists)) $cy_arr[] = array(lang('admin'),'txtR');
			trcategory($cy_arr);


			$pagetmp = $page;
			do{
				$query = $db->query("SELECT * $fromsql $wheresql ORDER BY mid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[mid]]\" value=\"$row[mid]\">";
				$mnamestr = $row['mname'].($row['isfounder'] ? '-'.lang('founder') : '');
				$mchannelstr = @$mchannels[$row['mchid']]['cname'];
				$checkstr = $row['checked'] ? 'Y' : '-';
				foreach($grouptypes as $k => $v){
					if(in_array("ugid$k",$u_lists)){
						if($row['grouptype'.$k]){
							$usergroups = read_cache('usergroups',$k);
							${'ugid'.$k.'str'} = @$usergroups[$row['grouptype'.$k]]['cname'];
						}else ${'ugid'.$k.'str'} = '-';
					}
				}
				$regipstr = $row['regip'];
				$regdatestr = $row['regdate'] ? date('Y-m-d',$row['regdate']) : '-';
				$lastvisitstr = $row['lastvisit'] ? date('Y-m-d',$row['lastvisit']) : '-';

				$editstr = '';
				if(in_array('edit',$u_lists)) $editstr .= "<a href=\"?entry=member&action=memberdetail&mid=$row[mid]\" onclick=\"return floatwin('open_memberedit',this)\">".lang('detail')."</a>";
				if(in_array('usergroup',$u_lists)) $editstr .= "&nbsp; <a href=\"?entry=member&action=grouptype&mid=$row[mid]\" onclick=\"return floatwin('open_memberedit',this)\">".lang('usergroup')."</a>";
				if(in_array('allowance',$u_lists)) $editstr .= "&nbsp; <a href=\"?entry=member&action=allowance&mid=$row[mid]\" onclick=\"return floatwin('open_memberedit',this)\">".lang('allowance')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$mnamestr</td>\n";
				if(in_array('mchannel',$u_lists)) $itemstr .= "<td class=\"txtC\">$mchannelstr</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$checkstr</td>\n";
				foreach($grouptypes as $k => $v) if(in_array("ugid$k",$u_lists)) $itemstr .= "<td class=\"txtC\">".${'ugid'.$k.'str'}."</td>\n";
				if(in_array('regip',$u_lists)) $itemstr .= "<td class=\"txtC\">$regipstr</td>\n";
				if(in_array('regdate',$u_lists)) $itemstr .= "<td class=\"txtC\">$regdatestr</td>\n";
				if(in_array('lastvisit',$u_lists)) $itemstr .= "<td class=\"txtC\">$lastvisitstr</td>\n";
				if($editstr) $itemstr .= "<td class=\"txtR\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=members&action=membersedit$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			
			//操作区
			tabheader(lang('operate_item'));
			$s_arr = array();
			if(empty($u_operates) || in_array('delete',$u_operates)) $s_arr['delete'] = lang('delete');
			if(empty($u_operates) || in_array('check',$u_operates)) $s_arr['check'] = lang('check');
			if(empty($u_operates) || in_array('uncheck',$u_operates)) $s_arr['uncheck'] = lang('uncheck');
			if($s_arr){
				$soperatestr = '';
				foreach($s_arr as $k => $v) $soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
				trbasic(lang('choose_item'),'',$soperatestr,'');
			}
			foreach($grouptypes as $k => $v){
				if(($v['mode'] < 2) && ($backamember || $k != 2)){
					if(empty($u_operates) || in_array('ugid'.$k,$u_operates)){
						$ugidsarr = array('0' => lang('release_usergroup')) + ugidsarr($k);
						trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[gtid$k]\" value=\"1\">&nbsp;".lang('set').$v['cname'],'arcugid'.$k,makeoption($ugidsarr),'select');
					}
				}
			}
			if(empty($u_operates) || in_array('arcallowance',$u_operates)){
				trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[arcallowance]\" value=\"1\">&nbsp;".lang('aw_arc_issue_limit'),'arcarcallowance');
			}
			if(empty($u_operates) || in_array('cuallowance',$u_operates)){
				trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[cuallowance]\" value=\"1\">&nbsp;".lang('aw_commu_issue_limit'),'arccuallowance');
			}
			tabfooter('barcsedit');
		}else include(M_ROOT.$u_tplname);
	}else{
		if(empty($arcdeal) && empty($dealstr)) amessage('selectoperateitem',"?entry=members&action=membersedit&page=$page$filterstr");
		if(empty($selectid) && empty($select_all)) amessage('selectmember',"?entry=members&action=membersedit&page=$page$filterstr");
		$mnamearr = array();
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
				$fromstr = empty($fromid) ? "" : "mid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? ' AND ' : '').$fromstr);
				$query = $db->query("SELECT mid,mname $fromsql $nwheresql ORDER BY mid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)) $selectid[] = $item['mid'];
			}
		}
		if(!empty($arcdeal['delete'])){
			$midarr = $mnamearr = array();
			$query = $db->query("SELECT mid,mname $fromsql WHERE mid ".multi_str($selectid)." AND mid != '$memberid' AND isfounder != 1".($backamember ? '' : " AND grouptype2=0"));
			while($item = $db->fetch_array($query)){
				$midarr[] = $item['mid'];
				$mnamearr[] = $item['mname'];
			}
			if($enable_uc){
				require_once M_ROOT.'./include/ucenter/config.inc.php';
				require_once M_ROOT.'./uc_client/client.php';
				$uids = array();
				foreach($mnamearr as $k){
					$ucresult = uc_get_user($k);
					is_array($ucresult) && $uids[] = $ucresult[0];
				}
				$uids && uc_user_delete($uids);
			}
			$midarr && $db->query("DELETE FROM {$tblprefix}members WHERE mid ".multi_str($midarr),'UNBUFFERED');
			$midarr && $db->query("DELETE FROM {$tblprefix}members_sub WHERE mid ".multi_str($midarr),'UNBUFFERED');
			foreach($mchannels as $k => $v) $midarr && $db->query("DELETE FROM {$tblprefix}members_$k WHERE mid ".multi_str($midarr),'UNBUFFERED');
		}else{
			if(!empty($arcdeal['check'])){
				$db->query("UPDATE {$tblprefix}members SET checked='1' WHERE mid ".multi_str($selectid).($backamember ? '' : " AND grouptype2=0"));
			}elseif(!empty($arcdeal['uncheck'])){
				$db->query("UPDATE {$tblprefix}members SET checked='0' WHERE mid ".multi_str($selectid)." AND isfounder != 1".($backamember ? '' : " AND grouptype2=0"));
			}
			if(!empty($arcdeal['arcallowance'])) $db->query("UPDATE {$tblprefix}members SET arcallowance='".max(0,intval($arcarcallowance))."' WHERE mid ".multi_str($selectid));
			if(!empty($arcdeal['cuallowance'])) $db->query("UPDATE {$tblprefix}members SET cuallowance='".max(0,intval($arccuallowance))."' WHERE mid ".multi_str($selectid));

			$actuser = new cls_userinfo;
			foreach($selectid as $id){
				$actuser->activeuser($id);
				foreach($grouptypes as $k => $v){
					if(($v['mode'] < 2) && !empty($arcdeal['gtid'.$k]) && ($backamember || $k != 2)){
						$actuser->handgrouptype($k,${"arcugid$k"},-1);
					}
				}
				$actuser->updatedb();
				$actuser->init();
			}
			unset($actuser);
		}

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
				amessage('operating',"?entry=members&action=membersedit&page=$page$filterstr$transtr$parastr&dealstr=$dealstr",
							$pages,$npage,"<a href=\"?entry=members&action=membersedit&page=$page$filterstr\">",'</a>');
			}
		}
		adminlog(lang('member_admin'),lang('member_list_admin'));
		amessage('memberoperatefinish',"?entry=members&action=membersedit&page=$page$filterstr");
	}

	}else include(M_ROOT.$u_tplname);
}
?>
