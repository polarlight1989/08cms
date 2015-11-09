<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? '10' : $mtag['setting']['limits']);
	$arr = array(
		'archives' => lang('archive'),
		'members' => lang('member'),
		'farchives' => lang('freeinfo'),
		'catalogs' => lang('catalog'),
		'coclass' => lang('coclass'),
		'offers' => lang('offer'),
		'replys' => lang('reply'),
		'comments' => lang('comment'),
		'mcfields' => lang('mcomment'),
		'mrfields' => lang('mreply'),
	);
	echo "<tr class=\"txt\"><td class=\"txt txtright fB borderright\">".lang('vote_type')."</td>\n";
	echo "<td class=\"txtL\">\n";
	echo "<input class=\"radio\" type=\"radio\" name=\"mtagnew[setting][type]\" value=\"\" onclick=\"\$id('vote_type1').style.display = '';\$id('vote_type2').style.display = 'none';\"".(empty($mtag['setting']['type']) ? ' checked' : '').">".lang('freevote')."\n";
	$i = 1;
	foreach($arr as $k => $v){
		echo "<input class=\"radio\" type=\"radio\" name=\"mtagnew[setting][type]\" value=\"$k\" onclick=\"\$id('vote_type1').style.display = 'none';\$id('vote_type2').style.display = '';\"".(@$mtag['setting']['type'] == $k ? ' checked' : '').">$v\n";
		echo $i % 6 ? '' : '<br>';
		$i ++;

	}
	echo "</td></tr>\n";
	echo "<tbody id=\"vote_type1\" style=\"display:".(empty($mtag['setting']['type']) ? '' : 'none')."\">";
	$sourcearr = array('0' => lang('nolimit_coclass')) + vcaidsarr();
	trbasic(lang('vote_coclass_limited'),'mtagnew[setting][vsource]',makeoption($sourcearr,empty($mtag['setting']['vsource']) ? '0' : $mtag['setting']['vsource']),'select');
	trbasic(lang('vote_id_limited'),'mtagnew[setting][vids]',empty($mtag['setting']['vids']) ? '' : $mtag['setting']['vids']);
	echo "</tbody>";
	echo "<tbody id=\"vote_type2\" style=\"display:".(!empty($mtag['setting']['type']) ? '' : 'none')."\">";
	trbasic(lang('soucerid'),'mtagnew[setting][id]',isset($mtag['setting']['id']) ? $mtag['setting']['id'] : '','text');
	trbasic(lang('soucefname'),'mtagnew[setting][fname]',isset($mtag['setting']['fname']) ? $mtag['setting']['fname'] : '','text');
	echo "</tbody>";	
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{
	$mtagnew['setting']['fname'] = trim($mtagnew['setting']['fname']);
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	if(!empty($mtagnew['setting']['type']) && (empty($mtagnew['setting']['id']) || !preg_match("/^[a-zA-Z_\$][a-zA-Z0-9_\[\]]*$/",$mtagnew['setting']['id']) || empty($mtagnew['setting']['fname']) || !preg_match("/^[a-zA-Z_][a-zA-Z0-9_]*$/",$mtagnew['setting']['fname']))){
		if(!submitcheck('bmtagcode')){
			amessage('sourceillegal',M_REFERER); 
		}else $errormsg = lang('sourceillegal');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['vids'] = empty($mtagnew['setting']['vids']) ? '' : trim($mtagnew['setting']['vids']);
	if($mtagnew['setting']['vids']){
		$vids = array_filter(explode(',',$mtagnew['setting']['vids']));
		$mtagnew['setting']['vids'] = empty($vids) ? '' : implode(',',$vids);
	}
}
?>
