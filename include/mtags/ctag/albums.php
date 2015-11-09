<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? 10 : $mtag['setting']['limits']);
	trbasic(lang('direct_aid'),'mtagnew[setting][aid]',empty($mtag['setting']['aid']) ? '' : $mtag['setting']['aid']);
	tabfooter();
	
	tabheader(lang('filter0_set'));
	$nsidsarr = array('0' => lang('current_subsite'),'-2' => lang('nolimitsubsite'),'-1' => lang('msite'),) + sidsarr(1);//为免与现有模板冲突，0为当前子站
	trbasic(lang('subsite_attr'),'mtagnew[setting][nsid]',makeoption($nsidsarr,empty($mtag['setting']['nsid']) ? 0 : $mtag['setting']['nsid']),'select');
	$sourcearr = array('0' => lang('nolimitcatas'),'1' => lang('handpoint'),'2' => lang('activecatas'),'3' => lang('nearofactive'),);
	sourcemodule(lang('catalog_attr')."&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"mtagnew[setting][caidson]\" value=\"1\"".(empty($mtag['setting']['caidson']) ? "" : " checked").">".lang('include_son'),
				'mtagnew[setting][casource]',
				$sourcearr,
				empty($mtag['setting']['casource']) ? '0' : $mtag['setting']['casource'],
				'1',
				'mtagnew[setting][caids][]',
				caidsarr($catalogs),
				empty($mtag['setting']['caids']) ? array() : explode(',',$mtag['setting']['caids'])
				);

	foreach($cotypes as $k => $cotype) {
		sourcemodule("$cotype[cname]".lang('attr')."&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"mtagnew[setting][ccidson$k]\" value=\"1\"".(empty($mtag['setting']['ccidson'.$k]) ? "" : " checked").">".lang('include_son'),
					"mtagnew[setting][cosource$k]",
					$sourcearr,
					empty($mtag['setting']['cosource'.$k]) ? '0' : $mtag['setting']['cosource'.$k],
					'1',
					"mtagnew[setting][ccids$k][]",
					ccidsarr($k),
					empty($mtag['setting']['ccids'.$k]) ? array() : explode(',',$mtag['setting']['ccids'.$k])
					);
	}
	$chsourcearr = array('0' => lang('nolimitchannel'),'1' => lang('active_channel'),'2' => lang('handpoint'),);
	sourcemodule(lang('chid_attr'),
				'mtagnew[setting][chsource]',
				$chsourcearr,
				empty($mtag['setting']['chsource']) ? '' : $mtag['setting']['chsource'],
				'2',
				'mtagnew[setting][chids][]',
				chidsarr(1),
				!empty($mtag['setting']['chids']) ? explode(',',$mtag['setting']['chids']) : array()
				);
	tabfooter();

	$orderbyarr = array(
		'' => lang('nosetting'),
		'createdate_desc' => lang('createdate_desc'),
		'createdate_asc' => lang('createdate_asc'),
		'clicks_desc' => lang('clicks_desc1'),
		'comments_desc' => lang('comments_desc1'),
		'scores_desc' => lang('average_score_desc1'),
		'favorites_desc' => lang('favorite_pics_desc1'),
		'praises_desc' => lang('praise_pics_desc1'),
		'debases_desc' => lang('debase_pics_desc1'),
		'orders_desc' => lang('orders_amount_desc1'),
		'downs_desc' => lang('download_pics_desc1'),
		'plays_desc' => lang('play_pics_desc1'),
		'currency_desc' => lang('answer_reward_desc1'),
	);
	tabheader(lang('list_order')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('ordersetting')\">".lang('viewdetail'));
	trbasic(lang('first_order'),'mtagnew[setting][orderby]',makeoption($orderbyarr,empty($mtag['setting']['orderby']) ? '' : $mtag['setting']['orderby']),'select');
	echo "<tbody id=\"ordersetting\" style=\"display: none;\">";
	$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=albums\" target=\"_blank\">".lang('create_str')."</a>";
	trbasic(lang('order_str').$createurl,'mtagnew[setting][orderstr]',empty($mtag['setting']['orderstr']) ? '' : stripslashes($mtag['setting']['orderstr']),'textarea');
	echo "</tbody>";
	trbasic(lang('startno'),'mtagnew[setting][startno]',empty($mtag['setting']['startno']) ? '' : $mtag['setting']['startno'],'text',lang('agstartno'));
	tabfooter();

	tabheader(lang('adv_options')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('advancedfilter')\">".lang('view'));
	echo "<tbody id=\"advancedfilter\" style=\"display: none;\">";
	trbasic(lang('only_valid_period'),'mtagnew[setting][validperiod]',empty($mtag['setting']['validperiod']) ? 0 : $mtag['setting']['validperiod'],'radio');
	trbasic(lang('view_ch_field'),'mtagnew[setting][detail]',empty($mtag['setting']['detail']) ? 0 : $mtag['setting']['detail'],'radio',lang('agtagdetail_yes'));
	trbasic(lang('nocp'),'mtagnew[setting][nocp]',empty($mtag['setting']['nocp']) ? 0 : $mtag['setting']['nocp'],'radio',lang('agnocp'));
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	$aboverarr = array('-1' => lang('nolimit'),'0' => lang('noabover'),'1' => lang('abover'));
	trbasic(lang('album_weather_abover'),'',makeradio('mtagnew[setting][abover]',$aboverarr,!isset($mtag['setting']['abover']) ? '-1' : $mtag['setting']['abover']),'');
	$isfuncstr = "<br><input class=\"checkbox\" type=\"checkbox\" id=\"mtagnew[setting][isfunc]\" name=\"mtagnew[setting][isfunc]\"".(empty($mtag['setting']['isfunc']) ? '' : ' checked').">".lang('fromfunc');
	trbasic(lang('filter_sql_str').$createurl.$isfuncstr,'mtagnew[setting][wherestr]',empty($mtag['setting']['wherestr']) ? '' : $mtag['setting']['wherestr'],'textarea');

	echo "</tbody>";
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['startno'] = trim($mtagnew['setting']['startno']);
	$mtagnew['setting']['orderstr'] = empty($mtagnew['setting']['orderstr']) ? '' : trim($mtagnew['setting']['orderstr']);
	$mtagnew['setting']['wherestr'] = empty($mtagnew['setting']['wherestr']) ? '' : stripslashes(trim($mtagnew['setting']['wherestr']));
	$mtagnew['setting']['isfunc'] = empty($mtagnew['setting']['isfunc']) || empty($mtagnew['setting']['wherestr']) ? 0 : 1;

	$idvars = array('caids','chids');//数组参数的处理
	foreach($cotypes as $k => $cotype) $idvars[] = 'ccids'.$k;
	foreach($idvars as $k){
		if(empty($mtagnew['setting'][$k])){
			unset($mtagnew['setting'][$k]);
		}else $mtagnew['setting'][$k] = implode(',',$mtagnew['setting'][$k]);
	}
}
?>
