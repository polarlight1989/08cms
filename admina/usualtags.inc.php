<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('tpl') || amessage('no_apermission');
load_cache('channels,fchannels,fcatalogs,cotypes,votes,commus,mcommus,shipings,vcatalogs,mchannels,dbsources,acatalogs,mcatalogs,matypes');
$catalogs = &$acatalogs;
cache_merge($channels,'channels',$sid);
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/template.fun.php";
empty($utclass) && $utclass = '0';
load_cache('usualtags,tagclasses',$sid);
#$utc = 'utc';
#$utclen = strlen($utc);
$tagclass = array();
foreach($tagclasses as $k => $v)$tagclass[$k] = $v['cname'];
$url_type = 'usualtags';include 'urlsarr.inc.php';
if(empty($action)){
	url_nav(lang('usualtagsadmin'),$urlsarr,'usualtags');
	if(!submitcheck('busualtagsedit')){
		$rsubmiturl = "?entry=usualtags$param_suffix".($utclass ? "&utclass=$utclass" : '');
		$tagclass['no_class'] = lang('noclass');
		if(empty($utclass)) foreach($tagclass as $k => $v){$utclass = $k;break;}
		$utclassarr = array();
		foreach($tagclass as $k => $v) $utclassarr[] = $utclass == $k ? "<b>-$v-</b>" : "<a href=\"?entry=usualtags&utclass=$k$param_suffix\">$v</a>";
		echo tab_list($utclassarr,9,0);
		unset($tagclass['no_class']);
	
		$helpstr = "&nbsp; &nbsp; [<a href=\"tools/taghelp.html\" target=\"08cmstaghelp\">".lang('help')."</a>]";
		tabheader(lang('usualtagsadmin').$helpstr,'usualtagsedit',$rsubmiturl,'9');
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('usualtagremark'),'txtL'),array(lang('tagname'),'txtL'),array(lang('tagtype'),'txtL'),array(lang('tag_style'),'txtL'),lang('order'),lang('edit')));
		foreach($usualtags as $key => $tag){
			$nclasses = empty($tag['class']) ? array() : explode(',',$tag['class']);
			$nclasses = array_intersect($nclasses,array_keys($tagclass));
			if(in_array($utclass,$nclasses) || ($utclass == 'no_class' && empty($nclasses))){
				$ttype = $tag['ttype'];
				$title = $tag['title'];
				$vieworder = $tag['vieworder'];
				$tag = read_cache($ttype,$tag['tname'],'',$sid);
				$tclassstr = fetch_class($ttype,$tag['tclass']);
				$title || $title = $tag['cname'];
				echo "<tr class=\"txt\">".
					"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[]\" value=\"$key\"></td>\n".
					"<td class=\"txtL\"><input type=\"text\" size=\"30\" maxlength=\"30\" name=\"usualtagsnew[$key][title]\" value=\"".mhtmlspecialchars($title)."\"></td>\n".
					"<td class=\"txtL\">".mhtmlspecialchars($tag['cname'])."</td>\n".
					"<td class=\"txtL\">$tclassstr</td>\n".
					"<td class=\"txtL\">".tag_style($tag['ename'], $ttype)."</td>\n".
					"<td class=\"txtC w50\"><input type=\"text\" size=\"4\" name=\"usualtagsnew[$key][vieworder]\" value=\"$vieworder\"></td>\n".
					"<td class=\"txtC w30\"><a href=\"?entry=mtags&action=mtagsdetail&ttype=$ttype$param_suffix&tname=$tag[ename]\" onclick=\"return floatwin('open_mtagsedit',this)\">".lang('detail')."</a></td>\n".
					"</tr>\n";
			}
		}
		tabfooter();

		//操作区
		tabheader(lang('operate_item'));
		trbasic(lang('choose_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"tagdeal[delete]\" value=\"1\">&nbsp;".lang('delete'),'');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"tagdeal[utclass]\" value=\"1\">&nbsp;".lang('set').lang('usualtagclass'),'',makecheckbox('tagutclass[]',$tagclass),'');

		tabfooter('busualtagsedit');
		a_guide('usualtags_edit');
	}else{
		if(!empty($selectid)){
			foreach($selectid as $key){
				if(!empty($tagdeal['delete'])){
					unset($usualtagsnew[$key],$usualtags[$key]);
					continue;
				}
				if(!empty($tagdeal['utclass'])){
					$usualtags[$key]['class'] = empty($tagutclass) ? '' : implode(',',$tagutclass);
				}
			}
		}
		if(!empty($usualtagsnew)){
			foreach($usualtagsnew as $key => $tagnew){
				$usualtags[$key]['vieworder'] = max(0,intval($tagnew['vieworder']));
				$usualtags[$key]['title'] = empty($tagnew['title']) ? $usualtags[$key]['title'] : $tagnew['title'];
			}
		}
		multisort($usualtags);
		cache2file($usualtags,'usualtags','usualtags',$sid);
		adminlog(lang('edit_usualtags_mlist'));
		amessage('tagmodfin',axaction(1,M_REFERER));
	}
}elseif($action == 'tagclasses'){
	url_nav(lang('usualtagsadmin'),$urlsarr,'tagclasses');
	if(!submitcheck('btagclassesedit')){
		tabheader(lang('tagclassesadmin'),'tagclassesedit',"?entry=usualtags&action=tagclasses$param_suffix",'9');
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('usualtagclass'),'txtL'),lang('order')));
		foreach($tagclasses as $key => $cls){
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[]\" value=\"$key\"></td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"30\" maxlength=\"30\" name=\"utclassnew[$key][cname]\" value=\"".mhtmlspecialchars($cls['cname'])."\"></td>\n".
				"<td class=\"txtC w50\"><input type=\"text\" size=\"4\" name=\"utclassnew[$key][vieworder]\" value=\"$cls[vieworder]\"></td>\n".
				"</tr>\n";
		}
		tabfooter();

		//操作区
		tabheader(lang('operate_item'));
		trbasic(lang('choose_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"clsdeal[delete]\" value=\"1\">&nbsp;".lang('delete'),'');
		trbasic(lang('add').lang('usualtagclass'),'','<input type="text" name="tagutclass" size="25">&nbsp;&nbsp;'.lang('order').'&nbsp;<input type="text" name="tagutclassorder" size="4">','');

		tabfooter('btagclassesedit');
		a_guide('tagclasses_edit');
	}else{
		if(!empty($selectid)){
			foreach($selectid as $key){
				if(!empty($clsdeal['delete'])){
					unset($tagclasses['data'][$key]);
					continue;
				}
			}
		}
#		$clsindex = array();
		$clscname = array();
		foreach($tagclasses as $key => $clsnew){
#			$clsindex[] = substr($key, $utclen);
			$clscname[] = $clsnew['cname'];
		}/*
		if(empty($clsindex)){
			$clsindex = 1;
		}else{
			sort($clsindex);
			$clsindex = intval(end($clsindex)) + 1;
		}*/
		if(!empty($tagutclass)){
			in_array($tagutclass, $clscname) && amessage('utcls_exist',axaction(1,M_REFERER));
#			$tagclasses[$utc.$clsindex] = array(
			$tagclasses[auto_utc_index()] = array(
							'cname' => $tagutclass,
							'vieworder' => max(0, intval($tagutclassorder))
			);
		}
		if(!empty($utclassnew)){
			foreach($utclassnew as $key => $clsnew){
				if(array_key_exists($key, $tagclasses)){
					!empty($clsnew['cname']) && !in_array($clsnew['cname'], $clscname) && $tagclasses[$key]['cname'] = $clsnew['cname'];
					$tagclasses[$key]['vieworder'] = max(0, intval($clsnew['vieworder']));
				}
			}
		}
		multisort($tagclasses);
		cache2file($tagclasses,'tagclasses','usualtags',$sid);
		adminlog(lang('edit_tagclasses_mlist'));
		amessage('utcls_fin',axaction(1,M_REFERER));
	}
}

