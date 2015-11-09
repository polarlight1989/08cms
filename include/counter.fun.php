<?php
!defined('M_COM') && exit('No Permission');
include_once M_ROOT.'./include/cache.fun.php';
function recent2main(){//每天自动更新，将30天内各文档的月统计与周统计记录汇总到文档的archive_rec的表中，将30天之前的记录清除。
	global $db,$tblprefix,$timestamp,$monthstats,$weekstats;
	@set_time_limit(1000);
	@ignore_user_abort(TRUE);
	$curdate = date('Ymd',$timestamp);//当天日期字串
	if(empty($monthstats) && empty($weekstats)) return;
	if($db->result_one("SELECT count(*) FROM {$tblprefix}arecents WHERE vardate='$curdate'")) return;//当天的记录已经处理过了。

	$mstatarr = empty($monthstats) ? array() : array_filter(explode(',',$monthstats));
	$wstatarr = empty($weekstats) ? array() : array_filter(explode(',',$weekstats));
	$rperiod = $mstatarr ? 30 : 7;
	$mindate = $curdate - $rperiod + 1;
	$db->query("DELETE FROM {$tblprefix}arecents WHERE vardate<$mindate OR vardate>$curdate", 'UNBUFFERED');//清除日记录表中的无效日的记录
	
	//文档记录中的原记录清空
	$setstr = $wherestr = '';
	foreach($mstatarr as $k){
		$setstr .= ($setstr ? ',' : '')."m$k=0";
		$wherestr .= ($wherestr ? ' OR ' : '')."m$k != 0";
	}
	foreach($wstatarr as $k){
		$setstr .= ($setstr ? ',' : '')."w$k=0";
		$wherestr .= ($wherestr ? ' OR ' : '')."w$k != 0";
	}
	$db->query("UPDATE {$tblprefix}archives_rec SET $setstr WHERE $wherestr", 'UNBUFFERED');
	
	if($mstatarr){//更新月记录
		$selectstr = 'aid';
		foreach($mstatarr as $k) $selectstr .= ",SUM($k) AS m$k";
		$query = $db->query("SELECT $selectstr FROM {$tblprefix}arecents GROUP BY aid");
		while($item = $db->fetch_array($query)){
			$setstr = '';
			foreach($mstatarr as $k) $setstr .= ($setstr ? ',' : '')."m$k=".$item['m'.$k];
			$db->query("UPDATE {$tblprefix}archives_rec SET $setstr WHERE aid=$item[aid]", 'UNBUFFERED');
		}
	
	}
	if($wstatarr){//更新周记录
		$selectstr = 'aid';
		foreach($wstatarr as $k) $selectstr .= ",SUM($k) AS w$k";
		$mindate = $curdate - 7;
		$query = $db->query("SELECT $selectstr FROM {$tblprefix}arecents WHERE vardate>$mindate GROUP BY aid");
		while($item = $db->fetch_array($query)){
			$setstr = '';
			foreach($wstatarr as $k) $setstr .= ($setstr ? ',' : '')."w$k=".$item['w'.$k];
			$db->query("UPDATE {$tblprefix}archives_rec SET $setstr WHERE aid=$item[aid]", 'UNBUFFERED');
		}
	
	}
}
function album_statsum(){//固定时间间隔统计合辑内的各种统计数据的合计//由文档统计的动作来触发这个函数
	global $db,$tblprefix,$timestamp,$albumstats,$albumstatcircle,$channels;
	$cachetimefile = M_ROOT.'./dynamic/stats/albumsum_time.cac';
	if(empty($albumstats) || ($timestamp - @filemtime($cachetimefile) < $albumstatcircle * 3600)) return;
	$statarr = array_filter(explode(',',$albumstats));
	if(!$statarr) return;
	$chids = array();
	load_cache('channels');
	foreach($channels as $k => $v){
		$v = read_cache('channel',$k);
		$v['isalbum'] && $v['statsum'] && $chids[] = $k;
	}
	if(!$chids) return;
	@set_time_limit(1000);
	@ignore_user_abort(TRUE);

	$selectstr = 'b.pid';
	foreach($statarr as $k) $selectstr .= ",SUM(a.$k) AS a$k";
	$query = $db->query("SELECT $selectstr FROM {$tblprefix}albums AS b LEFT JOIN {$tblprefix}archives AS a ON a.aid=b.aid WHERE a.checked=1 AND b.checked=1 AND b.pchid ".multi_str($chids)." GROUP BY b.pid");
	while($item = $db->fetch_array($query)){
		$setstr = '';
		foreach($statarr as $k) $setstr .= ($setstr ? ',' : '')."a$k=".$item["a$k"];
		$db->query("UPDATE {$tblprefix}archives_rec SET $setstr WHERE aid=$item[pid]", 'UNBUFFERED');
	}
	if(@$fp = fopen($cachetimefile,'w')) fclose($fp);
	return;
}
function album_new(){
	global $db,$tblprefix,$timestamp,$album_newstat;
	$cachetimefile = M_ROOT.'./dynamic/stats/albumnew_time.cac';
	if(empty($album_newstat) || ($timestamp - @filemtime($cachetimefile) < $album_newstat * 3600)) return;
	@set_time_limit(1000);
	@ignore_user_abort(TRUE);
	$db->query("UPDATE {$tblprefix}archives a SET a.abnew=(SELECT MAX(b.aid) FROM {$tblprefix}albums b WHERE b.pid=a.aid AND b.checked=1) 
	WHERE EXISTS (SELECT 1 FROM {$tblprefix}albums b WHERE a.aid=b.pid)");
	if(@$fp = fopen($cachetimefile,'w')) fclose($fp);
	return;
	
}
function cn_statsum(){//暂时不处理层的关系//需要更新缓存
	global $db,$tblprefix,$timestamp,$cotypestats,$cnodestatcircle,$cotypes,$subsites,$acatalogs;
	$cachetimefile = M_ROOT.'./dynamic/stats/cotypesum_time.cac';
	if(empty($cotypestats) || ($timestamp - @filemtime($cachetimefile) < $cnodestatcircle * 3600)) return;
	$statarr = array_filter(explode(',',$cotypestats));
	if(!$statarr) return;
	@set_time_limit(1000);
	@ignore_user_abort(TRUE);
	load_cache('cotypes,acatalogs');
	if(in_array('ca',$statarr)){
		$query = $db->query("SELECT caid,COUNT(aid) AS archives,SUM(clicks) AS clicks FROM {$tblprefix}archives GROUP BY caid");
		while($item = $db->fetch_array($query)){
			$db->query("UPDATE {$tblprefix}catalogs SET archives='$item[archives]',clicks='$item[clicks]' WHERE caid='$item[caid]'", 'UNBUFFERED');
		}
		foreach($acatalogs as $k1 => $v1){
			$ids = cnsonids($k1,$acatalogs);
			if(count($ids) > 1){
				$row = $db->fetch_one("SELECT SUM(archives) AS archives,SUM(clicks) AS clicks FROM {$tblprefix}catalogs WHERE caid ".multi_str($ids));
				$db->query("UPDATE {$tblprefix}catalogs SET archives='$row[archives]',clicks='$row[clicks]' WHERE caid='$k1'", 'UNBUFFERED');
			}
		}
		$sids = array_keys($subsites);
		$sids[] = 0;
		foreach($sids as $v) updatecache('catalogs','',$v);
	}
	foreach($cotypes as $k => $v){
		if(!$v['self_reg'] && in_array($k,$statarr)){
			$coclasses = read_cache('coclasses',$k);
			foreach($coclasses as $k1 => $v1){
				if($cnsql = cnsql($k,$k1,'')){
					$row = $db->fetch_one("SELECT COUNT(aid) AS archives,SUM(clicks) AS clicks FROM {$tblprefix}archives WHERE $cnsql");
					$db->query("UPDATE {$tblprefix}coclass SET archives='$row[archives]',clicks='$row[clicks]' WHERE ccid='$k1'", 'UNBUFFERED');
				}
			}
			foreach($coclasses as $k1 => $v1){
				$ids = cnsonids($k1,$coclasses);
				if((count($ids) > 1) && ($cnsql = cnsql($k,$ids,''))){
					$row = $db->fetch_one("SELECT SUM(archives) AS archives,SUM(clicks) AS clicks FROM {$tblprefix}coclass WHERE $cnsql");
					$db->query("UPDATE {$tblprefix}coclass SET archives='$row[archives]',clicks='$row[clicks]' WHERE ccid='$k1'", 'UNBUFFERED');
				}
			}
			updatecache('coclasses',$k);
		}
	}
	unset($coclasses);
	if(@$fp = fopen($cachetimefile,'w')) fclose($fp);
	return;
}
?>

