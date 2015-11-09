<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	trbasic(lang('customphp').'url','inurlnew[setting][customurl]',empty($inurl['setting']['customurl']) ? '' : $inurl['setting']['customurl'],'btext');
}else{
	$inurlnew['setting']['customurl'] = strip_tags(trim($inurlnew['setting']['customurl']));
	$inurlnew['url'] = $inurlnew['setting']['customurl'].(in_str('?',$inurlnew['setting']['customurl']) ? '&' : '?').'aid=';
}
?>