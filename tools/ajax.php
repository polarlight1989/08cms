<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
empty($action) && die();
switch($action){
case 'fetchcnodeurl':
	//取得节点的url，必须有节点所在$sid,url类型$urltype,及$caid，$ccid2形式的类目参数
	parse_str($_SERVER['QUERY_STRING'],$temparr);
	$nsid = empty($sid) ? 0 : max(0,intval($sid));
	$cnstr = cnstr($temparr);
	if(!($cnode = cnodearr($cnstr,$nsid))) ajax_info('#');
	ajax_info($cnode[empty($urltype) ? 'indexurl' : $urltype]);
	break;
case 'arcsamevalue'://能否通过一个数组传数据过来
	//$ret = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}archives WHERE aid='$aid' AND oid='0' AND mid='$memberid'")
	break;
case 'ablock':
	$caid = max(0,intval($caid));
	$caid_suffix = $caid ? "&caid=$caid" : '';
	$param_suffix = $sid ? "&sid=$sid" : '';
	$output = '';
	if($curuser->isadmin()){
		load_cache('aurls,amconfigs');
		foreach($aurls as $k => $v) if(!in_array($v['uclass'],array('archives','arcadd','arcupdate','comments','offers','replys','reports','answers','custom'))) unset($aurls[$k]);
		if(!$curuser->info['isfounder']){
			$ausergroup = read_cache('usergroup',2,$curuser->info['grouptype2']);
			if(($a_amcid = @$ausergroup['amcids'][$sid ? $sid : 'm']) && ($amconfig = @$amconfigs[$a_amcid])){
				if($amconfig['abcustom'] && !empty($amconfig['anodes'][$caid])){
					$auidsarr = explode(',',$amconfig['anodes'][$caid]);
					$auidsarr = array_intersect($auidsarr,array_keys($aurls));
				}
			}
		}
		if(!isset($auidsarr)){
			$auidsarr = array();
			foreach($aurls as $k => $v) if($v['issys']) $auidsarr[] = $k;
		}
		$output = '[';
		foreach($auidsarr as $k) $output .= "['".addslashes($aurls[$k]['cname'])."','".addslashes($aurls[$k]['url']."$caid_suffix$param_suffix")."'],";
		$output .= ']';
	}
	ajax_info($output);
	break;
case 'fblock':
	$caid = max(0,intval($caid));
	$caid_suffix = $caid ? "&fcaid=$caid" : '';
	$param_suffix = $sid ? "&sid=$sid" : '';
	$output = '';
	if($curuser->isadmin()){
		load_cache('aurls,amconfigs');
		foreach($aurls as $k => $v) if(!in_array($v['uclass'],array('farchives','farcadd','custom'))) unset($aurls[$k]);
		if(!$curuser->info['isfounder']){
			$ausergroup = read_cache('usergroup',2,$curuser->info['grouptype2']);
			if(($a_amcid = @$ausergroup['amcids'][$sid ? $sid : 'm']) && ($amconfig = @$amconfigs[$a_amcid])){
				if($amconfig['fbcustom'] && !empty($amconfig['fnodes'][$caid])){
					$auidsarr = explode(',',$amconfig['fnodes'][$caid]);
					$auidsarr = array_intersect($auidsarr,array_keys($aurls));
				}
			}
		}
		if(!isset($auidsarr)){
			$auidsarr = array();
			foreach($aurls as $k => $v) if($v['issys']) $auidsarr[] = $k;
		}
		$output = '[';
		foreach($auidsarr as $k) $output .= "['".addslashes($aurls[$k]['cname'])."','".addslashes($aurls[$k]['url']."$caid_suffix$param_suffix")."'],";
		$output .= ']';
	}
	ajax_info($output);
	break;
case 'mblock':
	$mchid = max(0,intval($mchid));
	$mchid_suffix = $mchid ? "&mchid=$mchid" : '';
	$param_suffix = $sid ? "&sid=$sid" : '';
	$output = '';
	if($curuser->isadmin()){
		load_cache('aurls,amconfigs');
		foreach($aurls as $k => $v) if(!in_array($v['uclass'],array('members','memadd','mcomments','mreplys','mreports','marchives','mtrans','utrans','custom'))) unset($aurls[$k]);
		if(!$curuser->info['isfounder']){
			$ausergroup = read_cache('usergroup',2,$curuser->info['grouptype2']);
			if(($a_amcid = @$ausergroup['amcids'][$sid ? $sid : 'm']) && ($amconfig = @$amconfigs[$a_amcid])){
				if($amconfig['mbcustom'] && !empty($amconfig['mnodes'][$mchid])){
					$auidsarr = explode(',',$amconfig['mnodes'][$mchid]);
					$auidsarr = array_intersect($auidsarr,array_keys($aurls));
				}
			}
		}
		if(!isset($auidsarr)){
			$auidsarr = array();
			foreach($aurls as $k => $v) if($v['issys']) $auidsarr[] = $k;
		}
		$output = '[';
		foreach($auidsarr as $k) $output .= "['".addslashes($aurls[$k]['cname'])."','".addslashes($aurls[$k]['url']."$mchid_suffix$param_suffix")."'],";
		$output .= ']';
	}
	ajax_info($output);
	break;
case 'allowids':
	$chids = empty($chids) ? '' : explode(',',$chids);
	$nochids = empty($nochids) ? '' : explode(',',$nochids);
	parse_str($_SERVER['QUERY_STRING'],$temparr);
	$nchids = $curuser->addidsfromcn($temparr);
	$output = '';
	foreach($nchids as $k){
		if(($chids && !in_array($k,$chids)) || ($nochids && in_array($k,$nochids))) continue;
		$output .= ",[$k,'".addslashes($channels[$k]['cname'])."',0]";
	}
	$output = '['.substr($output,1).']';
	ajax_info($output);
	break;
case 'subject':
	if(empty($table) || empty($subject) || preg_match('/\W/', $table)){
		$output = '-1';
	}else{
		$output = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}$table WHERE subject='$subject' LIMIT 0,1");
	}
	ajax_info($output);
	break;
