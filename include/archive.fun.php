<?php
!defined('M_COM') && exit('No Permission');
function arc_allow(&$item,$pname){//当前会员是否允许阅读或下载文档中的附件//出售或收税不在其中
	global $curuser,$catalogs,$cotypes;
	if($curuser->info['mid'] && $curuser->info['mid'] == $item['mid']) return true;
	$var = $pname == 'down' ? 'dpmid' : 'rpmid';
	if(empty($item[$var])) return true;
	$pmids = array();
	if($item[$var] == -1){
		$catalog = read_cache('catalog',$item['caid'],'',$item['sid']);
		if($catalog[$var]) $pmids[] = $catalog[$var];
		foreach($cotypes as $coid => $cotype){
			if($cotype['permission'] && !empty($item["ccid$coid"])){
				$ccids = array_filter(explode(',',$item["ccid$coid"]));
				foreach($ccids as $ccid){//多选中只要有一个分类不允许，则不允许
					$coclass = read_cache('coclass',$coid,$ccid);
					if($coclass[$var]) $pmids[] = $coclass[$var];
				}
			}
		}
		unset($catalog,$coclass);
	}else $pmids[] = $item[$var];
	return $curuser->pmbypmids($pname,$pmids);
}
function str_arcfee(&$item,$isatm=0){
	global $cotypes,$vcps,$currencys;
	$mode = $isatm ? 'f' : '';
	$feevar = $isatm ? 'atmfee' : 'arcfee';
	$item[$feevar] = '';
	if(empty($item['aid']) || empty($item['checked'])) return;
	
	$crids = array();
	$catalog = read_cache('catalog',$item['caid'],'',$item['sid']);
	if(!empty($catalog[$mode.'taxcp']) && !empty($vcps[$mode.'tax'][$catalog[$mode.'taxcp']])){
		$cparr = explode('_',$catalog[$mode.'taxcp']);
		$crids[$cparr[0]] = $cparr[1];
	}
	foreach($cotypes as $k => $cotype){
		if(!empty($item["ccid$k"])){
			$ccids = array_filter(explode(',',$item["ccid$k"]));
			foreach($ccids as $ccid){//多选中只要有一个分类不允许，则不允许
				$coclass = read_cache('coclass',$k,$ccid);
				if(!empty($coclass[$mode.'taxcp']) && !empty($vcps[$mode.'tax'][$coclass[$mode.'taxcp']])){
					$cparr = explode('_',$coclass[$mode.'taxcp']);
					$crids[$cparr[0]] = isset($crids[$cparr[0]]) ? $crids[$cparr[0]] + $cparr[1] : $cparr[1];
				}
			}
		}
	}
	if(!empty($item[$mode.'salecp'])){
		$cparr = explode('_',$item[$mode.'salecp']);
		$crids[$cparr[0]] = isset($crids[$cparr[0]]) ? $crids[$cparr[0]] + $cparr[1] : $cparr[1];
	}
	foreach($crids as $k =>$v) $item[$feevar] .= ($item[$feevar] ? ',' : '').$v.@$currencys[$k]['cname'];
	unset($catalog,$coclass,$cparr,$crids);
}
function calshipingfee($orderfee,$shid,$weight=0){
	global $shipings;
	$shipingfee = 0;
	if(empty($shipings[$shid])) return $shipingfee;
	extract($shipings[$shid]);
	if(!empty($freetop) && $orderfee < $freetop) return $shipingfee;
	$shipingfee = $basefee;
	$shipingfee += empty($plus1mode) ? $plus1 : $orderfee * $plus1 / 100;
	$shipingfee += empty($plus2mode) ? $plus2 : $orderfee * $plus2 / 100;
	if($base2 && $weight > $base2){
		$shipingfee += ceil(($weight - $base2) / $unit2) * $price2;
		$weight = $base2;
	}
	if($base1 && $weight > $base1){
		$shipingfee += ceil(($weight - $base1) / $unit1) * $price1;
	}
	return intval($shipingfee);
}
function arc_parse(&$item){//一个文档解析时需要分析的相关内容
	global $acatalogs,$cotypes,$channels,$subsites,$cms_abs;
	view_arcurl($item,-1);
	$item['sitename'] = empty($item['sid']) ? lang('msite') : $subsites[$item['sid']]['sitename'];
	$item['siteurl'] = view_siteurl($item['sid']);
	$item['catalog'] = $acatalogs[$item['caid']]['title'];
	$item['channel'] = @$channels[$item['chid']]['cname'];
	foreach($cotypes as $k => $cotype){
		$item['ccid'.$k.'title'] = '';
		if($item["ccid$k"]){
			$coclasses = read_cache('coclasses',$k);
			$item['ccid'.$k.'title'] = cnstitle($item["ccid$k"],$cotype['asmode'],$coclasses);
		}
	}
	$item['cms_counter'] = "<script type=\"text/javascript\" src=\"".$cms_abs."tools/counter.php?aid=".$item['aid']."&mid=".$item['mid']."\"></script>";
	fetch_txt($item);
	arr_tag2atm($item);
	foreach(array(0,1) as $k) str_arcfee($item,$k);//得到arcfee,atmfee
}
function arc_format(&$item){//保留page与addno的文档静态格式
	global $arccustomurl;
	$catalog = read_cache('catalog',$item['caid'],'',$item['sid']);
	return m_parseurl(!$item['customurl'] ? (!$catalog['customurl'] ? (!$arccustomurl ? '{$topdir}/{$y}{$m}/{$aid}/{$addno}_{$page}.html' : $arccustomurl) : $catalog['customurl']) : $item['customurl'],array('topdir' => arc_topdir($item),'cadir' => $catalog['dirname'],'chid' => $item['chid'],'aid' => $item['aid'],'y' => date('Y',$item['createdate']),'m' => date('m',$item['createdate']),'d' => date('d',$item['createdate']),'h' => date('H',$item['createdate']),'i' => date('i',$item['createdate']),'s' => date('s',$item['createdate']),));
}
function arc_tplname($addno = 0,$arctpls = '',$chtpls = ''){
	$arctpls = explode(',',$arctpls);
	$chtpls = explode(',',$chtpls);
	return empty($arctpls[$addno]) ? (empty($chtpls[$addno]) ? '' : $chtpls[$addno]) : $arctpls[$addno];
}
function arc_addno($addno = 0,$addnos = ''){
	$addnos = explode(',',$addnos);
	return empty($addnos[$addno]) ? ($addno ? $addno : '') : $addnos[$addno];
}
function arc_topdir(&$item){
	global $cnhtmldir,$subsites,$acatalogs;
	load_cache('acatalogs');
	$topid = cn_upid($item['caid'],$acatalogs);
	return ($item['sid'] ? $subsites[$item['sid']]['dirname'].'/' : ($cnhtmldir ? $cnhtmldir.'/' : '')).$acatalogs[$topid]['dirname'].'/';
}
function arc_blank($aid,$addno='',$arcfile,$force=0){//$arcfile完全服务器路径
	if($force || !is_file($arcfile)) str2file(direct_html("archive.php?aid=$aid".($addno ? "&addno=$addno" : '')),$arcfile);
}

