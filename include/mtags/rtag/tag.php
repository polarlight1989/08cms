<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
$true_tpldir = M_ROOT."./template/$templatedir/";
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail')){
	$template = load_tpl(@$mtag['template'],0);
	trbasic(lang('temfilecna'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template']);
	templatebox(lang('page_template'),'templatenew',$template,30,110);
	tabfooter();
}else{ 
	$mtagnew['template'] = trim($mtagnew['template']);
	if(empty($mtagnew['template'])) amessage('tag_data_miss',M_REFERER);

	if(preg_match("/[^a-z_A-Z0-9\.]+/",$mtagnew['template'])) amessage('tpl_file_name_illegal',M_REFERER);
	if(@!str2file(stripslashes($templatenew),$true_tpldir.$mtagnew['template'])) amessage('tpl_save_failed',M_REFERER);
}

?>
