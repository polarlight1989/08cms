<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
function p_tagsmap($tplname){
	$tagsarr = array();
	$template = load_tpl($tplname,1);
	if(!$template) return $tagsarr;
	$tagsarr = toptags($template,'c');
	return $tagsarr;
}
function m_tagsmap($tplname){
	$tagsarr = array();
	$template = load_tpl($tplname,0);
	if(!$template) return $tagsarr;
	foreach(array('b','u','c','p','tpl',) as $k) $tagsarr = array_merge($tagsarr,findtags($template,$k));
	return $tagsarr;
}
function tagmap($tname,$ttype,$level = 1){
	$tagsarr = array();
	$tag = read_tag($ttype,$tname);
	if(empty($tag['template'])) return $tagsarr;
	$template = $tag['template'];

	$tagsarr = array_merge($tagsarr,findtags($template,'b',$tname,$level));
	$tagsarr = array_merge($tagsarr,findtags($template,'u',$tname,$level));
	$tagsarr = array_merge($tagsarr,findtags($template,'c',$tname,$level));
	return $tagsarr;
}
function findtags($sstr,$mode = 'b',$pid='',$level=0){
	global $utags,$bnames,$ctags,$ptags,$rtags;
	$result = $temptags = array();
	if($mode == 'u'){
		preg_match_all("/\{u\\$(.+?)\}/is",$sstr,$matches);
		$temptags = array_unique($matches[1]);
		foreach($temptags as $tname){
			$temptag = array();
			$temptag['tname'] = $tname;
			$temptag['error'] = !isset($utags[$tname]) ? 1 : 0;
			$temptag['pid'] = $pid;
			$temptag['level'] = $level;
			$temptag['tmode'] = 'u';
			$result[] = $temptag;
		}
	}elseif($mode == 'b'){
		preg_match_all("/\{\\$(.+?)\}/is",$sstr,$matches);
		$temptags = array_unique($matches[1]);
		foreach($temptags as $tname){
			$temptag = array();
			$temptag['tname'] = $tname;
			$temptag['error'] = !isset($bnames[$tname]) ? 1 : 0;
			$temptag['pid'] = $pid;
			$temptag['level'] = $level;
			$temptag['tmode'] = 'b';
			$result[] = $temptag;
		}
	}elseif($mode == 'c'){
		preg_match_all("/\{c\\$(.+?)\}/is",$sstr,$matches);
		$temptags = array_unique($matches[1]);
		foreach($temptags as $tname){
			$temptag = array();
			$temptag['tname'] = $tname;
			$temptag['error'] = !isset($ctags[$tname]) ? 1 : 0;
			$temptag['pid'] = $pid;
			$temptag['level'] = $level;
			$temptag['tmode'] = 'c';
			$result[] = $temptag;
			if(!$temptag['error']){
				$result = array_merge($result,tagmap($tname,'ctag',$level+1));
			}
		}
	}elseif($mode == 'p'){
		preg_match_all("/\{p\\$(.+?)\}/is",$sstr,$matches);
		$temptags = array_unique($matches[1]);
		foreach($temptags as $tname){
			$temptag = array();
			$temptag['tname'] = $tname;
			$temptag['error'] = !isset($ptags[$tname]) ? 1 : 0;
			$temptag['pid'] = $pid;
			$temptag['level'] = $level;
			$temptag['tmode'] = 'p';
			$result[] = $temptag;
			if(!$temptag['error']){
				$result = array_merge($result,tagmap($tname,'ptag',$level+1));
			}
		}
	}elseif($mode == 'tpl'){
		preg_match_all("/\{tpl\\$(.+?)\}/is",$sstr,$matches);
		$temptags = array_unique($matches[1]);
		foreach($temptags as $tname){
			$temptag = array();
			$temptag['tname'] = $tname;
			$temptag['error'] = !isset($rtags[$tname]) ? 1 : 0;
			$temptag['pid'] = $pid;
			$temptag['level'] = $level;
			$temptag['tmode'] = 'tpl';
			$result[] = $temptag;
			if(!$temptag['error']){
				$result = array_merge($result,tagmap($tname,'rtag',$level+1));
			}
		}
	}
	return $result;
}
function toptags($sstr,$mode = 'c'){
	global $ctags;
	$result = $temptags = array();
	if($mode == 'c'){
		preg_match_all("/\{c\\$(.+?)\}/is",$sstr,$matches);
		$temptags = array_unique($matches[1]);
		foreach($temptags as $tname){
			isset($ctags[$tname]) && $result[] = $tname;
		}
	}
	return $result;
}


?>
