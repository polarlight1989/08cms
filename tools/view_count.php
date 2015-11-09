<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
$datastr = 'failed';
$itemstr = '';
$aid = empty($aid) ? 0 : max(0,intval($aid));
empty($aid) && ajax_info($datastr);
foreach(array('clicks','comments','scores','orders','favorites','praises','debases','answers','adopts','price','crid','currency','closed','downs','plays') as $k){
	$itemstr .= (!$itemstr ? '' : ',').'a.'.$k;
}
foreach(array('storage','spare','reports',) as $k){
	$itemstr .= ',s.'.$k;
}

if($arr = $db->fetch_one("SELECT $itemstr FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid WHERE a.aid=".$aid)){
	$datastr = '';
	foreach($arr as $k => $v){
		$datastr .= ($datastr ? '-' : '').$k.'#'.$v;
	}
}
ajax_info($datastr);
?>