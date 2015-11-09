<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);

$mid = empty($mid) ? 0 : max(0,intval($mid));
if(!$mid) message('choosereportofmember');
if(!($mcommu = read_cache('mcommu',6))) message('setmemcommitem');
if(empty($mcommu['ucadd'])){
	$actuser = new cls_userinfo;
	$actuser->activeuser($mid);
	if(!$actuser->info['mid']) message('choosereportofmember');
	if(!$curuser->pmbypmids('cuadd',$mcommu['setting']['apmid'])) message('younoreportpermis');
	$fieldsarr = empty($mcommu['setting']['fields']) ? array() : explode(',',$mcommu['setting']['fields']);
	
	if(!submitcheck('newcommu')){
		//对重复回复及频繁回复的处理
		if(!empty($mcommu['setting']['norepeat']) && $cid = $db->result_one("SELECT cid FROM {$tblprefix}mreports WHERE mid='$mid' AND fromid='$memberid' ORDER BY cid")){
			 message('dorepeataddreport');
		}
		if(!($tplname = @$mcommu['addtpl'])){
			load_cache('mlangs,mbfields');
			include_once M_ROOT."./include/fields.cls.php";
			include_once M_ROOT."./include/cheader.inc.php";
			_header();
			
			if(!$oldmsg = $db->fetch_one("SELECT * FROM {$tblprefix}mreports WHERE fromid='$memberid' ORDER BY cid DESC LIMIT 0,1")) $oldmsg = array();
			tabheader(lang('add').$mcommu['cname'],'reportadd',"?mid=$mid$forwardstr",2,1,1);
			$submitstr = '';
			$a_field = new cls_field;
			foreach($mbfields as $k => $v){
				if(!$v['isadmin'] && !$v['isfunc'] && in_array($k,$fieldsarr)){
					$a_field->init();
					$a_field->field = $v;
					if(isset($oldmsg[$k])){
						$a_field->oldvalue = $oldmsg[$k];
					}else $a_field->isadd = 1;
					$a_field->trfield('communew','','mb');
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
		load_cache('mbfields');
		include_once M_ROOT."./include/fields.cls.php";
		include_once M_ROOT."./include/upload.cls.php";
		include_once M_ROOT."./include/cheader.inc.php";
		include_once M_ROOT."./include/mcuedit.cls.php";
		$inajax ? aheader() : _header();
	
		//分析是否允许重复添加
		if(!empty($mcommu['setting']['norepeat']) && $cid = $db->result_one("SELECT cid FROM {$tblprefix}mreports WHERE mid='$mid' AND fromid='$memberid' ORDER BY cid")){
			mcmessage('dorepeataddreport',axaction(2,M_REFERER));
		}
	
		$db->query("INSERT INTO {$tblprefix}mreports SET
			mid='$mid',
			mname='".$actuser->info['mname']."',
			fromid='$memberid',
			fromname='".$curuser->info['mname']."',
			createdate='$timestamp'
			");
		if($cid = $db->insert_id()){
			$uedit = new cls_mcuedit;
			$uedit->read($cid,'report');
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
		$c_upload->closure(1, $cid, 'mreports');
		mcmessage('submitsucceed',axaction(10,$forward));
	}
}else include(M_ROOT.$mcommu['ucadd']);
?>