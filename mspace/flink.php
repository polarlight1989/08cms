<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
if(!($mcommu = read_cache('mcommu',3))) message('setmemcommitem');
if(empty($mcommu['ucadd'])){
	$mid = empty($mid) ? 0 : max(0,intval($mid));
	if(!$mid) message('chooseflinkofmember');
	$actuser = new cls_userinfo;
	$actuser->activeuser($mid);
	if(!$actuser->info['mid']) message('chooseflinkofmember');
	if(!$curuser->pmbypmids('cuadd',$mcommu['setting']['apmid'])) message('younoflinkpermis');
	$fieldsarr = empty($mcommu['setting']['fields']) ? array() : explode(',',$mcommu['setting']['fields']);
	
	if(!submitcheck('newcommu')){
		if(!empty($mcommu['setting']['norepeat']) && $cid = $db->result_one("SELECT cid FROM {$tblprefix}mflinks WHERE mid='$mid' AND fromid='$memberid' ORDER BY cid")){
			 message('dorepeataddflink');
		}
		if(empty($mcommu['addtpl']) || !($template = load_tpl($mcommu['addtpl']))){
			load_cache('mlangs,mlfields');
			include_once M_ROOT."./include/fields.cls.php";
			include_once M_ROOT."./include/cheader.inc.php";
			_header();
			
			if(!$oldmsg = $db->fetch_one("SELECT * FROM {$tblprefix}mflinks WHERE fromid='$memberid' ORDER BY cid DESC LIMIT 0,1")) $oldmsg = array();
			tabheader(lang('add').$mcommu['cname'],'flinkadd',"?mid=$mid$forwardstr",2,1,1);
			$submitstr = '';
			$a_field = new cls_field;
			foreach($mlfields as $k => $v){
				if(!$v['isadmin'] && !$v['isfunc'] && in_array($k,$fieldsarr)){
					$a_field->init();
					$a_field->field = $v;
					if(isset($oldmsg[$k])){
						$a_field->oldvalue = $oldmsg[$k];
					}else $a_field->isadd = 1;
					$a_field->trfield('communew','','ml');
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
		load_cache('mlfields');
		include_once M_ROOT."./include/fields.cls.php";
		include_once M_ROOT."./include/upload.cls.php";
		include_once M_ROOT."./include/cheader.inc.php";
		include_once M_ROOT."./include/mcuedit.cls.php";
		$inajax ? aheader() : _header();
	
		if(!empty($mcommu['setting']['norepeat']) && $cid = $db->result_one("SELECT cid FROM {$tblprefix}mflinks WHERE mid='$mid' AND fromid='$memberid' ORDER BY cid")){
			mcmessage('dorepeataddflink',axaction(2,M_REFERER));
		}
		$db->query("INSERT INTO {$tblprefix}mflinks SET
			mid='$mid',
			mname='".$actuser->info['mname']."',
			fromid='$memberid',
			fromname='".$curuser->info['mname']."',
			createdate='$timestamp'
			");
		if($cid = $db->insert_id()){
			$uedit = new cls_mcuedit;
			$uedit->read($cid,'flink');
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
		$c_upload->closure(1, $cid, 'mflinks');
		mcmessage('submitsucceed',axaction(10,$forward));
	}
}else include(M_ROOT.$mcommu['ucadd']);
?>