case 'stat':
	preg_match("/^\d+(,\d+)?(?:,\d+)*$/", $aids, $match) || exit();
	$sql  =	'SELECT ' .
			'a.clicks,a.comments,a.scores,a.orders,a.favorites,a.praises,a.debases,a.answers,a.adopts,a.price,a.crid,a.currency,a.closed,a.downs,a.plays,' .
			's.storage,s.spare,s.reports,r.*' .
			" FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid LEFT JOIN {$tblprefix}archives_rec r ON r.aid=a.aid WHERE a.aid ";
	$sql .=	empty($match[1]) ? "=$aids" : "IN ($aids)";
	$query = $db->query($sql);
	$output = '';
	while($row = $db->fetch_array($query)){
		$output .= ",$row[aid]:{";
		unset($row['aid']);
		$row = array_filter($row);
		$tmp = '';
		foreach($row as $k => $v)$tmp .= ",$k:$v";
		$output .= substr($tmp, 1) . '}';
	}
	ajax_info('{' . substr($output, 1) . '}');
	break;
case 'score':
	preg_match("/^\d+(,\d+)?(?:,\d+)*$/", $aids, $match) || exit();
	$commu = read_cache('commu',2);
	empty($commu['setting']['scorestr']) && exit();
	if(!($scorearr = array_filter(explode(',',$commu['setting']['scorestr'])))) exit();
	$selectstr = 'aid';
	foreach($scorearr as $v) $selectstr .= ',score_'.$v;
	$query = $db->query("SELECT $selectstr FROM {$tblprefix}archives WHERE aid ".(empty($match[1]) ? "=$aids" : "IN ($aids)"));
	$output = '';
	while($row = @$db->fetch_array($query)){
		$output .= ",$row[aid]:{";
		unset($row['aid']);
		$row = array_filter($row);
		$tmp = '';
		foreach($row as $k => $v)$tmp .= ",$k:$v";
		$output .= substr($tmp, 1) . '}';
	}
	ajax_info('{' . substr($output, 1) . '}');
	break;
case 'mark'://浏览记录
	$aid = empty($aid) ? 0 : max(0,intval($aid));
	$aid || exit();
	$db->result_one("SELECT COUNT(*) FROM {$tblprefix}archives WHERE aid='$aid' AND checked=1") || exit();
	$cookie_key = "BR_R_$memberid";
	$limit = 30;
	$tmp = empty($m_cookie[$cookie_key]) ? array() : explode(';', $m_cookie[$cookie_key]);
	in_array($aid, $tmp) || $tmp[] = "$aid,$timestamp";
	$cookie_val = implode(';', count($tmp) > $limit ? array_splice($tmp, -$limit) : $tmp);
	msetcookie($cookie_key, $cookie_val);
	exit;
	break;
case 'caid':
	empty($varname) && exit();
	$framein = empty($framein) ? 0 : 1;
	$chid = empty($chid) ? 0 : max(0,intval($chid));
	$ccidsarr = uccidsarr(0,$chid,$framein,1,1);
	echo "var $varname=[";
	foreach($ccidsarr as $k => $v)echo "[$k,$v[pid],'".addslashes($v['title'])."'".(empty($v['unsel']) ? '' : ',1') . '],';
	echo ']';
	break;
