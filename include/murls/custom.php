<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	trbasic(lang('customphp').'url','murlnew[setting][customurl]',empty($murl['setting']['customurl']) ? '' : $murl['setting']['customurl'],'btext');
}else{
	$murlnew['setting']['customurl'] = strip_tags(trim($murlnew['setting']['customurl']));
	$murlnew['url'] = $murlnew['setting']['customurl'];
}
?>