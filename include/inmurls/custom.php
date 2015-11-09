<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	trbasic(lang('customphp').'url','inmurlnew[setting][customurl]',empty($inmurl['setting']['customurl']) ? '' : $inmurl['setting']['customurl'],'btext');
}else{
	$inmurlnew['setting']['customurl'] = strip_tags(trim($inmurlnew['setting']['customurl']));
	$inmurlnew['url'] = $inmurlnew['setting']['customurl'].(in_str('?',$inmurlnew['setting']['customurl']) ? '&' : '?').'aid=';
}

?>