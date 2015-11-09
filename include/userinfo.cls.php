<?php
!defined('M_COM') && exit('No Permisson');
class cls_userinfo{
	var $info = array();
	var $updatearr = array();
	var $subed = 0;
	var $detailed = 0;
	function __construct(){
		$this->cls_userinfo();
	}
	function cls_userinfo(){
	}
	function init(){
		$this->info = array();
		$this->subed = 0;
		$this->detailed = 0;
		$this->updatearr = array();
	}
	function currentuser(){
		global $m_cookie,$db,$tblprefix,$onlineip,$nouserinfos,$timestamp,$sessionexists;
		$this->cumonthadd_reset();//将所有会员的月交互数量置为0
		$memberid = 0;
		if(!empty($m_cookie['userauth'])){
			@list($memberpwd,$memberid) = maddslashes(explode("\t", authcode($m_cookie['userauth'], 'DECODE')), 1);
			if(empty($memberid) || $memberid != intval($memberid)) mclearcookie('userauth');
		}else list($memberpwd,$memberid) = array('',0);

		$sessionexists = 0;
		$msid = isset($m_cookie['msid']) ? $m_cookie['msid'] : '';
		if($msid){
			if($memberid){
				$sqlstr = "SELECT ms.*,m.* FROM {$tblprefix}msession ms,{$tblprefix}members m
					WHERE ms.mid=m.mid AND ms.msid='$msid' AND onlineip='$onlineip' AND m.mid='$memberid' AND m.password='$memberpwd'";
			}else $sqlstr = "SELECT * FROM {$tblprefix}msession WHERE msid='$msid' AND onlineip='$onlineip'";
			if($msession = $db->fetch_one($sqlstr)){
				$sessionexists = 1;
				$memberid || $msession = array_merge($msession,$nouserinfos);
			}
		}
		if(!$sessionexists){
			if($memberid){
				if(!($msession = $db->fetch_one("SELECT * FROM {$tblprefix}members WHERE mid='$memberid' AND password='$memberpwd'"))){
					mclearcookie('userauth');
					$memberid = 0;
				}else $msession['mslastactive'] = $msession['lastolupdate'] = $timestamp;
			}
			$memberid || $msession = $nouserinfos;
			$msession['msid'] = random(6);
		}
		if(empty($m_cookie['msid']) || $msession['msid'] != $m_cookie['msid']) msetcookie('msid', $msession['msid']);
		$this->info = $msession;
		$this->updatesession();
	}
	function cumonthadd_reset(){
		global $cu_nowmonth,$db,$tblprefix;
		if($cu_nowmonth == date('n')) return;
		@set_time_limit(1000);
		@ignore_user_abort(TRUE);
		include_once M_ROOT."./include/cache.fun.php";
		$db->query("UPDATE {$tblprefix}members SET cuaddmonth = '0'",'SILENT');
		$db->query("REPLACE INTO {$tblprefix}mconfigs (varname,value,cftype) VALUES ('cu_nowmonth','".date('n')."','basic')",'SILENT');
		updatecache('mconfigs');
	}
	function rss_user(){
		global $m_cookie,$db,$tblprefix,$nouserinfos,$timestamp;
		$memberid = 0;
		if(!empty($m_cookie['userauth'])) list($memberpwd,$memberid) = maddslashes(explode("\t", authcode($m_cookie['userauth'], 'DECODE')), 1);
		if(empty($memberid) || $memberid != intval($memberid)){
			$this->info = $nouserinfos;
		}else{
			if(!$this->info = $db->fetch_one("SELECT * FROM {$tblprefix}members WHERE mid=$memberid AND password='$memberpwd'")) $this->info = $nouserinfos;
		}
	}
	function activeuserbyname($mname,$sub=0){
		global $nouserinfos,$db,$tblprefix;
		if(empty($mname)){
			$this->info = $nouserinfos;
			return;
		}
		$sqlstr = !$sub ? "SELECT * FROM {$tblprefix}members WHERE mname='$mname'" : "SELECT m.*,s.* FROM {$tblprefix}members m LEFT JOIN {$tblprefix}members_sub s ON s.mid=m.mid WHERE mname='$mname'";
		if(!($this->info = $db->fetch_one($sqlstr))){
			$this->info = $nouserinfos;
			return;
		}
		$sub && $this->subed = 1;
		if($sub > 1) $this->detail_data();
	}
	function activeuser($mid,$sub=0){//$sub取值0,1,2
		global $nouserinfos,$db,$tblprefix;
		if(empty($mid)){
			$this->info = $nouserinfos;
			return;
		}
		$sqlstr = !$sub ? "SELECT * FROM {$tblprefix}members WHERE mid='$mid'" : "SELECT m.*,s.* FROM {$tblprefix}members m LEFT JOIN {$tblprefix}members_sub s ON s.mid=m.mid WHERE m.mid='$mid'";
		if(!($this->info = $db->fetch_one($sqlstr))){
			$this->info = $nouserinfos;
			return;
		}
		$sub && $this->subed = 1;
		if($sub > 1) $this->detail_data();
	}
	function useradd($mname = '',$password = '',$email = '',$mchid = 0){
		global $db,$tblprefix;
		if(!$mname || !$mchid) return false;
		$db->query("INSERT INTO {$tblprefix}members SET mname='$mname',password='$password',email='$email',mchid='$mchid'");
		if(!($mid = $db->insert_id())){
			return false;
		}else{
			$db->query("INSERT INTO {$tblprefix}members_sub SET mid='$mid'");
			$db->query("INSERT INTO {$tblprefix}members_$mchid SET mid='$mid'");
			$this->info = $db->fetch_one("SELECT m.*,s.* FROM {$tblprefix}members m LEFT JOIN {$tblprefix}members_sub s ON s.mid=m.mid WHERE m.mid='$mid'");
			$this->subed = 1;
			$this->detail_data();
			return true;
		}
	}
	function sub_data(){
		global $db,$tblprefix;
		if(empty($this->info['mid'])) return;
		if($this->subed) return;
		if($member = $db->fetch_one("SELECT * FROM {$tblprefix}members_sub WHERE mid=".$this->info['mid'])) $this->info = array_merge($this->info,$member);
		unset($member);
		$this->subed = 1;
	}
	function detail_data(){
		global $db,$tblprefix;
		if(empty($this->info['mid']) || $this->detailed) return;
		!$this->subed && $this->sub_data();
		$customtable = 'members_'.$this->info['mchid'];
		if($infos = $db->fetch_one("SELECT * FROM {$tblprefix}$customtable WHERE mid='".$this->info['mid']."'")){
			$this->info = array_merge($infos,$this->info);
		}
		unset($infos);
		$this->detailed = 1;
	}
	function updatesession(){
		global $sessionupdated,$onlinetimecircle,$db,$tblprefix,$timestamp,$sessionexists,$onlinehold,$onlineip;
		if($sessionupdated) return;
		if($onlinetimecircle && $this->info['mid'] && $this->info['mslastactive'] && $timestamp - (!empty($this->info['lastactive']) ? $this->info['lastactive'] : $this->info['mslastactive']) > $onlinetimecircle * 60){
			$lastolupdate = $timestamp;
			$db->query("UPDATE {$tblprefix}members SET
						lastactive='{$this->info['mslastactive']}'
						WHERE mid='{$this->info['mid']}'",'UNBUFFERED');
		}else $lastolupdate = (!empty($this->info['lastolupdate'])) ? $this->info['lastolupdate'] : $timestamp;
		if($sessionexists){
			$db->query("UPDATE {$tblprefix}msession SET 
						mid='{$this->info['mid']}',
						mname='{$this->info['mname']}', 
						mslastactive='$timestamp'
						WHERE msid='{$this->info['msid']}'");
		}else{
			$db->query("DELETE FROM {$tblprefix}msession 
						WHERE msid='{$this->info['msid']}'
						OR mslastactive<($timestamp-$onlinetimecircle*60) 
						OR (mid<>'0' AND mid='{$this->info['mid']}') 
						OR (mid='0' AND onlineip='$onlineip' AND mslastactive>$timestamp-60)");
	
			$db->query("INSERT INTO {$tblprefix}msession (msid,onlineip,mid,mname,mslastactive)
				VALUES ('{$this->info['msid']}','$onlineip','{$this->info['mid']}','{$this->info['mname']}','$timestamp')", 'SILENT');
		
			if($this->info['mid'] && $timestamp - $this->info['lastactive'] > 21600) {
				$db->query("UPDATE {$tblprefix}members SET
							lastip='$onlineip',
							lastactive='$timestamp'
							WHERE mid='{$this->info['mid']}'",'UNBUFFERED');
			}
		}
		$sessionupdated = 1;
	}
	function handgrouptype($gtid,$ugid=0,$endstamp=-1,$updatedb = 0){//-1按会员组有效期0无限期>0实际输入时间
		global $grouptypes,$timestamp;
		if(!$this->info['mid'] || empty($grouptypes[$gtid]) || $grouptypes[$gtid]['mode'] > 1) return;
		$mchid = $this->info['mchid'];
		if($ugid && !in_array($mchid,explode(',',$grouptypes[$gtid]['mchids']))){
			$usergroups = read_cache('usergroups',$gtid);
			if(in_array($mchid,explode(',',$usergroups[$ugid]['mchids'])) && ($endstamp <= 0 || $endstamp > $timestamp)){
				if($grouptypes[$gtid]['allowance'] && $ugid != $this->info['grouptype'.$gtid]) $alter = true;
				$this->updatefield('grouptype'.$gtid,$ugid,'main');
				$this->updatefield('grouptype'.$gtid.'date',$endstamp == -1 ? ($usergroups[$ugid]['limitday'] ? ($timestamp + $usergroups[$ugid]['limitday'] * 86400) : 0) : $endstamp,'main');
			}else $ugid = 0;
		}else $ugid = 0;
		if(!$ugid){
			if($grouptypes[$gtid]['allowance'] && $ugid != $this->info['grouptype'.$gtid]) $alter = true;
			$this->updatefield('grouptype'.$gtid,0,'main');
			$this->updatefield('grouptype'.$gtid.'date',0,'main');
		}
		if(!empty($alter)) $this->reset_allowance();
		$updatedb && $this->updatedb();
	}
	function isadmin(){
		if(!$this->info['mid'] || !$this->info['checked']) return false;
		return $this->info['grouptype2'] || $this->info['isfounder'];
	}
	function basedeal($dname,$mode=1,$count=1,$updatedb=0){//会员操作之后的操作数记录及积分基本策略的处理
		global $currencys; 
		if(!$this->info['mid']) return;
		if(!$this->subed) $this->sub_data();
		if(in_array($dname,array('archive','check','freeinfo','comment','purchase','favorite','answer','reply','offer','score'))){//会员操作统计
			$this->updatefield($dname.'s',$mode ? $this->info[$dname.'s'] + $count : max(0,$this->info[$dname.'s'] - $count),'sub');
		}
		if(in_array($dname,array('archive','comment','purchase','answer','commu','vote','freeinfo','favorite','pm','search',))){//积分基本策略
			$crmode = $mode ? 1 : 0;
			if(in_array($dname,array('freeinfo','favorite','pm','search',))) $crmode = $mode ? 0 : 1;
			$crids = array();
			foreach($currencys as $crid => $currency){
				if($currency['available'] && !empty($currency[$dname])) $crids[$crid] = $crmode ? $count * $currency[$dname] : -$count * $currency[$dname];
			}
			$crids && $this->updatecrids($crids,0,lang('crpolicy'));
		}
		$updatedb && $this->updatedb();
	}
	function gt_discount($dcmode = 1){
		global $grouptypes;
		if(empty($this->info['mid']) || empty($dcmode)) return 0;
		$dcarr = array();
		foreach($grouptypes as $gtid => $grouptype){
			if(!$grouptype['forbidden'] && !empty($this->info['grouptype'.$gtid])){
				$usergroup = read_cache('usergroup',$gtid,$this->info['grouptype'.$gtid]);
				!empty($usergroup['discount']) && $dcarr[] = $usergroup['discount'];
			}
		}
		$discount = caldiscount($dcarr,$dcmode);
		return $discount;
	}
	function paydeny($aid,$isatm=0){//$isatm为1，表示为附件
		global $grouptypes,$db,$tblprefix;
		if(empty($this->info['mid'])) return false;
		foreach($grouptypes as $gtid => $grouptype){//免费订阅
			if(!$grouptype['forbidden'] && !empty($this->info['grouptype'.$gtid])){
				$usergroup = read_cache('usergroup',$gtid,$this->info['grouptype'.$gtid]);
				if(!empty($usergroup['deny'.($isatm ? 'atm' : 'arc')])) return true;//
			}
		}
		$sqlstr = "SELECT COUNT(*) FROM {$tblprefix}subscribes WHERE aid='$aid' AND mid='".$this->info['mid']."' AND isatm='$isatm'";
		if($db->result_one($sqlstr)) return true;
		return false;
	}
	function payrecord($aid,$isatm=0,$cridstr='',$updatedb=0){
		global $db,$tblprefix,$timestamp;
		if(empty($this->info['mid'])) return;
		$db->query("INSERT INTO {$tblprefix}subscribes SET
				mid='".$this->info['mid']."',
				mname='".$this->info['mname']."',
				aid='$aid',
				cridstr='$cridstr',
				isatm='$isatm',
				createdate='$timestamp'");
		$this->sub_data();
		$this->updatefield($isatm ? 'fsubscribes' : 'subscribes',$this->info[$isatm ? 'fsubscribes' : 'subscribes'] + 1,'sub');
		$updatedb && $this->updatedb();
	}
	function checkforbid($pname){
		global $grouptypes,$timestamp;
		if(empty($this->info['grouptype1'])) return true;
		if(!$usergroup = read_cache('usergroup',1,$this->info['grouptype1'])) return true;
		if(empty($usergroup[$pname.'permit'])) return false;
		return true;
	}
	function check_allow($var){
		global $grouptypes;
		if(!$this->info['mid']) return 0;
		if($this->info['isfounder']) return 1;
		foreach($grouptypes as $gtid => $grouptype){
			if(!$grouptype['forbidden'] && $this->info['grouptype'.$gtid]){
				$usergroup = read_cache('usergroup',$gtid,$this->info['grouptype'.$gtid]);
				if($usergroup[$var]) return 1;
			}
		}
		return 0;
	}
	function pmautocheck($pmid=0){
		return $pmid < 0 ? $this->pmbypmids('chk',-$pmid) : $pmid;
	}
	function pmbypmids($pname,$pmids=0){
		global $permissions,$grouptypes;
		if(!$pmids || !empty($this->info['isfounder'])) return true;
		$pmids = is_array($pmids) ? $pmids : array($pmids);
		if(!$pmids = array_filter(array_unique($pmids))) return true;
		if(!$this->info['mid']) return false;
		$ugids = $fugids = array();
		$isall = true;
		foreach($permissions as $k => $v){
			if(in_array($k,$pmids) && $v[$pname]){
				if($v['ugids'] != -1){
					$isall = false;
					$ugids = !$ugids ? explode(',',$v['ugids']) : array_intersect($ugids,explode(',',$v['ugids']));
					if(!$ugids) return false;
				}
				$fugids = !$fugids ? explode(',',$v['fugids']) : array_merge($fugids,explode(',',$v['fugids']));
			}
		}
		if($isall) $allow = true;
		$nugids = array();
		foreach($grouptypes as $k => $v) if(!$v['forbidden'] && $this->info['grouptype'.$k]) $nugids[] = $this->info['grouptype'.$k];
		if(!$isall) $allow = count(array_intersect($ugids,$nugids)) ? true : false;
		if(!$allow) return false;
		return count(array_intersect($fugids,$nugids)) ? false : true;
		
	}
	function allow_arcadd($chid,$sarr=array(),$ismc=0){
		if(!$chid) return false;
		if(!$sarr) return true;
		return in_array($chid,$this->addidsfromcn($sarr,$ismc));
	}
	function addidsfromcn($sarr=array(),$ismc=0){
		global $acatalogs,$cotypes,$channels;
		if(!$this->checkforbid('issue')) return array();
		$chids = array_keys($channels);
		if(!empty($sarr['caid'])){
			if(!$catalog = read_cache('catalog',$sarr['caid'],'',@$acatalogs[$sarr['caid']]['sid'])) return array();
			$nchids = array();
			if(!$catalog['isframe'] && $this->pmbypmids('aadd',$catalog['apmid'])){
				$cchids = array_filter(explode(',',$catalog['chids']));
				foreach($cchids as $chid){
					if(($channel = read_cache('channel',$chid)) && (!$ismc || !$channel['userforbidadd']) && $this->pmbypmids('aadd',$channel['apmid'])) $nchids[] = $chid;
				}
			}
			if(!($chids = array_intersect($chids,$nchids))) return array();
		}
		foreach($cotypes as $coid => $cotype){//如果是多选，需要同时有效
			if(!empty($cotype['permission']) && !$cotype['self_reg'] && ($ccids = array_filter(explode(',',@$sarr['ccid'.$coid])))){
				foreach($ccids as $k){
					if(!$coclass = read_cache('coclass',$coid,$k)) return array();
					$nchids = array();
					if(!$coclass['isframe'] && $this->pmbypmids('aadd',$coclass['apmid'])){
						$cchids = array_filter(explode(',',$coclass['chids']));
						foreach($cchids as $chid){
							if(($channel = read_cache('channel',$chid)) && (!$ismc || !$channel['userforbidadd']) && $this->pmbypmids('aadd',$channel['apmid'])) $nchids[] = $chid;
						}
					}
					if(!($chids = array_intersect($chids,$nchids))) return array();
				}
			}
		}
		return $chids;
	}
	function upload_capacity(){
		//会员组中的上传权限设置分析?????????????????????
		global $grouptypes,$upload_nouser;
		if(!$this->info['mid'] || !$this->info['checked']) return $upload_nouser ? -1 : 0;
		if($this->info['isfounder']) return -1;
		if(!$this->checkforbid('upload')) return 0;
		$permit = 0;
		$maxsize1 = 1;
		$maxsize2 = 0;//以M为单位
		foreach($grouptypes as $gtid => $grouptype){
			if(!$grouptype['forbidden'] && !empty($this->info['grouptype'.$gtid])){
				$usergroup = read_cache('usergroup',$gtid,$this->info['grouptype'.$gtid]);
				!empty($usergroup['uploadpermit']) && ($permit = 1);
				empty($usergroup['maxuptotal']) && ($maxsize1 = 0);
				$maxsize2 = max($maxsize2,$usergroup['maxuptotal']);
			}
		}
		if(empty($permit)) return 0; //不允许上传
		if(empty($maxsize)) return -1; //不限容量
		$capacity = max(0,$maxsize2 * 1024 - $this->info['uptotal']); //空间余量(K)
		return $capacity;
	}
	function updateuptotal($upsize,$mode='add',$updatedb=0){//$upsize以k为单位
		if(!$this->info['mid']) return;
		$this->updatefield('uptotal',$mode == 'add' ? ($this->info['uptotal'] + $upsize) : max(0,$this->info['uptotal'] - $upsize),'main');
		$updatedb && $this->updatedb();
	}
	function cridsaving($crid,$mode=0,$value=0){
		global $curuser,$db,$tblprefix,$timestamp;
		if(empty($value) || empty($this->info['mid'])) return;
		$this->updatecrids(array($crid => $mode ? $value : -$value),0,lang('currencyinout'));
		$this->updatedb();
		$mode = $mode ? '+' : '-';
		$db->query("INSERT INTO {$tblprefix}cradminlogs SET
				crid='$crid',
				mid='".$this->info['mid']."',
				mname='".$this->info['mname']."',
				frommid='".$curuser->info['mid']."',
				frommname='".$curuser->info['mname']."',
				createdate='$timestamp',
				value='$value',
				mode='$mode',
				dealmode='saving'");
	}
	function updatecrids($crids=array(),$updatedb=0,$reason=''){//积分能否为负值，及为小数点？
		global $currencys,$timestamp;
		if(empty($this->info['mid'])) return;
		if(empty($crids) || !is_array($crids)) return;
		if(!$reason) $reason = lang('otherreason');
		$record = array();
		foreach($crids as $crid => $value){
			$this->updatefield('currency'.$crid,$this->info['currency'.$crid] + $value,'main');
			$record[] = mhtmlspecialchars(
				$timestamp."\t".
				$this->info['mid']."\t".
				$this->info['mname']."\t".
				(!$crid ? lang('cash') : $currencys[$crid]['cname'])."\t".
				($value >= 0 ? '+' : '-')."\t".
				abs($value)."\t".
				$reason
				);
		}
		!empty($record) && record2file('currencylog',$record);
		$updatedb && $this->updatedb();
	}
	function crids_enough($crids=array()){
		if(empty($this->info['mid'])) return false;
		if(empty($crids)) return true;
		foreach($crids as $k => $v){
			if($v < 0 && $this->info['currency'.$k] < abs($v)) return false;
		}
		return true;
	}
	function updatefield($fieldname,$newvalue,$mode='main'){
		if(empty($this->info['mid'])) return;
		if($mode == 'sub' && !$this->subed){
			$this->sub_data();
		}elseif($mode == 'custom' && !$this->detailed){
			$this->detail_data();
		}
		if($this->info[$fieldname] != stripslashes($newvalue)){
			$this->info[$fieldname] = stripslashes($newvalue);
			$this->updatearr[$mode][$fieldname] = $newvalue;
		}
	}
	function gtidbymchid(){//当会员模型变化后，检查原先的会员组是否生效
		global $grouptypes;
		if(!$this->info['mid']) return;
		$mchid = $this->info['mchid'];
		foreach($grouptypes as $gtid => $grouptype){
			if($this->info["grouptype$gtid"]){
				if(!in_array($mchid,explode(',',$grouptype['mchids']))){
					$usergroup = read_cache('usergroup',$gtid,$this->info["grouptype$gtid"]);
					if(in_array($mchid,explode(',',$usergroup['mchids']))) continue;//只这种情况才维持原会员组
				}
				$this->updatefield("grouptype$gtid",0,'main');
				$this->updatefield("grouptype$gtid".'date',0,'main');
			}
		}
	}
	function autorgid(){
		global $repugrades;
		if(!$this->info['mid']) return;
		$rgid = 1;
		foreach($repugrades as $k => $v){
			if($this->info['repus'] < $v['rgbase']) break;
			$rgid = $k;
		}
		$this->updatefield('rgid',$rgid);
	}
	function repuadd($repus=0,$reason='',$updatedb=0){
		global $db,$tblprefix,$timestamp;
		if(!$this->info['mid'] || !$repus) return;
		$this->updatefield('repus',$this->info['repus'] + $repus);
		$db->query("INSERT INTO {$tblprefix}repus SET
			mid='".$this->info['mid']."',
			mname='".$this->info['mname']."',
			repus='$repus',
			reason='$reason',
			createdate='$timestamp'
		");
		$updatedb && $this->updatedb();
	}
	function autogrouptype(){
		global $grouptypes,$timestamp;
		if(!$this->info['mid']) return;
		$mchid = $this->info['mchid'];
		foreach($grouptypes as $gtid => $grouptype){
			if($grouptype['mode'] == 2){//分析积分基数会员组的变更
				$nowugid = 0;
				if(!in_array($mchid,explode(',',$grouptype['mchids']))){
					$usergroups = read_cache('usergroups',$gtid);
					foreach($usergroups as $ugid => $usergroup){
						if($this->info['currency'.$grouptype['crid']] >= $usergroup['currency'] && in_array($mchid,explode(',',$usergroup['mchids']))){
							$nowugid = $ugid;
							break 1;
						}
					}
				}
				if($nowugid != $this->info['grouptype'.$gtid]){
					$this->updatefield('grouptype'.$gtid,$nowugid,'main');
					if($grouptype['allowance']) $alter = true;
				}
			}
			if($this->info['grouptype'.$gtid.'date'] && $this->info['grouptype'.$gtid.'date'] < $timestamp){//清理到期会员组
				$this->updatefield('grouptype'.$gtid,0,'main');
				$this->updatefield('grouptype'.$gtid.'date',0,'main');
				if($grouptype['allowance']) $alter = true;
			}
		}
		if(!empty($alter)) $this->reset_allowance();
	}
	function autoinit($updatedb = 0){
		global $grouptypes,$timestamp;
		if(!$this->info['mid']) return;
		$mchid = $this->info['mchid'];
		$alter = 0;
		foreach($grouptypes as $k => $v){
			if(!$v['issystem'] && !$this->info['grouptype'.$k] && $v['mode'] != 2){
				if(!in_array($mchid,explode(',',$v['mchids']))){
					$usergroups = read_cache('usergroups',$k);
					foreach($usergroups as $x => $y){
						if($y['autoinit'] && in_array($mchid,explode(',',$y['mchids']))){
							$this->updatefield('grouptype'.$k,$x,'main');
							$y['limitday'] && $this->updatefield('grouptype'.$k.'date',$timestamp + $y['limitday'] * 86400,'main');
							if($v['allowance']) $alter = true;
							break;
						}
					}
				}
			}
		}
		$alter && $this->reset_allowance();
		$alter && $updatedb && $this->updatedb();
	}
	function reset_allowance(){//会员组变更时重置限额分析
		global $grouptypes;
		$arcallows = $cuallows = array();
		foreach($grouptypes as $gtid => $grouptype){
			if($grouptype['allowance']){
				if(!$this->info['grouptype'.$gtid]){//如果变成了组外会员，则清零。
					$arcallows[] = $cuallows[] = 0;
				}elseif($usergroup = read_cache('usergroup',$gtid,$this->info['grouptype'.$gtid])){
					$arcallows[] = $usergroup['arcallows'];
					$cuallows[] = $usergroup['cuallows'];
				}
			}
		}
		if($arcallows) $this->updatefield('arcallowance',max($arcallows),'main');
		if($cuallows) $this->updatefield('cuallowance',max($cuallows),'main');
	}
	function updatedb(){
		global $db,$tblprefix;
		if(empty($this->info['mid'])) return;
		//分析是否有积分变化更新自动会员组系
		$this->autogrouptype();
		$this->autorgid();
		//在这里分析函数字段的值的变化
		$fields = read_cache('mfields',$this->info['mchid']);
		foreach($fields as $k => $v){
			if($v['isfunc']){
				$this->updatefield($k,field_func($v['func'],$this->info,$arr2=''),$v['tbl']);
			}
		}
		foreach(array('main','sub','custom') as $upmode){
			if(!empty($this->updatearr[$upmode])){
				$sqlstr = '';
				foreach($this->updatearr[$upmode] as $k => $v) $sqlstr .= ($sqlstr ? "," : "").$k."='".$v."'";
				if(!empty($sqlstr)){
					$tablename = 'members'.($upmode == 'main' ? '' : ($upmode == 'sub' ? '_sub' : '_'.$this->info['mchid']));
					$db->query("UPDATE {$tblprefix}$tablename SET $sqlstr WHERE mid=".$this->info['mid']);
				}
				unset($this->updatearr[$upmode]);
			}
		}
	}
}

function login_safecheck($mname, $count = 0, $del = 0){
	global $db,$tblprefix,$curuser,$timestamp,$onlineip,$minerrtime;
	$ip = explode('.',$onlineip);
	$ip = (intval($ip[0]) << 24) | (intval($ip[1]) << 16) | (intval($ip[2]) << 8) | intval($ip[3]);
	if($count){
		$db->query("UPDATE {$tblprefix}logerrortimes SET errortime=$timestamp,times=$count WHERE mname='$mname' AND logip=$ip");
		$db->affected_rows() || $db->query("INSERT INTO {$tblprefix}logerrortimes (mname,logip,errortime,times) VALUES('$mname',$ip,$timestamp,$count)");
	}elseif(!$del){
		$ret = $db->fetch_one("SELECT times FROM {$tblprefix}logerrortimes WHERE mname='$mname' AND logip=$ip AND errortime>($timestamp-$minerrtime) LIMIT 0,1");
		return $ret ? $ret['times'] : 0;
	}else{
		$db->query("DELETE FROM {$tblprefix}logerrortimes WHERE (mname='$mname' AND logip=$ip) OR errortime<($timestamp-$minerrtime)");
	}
}
?>
