<?
function tpl_refresh($tplname){
	global $templatedir,$debugtag;
	$tpl_dir = M_ROOT."template/$templatedir/";
	if(file_exists($utags = $tpl_dir."function/utags.fun.php")) include_once $utags;
	mmkdir($tpl_dir.'pcache/');
	if($debugtag || !file_exists($tpl_dir.'pcache/'.$tplname.'.php')){
		$str = load_tpl($tplname);
		$str = preg_replace("/<\\?(?!php\\s|=|\\s)/i", '<?=\'<?\'?>', $str);
		$str = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $str);
		breplace($str,'');
		mp_pre($str);
		nreplace($str,'p');
		nreplace($str,'c');
		nreplace($str,'u');
		quit_avar();
		$str = tpl_basecode($str);
		str2file($str,$tpl_dir.'pcache/'.$tplname.'.php');
	}
}
function set_avar($var=''){
	global $_a_vars,$_a_var;
	if(!$var || empty($_a_vars)) $_a_vars = array();
	$_a_var = $var;
	array_unshift($_a_vars,$_a_var);
}
function quit_avar(){//u标识解析之后
	global $_a_vars,$_a_var;
	array_shift($_a_vars);
	$_a_var = @$_a_vars[0];
}

function mp_pre(&$str){
	$tag = array();
	if(preg_match("/\{p\\$([^\s]+?)\}/is",$str,$matches)){//封装分页标识
		$tag = read_tag('ptag',$matches[1]);
	}elseif(preg_match("/\{p\\$(.+?)\s+(.*?)\{\/p\\$\\1\}/is",$str,$matches)){//非封装分页标识
		$tag = fetch_tag_arr($matches[1],stripslashes($matches[2]));
		$tag['ename'] = $matches[1];
	}
	
	 
	
	
	if($tag && empty($tag['disabled'])){
		$str = str_replace($matches[0],'<?php _mpinfo(array('._tagstr($tag).'));?>'.$matches[0],$str);
		 
		/*
		$str = '<?php _mpinfo(array('._tagstr($tag).'));?>'."\n$str";
		*/
	}
	unset($matches);
}
function nreplace(&$content,$mode='c'){
	$content = preg_replace("/\{".$mode."\\$([^\s]+?)\}/ies","tagval_k('$mode','\\1')",$content);//封装标识
	$content = preg_replace("/\{".$mode."\\$(.+?)\s+(.*?)\{\/".$mode."\\$\\1\}/ies","tagval_n('$mode','\\1','\\2')",$content);//非封装标识
}
function tagval_k($mode,$tname){//封装
	global $debugtag;
	$tag = read_tag($mode.'tag',$tname);
	if(empty($tag) || empty($tag['tclass'])) return $debugtag ? '{'.$mode.' $'.$tname.'}' : '';
	$func = $mode.'tag_val';
	return $func($tname,$tag);
}
function tagval_n($mode,$tname,$tstr){//非封装
	global $debugtag;
	$tag = fetch_tag_arr($tname,stripslashes($tstr));
	if(empty($tag) || empty($tag['tclass'])) return $debugtag ? '{'.$mode.' $'.$tname.$tstr.'{'.$mode.' $'.$tname.'}' : '';
	$func = $mode.'tag_val';
	return $func($tname,$tag);
}
function fetch_tag_arr($tname,&$tstr){
	$arr = array();
	if(preg_match("/^\s*(.+?)\/\]\s*\}/is",$tstr,$matches)){
		if($str = $matches[0]){
			if(preg_match_all("/\[\s*(.+?)\s*\=\s*(.*?)\s*\/\]/is",$str, $matches)){
				foreach($matches[1] as $k => $v) $arr[$v] = $matches[2][$k];
			}
		}
		$tstr = preg_replace("/^\s*(.+?)\/\]\s*\}/is",'',$tstr);
	}
	$arr['template'] = $tstr;
	unset($matches);
	return $arr;
}

