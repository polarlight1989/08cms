<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('webparam') || amessage('no_apermission');
load_cache('localfiles');
$ftypearr = array(
				'image' => lang('image'),
				'flash' => lang('flash'),
				'media' => lang('media'),
				'file' => lang('download'),
				);
$url_type = 'mconfig';include 'urlsarr.inc.php';
url_nav(lang('webparam'),$urlsarr,'localfile',12);
if($action == 'localfilesedit'){
	tabheader(lang('local_upload_prj'),'','','5');
	trcategory(array(lang('sn'),lang('attachmenttype'),lang('all_att_type'),lang('allow_upload_type'),lang('setting')));
	$no = 0;
	foreach($localfiles as $k => $localfile){
		$extnames = $localnames = '';
		foreach($localfile as $ext => $v){
			$extnames .= $ext.'&nbsp;&nbsp;';
			!empty($v['islocal']) && $localnames .= $ext.'&nbsp;&nbsp;';
		}
		$no ++;
		echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\">$no</td>\n".
			"<td class=\"txtC w60\">$ftypearr[$k]</td>\n".
			"<td class=\"txtL\">$extnames</td>\n".
			"<td class=\"txtL\">$localnames</td>\n".
			"<td class=\"txtC w40\"><a href=\"?entry=localfiles&action=localfiledetail&ftype=$k\">".lang('detail')."</a></td></tr>\n";
	}
	tabfooter();
	a_guide('localfilesedit');
}elseif($action == 'localfiledetail' && $ftype){
	$localfile = $localfiles[$ftype];
	if(!submitcheck('bfilesedit') && !submitcheck('bfilesadd')){
		tabheader(lang('local_upload_filetype').'&nbsp;-&nbsp; '.$ftypearr[$ftype],'filesedit',"?entry=localfiles&action=localfiledetail&ftype=$ftype",'6');
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form,\'delete\')">'.lang('del'),lang('file_ext'),lang('attachmenttype'),lang('allow_local_upload'),lang('max_up_limit_k'),lang('min_up_limit_k')));
		foreach($localfile as $k => $rmfile){
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\">\n".
				"<td class=\"txtC w80\">$k</td>\n".
				"<td class=\"txtC\">$ftypearr[$ftype]</td>\n".
				"<td class=\"txtC\"><input class=\"checkbox\" type=\"checkbox\" name=\"rmfilesnew[$k][islocal]\" value=\"1\"".(empty($rmfile['islocal']) ? "" : " checked").">\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"10\" name=\"rmfilesnew[$k][maxsize]\" value=\"$rmfile[maxsize]\"></td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"10\" name=\"rmfilesnew[$k][minisize]\" value=\"$rmfile[minisize]\"></td></tr>\n";
		}
		tabfooter('bfilesedit');

		tabheader(lang('add_file_type'),'filesadd',"?entry=localfiles&action=localfiledetail&ftype=$ftype");
		trbasic(lang('file_type_input'),'extnamestr');
		tabfooter('bfilesadd',lang('add'));
		a_guide('localfiledetail');

	}elseif(submitcheck('bfilesadd')){
		$extnames = array_unique(array_filter(explode(',',strtolower($extnamestr))));
		if($extnames){
			foreach($extnames as $extname){
				if(preg_match("/[^a-zA-Z0-9]+/",$extname) || in_array($extname,array_keys($localfile))) continue;
				$db->query("INSERT INTO {$tblprefix}localfiles SET ftype='$ftype',extname='$extname'");
			}
			updatecache('localfiles');
		}
		adminlog(lang('edit_local_upload_prj'),lang('add_file_type'));
		amessage('filetypeaddfinish',"?entry=localfiles&action=localfiledetail&ftype=$ftype");
	}elseif(submitcheck('bfilesedit')){
		if(!empty($delete)){
			foreach($delete as $id) {
				$db->query("DELETE FROM {$tblprefix}localfiles WHERE extname='$id'");
				unset($rmfilesnew[$id]);
			}
		}
		if(!empty($rmfilesnew)){
			foreach($rmfilesnew as $id => $rmfilenew) {
				$rmfilenew['islocal'] = empty($rmfilenew['islocal']) ? 0 : $rmfilenew['islocal'];
				$rmfilenew['maxsize'] = max(0,intval($rmfilenew['maxsize']));
				$rmfilenew['minisize'] = max(0,intval($rmfilenew['minisize']));
				$db->query("UPDATE {$tblprefix}localfiles SET 
						islocal='$rmfilenew[islocal]',
						maxsize='$rmfilenew[maxsize]',
						minisize='$rmfilenew[minisize]'
						WHERE extname='$id'");
			}
		}
		updatecache('localfiles');
		adminlog(lang('edit_local_upload_prj'),lang('modify_filetype'));
		amessage('filetypeeditfinish',"?entry=localfiles&action=localfiledetail&ftype=$ftype");
	}
}
?>