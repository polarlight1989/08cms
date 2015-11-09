<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('static') || amessage('no_apermission');
load_cache('cotypes,channels,currencys,permissions');
load_cache('catalogs,mtpls,cnodes',$sid);
cache_merge($channels,'channels',$sid);
include_once M_ROOT."./include/parse.fun.php";
$url_type = 'static';include 'urlsarr.inc.php';
if($action == 'archives') {
	$staticmode = empty($staticmode) ? 0 : max(0,intval($staticmode));
	$numperpic = empty($numperpic) ? 20 : min(500,max(20,intval($numperpic)));
	$caid = empty($caid) ? '0' : $caid;
	$chid = empty($chid) ? '0' : $chid;
	if(!isset($ptypestr)){
		$ptypes = empty($ptypes) ? array() : $ptypes;
		$ptypestr = implode(',',$ptypes);
	}else $ptypes = explode(',',$ptypestr);
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
	
	$fromsql = "FROM {$tblprefix}archives a";
	$wheresql = "WHERE a.sid=$sid AND a.checked='1'";
	if(!empty($caid)){
		if($cnsql = cnsql(0,cnsonids($caid,$catalogs),'a.')) $wheresql .= " AND $cnsql";
	}
	$chid && $wheresql .= " AND a.chid='$chid'";
	$indays && $wheresql .= " AND a.createdate>'".($timestamp - 86400 * $indays)."'";
	$outdays && $wheresql .= " AND a.createdate<'".($timestamp - 86400 * $outdays)."'";
	$filterstr = '';
	foreach(array('staticmode','numperpic','caid','chid','ptypestr','indays','outdays',) as $k){
		$filterstr .= "&$k=".rawurlencode($$k);
	}
	foreach($cotypes as $coid => $cotype){
		${"ccid$coid"} = isset(${"ccid$coid"}) ? ${"ccid$coid"} : 0;
		$filterstr .= "&ccid$coid=".${"ccid$coid"};
		if(!empty(${"ccid$coid"})){
			$coclasses = read_cache('coclasses',$coid);
			$ccids = cnsonids(${"ccid$coid"},$coclasses);
			if($cnsql = cnsql($coid,$ccids,'a.')) $wheresql .= " AND $cnsql";
		}
	}
	if(!submitcheck('barchives')){
		url_nav(lang('staticadmin'),$urlsarr,'archives');
		$ptypearr = array(0 => lang('arcconpage'));
		for($i = 1;$i <= $max_addno;$i++) $ptypearr[$i] = lang('archive_plus_page').$i;
		$staticarr = array('0' => lang('pascresta'),'1' => lang('actcresta'),'2' => lang('repstaurl'));
		$caidsarr = array('0' => lang('all_catalog')) + caidsarr($catalogs);
		$chidsarr = array('0' => lang('all_channel')) + chidsarr();
		tabheader(lang('crearcpagsta'),'archives',"?entry=$entry&action=$action$param_suffix");
		trbasic(lang('stacremo'),'',makeradio('staticmode',$staticarr,$staticmode),'');
		trbasic(lang('choarcpaty'),'',makecheckbox('ptypes[]',$ptypearr,$ptypes),'');
		trbasic(lang('numperpic20_500'),'numperpic',$numperpic);
		tabfooter();

		$filtercounts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		tabheader(lang('filarcpagcurarcamo').$filtercounts);
		tr_cns(lang('be_catalog'),'caid',$caid,$sid,0,0,lang('p_choose'),1);
		trbasic(lang('achannel'),'chid',makeoption($chidsarr,$chid),'select');
		foreach($cotypes as $coid => $cotype){
			tr_cns("$cotype[cname]","ccid$coid",${"ccid$coid"},$sid,$coid,0,lang('p_choose'),1);
		}
		trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"barchives\" value=\"".lang('submit')."\"> &nbsp; &nbsp;";
		echo "<input class=\"button\" type=\"submit\" name=\"bfilter\" value=\"".lang('filter0')."\"></form>";

	}elseif(submitcheck('barchives')){
		if(empty($ptypes)) amessage('choarcpaty',"?entry=$entry&action=$action$param_suffix$filterstr");
		$npage = empty($npage) ? 1 : $npage;
		if(empty($pages)){
			$nowcounts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$pages = @ceil($nowcounts / $numperpic);
		}
		if(empty($pages)) amessage('selectarchive',"?entry=$entry&action=$action$param_suffix$filterstr");
		$selectid = array();
		if($npage <= $pages){
			$fromstr = empty($fromid) ? "" : "a.aid<$fromid";
			$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
			$query = $db->query("SELECT aid $fromsql $nwheresql ORDER BY a.aid DESC LIMIT 0,$numperpic");
			while($item = $db->fetch_array($query)) $selectid[] = $item['aid'];
		}
		if(!$staticmode){
			include_once M_ROOT."./include/archive.cls.php";
			$arc = new cls_archive();
			foreach($selectid as $aid){
				$arc->arcid($aid);
				$arc->update_needstatic($ptypes);
			}
			unset($arc);
		}elseif($staticmode == 1){
			include_once M_ROOT."./include/archive.cls.php";
			include_once M_ROOT."./include/arc_static.fun.php";
			foreach($selectid as $aid){
				foreach($ptypes as $k) arc_static($aid,$k,0);
				$arc->update_needstatic($ptypes,1);
			}
			unset($arc);
		}elseif($staticmode == 2){//修复静态链接
			include_once M_ROOT."./include/arcedit.cls.php";
			$aedit = new cls_arcedit;
			foreach($selectid as $aid){
				$aedit->set_aid($aid);
				$aedit->set_arcurl();
				$aedit->init();
			}
			unset($aedit);
		}
		$npage ++;
		if($npage <= $pages){
			$fromid = min($selectid);
			$transtr = "&pages=$pages";
			$transtr .= "&npage=$npage";
			$transtr .= "&barchives=1";
			$transtr .= "&fromid=$fromid";
			amessage('operating',"?entry=$entry&action=$action$param_suffix$filterstr$transtr",$pages,$npage,"<a href=\"?entry=$entry&action=$action$filterstr\">",'</a>');
		}
		adminlog(lang('arcstaadm'),lang('arc_list_aoperate'));
		amessage('staopefin',"?entry=$entry&action=$action$param_suffix$filterstr");
	}
	a_guide('staticarchives');
}elseif($action == 'cnodes'){
	$staticmode = empty($staticmode) ? 0 : max(0,intval($staticmode));
	$numperpic = empty($numperpic) ? 20 : min(500,max(20,intval($numperpic)));
	$caid = max(0,intval(@$caid));
	$mainline = max(0,intval(@$mainline));
	$cnlevel = max(0,intval(@$cnlevel));
	if(!isset($ptypestr)){
		$ptypes = empty($ptypes) ? array() : $ptypes;
		$ptypestr = implode(',',$ptypes);
	}else $ptypes = explode(',',$ptypestr);

	$fromsql = "FROM {$tblprefix}cnodes";
	$wheresql = "WHERE sid=$sid AND inconfig=1";
	$mainline && $wheresql .= " AND mainline='$mainline'";
	$cnlevel && $wheresql .= " AND cnlevel='$cnlevel'";
	$caid && $wheresql .= " AND caid ".multi_str(cnsonids($caid,$catalogs));
	$filterstr = '';
	foreach(array('staticmode','ptypestr','numperpic','caid','mainline','cnlevel',) as $k) $filterstr .= "&$k=".rawurlencode($$k);
	if(!submitcheck('bcnodes')){
		url_nav(lang('staticadmin'),$urlsarr,'cnodes');
		tabheader(lang('crecatcnodpagsta'),'archives',"?entry=$entry&action=$action$param_suffix");
		trbasic(lang('stacremo'),'',makeradio('staticmode',array('0' => lang('pascresta'),'1' => lang('actcresta'),'2' => lang('repstaurl')),$staticmode),'');
		$ptypearr = array();
		for($i = 0;$i <= $cn_max_addno;$i ++) $ptypearr[$i] = $i ? lang('addp').$i : lang('index');
		trbasic(lang('choatpaty'),'',makecheckbox('ptypes[]',$ptypearr,$ptypes),'');
		trbasic(lang('numperpic20_500'),'numperpic',$numperpic);
		tabfooter();

		tabheader(lang('ficatcnocuo').$db->result_one("SELECT count(*) $fromsql $wheresql"));
		$mainlinearr = array('0' => lang('nolimit'),'ca' => lang('catalog'));
		foreach($cotypes as $k => $v) if($v['sortable']) $mainlinearr[$k] = $v['cname'];
		trbasic(lang('mainlinemode'),'mainline',makeoption($mainlinearr,$mainline),'select');
		tr_cns(lang('caid_attr'),'caid',$caid,$sid,0,0,lang('p_choose'),1);
		trbasic(lang('cnodelevelnum'),'cnlevel',makeoption(array('0'=>lang('nolimit'),'1'=>lang('topic'),'2'=>lang('level2'),'3'=>lang('level3'),'4'=>lang('level4')),$cnlevel),'select');
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bcnodes\" value=\"".lang('submit')."\"> &nbsp; &nbsp;";
		echo "<input class=\"button\" type=\"submit\" name=\"bfilter\" value=\"".lang('filter0')."\"></form>";
	}
	else{
		empty($ptypes) && amessage('chocatpagty',"?entry=$entry&action=$action$param_suffix$filterstr");
		$npage = empty($npage) ? 1 : $npage;
		if(empty($pages)) $pages = @ceil($db->result_one("SELECT count(*) $fromsql $wheresql") / $numperpic);
		if(empty($pages)) amessage('chocatcno','history.go(-1)');
		$selectid = $cnstrarr = array();
		if($npage <= $pages){
			$fromstr = empty($fromid) ? "" : "cnid<$fromid";
			$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
			$query = $db->query("SELECT cnid,ename $fromsql $nwheresql ORDER BY cnid DESC LIMIT 0,$numperpic");
			while($item = $db->fetch_array($query)){
				$selectid[] = $item['cnid'];
				$cnstrarr[] = $item['ename'];
			}
		}
		include_once M_ROOT."./include/cn_static.fun.php";
		if(!$staticmode){
			foreach($cnstrarr as $cnstr) cn_update_needstatic($cnstr,$sid,$ptypes);
		}elseif($staticmode == 1){
			foreach($cnstrarr as $cnstr){
				for($i = 0;$i <= $cn_max_addno;$i ++) in_array($i,$ptypes) && index_static($cnstr,$i,1);
			}
		}elseif($staticmode == 2){
			foreach($cnstrarr as $cnstr) cn_blank($cnstr,$sid,$ptypes);
		}
		$npage ++;
		if($npage <= $pages){
			$fromid = min($selectid);
			$transtr = "&pages=$pages";
			$transtr .= "&npage=$npage";
			$transtr .= "&bcnodes=1";
			$transtr .= "&fromid=$fromid";
			amessage('operating',"?entry=$entry&action=$action$param_suffix$filterstr$transtr",$pages,$npage,"<a href=\"?entry=$entry&action=$action$param_suffix$filterstr\">",'</a>');
		}
		adminlog(lang('catcnostaadm'),lang('cnliadmope'));
		amessage('catcnoopefin',"?entry=$entry&action=$action$param_suffix$filterstr");
	}
	a_guide('staticcnotes');
}elseif($action == 'index'){
	if(!submitcheck('bstaticindex')){
		url_nav(lang('staticadmin'),$urlsarr,'index');
		$staticarr = array('0' => lang('pascresta'),'1' => lang('actcresta'));
		!$sid && $staticarr['2'] = lang('cleolstfi');
		$sid && $staticarr['3'] = lang('repstaurl');
		tabheader(lang(($sid ? 'subsite' : 'msite').'_index_deal'),'staticindex',"?entry=$entry&action=$action$param_suffix");
		if(!$sid){
			$ptypearr = array('i' => lang('siteidx'),'m' => lang('mcnidx'));
			trbasic(lang('choidxtp'),'',makecheckbox('ptypes[]',$ptypearr,array()),'');
		}
		trbasic(lang('stacremo'),'',makeradio('staticmode',$staticarr,0),'');
		tabfooter('bstaticindex');
		a_guide('staticindex');

	}else{
		if(!$sid && empty($ptypes)) amessage('pchoidxtp',"?entry=$entry&action=$action$param_suffix");
		if($sid) $ptypes = array('i');
		if(in_array('i',$ptypes)){
			include_once M_ROOT."./include/cn_static.fun.php";
			if(empty($staticmode)){
				cn_update_needstatic('',$sid,0);
			}elseif($staticmode == 1){
				index_static('',0,1);
			}elseif($staticmode == 2){
				index_unstatic('',0,1);
			}elseif($staticmode == 3){
				$sid && cn_blank('',$sid,0);
			}
		}
		if(in_array('m',$ptypes)){
			include_once M_ROOT."./include/mcn_static.fun.php";
			if(empty($staticmode)){
				$db->query("UPDATE {$tblprefix}mconfigs SET value='$timestamp' WHERE varname='mcnneedstatic'");
			}elseif($staticmode == 1){
				mindex_static('',0,1);
			}elseif($staticmode == 2){
				mindex_unstatic('',0,1);
			}
		}
		adminlog(lang('indstaadm'));
		amessage('inddeafin',"?entry=$entry&action=$action$param_suffix");
	}
}elseif($action == 'mcnodes'){
	$staticmode = max(0,intval(@$staticmode));
	$numperpic = min(500,max(20,intval(@$numperpic)));
	$mcnvar = trim(@$mcnvar);
	if(!isset($ptypestr)){
		$ptypes = empty($ptypes) ? array() : $ptypes;
		$ptypestr = implode(',',$ptypes);
	}else $ptypes = explode(',',$ptypestr);

	$wheresql = $mcnvar ? "WHERE mcnvar='$mcnvar'" : '';
	$fromsql = "FROM {$tblprefix}mcnodes";
	$filterstr = '';
	foreach(array('staticmode','ptypestr','numperpic','mcnvar',) as $k) $filterstr .= "&$k=".rawurlencode($$k);
	if(!submitcheck('bcnodes')){
		url_nav(lang('staticadmin'),$urlsarr,'mcnodes');
		$staticarr = array('0' => lang('pascresta'),'1' => lang('actcresta'),'2' => lang('repstaurl'));
		$ptypearr = array();
		for($i = 0;$i <= $mcn_max_addno;$i ++) $ptypearr[$i] = $i ? lang('addp').$i : lang('index');

		tabheader(lang('crecatcnodpagsta'),'archives',"?entry=$entry&action=$action$param_suffix");
		trbasic(lang('stacremo'),'',makeradio('staticmode',$staticarr,$staticmode),'');
		trbasic(lang('choatpaty'),'',makecheckbox('ptypes[]',$ptypearr,$ptypes),'');
		trbasic(lang('numperpic20_500'),'numperpic',$numperpic);
		tabfooter();

		$filtercounts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		tabheader(lang('ficatcnocuo').$filtercounts);
		$mcnvars = array('' => lang('alltype'),'caid' => lang('catalog'));
		foreach($cotypes as $k => $v) !$v['self_reg'] && $mcnvars['ccid'.$k] = $v['cname'];
		foreach($grouptypes as $k => $v) !$v['issystem'] && $mcnvars['ugid'.$k] = $v['cname'];
		$mcnvars['matid'] = lang('matype');
		$mcnvars['mcnid'] = lang('customnode');
		trbasic(lang('nodetype'),'mcnvar',makeoption($mcnvars,$mcnvar),'select');
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bcnodes\" value=\"".lang('submit')."\"> &nbsp; &nbsp;";
		echo "<input class=\"button\" type=\"submit\" name=\"bfilter\" value=\"".lang('filter0')."\"></form>";
	}else{
		empty($ptypes) && amessage('chocatpagty',"?entry=$entry&action=$action$param_suffix$filterstr");
		$npage = empty($npage) ? 1 : $npage;
		if(empty($pages)){
			$nowcounts = $db->result_one("SELECT count(*) $fromsql $wheresql");
			$pages = @ceil($nowcounts / $numperpic);
		}
		if(empty($pages)) amessage('chocatcno','history.go(-1)');
		$selectid = $cnstrarr = array();
		if($npage <= $pages){
			$fromstr = empty($fromid) ? "" : "cnid<$fromid";
			$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
			$query = $db->query("SELECT cnid,ename $fromsql $nwheresql ORDER BY cnid DESC LIMIT 0,$numperpic");
			while($item = $db->fetch_array($query)){
				$selectid[] = $item['cnid'];
				$cnstrarr[] = $item['ename'];
			}
		}
		include_once M_ROOT."./include/mcn_static.fun.php";
		if(!$staticmode){
			foreach($cnstrarr as $cnstr) mcn_update_needstatic($cnstr,$ptypes);
		}elseif($staticmode == 1){
			foreach($cnstrarr as $cnstr){
				for($i = 0;$i <= $mcn_max_addno;$i ++) in_array($i,$ptypes) && mindex_static($cnstr,$i,1);
			}
		}elseif($staticmode == 2){
			foreach($cnstrarr as $cnstr) mcn_blank($cnstr,$ptypes);
		}
		$npage ++;
		if($npage <= $pages){
			$fromid = min($selectid);
			$transtr = "&pages=$pages";
			$transtr .= "&npage=$npage";
			$transtr .= "&bcnodes=1";
			$transtr .= "&fromid=$fromid";
			amessage('operating',"?entry=$entry&action=$action$param_suffix$filterstr$transtr",$pages,$npage,"<a href=\"?entry=$entry&action=$action$param_suffix$filterstr\">",'</a>');
		}
		adminlog(lang('catcnostaadm'),lang('cnliadmope'));
		amessage('catcnoopefin',"?entry=$entry&action=$action$param_suffix$filterstr");
	}
	a_guide('staticmcnodes');
}
?>
