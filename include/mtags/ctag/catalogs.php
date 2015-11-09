<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
empty($mtag['setting']['listby']) && $mtag['setting']['listby'] = 'ca';
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? '10' : $mtag['setting']['limits']);
	tabfooter();

	tabheader(lang('list_item_setting'));
	$nsidsarr = array('0' => lang('current_subsite'),'-1' => lang('msite'),) + sidsarr(1);//为免与现有模板冲突，0为当前子站
	trbasic(lang('subsite_attr'),'mtagnew[setting][nsid]',makeoption($nsidsarr,empty($mtag['setting']['nsid']) ? 0 : $mtag['setting']['nsid']),'select');
	$caco_same_fix = 'caco_same_fix_';
	$caco_diff_fix = 'caco_diff_fix_';
	$cacoarr = array('ca' => lang('catalog'));
	foreach($cotypes as $k => $cotype)$cotype['sortable'] && $cacoarr["co$k"] = $cotype['cname'];
	trbasic(lang('list_item'),'',makeradio('mtagnew[setting][listby]', $cacoarr, $mtag['setting']['listby'],'',"single_list_set(this, '$caco_same_fix', '$caco_diff_fix')"), '');
	$sourcearr = array('0' => lang('all_topic_catas'),'4' => lang('all_1_catas'),'5' => lang('all_2_catas'),'1' => lang('handpoint'),'2' => lang('sonofactive'),'3' => lang('customsql'),);
	sourcemodule(lang('catalog'),
		'mtagnew[setting][casource]',
		$sourcearr,
		empty($mtag['setting']['casource']) ? '0' : $mtag['setting']['casource'],
		'1',
		'mtagnew[setting][caids][]',
		caidsarr($catalogs),
		empty($mtag['setting']['caids']) ? array() : explode(',',$mtag['setting']['caids']),
		'25%',
		$mtag['setting']['listby'] == 'ca',
		$caco_same_fix . 'ca');
	foreach($cotypes as $k => $cotype){
		if($cotype['sortable']) {
			sourcemodule($cotype['cname'],
				"mtagnew[setting][cosource$k]",
				$sourcearr,
				empty($mtag['setting']['cosource'.$k]) ? '0' : $mtag['setting']['cosource'.$k],
				'1',
				"mtagnew[setting][ccids$k][]",
				ccidsarr($k),
				empty($mtag['setting']['ccids'.$k]) ? array() : explode(',',$mtag['setting']['ccids'.$k]),
				'25%',
				$mtag['setting']['listby'] == "co$k",
				$caco_same_fix . "co$k");
		}
	}
	$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=catalogs\" target=\"_blank\">".lang('create_str')."</a>";
	$isfuncstr = "<br><input class=\"checkbox\" type=\"checkbox\" id=\"mtagnew[setting][isfunc]\" name=\"mtagnew[setting][isfunc]\"".(empty($mtag['setting']['isfunc']) ? '' : ' checked').">".lang('fromfunc');
	trbasic(lang('customsql').$createurl.$isfuncstr,'mtagnew[setting][wherestr]',empty($mtag['setting']['wherestr']) ? '' : stripslashes($mtag['setting']['wherestr']),'textarea');
	tabfooter();
	tabheader(lang('catas_attr').'('.lang('nolist_item_available').')');
	$inheritarr = array('0' => lang('norelated'),'active' => lang('active_catalog'),);
	$inheritarr = $inheritarr + caidsarr($catalogs);
	trbasic(lang('catalog'),'mtagnew[setting][cainherit]',makeoption($inheritarr,empty($mtag['setting']['cainherit']) ? '0' : $mtag['setting']['cainherit']),'select','','25%',$mtag['setting']['listby'] != 'ca',$caco_diff_fix . 'ca');
	foreach($cotypes as $k => $cotype){
		if($cotype['sortable']) {
			$inheritarr = array('0' => lang('norelated'),'active' => lang('active_coclass'),);
			$inheritarr = $inheritarr + ccidsarr($k);
			trbasic("$cotype[cname]","mtagnew[setting][coinherit$k]",makeoption($inheritarr,empty($mtag['setting']['coinherit'.$k]) ? '0' : $mtag['setting']['coinherit'.$k]),'select','','25%',$mtag['setting']['listby'] != "co$k",$caco_diff_fix . "co$k");
		}
	}
	tabfooter();
	tabheader(lang('adv_options')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('advancedfilter')\">".lang('view'));
	echo "<tbody id=\"advancedfilter\" style=\"display: none;\">";
	trbasic(lang('startno'),'mtagnew[setting][startno]',empty($mtag['setting']['startno']) ? '' : $mtag['setting']['startno'],'text',lang('agstartno'));
	$urlmodearr = array('0' => lang('default'),'caid' => lang('catalog'));
	foreach($cotypes as $k => $cotype){
		if($cotype['sortable']) $urlmodearr['ccid'.$k] = $cotype['cname'];
	}
	trbasic(lang('urlmode'),'mtagnew[setting][urlmode]',makeoption($urlmodearr,empty($mtag['setting']['urlmode']) ? '0' : $mtag['setting']['urlmode']),'select');
	$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=catalogs\" target=\"_blank\">".lang('create_str')."</a>";
	trbasic(lang('order_str').$createurl,'mtagnew[setting][orderstr]',empty($mtag['setting']['orderstr']) ? '' : stripslashes($mtag['setting']['orderstr']),'textarea');
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	echo "</tbody>";
	tabfooter();
}else{//?????????????????????????过滤非listby的参数
	if(empty($mtagnew['template'])) amessage('tag_data_miss',M_REFERER); 
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['startno'] = trim($mtagnew['setting']['startno']);
	$mtagnew['setting']['wherestr'] = empty($mtagnew['setting']['wherestr']) ? '' : stripslashes(trim($mtagnew['setting']['wherestr']));
	$mtagnew['setting']['isfunc'] = empty($mtagnew['setting']['isfunc']) || empty($mtagnew['setting']['wherestr']) ? 0 : 1;
	$mtagnew['setting']['orderstr'] = empty($mtagnew['setting']['orderstr']) ? '' : trim($mtagnew['setting']['orderstr']);

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
