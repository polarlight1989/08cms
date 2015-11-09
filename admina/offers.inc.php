<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('commu') || amessage('no_apermission');
load_cache('channels,rcatalogs,currencys,commus,ofields,ucotypes');
load_cache('catalogs',$sid);
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/cuedit.cls.php";
if($action == 'offersedit'){
	//分析页面设置
	$nauid = empty($nauid) ? 0 : $nauid;
	$u_checked = $u_valid = -1;
	if($nauid && $u_url = read_cache('aurl',$nauid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$u_checked = $u_url['setting']['checked'];
		$u_valid = $u_url['setting']['valid'];
		foreach(array('cuids','chids','filters','lists','operates',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_filters) && $u_filters = array('check','catalog',);
	empty($u_lists) && $u_lists = array('oprice','mname','catalog','channel','commu','check','edit',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$caid = empty($caid) ? 0 : $caid;
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : 1;
	$cuid = empty($cuid) ? 0 : $cuid;
	$chid = empty($chid) ? 0 : max(0,intval($chid));
	$checked = isset($checked) ? $checked : '-1';
	$valid = isset($valid) ? $valid : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
	$wheresql = "a.sid='$sid'";
	$fromsql = "FROM {$tblprefix}offers cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";

	//栏目范围
	$caids = array(-1);
	if(!empty($caid)){
		$caids = cnsonids($caid,$catalogs);
	}
	if(!in_array(-1,$a_caids)) $caids = in_array(-1,$caids) ? $a_caids : array_intersect($caids,$a_caids);
	if(!$caids) $no_list = true;
	elseif(!in_array(-1,$caids)) $wheresql .= " AND a.caid ".multi_str($caids);

	//模型范围
	if($chid){
		if(!empty($u_chids) && !in_array($chid,$u_chids)) $no_list = true;
		else $wheresql .= " AND a.chid='$chid'";
	}elseif(!empty($u_chids)) $wheresql .= " AND a.chid ".multi_str($u_chids);

	//交互项目范围
	if($cuid){
		if(!empty($u_cuids) && !in_array($cuid,$u_cuids)) $no_list = true;
		else $wheresql .= " AND cu.cuid='$cuid'";
	}elseif(!empty($u_cuids)) $wheresql .= " AND cu.cuid ".multi_str($u_cuids);

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
	$keyword && $wheresql .= " AND (cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";

	$indays && $wheresql .= " AND cu.createdate>'".($timestamp - 86400 * $indays)."'";
	$outdays && $wheresql .= " AND cu.createdate<'".($timestamp - 86400 * $outdays)."'";

	$filterstr = '';
	foreach(array('nauid','viewdetail','caid','cuid','chid','keyword','indays','outdays',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
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
			echo form_str($actionid.'arcsedit',"?entry=offers&action=offersedit&page=$page$param_suffix");
			//搜索区块

			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//合辑类型搜索
			if(in_array('commu',$u_filters)){
				$cuidsarr = array('0' => lang('all_cuitem'));
				foreach($commus as $k => $v) if($v['cclass'] == 'offer') $cuidsarr[$k] = $v['cname'];
				echo "<select style=\"vertical-align: middle;\" name=\"cuid\">".makeoption($cuidsarr,$cuid)."</select>&nbsp; ";
			}
			//审核状态
			if(in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			//有效状态
			if(in_array('valid',$u_filters)){
				$validarr = array('-1' => lang('nolimit').lang('available'),'0' => lang('invalid'),'1' => lang('available'));
				//trbasic(lang('validperiod_state'),'',makeradio('valid',$validarr,$valid),'');
				echo "<select style=\"vertical-align: middle;\" name=\"valid\">".makeoption($validarr,$valid)."</select>&nbsp; ";
			}
			foreach($ucotypes as $k => $v){//报价类系的搜索
				if(in_array('uccid'.$k,$u_filters) && $v['cclass'] == 'offer'){
					$ucoclasses = read_cache('ucoclasses',$k);
					$uccidsarr = array(0 => lang('nolimit').$v['cname']);
					foreach($ucoclasses as $k1 => $v1) $uccidsarr[$k1] = $v1['title'];
					echo "<select style=\"vertical-align: middle;\" name=\"uccid$k\">".makeoption($uccidsarr,empty(${"uccid$k"}) ? 0 : ${"uccid$k"})."</select>&nbsp; ";
				}
			}
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">".viewcheck('viewdetail',$viewdetail,$actionid.'tbodyfilter');
			echo "</td></tr>";


			//某些固定页面参数
			trhidden('caid',$caid);
			trhidden('nauid',$nauid);

			//隐藏区块
			echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
			//模型搜索
			if(in_array('channel',$u_filters)){
				$chidsarr = array('0' => lang('all_channel')) + chidsarr();
				trbasic(lang('achannel'),"chid",makeoption($chidsarr,$chid),'select');
			}
			//日期筛选
			if(in_array('date',$u_filters)){
				trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
			}
			echo "</tbody>";
			tabfooter();
	
	
			//列表区	
			tabheader(lang('offer_list')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);

			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('commu',$u_lists)) $cy_arr[] = lang('type');
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('oprice',$u_lists)) $cy_arr[] = lang('price');
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('valid',$u_lists)) $cy_arr[] = lang('available');
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('achannel');
			foreach($ucotypes as $k => $v) if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'offer') $cy_arr["ccid$k"] = $v['cname'];
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_date');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('update_time');
			if(in_array('enddate',$u_lists)) $cy_arr[] = lang('end1_time');
			if(in_array('edit',$u_lists)) $cy_arr[] = lang('edit');
			trcategory($cy_arr);

			$pagetmp = $page;
			do{
				$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

			$itemstr = '';
			while($row = $db->fetch_array($query)){
				cu_checkend($row,'offer');
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">$row[asubject]</a>";
				$mnamestr = $row['mname'];
				$opricestr = $row['oprice'];
				$catalogstr = @$catalogs[$row['caid']]['title'];
				$commustr = @$commus[$row['cuid']]['cname'];
				$channelstr = @$channels[$row['chid']]['cname'];
				foreach($ucotypes as $k => $v){
					if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'offer'){
						$ucoclasses = read_cache('ucoclasses',$k);
						${'uccid'.$k.'str'} = @$ucoclasses[$row['uccid'.$k]]['title'];
					}
				}
				$checkstr = $row['checked'] ? 'Y' : '-';
				$validstr = !$row['enddate'] || $row['enddate'] > $timestamp ? 'Y' : '-';
				$adddatestr = date('Y-m-d',$row['ucreatedate']);
				$updatedatestr = date('Y-m-d',$row['updatedate']);
				$enddatestr = $row['enddate'] ? date('Y-m-d',$row['enddate']) : '-';
				$editstr = "<a href=\"?entry=offers&action=offerdetail&cid=$row[cid]$param_suffix\" onclick=\"return floatwin('open_offersedit',this)\">".lang('detail')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('commu',$u_lists)) $itemstr .= "<td class=\"txtC\">$commustr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('oprice',$u_lists)) $itemstr .= "<td class=\"txtC\">$opricestr</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$checkstr</td>\n";
				if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$validstr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
				foreach($ucotypes as $k => $v) if(in_array('uccid'.$k,$u_lists) && $v['cclass'] == 'offer') $itemstr .= "<td class=\"item\">".${'uccid'.$k.'str'}."</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w80\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"txtC w80\">$updatedatestr</td>\n";
				if(in_array('enddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$enddatestr</td>\n";
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=offers&action=offersedit$param_suffix$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;

			//操作区
			tabheader(lang('operate_item'));
			$s_arr = array();
			if(empty($u_operates) || in_array('delete',$u_operates)) $s_arr['delete'] = lang('delete');
			if(empty($u_operates) || in_array('check',$u_operates)) $s_arr['check'] = lang('check');
			if(empty($u_operates) || in_array('uncheck',$u_operates)) $s_arr['uncheck'] = lang('uncheck');
			if(empty($u_operates) || in_array('readd',$u_operates)) $s_arr['readd'] = lang('readd');
			if($s_arr){
				$soperatestr = '';
				foreach($s_arr as $k => $v) $soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
				trbasic(lang('choose_item'),'',$soperatestr,'');
			}
			tabfooter('barcsedit');
			a_guide('offersedit');
		
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($arcdeal) && empty($dealstr)) amessage('selectoperateitem',axaction(1,M_REFERER));
		if(empty($selectid) && empty($select_all)) amessage('pchoosecontent',axaction(1,M_REFERER));
		if(!empty($select_all)){
			if(empty($dealstr)){
				$dealstr = implode(',',array_keys(array_filter($arcdeal)));
			}else{
				$arcdeal = array();
				foreach(array_filter(explode(',',$dealstr)) as $k) $arcdeal[$k] = 1;
			}

			$parastr = "";
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$pages = @ceil($counts / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "cu.cid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT cu.cid $fromsql $nwheresql ORDER BY cu.cid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)) $selectid[] = $item['cid'];
			}
		}
		if(!empty($arcdeal['delete'])){
			$query = $db->query("SELECT aid,mid FROM {$tblprefix}offers WHERE cid ".multi_str($selectid));
			while($row = $db->fetch_array($query)){
				$db->query("UPDATE {$tblprefix}archives SET offers=GREATEST(0,offers-1) WHERE aid='$row[aid]'");
				$db->query("UPDATE {$tblprefix}members_sub SET offers=GREATEST(0,offers-1) WHERE mid='$row[mid]'");
			}
			unset($row);
			$db->query("DELETE FROM {$tblprefix}offers WHERE cid ".multi_str($selectid));
		}else{
			if(!empty($arcdeal['check'])){
				$db->query("UPDATE {$tblprefix}offers SET checked='1' WHERE cid ".multi_str($selectid));
			}elseif(!empty($arcdeal['uncheck'])){
				$db->query("UPDATE {$tblprefix}offers SET checked='0' WHERE cid ".multi_str($selectid));
			}
			if(!empty($arcdeal['readd'])){
				foreach($selectid as $k){
					$cuid = $db->result_one("SELECT cuid FROM {$tblprefix}offers WHERE cid ='$k'");
					$commu = read_cache('commu',$cuid);
					$db->query("UPDATE {$tblprefix}offers SET enddate='".(empty($commu['setting']['vdays']) ? 0 : $timestamp + 86400 * $commu['setting']['vdays'])."' WHERE cid='$k'");
				}
			}
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
				amessage('operating',"?entry=offers&action=offersedit&page=$page$param_suffix$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=offers&action=offersedit&page=$page$param_suffix$filterstr\">",'</a>');
			}
		}
		adminlog(lang('offersetsucceed'),lang('offersetsucceed'));
		amessage('contentsetsucceed',"?entry=offers&action=offersedit$param_suffix&page=$page$filterstr");
	}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'offerdetail'){
	$cid = empty($cid) ? 0 : max(0,intval($cid));
	$cuid = $db->result_one("SELECT cuid FROM {$tblprefix}offers WHERE cid='$cid'");
	if(!($commu = read_cache('commu',$cuid))) amessage('setcommuitem');
	if(empty($commu['uadetail'])){
		load_cache('ofields,ucotypes');
		$uedit = new cls_cuedit;
		if($errno = $uedit->read($cid,'offer')){
			if($errno == 1) amessage('chooseoffer');
			if($errno == 2) amessage('chooseproduct');
			if($errno == 3) amessage('setcommuitem');
		}
		foreach(array('aid','commu','citems','fields',) as $var) $$var = &$uedit->$var;
		$oldrow = &$uedit->info;
		$forward = empty($forward) ? M_REFERER : $forward;
		$forwardstr = '&forward='.rawurlencode($forward);
		if(!submitcheck('bofferdetail')){
			tabheader($commu['cname'].'&nbsp; &nbsp; '."<a href=\"".view_arcurl($oldrow)."\" target=\"_blank\">>>&nbsp; ".$oldrow['subject']."</a>",'commudetail',"?entry=offers&action=offerdetail&cid=$cid$param_suffix$forwardstr",2,1,1);
			$submitstr = '';
			trbasic(lang('price'),'offernew[oprice]',$oldrow['oprice']);
			trbasic(lang('stock'),'offernew[storage]',$oldrow['storage']);
			foreach($ucotypes as $k => $v){
				if(in_array('uccid'.$k,$citems) && $v['umode'] != 1){
					trbasic(($v['notblank'] ? '*' : '').$v['cname'],'',mu_cnselect("offernew[uccid$k]",$oldrow['uccid'.$k],$k,lang('p_choose'),$v['emode'],"offernew[uccid{$k}date]",@$oldrow["uccid{$k}date"] ? date('Y-m-d',$oldrow["uccid{$k}date"]) : ''),'');
					$submitstr .= makesubmitstr("offernew[uccid$k]",$v['notblank'],0,0,0,'common');
					$v['emode'] == 2 && $submitstr .= makesubmitstr("offernew[uccid{$k}date]",1,0,0,0,'date');
				}
			}
			$a_field = new cls_field;
			foreach($fields as $k => $v){
				if(!$v['isfunc']){
					$a_field->init();
					$a_field->field = $v;
					$a_field->oldvalue = $oldrow[$k];
					if($curuser->pmbypmids('field',$v['pmid'])){
						$a_field->trfield('offernew','','o');
						$submitstr .= $a_field->submitstr;
					}
				}
			}
			unset($a_field);
			tabfooter('bofferdetail');
			check_submit_func($submitstr);
		}else{
			$uedit->updatefield('oprice',max(0,round($offernew['oprice'],2)));
			$uedit->updatefield('refreshdate',$timestamp);
			$uedit->updatefield('enddate',empty($uedit->commu['setting']['vdays']) ? 0 : $timestamp + 86400 * $uedit->commu['setting']['vdays']);
			$uedit->updatefield('storage',max(-1,intval($offernew['storage'])));
			$c_upload = new cls_upload;	
			$fields = fields_order($fields);
			$a_field = new cls_field;
			foreach($ucotypes as $k => $v){
				if(in_array('uccid'.$k,$citems) && $v['umode'] != 1){
					$uedit->updatefield('uccid'.$k,$offernew['uccid'.$k]);
					if($v['emode']){
						$offernew["uccid{$k}date"] = !isdate($offernew["uccid{$k}date"]) ? 0 : strtotime($offernew["uccid{$k}date"]);
						if($uedit->info["uccid$k"] && !$offernew["uccid{$k}date"] && $v['emode'] == 2) amessage('setcoclass',axaction(2,M_REFERER),$v['cname']);
						!$uedit->info["uccid$k"] && $offernew["uccid{$k}date"] = 0;
						$uedit->updatefield("uccid{$k}date",$offernew["uccid{$k}date"]);
					}
				}
			}
			foreach($fields as $k => $v){
				if(!$v['isfunc']){
					$a_field->init();
					$a_field->field = $v;
					if($curuser->pmbypmids('field',$v['pmid'])){
						$a_field->oldvalue = isset($oldrow[$k]) ? $oldrow[$k] : '';
						$a_field->deal('offernew');
						if(!empty($a_field->error)){
							$c_upload->rollback();
							amessage($a_field->error,axaction(2,M_REFERER));
						}
						$uedit->updatefield($k,$a_field->newvalue);
						if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $uedit->updatefield($k.'_'.$x,$y);
					}
				}
			}
			unset($a_field);
		
			$c_upload->closure(1, $cid, 'offers');
			$c_upload->saveuptotal(1);
			$uedit->updatedb();
			amessage('updatesucceed',axaction(6,$forward),$commu['cname']);
		}
	}
}
?>