<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	trbasic(lang('customphp').'url','aurlnew[setting][customurl]',empty($aurl['setting']['customurl']) ? '' : $aurl['setting']['customurl'],'btext');
}else{
	$aurlnew['setting']['customurl'] = strip_tags(trim($aurlnew['setting']['customurl']));
	$aurlnew['url'] = $aurlnew['setting']['customurl'];
}
?>