function fetch_txt(&$item){
	$fields = read_cache('fields',$item['chid']);
	foreach($fields as $k => $v){
		if(!empty($v['istxt']) && isset($item[$k])) $item[$k] = readfromtxt($item[$k]);
	}
}
function cn_discount(&$item,$dcmode = 1){
	global $catalogs,$cotypes;
	if(empty($item['aid']) || !$dcmode) return 0;
	$dcarr = array();
	if(!empty($item['caid'])){
		$catalog = read_cache('catalog',$item['caid'],'',$item['sid']);
		!empty($catalog['discount']) && $dcarr[] = $catalog['discount'];
	}
	foreach($cotypes as $coid => $cotype){//折扣取多选第一个id
		if($ccid = cnoneid(@$item["ccid$coid"])){
			$coclass = read_cache('coclass',$coid,$ccid);
			!empty($coclass['discount']) && $dcarr[] = $coclass['discount'];
		}
	}
	$discount = caldiscount($dcarr,$dcmode);
	return $discount;
}
function caldiscount($dcarr=array(),$dcmode=1){
	$discount = 0;
	if(!$dcmode || empty($dcarr)) return $discount;
	foreach($dcarr as $v){
		if($dcmode == 1){
			$discount = max($discount,$v / 100);
		}else{
			$discount = 1 - (1 - $discount) * (1 - $v / 100);
		}
	}
	return round($discount * 100,2);
}
function notice_static($aid){
	global $db,$tblprefix,$max_addno,$timestamp;
	if($aid){
		$sqlstr = '';
		for($i = 0;$i <= @$max_addno;$i++) $sqlstr .= "$timestamp,";
		$db->query("UPDATE {$tblprefix}archives_sub SET needstatics='$sqlstr' WHERE aid = '$aid'");
	}
}
?>