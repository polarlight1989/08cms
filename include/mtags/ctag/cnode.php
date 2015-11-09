<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	$sourcearr = array('0' => lang('norelated'),'active' => lang('activecatas'),);
	$sourcearr = $sourcearr + caidsarr($catalogs);
	trbasic(lang('catalog')."&nbsp;&nbsp;&nbsp;<input class=\"radio\" type=\"radio\" name=\"mtagnew[setting][listby]\" value=\"ca\"".(!empty($mtag['setting']['listby']) && ($mtag['setting']['listby'] == "ca") ? " checked" : "").">".lang('list_item'),
	'',"<select onchange=\"setIdWithS(this)\" id=\"mselect_mtagnew[setting][casource]\" style=\"vertical-align: middle;\">" . makeoption($sourcearr,@$mtag['setting']['casource']) . "</select><input type=\"text\" value=\"".@$mtag['setting']['casource']."\" onfocus=\"setIdWithI(this)\" name=\"mtagnew[setting][casource]\" id=\"mtagnew[setting][casource]\"/>",'');
	foreach($cotypes as $k => $cotype) {
		if($cotype['sortable']){
			$sourcearr = array(
				'0' => lang('norelated'),
				'active' => lang('activecatas'),
			);
			$sourcearr = $sourcearr + ccidsarr($k);
			isset($mtag['setting']['cosource'.$k]) || $mtag['setting']['cosource'.$k] = '0';
			trbasic("$cotype[cname]&nbsp;&nbsp;&nbsp;<input class=\"radio\" type=\"radio\" name=\"mtagnew[setting][listby]\" value=\"co$k\"".(!empty($mtag['setting']['listby']) && ($mtag['setting']['listby'] == "co$k") ? " checked" : "").">".lang('list_item'),
			'',"<select onchange=\"setIdWithS(this)\" id=\"mselect_mtagnew[setting][cosource$k]\" style=\"vertical-align: middle;\">" . makeoption($sourcearr,@$mtag['setting']['cosource'.$k]) . "</select><input type=\"text\" value=\"".@$mtag['setting']['cosource'.$k]."\" onfocus=\"setIdWithI(this)\" name=\"mtagnew[setting][cosource$k]\" id=\"mtagnew[setting][cosource$k]\"/>",'');
		}
	}

	$nsidsarr = array('0' => lang('current_subsite'),'-1' => lang('msite'),) + sidsarr(1);//为免与现有模板冲突，0为当前子站
	trbasic(lang('subsite_attr'),'mtagnew[setting][nsid]',makeoption($nsidsarr,empty($mtag['setting']['nsid']) ? 0 : $mtag['setting']['nsid']),'select');
	$urlmodearr = array('0' => lang('default'),'caid' => lang('catalog'));
	foreach($cotypes as $k => $cotype){
		$cotype['sortable'] && $urlmodearr['ccid'.$k] = $cotype['cname'];
	}
	trbasic(lang('urlmode'),'mtagnew[setting][urlmode]',makeoption($urlmodearr,isset($mtag['setting']['urlmode']) ? $mtag['setting']['urlmode'] : '0'),'select');
	$levelarr = array('0' => lang('not_trace'),'1' => lang('topic'),'2' => lang('level1'),'3' => lang('level2'),);
	trbasic(lang('list_upcata'),'',makeradio('mtagnew[setting][level]',$levelarr,isset($mtag['setting']['level']) ? $mtag['setting']['level'] : '0'),'');
	tabfooter();
	tabheader(lang('adv_options')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('advancedfilter')\">".lang('view'));
	echo "<tbody id=\"advancedfilter\" style=\"display: none;\">";
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	echo "</tbody>";
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	if($mtagnew['setting']['listby'] == 'ca' && empty($mtagnew['setting']['casource'])){
		$mtagnew['setting']['casource'] = 'active';
	}elseif(preg_match("/^co(\d+)/is",$mtagnew['setting']['listby'],$matches)){
		if(empty($mtagnew['setting']['cosource'.$matches[1]])) $mtagnew['setting']['cosource'.$matches[1]] = 'active';
	}
}
?>
