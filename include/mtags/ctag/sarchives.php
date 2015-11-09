<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? 10 : $mtag['setting']['limits']);
	trbasic(lang('list_cols'),'mtagnew[setting][cols]',empty($mtag['setting']['cols']) ? '1' : $mtag['setting']['cols']);
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();

	if($mtagnew['setting']['nsid'] < 0){
		load_cache('acatalogs');
		$catalogs = $acatalogs;
	}elseif($mtagnew['setting']['nsid'] != $sid){
		load_cache('catalogs',$mtagnew['setting']['nsid']);
	}
	tabheader(lang('filter0_set'));
	$sourcearr = array('0' => lang('nolimitcatas'),'1' => lang('handpoint'),'2' => lang('activecatas'),'3' => lang('nearofactive'),);
	sourcemodule(lang('caid_attr')."&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"mtagnew[setting][caidson]\" value=\"1\"".(empty($mtag['setting']['caidson']) ? "" : " checked").">".lang('include_son'),
				'mtagnew[setting][casource]',
				$sourcearr,
				empty($mtag['setting']['casource']) ? '0' : $mtag['setting']['casource'],
				'1',
				'mtagnew[setting][caids][]',
				caidsarr(),
				empty($mtag['setting']['caids']) ? array() : explode(',',$mtag['setting']['caids'])
				);

	foreach($cotypes as $k => $cotype) {
		sourcemodule(lang('colasslimit')."-$cotype[cname]"."&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"mtagnew[setting][ccidson$k]\" value=\"1\"".(empty($mtag['setting']['ccidson'.$k]) ? "" : " checked").">".lang('include_son'),
					"mtagnew[setting][cosource$k]",
					$sourcearr,
					empty($mtag['setting']['cosource'.$k]) ? '0' : $mtag['setting']['cosource'.$k],
					'1',
					"mtagnew[setting][ccids$k][]",
					ccidsarr($k),
					empty($mtag['setting']['ccids'.$k]) ? array() : explode(',',$mtag['setting']['ccids'.$k])
					);
	}
	$atsourcearr = array('0' => lang('noalbum_archive'),'all' => lang('all_archive'));
	foreach($altypes as $atid => $altype){
		$atsourcearr[$atid] = $altype['cname'];
	}
	trbasic(lang('atid_attr'),'mtagnew[setting][atsource]',makeoption($atsourcearr,empty($mtag['setting']['atsource']) ? '0' : $mtag['setting']['atsource']),'select');
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
	trbasic(lang('view_ch_field'),'mtagnew[setting][detail]',empty($mtag['setting']['detail']) ? 0 : $mtag['setting']['detail'],'radio',lang('agtagdetail_yes'));
	trbasic(lang('view_plus_stat'),'mtagnew[setting][rec]',empty($mtag['setting']['rec']) ? 0 : $mtag['setting']['rec'],'radio',lang('agtagrec_yes'));
	tabfooter();

	tabheader(lang('member_related')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('mcrelated')\">".lang('view'));
	$sourcearr = array('0' => lang('nolimitcatas'),'1' => lang('activecatas'),);
	echo "<tbody id=\"mcrelated\" style=\"display: none;\">";
	trbasic(lang('individual_list'),'mtagnew[setting][space]',empty($mtag['setting']['space']) ? 0 : $mtag['setting']['space'],'radio');
	trbasic(lang('active_uclass'),'mtagnew[setting][ucsource]',makeoption($sourcearr,empty($mtag['setting']['ucsource']) ? 0 : $mtag['setting']['ucsource']),'select');
	echo "</tbody>";
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
	$orderbyarr1 = array(
		'' => lang('nosetting'),
		'mclicks_desc' => lang('month_clicks_desc1'),
		'wclicks_desc' => lang('week_clicks_desc1'),
		'mcomments_desc' => lang('month_comments_desc1'),
		'wcomments_desc' => lang('week_comments_desc1'),
		'mfavorites_desc' => lang('m_fav_pics_desc1'),
		'wfavorites_desc' => lang('w_fav_pics_desc1'),
		'mpraises_desc' => lang('m_praise_pics_desc1'),
		'wpraises_desc' => lang('w_praise_pics_desc1'),
		'mdebases_desc' => lang('m_debase_pics_desc1'),
		'wdebases_desc' => lang('w_debase_pics_desc1'),
		'morders_desc' => lang('m_orders_amount_desc1'),
		'worders_desc' => lang('w_orders_amount_desc1'),
		'mdowns_desc' => lang('m_download_pics_desc1'),
		'wdowns_desc' => lang('w_download_pics_desc1'),
		'mplays_desc' => lang('m_play_pics_desc1'),
		'wplays_desc' => lang('w_play_pics_desc1'),
	);
	tabheader(lang('list_order')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('ordersetting')\">".lang('view'));
	echo "<tbody id=\"ordersetting\" style=\"display: none;\">";
	trbasic(lang('first_order'),'mtagnew[setting][orderby]',makeoption($orderbyarr,empty($mtag['setting']['orderby']) ? '' : $mtag['setting']['orderby']),'select');
	trbasic(lang('second0_order'),'mtagnew[setting][orderby1]',makeoption($orderbyarr1,empty($mtag['setting']['orderby1']) ? '' : $mtag['setting']['orderby1']),'select');
	$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=archives\" target=\"_blank\">".lang('create_str')."</a>";
	trbasic(lang('order_str').$createurl,'mtagnew[setting][orderstr]',empty($mtag['setting']['orderstr']) ? '' : stripslashes($mtag['setting']['orderstr']),'textarea');
	trbasic(lang('startno'),'mtagnew[setting][startno]',empty($mtag['setting']['startno']) ? '' : $mtag['setting']['startno'],'text',lang('agstartno'));
	echo "</tbody>";
	tabfooter();

	tabheader(lang('more_filter0_order_set')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('morefilter')\">".lang('view'));
	echo "<tbody id=\"morefilter\" style=\"display: none;\">";
	trbasic(lang('clicks_gt'),'mtagnew[setting][clicks]',!isset($mtag['setting']['clicks']) ? '' : $mtag['setting']['clicks']);
	trbasic(lang('comments_gt'),'mtagnew[setting][comments]',!isset($mtag['setting']['comments']) ? '' : $mtag['setting']['comments']);
	trbasic(lang('indays'),'mtagnew[setting][indays]',!isset($mtag['setting']['indays']) ? '' : $mtag['setting']['indays']);
	trbasic(lang('outdays'),'mtagnew[setting][outdays]',!isset($mtag['setting']['outdays']) ? '' : $mtag['setting']['outdays']);
	trbasic(lang('favorite_pics_gt'),'mtagnew[setting][favorites]',!isset($mtag['setting']['favorites']) ? '' : $mtag['setting']['favorites']);
	trbasic(lang('praise_pics_gt'),'mtagnew[setting][praises]',!isset($mtag['setting']['praises']) ? '' : $mtag['setting']['praises']);
	trbasic(lang('debase_pics_gt'),'mtagnew[setting][debases]',!isset($mtag['setting']['debases']) ? '' : $mtag['setting']['debases']);
	trbasic(lang('goods_orders_amount_gt'),'mtagnew[setting][orders]',!isset($mtag['setting']['orders']) ? '' : $mtag['setting']['orders']);
	trbasic(lang('goods_price_le'),'mtagnew[setting][inprice]',empty($mtag['setting']['inprice']) ? '' : $mtag['setting']['inprice']);
	trbasic(lang('goods_price_gt'),'mtagnew[setting][outprice]',empty($mtag['setting']['outprice']) ? '' : $mtag['setting']['outprice']);
	trbasic(lang('answer0_amount_gt'),'mtagnew[setting][answers]',!isset($mtag['setting']['answers']) ? '' : $mtag['setting']['answers']);
	trbasic(lang('adopt_answer0_amount_gt'),'mtagnew[setting][adopts]',!isset($mtag['setting']['adopts']) ? '' : $mtag['setting']['adopts']);
	trbasic(lang('answer_reward_currency_le'),'mtagnew[setting][incurrency]',empty($mtag['setting']['incurrency']) ? '' : $mtag['setting']['incurrency']);
	trbasic(lang('answer_reward_currency_gt'),'mtagnew[setting][outcurrency]',empty($mtag['setting']['outcurrency']) ? '' : $mtag['setting']['outcurrency']);
	$closedarr = array('-1' => lang('nolimit'),'0' => lang('noclose'),'1' => lang('closed'));
	trbasic(lang('is_answer_close'),'',makeradio('mtagnew[setting][closed]',$closedarr,!isset($mtag['setting']['closed']) ? '-1' : $mtag['setting']['closed']),'');
	$aboverarr = array('-1' => lang('nolimit'),'0' => lang('noabover'),'1' => lang('abover'));
	trbasic(lang('album_weather_abover'),'',makeradio('mtagnew[setting][abover]',$aboverarr,!isset($mtag['setting']['abover']) ? '-1' : $mtag['setting']['abover']),'');
	$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=sarchives\" target=\"_blank\">".lang('create_str')."</a>";
	trbasic(lang('filter_sql_str').$createurl,'mtagnew[setting][wherestr]',empty($mtag['setting']['wherestr']) ? '' : stripslashes($mtag['setting']['wherestr']),'textarea');
	echo "</tbody>";
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			namessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['cols'] = max(1,intval($mtagnew['setting']['cols']));
	$mtagnew['setting']['startno'] = max(0,intval($mtagnew['setting']['startno']));
	$mtagnew['setting']['ucsource'] = empty($mtagnew['setting']['space']) ? 0 : $mtagnew['setting']['ucsource'];
	$mtagnew['setting']['orderstr'] = empty($mtagnew['setting']['orderstr']) ? '' : trim($mtagnew['setting']['orderstr']);
	$mtagnew['setting']['wherestr'] = empty($mtagnew['setting']['wherestr']) ? '' : trim($mtagnew['setting']['wherestr']);

	//数组参数的处理
	$idvars = array('caids','chids');
	foreach($cotypes as $k => $cotype) $idvars[] = 'ccids'.$k;
	foreach($idvars as $k){
		if(empty($mtagnew['setting'][$k])){
			unset($mtagnew['setting'][$k]);
		}else $mtagnew['setting'][$k] = implode(',',$mtagnew['setting'][$k]);
	}

	foreach(array('clicks','comments','indays','outdays','favorites','praises','debases','orders','inprice','outprice','answers','adopts','incurrency','outcurrency',) as $k){
		$mtagnew['setting'][$k] = trim($mtagnew['setting'][$k]);
		if($mtagnew['setting'][$k] == ''){
			unset($mtagnew['setting'][$k]);
		}else{
			$mtagnew['setting'][$k] = max(0,intval($mtagnew['setting'][$k]));
		}
	}
}
?>
