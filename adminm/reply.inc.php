<?
!defined('M_COM') && exit('No Permission');
load_cache('channels,acatalogs,currencys,commus,rfields,ucotypes');
$cid = empty($cid) ? 0 : max(0,intval($cid));
$amode = empty($amode) ? 0 : 1;
$cuid = $db->result_one("SELECT cuid FROM {$tblprefix}replys WHERE cid='$cid'");
if(!$cuid || !($commu = read_cache('commu',$cuid))) exit('setcomitem');
if(empty($commu['umdetail'])){
	$nimuid = empty($nimuid) ? 0 : max(0,intval($nimuid));
	if($nimuid && $u_url = read_cache('inmurl',$nimuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$u_mtitle = $u_url['mtitle'];
		$u_guide = $u_url['guide'];
		$vars = array('lists',);
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	if(empty($u_tplname) || !empty($u_onlyview)){
		include_once M_ROOT."./include/fields.cls.php";
		include_once M_ROOT."./include/upload.cls.php";
		include_once M_ROOT."./include/cuedit.cls.php";
		$catalogs = &$acatalogs;
		
		$uedit = new cls_cuedit;
		if($errno = $uedit->read($cid,'reply')){
			if($errno == 1) mcmessage('choosereply');
			if($errno == 2) mcmessage('choosereplyobject');
			if($errno == 3) mcmessage('setcommuitem');
		}
		if($amode){
			if($uedit->info['a_mid'] != $memberid) mcmessage('younoreplypermi');
		}else{
			if($uedit->info['mid'] != $memberid) mcmessage('yntrap');
		}
		
		foreach(array('aid','commu','citems','fields','useredits',) as $var) $$var = &$uedit->$var;
		$oldrow = &$uedit->info;
		$freeupdate = $curuser->check_allow('freeupdatecheck') || !$oldrow['checked'] || $amode;
		if(!submitcheck('newcommu')){
			if(empty($u_tplname)){
				tabheader((empty($u_mtitle) ? $commu['cname'] : $u_mtitle).'&nbsp; &nbsp; '."<a href=\"".view_arcurl($oldrow)."\" target=\"_blank\">>>&nbsp; ".$oldrow['subject']."</a>",'commudetail',"?action=reply&amode=$amode&cid=$cid",2,1,1);
				$submitstr = '';
				foreach($ucotypes as $k => $v){
					if(empty($u_lists) || in_array("uccid$k",$u_lists)){
						if(in_array('uccid'.$k,$citems)){
							$noedit = $amode ? noedit('uccid'.$k,$v['umode'] == 1) : noedit('uccid'.$k,$v['umode'] == 2);
							trbasic($v['cname'].$noedit,'',mu_cnselect("replynew[uccid$k]",$oldrow['uccid'.$k],$k,lang('p_choose'),$v['emode'],"replynew[uccid{$k}date]",@$oldrow["uccid{$k}date"] ? date('Y-m-d',$oldrow["uccid{$k}date"]) : ''),'');
							!$noedit && $submitstr .= makesubmitstr("replynew[uccid$k]",$v['notblank'],0,0,0,'common');
							!$noedit && $v['emode'] == 2 && $submitstr .= makesubmitstr("replynew[uccid{$k}date]",1,0,0,0,'date');
						}
					}
				}
				$a_field = new cls_field;
				foreach($fields as $k => $v){
					if(empty($u_lists) || in_array($k,$u_lists)){
						if(!$v['isfunc']){
							$a_field->init();
							$a_field->field = $v;
							$a_field->oldvalue = $oldrow[$k];
							$noedit = noedit($k,(!$amode && $v['isadmin']) || !$curuser->pmbypmids('field',$v['pmid']));
							$a_field->trfield('replynew',$noedit,'r');
							!$noedit && $submitstr .= $a_field->submitstr;
						}
					}
				}
				unset($a_field);
				tabfooter('newcommu');
				check_submit_func($submitstr);
				m_guide(@$u_guide);
			}else include(M_ROOT.$u_tplname);
			$db->query("UPDATE {$tblprefix}replys SET ".($amode ? "aread='1'" : "uread='1'")." WHERE cid='$cid'");
		}else{
			$c_upload = new cls_upload;	
			$fields = fields_order($fields);
			$a_field = new cls_field;
			foreach($ucotypes as $k => $v){
				if(isset($replynew['uccid'.$k])){
					if(in_array('uccid'.$k,$citems)){
						$noedit = $amode ? noedit('uccid'.$k,$v['umode'] == 1) : noedit('uccid'.$k,$v['umode'] == 2);
						!$noedit && $uedit->updatefield('uccid'.$k,$replynew['uccid'.$k]);
						if(!$noedit && $v['emode']){
							$replynew["uccid{$k}date"] = !isdate($replynew["uccid{$k}date"]) ? 0 : strtotime($replynew["uccid{$k}date"]);
							if($uedit->info["uccid$k"] && !$replynew["uccid{$k}date"] && $v['emode'] == 2) mcmessage('setcoclass',axaction(2,M_REFERER),$v['cname']);
							!$uedit->info["uccid$k"] && $replynew["uccid{$k}date"] = 0;
							$uedit->updatefield("uccid{$k}date",$replynew["uccid{$k}date"]);
						}
					}
				}
			}
			foreach($fields as $k => $v){
				if(isset($replynew[$k])){
					if(!$v['isfunc'] && ($amode || !$v['isadmin'])){
						$a_field->init();
						$a_field->field = $v;
						if(!noedit($k,!$curuser->pmbypmids('field',$v['pmid']))){
							$a_field->oldvalue = isset($oldrow[$k]) ? $oldrow[$k] : '';
							$a_field->deal('replynew');
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
		
			$c_upload->closure(1, $cid, 'replys');
			$c_upload->saveuptotal(1);
			$uedit->updatedb();
			mcmessage('updatesucceed',axaction(6,M_REFERER),$commu['cname']);
		}
	}else include(M_ROOT.$u_tplname);
}else include(M_ROOT.$commu['umdetail']);
?>