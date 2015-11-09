<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);

$mid = empty($mid) ? 0 : max(0,intval($mid));
if(!$mid) message('choosecommentofmember');
$cuid = $db->result_one("SELECT c.comment FROM {$tblprefix}members m LEFT JOIN {$tblprefix}mchannels c ON c.mchid=m.mchid WHERE m.mid='$mid'");
if(!$cuid || !($mcommu = read_cache('mcommu',$cuid))) message('setmemcommitem');
$qtid = max(0,intval(@$qtid));
if(empty($mcommu['ucadd'])){
	$actuser = new cls_userinfo;
	$actuser->activeuser($mid);
	if(!$actuser->info['mid']) message('choosecommentofmember');
	if(!$curuser->pmbypmids('cuadd',$mcommu['setting']['apmid'])) message('younocommentpermis');
	$fieldsarr = empty($mcommu['setting']['fields']) ? array() : explode(',',$mcommu['setting']['fields']);
	
	if(!submitcheck('newcommu')){
		if(!empty($mcommu['setting']['norepeat']) || !empty($mcommu['setting']['repeattime'])){
			if(!empty($m_cookie['08cms_mcuid_'.$mcommu['cuid'].'_'.$mid])) message(empty($mcommu['setting']['norepeat']) ? 'addcommentoverquick' : 'dorepeataddcomment');
		}
		if(!($tplname = @$mcommu['addtpl'])){
			load_cache('mlangs,mcfields');
			include_once M_ROOT."./include/fields.cls.php";
			include_once M_ROOT."./include/cheader.inc.php";
			_header();
			
			if(!$oldmsg = $db->fetch_one("SELECT * FROM {$tblprefix}mcomments WHERE fromid='$memberid' AND cuid='$mcommu[cuid]' ORDER BY cid DESC LIMIT 0,1")) $oldmsg = array();
			tabheader(lang('add').$mcommu['cname'],'commentadd',"?mid=$mid".($qtid ? "&qtid=$qtid" : '')."$forwardstr",2,1,1);
			$submitstr = '';
			$a_field = new cls_field;
			foreach($mcfields as $k => $v){
				if(!$v['isadmin'] && !$v['isfunc'] && in_array($k,$fieldsarr)){
					$a_field->init();
					$a_field->field = $v;
					if(isset($oldmsg[$k])){
						$a_field->oldvalue = $oldmsg[$k];
					}else $a_field->isadd = 1;
					$a_field->trfield('communew','','mc');
					$submitstr .= $a_field->submitstr;
				}
			}
			unset($a_field);
			tabfooter('newcommu');
			check_submit_func($submitstr);
			_footer();
		}else{
			$_da = &$actuser->info;
			_aenter($_da,1);
			@extract($btags);
			extract($_da,EXTR_OVERWRITE);
			tpl_refresh($tplname);
			@include M_ROOT."template/$templatedir/pcache/$tplname.php";
			
			$_content = ob_get_contents();
			ob_clean();
			mexit($_content);
		}
		
	}else{
		load_cache('mcfields');
		include_once M_ROOT."./include/fields.cls.php";
		include_once M_ROOT."./include/upload.cls.php";
		include_once M_ROOT."./include/cheader.inc.php";
		include_once M_ROOT."./include/mcuedit.cls.php";
		$inajax ? aheader() : _header();
		if(!empty($mcommu['setting']['norepeat']) || !empty($mcommu['setting']['repeattime'])){
			if(empty($m_cookie['08cms_mcuid_'.$mcommu['cuid'].'_'.$mid])){
				msetcookie('08cms_mcuid_'.$mcommu['cuid'].'_'.$mid,'1',!empty($mcommu['setting']['norepeat']) ? 365 * 24 * 3600 : $mcommu['setting']['repeattime'] * 60);
			}else mcmessage(empty($mcommu['setting']['norepeat']) ? 'addcommentoverquick' : 'dorepeataddcomment',axaction(2,M_REFERER));
		}
		if(!($maxfloorid = $db->result_one("SELECT MAX(floorid) FROM {$tblprefix}mcomments WHERE mid='$mid'"))) $maxfloorid = 0;
		$maxfloorid ++;
		$quoteids = '';
		if($qtid && $r = $db->fetch_one("SELECT quoteids FROM {$tblprefix}mcomments WHERE mid='$mid' AND cid='$qtid'")) $quoteids = ($r['quoteids'] ? $r['quoteids'].',' : '').$qtid;
	
		$db->query("INSERT INTO {$tblprefix}mcomments SET
			mid='$mid',
			mname='".$actuser->info['mname']."',
			cuid='$mcommu[cuid]',
			fromid='$memberid',
			fromname='".$curuser->info['mname']."',
			checked='".($mcommu['setting']['autocheck'] ? 1 : 0)."',
			floorid = '$maxfloorid',
			quoteids = '$quoteids',
			createdate='$timestamp'
			");
		if($cid = $db->insert_id()){
			$uedit = new cls_mcuedit;
			$uedit->read($cid,'comment');
			foreach(array('fields',) as $var) $$var = &$uedit->$var;
			$c_upload = new cls_upload;	
			$fields = fields_order($fields);
			$a_field = new cls_field;
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
		$c_upload->closure(1, $cid, 'mcomments');
		mcmessage('submitsucceed',axaction(10,$forward));
	}
}else include(M_ROOT.$mcommu['ucadd']);
?>