case 'coid':
	$framein = empty($framein) ? 0 : 1;
	$chid = empty($chid) ? 0 : max(0,intval($chid));
	$coid = empty($coid) ? 0 : max(0,intval($coid));
	empty($varname) || empty($coid) && exit();
	$ccidsarr = uccidsarr($coid,$chid,$framein,1,1);
	echo "var $varname=[";
	foreach($ccidsarr as $k => $v)echo "[$k,$v[pid],'".addslashes($v['title'])."'".(empty($v['unsel']) ? '' : ',1') . '],';
	echo ']';
	break;
case 'cacc':
	$arr = cacc_arr(empty($coid) ? 0 : 1,empty($source) ? 0 : intval($source),empty($ids) ? '' : trim($ids));
	echo "var $varname=[";
	foreach($arr as $k => $v) echo "[$k,$v[pid],'".addslashes($v['title'])."',".(empty($v['unsel']) ? 0 : 1) . '],';
	echo ']';
	break;
case 'memcert':
	$info = array();
	if($option == 'msgcode'){
		if(preg_match("/^1[358]\\d{9}$/", $mobile)){
			$msgcode = random(6, 1);
			$id  = ${"msgcode_sp$msgcode_gate"};
			$pw  = ${"msgcode_pw$msgcode_gate"};
			if(empty($msgcode_mode) || ($msgcode_mode == 1 && empty($msgcode_msg)) || ($msgcode_mode == 2 && (empty($id) || empty($pw) || empty($msgcode_sms)))){
				$info = array(
					'time' => -1,
					'text' => 'no_msg_gate'
				);
			}elseif($msgcode_mode == 1){
				msetcookie('08cms_msgcode', authcode("$timestamp\t$msgcode", 'ENCODE'));
				$info = array(
					'time' => -1,
					'text' => str_replace('%s', $msgcode, $msgcode_msg)
				);
			}elseif($msgcode_mode == 2){
				list($inittime, $initcode) = maddslashes(explode("\t", @authcode($m_cookie['08cms_msgcode'],'DECODE')),1);
				if(($timestamp - $inittime) > 60){
					$msg = str_replace('%s', $msgcode, $msgcode_sms);
					if($mcharset != 'gbk' || $mcharset != 'gb2312'){
						include(M_ROOT.'include/charset.fun.php');
						$msg = convert_encoding($mcharset, 'gb2312', $msg);
					}
					$msg = rawurlencode($msg);
					$url = $msgcode_gate == 1 ? "http://sms.eshang8.cn/api/?esname=$id&key=pw&phone=$mobile&msg=$msg&smskind=1"
											 : "http://service.winic.org/sys_port/gateway/?id=$id&pwd=$pw&to=$mobile&content=$msg&time=$timestamp";
					include(M_ROOT.'include/http.cls.php');
					$http = new http;
					$http->timeout = 60;
					$msg = $http->fetchtext($url);
					if($msgcode_gate == 1){
						$msg = $msg === '0';
					}else{
						$msg = explode("/", $msg);
						$msg = $msg[0] === '000';
					}
					if($msg){
						msetcookie('08cms_msgcode', authcode("$timestamp\t$msgcode", 'ENCODE'));
					}else{
						$info = array(
							'time' => -1,
							'text' => 'msgcode_send_err'
						);
					}
				}else{
					$info = array(
						'time' => 1,
						'text' => 'donot_repeat_operate'
					);
				}
			}
		}else{
			$info = array(
				'time' => 0,
				'text' => 'mobile_format_fail'
			);
		}
	}
	ajax_info($info);
	break;
case 'dirname':
	if(empty($value)){
		ajax_info(-1);
	}else{
		$value = stripslashes($value);
		load_cache('catalogs,cotypes');
		foreach($catalogs as $k => $v)$v['dirname'] == $value && ajax_info(1);
		foreach($cotypes as $k => $v){
			$arr = read_cache('coclasses',$k);
			foreach($arr as $x => $y)$y['dirname'] == $value && ajax_info(1);
		}
		unset($arr);
	}
	ajax_info(0);
	break;
case 'floor':
	$v = explode(':', $querydata);
	(preg_match('/^m?(?:comment|reply)s$/', $v[0]) && preg_match('/^\w+(,\w+)*$/', $v[1]) && preg_match('/^\d+(,\d+)*$/', $v[2])) || exit();
	
	preg_match('/\bcid\b/', $v[1]) || $v[1] .= ',cid';
	
	$querydata = array($v[0] => array());
	$point = &$querydata[$v[0]];
	$query = $db->query("SELECT $v[1] FROM $tblprefix$v[0] WHERE cid IN ($v[2])");
	while($row = $db->fetch_array($query)){
		$point[$row['cid']] = $row;
		unset($point[$row['cid']]['cid']);
	}
	echo empty($callback) ? jsonEncode($querydata, 1) : $callback . '(' . jsonEncode($querydata, 1) . ')';
}
?>