<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('mcnode') || amessage('no_apermission');
load_cache('acatalogs,cotypes,grouptypes,matypes,mcnodes');
load_cache('mtpls',0);
$mcnvars = array('caid' => lang('catalog'));
foreach($cotypes as $k => $v) !$v['self_reg'] && $mcnvars['ccid'.$k] = $v['cname'];
foreach($grouptypes as $k => $v) !$v['issystem'] && $mcnvars['ugid'.$k] = $v['cname'];
$mcnvars['matid'] = lang('matype');
$mcnvars['mcnid'] = lang('customnode');
empty($action) && $action = 'mcnodesedit';
$url_type = 'mcnode';include 'urlsarr.inc.php';
if($action == 'mcnodesedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$keyword = empty($keyword) ? '' : $keyword;
	$mcnvar = empty($mcnvar)? '' : $mcnvar;
	$wheresql = '';
	$fromsql = "FROM {$tblprefix}mcnodes";
	
	$mcnvar && $wheresql .= ($wheresql ? ' AND ' : '')."mcnvar='$mcnvar'";
	$keyword && $wheresql .= ($wheresql ? ' AND ' : '')."(ename LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR alias LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
	$wheresql = $wheresql ? "WHERE $wheresql" : '';
	
	$filterstr = '';
	foreach(array('mcnvar','keyword',) as $k) $filterstr .= "&$k=".urlencode($$k);
	if(!submitcheck('bmcnodesedit')){
		url_nav(lang('mcnodeadmin'),$urlsarr,'mcnodesedit');
		echo form_str($actionid.'mcnodesedit',"?entry=$entry&action=$action&page=$page$param_suffix");
		tabheader_e();
		echo "<tr><td class=\"txt txtleft\">";
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"mcnvar\">".makeoption(array('' => lang('all').lang('nodetype')) + $mcnvars,$mcnvar)."</select>&nbsp; ";
		echo strbutton('bfilter','filter0');
		echo "</td></tr>";
		tabfooter();
		
		$addstr = "&nbsp; &nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage');
		tabheader(lang('mcnode_list').$addstr,'','',12);
		$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('cnode_name'),'txtL'),array(lang('nodetype').'-ID','txtL'),array(lang('look'),'txtL'),);
		for($i = 0;$i <= min($mcn_max_addno,2);$i ++) $cy_arr[] = $i ? lang('addp').$i.lang('template') : lang('index_tpl');
		$cy_arr[] = lang('detail');
		trcategory($cy_arr);
		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * $fromsql $wheresql ORDER BY cnid ASC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		while($cnode = $db->fetch_array($query)){
			view_mcnurl($cnode['ename'],$cnode);
			$lookstr = '';
			for($i = 0;$i <= $cnode['addnum'];$i ++) $lookstr .= "<a href=\"".$cnode['mcnurl'.($i ? $i : '')]."\" target=\"_blank\">".($i ? lang('add_p').$i : lang('index'))."</a>&nbsp; ";
			$tplsarr = explode(',',$cnode['tpls']);
			echo "<tr class=\"txt\"><td class=\"txtC\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$cnode[cnid]]\" value=\"$cnode[cnid]\">\n";
			echo "<td class=\"txtL\">$cnode[alias]</td>\n";
			echo "<td class=\"txtL\">".$mcnvars[$cnode['mcnvar']]."-".$cnode['mcnid']."</td>\n";
			echo "<td class=\"txtL\">$lookstr</td>\n";
			for($i = 0;$i <= min($mcn_max_addno,2);$i ++) echo "<td class=\"txtC\">".(empty($tplsarr[$i]) ? '-' : (empty($mtpls[@$tplsarr[$i]]['cname']) ? $tplsarr[$i] : $mtpls[@$tplsarr[$i]]['cname']))."</td>\n";
			echo "<td class=\"txtC\"><a href=\"?entry=$entry&action=mcnodedetail&cnid=$cnode[cnid]$param_suffix\" onclick=\"return floatwin('open_cnodedetail',this)\">".lang('edit')."</a></td></tr>\n";
		}
		tabfooter();
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		echo multi($counts,$atpp,$page,"?entry=$entry&action=$action$param_suffix$filterstr");

		tabheader(lang('operate_item'));
		trbasic(lang('operate_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[delete]\" value=\"1\">&nbsp;".lang('del_cnode'),'');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[addnum]\" value=\"1\">&nbsp;".lang('addnonum'),'cnaddnum');
		for($i = 0;$i <= $mcn_max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			$configstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[tpl$i]\" value=\"1\" title=\"".lang('needset')."\">&nbsp; <select id=\"cntpl$i\" name=\"cntpl$i\">".makeoption(array('' => lang('noset').lang('template')) + mtplsarr('marchive'),'')."</select>";
			$configstr .= "&nbsp; |&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[url$i]\" value=\"1\" title=\"".lang('needset')."\">&nbsp; <input type=\"text\" size=\"25\" id=\"cnurl$i\" name=\"cnurl$i\" value=\"\" title=\"".lang('staticfomart')."\">";
			$configstr .= "&nbsp; |&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[static$i]\" value=\"1\" title=\"".lang('needset')."\">&nbsp; <select id=\"cnstatic$i\" name=\"cnstatic$i\">".makeoption(array(0 => lang('staticsys'),1 => lang('keepdnc'),2 => lang('create_static')))."</select>";
			$configstr .= "&nbsp; |&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[period$i]\" value=\"1\" title=\"".lang('needset')."\">&nbsp; <input type=\"text\" size=\"5\" id=\"cnperiod$i\" name=\"cnperiod$i\" value=\"\" title=\"".lang('staticperiod')."\">";
			trbasic($pvar.lang('setting'),'',$configstr,'');
		}
		tabfooter('bmcnodesedit');
		a_guide('mcnodesedit');
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
			for($i = 0;$i <= $mcn_max_addno;$i ++) $parastr .= "&cntpl$i=".${'cntpl'.$i}."&cnurl$i=".${'cnurl'.$i}."&cnstatic$i=".${'cnstatic'.$i}."&cnperiod$i=".${'cnperiod'.$i};
			
			$selectid = array();
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
				for($i = 0;$i <= $r['addnum'];$i ++) m_unlink(m_parseurl(mcn_format($r['ename'],$i),array('addno' => $i)));
			}
			$db->query("DELETE $fromsql WHERE cnid ".multi_str($selectid), 'UNBUFFERED');
		}else{
			if(!empty($cndeal['addnum'])) $db->query("UPDATE {$tblprefix}mcnodes SET addnum='".min(empty($mcn_max_addno) ? 0 : $mcn_max_addno,max(0,intval($cnaddnum)))."' WHERE cnid ".multi_str($selectid));
			$tplarr = $urlarr = $staticarr = $periodarr = array();
			for($i = 0;$i <= $mcn_max_addno;$i ++){
				foreach(array('tpl','url','static','period',) as $var) if(!empty($cndeal[$var.$i])) ${$var.'arr'}[$i] = ${'cn'.$var.$i};
			}
			if($tplarr || $urlarr || $staticarr || $periodarr){
				$query = $db->query("SELECT * $fromsql WHERE cnid ".multi_str($selectid));
				while($cnode = $db->fetch_array($query)){
					if(!empty($cndeal['addnum'])) $cnode['addnum'] = min(empty($mcn_max_addno) ? 0 : $mcn_max_addno,max(0,intval($cnaddnum)));
					$sqlstr = '';
					foreach(array('tpl','url','static','period',) as $var){
						if(${$var.'arr'}){
							$vars = $var.'s';
							alter_cnode($cnode,${$var.'arr'},$vars);
							$sqlstr .= ($sqlstr ? ',' : '')."$vars='$cnode[$vars]'";
						}
					}
					$sqlstr && $db->query("UPDATE {$tblprefix}mcnodes SET $sqlstr WHERE cnid='$cnode[cnid]'");
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
				$transtr .= "&bmcnodesedit=1";
				$transtr .= "&fromid=$fromid";
				amessage('operating',"?entry=$entry&action=$action$param_suffix&page=$page$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=$entry&action=$action$param_suffix&page=$page$filterstr\">",'</a>');
			}
		}
		updatecache('mcnodes');
		adminlog(lang('cnode_admin_operate'),lang('cnode_list_admin'));
		amessage('cnodeoperatefinish',"?entry=$entry&action=$action&page=$page$param_suffix$filterstr");
	}
}elseif($action == 'mcnodeadd'){
	$mcnvar = empty($mcnodenew['mcnvar']) ? '' : $mcnodenew['mcnvar'];
	if(!submitcheck('bmcnodeadd')){
		url_nav(lang('mcnodeadmin'),$urlsarr,'mcnodeadd');
		tabheader(lang('addmcnode'),'mcnodeadd',"?entry=$entry&action=$action&mcnvar=$mcnvar$param_suffix",2);
		if(empty($mcnvar)){
			trbasic(lang('nodetype'),'mcnodenew[mcnvar]',makeoption($mcnvars),'select');
			tabfooter('baddpre',lang('continue'));
		}else{
			trbasic(lang('nodetype'),'',$mcnvars[$mcnvar],'');
			if($mcnvar == 'mcnid'){
				trbasic(lang('cnode_name'),'mcnodenew[alias]');
			}else{
				if($mcnvar == 'caid'){
					$arr = &$acatalogs;
					$tvar = 'title';
				}elseif($mcnvar == 'matid'){
					$arr = &$matypes;
					$tvar = 'cname';
				}elseif(in_str('ccid',$mcnvar)){
					$arr = read_cache('coclasses',str_replace('ccid','',$mcnvar));
					$tvar = 'title';
				}elseif(in_str('ugid',$mcnvar)){
					$arr = read_cache('usergroups',str_replace('ugid','',$mcnvar));
					$tvar = 'cname';
				}
				$narr = array();
				foreach($arr as $k => $v) if(empty($mcnodes[$mcnvar.'='.$k])) $narr[$k] = $v[$tvar].(isset($v['level']) ? '('.$v['level'].')' : '');
				trbasic(lang('choosenode')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallmcnids\" onclick=\"checkall(this.form,'mcnidsnew','chkallmcnids')\">".lang('selectall'),'',makecheckbox('mcnidsnew[]',$narr,array(),5),'');
			}
			trbasic(lang('addnonum'),'mcnodenew[addnum]');
			trhidden('mcnodenew[mcnvar]',$mcnvar);
			tabfooter('bmcnodeadd',lang('add'));
		}
	}else{
		$mcnodenew['addnum'] = min(empty($mcn_max_addno) ? 0 : $mcn_max_addno,max(0,intval($mcnodenew['addnum'])));
		if($mcnvar == 'mcnid'){
			if($mcnodenew['alias'] = trim(strip_tags($mcnodenew['alias']))){
				$db->query("INSERT INTO {$tblprefix}mcnodes SET alias='$mcnodenew[alias]',addnum='$mcnodenew[addnum]',mcnvar='$mcnvar'");
				if($cnid = $db->insert_id()) $db->query("UPDATE {$tblprefix}mcnodes SET mcnid='$cnid',ename='$mcnvar=$cnid' WHERE cnid=$cnid");
				updatecache('mcnodes');
			}
		}else{
			if(!empty($mcnidsnew)){
				foreach($mcnidsnew as $k){
					if($mcnvar == 'caid'){
						$arr = &$acatalogs;
						$tvar = 'title';
					}elseif($mcnvar == 'matid'){
						$arr = &$matypes;
						$tvar = 'cname';
					}elseif(in_str('ccid',$mcnvar)){
						$arr = read_cache('coclasses',str_replace('ccid','',$mcnvar));
						$tvar = 'title';
					}elseif(in_str('ugid',$mcnvar)){
						$arr = read_cache('usergroups',str_replace('ugid','',$mcnvar));
						$tvar = 'cname';
					}
					$db->query("INSERT INTO {$tblprefix}mcnodes SET alias='".$arr[$k][$tvar]."',addnum='$mcnodenew[addnum]',mcnid='$k',ename='$mcnvar=$k',mcnvar='$mcnvar'");
				}
				updatecache('mcnodes');
			}
		}
		amessage('addmcnodefin',axaction(6,"?entry=$entry&action=mcnodesedit$param_suffix"));
	}
}elseif($action == 'mcnodedetail' && $cnid){
	if(!$cnode = $db->fetch_one("SELECT * FROM {$tblprefix}mcnodes WHERE cnid='$cnid'")) amessage('pointcnode');
	foreach(array('tpls','urls','statics','periods',) as $var) ${$var.'arr'} = explode(',',$cnode[$var]);
	if(!submitcheck('bmcnodedetail')){
		tabheader(lang('cnodeadmin'),'mcnodedetail',"?entry=$entry&action=$action&cnid=$cnid$param_suffix",2);
		trbasic(lang('nodetype'),'',$mcnvars[$cnode['mcnvar']],'');
		trbasic(lang('cnode_name'),'mcnodenew[alias]',$cnode['alias']);
		trbasic(lang('cnode_url'),'mcnodenew[appurl]',$cnode['appurl'],'btext',lang('agappurl'));
		for($i = 0;$i <= $cnode['addnum'];$i ++){
			$pvar = $i ? lang('addp').$i : lang('index');
			trbasic($pvar.lang('template'),'tplsnew[]',makeoption(array('' => lang('noset')) + mtplsarr('marchive'),empty($tplsarr[$i]) ? '' : $tplsarr[$i]),'select');
			trbasic($pvar.lang('staticfomart'),'urlsnew[]',empty($urlsarr[$i]) ? '' : $urlsarr[$i],'btext',!$i ? lang('agmcnstaticfomart') : '');
			trbasic($pvar.lang('ifstatic'),"staticsnew[$i]",makeoption(array(0 => lang('staticsys'),1 => lang('keepdnc'),2 => lang('create_static')),empty($staticsarr[$i]) ? 0 : $staticsarr[$i]),'select');
			trbasic($pvar.lang('staticperiod'),'periodsnew[]',empty($periodsarr[$i]) ? '' : $periodsarr[$i],'text');
		}
		tabfooter('bmcnodedetail');
	}else{
		if(!($mcnodenew['alias'] = trim(strip_tags($mcnodenew['alias'])))) $mcnodenew['alias'] = $mcnode['alias'];
		$mcnodenew['appurl'] = trim($mcnodenew['appurl']);
		$sqlstr = "alias='$mcnodenew[alias]',appurl='$mcnodenew[appurl]'";
		foreach(array('tpls','urls','statics','periods',) as $var){
			$mcnodenew[$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
			$sqlstr .= ",$var='$mcnodenew[$var]'";
		}
		$db->query("UPDATE {$tblprefix}mcnodes SET $sqlstr WHERE cnid=$cnid");
		updatecache('mcnodes');
		amessage('editmcnodefin',axaction(6,"?entry=$entry&action=mcnodesedit$param_suffix"));
	}
}
function alter_cnode(&$cnode,$arr,$mode='tpls'){//用来设置多值字段的值
	$oarr = explode(',',$cnode[$mode]);
	$narr = array();
	for($i = 0;$i <= $cnode['addnum'];$i ++) $narr[$i] = isset($arr[$i]) ? $arr[$i] : (isset($oarr[$i]) ? $oarr[$i] : '');
	$cnode[$mode] = implode(',',$narr);
	
}
?>
