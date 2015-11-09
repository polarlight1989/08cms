<?
!defined('M_COM') && exit('No Permission');
load_cache('fcatalogs,fchannels,currencys,');
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/farcedit.cls.php";

empty($aid) && mcmessage('choosemesid');
$fcaid = $db->result_one("SELECT fcaid FROM {$tblprefix}farchives WHERE aid='$aid'");
if(!($fcatalog = read_cache('fcatalog',$fcaid))) mcmessage('choosearctype');
if(empty($fcatalog['umdetail'])){
	$aedit = new cls_farcedit;
	$aedit->set_aid($aid);
	$aedit->detail_data();
	!$aedit->aid && mcmessage('choosemessage');
	$chid = $aedit->channel['chid'];
	$fcatalog = &$aedit->catalog;
	$fields = read_cache('ffields',$chid);
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.urlencode($forward);
	if(!submitcheck('bfarchivedetail')){
		$a_field = new cls_field;
		tabheader($fcatalog['title'].'-'.lang('content_set'),'farchivedetail',"?action=farchive&aid=$aid$forwardstr",2,1,1,1);
		$submitstr = '';
		$subject_table = 'farchives';
		foreach($fields as $k => $field){
			if(!$field['isadmin'] && !$field['isfunc']){
				$a_field->init();
				$a_field->field = $field;
				$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
				$a_field->trfield('farchivenew','','f',$chid);
				$submitstr .= $a_field->submitstr;
			}
		}
		unset($a_field);
		if(empty($fcatalog['nodurat'])){
			foreach(array('startdate','enddate') as $var){
				trbasic(lang($var),"farchivenew[$var]",$aedit->archive[$var] ? date('Y-m-d',$aedit->archive[$var]) : '','calendar');
				$submitstr .= makesubmitstr("farchivenew[$var]",0,0,0,0,'date');
			}
		}
		tabfooter('bfarchivedetail');
		check_submit_func($submitstr);
	}else{
		if($aedit->archive['checked'] && !$fcatalog['allowupdate']) mcmessage('msgforbidupdate',axaction(2,M_REFERER));	
		if(empty($fcatalog['nodurat'])){
			foreach(array('startdate','enddate') as $var){
				$farchivenew[$var] = trim($farchivenew[$var]);
				$farchivenew[$var] = !isdate($farchivenew[$var]) ? 0 : strtotime($farchivenew[$var]);
				$aedit->updatefield($var,max(0,intval($farchivenew[$var])),'main');
			}
		}
		$c_upload = new cls_upload;	
		$fields = fields_order($fields);
		$a_field = new cls_field;
		foreach($fields as $k => $v){
			if(!$v['isadmin'] && !$v['isfunc']){
				$a_field->init();
				$a_field->field = $v;
				$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
				$a_field->deal('farchivenew');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					mcmessage($a_field->error,axaction(2,M_REFERER));
				}
				$aedit->updatefield($k,$a_field->newvalue,$v['issystem'] ? 'main' : 'custom');
				if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $aedit->updatefield($k.'_'.$x,$y,$v['issystem'] ? 'main' : 'custom');
			}
		}
		unset($a_field);
	
		$aedit->updatedb();
		$c_upload->closure(1, $aid, 'farchives');
		$c_upload->saveuptotal(1);
		mcmessage('freeinfoeditfinish',axaction(10,$forward));
	
	}
}else include(M_ROOT.$fcatalog['umdetail']);
?>
