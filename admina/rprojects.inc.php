<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('webparam') || amessage('no_apermission');
load_cache('rprojects,channels');
$url_type = 'mconfig';include 'urlsarr.inc.php';
url_nav(lang('webparam'),$urlsarr,'rproject',12);
if($action == 'rprojectedit'){
	if(!submitcheck('brprojectadd') && !submitcheck('brprojectedit')){
		tabheader(lang('remproman'),'rprojectedit','?entry=rprojects&action=rprojectedit','5');
		trcategory(array(lang('delete'),lang('projectname'),lang('projecttype'),lang('filetypeext'),lang('edit')));
		foreach($rprojects as $k => $rproject){
			$extnames = implode('&nbsp;&nbsp;',array_keys($rproject['rmfiles']));
			$rproject['issystemstr'] = empty($rproject['issystem']) ? lang('iscustom') : lang('system');
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\"".(!empty($rproject['issystem']) ? ' disabled' : '').">\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"30\" name=\"rprojectsnew[$k][cname]\" value=\"$rproject[cname]\"".(!empty($rproject['issystem']) ? " unselectable=\"on\"" : "")."></td>\n".
				"<td class=\"txtC w80\">$rproject[issystemstr]</td>\n".
				"<td class=\"txtC\">$extnames</td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=rprojects&action=rprojectdetail&rpid=$k\">".lang('detail')."</a></td></tr>\n";
		}

		tabfooter('brprojectedit',lang('modify'));
		tabheader(lang('addrempro'),'rprojectadd','?entry=rprojects&action=rprojectedit');
		trbasic(lang('projectname'),'rprojectadd[cname]');
		tabfooter('brprojectadd',lang('add'));
		a_guide('rprojectedit');
	}
	elseif(submitcheck('brprojectedit')) {
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}rprojects WHERE rpid=$k");
				unset($rprojectsnew[$k]);
			}
		}
		foreach($rprojectsnew as $k => $rprojectnew){
			if(empty($rprojects[$k]['issystem'])){
				$rprojectnew['cname'] = empty($rprojectnew['cname']) ? $rprojects[$k]['cname'] : $rprojectnew['cname'];
				$db->query("UPDATE {$tblprefix}rprojects SET cname='$rprojectnew[cname]' WHERE rpid=$k");
			}
		}
		updatecache('rprojects');
		adminlog(lang('ediremuplpro'),lang('editprojlist'));
		amessage('promodfin', '?entry=rprojects&action=rprojectedit');
	}
	elseif(submitcheck('brprojectadd')) {
		if(!$rprojectadd['cname']) {
			amessage('prodatamis', '?entry=rprojects&action=rprojectedit');
		}
		$db->query("INSERT INTO {$tblprefix}rprojects SET cname='$rprojectadd[cname]'");
		updatecache('rprojects');
		adminlog(lang('addremouplpro'),lang('addremouplpro'));
		amessage('proaddfin', '?entry=rprojects&action=rprojectedit');
	}
}
if($action =='rprojectdetail' && $rpid){
	$rmfiles = $rprojects[$rpid]['rmfiles'];
	$excludes = implode("\n",$rprojects[$rpid]['excludes']);
	$timeout = empty($rprojects[$rpid]['timeout']) ? 0 : intval($rprojects[$rpid]['timeout']);
	if(!submitcheck('bfilesedit') && !submitcheck('bfilesadd')){
		tabheader(lang('remdowfityp').'&nbsp; - &nbsp;'.$rprojects[$rpid]['cname'],'filesedit',"?entry=rprojects&action=rprojectdetail&rpid=$rpid",6);
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),lang('fileext'),lang('maxlimited').'(K)',lang('minilimited').'(K)','MIME'.lang('type'),lang('savecoclass')));
		$ftypearr = array(
						'image' => lang('image'),
						'flash' => lang('flash'),
						'media' => lang('media'),
						'file' => lang('other'),
		);
		foreach($rmfiles as $k => $rmfile) {
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\">\n".
				"<td class=\"txtC\">$rmfile[extname]</td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"6\" name=\"rmfilesnew[$k][maxsize]\" value=\"$rmfile[maxsize]\"></td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"6\" name=\"rmfilesnew[$k][minisize]\" value=\"$rmfile[minisize]\"></td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"25\" name=\"rmfilesnew[$k][mime]\" value=\"$rmfile[mime]\"></td>\n".
				"<td class=\"txtC w50\"><select name=\"rmfilesnew[$k][ftype]\">".makeoption($ftypearr,$rmfile['ftype'])."</select></td></tr>\n";
		}
		tabfooter();
		tabheader(lang('otherset').'&nbsp; - &nbsp;'.$rprojects[$rpid]['cname']);
		trbasic(lang('down_timeout'),'timeoutnew',$timeout,'text',lang('agnolimit'));
		trbasic(lang('excludestxt'),'excludesnew',$excludes,'textarea',lang('agexcludes'));
		tabfooter('bfilesedit');

		tabheader(lang('addfiletype'),'filesadd',"?entry=rprojects&action=rprojectdetail&rpid=$rpid",2,0,1);
		trbasic(lang('fileext'),'rmfileadd[extname]');
		trbasic(lang('filesavecoclass'),'rmfileadd[ftype]',makeoption($ftypearr),'select');
		tabfooter('bfilesadd',lang('add'));
		$submitstr = '';
		$submitstr .= makesubmitstr('rmfileadd[extname]',1,'numberletter',0,10);
		check_submit_func($submitstr);
		a_guide('rprojectdetail');

	}elseif(submitcheck('bfilesadd')){
		$rmfileadd['extname'] = trim(strtolower($rmfileadd['extname']));
		$rmfileadd['mime'] = '';
		if(!$rmfileadd['extname']){
			amessage('datamissing', '?entry=rprojects&action=rprojectdetail&rpid='.$rpid);
		}
		if(preg_match("/[^a-zA-Z0-9]+/",$rmfileadd['extname'])) {
			amessage('fileextill','?entry=rprojects&action=rprojectdetail&rpid='.$rpid);
		}
		if(in_array($rmfileadd['extname'],array_keys($rmfiles))) {
			amessage('fileextrep','?entry=rprojects&action=rprojectdetail&rpid='.$rpid);
		}
		$rmfileadd['maxsize'] = 0;
		$rmfileadd['minisize'] = 0;
		$rmfiles[$rmfileadd['extname']] = $rmfileadd;
		$rmfiles = addslashes(serialize($rmfiles));
		$db->query("UPDATE {$tblprefix}rprojects SET rmfiles='$rmfiles' WHERE rpid='$rpid'");
		updatecache('rprojects');
		adminlog(lang('ediremuplpro'),lang('addremupprofity'));
		amessage('filetypeaddfinish','?entry=rprojects&action=rprojectdetail&rpid='.$rpid);
	}elseif(submitcheck('bfilesedit')){
		if(isset($delete)){
			foreach($delete as $id) {
				unset($rmfilesnew[$id]);
			}
		}
		if(!empty($rmfilesnew)){
			foreach($rmfilesnew as $id => $rmfilenew) {
				$rmfilenew['extname'] = $rmfiles[$id]['extname'];
				$rmfilenew['mime'] = trim(strtolower($rmfilenew['mime']));
				$rmfilenew['maxsize'] = max(0,intval($rmfilenew['maxsize']));
				$rmfilenew['minisize'] = max(0,intval($rmfilenew['minisize']));
				$rmfilesnew[$id] = $rmfilenew;
			}
			$rmfilesnew = addslashes(serialize($rmfilesnew));
		}else{
			$rmfilesnew = '';
		}	
		if(!empty($excludesnew)){
			$excludesnew = str_replace(array("\r","\n"),',',$excludesnew);
			$excludesarr = array_filter(explode(',',$excludesnew));
			$excludesnew = implode(',',$excludesarr);
		}else $excludesnew = '';
		$timeoutnew = max(0,intval($timeoutnew));
		$db->query("UPDATE {$tblprefix}rprojects SET 
			rmfiles='$rmfilesnew',
			timeout='$timeoutnew',
			excludes='$excludesnew' 
			WHERE rpid='$rpid'");
		updatecache('rprojects');
		adminlog(lang('ediremuplpro'),lang('detmodremouplpro'));
		amessage('remproedifin','?entry=rprojects&action=rprojectdetail&rpid='.$rpid);
	}
}
?>
