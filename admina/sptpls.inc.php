<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('tpl') || amessage('no_apermission');
load_cache('bnames');
load_cache('sptpls',$sid);
include_once M_ROOT."./include/template.fun.php";
$dbtpls = fetch_arr();
$true_tpldir = M_ROOT."./template/$templatedir";
mmkdir($true_tpldir);
$url_type = 'tpl';include 'urlsarr.inc.php';
if($action == 'sptplsedit'){
	url_nav(lang('tplallconfig'),$urlsarr,'futpl',12);
	if(!submitcheck('bsptplsedit')) {
		tabheader(lang('sppagemana'),'sptplsedit',"?entry=sptpls&action=sptplsedit$param_suffix",'5');
		trcategory(array(lang('sn'),lang('pagecname'),lang('pick_url_style'),lang('templatefile'),lang('content')));
		$no = 0;
		foreach($dbtpls as $k => $v){
			$no ++;
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w30\">$no</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\">$v[link]</td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"20\" name=\"sptplsnew[$k][tplname]\" value=\"".(empty($sptpls[$k]) ? '' : $sptpls[$k])."\"></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=sptpls&action=sptpldetail&spid=$k$param_suffix\" onclick=\"return floatwin('open_sptplsedit',this)\">".lang('edit')."</a></td></tr>\n";
		}
		tabfooter('bsptplsedit',lang('modify'));
		a_guide('sptplsedit');
	}else{
		foreach($dbtpls as $k => $v){
			$sptplsnew[$k]['tplname'] = trim($sptplsnew[$k]['tplname']);
			if(preg_match("/[^a-z_A-Z0-9\.]+/",$sptplsnew[$k]['tplname'])) $sptplsnew[$k]['tplname'] = '';
			if($sptplsnew[$k]['tplname'] != @$sptpls[$k]){
				$sptpls[$k] = $sptplsnew[$k]['tplname'];
			}
		}
		cache2file($sptpls,'sptpls','sptpls',$sid);
		adminlog(lang('edsptemmanli'));
		amessage('pagemodfin', "?entry=sptpls&action=sptplsedit$param_suffix");
	}
}
elseif($action == 'sptpldetail' && $spid){
	$dbtpl = $dbtpls[$spid];
	$tplname = empty($sptpls[$spid]) ? '' : $sptpls[$spid];
	if(!submitcheck('bsptpldetail')){
		if(empty($tplname) || !is_file($true_tpldir.'/'.$tplname)){
			if(@!touch($true_tpldir.'/'.$tplname)) amessage('sptplnoexist',axaction(2,M_REFERER));
		}
		$template = load_tpl($tplname,0);
		tabheader(lang('sptemset').'-'.$dbtpl['cname'],'sptpldetail',"?entry=sptpls&action=sptpldetail&spid=$spid$param_suffix");
		trbasic(lang('templatecontent'),'',$tplname,'');
		templatebox(lang('templatecontent'),'templatenew',$template,30,110);
		tabfooter('bsptpldetail',lang('modify'));
		a_guide('sptpldetail');
	}else{
		empty($templatenew) && amessage('temconnot',"?entry=sptpls&action=sptplsedit$param_suffix");
		!str2file(stripslashes($templatenew),$true_tpldir.'/'.$tplname) && amessage('tplerrsave',"?entry=sptpls&action=sptplsedit$param_suffix");
		adminlog(lang('detmosptemp'));
		amessage('tplmodfin',axaction(6,"?entry=sptpls&action=sptplsedit$param_suffix"));
	}
}
elseif($action == 'sptplmap' && $spid){
	$dbtpl = $dbtpls[$spid];
	$tplname = empty($sptpls[$spid]) ? '' : $sptpls[$spid];
	if(empty($tplname) || !is_file($true_tpldir.'/'.$tplname)){
		if(@!touch($true_tpldir.'/'.$tplname)) amessage('sptplnoexist',M_REFERER);
	}
	load_cache('ctags,utags,ptags,rtags',$sid);
	$tagsarr = m_tagsmap($tplname);
	tabheader(lang('tagmap')."&nbsp;-&nbsp;$dbtpl[cname]",'','','6');
	trcategory(array(lang('sn'),lang('tagstyle'),lang('tagname'),lang('tagtype'),lang('tagmodify'),lang('referror')));
	$no = 0;
	foreach($tagsarr as $tpltag){
		$no ++;
		if($tpltag['tmode'] == 'b'){
			$tpltag['tmode'] = lang('initdata1');
			$tpltag['cname'] = !$tpltag['error'] ? $bnames[$tpltag['tname']] : '-';
			$tpltag['error'] = !$tpltag['error'] ? '-' : 'x';
			$tpltag['detail'] = "-";
			$tpltag['tname'] = "{\$$tpltag[tname]}";
		}elseif($tpltag['tmode'] == 'u'){
			$tpltag['tmode'] = lang('utfield');
			$tag = read_cache('utag',$tpltag['tname'],'',$sid);
			$tpltag['cname'] = !$tpltag['error'] ? $tag['cname'] : '-';
			$tpltag['error'] = !$tpltag['error'] ? '-' : 'x';
			$tpltag['detail'] = "<a href=\"?entry=mtags&action=mtagsdetail&ttype=utag&tname=$tpltag[tname]$param_suffix\">".lang('modify')."</a>";
			$tpltag['tname'] = "<b>{u\$$tpltag[tname]}</b>";
		}elseif($tpltag['tmode'] == 'c'){
			$tpltag['tmode'] = lang('cttag');
			$tag = read_cache('ctag',$tpltag['tname'],'',$sid);
			$tpltag['cname'] = !$tpltag['error'] ? $tag['cname'] : '-';
			$tpltag['error'] = !$tpltag['error'] ? '-' : 'x';
			$tpltag['detail'] = "<a href=\"?entry=mtags&action=mtagsdetail&ttype=ctag&tname=$tpltag[tname]$param_suffix\">".lang('modify')."</a>";
			$tpltag['tname'] = "<b>{c\$$tpltag[tname]}</b>";
		}elseif($tpltag['tmode'] == 'p'){
			$tpltag['tmode'] = lang('pttag');
			$tag = read_cache('ptag',$tpltag['tname'],'',$sid);
			$tpltag['cname'] = !$tpltag['error'] ? $tag['cname'] : '-';
			$tpltag['error'] = !$tpltag['error'] ? '-' : 'x';
			$tpltag['detail'] = "<a href=\"?entry=mtags&action=mtagsdetail&ttype=ptag&tname=$tpltag[tname]$param_suffix\">".lang('modify')."</a>";
			$tpltag['tname'] = "<b>{p\$$tpltag[tname]}</b>";
		}elseif($tpltag['tmode'] == 'tpl'){
			$tpltag['tmode'] = lang('rt');
			$tag = read_cache('rtag',$tpltag['tname'],'',$sid);
			$tpltag['cname'] = !$tpltag['error'] ? $tag['cname'] : '-';
			$tpltag['error'] = !$tpltag['error'] ? '-' : 'x';
			$tpltag['detail'] = "<a href=\"?entry=mtags&action=mtagsdetail&ttype=rtag&tname=$tpltag[tname]$param_suffix\">".lang('modify')."</a>";
			$tpltag['tname'] = "<b>{tpl\$$tpltag[tname]}</b>";
		}
		$space = "- - - - &nbsp; ";
		for($i = 0; $i < $tpltag['level']; $i++){
			$tpltag['tname'] = $space.$tpltag['tname'];
		}
		echo "<tr class=\"txt\">".
			"<td class=\"txtC w40\">$no</td>\n".
			"<td class=\"txtL\">$tpltag[tname]</td>\n".
			"<td class=\"txtL w200\">$tpltag[cname]</td>\n".
			"<td class=\"txtC w70\">$tpltag[tmode]</td>\n".
			"<td class=\"txtC w60\">$tpltag[detail]</td>\n".
			"<td class=\"txtC w60\">$tpltag[error]</td></tr>\n";
	}
	tabfooter();
	echo "<input class=\"button\" type=\"submit\" name=\"\" value=\"".lang('goback')."\" onclick=\"history.go(-1);\">\n";	
	a_guide('sptplsmap');
}
function fetch_arr(){
	global $db,$tblprefix;
	$items = array();
	$query = $db->query("SELECT * FROM {$tblprefix}sptpls ORDER BY vieworder");
	while($item = $db->fetch_array($query)){
		$items[$item['ename']] = $item;
	}
	return $items;
}

?>
