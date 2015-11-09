<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('tpl') || amessage('no_apermission');
!in_array($ttype,array('ctag','utag','rtag','ptag')) && amessage('paramerror');
load_cache('channels,fchannels,fcatalogs,cotypes,votes,commus,mcommus,shipings,vcatalogs,mchannels,dbsources,acatalogs,mcatalogs,matypes');
$catalogs = &$acatalogs;
cache_merge($channels,'channels',$sid);
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/template.fun.php";
load_cache($ttype.'s',$sid);
load_cache('usualtags,tagclasses',$sid);
$mtags = &${$ttype.'s'};
switch($ttype){
	case 'ctag':
		$tclassarr = array(
		'archives' => lang('archive_list'),
		'catalogs' => lang('cataslist'),
		'mccatalogs' => lang('mcataslist'),
		'alarchives' => lang('inaarcli'),
		'albums' => lang('inalbumlist'),
		'outinfos' => lang('freepickli'),
		'members' => lang('memberlist'),
		'farchives' => lang('freelist'),
		'commus' => lang('archcommlist'),
		'mcommus' => lang('membcommlist'),
		'marchives' => lang('marchive_list'),
		'searchs' => lang('seararchli'),
		'relates' => lang('relarclis'),
		'archive' => lang('arcconmod'),
		'farchive' => lang('messconmod'),
		'marchive' => lang('marchive_mod'),
		'cnode' => lang('catascnode'),
		'cnmod' => lang('catasmod'),
		'mcnode' => lang('mscnode'),
		'userinfos' => lang('membermessage'),
		'keywords' => lang('keywlist'),
		'channels' => lang('archichanlist'),
		'mchannels' => lang('membchalist'),
		'usergroups' => lang('grouplist'),
		'matypes' => lang('matypelist'),
		'nownav' => lang('catasplacnav'),
		'context' => lang('context'),
		'acontext' => lang('inalbumcontext'),
		'vote' => lang('votemod'),
		'votes' => lang('votelist'),
		'subsites' => lang('subsitelist'),
		'arcscount' => lang('arcamosta'),
		'memscount' => lang('membamousta'),
		'inscount' => lang('inscount'),
		'freeurl' => lang('isolpageurl'),
		'mcatalogs' => lang('cataslist').'('.lang('space0').')',
		'mnownav' => lang('catasplace').'('.lang('space0').')',
		);
		$tclass = empty($tclass) ? 'archives' : $tclass;
	break;
	case 'utag':
		$tclassarr = array(
		'image' => lang('imagemod'),
		'file' => lang('downloadmod'),
		'flash' => lang('flashmod'),
		'media' => lang('mediamod'),
		'images' => lang('imageslist'),
		'files' => lang('downlist'),
		'flashs' => lang('flashlist'),
		'medias' => lang('medialist'),
		'fromid' => lang('fromidmod'),
		'date' => lang('timeviewtag'),
		'odeal' => lang('txtdealtag'),
		'field' => lang('fieldtitle'),
		);
		$tclass = empty($tclass) ? 'image' : $tclass;
	break;
	case 'rtag':
		$tclassarr = array('' => '',);
		$tclass = '';
	break;
	case 'ptag':
		$tclassarr = array(
		'archives' => lang('archive_list'),
		'alarchives' => lang('inaarcli'),
		'farchives' => lang('freelist'),
		'commus' => lang('archcommlist'),
		'members' => lang('memberlist'),
		'marchives' => lang('marchive_list'),
		'mcommus' => lang('membcommlist'),
		'normal' => lang('pttxt'),
		'images' => lang('ptimages'),
		'searchs' => lang('seararchli'),
		'outinfos' => lang('freepickli'),
		'masearchs' => lang('masearchlist'),
		'msearchs' => lang('searmembelist'),
		);
		$tclass = empty($tclass) ? 'archives' : $tclass;
	break;
}
$unsetvars = array('casource','cainherit','caidson','urlmode','chsource','space','ucsource','detail','rec','orderby','orderby1','orderstr','startno','wherestr','simple','alimits',
'fmode','date','time','tmode','width','height','maxwidth','maxheight','expand','emptyurl','emptytitle','dealhtml','trim','badword','wordlink','nl2br','randstr',
'next','chid','caid','mid','aid','func','mpfunc','sqlstr','vid','vsource','vids','chdata','js','checked','nsid','cnid','cnsource','level','caids','limits',
'validperiod','thumb','nocp','asc','chids','nochids','val','tname','disabled','face','source','pmid','isfunc','type','id',);//只要为空或为0就可以清除的参数
foreach($cotypes as $k => $v){
	$unsetvars[] = 'cosource'.$k;
	$unsetvars[] = 'coinherit'.$k;
	$unsetvars[] = 'ccid'.$k;
	$unsetvars[] = 'ccidson'.$k;
	$unsetvars[] = 'ccids'.$k;
}
foreach($grouptypes as $k => $v){
	$unsetvars[] = 'ugsource'.$k;
	$unsetvars[] = 'ugids'.$k;
}
$unsetvars1 = array('val' => array('v','u'),'limits' => array('10',));

