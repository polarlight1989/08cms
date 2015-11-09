<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('other') || amessage('no_apermission');
if(!submitcheck('brebuilds')){
	tabheader($sid ? lang('refsubsyca') : lang('refmssyscac'),$actionid.'rebuilds',"?entry=rebuilds$param_suffix",2);
	trbasic(lang('bassyscac'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[based]\" value=\"1\" checked disabled>",'');
	trbasic(lang('catcnocac'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[cnode]\" value=\"1\">",'');
	!$sid && trbasic(lang('allsitecac'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[allsite]\" value=\"1\">",'');
	tabfooter('brebuilds');
	a_guide('rebuildcache');
}else{
	$exceptstr = '';
	if(empty($arcdeal['cnode'])) $exceptstr .= ($exceptstr ? ',' :  '').'cnodes';
	if(!empty($arcdeal['allsite'])) clear_dir(M_ROOT.'./dynamic/cache',false,$exceptstr);
	rebuild_cache(empty($arcdeal['allsite']) ? $sid : '-1',$exceptstr);
	amessage(($sid ? 'subsite' : 'msite').'syscacreffin',M_REFERER);
}



?>