function multisort(&$arr){
	if(!is_array($arr) || empty($arr) || !function_exists('array_multisort')) return;
	foreach($arr as $k => $v){
		$vorder[$k] = $v['vieworder'];
		$eorder[$k] = $k;
	}
	array_multisort($vorder,SORT_ASC,$eorder,SORT_ASC,$arr);
}

function tag_style($ename, $ttype){
	return "{".($ttype == 'rtag' ? 'tpl' : str_replace('tag','',$ttype))."\$<b>$ename</b>}";
}
function auto_utc_index(){
	global $timestamp;
	$str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$len = strlen($str);
	$ret = '';
	$tmp = mt_rand(0, 0x7fffffff);
	while($tmp){
		$qer = $tmp % $len;
		$tmp = ($tmp - $qer) / $len;
		$ret = $str{$qer} . $ret;
	}
	$tmp = $timestamp;
	while($tmp){
		$qer = $tmp % $len;
		$tmp = ($tmp - $qer) / $len;
		$ret = $str{$qer} . $ret;
	}
	return $ret;
}
function fetch_class($ttype,$tclass=''){
	if(!$tclass || $ttype == 'rtag') return '-';
	switch($ttype){
		case 'ctag':
			$tclassarr = array(
			'archives' => lang('archive_list'),
			'catalogs' => lang('cataslist'),
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
			'userinfos' => lang('membermessage'),
			'keywords' => lang('keywlist'),
			'channels' => lang('archichanlist'),
			'mchannels' => lang('membchalist'),
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
			);
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
		break;
	}
	return empty($tclassarr[$tclass]) ? '-' : $tclassarr[$tclass];

}
?>
