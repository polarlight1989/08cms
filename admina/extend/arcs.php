<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('normal') || amessage('no_apermission');
load_cache('cotypes,channels,currencys,permissions,inurls');
load_cache('catalogs,mtpls,cnodes',$sid);
include_once M_ROOT."./include/parse.fun.php";
include_once M_ROOT."./include/arcedit.cls.php";
include_once M_ROOT."./include/commu.fun.php";
$urlpre = "?entry=extend&extend=arcs&";
$action = $action ? $action : 'archivesedit';
 
if($action == 'archivesedit'){//允许不区分栏目进行管理
	if($urlnavtitle){
		url_nav($urlnavtitle,$urlsarr,$urlskey);
	}
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
				echo form_str($actionid.'arcsedit',"{$urlpre}action=$action&page=$page$param_suffix");
				//搜索区块
	
				tabheader_e();
				echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
				//关键词固定显示
				echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
				if(empty($u_filters) || in_array('myid',$u_filters)){
					echo "<select style=\"vertical-align: middle;\" name=\"myid\">".makeoption(array('0' => lang('nomyid'),'1' => lang('myadd'),'2' => lang('mycheck'),'3' => lang('myneedchk')),$myid)."</select>&nbsp; ";
				}
				 
				echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">".viewcheck('viewdetail',$viewdetail,$actionid.'tbodyfilter');
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='{$urlpre}action=add&caid=$caid&chid=$chid'>添加 >></a>";
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
				$cy_arr[] = array('发布时间','w250');
				if(in_array('admin',$u_lists)) $cy_arr[] = array(lang('admin').'&nbsp; ','txtR');
				trcategory($cy_arr);
	
				$pagetmp = $page;
				do{
					$query = $db->query("SELECT * $fromsql $wheresql ORDER BY a.createdate DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
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
					$createdatestr = "<input type='text' name='createdate[$row[aid]]' style='width:150px;' value='".(date("Y-m-d H:i:s",$row['createdate']))."'/> ";
					$adminstr = '';
					if(empty($u_iuids) || empty($channel['iuids'])){
						#$adminstr .= "<a href=\"?entry=inarchive&aid=$row[aid]$param_suffix\" onclick=\"return floatwin('open_inarchive',this)\">".lang('admin')."</a>&nbsp; ";
						$adminstr .= "<a href=\"{$urlpre}action=archivedetail&aid=$row[aid]$param_suffix\" >".lang('edit')."</a>";
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
					$itemstr .= "<td class=\"txtC  \">$createdatestr</td>\n";
					if(in_array('admin',$u_lists)) $itemstr .= "<td class=\"txtR\">$adminstr</td>\n";
					$itemstr .= "</tr>\n";
				}
	
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$multi = multi($counts, $atpp, $page, "{$urlpre}action=$action$param_suffix$filterstr");
				echo $itemstr;
				tabfooter();
				echo $multi;
	
				//操作区
				tabheader(lang('operate_item'));
				$s_arr = array();
				if(empty($u_operates) || in_array('delete',$u_operates)) $s_arr['delete'] = lang('delete');
				if(empty($u_operates) || in_array('check',$u_operates)) $s_arr += chksarr($a_checks,1);
				if(empty($u_operates) || in_array('uncheck',$u_operates)) $s_arr += chksarr($a_checks,0);
				if(empty($u_operates) || in_array('readd',$u_operates)) $s_arr['readd'] = lang('readd');
				if(empty($u_operates) || in_array('abstract',$u_operates)) $s_arr['autoabstract'] = lang('auto_abstract');
				if(empty($u_operates) || in_array('thumb',$u_operates)) $s_arr['autothumb'] = lang('auto_thumb');
				 
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
 
				 
				tabfooter('barcsedit');
				a_guide('archivesedit');
			
			}else include(M_ROOT.$u_tplname);
			
		}else{
 
			foreach($createdate as $k=>$v){
				
				$v = strtotime($v);
				if(!$v){
					$v = time();
				}
				$db->query("UPDATE {$tblprefix}archives SET createdate = '".$v."' WHERE aid = '$k'");
 
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
			if($selectid ) foreach($selectid as $aid){
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
					amessage('operating',"{$urlpre}action=$action&page=$page$param_suffix$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"{$urlpre}action=$action&page=$page$param_suffix$filterstr\">",'</a>');
				}
			}
			adminlog(lang('arc_update_admin'),lang('arc_list_aoperate'));
			$forward = empty($forward) ? M_REFERER : $forward;
			 
			amessage('arcfinish',$forward);
		}
	}else include(M_ROOT.$u_tplname);
}
elseif($action == 'archivesupdate'){
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
			echo form_str($actionid.'arcsedit',"{$urlpre}action=$action&page=$page$param_suffix");
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
			$multi = multi($counts, $atpp, $page, "{$urlpre}action=$action$param_suffix$filterstr");
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
				$query = $db->query("SELECT a.aid $fromsql $nwheresql ORDER BY a.createdatedesc DESC LIMIT 0,$atpp");
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
				amessage('operating',"{$urlpre}action=$action&page=$page$param_suffix$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"{$urlpre}action=$action&page=$page$param_suffix$filterstr\">",'</a>');
			}
		}
		adminlog(lang('arc_update_admin'),lang('arc_list_aoperate'));
		amessage('arcfinish',"{$urlpre}action=$action$param_suffix&page=$page$filterstr");
	}
}
elseif($action == 'add'){
	include_once M_ROOT."./include/upload.cls.php";
	include_once M_ROOT."./include/arcedit.cls.php";
	include_once M_ROOT."./include/commu.fun.php";
	include_once M_ROOT."./include/fields.cls.php";
	
	$chid = empty($chid) ? $catalogs[$caid]['chids'] : max(0,intval($chid));
	if(!($channel = read_cache('channel',$chid))) amessage('choosearctype');
	foreach(array('acoids','aitems','additems','coidscp','cpkeeps') as $var) $$var = $channel[$var] ? explode(',',$channel[$var]) : array();
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	$fields = read_cache('fields',$chid);
	if(!submitcheck('barchiveadd')){
		$pre_cns = array();
		$pre_cns['caid'] = empty($caid) ? 0 : max(0,intval($caid));
		foreach($cotypes as $k => $v) if(!$v['self_reg'] && !in_array($k,$acoids) && !in_array($k,$additems)) $pre_cns['ccid'.$k] = empty(${'ccid'.$k}) ? 0 : trim(${'ccid'.$k});
		//如果指定在某个合辑内添加，需要分析继承类目
			$pid = empty($pid) ? 0 : max(0,intval($pid));
			if($pid && $p_album = $db->fetch_one("SELECT * FROM {$tblprefix}archives WHERE aid=$pid")){//指定合辑内添加文档的信息提示
				if(($p_channel = read_cache('channel',$p_album['chid'])) && $p_channel['isalbum']){//合辑功能是否取消
					$incoids = explode(',',$p_channel['incoids']);
					if(in_array('caid',$incoids))  $pre_cns['caid'] = $p_album['caid'];
					foreach($cotypes as $k => $v) if(!$v['self_reg'] && !in_array($k,$acoids) && !in_array($k,$additems) && !in_array($k,$incoids) && $p_album['ccid'.$k]) $pre_cns['ccid'.$k] = $p_album['ccid'.$k];
				}else $pid = 0;
			}else $pid = 0;
			foreach($pre_cns as $k => $v) if(!$v) unset($pre_cns[$k]);
			if(!$curuser->allow_arcadd($chid,$pre_cns)) amessage('noissuepermission','',lang('cn_pointed'));
		$submitstr = '';
		$a_field = new cls_field;
		tabheader($channel['cname'].'&nbsp; -&nbsp; '.lang('add_archive'),'archiveadd',"{$urlpre}action=add&chid=$chid$param_suffix$forwardstr",2,1,1,1);
		$submitstr .= makesubmitstr('archiveadd[caid]',1,0,0,0,'common');
		//栏目定义
			if(empty($pre_cns['caid'])){
				tr_cns('*'.lang('be_catalog'),'archiveadd[caid]',0,$sid,0,$chid,lang('p_choose'));
			}else{
				trbasic('*'.lang('be_catalog'),'',@$acatalogs[$pre_cns['caid']]['title'],'');
				trhidden('archiveadd[caid]',$pre_cns['caid']);
			}
			$submitstr .= makesubmitstr('archiveadd[caid]',1,0,0,0,'common');
		//类别定义
		foreach($cotypes as $k => $v){
			if(!$v['self_reg'] && !in_array($k,$acoids) && !in_array("ccid$k",$additems)){
				if(empty($pre_cns['ccid'.$k])){
					tr_cns(($v['notblank'] ? '*' : '').$v['cname'],"archiveadd[ccid$k]",0,$sid,$k,$chid,lang('p_choose'),0,$v['asmode'],0,$v['emode'],"archiveadd[ccid{$k}date]",0);
				}else{
					$endstr = $v['emode'] ? '&nbsp; &nbsp; '.lang('enddate1').($v['emode'] > 1 ? '*' : '')."<input type=\"text\" size=\"10\" id=\"archiveadd[ccid{$k}date]\" name=\"archiveadd[ccid{$k}date]\" value=\"\" onclick=\"ShowCalendar(this.id);\"><span id=\"alert_archiveadd[ccid{$k}date]\" name=\"alert_archiveadd[ccid{$k}date]\" class=\"mistake0\"></span>\n" : '';
					$coclasses = read_cache('coclasses',$k);
					trbasic(($v['notblank'] ? '*' : '').$v['cname'],'',cnstitle($pre_cns['ccid'.$k],$v['asmode'],$coclasses).$endstr,'');
					trhidden("archiveadd[ccid$k]",$pre_cns['ccid'.$k]);
				}
				$submitstr .= makesubmitstr("archiveadd[ccid$k]", $v['notblank'],0,0,0,'common');
				$v['emode'] == 2 && $submitstr .= makesubmitstr("archiveadd[ccid{$k}date]",1,0,0,0,'date');
			}
		}
		if(!in_array('copy',$aitems) && !in_array('copy',$additems)){
			in_array('caid',$coidscp) && tr_cns(lang('addcpinca'),'archiveadd[cpcaids]','',$sid,0,$chid,lang('p_choose'),0,5);
			foreach($cotypes as $k => $v){
				if(!$v['self_reg'] && in_array($k,$coidscp) && empty($v['asmode'])) tr_cns(lang('addcpincc',$v['cname']),"archiveadd[cpccids$k]",'',$sid,$k,$chid,lang('p_choose'),0,5);
			}			
		}
		tabfooter();
		tabheader($channel['cname'].'&nbsp; -&nbsp; '.lang('content_set'));
		$subject_table = 'archives';
		foreach($fields as $k => $field){
			if($field['available'] && !$field['isfunc'] && !in_array($k,$additems)){
				$a_field->init();
				$a_field->field = $field;
				if($curuser->pmbypmids('field',$a_field->field['pmid'])){//字段附加权限设置
					$a_field->isadd = 1;
					$a_field->trfield('archiveadd','','',$chid);
					$submitstr .= $a_field->submitstr;
				}
			}
		}
		unset($a_field);
		tabfooter();
		tabheader($channel['cname'].'&nbsp; -&nbsp; '.lang('more_set'));
		if(!in_array('jumpurl',$aitems) && !in_array('jumpurl',$additems)){
			trbasic(lang('jumpurl'),'archiveadd[jumpurl]','','btext',lang('agjumpurl'));
		}
		tabfooter('barchiveadd',lang('add'));
		check_submit_func($submitstr);
		a_guide('archiveadd');
	}else{
		$forward = $forward ? $forward : "?entry=extend&extend=arcs&caid=$caid";
		 
			if(empty($archiveadd['caid']) || empty($catalogs[$archiveadd['caid']])) amessage('choosecatalog',axaction(2,M_REFERER));
			if(!array_intersect(array(-1,$archiveadd['caid']),$a_caids)) amessage('fbd_caids',axaction(2,M_REFERER));//没有管理权限，则不能在管理后台的该栏目中添加内容
			$sqlmain = "sid='$sid',
			chid='$chid',
			caid='$archiveadd[caid]',
			mid='$memberid',
			mname='".$curuser->info['mname']."',
			refreshdate='$timestamp',
			createdate='$timestamp'";
	
			$pre_cns = array();
			$pre_cns['caid'] = $archiveadd['caid'];
			//分析分类的定义及权限
			foreach($cotypes as $k => $v){
				if(!$v['self_reg'] && !in_array($k,$acoids) && !in_array("ccid$k",$additems)){
					$archiveadd["ccid$k"] = empty($archiveadd["ccid$k"]) ? '' : $archiveadd["ccid$k"];
					if($v['notblank'] && !$archiveadd["ccid$k"]) amessage('setcoclass',axaction(2,M_REFERER),$v['cname']);//必选类系
					$sqlmain .= ",ccid$k = '".$archiveadd["ccid$k"]."'";
					if($arr = multi_val_arr($archiveadd["ccid$k"],$v,1)) foreach($arr as $x => $y) $sqlmain .= ',ccid'.$k.'_'.$x."='$y'";
					if($archiveadd["ccid$k"]) $pre_cns['ccid'.$k] = $archiveadd["ccid$k"];
					if($v['emode']){
						$archiveadd["ccid{$k}date"] = !isdate($archiveadd["ccid{$k}date"]) ? 0 : strtotime($archiveadd["ccid{$k}date"]);
						!$archiveadd["ccid$k"] && $archiveadd["ccid{$k}date"] = 0;
						if($archiveadd["ccid$k"] && !$archiveadd["ccid{$k}date"] && $v['emode'] == 2) amessage('setcoclass',axaction(2,M_REFERER),$v['cname']);
						$sqlmain .= ",ccid{$k}date = '".$archiveadd["ccid{$k}date"]."'";
					}
				}
			}
			if(!$curuser->allow_arcadd($chid,$pre_cns)) amessage('noissuepermission',axaction(2,M_REFERER),lang('cn_pointed'));//分析类目组合的发表权限
	
			if(!in_array('jumpurl',$aitems)  && !in_array('jumpurl',$additems)) $sqlmain .= ",jumpurl='".trim($archiveadd['jumpurl'])."'";
			//有效值设置
			$archiveadd['validperiod'] = empty($archiveadd['validperiod']) ? 0 : max(0,intval($archiveadd['validperiod']));
			$channel['mindays'] && $archiveadd['validperiod'] = max($archiveadd['validperiod'],$channel['mindays']);
			$channel['maxdays'] && $archiveadd['validperiod'] = min($archiveadd['validperiod'],$channel['maxdays']);
			if($archiveadd['validperiod']) $sqlmain .= ",enddate='".($timestamp + $archiveadd['validperiod'] * 24 * 3600)."'";
	
			//权限方案与出售
			if(!in_array('rpmid',$aitems) && !in_array('rpmid',$additems)) $sqlmain .= ",rpmid='".$archiveadd['rpmid']."'";
			if(!in_array('dpmid',$aitems) && !in_array('dpmid',$additems)) $sqlmain .= ",dpmid='".$archiveadd['dpmid']."'";
			if(!in_array('salecp',$aitems) && !in_array('salecp',$additems)) $sqlmain .= ",salecp='".$archiveadd['salecp']."'";
			if(!in_array('fsalecp',$aitems) && !in_array('fsalecp',$additems)) $sqlmain .= ",fsalecp='".$archiveadd['fsalecp']."'";
			if(!in_array('arcurl',$aitems)  && !in_array('arcurl',$additems)) $sqlmain .= ",customurl='".trim($archiveadd['customurl'])."'";
	
			$c_upload = new cls_upload;	
			$fields = fields_order($fields);
			$a_field = new cls_field;
			foreach($fields as $k => $field){
				if($field['available'] && !$field['isfunc'] && !in_array($k,$additems)){
					$a_field->init();
					$a_field->field = $field;
					if($curuser->pmbypmids('field',$a_field->field['pmid'])){//字段附加权限设置
						$a_field->deal('archiveadd');
						if(!empty($a_field->error)){
							$c_upload->rollback();
							amessage($a_field->error,axaction(2,M_REFERER));
						}
						$archiveadd[$k] = $a_field->newvalue;
					}
				}
			}
			unset($a_field);
			$oldarr = array();
			$cu_ret = cu_fields_deal($channel['cuid'],'archiveadd',$oldarr);
			$cu_ret && amessage($cu_ret,axaction(2,M_REFERER));
	
			if(isset($archiveadd['keywords'])) $archiveadd['keywords'] = keywords($archiveadd['keywords']);
			$fields['author']['available'] && $archiveadd['author'] = empty($archiveadd['author']) ? $curuser->info['mname'] : $archiveadd['author'];
			if($fields['abstract']['available'] && $channel['autoabstract'] && empty($archiveadd['abstract']) && !empty($archiveadd[$channel['autoabstract']])){
				$archiveadd['abstract'] = autoabstract($archiveadd[$channel['autoabstract']]);
			}
			if($fields['thumb']['available'] && $channel['autothumb'] && empty($archiveadd['thumb']) && !empty($archiveadd[$channel['autothumb']])){
				$archiveadd['thumb'] = $c_upload->thumb_pick(stripslashes($archiveadd[$channel['autothumb']]),$fields[$channel['autothumb']]['datatype'],$fields['thumb']['rpid']);
			}
			if($channel['autosize'] && !empty($archiveadd[$channel['autosize']])){
				$archiveadd['atmsize'] = atm_size(stripslashes($archiveadd[$channel['autosize']]),$fields[$channel['autosize']]['datatype'],$channel['autosizemode']);
				$sqlmain .= ",atmsize='".$archiveadd['atmsize']."'";
			}
			if($channel['autobyte'] && isset($archiveadd[$channel['autobyte']])){
				$archiveadd['bytenum'] = atm_byte(stripslashes($archiveadd[$channel['autobyte']]),$fields[$channel['autobyte']]['datatype']);
				$sqlmain .= ",bytenum='".$archiveadd['bytenum']."'";
			}
			$sqlsub = $sqlcustom = '';
			foreach($fields as $k => $v){
				if($v['available'] && !$v['isfunc'] && !in_array($k,$additems)){
					if($curuser->pmbypmids('field',$v['pmid'])){
						if(!empty($v['istxt'])) $archiveadd[$k] = saveastxt(stripslashes($archiveadd[$k]));
						${'sql'.$v['tbl']} .= (${'sql'.$v['tbl']} ? ',' : '').$k."='".$archiveadd[$k]."'";
						if($arr = multi_val_arr($archiveadd[$k],$v)) foreach($arr as $x => $y) ${'sql'.$v['tbl']} .= (${'sql'.$v['tbl']} ? ',' : '').$k.'_'.$x."='$y'";
					}
				}
			}
			cu_sqls_deal($channel['cuid'],$archiveadd,$sqlmain,$sqlsub,$sqlcustom);//将字段之外的交互资料写入
			
			$db->query("INSERT INTO {$tblprefix}archives SET ".$sqlmain);
			if(!$aid = $db->insert_id()){
				$c_upload->closure(1);
				amessage('addarcfailed',axaction(2,M_REFERER));
			}else{
				$c_upload->closure(1, $aid);
				$db->query("INSERT INTO {$tblprefix}archives_rec SET aid='$aid'");
				
				$sqlsub = "aid='$aid'".($sqlsub ? ',' : '').$sqlsub;
				$needstatics = '';
				for($i = 0;$i <= $channel['addnum'];$i ++) $needstatics .= $timestamp.',';
				$sqlsub .= ",needstatics='$needstatics'";
	
			 
				$db->query("INSERT INTO {$tblprefix}archives_sub SET ".$sqlsub);
				$sqlcustom = "aid='$aid'".($sqlcustom ? ',' : '').$sqlcustom;
				$db->query("INSERT INTO {$tblprefix}archives_$chid SET ".$sqlcustom);
				$curuser->basedeal('archive',1);
	
				$aedit = new cls_arcedit;
				$aedit->set_aid($aid);
				$aedit->set_arcurl();
				$aedit->set_cpid($aid);
	
				if($fields['keywords']['available'] && $channel['autokeyword'] && empty($aedit->archive['keywords'])){
					include_once M_ROOT."./include/splitword.cls.php";
					$a_split = new SplitWord();
					$aedit->autokeyword();
					unset($a_split);
				}
				$curuser->pmautocheck($channel['autocheck']) && $aedit->arc_check(1,0);
				$aedit->updatedb();
				
				$pids = array();
				if(!empty($archiveadd['pid'])) $pids[] = max(0,intval($archiveadd['pid']));
				foreach(array('ppids','opids') as $var) if(!empty($archiveadd[$var])) $pids = array_merge($pids,explode(',',$archiveadd[$var]));
				$pids = array_filter(array_unique($pids));
				foreach($pids as $k) $aedit->set_album($k);//归辑设置,与文档数据库无关
				if(!empty($archiveadd['volid']) && !empty($archiveadd['pid'])) $db->query("UPDATE {$tblprefix}albums SET volid='$archiveadd[volid]' WHERE aid=$aid AND pid='$archiveadd[pid]'",'SILENT');
	
				//处理在类目中的复制及更新
				if(!in_array('copy',$aitems) && !in_array('copy',$additems) && $coidscp){
					$aedit->init();
					$aedit->set_aid($aid);
					if(in_array('caid',$coidscp) && $cpcaids = array_filter(explode(',',$archiveadd['cpcaids']))){
						foreach($cpcaids as $k1) $aedit->addcopy(0,$k1);
					}
					foreach($cotypes as $k => $v){
						if(!$v['self_reg'] && empty($v['asmode']) && in_array($k,$coidscp) && ${"cpccids$k"} = array_filter(explode(',',$archiveadd["cpccids$k"]))){
							foreach(${"cpccids$k"} as $k1) $aedit->addcopy($k,$k1);
						}
					}
				}
				unset($aedit);
	
				if($channel['autostatic']){
					include_once M_ROOT."./include/arc_static.fun.php";
					arc_static($aid);
					unset($arc);
				}
			}
			$c_upload->saveuptotal(1);
			adminlog(lang('add_archive'));
			amessage('arcaddfinish',axaction(26,$forward));
	}
}elseif($action == 'archivedetail' && $aid){
	include_once M_ROOT."./include/upload.cls.php";
	include_once M_ROOT."./include/arcedit.cls.php";
	include_once M_ROOT."./include/commu.fun.php";
	include_once M_ROOT."./include/fields.cls.php";
	$chid = $db->result_one("SELECT chid FROM {$tblprefix}archives WHERE aid='$aid'");
	if(!($channel = read_cache('channel',$chid))) amessage('choosearctype');
	if(empty($channel['uadetail'])){
		//分析页面设置
		$niuid = empty($niuid) ? 0 : max(0,intval($niuid));
		if($niuid && $u_url = read_cache('inurl',$niuid)){
			$u_tplname = $u_url['tplname'];
			$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
			$u_mtitle = $u_url['mtitle'];
			$u_guide = $u_url['guide'];
			$vars = array('lists',);
			foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
		}
		if(empty($u_tplname) || !empty($u_onlyview)){
			$forward = empty($forward) ? M_REFERER : $forward;
			$forwardstr = '&forward='.rawurlencode($forward);
			 
			$aedit = new cls_arcedit;
			$aedit->set_aid($aid);
			$aedit->detail_data();
		
			$chid = $aedit->archive['chid'];
			$channel = &$aedit->channel;
			if(!array_intersect(array(-1,$aedit->archive['caid']),$a_caids)) amessage('fbd_caids');//管理后台对该栏目的限制
		
			$fields = read_cache('fields',$chid);
			foreach(array('acoids','aitems','coidscp','cpkeeps') as $var) $$var = $channel[$var] ? explode(',',$channel[$var]) : array();
			if(!submitcheck('barchivedetail')) {
				if(empty($u_tplname)){
					$submitstr = '';
					$a_field = new cls_field;
		
					tabheader($channel['cname'].'&nbsp; -&nbsp; '.lang('arcedit'),'archivedetail',"{$urlpre}action=archivedetail&aid=$aid$param_suffix$forwardstr",2,1,1,1);
					//tabheader($channel['cname'].'&nbsp; -&nbsp; '.lang('arcedit'),'archivedetail',"?entry=archive&action=archivedetail&aid=$aid$param_suffix$forwardstr",2,1,1);
					if(empty($u_lists) || in_array('caid',$u_lists)){
						tr_cns(lang('be_catalog'),'archivenew[caid]',$aedit->archive['caid'],$aedit->archive['sid'],0,$chid,lang('p_choose'));
						$submitstr .= makesubmitstr('archivenew[caid]',1,0,0,0,'common');
					}
			
					foreach($cotypes as $k => $v) {
						if(empty($u_lists) || in_array("ccid$k",$u_lists)){
							if(!$v['self_reg'] && !in_array($k,$acoids)){
								tr_cns(($v['notblank'] ? '*' : '').$v['cname'],"archivenew[ccid$k]",$aedit->archive["ccid$k"],$aedit->archive['sid'],$k,$chid,lang('p_choose'),0,$v['asmode'],0,$v['emode'],"archivenew[ccid{$k}date]",@$aedit->archive["ccid{$k}date"] ? date('Y-m-d',$aedit->archive["ccid{$k}date"]) : '');
								#$submitstr .= makesubmitstr("archivenew[ccid$k]", $v['notblank'],0,0,0,'common');
								$v['emode'] == 2 && $submitstr .= makesubmitstr("archivenew[ccid{$k}date]",1,0,0,0,'date');
							}
						}
					}
			
					$subject_table = 'archives';
					foreach($fields as $k => $field){
						if(empty($u_lists) || in_array($k,$u_lists)){
							if($field['available'] && !$field['isfunc']){
								$a_field->init();
								$a_field->field = $field;
								if($curuser->pmbypmids('field',$a_field->field['pmid'])){//字段附加权限设置
									$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
									$a_field->trfield('archivenew','','',$chid);
									#$submitstr .= $a_field->submitstr;
								}
							}
						}
					}
			
					if(empty($u_lists) || in_array('jumpurl',$u_lists)){
						trbasic(lang('jumpurl'),'archivenew[jumpurl]',$aedit->archive['jumpurl'],'btext',lang('agjumpurl'));
					}
					 
					tabfooter('barchivedetail');
					#@check_submit_func($submitstr);
					a_guide('archivedetail');
				}else include(M_ROOT.$u_tplname);
			}else{
				if(isset($archivenew['caid'])) $aedit->arc_caid($archivenew['caid']);
				foreach($cotypes as $k => $v){
					if(isset($archivenew["ccid$k"])){
						if(!$v['self_reg'] && !in_array($k,$acoids)){
							$archivenew["ccid$k"] = empty($archivenew["ccid$k"]) ? '' : $archivenew["ccid$k"];
							$aedit->arc_ccid($archivenew["ccid$k"],$k);
							if($v['emode']){
								$archivenew["ccid{$k}date"] = !isdate($archivenew["ccid{$k}date"]) ? 0 : strtotime($archivenew["ccid{$k}date"]);
								if($aedit->archive["ccid$k"] && !$archivenew["ccid{$k}date"] && $v['emode'] == 2) amessage('setcoclass',axaction(2,M_REFERER),$v['cname']);
								!$aedit->archive["ccid$k"] && $archivenew["ccid{$k}date"] = 0;
								$aedit->updatefield("ccid{$k}date",$archivenew["ccid{$k}date"],'main');
							}
						}
					}
				}
				if(isset($archivenew['jumpurl'])){
					if(!in_array('jumpurl',$aitems)) $aedit->updatefield('jumpurl',trim($archivenew['jumpurl']),'main');
				}
				if(isset($archivenew['rpmid'])){
					if(!in_array('rpmid',$aitems)) $aedit->updatefield('rpmid',$archivenew['rpmid'],'main');
				}
				if(isset($archivenew['dpmid'])){
					if(!in_array('dpmid',$aitems)) $aedit->updatefield('dpmid',$archivenew['dpmid'],'main');
				}
				if(isset($archivenew['salecp'])){
					if(!in_array('salecp',$aitems)) $aedit->updatefield('salecp',$archivenew['salecp'],'main');
				}
				if(isset($archivenew['fsalecp'])){
					if(!in_array('fsalecp',$aitems)) $aedit->updatefield('fsalecp',$archivenew['fsalecp'],'main');
				}
				$aedit->sale_define();
				if(isset($archivenew['customurl'])){
					if(!in_array('arcurl',$aitems)) $aedit->updatefield('customurl',trim($archivenew['customurl']),'main');
				}
		
				if(isset($archivenew['arctpls'])){
					if(!in_array('arctpl',$aitems)) $aedit->updatefield('arctpls',implode(',',$archivenew['arctpls']),'sub');
				}
				$c_upload = new cls_upload;	
				$fields = fields_order($fields);
				$a_field = new cls_field;
				foreach($fields as $k => $field){
					if(isset($archivenew[$k])){
						if($field['available'] && !$field['isfunc']){
							$a_field->init();
							$a_field->field = $field;
							if($curuser->pmbypmids('field',$a_field->field['pmid'])){//字段附加权限设置
								$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
								$a_field->deal('archivenew');
								if(!empty($a_field->error)){
									$c_upload->rollback();
									amessage($a_field->error,axaction(2,M_REFERER));
								}
								$archivenew[$k] = $a_field->newvalue;
							}
						}
					}
				}
				unset($a_field);
				$cu_ret = cu_fields_deal($channel['cuid'],'archivenew',$aedit->archive);
				!empty($cu_ret) && amessage($cu_ret,axaction(2,M_REFERER));
				$aedit->edit_cudata($archivenew,1);
		
				if(isset($archivenew['keywords'])) $archivenew['keywords'] = keywords($archivenew['keywords'],$aedit->archive['keywords']);
				if($fields['abstract']['available'] && $channel['autoabstract'] && empty($archivenew['abstract']) && isset($archivenew[$channel['autoabstract']])){
					$archivenew['abstract'] = autoabstract($archivenew[$channel['autoabstract']]);
				}
				if($fields['thumb']['available'] && $channel['autothumb'] && empty($archivenew['thumb']) && isset($archivenew[$channel['autothumb']])){
					$archivenew['thumb'] = $c_upload->thumb_pick(stripslashes($archivenew[$channel['autothumb']]),$fields[$channel['autothumb']]['datatype'],$fields['thumb']['rpid']);
				}
				if($channel['autosize'] && isset($archivenew[$channel['autosize']]) && $archivenew[$channel['autosize']] != addslashes($aedit->archive[$channel['autosize']])){
					$archivenew['atmsize'] = atm_size(stripslashes($archivenew[$channel['autosize']]),$fields[$channel['autosize']]['datatype'],$channel['autosizemode']);
					$aedit->updatefield('atmsize',$archivenew['atmsize'],'main');
				}
				if($channel['autobyte'] && isset($archivenew[$channel['autobyte']])){
					$archivenew['bytenum'] = atm_byte(stripslashes($archivenew[$channel['autobyte']]),$fields[$channel['autobyte']]['datatype']);
					$aedit->updatefield('bytenum',$archivenew['bytenum'],'main');
				}
				foreach($fields as $k => $v){
					if(isset($archivenew[$k])){
						if($v['available'] && !$v['isfunc']){
							if($curuser->pmbypmids('field',$v['pmid'])){
								if(!empty($v['istxt'])) $archivenew[$k] = saveastxt(stripslashes($archivenew[$k]),$aedit->namepres[$k]);
								$aedit->updatefield($k,$archivenew[$k],$v['tbl']);
								if($arr = multi_val_arr($archivenew[$k],$v)) foreach($arr as $x => $y) $aedit->updatefield($k.'_'.$x,$y,$v['tbl']);
							}
						}
					}
				}
				$c_upload->closure(1, $aid);
		
				$aedit->updatedb();
		
				if(!empty($archivenew['cpupdate'])) $aedit->updatecopy($archivenew['cpupdate']);
				
				if($channel['autostatic']){
					include_once M_ROOT."./include/arc_static.fun.php";
					arc_static($aid);
					unset($arc);
				}
				$c_upload->saveuptotal();
				adminlog(lang('modify_archive'));
				 
				amessage('arceditfinish',$forward);
			}
		}else include(M_ROOT.$u_tplname);
	}else include(M_ROOT.$channel['uadetail']);
}
?>
