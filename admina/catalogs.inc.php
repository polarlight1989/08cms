<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('catalog') || amessage('no_apermission');
load_cache('cotypes,channels,grouptypes,permissions,vcps,rprojects,cafields');
sys_cache('fieldwords');
load_cache('catalogs,mtpls',$sid);
cache_merge($channels,'channels',$sid);
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.fun.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/cnode.fun.php";
if($action == 'catalogadd'){
	if(!submitcheck('bcatalogadd')){
		!chidsarr() && amessage('addchannel');
		$pid = empty($pid) ? 0 : $pid;
		if($pid){
			$pmsg = read_cache('catalog',$pid,'',$sid);
			$pmsg['tpls'] = empty($pmsg['tpls']) ? array() : explode(',',$pmsg['tpls']);
		}
		$submitstr = '';
		tabheader(lang('add_catalog').'-'.lang('base_setting'),'catalogadd',"?entry=catalogs&action=catalogadd$param_suffix",2,1,1);
		trbasic(lang('catalog_name'),'catalognew[title]','','text');
		trbasic(lang('html_dirname'),'','<input type="text" value="" name="catalognew[dirname]" id="catalognew[dirname]" size="25">&nbsp;&nbsp;<input type="button" value="'.lang('checksubject').'" onclick="check_repeat(this,\'catalognew[dirname]\',\'dirname\');">','');
		trbasic(lang('parent_catalog'),'catalognew[pid]',makeoption(array('0' => lang('topic_catalog')) + caidsarr($catalogs), $pid),'select');
		trbasic(lang('isframe_catalog_r'),'catalognew[isframe]','','radio');
		trbasic(lang('allow_channel_archive'),'',makecheckbox('catalognew[chids][]',chidsarr(0),!empty($pmsg['chids']) ? explode(',',$pmsg['chids']) : array(),5),'');
		trbasic(lang('add_pmid'),'catalognew[apmid]',makeoption(pmidsarr('aadd'),@$pmsg['apmid']),'select');
		trbasic(lang('read_pmid'),'catalognew[rpmid]',makeoption(pmidsarr('aread',@$pmsg['rpmid'])),'select');
		trbasic(lang('cread_pmid'),'catalognew[crpmid]',makeoption(pmidsarr('cread'),@$pmsg['crpmid']),'select');
		trbasic(lang('down_pmid'),'catalognew[dpmid]',makeoption(pmidsarr('down'),@$pmsg['dpmid']),'select');
		trbasic(lang('issue_arc_currency'),'catalognew[awardcp]',makeoption(array('' => lang('noaward')) + $vcps['award'],@$pmsg['awardc']),'select');
		trbasic(lang('arc_deduct_currency'),'catalognew[taxcp]',makeoption(array('' => lang('freesale')) + $vcps['tax'],@$pmsg['taxcp']),'select');
		trbasic(lang('att_deduct_currency'),'catalognew[ftaxcp]',makeoption(array('' => lang('freesale')) + $vcps['ftax'],@$pmsg['ftaxcp']),'select');
		trbasic(lang('allow_sale_archive'),'catalognew[allowsale]',@$pmsg['allowsale'],'radio');
		trbasic(lang('allow_sale_attachment'),'catalognew[allowfsale]',@$pmsg['allowfsale'],'radio');
		for($i = 0;$i <= $cn_max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			trbasic($pvar.lang('df_tpl'),"catalognew[tpls][$i]",makeoption(array('' => lang('noset')) + mtplsarr('cindex'),@$pmsg['tpls'][$i]),'select');
		}
		trbasic(lang('arc_static_url_format'),'catalognew[customurl]',@$pmsg['customurl'],'btext',lang('agcustomurl'));
		tabfooter();
		$submitstr .= makesubmitstr('catalognew[title]',1,0,0,30);
		$submitstr .= makesubmitstr('catalognew[dirname]',1,'tagtype',0,30);
		$addfieldstr = "&nbsp; &nbsp; >><a href=\"?entry=catalogs&action=cafieldsedit\" onclick=\"return floatwin('open_{$actionid}_cafieldsedit',this);\">".lang('iscustom_catalog_field')."</a>";
		tabheader(lang('add_catalog').'-'.lang('iscustom_message').$addfieldstr);
		$a_field = new cls_field;
		foreach($cafields as $field){
			$a_field->init();
			$a_field->field = $field;
			$a_field->isadd = 1;
			$a_field->trfield('catalognew','','ca');
			$submitstr .= $a_field->submitstr;
		}
		tabfooter('bcatalogadd',lang('add'),"&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"autocnode\" value=\"1\" checked>&nbsp; ".lang('autocnode')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"bkfollow\" value=\"1\" checked>&nbsp; ".lang('bkfollow'));
		check_submit_func($submitstr);
		a_guide('catalogadd');

	} else {
		$catalognew['title'] = trim(strip_tags($catalognew['title']));
		if(!$catalognew['title'] || !$catalognew['dirname']) amessage('catalogdatamiss',M_REFERER);
		if(preg_match("/[^a-zA-Z_0-9]+/",$catalognew['dirname'])) amessage('catalogenameillegal',M_REFERER);
		$catalognew['dirname'] = strtolower($catalognew['dirname']);
		$enamearr = array();
		foreach($catalogs as $k => $v) $enamearr[] = $v['dirname'];
		foreach($cotypes as $k => $v){
			$arr = read_cache('coclasses',$k);
			foreach($arr as $x => $y) $enamearr[] = $y['dirname'];
		}
		unset($arr);
		if(in_array($catalognew['dirname'], $enamearr)) amessage('catalogenamerepeat',M_REFERER);
		$catalognew['chids'] = !empty($catalognew['chids']) ? implode(',',$catalognew['chids']) : '';
		$catalognew['level'] = !$catalognew['pid'] ? 0 : $catalogs[$catalognew['pid']]['level'] + 1;
		$catalognew['customurl'] = preg_replace("/^\/+/",'',trim($catalognew['customurl']));
		$catalognew['tpls'] = !empty($catalognew['tpls']) ? implode(',',$catalognew['tpls']) : '';

		$c_upload = new cls_upload;	
		$cafields = fields_order($cafields);
		$a_field = new cls_field;
		$sqlstr = "";
		foreach($cafields as $k => $v){
			$a_field->init();
			$a_field->field = $v;
			$a_field->deal('catalognew');
			if(!empty($a_field->error)){
				$c_upload->rollback();
				amessage($a_field->error,"?entry=catalogs&action=catalogadd$param_suffix");
			}
			$sqlstr .= ','.$k."='".$a_field->newvalue."'";
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
		}
		$c_upload->saveuptotal(1);
		$db->query("INSERT INTO {$tblprefix}catalogs SET 
					title='$catalognew[title]', 
					dirname='$catalognew[dirname]', 
					sid='$sid', 
					level='$catalognew[level]', 
					chids='$catalognew[chids]', 
					isframe='$catalognew[isframe]',
					apmid='$catalognew[apmid]',
					rpmid='$catalognew[rpmid]',
					crpmid='$catalognew[crpmid]',
					dpmid='$catalognew[dpmid]',
					awardcp='$catalognew[awardcp]',
					taxcp='$catalognew[taxcp]',
					ftaxcp='$catalognew[ftaxcp]',
					allowsale='$catalognew[allowsale]',
					allowfsale='$catalognew[allowfsale]',
					tpls='$catalognew[tpls]',
					customurl='$catalognew[customurl]',
					pid='$catalognew[pid]'
					$sqlstr
					");
		
		//自动生成节点
		if($caid = $db->insert_id()){
			if(!empty($autocnode) && $cncid = relate_cncid(0,$caid,$catalognew['level'],$sid,1)){
				save_cnode("caid=$caid",$sid,$cncid,$catalognew['tpls']);
				updatecache('cnconfigs','',$sid);
				updatecache('cnodes',1,$sid);
			}
			$c_upload->closure(1, $caid, 'catalogs');
		}
		unset($a_field,$c_upload);
		
		adminlog(lang('add_catalog'));
		updatecache('catalogs','',$sid);
		!empty($bkfollow) && $caid  && back_follow($entry.'_'.$action.'_'.$sid,array('caid' => $caid,));
		amessage('catalogaddfinish', axaction(6,"?entry=catalogs&action=catalogedit$param_suffix"));
	}
}elseif($action == 'catalogadds'){
	chidsarr() || amessage('addchannel');
	$pid = empty($pid) ? 0 : $pid;
	if($pid){
		$pmsg = read_cache('catalog',$pid,'',$sid);
		$pmsg['tpls'] = empty($pmsg['tpls']) ? array() : explode(',',$pmsg['tpls']);
	}
	$submitstr = '';
	$chids = chidsarr(0);
	$_settings = array(
		'pid' => array(
			'type' => 'select',
			'title' => lang('parent_catalog'),
			'value' => makeoption(array('0' => lang('topic_catalog')) + caidsarr($catalogs), $pid)
		),
		'isframe' => array(
			'type' => 'radio',
			'title' => lang('isframe_catalog_r'),
			'value' => ''
		),
		'chids' => array(
			'type' => '',
			'title' => lang('allow_channel_archive'),
			'value' => makecheckbox('catalogsame[chids][]',$chids,!empty($pmsg['chids']) ? explode(',',$pmsg['chids']) : array(),5)
		),
		'apmid' => array(
			'type' => 'select',
			'title' => lang('add_pmid'),
			'value' => makeoption(pmidsarr('aadd'),@$pmsg['apmid'])
		),
		'rpmid' => array(
			'type' => 'select',
			'title' => lang('read_pmid'),
			'value' => makeoption(pmidsarr('aread',@$pmsg['rpmid']))
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
		'awardcp' => array(
			'type' => 'select',
			'title' => lang('issue_arc_currency'),
			'value' => makeoption(array('' => lang('noaward')) + $vcps['award'],@$pmsg['awardc'])
		),
		'taxcp' => array(
			'type' => 'select',
			'title' => lang('arc_deduct_currency'),
			'value' => makeoption(array('' => lang('freesale')) + $vcps['tax'],@$pmsg['taxcp'])
		),
		'ftaxcp' => array(
			'type' => 'select',
			'title' => lang('att_deduct_currency'),
			'value' => makeoption(array('' => lang('freesale')) + $vcps['ftax'],@$pmsg['ftaxcp'])
		),
		'allowsale' => array(
			'type' => 'radio',
			'title' => lang('allow_sale_archive'),
			'value' => @$pmsg['allowsale']
		),
		'allowfsale' => array(
			'type' => 'radio',
			'title' => lang('allow_sale_attachment'),
			'value' => @$pmsg['allowfsale']
		),
	);
	for($i = 0;$i <= $cn_max_addno;$i ++){
		$pvar = $i ? lang('addp').$i : lang('index');
		$_settings["tpl][$i"] = array(
			'type' => 'select',
			'title' => $pvar.lang('df_tpl'),
			'value' => makeoption(array('' => lang('noset')) + mtplsarr('cindex'),@$pmsg['tpls'][$i])
		);
	}
	$_settings['customurl'] = array(
			'type' => 'btext',
			'title' => lang('arc_static_url_format'),
			'value' => @$pmsg['customurl'],
			'tip' => lang('agcustomurl')
		);
	foreach($cafields as $k => $v){
		$_settings[$k] = array(
			'type' => 'field',
			'title' => $v['cname']
		);
	}
	if(!submitcheck('bcatalogset') && !submitcheck('bcatalogadd')){
		tabheader(lang('batch_catalogs').'-'.lang('base_setting'),'catalogadd',"?entry=$entry&action=$action$param_suffix");
		trbasic(lang('batch_catalog_numbers'),'batch_count');
		trbasic(lang('catalog_diffitems'),'','','');

		trbasic('<input class="checkbox" type="checkbox" checked="checked" disabled="disabled" />', '', lang('html_dirname').
			' <input class="checkbox" type="checkbox" name="auto_pinyin" id="auto_pinyin" value="1" /><label for="auto_pinyin">'.lang('auto_pinyin').'</label>', '');
		foreach($_settings as $k => $v)trbasic('<input class="checkbox" type="checkbox" name="diffitems[]" value="'.$k.'" />', '', $v['title'], '');
		tabfooter('bcatalogset');
	}elseif(!submitcheck('bcatalogadd')){
		$batch_count = max(0, intval($batch_count));
		empty($batch_count) && amessage('empty_batch_count', M_REFERER);
		$_diffitems = array(
			'title' => array(
				'type' => 'text',
				'title' => lang('catalog_name'),
				'value' => ''
			),
		);
		empty($auto_pinyin) && $_diffitems['dirname'] = array(
			'type' => 'text',
			'title' => lang('html_dirname'),
			'value' => ''
		);

		$a_field = new cls_field;
		empty($diffitems) && $diffitems = array();
		tabheader(lang('batch_catalogs').'-'.lang('same_setting'),'catalogadd',"?entry=$entry&action=$action$param_suffix",2,1,1);
		foreach($_settings as $k => $v){
			if(in_array($k, $diffitems)){
				$_diffitems[$k] = $v;
			}elseif($v['type'] == 'field'){
				$a_field->init();
				$a_field->field = $cafields[$k];
				$a_field->isadd = 1;
				$a_field->trfield('catalogsame','','ca');
			}else{
				trbasic($v['title'], "catalogsame[$k]", $v['value'], $v['type'], array_key_exists('tip', $v) ? $v['tip'] : '');
			}
		}
		tabfooter();
		echo '<br /><br />'.(empty($auto_pinyin) ? '' : '<input type="hidden" name="auto_pinyin" value="1" />');
		for($i = 0; $i < $batch_count; $i++){
			tabheader(lang('batch_catalog_item').($i+1));
			foreach($_diffitems as $k => $v){
				if($v['type'] == 'field'){
					$a_field->init();
					$a_field->field = $cafields[$k];
					$a_field->isadd = 1;
					$a_field->trfield("catalogitems[$i]",'','ca');
				}else{
					trbasic($v['title'], "catalogitems[$i][$k]", $k != 'chids' ? $v['value'] : makecheckbox("catalogitems[$i][chids][]",$chids,!empty($pmsg['chids']) ? explode(',',$pmsg['chids']) : array(),5), $v['type'], array_key_exists('tip', $v) ? $v['tip'] : '');
				}
			}
			tabfooter();
		}
		echo '
<br /><input class="btn" type="submit" name="bcatalogadd" value="'.lang('submit').'">&nbsp; <input class="checkbox" type="checkbox" name="autocnode" value="1" checked>&nbsp; '.lang('autocnode');
		a_guide('catalogadd');
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
		foreach($catalogitems as $item){
			$catalognew = $catalogsame;
			foreach($item as $k => $v){
				if(is_array($v)){
					foreach($v as $a => $b)$catalognew[$k][$a] = $b;
				}else{
					$catalognew[$k] = $v;
				}
			}

			$catalognew['title'] = trim(strip_tags($catalognew['title']));
			empty($auto_pinyin) || $catalognew['dirname'] = Pinyin($catalognew['title']);
			if(!$catalognew['title'] || !$catalognew['dirname'])continue;
			if(preg_match("/\W/",$catalognew['dirname']))continue;
			$catalognew['dirname'] = strtolower($catalognew['dirname']);
			if(empty($auto_pinyin)){
				if(in_array($catalognew['dirname'], $enamearr))continue;
			}else{
				$i = 1;
				$dirname = $catalognew['dirname'];
				while(in_array($catalognew['dirname'], $enamearr))$catalognew['dirname'] = $dirname.($i++);
			}

			$catalognew['chids'] = !empty($catalognew['chids']) ? implode(',',$catalognew['chids']) : '';
			$catalognew['level'] = !$catalognew['pid'] ? 0 : $catalogs[$catalognew['pid']]['level'] + 1;
			$catalognew['customurl'] = preg_replace("/^\/+/",'',trim($catalognew['customurl']));
			$catalognew['tpls'] = !empty($catalognew['tpls']) ? implode(',',$catalognew['tpls']) : '';
	
			$cafields = fields_order($cafields);
			$sqlstr = "";
			foreach($cafields as $k => $v){
				$a_field->init();
				$a_field->field = $v;
				$a_field->deal('catalognew');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					break;
				}
				$sqlstr .= ','.$k."='".$a_field->newvalue."'";
				if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";

			}
			if(!empty($a_field->error))continue;
			$c_upload->saveuptotal(1);
			$db->query("INSERT INTO {$tblprefix}catalogs SET 
						title='$catalognew[title]', 
						dirname='$catalognew[dirname]', 
						sid='$sid', 
						level='$catalognew[level]', 
						chids='$catalognew[chids]', 
						isframe='$catalognew[isframe]',
						apmid='$catalognew[apmid]',
						rpmid='$catalognew[rpmid]',
						crpmid='$catalognew[crpmid]',
						dpmid='$catalognew[dpmid]',
						awardcp='$catalognew[awardcp]',
						taxcp='$catalognew[taxcp]',
						ftaxcp='$catalognew[ftaxcp]',
						allowsale='$catalognew[allowsale]',
						allowfsale='$catalognew[allowfsale]',
						tpls='$catalognew[tpls]',
						customurl='$catalognew[customurl]',
						pid='$catalognew[pid]'
						$sqlstr
						");
			
			//自动生成节点
			if($caid = $db->insert_id()){
				if(!empty($autocnode) && $cncid = relate_cncid(0,$caid,$catalognew['level'],$sid,1)){
					save_cnode("caid=$caid",$sid,$cncid,$catalognew['tpls']);
				}
				$enamearr[] = $catalognew['dirname'];
				$ok++;
			}
			$c_upload->closure(1, $caid, 'catalogs');
		}
		if($ok && !empty($autocnode)){
			updatecache('cnconfigs','',$sid);
			updatecache('cnodes',1,$sid);
		}
		unset($a_field,$c_upload);
		
		adminlog(lang('batch_catalog'));
		updatecache('catalogs','',$sid);
		amessage($ok ? ( $ok == $i - 1 ? 'catalogaddfinish' : 'batch_catalog_some') : 'batch_catalog_bad', axaction(6,"?entry=catalogs&action=catalogedit$param_suffix"), $ok);
	}
}elseif($action == 'catalogedit'){
	if(!submitcheck('bcatalogedit')){
		$addfieldstr = $sid ? '' : ("&nbsp; &nbsp; >><a href=\"?entry=catalogs&action=cafieldsedit\"  onclick=\"return floatwin('open_catalogedit',this)\">".lang('iscustom_catalog_field').'</a>');
		tabheader(lang('catalog_manager')."&nbsp; &nbsp; >><a href=\"?entry=catalogs&action=catalogadd$param_suffix\">".lang('add_catalog')."</a> [<a href=\"?entry=$entry&action=catalogadds$param_suffix\">".lang('batch').'</a>]'.$addfieldstr,'catalogedit',"?entry=catalogs&action=catalogedit$param_suffix",'7');
		echo '<script type="text/javascript">var cata = [';
		foreach($catalogs as $caid => $catalog)echo "[$catalog[level],$caid,'" . str_replace("'","\\'",mhtmlspecialchars($catalog['title'])) . "',$catalog[vieworder]],";
		$lang_cat = lang('catalog_name');
		$lang_ord = lang('order');
		$lang_add = lang('add');
		$lang_det = lang('detail');
		$lang_del = lang('delete');
		echo <<<DOT
], c, i, l = 0, ckey = 'cataedit_', stat = [], tmp = [0], imgs = [], img = '',ico = '', ret = '';
function setTreeNode(ico, ix, img){
	var c = Cookie(ckey + ix) == 1, row = ico.parentNode.parentNode;
	row = row.parentNode.rows[row.rowIndex + 1];
	Cookie(ckey + ix, c ? 0 : 1, '9Y');
	ico.src = 'images/admina/' + (c ? 'add' : 'sub') + img + '.gif';
	if(row.firstChild.colSpan > 1)row.style.display = c ? 'none' : '';
}
for(i = 0; i< cata.length && cata[i]; i++){
	if(l > cata[i][0]){
		while(k = tmp.pop())if(cata[k][0] > cata[i][0])stat[k] = 1;else if(cata[k][0] == cata[i][0])break;
	}
	if(l == cata[i][0]){
		tmp[tmp.length - 1] = i;
	}else{
		tmp.push(i);
	}
	l = cata[i][0];
}
stat[i - 1] = 1;//last child
while((i = tmp.pop()) || i === 0)if(cata[i][0] != l)stat[i] = 1;//last child
c = l = k = 0;
for(i = 0; i< cata.length && cata[i]; i++){
	if(l < cata[i][0]){
		ret += '<tr' + (c ? '' : ' style="display:none"') + '><td class="nb" colspan="7" style="padding:0px;border:none"><table width="100%" border="0" cellpadding="0" cellspacing="0">';
		imgs.push(stat[i - 1] ? '<img src="images/admina/blank.gif" width="32" height="32" class="md" />' : '<img src="images/admina/line1.gif" width="32" height="32" class="md" />');
		img = imgs.join('');
	}else if(l > cata[i][0])while(l-- > cata[i][0]){
		ret += '</table></td></tr>';
		imgs.pop();
		img = imgs.join('');
	}
	l = cata[i][0];
	c = Cookie(ckey + i) == 1;
	if(stat[i]){//last child
		if(cata[i + 1] && cata[i + 1][0] > cata[i][0]){
			ico = '<img onclick="setTreeNode(this,' + i + ',3)" src="images/admina/' + (c ? 'sub' : 'add') + '3.gif" width="32" height="32" class="md" />';
		}else{
			ico = '<img src="images/admina/line3.gif" width="32" height="32" class="md" />';
		}
	}else{
		if(cata[i + 1] && cata[i + 1][0] > cata[i][0]){
			ico = '<img onclick="setTreeNode(this,' + i + ',2)" src="images/admina/' + (c ? 'sub' : 'add') + '2.gif" width="32" height="32" class="md" />';
		}else{
			ico = '<img src="images/admina/line2.gif" width="32" height="32" class="md" />';
		}
	}
	ret += '<tr class="txt"><td width="30" align="center"><input class="checkbox" name="selectid[' + cata[i][1] + ']" value="' + cata[i][1] + '" type="checkbox" /></td>'
		+  '<td width="40" align="center">' + cata[i][1] + '</td>'
		+  '<td width="400" align="left">' + img + ico + '<input name="catalogsnew[' + cata[i][1] + '][title]" value="' + cata[i][2] + '" size="25" maxlength="30" type="text" /></td>'
		+  '<td width="40" align="center"><input name="catalogsnew[' + cata[i][1] + '][vieworder]" value="' + cata[i][3] + '" size="2" type="text" /></td>'
		+  '<td width="40" align="center"><a href="?entry=catalogs&action=catalogadd$param_suffix&pid=' + cata[i][1] + '" onclick="return floatwin(\'open_catalogedit\',this)">$lang_add</a></td>'
		+  '<td width="40" align="center"><a href="?entry=catalogs&action=catalogdetail$param_suffix&caid=' + cata[i][1] + '" onclick="return floatwin(\'open_catalogedit\',this)">$lang_det</a></td>'
		+  '<td width="40" align="center"><a href="?entry=catalogs&action=catalogdelete$param_suffix&caid=' + cata[i][1] + '">$lang_del</a></td>'
}
while(l-- > 0)ret += '</table></td></tr>';
ret = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb0 tb2 bdbot">'
	+ '<tr><td width="30" align="center"><input class="checkbox" name="chkall" onclick="checkall(this.form, \'selectid\', \'chkall\')" type="checkbox"></td><td width="40" align="center">ID</td><td align="center">$lang_cat</td><td width="40" align="center">$lang_ord</td><td width="40" align="center">$lang_add</td><td width="40" align="center">$lang_det</td><td width="40" align="center">$lang_del</td></tr>'
	+ ret + '</table>';
document.write(ret);
DOT;
		echo '</script>';
		tabfooter();

		tabheader(lang('operate_item').viewcheck('viewdetail',0,$actionid.'tbodyfilter'));
		echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display:none\">";
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[pid]\" value=\"1\">&nbsp;".lang('reset_parent_catalog'),'arcpid',makeoption(array('0' => lang('topic_catalog')) + caidsarr($catalogs)),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[chids]\" value=\"1\">&nbsp;".lang('allow_channel_archive'),'',makecheckbox('arcchids[]',chidsarr(0),array(),5),'');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[apmid]\" value=\"1\">&nbsp;".lang('add_pmid'),'arcapmid',makeoption(pmidsarr('aadd')),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[rpmid]\" value=\"1\">&nbsp;".lang('read_pmid'),'arcrpmid',makeoption(pmidsarr('aread')),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[crpmid]\" value=\"1\">&nbsp;".lang('cread_pmid'),'arccrpmid',makeoption(pmidsarr('cread')),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[dpmid]\" value=\"1\">&nbsp;".lang('down_pmid'),'arcdpmid',makeoption(pmidsarr('cread')),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[awardcp]\" value=\"1\">&nbsp;".lang('issue_arc_currency'),'arcawardcp',makeoption(array('' => lang('noaward')) + $vcps['award']),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[taxcp]\" value=\"1\">&nbsp;".lang('arc_deduct_currency'),'arctaxcp',makeoption(array('' => lang('freesale')) + $vcps['tax']),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[ftaxcp]\" value=\"1\">&nbsp;".lang('att_deduct_currency'),'arcftaxcp',makeoption(array('' => lang('freesale')) + $vcps['ftax']),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[allowsale]\" value=\"1\">&nbsp;".lang('allow_sale_archive'),'arcallowsale','','radio');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[allowfsale]\" value=\"1\">&nbsp;".lang('allow_sale_attachment'),'arcallowfsale','','radio');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[trantosid]\" value=\"1\">&nbsp;".lang('to_other_subsite'),'arctrantosid',makeoption(array(0 => lang('msite')) + sidsarr(1)),'select');
		echo "</tbody>";
		tabfooter('bcatalogedit');
		a_guide('catalogedit');
	}
	else{
		if(isset($catalogsnew)){
			foreach($catalogsnew as $caid => $catalognew){
				$catalognew['title'] = trim(strip_tags($catalognew['title']));
				$catalognew['title'] = $catalognew['title'] ? $catalognew['title'] : $catalogs[$caid]['title'];
				$catalognew['vieworder'] = max(0,intval($catalognew['vieworder']));
				if(($catalognew['title'] != $catalogs[$caid]['title']) || ($catalognew['vieworder'] != $catalogs[$caid]['vieworder'])){
					$db->query("UPDATE {$tblprefix}catalogs SET 
								title='$catalognew[title]', 
								vieworder='$catalognew[vieworder]' 
								WHERE caid='$caid'
								");
				}
			}
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
			$sqlstr && $db->query("UPDATE {$tblprefix}catalogs SET $sqlstr WHERE caid ".multi_str($selectid));
			if(!empty($arcdeal['trantosid']) && $arctrantosid != $sid){
				foreach($selectid as $caid){
					if(!$catalogs[$caid]['level']){
						if($caids = cnsonids($caid,$catalogs)){
							$db->query("UPDATE {$tblprefix}catalogs SET sid='$arctrantosid' WHERE caid ".multi_str($caids));
							$db->query("UPDATE {$tblprefix}archives SET sid='$arctrantosid' WHERE caid ".multi_str($caids));
						}
					}
				}
				updatecache('catalogs','',$arctrantosid);
			}
			if(!empty($arcdeal['pid'])){
				foreach($selectid as $caid){
					$sonids = array();
					$sonids = son_ids($catalogs,$caid,$sonids);
					$caids = cnsonids($caid,$catalogs);
					if(in_array($arcpid,array_merge(array($caid),$sonids))) continue;
					
					$newlevel = !$arcpid ? 0 : $catalogs[$arcpid]['level'] + 1;
					$db->query("UPDATE {$tblprefix}catalogs SET pid='$arcpid',level='$newlevel' WHERE caid='$caid'");

					$leveldiff = $newlevel - $catalogs[$caid]['level'];
					foreach($sonids as $sonid) $db->query("UPDATE {$tblprefix}catalogs SET level=level+".$leveldiff." WHERE caid='$sonid'");
				}
			}
		}
		updatecache('catalogs','',$sid);
		adminlog(lang('edit_catalog_mlist'));
		amessage('catalogeditfinish', "?entry=catalogs&action=catalogedit$param_suffix");
	}
}elseif($action =='catalogdetail' && $caid){
	$catalog = read_cache('catalog',$caid,'',$sid);
	if(!submitcheck('bcatalogdetail')){
		$submitstr = '';
		tabheader(lang('catalog_base_set')."&nbsp;&nbsp;[$catalog[title]]",'catalogdetail',"?entry=catalogs&action=catalogdetail&caid=$caid$param_suffix",2,1,1);
		trbasic(lang('html_dirname'),'catalognew[dirname]',$catalog['dirname'],'text',lang('agdirname'));
		trbasic(lang('parent_catalog'),'catalognew[pid]',makeoption(array('0' => lang('topic_catalog')) + caidsarr($catalogs),$catalog['pid']),'select');
		trbasic(lang('isframe_catalog_r'),'catalognew[isframe]',$catalog['isframe'],'radio');
		trbasic(lang('allow_channel_archive'),'',makecheckbox('catalognew[chids][]',chidsarr(0),!empty($catalog['chids']) ? explode(',',$catalog['chids']) : array(),5),'');
		trbasic(lang('add_pmid'),'catalognew[apmid]',makeoption(pmidsarr('aadd'),$catalog['apmid']),'select');
		trbasic(lang('read_pmid'),'catalognew[rpmid]',makeoption(pmidsarr('aread'),$catalog['rpmid']),'select');
		trbasic(lang('cread_pmid'),'catalognew[crpmid]',makeoption(pmidsarr('cread'),$catalog['crpmid']),'select');
		trbasic(lang('down_pmid'),'catalognew[dpmid]',makeoption(pmidsarr('down'),$catalog['dpmid']),'select');
		trbasic(lang('issue_arc_currency'),'catalognew[awardcp]',makeoption(array('' => lang('noaward')) + $vcps['award'],$catalog['awardcp']),'select');
		trbasic(lang('arc_deduct_currency'),'catalognew[taxcp]',makeoption(array('' => lang('freesale')) + $vcps['tax'],$catalog['taxcp']),'select');
		trbasic(lang('att_deduct_currency'),'catalognew[ftaxcp]',makeoption(array('' => lang('freesale')) + $vcps['ftax'],$catalog['ftaxcp']),'select');
		trbasic(lang('allow_sale_archive'),'catalognew[allowsale]',$catalog['allowsale'],'radio');
		trbasic(lang('allow_sale_attachment'),'catalognew[allowfsale]',$catalog['allowfsale'],'radio');
		$catalog['tpls'] = empty($catalog['tpls']) ? array() : explode(',',$catalog['tpls']);
		for($i = 0;$i <= $cn_max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			trbasic($pvar.lang('df_tpl'),"catalognew[tpls][$i]",makeoption(array('' => lang('noset')) + mtplsarr('cindex'),@$catalog['tpls'][$i]),'select');
		}
		trbasic(lang('arc_static_url_format'),'catalognew[customurl]',$catalog['customurl'],'btext',lang('agcustomurl'));
		tabfooter();
		$a_field = new cls_field;
		$addfieldstr = $sid ? '' : ("&nbsp; &nbsp; >><a href=\"?entry=catalogs&action=cafieldsedit\">".lang('iscustom_catalog_field').'</a>');
		tabheader(lang('catalog_iscustom_msg')."&nbsp;&nbsp;[$catalog[title]]".$addfieldstr);
		foreach($cafields as $field){
			$a_field->init();
			$a_field->field = $field;
			$a_field->oldvalue = isset($catalog[$field['ename']]) ? $catalog[$field['ename']] : '';
			$a_field->trfield('catalognew','','ca');
			$submitstr .= $a_field->submitstr;
		}
		tabfooter('bcatalogdetail');
		check_submit_func($submitstr);
		a_guide('catalogdetail');
	}else{
		$catalognew['dirname'] = strtolower($catalognew['dirname']);
		if($catalognew['dirname'] != $catalog['dirname']){
			if(preg_match("/[^a-zA-Z_0-9]+/",$catalognew['dirname'])) amessage('catalogenameillegal',M_REFERER);
			$enamearr = array();
			foreach($catalogs as $k => $v) $enamearr[] = $v['dirname'];
			foreach($cotypes as $coid => $cotype){
				$coclasses = read_cache('coclasses',$coid);
				foreach($coclasses as $k => $v){
					$enamearr[] = $v['dirname'];
				}
			}
			unset($coclasses);
			if(in_array($catalognew['dirname'], $enamearr)) amessage('catalogenamerepeat',M_REFERER);
		}
		$sonids = array();
		$sonids = son_ids($catalogs,$caid,$sonids);
		(in_array($catalognew['pid'],array_merge(array($caid),$sonids))) && amessage('catas_forbidmove',"?entry=catalogs&action=catalogdetail&caid=$caid$param_suffix");
		$catalognew['chids'] = !empty($catalognew['chids']) ? implode(',',$catalognew['chids']) : '';
		$catalognew['level'] = !$catalognew['pid'] ? 0 : $catalogs[$catalognew['pid']]['level'] + 1;
		$catalognew['customurl'] = preg_replace("/^\/+/",'',trim($catalognew['customurl']));
		$catalognew['tpls'] = !empty($catalognew['tpls']) ? implode(',',$catalognew['tpls']) : '';

		$c_upload = new cls_upload;	
		$cafields = fields_order($cafields);
		$a_field = new cls_field;
		$sqlstr = "";
		foreach($cafields as $k => $v){
			$a_field->init();
			$a_field->field = $v;
			$a_field->oldvalue = isset($catalog[$k]) ? $catalog[$k] : '';
			$a_field->deal('catalognew');
			if(!empty($a_field->error)){
				$c_upload->rollback();
				amessage($a_field->error,"?entry=catalogs&action=catalogdetail&caid=$caid$param_suffix");
			}
			$sqlstr .= ','.$k."='".$a_field->newvalue."'";
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
		}
		$c_upload->closure(1, $caid, 'catalogs');
		$c_upload->saveuptotal(1);
		unset($a_field,$c_upload);

		$leveldiff = $catalognew['level'] - $catalog['level'];
		foreach($sonids as $sonid){
			$db->query("UPDATE {$tblprefix}catalogs SET level=level+".$leveldiff." WHERE caid='$sonid'");
		}
		$db->query("UPDATE {$tblprefix}catalogs SET
			dirname='$catalognew[dirname]',
			pid='$catalognew[pid]',
			chids='$catalognew[chids]', 
			level='$catalognew[level]',
			isframe='$catalognew[isframe]',
			apmid='$catalognew[apmid]',
			rpmid='$catalognew[rpmid]',
			crpmid='$catalognew[crpmid]',
			dpmid='$catalognew[dpmid]',
			awardcp='$catalognew[awardcp]',
			taxcp='$catalognew[taxcp]',
			ftaxcp='$catalognew[ftaxcp]',
			tpls='$catalognew[tpls]',
			customurl='$catalognew[customurl]',
			allowsale='$catalognew[allowsale]',
			allowfsale='$catalognew[allowfsale]'
			$sqlstr
			WHERE caid='$caid'");
		adminlog(lang('detail_modify_catalog'));
		updatecache('catalogs','',$sid);
		amessage('catalogsetfinish', axaction(6,"?entry=catalogs&action=catalogedit$param_suffix"));
	}

}elseif($action == 'catalogdelete' && $caid){
	if(!isset($confirm) || $confirm != 'ok') {
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=catalogs&action=catalogdelete&caid=".$caid."&confirm=ok$param_suffix>".lang('delete')."</a><br>";
		$message .= lang('giveupclick').">><a href=?entry=catalogs&action=catalogedit$param_suffix>".lang('goback')."</a>";
		amessage($message);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}catalogs WHERE pid='$caid'")) {
		amessage('catalognosoncandel', "?entry=catalogs&action=catalogedit$param_suffix");
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}archives WHERE caid='$caid'")) {
		amessage('catalognoarccandel', "?entry=catalogs&action=catalogedit$param_suffix");
	}

	//删除相关的节点
	$db->query("DELETE FROM {$tblprefix}cnodes WHERE caid='$caid'");
	updatecache('cnodes',1,$sid);
	$db->query("DELETE FROM {$tblprefix}mcnodes WHERE mcnvar='caid' AND mcnid='$caid'");
	updatecache('mcnodes');

	$db->query("DELETE FROM {$tblprefix}catalogs WHERE caid='$caid'");
	adminlog(lang('del_catalog'));
	updatecache('catalogs','',$sid);
	amessage('catalogdelfinish', "?entry=catalogs&action=catalogedit$param_suffix");
}elseif($action == 'cafieldadd'){
	if(!submitcheck('bcafieldadd')){
		tabheader(lang('add_catalog_field'),'cafieldadd',"?entry=catalogs&action=cafieldadd",2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			tabfooter('bcafieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('cn',true,false);
			include_once M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bcafieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('cafieldadd');
	}else{
		$enamearr = $usednames['cafields'];
		$fconfigarr = array(
			'errorurl' => '?entry=catalogs&action=cafieldsedit',
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'catalogs',
			'fieldtable' => $tblprefix.'cnfields',
			'sqlstr' => "iscc='0'",
		);
		list($fmode,$fnew,$fsave) = array('cn',true,true);
		include_once M_ROOT."./include/fields/$fieldnew[datatype].php";
		adminlog(lang('add_catalog_msg_field'));
		updatecache('cafields');
		updatecache('usednames','cafields');
		amessage('fieldaddfinish','?entry=catalogs&action=cafieldsedit');
	}
}elseif($action == 'cafieldsedit'){
	if(!submitcheck('bcafieldsedit')){
		$addfieldstr = "&nbsp; &nbsp; >><a href=\"?entry=catalogs&action=cafieldadd\" onclick=\"return floatwin('open_{$actionid}_cafieldadd',this);\">".lang('add_field')."</a>";
		tabheader(lang('catalog_msg_field_m').$addfieldstr,'cafieldsedit','?entry=catalogs&action=cafieldsedit','5');
		trcategory(array(lang('delete'),lang('field_name'),lang('order'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($cafields as $k => $cafield) {
			fieldlist($k,$cafield,'ca');
		}
		tabfooter('bcafieldsedit');
		a_guide('cafieldsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				$fieldename = $cafields[$id]['ename'];
				dropfieldfromtbl('catalogs',$fieldename,$cafields[$id]['datatype']);
				$db->query("DELETE FROM {$tblprefix}cnfields WHERE iscc=0 AND ename='$id'");
				unset($cafields[$id],$fieldsnew[$id]);
			}
		}

		foreach($cafields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}cnfields SET 
						vieworder='$field[vieworder]',
						cname='$field[cname]'
						WHERE iscc=0 AND ename='$id'");
		}
		adminlog(lang('edit_cmsg_field_mlist'));
		updatecache('cafields');
		updatecache('usednames','cafields');
		amessage('fieldmodifyfinish','?entry=catalogs&action=cafieldsedit');
	}
}elseif($action == 'cafielddetail' && $fieldename){
	!isset($cafields[$fieldename]) && amessage('choosefield', '?entry=catalogs&action=cafieldsedit');
	$field = $cafields[$fieldename];
	if(!submitcheck('bcafielddetail')){
		$submitstr = '';
		tabheader(lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'cafielddetail',"?entry=catalogs&action=cafielddetail&fieldename=$fieldename",2,0,1);
		list($fmode,$fnew,$fsave) = array('cn',false,false);
		include_once M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bcafielddetail');
		check_submit_func($submitstr);
		a_guide('cafielddetail');
	}else{
		$fconfigarr = array(
			'altertable' => $tblprefix.'catalogs',
			'fieldtable' => $tblprefix.'cnfields',
			'wherestr' => "WHERE ename='$fieldename' AND iscc=0",
		);
		list($fmode,$fnew,$fsave) = array('cn',false,true);
		include_once M_ROOT."./include/fields/$field[datatype].php";
		adminlog(lang('detail_mcmsg_field'));
		updatecache('cafields');
		amessage('fieldmodifyfinish','?entry=catalogs&action=cafieldsedit');
	}
}else amessage('errorparament');

?>
