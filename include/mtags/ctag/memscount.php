<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	tabfooter();
	tabheader(lang('filter0_set'));
	foreach($grouptypes as $gtid => $grouptype){
		$ugidsarr = array('0' => lang('nolimitusergroup')) + ugidsarr($grouptype['gtid']);
		trbasic("$grouptype[cname]".lang('filter0'),'mtagnew[setting][ugid'.$gtid.']',makeoption($ugidsarr,empty($mtag['setting']['ugid'.$gtid]) ? 0 : $mtag['setting']['ugid'.$gtid]),'select');
	}
	$chsourcearr = array('0' => lang('nolimitchannel'),'1' => lang('active_channel'),'2' => lang('handpoint'),);
	sourcemodule(lang('member_channel_limited'),
				'mtagnew[setting][chsource]',
				$chsourcearr,
				empty($mtag['setting']['chsource']) ? '' : $mtag['setting']['chsource'],
				'2',
				'mtagnew[setting][chids][]',
				mchidsarr(),
				!empty($mtag['setting']['chids']) ? explode(',',$mtag['setting']['chids']) : array()
				);
	tabfooter();

	tabheader(lang('adv_options')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('advancedfilter')\">".lang('view'));
	echo "<tbody id=\"advancedfilter\" style=\"display: none;\">";
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	trbasic(lang('clicks_gt'),'mtagnew[setting][clicks]',!isset($mtag['setting']['clicks']) ? '' : $mtag['setting']['clicks']);
	trbasic(lang('online_time'),'mtagnew[setting][onlinetime]',!isset($mtag['setting']['onlinetime']) ? '' : $mtag['setting']['onlinetime']);
	trbasic(lang('msclicks1'),'mtagnew[setting][msclicks]',!isset($mtag['setting']['msclicks']) ? '' : $mtag['setting']['msclicks']);
	trbasic(lang('issue_archive_amount'),'mtagnew[setting][checks]',!isset($mtag['setting']['checks']) ? '' : $mtag['setting']['checks']);
	trbasic(lang('comments_gt'),'mtagnew[setting][comments]',!isset($mtag['setting']['comments']) ? '' : $mtag['setting']['comments']);
	trbasic(lang('purchase_goods_amount'),'mtagnew[setting][purchases]',!isset($mtag['setting']['purchases']) ? '' : $mtag['setting']['purchases']);
	trbasic(lang('answer_amount'),'mtagnew[setting][answers]',!isset($mtag['setting']['answers']) ? '' : $mtag['setting']['answers']);
	trbasic(lang('answer_credit'),'mtagnew[setting][credits]',!isset($mtag['setting']['credits']) ? '' : $mtag['setting']['credits']);
	trbasic(lang('indays'),'mtagnew[setting][indays]',!isset($mtag['setting']['indays']) ? '' : $mtag['setting']['indays']);
	trbasic(lang('outdays'),'mtagnew[setting][outdays]',!isset($mtag['setting']['outdays']) ? '' : $mtag['setting']['outdays']);
	$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=members\" target=\"_blank\">".lang('create_str')."</a>";
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
	$mtagnew['setting']['wherestr'] = empty($mtagnew['setting']['wherestr']) ? '' : stripslashes(trim($mtagnew['setting']['wherestr']));
	$mtagnew['setting']['isfunc'] = empty($mtagnew['setting']['isfunc']) || empty($mtagnew['setting']['wherestr']) ? 0 : 1;

	//数组参数的处理
	$idvars = array('chids');
	foreach($idvars as $k){
		if(empty($mtagnew['setting'][$k])){
			unset($mtagnew['setting'][$k]);
		}else $mtagnew['setting'][$k] = implode(',',$mtagnew['setting'][$k]);
	}

	foreach(array('clicks','comments','indays','outdays','onlinetime','msclicks','checks','purchases','answers','credits',) as $k){
		$mtagnew['setting'][$k] = trim($mtagnew['setting'][$k]);
		if($mtagnew['setting'][$k] == ''){
			unset($mtagnew['setting'][$k]);
		}else{
			$mtagnew['setting'][$k] = max(0,intval($mtagnew['setting'][$k]));
		}
	}
}
?>
