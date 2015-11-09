<?php
!defined('M_COM') && exit('No Permission');
load_cache('cotypes,channels,currencys,permissions,inmurls,acatalogs');
//分析页面设置
$nimuid = empty($nimuid) ? 0 : $nimuid;
if($nimuid && $u_url = read_cache('inmurl',$nimuid)){
	$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
	$u_mtitle = $u_url['mtitle'];
	$u_guide = $u_url['guide'];
	$vars = array('sids','chids','filters','lists','operates',);
	$u_url['setting']['sids'] = str_replace('m','0',$u_url['setting']['sids']);
	foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
empty($u_filters) && $u_filters = array('channel','catalog',);
empty($u_lists) && $u_lists = array('catalog','channel','incheck','view','edit',);
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
	if(!$aedit->aid || $aedit->archive['mid'] != $memberid) mcmessage('confchoosarchi');
	
	$catalogs = &$acatalogs;
	
	$caid = empty($caid) ? 0 : max(0,intval($caid));
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$nsid = isset($nsid) ? intval($nsid) : '-1';
	$chid = empty($chid) ? 0 : max(0,intval($chid));
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "b.pid=$aid";
	$fromsql = "FROM {$tblprefix}albums b LEFT JOIN {$tblprefix}archives a ON a.aid=b.aid";
	
	//子站范围
	if($nsid != -1){
		if(!empty($u_sids) && !in_array($nsid,$u_sids)) $no_list = true;
		else $wheresql .= " AND a.sid='$nsid'";
	}elseif(!empty($u_sids)) $wheresql .= " AND a.sid ".multi_str($u_sids);
	
	//栏目范围
	if(!empty($caid)){
		$caids = cnsonids($caid,$catalogs);
		$wheresql .= " AND a.caid ".multi_str($caids);
	}
	
	//模型范围
	if($chid){
		if(!empty($u_chids) && !in_array($chid,$u_chids)) $no_list = true;
		else $wheresql .= " AND a.chid='$chid'";
	}elseif(!empty($u_chids)) $wheresql .= " AND a.chid ".multi_str($u_chids);
	
	//搜索关键词处理
	$keyword && $wheresql .= " AND (a.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
	
	$filterstr = '';
	foreach(array('nimuid','caid','chid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('nsid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	
	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	//echo $wheresql;
	
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($action.'arcsedit',"?action=inarchives&aid=$aid&page=$page");
			//搜索区块
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"item2\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//模型搜索
			if(empty($u_filters) || in_array('channel',$u_filters)){
				$chidsarr = array('0' => lang('all_channel')) + chidsarr();
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
			tabheader($aedit->archive['subject'].'&nbsp; '.(empty($u_mtitle) ? lang('content_list') : $u_mtitle),'','',20);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
			if(in_array('subsite',$u_lists)) $cy_arr[] = lang('subsite');
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('valid',$u_lists)) $cy_arr[] = lang('available');
			if(in_array('incheck',$u_lists)) $cy_arr[] = lang('incheck');
			$cy_arr[] = lang('inorder');
			if(in_array('clicks',$u_lists)) $cy_arr[] = lang('clicks');
			if(in_array('comments',$u_lists)) $cy_arr[] = lang('comments');
			if(in_array('replys',$u_lists)) $cy_arr[] = lang('replys');
			if(in_array('offers',$u_lists)) $cy_arr[] = lang('offers');
			if(in_array('orders',$u_lists)) $cy_arr[] = lang('order_num');
			if(in_array('ordersum',$u_lists)) $cy_arr[] = lang('ordersum');
			if(in_array('favorites',$u_lists)) $cy_arr[] = lang('favorites');
			if(in_array('praises',$u_lists)) $cy_arr[] = lang('praises');
			if(in_array('debases',$u_lists)) $cy_arr[] = lang('debases');
			if(in_array('answers',$u_lists)) $cy_arr[] = lang('answers');
			if(in_array('adopts',$u_lists)) $cy_arr[] = lang('adopts');
			if(in_array('closed',$u_lists)) $cy_arr[] = lang('close');
			if(in_array('downs',$u_lists)) $cy_arr[] = lang('download');
			if(in_array('price',$u_lists)) $cy_arr[] = lang('price');
			if(in_array('currency',$u_lists)) $cy_arr[] = lang('reward');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_time');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('update_time');
			if(in_array('refreshdate',$u_lists)) $cy_arr[] = lang('readd_time');
			if(in_array('enddate',$u_lists)) $cy_arr[] = lang('end1_time');
			if(in_array('view',$u_lists)) $cy_arr[] = lang('message');
			if(in_array('edit',$u_lists)) $cy_arr[] = lang('edit');
			trcategory($cy_arr);
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT a.*,b.*,a.checked AS inchecked $fromsql $wheresql ORDER BY b.vieworder ASC,a.aid LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);
	
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$nchannel = read_cache('channel',$row['chid']);
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[abid]]\" value=\"$row[aid]\">";
				$row['arcurl'] = view_arcurl($row);
				$subjectstr = ($row['thumb'] ? lang('imged') : '')." &nbsp;<a href=$row[arcurl] target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
				$catalogstr = @$acatalogs[$row['caid']]['title'];
				$channelstr = @$nchannel['cname'];
				$subsitestr = $row['sid'] ? @$subsites[$row['sid']]['sitename'] : lang('msite');
				$checkstr = $row['inchecked'] ? 'Y' : '-';
				$validstr = !$row['enddate'] || $row['enddate'] > $timestamp ? 'Y' : '-';
				$incheckstr = $row['checked'] ? 'Y' : '-';
				$vieworderstr = "<input type=\"text\" size=\"5\" maxlength=\"5\" name=\"albumsnew[".$row['abid']."][vieworder]\" value=\"".$row['vieworder']."\">";
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
				$editstr = $row['mid'] == $memberid ? "<a href=\"?action=archive&aid=$row[aid]\" onclick=\"return floatwin('open_archiveedit',this)\">".lang('detail')."</a>" : '-';
	
				$itemstr .= "<tr class=\"txt\"><td class=\"item\" >$selectstr</td><td class=\"item2\">$subjectstr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"item\">$catalogstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"item\">$channelstr</td>\n";
				if(in_array('subsite',$u_lists)) $itemstr .= "<td class=\"item\">$subsitestr</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"item\">$checkstr</td>\n";
				if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"item\">$validstr</td>\n";
				if(in_array('incheck',$u_lists)) $itemstr .= "<td class=\"item\">$incheckstr</td>\n";
				$itemstr .= "<td class=\"item\">$vieworderstr</td>\n";
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
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"item\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}
	
			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $mrowpp, $page, "?action=inarchives&aid=$aid$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
	
			//操作区
			tabheader(lang('operate_item'));
			$s_arr = array();
			if(empty($u_operates) || in_array('inclear',$u_operates)) $s_arr['inclear'] = lang('inclear');
			if(empty($u_operates) || in_array('incheck',$u_operates)) $s_arr['incheck'] = lang('incheck');
			if(empty($u_operates) || in_array('inuncheck',$u_operates)) $s_arr['inuncheck'] = lang('inuncheck');
			if(empty($u_operates) || in_array('delete',$u_operates)) $s_arr['delete'] = lang('delete');
			if(empty($u_operates) || in_array('abover',$u_operates)) $s_arr['abover'] = lang('setting_album_abover');
			if(empty($u_operates) || in_array('unabover',$u_operates)) $s_arr['unabover'] = lang('cancel_album_abover');
			if(empty($u_operates) || in_array('readd',$u_operates)) $s_arr['readd'] = lang('readd');
			if($s_arr){
				$soperatestr = '';
				foreach($s_arr as $k => $v) $soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
				trbasic(lang('choose_item'),'',$soperatestr,'');
			}
			tabfooter('barcsedit');
			m_guide(@$u_guide);
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($arcdeal) && empty($albumsnew)) mcmessage('selectoperateitem',M_REFERER);
		if(empty($selectid) && empty($albumsnew)) mcmessage('selectarchive',M_REFERER);
		$naid = $aid;
		if(!empty($albumsnew)){
			foreach($albumsnew as $k => $v) $db->query("UPDATE {$tblprefix}albums SET vieworder='".max(0,intval($v['vieworder']))."' WHERE abid='$k'");
		}
		if(!empty($selectid)){
			//合辑内的退出合辑，辑内审核，辑内解审
			if(!empty($arcdeal['inclear'])){
				$db->query("DELETE FROM {$tblprefix}albums WHERE abid ".multi_str(array_keys($selectid)), 'UNBUFFERED');
			}elseif(!empty($arcdeal['incheck'])){
				$db->query("UPDATE {$tblprefix}albums SET checked='1' WHERE abid ".multi_str(array_keys($selectid)));
			}elseif(!empty($arcdeal['inuncheck'])){
				$db->query("UPDATE {$tblprefix}albums SET checked='0' WHERE abid ".multi_str(array_keys($selectid)));
			}
			$aedit = new cls_arcedit;
			foreach($selectid as $abid => $aid){
				$aedit->set_aid($aid);
				$aedit->basic_data();
				if(!empty($arcdeal['delete'])){
					$aedit->arc_delete(1);
					continue;
				}
				if(!empty($arcdeal['readd'])){//重发布
					$aedit->readd(0);
				}
				if(!empty($arcdeal['abover'])){
					$aedit->updatefield('abover',1,'main');
				}elseif(!empty($arcdeal['unabover'])){
					$aedit->updatefield('abover',0,'main');
				}
	
				$aedit->updatedb();
				$aedit->init();
			}
			unset($aedit);
		
		}
		mcmessage('arcfinish',"?action=inarchives&aid=$naid&page=$page$filterstr");
	}
}else include(M_ROOT.$u_tplname);
?>
