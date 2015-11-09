<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('tpl') || amessage('no_apermission');
load_cache('cotypes,bnames');
load_cache('mtpls',$sid);
include_once M_ROOT."./include/template.fun.php";
$tpclasses = array(
	'index' => lang('index'),
	'cindex' => lang('catasindex'),
	'archive' => lang('archivecontent'),
	'freeinfo' => lang('freeinfocontent'),
	'commu' => lang('archivecommu'),
	'mcommu' => lang('membercommu'),
	'search' => lang('searchpage0'),
	'wap' => lang('waptpl'),
	'other' => lang('isolutepage'),
	'js' => lang('jstemplate'),
);
if(!$sid){
	$tpclasses['marchive'] = lang('membertpl');
	$tpclasses['space'] = lang('spacepage');
}
$true_tpldir = M_ROOT."./template/$templatedir";
mmkdir($true_tpldir);
$url_type = 'tpl';include 'urlsarr.inc.php';
if($action == 'mtpladd'){
	url_nav(lang('tplallconfig'),$urlsarr,'retpl',12);
	if(!submitcheck('bmtpladd') && !submitcheck('bmtplsave')){
		if(submitcheck('bmtplsearch')){
			$mtplstmp = findfiles($true_tpldir);
			$enamearr = array_keys($mtpls);
			foreach($mtplstmp as $k => $tplname){
				if(in_array($tplname,$enamearr)) unset($mtplstmp[$k]);
			}
			empty($mtplstmp) && amessage('mtplsearchnone', "?entry=mtpls&action=mtpladd$param_suffix");
			$in_search = 1;
		}
		tabheader(lang('addnormtemp')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"button\" type=\"submit\" name=\"bmtplsearch\" value=\"".lang('autosearch')."\">",'mtpladd',"?entry=mtpls&action=mtpladd$param_suffix");
		trbasic(lang('templatecname'),'mtpladd[cname]');
		trbasic(lang('templatetype'),'mtpladd[tpclass]',makeoption($tpclasses),'select');
		trbasic(lang('templatefile'),'mtpladd[tplname]');
		tabfooter('bmtpladd',lang('add'));
		if(!empty($in_search)){
			tabheader(lang('nortemaddpu'),'mtplsave',"?entry=mtpls&action=mtpladd$param_suffix",'4');
			trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('selectall'),lang('templatefile'),lang('settempcna'),lang('settitemplty')));
			foreach($mtplstmp as $tplname){
				echo "<tr class=\"txt\">".
					"<td class=\"txtC w45\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$tplname]\" value=\"$tplname\">\n".
					"<td class=\"txtL\">$tplname</td>\n".
					"<td class=\"txtC\"><input type=\"text\" size=\"30\" name=\"mtplsnew[$tplname][cname]\" value=\"\"></td>\n".
					"<td class=\"txtC w150\"><select style=\"vertical-align: middle;\" name=\"mtplsnew[$tplname][tpclass]\">".makeoption($tpclasses)."</select></td></tr>";
			}
			tabfooter('bmtplsave',lang('putin'));
		}
		a_guide('mtpladd');
	}elseif(submitcheck('bmtpladd')){
		if(empty($mtpladd['cname']) || empty($mtpladd['tplname'])) amessage('datamissing',M_REFERER);
		if(preg_match("/[^a-z_A-Z0-9\.]+/",$mtpladd['tplname'])) amessage('temcnaill',M_REFERER);
		$enamearr = array_keys($mtpls);
		if(in_array($mtpladd['tplname'], $enamearr)) amessage('pagtemrepdef',M_REFERER);
		if(!is_file($true_tpldir.'/'.$mtpladd['tplname'])){
			if(@!touch($true_tpldir.'/'.$mtpladd['tplname'])) amessage('temfiladdfai',M_REFERER);
		}
		$mtpls[$mtpladd['tplname']] = array('cname' => stripslashes($mtpladd['cname']),'tpclass' => $mtpladd['tpclass']);
		cache2file($mtpls,'mtpls','mtpls',$sid);
		adminlog(lang('addnormtemp'));
		amessage('temaddfin',"?entry=mtpls&action=mtplsedit$param_suffix");
	}elseif(submitcheck('bmtplsave')){
		if(!empty($selectid)){
			foreach($selectid as $tplname){
				if(!empty($mtplsnew[$tplname]['cname']) && !empty($mtplsnew[$tplname]['tpclass'])){
					$cname = $mtplsnew[$tplname]['cname'];
					$tpclass = $mtplsnew[$tplname]['tpclass'];
					$mtpls[$tplname] = array('cname' => stripslashes($mtplsnew[$tplname]['cname']),'tpclass' => $mtplsnew[$tplname]['tpclass']);
				}
			}
		}
		cache2file($mtpls,'mtpls','mtpls',$sid);
		adminlog(lang('addnormtemp'));
		amessage('temputfin',"?entry=mtpls&action=mtplsedit$param_suffix");
	}
}
elseif($action == 'mtplsedit'){
	url_nav(lang('tplallconfig'),$urlsarr,'retpl',12);
	$ftpclass = empty($ftpclass) ? 'index' : $ftpclass;
	$urlsarr_1 = array();
	foreach($tpclasses as $k => $v) $urlsarr_1[] = $ftpclass == $k ? "<b>-$v-</b>" : "<a href=\"?entry=mtpls&action=mtplsedit$param_suffix&ftpclass=$k\">$v</a>";
	echo tab_list($urlsarr_1,7,0);
	if(!submitcheck('bmtplsedit')){
		tabheader(lang('norpagtempadm')."&nbsp;&nbsp;&nbsp;&nbsp;[<a href=\"?entry=mtpls&action=mtpladd$param_suffix\">".lang('add').'</a>]','mtplsedit',"?entry=mtpls&action=mtplsedit$param_suffix&ftpclass=$ftpclass",'9');
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),lang('templatecname'),lang('type'),lang('templatefile'),lang('copy'),lang('content')));
		foreach($mtpls as $k => $v){
			if(empty($ftpclass) || $ftpclass == $v['tpclass']){
				echo "<tr class=\"txt\">".
					"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\">\n".
					"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"mtplsnew[$k][cname]\" value=\"".mhtmlspecialchars($v['cname'])."\"></td>\n".
					"<td class=\"txtC w150\">".$tpclasses[$v['tpclass']]."</td>\n".
					"<td class=\"txtL\">$k</td>\n".
					"<td class=\"txtC w30\"><a href=\"?entry=mtpls&action=mtplcopy&tplname=$k$param_suffix\" onclick=\"return floatwin('open_mtplsedit',this)\">".lang('copy')."</a></td>\n".
					"<td class=\"txtC w30\"><a href=\"?entry=mtpls&action=mtpldetail&tplname=$k$param_suffix\" onclick=\"return floatwin('open_mtplsedit',this)\">".lang('edit')."</a></td></tr>\n";
			}
		}
		tabfooter('bmtplsedit',lang('modify'));
		a_guide('mtplsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				unset($mtplsnew[$k],$mtpls[$k]);
			}
		}
		if(!empty($mtplsnew)){
			foreach($mtplsnew as $k => $v){
				$v['cname'] = empty($v['cname']) ? $mtpls[$k]['cname'] : $v['cname'];
				if($v['cname'] != $mtpls[$k]['cname']) $mtpls[$k]['cname'] = stripslashes($v['cname']);
			}
		}
		cache2file($mtpls,'mtpls','mtpls',$sid);
		adminlog(lang('edinortemmanli'));
		amessage('tplmodfin', "?entry=mtpls&action=mtplsedit$param_suffix&ftpclass=$ftpclass");
	}
}
elseif($action == 'mtpldetail' && $tplname){
	$mtpl = $mtpls[$tplname];
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(!submitcheck('bmtpldetail')){
		$template = load_tpl($tplname,0);
		tabheader("$mtpl[cname]".lang('setting').'-'.$mtpl['cname'],'mtpldetail',"?entry=mtpls&action=mtpldetail&tplname=$tplname$param_suffix$forwardstr");
		trbasic(lang('templateclass'),'mtplnew[tpclass]',makeoption($tpclasses,$mtpl['tpclass']),'select');
		templatebox(lang('page_template'),'templatenew',$template,30,110);
		tabfooter('bmtpldetail');
		a_guide('mtpldetail');
	}
	else{
		@str2file(stripslashes($templatenew),$true_tpldir.'/'.$tplname);
		$mtpls[$tplname]['tpclass'] = $mtpl['tpclass'] = $mtplnew['tpclass'];
		cache2file($mtpls,'mtpls','mtpls',$sid);
		adminlog(lang('detamodnormtem'));
		amessage('tplmodfin',axaction(6,$forward));
	}
}
elseif($action == 'mtplcopy' && $tplname){
	$mtpl = $mtpls[$tplname];
	if(!submitcheck('bmtplcopy')){
		!is_file($true_tpldir.'/'.$tplname) && amessage('poisotemfino');
		tabheader(lang('copnormapagetemp'),'mtplcopy',"?entry=mtpls&action=mtplcopy$param_suffix&tplname=$tplname");
		trbasic(lang('templatecname'),'mtpladd[cname]');
		trbasic(lang('templateclass'),'mtpladd[tpclass]',makeoption($tpclasses,$mtpl['tpclass']),'select');
		trbasic(lang('soctemfi'),'',$tplname,'');
		trbasic(lang('tempfilsav'),'mtpladd[tplname]');
		tabfooter('bmtplcopy');
		a_guide('mtplcopy');
	}else{
		(!$mtpladd['cname'] || !$mtpladd['tplname']) && amessage('datamissing',M_REFERER);
		$mtplsnew = findfiles($true_tpldir);
		in_array($mtpladd['tplname'],$mtplsnew) && amessage('poitemficnarep',M_REFERER);
		!copy($true_tpldir.'/'.$tplname,$true_tpldir.'/'.$mtpladd['tplname']) && amessage('temcopfai',M_REFERER);

		$mtpls[$mtpladd['tplname']] = array('cname' => stripslashes($mtpladd['cname']),'tpclass' => $mtpladd['tpclass']);
		cache2file($mtpls,'mtpls','mtpls',$sid);
		adminlog(lang('copynormaltemplate'));
		amessage('temcopfin',axaction(6,"?entry=mtpls&action=mtplsedit$param_suffix"));
	}
}elseif($action == 'mtagcode'){
	if(empty($createrange)) amessage('poitagsou');
	if(preg_match("/\{(u|c|p)\\$(.+?)(\s|\})/is",$createrange,$matches)){
		$ttype = $matches[1].'tag';
		$tname = $matches[2];
		$url = "?entry=mtags&action=mtagsdetail&ttype=$ttype$param_suffix&tname=$tname";
		mheader("location:$url");
	}elseif(preg_match("/\{tpl\\$(.+?)\}/is",$createrange,$matches)){
		$tname = $matches[1];
		$url = "?entry=mtags&action=mtagsdetail&ttype=rtag$param_suffix&tname=$tname";
		mheader("location:$url");
	}
	amessage('poitagsou');
}
?>
