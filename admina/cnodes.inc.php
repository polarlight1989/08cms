<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cnode') || amessage('no_apermission');
load_cache('cotypes');
load_cache('catalogs,mtpls,cnconfigs,',$sid);
include_once M_ROOT."./include/cnode.fun.php";
include_once M_ROOT."./include/cparse.fun.php";
include_once M_ROOT."./include/parse/general.php";
$url_type = 'cnode';include 'urlsarr.inc.php';
if($action == 'cnconfigs'){
	if(!submitcheck('bcnconfigs')){
		url_nav(lang('cnodeadmin'),$urlsarr,'cnconfigs');
		$addstr = "&nbsp; &nbsp; >><a href=\"?entry=$entry&action=cnconfigsadd$param_suffix\" onclick=\"return floatwin('open_cnodes',this)\">".lang('cnconfigsadd')."</a>";
		tabheader(lang('cnconfigadmin').$addstr,'cnodesupdate',"?entry=$entry&action=$action$param_suffix",3);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('config_name'),'txtL'),array(lang('catas_configs'),'txtL'),array(lang('mlclass'),'txtL'),lang('order'),lang('copy'),lang('edit')));
		$cnidsarr = array();
		foreach($cnconfigs as $k => $v){
			$configstr = '';
			$i = 0;
			$idsarr = cfgs2ids($v['configs'],$sid);
			foreach($v['configs'] as $k1 => $v1){
				$configstr .= ($configstr ? ' x ' : '').(!$k1 ? lang('catalog') : @$cotypes[$k1]['cname']).'('.count($idsarr[$k1]).')';
				!$i && $type = lang(!$k1 ? 'catalog' : @$cotypes[$k1]['cname']);
				!$i && $mlclassstr = fetch_mlclass($k1,$v['configs'][$k1]);
				$i ++;
			}
			$cnidsarr[$k] = $v['cname'].'('.$type.')';
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[]\" value=\"$k\"></td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"25\" maxlength=\"30\" name=\"cnconfigsnew[$k][cname]\" value=\"$v[cname]\"></td>\n".
				"<td class=\"txtL\">$configstr</td>\n".
				"<td class=\"txtL\">$mlclassstr</td>\n".
				"<td class=\"txtC w40\"><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"cnconfigsnew[$k][vieworder]\" value=\"$v[vieworder]\"></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=$entry&action=cnconfigdetail&cncid=$k$param_suffix&iscopy=1\" onclick=\"return floatwin('open_cnodes',this)\">".lang('copy')."</a></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=$entry&action=cnconfigdetail&cncid=$k$param_suffix\" onclick=\"return floatwin('open_cnodes',this)\">".lang('detail')."</a></td>\n".
				"</tr>\n";
		
		}
		tabfooter();
		tabheader(lang('operate_item'));
		$str = "<input class=\"radio\" type=\"radio\" name=\"arcdeal\" value=\"update\" checked><b>".lang('updatecnode')."</b> &nbsp;";
		$str .= "<input class=\"radio\" type=\"radio\" name=\"arcdeal\" value=\"delete\">".lang('delete').lang('cncfg')." &nbsp;";
		trbasic(lang('choose_item'),'',$str,'');
		$cnmodearr = array(0 => lang('cnopmode0'),1 => lang('cnopmode1'),2 => lang('cnopmode2'),);
		trbasic("<input class=\"radio\" type=\"radio\" name=\"arcdeal\" value=\"ccid0\">&nbsp;".lang('partop').lang('catalog'),'',multiselect('cnccids0[]',caidsarr($catalogs),array(),'30%').
		"&nbsp; &nbsp; <select id=\"cnmode0\" name=\"cnmode0\" style=\"vertical-align: top;\">".makeoption($cnmodearr)."</select>",'',lang('agpartop'));
		foreach($cotypes as $k => $v){
			if($v['sortable']){
				trbasic("<input class=\"radio\" type=\"radio\" name=\"arcdeal\" value=\"ccid$k\">&nbsp;".lang('partop').$v['cname'],'',multiselect('cnccids'.$k.'[]',ccidsarr($k),array(),'30%').
				"&nbsp; &nbsp; <select id=\"cnmode$k\" name=\"cnmode$k\" style=\"vertical-align: top;\">".makeoption($cnmodearr)."</select>",'',lang('agpartop'));
			}
		}
		tabfooter('bcnconfigs');
		a_guide('cnconfigs');
	}else{
		if(!empty($cnconfigsnew)){
			foreach($cnconfigsnew as $k => $v){
				$v['cname'] = trim(strip_tags($v['cname']));
				!$v['cname'] && $v['cname'] = $cnconfigs[$k]['cname'];
				$v['vieworder'] = max(0,intval($v['vieworder']));
				$db->query("UPDATE {$tblprefix}cnconfigs SET cname = '$v[cname]',vieworder='$v[vieworder]' WHERE cncid='$k'",'SILENT');
			}
			updatecache('cnconfigs','',$sid);
		}
		if(!empty($arcdeal)){
			if($arcdeal == 'delete'){
				if(!empty($selectid)){
					foreach($selectid as $k){
						$db->query("UPDATE {$tblprefix}cnodes SET cncids = REPLACE(cncids,'|$k|','') WHERE sid='$sid' AND cncids LIKE '%|$k|%'");
						$db->query("DELETE FROM {$tblprefix}cnconfigs WHERE cncid=$k");
						unset($cnconfigsnew[$k]);
					}
					$db->query("UPDATE {$tblprefix}cnodes SET inconfig=0 WHERE sid='$sid' AND inconfig='1' AND cncids=''");
					updatecache('cnodes',0,$sid);
					updatecache('cnconfigs','',$sid);
				}
			}elseif($arcdeal == 'update'){
				if(empty($selectid) && empty($selectstr)) amessage(lang('choosecnconfig'),"?entry=$entry&action=$action$param_suffix");
				if(empty($selectid)) $selectid = array_filter(explode(',',$selectstr));
				$pages = max(empty($pages) ? 0 : max(0,intval($pages)),count($selectid));
				$cncid = $selectid[0];
				$db->query("UPDATE {$tblprefix}cnodes SET cncids = REPLACE(cncids,'|$cncid|','') WHERE sid='$sid' AND cncids LIKE '%|$cncid|%'");
				if($cnconfig = $cnconfigs[$cncid]){
					cnodesfromcnc($cnconfig,$sid);
					$db->query("UPDATE {$tblprefix}cnodes SET inconfig=0 WHERE sid='$sid' AND inconfig='1' AND cncids=''");
					$db->query("UPDATE {$tblprefix}cnodes SET inconfig=1 WHERE sid='$sid' AND inconfig='0' AND cncids !=''");
					updatecache('cnodes',0,$sid);
				}
				unset($selectid[0]);
				if($selectid){
					$selectstr = implode(',',$selectid);
					$npage = $pages - count($selectid) + 1;
					amessage('operating',"?entry=$entry&action=$action&selectstr=$selectstr&arcdeal=$arcdeal&pages=$pages&bcnconfigs=1$param_suffix",$pages,$npage,"<a href=\"?entry=$entry&action=$action$param_suffix\">",'</a>');
				}
			}elseif(in_str('ccid',$arcdeal)){//更新结构但不更新节点
				if(!empty($selectid)){
					$coid = intval(str_replace('ccid','',$arcdeal));
					${"cnccids$coid"} = empty(${"cnccids$coid"}) ? array() : ${"cnccids$coid"};
					${"cnmode$coid"} = empty(${"cnmode$coid"}) ? 0 : ${"cnmode$coid"};
					foreach($selectid as $k) modify_cnconfig(@$cnconfigs[$k],$coid,${"cnccids$coid"},${"cnmode$coid"});
					updatecache('cnconfigs','',$sid);
				}
				
			}
		}
		adminlog(lang('update_catas_cnode'));
		amessage('ccnodeupdatefinish', "?entry=$entry&action=$action$param_suffix");
		
	}
}elseif($action == 'cnconfigdetail' && $cncid){
	$iscopy = empty($iscopy) ? 0 : 1;
	$cnconfig = &$cnconfigs[$cncid];
	$configs = &$cnconfig['configs'];
	if(!submitcheck('bcnconfigdetail')){
		tabheader(lang('edit').lang('cncfg'),'cnconfigdetail',"?entry=$entry&action=$action".($iscopy ? '&iscopy=1' : '')."$param_suffix&cncid=$cncid");
		trbasic(lang('config_name'),'cnconfignew[cname]',$cnconfig['cname'].($iscopy ? '-'.lang('copy0') : ''));
		$modearr = array(0 => lang('allcoclass'),1 => lang('all_topic_catas'),2 => lang('all_1_catas'),3 => lang('all_2_catas'),4 => lang('all_3_catas'),-1 => lang('handpoint'));
		$nomodearr = array(0 => lang('nosetting'),1 => lang('handpoint'));
		$i = 1;
		foreach($configs as $k => $v){
			$arr = $k ? read_cache('coclasses',$k) : $catalogs;
			foreach($arr as $x => $y) $arr[$x] = $y['title'].'('.$y['level'].')';
			$cname = $k ? $cotypes[$k]['cname'] : lang('catalog');
			sourcemodule("$i.".lang('incbelow').$cname.
				"<br><input class=\"checkbox\" type=\"checkbox\" name=\"configsnew[$k][son]\" value=\"1\"".(empty($v['son']) ? "" : " checked").">".lang('include_son'),
				"configsnew[$k][mode]",
				$modearr,
				empty($v['mode']) ? 0 : $v['mode'],
				-1,
				"configsnew[$k][ids][]",
				$arr,
				empty($v['ids']) ? array() : explode(',',$v['ids']),
				'25%',1,'',1
			);
			sourcemodule("$i.".lang('nobelow').$cname.
				"<br><input class=\"checkbox\" type=\"checkbox\" name=\"configsnew[$k][noson]\" value=\"1\"".(empty($v['noson']) ? "" : " checked").">".lang('include_son'),
				"configsnew[$k][nomode]",
				$nomodearr,
				empty($v['noids']) ? 0 : 1,
				1,
				"configsnew[$k][noids][]",
				$arr,
				empty($v['noids']) ? array() : explode(',',$v['noids']),
				'25%',1,'',1
			);
			$i ++;
		}
		tabfooter();
		
		tabheader(lang('cncfgtpl'));
		foreach(array('tpls','wtpls','urls','statics','periods',) as $var) ${$var.'arr'} = explode(',',$cnconfig[$var]);
		$modearr = array(0 => lang('tplsmode0'),1 => lang('tplsmode1'),2 => lang('tplsmode2'),);
		for($i = 0;$i <= $cn_max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			trbasic($pvar.lang('template'),'cfgnew[tpls][]',makeoption(array('' => lang('noset')) + mtplsarr('cindex'),empty($tplsarr[$i]) ? '' : $tplsarr[$i]),'select');
		}
		trbasic(lang('tplsmode'),'',makeradio('cfgnew[tplsmode]',$modearr,$cnconfig['tplsmode']),'');
		for($i = 0;$i <= $cn_max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			trbasic($pvar.lang('wtemplate'),'cfgnew[wtpls][]',makeoption(array('' => lang('noset')) + mtplsarr('wap'),empty($wtplsarr[$i]) ? '' : $wtplsarr[$i]),'select');
		}
		trbasic(lang('tplsmode'),'',makeradio('cfgnew[wtplsmode]',$modearr,$cnconfig['wtplsmode']),'');
		for($i = 0;$i <= $cn_max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			trbasic($pvar.lang('staticfomart'),'cfgnew[urls][]',empty($urlsarr[$i]) ? '' : $urlsarr[$i],'btext',!$i ? lang('agcnstaticfomart') : '');
		}
		trbasic(lang('tplsmode'),'',makeradio('cfgnew[urlsmode]',$modearr,$cnconfig['urlsmode']),'');
		for($i = 0;$i <= $cn_max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			trbasic($pvar.lang('ifstatic'),"cfgnew[statics][$i]",makeoption(array(0 => lang('staticsys'),1 => lang('keepdnc'),2 => lang('create_static')),empty($staticsarr[$i]) ? 0 : $staticsarr[$i]),'select');
		}
		trbasic(lang('tplsmode'),'',makeradio('cfgnew[staticsmode]',$modearr,$cnconfig['staticsmode']),'');
		for($i = 0;$i <= $cn_max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			trbasic($pvar.lang('staticperiod'),'cfgnew[periods][]',empty($periodsarr[$i]) ? '' : $periodsarr[$i],'text');
		}
		trbasic(lang('tplsmode'),'',makeradio('cfgnew[periodsmode]',$modearr,$cnconfig['periodsmode']),'');
		tabfooter('bcnconfigdetail');
	}else{
		if(!empty($configsnew)){
			$mainline = -1;
			foreach($configsnew as $k => $v){
				$mainline < 0 && $mainline = $k;
				foreach(array('ids','noids') as $var) $configsnew[$k][$var] = $configsnew[$k][$var][0];
				foreach(array('son','noson') as $var) $configsnew[$k][$var] = empty($configsnew[$k][$var]) ? 0 : 1;
				if(empty($configsnew[$k]['nomode'])) $configsnew[$k]['noids'] = '';
				unset($configsnew[$k]['nomode']);
			}
			if(!$configsnew) amessage('datamissing',M_REFERER);
			$cnlevel = count($configsnew);
			$configsnew = empty($configsnew) ? '' : addslashes(serialize($configsnew));
			$cnconfignew['cname'] = trim(strip_tags($cnconfignew['cname']));
			!$cnconfignew['cname'] && $cnconfignew['cname'] = $cnconfig['cname'];
			$sqlstr = '';
			foreach(array('tpls','wtpls','urls','statics','periods',) as $var){
				$cfgnew[$var] = empty($cfgnew[$var]) ? '' : implode(',',$cfgnew[$var]);
				$varm = $var.'mode';
				$sqlstr .= ",$var='$cfgnew[$var]',$varm='$cfgnew[$varm]'";
			}
			if($iscopy){
				$db->query("INSERT INTO {$tblprefix}cnconfigs SET cname='$cnconfignew[cname]',configs='$configsnew',level='$cnlevel',mainline='$mainline',sid='$sid'");
			}else{
				$db->query("UPDATE {$tblprefix}cnconfigs SET cname='$cnconfignew[cname]',configs='$configsnew',level='$cnlevel',mainline='$mainline' $sqlstr WHERE cncid=$cncid");
			}
		}
		adminlog(lang('edit_catas_configs'));
		updatecache('cnconfigs','',$sid);
		amessage('cncfgeditfin',axaction(6,"?entry=$entry&action=cnconfigs$param_suffix"));
	}
}elseif($action == 'cnconfigsadd'){
	if(!submitcheck('bcnconfigsadd')){
		tabheader(lang('node_step1'),'cnconfigsadd',"?entry=$entry&action=$action$param_suffix");
		trbasic(lang('catas_cdescription'),'cname','','btext');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"cnactor[0]\" value=\"1\">".lang('catalog'),'',"<input type=\"text\" size=\"5\" name=\"cnorder[0]\" value=\"\">",'',lang('agcncorder'));
		foreach($cotypes as $k => $v){
			if($v['sortable']){
				trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"cnactor[$k]\" value=\"1\">".$v['cname'],'',"<input type=\"text\" size=\"5\" name=\"cnorder[$k]\" value=\"\">",'',lang('agcncorder'));
			}
		}
		tabfooter('bcnconfigsadd',lang('add'));
		a_guide('cnconfigs');
	}else{
		if(!$cname = trim($cname)) amessage('datamissing',M_REFERER);
		foreach($cnorder as $k => $v) $cnorder[$k] = max(0,intval($v));
		asort($cnorder);
		$configs = array();
		$mainline = -1;
		foreach($cnorder as $k => $v){
			if(!isset($configs[$k]) && !empty($cnactor[$k])){
				$mainline < 0 && $mainline = $k;
				$configs[$k] = array('mode' => '-1','ids' => '','son' => '0','noids' => '','noson' => '0',);
			}
		}
		if(!$configs) amessage('datamissing',M_REFERER);
		$cnlevel = count($configs);
		$configs = addslashes(serialize($configs));
		$db->query("INSERT INTO {$tblprefix}cnconfigs SET cname='$cname',configs='$configs',level='$cnlevel',mainline='$mainline',sid='$sid'");
		adminlog(lang('add_catas_configs'));
		updatecache('cnconfigs','',$sid);
		amessage('cconfigsaddfinish',axaction(6,"?entry=$entry&action=cnconfigs$param_suffix"));
	}
}elseif($action == 'cnodescommon'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : $viewdetail;
	$keyword = empty($keyword) ? '' : $keyword;
	$caid = !isset($caid)? '0' : $caid;
	$mainline = !isset($mainline)? '-1' : $mainline;
	$cnlevel = !isset($cnlevel) ? '0' : $cnlevel;
	$inconfig = !isset($inconfig)? '-1' : $inconfig;
	
	$fromsql = "FROM {$tblprefix}cnodes";
	$wheresql = "sid=$sid";
	$mainline != -1 && $wheresql .= " AND mainline='$mainline'";
	$cnlevel && $wheresql .= " AND cnlevel='$cnlevel'";
	!empty($caid) && $wheresql .= " AND caid ".multi_str(cnsonids($caid,$catalogs));
	$keyword && $wheresql .= " AND ename LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	$inconfig != '-1' && $wheresql .= " AND inconfig='$inconfig'";
	$filterstr = '';
	foreach(array('keyword','viewdetail','caid','mainline','cnlevel','inconfig') as $k) $filterstr .= "&$k=".urlencode($$k);

	foreach($cotypes as $coid => $cotype){
		if($cotype['sortable']){
			${"ccid$coid"} = isset(${"ccid$coid"}) ? ${"ccid$coid"} : 0;
			if(!empty(${"ccid$coid"})){
				$filterstr .= "&ccid$coid=".${"ccid$coid"};
				$wheresql .= " AND ename REGEXP 'ccid$coid=".${"ccid$coid"}."(&|$)'";
			}
		}
	}

	$wheresql = $wheresql ? ("WHERE ".$wheresql) : "";

	if(!submitcheck('bcnodescommon')){
		url_nav(lang('cnodeadmin'),$urlsarr,'cnodescommon');
		
		echo form_str('cnodescommon',"?entry=$entry&action=$action$param_suffix&page=$page");
		tabheader_e();
		echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
		//关键词固定显示
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
		echo "<select name=\"caid\">".makeoption(array('0' => lang('catalog_attr')) + caidsarr($catalogs),$caid)."</select>&nbsp; ";
		echo "<select name=\"cnlevel\">".makeoption(array('0'=>lang('cnodelevelnum'),'1'=>lang('acrossleve1'),'2'=>lang('acrossleve2'),'3'=>lang('acrossleve3'),'4'=>lang('acrossleve4')),$cnlevel)."</select>&nbsp; ";
		echo strbutton('bfilter','filter0').viewcheck('viewdetail',$viewdetail,'tbodyfilter');
		echo "</td></tr>";
		echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
		$mainlinearr = array('-1' => lang('nolimit'),'0' => lang('catalog'));
		foreach($cotypes as $k => $v) if($v['sortable']) $mainlinearr[$k] = $v['cname'];
		trbasic(lang('mainline'),'',makeradio('mainline',$mainlinearr,$mainline),'');
		foreach($cotypes as $k => $v){
			if($v['sortable']) trbasic("$v[cname]","ccid$k",makeoption(array('0' => lang('nolimit')) + ccidsarr($k),${"ccid$k"}),'select');
		}
		trbasic(lang('is_outconfig_cnode'),'',makeradio('inconfig',array('-1' => lang('nolimit'),'0' => lang('outconfig_cnode'),'1' => lang('inconfig_cnode'),),$inconfig),'');
		echo "</tbody>";
		tabfooter();
		
		tabheader(lang('catas_cnode_list')."&nbsp; &nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',12);
		$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('cnode_name'),'txtL'),array(lang('cnode_alias'),'txtL'),lang('outconfig'),array(lang('catalog_attr'),'txtL'),array(lang('look'),'txtL'),);
		for($i = 0;$i <= min($cn_max_addno,2);$i ++) $cy_arr[] = $i ? lang('addp').$i.lang('template') : lang('index_tpl');
		$cy_arr[] = lang('detail');
		trcategory($cy_arr);
		
		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * $fromsql $wheresql ORDER BY cnid ASC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		while($cnode = $db->fetch_array($query)) {
			$cnode['catalog'] = empty($cnode['caid']) ? '-' : $catalogs[$cnode['caid']]['title'];
			$cnode['inconfig'] = $cnode['inconfig'] ? '-' : lang('outconfig');
			view_cnurl($cnode['ename'],$cnode);
			$cnode['cname'] = cnode_cname($cnode['ename']);
			$lookstr = '';
			for($i = 0;$i <= $cnode['addnum'];$i ++) $lookstr .= "<a href=\"".$cnode['indexurl'.($i ? $i : '')]."\" target=\"_blank\">".($i ? lang('add_p').$i : lang('index'))."</a>&nbsp; ";
			$tplsarr = explode(',',$cnode['tpls']);
			echo "<tr class=\"txt\"><td class=\"txtC\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$cnode[cnid]]\" value=\"$cnode[cnid]\">\n";
			echo "<td class=\"txtL\">$cnode[cname]</td>\n";
			echo "<td class=\"txtL\">".(empty($cnode['alias']) ? '-' : $cnode['alias'])."</td>\n";
			echo "<td class=\"txtC\">$cnode[inconfig]</td>\n";
			echo "<td class=\"txtL\">$cnode[catalog]</td>\n";
			echo "<td class=\"txtL\">$lookstr</td>\n";
			for($i = 0;$i <= min($cn_max_addno,2);$i ++) echo "<td class=\"txtC\">".(empty($tplsarr[$i]) ? '-' : (empty($mtpls[@$tplsarr[$i]]['cname']) ? $tplsarr[$i] : $mtpls[@$tplsarr[$i]]['cname']))."</td>\n";
			echo "<td class=\"txtC\"><a href=\"?entry=$entry&action=cnodedetail&cnid=$cnode[cnid]$param_suffix\" onclick=\"return floatwin('open_cnodedetail',this)\">".lang('edit')."</a></td></tr>\n";
		}
		tabfooter();
		echo multi($db->result_one("SELECT count(*) $fromsql $wheresql"), $atpp, $page, "?entry=$entry&action=$action$param_suffix$filterstr");

		tabheader(lang('operate_item'));
		trbasic(lang('operate_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[delete]\" value=\"1\">&nbsp;".lang('del_cnode'),'');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[addnum]\" value=\"1\">&nbsp;".lang('addnonum'),'cnaddnum');
		for($i = 0;$i <= $cn_max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			$configstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[tpl$i]\" value=\"1\" title=\"".lang('needset')."\">&nbsp;<select id=\"cntpl$i\" name=\"cntpl$i\">".makeoption(array('' => lang('noset').lang('template')) + mtplsarr('cindex'),'')."</select>";
			$configstr .= "&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[wtpl$i]\" value=\"1\" title=\"".lang('needset')."\">&nbsp;<select id=\"cnwtpl$i\" name=\"cnwtpl$i\">".makeoption(array('' => lang('noset').lang('wtemplate')) + mtplsarr('wap'),'')."</select>";
			$configstr .= "&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[url$i]\" value=\"1\" title=\"".lang('needset')."\">&nbsp;<input type=\"text\" size=\"20\" id=\"cnurl$i\" name=\"cnurl$i\" value=\"\" title=\"".lang('staticfomart')."\">";
			$configstr .= "&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[static$i]\" value=\"1\" title=\"".lang('needset')."\">&nbsp;<select id=\"cnstatic$i\" name=\"cnstatic$i\">".makeoption(array(0 => lang('staticsys'),1 => lang('keepdnc'),2 => lang('create_static')))."</select>";
			$configstr .= "&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[period$i]\" value=\"1\" title=\"".lang('needset')."\">&nbsp;<input type=\"text\" size=\"2\" id=\"cnperiod$i\" name=\"cnperiod$i\" value=\"\" title=\"".lang('staticperiod')."\">";
			trbasic($pvar.lang('setting'),'',$configstr,'');
		}
		tabfooter('bcnodescommon');
		a_guide('cnodesedit');
	}
	else{
		if(empty($cndeal) && empty($dealstr)) amessage('selectoperateitem',"?entry=$entry&action=$action$param_suffix&page=$page$filterstr");
		if(empty($selectid) && empty($select_all)) amessage('selectcnode',"?entry=$entry&action=$action$param_suffix&page=$page$filterstr");
		if(!empty($select_all)){
			if(empty($dealstr)){
				$dealstr = implode(',',array_keys(array_filter($cndeal)));
			}else{
				$cndeal = array();
				foreach(array_filter(explode(',',$dealstr)) as $k) $cndeal[$k] = 1;
			}

			$parastr = "";
			foreach(array('cnaddnum',) as $k) $parastr .= "&$k=".$$k;
			for($i = 0;$i <= $cn_max_addno;$i ++) $parastr .= "&cntpl$i=".${'cntpl'.$i}."&cnwtpl$i=".${'cnwtpl'.$i}."&cnurl$i=".${'cnurl'.$i}."&cnstatic$i=".${'cnstatic'.$i}."&cnperiod$i=".${'cnperiod'.$i};
			
			$selectid = $cnstrarr = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)) $pages = @ceil($db->result_one("SELECT count(*) $fromsql $wheresql") / $atpp);
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "cnid>$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT cnid,ename $fromsql $nwheresql ORDER BY cnid ASC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)) $selectid[] = $item['cnid'];
			}
			if(empty($selectid)) amessage('selectcnode',"?entry=$entry&action=$action$param_suffix&page=$page$filterstr");

		}

		if(!empty($cndeal['delete'])){
			$query = $db->query("SELECT * $fromsql WHERE cnid ".multi_str($selectid));
			while($r = $db->fetch_array($query)){
				for($i = 0;$i <= $r['addnum'];$i ++) m_unlink(m_parseurl(cn_format($r['ename'],$i,$r),array('addno' => $i)));
			}
			$db->query("DELETE $fromsql WHERE cnid ".multi_str($selectid), 'UNBUFFERED');
		}else{
			if(!empty($cndeal['addnum'])) $db->query("UPDATE {$tblprefix}cnodes SET addnum='".min(empty($cn_max_addno) ? 0 : $cn_max_addno,max(0,intval($cnaddnum)))."' WHERE cnid ".multi_str($selectid));
			$tplarr = $wtplarr = $urlarr = $staticarr = $periodarr = array();
			for($i = 0;$i <= $cn_max_addno;$i ++){
				foreach(array('tpl','wtpl','url','static','period',) as $var) if(!empty($cndeal[$var.$i])) ${$var.'arr'}[$i] = ${'cn'.$var.$i};
			}
			if($tplarr || $wtplarr || $urlarr || $staticarr || $periodarr){
				$query = $db->query("SELECT * $fromsql WHERE cnid ".multi_str($selectid));
				while($cnode = $db->fetch_array($query)){
					if(!empty($cndeal['addnum'])) $cnode['addnum'] = min(empty($cn_max_addno) ? 0 : $cn_max_addno,max(0,intval($cnaddnum)));
					$sqlstr = '';
					foreach(array('tpl','wtpl','url','static','period',) as $var){
						if(${$var.'arr'}){
							$vars = $var.'s';
							alter_cnode($cnode,${$var.'arr'},$vars);
							$sqlstr .= ($sqlstr ? ',' : '')."$vars='$cnode[$vars]'";
						}
					}
					$sqlstr && $db->query("UPDATE {$tblprefix}cnodes SET $sqlstr WHERE cnid='$cnode[cnid]'");
				}
			}
		}
		if(!empty($select_all)){
			$npage ++;
			if($npage <= $pages){
				$fromid = max($selectid);
				$transtr = '';
				$transtr .= "&select_all=1";
				$transtr .= "&pages=$pages";
				$transtr .= "&npage=$npage";
				$transtr .= "&bcnodescommon=1";
				$transtr .= "&fromid=$fromid";
				amessage('operating',"?entry=$entry&action=$action$param_suffix&page=$page$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=$entry&action=$action$param_suffix&page=$page$filterstr\">",'</a>');
			}
		}
		updatecache('cnodes','',$sid);
		adminlog(lang('cnode_admin_operate'),lang('cnode_list_admin'));
		amessage('cnodeoperatefinish',"?entry=$entry&action=$action&page=$page$param_suffix$filterstr");
	}
}elseif($action == 'cnodedetail' && $cnid){
	$forward = empty($forward) ? M_REFERER : $forward;
	$cnode = $db->fetch_one("SELECT * FROM {$tblprefix}cnodes WHERE cnid=$cnid");
	foreach(array('tpls','wtpls','urls','statics','periods',) as $var) ${$var.'arr'} = explode(',',$cnode[$var]);
	if(!submitcheck('bcnodedetail')){
		tabheader(lang('cnode_detail_set'),'cnodedetail',"?entry=$entry&action=$action$param_suffix&cnid=$cnid&forward=".urlencode($forward));
		trbasic(lang('cnode_name'),'',cnode_cname($cnode['ename']),'');
		trbasic(lang('cnode_alias'),'cnodenew[alias]',$cnode['alias']);
		trbasic(lang('cnode_url'),'cnodenew[appurl]',$cnode['appurl'],'btext',lang('agappurl'));
		for($i = 0;$i <= $cnode['addnum'];$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			trbasic($pvar.lang('template'),'tplsnew[]',makeoption(array('' => lang('noset')) + mtplsarr('cindex'),empty($tplsarr[$i]) ? '' : $tplsarr[$i]),'select');
			trbasic($pvar.lang('wtemplate'),'wtplsnew[]',makeoption(array('' => lang('noset')) + mtplsarr('wap'),empty($wtplsarr[$i]) ? '' : $wtplsarr[$i]),'select');
			trbasic($pvar.lang('staticfomart'),'urlsnew[]',empty($urlsarr[$i]) ? '' : $urlsarr[$i],'btext',!$i ? lang('agcnstaticfomart') : '');
			trbasic($pvar.lang('ifstatic'),"staticsnew[$i]",makeoption(array(0 => lang('staticsys'),1 => lang('keepdnc'),2 => lang('create_static')),empty($staticsarr[$i]) ? 0 : $staticsarr[$i]),'select');
			trbasic($pvar.lang('staticperiod'),'periodsnew[]',empty($periodsarr[$i]) ? '' : $periodsarr[$i],'text');
		}
		tabfooter('bcnodedetail');
		a_guide('cnodedetail');
	}
	else{
		$cnodenew['alias'] = trim(strip_tags($cnodenew['alias']));
		$cnodenew['appurl'] = trim($cnodenew['appurl']);
		$sqlstr = "alias='$cnodenew[alias]',appurl='$cnodenew[appurl]'";
		foreach(array('tpls','wtpls','urls','statics','periods',) as $var){
			$cnodenew[$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
			$sqlstr .= ",$var='$cnodenew[$var]'";
		}
		$db->query("UPDATE {$tblprefix}cnodes SET $sqlstr WHERE cnid=$cnid");
		adminlog(lang('detail_catas_cnode'));
		updatecache('cnodes','',$sid);
		amessage('cnodesetfinish',axaction(6,$forward));
	}
}
?>
