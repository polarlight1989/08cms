<?php
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	trbasic(lang('point_isolute_page0_id'),'mtagnew[setting][fid]',empty($mtag['setting']['fid']) ? '' : $mtag['setting']['fid']);
	tabfooter();
}else{
	$mtagnew['setting']['fid'] = max(0,intval($mtagnew['setting']['fid']));
	if(empty($mtagnew['setting']['fid'])){
		if(!submitcheck('bmtagcode')){
			amessage('point_isolute_page_id',M_REFERER); 
		}else $errormsg = lang('page0id');//生成代码出错的提示信息
	}
}
?>
