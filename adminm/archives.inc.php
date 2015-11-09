<?php
include_once M_ROOT."./include/arcedit.cls.php";
include_once M_ROOT."./include/commu.fun.php";
load_cache('permissions,vcps,channels,cotypes,acatalogs,inmurls');
!defined('M_COM') && exit('No Permission');
//分析页面设置
$nmuid = empty($nmuid) ? 0 : max(0,intval($nmuid));
$u_checked = $u_valid = -1;
if($nmuid && $u_url = read_cache('murl',$nmuid)){
	foreach(array('tplname','mtitle','guide','onlyview',) as $var) ${'u_'.$var} = empty($u_url[$var]) ? '' : $u_url[$var];
	foreach(array('checked','valid',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
	$u_url['setting']['sids'] = str_replace('m','0',$u_url['setting']['sids']);
	$vars = array('caids','chids','sids','filters','lists','operates','imuids',);
	foreach($cotypes as $k => $v) $vars[] = 'ccid'.$k;
	foreach($vars as $var) ${'u_'.$var} = empty($u_url['setting'][$var]) ? array() : explode(',',$u_url['setting'][$var]);
}
empty($u_filters) && $u_filters = array('check','catalog',);
empty($u_lists) && $u_lists = array('catalog','channel','check','view','admin',);
if(empty($u_tplname) || !empty($u_onlyview)){
	//分析当前会员的更新权限
	$issueupdatecheck = !$curuser->check_allow('freeupdatecheck');
	//关于文档的个人分类
	$uclasses = loaduclasses($curuser->info['mid']);
	$ucidsarr = array();
	foreach($uclasses as $k => $v) if(!$v['cuid']) $ucidsarr[$k] = $v['title'];
	//使用全局栏目
	$catalogs = &$acatalogs;
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : 1;
	$caid = empty($caid) ? 0 : max(0,intval($caid));
	$valid = isset($valid) ? $valid : '-1';
	$checked = isset($checked) ? $checked : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "a.mid='$memberid'";
	$fromsql = "FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid";
	
	//栏目范围
	$caids = array();
	if($caid){
		$caids = cnsonids($caid,$catalogs);
		if(!empty($u_caids)) $caids = array_intersect($caids,$u_caids);
	}elseif(!empty($u_caids)) $caids = $u_caids;
	if($caids && ($cnsql = cnsql(0,$caids,'a.'))) $wheresql .= " AND $cnsql";////////////////////////
	elseif(!empty($u_caids)) $no_list = true;
	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= " AND a.checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= " AND a.checked='$u_checked'";
	//有效期状态范围
	if($valid != -1){
		if(($u_valid != -1) && $valid != $u_valid) $no_list = true;
		else $wheresql .= $valid ? " AND (a.enddate='0' OR a.enddate>'$timestamp')" : " AND a.enddate>'0' AND a.enddate<'$timestamp'";
	}elseif($u_valid != -1) $wheresql .= $u_valid ? " AND (a.enddate='0' OR a.enddate>'$timestamp')" : " AND a.enddate>'0' AND a.enddate<'$timestamp'";
	//搜索关键词处理
	$keyword && $wheresql .= " AND a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	//子站范围
	if(!empty($u_sids)) $wheresql .= " AND a.sid ".multi_str($u_sids);
	//模型范围
	if(!empty($u_chids)) $wheresql .= " AND a.chid ".multi_str($u_chids);
	
	$filterstr = '';
	foreach(array('nmuid','viewdetail','caid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked','valid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	
	foreach($cotypes as $coid => $cotype){
		if(!empty(${'u_ccids'.$coid})){
			if($cnsql = cnsql($coid,${'u_ccids'.$coid},'a.')) $wheresql .= " AND $cnsql";///////////////////////
		}
	}
	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($action.'archivesedit',"?action=archives&nmuid=$nmuid&page=$page");
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
				echo '<span>'.cn_select('caid',$caid,-1,0,0,lang('catalog'),1,0,1).'</span>';
			}
			echo strbutton('bfilter','filter0').'</td></tr>';
			tabfooter();
		
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT a.*,s.needupdate $fromsql $wheresql ORDER BY a.aid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
				$pagetmp--;
			}while(!$db->num_rows($query) && $pagetmp);
	
			tabheader(empty($u_mtitle) ? lang('contentlist') : $u_mtitle,'','',30);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('title'), 'left'),);
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('uclass',$u_lists)) $cy_arr[] = lang('mycoclass');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
			foreach($cotypes as $k => $v) if(!$v['self_reg'] && in_array('ccid'.$k,$u_lists)) $cy_arr["ccid$k"] = $v['cname'];
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('valid',$u_lists)) $cy_arr[] = lang('available');
			if(in_array('clicks',$u_lists)) $cy_arr[] = lang('click');
			if(in_array('comments',$u_lists)) $cy_arr[] = lang('comment');
			if(in_array('replys',$u_lists)) $cy_arr[] = lang('reply');
			if(in_array('offers',$u_lists)) $cy_arr[] = lang('offer');
			if(in_array('orders',$u_lists)) $cy_arr[] = lang('orders');
			if(in_array('ordersum',$u_lists)) $cy_arr[] = lang('ordersum');
			if(in_array('favorites',$u_lists)) $cy_arr[] = lang('favorite');
			if(in_array('praises',$u_lists)) $cy_arr[] = lang('praise');
			if(in_array('debases',$u_lists)) $cy_arr[] = lang('debase');
			if(in_array('answers',$u_lists)) $cy_arr[] = lang('answer0');
			if(in_array('adopts',$u_lists)) $cy_arr[] = lang('adopt');
			if(in_array('closed',$u_lists)) $cy_arr[] = lang('close');
			if(in_array('downs',$u_lists)) $cy_arr[] = lang('download');
			if(in_array('price',$u_lists)) $cy_arr[] = lang('price');
			if(in_array('currency',$u_lists)) $cy_arr[] = lang('reward');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('addtime');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('updatetime');
			if(in_array('refreshdate',$u_lists)) $cy_arr[] = lang('retime');
			if(in_array('enddate',$u_lists)) $cy_arr[] = lang('endtime');
			if(in_array('view',$u_lists)) $cy_arr[] = lang('message');
			if(in_array('admin',$u_lists)) $cy_arr[] = lang('admin');
	
			trcategory($cy_arr);
	
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$channel = read_cache('channel',$row['chid']);
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\">";
				$row['arcurl'] = view_arcurl($row);
				$subjectstr = ($row['thumb'] ? lang('imged') : '')." &nbsp;<a href=$row[arcurl] target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
				$catalogstr = @$catalogs[$row['caid']]['title'];
				$uclassstr = $row['ucid'] ? $ucidsarr[$row['ucid']] : '-';
				$channelstr = @$channel['cname'];
				foreach($cotypes as $k => $v){
					${'ccid'.$k.'str'} = '';
					if(!$v['self_reg'] && in_array('ccid'.$k,$u_lists) && $row['ccid'.$k]){
						$coclasses = read_cache('coclasses',$k);
						${'ccid'.$k.'str'} = cnstitle($row['ccid'.$k],$v['asmode'],$coclasses);/////////////
					}
				}
				$checkstr = $row['checked'] ? 'Y' : '-';
				$validstr = !$row['enddate'] || $row['enddate'] > $timestamp ? 'Y' : '-';
				$clicksstr = $row['clicks'];
				$commentsstr = $row['comments'];
				$replysstr = $row['replys'];
				$offersstr = $row['offers'];
				$ordersstr = $row['orders'];
				$ordersumstr = $row['ordersum'];
				$favoritesstr = $row['favorites'];
				$praisesstr = $row['praises'];
				$debasesstr = $row['debases'];
				$answersstr = $row['answers'];
				$adoptsstr = $row['adopts'];
				$closedstr = $row['closed'] ? 'Y' : '-';
				$downsstr = $row['downs'];
				$pricestr = $row['price'];
				$currencystr = $row['currency'];
				$adddatestr = $row['createdate'] ? date('Y-m-d',$row['createdate']) : '-';
				$updatedatestr = $row['updatedate'] ? date('Y-m-d',$row['updatedate']) : '-';
				$refreshdatestr = $row['refreshdate'] ? date('Y-m-d',$row['refreshdate']) : '-';
				$enddatestr = $row['enddate'] ? date('Y-m-d',$row['enddate']) : '-';
				$viewstr = "<a id=\"{$action}_info_$row[aid]\" href=\"?action=arcview&aid=$row[aid]\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";
				$adminstr = '';
				if(empty($u_imuids) || empty($channel['imuids'])){
					$adminstr .= "<a href=\"?action=inarchive&aid=$row[aid]\" onclick=\"return floatwin('open_inarchive',this)\">".lang('admin')."</a>&nbsp; ";
					$adminstr .= "<a href=\"?action=archive&aid=$row[aid]\" onclick=\"return floatwin('open_inarchive',this)\">".lang('edit')."</a>";
				}else{
					$u_imuids = array_intersect($u_imuids,explode(',',$channel['imuids']));
					foreach($u_imuids as $k) if(!empty($inmurls[$k])) $adminstr .= "<a href=\"".$inmurls[$k]['url']."$row[aid]\" onclick=\"return floatwin('open_inarchive',this)\">".$inmurls[$k]['cname']."</a>&nbsp; ";
				}
				$itemstr .= "<tr><td class=\"item\">$selectstr</td><td class=\"item2\">$subjectstr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"item\">$catalogstr</td>\n";
				if(in_array('uclass',$u_lists)) $itemstr .= "<td class=\"item\">$uclassstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"item\">$channelstr</td>\n";
				foreach($cotypes as $k => $v) if(!$v['self_reg'] && in_array('ccid'.$k,$u_lists)) $itemstr .= "<td class=\"item\">".${'ccid'.$k.'str'}."</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"item\">$checkstr</td>\n";
				if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"item\">$validstr</td>\n";
				if(in_array('clicks',$u_lists)) $itemstr .= "<td class=\"item\">$clicksstr</td>\n";
				if(in_array('comments',$u_lists)) $itemstr .= "<td class=\"item\">$commentsstr</td>\n";
				if(in_array('replys',$u_lists)) $itemstr .= "<td class=\"item\">$replysstr</td>\n";
				if(in_array('offers',$u_lists)) $itemstr .= "<td class=\"item\">$offersstr</td>\n";
				if(in_array('orders',$u_lists)) $itemstr .= "<td class=\"item\">$ordersstr</td>\n";
				if(in_array('ordersum',$u_lists)) $itemstr .= "<td class=\"item\">$ordersumstr</td>\n";
				if(in_array('favorites',$u_lists)) $itemstr .= "<td class=\"item\">$favoritesstr</td>\n";
				if(in_array('praises',$u_lists)) $itemstr .= "<td class=\"item\">$praisesstr</td>\n";
				if(in_array('debases',$u_lists)) $itemstr .= "<td class=\"item\">$debasesstr</td>\n";
				if(in_array('answers',$u_lists)) $itemstr .= "<td class=\"item\">$answersstr</td>\n";
				if(in_array('adopts',$u_lists)) $itemstr .= "<td class=\"item\">$adoptsstr</td>\n";
				if(in_array('closed',$u_lists)) $itemstr .= "<td class=\"item\">$closedstr</td>\n";
				if(in_array('downs',$u_lists)) $itemstr .= "<td class=\"item\">$downsstr</td>\n";
				if(in_array('price',$u_lists)) $itemstr .= "<td class=\"item\">$pricestr</td>\n";
				if(in_array('currency',$u_lists)) $itemstr .= "<td class=\"item\">$currencystr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"item\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"item\">$updatedatestr</td>\n";
				if(in_array('refreshdate',$u_lists)) $itemstr .= "<td class=\"item\">$refreshdatestr</td>\n";
				if(in_array('enddate',$u_lists)) $itemstr .= "<td class=\"item\">$enddatestr</td>\n";
				if(in_array('view',$u_lists)) $itemstr .= "<td class=\"item\">$viewstr</td>\n";
				if(in_array('admin',$u_lists)) $itemstr .= "<td class=\"item\">$adminstr</td>\n";;
				$itemstr .= "</tr>\n";
	
	
			}
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts,$mrowpp,$page,"?action=archives$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			//操作区
			tabheader(lang('operateitem'));
			$s_arr = array();
			if(empty($u_operates) || in_array('readd',$u_operates)) $s_arr['readd'] = lang('rearchive');
			if(empty($u_operates) || in_array('abover',$u_operates)) $s_arr['abover'] = lang('setting_album_abover');
			if(empty($u_operates) || in_array('unabover',$u_operates)) $s_arr['unabover'] = lang('cancel_album_abover');
			if(empty($u_operates) || in_array('close',$u_operates)) $s_arr['close'] = lang('closequestion');
			if(empty($u_operates) || in_array('need',$u_operates)) $issueupdatecheck && $s_arr['need'] = lang('archiveupdateneed');
			if(empty($u_operates) || in_array('unneed',$u_operates)) $issueupdatecheck && $s_arr['unneed'] = lang('unneedupdate');
			if(empty($u_operates) || in_array('delete',$u_operates)) $s_arr['delete'] = lang('delarchive');
			if($s_arr){
				$soperatestr = '';
				foreach($s_arr as $k => $v) $soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
				trbasic(lang('choose_item'),'',$soperatestr,'');
			}
			if(empty($u_operates) || in_array('uclass',$u_operates)){
				trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[ucid]\" value=\"1\">&nbsp;".lang('set').lang('mycoclass'),'arcucid',makeoption(array('0' => lang('cancelcoclass')) + $ucidsarr),'select');
			}
			if(empty($u_operates) || in_array('valid',$u_operates)){
				trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[validperiod]\" value=\"1\">&nbsp;".lang('resetvalidperiod'),'arcvalidperiod','','text');
			}
			tabfooter('barcsedit');
			m_guide(@$u_guide);
		}else include(M_ROOT.$u_tplname);
	}else{
		if(empty($arcdeal)) mcmessage('selectoperateitem',M_REFERER);
		if(empty($selectid)) mcmessage('selectarchive',M_REFERER);
		$aedit = new cls_arcedit;
		foreach($selectid as $aid){
			$aedit->init();
			$aedit->set_aid($aid);
			if(!empty($arcdeal['delete'])){
				$aedit->arc_delete(1);
				continue;
			}
			if(!empty($arcdeal['need']) && $issueupdatecheck){
				$aedit->basic_data();
				$aedit->archive['checked'] && $aedit->updatefield('needupdate',$timestamp,'sub');
			}
			if(!empty($arcdeal['unneed'])){
				$aedit->basic_data();
				$aedit->archive['checked'] && $aedit->archive['needupdate'] && $aedit->updatefield('needupdate',0,'sub');
			}
			if(!empty($arcdeal['readd'])){//重发布
				$aedit->readd();
			}
			if(!empty($arcdeal['abover'])){
				$aedit->updatefield('abover',1,'main');
			}
			if(!empty($arcdeal['unabover'])){
				$aedit->updatefield('abover',0,'main');
			}
			if(!empty($arcdeal['close'])){
				$aedit->updatefield('closed',1,'main');
			}
			if(!empty($arcdeal['validperiod'])){
				$arcvalidperiod = empty($arcvalidperiod) ? 0 : max(0,intval($arcvalidperiod));
				$aedit->reset_validperiod($arcvalidperiod);
			}
			$aedit->updatedb();
		}
		unset($aedit);
		if(!empty($arcdeal['ucid'])) $db->query("UPDATE {$tblprefix}archives SET ucid='$arcucid' WHERE aid ".multi_str($selectid),'SILENT');
	
		mcmessage('archiveoperatefinish',axaction(2,"?action=archives$filterstr&page=$page"));
	}
}else include(M_ROOT.$u_tplname);
?>
