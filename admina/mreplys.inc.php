<?
!defined('M_COM') && exit('No Permission');
load_cache('mchannels,currencys,mcommus,mrfields');
include_once M_ROOT."./include/mcuedit.cls.php";
aheader();
$cid = empty($cid) ? 0 : max(0,intval($cid));
if($action == 'mreplysedit'){
	//分析页面设置
	$nauid = empty($nauid) ? 0 : max(0,intval($nauid));
	$u_checked = -1;
	if($nauid && $u_url = read_cache('aurl',$nauid)){
		$u_tplname = @$u_url['tplname'];
		$u_mtitle = @$u_url['mtitle'];
		$u_guide = @$u_url['guide'];
		foreach(array('checked',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
		$vars = array('mchids','cuids','filters','lists','operates',);
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_filters) && $u_filters = array('check');
	empty($u_lists) && $u_lists = array('check',);
	empty($u_operates) && $u_operates = array('delete','check');
	
	$page = empty($page) ? 1 : max(1, intval($page));
	submitcheck('bfilter') && $page = 1;
	$checked = isset($checked) ? $checked : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$mchid = empty($mchid) ? 0 : max(0,intval($mchid));
	$cuid = empty($cuid) ? 0 : max(0,intval($cuid));
	
	$wheresql = '';
	$fromsql = "FROM {$tblprefix}mreplys cu LEFT JOIN {$tblprefix}members m ON m.mid=cu.mid";
	if($mchid){
		if(!empty($u_chids) && !in_array($mchid,$u_chids)) $no_list = true;
		else $wheresql .= ($wheresql ? ' AND ' : '')."m.mchid='$mchid'";
	}elseif(!empty($u_chids)) $wheresql .= ($wheresql ? ' AND ' : '')."m.mchid ".multi_str($u_chids);
	if($cuid){
		if(!empty($u_cuids) && !in_array($cuid,$u_cuids)) $no_list = true;
		$wheresql .= ($wheresql ? ' AND ' : '')."cu.cuid ='$cuid'";
	}elseif(!empty($u_cuids)) $wheresql .= ($wheresql ? ' AND ' : '')."cu.cuid ".multi_str($u_cuids);
	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= ($wheresql ? ' AND ' : '')."cu.checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= ($wheresql ? ' AND ' : '')."cu.checked='$u_checked'";
	//搜索关键词处理
	$keyword && $wheresql .= ($wheresql ? ' AND ' : '')."cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	$filterstr = '';
	foreach(array('nauid','mchid','cuid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	$wheresql = empty($no_list) ? ($wheresql ? "WHERE $wheresql" : '') : "WHERE 1=0";
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			$cuidsarr = array();
			foreach($mcommus as $k => $v) if($v['cclass'] == 'reply') $cuidsarr[$k] = $v['cname'];
			
			echo form_str($action.'archivesedit',"?entry=$entry&action=$action&nauid=$nauid&page=$page");
			tabheader_e();
			echo "<tr><td class=\"txtL\">";
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//审核状态
			if(in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			if(in_array('commu',$u_filters)){
				echo "<select style=\"vertical-align: middle;\" name=\"cuid\">".makeoption(array('0' => lang('commuitem')) + $cuidsarr,$cuid)."</select>&nbsp; ";
			}
			//栏目搜索
			if(in_array('channel',$u_filters)){
				$mchidsarr = array('0' => lang('memberchannel')) + mchidsarr();
				echo "<select style=\"vertical-align: middle;\" name=\"mchid\">".makeoption($mchidsarr,$mchid)."</select>&nbsp; ";
			}
			echo strbutton('bfilter','filter0').'</td></tr>';
			trhidden('mchid',$mchid);
			tabfooter();
		
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT cu.*,m.mchid $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			}while(!$db->num_rows($query) && $pagetmp);
			tabheader(lang('reply_list'),'','',30);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('member'),'txtL'),);
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('mchannel');
			if(in_array('commu',$u_lists)) $cy_arr[] = lang('commuitem');
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_time');
			$cy_arr[] = lang('edit');
			trcategory($cy_arr);
	
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$mnamestr = $row['mname'];
				$commustr = @$cuidsarr[$row['cuid']];
				$channelstr = @$mchannels[$row['mchid']]['cname'];
				$checkstr = $row['checked'] ? 'Y' : '-';
				$adddatestr = $row['createdate'] ? date('Y-m-d',$row['createdate']) : '-';
				$editstr = "<a href=\"?entry=$entry&action=mreplydetail&cid=$row[cid]\" onclick=\"return floatwin('open_inarchive',this)\">".lang('edit')."</a>&nbsp; ";
	
				$itemstr .= "<tr><td class=\"txt\">$selectstr</td><td class=\"txtL\">$mnamestr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txt\">$channelstr</td>\n";
				if(in_array('commu',$u_lists)) $itemstr .= "<td class=\"txt\">$commustr</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txt\">$checkstr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txt\">$adddatestr</td>\n";
				$itemstr .= "<td class=\"txt\">$editstr</td>\n";
				$itemstr .= "</tr>\n";
			}
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts,$atpp,$page,"?entry=$entry&action=$action$filterstr");
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
			tabfooter('barcsedit');
			a_guide(@$u_guide);
		}else include(M_ROOT.$u_tplname);
	}else{
		if(empty($arcdeal)) amessage('selectoperateitem',M_REFERER);
		if(empty($selectid)) amessage('choosereply',M_REFERER);
		$uedit = new cls_mcuedit;
		foreach($selectid as $cid){
			if($errno = $uedit->read($cid,'reply')) continue;
			if(!empty($arcdeal['delete'])){
				$uedit->delete();
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
		amessage('replysetsucceed',"?entry=$entry&action=$action$filterstr&page=$page");
	}
}elseif($action=='mreplydetail' && $cid){
	$cuid = $db->result_one("SELECT cuid FROM {$tblprefix}replys WHERE cid='$cid'");
	if(!$cuid || !($mcommu = read_cache('mcommu',$cuid))) amessage('setcommuitem');
	if(empty($mcommu['uadetail'])){
		load_cache('currencys,mrfields,mchannels');
		include_once M_ROOT."./include/fields.cls.php";
		include_once M_ROOT."./include/upload.cls.php";
		$catalogs = &$acatalogs;	
		$uedit = new cls_mcuedit;
		if($errno = $uedit->read($cid,'reply')){
			if($errno == 1) amessage('choosereply');
			if($errno == 2) amessage('choosereplyobject');
			if($errno == 3) amessage('setcommuitem');
		}
		foreach(array('mcommu','fields',) as $var) $$var = &$uedit->$var;
		$oldrow = &$uedit->info;
		if(!submitcheck('newcommu')){
			tabheader($mcommu['cname'].'&nbsp; &nbsp; '."<a href=\"{$mspaceurl}index.php?mid=$oldrow[mid]\" target=\"_blank\">>>&nbsp; $oldrow[mname]</a>",'commudetail',"?entry=$entry&action=$action&cid=$cid",2,1,1);
			$submitstr = '';
			$a_field = new cls_field;
			foreach($fields as $k => $v){
				if(empty($u_lists) || in_array($k,$u_lists)){
					if(!$v['isfunc']){
						$a_field->init();
						$a_field->field = $v;
						$a_field->oldvalue = $oldrow[$k];
						$a_field->trfield('communew','','mr');
						$submitstr .= $a_field->submitstr;
					}
				}
			}
			unset($a_field);
			tabfooter('newcommu');
			check_submit_func($submitstr);
	//		$db->query("UPDATE {$tblprefix}mreplys SET ".($amode ? "aread='1'" : "uread='1'")." WHERE cid='$cid'");
		}else{
			$c_upload = new cls_upload;	
			$fields = fields_order($fields);
			$a_field = new cls_field;
			foreach($fields as $k => $v){
				if(empty($u_lists) || in_array($k,$u_lists)){
					if(!$v['isfunc']){
						$a_field->init();
						$a_field->field = $v;
						$a_field->oldvalue = isset($oldrow[$k]) ? $oldrow[$k] : '';
						$a_field->deal('communew');
						if(!empty($a_field->error)){
							$c_upload->rollback();
							amessage($a_field->error,M_REFERER);
						}
						$uedit->updatefield($k,$a_field->newvalue);
						if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $uedit->updatefield($k.'_'.$x,$y);
					}
				}
			}
			unset($a_field);
	//		if($amode) $uedit->updatefield('areply',1);
		
			$c_upload->closure(1, $cid, 'mreplys');
			$c_upload->saveuptotal(1);
			$uedit->updatedb();
			amessage('updatesucceed',axaction(6,M_REFERER),$mcommu['cname']);
		}
	}else include(M_ROOT.$mcommu['uadetail']);
}
?>