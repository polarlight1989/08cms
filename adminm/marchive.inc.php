<?php
!defined('M_COM') && exit('No Permission');
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/marcedit.cls.php";

$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);

$maid = empty($maid) ? 0 : max(0,intval($maid));
$matid = empty($matid) ? 0 : max(0,intval($matid));
$aedit = new cls_marcedit;
$aedit->set_id($maid,$matid,0);
if(!$aedit->maid) mcmessage('choosemarchive');
if($aedit->archive['mid'] != $memberid) mcmessage('selectyoumarc');

$fields = read_cache('mafields',$matid);
if(!submitcheck('bmarchive')){
	$a_field = new cls_field;
	$submitstr = '';
	tabheader($aedit->matype['cname'].'&nbsp; -&nbsp; '.lang('contentsetting'),'marchive',"?matid=$matid&maid=$maid&action=marchive$param_suffix$forwardstr",2,1,1,1);
	foreach($fields as $k => $field){
		if($field['available'] && !$field['isadmin'] && !$field['isfunc']){
			$a_field->init();
			$a_field->field = $field;
			$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
			$a_field->trfield('marchivenew','','ma',$matid);
			$submitstr .= $a_field->submitstr;
		}
	}
	unset($a_field);

	tabfooter('bmarchive');
	check_submit_func($submitstr);
}else{
	$c_upload = new cls_upload;	
	$fields = fields_order($fields);
	$a_field = new cls_field;
	foreach($fields as $k => $v){
		if($v['available'] && !$v['isadmin'] && !$v['isfunc']){
			$a_field->init();
			$a_field->field = $v;
			$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
			$a_field->deal('marchivenew');
			if(!empty($a_field->error)){
				$c_upload->rollback();
				mcmessage($a_field->error,M_REFERER);
			}
			$aedit->updatefield($k,$a_field->newvalue);
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $aedit->updatefield($k.'_'.$x,$y);
		}
	}
	unset($a_field);

	$aedit->updatedb();
	$c_upload->closure(1, $maid, 'marchives');
	$c_upload->saveuptotal(1);
	mcmessage('marceditfinish',$forward);
}

?>
