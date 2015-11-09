<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
load_cache('channels,catalogs,cotypes');
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/fields.fun.php";
if(empty($action)){
	tabheader(lang('contseaurl'),'','','8');
	trcategory(array(lang('sn'),lang('channel_name'),lang('searformurl'),lang('searesurl'),lang('more')));
	$i = 0;
	$channels = array(0 => array('cname' => lang('all_channel'))) + $channels;
	foreach($channels as $k => $channel) {
		$i ++;
		$surlstr = "search.php".($k ? "?chid=$k" : '');
		$rurlstr = "search.php?searchsubmit=1".($k ? "&chid=$k" : '');
		echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\">$i</td>\n".
			"<td class=\"txtL w120\">$channel[cname]</td>\n".
			"<td class=\"txtL\">{\$cms_abs}$surlstr</td>\n".
			"<td class=\"txtL\"><a href=\"$rurlstr\" target=\"_blank\">{\$cms_abs}$rurlstr</a></td>\n".
			"<td class=\"txtC w40\"><a href=\"?entry=searchs&action=searchdetail&chid=$k\">".lang('setting')."</a></td>\n".
			"</tr>\n";
	}
	tabfooter();
	a_guide('searchs');

}elseif($action == 'searchdetail'){
	$chid = empty($chid) ? 0 : $chid;
	$searchword = empty($searchword) ? '' : cutstr(trim($searchword),50,'');
	$searchmode = empty($searchmode) ? 'subject' : trim($searchmode);
	$caid = empty($caid) ? 0 : $caid;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
	$orderby = empty($orderby) ? 'createdate' : $orderby;
	$ordermode = empty($ordermode) ? 0 : $ordermode;
	foreach($cotypes as $coid => $cotype){
		if(!$chid || !$cotype['chids'] || !in_array($chid,explode(',',$cotype['chids']))){
			${"ccid$coid"} = !empty(${"ccid$coid"}) ? ${"ccid$coid"} : 0;
		}
	}

	$searchmodearr = array('subject' => lang('title'),'keywords' => lang('keyword'),'mname' => lang('member'),);
	$caidsarr = array('0' => lang('all_catalog')) + caidsarr();
	$orderbyarr = array(
		'createdate' => lang('add_time'),
		'clicks' => lang('clicks'),
		'comments' => lang('comments'),
	);
	tabheader(lang('seaparset'),'search',"?entry=searchs&action=searchdetail&chid=$chid");
	trbasic(lang('achannel'),'',$chid ? $channels[$chid]['cname'] : lang('all_channel'),'');
	trbasic(lang('searchmode1'),'searchmode',makeoption($searchmodearr,$searchmode),'select');
	trbasic(lang('search_keyword'),'searchword',$searchword);
	trbasic(lang('be_catalog'),'caid',makeoption($caidsarr,$caid),'select');
	foreach($cotypes as $coid => $cotype){
		if(!$chid || !$cotype['chids'] || !in_array($chid,explode(',',$cotype['chids']))){
			$ccidsarr = array('0' => lang('nolimit'));
			$ccidsarr = $ccidsarr + ccidsarr($coid);	
			trbasic("$cotype[cname]","ccid$coid",makeoption($ccidsarr,${"ccid$coid"}),'select');
		}
	}
	if($chid){
		$a_field = new cls_field;
		$fields = read_cache('fields',$chid);
		foreach($fields as $k => $field){
			if($field['available'] && $field['issearch']){
				$a_field->init();
				$a_field->field = read_cache('field',$chid,$k);
				$a_field->trsearch();
			}
		}
		unset($a_field);
	}
	trbasic(lang('indays'),'indays',$indays);
	trbasic(lang('outdays'),'outdays',$outdays);
	trbasic(lang('order type'),'orderby',makeoption($orderbyarr,$orderby),'select');
	trbasic(lang('isasc'),'ordermode',$ordermode,'radio');
	tabfooter('submit',lang('setting'));

	$filterstr = '';
	$wherestr = '';
	$fromstr = 'FROM {$tblprefix}archives AS a';
	if($searchword){
		$filterstr .= ($filterstr ? '&' : '')."searchword=".rawurlencode(stripslashes($searchword));
		$searchmode != 'subject' && $filterstr .= ($filterstr ? '&' : '')."searchmode=$searchmode";
		$searchword = str_replace(array(' ','*'),'%',addcslashes($searchword, '%_'));
		$wherestr .= ($wherestr ? ' AND ' : '')."a.$searchmode LIKE '%$searchword%'";
	}
	if($caid){
		$filterstr .= ($filterstr ? '&' : '')."caid=$caid";
		$caids = array($caid);
		$tempids = array();
		$tempids = son_ids($catalogs,$caid,$tempids);
		$caids = array_merge($caids,$tempids);
		$wherestr .= ($wherestr ? ' AND ' : '')."a.caid ".multi_str($caids);
	}
	foreach($cotypes as $coid => $cotype){
		if(!$chid || !$cotype['chids'] || !in_array($chid,explode(',',$cotype['chids']))){
			if(${"ccid$coid"}){
				$filterstr .= ($filterstr ? '&' : '')."ccid$coid=".${"ccid$coid"};
				$ccids = array(${"ccid$coid"});
				$tempids = array();
				$coclasses = read_cache('coclasses',$coid);
				$tempids = son_ids($coclasses,${"ccid$coid"},$tempids);
				$ccids = array_merge($ccids,$tempids);
				if(empty($cotype['self_reg'])){
					$wherestr .= ($wherestr ? ' AND ' : '')."a.ccid$coid ".multi_str($ccids);
				}else{
					$tempstr = self_sqlstr($coid,$ccids,'a.');
					$tempstr && $wherestr .= (!$wherestr ? '' : ' AND ').$tempstr;
					unset($tempstr);
				} 
			}
		}
	}
	if($chid){
		$filterstr .= ($filterstr ? '&' : '')."chid=$chid";
		$customtable = "archives_$chid";
		$fromstr .= ' LEFT JOIN {$tblprefix}'.$customtable.' AS c ON (a.aid=c.aid)';
		$wherestr .= ($wherestr ? ' AND ' : '')."a.chid='$chid'";
		$a_field = new cls_field;
		$fields = read_cache('fields',$chid);
		foreach($fields as $k => $field){
			if($field['available'] && $field['issearch']){
				$a_field->init();
				$a_field->field = read_cache('field',$chid,$k);
				$a_field->deal_search($a_field->field['tbl'] == 'main' ? "a." : "c.");
				$wherestr .= ($wherestr && $a_field->searchstr ? ' AND ' : '').$a_field->searchstr;
				$a_field->filterstr && $filterstr .= ($filterstr ? '&' : '').$a_field->filterstr;
			}
		}
		unset($a_field);
	}
	if(!empty($indays)){
		$filterstr .= ($filterstr ? '&' : '')."indays=$indays";
		//$wherestr .= ($wherestr ? ' AND ' : '')."a.createdate>'".($timestamp - 86400 * $indays)."'";
		$wherestr .= ($wherestr ? ' AND ' : '')."a.createdate>UNIX_TIMESTAMP()-86400*$indays";

	}
	if(!empty($outdays)){
		$filterstr .= ($filterstr ? '&' : '')."outdays=$outdays";
		//$wherestr .= ($wherestr ? ' AND ' : '')."a.createdate<'".($timestamp - 86400 * $outdays)."'";
		$wherestr .= ($wherestr ? ' AND ' : '')."a.createdate<UNIX_TIMESTAMP()-86400*$outdays";
	}
	$wherestr = "WHERE a.sid='$sid' AND a.checked='1'".($wherestr ? ' AND ' : '').$wherestr;
	
	$orderstr = "ORDER BY a.$orderby ".($ordermode ? 'ASC' : 'DESC');
	$orderby != 'createdate' && $filterstr .= ($filterstr ? '&' : '')."orderby=$orderby";
	$ordermode && $filterstr .= ($filterstr ? '&' : '')."ordermode=$ordermode";
	$sqlstr = "$fromstr $wherestr $orderstr";

	$surlstr = 'search.php'.($filterstr ? '?' : '').$filterstr;
	$rurlstr = 'search.php?searchsubmit=1'.($filterstr ? '&' : '').$filterstr;
	tabheader(lang('seasettres'));
	trbasic(lang('searformurl'),'',"{\$cms_abs}$surlstr",'');
	trbasic(lang('searesurl'),'',"<a href=\"$rurlstr\" target=\"_blank\">{\$cms_abs}$rurlstr</a>",'');
	trbasic(lang('ctaquestr'),'',$sqlstr,'');
	tabfooter();
	a_guide('searchs');
}
?>