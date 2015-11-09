<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('gather') || amessage('no_apermission');
load_cache('gmodels,channels');
load_cache('gmodels',$sid);
include_once M_ROOT."./include/commu.fun.php";
$url_type = 'gmiss';include 'urlsarr.inc.php';

if($action == 'gmodeledit'){
	if(!submitcheck('bgmodeledit')){
		url_nav(lang('collectmanagement'),$urlsarr,'model');
		$chidsarr = array(0 => lang('noset')) + chidsarr();
		tabheader(lang('gather_model_manager')."&nbsp; &nbsp; >><a href=\"?entry=gmodels&action=gmodeladd$param_suffix\" onclick=\"return floatwin('open_gmodel',this)\">".lang('add')."</a>",'gmodeledit',"?entry=gmodels&action=gmodeledit$param_suffix",'5');
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),'ID',array(lang('gather_model'),'txtL'),lang('achannel'),lang('edit')));
		foreach($gmodels as $k => $gmodel){
			$chidstr = @$channels[$gmodel['chid']]['cname'];
			$editstr = empty($channels[$gmodel['chid']]) ? '-' : "<a href=\"?entry=gmodels&action=gmodeldetail&gmid=$k$param_suffix\" onclick=\"return floatwin('open_gmodel',this)\">".lang('detail')."</a>";
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\">\n".
				"<td class=\"txtC w30\">$k</td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"30\" name=\"gmodelsnew[$k][cname]\" value=\"$gmodel[cname]\"></td>\n".
				"<td class=\"txtC\">$chidstr</td>\n".
				"<td class=\"txtC w30\">$editstr</td></tr>\n";
		}
		tabfooter('bgmodeledit',lang('modify'));
		a_guide('gmodeledit');
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}gmissions WHERE gmid='$k'")) continue;
				$db->query("DELETE FROM {$tblprefix}gmodels WHERE gmid=$k");
				unset($gmodelsnew[$k]);
			}
		}
		if(!empty($gmodelsnew)){
			foreach($gmodelsnew as $k => $v){
				$v['cname'] = empty($v['cname']) ? addslashes($gmodels[$k]['cname']) : $v['cname'];
				$db->query("UPDATE {$tblprefix}gmodels SET cname='$v[cname]' WHERE gmid=$k");
			}
		}
		updatecache('gmodels','',$sid);
		adminlog(lang('edit_gat_model_mlist'));
		amessage('gatmodmodfin',axaction(6,"?entry=gmodels&action=gmodeledit$param_suffix"));
	}
}elseif($action == 'gmodeladd'){
	cache_merge($channels,'channels',$sid);
	if(!submitcheck('bgmodeladd')){
		$chidsarr = array(0 => lang('noset')) + chidsarr(0);
		tabheader(lang('add_gather_model'),'gmodeladd',"?entry=gmodels&action=gmodeladd$param_suffix");
		trbasic(lang('gather_model_name'),'gmodeladd[cname]');
		trbasic(lang('arc_model_choose'),'gmodeladd[chid]',makeoption($chidsarr),'select');
		tabfooter('bgmodeladd',lang('add'));
	}else{
		$gmodeladd['cname'] = trim(strip_tags($gmodeladd['cname']));
		if(!$gmodeladd['cname']) amessage('inpgatmodnam',M_REFERER);
		if(!$gmodeladd['chid']) amessage('chorcchanalt',M_REFERER);
		$db->query("INSERT INTO {$tblprefix}gmodels SET cname='$gmodeladd[cname]',chid='$gmodeladd[chid]',sid='$sid'");
		updatecache('gmodels','',$sid);
		adminlog(lang('add_gather_model'));
		amessage('gamodaddfin',axaction(6,"?entry=gmodels&action=gmodeledit$param_suffix"));
	}

}elseif($action =='gmodeldetail' && $gmid){
	$gmodel = read_cache('gmodel',$gmid,'',$sid);
	empty($gmodel) && amessage('choosegatmod');
	empty($channels[$gmodel['chid']]) && amessage('modrelarcmodnoe');
	$gfields = empty($gmodel['gfields']) ? array() : $gmodel['gfields'];
	$fields = read_cache('fields',$gmodel['chid']);
	if(!submitcheck('bfieldsedit')){
		$datatypearr = array(
			'text' => lang('text'),
			'multitext' => lang('multitext'),
			'htmltext' => lang('htmltext'),
			'image' => lang('image_f'),
			'images' => lang('images'),
			'flash' => lang('flash'),
			'flashs' => lang('flashs'),
			'media' => lang('media'),
			'medias' => lang('medias'),
			'file' => lang('file_f'),
			'files' => lang('files_f'),
			'select' => lang('select'),
			'mselect' => lang('mselect'),
			'cacc' => lang('cacc'),
			'date' => lang('date_f'),
			'int' => lang('int'),
			'float' => lang('float'),
			'map' => lang('map'),
			'vote' => lang('vote'),
		);
		tabheader($gmodel['cname'].'-'.lang('gather_field_set'),'gmodeldetail',"?entry=gmodels&action=gmodeldetail&gmid=$gmid$param_suffix",'5');
		trcategory(array(lang('gather'),lang('onlylink'),array(lang('field_name'),'txtL'),lang('field_ename'),lang('field_type')));
		foreach($fields as $k => $field){
			$islinkstr = $field['datatype'] != 'text' ? '-' : "<input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$k][islink]\" value=\"1\"".(!empty($gfields[$k]) ? ' checked' : '').">";
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$k][available]\" value=\"1\"".(isset($gfields[$k]) ? ' checked' : '')."></td>\n".
				"<td class=\"txtC w50\">$islinkstr</td>\n".
				"<td class=\"txtL\">$field[cname]</td>\n".
				"<td class=\"txtC\">$k</td>\n".
				"<td class=\"txtC w80\">".$datatypearr[$field['datatype']]."</td>\n".
				"</tr>";
		}
		tabfooter('bfieldsedit');
		a_guide('gmodeldetail');
	}else{
		foreach($fields as $k => $v){
			if(!empty($fieldsnew[$k]['available'])){
				$islink = empty($fieldsnew[$k]['islink']) ? 0 : 1;
				in_array($v['datatype'],array('image','flash','file','media')) && $islink = 1;
				$newgfields[$k] = $islink;
			}
		}
		$gfieldsnew = empty($newgfields) ? '' : addslashes(serialize($newgfields));
		$db->query("UPDATE {$tblprefix}gmodels SET gfields='$gfieldsnew' WHERE gmid='$gmid'");
		updatecache('gmodels','',$sid);
		adminlog(lang('det_modify_gather_model'));
		amessage('gathmodedifin',axaction(6,"?entry=gmodels&action=gmodeledit$param_suffix"));
	}
}
?>
