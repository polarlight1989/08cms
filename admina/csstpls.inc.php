<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
load_cache('mtpls,csstpls,jstpls',$sid);
aheader();
backallow('tpl') || amessage('no_apermission');
$action = empty($action) ? 'csstplsedit' : $action;
$jsmode = empty($jsmode) ? 0 : 1;

$subsite = @$subsites[$sid];
$css_dir = $sid ? @$subsite['css_dir'] : $css_dir;
$js_dir = $sid ? @$subsite['js_dir'] : $js_dir;
$css_tpldir = M_ROOT."./template/$templatedir/".(empty($css_dir) ? 'css' : $css_dir)."/";
$js_tpldir = M_ROOT."./template/$templatedir/".(empty($js_dir) ? 'js' : $js_dir)."/";
$true_tpldir = empty($jsmode) ? $css_tpldir : $js_tpldir;
mmkdir($true_tpldir);
$url_type = 'othertpl';include 'urlsarr.inc.php';
if($action == 'csstplsedit'){
	url_nav(lang('tplrelated'),$urlsarr,'cssjs');
	if(!submitcheck('bcsstplsedit')){
		$cssdocs = findfiles($css_tpldir,'css');
		tabheader(lang('css_file_admin')."&nbsp;&nbsp;&nbsp;&nbsp;[<a href=\"?entry=csstpls&action=fileadd$param_suffix\" onclick=\"return floatwin('open_csstplsedit',this)\">".lang('add').'</a>]','csstplsedit',"?entry=csstpls&action=csstplsedit$param_suffix",'9');
		trcategory(array(lang('del'),array(lang('css_file'),'txtL'),array(lang('cname'),'txtL'),lang('copy'),lang('content')));
		foreach($cssdocs as $k => $v){
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\"><a href=\"?entry=csstpls&action=filedel&filename=$v$param_suffix\">".lang('del')."</a></td>\n".
				"<td class=\"txtL w150\">$v</td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"csstplsnew[$v][cname]\" value=\"".mhtmlspecialchars(@$csstpls[$v]['cname'])."\"></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=csstpls&action=filecopy&filename=$v$param_suffix\" onclick=\"return floatwin('open_csstplsedit',this)\">".lang('copy')."</a></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=csstpls&action=filedetail&filename=$v$param_suffix\" onclick=\"return floatwin('open_csstplsedit',this)\">".lang('edit')."</a></td>\n".
				"</tr>\n";
		}
		tabfooter('bcsstplsedit',lang('modify'));

		$jsdocs = findfiles($js_tpldir,'js');
		tabheader(lang('js_file_admin')."&nbsp;&nbsp;&nbsp;&nbsp;[<a href=\"?entry=csstpls&action=fileadd$param_suffix&jsmode=1\" onclick=\"return floatwin('open_csstplsedit',this)\">".lang('add').'</a>]','jstplsedit',"?entry=csstpls&action=csstplsedit$param_suffix&jsmode=1",'9');
		trcategory(array(lang('del'),array(lang('js_file'),'txtL'),array(lang('cname'),'txtL'),array(lang('template'),'txtL'),lang('update'),lang('copy'),lang('content')));
		foreach($jsdocs as $k => $v){
			$updatestr = empty($jstpls[$v]['tplname']) ? '-' : "<a href=\"?entry=csstpls&action=fileupdate&filename=$v$param_suffix&jsmode=1\">".lang('create')."</a>";
			$mtpldetailstr = empty($jstpls[$v]['tplname']) ? '' : "&nbsp; >><a href=\"?entry=mtpls&action=mtpldetail&tplname=".$jstpls[$v]['tplname']."$param_suffix\" onclick=\"return floatwin('open_csstplsedit',this)\">".lang('edit')."</a>";
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\"><a href=\"?entry=csstpls&action=filedel&filename=$v$param_suffix&jsmode=1\">".lang('del')."</a></td>\n".
				"<td class=\"txtL w150\">$v</td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"jstplsnew[$v][cname]\" value=\"".mhtmlspecialchars(@$jstpls[$v]['cname'])."\"></td>\n".
				"<td class=\"txtL\"><select name=\"jstplsnew[$v][tplname]\">".makeoption(array('' => lang('none')) + mtplsarr('js'),@$jstpls[$v]['tplname'])."</select>$mtpldetailstr</td>\n".
				"<td class=\"txtC w30\">$updatestr</td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=csstpls&action=filecopy&filename=$v$param_suffix&jsmode=1\" onclick=\"return floatwin('open_csstplsedit',this)\">".lang('copy')."</a></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=csstpls&action=filedetail&filename=$v$param_suffix&jsmode=1\" onclick=\"return floatwin('open_csstplsedit',this)\">".lang('edit')."</a></td>\n".
				"</tr>\n";
		}
		tabfooter('bcsstplsedit',lang('modify'));
	}elseif(!$jsmode){
		if(!empty($csstplsnew)){
			foreach($csstplsnew as $k => $v){
				$csstpls[$k]['cname'] = stripslashes($v['cname']);
			}
		}
		cache2file($csstpls,'csstpls','csstpls',$sid);
		adminlog(lang('edit_cssfile_mlist'));
		amessage('cssfilemodifyfinish',M_REFERER);
	}else{
		if(!empty($jstplsnew)){
			foreach($jstplsnew as $k => $v){
				$jstpls[$k]['cname'] = stripslashes($v['cname']);
				$jstpls[$k]['tplname'] = stripslashes($v['tplname']);
			}
		}
		cache2file($jstpls,'jstpls','jstpls',$sid);
		adminlog(lang('edit_jsfile_mlist'));
		amessage('jsfilemodifyfinish',M_REFERER);
	}
}elseif($action == 'fileadd'){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(!submitcheck('bfileadd')){
		tabheader(lang('add_css_js_file'),'filecopy',"?entry=csstpls&action=fileadd$param_suffix&jsmode=$jsmode$forwardstr");
		trbasic(($jsmode ? 'JS' : 'CSS').lang('file_saveas'),'filenamenew');
		echo "<tr class=\"txt\"><td class=\"txtL\">".lang('file_content')."</td>".
		"<td class=\"txtL\"><textarea class=\"textarea\" style=\"width:650px;height:480px\" name=\"contentnew\" id=\"contentnew\"></textarea></td></tr>";
		tabfooter('bfileadd');
		a_guide('csstpladd');
	}else{
		if(empty($filenamenew)) amessage('pointfilename',M_REFERER);
		$filesnew = findfiles($true_tpldir);
		in_array($filenamenew,$filesnew) && amessage('filenamerepeat',M_REFERER);
		if(!str2file(stripslashes($contentnew),$true_tpldir.$filenamenew)) amessage('fileaddfailed',M_REFERER);
		adminlog(lang('add_file',$jsmode ? 'JS' : 'CSS'));
		amessage('sfileaddfinish',axaction(6,$forward),$jsmode ? 'JS' : 'CSS');
	}

}elseif($action == 'filecopy'){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(empty($filename)) amessage('pointfilename',M_REFERER);
	if(!is_file($true_tpldir.$filename)) amessage('socfilenoexist',M_REFERER);
	if(!submitcheck('bfilecopy')){
		tabheader(lang('copy_css_js_file'),'filecopy',"?entry=csstpls&action=filecopy&filename=$filename$param_suffix&jsmode=$jsmode$forwardstr");
		trbasic(lang('soc_file'),'',$filename,'');
		trbasic(lang('file_saveas'),'filenamenew','');
		tabfooter('bfilecopy');
		a_guide('csstplcopy');
	}else{
		if(empty($filenamenew)) amessage('targetfilename',M_REFERER);
		$filesnew = findfiles($true_tpldir);
		in_array($filenamenew,$filesnew) && amessage('filenamerepeat',M_REFERER);

		if(!copy($true_tpldir.$filename,$true_tpldir.$filenamenew)) amessage('filecopyfailed',M_REFERER);
		adminlog(lang('copy_file',$jsmode ? 'JS' : 'CSS'));
		amessage('filecopyfinish',axaction(6,$forward,$jsmode ? 'JS' : 'CSS'));
	}

}elseif($action == 'filedetail'){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(empty($filename)) amessage('pointfilename',M_REFERER);
	if(!submitcheck('bfiledetail')){
		$content = @file2str($true_tpldir.$filename);
		tabheader(($jsmode ? 'JS' : 'CSS').lang('file_edit').'&nbsp;-&nbsp;'.$filename,'filedetail',"?entry=csstpls&action=filedetail&filename=$filename$param_suffix&jsmode=$jsmode$forwardstr");
		echo "<tr class=\"txt\"><td colspan=\"2\"><textarea class=\"textarea\" style=\"width:700px;height:450px\" name=\"contentnew\" id=\"contentnew\">".htmlspecialchars(str_replace("\t","    ",$content))."</textarea></td><tr>";
		tabfooter('bfiledetail');
	}
	else{
		@str2file(stripslashes($contentnew),$true_tpldir.$filename);
		adminlog(lang('detail_modify_file',$jsmode ? 'JS' : 'CSS'));
		amessage('filemodifyfinish',axaction(6,$forward),$jsmode ? 'JS' : 'CSS');
	}
}elseif($action == 'filedel'){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(empty($filename)) amessage('pointfilename',M_REFERER);
	if(empty($confirm)){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick')."[<a href='?entry=csstpls&action=filedel&filename=$filename$param_suffix&jsmode=$jsmode&confirm=1$forwardstr'>".lang('delete')."</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$message .= lang('giveupclick')."[<a href='?entry=csstpls'>".lang('goback')."</a>]";
		amessage($message);
	}
	@unlink($true_tpldir.$filename);
	adminlog(lang('delete_file',$jsmode ? 'JS' : 'CSS'));
	amessage('filedelfinish',$forward,$jsmode ? 'JS' : 'CSS');
}elseif($action == 'fileupdate'){
	include_once M_ROOT."./include/parse.fun.php";
	if(empty($filename)) amessage('pointfilename',M_REFERER);
	$tplname = empty($jstpls[$filename]['tplname']) ? '' : $jstpls[$filename]['tplname'];
	if(!$tplname) amessage('definejsfiletemplate',M_REFERER);
	$filename = $js_tpldir.$filename;

	$data = array();
	_aenter($data,1);
	@extract($btags);
	tpl_refresh($tplname);
	@include M_ROOT."template/$templatedir/pcache/$tplname.php";
	
	$_content = ob_get_contents();
	ob_clean();
	@str2file($_content,$filename);
	adminlog(lang('update_js_file'));
	amessage('jsfileupdatefinish',M_REFERER);
}
?>
