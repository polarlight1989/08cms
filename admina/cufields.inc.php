<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cfcommu') || amessage('no_apermission');
load_cache('grouptypes,currencys,rprojects');
sys_cache('fieldwords');
include_once M_ROOT."./include/fields.fun.php";
$action = empty($action) ? 'pfieldsedit' : $action;
$url_type = 'cufield';include 'urlsarr.inc.php';

if($action == 'pfieldsedit'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	load_cache('pfields');
	if(!submitcheck('bpfieldsedit')){
		tabheader(lang('pu_msg_field_manager')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=cufields&action=pfieldadd\">".lang('add_field')."</a>",'pfieldsedit','?entry=cufields&action=pfieldsedit',7);
		trcategory(array(lang('delete'),lang('field_name'),lang('admin_self'),lang('order'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($pfields as $k => $field){
			fieldlist($k,$field,'p');
		}
		tabfooter('bpfieldsedit');
		a_guide('pfieldsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				dropfieldfromtbl('orders',$id,$pfields[$id]['datatype']);
				$db->query("DELETE FROM {$tblprefix}cufields WHERE ename='$id' AND cu='1'"); 
				unset($pfields[$id],$fieldsnew[$id]);
			}
		}
		foreach($pfields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['isadmin'] = empty($fieldsnew[$id]['isadmin']) ? 0 : 1;
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}cufields SET cname='$field[cname]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND cu='1'");
		}
		updatecache('pfields');
		updatecache('usednames','pfields');
		adminlog(lang('e_pu_msg_field_mlist'));
		amessage('fieldmodifyfinish','?entry=cufields&action=pfieldsedit');
	}

}elseif($action == 'pfieldadd'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	$forward = empty($forward) ? M_REFERER : $forward;
	load_cache('pfields');
	if(!submitcheck('bpfieldadd')){
		if(!$sid) url_nav(lang('docinterconfig'),$urlsarr,'p');
		tabheader(lang('add_pu_field'),'pfieldadd',"?entry=cufields&action=pfieldadd&forward=".rawurlencode($forward),2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('bpfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('cu',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bpfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('pfieldadd');
	}else{
		$enamearr = $usednames['pfields'];
		$fconfigarr = array(
			'errorurl' => $forward,
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'orders',
			'fieldtable' => $tblprefix.'cufields',
			'sqlstr' => "cu='1'",
		);
		list($fmode,$fnew,$fsave) = array('cu',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		updatecache('pfields');
		updatecache('usednames','pfields');
		adminlog(lang('add_pu_msg_field'));
		amessage('fieldaddfinish',$forward);
	}
}elseif($action == 'pfielddetail' && $fieldename){
	load_cache('pfields');
	!isset($pfields[$fieldename]) && amessage('choosefield','?entry=cufields&action=pfieldsedit');
	$field = $pfields[$fieldename];
	if(!submitcheck('bpfielddetail')){
		$submitstr = '';
		tabheader(lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'pfielddetail',"?entry=cufields&action=pfielddetail&fieldename=$fieldename",2,0,1);
		list($fmode,$fnew,$fsave) = array('cu',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bpfielddetail');
		check_submit_func($submitstr);
		a_guide('pfielddetail');
	}
	else{
		$fconfigarr = array(
			'altertable' => $tblprefix.'orders',
			'fieldtable' => $tblprefix.'cufields',
			'wherestr' => "WHERE ename='$fieldename' AND cu='1'",
		);
		list($fmode,$fnew,$fsave) = array('cu',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		updatecache('pfields');
		adminlog(lang('det_modify_pu_msg_field'));
		amessage('fieldmodifyfinish',axaction(6,'?entry=cufields&action=pfieldsedit'));
	}
}elseif($action == 'ofieldsedit'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	load_cache('ofields');
	if(!submitcheck('bofieldsedit')){
		tabheader(lang('offer_msg_field_manager')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=cufields&action=ofieldadd\">".lang('add_field')."</a>",'ofieldsedit','?entry=cufields&action=ofieldsedit',7);
		trcategory(array(lang('delete'),lang('field_name'),lang('admin_self'),lang('order'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($ofields as $k => $field){
			fieldlist($k,$field,'o');
		}
		tabfooter('bofieldsedit');
		a_guide('ofieldsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				dropfieldfromtbl('offers',$id,$ofields[$id]['datatype']);
				$db->query("DELETE FROM {$tblprefix}cufields WHERE ename='$id' AND cu='2'"); 
				unset($ofields[$id],$fieldsnew[$id]);
			}
		}
		foreach($ofields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['isadmin'] = empty($fieldsnew[$id]['isadmin']) ? 0 : 1;
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}cufields SET cname='$field[cname]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND cu='2'");
		}
		updatecache('ofields');
		updatecache('usednames','ofields');
		adminlog(lang('e_pu_msg_field_mlist'));
		amessage('fieldmodifyfinish','?entry=cufields&action=ofieldsedit');
	}

}elseif($action == 'ofieldadd'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	$forward = empty($forward) ? M_REFERER : $forward;
	load_cache('ofields');
	if(!submitcheck('bofieldadd')){
		tabheader(lang('add_offer_field'),'ofieldadd',"?entry=cufields&action=ofieldadd&forward=".rawurlencode($forward),2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('bofieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('cu',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bofieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('ofieldadd');
	}else{
		$enamearr = $usednames['ofields'];
		$fconfigarr = array(
			'errorurl' => $forward,
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'offers',
			'fieldtable' => $tblprefix.'cufields',
			'sqlstr' => "cu='2'",
		);
		list($fmode,$fnew,$fsave) = array('cu',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		updatecache('ofields');
		updatecache('usednames','ofields');
		adminlog(lang('add_offer_msg_field'));
		amessage('fieldaddfinish',$forward);
	}
}elseif($action == 'ofielddetail' && $fieldename){
	load_cache('ofields');
	!isset($ofields[$fieldename]) && amessage('choosefield','?entry=cufields&action=ofieldsedit');
	$field = $ofields[$fieldename];
	if(!submitcheck('bofielddetail')){
		$submitstr = '';
		tabheader(lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'ofielddetail',"?entry=cufields&action=ofielddetail&fieldename=$fieldename",2,0,1);
		list($fmode,$fnew,$fsave) = array('cu',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bofielddetail');
		check_submit_func($submitstr);
		a_guide('ofielddetail');
	}
	else{
		$fconfigarr = array(
			'altertable' => $tblprefix.'offers',
			'fieldtable' => $tblprefix.'cufields',
			'wherestr' => "WHERE ename='$fieldename' AND cu='2'",
		);
		list($fmode,$fnew,$fsave) = array('cu',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		updatecache('ofields');
		adminlog(lang('det_modify_pu_msg_field'));
		amessage('fieldmodifyfinish',axaction(6,'?entry=cufields&action=ofieldsedit'));
	}
}elseif($action == 'rfieldsedit'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	load_cache('rfields');
	if(!submitcheck('brfieldsedit')){
		tabheader(lang('reply_msg_field_manager')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=cufields&action=rfieldadd\">".lang('add_field')."</a>",'rfieldsedit','?entry=cufields&action=rfieldsedit',7);
		trcategory(array(lang('delete'),lang('field_name'),lang('admin_self'),lang('order'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($rfields as $k => $field){
			fieldlist($k,$field,'r');
		}
		tabfooter('brfieldsedit');
		a_guide('rfieldsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				dropfieldfromtbl('replys',$id,$rfields[$id]['datatype']);
				$db->query("DELETE FROM {$tblprefix}cufields WHERE ename='$id' AND cu='3'"); 
				unset($rfields[$id],$fieldsnew[$id]);
			}
		}
		foreach($rfields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['isadmin'] = empty($fieldsnew[$id]['isadmin']) ? 0 : 1;
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}cufields SET cname='$field[cname]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND cu='3'");
		}
		updatecache('rfields');
		updatecache('usednames','rfields');
		adminlog(lang('e_pu_msg_field_mlist'));
		amessage('fieldmodifyfinish','?entry=cufields&action=rfieldsedit');
	}

}elseif($action == 'rfieldadd'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	$forward = empty($forward) ? M_REFERER : $forward;
	load_cache('rfields');
	if(!submitcheck('brfieldadd')){
		tabheader(lang('add_reply_field'),'rfieldadd',"?entry=cufields&action=rfieldadd&forward=".rawurlencode($forward),2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('brfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('cu',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('brfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('rfieldadd');
	}else{
		$enamearr = $usednames['rfields'];
		$fconfigarr = array(
			'errorurl' => $forward,
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'replys',
			'fieldtable' => $tblprefix.'cufields',
			'sqlstr' => "cu='3'",
		);
		list($fmode,$fnew,$fsave) = array('cu',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		updatecache('rfields');
		updatecache('usednames','rfields');
		adminlog(lang('add_reply_msg_field'));
		amessage('fieldaddfinish',$forward);
	}
}elseif($action == 'rfielddetail' && $fieldename){
	load_cache('rfields');
	!isset($rfields[$fieldename]) && amessage('choosefield','?entry=cufields&action=rfieldsedit');
	$field = $rfields[$fieldename];
	if(!submitcheck('brfielddetail')){
		//if(!$sid) url_nav(lang('docinterconfig'),$urlsarr,'r');
		$submitstr = '';
		tabheader(lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'rfielddetail',"?entry=cufields&action=rfielddetail&fieldename=$fieldename",2,0,1);
		list($fmode,$fnew,$fsave) = array('cu',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('brfielddetail');
		check_submit_func($submitstr);
		a_guide('rfielddetail');
	}
	else{
		$fconfigarr = array(
			'altertable' => $tblprefix.'replys',
			'fieldtable' => $tblprefix.'cufields',
			'wherestr' => "WHERE ename='$fieldename' AND cu='3'",
		);
		list($fmode,$fnew,$fsave) = array('cu',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		updatecache('rfields');
		adminlog(lang('det_modify_pu_msg_field'));
		amessage('fieldmodifyfinish',axaction(6,'?entry=cufields&action=rfieldsedit'));
	}
}elseif($action == 'cfieldsedit'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	load_cache('cfields');
	if(!submitcheck('bcfieldsedit')){
		tabheader(lang('cmt_msg_field_manager')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=cufields&action=cfieldadd\">".lang('add_field')."</a>",'cfieldsedit','?entry=cufields&action=cfieldsedit',7);
		trcategory(array(lang('delete'),lang('field_name'),lang('admin_self'),lang('order'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($cfields as $k => $field){
			fieldlist($k,$field,'c');
		}
		tabfooter('bcfieldsedit');
		a_guide('cfieldsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				dropfieldfromtbl('comments',$id,$cfields[$id]['datatype']);
				$db->query("DELETE FROM {$tblprefix}cufields WHERE ename='$id' AND cu='4'"); 
				unset($cfields[$id],$fieldsnew[$id]);
			}
		}
		foreach($cfields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['isadmin'] = empty($fieldsnew[$id]['isadmin']) ? 0 : 1;
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}cufields SET cname='$field[cname]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND cu='4'");
		}
		updatecache('cfields');
		updatecache('usednames','cfields');
		adminlog(lang('e_pu_msg_field_mlist'));
		amessage('fieldmodifyfinish','?entry=cufields&action=cfieldsedit');
	}

}elseif($action == 'cfieldadd'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	$forward = empty($forward) ? M_REFERER : $forward;
	load_cache('cfields');
	if(!submitcheck('bcfieldadd')){
		if(!$sid) url_nav(lang('docinterconfig'),$urlsarr,'c');
		tabheader(lang('add_comment_field'),'cfieldadd',"?entry=cufields&action=cfieldadd&forward=".rawurlencode($forward),2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('bcfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('cu',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bcfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('cfieldadd');
	}else{
		$enamearr = $usednames['cfields'];
		$fconfigarr = array(
			'errorurl' => $forward,
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'comments',
			'fieldtable' => $tblprefix.'cufields',
			'sqlstr' => "cu='4'",
		);
		list($fmode,$fnew,$fsave) = array('cu',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		updatecache('cfields');
		updatecache('usednames','cfields');
		adminlog(lang('add_cmt_msg_field'));
		amessage('fieldaddfinish',$forward);
	}
}elseif($action == 'cfielddetail' && $fieldename){
	load_cache('cfields');
	!isset($cfields[$fieldename]) && amessage('choosefield','?entry=cufields&action=cfieldsedit');
	$field = $cfields[$fieldename];
	if(!submitcheck('bcfielddetail')){
		//if(!$sid) url_nav(lang('docinterconfig'),$urlsarr,'c');
		$submitstr = '';
		tabheader(lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'cfielddetail',"?entry=cufields&action=cfielddetail&fieldename=$fieldename",2,0,1);
		list($fmode,$fnew,$fsave) = array('cu',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bcfielddetail');
		check_submit_func($submitstr);
		a_guide('cfielddetail');
	}
	else{
		$fconfigarr = array(
			'altertable' => $tblprefix.'comments',
			'fieldtable' => $tblprefix.'cufields',
			'wherestr' => "WHERE ename='$fieldename' AND cu='4'",
		);
		list($fmode,$fnew,$fsave) = array('cu',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		updatecache('cfields');
		adminlog(lang('det_modify_pu_msg_field'));
		amessage('fieldmodifyfinish',axaction(6,'?entry=cufields&action=cfieldsedit'));
	}
}elseif($action == 'bfieldsedit'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	load_cache('bfields');
	if(!submitcheck('bbfieldsedit')){
		tabheader(lang('pb_msg_field_manager')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=cufields&action=bfieldadd\">".lang('add_field')."</a>",'bfieldsedit','?entry=cufields&action=bfieldsedit',7);
		trcategory(array(lang('delete'),lang('field_name'),lang('admin_self'),lang('order'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($bfields as $k => $field){
			fieldlist($k,$field,'b');
		}
		tabfooter('bbfieldsedit');
		a_guide('bfieldsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				dropfieldfromtbl('reports',$id,$bfields[$id]['datatype']);
				$db->query("DELETE FROM {$tblprefix}cufields WHERE ename='$id' AND cu='5'"); 
				unset($bfields[$id],$fieldsnew[$id]);
			}
		}
		foreach($bfields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['isadmin'] = empty($fieldsnew[$id]['isadmin']) ? 0 : 1;
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}cufields SET cname='$field[cname]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND cu='5'");
		}
		updatecache('bfields');
		updatecache('usednames','bfields');
		adminlog(lang('e_pu_msg_field_mlist'));
		amessage('fieldmodifyfinish','?entry=cufields&action=bfieldsedit');
	}

}elseif($action == 'bfieldadd'){
	url_nav(lang('docinterconfig'),$urlsarr,'field');
	echo tab_list($urlsarr_1,6,0);
	$forward = empty($forward) ? M_REFERER : $forward;
	load_cache('bfields');
	if(!submitcheck('bbfieldadd')){
		tabheader(lang('add_pickbug_field'),'bfieldadd',"?entry=cufields&action=bfieldadd&forward=".rawurlencode($forward),2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('bbfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('cu',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bbfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('bfieldadd');
	}else{
		$enamearr = $usednames['bfields'];
		$fconfigarr = array(
			'errorurl' => $forward,
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'reports',
			'fieldtable' => $tblprefix.'cufields',
			'sqlstr' => "cu='5'",
		);
		list($fmode,$fnew,$fsave) = array('cu',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		updatecache('bfields');
		updatecache('usednames','bfields');
		adminlog(lang('add_pickbug_msg_field'));
		amessage('fieldaddfinish',$forward);
	}
}elseif($action == 'bfielddetail' && $fieldename){
	load_cache('bfields');
	echo tab_list($urlsarr_1,6,0);
	!isset($bfields[$fieldename]) && amessage('choosefield','?entry=cufields&action=bfieldsedit');
	$field = $bfields[$fieldename];
	if(!submitcheck('bbfielddetail')){
		$submitstr = '';
		tabheader(lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'bfielddetail',"?entry=cufields&action=bfielddetail&fieldename=$fieldename",2,0,1);
		list($fmode,$fnew,$fsave) = array('cu',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bbfielddetail');
		check_submit_func($submitstr);
		a_guide('bfielddetail');
	}
	else{
		$fconfigarr = array(
			'altertable' => $tblprefix.'reports',
			'fieldtable' => $tblprefix.'cufields',
			'wherestr' => "WHERE ename='$fieldename' AND cu='5'",
		);
		list($fmode,$fnew,$fsave) = array('cu',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		updatecache('bfields');
		adminlog(lang('det_modify_pickbug_msg_field'));
		amessage('fieldmodifyfinish',axaction(6,'?entry=cufields&action=bfieldsedit'));
	}
}
?>
