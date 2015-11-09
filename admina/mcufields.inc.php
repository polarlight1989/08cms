<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cfmcommu') || amessage('no_apermission');
load_cache('grouptypes,currencys,rprojects');
sys_cache('fieldwords');
include_once M_ROOT."./include/fields.fun.php";
$nowarrs = array(
	2 => array('cu' => 'ml','cname' => lang('spaceflink'),'table' => 'mflinks'),
	3 => array('cu' => 'mc','cname' => lang('membercomment'),'table' => 'mcomments'),
	4 => array('cu' => 'mr','cname' => lang('memberreply'),'table' => 'mreplys'),
	5 => array('cu' => 'mb','cname' => lang('memberreport'),'table' => 'mreports'),
);
$cu = empty($cu) ? 2 : $cu;
if(empty($nowarrs[$cu])) amessage('pointfieldtype');
$nowarr = $nowarrs[$cu];
$action = empty($action) ? 'fieldsedit' : $action;
load_cache($nowarr['cu'].'fields');
$mcufields = ${$nowarr['cu'].'fields'};
$url_type = 'mcufield';include 'urlsarr.inc.php';
if($action == 'fieldsedit'){
	if(!submitcheck('bfieldsedit')){
		url_nav(lang('memberinterconfig'),$urlsarr,$cu);
		tabheader($nowarr['cname'].lang('mesfiman')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=mcufields&action=fieldadd&cu=$cu\">".lang('add_field')."</a>",'fieldsedit',"?entry=mcufields&action=fieldsedit&cu=$cu",7);
		trcategory(array(lang('delete'),lang('field_name'),lang('admin_self'),lang('order'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($mcufields as $k => $field){
			fieldlist($k,$field,$nowarr['cu']);
		}
		tabfooter('bfieldsedit');
		a_guide($nowarr['cu'].'fieldsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				dropfieldfromtbl($nowarr['table'],$id,$mcufields[$id]['datatype']);
				$db->query("DELETE FROM {$tblprefix}mcufields WHERE ename='$id' AND cu='$cu'"); 
				unset($mcufields[$id],$fieldsnew[$id]);
			}
		}
		foreach($mcufields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['isadmin'] = empty($fieldsnew[$id]['isadmin']) ? 0 : 1;
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}mcufields SET cname='$field[cname]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND cu='$cu'");
		}
		updatecache($nowarr['cu'].'fields');
		updatecache('usednames',$nowarr['cu'].'fields');
		adminlog(lang('edmecomefimali'));
		amessage('fieldmodifyfinish',"?entry=mcufields&action=fieldsedit&cu=$cu");
	}

}elseif($action == 'fieldadd'){
	url_nav(lang('memberinterconfig'),$urlsarr,$cu);
	$forward = empty($forward) ? M_REFERER : $forward;
	if(!submitcheck('bfieldadd')){
		tabheader(lang('add').$nowarr['cname'].lang('field'),'fieldadd',"?entry=mcufields&action=fieldadd&cu=$cu&forward=".rawurlencode($forward),2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('bfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array($nowarr['cu'],true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide($nowarr['cu'].'fieldadd');
	}else{
		$enamearr = $usednames[$nowarr['cu'].'fields'];
		$fconfigarr = array(
			'errorurl' => $forward,
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.$nowarr['table'],
			'fieldtable' => $tblprefix.'mcufields',
			'sqlstr' => "cu='$cu'",
		);
		list($fmode,$fnew,$fsave) = array($nowarr['cu'],true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		updatecache($nowarr['cu'].'fields');
		updatecache('usednames',$nowarr['cu'].'fields');
		adminlog(lang('addmecomefi'));
		amessage('fieldaddfinish',$forward);
	}
}elseif($action == 'fielddetail' && $fieldename){
	!isset($mcufields[$fieldename]) && amessage('choosefield','?entry=cufields&action=fieldsedit');
	$field = $mcufields[$fieldename];
	if(!submitcheck('bfielddetail')){
		$submitstr = '';
		tabheader(lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'fielddetail',"?entry=mcufields&action=fielddetail&cu=$cu&fieldename=$fieldename",2,0,1,1);
		list($fmode,$fnew,$fsave) = array($nowarr['cu'],false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bfielddetail');
		check_submit_func($submitstr);
		a_guide($nowarr['cu'].'fielddetail');
	}
	else{
		$fconfigarr = array(
			'altertable' => $tblprefix.$nowarr['table'],
			'fieldtable' => $tblprefix.'mcufields',
			'wherestr' => "WHERE ename='$fieldename' AND cu='$cu'",
		);
		list($fmode,$fnew,$fsave) = array($nowarr['cu'],false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		updatecache($nowarr['cu'].'fields');
		adminlog(lang('detmocomefi'));
		amessage('fieldmodifyfinish',axaction(10,'?entry=mcufields&action=fieldsedit'));
	}
}
?>
