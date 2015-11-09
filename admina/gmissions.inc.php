<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('gather') || amessage('no_apermission');
load_cache('rprojects,cotypes,channels,vcps,permissions,currencys');
load_cache('gmodels,gmissions,catalogs',$sid);
cache_merge($channels,'channels',$sid);
include_once M_ROOT."./include/gather.cls.php";
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/commu.fun.php";
include_once M_ROOT."./include/arcedit.cls.php";
include_once M_ROOT.'./include/progress.cls.php';
$gmidsarr = array();
foreach($gmodels as $k =>$v) $gmidsarr[$k] = $v['cname'];
if($action == 'gmissionsedit'){
	$url_type = 'gmiss';include 'urlsarr.inc.php';
	url_nav(lang('collectmanagement'),$urlsarr,'admin');
	empty($gmidsarr) && amessage('addgatcha');
	if(!submitcheck('bgmissionsedit')){
		tabheader(lang('gather_mission_manager')."&nbsp; &nbsp; >><a href=\"?entry=gmissions&action=gmissionadd$param_suffix\" onclick=\"return floatwin('open_gmission',this)\">".lang('add')."</a>",'gmissionsedit',"?entry=gmissions&action=gmissionsedit$param_suffix",'8');
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),array(lang('mission_cname'),'txtL'),lang('inalbum_mission'),lang('gather_model'),lang('rule'),lang('gather'),lang('admin'),lang('gmission_copy')));
		foreach($gmissions as $k => $gmission){
			$gmission = read_cache('gmission',$k,'',$sid);
			if(empty($gmission['pid'])){
				gmission_list();
				if(!empty($gmission['sonid'])){
					$gmission = read_cache('gmission',$gmission['sonid'],'',$sid);
					gmission_list();
				}
			}
		}
		tabfooter('bgmissionsedit',lang('modify'));
		a_guide('gmissionadd');
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				$gmission = read_cache('gmission',$k,'',$sid);
				if($gmission['pid']) $db->query("UPDATE {$tblprefix}gmissions SET sonid='0' WHERE gsid='".$gmission['pid']."'");//如果有父任务，将关联关系清除
				if($gmission['sonid']){//如有辑内任务，将辑内任务一并删除
					$db->query("DELETE FROM {$tblprefix}gmissions WHERE gsid='".$gmission['sonid']."'");
					$db->query("DELETE FROM {$tblprefix}gurls WHERE gsid='".$gmission['sonid']."'");
					unset($gmissionsnew[$gmission['sonid']]);
				}
				$db->query("DELETE FROM {$tblprefix}gmissions WHERE gsid=$k");
				$db->query("DELETE FROM {$tblprefix}gurls WHERE gsid=$k");//将相关记录清除
				unset($gmissionsnew[$k]);
			}
		}
		if(!empty($gmissionsnew)){
			foreach($gmissionsnew as $k => $v){
				$v['cname'] = empty($v['cname']) ? addslashes($gmissions[$k]['cname']) : $v['cname'];
				$db->query("UPDATE {$tblprefix}gmissions SET cname='$v[cname]' WHERE gsid=$k");
			}
		}
		updatecache('gmissions','',$sid);
		adminlog(lang('edit_gather_mission'));
		amessage('gatmismodfin',"?entry=gmissions&action=gmissionsedit$param_suffix");
	
	}
}elseif($action == 'gmissionadd'){
	$pid = empty($pid) ? 0 : max(0,intval($pid));
	if(empty($gmissions[$pid])) $pid = 0;
	if(!submitcheck('bgmissionadd')){
		tabheader(lang('gather_mission_add'),'gmissionadd',"?entry=gmissions&action=gmissionadd$param_suffix");
		trbasic(lang('gather_mission_cname'),'gmissionadd[cname]');
		trbasic(lang('gather_model'),'gmissionadd[gmid]',makeoption($gmidsarr),'select');
		if($pid){
			trbasic(lang('belong_gather_mission'),'',$gmissions[$pid]['cname'],'');
			trhidden('pid',$pid);
		}
		tabfooter('bgmissionadd',lang('add'));
		a_guide('gmissionadd');
	}else{
		$gmissionadd['cname'] = trim(strip_tags($gmissionadd['cname']));
		(!$gmissionadd['cname'] || !$gmissionadd['gmid']) && amessage('gatmisdatmis',M_REFERER);
		$db->query("INSERT INTO {$tblprefix}gmissions SET cname='$gmissionadd[cname]',gmid='$gmissionadd[gmid]',pid='$pid',sid='$sid',timeout=5");
		if($pid && $sonid = $db->insert_id()){
			$db->query("UPDATE {$tblprefix}gmissions SET sonid='$sonid' WHERE gsid='$pid'");
		}
		updatecache('gmissions','',$sid);
		adminlog(lang('add_gather_mission'));
		amessage('gatmisaddfin',axaction(6,"?entry=gmissions&action=gmissionsedit$param_suffix"));
	}
}elseif($action == 'gmissioncopy'){
	$gsid = empty($gsid) ? 0 : max(0,intval($gsid));
	empty($gmissions[$gsid]) && amessage('gatmisdatmis');
	$gmissionss = array(read_cache('gmission', $gsid, '', $sid));
	if(!submitcheck('bgmissioncopy')){
		tabheader(lang('gather_mission_copy'),'gmissioncopy',"?entry=gmissions&action=gmissioncopy$param_suffix");
		trbasic(lang('gather_mission_cname'),'gmissionnew[cname][]', lang('gmission_copy_with', $gmissions[$gsid]['cname']));
		trbasic(lang('gather_model'),'',$gmidsarr[$gmissions[$gsid]['gmid']],'');
		if($gmissionss[0]['sonid']){
			trbasic(lang('son_gather_mission_cname'),'gmissionnew[cname][]', lang('gmission_copy_with', $gmissions[$gmissionss[0]['sonid']]['cname']));
			trbasic(lang('son_gather_model'),'',$gmidsarr[$gmissions[$gmissionss[0]['sonid']]['gmid']],'');
		}
		trhidden('gsid',$gsid);
		tabfooter('bgmissioncopy',lang('copy'));
		a_guide('gmissioncopy');
	}else{
		foreach($gmissionnew['cname'] as $k => $cname)
			$gmissionnew['cname'][$k] = trim(strip_tags($cname));
		$gmissionnew['cname'][0] || amessage('gatmisdatmis',M_REFERER);
		$gmissionss[0]['sonid'] && !empty($gmissionnew['cname'][1]) && $gmissionss[] = read_cache('gmission', $gmissionss[0]['sonid'], '', $sid);
		$gmissionss[0]['gsid'] = $pid = 0;
		updatecache('gmissions','',$sid);
		foreach($gmissionss as $k => $gmission){
			$cname = $gmissionnew['cname'][$k];
			$gmission['fsettings']	= serialize($gmission['fsettings']);
			$gmission['dvalues']	= serialize($gmission['dvalues']);
			$db->query("INSERT INTO {$tblprefix}gmissions SET 
				cname='$cname',
				gmid='$gmission[gmid]',
				mcharset='$gmission[mcharset]',
				timeout='$gmission[timeout]',
				mcookies='$gmission[mcookies]',
				umode='$gmission[umode]',
				uurls='$gmission[uurls]',
				uregular='$gmission[uregular]',
				ufromnum='$gmission[ufromnum]',
				utonum='$gmission[utonum]',
				ufrompage='$gmission[ufrompage]',
				udesc='$gmission[udesc]',
				uinclude='$gmission[uinclude]',
				uforbid='$gmission[uforbid]',
				uregion='$gmission[uregion]',
				uspilit='$gmission[uspilit]',
				uurltag='$gmission[uurltag]',
				utitletag='$gmission[utitletag]',
				uurltag1='$gmission[uurltag1]',
				uinclude1='$gmission[uinclude1]',
				uforbid1='$gmission[uforbid1]',
				uurltag2='$gmission[uurltag2]',
				uinclude2='$gmission[uinclude2]',
				uforbid2='$gmission[uforbid2]',
				mpfield='$gmission[mpfield]',
				mpmode='$gmission[mpmode]',
				mptag='$gmission[mptag]',
				mpinclude='$gmission[mpinclude]',
				mpforbid='$gmission[mpforbid]',
				fsettings='$gmission[fsettings]',
				dvalues='$gmission[dvalues]',
				pid='$pid',sonid='0',sid='$sid'"
			);
			$gmissionss[$k]['gsid'] = $pid = $db->insert_id();
		}
		if(count($gmissionss) > 1)
			$db->query("UPDATE {$tblprefix}gmissions SET sonid='$pid' WHERE gsid='{$gmissionss[0]['gsid']}'");
		updatecache('gmissions','',$sid);
		adminlog(lang('copy_gather_mission'));
		amessage('gatmisaddfin',axaction(6,"?entry=gmissions&action=gmissionsedit$param_suffix"));
	}
}elseif($action == 'gmissionurls' && $gsid){
	$url_type = 'grule';include 'urlsarr.inc.php';
	url_nav(lang('rulemanagement'),$urlsarr,'netsite');
	$gmission = read_cache('gmission',$gsid,'',$sid);
	$gmodel = $gmodels[$gmission['gmid']];
	if(!submitcheck('bgmissionurls')){
		$mchararr = array('gbk' => 'GBK/GB2312','utf8' => 'UTF-8','big5' => 'BIG5',);
		tabheader(lang('gather_based_setting'),'gmissionurls',"?entry=gmissions&action=gmissionurls&gsid=$gsid$param_suffix");
		trbasic(lang('gather_mission_cname'),'gmissionnew[cname]',$gmission['cname']);
		trbasic(lang('charset'),'gmissionnew[mcharset]',makeoption($mchararr,$gmission['mcharset']),'select');
		trbasic(lang('timeout_s'),'gmissionnew[timeout]',empty($gmission['timeout']) ? 0 : $gmission['timeout'],'text',lang('agnolimit'));
		trbasic(lang('login_website').'Cookies','gmissionnew[mcookies]',empty($gmission['mcookies']) ? '' : $gmission['mcookies'],'btext');
		tabfooter();

		tabheader(lang('netsite_source_rule'));
		if(empty($gmission['pid'])){
			trbasic(lang('handwork_source_netsite'),'gmissionnew[uurls]',$gmission['uurls'],'textarea');
			trbasic(lang('serial_source_netsite'),'gmissionnew[uregular]',empty($gmission['uregular']) ? '' : $gmission['uregular'],'btext');
			trbasic(lang('serial_start_pagecode'),'gmissionnew[ufromnum]',$gmission['ufromnum']);
			trbasic(lang('serial_end_pagecode'),'gmissionnew[utonum]',$gmission['utonum']);
		}else{
			$frompagearr = array(0 => lang('based_content_page0'),1 => lang('content_trace_page0_1'),2 => lang('content_trace_page0_2'));
			trbasic(lang('ufrompage'),'gmissionnew[ufrompage]',makeoption($frompagearr,$gmission['ufrompage']),'select');
		}
		trbasic(lang('reverseorder_gather'),'gmissionnew[udesc]',$gmission['udesc'],'radio');
		tabfooter();

		tabheader(lang('netsite_gather_rule'));
		trbasic(lang('page_initial_rgp'),'gmissionnew[uregion]',$gmission['uregion'],'textarea');
		trbasic(lang('netsite_list_cell_split_tag'),'gmissionnew[uspilit]',$gmission['uspilit']);
		trbasic(lang('netsite_gather_pattern'),'gmissionnew[uurltag]',$gmission['uurltag'],'textarea');
		trbasic(lang('title_gather_pattern'),'gmissionnew[utitletag]',$gmission['utitletag'],'textarea');
		trbasic(lang('result_netsite_mustinc'),'gmissionnew[uinclude]',$gmission['uinclude']);
		trbasic(lang('result_netsite_forbidinc'),'gmissionnew[uforbid]',$gmission['uforbid']);
		tabfooter();

		tabheader(lang('trace_netsite_rule'));
		trbasic(lang('trace_netsite_1_gp'),'gmissionnew[uurltag1]',$gmission['uurltag1'],'textarea');
		trbasic(lang('trace_netsite_1_m'),'gmissionnew[uinclude1]',$gmission['uinclude1']);
		trbasic(lang('trace_netsite_1_f'),'gmissionnew[uforbid1]',$gmission['uforbid1']);
		trbasic(lang('trace_netsite_2_gp'),'gmissionnew[uurltag2]',$gmission['uurltag2'],'textarea');
		trbasic(lang('trace_netsite_2_m'),'gmissionnew[uinclude2]',$gmission['uinclude2']);
		trbasic(lang('trace_netsite_2_f'),'gmissionnew[uforbid2]',$gmission['uforbid2']);
		tabfooter('bgmissionurls');
		a_guide('gmissionurls');
	}else{
		$gmissionnew['cname'] = empty($gmissionnew['cname']) ? $gmission['cname'] : $gmissionnew['cname'];
		if(empty($gmission['pid'])){
			$gmissionnew['uurls'] = trim($gmissionnew['uurls']);
			$gmissionnew['uregular'] = trim($gmissionnew['uregular']);
			$gmissionnew['ufromnum'] = max(0,intval($gmissionnew['ufromnum']));
			$gmissionnew['utonum'] = max(0,intval($gmissionnew['utonum']));
			$gmissionnew['ufrompage'] = 0;
		}else{
			$gmissionnew['uurls'] = '';
			$gmissionnew['uregular'] = '';
			$gmissionnew['ufromnum'] = 0;
			$gmissionnew['utonum'] = 0;
			$gmissionnew['ufrompage'] = max(0,intval($gmissionnew['ufrompage']));
		}
		$db->query("UPDATE {$tblprefix}gmissions SET
					cname='$gmissionnew[cname]',
					timeout='$gmissionnew[timeout]',
					mcharset='$gmissionnew[mcharset]',
					mcookies='$gmissionnew[mcookies]',
					uurls='$gmissionnew[uurls]',
					uregular='$gmissionnew[uregular]',
					ufromnum='$gmissionnew[ufromnum]',
					utonum='$gmissionnew[utonum]',
					ufrompage='$gmissionnew[ufrompage]',
					udesc='$gmissionnew[udesc]',
					uregion='$gmissionnew[uregion]',
					uspilit='$gmissionnew[uspilit]',
					uurltag='$gmissionnew[uurltag]',
					utitletag='$gmissionnew[utitletag]',
					uinclude='$gmissionnew[uinclude]',
					uforbid='$gmissionnew[uforbid]',
					uurltag1='$gmissionnew[uurltag1]',
					uinclude1='$gmissionnew[uinclude1]',
					uforbid1='$gmissionnew[uforbid1]',
					uurltag2='$gmissionnew[uurltag2]',
					uinclude2='$gmissionnew[uinclude2]',
					uforbid2='$gmissionnew[uforbid2]'
					WHERE gsid=$gsid");
		updatecache('gmissions','',$sid);
		adminlog(lang('detail0_modify_gm'));
		amessage('gatmismodfin',M_REFERER);
	
	}
}elseif($action == 'gmissionfields' && $gsid){
	$url_type = 'grule';include 'urlsarr.inc.php';
	url_nav(lang('rulemanagement'),$urlsarr,'content');
	$gmission = read_cache('gmission',$gsid,'',$sid);
	$gmodel = read_cache('gmodel',$gmission['gmid'],'',$sid);
	$fields = read_cache('fields',$gmodel['chid']);
	if(!submitcheck('bgmissionfields')){
		$mpfieldarr = array('' => lang('no_splitpage'));
		foreach($fields as $k => $v){
			if(isset($gmodel['gfields'][$k])) $mpfieldarr[$k] = $v['cname'];
		}
		tabheader(lang('splitpage_gather_rule'),'gmissionfields',"?entry=gmissions&action=gmissionfields&gsid=$gsid$param_suffix",4);
		trbasic(lang('splitpage_field'),'gmissionnew[mpfield]',makeoption($mpfieldarr,isset($gmission['mpfield']) ? $gmission['mpfield'] : ''),'select');
		trbasic(lang('notall_splitpage_navi'),'',makeradio('gmissionnew[mpmode]', array('0' => lang('yes'), '1' => lang('no')), $gmission['mpmode']),'');
		trbasic(lang('plitpage_navi_region'),'gmissionnew[mptag]',isset($gmission['mptag']) ? $gmission['mptag'] : '','textarea');
		trbasic(lang('splitpage_url_mustinc'),'gmissionnew[mpinclude]',isset($gmission['mpinclude']) ? $gmission['mpinclude'] : '');
		trbasic(lang('splitpage_url_forbidinc'),'gmissionnew[mpforbid]',isset($gmission['mpforbid']) ? $gmission['mpforbid'] : '');
		tabfooter();
		tabheader(lang('gather_field_rule'),'',"",4);
		foreach($fields as $k => $v){
			if(isset($gmodel['gfields'][$k])) missionfield($v['cname'],$k,empty($gmission['fsettings'][$k]) ? array() : $gmission['fsettings'][$k],$v['datatype']);
		}
		tabfooter('bgmissionfields');
		a_guide('gmissionfields');
	}else{
		if(!empty($fsettingsnew)){
			foreach($fsettingsnew as $k => $fsettingnew){
				if(!in_array($fields[$k]['datatype'],array('images','files','flashs','medias'))){
					$fsettingnew['clearhtml'] = isset(${'clearhtml'.$k}) ? implode(',',${'clearhtml'.$k}) : '';
				}
				foreach($fsettingnew as $t => $v){
					$fsettingnew[$t] = stripslashes($v);
				}
				$fsettingsnew[$k] = $fsettingnew;
			}
		}
		$fsettingsnew = empty($fsettingsnew) ? '' : addslashes(serialize($fsettingsnew));
		$db->query("UPDATE {$tblprefix}gmissions SET
					mpfield='$gmissionnew[mpfield]',
					mpmode='$gmissionnew[mpmode]',
					mptag='$gmissionnew[mptag]',
					mpinclude='$gmissionnew[mpinclude]',
					mpforbid='$gmissionnew[mpforbid]',
					fsettings='$fsettingsnew'
					WHERE gsid=$gsid");
		updatecache('gmissions','',$sid);
		adminlog(lang('detail0_modify_gm'));
		amessage('gatmisedifin',M_REFERER);

	}
}elseif($action == 'gmissionoutput' && $gsid){
	$url_type = 'grule';include 'urlsarr.inc.php';
	url_nav(lang('rulemanagement'),$urlsarr,'output');
	$gmission = read_cache('gmission',$gsid,'',$sid);
	$gmodel = read_cache('gmodel',$gmission['gmid'],'',$sid);
	$dvalues = empty($gmission['dvalues']) ? array() : $gmission['dvalues'];
	$chid = $gmodel['chid'];
	$channel = read_cache('channel',$chid);
	$fields = read_cache('fields',$chid);
	if(!submitcheck('bgmissionoutput')){
		$a_field = new cls_field;
		$mustsarr = array();
		foreach($fields as $k => $v){
			if(in_array($k,array_keys($gmodel['gfields']))) $mustsarr[$k] = $v['cname'];
		}
		tabheader('['.$gmission['cname'].lang('output_based_setting'),'gmissionoutput',"?entry=gmissions&action=gmissionoutput&gsid=$gsid$param_suffix",2,1,1);
		$submitstr = '';
		trbasic(lang('mustfields'),'',multiselect('dvaluesnew[musts][]',$mustsarr,empty($dvalues['musts']) ? array() : explode(',',$dvalues['musts'])),'');
		if($fields['abstract']['available'] && !in_array('abstract',array_keys($gmodel['gfields']))){
			trbasic(lang('auto_abstract'),'dvaluesnew[autoabstract]',empty($dvalues['autoabstract']) ? 0 : $dvalues['autoabstract'],'radio');
		}
		if($fields['thumb']['available'] && !in_array('thumb',array_keys($gmodel['gfields']))){
			trbasic(lang('auto_thumb'),'dvaluesnew[autothumb]',empty($dvalues['autothumb']) ? 0 : $dvalues['autothumb'],'radio');
		}
		tabfooter();
		tabheader('['.$gmission['cname'].lang('output_default_value'));
		tr_cns('*'.lang('be_catalog'),'dvaluesnew[caid]',empty($dvalues['caid']) ? 0 : $dvalues['caid'],$sid,0,$chid,lang('p_choose'));
		$submitstr .= makesubmitstr('dvaluesnew[caid]',1,'int',0,0,'common');
		foreach($fields as $k => $field){
			if($field['available'] && !in_array($k,array_keys($gmodel['gfields'])) && !in_array($k,array('abstract','thumb'))){
				$a_field->init();
				$a_field->field = $field;
				$a_field->oldvalue = !isset($dvalues[$k]) ? '' : $dvalues[$k];
				$a_field->trfield('dvaluesnew','','',$chid);
				$submitstr .= $a_field->submitstr;
			}
		}
		foreach($cotypes as $k => $v){
			if(!$v['self_reg']){
				tr_cns($v['cname'],"dvaluesnew[ccid$k]",empty($dvalues["ccid$k"]) ? '' : $dvalues["ccid$k"],$sid,$k,$chid,lang('p_choose'),0,$v['asmode']);
			}
		}
		tabfooter('bgmissionoutput');
		check_submit_func($submitstr);
		a_guide('gmissionoutput');
	}else{//数组内的addsalshes
		if(empty($dvaluesnew['caid'])) amessage('choosecatalog',"?entry=gmissions&action=gmissionoutput&gsid=$gsid$param_suffix");
		$dvaluesnew['musts'] = empty($dvaluesnew['musts']) ? '' : implode(',',$dvaluesnew['musts']);
		foreach($cotypes as $k => $v){
			$dvaluesnew["ccid$k"] = empty($dvaluesnew["ccid$k"]) ? '' : $dvaluesnew["ccid$k"];
		}
		$dvaluesnew['autoabstract'] = empty($dvaluesnew['autoabstract']) ? 0 : $dvaluesnew['autoabstract'];
		$dvaluesnew['autothumb'] = empty($dvaluesnew['autothumb']) ? 0 : $dvaluesnew['autothumb'];
		$c_upload = new cls_upload;	
		$fields = fields_order($fields);
		$a_field = new cls_field;
		foreach($fields as $k => $field){
			if($field['available'] && !in_array($k,array_keys($gmodel['gfields'])) && !in_array($k,array('abstract','thumb'))){
				$a_field->init();
				$a_field->field = $field;
				$a_field->oldvalue = !isset($dvalues[$k]) ? '' : $dvalues[$k];
				$a_field->deal('dvaluesnew');
				if(!empty($a_field->error)){//非采集字段的设置
					$c_upload->rollback();
					amessage($a_field->error,M_REFERER);
				}
				$dvaluesnew[$k] = $a_field->newvalue;
			}
		}
		unset($a_field);
		if(!empty($dvaluesnew)){
			foreach($dvaluesnew as $t => $v){
				$dvaluesnew[$t] = stripslashes($v);
			}
		}
		$dvaluesnew = empty($dvaluesnew) ? '' : addslashes(serialize($dvaluesnew));
		$db->query("UPDATE {$tblprefix}gmissions SET
					dvalues='$dvaluesnew'
					WHERE gsid=$gsid");
		$c_upload->closure(1, $gsid, 'gmissions');
		$c_upload->saveuptotal(1);
		updatecache('gmissions','',$sid);
		adminlog(lang('detail0_modify_gm'));
		amessage('outrulmodfin',M_REFERER);
	}
}elseif($action == 'urlstest' && $gsid){
	$url_type = 'grule';include 'urlsarr.inc.php';
	url_nav(lang('rulemanagement'),$urlsarr,'test');
	if(empty($confirm) && empty($gather_test_url)){
		$message = lang('choose_urlstest')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=gmissions&action=urlstest&gsid=".$gsid."&confirm=1$param_suffix>".lang('start')."</a>";
		amessage($message);
	}else{
		tabheader(lang('gather_netsite_rule_test'), 'gather_testu', "?$_SERVER[QUERY_STRING]");
		$c_upload = new cls_upload;
		$gather = new cls_gather;
		$gather->set_mission($gsid);
#		check_rule_urls($gather->gmission);
		if(empty($gather_test_url)){
			$message = '';
			if($surls = $gather->fetch_surls()){
				foreach($surls as $surl) $message .= $surl.'<br>';
			}else $message = lang('none_source_netsite').'<br>';
				trbasic(lang('all_source_netsite'),'',$message,'');
			$surl = empty($surls) ? '' : $surls[array_rand($surls)];
		}else{
			$surl = $gather_test_url;
		}
		$sonid = $gather->gmission['sonid'];
		$lang_test = lang('test');
		$lang_content = lang('content');
		$lang_son = lang('son_mission');
		trbasic(lang('current_test_source_netsite'),'',"<input name=\"gather_test_url\" style=\"width:98%\" value=\"$surl\" />",'');
		tabfooter('bsubmit');
		$tab_titles = array(lang('sn'),array(lang('netsite_title'), 'txtL'),array(lang('content_netsite'), 'txtL'),$lang_test,lang('trace_netsite_1'),lang('trace_netsite_2'));
		if($sonid){
			array_splice($tab_titles, 3, 0, $lang_son);
			$ufrompage = read_cache('gmission',$sonid,'',$sid);
			$ufrompage = $ufrompage['ufrompage'];
			$ufrompage = 'gurl' . ($ufrompage ? $ufrompage : '');
		}
		if($rets = $gather->fetch_gurls($surl,1)){//得到测试网址列表
			tabheader(lang('con_weblist'),'','',$sonid ? 7 : 6);
			trcategory($tab_titles);
			$i = 0;
			foreach($rets as $k => $v){
				$i ++;
				$titlestr = empty($v['son']) ? "<b>$v[utitle]</b>" : "&nbsp; &nbsp; &nbsp; &nbsp; $v[utitle]";
				$gurlstr  = empty($k) ? '-' : "<a href=\"$k\" target=\"_blank\">".mhtmlspecialchars(strlen($k) > 25 ? '...' . substr($k, -25) : $k)."</a>";
				$gurl1str = empty($v['gurl1']) ? '-' : "<a href=\"$v[gurl1]\" target=\"_blank\">Y</a>";
				$gurl2str = empty($v['gurl2']) ? '-' : "<a href=\"$v[gurl2]\" target=\"_blank\">Y</a>";
				if(empty($k)){
					$teststr  = '&nbsp;';
				}else{
					$gurl	  = rawurlencode($k);
					$gurl1	  = empty($v['gurl1']) ? '' : rawurlencode($v['gurl1']);
					$gurl2	  = empty($v['gurl2']) ? '' : rawurlencode($v['gurl2']);
					$teststr  = "<a href=\"?entry=gmissions&action=contentstest$param_suffix&gsid=$gsid&confirm=1&gather_test_url=$gurl&gather_test_url1=$gurl1&gather_test_url2=$gurl2\" onclick=\"return floatwin('open_newgmission_cnt',this)\" >$lang_content</a>";
					if($sonid){
	#					$sonurl   = $gurl2 ? $gurl2 : ($gurl1 ? $gurl1 : $gurl);
						$sonurl   = $$ufrompage;
						$teststr2 = "<a href=\"?entry=gmissions&action=urlstest$param_suffix&gsid=$sonid&confirm=1&gather_test_url=$sonurl\" onclick=\"return floatwin('open_newgmission_son',this)\" >$lang_son</a>";
					}
				}
				echo "<tr class=\"txt\">".
					"<td class=\"txtC w40\">$i</td>\n".
					"<td class=\"txtL\">$titlestr</td>\n".
					"<td class=\"txtL\">$gurlstr</td>\n".
					"<td class=\"txtC\">$teststr</td>\n".
					($sonid ? "<td class=\"txtC\">$teststr2</td>\n" : '') .
					"<td class=\"txtC\">$gurl1str</td>\n".
					"<td class=\"txtC\">$gurl2str</td></tr>\n";
			}
			tabfooter();
		}else{
			$surl && amessage(lang(is_array($rets) ? 'no_content_gather' : 'gather_timeout_err'));
		}
		a_guide('urlstest');
	}
}elseif($action == 'contentstest' && $gsid){//只从数据库中加入有效链接来测试
	$url_type = 'grule';include 'urlsarr.inc.php';
	url_nav(lang('rulemanagement'),$urlsarr,'test');
	if(empty($confirm)){
		$message = lang('choose_contentstest')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=gmissions&action=contentstest&gsid=".$gsid."&confirm=1$param_suffix>".lang('start')."</a>";
		amessage($message);
	}else{
		tabheader(lang('gather_content_rule_test'), 'gather_testc', "?$_SERVER[QUERY_STRING]");
#		$counts = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}gurls WHERE gsid='$gsid' AND gatherdate=0");

		if(empty($gather_test_url)){
			$item = $db->fetch_one("SELECT guid,gurl,gurl1,gurl2,utitle FROM {$tblprefix}gurls WHERE gsid='$gsid' AND gatherdate=0 AND guid >= (SELECT floor(RAND() * (SELECT MAX(guid) FROM {$tblprefix}gurls))) ORDER BY guid LIMIT 1");
		}else{
			$item = array(
				'utitle' => lang('gather_test_title'),
				'gurl' => $gather_test_url,
				'gurl1' => $gather_test_url1,
				'gurl2' => $gather_test_url2,
			);
		}
		if($item){
			trbasic(lang('current_test_netsite_title'),'',mhtmlspecialchars($item['utitle']),'');
#			trbasic(lang('current_test_netsite'),'',"<input name=\"gather_test_url\" style=\"width:98%\" value=\"$item[gurl]\" />",'');
#			trbasic('', '', '<input class="bigButton" type="submit" name="bsubmit" value="' . lang('submit') . '">','');
			trbasic(lang('current_test_netsite'),'',"<a href=\"$item[gurl]\" target=\"_blank\">$item[gurl]</a>",'');
			empty($item['gurl1']) || trbasic(lang('trace_netsite_1'),'',"<a href=\"$item[gurl1]\" target=\"_blank\">$item[gurl1]</a>",'');
			empty($item['gurl2']) || trbasic(lang('trace_netsite_2'),'',"<a href=\"$item[gurl2]\" target=\"_blank\">$item[gurl2]</a>",'');
			$c_upload = new cls_upload;
			$gather = new cls_gather;
			$gather->set_mission($gsid);
			$contents = $gather->gather_guid(0,1, $item);
			if($contents){
				$timeout = lang('gather_timeout_err');
				$chid = $gmodels[$gmissions[$gsid]['gmid']]['chid'];
				$fields = read_cache('fields',$chid);
				foreach($contents as $k => $v){
					trbasic('['.$fields[$k]['cname'].']'.lang('gather_result'), '', $v === false ? $timeout : mhtmlspecialchars($v),'');
				}
			}else{
				trbasic(lang('gather_result'),'','','');
			}
		}else{
			trbasic('', '', lang('please_gather_netsite'),'');
		}
		tabfooter();
		a_guide('contentstest');
	}
}elseif($action == 'contentsoption' && $gsid){
	empty($gmissions[$gsid]) && amessage('choosegatmis');
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : $viewdetail;
	$gathered = isset($gathered) ? $gathered : '-1';
	$outputed = isset($outputed) ? $outputed : '-1';
	$abover = isset($abover) ? $abover : '-1';
	$keyword = empty($keyword) ? '' : $keyword;

	$filterstr = '';
	foreach(array('viewdetail','gathered','outputed','abover','keyword') as $k) $filterstr .= "&$k=".rawurlencode(stripslashes($$k));

	$wheresql = "WHERE gsid='$gsid'";
	$gathered != '-1' && $wheresql .= " AND gatherdate".($gathered ? '!=' : '=')."'0'";
	$outputed != '-1' && $wheresql .= " AND outputdate".($outputed ? '!=' : '=')."'0'";
	$abover != '-1' && $wheresql .= " AND abover='$abover'";
	$keyword && $wheresql .= " AND utitle LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	if(!submitcheck('barcsedit')){
		echo form_str($actionid.'arcsedit',"?entry=gmissions&action=contentsoption&gsid=$gsid&use_push=1&page=$page$param_suffix");
		tabheader_e();
		echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
		$gatheredarr = array('-1' => lang('gather_state'),'0' => lang('no1_gather'),'1' => lang('already1_gather'));
		echo "<select style=\"vertical-align: middle;\" name=\"gathered\">".makeoption($gatheredarr,$gathered)."</select>&nbsp; ";
		$outputedarr = array('-1' => lang('output_state'),'0' => lang('no1_output'),'1' => lang('already1_output'));
		echo "<select style=\"vertical-align: middle;\" name=\"outputed\">".makeoption($outputedarr,$outputed)."</select>&nbsp; ";
		$aboverarr = array('-1' => lang('weather_abover_album'),'0' => lang('noabover'),'1' => lang('abover'));
		echo "<select style=\"vertical-align: middle;\" name=\"abover\">".makeoption($aboverarr,$abover)."</select>&nbsp; ";
		echo strbutton('bfilter','filter0');
		echo "</td></tr>";
		tabfooter();

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * FROM {$tblprefix}gurls $wheresql ORDER BY guid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($row = $db->fetch_array($query)){
			$gatherstr = $row['gatherdate'] ? date("Y-m-d",$row['gatherdate']) : '-';
			$outputstr = $row['outputdate'] ? date("Y-m-d",$row['outputdate']) : '-';
			$gurl1str = $row['gurl1'] ? "<a href=$row[gurl1] target=\"_blank\">".lang('look')."</a>" : '-';
			$gurl2str = $row['gurl2'] ? "<a href=$row[gurl2] target=\"_blank\">".lang('look')."</a>" : '-';
			$aboverstr = $row['abover'] ? 'Y' : '-';
			$itemstr .= "<tr class=\"txt\"><td class=\"txtC\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[guid]]\" value=\"$row[guid]\">\n".
				"<td class=\"txtL\"><a href=$row[gurl] target=\"_blank\">$row[utitle]</a></td>\n".
				"<td class=\"txtC\">$gurl1str</td>\n".
				"<td class=\"txtC\">$gurl2str</td>\n".
				"<td class=\"txtC\">$gatherstr</td>\n".
				"<td class=\"txtC\">$outputstr</td>\n".
				"<td class=\"txtC\">$aboverstr</td>\n".
				"<td class=\"txtC\"><a href=\"?entry=gmissions&action=contentdetail&guid=$row[guid]$param_suffix\" onclick=\"return floatwin('open_newgmission',this)\">".lang('look')."</a></td></tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}gurls $wheresql");
		$multi = multi($counts,$atpp,$page, "?entry=gmissions&action=contentsoption&gsid=$gsid$filterstr$param_suffix");

		tabheader(lang('content_gather_manager').'-'.$gmissions[$gsid]['cname']."&nbsp; &nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',8);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('content_netsite'),lang('trace_netsite_1'),lang('trace_netsite_2'),lang('gather'),lang('putin'),lang('abover'),lang('result')));
		echo $itemstr;
		tabfooter();
		echo $multi;

		tabheader(lang('operate_item'));
		$soperatestr = '';
		$s_arr = array('delete' => lang('delete'),'gather' => lang('gather'),'output' => lang('putin'),'regather' => lang('reset_gather'));
		foreach($s_arr as $k => $v) $soperatestr .= "<input class=\"radio\" type=\"radio\" id=\"arcdeal_$k\" name=\"arcdeal\" value=\"$k\" /><label for=\"arcdeal_$k\">$v</label> &nbsp;";
		trbasic(lang('choose_item'),'',$soperatestr,'');
		$aboverarr = array(0 => lang('noabover'),1 => lang('already1_abover'));
		trbasic("<input class=\"radio\" type=\"radio\" name=\"arcdeal\" value=\"abover\">&nbsp;".lang('setting_album_abover'),'',makeradio('arcabover',$aboverarr),'');
		tabfooter('barcsedit');
	}else{
		if(empty($selectid) && empty($select_all)) amessage('selectnet',"?entry=gmissions&action=contentsoption&gsid=$gsid$filterstr$param_suffix");
		if(!empty($select_all)){
			$parastr = "";
			foreach(array('arcabover') as $k) $parastr .= "&$k=".$$k;
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}gurls $wheresql");
				$pages = @ceil($counts / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "guid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT guid FROM {$tblprefix}gurls $nwheresql ORDER BY guid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)) $selectid[] = $item['guid'];
			}
		}
		if($arcdeal == 'delete'){
			$idstr = multi_str($selectid);
			$db->query("DELETE FROM {$tblprefix}gurls WHERE guid $idstr OR pid $idstr", 'UNBUFFERED');
		}elseif($arcdeal == 'gather'){
			$progress = new Progress();
			$c_upload = new cls_upload;
			$gather = new cls_gather;
			$gather->set_mission($gsid);
			foreach($selectid as $guid) $gather->gather_guid($guid,0);
			unset($gather);
		}elseif($arcdeal == 'output'){
			$progress = new Progress();
			$c_upload = new cls_upload;
			$gather = new cls_gather;
			$gather->set_mission($gsid);
			foreach($selectid as $guid) $gather->output_guid($guid);
			unset($gather);
		}elseif($arcdeal == 'abover'){
			$gmissions[$gsid]['sonid'] && $db->query("UPDATE {$tblprefix}gurls SET abover='$arcabover' WHERE guid ".multi_str($selectid),'UNBUFFERED');
		}elseif($arcdeal == 'regather'){
			$db->query("UPDATE {$tblprefix}gurls SET gatherdate=0,outputdate=0 WHERE guid ".multi_str($selectid),'UNBUFFERED');
		}
		empty($progress) || $progress->hide();
		if(!empty($select_all)){
			$npage ++;
			if($npage <= $pages){
				$fromid = min($selectid);
				$transtr = '';
				$transtr .= "&select_all=1";
				$transtr .= "&pages=$pages";
				$transtr .= "&npage=$npage";
				$transtr .= "&barcsedit=1";
				$transtr .= "&fromid=$fromid";
				amessage(lang('operating')."<br>".lang('all')." $pages ".lang('page0')."，".lang('dealing')." $npage ".lang('page0')."<br><br>
				<a href=\"?entry=gmissions&action=contentsoption&gsid=$gsid$filterstr$param_suffix\">>>".lang('pause')."</a>",
				"?entry=gmissions&action=contentsoption&gsid=$gsid&page=$page$filterstr$transtr$parastr&arcdeal=$arcdeal$param_suffix",200);
			}
		}
		adminlog(lang('content gather admin'));
		amessage('ga_op_finish',"?entry=gmissions&action=contentsoption&gsid=$gsid$filterstr$param_suffix");
	}
}elseif($action == 'contentdetail' && $guid){
	if(!$item = $db->fetch_one("SELECT * FROM {$tblprefix}gurls WHERE guid=".$guid)) amessage('p_choosegurl');
	tabheader(lang('ga_result'));
	trbasic(lang('utitle'),'',mhtmlspecialchars($item['utitle']),'');
	trbasic(lang('cnturl'),'',$item['gurl'] ? "<a href=\"$item[gurl]\" target=\"_blank\">$item[gurl]</a>" : '-','');
	trbasic(lang('traceurl').'1','',$item['gurl1'] ? "<a href=\"$item[gurl1]\" target=\"_blank\">$item[gurl1]</a>" : '-','');
	trbasic(lang('traceurl').'2','',$item['gurl2'] ? "<a href=\"$item[gurl2]\" target=\"_blank\">$item[gurl2]</a>" : '-','');
	if($item['contents']){
		$item['contents'] = unserialize($item['contents']);
		$chid = $gmodels[$gmissions[$item['gsid']]['gmid']]['chid'];
		$fields = read_cache('fields',$chid);
		foreach($item['contents'] as $k => $v){
			trbasic('['.$fields[$k]['cname'].']'.lang('ga_result'),'',mhtmlspecialchars($v),'');
		}
	}elseif($item['outputdate']){
		trbasic(lang('ga_result'),'',lang('already1_output'),'');
	}
	tabfooter();
}elseif($action == 'urlsauto' && $gsid){
	empty($gmissions[$gsid]) && amessage('choosegatmis');
	if(empty($confirm)){
		$message = lang('choose_urlsauto')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=gmissions&action=urlsauto&gsid=".$gsid."&confirm=1&use_push=1$param_suffix>".lang('start')."</a>";
		amessage($message);
	}
	$c_upload = new cls_upload;
	$gather = new cls_gather;
	$gather->set_mission($gsid);
	$surls = $gather->fetch_surls();
	$progress = new Progress();
	foreach($surls as $surl) $gather->fetch_gurls($surl);
	unset($gather);
	$progress->hide();
	adminlog(lang('url_auto_gather'));
	amessage('connetgatfin');
	
}elseif($action == 'gatherauto' && $gsid){
	empty($gmissions[$gsid]) && amessage('choosegatmis');
	if(empty($confirm)){
		$message = lang('choose_gatherauto')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=gmissions&action=gatherauto&gsid=".$gsid."&confirm=1&use_push=1$param_suffix>".lang('start')."</a>";
		amessage($message);
	}
	$gmission = read_cache('gmission',$gsid,'',$sid);
	//已采集但未完结的合辑中的子内容也需要采集
	$wheresql = "WHERE gsid='$gsid' AND ".($gmission['sonid'] ? 'abover=0' : 'gatherdate=0');
	if(empty($pages)){
		if(!$nums = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}gurls $wheresql")) amessage('nongatitem');
		$pages = @ceil($nums / $atpp);
		$npage = $fromid = 0;
	}
	$npage = empty($npage) ? 0 : $npage;
	$c_upload = new cls_upload;
	$gather = new cls_gather;
	$gather->set_mission($gsid);
	$gather->gather_fields();//先行分析采集规则
	empty($gather->fields) && amessage('setgatrul');
	$progress = new Progress();
	$query = $db->query("SELECT guid FROM {$tblprefix}gurls $wheresql AND guid>'$fromid' ORDER BY guid ASC LIMIT 0,$atpp");
	while($row = $db->fetch_array($query)){
		$gather->gather_guid($row['guid'],0);
		$fromid = $row['guid'];
	}
	unset($gather);
	$npage ++;
	if($npage <= $pages){
		amessage('operating',"?entry=gmissions&action=gatherauto&gsid=$gsid&pages=$pages&npage=$npage&fromid=$fromid&confirm=1&use_push=1$param_suffix",$pages,$npage,"<a href=\"?entry=gmissions&action=gmissionsedit$param_suffix\">",'</a>');
	}
	$progress->hide();
	adminlog(lang('content_auto_gather'));
	amessage('conaugatfin');
	
}elseif($action == 'outputauto' && $gsid){
	empty($gmissions[$gsid]) && amessage('choosegatmis');
	if(empty($confirm)){
		$message = lang('choose_outputauto')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=gmissions&action=outputauto&gsid=".$gsid."&confirm=1&use_push=1$param_suffix>".lang('start')."</a>";
		amessage($message);
	}
	$gmission = read_cache('gmission',$gsid,'',$sid);
	//已入库但未完结的合辑中的子内容也需要入库
	$wheresql = "WHERE gsid='$gsid' AND gatherdate<>'0' AND ".($gmission['sonid'] ? 'abover=0' : 'outputdate=0');
	if(empty($pages)){
		if(!$nums = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}gurls $wheresql")) amessage('nooutitem');
		$pages = @ceil($nums / $atpp);
		$npage = $fromid = 0;
	}
	$c_upload = new cls_upload;
	$gather = new cls_gather;
	$gather->set_mission($gsid);
	$gather->output_configs();//先行分析入库规则
	empty($gather->oconfigs) && amessage('setoutrul');
	$progress = new Progress();
	$query = $db->query("SELECT guid FROM {$tblprefix}gurls $wheresql AND guid>'$fromid' ORDER BY guid ASC LIMIT 0,$atpp");
	while($row = $db->fetch_array($query)){
		$gather->output_guid($row['guid']);
		$fromid = $row['guid'];
	}
	$progress->hide();
	unset($gather);
	$npage ++;
	if($npage <= $pages){
		amessage('operating',"?entry=gmissions&action=outputauto&gsid=$gsid&pages=$pages&npage=$npage&fromid=$fromid&confirm=1&use_push=1$param_suffix",$pages,$npage,"<a href=\"?entry=gmissions&action=gmissionsedit$param_suffix\">",'</a>');
	}
	adminlog(lang('content_auto_output'));
	amessage('conautoutfin');
}elseif($action == 'allauto' && $gsid){
	empty($gmissions[$gsid]) && amessage('choosegatmis');
	if(empty($confirm)){
		$message = lang('choose_allauto')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=gmissions&action=allauto&gsid=".$gsid."&confirm=1&use_push=1$param_suffix>".lang('start')."</a>";
		amessage($message);
	}
	$gmission = read_cache('gmission',$gsid,'',$sid);
	$c_upload = new cls_upload;
	$gather = new cls_gather;
	$gather->set_mission($gsid);
	$progress = new Progress();
	if(empty($deal)){
		$surls = $gather->fetch_surls();
		foreach($surls as $surl){
			$gather->fetch_gurls($surl);
		}
		$progress->hide();
		amessage('toautogather',"?entry=gmissions&action=allauto&gsid=$gsid&deal=gather&confirm=1&use_push=1$param_suffix");
	}elseif($deal == 'gather'){
		//已采集但未完结的合辑中的子内容也需要采集
		$wheresql = "WHERE gsid='$gsid' AND ".($gmission['sonid'] ? 'abover=0' : 'gatherdate=0');
		if(empty($pages)){
			if(!$nums = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}gurls $wheresql")) amessage('nogatheritem');
			$pages = @ceil($nums / $atpp);
			$npage = $fromid = 0;
		}
		$npage = empty($npage) ? 0 : $npage;
		$c_upload = new cls_upload;
		$gather = new cls_gather;
		$gather->set_mission($gsid);
		$gather->gather_fields();//先行分析采集规则
		empty($gather->fields) && amessage(lang('p_setrule'));
		$query = $db->query("SELECT guid FROM {$tblprefix}gurls $wheresql AND guid>'$fromid' ORDER BY guid ASC LIMIT 0,$atpp");
		while($row = $db->fetch_array($query)){
			$gather->gather_guid($row['guid'],0);
			$fromid = $row['guid'];
		}
		unset($gather);
		$npage ++;
		if($npage <= $pages){
			amessage('operating',"?entry=gmissions&action=allauto&gsid=$gsid&deal=gather&pages=$pages&npage=$npage&fromid=$fromid&confirm=1&use_push=1$param_suffix",$pages,$npage+1,"<a href=\"?entry=gmissions&action=gmissionsedit$param_suffix\">",'</a>');
		}
		$progress->hide();
		amessage('toautoouput',"?entry=gmissions&action=allauto&gsid=$gsid&deal=output&confirm=1&use_push=1$param_suffix");
	}elseif($deal == 'output'){
		$progress->hide();
		//已入库但未完结的合辑中的子内容也需要入库
		$wheresql = "WHERE gsid='$gsid' AND gatherdate<>'0' AND ".($gmission['sonid'] ? 'abover=0' : 'outputdate=0');
		if(empty($pages)){
			if(!$nums = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}gurls $wheresql")) amessage(lang('none output item'),"?entry=gmissions&action=gmissionsedit$param_suffix");
			$pages = @ceil($nums / $atpp);
			$npage = $fromid = 0;
		}
		$c_upload = new cls_upload;
		$gather = new cls_gather;
		$gather->set_mission($gsid);
		$gather->output_configs();//先行分析入库规则
		empty($gather->oconfigs) && amessage('p_setrule');
		$query = $db->query("SELECT guid FROM {$tblprefix}gurls $wheresql AND guid>'$fromid' ORDER BY guid ASC LIMIT 0,$atpp");
		while($row = $db->fetch_array($query)){
			$gather->output_guid($row['guid']);
			$fromid = $row['guid'];
		}
		unset($gather);
		$npage ++;
		if($npage <= $pages){
			amessage('operating',"?entry=gmissions&action=allauto&gsid=$gsid&deal=output&pages=$pages&npage=$npage&fromid=$fromid&confirm=1&use_push=1$param_suffix",$pages,$npage+1,"<a href=\"?entry=gmissions&action=gmissionsedit$param_suffix\">",'</a>');
		}
		amessage('onekeyfinish');
	}
}elseif($action == 'break'){
	amessage('breakfinish', axaction(2, "?entry=gmissions&action=gmissionsedit$param_suffix"));
}
function gmission_list(){
	global $param_suffix,$gmission,$sid;
	$gsid = $gmission['gsid'];
	$gmodel = read_cache('gmodel',$gmission['gmid'],'',$sid);
	$levelstr = !empty($gmission['pid']) ? '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ' : '';
	$addstr = !empty($gmission['pid']) ? 'Y' : (!empty($gmission['sonid']) ? '-' : "<a href=\"?entry=gmissions&action=gmissionadd&pid=$gsid$param_suffix\" onclick=\"return floatwin('open_gmission',this)\">".lang('add')."</a>");
	$regularstr = "<a href=\"?entry=gmissions&action=gmissionurls&gsid=$gsid$param_suffix\" onclick=\"return floatwin('open_gmission',this)\">".lang('rule')."</a>";
	$gatherstr = !empty($gmission['pid']) ? '&nbsp;' : "<a href=\"?entry=gmissions&action=allauto&gsid=$gsid&use_push=1$param_suffix\" onclick=\"return floatwin('open_gmission_gather',this)\"><b>".lang('autoall')."</b></a>&nbsp;" .
				 "<a href=\"?entry=gmissions&action=urlsauto&gsid=$gsid&use_push=1$param_suffix\" onclick=\"return floatwin('open_gmission_gather',this)\">".lang('netsite')."</a>&nbsp;" .
				 "<a href=\"?entry=gmissions&action=gatherauto&gsid=$gsid&use_push=1$param_suffix\" onclick=\"return floatwin('open_gmission_gather',this)\">".lang('content')."</a>&nbsp;" .
				 "<a href=\"?entry=gmissions&action=outputauto&gsid=$gsid&use_push=1$param_suffix\" onclick=\"return floatwin('open_gmission_gather',this)\">".lang('warehousing')."</a>";
	echo "<tr class=\"txt\">".
		"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$gsid]\" value=\"$gsid\">\n".
		"<td class=\"txtL\">$levelstr<input type=\"text\" size=\"20\" name=\"gmissionsnew[$gsid][cname]\" value=\"$gmission[cname]\"></td>\n".
		"<td class=\"txtC\">$addstr</td>\n".
		"<td class=\"txtC\">$gmodel[cname]</td>\n".
		"<td class=\"txtC w70\">$regularstr</td>\n".
		"<td class=\"txtC w120\">$gatherstr</td>\n".
		"<td class=\"txtC w40\"><a href=\"?entry=gmissions&action=contentsoption&gsid=$gsid$param_suffix\" onclick=\"return floatwin('open_gmission',this)\">".lang('admin')."</a></td>".
		"<td class=\"txtC w60\"><a href=\"?entry=gmissions&action=gmissioncopy&gsid=$gsid$param_suffix\" onclick=\"return floatwin('open_gmission',this)\">".lang('copy')."</a></td>".
		"</tr>\n";
}
function missionfield($cname,$ename,$setting=array(),$datatype='text'){
	global $rprojects;
	$mcell = in_array($datatype,array('images','files','flashs','medias')) ? 1 : 0;//是否是多集模式字段
	$noremote = in_array($datatype,array('int','float','select','mselect','date')) ? 1 : 0;//是否不存在附件下载因素的字段
	${'clearhtml'.$ename} = (isset($setting['clearhtml']) && !$mcell) ? explode(',',$setting['clearhtml']) : array();
	$rpidsarr = array('0' => lang('notremote'));
	foreach($rprojects as $rpid => $rproject){
		$rpidsarr[$rpid] = $rproject['cname'];
	}
	$frompagearr = array('0' => lang('based_content_page0'),'1' => lang('netsilistpage'),'2' => lang('content_trace_page0_1'),'3' => lang('content_trace_page0_2'));
	
	echo "<tr class=\"category\"><td class=\"txtL\"><b>[".mhtmlspecialchars($cname)."]</b></td><td colspan=\"3\"></td></tr>";
	echo "<tr>\n".
		"<td width=\"15%\" class=\"txtR\">".lang('contensourcpage')."</td>\n".
		"<td width=\"35%\" class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"fsettingsnew[$ename][frompage]\">".makeoption($frompagearr,empty($setting['frompage']) ? 0 : $setting['frompage'])."</select></td>\n".
		"<td width=\"15%\" class=\"txtR\">".lang('resultdealfunc')."</td>\n".
		"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fsettingsnew[$ename][func]\" value=\"".(empty($setting['func']) ? '' : mhtmlspecialchars($setting['func']))."\"></td>\n".
		"</tr>\n";
	if(!$mcell){
		echo "<tr>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('fiecontgathpatt')."</td>\n".
			"<td class=\"txtL\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][ftag]\" cols=\"30\">".(isset($setting['ftag']) ? mhtmlspecialchars($setting['ftag']) : '')."</textarea></td>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('clear')."Html<br><input class=\"checkbox\" type=\"checkbox\" name=\"chk$ename\" onclick=\"checkall(this.form,'clearhtml$ename','chk$ename')\">".lang('selectall')."</td>\n".
			"<td class=\"txtL\">".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"1\"".(in_array('1',${'clearhtml'.$ename}) ? " checked" : "").">&lt;a&gt;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"2\"".(in_array('2',${'clearhtml'.$ename}) ? " checked" : "").">&lt;br&gt;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"3\"".(in_array('3',${'clearhtml'.$ename}) ? " checked" : "").">&lt;table&gt;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"4\"".(in_array('4',${'clearhtml'.$ename}) ? " checked" : "").">&lt;tr&gt;<br>\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"5\"".(in_array('5',${'clearhtml'.$ename}) ? " checked" : "").">&lt;td&gt;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"6\"".(in_array('6',${'clearhtml'.$ename}) ? " checked" : "").">&lt;p&gt;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"7\"".(in_array('7',${'clearhtml'.$ename}) ? " checked" : "").">&lt;font&gt;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"8\"".(in_array('8',${'clearhtml'.$ename}) ? " checked" : "").">&lt;div&gt;<br>\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"9\"".(in_array('9',${'clearhtml'.$ename}) ? " checked" : "").">&lt;span&gt;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"10\"".(in_array('10',${'clearhtml'.$ename}) ? " checked" : "").">&lt;tbody&gt;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"11\"".(in_array('11',${'clearhtml'.$ename}) ? " checked" : "").">&lt;b&gt;<br>\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"12\"".(in_array('12',${'clearhtml'.$ename}) ? " checked" : "").">&amp;nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"13\"".(in_array('13',${'clearhtml'.$ename}) ? " checked" : "").">&lt;script&gt;\n".
			"</td>\n".
			"</tr>\n";
		echo "<tr>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('replmesssouront')."</td>\n".
			"<td class=\"txtL\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][fromreplace]\" cols=\"30\">".(isset($setting['fromreplace']) ? mhtmlspecialchars($setting['fromreplace']) : '')."</textarea></td>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('repmessagresulcont')."</td>\n".
			"<td class=\"txtL\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][toreplace]\" cols=\"30\">".(isset($setting['toreplace']) ? mhtmlspecialchars($setting['toreplace']) : '')."</textarea></td>\n".
			"</tr>\n";
	}else{
		echo "<tr>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('lisregigathpatt')."</td>\n".
			"<td class=\"txtL\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][ftag]\" cols=\"30\">".(isset($setting['ftag']) ? mhtmlspecialchars($setting['ftag']) : '')."</textarea></td>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('liscellsplitag')."</td>\n".
			"<td class=\"txtL\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][splittag]\" cols=\"30\">".(isset($setting['splittag']) ? mhtmlspecialchars($setting['splittag']) : '')."</textarea></td>\n".
			"</tr>\n";
		echo "<tr>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('cellurlgathpatte')."</td>\n".
			"<td class=\"txtL\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][remotetag]\" cols=\"30\">".(isset($setting['remotetag']) ? mhtmlspecialchars($setting['remotetag']) : '')."</textarea></td>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('celltitlgathepatt')."</td>\n".
			"<td class=\"txtL\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][titletag]\" cols=\"30\">".(isset($setting['titletag']) ? mhtmlspecialchars($setting['titletag']) : '')."</textarea></td>\n".
			"</tr>\n";
	
	}
	if(!$noremote){
		echo "<tr>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('remote_download')."</td>\n".
			"<td width=\"35%\" class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"fsettingsnew[$ename][rpid]\">".makeoption($rpidsarr,empty($setting['rpid']) ? 0 : $setting['rpid'])."</select></td>\n".
			"<td width=\"15%\" class=\"txtR\">".lang('downjumfilsty')."</td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fsettingsnew[$ename][jumpfile]\" value=\"".(empty($setting['jumpfile']) ? '' : mhtmlspecialchars($setting['jumpfile']))."\"></td>\n".
			"</tr>\n";
	}
}

function check_rule_urls(&$g){
	!$g['uurls'] && (!$g['uregular'] || !$g['ufromnum'] || !$g['utonum']) && amessage('uurls_or_uregular');
	$g['uspilit'] && $g['uurltag'] || amessage('uspilit_and_uurltag');
}

function check_rule_cnts(&$g){
}
?>

