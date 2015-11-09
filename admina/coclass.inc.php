<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cotype') || amessage('no_apermission');
load_cache('cotypes');
if(!$coid || empty($cotypes[$coid])) amessage('choosecotypem');
load_cache('channels,grouptypes,permissions,vcps,rprojects,ccfields,acatalogs');
$catalogs = &$acatalogs;
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.fun.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/cnode.fun.php";
$cotype = $cotypes[$coid];
$coclasses = read_cache('coclasses',$coid);
$cotypename = $cotype['cname'];
if($action == 'coclassadd')  {
	if(!submitcheck('bcoclassadd')) {
		$pid = empty($pid) ? 0 : $pid;
		if($pid) $pmsg = read_cache('coclass',$coid,$pid);
		$submitstr = '';
		tabheader(lang('add')."&nbsp;[$cotypename]&nbsp;".lang('coclass')."-".lang('base_setting'),'coclassadd','?entry=coclass&action=coclassadd&coid='.$coid,2,1,1);
		trbasic(lang('cocname'),'coclassnew[title]');
		$submitstr .= makesubmitstr('coclassnew[title]',1,0,0,30);
		trbasic(lang('coclass_ename'),'', '<input type="text" value="" name="coclassnew[dirname]" id="coclassnew[dirname]" size="25">&nbsp;&nbsp;<input type="button" value="'.lang('checksubject').'" onclick="check_repeat(this,\'coclassnew[dirname]\',\'dirname\');">', '');
		$submitstr .= makesubmitstr('coclassnew[dirname]',1,'tagtype',0,30);
		trbasic(lang('parent_coclass'),'coclassnew[pid]',makeoption(array('0' => lang('topiccoclass')) + ccidsarr($coid), $pid),'select');
		trbasic(lang('isframe_coclass_i'),'coclassnew[isframe]','','radio');
		if(empty($cotype['self_reg'])){
			trbasic(lang('allow_channel_archive'),'',makecheckbox('coclassnew[chids][]',chidsarr(1),!empty($pmsg['chids']) ? explode(',',$pmsg['chids']) : array(),5),'');
			if($cotype['permission']){
				trbasic(lang('add_pmid'),'coclassnew[apmid]',makeoption(pmidsarr('aadd'),@$pmsg['apmid']),'select');
				trbasic(lang('read_pmid'),'coclassnew[rpmid]',makeoption(pmidsarr('aread'),@$pmsg['rpmid']),'select');
				trbasic(lang('cread_pmid'),'coclassnew[crpmid]',makeoption(pmidsarr('cread'),@$pmsg['crpmid']),'select');
				trbasic(lang('down_pmid'),'coclassnew[dpmid]',makeoption(pmidsarr('down'),@$pmsg['dpmid']),'select');
			}
			$cotype['awardcp'] && trbasic(lang('issue_arc_currency'),'coclassnew[awardcp]',makeoption(array('' => lang('noaward')) + $vcps['award'],@$pmsg['awardcp']),'select');
			$cotype['taxcp'] && trbasic(lang('arc_deduct_currency'),'coclassnew[taxcp]',makeoption(array('' => lang('freesale')) + $vcps['tax'],@$pmsg['taxcp']),'select');
			$cotype['ftaxcp'] && trbasic(lang('att_deduct_currency'),'coclassnew[ftaxcp]',makeoption(array('' => lang('freesale')) + $vcps['ftax'],@$pmsg['ftaxcp']),'select');
			$cotype['sale'] && trbasic(lang('allow_sale_arc'),'coclassnew[allowsale]',@$pmsg['allowsale'],'radio');
			$cotype['fsale'] && trbasic(lang('allow_sale_att'),'coclassnew[allowfsale]',@$pmsg['allowfsale'],'radio');
		}
		tabfooter();
		if(!empty($cotype['self_reg'])){
			tabheader(lang('add')."&nbsp;[$cotypename]&nbsp;".lang('coclass')."-".lang('arc_acondition_set')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('morefilter')\">".lang('viewdetail'));
			trrange(lang('add_date'),array('coclassnew[conditions][indays]','','','&nbsp; '.lang('day_before').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][outdays]','','','&nbsp; '.lang('day_in')));
			trrange(lang('add_date'),array('coclassnew[conditions][createdatefrom]','','','&nbsp; '.lang('start').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][createdateto]','','','&nbsp; '.lang('end')),'calendar');
			trrange(lang('clicks'),array('coclassnew[conditions][clicksfrom]','','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][clicksto]','','','&nbsp; '.lang('max')));
			trrange(lang('comments'),array('coclassnew[conditions][commentsfrom]','','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][commentsto]','','','&nbsp; '.lang('max')));
			trrange(lang('praise_pics'),array('coclassnew[conditions][praisesfrom]','','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][praisesto]','','','&nbsp; '.lang('max')));
			trrange(lang('debase_pics'),array('coclassnew[conditions][debasesfrom]','','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][debasesto]','','','&nbsp; '.lang('max')));
			echo "<tbody id=\"morefilter\" style=\"display: none;\">";
			trrange(lang('favorite_pics'),array('coclassnew[conditions][favoritesfrom]','','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][favoritesto]','','','&nbsp; '.lang('max')));
			trrange(lang('goods_orders_amount'),array('coclassnew[conditions][ordersfrom]','','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][ordersto]','','','&nbsp; '.lang('max')));
			trrange(lang('goods_price'),array('coclassnew[conditions][pricefrom]','','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][priceto]','','','&nbsp; '.lang('max')));
			trrange(lang('answer_amount'),array('coclassnew[conditions][answersfrom]','','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][answersto]','','','&nbsp; '.lang('max')));
			trrange(lang('answer_reward_currency'),array('coclassnew[conditions][currencyfrom]','','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][currencyto]','','','&nbsp; '.lang('max')));
			$closedarr = array('-1' => lang('nolimit'),'0' => lang('noclose'),'1' => lang('closed'));
			trbasic(lang('is_answer_close'),'coclassnew[conditions][closed]',makeoption($closedarr,'-1'),'select');
			$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=coclass\" target=\"_blank\">".lang('create_string')."</a>";
			trbasic(lang('udef_query_string').$createurl,'coclassnew[conditions][sqlstr]','','textarea');
			echo "</tbody>";
			tabfooter();
		}
		$addfieldstr = "&nbsp; &nbsp; >><a href=\"?entry=cotypes&action=ccfieldsedit\">".lang('iscustom_coclass_field').'</a>';
		tabheader(lang('add')."&nbsp;[$cotypename]&nbsp;".lang('coclass')."-".lang('iscustom_message').$addfieldstr);
		$a_field = new cls_field;
		foreach($ccfields as $field){
			$a_field->init();
			$a_field->field = $field;
			$a_field->isadd = 1;
			$a_field->trfield('coclassnew','','cc');
			$submitstr .= $a_field->submitstr;
		}
		tabfooter('bcoclassadd',lang('add'),"&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"autocnode\" value=\"1\" checked>&nbsp; ".lang('autocnode')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"bkfollow\" value=\"1\" checked>&nbsp; ".lang('bkfollow'));
		check_submit_func($submitstr);
		a_guide('coclassadd');
	}else{
		if(!$coclassnew['title'] || !$coclassnew['dirname']) amessage('coclassdatamiss',axaction(2,M_REFERER));
		if(preg_match("/[^a-zA-Z_0-9]+/",$coclassnew['dirname'])) amessage('coclassenameillegal',axaction(2,M_REFERER));
		$coclassnew['dirname'] = strtolower($coclassnew['dirname']);
		$enamearr = array();
		foreach($catalogs as $k => $v) $enamearr[] = $v['dirname'];
		foreach($cotypes as $k => $v){
			$arr = read_cache('coclasses',$k);
			foreach($arr as $k1 => $v1) $enamearr[] = $v1['dirname'];
		}
		if(in_array($coclassnew['dirname'], $enamearr)) amessage('coclassenamerepeat',axaction(2,M_REFERER));
		$coclassnew['level'] = $coclassnew['pid'] ? ($coclasses[$coclassnew['pid']]['level'] + 1) : 0;
		$sqlstr0 = "title='$coclassnew[title]',
					dirname='$coclassnew[dirname]',
					isframe='$coclassnew[isframe]',
					level='$coclassnew[level]',
					pid='$coclassnew[pid]'";
		if(empty($cotype['self_reg'])){
			$coclassnew['chids'] = !empty($coclassnew['chids']) ? implode(',',$coclassnew['chids']) : '';
			$coclassnew['taxcp'] = $cotype['taxcp'] ? $coclassnew['taxcp'] : 0;
			$coclassnew['awardcp'] = $cotype['awardcp'] ? $coclassnew['awardcp'] : 0;
			$coclassnew['ftaxcp'] = $cotype['ftaxcp'] ? $coclassnew['ftaxcp'] : 0;
			$coclassnew['apmid'] = $cotype['permission'] ? $coclassnew['apmid'] : 0;
			$coclassnew['rpmid'] = $cotype['permission'] ? $coclassnew['rpmid'] : 0;
			$coclassnew['crpmid'] = $cotype['permission'] ? $coclassnew['crpmid'] : 0;
			$coclassnew['dpmid'] = $cotype['permission'] ? $coclassnew['dpmid'] : 0;
			$coclassnew['allowsale'] = $cotype['sale'] ? $coclassnew['allowsale'] : 0;
			$coclassnew['allowfsale'] = $cotype['fsale'] ? $coclassnew['allowfsale'] : 0;
			$sqlstr0 .= ",awardcp='$coclassnew[awardcp]',
						taxcp='$coclassnew[taxcp]',
						ftaxcp='$coclassnew[ftaxcp]',
						allowsale='$coclassnew[allowsale]',
						allowfsale='$coclassnew[allowfsale]',
						apmid='$coclassnew[apmid]',
						rpmid='$coclassnew[rpmid]',
						crpmid='$coclassnew[crpmid]',
						dpmid='$coclassnew[dpmid]',
						chids='$coclassnew[chids]'";
		}else{
			foreach(array('clicksfrom','commentsfrom','indays','favoritesfrom','praisesfrom','debasesfrom','ordersfrom','pricefrom','answersfrom','currencyfrom',
			'clicksto','commentsto','outdays','favoritesto','praisesto','debasesto','ordersto','priceto','answersto','currencyto',) as $v){
				if($coclassnew['conditions'][$v] == ''){
					unset($coclassnew['conditions'][$v]);
				}else $coclassnew['conditions'][$v] = max(0,intval($coclassnew['conditions'][$v]));
			}
			foreach(array('createdatefrom','createdateto',) as $v){
				if($coclassnew['conditions'][$v] == '' || !isdate($coclassnew['conditions'][$v])){
					unset($coclassnew['conditions'][$v]);
				}else $coclassnew['conditions'][$v] = strtotime($coclassnew['conditions'][$v]);
			}
			if($coclassnew['conditions']['closed'] == '-1') unset($coclassnew['conditions']['closed']);
			$coclassnew['conditions']['sqlstr'] = trim($coclassnew['conditions']['sqlstr']);
			if($coclassnew['conditions']['sqlstr'] == '') unset($coclassnew['conditions']['sqlstr']);
			if(empty($coclassnew['conditions'])) amessage('setself_regcondition','history.go(-1)');
			$coclassnew['conditions'] = addslashes(serialize($coclassnew['conditions']));
			$sqlstr0 .= ",conditions='$coclassnew[conditions]'";
		}
		$c_upload = new cls_upload;	
		$ccfields = fields_order($ccfields);
		$a_field = new cls_field;
		$sqlstr = "";
		foreach($ccfields as $k => $v){
			$a_field->init();
			$a_field->field = $v;
			$a_field->deal('coclassnew');
			if(!empty($a_field->error)){
				$c_upload->rollback();
				amessage($a_field->error,axaction(2,M_REFERER));
			}
			$sqlstr .= ','.$k."='".$a_field->newvalue."'";
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
		}
		$c_upload->saveuptotal(1);
		$db->query("INSERT INTO {$tblprefix}coclass SET 
			$sqlstr0,
			coid='$coid' 
			$sqlstr
			");
		if($ccid = $db->insert_id()){
			if(!empty($autocnode)){
				$sids = array_merge(array(0),array_keys($subsites));
				foreach($sids as $k){
					if($cncid = relate_cncid($coid,$ccid,$coclassnew['level'],$k,1)){
						save_cnode("ccid$coid=$ccid",$k,$cncid);
						updatecache('cnconfigs','',$k);
						updatecache('cnodes',1,$k);
					}
				}
			}
			$c_upload->closure(1, $ccid, 'coclass');
		}
		unset($a_field,$c_upload);
		
		adminlog(lang('add_arc_coclass'));
		updatecache('coclasses',$coid);
		!empty($bkfollow) && $ccid && back_follow($entry.'_'.$action.'_'.$coid.'_'.$sid,array('ccid' => $ccid,));
		amessage('coclassaddfinish', axaction(6,'?entry=coclass&action=coclassedit&coid='.$coid));
	}
}elseif($action == 'coclassadds' && empty($cotype['self_reg'])){
	$pid = 0;
	$chids = chidsarr(1);
	$_settings = array(
		'pid' => array(
			'type' => 'select',
			'title' => lang('parent_coclass'),
			'value' => makeoption(array('0' => lang('topiccoclass')) + ccidsarr($coid), $pid)
		),
		'isframe' => array(
			'type' => 'radio',
			'title' => lang('isframe_coclass_i'),
			'value' => ''
		),
		'chids' => array(
			'type' => '',
			'title' => lang('allow_channel_archive'),
			'value' => makecheckbox('coclasssome[chids][]',$chids,!empty($pmsg['chids']) ? explode(',',$pmsg['chids']) : array(),5)
		)
	);
	if($cotype['permission']){
		$_settings += array(
			'apmid' => array(
				'type' => 'select',
				'title' => lang('add_pmid'),
				'value' => makeoption(pmidsarr('aadd'),@$pmsg['apmid'])
			),
			'rpmid' => array(
				'type' => 'select',
				'title' => lang('read_pmid'),
				'value' => makeoption(pmidsarr('aread'),@$pmsg['rpmid'])
			),
			'crpmid' => array(
				'type' => 'select',
				'title' => lang('cread_pmid'),
				'value' => makeoption(pmidsarr('cread'),@$pmsg['crpmid'])
			),
			'dpmid' => array(
				'type' => 'select',
				'title' => lang('down_pmid'),
				'value' => makeoption(pmidsarr('down'),@$pmsg['dpmid'])
			),
		);
	}
	$cotype['awardcp'] && $_settings['awardcp'] = array(
		'type' => 'select',
		'title' => lang('issue_arc_currency'),
		'value' => makeoption(array('' => lang('noaward')) + $vcps['award'],@$pmsg['awardcp'])
	);
	$cotype['taxcp'] && $_settings['taxcp'] = array(
		'type' => 'select',
		'title' => lang('arc_deduct_currency'),
		'value' => makeoption(array('' => lang('freesale')) + $vcps['tax'],@$pmsg['taxcp'])
	);
	$cotype['ftaxcp'] && $_settings['ftaxcp'] = array(
		'type' => 'select',
		'title' => lang('att_deduct_currency'),
		'value' => makeoption(array('' => lang('freesale')) + $vcps['ftax'],@$pmsg['ftaxcp'])
	);
	$cotype['sale'] && $_settings['allowsale'] = array(
		'type' => 'radio',
		'title' => lang('allow_sale_arc'),
		'value' => @$pmsg['allowsale']
	);
	$cotype['fsale'] && $_settings['allowfsale'] = array(
		'type' => 'radio',
		'title' => lang('allow_sale_att'),
		'value' => @$pmsg['allowfsale']
	);
	foreach($ccfields as $k => $v){
		$_settings[$k] = array(
			'type' => 'field',
			'title' => $v['cname']
		);
	}
	if(!submitcheck('bcoclassset') && !submitcheck('bcoclassadd')) {
		tabheader(lang('batch_coclasses').'-'.lang('base_setting'),'coclasseadd',"?entry=$entry&action=$action&coid=$coid$param_suffix");
		trbasic(lang('batch_coclass_numbers'),'batch_count');
		trbasic(lang('coclass_diffitems'),'','','');

		trbasic('<input class="checkbox" type="checkbox" checked="checked" disabled="disabled" />', '', lang('coclass_ename').
			' <input class="checkbox" type="checkbox" name="auto_pinyin" id="auto_pinyin" value="1" /><label for="auto_pinyin">'.lang('auto_pinyin').'</label>', '');
		foreach($_settings as $k => $v)trbasic('<input class="checkbox" type="checkbox" name="diffitems[]" value="'.$k.'" />', '', $v['title'], '');
		tabfooter('bcoclassset');
	}elseif(!submitcheck('bcoclassadd')){
		$batch_count = max(0, intval($batch_count));
		empty($batch_count) && amessage('empty_batch_count', M_REFERER);
		$_diffitems = array(
			'title' => array(
				'type' => 'text',
				'title' => lang('cocname'),
				'value' => ''
			),
		);
		empty($auto_pinyin) && $_diffitems['dirname'] = array(
			'type' => 'text',
			'title' => lang('coclass_ename'),
			'value' => ''
		);

		$a_field = new cls_field;
		empty($diffitems) && $diffitems = array();
		tabheader(lang('batch_coclasses').'-'.lang('same_setting'),'coclassadd',"?entry=$entry&action=$action&coid=$coid$param_suffix",2,1,1);
		foreach($_settings as $k => $v){
			if(in_array($k, $diffitems)){
				$_diffitems[$k] = $v;
			}elseif($v['type'] == 'field'){
				$a_field->init();
				$a_field->field = $ccfields[$k];
				$a_field->isadd = 1;
				$a_field->trfield('coclasssome','','cc');
			}else{
				trbasic($v['title'], "coclasssome[$k]", $v['value'], $v['type'], array_key_exists('tip', $v) ? $v['tip'] : '');
			}
		}
		tabfooter();
		echo '<br /><br />'.(empty($auto_pinyin) ? '' : '<input type="hidden" name="auto_pinyin" value="1" />');
		for($i = 0; $i < $batch_count; $i++){
			tabheader(lang('batch_coclass_item').($i+1));
			foreach($_diffitems as $k => $v){
				if($v['type'] == 'field'){
					$a_field->init();
					$a_field->field = $ccfields[$k];
					$a_field->isadd = 1;
					$a_field->trfield("coclassitems[$i]",'','cc');
				}else{
					trbasic($v['title'], "coclassitems[$i][$k]", $k != 'chids' ? $v['value'] : makecheckbox("coclassitems[$i][chids][]",$chids,!empty($pmsg['chids']) ? explode(',',$pmsg['chids']) : array(),5), $v['type'], array_key_exists('tip', $v) ? $v['tip'] : '');
				}
			}
			tabfooter();
		}
		echo '
<br /><input class="btn" type="submit" name="bcoclassadd" value="'.lang('submit').'">&nbsp; <input class="checkbox" type="checkbox" name="autocnode" value="1" checked />&nbsp; '.lang('autocnode');
		a_guide('coclassadd');
	}else{
		$enamearr = array();
		foreach($catalogs as $k => $v) $enamearr[] = $v['dirname'];
		foreach($cotypes as $k => $v){
			$arr = read_cache('coclasses',$k);
			foreach($arr as $x => $y) $enamearr[] = $y['dirname'];
		}
		unset($arr);
		if(!empty($auto_pinyin) && !function_exists('Pinyin'))include(M_ROOT.'include/pinyin.fun.php');

		$ok = 0;
		$c_upload = new cls_upload;
		$a_field = new cls_field;
		foreach($coclassitems as $item){
			$coclassnew = $coclasssome;
			foreach($item as $k => $v){
				if(is_array($v)){
					foreach($v as $a => $b)$coclassnew[$k][$a] = $b;
				}else{
					$coclassnew[$k] = $v;
				}
			}

			empty($auto_pinyin) || $coclassnew['dirname'] = Pinyin($coclassnew['title']);
			if(!$coclassnew['title'] || !$coclassnew['dirname'])continue;
			if(preg_match("/[^a-zA-Z_0-9]+/",$coclassnew['dirname']))continue;
			$coclassnew['dirname'] = strtolower($coclassnew['dirname']);
			if(empty($auto_pinyin)){
				if(in_array($coclassnew['dirname'], $enamearr))continue;
			}else{
				$i = 1;
				$dirname = $coclassnew['dirname'];
				while(in_array($coclassnew['dirname'], $enamearr))$coclassnew['dirname'] = $dirname.($i++);
			}

			$coclassnew['level'] = $coclassnew['pid'] ? ($coclasses[$coclassnew['pid']]['level'] + 1) : 0;
			$sqlstr0 = "title='$coclassnew[title]',
						dirname='$coclassnew[dirname]',
						isframe='$coclassnew[isframe]',
						level='$coclassnew[level]',
						pid='$coclassnew[pid]'";

			$coclassnew['chids'] = !empty($coclassnew['chids']) ? implode(',',$coclassnew['chids']) : '';
			$coclassnew['taxcp'] = $cotype['taxcp'] ? $coclassnew['taxcp'] : 0;
			$coclassnew['awardcp'] = $cotype['awardcp'] ? $coclassnew['awardcp'] : 0;
			$coclassnew['ftaxcp'] = $cotype['ftaxcp'] ? $coclassnew['ftaxcp'] : 0;
			$coclassnew['apmid'] = $cotype['permission'] ? $coclassnew['apmid'] : 0;
			$coclassnew['rpmid'] = $cotype['permission'] ? $coclassnew['rpmid'] : 0;
			$coclassnew['crpmid'] = $cotype['permission'] ? $coclassnew['crpmid'] : 0;
			$coclassnew['dpmid'] = $cotype['permission'] ? $coclassnew['dpmid'] : 0;
			$coclassnew['allowsale'] = $cotype['sale'] ? $coclassnew['allowsale'] : 0;
			$coclassnew['allowfsale'] = $cotype['fsale'] ? $coclassnew['allowfsale'] : 0;
			$sqlstr0 .= ",awardcp='$coclassnew[awardcp]',
						taxcp='$coclassnew[taxcp]',
						ftaxcp='$coclassnew[ftaxcp]',
						allowsale='$coclassnew[allowsale]',
						allowfsale='$coclassnew[allowfsale]',
						apmid='$coclassnew[apmid]',
						rpmid='$coclassnew[rpmid]',
						crpmid='$coclassnew[crpmid]',
						dpmid='$coclassnew[dpmid]',
						chids='$coclassnew[chids]'";

			$c_upload = new cls_upload;	
			$ccfields = fields_order($ccfields);
			$a_field = new cls_field;
			$sqlstr = "";
			foreach($ccfields as $k => $v){
				$a_field->init();
				$a_field->field = $v;
				$a_field->deal('coclassnew');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					amessage($a_field->error,axaction(2,M_REFERER));
				}
				$sqlstr .= ','.$k."='".$a_field->newvalue."'";
				if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
			}
			$c_upload->saveuptotal(1);
			$db->query("INSERT INTO {$tblprefix}coclass SET 
				$sqlstr0,
				coid='$coid' 
				$sqlstr
				");
			if($ccid = $db->insert_id()){
				if(!empty($autocnode)){
					$sids = array_merge(array(0),array_keys($subsites));
					foreach($sids as $k){
						if($cncid = relate_cncid($coid,$ccid,$coclassnew['level'],$k,1)){
							save_cnode("ccid$coid=$ccid",$k,$cncid);
						}
					}
				}
				$c_upload->closure(1, $ccid, 'coclass');
				$enamearr[] = $coclassnew['dirname'];
				$ok++;
			}
		}
		unset($a_field,$c_upload);
		if($ok && !empty($autocnode)){
			$sids = array_merge(array(0),array_keys($subsites));
			foreach($sids as $k){
				updatecache('cnconfigs','',$k);
				updatecache('cnodes',1,$k);
			}
		}
		adminlog(lang('add_arc_coclass'));
		updatecache('coclasses',$coid);
		amessage($ok ? ( $ok == $i - 1 ? 'coclassaddfinish' : 'batch_coclass_some') : 'batch_coclass_bad', axaction(6,'?entry=coclass&action=coclassedit&coid='.$coid), $ok);
	}
}elseif($action == 'coclassedit') {
	if(!submitcheck('bcoclassedit')) {
		$ccidsarr = array('0' => lang('noset'));
		tabheader("[$cotypename]&nbsp;".lang('coclass_manager')."&nbsp; &nbsp; >><a href=\"?entry=coclass&action=coclassadd&coid=$coid\" onclick=\"return floatwin('open_coclassedit',this)\">".lang('add_coclass').'</a>'.(empty($cotype['self_reg']) ? " [<a href=\"?entry=$entry&action=coclassadds&coid=$coid$param_suffix\">".lang('batch').'</a>]' : ''),'coclassedit','?entry=coclass&action=coclassedit&coid='.$coid,8);
		echo '<script type="text/javascript">var cocs = [';
		foreach($coclasses as $ccid => $coclass)echo "[$coclass[level],$ccid,'" . str_replace("'","\\'",mhtmlspecialchars($coclass['title'])) . "',$coclass[vieworder]],";
		$lang_cat = lang('cocname');
		$lang_ord = lang('order');
		$lang_add = lang('add');
		$lang_det = lang('detail');
		$lang_del = lang('delete');
		echo <<<DOT
], c, i, l = 0, ckey = 'cocsedit_{$coid}_', stat = [], tmp = [0], imgs = [], img = '',ico = '', ret = '';
function setTreeNode(ico, ix, img){
	var c = Cookie(ckey + ix) == 1, row = ico.parentNode.parentNode;
	row = row.parentNode.rows[row.rowIndex + 1];
	Cookie(ckey + ix, c ? 0 : 1, '9Y');
	ico.src = 'images/admina/' + (c ? 'add' : 'sub') + img + '.gif';
	if(row.firstChild.colSpan > 1)row.style.display = c ? 'none' : '';
}
for(i = 0; i< cocs.length && cocs[i]; i++){
	if(l > cocs[i][0]){
		while(k = tmp.pop())if(cocs[k][0] > cocs[i][0])stat[k] = 1;else if(cocs[k][0] == cocs[i][0])break;
	}
	if(l == cocs[i][0]){
		tmp[tmp.length - 1] = i;
	}else{
		tmp.push(i);
	}
	l = cocs[i][0];
}
stat[i - 1] = 1;//last child
while((i = tmp.pop()) || i === 0)if(cocs[i][0] != l)stat[i] = 1;//last child
c = l = k = 0;
for(i = 0; i< cocs.length && cocs[i]; i++){
	if(l < cocs[i][0]){
		ret += '<tr' + (c ? '' : ' style="display:none"') + '><td class="nb" colspan="8"><table width="100%" border="0" cellpadding="0" cellspacing="0">';
		imgs.push(stat[i - 1] ? '<img src="images/admina/blank.gif" width="32" height="32" class="md" />' : '<img src="images/admina/line1.gif" width="32" height="32" class="md" />');
		img = imgs.join('');
	}else if(l > cocs[i][0])while(l-- > cocs[i][0]){
		ret += '</table></td></tr>';
		imgs.pop();
		img = imgs.join('');
	}
	l = cocs[i][0];
	c = Cookie(ckey + i) == 1;
	if(stat[i]){//last child
		if(cocs[i + 1] && cocs[i + 1][0] > cocs[i][0]){
			ico = '<img onclick="setTreeNode(this,' + i + ',3)" src="images/admina/' + (c ? 'sub' : 'add') + '3.gif" width="32" height="32" class="md" />';
		}else{
			ico = '<img src="images/admina/line3.gif" width="32" height="32" class="md" />';
		}
	}else{
		if(cocs[i + 1] && cocs[i + 1][0] > cocs[i][0]){
			ico = '<img onclick="setTreeNode(this,' + i + ',2)" src="images/admina/' + (c ? 'sub' : 'add') + '2.gif" width="32" height="32" class="md" />';
		}else{
			ico = '<img src="images/admina/line2.gif" width="32" height="32" class="md" />';
		}
	}
	ret += '<tr><td width="30" align="center"><input class="checkbox" name="selectid[' + cocs[i][1] + ']" value="' + cocs[i][1] + '" type="checkbox" /></td>'
		+  '<td width="40" align="center">' + cocs[i][1] + '</td>'
		+  '<td width="350" align="left">' + img + ico + '<input name="coclassesnew[' + cocs[i][1] + '][title]" value="' + cocs[i][2] + '" size="25" maxlength="30" type="text" /></td>'
		+  '<td width="40" align="center"><input name="coclassesnew[' + cocs[i][1] + '][vieworder]" value="' + cocs[i][3] + '" size="2" type="text" /></td>'
		+  '<td width="40" align="center"><a href="?entry=coclass&action=coclassadd$param_suffix&coid=$coid&pid=' + cocs[i][1] + '" onclick="return floatwin(\'open_coclassedit\',this)">$lang_add</a></td>'
		+  '<td width="40" align="center"><a href="?entry=coclass&action=coclassdetail$param_suffix&coid=$coid&ccid=' + cocs[i][1] + '" onclick="return floatwin(\'open_coclassedit\',this)">$lang_det</a></td>'
		+  '<td width="40" align="center"><a href="?entry=coclass&action=coclassdelete$param_suffix&coid=$coid&ccid=' + cocs[i][1] + '">$lang_del</a></td>'
}
while(l-- > 0)ret += '</table></td></tr>';
ret = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb0 tb2 bdbot">'
	+ '<tr><td width="30" align="center"><input class="checkbox" name="chkall" onclick="checkall(this.form, \'selectid\', \'chkall\')" type="checkbox"></td><td width="40" align="center">ID</td><td width="350" align="center">$lang_cat</td><td width="40" align="center">$lang_ord</td><td width="40" align="center">$lang_add</td><td width="40" align="center">$lang_det</td><td width="40" align="center">$lang_del</td></tr>'
	+ ret + '</table>';
document.write(ret);
DOT;
		echo '</script>';

		if(!$cotype['self_reg']){
			tabfooter();
			tabheader(lang('operate_item').viewcheck('viewdetail',0,$actionid.'tbodyfilter'));
			echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display:none\">";
			trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[pid]\" value=\"1\">&nbsp;".lang('reset_parent_coclass'),'arcpid',makeoption(array('0' => lang('topiccoclass')) + ccidsarr($coid)),'select');
			trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[chids]\" value=\"1\">&nbsp;".lang('allow_channel_archive'),'',makecheckbox('arcchids[]',chidsarr(0),array(),5),'');
			$cotype['permission'] && trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[apmid]\" value=\"1\">&nbsp;".lang('add_pmid'),'arcapmid',makeoption(pmidsarr('aadd')),'select');
			$cotype['permission'] && trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[rpmid]\" value=\"1\">&nbsp;".lang('read_pmid'),'arcrpmid',makeoption(pmidsarr('aread')),'select');
			$cotype['permission'] && trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[crpmid]\" value=\"1\">&nbsp;".lang('cread_pmid'),'arccrpmid',makeoption(pmidsarr('cread')),'select');
			$cotype['permission'] && trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[dpmid]\" value=\"1\">&nbsp;".lang('down_pmid'),'arcdpmid',makeoption(pmidsarr('down')),'select');
			$cotype['awardcp'] && trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[awardcp]\" value=\"1\">&nbsp;".lang('issue_arc_currency'),'arcawardcp',makeoption(array('' => lang('noaward')) + $vcps['award']),'select');
			$cotype['taxcp'] && trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[taxcp]\" value=\"1\">&nbsp;".lang('arc_deduct_currency'),'arctaxcp',makeoption(array('' => lang('freesale')) + $vcps['tax']),'select');
			$cotype['ftaxcp'] && trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[ftaxcp]\" value=\"1\">&nbsp;".lang('att_deduct_currency'),'arcftaxcp',makeoption(array('' => lang('freesale')) + $vcps['ftax']),'select');
			$cotype['sale'] && trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[allowsale]\" value=\"1\">&nbsp;".lang('allow_sale_archive'),'arcallowsale','','radio');
			$cotype['fsale'] && trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[allowfsale]\" value=\"1\">&nbsp;".lang('allow_sale_attachment'),'arcallowfsale','','radio');
			echo "</tbody>";
		}
		tabfooter('bcoclassedit');
		a_guide('coclassedit');
	}else{
		if(isset($coclassesnew)){
			foreach($coclassesnew as $ccid => $coclassnew){
				$coclassnew['title'] = trim(strip_tags($coclassnew['title']));
				$coclassnew['title'] = $coclassnew['title'] ? $coclassnew['title'] : $coclasses[$ccid]['title'];
				$coclassnew['vieworder'] = max(0,intval($coclassnew['vieworder']));
				if(($coclassnew['title'] != $coclasses[$ccid]['title']) || ($coclassnew['vieworder'] != $coclasses[$ccid]['vieworder'])){
					$db->query("UPDATE {$tblprefix}coclass SET 
								title='$coclassnew[title]', 
								vieworder='$coclassnew[vieworder]' 
								WHERE ccid='$ccid'
								");
				}
			}
			adminlog(lang('edit_acoclass_mlist'));
		}
		if(!empty($selectid) && !empty($arcdeal)){
			$sqlstr = '';
			if(!empty($arcdeal['chids'])){
				$arcchids = !empty($arcchids) ? implode(',',$arcchids) : '';
				$sqlstr .= ($sqlstr ? ',' : '')."chids='$arcchids'";
			}
			if(!empty($arcdeal['apmid'])) $sqlstr .= ($sqlstr ? ',' : '')."apmid='$arcapmid'";
			if(!empty($arcdeal['rpmid'])) $sqlstr .= ($sqlstr ? ',' : '')."rpmid='$arcrpmid'";
			if(!empty($arcdeal['crpmid'])) $sqlstr .= ($sqlstr ? ',' : '')."crpmid='$arccrpmid'";
			if(!empty($arcdeal['dpmid'])) $sqlstr .= ($sqlstr ? ',' : '')."dpmid='$arcdpmid'";
			if(!empty($arcdeal['awardcp'])) $sqlstr .= ($sqlstr ? ',' : '')."awardcp='$arcawardcp'";
			if(!empty($arcdeal['taxcp'])) $sqlstr .= ($sqlstr ? ',' : '')."taxcp='$arctaxcp'";
			if(!empty($arcdeal['ftaxcp'])) $sqlstr .= ($sqlstr ? ',' : '')."ftaxcp='$arcftaxcp'";
			if(!empty($arcdeal['allowsale'])) $sqlstr .= ($sqlstr ? ',' : '')."allowsale='$arcallowsale'";
			if(!empty($arcdeal['allowfsale'])) $sqlstr .= ($sqlstr ? ',' : '')."allowfsale='$arcallowfsale'";
			$sqlstr && $db->query("UPDATE {$tblprefix}coclass SET $sqlstr WHERE ccid ".multi_str($selectid));

			if(!empty($arcdeal['pid'])){
				foreach($selectid as $ccid){
					$sonids = array();
					$sonids = son_ids($coclasses,$ccid,$sonids);
					if(in_array($arcpid,array_merge(array($ccid),$sonids))) continue;
					
					$newlevel = !$arcpid ? 0 : $coclasses[$arcpid]['level'] + 1;
					$db->query("UPDATE {$tblprefix}coclass SET pid='$arcpid',level='$newlevel' WHERE ccid='$ccid'");

					$leveldiff = $newlevel - $coclasses[$ccid]['level'];
					foreach($sonids as $sonid) $db->query("UPDATE {$tblprefix}coclass SET level=level+".$leveldiff." WHERE ccid='$sonid'");
				}
			}

		}
		updatecache('coclasses',$coid);
		amessage('cocledifin', '?entry=coclass&action=coclassedit&coid='.$coid);
	}
}elseif($action =='coclassdetail' && $ccid) {
	$coclass = read_cache('coclass',$coid,$ccid);
	if(!submitcheck('bcoclassdetail')) {
		$ccidsarr = array('0' => lang('noset'));
		$submitstr = '';

		tabheader(lang('coclass')."&nbsp;[$coclass[title]]&nbsp;".lang('base_setting'),'coclassdetail',"?entry=coclass&action=coclassdetail&coid=$coid&ccid=$ccid",2,1,1);
		trbasic(lang('coclass_ename'),'coclassnew[dirname]',$coclass['dirname'],'text',lang('agdirname'));
		trbasic(lang('parent_coclass'),'coclassnew[pid]',makeoption(array('0' => lang('topiccoclass')) + ccidsarr($coid),$coclass['pid']),'select');
		trbasic(lang('isframe_coclass_i'),'coclassnew[isframe]',$coclass['isframe'],'radio');
		if(empty($cotype['self_reg'])){
			trbasic(lang('allow_channel_archive'),'',makecheckbox('coclassnew[chids][]',chidsarr(1),!empty($coclass['chids']) ? explode(',',$coclass['chids']) : array(),5),'');
			if($cotype['permission']){
				trbasic(lang('add_pmid'),'coclassnew[apmid]',makeoption(pmidsarr('aadd'),$coclass['apmid']),'select');
				trbasic(lang('read_pmid'),'coclassnew[rpmid]',makeoption(pmidsarr('aread'),$coclass['rpmid']),'select');
				trbasic(lang('cread_pmid'),'coclassnew[crpmid]',makeoption(pmidsarr('cread'),$coclass['crpmid']),'select');
				trbasic(lang('down_pmid'),'coclassnew[dpmid]',makeoption(pmidsarr('down'),$coclass['dpmid']),'select');
			}
			$cotype['awardcp'] && trbasic(lang('issue_arc_currency'),'coclassnew[awardcp]',makeoption(array('' => lang('noaward')) + $vcps['award'],$coclass['awardcp']),'select');
			$cotype['taxcp'] && trbasic(lang('arc_deduct_currency'),'coclassnew[taxcp]',makeoption(array('' => lang('freesale')) + $vcps['tax'],$coclass['taxcp']),'select');
			$cotype['ftaxcp'] && trbasic(lang('att_deduct_currency'),'coclassnew[ftaxcp]',makeoption(array('' => lang('freesale')) + $vcps['ftax'],$coclass['ftaxcp']),'select');
			$cotype['sale'] && trbasic(lang('allow_sale_arc'),'coclassnew[allowsale]',$coclass['allowsale'],'radio');
			$cotype['fsale'] && trbasic(lang('allow_sale_att'),'coclassnew[allowfsale]',$coclass['allowfsale'],'radio');
		}
		tabfooter();
		if(!empty($cotype['self_reg'])){
			tabheader(lang('coclass')."&nbsp;[$coclass[title]]&nbsp;".lang('arc_acondition_set')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('morefilter')\">".lang('viewdetail'));
			trrange(lang('add_date'),array('coclassnew[conditions][indays]',isset($coclass['conditions']['indays']) ? $coclass['conditions']['indays'] : '','','&nbsp; '.lang('day_before').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][outdays]',isset($coclass['conditions']['outdays']) ? $coclass['conditions']['outdays'] : '','','&nbsp; '.lang('day_in')));
			trrange(lang('add_date'),array('coclassnew[conditions][createdatefrom]',isset($coclass['conditions']['createdatefrom']) ? date('Y-m-d',$coclass['conditions']['createdatefrom']) : '','','&nbsp; '.lang('start').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][createdateto]',isset($coclass['conditions']['createdateto']) ? date('Y-m-d',$coclass['conditions']['createdateto']) : '','','&nbsp; '.lang('end')),'calendar');
			trrange(lang('clicks'),array('coclassnew[conditions][clicksfrom]',isset($coclass['conditions']['clicksfrom']) ? $coclass['conditions']['clicksfrom'] : '','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][clicksto]',isset($coclass['conditions']['clicksto']) ? $coclass['conditions']['clicksto'] : '','','&nbsp; '.lang('max')));
			trrange(lang('comments'),array('coclassnew[conditions][commentsfrom]',isset($coclass['conditions']['commentsfrom']) ? $coclass['conditions']['commentsfrom'] : '','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][commentsto]',isset($coclass['conditions']['commentsto']) ? $coclass['conditions']['commentsto'] : '','','&nbsp; '.lang('max')));
			trrange(lang('praise_pics'),array('coclassnew[conditions][praisesfrom]',isset($coclass['conditions']['praisesfrom']) ? $coclass['conditions']['praisesfrom'] : '','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][praisesto]',isset($coclass['conditions']['praisesto']) ? $coclass['conditions']['praisesto'] : '','','&nbsp; '.lang('max')));
			trrange(lang('debase_pics'),array('coclassnew[conditions][debasesfrom]',isset($coclass['conditions']['debasesfrom']) ? $coclass['conditions']['debasesfrom'] : '','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][debasesto]',isset($coclass['conditions']['debasesto']) ? $coclass['conditions']['debasesto'] : '','','&nbsp; '.lang('max')));
			echo "<tbody id=\"morefilter\" style=\"display: none;\">";
			trrange(lang('favorite_pics'),array('coclassnew[conditions][favoritesfrom]',isset($coclass['conditions']['favoritesfrom']) ? $coclass['conditions']['favoritesfrom'] : '','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][favoritesto]',isset($coclass['conditions']['favoritesto']) ? $coclass['conditions']['favoritesto'] : '','','&nbsp; '.lang('max')));
			trrange(lang('goods_orders_amount'),array('coclassnew[conditions][ordersfrom]',isset($coclass['conditions']['ordersfrom']) ? $coclass['conditions']['ordersfrom'] : '','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][ordersto]',isset($coclass['conditions']['ordersto']) ? $coclass['conditions']['ordersto'] : '','','&nbsp; '.lang('max')));
			trrange(lang('goods_price'),array('coclassnew[conditions][pricefrom]',isset($coclass['conditions']['pricefrom']) ? $coclass['conditions']['pricefrom'] : '','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][priceto]',isset($coclass['conditions']['priceto']) ? $coclass['conditions']['priceto'] : '','','&nbsp; '.lang('max')));
			trrange(lang('answer_amount'),array('coclassnew[conditions][answersfrom]',isset($coclass['conditions']['answersfrom']) ? $coclass['conditions']['answersfrom'] : '','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][answersto]',isset($coclass['conditions']['answersto']) ? $coclass['conditions']['answersto'] : '','','&nbsp; '.lang('max')));
			trrange(lang('answer_reward_currency'),array('coclassnew[conditions][currencyfrom]',isset($coclass['conditions']['currencyfrom']) ? $coclass['conditions']['currencyfrom'] : '','','&nbsp; '.lang('mini').'&nbsp; &nbsp; -&nbsp; &nbsp; '),array('coclassnew[conditions][currencyto]',isset($coclass['conditions']['currencyto']) ? $coclass['conditions']['currencyto'] : '','','&nbsp; '.lang('max')));
			$closedarr = array('-1' => lang('nolimit'),'0' => lang('noclose'),'1' => lang('closed'));
			trbasic(lang('is_answer_close'),'coclassnew[conditions][closed]',makeoption($closedarr,isset($coclass['conditions']['closed']) ? $coclass['conditions']['closed'] : '-1'),'select');
			$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=coclass\" target=\"_blank\">".lang('create_string')."</a>";
			trbasic(lang('udef_query_string').$createurl,'coclassnew[conditions][sqlstr]',isset($coclass['conditions']['sqlstr']) ? stripslashes($coclass['conditions']['sqlstr']) : '','textarea');
			echo "</tbody>";
			tabfooter();
		}
		$a_field = new cls_field;
		$addfieldstr = "&nbsp; &nbsp; >><a href=\"?entry=cotypes&action=ccfieldsedit\">".lang('iscustom_coclass_field').'</a>';
		tabheader(lang('coclass')."&nbsp;[$coclass[title]]&nbsp;".lang('iscustom_message').$addfieldstr);
		foreach($ccfields as $field){
			$a_field->init();
			$a_field->field = $field;
			$a_field->oldvalue = !isset($coclass[$field['ename']]) ? '' : $coclass[$field['ename']];
			$a_field->trfield('coclassnew','','cc');
			$submitstr .= $a_field->submitstr;
		}
		tabfooter('bcoclassdetail');
		check_submit_func($submitstr);
		a_guide('coclassdetail');
	}else{
		$coclassnew['dirname'] = strtolower($coclassnew['dirname']);
		if($coclassnew['dirname'] != $coclass['dirname']){
			if(preg_match("/[^a-zA-Z_0-9]+/",$coclassnew['dirname'])) amessage('coclassenameillegal',axaction(2,M_REFERER));
			$enamearr = array();
			foreach($catalogs as $k => $v) $enamearr[] = $v['dirname'];
			foreach($cotypes as $k => $v){
				$arr = read_cache('coclasses',$k);
				foreach($arr as $k1 => $v1){
					$enamearr[] = $v1['dirname'];
				}
			}
			unset($arr);
			if(in_array($coclassnew['dirname'], $enamearr)) amessage('coclassenamerepeat',axaction(2,M_REFERER));
		}
		$sonids = array();
		$sonids = son_ids($coclasses,$ccid,$sonids);
		(in_array($coclassnew['pid'],array_merge(array($ccid),$sonids))) && amessage('catas_forbidmove',axaction(2,M_REFERER));
		$coclassnew['level'] = !$coclassnew['pid'] ? 0 : $coclasses[$coclassnew['pid']]['level'] + 1;
		$sqlstr0 = "isframe='$coclassnew[isframe]',
					dirname='$coclassnew[dirname]',
					level='$coclassnew[level]',
					pid='$coclassnew[pid]'";
		if(empty($cotype['self_reg'])){
			$coclassnew['chids'] = !empty($coclassnew['chids']) ? implode(',',$coclassnew['chids']) : '';
			$coclassnew['apmid'] = $cotype['permission'] ? $coclassnew['apmid'] : 0;
			$coclassnew['rpmid'] = $cotype['permission'] ? $coclassnew['rpmid'] : 0;
			$coclassnew['crpmid'] = $cotype['permission'] ? $coclassnew['crpmid'] : 0;
			$coclassnew['dpmid'] = $cotype['permission'] ? $coclassnew['dpmid'] : 0;
			$coclassnew['awardcp'] = $cotype['awardcp'] ? $coclassnew['awardcp'] : 0;
			$coclassnew['taxcp'] = $cotype['taxcp'] ? $coclassnew['taxcp'] : 0;
			$coclassnew['ftaxcp'] = $cotype['ftaxcp'] ? $coclassnew['ftaxcp'] : 0;
			$coclassnew['allowsale'] = $cotype['sale'] ? $coclassnew['allowsale'] : 0;
			$coclassnew['allowfsale'] = $cotype['fsale'] ? $coclassnew['allowfsale'] : 0;
			$sqlstr0 .= ",awardcp='$coclassnew[awardcp]',
						taxcp='$coclassnew[taxcp]',
						ftaxcp='$coclassnew[ftaxcp]',
						allowsale='$coclassnew[allowsale]',
						allowfsale='$coclassnew[allowfsale]',
						apmid='$coclassnew[apmid]',
						rpmid='$coclassnew[rpmid]',
						crpmid='$coclassnew[crpmid]',
						dpmid='$coclassnew[dpmid]',
						chids='$coclassnew[chids]'";
		}else{
			foreach(array('clicksfrom','commentsfrom','indays','favoritesfrom','praisesfrom','debasesfrom','ordersfrom','pricefrom','answersfrom','currencyfrom',
			'clicksto','commentsto','outdays','favoritesto','praisesto','debasesto','ordersto','priceto','answersto','currencyto',) as $v){
				if($coclassnew['conditions'][$v] == ''){
					unset($coclassnew['conditions'][$v]);
				}else{
					$coclassnew['conditions'][$v] = max(0,intval($coclassnew['conditions'][$v]));
				}
			}
			foreach(array('createdatefrom','createdateto',) as $v){
				if($coclassnew['conditions'][$v] == '' || !isdate($coclassnew['conditions'][$v])){
					unset($coclassnew['conditions'][$v]);
				}else{
					$coclassnew['conditions'][$v] = strtotime($coclassnew['conditions'][$v]);
				}
			}
			if($coclassnew['conditions']['closed'] == '-1') unset($coclassnew['conditions']['closed']);
			$coclassnew['conditions']['sqlstr'] = trim($coclassnew['conditions']['sqlstr']);
			if($coclassnew['conditions']['sqlstr'] == '') unset($coclassnew['conditions']['sqlstr']);
			if(empty($coclassnew['conditions'])) amessage('setself_regcondition',axaction(2,M_REFERER));
			$coclassnew['conditions'] = addslashes(serialize($coclassnew['conditions']));
			$sqlstr0 .= ",conditions='$coclassnew[conditions]'";
		}
		$c_upload = new cls_upload;	
		$ccfields = fields_order($ccfields);
		$a_field = new cls_field;
		$sqlstr = "";
		foreach($ccfields as $k => $v){
			$a_field->init();
			$a_field->field = $v;
			$a_field->oldvalue = !isset($coclass[$k]) ? '' : $coclass[$k];
			$a_field->deal('coclassnew');
			if(!empty($a_field->error)){
				$c_upload->rollback();
				amessage($a_field->error,"?entry=coclass&action=coclassdetail&coid=$coid&ccid=$ccid");
			}
			$sqlstr .= ','.$k."='".$a_field->newvalue."'";
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
		}
		$c_upload->closure(1, $ccid, 'coclass');
		$c_upload->saveuptotal(1);
		unset($a_field,$c_upload);

		$leveldiff = $coclassnew['level'] - $coclass['level'];
		foreach($sonids as $sonid){
			$db->query("UPDATE {$tblprefix}coclass SET level=level+".$leveldiff." WHERE ccid='$sonid'");
		}

		$db->query("UPDATE {$tblprefix}coclass SET $sqlstr0 $sqlstr WHERE ccid='$ccid'");
		adminlog(lang('detail_marc_coclass'));
		updatecache('coclasses',$coid);
		amessage('coclasssetfinish',axaction(6,M_REFERER));
	}

}elseif($action == 'coclassdelete' && $ccid) {
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}coclass WHERE pid='$ccid'")) {
		amessage('coclassnosoncandel', '?entry=coclass&action=coclassedit&coid='.$coid);
	}
	if(empty($confirm) || $confirm != 'ok'){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick').">><a href=\"?entry=coclass&action=coclassdelete&coid=$coid&ccid=$ccid&confirm=ok$param_suffix\">".lang('delete')."</a><br>";
		$message .= lang('giveupclick').">><a href=\"?entry=coclass&action=coclassedit&coid=$coid$param_suffix\">".lang('goback')."</a>";
		amessage($message);
	}
	$db->query("UPDATE {$tblprefix}archives SET ccid$coid=0 WHERE ccid$coid='$ccid'",'SILENT');//将该分类的信息从主文档中删除
	
	//删除相关的节点
	$db->query("DELETE FROM {$tblprefix}cnodes WHERE ename REGEXP 'ccid$coid=$ccid(&|$)'");
	$sids = array_merge(array(0),array_keys($subsites));
	foreach($sids as $k) updatecache('cnodes',1,$k);
	$db->query("DELETE FROM {$tblprefix}mcnodes WHERE mcnvar='ccid$coid' AND mcnid='$ccid'");
	updatecache('mcnodes');
	
	$db->query("DELETE FROM {$tblprefix}coclass WHERE ccid='$ccid'");
	adminlog(lang('del_arc_coclass'));
	updatecache('coclasses',$coid);
	amessage('cocdelefini', '?entry=coclass&action=coclassedit&coid='.$coid);

}
?>
