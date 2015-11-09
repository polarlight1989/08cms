<?php
!defined('M_COM') && exit('No Permission');
load_cache('mtconfigs,grouptypes,rprojects,acatalogs,cotypes,mchannels');
//分析页面设置
$nmuid = empty($nmuid) ? 0 : max(0,intval($nmuid));
if($nmuid && $u_url = read_cache('murl',$nmuid)){
	$u_tplname = $u_url['tplname'];
	$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
	$u_mtitle = $u_url['mtitle'];
	$u_guide = $u_url['guide'];
	$vars = array('lists',);
	foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
}
if(empty($u_tplname) || !empty($u_onlyview)){
	include_once M_ROOT."./include/upload.cls.php";
	include_once M_ROOT."./include/fields.cls.php";
	$curuser->detail_data();
	$mchid = $curuser->info['mchid'];
	$mchannel = $mchannels[$mchid];
	foreach(array('useredits') as $var) $$var = $mchannel[$var] ? explode(',',$mchannel[$var]) : array();
	$mfields = read_cache('mfields',$mchid);
	if(!submitcheck('bmemberdetail')){
		$a_field = new cls_field;
		$submitstr = '';
		$no_view = true;
		tabheader(empty($u_mtitle) ? lang('baseoption') : $u_mtitle,'memberdetail',"?action=memberinfo&nmuid=$nmuid",2,1,1);
		if(empty($u_lists) || in_array('email',$u_lists)){
			trbasic('*'.lang('email'),'minfosnew[email]',$curuser->info['email']);
			$submitstr .= makesubmitstr('minfosnew[email]',1,'email',0,50);
		}
		if(empty($u_lists) || in_array('mtcid',$u_lists)){
			$noedit = noedit('mtcid');
			trbasic(lang('spacetemplateproject').$noedit,'minfosnew[mtcid]',makeoption(mtcidsarr($mchid),$curuser->info['mtcid']),'select');
		}
		foreach($grouptypes as $k => $v){
			if(empty($u_lists) || in_array("grouptype$k",$u_lists)){
				if(!$v['mode'] && !in_array($mchid,explode(',',$v['mchids']))){
					$noedit = noedit("grouptype$k");
					trbasic(lang('usergroup').$noedit,"minfosnew[grouptype$k]",makeoption(ugidsarr($k,$mchid),$curuser->info["grouptype$k"]),'select');
				}
			}
		}
		foreach($mfields as $k => $field){
			if(empty($u_lists) || in_array($k,$u_lists)){
				if($field['available'] && !$field['issystem'] && !$field['isfunc'] && !$field['isadmin']){
					$a_field->init();
					$a_field->field = $field;
					$noedit = noedit($k,!$curuser->pmbypmids('field',$a_field->field['pmid']));
					$a_field->oldvalue = isset($curuser->info[$k]) ? $curuser->info[$k] : '';
					$a_field->trfield('minfosnew',$noedit,'m',$mchid);
					!$noedit && $submitstr .= $a_field->submitstr;
				}
			}
		}
		unset($a_field);
		tabfooter('bmemberdetail');
		check_submit_func($submitstr);
		m_guide(@$u_guide);
	}else{
		if(empty($u_lists) || in_array('email',$u_lists)){
			$minfosnew['email'] = empty($minfosnew['email']) ? '' : trim($minfosnew['email']);
			if(empty($minfosnew['email']) || !isemail($minfosnew['email'])) mcmessage('mememill',M_REFERER);
			$curuser->updatefield('email',$minfosnew['email'],'main');
		}
		if(empty($u_lists) || in_array('mtcid',$u_lists)){
			if(!noedit('mtcid')){
				$curuser->updatefield('mtcid',empty($minfosnew['mtcid']) ? 0 : $minfosnew['mtcid'],'main');
			}
		}
		foreach($grouptypes as $k => $v) {
			if(empty($u_lists) || in_array("grouptype$k",$u_lists)){
				if(!$v['mode'] && !in_array($mchid,explode(',',$v['mchids'])) && !noedit("grouptype$k")){
					$curuser->handgrouptype($k,empty($minfosnew['grouptype'.$k]) ? 0 : $minfosnew['grouptype'.$k],-1);
				}
			}
		}
	
		$c_upload = new cls_upload;	
		$mfields = fields_order($mfields);
		$a_field = new cls_field;
		foreach($mfields as $k => $v){
			if(empty($u_lists) || in_array($k,$u_lists)){
				if($v['available'] && !$v['issystem'] && !$v['isfunc'] && !$v['isadmin']){
					if(noedit($k,!$curuser->pmbypmids('field',$v['pmid']))) continue;
					$a_field->init();
					$a_field->field = $v;
					$a_field->oldvalue = isset($curuser->info[$k]) ? $curuser->info[$k] : '';
					$a_field->deal('minfosnew');
					if(!empty($a_field->error)){
						$c_upload->rollback();
						mcmessage($a_field->error,M_REFERER);
					}
					$curuser->updatefield($k,$a_field->newvalue,$v['tbl']);
					if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $curuser->updatefield($k.'_'.$x,$y,$v['tbl']);
				}
			}
		}
		unset($a_field);
		$curuser->updatedb();
		$c_upload->closure(1, $memberid, 'members');
		$c_upload->saveuptotal(1);
		mcmessage('memmesmodfin',M_REFERER);
	
	}
}else include(M_ROOT.$u_tplname);
?>
