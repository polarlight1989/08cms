<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('normal') || amessage('no_apermission');
load_cache('cotypes,channels,currencys,permissions,inurls,acatalogs');
load_cache('catalogs,mtpls',$sid);
include_once M_ROOT."./include/parse.fun.php";
include_once M_ROOT."./include/arcedit.cls.php";
include_once M_ROOT."./include/commu.fun.php";
$niuid = empty($niuid) ? 0 : max(0,intval($niuid));
$aedit = new cls_arcedit;
$aedit->set_aid($aid);
$aedit->basic_data(0);
$channel = &$aedit->channel;
if(!$aedit->aid) amessage('confchoosarchi');

if(empty($action)){
	$iuids = $channel['iuids'] ? explode(',',$channel['iuids']) : array();
	if(empty($iuids)) foreach($inurls as $k => $v) $v['issys'] && in_array($v['uclass'],array('edit','madd','content','load','setalbum','vol','comment','reply','offer','purchase','report','answer','custom',)) && $iuids[] = $k;
	
	tabheader(lang('inadmin')." &nbsp; &nbsp;<a href=\"".view_arcurl($aedit->archive)."\" target=\"_blank\">>>".$aedit->archive['subject']."</a>");
	foreach($iuids as $k){
		if(!empty($inurls[$k])) trbasic(">><a href=\"".$inurls[$k]['url']."$aid$param_suffix\" onclick=\"return floatwin('open_newinarchive',this)\">".$inurls[$k]['cname']."</a>",'',$inurls[$k]['remark'],'');
	}
	tabfooter();
}elseif($action == 'archives'){
	//分析页面设置
	$niuid = empty($niuid) ? 0 : $niuid;
	if($niuid && $u_url = read_cache('inurl',$niuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$vars = array('sids','chids','filters','lists','operates',);
		$u_url['setting']['sids'] = str_replace('m','0',$u_url['setting']['sids']);
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_filters) && $u_filters = array('channel','catalog',);
	empty($u_lists) && $u_lists = array('catalog','channel','incheck','view','edit',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$catalogs = &$acatalogs;
		$volids = volidsarr($aid);
		
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
		foreach(array('niuid','caid','chid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
		foreach(array('nsid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;
	
		$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
		//echo $wheresql;
	
		if(!submitcheck('barcsedit')){
			if(empty($u_tplname)){
				echo form_str($actionid.'arcsedit',"?entry=inarchive&action=archives&aid=$aid&page=$page$param_suffix");
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
				echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
				echo "</td></tr>";
				//某些固定页面参数
				trhidden('niuid',$niuid);
				tabfooter();
		
				//列表区
				tabheader($aedit->archive['subject'].'&nbsp; '.lang('content_list'),'','',9);
				$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
				if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
				if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
				if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
				if(in_array('subsite',$u_lists)) $cy_arr[] = lang('subsite');
				if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
				if(in_array('valid',$u_lists)) $cy_arr[] = lang('available');
				if(in_array('vol',$u_lists)) $cy_arr[] = lang('vol');
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
					$query = $db->query("SELECT a.*,b.*,a.checked AS inchecked $fromsql $wheresql ORDER BY b.vieworder ASC,a.aid LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
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
					$volstr = empty($volids[$row['volid']]) ? '-' : $volids[$row['volid']];
					$checkstr = $row['inchecked'] ? 'Y' : (in_array($row['chkstate'],array(1,2)) ? $row['chkstate'] : 0).'/'.$nchannel['chklv'];
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
					$viewstr = "<a id=\"{$actionid}_info_$row[aid]\" href=\"?entry=archive&action=viewinfos&aid=$row[aid]$param_suffix\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";
					$editstr = $row['sid'] == $sid ? "<a href=\"?entry=archive&action=archivedetail&aid=$row[aid]$param_suffix\" onclick=\"return floatwin('open_archiveedit',this)\">".lang('detail')."</a>" : '-';//不是相同子站的内容不能编辑
	
					$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
					if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
					if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
					if(in_array('subsite',$u_lists)) $itemstr .= "<td class=\"txtC\">$subsitestr</td>\n";
					if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$checkstr</td>\n";
					if(in_array('valid',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$validstr</td>\n";
					if(in_array('vol',$u_lists)) $itemstr .= "<td class=\"txtC\">$volstr</td>\n";
					if(in_array('incheck',$u_lists)) $itemstr .= "<td class=\"txtC w60\">$incheckstr</td>\n";
					$itemstr .= "<td class=\"txtC w60\">$vieworderstr</td>\n";
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
					if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
					$itemstr .= "</tr>\n";
				}
	
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$multi = multi($counts, $atpp, $page, "?entry=inarchive&action=archives&aid=$aid$param_suffix$filterstr");
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
				if(empty($u_operates) || in_array('check',$u_operates)) $s_arr += chksarr($a_checks,1);
				if(empty($u_operates) || in_array('uncheck',$u_operates)) $s_arr += chksarr($a_checks,0);
				if(empty($u_operates) || in_array('abover',$u_operates)) $s_arr['abover'] = lang('setting_album_abover');
				if(empty($u_operates) || in_array('unabover',$u_operates)) $s_arr['unabover'] = lang('cancel_album_abover');
				if(empty($u_operates) || in_array('readd',$u_operates)) $s_arr['readd'] = lang('readd');
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
				if(empty($u_operates) || in_array('catalog',$u_operates)){
					tr_cns("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[caid]\" value=\"1\">&nbsp;".lang('set').lang('catalog'),'arccaid',0,$sid,0,0,lang('p_choose'));
				}
				foreach($cotypes as $k => $v){
					if(empty($v['self_reg'])){
						if(empty($u_operates) || in_array('ccid'.$k,$u_operates)){
							tr_cns("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[ccid$k]\" value=\"1\">&nbsp;".lang('set')."$v[cname]","arcccid$k",'',$sid,$k,0,lang('p_choose'),0,$v['asmode'],0,$v['emode'],"arcccid{$k}date");
						}
					}
				}
				if(empty($u_operates) || in_array('vol',$u_operates)){
					trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[vol]\" value=\"1\">&nbsp;".lang('set_volid'),'arcvol',makeoption(array('' => lang('nosetting')) + $volids),'select');
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
				tabfooter('barcsedit');
			}else include(M_ROOT.$u_tplname);
			
		}else{
			if(empty($arcdeal) && empty($albumsnew)) amessage('selectoperateitem',axaction(1,M_REFERER));
			if(empty($selectid) && empty($albumsnew)) amessage('selectarchive',axaction(1,M_REFERER));
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
				if(!empty($arcdeal['vol'])){
					$db->query("UPDATE {$tblprefix}albums SET volid='$arcvol' WHERE abid ".multi_str(array_keys($selectid)));
				}
				$aedit = new cls_arcedit;
				foreach($selectid as $abid => $aid){
					$aedit->set_aid($aid);
					$aedit->basic_data();
					if($aedit->archive['sid'] != $sid) continue;//只能编辑当前子站的文档
					if(!empty($arcdeal['delete'])){
						$aedit->arc_delete();
						continue;
					}
					if(!empty($arcdeal['readd'])){//重发布
						$aedit->readd(1);
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
					foreach($cotypes as $k => $v){
						if(!empty($arcdeal['ccid'.$k])){
							$aedit->arc_ccid(${'arcccid'.$k},$k);
							if($v['emode']) $aedit->updatefield("ccid{$k}date",!isdate(${"arcccid{$k}date"}) || !$aedit->archive["ccid$k"] ? 0 : strtotime(${"arcccid{$k}date"}),'main');
						}
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
				unset($aedit);
			
			}
			adminlog(lang('arc_update_admin'),lang('arc_list_aoperate'));
			amessage('arcfinish',"?entry=inarchive&action=archives&aid=$naid$param_suffix&page=$page$filterstr");
		}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'setalbum'){
	//分析页面设置
	$niuid = empty($niuid) ? 0 : $niuid;
	if($niuid && $u_url = read_cache('inurl',$niuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$vars = array('sids','chids','filters','lists',);
		$u_url['setting']['sids'] = str_replace('m','0',$u_url['setting']['sids']);
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('mname','catalog','channel','view',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$catalogs = &$acatalogs;
	$caid = empty($caid) ? 0 : $caid;
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$chid = empty($chid) ? 0 : max(0,intval($chid));
	$nsid = isset($nsid) ? intval($nsid) : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "a.abover=0 AND b.aid IS NULL";//要查出当前允许归辑的模型出来
	$fromsql = "FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}albums b ON (b.pid=a.aid AND b.aid='$aid')";
	
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

	if($chid){
		if(!empty($u_chids) && !in_array($chid,$u_chids)) $no_list = true;
		else $u_chids = array($chid);
	}
	if($chidstr = $aedit->set_chidstr('a.',empty($u_chids) ? array() : $u_chids)){//取得当前文档可以归入的合辑类型
		$wheresql .= " AND $chidstr";
	}else $no_list = 1;

	//搜索关键词处理
	$keyword && $wheresql .= " AND (a.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";

	$filterstr = '';
	foreach(array('niuid','caid','chid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('nsid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;

	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	//echo $wheresql;
	if(!submitcheck('bquitalbum') && !submitcheck('bsetalbum')){
		if(empty($u_tplname)){

			tabheader($aedit->archive['subject'].'&nbsp; '.lang('current_album_set'),$actionid.'quitalbum',"?entry=inarchive&action=setalbum&aid=$aid&page=$page$param_suffix",8);
			$cy_arr = array(lang('exit'),lang('belong_album'),);
			if(in_array('id',$u_lists)) $cy_arr[] = lang('id');
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('channel');//模型与合辑类型综合在一起
			if(in_array('subsite',$u_lists)) $cy_arr[] = lang('subsite');
			if(in_array('view',$u_lists)) $cy_arr[] = lang('message');
			$cy_arr[] = lang('inalbum_check');
			trcategory($cy_arr);
			$query = $db->query("SELECT b.checked,a.aid,a.sid,a.createdate,a.caid,a.chid,a.subject,a.customurl,a.mname FROM {$tblprefix}albums b LEFT JOIN {$tblprefix}archives a ON a.aid=b.pid WHERE b.aid=$aid".(empty($u_chids) ? '' : " AND a.chid ".multi_str($u_chids))." ORDER BY a.aid DESC");
			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$nchannel = read_cache('channel',$row['chid']);
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\"".($nchannel['onlyload'] ? ' disabled' : '').">";
				$idstr = $row['aid'];
				$mnamestr = $row['mname'];
				$subsitestr = $row['sid'] ? $subsites[$row['sid']]['sitename'] : lang('msite');
				$catalogstr = @$acatalogs[$row['caid']]['title'];
				$channelstr = $nchannel['cname'];
				$checkstr = $row['checked'] ? ' Y' : '-';
				$viewstr = "<a id=\"{$actionid}_info_$row[aid]\" href=\"?entry=archive&action=viewinfos&aid=$row[aid]$param_suffix\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('id',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$idstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
				if(in_array('subsite',$u_lists)) $itemstr .= "<td class=\"txtC\">$subsitestr</td>\n";
				if(in_array('view',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$viewstr</td>\n";
				$itemstr .= "<td class=\"txtC w60\">$checkstr</td>\n</tr>\n";
			}
			echo  $itemstr;
			tabfooter('bquitalbum',lang('exit_album'));
			
			//需要归入的合辑管理区***********************************************************
			echo form_str($actionid.'setalbum',"?entry=inarchive&action=setalbum&aid=$aid&page=$page$param_suffix");
			//搜索区块
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";

			//文档模型搜索
			if(empty($u_filters) || in_array('channel',$u_filters)){
				echo "<select style=\"vertical-align: middle;\" name=\"chid\">".makeoption(array('0' => lang('all_channel')) + chidsarr(),$chid)."</select>&nbsp; ";
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
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";

			//某些固定页面参数
			trhidden('niuid',$niuid);
			tabfooter();
	
			//列表区	
			tabheader(lang('choose_want_setin_album'),'','',9);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('album'),);
			if(in_array('id',$u_lists)) $cy_arr[] = lang('id');
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
			if(in_array('subsite',$u_lists)) $cy_arr[] = lang('subsite');
			if(in_array('view',$u_lists)) $cy_arr[] = lang('message');
			trcategory($cy_arr);
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT a.* $fromsql $wheresql ORDER BY a.aid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$nchannel = read_cache('channel',$row['chid']);
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">".mhtmlspecialchars($row['subject'])."</a>";
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[aid]]\" value=\"$row[aid]\">";
				$idstr = $row['aid'];
				$mnamestr = $row['mname'];
				$subsitestr = $row['sid'] ? $subsites[$row['sid']]['sitename'] : lang('msite');
				$catalogstr = @$acatalogs[$row['caid']]['title'];
				$channelstr = $nchannel['cname'];
				$checkstr = $row['checked'] ? ' Y' : '-';
				$viewstr = "<a id=\"{$actionid}_info_$row[aid]\" href=\"?entry=archive&action=viewinfos&aid=$row[aid]$param_suffix\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('id',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$idstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
				if(in_array('subsite',$u_lists)) $itemstr .= "<td class=\"txtC\">$subsitestr</td>\n";
				if(in_array('view',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$viewstr</td>\n";
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=inarchive&action=setalbum&aid=$aid$param_suffix$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			echo '<br><br>'.strbutton('bsetalbum',lang('setalbum')).'</form>';
		}else include(M_ROOT.$u_tplname);
		
	}elseif(submitcheck('bquitalbum')){
		empty($selectid) && amessage('selectalbum',M_REFERER);
		$db->query("DELETE FROM {$tblprefix}albums WHERE aid='$aid' AND pid ".multi_str($selectid), 'UNBUFFERED');
		adminlog(lang('exit_album_admin'),lang('archive_exit_album'));
		amessage('exitalbumfinish',"?entry=inarchive&action=setalbum&aid=$aid$param_suffix&page=$page$filterstr");

	}elseif(submitcheck('bsetalbum')){
		empty($selectid) && amessage('selectalbum',M_REFERER);
		$aedit = new cls_arcedit;
		foreach($selectid as $k){
			$aedit->set_aid($aid);
			$aedit->set_album($k);
			$aedit->init();
		}
		adminlog(lang('setalbum_admin'),lang('archive_setalbum'));
		amessage('setalbumfinish',"?entry=inarchive&action=setalbum&aid=$aid$param_suffix&page=$page$filterstr");
	}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'loadold'){
	//分析页面设置
	$niuid = empty($niuid) ? 0 : $niuid;
	if($niuid && $u_url = read_cache('inurl',$niuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$vars = array('sids','chids','filters','lists',);
		$u_url['setting']['sids'] = str_replace('m','0',$u_url['setting']['sids']);
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('catalog','channel','view',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		if(!$channel['isalbum']) amessage('choosealbum');
	if($aedit->archive['abover']) amessage('albumisover');
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
	if(!empty($no_list) || !($wheresql = $aedit->inalbumsqlstr('a.',@$u_chids))) amessage('noarcoralbumload');
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
	foreach(array('niuid','caid','chid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('nsid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;

	$wheresql = empty($no_list) ? $wheresql : 'WHERE 1=0';
	//echo $wheresql;
	if(!submitcheck('bloadold')){
		if(empty($u_tplname)){
			
			//需要归入的合辑管理区***********************************************************
			echo form_str($actionid.'albumadmin',"?entry=inarchive&action=loadold&aid=$aid&page=$page$param_suffix");
			//搜索区块
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
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
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";

			//某些固定页面参数
			trhidden('niuid',$niuid);
			tabfooter();
	
			//列表区	
			tabheader(lang('content_load_list').'&nbsp; -&nbsp; '.$aedit->archive['subject'],'','',9);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('view',$u_lists)) $cy_arr[] = lang('message');
			if(in_array('catalog',$u_lists)) $cy_arr[] = lang('catalog');
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('channel',$u_lists)) $cy_arr[] = lang('arctype');//模型与合辑类型综合在一起
			if(in_array('subsite',$u_lists)) $cy_arr[] = lang('subsite');
			trcategory($cy_arr);
			$pagetmp = $page;
			do{
				$query = $db->query("SELECT a.aid,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject,a.mname $fromsql $wheresql ORDER BY a.aid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
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
				$viewstr = "<a id=\"{$actionid}_info_$row[aid]\" href=\"?entry=archive&action=viewinfos&aid=$row[aid]$param_suffix\" onclick=\"return showInfo(this.id,this.href)\">".lang('look')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w50\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('view',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$viewstr</td>\n";
				if(in_array('catalog',$u_lists)) $itemstr .= "<td class=\"txtC\">$catalogstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('channel',$u_lists)) $itemstr .= "<td class=\"txtC\">$channelstr</td>\n";
				if(in_array('subsite',$u_lists)) $itemstr .= "<td class=\"txtC\">$subsitestr</td>\n";
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=inarchive&action=loadold&aid=$aid$param_suffix$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			echo '<br><br>'.strbutton('bloadold',lang('load')).'</form>';
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($selectid)) amessage('selectalbum');
		$aedit = new cls_arcedit;
		foreach($selectid as $k){
			$aedit->set_aid($k);
			$aedit->set_album($aid,1);
			$aedit->init();
		}
		adminlog(lang('setalbum_admin'),lang('archive_setalbum'));
		amessage('setalbumfinish',"?entry=inarchive&action=loadold&aid=$aid$param_suffix&page=$page$filterstr");
	}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'comments'){
	//分析页面设置
	$niuid = empty($niuid) ? 0 : $niuid;
	$u_checked = -1;
	if($niuid && $u_url = read_cache('inurl',$niuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$u_checked = $u_url['setting']['checked'];
		foreach(array('filters','lists','operates',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('mname','check','adddate','edit',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$checked = isset($checked) ? $checked : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "cu.aid='$aid'";
	$fromsql = "FROM {$tblprefix}comments cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";

	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= " AND cu.checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= " AND cu.checked='$u_checked'";

	//搜索关键词处理
	$keyword && $wheresql .= " AND (cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";

	$filterstr = '';
	foreach(array('niuid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;

	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'arcsedit',"?entry=inarchive&action=comments&aid=$aid&page=$page$param_suffix");
			//搜索区块
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//审核状态
			if(empty($u_filters) || in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";
			//某些固定页面参数
			trhidden('niuid',$niuid);
			tabfooter();
	
			//列表区	
			tabheader($aedit->archive['subject'].'&nbsp; '.lang('comment_list'),'','',9);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_date');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('update_time');
			if(in_array('edit',$u_lists)) $cy_arr[] = lang('edit');
			trcategory($cy_arr);

			$pagetmp = $page;
			do{
				$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

			$itemstr = '';
			while($row = $db->fetch_array($query)){
				cu_checkend($row,'comment');
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">$row[asubject]</a>";
				$mnamestr = $row['mname'];
				$checkstr = $row['checked'] ? 'Y' : '-';
				$adddatestr = date('Y-m-d',$row['ucreatedate']);
				$updatedatestr = date('Y-m-d',$row['updatedate']);
				$editstr = "<a href=\"?entry=comments&action=commentdetail&cid=$row[cid]$param_suffix\" onclick=\"return floatwin('open_commentsedit',this)\">".lang('detail')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$checkstr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$updatedatestr</td>\n";
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=inarchive&action=comments&aid=$aid$param_suffix$filterstr");
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
			a_guide('commentsedit');
		
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($arcdeal)) amessage('selectoperateitem',M_REFERER);
		if(empty($selectid)) amessage('pchoosecontent',M_REFERER);
		if(!empty($arcdeal['delete'])){
			$aedit = new cls_arcedit;
			$actuser = new cls_userinfo;
			$query = $db->query("SELECT aid,mid FROM {$tblprefix}comments WHERE cid ".multi_str($selectid));
			while($row = $db->fetch_array($query)){
				$aedit->set_aid($row['aid']);
				$aedit->arc_nums('comments',-1,1);
				$aedit->init();
				$actuser->activeuser($row['mid']);
				$actuser->basedeal('comment',0,1,1);
				$actuser->init();
			}
			$db->query("DELETE FROM {$tblprefix}comments WHERE cid ".multi_str($selectid),'UNBUFFERED');
		}else{
			if(!empty($arcdeal['check'])){
				$db->query("UPDATE {$tblprefix}comments SET checked='1' WHERE cid ".multi_str($selectid));
			}
			if(!empty($arcdeal['uncheck'])){
				$db->query("UPDATE {$tblprefix}comments SET checked='0' WHERE cid ".multi_str($selectid));
			}
		}
		adminlog(lang('commentsetsucceed'),lang('commentsetsucceed'));
		amessage('contentsetsucceed',"?entry=inarchive&action=comments&aid=$aid$param_suffix&page=$page$filterstr");
	}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'replys'){
	//分析页面设置
	$niuid = empty($niuid) ? 0 : $niuid;
	$u_checked = -1;
	if($niuid && $u_url = read_cache('inurl',$niuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$u_checked = $u_url['setting']['checked'];
		foreach(array('filters','lists','operates',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('mname','check','adddate','edit',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$checked = isset($checked) ? $checked : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "a.aid='$aid'";
	$fromsql = "FROM {$tblprefix}replys cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";

	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= " AND cu.checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= " AND cu.checked='$u_checked'";

	//搜索关键词处理
	$keyword && $wheresql .= " AND (cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";

	$filterstr = '';
	foreach(array('niuid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;

	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'arcsedit',"?entry=inarchive&action=replys&aid=$aid&page=$page$param_suffix");
			//搜索区块

			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//审核状态
			if(empty($u_filters) || in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";
			//某些固定页面参数
			trhidden('niuid',$niuid);
			tabfooter();
	
	
			//列表区	
			tabheader($aedit->archive['subject'].'&nbsp; '.lang('reply_list'),'','',9);

			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_date');
			if(in_array('updatedate',$u_lists)) $cy_arr[] = lang('update_time');
			if(in_array('edit',$u_lists)) $cy_arr[] = lang('edit');
			trcategory($cy_arr);

			$pagetmp = $page;
			do{
				$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

			$itemstr = '';
			while($row = $db->fetch_array($query)){
				cu_checkend($row,'reply');
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">$row[asubject]</a>";
				$mnamestr = $row['mname'];
				$checkstr = $row['checked'] ? 'Y' : '-';
				$adddatestr = date('Y-m-d',$row['ucreatedate']);
				$updatedatestr = date('Y-m-d',$row['updatedate']);
				$editstr = "<a href=\"?entry=replys&action=replydetail&cid=$row[cid]$param_suffix\" onclick=\"return floatwin('open_replysedit',this)\">".lang('detail')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$checkstr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$updatedatestr</td>\n";
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=inarchive&action=replys&aid=$aid$param_suffix$filterstr");
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
			a_guide('replysedit');
		
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($arcdeal)) amessage('selectoperateitem',M_REFERER);
		if(empty($selectid)) amessage('pchoosecontent',M_REFERER);
		if(!empty($arcdeal['delete'])){
			$query = $db->query("SELECT aid,mid FROM {$tblprefix}replys WHERE cid ".multi_str($selectid));
			while($row = $db->fetch_array($query)){
				$db->query("UPDATE {$tblprefix}archives SET replys=GREATEST(0,replys-1) WHERE aid='$row[aid]'");
				$db->query("UPDATE {$tblprefix}members_sub SET replys=GREATEST(0,replys-1) WHERE mid='$row[mid]'");
			}
			unset($row);
			$db->query("DELETE FROM {$tblprefix}replys WHERE cid ".multi_str($selectid));
		}else{
			if(!empty($arcdeal['check'])){
				$db->query("UPDATE {$tblprefix}replys SET checked='1' WHERE cid ".multi_str($selectid));
			}
			if(!empty($arcdeal['uncheck'])){
				$db->query("UPDATE {$tblprefix}replys SET checked='0' WHERE cid ".multi_str($selectid));
			}
		}
		adminlog(lang('replysetsucceed'),lang('replysetsucceed'));
		amessage('contentsetsucceed',"?entry=inarchive&action=replys&aid=$aid$param_suffix&page=$page$filterstr");
	}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'offers'){
	//分析页面设置
	$niuid = empty($niuid) ? 0 : $niuid;
	$u_checked = $u_valid = -1;
	if($niuid && $u_url = read_cache('inurl',$niuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$u_checked = $u_url['setting']['checked'];
		$u_valid = $u_url['setting']['valid'];
		foreach(array('filters','lists','operates',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('oprice','mname','check','adddate','edit',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$checked = isset($checked) ? $checked : '-1';
	$valid = isset($valid) ? $valid : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "a.aid='$aid'";
	$fromsql = "FROM {$tblprefix}offers cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";

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

	$filterstr = '';
	foreach(array('niuid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked','valid',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;

	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'arcsedit',"?entry=inarchive&action=offers&aid=$aid&page=$page$param_suffix");
			//搜索区块

			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//审核状态
			if(empty($u_filters) || in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('check'),'0' => lang('nocheck'),'1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			//有效状态
			if(empty($u_filters) || in_array('valid',$u_filters)){
				$validarr = array('-1' => lang('nolimit').lang('available'),'0' => lang('invalid'),'1' => lang('available'));
				//trbasic(lang('validperiod_state'),'',makeradio('valid',$validarr,$valid),'');
				echo "<select style=\"vertical-align: middle;\" name=\"valid\">".makeoption($validarr,$valid)."</select>&nbsp; ";
			}
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";
			//某些固定页面参数
			trhidden('niuid',$niuid);
			tabfooter();
	
	
			//列表区	
			tabheader($aedit->archive['subject'].'&nbsp; '.lang('offer_list'),'','',9);

			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('oprice',$u_lists)) $cy_arr[] = lang('price');
			if(in_array('check',$u_lists)) $cy_arr[] = lang('check');
			if(in_array('valid',$u_lists)) $cy_arr[] = lang('available');
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
				$checkstr = $row['checked'] ? 'Y' : '-';
				$validstr = (!$row['enddate'] || $row['enddate'] > $timestamp) ? 'Y' : '-';
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
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
				if(in_array('updatedate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$updatedatestr</td>\n";
				if(in_array('enddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$enddatestr</td>\n";
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=inarchive&action=offers&aid=$aid$param_suffix$filterstr");
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
		if(empty($arcdeal)) amessage('selectoperateitem',M_REFERER);
		if(empty($selectid)) amessage('pchoosecontent',M_REFERER);
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
		adminlog(lang('offersetsucceed'),lang('offersetsucceed'));
		amessage('contentsetsucceed',"?entry=inarchive&action=offers&aid=$aid$param_suffix&page=$page$filterstr");
	}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'answers'){
	//分析页面设置
	$niuid = empty($niuid) ? 0 : $niuid;
	$u_checked = -1;
	if($niuid && $u_url = read_cache('inurl',$niuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$u_checked = $u_url['setting']['checked'];
		foreach(array('filters','lists',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('mname','check','adddate','edit',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$checked = isset($checked) ? $checked : '-1';
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "a.aid='$aid'";
	$fromsql = "FROM {$tblprefix}answers cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";

	//审核状态范围
	if($checked != -1){
		if(($u_checked != -1) && $checked != $u_checked) $no_list = true;
		else $wheresql .= " AND cu.checked='$checked'";
	}elseif($u_checked != -1) $wheresql .= " AND cu.checked='$u_checked'";

	//搜索关键词处理
	$keyword && $wheresql .= " AND (cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";

	$filterstr = '';
	foreach(array('niuid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	foreach(array('checked',) as $k) $$k != -1 && $filterstr .= "&$k=".$$k;

	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'arcsedit',"?entry=inarchive&action=answers&aid=$aid&page=$page$param_suffix");
			//搜索区块
			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			//审核状态
			if(empty($u_filters) || in_array('check',$u_filters)){
				$checkedarr = array('-1' => lang('nolimit').lang('adopt'),'0' => lang('noadopt'),'1' => lang('adopted'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
			}
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";
			//某些固定页面参数
			trhidden('niuid',$niuid);
			tabfooter();
	
	
			//列表区	
			tabheader($aedit->archive['subject'].'&nbsp; '.lang('answer_list'),'','',9);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('check',$u_lists)) $cy_arr[] = lang('adopt');
			if(in_array('award',$u_lists)) $cy_arr[] = lang('award');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_date');
			if(in_array('edit',$u_lists)) $cy_arr[] = lang('edit');
			trcategory($cy_arr);

			$pagetmp = $page;
			do{
				$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">$row[asubject]</a>";
				$mnamestr = $row['mname'];
				$awardstr = $row['currency'].($row['currency'] ? $currencys[$row['crid']]['cname'] : '');
				$checkstr = $row['checked'] ? 'Y' : '-';
				$adddatestr = date('Y-m-d',$row['ucreatedate']);
				$editstr = "<a href=\"?entry=answers&action=answerdetail&cid=$row[cid]$param_suffix\" onclick=\"return floatwin('open_answersedit',this)\">".lang('detail')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('check',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$checkstr</td>\n";
				if(in_array('award',$u_lists)) $itemstr .= "<td class=\"txtC\">$awardstr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=inarchive&action=answers&aid=$aid$param_suffix$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			echo '<br><br>'.strbutton('barcsedit',lang('delete')).'</form>';
			a_guide('answersedit');
		
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($selectid)) amessage('pchoosecontent',M_REFERER);
		$aedit = new cls_arcedit;
		$actuser = new cls_userinfo;
		$query = $db->query("SELECT aid,mid,checked FROM {$tblprefix}answers WHERE cid ".multi_str($selectid));
		while($row = $db->fetch_array($query)){
			$aedit->set_aid($row['aid']);
			$row['checked'] && $aedit->arc_nums('adopts',-1,0);
			$aedit->arc_nums('answers',-1,1);
			$aedit->init();
			$actuser->activeuser($row['mid']);
			$actuser->basedeal('answer',0,1,1);
			$actuser->init();
		}
		$db->query("DELETE FROM {$tblprefix}answers WHERE cid ".multi_str($selectid),'UNBUFFERED');
		adminlog(lang('answersetsucceed'),lang('answersetsucceed'));
		amessage('contentsetsucceed',"?entry=inarchive&action=answers&aid=$aid$param_suffix&page=$page$filterstr");
	}
	}else include(M_ROOT.$u_tplname);
}elseif($action == 'purchases'){
	echo '1111111111111111111111111111111';
}elseif($action == 'reports'){
	//分析页面设置
	$niuid = empty($niuid) ? 0 : $niuid;
	if($niuid && $u_url = read_cache('inurl',$niuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		foreach(array('lists',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	empty($u_lists) && $u_lists = array('mname','adddate','edit',);
	if(empty($u_tplname) || !empty($u_onlyview)){
		$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$keyword = empty($keyword) ? '' : $keyword;
	$wheresql = "a.aid='$aid'";
	$fromsql = "FROM {$tblprefix}reports cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";

	//搜索关键词处理
	$keyword && $wheresql .= " AND (cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";

	$filterstr = '';
	foreach(array('niuid','keyword',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));

	$wheresql = "WHERE ".(empty($no_list) ? $wheresql : '1=0');
	if(!submitcheck('barcsedit')){
		if(empty($u_tplname)){
			echo form_str($actionid.'arcsedit',"?entry=inarchive&action=reports&aid=$aid&page=$page$param_suffix");
			//搜索区块

			tabheader_e();
			echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
			//关键词固定显示
			echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
			echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
			echo "</td></tr>";
			//某些固定页面参数
			trhidden('niuid',$niuid);
			tabfooter();

	
			//列表区	
			tabheader($aedit->archive['subject'].'&nbsp; '.lang('report_list'),'','',9);
			$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),);
			if(in_array('mname',$u_lists)) $cy_arr[] = lang('member');
			if(in_array('adddate',$u_lists)) $cy_arr[] = lang('add_date');
			if(in_array('edit',$u_lists)) $cy_arr[] = lang('edit');
			trcategory($cy_arr);

			$pagetmp = $page;
			do{
				$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			} while(!$db->num_rows($query) && $pagetmp);

			$itemstr = '';
			while($row = $db->fetch_array($query)){
				$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[cid]]\" value=\"$row[cid]\">";
				$subjectstr = "<a href=\"".view_arcurl($row)."\" target=\"_blank\">$row[asubject]</a>";
				$mnamestr = $row['mname'];
				$adddatestr = date('Y-m-d',$row['ucreatedate']);
				$editstr = "<a href=\"?entry=reports&action=reportdetail&cid=$row[cid]$param_suffix\" onclick=\"return floatwin('open_reportsedit',this)\">".lang('detail')."</a>";

				$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\" >$selectstr</td><td class=\"txtL\">$subjectstr</td>\n";
				if(in_array('mname',$u_lists)) $itemstr .= "<td class=\"txtC\">$mnamestr</td>\n";
				if(in_array('adddate',$u_lists)) $itemstr .= "<td class=\"txtC w100\">$adddatestr</td>\n";
				if(in_array('edit',$u_lists)) $itemstr .= "<td class=\"txtC w35\">$editstr</td>\n";;
				$itemstr .= "</tr>\n";
			}

			$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$multi = multi($counts, $atpp, $page, "?entry=inarchive&action=reports&aid=$aid$param_suffix$filterstr");
			echo $itemstr;
			tabfooter();
			echo $multi;
			echo '<br><br>'.strbutton('barcsedit',lang('delete')).'</form>';
			a_guide('reportsedit');
		
		}else include(M_ROOT.$u_tplname);
		
	}else{
		if(empty($selectid)) amessage('pchoosecontent',M_REFERER);
		$db->query("DELETE FROM {$tblprefix}reports WHERE cid ".multi_str($selectid),'UNBUFFERED');
		adminlog(lang('reportsetsucceed'),lang('reportsetsucceed'));
		amessage('contentsetsucceed',"?entry=inarchive&action=reports&aid=$aid$param_suffix&page=$page$filterstr");
	}
	}else include(M_ROOT.$u_tplname);
}

?>
