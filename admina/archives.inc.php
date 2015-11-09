<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('normal') || amessage('no_apermission');
load_cache('cotypes,channels,currencys,permissions,inurls');
load_cache('catalogs,mtpls,cnodes',$sid);
include_once M_ROOT."./include/parse.fun.php";
include_once M_ROOT."./include/arcedit.cls.php";
include_once M_ROOT."./include/commu.fun.php";
if($action == 'archivesedit'){//允许不区分栏目进行管理
	//分析页面设置
	$nauid = empty($nauid) ? 0 : $nauid;
	$u_checked = $u_valid = -1;
	if($nauid && $u_url = read_cache('aurl',$nauid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		foreach(array('checked','valid',) as $var) ${'u_'.$var} = $u_url['setting'][$var];
		$vars = array('chids','filters','lists','operates','iuids',);
		foreach($cotypes as $k => $v) $vars[] = 'ccids'.$k;
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('catalog','channel','check','view','admin',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$caid = empty($caid) ? 0 : max(0,intval($caid));
		$page = !empty($page) ? max(1, intval($page)) : 1;
		submitcheck('bfilter') && $page = 1;
		$viewdetail = empty($viewdetail) ? 0 : 1;
		$chid = empty($chid) ? 0 : max(0,intval($chid));
		$valid = isset($valid) ? $valid : '-1';
		$checked = isset($checked) ? $checked : '-1';
		$myid = empty($myid) ? 0 : max(0,intval($myid));
		$keyword = empty($keyword) ? '' : $keyword;
		$indays = empty($indays) ? 0 : max(0,intval($indays));
		$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
		$wheresql = "a.sid='$sid'";
		$fromsql = "FROM {$tblprefix}archives a";
		$myid && $fromsql .= " LEFT JOIN {$tblprefix}archives_sub s ON (s.aid=a.aid)";
		
		
		$caids = array(-1);
		if(!empty($caid)) $caids = cnsonids($caid,$catalogs);
		if(!in_array(-1,$a_caids)) $caids = in_array(-1,$caids) ? $a_caids : array_intersect($caids,$a_caids);
		if(!$caids) $no_list = true;
		elseif(!in_array(-1,$caids) && ($cnsql = cnsql(0,$caids,'a.'))) $wheresql .= " AND $cnsql";//////////////
	
		if($chid){
			if(!empty($u_chids) && !in_array($chid,$u_chids)) $no_list = true;
			else $wheresql .= " AND a.chid='$chid'";
		}elseif(!empty($u_chids)) $wheresql .= " AND a.chid ".multi_str($u_chids);
		
		if($checked == -1){
			$u_checked != -1 && $wheresql .= " AND a.checked='$u_checked'";
		}elseif(in_array($checked,array(0,1))){
			if(!in_array($u_checked,array(-1,$checked))) $no_list = true;
			else $wheresql .= " AND a.checked='$checked'";
		}elseif(in_array($checked,array(11,12))){
			if(!in_array($u_checked,array(-1,0))) $no_list = true;
			else $wheresql .= " AND a.checked=0 AND a.chkstate='".($checked - 10)."'";
		}
		
		if($myid){
			if($myid == 1){
				$wheresql .= " AND a.mid='$memberid'";
			}elseif($myid == 2){
				$wheresql .= " AND (s.editorid='$memberid' OR s.editorid1='$memberid' OR s.editorid2='$memberid' OR s.editorid3='$memberid')";
			}elseif($myid == 3){
				if($str = myneedchkstr()) $wheresql .= " AND $str";
			}
		}
		if($valid != -1){
			if(($u_valid != -1) && $valid != $u_valid) $no_list = true;
			else $wheresql .= $valid ? " AND (a.enddate='0' OR a.enddate>'$timestamp')" : " AND a.enddate>'0' AND a.enddate<'$timestamp'";
		}elseif($u_valid != -1) $wheresql .= $u_valid ? " AND (a.enddate='0' OR a.enddate>'$timestamp')" : " AND a.enddate>'0' AND a.enddate<'$timestamp'";
	
		$keyword && $wheresql .= " AND (a.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
	
		$indays && $wheresql .= " AND a.createdate>'".($timestamp - 86400 * $indays)."'";
		$outdays && $wheresql .= " AND a.createdate<'".($timestamp - 86400 * $outdays)."'";
	
		$filterstr = '';
		foreach(array('nauid','viewdetail','caid','chid','myid','keyword','indays','outdays',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
		foreach(array('checked','valid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	
		foreach($cotypes as $coid => $cotype){
			${"ccid$coid"} = isset(${"ccid$coid"}) ? max(0,intval(${"ccid$coid"})) : 0;
			$ccids = array();
			if(!empty(${"ccid$coid"})){
				$coclasses = read_cache('coclasses',$coid);
				$ccids = cnsonids(${"ccid$coid"},$coclasses);
				if(!empty(${'u_ccids'.$coid})) $ccids = array_intersect($ccids,${'u_ccids'.$coid});
			}elseif(!empty(${'u_ccids'.$coid})) $ccids = ${'u_ccids'.$coid};
			if(!empty($ccids)){
				if($cnsql = cnsql($coid,$ccids,'a.')) $wheresql .= " AND $cnsql";//////////////
			}elseif(!empty(${"ccid$coid"}) || !empty(${'u_ccids'.$coid})) $no_list = true;
			${"ccid$coid"} && $filterstr .= "&ccid$coid=".${"ccid$coid"};
		}
		$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
		if(!submitcheck('barcsedit')){
			if(empty($u_tplname)){
				echo form_str($actionid.'arcsedit',"?entry=archives&action=$action&page=$page$param_suffix");
				//搜索区块
	
				tabheader_e();
				echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
				//关键词固定显示
				echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
				if(empty($u_filters) || in_array('myid',$u_filters)){
					echo "<select style=\"vertical-align: middle;\" name=\"myid\">".makeoption(array('0' => lang('nomyid'),'1' => lang('myadd'),'2' => lang('mycheck'),'3' => lang('myneedchk')),$myid)."</select>&nbsp; ";
				}
				//模型搜索
				if(empty($u_filters) || in_array('channel',$u_filters)){
					$chidsarr = array('0' => lang('all_channel')) + chidsarr();
					echo "<select style=\"vertical-align: middle;\" name=\"chid\">".makeoption($chidsarr,$chid)."</select>&nbsp; ";
				}
				echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">".viewcheck('viewdetail',$viewdetail,$actionid.'tbodyfilter');
				echo "</td></tr>";
	
	
				//某些固定页面参数
				trhidden('caid',$caid);
				trhidden('nauid',$nauid);
	
				//隐藏区块
				echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
				//审核状态
				if(empty($u_filters) || in_array('check',$u_filters)){
					$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheck'));
					for($i = 1;$i < $max_chklv;$i ++) $checkedarr[10+$i] = lang('check_'.$i);
					$checkedarr[1] = lang('check');
					trbasic(lang('check_state'),'',makeradio('checked',$checkedarr,$checked),'');
				}
				//有效状态
				if(empty($u_filters) || in_array('valid',$u_filters)){
					$validarr = array('-1' => lang('nolimit'),'0' => lang('invalid'),'1' => lang('available'));
					trbasic(lang('validperiod_state'),'',makeradio('valid',$validarr,$valid),'');
				}
				//类系筛选
				foreach($cotypes as $coid => $cotype){
					if(empty($u_filters) || in_array('ccid'.$coid,$u_filters)){
						tr_cns("$cotype[cname]","ccid$coid",${"ccid$coid"},$sid,$coid,0,lang('p_choose'),1,0,1);
					}
				}
				//日期筛选
				if(empty($u_filters) || in_array('date',$u_filters)){
					trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
				}
				echo "</tbody>";
				tabfooter();
		
		
				//列表区	
				tabheader(lang('content_list')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);
	
				$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array('&nbsp;'.lang('title'),'txtL'),);
				if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
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
				if(in_array('vieworder',$u_lists)) $cy_arr[] = lang('order');
				if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_time');
				if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('update_time');
				if(in_array('refreshdate',$u_lists)) $cy_arr[] = lang('readd_time');
				if(in_array('enddate',$u_lists)) $cy_arr[] = lang('end1_time');
				if(in_array('view',$u_lists)) $cy_arr[] = lang('message');
				if(in_array('admin',$u_lists)) $cy_arr[] = array(lang('admin').'&nbsp; ','txtR');
				trcategory($cy_arr);
	
				$pagetmp = $page;
				do{
					$query = $db->query("SELECT * $fromsql $wheresql ORDER BY a.aid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
					$pagetmp--;
				} while(!$db->num_rows($query) && $pagetmp);
	
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
							${'ccid'.$k.'str'} = cnstitle($row['ccid'.$k],$v['asmode'],$coclasses);/////////////
						}
					}
					$checkstr = $row['checked'] ? 'Y' : (in_array($row['chkstate'],array(1,2)) ? $row['chkstate'] : 0).'/'.$channel['chklv'];
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
					$vieworderstr = $row['vieworder'];
					$adddatestr = $row['createdate'] ? date('Y-m-d',$row['createdate']) : '-';
					$updatedatestr = $row['updatedate'] ? date('Y-m-d',$row['updatedate']) : '-';
					$refreshdatestr = $row['refreshdate'] ? date('Y-m-d',$row['refreshdate']) : '-';
					$enddatestr = $row['enddate'] ? date('Y-m-d',$row['enddate']) : '-';
					$viewstr = "<a id=\"{$actionid}_info_$row[aid]\" href=\"?entry=archive&action=viewinfos&aid=$row[aid]$param_suffix\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";
	
					$adminstr = '';
					if(empty($u_iuids) || empty($channel['iuids'])){
						$adminstr .= "<a href=\"?entry=inarchive&aid=$row[aid]$param_suffix\" onclick=\"return floatwin('open_inarchive',this)\">".lang('admin')."</a>&nbsp; ";
						$adminstr .= "<a href=\"?entry=archive&action=archivedetail&aid=$row[aid]$param_suffix\" onclick=\"return floatwin('open_inarchive',this)\">".lang('edit')."</a>";
					}else{
						$n_iuids = array_intersect($u_iuids,explode(',',$channel['iuids']));
						foreach($n_iuids as $k) if(!empty($inurls[$k])) $adminstr .= "<a href=\"".$inurls[$k]['url']."$row[aid]$param_suffix\" onclick=\"return floatwin('open_inarchive',this)\">".$inurls[$k]['cname']."</a>&nbsp; ";
					}
	
					$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
					if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
					if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
					foreach($cotypes as $k => $v) if(!$v['self_reg'] && in_array('ccid'.$k,$u_lists)) $itemstr .= "<td class=\"txtC\">".${'ccid'.$k.'str'}."</td>\n";
					if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$checkstr</td>\n";
					if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$validstr</td>\n";
					if(in_array('clicks',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$clicksstr</td>\n";
					if(in_array('comments',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$commentsstr</td>\n";
					if(in_array('replys',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$replysstr</td>\n";
					if(in_array('offers',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$offersstr</td>\n";
					if(in_array('orders',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$ordersstr</td>\n";
					if(in_array('ordersum',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$ordersumstr</td>\n";
					if(in_array('favorites',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$favoritesstr</td>\n";
					if(in_array('praises',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$praisesstr</td>\n";
					if(in_array('debases',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$debasesstr</td>\n";
					if(in_array('answers',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$answersstr</td>\n";
					if(in_array('adopts',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$adoptsstr</td>\n";
					if(in_array('closed',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$closedstr</td>\n";
					if(in_array('downs',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$downsstr</td>\n";
					if(in_array('price',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$pricestr</td>\n";
					if(in_array('currency',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$currencystr</td>\n";
					if(in_array('vieworder',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$vieworderstr</td>\n";
					if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
					if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$updatedatestr</td>\n";
					if(in_array('refreshdate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$refreshdatestr</td>\n";
					if(in_array('enddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$enddatestr</td>\n";
					if(in_array('view',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$viewstr</td>\n";
					if(in_array('admin',$u_lists)) $itemstr .= "<td class=\"txtR\">$adminstr</td>\n";
					$itemstr .= "</tr>\n";
				}
	
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$multi = multi($counts, $atpp, $page, "?entry=archives&action=$action$param_suffix$filterstr");
				echo $itemstr;
				tabfooter();
				echo $multi;
	
				//操作区
				tabheader(lang('operate_item'));
				$s_arr = array();
				if(empty($u_operates) || in_array('delete',$u_operates)) $s_arr['delete'] = lang('delete');
				if(empty($u_operates) || in_array('check',$u_operates)) $s_arr += chksarr($a_checks,1);
				if(empty($u_operates) || in_array('uncheck',$u_operates)) $s_arr += chksarr($a_checks,0);
				if(empty($u_operates) || in_array('abover',$u_operates)) $s_arr['abover'] = lang('setting_album_abover');
				if(empty($u_operates) || in_array('unabover',$u_operates)) $s_arr['unabover'] = lang('cancel_album_abover');
				if(empty($u_operates) || in_array('readd',$u_operates)) $s_arr['readd'] = lang('readd');
				if(empty($u_operates) || in_array('abstract',$u_operates)) $s_arr['autoabstract'] = lang('auto_abstract');
				if(empty($u_operates) || in_array('thumb',$u_operates)) $s_arr['autothumb'] = lang('auto_thumb');
				if(empty($u_operates) || in_array('atmsize',$u_operates)) $s_arr['autosize'] = lang('stat_attachment_size');
				if(empty($u_operates) || in_array('keyword',$u_operates)) $s_arr['autokeyword'] = lang('auto_keyword');
				if($s_arr){
					$soperatestr = '';
					$i = 1;
					foreach($s_arr as $k => $v){
						$soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
						if(!($i % 5)) $soperatestr .= '<br>';
						$i ++;
					}
					trbasic(lang('choose_item'),'',$soperatestr,'');
				}
				if(empty($u_operates) || in_array('catalog',$u_operates)){/////////////
					tr_cns("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[caid]\" value=\"1\">&nbsp;".lang('set').lang('catalog'),'arccaid',0,$sid,0,0,lang('p_choose'));
				}
				foreach($cotypes as $k => $v){
					if(empty($v['self_reg'])){
						if(empty($u_operates) || in_array('ccid'.$k,$u_operates)){////////////////
							tr_cns("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[ccid$k]\" value=\"1\">&nbsp;".lang('set')."$v[cname]","arcccid$k",'',$sid,$k,0,lang('p_choose'),0,$v['asmode'],0,$v['emode'],"arcccid{$k}date");
						}
					}
				}
				if(empty($u_operates) || in_array('setalbum',$u_operates)){
					trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[album]\" value=\"1\">&nbsp;".lang('setalbum_input'),'arcalbum');
				}
				if(empty($u_operates) || in_array('valid',$u_operates)){
					trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[validperiod]\" value=\"1\">&nbsp;".lang('reset_validperiod_days'),'arcvalidperiod','','text');
				}
				if(empty($u_operates) || in_array('prior',$u_operates)){
					trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[vieworder]\" value=\"1\">&nbsp;".lang('order_prior'),'arcvieworder');
				}
				if(empty($u_operates) || in_array('rpmid',$u_operates)){
					trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[rpmid]\" value=\"1\">&nbsp;".lang('read_pmid'),'arcrpmid',makeoption(array('-1' => lang('fromcata')) + pmidsarr('aread'),-1),'select');
				}
				if(empty($u_operates) || in_array('dpmid',$u_operates)){
					trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[dpmid]\" value=\"1\">&nbsp;".lang('down_pmid'),'arcdpmid',makeoption(array('-1' => lang('fromcata')) + pmidsarr('down'),-1),'select');
				}
				if(empty($u_operates) || in_array('sale',$u_operates)){
					trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[salecp]\" value=\"1\">&nbsp;".lang('arc_price'),'arcsalecp',makeoption(array('' => lang('freesale')) + $vcps['sale']),'select');
				}
				if(empty($u_operates) || in_array('fsale',$u_operates)){
					trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[fsalecp]\" value=\"1\">&nbsp;".lang('annex_price'),'arcfsalecp',makeoption(array('' => lang('freesale')) + $vcps['fsale']),'select');
				}
				if(empty($u_operates) || in_array('cpcatalog',$u_operates)){////////////////
					tr_cns("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[cpcaid]\" value=\"1\">&nbsp;".lang('addcp',lang('catalog')),'arccpcaid','',$sid,0,0,lang('p_choose'),0,5);
				}
				foreach($cotypes as $k => $v){
					if(empty($v['self_reg']) && empty($v['asmode'])){
						if(empty($u_operates) || in_array('cpccid'.$k,$u_operates)){/////////////////////
							tr_cns("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[cpccid$k]\" value=\"1\">&nbsp;".lang('addcp',$v['cname']),'arccpccid'.$k,'',$sid,$k,0,lang('p_choose'),0,5);
						}
					}
				}
				tabfooter('barcsedit');
				a_guide('archivesedit');
			
			}else include(M_ROOT.$u_tplname);
			
		}else{
			if(empty($arcdeal) && empty($dealstr)){
				amessage('selectoperateitem',axaction(1,M_REFERER));
			}
			if(empty($selectid) && empty($select_all)){
				amessage('selectarchive',axaction(1,M_REFERER));
			}
			if(!empty($select_all)){
				if(empty($dealstr)){
					$dealstr = implode(',',array_keys(array_filter($arcdeal)));
				}else{
					$arcdeal = array();
					foreach(array_filter(explode(',',$dealstr)) as $k)$arcdeal[$k] = 1;
				}
	
				$parastr = "";
				foreach(array('arcchecked','arccaid','arccpcaid','arcalbum','arcvieworder','arcvalidperiod',) as $k){
					$parastr .= "&$k=".@$$k;
				}
				foreach($cotypes as $k => $v){
					empty($v['self_reg']) && $parastr .= "&arcccid$k=".@${"arcccid$k"}."&arcccid{$k}date=".@${"arcccid{$k}date"}."&arccpccid$k=".@${"arccpccid$k"};
				}
				$selectid = array();
				$npage = empty($npage) ? 1 : $npage;
				if(empty($pages)) $pages = @ceil($db->result_one("SELECT count(*) $fromsql $wheresql") / $atpp);
				if($npage <= $pages){
					$fromstr = empty($fromid) ? "" : "a.aid<$fromid";
					$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
					$query = $db->query("SELECT a.aid $fromsql $nwheresql ORDER BY a.aid DESC LIMIT 0,$atpp");
					while($item = $db->fetch_array($query)) $selectid[] = $item['aid'];
				}
			}
			$aedit = new cls_arcedit;
			if(!empty($arcdeal['autokeyword'])){
				include_once M_ROOT."./include/splitword.cls.php";
				$a_split = new SplitWord();
			}
			if(!empty($arcdeal['autothumb'])){
				include_once M_ROOT."./include/upload.cls.php";
				$c_upload = new cls_upload;
			}
			foreach($selectid as $aid){
				$aedit->set_aid($aid);
				$aedit->basic_data();
				if(!empty($arcdeal['delete'])){
					$aedit->arc_delete();
					continue;
				}
				if(!empty($arcdeal['readd'])){//重发布
					$aedit->readd(1);
				}
				if(!empty($arcdeal['autoabstract'])){
					$aedit->autoabstract();
				}
				if(!empty($arcdeal['autothumb'])){
					$aedit->autothumb();
					if(!empty($c_upload->ufids)){
						$c_upload->closure(1, $aid);
						$c_upload->ufids = array();
					}
				}
				if(!empty($arcdeal['autosize'])){
					include_once M_ROOT."./include/fields.cls.php";
					$aedit->autosize();
				}
				if(!empty($arcdeal['autokeyword'])){
					$aedit->autokeyword();
				}
				if($curuser->pmbypmids('chk',$aedit->channel['chpmid'])){
					$_chk = 0;
					foreach(array(-1,1,2,3,13,12,11) as $v){
						if(!empty($arcdeal['check'.$v])){
							if($_chk && $v > 10) break;
							$aedit->pre_check($v);
							if($v == -1) break;
							if($v <10) $_chk = 1;
						}
					}
				}
		
				if(!empty($arcdeal['abover'])){
					$aedit->updatefield('abover',1,'main');
				}elseif(!empty($arcdeal['unabover'])){
					$aedit->updatefield('abover',0,'main');
				}
	
				if(!empty($arcdeal['caid'])){
					$aedit->arc_caid($arccaid);
				}
				if(!empty($arcdeal['cpcaid'])){
					$ids = array_filter(explode(',',$arccpcaid));
					foreach($ids as $id) $aedit->addcopy(0,$id);
				}
				foreach($cotypes as $k => $v){
					if(!empty($arcdeal['ccid'.$k])){
						$aedit->arc_ccid(empty(${'arcccid'.$k}) ? '' : ${'arcccid'.$k},$k);
						if($v['emode']) $aedit->updatefield("ccid{$k}date",!isdate(${"arcccid{$k}date"}) || !$aedit->archive["ccid$k"] ? 0 : strtotime(${"arcccid{$k}date"}),'main');
					}
					if(!empty($arcdeal['cpccid'.$k])){
						$ids = array_filter(explode(',',${'arccpccid'.$k}));
						foreach($ids as $id) $aedit->addcopy($k,$id);
					}
				}
				if(!empty($arcdeal['album'])){
					$aedit->set_album($arcalbum);
				}
				if(!empty($arcdeal['validperiod'])){
					$aedit->reset_validperiod($arcvalidperiod);
				}
				if(!empty($arcdeal['vieworder'])){
					$aedit->updatefield('vieworder',$arcvieworder,'main');
				}
				if(!empty($arcdeal['rpmid'])){
					$aedit->updatefield('rpmid',$arcrpmid,'main');
				}
				if(!empty($arcdeal['dpmid'])){
					$aedit->updatefield('dpmid',$arcdpmid,'main');
				}
				if(!empty($arcdeal['fsalecp'])){
					$aedit->updatefield('fsalecp',$arcfsalecp,'main');
					$aedit->sale_define();
				}
				if(!empty($arcdeal['salecp'])){
					$aedit->updatefield('salecp',$arcsalecp,'main');
					$aedit->sale_define();
				}
				$aedit->updatedb();
				$aedit->init();
			}
			if(!empty($arcdeal['autothumb'])) $c_upload->saveuptotal(1);
			unset($aedit,$a_split,$c_upload);
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
					amessage('operating',"?entry=archives&action=$action&page=$page$param_suffix$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=archives&action=$action&page=$page$param_suffix$filterstr\">",'</a>');
				}
			}
			adminlog(lang('arc_update_admin'),lang('arc_list_aoperate'));
			amessage('arcfinish',"?entry=archives&action=$action$param_suffix&page=$page$filterstr");
		}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'archivesupdate'){
	empty($u_lists) && $u_lists = array('catalog','channel','status','view','admin',);

	$caid = empty($caid) ? 0 : max(0,intval($caid));
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : 1;
	$status = isset($status) ? $status : '-1';
	$chid = empty($chid) ? 0 : max(0,intval($chid));
	$keyword = empty($keyword) ? '' : $keyword;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
	$wheresql = "a.sid='$sid' AND s.needupdate!=0";
	$fromsql = "FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid";
	
	//栏目范围
	$caids = array(-1);
	if(!empty($caid)) $caids = cnsonids($caid,$catalogs);
	if(!in_array(-1,$a_caids)) $caids = in_array(-1,$caids) ? $a_caids : array_intersect($caids,$a_caids);
	if(!$caids) $no_list = true;
	elseif(!in_array(-1,$caids) && ($cnsql = cnsql(0,$caids,'a.'))) $wheresql .= " AND $cnsql";

	//模型范围
	if($chid){
		if(!empty($u_chids) && !in_array($chid,$u_chids)) $no_list = true;
		else $wheresql .= " AND a.chid='$chid'";
	}elseif(!empty($u_chids)) $wheresql .= " AND a.chid ".multi_str($u_chids);

	//状态范围
	if($status != -1){
		switch($status){
		case '0':
			$wheresql .= " AND a.checked=1";
			break;
		case '1':
			$wheresql .= " AND a.checked=0 AND s.overupdate=0";
			break;
		case '2':
			$wheresql .= " AND s.overupdate!=0";
			break;
		}
	}

	//搜索关键词处理
	$keyword && $wheresql .= " AND (a.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";

	$indays && $wheresql .= " AND a.createdate>'".($timestamp - 86400 * $indays)."'";
	$outdays && $wheresql .= " AND a.createdate<'".($timestamp - 86400 * $outdays)."'";

	$filterstr = '';
	foreach(array('nauid','viewdetail','caid','chid','keyword','indays','outdays',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('status') as $k) $$k != -1 && $filterstr .= "&$k=".$$k;


	foreach($cotypes as $coid => $cotype){
		${"ccid$coid"} = isset(${"ccid$coid"}) ? max(0,intval(${"ccid$coid"})) : 0;
		$ccids = array();
		if(!empty(${"ccid$coid"})){
			$coclasses = read_cache('coclasses',$coid);
			$ccids = cnsonids(${"ccid$coid"},$coclasses);
			if(!empty(${'u_ccids'.$coid})) $ccids = array_intersect($ccids,${'u_ccids'.$coid});
		}elseif(!empty(${'u_ccids'.$coid})) $ccids = ${'u_ccids'.$coid};
		
		
		if(!empty($ccids)){
			if($cnsql = cnsql($coid,$ccids,'a.')) $wheresql .= " AND $cnsql";
		}elseif(!empty(${"ccid$coid"}) || !empty(${'u_ccids'.$coid})) $no_list = true;
		${"ccid$coid"} && $filterstr .= "&ccid$coid=".${"ccid$coid"};
	}
	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');

	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'arcsedit',"?entry=archives&action=$action&page=$page$param_suffix");
			//搜索区块

			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//模型搜索
			if(empty($u_filters) || in_array('channel',$u_filters)){
				$chidsarr = array('0' => lang('all_channel')) + chidsarr();
				echo "<select style=\"vertical-align: middle;\" name=\"chid\">".makeoption($chidsarr,$chid)."</select>&nbsp; ";
			}
			$statusarr = array('-1' => lang('nolimit'), '0' => lang('update_need'), '1' => lang('checkneed'), '2' => lang('overupdate'));
			echo "<select style=\"vertical-align: middle;\" name=\"status\">".makeoption($statusarr,$status)."</select>&nbsp; ";
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">".viewcheck('viewdetail',$viewdetail,$actionid.'tbodyfilter');
			echo "</td></tr>";


			//某些固定页面参数
			trhidden('caid',$caid);
			trhidden('nauid',$nauid);

			//隐藏区块
			echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
			//类系筛选
			foreach($cotypes as $coid => $cotype){
				if(empty($u_filters) || in_array('ccid'.$coid,$u_filters)){
					tr_cns("$cotype[cname]","ccid$coid",${"ccid$coid"},$sid,$coid,0,lang('p_choose'),0,0,1);
				}
			}
			//日期筛选
			if(empty($u_filters) || in_array('date',$u_filters)){
				trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
			}
			echo "</tbody>";
			tabfooter();
	
	
			//列表区	
			tabheader(lang('content_list')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);

			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array('&nbsp;'.lang('title'),'txtL'),);
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
			foreach($cotypes as $k => $v) if(!$v['self_reg'] && in_array('ccid'.$k,$u_lists)) $cy_arr["ccid$k"] = $v['cname'];
			if(in_array('status',$u_lists)) $cy_arr[] = lang('status');
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
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_time');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('update_time');
			if(in_array('refreshdate',$u_lists)) $cy_arr[] = lang('readd_time');
			if(in_array('enddate',$u_lists)) $cy_arr[] = lang('end1_time');
			if(in_array('view',$u_lists)) $cy_arr[] = lang('message');
			if(in_array('admin',$u_lists)) $cy_arr[] = array(lang('admin').'&nbsp; ','txtR');
			trcategory($cy_arr);

			$pagetmp = $page;
			do{
				$query = $db->query("SELECT * $fromsql $wheresql ORDER BY s.overupdate DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

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
				$statusstr = $statusarr[$row['overupdate'] ? '2' : ($row['checked'] ? '0' : '1')];
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
				$viewstr = "<a id=\"{$actionid}_info_$row[aid]\" href=\"?entry=archive&action=viewinfos&aid=$row[aid]$param_suffix\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";

				$adminstr = "<a href=\"?entry=archive&action=archivedetail&aid=$row[aid]$param_suffix\" onclick=\"return floatwin('open_inarchive',this)\">".lang('edit')."</a>&nbsp; ";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
				foreach($cotypes as $k => $v) if(!$v['self_reg'] && in_array('ccid'.$k,$u_lists)) $itemstr .= "<td class=\"txtC\">".${'ccid'.$k.'str'}."</td>\n";
				if(in_array('status',$u_lists)) $itemstr .= "<td class=\"txtC w80\">$statusstr</td>\n";
				if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$validstr</td>\n";
				if(in_array('clicks',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$clicksstr</td>\n";
				if(in_array('comments',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$commentsstr</td>\n";
				if(in_array('replys',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$replysstr</td>\n";
				if(in_array('offers',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$offersstr</td>\n";
				if(in_array('orders',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$ordersstr</td>\n";
				if(in_array('ordersum',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$ordersumstr</td>\n";
				if(in_array('favorites',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$favoritesstr</td>\n";
				if(in_array('praises',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$praisesstr</td>\n";
				if(in_array('debases',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$debasesstr</td>\n";
				if(in_array('answers',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$answersstr</td>\n";
				if(in_array('adopts',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$adoptsstr</td>\n";
				if(in_array('closed',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$closedstr</td>\n";
				if(in_array('downs',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$downsstr</td>\n";
				if(in_array('price',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$pricestr</td>\n";
				if(in_array('currency',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$currencystr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$updatedatestr</td>\n";
				if(in_array('refreshdate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$refreshdatestr</td>\n";
				if(in_array('enddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$enddatestr</td>\n";
				if(in_array('view',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$viewstr</td>\n";
				if(in_array('admin',$u_lists)) $itemstr .= "<td class=\"txtR\">$adminstr</td>\n";
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=archives&action=$action$param_suffix$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;

			//操作区
			tabheader(lang('operate_item'));
			$s_arr = array();
			$s_arr['checkneed'] = lang('checkneed');
			$s_arr['uncheckneed'] = lang('uncheckneed');
			$s_arr['checkupdate'] = lang('checkupdate');
			$s_arr['uncheckupdate'] = lang('uncheckupdate');
			if($s_arr){
				$soperatestr = '';
				foreach($s_arr as $k => $v) $soperatestr .= "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[$k]\" value=\"1\">$v &nbsp;";
				trbasic(lang('choose_item'),'',$soperatestr,'');
			}
			tabfooter('barcsedit');
			a_guide('archivesupdate');
		}
	}else{
		if(empty($arcdeal) && empty($dealstr)){
			amessage('selectoperateitem',axaction(1,M_REFERER));
		}
		if(empty($selectid) && empty($select_all)){
			amessage('selectarchive',axaction(1,M_REFERER));
		}
		if(!empty($select_all)){
			if(empty($dealstr)){
				$dealstr = implode(',',array_keys(array_filter($arcdeal)));
			}else{
				$arcdeal = array();
				foreach(array_filter(explode(',',$dealstr)) as $k){
					$arcdeal[$k] = 1;
				}
			}

			$parastr = "";
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$pages = @ceil($counts / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "a.aid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT a.aid $fromsql $nwheresql ORDER BY a.aid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)){
					$selectid[] = $item['aid'];
				}
			}
		}
		$aedit = new cls_arcedit;
		foreach($selectid as $aid){
			$aedit->set_aid($aid);
			$aedit->basic_data();
			if(!empty($arcdeal['checkneed'])){
				$aedit->archive['needupdate'] && $aedit->arc_check(0);
			}elseif(!empty($arcdeal['uncheckneed']) && !$aedit->archive['overupdate']){
				$aedit->archive['checked'] || $aedit->arc_check(1);
				$aedit->updatefield('needupdate',0,'sub');
			}
			if($aedit->archive['overupdate']){
				if(!empty($arcdeal['checkupdate'])){
					$aedit->arc_check(1);
					$aedit->updatefield('needupdate',0,'sub');
					$aedit->updatefield('overupdate',0,'sub');
				}elseif(!empty($arcdeal['uncheckupdate'])){
					$aedit->updatefield('needupdate',0,'sub');
					$aedit->updatefield('overupdate',0,'sub');
				}
			}

			$aedit->updatedb();
			$aedit->init();
		}
		unset($aedit);
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
				amessage('operating',"?entry=archives&action=$action&page=$page$param_suffix$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=archives&action=$action&page=$page$param_suffix$filterstr\">",'</a>');
			}
		}
		adminlog(lang('arc_update_admin'),lang('arc_list_aoperate'));
		amessage('arcfinish',"?entry=archives&action=$action$param_suffix&page=$page$filterstr");
	}
}

?>
