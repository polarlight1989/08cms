<?
//本页面只是修改回复资料
!defined('M_COM') && exit('No Permission');
load_cache('currencys,mrfields,mchannels');
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/mcuedit.cls.php";
$cid = empty($cid) ? 0 : max(0,intval($cid));
$cuid = $db->result_one("SELECT cuid FROM {$tblprefix}mreplys WHERE cid='$cid'");
if(!$cuid || !($mcommu = read_cache('mcommu',$cuid))) mcmessage('setcommuitem');
if(empty($mcommu['umdetail'])){
	$catalogs = &$acatalogs;
	$amode = empty($amode) ? 0 : 1;
	
	$uedit = new cls_mcuedit;
	if($errno = $uedit->read($cid,'reply')){
		if($errno == 1) mcmessage('choosereply');
		if($errno == 2) mcmessage('choosereplyobject');
		if($errno == 3) mcmessage('setcommuitem');
	}
	if($amode){
		if($uedit->info['mid'] != $memberid) mcmessage('yntrap');
	}else{
		if($uedit->info['fromid'] != $memberid) mcmessage('yntrap');
	}
	
	foreach(array('mcommu','fields',) as $var) $$var = &$uedit->$var;
	$oldrow = &$uedit->info;
	$freeupdate = $curuser->check_allow('freeupdatecheck') || !$oldrow['checked'] || $amode;
	if(!submitcheck('newcommu')){
		tabheader($mcommu['cname'].'&nbsp; &nbsp; '."<a href=\"{$mspaceurl}index.php?mid=".$oldrow[$amode ? 'fromid' : 'mid']."\" target=\"_blank\">>>&nbsp; ".$oldrow[$amode ? 'fromname' : 'mname']."</a>",'commudetail',"?action=mreply&amode=$amode&cid=$cid",2,1,1);
		$submitstr = '';
		$a_field = new cls_field;
		foreach($fields as $k => $v){
			if(empty($u_lists) || in_array($k,$u_lists)){
				if(!$v['isfunc']){
					$a_field->init();
					$a_field->field = $v;
					$a_field->oldvalue = $oldrow[$k];
					$noedit = noedit($k,(!$amode && $v['isadmin']) || !$curuser->pmbypmids('field',$v['pmid']));
					$a_field->trfield('communew',$noedit,'','mr');
					!$noedit && $submitstr .= $a_field->submitstr;
				}
			}
		}
		unset($a_field);
		tabfooter('newcommu');
		check_submit_func($submitstr);
		$db->query("UPDATE {$tblprefix}mreplys SET ".($amode ? "aread='1'" : "uread='1'")." WHERE cid='$cid'");
	}else{
		$c_upload = new cls_upload;	
		$fields = fields_order($fields);
		$a_field = new cls_field;
		foreach($fields as $k => $v){
			if(empty($u_lists) || in_array($k,$u_lists)){
				if(!$v['isfunc'] && ($amode || !$v['isadmin'])){
					$a_field->init();
					$a_field->field = $v;
					if(!noedit($k,!$curuser->pmbypmids('field',$v['pmid']))){
						$a_field->oldvalue = isset($oldrow[$k]) ? $oldrow[$k] : '';
						$a_field->deal('communew');
						if(!empty($a_field->error)){
							$c_upload->rollback();
							mcmessage($a_field->error,M_REFERER);
						}
						$uedit->updatefield($k,$a_field->newvalue);
						if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $uedit->updatefield($k.'_'.$x,$y);
					}
				}
			}
		}
		unset($a_field);
		if($amode) $uedit->updatefield('areply',1);
	
		$c_upload->closure(1, $cid, 'mreplys');
		$c_upload->saveuptotal(1);
		$uedit->updatedb();
		mcmessage('updatesucceed',axaction(6,M_REFERER),$mcommu['cname']);
	}
}
?>