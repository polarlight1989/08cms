<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/archive.fun.php';
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);

if(empty($action)){
	$aid = empty($aid) ? 0 : max(0,intval($aid));
	if(!$aid) message('choosecommentobject');
	$cuid = $db->result_one("SELECT c.comment FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}channels c ON c.chid=a.chid WHERE a.aid='$aid'");
	if(!$cuid || !($commu = read_cache('commu',$cuid))) message('setcomitem');
	$qtid = max(0,intval(@$qtid));
	if(empty($commu['ucadd'])){
		if(!submitcheck('newcommu')){
			include_once M_ROOT.'./include/archive.cls.php';
			$arc = new cls_archive();
			$arc->arcid($aid);
			if(!$arc->aid) message('choosearchive');
			if(!$arc->archive['checked']) message('poinarcnoche');
			if($commu['allowance'] && @$curuser->info['cuallowance'] <= @$curuser->info['cuaddmonth']) message('owancecommuamooverlim');
			if(empty($commu['setting']['repeat']) || !empty($commu['setting']['repeattime'])){
				if(!empty($m_cookie['08cms_cuid_'.$commu['cuid'].'_'.$aid])) message(empty($commu['setting']['repeat']) ? 'norepeatoper' : 'overquick');
			}
			switch_cache($arc->archive['sid']);
			$sid = $arc->archive['sid'];
			if_siteclosed($sid);
			cache_merge($commu,'commu',$sid);
	
			if(!($tplname = @$commu['addtpl'])){
				unset($arc);
				load_cache('mlangs,cfields,ucotypes');
				include_once M_ROOT."./include/admin.fun.php";
				include_once M_ROOT."./include/adminm.fun.php";
				include_once M_ROOT."./include/fields.cls.php";
				include_once M_ROOT."./include/arcedit.cls.php";
				include_once M_ROOT."./include/cheader.inc.php";
				_header();
				
				$aedit = new cls_arcedit;
				$aedit->set_aid($aid);
				$aedit->detail_data();
				$citems = empty($commu['setting']['citems']) ? array() : explode(',',$commu['setting']['citems']);
				if(!$oldmsg = $db->fetch_one("SELECT * FROM {$tblprefix}comments WHERE mid='$memberid' AND cuid='$commu[cuid]' ORDER BY cid DESC LIMIT 0,1")) $oldmsg = array();
				tabheader(lang('add').$commu['cname'],'commentadd',"?aid=$aid".($qtid ? "&qtid=$qtid" : '')."$forwardstr",2,1,1);
				$submitstr = '';
		
				foreach($ucotypes as $k => $v){
					if(in_array('uccid'.$k,$citems) && $v['umode'] != 2){
						trbasic($v['cname'],'',mu_cnselect("communew[uccid$k]",@$oldmsg['uccid'.$k],$k,lang('p_choose'),$v['emode'],"communew[uccid{$k}date]",@$oldmsg["uccid{$k}date"] ? date('Y-m-d',$oldmsg["uccid{$k}date"]) : ''),'');
						$submitstr .= makesubmitstr("communew[uccid$k]",$v['notblank'],0,0,0,'common');
						$v['emode'] == 2 && $submitstr .= makesubmitstr("communew[uccid{$k}date]",1,0,0,0,'date');
					}
				}
				$a_field = new cls_field;
				foreach($cfields as $k => $v){
					if(!$v['isadmin'] && !$v['isfunc'] && in_array($k,$citems)){
						$a_field->init();
						$a_field->field = $v;
						if(isset($oldmsg[$k])){
							$a_field->oldvalue = $oldmsg[$k];
						}else $a_field->isadd = 1;
						$a_field->trfield('communew','','c');
						$submitstr .= $a_field->submitstr;
					}
				}
				unset($a_field);
				$submitstr .= tr_regcode('comment');
				tabfooter('newcommu');
				check_submit_func($submitstr);
				_footer();
			}else{
				$_da = &$arc->archive;
				arc_parse($_da);
				
				_aenter($_da,1);
				@extract($btags);
				extract($_da,EXTR_OVERWRITE);
				tpl_refresh($tplname);
				@include M_ROOT."template/$templatedir/pcache/$tplname.php";
				
				$_content = ob_get_contents();
				ob_clean();
				mexit($_content);
			}
		}else{//数据处理
			load_cache('cfields,ucotypes');
			include_once M_ROOT."./include/fields.cls.php";
			include_once M_ROOT."./include/upload.cls.php";
			include_once M_ROOT."./include/arcedit.cls.php";
			include_once M_ROOT."./include/cuedit.cls.php";
			include_once M_ROOT."./include/cheader.inc.php";
			$inajax ? aheader() : _header();
			if(!regcode_pass('comment',empty($regcode) ? '' : trim($regcode))) mcmessage('regcodeerror',axaction(2,M_REFERER));
			if(!$curuser->checkforbid('comment')) mcmessage('userisforbid',axaction(2,M_REFERER));//屏蔽组
			$aedit = new cls_arcedit;
			$aedit->set_aid($aid);
			$aedit->basic_data();
			if(!$aedit->aid) mcmessage('choosecommentobject',axaction(2,M_REFERER));
			if(!$aedit->archive['checked']) mcmessage('poinarcnoche');
			if(!$aedit->channel['comment'] || !($commu = read_cache('commu',$aedit->channel['comment']))) mcmessage('setcomitem',axaction(2,M_REFERER));
			if(!$curuser->pmbypmids('cuadd',$commu['setting']['apmid'])) mcmessage('younoitempermis',axaction(2,M_REFERER));
			
			if(empty($commu['setting']['repeat']) || !empty($commu['setting']['repeattime'])){
				if(empty($m_cookie['08cms_cuid_'.$commu['cuid'].'_'.$aid])){
					msetcookie('08cms_cuid_'.$commu['cuid'].'_'.$aid,'1',empty($commu['setting']['repeat']) ? 365 * 24 * 3600 : $commu['setting']['repeattime'] * 60);
				}else mcmessage(!empty($commu['setting']['repeat']) ? 'overquick' : 'norepeatoper',axaction(2,M_REFERER));
			}
			
			if(!($maxfloorid = $db->result_one("SELECT MAX(floorid) FROM {$tblprefix}comments WHERE aid='$aid'"))) $maxfloorid = 0;
			$maxfloorid ++;
			$quoteids = '';
			if($qtid && $r = $db->fetch_one("SELECT quoteids FROM {$tblprefix}comments WHERE aid='$aid' AND cid='$qtid'")) $quoteids = ($r['quoteids'] ? $r['quoteids'].',' : '').$qtid;
			$db->query("INSERT INTO {$tblprefix}comments SET
				aid='$aid',
				cuid='$commu[cuid]',
				mid='$memberid',
				mname='".$curuser->info['mname']."',
				ip='$onlineip',
				checked='".($commu['setting']['autocheck'] ? 1 : 0)."',
				floorid = '$maxfloorid',
				quoteids = '$quoteids',
				createdate='$timestamp'
			");
			if($cid = $db->insert_id()){
				if($commu['allowance']) $curuser->updatefield('cuaddmonth',$curuser->info['cuaddmonth'] + 1,'main');//限额文档统计
				$curuser->basedeal('comment',1,1,1);
				$aedit->arc_nums('comments',1,1);
		
				$uedit = new cls_cuedit;
				$uedit->read($cid,'comment');
				foreach(array('citems','fields',) as $var) $$var = &$uedit->$var;
				$c_upload = new cls_upload;	
				$fields = fields_order($fields);
				$a_field = new cls_field;
				foreach($ucotypes as $k => $v){
					if(in_array('uccid'.$k,$citems) && $v['umode'] != 2){
						if($v['notblank'] && empty($communew['uccid'.$k])){
							$uedit->delete();
							mcmessage('notnull',axaction(2,M_REFERER),$v['cname']);
						}
						$uedit->updatefield('uccid'.$k,$communew['uccid'.$k]);
						if($v['emode']){
							$communew["uccid{$k}date"] = !isdate($communew["uccid{$k}date"]) ? 0 : strtotime($communew["uccid{$k}date"]);
							if($uedit->info["uccid$k"] && !$communew["uccid{$k}date"] && $v['emode'] == 2){
								$uedit->delete();
								mcmessage('setcoclass',axaction(2,M_REFERER),$v['cname']);
							}
							!$uedit->info["uccid$k"] && $communew["uccid{$k}date"] = 0;
							$uedit->updatefield("uccid{$k}date",$communew["uccid{$k}date"]);
						}
					}
				}
				foreach($fields as $k => $v){
					if(!$v['isfunc'] && !$v['isadmin']){
						$a_field->init();
						$a_field->field = $v;
						if($curuser->pmbypmids('field',$v['pmid'])){
							$a_field->oldvalue = '';
							$a_field->deal('communew');
							if(!empty($a_field->error)){
								$c_upload->rollback();
								$uedit->delete();
								mcmessage($a_field->error,axaction(2,M_REFERER));
							}
							$uedit->updatefield($k,$a_field->newvalue);
							if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $uedit->updatefield($k.'_'.$x,$y);
						}
					}
				}
				unset($a_field);
			
				$c_upload->saveuptotal(1);
				$uedit->updatedb();
			}
			$c_upload->closure(1, $cid, 'comments');
			mcmessage('submitsucceed',axaction(10,$forward));
		}
	}else include(M_ROOT.$commu['ucadd']);
}elseif($action == 'vote'){
	$inajax = empty($inajax) ? 0 : 1;
	$cid = empty($cid) ? 0 : max(0,intval($cid));
	if(!$cid) cumessage('choosevoteobject');
	if(!$row = $db->fetch_one("SELECT * FROM {$tblprefix}comments WHERE cid='$cid'")) cumessage('choosevoteobject',$forward);
	if(!($commu = read_cache('commu',$row['cuid']))) cumessage('setcomitem',$forward);
	if(empty($commu['ucvote'])){
		if(!empty($commu['setting']['nouservote']) && !$memberid) cumessage('loginmember',$forward);
		if(empty($commu['setting']['repeatvote'])){
			if(empty($m_cookie['08cms_cuid_'.$commu['cuid'].'_vote_'.$aid.'_'.$cid])){
				msetcookie('08cms_cuid_'.$commu['cuid'].'_vote_'.$aid.'_'.$cid,'1',365 * 24 * 3600);
			}else cumessage('dontnrepeatvote',$forward);
		}
		$option = empty($option) ? 1 : min(5,max(1,intval($option)));
		$db->query("UPDATE {$tblprefix}comments SET votes$option = votes$option + 1 WHERE cid='$cid'",'SILENT');
		cumessage($inajax ? 'succeed' : 'votesucceed',$forward);
	}else include(M_ROOT.$commu['ucvote']);
}

?>