function utag_val($tname,&$tag){//处理的时候要注意，u是有图集等列表标识的。
	if(!empty($tag['disabled'])) return '';
	if(in_array($tag['tclass'],array('date','odeal','field',))){
		$ret = '<?php echo _utag_parse(array(';
		$ret .= _tagstr($tag);
		$ret .= '));?>'."\n";
		return $ret;
	}elseif(in_array($tag['tclass'],array('images','files','medias','flashs',))){
		$name_vars = '_'.$tname;
		$val_var = empty($tag['val']) ? 'u' : $tag['val'];
		$t = arr_row_block($tag['template']);
		$ret = $t[1].'<?php $'.$name_vars.'=_utag_parse(array(';
		$ret .= _tagstr($tag);
		$ret .= '));foreach($'.$name_vars.' as $'.$val_var.'){';
		$ret .= '_aenter($'.$val_var.');?>'."\n";//进入了具体的资料之后激活
		breplace($t[2],$val_var);
		nreplace($t[2],'u');
		quit_avar();
		$ret .= $t[2];
		$ret .= '<?php _aquit();} unset($'.$name_vars.',$'.$val_var.');?>'."\n";
		$ret .= $t[3];
		unset($t);
		return $ret;
	}else{
		$val_var = empty($tag['val']) ? 'u' : $tag['val'];
		$ret = '<?php $'.$val_var.'=_utag_parse(array(';
		$ret .= _tagstr($tag);
		$ret .= '));if(!empty($'.$val_var.')){ ?>';
		$str = $tag['template'];
		breplace($str,$val_var);
		nreplace($str,'u');
		quit_avar();
		$ret .= $str;
		unset($str);
		$ret .= '<?php } unset($'.$val_var.');?>';
		return $ret;
	}
}
function js_refresh($tname,&$tag){
	global $templatedir,$debugtag;
	$tpl_dir = M_ROOT."template/$templatedir/";
	$jsname = 'js_'.(empty($tag['pmid']) ? '' : 'p_').$tname;
	if($debugtag || !file_exists($tpl_dir.'pcache/'.$jsname.'.php')){
		!empty($tag['pmid']) && $tag['_pmid'] = $tag['pmid'];
		unset($tag['js'],$tag['pmid']);
		$str = ctag_val($tname,$tag);
		$str = tpl_basecode($str);
		str2file($str,$tpl_dir.'pcache/'.$jsname.'.php');
	}
}
function ctag_val($tname,&$tag){
	if(!empty($tag['disabled'])) return '';
	if(!empty($tag['pmid']) && in_array($tag['tclass'],array('archive','userinfos','farchive','marchive',))){
		global $cms_abs,$sid;
		$jsfile = 'tools/js.php?is_p=1&tname='.$tname.(empty($sid) ? '' : "&sid=$sid");
		$ret = '<?php $js_file=$cms_abs.\''.$jsfile.'\';foreach($_midarr as $k => $v){ $js_file.= \'&data[\'.$k.\']=\'.rawurlencode($v);} ?>';
		$ret .= '<script type="text/javascript">document.write(\'<script type="text/javascript" src="<?=$js_file?>&t=\'+(new Date).getTime()+\'"></\'+\'script>\')</script><?php unset($js_file);?>';
		js_refresh($tname,$tag);
		return $ret;
	}elseif(!empty($tag['js'])){
		global $cms_abs,$sid;
		$jsfile = 'tools/js.php?tname='.$tname.(empty($sid) ? '' : "&sid=$sid");
		$ret = '<?php $js_file=$cms_abs.\''.$jsfile.'\';foreach($_midarr as $k => $v){ $js_file.= \'&data[\'.$k.\']=\'.rawurlencode($v);} ?>';
		$ret .= '<script type="text/javascript">document.write(\'<script type="text/javascript" src="<?=$js_file?>&t=\'+(new Date).getTime()+\'"></\'+\'script>\')</script><?php unset($js_file);?>';
		js_refresh($tname,$tag);
		return $ret;
	}elseif(in_array($tag['tclass'],array('archives','farchives','alarchives','albums','outinfos','catalogs','mccatalogs','vote','votes','mcatalogs','commus',
		'mcommus','relates','members','keywords','channels','mchannels','usergroups','matypes','subsites','marchives','nownav','mnownav'))){
		$name_vars = '_'.$tname;
		$val_var = empty($tag['val']) ? 'v' : $tag['val'];
		$t = arr_row_block($tag['template']);
		$ret = $t[1].'<?php $'.$name_vars.'=_ctag_parse(array(';
		$ret .= _tagstr($tag);
		$ret .= '));foreach($'.$name_vars.' as $'.$val_var.'){';
		$ret .= '_aenter($'.$val_var.');?>'."\n";//进入了具体的资料之后激活
		breplace($t[2],$val_var);
		nreplace($t[2],'c');
		nreplace($t[2],'u');
		quit_avar();
		$ret .= $t[2];
		$ret .= '<?php _aquit();} unset($'.$name_vars.',$'.$val_var.');?>'."\n";
		$ret .= $t[3];
		unset($t);
		return $ret;
	}elseif(in_array($tag['tclass'],array('freeurl',))){
		$ret = '<?php echo _ctag_parse(array(';
		foreach($tag as $k => $v) if(!in_array($k,array('cname','val','template'))) $ret .= '"'.$k.'" => "'.$v.'",';
		$ret .= '));?>'."\n";
		return $ret;
	}else{
		$val_var = empty($tag['val']) ? 'v' : $tag['val'];
		$ret = '<?php $'.$val_var.'=_ctag_parse(array(';
		$ret .= _tagstr($tag);
		$ret .= '));if(!empty($'.$val_var.')){';
		$ret .= '_aenter($'.$val_var.');?>'."\n";
		if(!empty($tag['_pmid']) && in_array($tag['tclass'],array('archive','userinfos','farchive','marchive',))){
			$str = pm_template($tag['template'],$tag['_pmid']);
		}else $str = $tag['template'];
		breplace($str,$val_var);
		nreplace($str,'c');
		nreplace($str,'u');
		quit_avar();
		$ret .= $str;
		unset($str);
		$ret .= '<?php _aquit();} unset($'.$val_var.');?>'."\n";
		return $ret;
	}
}
function pm_template(&$template,$pmid=0){
	if(!$pmid) return;
	$arr = explode('[#pm#]',$template);
	return '<?php if(tpl_permission('.$pmid.')){ ?>'.$arr[0].'<?php }else{ ?>'.(empty($arr[1]) ? 'NoPermission' : $arr[1]).'<?php } ?>';
}
function ptag_val($tname,&$tag){
	if(!empty($tag['disabled'])) return '';
	if(in_array($tag['tclass'],array('normal',))){
		$ret = '<?php echo _ptag_parse(array(';
		$ret .= _tagstr($tag);
		$ret .= '));?>'."\n";
		return $ret;
	}else{
		$name_vars = '_'.$tname;
		$val_var = empty($tag['val']) ? 'v' : $tag['val'];
		$t = arr_row_block($tag['template']);
		$ret = $t[1].'<?php $'.$name_vars.'=_ptag_parse(array(';
		$ret .= _tagstr($tag);
		$ret .= '));foreach($'.$name_vars.' as $'.$val_var.'){';
		$ret .= '_aenter($'.$val_var.');?>'."\n";//进入了具体的资料之后激活
		breplace($t[2],$val_var);
		nreplace($t[2],'c');
		nreplace($t[2],'u');
		quit_avar();
		$ret .= $t[2];
		$ret .= '<?php _aquit();} unset($'.$name_vars.',$'.$val_var.');?>'."\n";
		$ret .= $t[3];
		unset($t);
		return $ret;
	}
}
function _tagstr(&$tag){
	global $_a_var;
	$ret = '';
	foreach($tag as $k => $v){
		if(!in_array($k,array('cname','val','template'))){
			if($k == 'tname' && preg_match("/^[a-zA-Z_][a-zA-Z0-9_]*$/is",$v)){
				$v = $_a_var ? '$'.$_a_var.'['.$v.']' : '$'.$v;
			}
			$ret .= '"'.$k.'" => "'.$v.'",';
		} 
	}
	return $ret;
}
function breplace(&$str,$var='v'){
	if(preg_match_all("/\{(p|c|u)\\$(.+?)(\}|\s+(.*?)\{\/\\1\\$\\2\})/is",$str,$matches)){
		$_reps = $_order = array();
		foreach($matches[0] as $k => $v){
			$_reps[$k] = $v;
			$_order[$k] = $matches[1][$k] == 'p' ? 0 : ($matches[1][$k] == 'c' ? 1 : 2);
		}
		if($_reps){
			array_multisort($_order,SORT_ASC,$_reps);
			foreach($_reps as $k => $v) $str = str_replace($v,'[[['.$k.']]]',$str);
		}
		unset($matches,$_order,$k,$v);
	}
	set_avar($var);
	$str = preg_replace("/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/is",$var ? '{$'.$var.'[\\1]}' : '{$\\1}', $str);
	if(!empty($_reps)){
		foreach($_reps as $k => $v) $str = str_replace('[[['.$k.']]]',$v,$str);
	}
	unset($reps,$k,$v);
}
function arr_row_block($str){
	$narr = array(1 => '',2 => $str,3 => '',);
	if(preg_match_all("/\{(c|u)\\$(.+?)(\}|\s+(.*?)\{\/\\1\\$\\2\})/is",$str,$matches)){
		$_reps = $_order = array();
		foreach($matches[0] as $k => $v){
			$_reps[$k] = $v;
			$_order[$k] = $matches[1][$k] == 'c' ? 0 : 1;
		}
		if($_reps){
			array_multisort($_order,SORT_ASC,$_reps);
			foreach($_reps as $k => $v) $str = str_replace($v,'[[['.$k.']]]',$str);
		}
		unset($matches,$_order,$k,$v);
	}
	if(preg_match("/^(.*?)\[row\](.*)\[\/row\](.*?)$/is",$str,$matches)){
		unset($matches[0]);
		foreach($matches as $x => $y){
			if(!empty($_reps)) foreach($_reps as $k => $v) $y = str_replace('[[['.$k.']]]',$v,$y);
			if($x != 2){
				nreplace($y,'c');
				nreplace($y,'u');
			}
			$narr[$x] = $y;
		}
	}elseif(!empty($_reps)) foreach($_reps as $k => $v) $narr[2] = str_replace('[[['.$k.']]]',$v,$narr[2]);
	unset($matches,$reps,$k,$v);
	return $narr;
}

