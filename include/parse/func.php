<?php
!defined('M_COM') && exit('No Permisson');
include_once M_ROOT."./include/archive.cls.php";
include_once M_ROOT."./include/farchive.cls.php";

function one_acontext(&$tag){
	global $cotypes,$db,$tblprefix,$_midarr;
	if(empty($_midarr['aid']) || empty($tag['chid'])) return '';
	if(!($pid = $db->result_one("SELECT pid FROM {$tblprefix}albums WHERE aid='$_midarr[aid]' AND pchid='$tag[chid]'"))) return '';
	$sqlstr = "SELECT a.* FROM {$tblprefix}albums b LEFT JOIN {$tblprefix}archives a ON a.aid=b.aid WHERE b.pid='$pid' AND b.aid".(empty($tag['next']) ? "<" : ">").$_midarr['aid']." AND b.checked=1 AND a.checked=1";
	if(!empty($tag['chsource'])){
		$sqlstr .= " AND b.chid='$tag[chsource]'";
	}
	if(!($item = $db->fetch_one($sqlstr." ORDER BY a.aid ".(empty($tag['next']) ? "DESC" : "ASC")." LIMIT 0,1"))) return '';
	arc_parse($item);
	return $item;
}
function arr_alarchives(&$tag,$type='c'){
	global $db;
	$rets = array();
	if($sqlstr = alarc_sqlstr($tag,$type)){
		$query = $db->query($sqlstr);
		while($row = $db->fetch_array($query)){
			arc_parse($row);
			$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$rets[] = $row;
		}
	}
	return $rets;
}
function inscounts(&$tag){
	global $db;
	$ret['counts'] = 0;
	if($sqlstr = alarc_sqlstr($tag,'c',1)) $ret['counts'] = $db->result_one($sqlstr);
	return $ret;

}
function alarc_sqlstr(&$tag,$type='c',$iscount=0){
	global $channels,$acatalogs,$cotypes,$tblprefix,$timestamp,$_midarr,$_mp;
	$sqlorder = $sqllimit = '';
	$sqlselect = $iscount ? (empty($tag['nocp']) ? "SELECT COUNT(*)" : "SELECT COUNT(DISTINCT cpid)") : "SELECT b.checked,b.volid,a.*";
	$sqlfrom = " FROM {$tblprefix}albums b LEFT JOIN {$tblprefix}archives a ON a.aid=b.aid";
	$sqlwhere = " WHERE a.checked=1 AND b.checked=1";
	if(empty($_midarr['aid'])) return false;
	$sqlwhere .= " AND b.pid='".$_midarr['aid']."'";
	if(!empty($tag['rec']) || !empty($tag['orderby1'])){
		$sqlfrom .= " LEFT JOIN {$tblprefix}archives_rec r ON r.aid=b.aid";
		!$iscount && $sqlselect .= ",r.*";
	}
	if(!empty($tag['casource'])){
		$caids = array();
		if($tag['casource'] == '1'){//如果指定为空，返回空集
			if(empty($tag['caids'])) return false;
			$tcaids = explode(',',$tag['caids']);
			foreach($tcaids as $caid) isset($acatalogs[$caid]) && $caids[] = $caid;
			if(empty($caids)) return false;
		}elseif($tag['casource'] == '2'){
			if(!empty($_midarr['caid'])){
				if(empty($acatalogs[$_midarr['caid']])) return false;
				$caids[] = $_midarr['caid'];
			}
		}elseif($tag['casource'] == '3'){
			if(!empty($_midarr['caid'])){
				if(empty($acatalogs[$_midarr['caid']])) return false;
				foreach($acatalogs as $catalog){
					$catalog['pid'] == $acatalogs[$_midarr['caid']]['pid'] && $caids[] = $catalog['caid'];
				}
			}
		}
		if(!empty($caids) && !empty($tag['caidson'])){
			$tempsons = array();
			foreach($caids as $caid){
				$tempsons = son_ids($acatalogs,$caid,$tempsons);
			}
			$caids = array_unique(array_merge($caids,$tempsons));
		}
		!empty($caids) && $sqlwhere .= " AND a.caid ".multi_str($caids);
	}
	foreach($cotypes as $k => $cotype){
		$ccids = array();	
		if(!empty($tag['cosource'.$k])){
			$coclasses = read_cache('coclasses',$k);
			if($tag['cosource'.$k] == '1'){
				if(empty($tag['ccids'.$k])) return false;
				$tccids = explode(',',$tag['ccids'.$k]);
				foreach($tccids as $ccid) isset($coclasses[$ccid]) && $ccids[] = $ccid;
				if(empty($ccids)) return false;
			}elseif($tag['cosource'.$k] == '2'){
				if(!empty($_midarr['ccid'.$k])){
					if(empty($coclasses[$_midarr['ccid'.$k]])) return false;
					$ccids[] = $_midarr['ccid'.$k];
				}
			}elseif($tag['cosource'.$k] == '3'){
				if(!empty($_midarr['ccid'.$k])){
					if(empty($coclasses[$_midarr['ccid'.$k]])) return false;
					foreach($coclasses as $coclass){
						$coclass['pid'] == $coclasses[$_midarr['ccid'.$k]]['pid'] && $ccids[] = $coclass['ccid'];
					}
				}
			}
			if(!empty($ccids) && !empty($tag['ccidson'.$k])){
				$tempsons = array();
				foreach($ccids as $ccid){
					$tempsons = son_ids($coclasses,$ccid,$tempsons);
				}
				$ccids = array_unique(array_merge($ccids,$tempsons));
			}
			if(!empty($ccids) && $str = cnsql($k,$ccids,'a.')) $sqlwhere .= ' AND '.$str;
		}
	}
	if(!empty($tag['chsource'])){
		if(empty($tag['chids'])) return false;
		$tchids = explode(',',$tag['chids']);
		$sqlwhere .= " AND a.chid ".multi_str($tchids);
		$chid = 0;
		if(count($tchids) == 1) $chid = $tchids[0];
		if(!empty($tag['detail']) && $chid && @$channels[$chid]){
			$sqlfrom .= " LEFT JOIN {$tblprefix}archives_$chid c ON c.aid=a.aid";
			!$iscount && $sqlselect .= ",c.*";
		}
	}
	if(!empty($tag['nochids'])){
		if($nochids = explode(',',$tag['nochids'])){
			$sqlwhere .= " AND a.chid ".multi_str($nochids,1);
		}
	}
	if(!empty($tag['validperiod'])) $sqlwhere .= " AND (a.enddate='0' OR a.enddate>'$timestamp')";
	isset($tag['clicks']) && $sqlwhere .= " AND a.clicks > $tag[clicks]";
	isset($tag['comments']) && $sqlwhere .= " AND a.comments > $tag[comments]";
	!empty($tag['indays']) && $sqlwhere .= " AND a.createdate > $timestamp - 86400 * $tag[indays]";
	!empty($tag['outdays']) && $sqlwhere .= " AND a.createdate < $timestamp - 86400 * $tag[outdays]";
	isset($tag['favorites']) && $sqlwhere .= " AND a.favorites > $tag[favorites]";
	isset($tag['praises']) && $sqlwhere .= " AND a.praises > $tag[praises]";
	isset($tag['debases']) && $sqlwhere .= " AND a.debases > $tag[debases]";
	isset($tag['orders']) && $sqlwhere .= " AND a.orders > $tag[orders]";
	isset($tag['answers']) && $sqlwhere .= " AND a.answers > $tag[answers]";
	isset($tag['adopts']) && $sqlwhere .= " AND a.adopts > $tag[adopts]";
	!empty($tag['inprice']) && $sqlwhere .= " AND a.price < $tag[inprice]";
	!empty($tag['outprice']) && $sqlwhere .= " AND a.price >= $tag[outprice]";
	!empty($tag['incurrency']) && $sqlwhere .= " AND a.currency < $tag[incurrency]";
	!empty($tag['outcurrency']) && $sqlwhere .= " AND a.currency >= $tag[outcurrency]";
	if(isset($tag['closed']) && $tag['closed'] != '-1'){
		if($tag['closed']){
			$sqlwhere .= " AND (a.closed = 1 OR a.finishdate<$timestamp)";
		}else $sqlwhere .= " AND a.closed = 0 AND a.finishdate>$timestamp";
	}
	if(isset($tag['abover']) && $tag['abover'] != '-1'){
		$sqlwhere .= " AND a.abover = '$tag[abover]'";
	}
	if(!empty($tag['wherestr'])){
		if(empty($tag['isfunc'])){
			$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
		}else{
			$v = @func_sqlstr($tag['wherestr']);
			$v && $sqlwhere .= " AND $v";
		}
	}
	
	if(!$iscount){
		!empty($tag['nocp']) && $sqlwhere .= " GROUP BY a.cpid";
		!empty($tag['orderby']) && $sqlorder .= in_str('albumorder',$tag['orderby']) ? 'b.vieworder ASC' : ('a.'.str_replace('_',' ',$tag['orderby']));
		!empty($tag['orderby1']) && $sqlorder .= ($sqlorder ? ',' : '').'r.'.str_replace('_',' ',$tag['orderby1']);
		!empty($tag['orderstr']) && $sqlorder .= ($sqlorder ? ',' : '').$tag['orderstr'];
		$sqlorder = empty($sqlorder) ? " ORDER BY b.volid,a.vieworder,a.aid" : " ORDER BY $sqlorder";

		$limits = empty($tag['limits']) ? 10 : $tag['limits'];
		$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;
	}
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function one_archive(&$tag){
	global $db,$tblprefix,$channels,$_midarr;
	$ret = array();
	$aid = !empty($tag['aid']) ? $tag['aid'] : (empty($_midarr['aid']) ? 0 : $_midarr['aid']);
	if(!$aid) return $ret;
	$arc = new cls_archive;
	if(!$arc->arcid($aid)) return $ret;
	if(!empty($tag['album'])){
		if(empty($channels[$tag['album']])) return $ret;
		if(!($aid = $db->result_one("SELECT pid FROM {$tblprefix}albums WHERE aid='$aid' AND pchid='$tag[album]' AND checked=1"))) return $ret;
		$arc->init();
		if(!$arc->arcid($aid)) return $ret;
	}
	!empty($tag['chdata']) && $arc->detail_data();
	$ret = $arc->archive;
	unset($arc);
	arc_parse($ret);
	return $ret;
}
function arr_searchs(&$tag,$type='p'){
	global $db,$tblprefix,$timestamp,$_mp;
	$_da = $GLOBALS['_da'];
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$sqlstr = empty($tag['detail']) || empty($_da['chid']) ? "SELECT a.* " : "SELECT a.*,c.*";
	$validstr = !empty($tag['validperiod']) ? " AND (a.enddate='0' OR a.enddate>'$timestamp')" : '';
	$sqlstr .= $_da['sqlstr'].$validstr.' '.$_da['orderstr']." LIMIT ".(($_mp['nowpage'] - 1) * $limits).",".$limits;
	unset($_da);
	$query = $db->query($sqlstr);
	$rets = array();
	while($row = $db->fetch_array($query)){
		arc_parse($row);
		$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
		$rets[] = $row;
	}
	return $rets;
}
function arr_msearchs(&$tag,$type='p'){
	global $db,$tblprefix,$_mp;
	$_da = $GLOBALS['_da'];
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$sqlstr = empty($tag['detail']) || empty($_da['mchid']) ? "SELECT m.*,s.* " : "SELECT m.*,s.*,c.*";
	$sqlstr .= $_da['sqlstr'].' '.$_da['orderstr']." LIMIT ".(($_mp['nowpage'] - 1) * $limits).",".$limits;
	unset($_da);
	$query = $db->query($sqlstr);
	$rets = array();
	while($row = $db->fetch_array($query)){
		$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
		$rets[] = $row;
	}
	return $rets;
}
function arr_masearchs(&$tag,$type='p'){
	global $db,$tblprefix,$timestamp,$_mp,$cotypes;
	$_da = $GLOBALS['_da'];
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$sqlstr = 'ma.*,m.mchid,m.caid';
	foreach($cotypes as $k => $v) $sqlstr .= ",m.ccid$k";
	$sqlstr .= $_da['sqlstr'].' '.$_da['orderstr']." LIMIT ".(($_mp['nowpage'] - 1) * $limits).",".$limits;
	unset($_da);
	$query = $db->query($sqlstr);
	$rets = array();
	while($row = $db->fetch_array($query)){
		$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
		$rets[] = $row;
	}
	return $rets;
}

function arr_archives(&$tag,$type='c'){
	global $db;
	$rets = array();
	if($sqlstr = arc_sqlstr($tag,$type)){
		$query = $db->query($sqlstr);
		while($row = $db->fetch_array($query)){
			arc_parse($row);
			$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$rets[] = $row;
		}
	}
	return $rets;
}
function one_arcscount(&$tag){
	global $db;
	$ret['counts'] = 0;
	if($sqlstr = arc_sqlstr($tag,'c',1)) $ret['counts'] = $db->result_one($sqlstr);
	return $ret;
}

function arc_sqlstr(&$tag,$type='c',$iscount=0){
	global $channels,$acatalogs,$cotypes,$tblprefix,$timestamp,$sid,$_midarr,$_mp;
	$nsid = empty($tag['nsid']) ? 0 : intval($tag['nsid']);
	if(!$nsid){
		$nsid = isset($_midarr['sid']) ? $_midarr['sid'] : $sid;
	}elseif($nsid == -1) $nsid = 0;
	if($nsid == -2){
		$ncatalogs = &$acatalogs;
	}else $ncatalogs = read_cache('catalogs','','',$nsid);

	$sqlorder = $sqllimit = '';
	$sqlselect = $iscount ? (empty($tag['nocp']) ? "SELECT COUNT(*)" : "SELECT COUNT(DISTINCT cpid)") : "SELECT a.*";
	$sqlwhere = " WHERE a.checked=1";
	$sqlfrom = " FROM {$tblprefix}archives a";
	if(!empty($tag['rec']) || !empty($tag['orderby1'])){
		$sqlfrom .= " LEFT JOIN {$tblprefix}archives_rec r ON r.aid=a.aid";
		!$iscount && $sqlselect .= ",r.*";
	}

	if($nsid != -2) $sqlwhere .= " AND a.sid='$nsid'";
	if(!empty($tag['casource'])){
		$caids = array();
		if($tag['casource'] == '1'){//如果指定为空，返回空集
			if(empty($tag['caids'])) return false;
			$tcaids = explode(',',$tag['caids']);
			foreach($tcaids as $caid) isset($ncatalogs[$caid]) && $caids[] = $caid;
			if(empty($caids)) return false;
		}elseif($tag['casource'] == '2'){
			if(!empty($_midarr['caid'])){
				if(empty($ncatalogs[$_midarr['caid']])) return false;
				$caids[] = $_midarr['caid'];
			}
		}elseif($tag['casource'] == '3'){
			if(!empty($_midarr['caid'])){
				if(empty($ncatalogs[$_midarr['caid']])) return false;
				foreach($ncatalogs as $catalog){
					$catalog['pid'] == $ncatalogs[$_midarr['caid']]['pid'] && $caids[] = $catalog['caid'];
				}
			}
		}
		if(!empty($caids) && !empty($tag['caidson'])){
			$tempsons = array();
			foreach($caids as $caid){
				$tempsons = son_ids($ncatalogs,$caid,$tempsons);
			}
			$caids = array_unique(array_merge($caids,$tempsons));
		}
		!empty($caids) && $sqlwhere .= " AND a.caid ".multi_str($caids);
	}

	foreach($cotypes as $k => $cotype){
		$ccids = array();	
		if(!empty($tag['cosource'.$k])){
			$coclasses = read_cache('coclasses',$k);
			if($tag['cosource'.$k] == '1'){
				if(empty($tag['ccids'.$k])) return false;
				$tccids = explode(',',$tag['ccids'.$k]);
				foreach($tccids as $ccid) isset($coclasses[$ccid]) && $ccids[] = $ccid;
				if(empty($ccids)) return false;
			}elseif($tag['cosource'.$k] == '2'){
				if(!empty($_midarr['ccid'.$k])){
					if(empty($coclasses[$_midarr['ccid'.$k]])) return false;
					$ccids[] = $_midarr['ccid'.$k];
				}
			}elseif($tag['cosource'.$k] == '3'){
				if(!empty($_midarr['ccid'.$k])){
					if(empty($coclasses[$_midarr['ccid'.$k]])) return false;
					foreach($coclasses as $coclass){
						$coclass['pid'] == $coclasses[$_midarr['ccid'.$k]]['pid'] && $ccids[] = $coclass['ccid'];
					}
				}
			}
			if(!empty($ccids) && !empty($tag['ccidson'.$k])){
				$tempsons = array();
				foreach($ccids as $ccid) $tempsons = son_ids($coclasses,$ccid,$tempsons);
				$ccids = array_unique(array_merge($ccids,$tempsons));
			}
			if(!empty($ccids) && $str = cnsql($k,$ccids,'a.')) $sqlwhere .= ' AND '.$str;
		}
	}
	if(!empty($tag['space'])){
		if(empty($_midarr['mid'])) return false;
		$sqlwhere .= " AND a.mid='".$_midarr['mid']."'";
	}
	if(!empty($tag['ucsource'])){
		if(!empty($_midarr['ucid'])) $sqlwhere .= " AND a.ucid='".$_midarr['ucid']."'";
	}
	if(!empty($tag['chsource'])){
		$chid = 0;
		if($tag['chsource'] == 1){
			if(empty($_midarr['chid'])) return false;
			$sqlwhere .= " AND a.chid='".$_midarr['chid']."'";
			$chid = $_midarr['chid'];
		}elseif($tag['chsource'] == 2){
			if(empty($tag['chids'])) return false;
			$tchids = explode(',',$tag['chids']);
			$sqlwhere .= " AND a.chid ".multi_str($tchids);
			if(count($tchids) == 1) $chid = $tchids[0];
		}
		if(!empty($tag['detail']) && $chid && @$channels[$chid]){
			$customtable = "archives_$chid";
			$sqlfrom .= " LEFT JOIN {$tblprefix}$customtable c ON c.aid=a.aid";
			!$iscount && $sqlselect .= ",c.*";
		}
	}
	if(!empty($tag['nochids'])){
		if($nochids = explode(',',$tag['nochids'])){
			$sqlwhere .= " AND a.chid ".multi_str($nochids,1);
		}
	}
	if(!empty($tag['validperiod'])) $sqlwhere .= " AND (a.enddate='0' OR a.enddate>'$timestamp')";
	isset($tag['clicks']) && $sqlwhere .= " AND a.clicks > $tag[clicks]";
	isset($tag['comments']) && $sqlwhere .= " AND a.comments > $tag[comments]";
	!empty($tag['indays']) && $sqlwhere .= " AND a.createdate > $timestamp - 86400 * $tag[indays]";
	!empty($tag['outdays']) && $sqlwhere .= " AND a.createdate < $timestamp - 86400 * $tag[outdays]";
	isset($tag['favorites']) && $sqlwhere .= " AND a.favorites > $tag[favorites]";
	isset($tag['praises']) && $sqlwhere .= " AND a.praises > $tag[praises]";
	isset($tag['debases']) && $sqlwhere .= " AND a.debases > $tag[debases]";
	isset($tag['orders']) && $sqlwhere .= " AND a.orders > $tag[orders]";
	isset($tag['answers']) && $sqlwhere .= " AND a.answers > $tag[answers]";
	isset($tag['adopts']) && $sqlwhere .= " AND a.adopts > $tag[adopts]";
	!empty($tag['inprice']) && $sqlwhere .= " AND a.price < $tag[inprice]";
	!empty($tag['outprice']) && $sqlwhere .= " AND a.price >= $tag[outprice]";
	!empty($tag['incurrency']) && $sqlwhere .= " AND a.currency < $tag[incurrency]";
	!empty($tag['outcurrency']) && $sqlwhere .= " AND a.currency >= $tag[outcurrency]";
	if(isset($tag['closed']) && $tag['closed'] != '-1'){
		if($tag['closed']){
			$sqlwhere .= " AND (a.closed = 1 OR a.finishdate<$timestamp)";
		}else $sqlwhere .= " AND a.closed = 0 AND a.finishdate>$timestamp";
	}
	if(isset($tag['abover']) && $tag['abover'] != '-1'){
		$sqlwhere .= " AND a.abover = '$tag[abover]'";
	}
	if(!empty($tag['wherestr'])){
		if(empty($tag['isfunc'])){
			$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
		}else{
			$v = @func_sqlstr($tag['wherestr']);
			$v && $sqlwhere .= " AND $v";
		}
	}
	if(!$iscount){
		!empty($tag['nocp']) && $sqlwhere .= " GROUP BY a.cpid";
		!empty($tag['orderby']) && $sqlorder .= ($sqlorder ? ',' : '').'a.'.str_replace('_',' ',$tag['orderby']);
		!empty($tag['orderby1']) && $sqlorder .= ($sqlorder ? ',' : '').'r.'.str_replace('_',' ',$tag['orderby1']);
		!empty($tag['orderstr']) && $sqlorder .= ($sqlorder ? ',' : '').$tag['orderstr'];
		$sqlorder = empty($sqlorder) ? " ORDER BY a.aid DESC" : " ORDER BY $sqlorder";
		
		$limits = empty($tag['limits']) ? 10 : $tag['limits'];
		$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;
	}
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function arr_albums(&$tag,$type='c'){
	global $db;
	$rets = array();
	if($sqlstr = ab_sqlstr($tag,$type)){
		$query = $db->query($sqlstr);
		while($row = $db->fetch_array($query)){
			arc_parse($row);
			$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$rets[] = $row;
		}
	}
	return $rets;
}

function ab_sqlstr(&$tag,$type='c'){
	global $channels,$acatalogs,$cotypes,$tblprefix,$timestamp,$sid,$_midarr,$_mp;
	$aid = empty($tag['aid']) ? @$_midarr['aid'] : $tag['aid'];
	if(!$aid) return '';
	
	$nsid = empty($tag['nsid']) ? 0 : intval($tag['nsid']);
	if(!$nsid){
		$nsid = isset($_midarr['sid']) ? $_midarr['sid'] : $sid;
	}elseif($nsid == -1) $nsid = 0;
	if($nsid == -2){
		$ncatalogs = &$acatalogs;
	}else $ncatalogs = read_cache('catalogs','','',$nsid);

	$sqlorder = $sqllimit = '';
	$sqlselect = "SELECT a.*";
	$sqlwhere = " WHERE b.aid='$aid' AND a.checked=1";
	$sqlfrom = " FROM {$tblprefix}albums b LEFT JOIN {$tblprefix}archives a ON a.aid=b.pid";

	if($nsid != -2) $sqlwhere .= " AND a.sid='$nsid'";
	if(!empty($tag['casource'])){
		$caids = array();
		if($tag['casource'] == '1'){//如果指定为空，返回空集
			if(empty($tag['caids'])) return false;
			$tcaids = explode(',',$tag['caids']);
			foreach($tcaids as $caid) isset($ncatalogs[$caid]) && $caids[] = $caid;
			if(empty($caids)) return false;
		}elseif($tag['casource'] == '2'){
			if(!empty($_midarr['caid'])){
				if(empty($ncatalogs[$_midarr['caid']])) return false;
				$caids[] = $_midarr['caid'];
			}
		}elseif($tag['casource'] == '3'){
			if(!empty($_midarr['caid'])){
				if(empty($ncatalogs[$_midarr['caid']])) return false;
				foreach($ncatalogs as $catalog){
					$catalog['pid'] == $ncatalogs[$_midarr['caid']]['pid'] && $caids[] = $catalog['caid'];
				}
			}
		}
		if(!empty($caids) && !empty($tag['caidson'])){
			$tempsons = array();
			foreach($caids as $caid){
				$tempsons = son_ids($ncatalogs,$caid,$tempsons);
			}
			$caids = array_unique(array_merge($caids,$tempsons));
		}
		!empty($caids) && $sqlwhere .= " AND a.caid ".multi_str($caids);
	}

	foreach($cotypes as $k => $cotype){
		$ccids = array();	
		if(!empty($tag['cosource'.$k])){
			$coclasses = read_cache('coclasses',$k);
			if($tag['cosource'.$k] == '1'){
				if(empty($tag['ccids'.$k])) return false;
				$tccids = explode(',',$tag['ccids'.$k]);
				foreach($tccids as $ccid) isset($coclasses[$ccid]) && $ccids[] = $ccid;
				if(empty($ccids)) return false;
			}elseif($tag['cosource'.$k] == '2'){
				if(!empty($_midarr['ccid'.$k])){
					if(empty($coclasses[$_midarr['ccid'.$k]])) return false;
					$ccids[] = $_midarr['ccid'.$k];
				}
			}elseif($tag['cosource'.$k] == '3'){
				if(!empty($_midarr['ccid'.$k])){
					if(empty($coclasses[$_midarr['ccid'.$k]])) return false;
					foreach($coclasses as $coclass){
						$coclass['pid'] == $coclasses[$_midarr['ccid'.$k]]['pid'] && $ccids[] = $coclass['ccid'];
					}
				}
			}
			if(!empty($ccids) && !empty($tag['ccidson'.$k])){
				$tempsons = array();
				foreach($ccids as $ccid){
					$tempsons = son_ids($coclasses,$ccid,$tempsons);
				}
				$ccids = array_unique(array_merge($ccids,$tempsons));
			}
			if(!empty($ccids) && $str = cnsql($k,$ccids,'a.')) $sqlwhere .= ' AND '.$str;
		}
	}
	if(!empty($tag['chsource'])){
		$chid = 0;
		if($tag['chsource'] == 1){
			if(empty($_midarr['chid'])) return false;
			$sqlwhere .= " AND a.chid='".$_midarr['chid']."'";
			$chid = $_midarr['chid'];
		}elseif($tag['chsource'] == 2){
			if(empty($tag['chids'])) return false;
			$tchids = explode(',',$tag['chids']);
			$sqlwhere .= " AND a.chid ".multi_str($tchids);
			if(count($tchids) == 1) $chid = $tchids[0];
		}
		if(!empty($tag['detail']) && $chid && @$channels[$chid]){
			$customtable = "archives_$chid";
			$sqlfrom .= " LEFT JOIN {$tblprefix}$customtable c ON c.aid=a.aid";
			$sqlselect .= ",c.*";
		}
	}
	if(!empty($tag['validperiod'])) $sqlwhere .= " AND (a.enddate='0' OR a.enddate>'$timestamp')";
	if(isset($tag['abover']) && $tag['abover'] != '-1'){
		$sqlwhere .= " AND a.abover = '$tag[abover]'";
	}
	if(!empty($tag['wherestr'])){
		if(empty($tag['isfunc'])){
			$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
		}else{
			$v = @func_sqlstr($tag['wherestr']);
			$v && $sqlwhere .= " AND $v";
		}
	}
	!empty($tag['nocp']) && $sqlwhere .= " GROUP BY a.cpid";
	!empty($tag['orderby']) && $sqlorder .= ($sqlorder ? ',' : '').'a.'.str_replace('_',' ',$tag['orderby']);
	!empty($tag['orderstr']) && $sqlorder .= ($sqlorder ? ',' : '').$tag['orderstr'];
	$sqlorder = empty($sqlorder) ? " ORDER BY a.aid DESC" : " ORDER BY $sqlorder";
	
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$sqllimit = " LIMIT ".(empty($tag['startno']) ? 0 : $tag['startno']).",".$limits;
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function one_file(&$tag){
	global $_midarr;
	$ret = array();
	$ret['aid'] = @$_midarr['aid'];
	$ret['url'] = empty($tag['tname']) ? @$_midarr['url'] : view_atmurl($tag['tname']);
	return $ret;
}
function arr_files(&$tag){
	global $_midarr;
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$files = @marray_slice(unserialize($tag['tname']),0,$limits);
	$rets = array();
	if(!empty($files)){
		foreach($files as $k => $v){
			$item = array();
			$item['fid'] = $k;
			$item['aid'] = @$_midarr['aid'];
			$item['url'] = view_atmurl($v['remote']);
			$item['title'] = $v['title'];
			$rets[] = $item;
		}
	}
	unset($files,$v,$item);
	return $rets; 
}

function one_media(&$tag){//返回包含播放区块的数组
	global $players,$_midarr;
	$ret = array();
	if(empty($tag['tname'])){
		$ret['url'] = @$_midarr['url'];
		$ret['player'] = @$_midarr['player'];
	}else{
		$temps = explode('#',$tag['tname']);
		$ret['url'] = view_atmurl($temps[0]);
		$ret['player'] = empty($temps[1]) ? 0 : $temps[1];
		unset($temps);
	}
	$ret['aid'] = @$_midarr['aid'];
	$ret['playbox'] = player_box($ret,$tag);
	return $ret;
}
function arr_medias(&$tag){//需要加上width,height,fname
	global $_midarr;
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$medias = @marray_slice(unserialize($tag['tname']),0,$limits);
	$rets = array();
	if(!empty($medias)){
		$ptype = substr($tag['tclass'],0,5);
		foreach($medias as $k => $v){
			$item = array();
			$item['fid'] = $k;
			$item['aid'] = @$_midarr['aid'];
			$item['url'] = view_atmurl($v['remote']);
			$item['title'] = $v['title'];
			$item['player'] = empty($v['player']) ? 0 : $v['player'];
			$item['playbox'] = player_box($item,$tag);
			$rets[] = $item;
		}
	}
	unset($medias,$v,$item);
	return $rets; 
}
function player_box(&$item,&$tag){
	global $players;
	if(empty($item['url'])) return '';
	$ret = '';
	load_cache('players');
	$plid = empty($item['player']) ? 0 : $item['player'];
	if(!$plid){
		$ext = strtolower(mextension($item['url']));
		$ptype = substr($tag['tclass'],0,5);
		foreach($players as $k => $player){
			if($player['available'] && ($player['ptype'] == $ptype) && in_array($ext,array_filter(explode(',',$player['exts'])))){
				$plid = $k;
				break;
			}
		}
	}
	if($plid){
		$item['width'] = empty($tag['width']) ? '100%' : $tag['width'];
		$item['height'] = empty($tag['height']) ? '100%' : $tag['height'];
		$player = read_cache('player',$plid);
		$ret = sqlstr_replace($player['template'],$item);
	}
	unset($player);
	return $ret;
}
function one_image(&$tag){//带url为数组
	$ret = array();
	$temp = @explode('#',$tag['tname']);
	if(!empty($temp[0])){
		$ret['url'] = view_atmurl($temp[0]);//完整的url
		if(@$tag['thumb'] && @$tag['maxwidth'] && @$tag['maxheight'] && islocal($ret['url'],1)){
			if(is_file(local_atm($ret['url'],1).'s/'.$tag['maxwidth'].'_'.$tag['maxheight'].'.jpg')){
				$ret['url_s'] = view_atmurl(str_replace(M_ROOT,'',local_atm($ret['url'],1)).'s/'.$tag['maxwidth'].'_'.$tag['maxheight'].'.jpg');
			}else $ret['url_s'] = thumb($ret['url'],@$tag['maxwidth'],@$tag['maxheight']);
		}else $ret['url_s'] = $ret['url'];

		if($ret['url_s'] == $ret['url']){//使用原图来重计宽高
			$wh = imagewh($ret['url'],@$temp[1],@$temp[2],@$tag['maxwidth'],@$tag['maxheight']);
			foreach(array('width','height') as $var) $ret[$var] = $wh[$var];
		}else foreach(array('width','height') as $var) $ret[$var] = @$tag['max'.$var];//真正启用了缩略图,直接使用设定的宽高
	}else{
		$ret['url'] = $ret['url_s'] = empty($tag['emptyurl']) ? '' : view_atmurl($tag['emptyurl']);
		foreach(array('width','height') as $var) $ret[$var] = @$tag['max'.$var];
	}
	return $ret;
}
function arr_images(&$tag,$type='u'){
	global $_mp;
	$rets = array();
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$images = @marray_slice(unserialize($tag['tname']),$type == 'p' ? ($_mp['nowpage'] - 1) * $limits : 0,$limits);
	if(!empty($images)){
		foreach($images as $k => $v){
			$v['fid'] = $k;
			$v['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$v['url'] = view_atmurl($v['remote']);//完整的url
			if(empty($v['url']) && empty($tag['emptyurl'])) continue;
			if(!empty($v['url'])){
				if(@$tag['thumb'] && @$tag['maxwidth'] && @$tag['maxheight'] && islocal($v['url'],1)){
					if(is_file(local_atm($v['url'],1).'s/'.$tag['maxwidth'].'_'.$tag['maxheight'].'.jpg')){
						$v['url_s'] = view_atmurl(str_replace(M_ROOT,'',local_atm($v['url'],1)).'s/'.$tag['maxwidth'].'_'.$tag['maxheight'].'.jpg');
					}else $v['url_s'] = thumb($v['url'],@$tag['maxwidth'],@$tag['maxheight']);
				}else $v['url_s'] = $v['url'];
		
				if($v['url_s'] == $v['url']){//使用原图来重计宽高
					$wh = imagewh($v['url'],@$v['width'],@$v['height'],@$tag['maxwidth'],@$tag['maxheight']);
					foreach(array('width','height') as $var) $v[$var] = $wh[$var];
				}else foreach(array('width','height') as $var) $v[$var] = @$tag['max'.$var];//真正启用了缩略图,直接使用设定的宽高
			}else{
				$v['url'] = $v['url_s'] = view_atmurl($tag['emptyurl']);
				foreach(array('width','height') as $var) $v[$var] = @$tag['max'.$var];
			}
			$rets[] = $v;
		}
	}
	unset($images,$v);
	return $rets;
}
function thumb($url = '',$width = 0,$height = 0){
	global $cms_abs,$cmsurl,$ftp_url,$atm_smallsite;
	if(!$url || !$width || !$height) return $url;
	include_once M_ROOT."./include/upload.cls.php";
	if($ftp_url && preg_match(u_regcode($ftp_url),$url)){
		include_once M_ROOT."./include/http.cls.php";
		include_once M_ROOT."./include/ftp.fun.php";
		//下载原图
		$tempfile = local_atm($url,1);
		mmkdir($tempfile,0,1);
		$m_http = new http;
		$m_http->savetofile($url,$tempfile);
		unset($m_http);
		//生成缩略图
		$m_upload = new cls_upload;
		$m_upload -> image_resize($tempfile,$width,$height,$tempfile.'s/'.$width.'_'.$height.'.jpg');
		@unlink($tempfile);
		unset($m_upload);
		return view_atmurl(str_replace(M_ROOT,'',$tempfile).'s/'.$width.'_'.$height.'.jpg');
	}else{//本地服务器上的文件
		$m_upload = new cls_upload;
		$localfile = local_atm($url);
		$m_upload -> image_resize($localfile,$width,$height,$localfile.'s/'.$width.'_'.$height.'.jpg');
		unset($m_upload);
		return $url.'s/'.$width.'_'.$height.'.jpg';
	}
}

function imagewh($imageurl,$width=0,$height=0,$maxwidth=0,$maxheight=0){
	if(!$width) $width = !$maxwidth ? '100' : $maxwidth;
	if(!$height) $height = !$maxheight ? '100' : $maxheight;
	$maxwidth = !$maxwidth ? $width : $maxwidth;
	$maxheight = !$maxheight ? $height : $maxheight;
	$wh['width'] = $width;
	$wh['height'] = $height;
	if($wh['width'] > $maxwidth || $wh['height'] > $maxheight) {
		$x_ratio = $maxwidth / $wh['width'];
		$y_ratio = $maxheight / $wh['height'];
		if(($x_ratio * $wh['height']) < $maxheight) {
			$wh['height'] = @ceil($x_ratio * $wh['height']);
			$wh['width'] = $maxwidth;
		} else {
			$wh['width'] = @ceil($y_ratio * $wh['width']);
			$wh['height'] = $maxheight;
		}
	}
	return $wh;
}
function arr_catalogs(&$tag){
	global $db,$tblprefix,$sid,$_midarr,$cotypes,$acatalogs;
	$listby = $tag['listby'] == 'ca' ? 0 :  intval(str_replace('co','',$tag['listby']));
	$sourcestr = @$tag[$listby ? "cosource$listby" : 'casource'];
	$nsid = empty($tag['nsid']) ? 0 : intval($tag['nsid']);
	if(!$nsid){
		$nsid = isset($_midarr['sid']) ? $_midarr['sid'] : $sid;
	}elseif($nsid == -1) $nsid = 0;

	$sqlselect = "SELECT *";
	$sqlfrom = " FROM {$tblprefix}".($listby ? 'coclass' : 'catalogs');
	$sqlwhere = ' WHERE '.($listby ? "coid=$listby" : "sid=$nsid");
	
	$_empty = 0;
	if(empty($sourcestr)){
		$sqlwhere .= " AND level=0";
	}elseif($sourcestr == 1){
		if($ids = array_filter(explode(',',@$tag[$listby ? 'ccids'.$listby : 'caids']))){
			$sqlwhere .= ' AND '.($listby ? 'ccid ' : 'caid ').multi_str($ids);
		}else $_empty = 1;
	}elseif($sourcestr == 2){//激活栏目的子栏目
		if($actid = @$_midarr[$listby ? 'ccid'.$listby : 'caid']){
			$sqlwhere .= " AND pid=$actid";
		}else $_empty = 1;
	}elseif($sourcestr == 3){
		if(!empty($tag['wherestr'])){
			if(empty($tag['isfunc'])){
				$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
			}else{
				$v = @func_sqlstr($tag['wherestr']);
				$v && $sqlwhere .= " AND $v";
			}
		}
	}elseif($sourcestr == 4){
		$sqlwhere .= " AND level=1";
	}elseif($sourcestr == 5){
		$sqlwhere .= " AND level=2";
	}
	if($_empty) return array();

	$sqllimit = ' LIMIT '.(empty($tag['startno']) ? 0 : $tag['startno']).','.(empty($tag['limits']) ? 10 : $tag['limits']);
	$sqlorder = empty($tag['orderstr']) ? " ORDER BY trueorder ASC" : " ORDER BY $tag[orderstr]";
	
	$rets = array();
	$query = $db->query($sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit);
	while($row = $db->fetch_array($query)){
		parse_str(cnstr($_midarr),$midarr);
		if(isset($midarr['caid']) && @$tag['cainherit'] != 'active') unset($midarr['caid']);
		foreach($cotypes as $k => $v){
			if(isset($midarr['ccid'.$k]) && @$tag['coinherit'.$k] != 'active') unset($midarr['ccid'.$k]);
		}
		if(!$listby){
			foreach($cotypes as $k => $v){
				if($v['sortable'] && !empty($tag['coinherit'.$k]) && is_numeric($tag['coinherit'.$k])) $midarr['ccid'.$k] = $tag['coinherit'.$k];
			}
			unset($midarr['caid']);
			$midarr['caid'] = $row['caid'];
		}else{
			$coid = $listby;
			if(!empty($tag['cainherit']) && is_numeric($tag['cainherit'])) $midarr['caid'] = $tag['cainherit'];
			foreach($cotypes as $k => $v){
				(($k != $coid) && $v['sortable'] && !empty($tag['coinherit'.$k]) && is_numeric($tag['coinherit'.$k])) && $midarr['ccid'.$k] = $tag['coinherit'.$k];
			}
			unset($midarr['ccid'.$coid]);
			$midarr['ccid'.$coid] = $row['ccid'];
		}
		if(!empty($tag['urlmode']) && !empty($midarr[$tag['urlmode']])) $midarr = array_merge(array($tag['urlmode'] => $midarr[$tag['urlmode']]),$midarr);
	
		$cnstr = cnstr($midarr);
		$row = cn_parse($cnstr,$nsid,$listby);//??????????????????????????????
		$cnode = cnodearr($cnstr,$row['sid']);
		re_cnode($row,$cnstr,$cnode);
		unset($cnode,$midarr);

		$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
		$rets[] = $row;
	}
	return $rets;
}
function arr_mccatalogs(&$tag){
	global $db,$tblprefix,$sid,$_midarr,$cotypes,$acatalogs;
	$listby = $tag['listby'] == 'ca' ? 0 :  intval(str_replace('co','',$tag['listby']));
	$sourcestr = @$tag[$listby ? "cosource$listby" : 'casource'];
	$nsid = empty($tag['nsid']) ? 0 : intval($tag['nsid']);

	$sqlselect = "SELECT *";
	$sqlfrom = " FROM {$tblprefix}".($listby ? 'coclass' : 'catalogs');
	$sqlwhere = $listby ? " AND coid=$listby" : ($nsid ? ($nsid > 0 ? " AND sid=$nsid" : ' AND sid=0') : '');
	
	$_empty = 0;
	if(empty($sourcestr)){
		$sqlwhere .= " AND level=0";
	}elseif($sourcestr == 1){
		if($ids = array_filter(explode(',',@$tag[$listby ? 'ccids'.$listby : 'caids']))){
			$sqlwhere .= ' AND '.($listby ? 'ccid ' : 'caid ').multi_str($ids);
		}else $_empty = 1;
	}elseif($sourcestr == 2){//激活栏目的子栏目
		if($actid = @$_midarr[$listby ? 'ccid'.$listby : 'caid']){
			$sqlwhere .= " AND pid=$actid";
		}else $_empty = 1;
	}elseif($sourcestr == 3){
		if(!empty($tag['wherestr'])){
			if(empty($tag['isfunc'])){
				$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
			}else{
				$v = @func_sqlstr($tag['wherestr']);
				$v && $sqlwhere .= " AND $v";
			}
		}
	}elseif($sourcestr == 4){
		$sqlwhere .= " AND level=1";
	}elseif($sourcestr == 5){
		$sqlwhere .= " AND level=2";
	}
	if($_empty) return array();
	$sqlwhere = $sqlwhere ? ' WHERE '.substr($sqlwhere,5) : '';

	$sqllimit = ' LIMIT 0,'.(empty($tag['limits']) ? 10 : $tag['limits']);
	$sqlorder = empty($tag['orderstr']) ? " ORDER BY trueorder ASC" : " ORDER BY $tag[orderstr]";
	
	$rets = array();
	$query = $db->query($sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit);
	while($row = $db->fetch_array($query)){
		$row = array_merge($row,mcnodearr($listby ? 'ccid'.$listby.'='.$row['ccid'] : 'caid='.$row['caid']));
		$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
		$rets[] = $row;
	}
	return $rets;
}
function arr_usergroups(&$tag){
	$rets = array();
	if(!($gtid = @$tag['listby']) || !($usergroups = read_cache('usergroups',$gtid))) return $rets;
	if(!empty($tag['ugsource'.$gtid]) && empty($tag['ugids'.$gtid])) return $rets;
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$i = 1;
	foreach($usergroups as $k => $v){
		if($i > $limits) break;
		if(empty($tag['ugsource'.$gtid]) || in_array($k,explode(',',$tag['ugids'.$gtid]))){
			$rets[] = array('grouptype'.$gtid => $k,'cname' => $v['cname'],'sn_row' => $i,) + mcnodearr('ugid'.$gtid.'='.$k);
			$i ++;
		}
	}
	return $rets;
}
function arr_matypes(&$tag){
	global $matypes;
	load_cache('matypes');
	$rets = array();
	if(!empty($tag['source']) && empty($tag['matids'])) return $rets;
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$i = 1;
	foreach($matypes as $k => $v){
		if($i > $limits) break;
		if(empty($tag['source']) || in_array($k,explode(',',$tag['matids']))){
			$rets[] = array('matid' => $k,'cname' => $v['cname'],'sn_row' => $i,) + mcnodearr('matid='.$k);
			$i ++;
		}
	}
	return $rets;
}

function one_cnmod(&$tag){//应该可以传送从上级传过来的相关参数,以及当前指定类目的参数
	global $acatalogs,$_midarr;
	$item = array();
	$coid = empty($tag['cnsource']) ? 0 : max(0,intval($tag['cnsource']));
	if($coid){//指定类系
		$ccid = empty($tag['cnid']) ? (empty($_midarr['ccid'.$coid]) ? 0 : $_midarr['ccid'.$coid]) : max(0,intval($tag['cnid']));
		if(!empty($tag['level'])){
			$coclasses = read_cache('coclasses',$coid);
			$ccid = cn_upid($ccid,$coclasses,$tag['level'] - 1);
			unset($coclasses);
		}
		if($ccid) $item = read_cache('coclass',$coid,$ccid);
	}else{//指定栏目
		$caid = empty($tag['cnid']) ? (empty($_midarr['caid']) ? 0 : $_midarr['caid']) : max(0,intval($tag['cnid']));
		if(!empty($tag['level'])) $caid = cn_upid($caid,$acatalogs,$tag['level'] - 1);
		if($caid) $item = read_cache('catalog',$caid,'',@$acatalogs[$caid]['sid']);
	}
	return $item;
}
function one_cnode(&$tag){
	global $acatalogs,$cotypes,$sid,$_midarr;

	$nsid = empty($tag['nsid']) ? 0 : intval($tag['nsid']);
	if(!$nsid){
		$nsid = isset($_midarr['sid']) ? $_midarr['sid'] : $sid;
	}elseif($nsid == -1) $nsid = 0;

	parse_str(cnstr($_midarr),$midarr);
	if(isset($midarr['caid']) && @$tag['casource'] != 'active') unset($midarr['caid']);
	foreach($cotypes as $k => $v){
		if(isset($midarr['ccid'.$k]) && @$tag['cosource'.$k] != 'active') unset($midarr['ccid'.$k]);
	}

	$listby = $tag['listby'] == 'ca' ? 0 : intval(str_replace('co','',$tag['listby']));
	if(!$listby){
		foreach($cotypes as $k => $cotype){//先处理非列表项目
			if($cotype['sortable'] && !empty($tag['cosource'.$k]) && is_numeric($tag['cosource'.$k])) $midarr['ccid'.$k] = $tag['cosource'.$k];
		}
		//获取列表的caid
		$caid = @$tag['casource'] == 'active' ? @$_midarr['caid'] : @$tag['casource'];
		if(!empty($tag['level'])) $caid = cn_upid($caid,$acatalogs,$tag['level'] - 1);
		if(!($caid = max(0,intval($caid))) || empty($acatalogs[$caid])) return array();
		unset($midarr['caid']);
		$midarr['caid'] = $caid;
		$nsid = $acatalogs[$caid]['sid'];
	}else{
		$coid = $listby;
		if(!empty($tag['casource']) && is_numeric($tag['casource'])) $midarr['caid'] = $tag['casource'];
		foreach($cotypes as $k => $cotype){
			if(($k != $coid) && $cotype['sortable'] && !empty($tag['cosource'.$k]) && is_numeric($tag['cosource'.$k])) $midarr['ccid'.$k] = $tag['cosource'.$k];
		}
		$ccid = @$tag['cosource'.$coid] == 'active' ? @$_midarr['ccid'.$coid] : @$tag['cosource'.$coid];
		if(!empty($tag['level'])){
			$coclasses = read_cache('coclasses',$coid);
			$ccid = cn_upid($ccid,$coclasses,$tag['level'] - 1);
			unset($coclasses);
		}
		if(!($ccid = max(0,intval($ccid)))) return array();
		unset($midarr['ccid'.$coid]);
		$midarr['ccid'.$coid] = $ccid;
	}
	if(!empty($tag['urlmode']) && !empty($midarr[$tag['urlmode']])) $midarr = array_merge(array($tag['urlmode'] => $midarr[$tag['urlmode']]),$midarr);//强制频道

	$cnstr = cnstr($midarr);
	$item = cn_parse($cnstr,$nsid,$listby);//子站id会在其中转换
	$cnode = cnodearr($cnstr,$item['sid']);
	re_cnode($item,$cnstr,$cnode);

	return $item;
}
function one_mcnode(&$tag){
	if(empty($tag['cnsource']) || empty($tag['cnid'])) return array();
	return m_cnparse($tag['cnsource'].'='.$tag['cnid']) + mcnodearr($tag['cnsource'].'='.$tag['cnid']);
}

function arr_commus(&$tag,$type='c'){
	global $db;
	$rets = array();
	if($sqlstr = cu_sqlstr($tag,$type)){
		$query = $db->query($sqlstr);
		while($row = $db->fetch_array($query)){
			if(($u = read_cache('commu',$tag['cuid'])) && in_array($u['cclass'],array('comment','reply','offer'))) cu_checkend($row,$u['cclass']);
			$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$rets[] = $row;
		}
	}
	return $rets;
}
function cu_sqlstr(&$tag,$type='c',$iscount=0){
	global $tblprefix,$timestamp,$_midarr,$_mp;
	if(!($commu = read_cache('commu',$tag['cuid']))) return false;
	$sqlorder = $sqllimit = '';
	$sqlselect = $iscount ? "SELECT COUNT(*)" : "SELECT *";
	$sqlfrom = " FROM {$tblprefix}".$commu['cclass'].'s';

	if($tag['idsource'] == 'aid'){
		if(empty($_midarr['aid'])) return false;
		$sqlwhere = " WHERE aid=".$_midarr['aid'];
	}elseif($tag['idsource'] == 'mid'){
		if(empty($_midarr['mid'])) return false;
		$sqlwhere = " WHERE mid=".$_midarr['mid'];
		if(!empty($tag['ucsource']) && !empty($_midarr['ucid'])) $sqlwhere .= " AND ucid=".$_midarr['ucid'];
	}elseif($tag['idsource'] == 'tomid'){
		if(empty($_midarr['mid'])) return false;
		$sqlwhere = " WHERE tomid=".$_midarr['mid'];
	}elseif($tag['idsource'] == 'other') $sqlwhere = '';

	if(in_array($commu['cclass'],array('comments','replys')) && !empty($tag['cuid'])) $sqlwhere .= " AND cuid = '$tag[cuid]'";
	if(!empty($tag['validperiod']) && in_array($commu['cclass'],array('offer'))) $sqlwhere .= " AND (enddate='0' OR enddate>'$timestamp')";
	if(!empty($tag['checked']) && $commu['cclass'] != 'purchase') $sqlwhere .= " AND checked <>'0'";
	if(!empty($tag['indays'])) $sqlwhere .= " AND createdate > $timestamp - 86400 * $tag[indays]";
	if(!empty($tag['outdays'])) $sqlwhere .= " AND createdate < $timestamp - 86400 * $tag[outdays]";
	if(!empty($tag['wherestr'])){
		if(empty($tag['isfunc'])){
			$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
		}else{
			$v = @func_sqlstr($tag['wherestr']);
			$v && $sqlwhere .= " AND $v";
		}
	}
	if($sqlwhere && $tag['idsource'] == 'other') $sqlwhere = ' WHERE '.substr($sqlwhere,5);
	
	if(!$iscount){
		$sqlorder = empty($tag['orderstr']) ? " ORDER BY createdate ".(empty($tag['orderby']) ? "DESC" : "ASC") : " ORDER BY ".$tag['orderstr'];
		$limits = empty($tag['limits']) ? 10 : $tag['limits'];
		$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;
	}
	
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function one_context(&$tag){
	global $cotypes,$db,$tblprefix,$_midarr,$sid;
	$ret = array();
	if(empty($_midarr['aid'])) return $ret;
	$sqlstr = "SELECT * FROM {$tblprefix}archives WHERE sid='$sid' AND aid".(empty($tag['next']) ? "<" : ">").$_midarr['aid']." AND checked=1";
	$vararr = array('chid' => 'chid','caid' => 'caid','mid' => 'mid');
	foreach($cotypes as $k => $v) if($v['sortable']) $vararr['ccid'.$k] = "ccid$k";
	foreach($vararr as $k => $v) if(!empty($tag[$k]) && !empty($_midarr[$k])) $sqlstr .= " AND $v=".$_midarr[$k];
	!empty($tag['nocp']) && $sqlstr .= " GROUP BY cpid";
	if($ret = $db->fetch_one($sqlstr." ORDER BY aid ".(empty($tag['next']) ? "DESC" : "ASC")." LIMIT 0,1")){
		$ret['arcurl'] = view_arcurl($ret);
	}
	return $ret ? $ret : array();
}
function one_date(&$tag){
	if(!($datetime = @$tag['tname']) || !($datetime = intval($datetime))) return '';
	$formatstr = '';
	!empty($tag['date']) && $formatstr .= $tag['date'];
	!empty($tag['time']) && $formatstr .= ($formatstr ? ' ' : '').$tag['time'];
	if($formatstr){
		$datetime = @date($formatstr,$datetime);
	}
	return $datetime;
}
function one_field(&$tag){
	global $_midarr;
	$val = @$tag['tname'];
	$typearr = array(
		'archive' => array('','chid'),
		'member' => array('m','mchid'),
		'farchive' => array('f','chid'),
		'marchive' => array('ma','matid'),
		'catalog' => array('ca',''),
		'coclass' => array('cc',''),
		'comment' => array('c',''),
		'purchase' => array('p',''),
		'offer' => array('o',''),
		'reply' => array('r',''),
		'report' => array('b',''),
		'mcomment' => array('mc',''),
		'mreply' => array('mr',''),
		'mreport' => array('mb',''),
		'mflink' => array('ml',''),
	);
	if(!($type = @$typearr[$tag['type']]) || (!$fields = read_cache($type[0].'fields',$type[1] ? $_midarr[$type[1]] : '')) || !($field = @$fields[$tag['fname']])) return $val;
	if(in_array($field['datatype'],array('mselect','select',))){
		$tmp = explode("\n",$field['innertext']);
		$arr = array();
		foreach($tmp as $v){
			$t = explode('=',str_replace(array("\r","\n"),'',$v));
			$t[1] = isset($t[1]) ? $t[1] : $t[0];
			$arr[$t[0]] = $t[1];
		}
		$multi = $field['datatype'] == 'mselect' ? 1 : 0;
	}elseif($field['datatype'] == 'cacc'){
		$arr = empty($field['length']) ? read_cache('acatalogs') : read_cache('coclasses',$field['length']);
		foreach($arr as $k => $v) $arr[$k] = $v['title'];
		$multi = empty($field['max']) ? 0 : 1;
	}else return $val;
	if($multi){
		$vals = explode($field['datatype'] == 'cacc' ? ',' : "\t",$val);
		$ret = '';
		foreach($vals as $k) $ret .= isset($arr[$k]) ? $arr[$k].' ' : '';
		return $ret;
	}else return @$arr[$val];
}

function one_farchive(&$tag){
	global $db,$tblprefix,$timestamp,$_midarr;
	$aid = $tag['aid'] ? $tag['aid'] : (empty($_midarr['aid']) ? 0 : $_midarr['aid']);
	$ret = array();
	if(!$aid) return $ret;
	//需要限制为有效信息
	include_once M_ROOT."./include/farchive.cls.php";
	$arc = new cls_farchive;
	if(!$arc->arcid($aid)) return $ret;
	if(($arc->archive['startdate'] && $arc->archive['startdate'] > $timestamp) || ($arc->archive['enddate'] && $arc->archive['enddate'] < $timestamp)) return $ret;
	$ret = $arc->archive;
	unset($arc);
	return $ret;
}
function arr_farchives(&$tag,$type='c'){
	global $db;
	$rets = array();
	if($sqlstr = farc_sqlstr($tag,$type)){
		$query = $db->query($sqlstr);
		while($row = $db->fetch_array($query)){
			$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$row['arcurl'] = view_farcurl($row['aid'],$row['arcurl']);
			arr_tag2atm($row,'f');
			$rets[] = $row;
		}
	}
	return $rets;
}
function farc_sqlstr(&$tag,$type='c',$iscount=0){
	global $tblprefix,$timestamp,$fcatalogs,$fchannels,$_midarr,$_mp;
	if(empty($tag['casource'])) return '';
	$chid = @$fcatalogs[$tag['casource']]['chid'];
	if(empty($fchannels[$chid])) return '';
	$customtable = "farchives_$chid";
	$sqlorder = $sqllimit = '';
	$sqlwhere = " WHERE a.checked=1 AND a.fcaid=".$tag['casource'];
	$sqlselect = $iscount ? "SELECT COUNT(*)" : "SELECT a.*,c.*";
	$sqlfrom = " FROM {$tblprefix}farchives a LEFT JOIN {$tblprefix}$customtable c ON c.aid=a.aid";
	
	if(!empty($tag['validperiod'])) $sqlwhere .= " AND a.startdate<$timestamp AND (a.enddate=0 OR a.enddate>$timestamp)";
	if(!empty($tag['wherestr'])){
		if(empty($tag['isfunc'])){
			$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
		}else{
			$v = @func_sqlstr($tag['wherestr']);
			$v && $sqlwhere .= " AND $v";
		}
	}
	if(!$iscount){
		!empty($tag['orderby']) && $sqlorder .= ($sqlorder ? ',' : ''). 'a.'.str_replace('_',' ',$tag['orderby']);
		!empty($tag['orderstr']) && $sqlorder .= ($sqlorder ? ',' : '').$tag['orderstr'];
		$sqlorder = empty($sqlorder) ? " ORDER BY a.aid DESC" : " ORDER BY $sqlorder";
		$limits = empty($tag['limits']) ? 10 : $tag['limits'];
		$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;
	}
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function one_freeurl(&$tag){
	global $freeinfos,$cms_abs,$subsites,$infohtmldir,$_midarr;
	load_cache('freeinfos');
	if(empty($tag['fid']) || empty($freeinfos[$tag['fid']])) return '#';
	$fid = $tag['fid'];
	$sid = $freeinfos[$fid]['sid'];
	if(empty($freeinfos[$fid]['arcurl'])){
		$freeurl = $cms_abs."info.php?fid=$fid";
	}else $freeurl = ($sid ? view_url($subsites[$sid]['dirname'].'/') : view_url($infohtmldir.'/')).$freeinfos[$fid]['arcurl'];
	unset($fid,$sid);
	return $freeurl;
}
function one_fromid(&$tag){
	global $acatalogs,$subsites,$_midarr,$repugrades;
	if(empty($tag['type'])) return '';
	if(empty($tag['idsource'])){
		if(empty($_midarr[$tag['type']])) return '';
		$id = $_midarr[$tag['type']];
	}else $id = max(0,intval($tag['idsource']));
	if(!$id) return '';
	if($tag['type'] == 'chid'){
		return read_cache('channel',$id);
	}elseif($tag['type'] == 'mchid'){
		return read_cache('mchannel',$id);
	}elseif($tag['type'] == 'caid'){
		if($ret = @$acatalogs[$id]) return read_cache('catalog',$id,'',$ret['sid']);
	}elseif($tag['type'] == 'sid'){
		return @$subsites[$id];
	}elseif($tag['type'] == 'matid'){
		return read_cache('matype',$id);
	}elseif($tag['type'] == 'rgid'){
		return @$repugrades[$id];
	}elseif(in_str('ccid',$tag['type'])){
		if(!$coid = max(0,intval(str_replace('ccid','',$tag['type'])))) return '';
		return read_cache('coclass',$coid,$id);
	}elseif(in_str('grouptype',$tag['type'])){
		if(!$gtid = max(0,intval(str_replace('grouptype','',$tag['type'])))) return '';
		return read_cache('usergroup',$gtid,$id);
	}
}
function one_marchive(&$tag){
	global $db,$tblprefix,$_midarr;
	if(!$matid = @$tag['matid']) return '';
	$mid = !empty($tag['mid']) ? $tag['mid'] : (empty($_midarr['mid']) ? 0 : $_midarr['mid']);
	if(!$mid) return '';
	$arc = new cls_marchive;
	if(!$arc->arc_mid($mid,$matid)) return '';
	$ret = $arc->archive;
	unset($arc);
	marc_parse($ret);
	return $ret;
}
function arr_marchives(&$tag,$type='c'){
	global $db;
	$rets = array();
	if($sqlstr = marc_sqlstr($tag,$type)){
		$query = $db->query($sqlstr);
		while($row = $db->fetch_array($query)){
			marc_parse($row);
			$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$rets[] = $row;
		}
	}
	return $rets;
}


function marc_sqlstr(&$tag,$type='c',$iscount=0){
	global $channels,$acatalogs,$cotypes,$tblprefix,$timestamp,$_midarr,$_mp;
	if(!$matid = @$tag['matid']) return '';

	$sqlorder = $sqllimit = '';
	$sqlselect = $iscount ? "SELECT COUNT(*)" : "SELECT ma.*";
	$sqlwhere = " WHERE ma.checked=1";
	$sqlfrom = " FROM {$tblprefix}marchives_$matid ma LEFT JOIN {$tblprefix}members m ON m.mid=ma.mid";
	if(!$iscount){
		$sqlselect .= ",m.mchid";
	}
	if(!empty($tag['wherestr'])){
		if(empty($tag['isfunc'])){
			$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
		}else{
			$v = @func_sqlstr($tag['wherestr']);
			$v && $sqlwhere .= " AND $v";
		}
	}
	if(!$iscount){
		!empty($tag['orderby']) && $sqlorder .= ($sqlorder ? ',' : '').'ma.'.str_replace('_',' ',$tag['orderby']);
		!empty($tag['orderstr']) && $sqlorder .= ($sqlorder ? ',' : '').$tag['orderstr'];
		$sqlorder = empty($sqlorder) ? " ORDER BY ma.maid DESC" : " ORDER BY $sqlorder";
	
		$limits = empty($tag['limits']) ? 10 : $tag['limits'];
		$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;
	}
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function arr_mcatalogs(&$tag){
	global $mcatalogs,$uclasses,$_midarr;
	$rets = array();
	
	if($tag['listby'] == 'ca'){//全部栏目
		if(empty($tag['casource'])){
			foreach($mcatalogs as $k => $v) $rets[] = $v;
		}elseif($tag['casource'] == 1){//指定栏目
			if(!empty($tag['caids'])){
				$caids = explode(',',$tag['caids']);
				foreach($mcatalogs as $k => $v) if(in_array($k,$caids)) $rets[] = $v;
			}
		}
		foreach($rets as $k => $v){
			foreach(array(0,1) as $x) $v['indexurl'.($x ? $x : '')] = mcn_url($v['mcaid'],0,$x);
			$v['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$rets[$k] = $v;
		}
	}elseif($tag['listby'] == 'uc'){
		if(!empty($_midarr['mcaid'])){//一定必须是激活栏目下的分类
			foreach($uclasses as $k => $v){
				if($v['mcaid'] == $_midarr['mcaid']){
					foreach(array(0,1) as $x) $v['indexurl'.($x ? $x : '')] = mcn_url($v['mcaid'],$v['ucid'],$x);
					$v['sn_row'] = $i = empty($i) ? 1 : ++ $i;
					$rets[] = $v;
				}
			}
		}
	}
	return $rets;
}
function arr_mcommus(&$tag,$type='c'){
	global $db;
	$rets = array();
	if($sqlstr = mcu_sqlstr($tag,$type)){
		$query = $db->query($sqlstr);
		while($row = $db->fetch_array($query)){
			$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$rets[] = $row;
		}
	}
	return $rets;
}

function mcu_sqlstr(&$tag,$type='c',$iscount=0){
	global $tblprefix,$_midarr,$_mp;
	if(!($mcommu = read_cache('mcommu',$tag['cuid']))) return false;
	$sqlorder = $sqllimit = '';
	$sqlselect = $iscount ? "SELECT COUNT(*)" : "SELECT *";
	$sqlfrom = " FROM {$tblprefix}m".$mcommu['cclass'].'s';
	if($tag['idsource'] == 'mid'){
		if(empty($_midarr['mid'])) return false;
		$sqlwhere = " WHERE mid=".$_midarr['mid'];
	}elseif($tag['idsource'] == 'fromid'){
		if(empty($_midarr['mid'])) return false;
		$sqlwhere = " WHERE fromid=".$_midarr['mid'];
	}
	if(!empty($tag['ucsource']) && !empty($_midarr['ucid'])) $sqlwhere .= " AND ucid=".$_midarr['ucid'];
	if(in_array($mcommu['cclass'],array('comment','reply'))) " AND cuid = '$tag[cuid]'";
	if(!empty($tag['checked'])) $sqlwhere .= " AND checked = '1'";
	if(!empty($tag['indays'])) $sqlwhere .= " AND createdate > $timestamp - 86400 * $tag[indays]";
	if(!empty($tag['outdays'])) $sqlwhere .= " AND createdate < $timestamp - 86400 * $tag[outdays]";
	if(!empty($tag['wherestr'])){
		if(empty($tag['isfunc'])){
			$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
		}else{
			$v = @func_sqlstr($tag['wherestr']);
			$v && $sqlwhere .= " AND $v";
		}
	}

	if(!$iscount){
		$sqlorder = " ORDER BY ".(empty($tag['orderstr']) ? '' : $tag['orderstr'].',')."createdate ".(empty($tag['orderby']) ? "DESC" : "ASC");
	
		$limits = empty($tag['limits']) ? 10 : $tag['limits'];
		$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;
	}
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function arr_members(&$tag,$type='c'){
	global $db,$grouptypes;
	$rets = array();
	if($sqlstr = mem_sqlstr($tag,$type)){
		$query = $db->query($sqlstr);
		while($row = $db->fetch_array($query)){
			foreach($grouptypes as $k => $v){
				$row['grouptype'.$k.'name'] = '';
				if(!empty($row['grouptype'.$k])){
					$usergroups = read_cache('usergroups',$k);
					$row['grouptype'.$k.'name'] = $usergroups[$row['grouptype'.$k]]['cname'];
				}
			}
			$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$rets[] = $row;
		}
		unset($usergroups,$v);
	}
	return $rets;
}
function one_memscount(&$tag){
	global $db;
	$ret['counts'] = 0;
	if($sqlstr = mem_sqlstr($tag,'c',1)) $ret['counts'] = $db->result_one($sqlstr);
	return $ret;

}
function mem_sqlstr(&$tag,$type='c',$iscount=0){
	global $grouptypes,$tblprefix,$timestamp,$mchannels,$acatalogs,$cotypes,$_midarr,$_mp;
	$sqlorder = $sqllimit = '';
	$sqlselect = $iscount ? "SELECT COUNT(*)" : "SELECT m.*,s.*";
	$sqlwhere = " WHERE m.checked=1";
	$sqlfrom = " FROM {$tblprefix}members m LEFT JOIN {$tblprefix}members_sub s ON m.mid=s.mid";
	foreach($grouptypes as $k => $v){
		!empty($tag['ugid'.$k]) && $sqlwhere .= " AND m.grouptype$k='".$tag['ugid'.$k]."'";
	}

	if(!empty($tag['casource'])){
		$caids = array();
		if($tag['casource'] == '1'){//如果指定为空，返回空集
			if(empty($tag['caids'])) return false;
			$caids = explode(',',$tag['caids']);
			if(empty($caids)) return false;
		}elseif($tag['casource'] == '2'){
			if(!empty($_midarr['caid'])) $caids[] = $_midarr['caid'];
		}
		if(!empty($caids) && !empty($tag['caidson'])){
			$tempsons = array();
			foreach($caids as $caid) $tempsons = son_ids($acatalogs,$caid,$tempsons);
			$caids = array_unique(array_merge($caids,$tempsons));
		}
		if(!empty($caids)) $sqlwhere .= " AND m.caid ".multi_str($caids);
	}
	foreach($cotypes as $k => $cotype){
		$ccids = array();	
		if(!empty($tag['cosource'.$k])){
			$coclasses = read_cache('coclasses',$k);
			if($tag['cosource'.$k] == '1'){//手动选择
				if(empty($tag['ccids'.$k])) return false;
				$ccids = explode(',',$tag['ccids'.$k]);
				if(empty($ccids)) return false;
			}elseif($tag['cosource'.$k] == '2'){//激活
				if(!empty($_midarr['ccid'.$k])){
					if(empty($coclasses[$_midarr['ccid'.$k]])) return false;
					$ccids[] = $_midarr['ccid'.$k];
				}
			}
			if(!empty($ccids) && !empty($tag['ccidson'.$k])){
				$tempsons = array();
				foreach($ccids as $ccid) $tempsons = son_ids($coclasses,$ccid,$tempsons);
				$ccids = array_unique(array_merge($ccids,$tempsons));
			}
			if(!empty($ccids)) $sqlwhere .= " AND m.ccid$k ".multi_str($ccids);
		}
	}
	if(!empty($tag['chsource'])){
		$mchid = 0;
		if($tag['chsource'] == 1){
			if(empty($_midarr['mchid'])) return false;
			$sqlwhere .= " AND m.mchid='".$_midarr['mchid']."'";
			$mchid = $_midarr['mchid'];
		}elseif($tag['chsource'] == 2){
			if(empty($tag['chids'])) return false;
			$tchids = explode(',',$tag['chids']);
			$sqlwhere .= " AND m.mchid ".multi_str($tchids);
			if(count($tchids) == 1) $mchid = $tchids[0];
		}
		if(!empty($tag['detail']) && $mchid && @$mchannels[$mchid]){
			$sqlfrom .= " LEFT JOIN {$tblprefix}members_$mchid c ON c.mid=m.mid";
			!$iscount && $sqlselect .= ",c.*";
		}
	}
	!empty($tag['indays']) && $sqlwhere .= " AND m.regdate > $timestamp - 86400 * $tag[indays]";
	!empty($tag['outdays']) && $sqlwhere .= " AND m.regdate < $timestamp - 86400 * $tag[outdays]";
	isset($tag['clicks']) && $sqlwhere .= " AND m.clicks > $tag[clicks]";
	isset($tag['onlinetime']) && $sqlwhere .= " AND m.onlinetime > $tag[onlinetime]";
	isset($tag['msclicks']) && $sqlwhere .= " AND s.msclicks > $tag[praises]";
	isset($tag['comments']) && $sqlwhere .= " AND s.comments > $tag[comments]";
	isset($tag['checks']) && $sqlwhere .= " AND s.checks > $tag[checks]";
	isset($tag['purchases']) && $sqlwhere .= " AND s.purchases > $tag[purchases]";
	isset($tag['answers']) && $sqlwhere .= " AND s.answers > $tag[answers]";
	isset($tag['credits']) && $sqlwhere .= " AND s.credits > $tag[credits]";
	if(!empty($tag['wherestr'])){
		if(empty($tag['isfunc'])){
			$sqlwhere .= " AND ".stripslashes($tag['wherestr']);
		}else{
			$v = @func_sqlstr($tag['wherestr']);
			$v && $sqlwhere .= " AND $v";
		}
	}
	if(!$iscount){
		!empty($tag['orderby']) && $sqlorder .= str_replace('_',' ',$tag['orderby']);
		!empty($tag['orderstr']) && $sqlorder .= ($sqlorder ? ',' : '').$tag['orderstr'];
		$sqlorder = empty($sqlorder) ? " ORDER BY m.mid ASC" : " ORDER BY $sqlorder";
	
		$limits = empty($tag['limits']) ? 10 : $tag['limits'];
		$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;
	}
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function mpnav($temparr=array(),$s_num=0){
	global $mpstart,$mpend,$mppre,$mpnext,$mppage,$mpcount,$mpacount;
	//$temparr中需要的参数:static,simple,length,pcount,acount,nowpage,durlpre,surlpre,s_num
	extract($temparr,EXTR_OVERWRITE);
	$mppage = $nowpage;
	$mpcount = $pcount;
	$mpacount = $acount;
	$mpstart = $mpend = $mppre = $mpnext = '#';
	if($pcount < 2) return '';
	$static = empty($static) ? 0 : $static;
	$simple = empty($simple) ? 0 : $simple;
	$length = empty($length) ? 10 : $length;
	$s_num = empty($s_num) ? $pcount : min($pcount,$s_num);

	$offset = 2;
	if($length > $pcount){
		$from = 1;
		$to = $pcount;
	}else{
		$from = $nowpage - $offset;
		$to = $from + $length - 1;
		if($from < 1){
			$to = $nowpage + 1 - $from;
			$from = 1;
			if($to - $from < $length) $to = $length;
		}elseif($to > $pcount){
			$from = $pcount - $length + 1;
			$to = $pcount;
		}
	}
	$mpnav = '';
	$mpstart = m_parseurl($static ? $surlpre : $durlpre,array('page' => 1));
	$mpend = m_parseurl($static && $pcount <= $s_num ? $surlpre : $durlpre,array('page' => $pcount));
	$mppre = $nowpage > 1 ? m_parseurl($static && ($nowpage - 1) <= $s_num ? $surlpre : $durlpre,array('page' => $nowpage - 1)) : '#';
	$mpnext = $nowpage < $pcount ? m_parseurl($static && ($nowpage + 1) <= $s_num ? $surlpre : $durlpre,array('page' => $nowpage + 1)) : '#';
	if(defined('WAP_MODE')){
		if($nowpage - $offset > 1 && $pcount > $length) $mpnav .= '<a href="'.$mpstart.'">|&lt;</a> ';
		if($nowpage > 1 && !$simple) $mpnav .= '<a href="'.$mppre.'">&lt;&lt;</a> ';
		for($i = $from; $i <= $to; $i++){
			$mpnav .= $i == $nowpage ? '<a>'.$i.'</a> ' :  '<a href="'.m_parseurl($static && $i <= $s_num ? $surlpre : $durlpre,array('page' => $i)).'">'.$i.'</a> ';
		}
		if($nowpage < $pcount && !$simple) $mpnav .= '<a href="'.$mpnext.'">&gt;&gt;</a> ';
		if($to < $pcount) $mpnav .= '<a href="'.$mpend.'">&gt;|</a> ';
		$mpnav = $mpnav ? (!$simple ? '<a> '.$acount.' </a> <a> '.$nowpage.'/'.$pcount.' </a> ' : '').$mpnav : '';
	}else{
		if($nowpage - $offset > 1 && $pcount > $length) $mpnav .= '<a href="'.$mpstart.'" class="p_redirect">|<</a>';
		if($nowpage > 1 && !$simple) $mpnav .= '<a href="'.$mppre.'" class="p_redirect"><<</a>';
		for($i = $from; $i <= $to; $i++){
			$mpnav .= $i == $nowpage ? '<a class="p_curpage">'.$i.'</a>' :  '<a href="'.m_parseurl($static && $i <= $s_num ? $surlpre : $durlpre,array('page' => $i)).'" class="p_num">'.$i.'</a>';
		}
		if($nowpage < $pcount && !$simple) $mpnav .= '<a href="'.$mpnext.'" class="p_redirect">>></a>';
		if($to < $pcount) $mpnav .= '<a href="'.$mpend.'" class="p_redirect">>|</a>';
		$mpnav = $mpnav ? '<div class="p_bar">'.(!$simple ? '<a class="p_total">&nbsp;'.$acount.'&nbsp;</a><a class="p_pages">&nbsp;'.$nowpage.'/'.$pcount.'&nbsp;</a>' : '').$mpnav.'</div>' : '';
	}
	return $mpnav;
}
function arr_nownav(&$tag){
	global $_midarr;
	parse_str(cnstr($_midarr),$idsarr);
	if(!empty($tag['urlmode']) && !empty($idsarr[$tag['urlmode']])) $idsarr = array_merge(array($tag['urlmode'] => $idsarr[$tag['urlmode']]),$idsarr);//强制频道
	$navstr = '';
	$rets = $midarr = array();
	foreach($idsarr as $k => $v){
		$coid = $k == 'caid' ? 0 : intval(str_replace('ccid','',$k));
		$pids = pccidsarr($v,$coid,1);
		foreach($pids as $id){
			$midarr[$k] = $id;
			if($item = onenav($tag,$midarr,$coid ? $coid : 'ca')){
				$item['sn_row'] = $i = empty($i) ? 1 : ++ $i;
				$rets[] = $item;
			}
		}
	}
	unset($idsarr,$midarr,$pids,$item);
	return $rets;
}
function onenav(&$tag,$midarr,$mode='ca'){
	global $catalogs,$cotypes,$sid;
	$item = $mode == 'ca' ? read_cache('catalog',$midarr['caid'],'',$sid) : read_cache('coclass',$mode,$midarr['ccid'.$mode]);
	$cnstr = cnstr($midarr);
	if(!($cnode = cnodearr($cnstr,$sid))){
		return '';
	}else{
		re_cnode($item,$cnstr,$cnode);
		if(!cn_tplname($cnstr,$cnode,0) && cn_tplname($cnstr,$cnode,1)) $item['indexurl'] = $item['indexurl1'];
	}
	return $item;
}
function one_odeal($content,&$tag){
	if(!$content) return '';
	if(!empty($tag['dealhtml'])){
		switch($tag['dealhtml']){
		case 'clearhtml':
			$content = html2text($content);
			break;
		case 'disablehtml':
			$content = mhtmlspecialchars($content);
			break;
		case 'safehtml':
			$content = safestr($content);
			break;
		case 'wapcode':
			$content = html2wml($content);
			break;
		}
	}
	!empty($tag['trim']) && $content = cutstr($content,$tag['trim'],'');
	!empty($tag['badword']) && mbadword($content);
	!empty($tag['wordlink']) && mwordlink($content);
	!empty($tag['face']) && mface($content);
	!empty($tag['nl2br']) && $content = mnl2br($content);
	!empty($tag['randstr']) && $content = preg_replace("/\<br \/\>/e", "randstr()", $content);
	return $content;
}
function mbadword(&$source){
	global $badwords;
	load_cache('badwords');
	!empty($badwords['wsearch']) && $source = preg_replace($badwords['wsearch'],$badwords['wreplace'],$source);
}
function mwordlink(&$source){
	global $wordlinks;
	load_cache('wordlinks');
	if(!empty($wordlinks['swords'])){
		if(preg_match_all("/<.*?>/s", $source, $matchs)){
			$i = 0;
			$matchs = &$matchs[0];
			$source = preg_replace("/<.*?>/se", '":::".$i++.":::"', $source);
			$source = preg_replace($wordlinks['swords'],$wordlinks['rwords'],$source,1);
			$source = preg_replace("/:::(\d+):::/se", '$matchs[$1]', $source);
		}
	}
	return $source;
}
function mface(&$source){
	global $faceicons,$cms_abs;
	load_cache('faceicons');
	if(!empty($faceicons['from'])){
		$tos = array();
		foreach($faceicons['to'] as $v) $tos[] = '<img src="'.$cms_abs.$v.'">';
		$source = str_replace($faceicons['from'],$tos,$source);
		unset($tos);
	}
	return $source;
}

function randstr(){
	$str = '';
	for($i = 0;$i < mt_rand(5,15);$i++)  $str .= chr(mt_rand(0,59)).chr(mt_rand(63,126));
	return mt_rand(0, 1) ? '<br /><span style="display:none">'.$str.'</span>' : '<br /><font style="display:none">'.$str.'</font>';
}
function arr_channels(&$tag){
	global $channels;
	$rets = array();
	if(empty($channels) || (!empty($tag['chsource']) && empty($tag['chids']))) return $rets;
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$i = 1;
	foreach($channels as $k => $v){
		if($i > $limits) break;
		if(empty($tag['chsource']) || (!empty($tag['chsource']) && in_array($k,explode(',',$tag['chids'])))){
			$rets[] = array('chid' => $k,'cname' => $v['cname'],'sn_row' => $i,);
			$i ++;
		}
	}
	return $rets;
}
function arr_mchannels(&$tag){
	global $mchannels;
	$rets = array();
	if(empty($mchannels) || (!empty($tag['chsource']) && empty($tag['chids']))) return $rets;
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$i = 1;
	foreach($mchannels as $k => $v){
		if($i > $limits) break;
		if(empty($tag['chsource']) || (!empty($tag['chsource']) && in_array($k,explode(',',$tag['chids'])))){
			$rets[] = array('mchid' => $k,'title' => $v['cname'],'sn_row' => $i,);
			$i ++;
		}
	}
	return $rets;
}
function arr_subsites(&$tag){
	global $subsites,$cmsname;
	$rets = array();
	$i = 1;
	if(empty($tag['source']) || (($tag['source'] == 2) && is_array($tag['sids']) && in_array('0',$tag['sids']))){
		$rets[] = array('sid' => 0,'siteurl' => view_siteurl(0),'sitename' => $cmsname,'sn_row' => $i);
		$i ++;
	}
	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	foreach($subsites as $k => $v){
		if($i > $limits) break;
		if(($tag['source'] < 2) || (($tag['source'] == 2) && in_array($k,explode(',',$tag['sids'])))){
			$rets[] = array('sid' => $k,'siteurl' => view_siteurl($k),'sitename' => $v['sitename'],'sn_row' => $i);
			$i ++;
		}
	}
	return $rets;
}
function arr_keywords(&$tag){
	global $uwordlinks;
	load_cache('uwordlinks');
	$rets = array();
	if(empty($uwordlinks)) return $rets;

	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$temps = @marray_slice($uwordlinks['swords'],0,$limits);
	foreach($temps as $k =>$v){
		$rets[] = array('word' => $v,'wordlink' => $uwordlinks['rwords'][$k]);
	}
	unset($temps,$k,$v);
	return $rets;
}
function arr_vote(&$tag){//投票选项列表
	global $db,$tblprefix,$timestamp,$_midarr;
	$rets = array();
	if(empty($tag['type'])){
		$vid = empty($tag['vid']) ? (empty($_midarr['vid']) ? 0 : $_midarr['vid']) : $tag['vid'];
		if(!$vid) return $rets;
		$vote = $db->fetch_one("SELECT * FROM {$tblprefix}votes WHERE vid='$vid'");
		$query = $db->query("SELECT * FROM {$tblprefix}voptions WHERE vid='$vid' ORDER BY vieworder,vopid");
		while($row = $db->fetch_array($query)){
			$row['input'] = empty($vote['ismulti']) ? "<input type=\"radio\" value=\"".$row['vopid']."\" name=\"vopids[]\">" : "<input type=\"checkbox\" value=\"".$row['vopid']."\" name=\"vopids[]\">";
			$row['percent'] = $vote['totalnum'] ? @round($row['votenum'] / $vote['totalnum'],3) : 0;
			$row['percent'] = ($row['percent'] * 100).'%';
			$rets[] = $row;
		}
	}else{
		if(empty($tag['fname']) || empty($tag['id']))  return $rets;
		include_once(M_ROOT.'include/vote.fun.php');
		$votes = field_votes($tag['fname'],$tag['type'],$tag['id']);
		$vid = empty($_midarr['vid']) ? 0 : $_midarr['vid'];
		if(!$votes || !($vote = $votes[$vid]) || !is_array($vote)) return $rets;
		foreach($vote['options'] as $k => $row){
			$row['input'] = empty($vote['ismulti']) ? "<input type=\"radio\" value=\"".$k."\" name=\"vopids[$vid][]\">" : "<input type=\"checkbox\" value=\"".$k."\" name=\"vopids[$vid][]\">";
			$row['percent'] = $vote['totalnum'] ? @round($row['votenum'] / $vote['totalnum'],3) : 0;
			$row['percent'] = ($row['percent'] * 100).'%';
			$rets[] = $row;
		}
	}
	unset($vote,$row);
	return $rets;
}

function arr_votes(&$tag,$type='c'){
	global $db;
	$rets = array();
	if(empty($tag['type'])){
		if($sqlstr = v_sqlstr($tag,$type)){
			$query = $db->query($sqlstr);
			while($row = $db->fetch_array($query)){
				$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
				$rets[] = $row;
			}
		}
	}else{
		if(!empty($tag['fname']) || !empty($tag['id'])){
			include_once(M_ROOT.'include/vote.fun.php');
			$rets = field_votes($tag['fname'],$tag['type'],$tag['id']);
			foreach($rets as $k => $v){
				$rets[$k]['vid'] = $k;
				$rets[$k]['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			}
		}
	}
	return $rets;
}
function v_sqlstr(&$tag,$type='c',$iscount=0){
	global $vcatalogs,$tblprefix,$timestamp,$_midarr,$_mp;
	$sqlorder = $sqllimit = '';
	$sqlwhere = " WHERE checked=1 AND (enddate=0 OR enddate>$timestamp)";
	$sqlselect = $iscount ? "SELECT COUNT(*)" : "SELECT *";
	$sqlfrom = " FROM {$tblprefix}votes";
	if(!empty($tag['vsource'])){
		if(empty($vcatalogs[$tag['vsource']])) return false;
		$sqlwhere .= " AND caid='".$tag['vsource']."'";
	}
	if(!empty($tag['vids'])){
		$vids = explode(',',$tag['vids']);
		foreach($vids as $k => $v) $vids[$k] = max(0,intval($v));
		$sqlwhere .= " AND vid ".multi_str($vids);
		unset($vids);
	}
	if(!$iscount){
		$sqlorder = " ORDER BY vieworder,vid DESC";
		$limits = empty($tag['limits']) ? 10 : $tag['limits'];
		$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;
	}
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function arr_outinfos(&$tag,$type='c'){
	global $dbsources,$db,$authkey,$_midarr,$_mp;
	$retarr = array();
	if(empty($tag['sqlstr'])) return $retarr;
	if(empty($tag['dsid']) || empty($dbsources[$tag['dsid']])){
		$ndb = &$db;
	}else{
		$dbsource = $dbsources[$tag['dsid']];
		$dbsource['dbpw'] && $dbsource['dbpw'] = authcode($dbsource['dbpw'],'DECODE',md5($authkey));
		if(empty($dbsource['dbhost']) || empty($dbsource['dbuser']) || empty($dbsource['dbname'])){
			return $retarr;
		}
		$ndb = new cls_mysql;
		if(!$ndb->connect($dbsource['dbhost'],$dbsource['dbuser'],$dbsource['dbpw'],$dbsource['dbname'],0,false,$dbsource['dbcharset'])){
			return $retarr;
		}
	}

	$limits = empty($tag['limits']) ? 10 : $tag['limits'];
	$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;

	$sqlstr = $tag['sqlstr'].$sqllimit;

	$query = $ndb->query($sqlstr);
	while($row = $ndb->fetch_array($query)){
		$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
		$retarr[] = $row;
	}
	return $retarr;
}
function outinfos_nums(&$tag){
	global $dbsources,$db,$authkey;
	$nums = 0;
	if(empty($tag['sqlstr'])) return $nums;
	if(empty($tag['dsid']) || empty($dbsources[$tag['dsid']])){
		$ndb = &$db;
	}else{
		$dbsource = $dbsources[$tag['dsid']];
		$dbsource['dbpw'] && $dbsource['dbpw'] = authcode($dbsource['dbpw'],'DECODE',md5($authkey));
		if(empty($dbsource['dbhost']) || empty($dbsource['dbuser']) || empty($dbsource['dbname'])) return $nums;
		$ndb = new cls_mysql;
		if(!$ndb->connect($dbsource['dbhost'],$dbsource['dbuser'],$dbsource['dbpw'],$dbsource['dbname'],0,false,$dbsource['dbcharset'])) return $nums;
	}
	$sqlstr = "SELECT COUNT(*) ".stristr($tag['sqlstr'],'FROM');
	$nums = $ndb->result($ndb->query($sqlstr),0);
	return $nums;
}
function arr_relates(&$tag,$type='c'){
	global $db;
	$rets = array();
	if($sqlstr = rel_sqlstr($tag,$type)){
		$query = $db->query($sqlstr);
		while($row = $db->fetch_array($query)){
			arc_parse($row);
			$row['sn_row'] = $i = empty($i) ? 1 : ++ $i;
			$rets[] = $row;
		}
	}
	return $rets;
}

function rel_sqlstr(&$tag,$type='c',$iscount=0){
	global $channels,$ncatalogs,$cotypes,$db,$tblprefix,$sid,$timestamp,$_midarr,$_mp;
	if(empty($_midarr['aid'])) return false;
	$keywords = $db->result_one("SELECT keywords FROM {$tblprefix}archives WHERE aid='".$_midarr['aid']."'");
	if(empty($keywords)) return false;
	$arr = array_unique(explode(' ',$keywords));
	$i = 0;
	$keywordstr = '';
	foreach($arr as $str){
		$str = addcslashes($str,'%_');
		$keywordstr .= ($keywordstr ? ' OR ' : '')."keywords LIKE '%$str%'";
		$i ++;
		if($i > 5){
			unset($arr);
			break;
		}
	}
	$nsid = empty($tag['nsid']) ? 0 : intval($tag['nsid']);
	if(!$nsid){
		$nsid = isset($_midarr['sid']) ? $_midarr['sid'] : $sid;
	}elseif($nsid == -1) $nsid = 0;
	if($nsid == -2){
		$ncatalogs = &$acatalogs;
	}else $ncatalogs = read_cache('catalogs','','',$nsid);
	$sqlorder = $sqllimit = '';
	$sqlselect = $iscount ? (empty($tag['nocp']) ? "SELECT COUNT(*)" : "SELECT COUNT(DISTINCT cpid)") : "SELECT *";
	$sqlwhere = " WHERE checked=1 AND aid!='$_midarr[aid]' AND ($keywordstr)";
	$sqlfrom = " FROM {$tblprefix}archives";
	
	if($nsid != -2) $sqlwhere .= " AND sid='$nsid'";
	if(!empty($tag['casource'])){
		$caids = array();
		if($tag['casource'] == '1'){//如果指定为空，返回空集
			if(empty($tag['caids'])) return false;
			$tcaids = explode(',',$tag['caids']);
			foreach($tcaids as $caid) isset($ncatalogs[$caid]) && $caids[] = $caid;
			if(empty($caids)) return false;
		}elseif($tag['casource'] == '2'){
			if(!empty($_midarr['caid'])){
				if(empty($ncatalogs[$_midarr['caid']])) return false;
				$caids[] = $_midarr['caid'];
			}
		}
		if(!empty($caids) && !empty($tag['caidson'])){
			$tempsons = array();
			foreach($caids as $caid) $tempsons = son_ids($ncatalogs,$caid,$tempsons);
			$caids = array_unique(array_merge($caids,$tempsons));
		}
		!empty($caids) && $sqlwhere .= " AND caid ".multi_str($caids);
	}

	foreach($cotypes as $k => $cotype){
		$ccids = array();	
		if(!empty($tag['cosource'.$k])){
			$coclasses = read_cache('coclasses',$k);
			if($tag['cosource'.$k] == '1'){
				if(empty($tag['ccids'.$k])) return false;
				$tccids = explode(',',$tag['ccids'.$k]);
				foreach($tccids as $ccid) isset($coclasses[$ccid]) && $ccids[] = $ccid;
				if(empty($ccids)) return false;
			}elseif($tag['cosource'.$k] == '2'){
				if(!empty($_midarr['ccid'.$k])){
					if(empty($coclasses[$_midarr['ccid'.$k]])) return false;
					$ccids[] = $_midarr['ccid'.$k];
				}
			}
			if(!empty($ccids) && !empty($tag['ccidson'.$k])){
				$tempsons = array();
				foreach($ccids as $ccid) $tempsons = son_ids($coclasses,$ccid,$tempsons);
				$ccids = array_unique(array_merge($ccids,$tempsons));
			}
			if(!empty($ccids) && $str = cnsql($k,$ccids,'a.')) $sqlwhere .= ' AND '.$str;
		}
	}
	if(!empty($tag['chsource'])){
		if($tag['chsource'] == 1){
			if(empty($_midarr['chid'])) return false;
			$sqlwhere .= " AND chid='".$_midarr['chid']."'";
		}elseif($tag['chsource'] == 2){
			if(empty($tag['chids'])) return false;
			$tchids = explode(',',$tag['chids']);
			$sqlwhere .= " AND chid ".multi_str($tchids);
		}
	}
	if(!empty($tag['nochids'])){
		if($nochids = explode(',',$tag['nochids'])){
			$sqlwhere .= " AND chid ".multi_str($nochids,1);
		}
	}
	if(!empty($tag['validperiod'])) $sqlwhere .= " AND (enddate='0' OR enddate>'$timestamp')";
	if(!$iscount){
		!empty($tag['nocp']) && $sqlwhere .= " GROUP BY cpid";
		!empty($tag['orderby']) && $sqlorder .= ($sqlorder ? ',' : '').str_replace('_',' ',$tag['orderby']);
		$sqlorder = empty($sqlorder) ? " ORDER BY aid DESC" : " ORDER BY $sqlorder";
		$limits = empty($tag['limits']) ? 10 : $tag['limits'];
		$sqllimit = " LIMIT ".($type == 'p' ? ($_mp['nowpage'] - 1) * $limits : (empty($tag['startno']) ? 0 : $tag['startno'])).",".$limits;
	}
	return $sqlselect.$sqlfrom.$sqlwhere.$sqlorder.$sqllimit;
}
function one_user(&$tag){
	global $curuser,$memberid,$db,$tblprefix,$nouserinfos,$grouptypes,$_midarr;
	$item = array();
	if(!$tag['usource']){
		if($memberid){
			$curuser->sub_data();
			$item = $curuser->info;
		}
	}elseif($tag['usource'] == 1){
		if(!empty($_midarr['mid'])) $item = $db->fetch_one("SELECT m.*,s.* FROM {$tblprefix}members m LEFT JOIN {$tblprefix}members_sub s ON s.mid=m.mid WHERE m.mid='$_midarr[mid]'");
	}elseif($tag['usource'] == 2){
		if(!empty($tag['mid'])) $item = $db->fetch_one("SELECT m.*,s.* FROM {$tblprefix}members m LEFT JOIN {$tblprefix}members_sub s ON s.mid=m.mid WHERE m.mid='$tag[mid]'");
	}
	if(!empty($tag['detail']) && !empty($item)){
		if($temps = $db->fetch_one("SELECT * FROM {$tblprefix}members_".$item['mchid']." WHERE mid='$item[mid]'")) $item = array_merge($item,$temps);
		unset($temps);
	}
	if($item){
		arr_tag2atm($item,'m');
	}else $item = $nouserinfos;

	foreach($grouptypes as $k => $v){
		$item['grouptype'.$k.'name'] = '';
		if(!empty($item['grouptype'.$k])){
			$usergroups = read_cache('usergroups',$k);
			$item['grouptype'.$k.'name'] = $usergroups[$item['grouptype'.$k]]['cname'];
		}
	}
	unset($usergroups);
	return $item;
}
function func_sqlstr($funcstr){
	if(empty($funcstr)) return '';
	@eval("\$v = $funcstr;");
	return @$v;
}

?>