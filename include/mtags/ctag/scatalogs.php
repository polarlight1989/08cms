<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	$urlmodearr = array('0' => lang('default'),'caid' => lang('catalog'));
	foreach($cotypes as $k => $cotype){
		if($cotype['mainline']) $urlmodearr['ccid'.$k] = $cotype['cname'];
	}
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? '10' : $mtag['setting']['limits']);
	trbasic(lang('list_cols'),'mtagnew[setting][cols]',empty($mtag['setting']['cols']) ? '1' : $mtag['setting']['cols']);
	trbasic(lang('urlmode'),'mtagnew[setting][urlmode]',makeoption($urlmodearr,empty($mtag['setting']['urlmode']) ? '0' : $mtag['setting']['urlmode']),'select');
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();

	if($mtagnew['setting']['nsid'] < 0){
		$catalogs = array();
	}elseif($mtagnew['setting']['nsid'] != $sid){
		load_cache('catalogs',$mtagnew['setting']['nsid']);
	}
	tabheader(lang('list_item_setting'));
	$sourcearr = array('0' => lang('all_topic_catas'),'1' => lang('handpoint'),'2' => lang('sonofactive'),'3' => lang('sameofactive'),);
	sourcemodule(lang('catalog')."&nbsp;&nbsp;&nbsp;<input class=\"radio\" type=\"radio\" name=\"mtagnew[setting][listby]\" value=\"ca\"".(empty($mtag['setting']['listby']) || ($mtag['setting']['listby'] == 'ca') ? " checked" : "").">".lang('list_item'),
				'mtagnew[setting][casource]',
				$sourcearr,
				empty($mtag['setting']['casource']) ? '0' : $mtag['setting']['casource'],
				'1',
				'mtagnew[setting][caids][]',
				caidsarr(),
				empty($mtag['setting']['caids']) ? array() : explode(',',$mtag['setting']['caids'])
				);
	foreach($cotypes as $k => $cotype) {
		if($cotype['sortable']) {
			sourcemodule("$cotype[cname]&nbsp;&nbsp;&nbsp;<input class=\"radio\" type=\"radio\" name=\"mtagnew[setting][listby]\" value=\"co$k\"".(!empty($mtag['setting']['listby']) && ($mtag['setting']['listby'] == "co$k") ? " checked" : "").">".lang('list_item'),
						"mtagnew[setting][cosource$k]",
						$sourcearr,
						empty($mtag['setting']['cosource'.$k]) ? '0' : $mtag['setting']['cosource'.$k],
						'1',
						"mtagnew[setting][ccids$k][]",
						ccidsarr($k),
						empty($mtag['setting']['ccids'.$k]) ? array() : explode(',',$mtag['setting']['ccids'.$k])
						);
		}
	}
	tabfooter();
	tabheader(lang('catas_attr').'('.lang('nolist_item_available').')');
	$inheritarr = array('0' => lang('norelated'),'active' => lang('active_catalog'),);
	$inheritarr = $inheritarr + caidsarr();
	trbasic(lang('catalog'),'mtagnew[setting][cainherit]',makeoption($inheritarr,empty($mtag['setting']['cainherit']) ? '0' : $mtag['setting']['cainherit']),'select');
	foreach($cotypes as $k => $cotype) {
		if($cotype['sortable']) {
			$inheritarr = array('0' => lang('norelated'),'active' => lang('active_coclass'),);
			$inheritarr = $inheritarr + ccidsarr($k);
			trbasic("$cotype[cname]","mtagnew[setting][coinherit$k]",makeoption($inheritarr,empty($mtag['setting']['coinherit'.$k]) ? '0' : $mtag['setting']['coinherit'.$k]),'select');
		}
	}
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			namessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['cols'] = max(1,intval($mtagnew['setting']['cols']));

	//数组参数的处理
	$idvars = array('caids');
	foreach($cotypes as $k => $cotype) $idvars[] = 'ccids'.$k;
	foreach($idvars as $k){
		if(empty($mtagnew['setting'][$k])){
			unset($mtagnew['setting'][$k]);
		}else $mtagnew['setting'][$k] = implode(',',$mtagnew['setting'][$k]);
	}

}
?>