function tpl_basecode($str){
	$var_regexp = "\{((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)\}";
	$str = preg_replace("/$var_regexp/es", "addquote('<?=\\1?>')", $str);
	$str = preg_replace("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<? echo \\1; ?>','')", $str);
	$str = preg_replace("/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/ies", "stripvtags('\\1<? } elseif(\\2) { ?>\\3','')", $str);
	$str = preg_replace("/([\n\r\t]*)\{else\s+\}([\n\r\t]*)/is", "\\1<? } else { ?>\\2", $str);
	for($i = 0; $i < 3; $i++) {
		$str = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r]*(.+?)[\n\r]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('<? if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\\3<? } } ?>')", $str);
		$str = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*(.+?)[\n\r\t]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('<? if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\\4<? } } ?>')", $str);
		$str = preg_replace("/([\n\r\t]*)\{if\s+(.+?)\}([\n\r]*)(.+?)([\n\r]*)\{\/if\}([\n\r\t]*)/ies", "stripvtags('\\1<? if(\\2) { ?>\\3','\\4\\5<? } ?>\\6')", $str);
	}
	$str = preg_replace("/\{\?(.*?)\?\}/is", "<?\\1?>", $str);
	$str = preg_replace("/\{\\\$ (.*?)\}/is", "{\$\\1}", $str);
	$str = preg_replace("/<\?=\\\$([a-zA-Z_0-9]+\[')*else('\])*\?>/is", '<? }else{ ?>', $str);
	return $str;
}

function addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}
function stripvtags($expr, $statement) {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr.$statement;
}

?>