if($action == 'mtagadd'){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.urlencode($forward);
	$tclass = empty($mtagnew['tclass']) ? '' : $mtagnew['tclass'];
	if(!submitcheck('bmtagadd') && !submitcheck('bmtagcode')){
		$upform = (!empty($mtagnew['tclass']) && in_array($mtagnew['tclass'],array('image','images',))) ? 1 : 0;
		$helpstr = empty($mtagnew['tclass']) || $ttype == 'rtag' ? '' : "&nbsp; &nbsp; [<a href=\"tools/taghelp.html#".(str_replace('tag','',$ttype).'_'.$mtagnew['tclass'])."\" target=\"08cmstaghelp\">".lang('help')."</a>]";
		tabheader(lang('addtype',lang($ttype)).$helpstr,'mtagsadd',"?entry=mtags&action=mtagadd&ttype=$ttype$param_suffix$forwardstr",2,$upform);
		trbasic(lang('tagname'),'mtagnew[cname]',empty($mtagnew['cname']) ? '' : $mtagnew['cname']);
		trbasic(lang('enid'),'mtagnew[ename]',empty($mtagnew['ename']) ? '' : $mtagnew['ename']);
		if(empty($mtagnew['tclass']) && $ttype != 'rtag'){
			trbasic(lang('tagtype'),'mtagnew[tclass]',makeoption($tclassarr),'select');
			tabfooter('bmtagaddpre',lang('continue'));
		}else{
			if($ttype != 'rtag'){
				trbasic(lang('tagtype'),'',$tclassarr[$mtagnew['tclass']],'');
				echo "<input type=\"hidden\" name=\"mtagnew[tclass]\" value=\"$mtagnew[tclass]\">\n";
			}
			include_once M_ROOT."./include/mtags/$ttype/".($tclass ? $tclass : 'tag').".php";
			echo "<input class=\"button\" type=\"submit\" name=\"bmtagadd\" value=\"".lang('add')."\">".
			($ttype != 'rtag' ? "&nbsp; &nbsp; &nbsp; &nbsp; <input class=\"button\" type=\"submit\" name=\"bmtagcode\" value=\"".lang('createcode')."\" onclick=\"this.form.action='?entry=mtags&action=mtagadd&ttype=$ttype$param_suffix';this.form.target='mtagcodeiframe';\">" : '').
			"</form><br><iframe id=\"mtagcodeiframe\" name=\"mtagcodeiframe\" frameborder=\"0\" width=\"100%\"  height=\"200\" style=\"display:none\"></iframe>";
		}
		a_guide($ttype.(empty($mtagnew['tclass']) ? 'edit' : $mtagnew['tclass']));
		
	}elseif(submitcheck('bmtagcode')){
		$errormsg = '';
		if(empty($mtagnew['ename'])){
			$errormsg = lang('inptagenid');
		}elseif(preg_match("/[^a-z_A-Z0-9]+/",$mtagnew['ename'])){
			$errormsg = lang('tagenidill');
		}else{
			$mtagnew['ename'] = trim(strtolower($mtagnew['ename']));
			$usedename = array_keys($mtags);
			if(in_array($mtagnew['ename'], $usedename)) $errormsg = lang('tagenidill');
		}
		if(!$errormsg){
			$tclass = empty($mtagnew['tclass']) ? '' : $mtagnew['tclass'];
			include_once M_ROOT."./include/mtags/$ttype/".($tclass ? $tclass : 'tag').".php";
			if(!$errormsg){
				$mtagnew['setting'] = empty($mtagnew['setting']) ? array() : $mtagnew['setting'];
				if(!empty($mtagnew['setting'])){
					foreach($mtagnew['setting'] as $key => $val){
						if(in_array($key,$unsetvars) && empty($val)) unset($mtagnew['setting'][$key]);
						if(!empty($unsetvars1[$key]) && in_array($val,$unsetvars1[$key])) unset($mtagnew['setting'][$key]);
					}
				}
				$mtagnew['template'] = empty($mtagnew['template']) ? '' : stripslashes($mtagnew['template']);
				$mtagcode = mtag_code($ttype,$mtagnew);
				echo "<script language=\"javascript\" reload=\"1\">parent.\$id('mtagcodeiframe').style.display='';</script>".
				"<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#D3ECFC\" style=\"padding:0px;margin:0px;\">".
				"<tr class=\"txt\"><td class=\"txtL w25B\">".lang('curtagoftemcod').
				"<br><br>[<a href='#' onclick=\"mtagcodecopy(\$id('mtagcode'));\">".lang('copycode')."</a>]&nbsp; &nbsp; [<a href='#' onclick=\"parent.\$id('mtagcodeiframe').style.display='none';\">".lang('closecode')."</a>]</td><td class=\"item2\">".
				"<textarea rows=\"8\" name=\"mtagcode\" id=\"mtagcode\" cols=\"110\">".(empty($mtagcode) ? '' : htmlspecialchars(str_replace("\t","    ",$mtagcode)))."</textarea>";
				"</td></tr></table>";
			}
		}
		echo "<script language=\"javascript\" reload=\"1\">".($errormsg ? "alert('$errormsg');" : '')."parent.\$id('mtagsadd').action='?entry=mtags&action=mtagadd&ttype=$ttype$param_suffix';parent.\$id('mtagsadd').target='_self';</script>";
		mexit();
	}else{
		$error_url = "?entry=mtags&action=mtagadd&ttype=$ttype$param_suffix$forwardstr".($ttype == 'rtag' ? '' : '&mtagnew[tclass]='.$mtagnew['tclass']);
		if(empty($mtagnew['cname']) || empty($mtagnew['ename'])){
			amessage('tagdatamiss',$error_url);
		}
		if(preg_match("/[^a-z_A-Z0-9]+/",$mtagnew['ename'])){
			amessage('tagenidill',$error_url);
		}
		$mtagnew['ename'] = trim(strtolower($mtagnew['ename']));
		$usedename = array_keys($mtags);
		if(in_array($mtagnew['ename'], $usedename)){
			amessage('tagenidrep',$error_url);
		}
		$tclass = empty($mtagnew['tclass']) ? '' : $mtagnew['tclass'];
		include_once M_ROOT."./include/mtags/$ttype/".($tclass ? $tclass : 'tag').".php";
		$mtagnew['setting'] = empty($mtagnew['setting']) ? array() : $mtagnew['setting'];
		if(!empty($mtagnew['setting'])){
			foreach($mtagnew['setting'] as $key => $val){
				if(in_array($key,$unsetvars) && empty($val)) unset($mtagnew['setting'][$key]);
			}
		}
		$mtagnew['template'] = empty($mtagnew['template']) ? '' : stripslashes($mtagnew['template']);
		$mtag = array(
		'cname' => stripslashes($mtagnew['cname']),
		'ename' => $mtagnew['ename'],
		'tclass' => $tclass,
		'template' => $mtagnew['template'],
		'setting' => $mtagnew['setting'],
		);
		$mtags[$mtagnew['ename']] = array('tclass' => $tclass,'vieworder' => 0,);
		cache2file($mtag,cache_name($ttype,$mtagnew['ename']),$ttype,$sid);
		mtags_order($mtags);
		cache2file($mtags,$ttype.'s',$ttype.'s',$sid);
		adminlog(lang('addtype',$ttype));
		amessage('tagaddfin',$forward);
	}
}elseif($action == 'mtagcode'){
	empty($mtags[$tname]) && amessage('choosetag');
	$mtag = read_cache($ttype,$tname,'',$sid);
	$tclass = empty($mtag['tclass']) ? '' : $mtag['tclass'];
	$mtagcode = mtag_code($ttype,$mtag);
	$helpstr = $ttype == 'rtag' ? '' : "&nbsp; &nbsp; [<a href=\"tools/taghelp.html#".(str_replace('tag','',$ttype).'_'.$tclass)."\" target=\"08cmstaghelp\">".lang('help')."</a>]";
	tabheader(lang($ttype).'&nbsp; -&nbsp; '.$tclassarr[$mtag['tclass']].'&nbsp; -&nbsp; '.$mtag['cname']);
	echo "<tr class=\"txt\"><td class=\"txtL\">".lang('curtagtemcod').$helpstr.
	"<br><br>[<a href='#' onclick=\"mtagcodecopy(\$id('mtagcode'));\">".lang('copycode')."</a>]&nbsp; &nbsp; [<a href='#' onclick=\"window.close();\">".lang('closewindow')."</a>]".
	"</td><td class=\"txtL\"><textarea rows=\"15\" name=\"mtagcode\" id=\"mtagcode\" cols=\"90\">".(empty($mtagcode) ? '' : htmlspecialchars(str_replace("\t","    ",$mtagcode)))."</textarea></td></tr>";
	tabfooter();	
}elseif($action == 'mtagsedit'){
	$keyword = empty($keyword) ? '' : trim($keyword);
	if(!submitcheck('bmtagsedit')){
		$rsubmiturl = "?entry=mtags&action=mtagsedit&ttype=$ttype$param_suffix".($tclass ? "&tclass=$tclass" : '');
		if($ttype != 'rtag'){
			$ftclassarr = array();
			foreach($tclassarr as $k => $v) $ftclassarr[] = $tclass == $k ? "<b>-$v-</b>" : "<a href=\"?entry=mtags&action=mtagsedit&ttype=$ttype$param_suffix&tclass=$k\">$v</a>";
			$ftclassarr[] = $tclass == 'sch' ? "<b>-".lang('tagsearch')."-</b>" : ">><a href=\"?entry=mtags&action=mtagsedit&ttype=$ttype$param_suffix&tclass=sch\">".lang('tagsearch')."</a>";
			echo tab_list($ftclassarr,9,0);
		}
		$searchstr = $tclass == 'sch' ? "&nbsp; &nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"12\" style=\"vertical-align: middle;\">&nbsp;&nbsp;<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">" : '';
		$helpstr = $ttype == 'rtag' ? '' : "&nbsp; &nbsp; [<a href=\"tools/taghelp.html".($tclass ? '#'.str_replace('tag','',$ttype).'_'.$tclass : '')."\" target=\"08cmstaghelp\">".lang('help')."</a>]";
		tabheader(lang($ttype.'_admin').($tclass == 'sch' ? '' : "&nbsp; &nbsp; [<a href=\"?entry=mtags&action=mtagadd&ttype=$ttype$param_suffix&mtagnew[tclass]=$tclass\">".lang('add').'</a>]').$helpstr.$searchstr,'mtagsedit',$rsubmiturl,'9');
		trcategory(array(lang('sn'),'<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),array(lang('tagname'),'txtL'),array(lang('tag_style'),'txtL'),lang('type'),lang('order'),lang('close'),lang('edit'),lang('copy'),lang('usual'),lang('code')));
		$i = 1;
		foreach($mtags as $key => $mtag){
			if($tclass == $mtag['tclass'] || $tclass == 'sch'){
				$vieworder = $mtag['vieworder'];
				$mtag = read_cache($ttype,$key,'',$sid);
				if(!$keyword || in_str($keyword,$mtag['ename']) || in_str($keyword,$mtag['cname'])){
					$mtagcodestr = $tclass ? "<a href=\"?entry=mtags&action=mtagcode&ttype=$ttype$param_suffix&tname=$key\" onclick=\"return floatwin('open_mtagsedit',this)\">".lang('code')."</a>" : '-';
					$setusualstr = "<a href=\"?entry=mtags&action=setusual&ttype=$ttype$param_suffix&tname=$key\" onclick=\"return floatwin('open_mtagsedit',this)\">".lang('usual')."</a>";
					echo "<tr class=\"txt\">".
						"<td class=\"txtC w30\">$i</td>\n".
						"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$key]\" value=\"$key\"></td>\n".
						"<td class=\"txtL\"><input type=\"text\" size=\"30\" maxlength=\"30\" name=\"mtagsnew[$key][cname]\" value=\"".mhtmlspecialchars($mtag['cname'])."\"></td>\n".
						"<td class=\"txtL\">".tag_style($key)."</td>\n".
						"<td class=\"txtC w100\">".@$tclassarr[$mtag['tclass']]."</td>\n".
						"<td class=\"txtC w50\"><input type=\"text\" size=\"4\" name=\"mtagsnew[$key][vieworder]\" value=\"$vieworder\"></td>\n".
						"<td class=\"txtC w40\">".($tclass ? "<input class=\"checkbox\" type=\"checkbox\" name=\"mtagsnew[$key][disabled]\" value=\"1\"".(empty($mtag['disabled']) ? '' : ' checked').">" : '-')."</td>\n".
						"<td class=\"txtC w30\"><a href=\"?entry=mtags&action=mtagsdetail&ttype=$ttype$param_suffix&tname=$key\" onclick=\"return floatwin('open_mtagsedit',this)\">".lang('detail')."</a></td>\n".
						"<td class=\"txtC w30\"><a href=\"?entry=mtags&action=mtagscopy&ttype=$ttype$param_suffix&tname=$key\" onclick=\"return floatwin('open_mtagsedit',this)\">".lang('copy')."</a></td>\n".
						"<td class=\"txtC w30\">$setusualstr</td>\n".
						"<td class=\"txtC w30\">$mtagcodestr</td>\n".
						"</tr>\n";
					$i ++;
				}
			}
		}
		tabfooter('bmtagsedit',lang('modify'));
		a_guide($ttype.'edit');
	}else{
		if(!empty($delete)){
			foreach($delete as $key){
				del_cache($ttype,$key,'',$sid);
				unset($mtagsnew[$key],$mtags[$key]);
			}
		}
		if(!empty($mtagsnew)){
			foreach($mtagsnew as $key => $mtagnew){
				$mtagnew['vieworder'] = max(0,intval($mtagnew['vieworder']));
				$mtagnew['cname'] = empty($mtagnew['cname']) ? $mtags[$key]['cname'] : $mtagnew['cname'];
				$mtag = read_cache($ttype,$key,'',$sid);
				if(($mtagnew['cname'] != $mtag['cname']) || ($mtagnew['vieworder'] != $mtags[$key]['vieworder']) || (@$mtagnew['disabled'] != @$mtag['disabled'])){
					$mtag['cname'] = stripslashes($mtagnew['cname']);
					$mtags[$key]['vieworder'] = $mtagnew['vieworder'];
					if(empty($mtagnew['disabled'])){
						unset($mtag['disabled']);
					}else $mtag['disabled'] = 1;
					cache2file($mtag,cache_name($ttype,$key),$ttype,$sid);
				}
			}
		}
		mtags_order($mtags);
		cache2file($mtags,$ttype.'s',$ttype.'s',$sid);
		adminlog(lang('edit_'.$ttype.'_mlist'));
		amessage('tagmodfin',M_REFERER);
	}
}
elseif($action == 'setusual' && $tname){//设为常用分类
	empty($mtags[$tname]) && amessage('choosetag');
	$mtag = read_cache($ttype,$tname,'',$sid);
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.urlencode($forward);
	if(!submitcheck('bsetusual')){
		tabheader(lang('setusualtag'),'setusual',"?entry=mtags&action=setusual&ttype=$ttype$param_suffix&tname=$tname$forwardstr");
		trbasic(lang('tagname'),'',$mtag['cname'],'');
		trbasic(lang('usualtagremark'),'mtagnew[title]',empty($usualtags[$ttype.'_'.$tname]['title']) ? '' : $usualtags[$ttype.'_'.$tname]['title'],'btext');
		$tagclassesarr = array();
		foreach($tagclasses as $k => $v) $tagclassesarr[$k] = $v['cname'];
		trbasic(lang('usualtagclass'),'',makecheckbox('mtagnew[class][]',$tagclassesarr,empty($usualtags[$ttype.'_'.$tname]['class']) ? array() : explode(',',$usualtags[$ttype.'_'.$tname]['class'])),'');
		tabfooter('bsetusual');
	}
	else{
		$mtagnew['class'] = empty($mtagnew['class']) ? '' : implode(',',$mtagnew['class']);
		$usualtags[$ttype.'_'.$tname] = array(
		'title' => stripslashes(trim($mtagnew['title'])),
		'class' => $mtagnew['class'],
		'ttype' => $ttype,
		'tname' => $tname,
		'vieworder' => 0,
		);
		mtags_order($usualtags);
		cache2file($usualtags,'usualtags','usualtags',$sid);
		amessage('setusualfin',axaction(6,$forward));
	}
}
elseif($action == 'mtagscopy' && $tname){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.urlencode($forward);
	empty($mtags[$tname]) && amessage('choosetag',$forward);
	$mtag = read_cache($ttype,$tname,'',$sid);
	if(!submitcheck('bmtagscopy')){
		tabheader(lang('copy '.$ttype),'mtagscopy',"?entry=mtags&action=mtagscopy&ttype=$ttype$param_suffix&tname=$tname$forwardstr");
		$ttype != 'rtag' && trbasic(lang('tagtype'),'',$tclassarr[$mtag['tclass']],'');
		trbasic(lang('soctagcname'),'',$mtag['cname'],'');
		trbasic(lang('soctagid'),'',$mtag['ename'],'');
		$ttype == 'rtag' && trbasic(lang('soctempcnam'),'',$mtag['template'],'');
		trbasic(lang('newtagcnam'),'mtagnew[cname]');
		trbasic(lang('newtagid'),'mtagnew[ename]');
		$ttype == 'rtag' && trbasic(lang('newtempcna'),'mtagnew[template]');
		tabfooter('bmtagscopy');
		a_guide($ttype.'edit');
	}
	else{
		if(!$mtagnew['cname'] || !$mtagnew['ename']) amessage('tagdatamiss',M_REFERER);
		if(preg_match("/[^a-z_A-Z0-9]+/",$mtagnew['ename'])) amessage('tagenidill',M_REFERER);
		if($ttype == 'rtag'){
			if(empty($mtagnew['template'])) amessage('tagdatamiss',M_REFERER);
			if(preg_match("/[^a-z_A-Z0-9\.]+/",$mtagnew['template'])) amessage('temfilcnaill',M_REFERER);
			$true_tpldir = M_ROOT."./template/$templatedir/";
			if(!copy($true_tpldir.$mtag['template'],$true_tpldir.$mtagnew['template'])) amessage('temcopfai',M_REFERER);
		}
		$mtagnew['ename'] = trim(strtolower($mtagnew['ename']));
		$usedename = array_keys($mtags);
		if(in_array($mtagnew['ename'], $usedename))amessage('tagenidrep',M_REFERER);
		$mtag['cname'] = stripslashes($mtagnew['cname']);
		$mtag['ename'] = $mtagnew['ename'];
		if($ttype == 'rtag') $mtag['template'] = $mtagnew['template'];
		$mtags[$mtagnew['ename']] = array('tclass' => $mtag['tclass'],'vieworder' => 0,);
		cache2file($mtag,cache_name($ttype,$mtagnew['ename']),$ttype,$sid);
		mtags_order($mtags);
		cache2file($mtags,$ttype.'s',$ttype.'s',$sid);
		adminlog(lang('copy '.$ttype));
		amessage('tagcopfin',axaction(6,$forward));
	}
}
elseif($action == 'mtagsdetail' && $tname){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.urlencode($forward);
	empty($mtags[$tname]) && amessage('choosetag',$forward);
	$mtag = read_cache($ttype,$tname,'',$sid);
	$tclass = empty($mtag['tclass']) ? '' : $mtag['tclass'];
	if(!submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
		$upform = in_array($mtag['tclass'],array('image','images',)) ? 1 : 0;
		$helpstr = $ttype == 'rtag' ? '' : "&nbsp; &nbsp; [<a href=\"tools/taghelp.html#".(str_replace('tag','',$ttype).'_'.$mtag['tclass'])."\" target=\"08cmstaghelp\">".lang('help')."</a>]";
		tabheader(lang('typeset',lang($ttype)).$helpstr,'mtagsdetail',"?entry=mtags&action=mtagsdetail&ttype=$ttype$param_suffix&tname=$tname$forwardstr",2,$upform);
		$disabledstr = empty($mtag['disabled']) ? '' : lang('tagdisabled');
		($ttype != 'rtag') && trbasic(lang('tagtype'),'',@$tclassarr[$mtag['tclass']].'&nbsp; '.$disabledstr,'');
		trbasic(lang('tagname'),'mtagnew[cname]',$mtag['cname']);
		trbasic(lang('enid'),'mtagnew[ename]',$tname);
		include_once M_ROOT."./include/mtags/$ttype/".($tclass ? $tclass : 'tag').".php";
		echo "<input class=\"button\" type=\"submit\" name=\"bmtagsdetail\" value=\"".lang('submit')."\">".
		($ttype != 'rtag' ? "&nbsp; &nbsp; &nbsp; &nbsp; <input class=\"button\" type=\"submit\" name=\"bmtagcode\" value=\"".lang('createcode')."\" onclick=\"this.form.action='?entry=mtags&action=mtagsdetail&ttype=$ttype$param_suffix&tname=$tname';this.form.target='mtagcodeiframe';\">" : '').
		"</form><br><iframe id=\"mtagcodeiframe\" name=\"mtagcodeiframe\" frameborder=\"0\" width=\"100%\"  height=\"200\" style=\"display:none\"></iframe>";
		a_guide($ttype.(empty($mtag['tclass']) ? 'edit' : $mtag['tclass']));
	}elseif(submitcheck('bmtagcode')){
		$errormsg = '';
		if(empty($mtagnew['ename'])){
			$errormsg = lang('inptagenid');
		}elseif(preg_match("/[^a-z_A-Z0-9]+/",$mtagnew['ename'])){
			$errormsg = lang('tagenidill');
		}else{
			$mtagnew['ename'] = trim(strtolower($mtagnew['ename']));
			$usedename = array_keys($mtags);
			if(($mtagnew['ename'] != $tname) && in_array($mtagnew['ename'], $usedename)) $errormsg = lang('tagenidill');
		}
		if(!$errormsg){
			$tclass = $mtagnew['tclass'] = $mtag['tclass'];
			include_once M_ROOT."./include/mtags/$ttype/".($tclass ? $tclass : 'tag').".php";
			if(!$errormsg){
				$mtagnew['disabled'] = @$mtag['disabled'];
				$mtagnew['setting'] = empty($mtagnew['setting']) ? array() : $mtagnew['setting'];
				if(!empty($mtagnew['setting'])){
					foreach($mtagnew['setting'] as $key => $val){
						if(in_array($key,$unsetvars) && empty($val)) unset($mtagnew['setting'][$key]);
						if(!empty($unsetvars1[$key]) && in_array($val,$unsetvars1[$key])) unset($mtagnew['setting'][$key]);
					}
				}
				$mtagnew['template'] = empty($mtagnew['template']) ? '' : stripslashes($mtagnew['template']);
				$mtagcode = mtag_code($ttype,$mtagnew);
				echo "<script language=\"javascript\" reload=\"1\">parent.\$id('mtagcodeiframe').style.display='';</script>".
				"<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#D3ECFC\" style=\"padding:0px;margin:0px;\">".
				"<tr class=\"txt\"><td class=\"txtL w25B\">".lang('curtagoftemcod').
				"<br><br>[<a href='#' onclick=\"mtagcodecopy(\$id('mtagcode'));\">".lang('copycode')."</a>]&nbsp; &nbsp; [<a href='#' onclick=\"parent.\$id('mtagcodeiframe').style.display='none';\">".lang('closecode')."</a>]</td><td class=\"item2\">".
				"<textarea rows=\"8\" name=\"mtagcode\" id=\"mtagcode\" cols=\"90\">".(empty($mtagcode) ? '' : htmlspecialchars(str_replace("\t","    ",$mtagcode)))."</textarea>";
				"</td></tr></table>";
			}
		}
		echo "<script language=\"javascript\" reload=\"1\">".($errormsg ? "alert('$errormsg');" : '')."parent.\$id('mtagsdetail').action='?entry=mtags&action=mtagsdetail&ttype=$ttype$param_suffix&tname=$tname';parent.\$id('mtagsdetail').target='_self';</script>";
		mexit();
	}else{
		if(!$mtagnew['cname'] || !$mtagnew['ename']) {
			amessage('tagdatamiss',M_REFERER);
		}
		if(preg_match("/[^a-z_A-Z0-9]+/",$mtagnew['ename'])) {
			amessage('tagenidill',M_REFERER);
		}
		$mtagnew['ename'] = trim(strtolower($mtagnew['ename']));
		$usedename = array_keys($mtags);
		if(($mtagnew['ename'] != $tname) && in_array($mtagnew['ename'], $usedename)){
			amessage('tagenidrep',M_REFERER);
		}
		$tclass = $mtag['tclass'];
		include_once M_ROOT."./include/mtags/$ttype/".($tclass ? $tclass : 'tag').".php";
		$mtagnew['setting'] = empty($mtagnew['setting']) ? array() : $mtagnew['setting'];
		if(!empty($mtagnew['setting'])){
			foreach($mtagnew['setting'] as $key => $val){
				if(in_array($key,$unsetvars) && empty($val)) unset($mtagnew['setting'][$key]);
				if(!empty($unsetvars1[$key]) && in_array($val,$unsetvars1[$key])) unset($mtagnew['setting'][$key]);
			}
		}
		$mtagnew['template'] = empty($mtagnew['template']) ? '' : stripslashes($mtagnew['template']);
		$mtagnew['disabled'] = empty($mtag['disabled']) ? 0 : 1;
		$mtag = array(
		'cname' => stripslashes($mtagnew['cname']),
		'ename' => $mtagnew['ename'],
		'tclass' => $tclass,
		'template' => $mtagnew['template'],
		'setting' => $mtagnew['setting'],
		'disabled' => $mtagnew['disabled'],
		);
		$mtags[$mtagnew['ename']] = array('tclass' => $tclass,'vieworder' => $mtags[$tname]['vieworder'],);
		cache2file($mtag,cache_name($ttype,$mtagnew['ename']),$ttype,$sid);
		if($mtagnew['ename'] != $tname){
			del_cache($ttype,$tname,'',$sid);
			unset($mtags[$tname]);
		}
		mtags_order($mtags);
		cache2file($mtags,$ttype.'s',$ttype.'s',$sid);
		adminlog(lang('detamod'.$ttype));
		amessage('tagmodfin',axaction(6,$forward));
	}
}
function mtags_order(&$mtags){
	if(!is_array($mtags) || empty($mtags) || !function_exists('array_multisort')) return;
	foreach($mtags as $k => $v){
		$vorder[$k] = $v['vieworder'];
		$eorder[$k] = $k;
	}
	array_multisort($vorder,SORT_ASC,$eorder,SORT_ASC,$mtags);
}
function tag_style($ename){
	global $ttype;
	return "{".($ttype == 'rtag' ? 'tpl' : str_replace('tag','',$ttype))."\$<b>$ename</b>}";
}
function mtag_code($ttype,$mtag){
	$mode = str_replace('tag','',$ttype);
	$str = '{'.$mode.'$'.$mtag['ename'];//起始符
	!empty($mtag['tclass']) && $str .= ' [tclass='.$mtag['tclass'].'/]';
	!empty($mtag['disabled']) && $str .= ' [disabled=1/]';
	if(!empty($mtag['setting'])){
		foreach($mtag['setting'] as $key => $val){
			$str .= ' ['.$key.'='.$val.'/]';
		}
	}
	$str .= "}";//参数中止
	!empty($mtag['template']) && $str .= $mtag['template'];//加入模板
	$str .= '{/'.$mode.'$'.$mtag['ename'].'}';//加入结束符
	return $str;
	
}

?>
