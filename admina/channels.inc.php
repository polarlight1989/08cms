<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('channel') || amessage('no_apermission');
load_cache('initfields,rprojects,commus,cotypes,permissions,inurls,inmurls');
$channels = fetch_arr();
sys_cache('fieldwords');
load_cache('mtpls',$sid);
cache_merge($channels,'channels',$sid);
include_once M_ROOT."./include/fields.fun.php";
include_once M_ROOT."./include/commu.fun.php";
if($action == 'channeledit'){
	if(!$sid){
		$url_type = 'channel';include 'urlsarr.inc.php';
		url_nav(lang('achannel'),$urlsarr,'channel');
	}
	if(!submitcheck('bchanneledit')){
		$cuidsarr = cuidsarr('answer') + cuidsarr('purchase');
		tabheader(lang('channel_manager').($sid ? '' : "&nbsp; &nbsp; >><a href=\"?entry=channels&action=channeladd\">".lang('add')."</a>"),'channeledit',"?entry=channels&action=channeledit$param_suffix",'10');
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('id'),array(lang('channel_name'),'txtL'),array(lang('remark'),'txtL'),lang('available'),lang('order'),lang('ut_commu'),lang('admin'),lang('album'),lang('delete'),lang('copy'),lang('edit')));
		foreach($channels as $k => $channel){
			$cuidstr = empty($cuidsarr[$channel['cuid']]) ? '-' : $cuidsarr[$channel['cuid']];
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w30\">".($sid ? '-' : "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$k]\" value=\"$k\">")."</td>\n".
				"<td class=\"txtC w30\">$k</td>\n".
				"<td class=\"txtL\">".($sid ? "$channel[cname]" : "<input type=\"text\" size=\"15\" maxlength=\"30\" name=\"channelnew[$k][cname]\" value=\"$channel[cname]\">")."</td>\n".
				"<td class=\"txtL\">".($sid ? "$channel[remark]" : "<input type=\"text\" size=\"30\" maxlength=\"30\" name=\"channelnew[$k][remark]\" value=\"$channel[remark]\">")."</td>\n".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"channelnew[$k][available]\" value=\"1\"".($channel['available'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w40\">".($sid ? "$channel[vieworder]" : "<input type=\"text\" size=\"4\" maxlength=\"4\" name=\"channelnew[$k][vieworder]\" value=\"$channel[vieworder]\">")."</td>\n".
				"<td class=\"txtC\">$cuidstr</td>\n".
				"<td class=\"txtC w30\">".(empty($channel['userforbidadd']) ? '-' : 'Y')."</td>\n".
				"<td class=\"txtC w30\">".(empty($channel['isalbum']) ? '-' : 'Y')."</td>\n".
				"<td class=\"txtC w30\">".($sid ? '-' : "<a href=\"?entry=channels&action=channeldel&chid=$channel[chid]\">".lang('delete')."</a>")."</td>\n".
				"<td class=\"txtC w30\">".($sid ? '-' : "<a href=\"?entry=channels&action=channelcopy&chid=$channel[chid]\" onclick=\"return floatwin('open_channeledit',this)\">".lang('copy')."</a>")."</td>\n".
				"<td class=\"txtC w30\">".($sid ? '-' : "<a href=\"?entry=channels&action=channeldetail&chid=$channel[chid]$param_suffix\" onclick=\"return floatwin('open_channeledit',this)\">".lang('detail')."</a>")."</td>\n".
				"</tr>\n";
		}
		if(!$sid){
			tabfooter();
			tabheader(lang('operate_item').viewcheck('viewdetail',0,$actionid.'tbodyfilter'));
			echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display:none\">";
			$itemsarr = array();
			foreach($cotypes as $k => $v) if(!$v['self_reg']) $itemsarr[$k] = $v['cname'];
			trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[acoids]\" value=\"1\">&nbsp;".lang('acoids')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_1\" onclick=\"checkall(this.form,'acoidsnew','chkall_1')\">".lang('selectall'),'',makecheckbox('acoidsnew[]',$itemsarr,array(),5),'',lang('agcoids'));
			trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[ccoids]\" value=\"1\">&nbsp;".lang('ccoids')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_2\" onclick=\"checkall(this.form,'ccoidsnew','chkall_2')\">".lang('selectall'),'',makecheckbox('ccoidsnew[]',$itemsarr,array(),5),'',lang('agcoids'));
			$itemsarr = array();
			$itemsarr['ppids'] = lang('addinpriv');
			$itemsarr['opids'] = lang('addinopen');
			$itemsarr['copy'] = lang('addcopy');
			$itemsarr['rpmid'] = lang('read_pmid');
			$itemsarr['dpmid'] = lang('down_pmid');
			$itemsarr['salecp'] = lang('arc_price');
			$itemsarr['fsalecp'] = lang('annex_price');
			$itemsarr['arctpl'] = lang('archive_content_template');
			trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[aitems]\" value=\"1\">&nbsp;".lang('aitems')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_3\" onclick=\"checkall(this.form,'aitemsnew','chkall_3')\">".lang('selectall'),'',makecheckbox('aitemsnew[]',$itemsarr,array(),5),'',lang('agitems'));
	
			$itemsarr = array();
			$itemsarr['ucid'] = lang('uclass');
			$itemsarr['ppids'] = lang('addinpriv');
			$itemsarr['opids'] = lang('addinopen');
			$itemsarr['copy'] = lang('addcopy');
			$itemsarr['salecp'] = lang('arc_price');
			$itemsarr['fsalecp'] = lang('annex_price');
			trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[citems]\" value=\"1\">&nbsp;".lang('citems')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_4\" onclick=\"checkall(this.form,'citemsnew','chkall_4')\">".lang('selectall'),'',makecheckbox('citemsnew[]',$itemsarr,array(),5),'',lang('agitems'));
	
			$coidsarr = array('caid' => lang('catalog'));
			foreach($cotypes as $k => $v) if(!$v['self_reg']) $coidsarr[$k] = $v['cname'];
			trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[coidscp]\" value=\"1\">&nbsp;".lang('allowcopys')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_5\" onclick=\"checkall(this.form,'coidscpnew','chkall_4')\">".lang('selectall'),'',makecheckbox('coidscpnew[]',$coidsarr,array(),5),'');
			$coidsarr = array();
			foreach($cotypes as $k => $v) if(!$v['self_reg']) $coidsarr[$k] = $v['cname'];
			trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[cpkeeps]\" value=\"1\">&nbsp;".lang('cpkeeps')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_6\" onclick=\"checkall(this.form,'cpkeepsnew','chkall_6')\">".lang('selectall'),'',makecheckbox('cpkeepsnew[]',$coidsarr,array(),5),'');
			echo "</tbody>";
		}

		tabfooter('bchanneledit',lang('modify'));
		a_guide('channeledit');
	}else{
		if(isset($channelnew)){
			if(!$sid){
				foreach($channelnew as $k => $v){
					$v['available'] = isset($v['available']) ? $v['available'] : 0;
					$v['cname'] = trim(strip_tags($v['cname']));
					$v['cname'] = $v['cname'] ? $v['cname'] : $channels[$k]['cname'];
					$v['remark'] = trim(strip_tags($v['remark']));
					$v['vieworder'] = max(0,intval($v['vieworder']));
					$db->query("UPDATE {$tblprefix}channels SET cname='$v[cname]',remark='$v[remark]',vieworder='$v[vieworder]',available='$v[available]' WHERE chid='$k'");
				}
				if(!empty($selectid) && !empty($arcdeal)){
					$sqlstr = '';
					foreach(array('acoids','ccoids','aitems','citems','coidscp','cpkeeps',) as $var){
						if(!empty($arcdeal[$var])){
							${$var.'new'} = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
							$sqlstr .= ($sqlstr ? ',' : '')."$var='".${$var.'new'}."'";
						}
					}
					$sqlstr && $db->query("UPDATE {$tblprefix}channels SET $sqlstr WHERE chid ".multi_str($selectid));
				}
				adminlog(lang('edit_arc_channel_list'));
				updatecache('channels');
			}else{
				$t_channels = empty($subsites[$sid]['channels']) ? array() : $subsites[$sid]['channels'];
				foreach($channels as $k => $v){
					$t_channels[$k]['available'] = empty($channelnew[$k]['available']) ? 0 : 1;
				}
				$t_channels = addslashes(serialize($t_channels));
				$db->query("UPDATE {$tblprefix}subsites SET channels='$t_channels' WHERE sid='$sid'");
				adminlog(lang('edit_arc_channel_list'));
				updatecache('subsites');
			}
		}
		amessage('archanneleditfinish',"?entry=channels&action=channeledit$param_suffix");
	}
}elseif($action == 'channeladd'){
	$url_type = 'channel';include 'urlsarr.inc.php';
	url_nav(lang('achannel'),$urlsarr,'channel');
	if(!submitcheck('bchanneladd')){
		$cuidsarr = cuidsarr('answer') + cuidsarr('purchase');
		$cuidsarr = array(0 => lang('noset')) + $cuidsarr;
		tabheader(lang('add_channel'),'channeladd','?entry=channels&action=channeladd',2,0,1);
		trbasic(lang('channel_name'),'channeladd[cname]');
		trbasic(lang('ut_commu_item'),'channeladd[cuid]',makeoption($cuidsarr),'select');
		tabfooter('bchanneladd',lang('add'));
		$submitstr = '';
		$submitstr .= makesubmitstr('channeladd[cname]',1,0,0,30);
		check_submit_func($submitstr);
	}else{
		$channeladd['cname'] = trim(strip_tags($channeladd['cname']));
		empty($channeladd['cname']) && amessage('channelnamemiss', '?entry=channels&action=channeledit');
		$db->query("INSERT INTO {$tblprefix}channels SET 
					cname='$channeladd[cname]', 
					cuid='$channeladd[cuid]'
					");
		if($chid = $db->insert_id()){
			$sqlstr = '';
			if(@$commus[$channeladd['cuid']]['cclass'] == 'answer') $sqlstr = "question text NOT NULL,";
			$customtable = "archives_$chid";
			$db->query("CREATE TABLE {$tblprefix}$customtable (
						aid mediumint(8) unsigned NOT NULL default '0',
						$sqlstr
						PRIMARY KEY (aid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM"));
			foreach($initfields as $field){
				$sqlstr = "chid='$chid',available='1'";
				foreach($field as $k => $v) if(!in_array($k,array('fid','chid','available'))) $sqlstr .= ",$k='".addslashes($v)."'";
				$db->query("INSERT INTO {$tblprefix}fields SET $sqlstr");
			}
			cu_addfields($chid,$channeladd['cuid']);
			updatecache('channels');
			updatecache('fields',$chid);
		}
		adminlog(lang('add_arc_channel'));
		amessage('arcchanneladdfinish',"?entry=channels&action=channeledit$param_suffix");
	}

}elseif($action == 'channelcopy' && $chid){
	$forward = empty($forward) ? M_REFERER : $forward;
	if(!submitcheck('bchannelcopy')){
		$channel = read_cache('channel',$chid);
		$fields = read_cache('fields',$chid);
		$cuidsarr = array(0 => lang('default')) + cuidsarr();
		tabheader(lang('arc_channel_copy'),'channelcopy',"?entry=channels&action=channelcopy&chid=$chid&forward=".rawurlencode($forward),2,0,1);
		trbasic(lang('soc_channel_name'),'',$channel['cname'],'');
		trbasic(lang('soc_ccommu_config'),'',$cuidsarr[$channel['cuid']],'');
		trbasic(lang('new_channel_name'),'channelnew[cname]');
		if($channel['cuid']){
			$cuidsarr = cuidsarr($commus[$channel['cuid']]['cclass']);
			trbasic(lang('new_ccommu_config'),'channelnew[cuid]',makeoption($cuidsarr,$channel['cuid']),'select');
		}
		tabfooter('bchannelcopy');
		$submitstr = '';
		$submitstr .= makesubmitstr('channelnew[cname]',1,0,0,30);
		check_submit_func($submitstr);
		a_guide('channelcopy');
	}else{
		$channelnew['cname'] = trim(strip_tags($channelnew['cname']));
		empty($channelnew['cname']) && amessage('channelnamemiss',M_REFERER);
		$channelnew['cuid'] = empty($channelnew['cuid']) ? 0 : $channelnew['cuid'];
		$channel = $db->fetch_one("SELECT * FROM {$tblprefix}channels WHERE chid='$chid'");
		$sqlstr = '';
		foreach($channel as $k => $v){
			if(!in_array($k,array('chid','cname','cuid'))) $sqlstr .= ",$k='".addslashes($v)."'";
		}
		$db->query("INSERT INTO {$tblprefix}channels SET 
					cname='$channelnew[cname]', 
					cuid='$channelnew[cuid]'
					$sqlstr
					");
		if($nchid = $db->insert_id()){
			$customtable = "archives_$nchid";
			$db->query("CREATE TABLE {$tblprefix}$customtable (
						aid mediumint(8) unsigned NOT NULL default '0',
						PRIMARY KEY (aid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM")); 
			$query = $db->query("SELECT * FROM {$tblprefix}fields WHERE chid='$chid' ORDER BY fid ASC");
			while($fieldnew = $db->fetch_array($query)){
				$fieldnew = maddslashes($fieldnew);
				if($fieldnew['tbl'] == 'custom'){
					$fconfigarr = array(
						'errorurl' => M_REFERER,
						'enamearr' => array(),
						'altertable' => $tblprefix.$customtable,
						'fieldtable' => $tblprefix.'fields',
						'sqlstr' => "chid=$nchid,issystem='$fieldnew[issystem]',iscustom='$fieldnew[iscustom]',available='$fieldnew[available]',vieworder='$fieldnew[vieworder]',tbl='custom'",
						'filterstr' => "",
					);
					list($fmode,$fnew,$fsave) = array('a',true,true);
					include M_ROOT."./include/fields/$fieldnew[datatype].php";
				}else{
					$sqlstr = "chid='$nchid'";
					foreach($fieldnew as $k =>$v) if(!in_array($k,array('fid','chid'))) $sqlstr .= ",$k='$v'";
					$db->query("INSERT INTO {$tblprefix}fields SET $sqlstr");
				}
			}
			updatecache('channels');
			updatecache('fields',$nchid);
		}
		adminlog(lang('copy_arc_channel'));
		amessage('arcchannelcopyfinish',axaction(6,"?entry=channels&action=channeledit"));

	}
}elseif($action == 'channeldetail' && $chid){
	$channel = read_cache('channel',$chid);
	$fields = read_cache('fields',$chid);
	if(!submitcheck('bchanneldetail')){
		$url_type = 'channeldetail';include 'urlsarr.inc.php';
		url_nav($channel['cname'].'-'.lang('achannel'),$urlsarr,'detail');
		tabheader(lang('base_setting'),'channeldetail',"?entry=channels&action=channeldetail&chid=$chid$param_suffix");
		trbasic(lang('admin_self_channel'),'channelnew[userforbidadd]',$channel['userforbidadd'],'radio');
		trbasic(lang('add_pmid'),'channelnew[apmid]',makeoption(pmidsarr('aadd'),$channel['apmid']),'select');
		trbasic(lang('chpmid'),'channelnew[chpmid]',makeoption(pmidsarr('chk'),$channel['chpmid']),'select');
		$chklvarr = array();
		for($i = 1;$i <= $max_chklv;$i ++) $chklvarr[$i] = lang('level'.$i);
		trbasic(lang('chklevel'),'',makeradio('channelnew[chklv]',$chklvarr,max(1,@$channel['chklv'])),'');
		$autocheckarr = array(0 => lang('noatchk'),1 => lang('autocheck'),);
		foreach(pmidsarr('chk') as $k => $v) $k && $autocheckarr[-$k] = $v;
		trbasic(lang('arc_auto_check'),'channelnew[autocheck]',makeoption($autocheckarr,$channel['autocheck']),'select');
		trbasic(lang('arc_auto_static'),'channelnew[autostatic]',$channel['autostatic'],'radio');
		trbasic(lang('addnonum'),'channelnew[addnum]',$channel['addnum']);
		$itemsarr = array();
		foreach($cotypes as $k => $v) if(!$v['self_reg']) $itemsarr[$k] = $v['cname'];
		trbasic(lang('acoids')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_1\" onclick=\"checkall(this.form,'acoidsnew','chkall_1')\">".lang('selectall'),'',makecheckbox('acoidsnew[]',$itemsarr,empty($channel['acoids']) ? array() : explode(',',$channel['acoids']),5),'',lang('agcoids'));
		trbasic(lang('ccoids')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_3\" onclick=\"checkall(this.form,'ccoidsnew','chkall_3')\">".lang('selectall'),'',makecheckbox('ccoidsnew[]',$itemsarr,empty($channel['ccoids']) ? array() : explode(',',$channel['ccoids']),5),'',lang('agcoids'));

		$itemsarr = array();
		$itemsarr['jumpurl'] = lang('jumpurl');
		$itemsarr['ppids'] = lang('addinpriv');
		$itemsarr['opids'] = lang('addinopen');
		$itemsarr['copy'] = lang('addcopy');
		$itemsarr['rpmid'] = lang('read_pmid');
		$itemsarr['dpmid'] = lang('down_pmid');
		$itemsarr['salecp'] = lang('arc_price');
		$itemsarr['fsalecp'] = lang('annex_price');
		$itemsarr['arctpl'] = lang('archive_content_template');
		$itemsarr['arcurl'] = lang('arc_static_url_format');
		trbasic(lang('aitems')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_2\" onclick=\"checkall(this.form,'aitemsnew','chkall_2')\">".lang('selectall'),'',makecheckbox('aitemsnew[]',$itemsarr,empty($channel['aitems']) ? array() : explode(',',$channel['aitems']),5),'',lang('agitems'));

		$itemsarr = array();
		$itemsarr['jumpurl'] = lang('jumpurl');
		$itemsarr['ucid'] = lang('uclass');
		$itemsarr['ppids'] = lang('addinpriv');
		$itemsarr['opids'] = lang('addinopen');
		$itemsarr['copy'] = lang('addcopy');
		$itemsarr['salecp'] = lang('arc_price');
		$itemsarr['fsalecp'] = lang('annex_price');
		trbasic(lang('citems')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_4\" onclick=\"checkall(this.form,'citemsnew','chkall_4')\">".lang('selectall'),'',makecheckbox('citemsnew[]',$itemsarr,empty($channel['citems']) ? array() : explode(',',$channel['citems']),5),'',lang('agitems'));

		$itemsarr = array();
		foreach($cotypes as $k => $v) if(!$v['self_reg']) $itemsarr['ccid'.$k] = $v['cname'];
		$itemsarr['jumpurl'] = lang('jumpurl');
		$itemsarr['copy'] = lang('addcopy');
		$itemsarr['ppids'] = lang('addinpriv');
		$itemsarr['opids'] = lang('addinopen');
		foreach($fields as $k => $v) if(!$v['issystem']) $itemsarr[$k] = $v['cname'];
		$itemsarr['validperiod'] = lang('set_valid_day');
		$itemsarr['rpmid'] = lang('read_pmid');
		$itemsarr['dpmid'] = lang('down_pmid');
		$itemsarr['salecp'] = lang('arc_price');
		$itemsarr['fsalecp'] = lang('annex_price');
		$itemsarr['ucid'] = lang('uclass');
		$itemsarr['arctpl'] = lang('archive_content_template');
		$itemsarr['arcurl'] = lang('arc_static_url_format');
		trbasic(lang('additems_a')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_5\" onclick=\"checkall(this.form,'additemsnew','chkall_5')\">".lang('selectall'),'',makecheckbox('additemsnew[]',$itemsarr,empty($channel['additems']) ? array() : explode(',',$channel['additems']),5),'',lang('agadditems'));

		$itemsarr = array('caid' => lang('catalog'));
		foreach($cotypes as $k => $v) if(!$v['self_reg']) $itemsarr['ccid'.$k] = $v['cname'];
		foreach($fields as $k => $v) $itemsarr[$k] = $v['cname'];
		$itemsarr['validperiod'] = lang('set_valid_day');
		$itemsarr['salecp'] = lang('arc_price');
		$itemsarr['fsalecp'] = lang('annex_price');
		trbasic(lang('useredits')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_6\" onclick=\"checkall(this.form,'usereditsnew','chkall_6')\">".lang('selectall'),'',makecheckbox('usereditsnew[]',$itemsarr,empty($channel['useredits']) ? array() : explode(',',$channel['useredits']),5),'',lang('aguseredits'));

		$coidsarr = array('caid' => lang('catalog'));
		foreach($cotypes as $k => $v) if(!$v['self_reg']) $coidsarr[$k] = $v['cname'];
		trbasic(lang('allowcopys')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_7\" onclick=\"checkall(this.form,'channelnew[coidscp]','chkall_7')\">".lang('selectall'),'',makecheckbox('channelnew[coidscp][]',$coidsarr,empty($channel['coidscp']) ? array() : explode(',',$channel['coidscp']),5),'');
		$coidsarr = array();
		foreach($cotypes as $k => $v) if(!$v['self_reg']) $coidsarr[$k] = $v['cname'];
		trbasic(lang('cpkeeps')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_8\" onclick=\"checkall(this.form,'channelnew[cpkeeps]','chkall_8')\">".lang('selectall'),'',makecheckbox('channelnew[cpkeeps][]',$coidsarr,empty($channel['cpkeeps']) ? array() : explode(',',$channel['cpkeeps']),5),'');
		tabfooter('bchanneldetail');
	}else{
		$channelnew['addnum'] = min($max_addno,max(0,intval($channelnew['addnum'])));
		$channelnew['acoids'] = empty($acoidsnew) ? '' : implode(',',$acoidsnew);
		$channelnew['ccoids'] = empty($ccoidsnew) ? '' : implode(',',$ccoidsnew);
		$channelnew['aitems'] = empty($aitemsnew) ? '' : implode(',',$aitemsnew);
		$channelnew['citems'] = empty($citemsnew) ? '' : implode(',',$citemsnew);
		$channelnew['additems'] = empty($additemsnew) ? '' : implode(',',$additemsnew);
		$channelnew['useredits'] = empty($usereditsnew) ? '' : implode(',',$usereditsnew);
		$channelnew['coidscp'] = empty($channelnew['coidscp']) ? '' : implode(',',$channelnew['coidscp']);
		$channelnew['cpkeeps'] = empty($channelnew['cpkeeps']) ? '' : implode(',',$channelnew['cpkeeps']);
		$db->query("UPDATE {$tblprefix}channels SET 
			userforbidadd='$channelnew[userforbidadd]', 
			apmid='$channelnew[apmid]',
			chpmid='$channelnew[chpmid]',
			chklv='$channelnew[chklv]',
			autocheck='$channelnew[autocheck]', 
			autostatic='$channelnew[autostatic]', 
			addnum='$channelnew[addnum]',
			acoids='$channelnew[acoids]',
			ccoids='$channelnew[ccoids]',
			aitems='$channelnew[aitems]',
			citems='$channelnew[citems]',
			additems='$channelnew[additems]',
			useredits='$channelnew[useredits]',
			coidscp='$channelnew[coidscp]',
			cpkeeps='$channelnew[cpkeeps]'
			WHERE chid='$chid'");
		updatecache('channels');
		adminlog(lang('detail_marc_channel'));
		amessage('channelmodifyfinish',"?entry=channels&action=channeldetail&chid=$chid$param_suffix");
	}
}elseif($action == 'channelalbum' && $chid){
	$channel = read_cache('channel',$chid);
	if(!submitcheck('bchanneldetail')){
		$url_type = 'channeldetail';include 'urlsarr.inc.php';
		url_nav($channel['cname'].'-'.lang('achannel'),$urlsarr,'album');
		tabheader(lang('abfunc'),'channeldetail',"?entry=channels&action=channelalbum&chid=$chid$param_suffix");
		trbasic(lang('enablealbum'),'channelnew[isalbum]',$channel['isalbum'],'radio',lang('agisalbum'));
		trbasic(lang('inalbum_add_archive'),'',makecheckbox('channelnew[inchids][]',chidsarr(0),empty($channel['inchids']) ? array() : explode(',',$channel['inchids']),5),'');
		$coidsarr = array('caid' => lang('catalog'));
		foreach($cotypes as $k => $v) if(!$v['self_reg']) $coidsarr[$k] = $v['cname'];
		trbasic(lang('inalbum_add_coids'),'',makecheckbox('channelnew[incoids][]',$coidsarr,empty($channel['incoids']) ? array() : explode(',',$channel['incoids']),5),'');
		trbasic(lang('setalbum_auto_check'),'channelnew[inautocheck]',$channel['inautocheck'],'radio');
		trbasic(lang('albumoneuser'),'channelnew[oneuser]',$channel['oneuser'],'radio');
		trbasic(lang('albumonlyone'),'channelnew[onlyone]',$channel['onlyone'],'radio');
		trbasic(lang('isonlyloadalbum'),'channelnew[onlyload]',$channel['onlyload'],'radio',lang('agonlyload'));
		trbasic(lang('enableinalbumcount'),'channelnew[statsum]',$channel['statsum'],'radio');
		trbasic(lang('inalbummaxlimit'),'channelnew[maxnums]',$channel['maxnums']);
		tabfooter();
		tabheader(lang('commu_sett'));
		$cuidsarr = cuidsarr('answer') + cuidsarr('purchase');
		trbasic(lang('ut_commu_item'),'',empty($cuidsarr[$channel['cuid']]) ? '-' : $cuidsarr[$channel['cuid']],'');
		trbasic(lang('comment_commu_setg'),'channelnew[comment]',makeoption(array(0 => lang('noset')) + cuidsarr('comment'),$channel['comment']),'select');
		trbasic(lang('reply_commu_set'),'channelnew[reply]',makeoption(array(0 => lang('noset')) + cuidsarr('reply'),$channel['reply']),'select');
		trbasic(lang('offer_commu_set'),'channelnew[offer]',makeoption(array(0 => lang('noset')) + cuidsarr('offer'),$channel['offer']),'select');
		trbasic(lang('pickbug_commu_set'),'channelnew[report]',makeoption(array(0 => lang('noset')) + cuidsarr('report'),$channel['report']),'select');
		tabfooter('bchanneldetail');
	}else{
		$channelnew['inchids'] = empty($channelnew['inchids']) ? '' : implode(',',array_diff($channelnew['inchids'],array($chid)));
		$channelnew['incoids'] = empty($channelnew['incoids']) ? '' : implode(',',$channelnew['incoids']);
		$channelnew['maxnums'] = max(0,intval($channelnew['maxnums']));
		$db->query("UPDATE {$tblprefix}channels SET 
			isalbum='$channelnew[isalbum]', 
			inchids='$channelnew[inchids]',
			incoids='$channelnew[incoids]', 
			inautocheck='$channelnew[inautocheck]', 
			oneuser='$channelnew[oneuser]',
			onlyone='$channelnew[onlyone]',
			onlyload='$channelnew[onlyload]',
			statsum='$channelnew[statsum]',
			maxnums='$channelnew[maxnums]',
			comment='$channelnew[comment]', 
			reply='$channelnew[reply]', 
			offer='$channelnew[offer]', 
			report='$channelnew[report]'
			WHERE chid='$chid'");
		updatecache('channels');
		adminlog(lang('detail_marc_channel'));
		amessage('channelmodifyfinish',"?entry=channels&action=channelalbum&chid=$chid$param_suffix");
	}
}elseif($action == 'channelother' && $chid){
	$channel = read_cache('channel',$chid);
	if(!submitcheck('bchanneldetail')){
		$url_type = 'channeldetail';include 'urlsarr.inc.php';
		url_nav($channel['cname'].'-'.lang('achannel'),$urlsarr,'other');
		tabheader(lang('allowance_and_vp'),'channeldetail',"?entry=channels&action=channelother&chid=$chid$param_suffix");
		trbasic(lang('is_allowance_arc'),'channelnew[allowance]',$channel['allowance'],'radio');
		$readdarr = array(0 => lang('not_enable_readd'),1 => lang('ba_allow_readd'),2 => lang('member_allow_readd'));
		trbasic(lang('readd_set'),'',makeradio('channelnew[readd]',$readdarr,$channel['readd']),'');
		trbasic(lang('readd_time_inval_h'),'channelnew[reinterval]',$channel['reinterval']);
		$validperiodarr = array(0 => lang('not_enable_vp'),1 => lang('over_reset_vp'),2 => lang('anytime_reset_vp'));
		trbasic(lang('arc_vp_set'),'',makeradio('channelnew[validperiod]',$validperiodarr,$channel['validperiod']),'');
		trrange(lang('vp_days'),array('channelnew[mindays]',empty($channel['mindays']) ? '' : $channel['mindays'],'','&nbsp; '.lang('mini').'&nbsp; -&nbsp; ',5),array('channelnew[maxdays]',empty($channel['maxdays']) ? '' : $channel['maxdays'],'','&nbsp; '.lang('max'),5));
		tabfooter();
		
		tabheader(lang('a_url'));
		$iuidsarr = array();
		foreach($inurls as $k => $v) if(in_array($v['uclass'],array('adetail','inadd','content','load','setalbum','vol','comments','offers','replys','answers','purchases','reports','custom'))) $iuidsarr[$k] = '<b>'.$v['cname'].'</b>&nbsp; '.$v['remark'];
		trbasic(lang('view_inurls'),'',makecheckbox('channelnew[iuids][]',$iuidsarr,empty($channel['iuids']) ? array() : explode(',',$channel['iuids']),3),'',lang('agnoselect1'));
		$imuidsarr = array();
		foreach($inmurls as $k => $v) if(in_array($v['uclass'],array('adetail','inadd','content','load','setalbum','vol','replys','answers','custom'))) $imuidsarr[$k] = '<b>'.$v['cname'].'</b>&nbsp; '.$v['remark'];
		trbasic(lang('view_inmurls'),'',makecheckbox('channelnew[imuids][]',$imuidsarr,empty($channel['imuids']) ? array() : explode(',',$channel['imuids']),3),'',lang('agnoselect1'));
		tabfooter();
		
		tabheader(lang('astaticset'));
		$addnos = explode(',',$channel['addnos']);
		$statics = explode(',',$channel['statics']);
		$periods = explode(',',$channel['periods']);
		$novus = explode(',',$channel['novus']);
		for($i = 0;$i <= $max_addno;$i ++){
			$pvar = $i ? lang('addp').$i : lang('arcconpage');
			$str = "<input type=\"text\" size=\"6\" id=\"channelnew[addnos][$i]\" name=\"channelnew[addnos][$i]\" value=\"".@$addnos[$i]."\" title=\"".lang('addnoset')."\">&nbsp; ";
			$str .= "<select id=\"channelnew[statics][$i]\" name=\"channelnew[statics][$i]\">".makeoption(array(0 => lang('staticsys'),1 => lang('keepdnc'),2 => lang('create_static')),@$statics[$i])."</select>&nbsp; ";
			$str .= "<input type=\"text\" size=\"2\" id=\"channelnew[periods][$i]\" name=\"channelnew[periods][$i]\" value=\"".@$periods[$i]."\" title=\"".lang('staticperiod')."\">&nbsp; ";
			$str .= "<input class=\"checkbox\" type=\"checkbox\" id=\"channelnew[novus][$i]\" name=\"channelnew[novus][$i]\" value=\"1\" ".(empty($novus[$i]) ? '' : ' checked')." title=\"".lang('novu')."\">";
			trbasic($pvar.lang('setting'),'',$str,'',$i ? '' : lang('agaddnos'));
		}	
		tabfooter('bchanneldetail');
	}else{
		$channelnew['reinterval'] = empty($channelnew['reinterval']) ? 0 : max(0,intval($channelnew['reinterval']));
		$channelnew['mindays'] = empty($channelnew['mindays']) ? 0 : max(0,intval($channelnew['mindays']));
		$channelnew['maxdays'] = empty($channelnew['maxdays']) ? 0 : max(0,intval($channelnew['maxdays']));
		$channelnew['iuids'] = empty($channelnew['iuids']) ? '' : implode(',',$channelnew['iuids']);
		$channelnew['imuids'] = empty($channelnew['imuids']) ? '' : implode(',',$channelnew['imuids']);
		$channelnew['addnos'] = implode(',',$channelnew['addnos']);
		$channelnew['statics'] = implode(',',$channelnew['statics']);
		$channelnew['periods'] = implode(',',$channelnew['periods']);
		for($i = 0;$i <= $max_addno;$i ++) $novus[$i] = empty($channelnew['novus'][$i]) ? 0 : 1;
		$channelnew['novus'] = empty($novus) ? '' : implode(',',$novus);
		$db->query("UPDATE {$tblprefix}channels SET 
			allowance='$channelnew[allowance]',
			readd='$channelnew[readd]',
			reinterval='$channelnew[reinterval]',
			validperiod='$channelnew[validperiod]',
			mindays='$channelnew[mindays]',
			maxdays='$channelnew[maxdays]',
			iuids='$channelnew[iuids]',
			imuids='$channelnew[imuids]',
			addnos='$channelnew[addnos]',
			statics='$channelnew[statics]',
			periods='$channelnew[periods]',
			novus='$channelnew[novus]'
			WHERE chid='$chid'");
		updatecache('channels');
		adminlog(lang('detail_marc_channel'));
		amessage('channelmodifyfinish',"?entry=channels&action=channelother&chid=$chid$param_suffix");
	}
}elseif($action == 'channeladv' && $chid){
	$channel = read_cache('channel',$chid);
	if(!submitcheck('bchanneldetail')){
		$url_type = 'channeldetail';include 'urlsarr.inc.php';
		url_nav($channel['cname'].'-'.lang('achannel'),$urlsarr,'adv');
		if(!empty($channel['usetting'])){
			$str = '';
			foreach($channel['usetting'] as $k => $v) $str .= $k.'='.$v."\n";	
			$channel['usetting'] = $str;
			unset($str);
		}
		tabheader(lang('advsetting'),'channeldetail',"?entry=channels&action=channeladv&chid=$chid$param_suffix");
		trbasic(lang('custom_ucadd'),'channelnew[ucadd]',empty($channel['ucadd']) ? '' : $channel['ucadd'],'text',lang('agmucustom'));
		trbasic(lang('custom_uaadd'),'channelnew[uaadd]',empty($channel['uaadd']) ? '' : $channel['uaadd'],'text',lang('agmucustom'));
		trbasic(lang('custom_uadetail'),'channelnew[uadetail]',empty($channel['uadetail']) ? '' : $channel['uadetail'],'text',lang('agmucustom'));
		trbasic(lang('custom_umdetail'),'channelnew[umdetail]',empty($channel['umdetail']) ? '' : $channel['umdetail'],'text',lang('agmucustom'));
		trbasic(lang('customsetting'),'channelnew[usetting]',empty($channel['usetting']) ? '' : $channel['usetting'],'btextarea',lang('agcustomsetting'));
		tabfooter('bchanneldetail');
	}else{
		$channelnew['ucadd'] = empty($channelnew['ucadd']) ? '' : trim($channelnew['ucadd']);
		$channelnew['uaadd'] = empty($channelnew['uaadd']) ? '' : trim($channelnew['uaadd']);
		$channelnew['uadetail'] = empty($channelnew['uadetail']) ? '' : trim($channelnew['uadetail']);
		$channelnew['umdetail'] = empty($channelnew['umdetail']) ? '' : trim($channelnew['umdetail']);
		if(!empty($channelnew['usetting'])){
			$channelnew['usetting'] = str_replace("\r","",$channelnew['usetting']);
			$temps = explode("\n",$channelnew['usetting']);
			$channelnew['usetting'] = array();
			foreach($temps as $v){
				$temparr = explode('=',str_replace(array("\r","\n"),'',$v));
				if(!isset($temparr[1]) || !($temparr[0] = trim($temparr[0]))) continue;
				$channelnew['usetting'][$temparr[0]] = trim($temparr[1]);
			}
			unset($temps,$temparr);
		}
		$channelnew['usetting'] = !empty($channelnew['usetting']) ? addslashes(serialize($channelnew['usetting'])) : '';
		$db->query("UPDATE {$tblprefix}channels SET 
			ucadd='$channelnew[ucadd]',
			uaadd='$channelnew[uaadd]',
			uadetail='$channelnew[uadetail]',
			umdetail='$channelnew[umdetail]',
			usetting='$channelnew[usetting]'
			WHERE chid='$chid'");
		updatecache('channels');
		adminlog(lang('detail_marc_channel'));
		amessage('channelmodifyfinish',"?entry=channels&action=channeladv&chid=$chid$param_suffix");
	}
}elseif($action == 'channelfields' && $chid){
	$channel = read_cache('channel',$chid);
	$fields = read_cache('fields',$chid);
	if(!submitcheck('bchanneldetail')){
		$url_type = 'channeldetail';include 'urlsarr.inc.php';
		url_nav($channel['cname'].'-'.lang('achannel'),$urlsarr,'filed');
		tabheader($channel['cname'].'-'.lang('field_manager')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=channels&action=fieldadd&chid=$chid$param_suffix\">".lang('add_field')."</a>",'channeldetail',"?entry=channels&action=channelfields&chid=$chid$param_suffix");
		trcategory(array(lang('delete'),lang('available'),lang('field_name'),lang('admin_self'),lang('order'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($fields as $k => $field) fieldlist($k,$field);
		tabfooter();
		tabheader($channel['cname'].'-'.lang('field_manager'));
		$abstractarr = $thumbarr = $newsarr = $sizearr = array('0' => lang('noset'));
		foreach($fields as $k => $field){
			if($field['available']){
				($field['iscustom'] && in_array($field['datatype'],array('text','multitext','htmltext'))) && $abstractarr[$k] = $field['cname'];
				($field['iscustom'] && in_array($field['datatype'],array('image','images','text','multitext','htmltext'))) && $thumbarr[$k] = $field['cname'];
				in_array($field['datatype'],array('multitext','htmltext')) && $newsarr[$k] = $field['cname'];
				in_array($field['datatype'],array('image','flash','media','file','images','flashs','medias','files',)) && $sizearr[$k] = $field['cname'];
			}
		}
		$autosizemodearr = array('0' => lang('sum'),'1' => lang('oneof'));
		trbasic(lang('auto_abstract_src'),'channelnew[autoabstract]',makeoption($abstractarr,$channel['autoabstract']),'select');
		trbasic(lang('auto_thumb_src'),'channelnew[autothumb]',makeoption($thumbarr,$channel['autothumb']),'select');
		trbasic(lang('auto_keyword_src'),'channelnew[autokeyword]',makeoption($abstractarr,$channel['autokeyword']),'select');
		trbasic(lang('auto_stat_asize_src'),'channelnew[autosize]',makeoption($sizearr,$channel['autosize']),'select');
		trbasic(lang('auto_stat_a_size_mode'),'channelnew[autosizemode]',makeradio('channelnew[autosizemode]',$autosizemodearr,$channel['autosizemode']),'');
		trbasic(lang('baidu_map_src'),'channelnew[baidu]',makeoption($newsarr,$channel['baidu']),'select');
		trbasic(lang('fulltxt_search_src'),'channelnew[fulltxt]',makeoption($newsarr,$channel['fulltxt']),'select');
		trbasic(lang('stat_text_size_src'),'channelnew[autobyte]',makeoption($newsarr,$channel['autobyte']),'select');

		tabfooter('bchanneldetail');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				if(!$fields[$id]['mcommon']){
					$customtable = "archives_$chid";
					if(!empty($fields[$id]['istxt'])){//如果是文本字段，需要删除相关的存储文件
						$query = $db->query("SELECT $id FROM {$tblprefix}$customtable");
						while($row = $db->fetch_array($query)) txtunlink($row[$id]);
					}
					dropfieldfromtbl($customtable,$id,$fields[$id]['datatype']);
					$db->query("DELETE FROM {$tblprefix}fields WHERE ename='$id' AND chid='$chid'"); 
					unset($fields[$id],$fieldsnew[$id]);
				}
			}
		}
		foreach($fields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['available'] = $field['issystem'] ? $field['available'] : (empty($fieldsnew[$id]['available']) ? 0 : 1);
			$field['isadmin'] = $field['issystem'] ? '0' : (empty($fieldsnew[$id]['isadmin']) ? 0 : 1);
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}fields SET cname='$field[cname]',available='$field[available]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND chid='$chid'");
		}
		$db->query("UPDATE {$tblprefix}channels SET 
			autoabstract='$channelnew[autoabstract]', 
			autokeyword='$channelnew[autokeyword]', 
			autothumb='$channelnew[autothumb]', 
			autosize='$channelnew[autosize]', 
			autosizemode='$channelnew[autosizemode]', 
			baidu='$channelnew[baidu]', 
			fulltxt='$channelnew[fulltxt]', 
			autobyte='$channelnew[autobyte]' 
			WHERE chid='$chid'");
		updatecache('channels');
		updatecache('fields',$chid);
		adminlog(lang('detail_marc_channel'));
		amessage('channelmodifyfinish',"?entry=channels&action=channelfields&chid=$chid$param_suffix");
	}
}elseif($action == 'fieldadd' && $chid){
	$channel = read_cache('channel',$chid);
	if(!submitcheck('bfieldadd')){
		$url_type = 'channeldetail';include 'urlsarr.inc.php';
		url_nav($channel['cname'].'-'.lang('achannel'),$urlsarr,'filed');
		tabheader(lang('add_arc_channel_field')."&nbsp; -&nbsp; ".$channels[$chid]['cname'],'fieldadd',"?entry=channels&action=fieldadd&chid=$chid",2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('bfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('a',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('fieldadd');
	}else{
		$enamearr = $usednames['fields'];
		$fields = read_cache('fields',$chid);
		foreach($fields as $ename => $field){
			if(!in_array($ename,$enamearr)) $enamearr[] = $ename;
		}
		$fconfigarr = array(
		'errorurl' => '?entry=channels&action=channeldetail&chid='.$chid,
		'enamearr' => $enamearr,
		'altertable' => $tblprefix.'archives_'.$chid,
		'fieldtable' => $tblprefix.'fields',
		'sqlstr' => "chid=$chid,iscustom='1',available='1',tbl='custom'",
		'filterstr' => "/[^a-zA-Z_0-9]+|^[0-9_]+|^ccid(.*?)/",
		);
		list($fmode,$fnew,$fsave) = array('a',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		adminlog(lang('add_achannel_msg_field'));
		updatecache('fields',$chid);
		amessage('fieldaddfinish',"?entry=channels&action=channelfields&chid=$chid$param_suffix");
	}
}elseif($action == 'fielddetail' && $chid && $fieldename){
	!isset($channels[$chid]) && amessage('choosechannel');
	$field = read_cache('field',$chid,$fieldename);
	empty($field) && amessage('choosefield');
	if(!submitcheck('bfielddetail')){
		$submitstr = '';
		tabheader(lang('edit_channel_field')."&nbsp; -&nbsp; ".$channels[$chid]['cname']."&nbsp; -&nbsp; $field[cname]",'fielddetail',"?entry=channels&action=fielddetail&chid=$chid&fieldename=$fieldename",2,0,1);
		list($fmode,$fnew,$fsave) = array('a',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bfielddetail');
		check_submit_func($submitstr);
		a_guide('fielddetail');
	}else{
		$fconfigarr = array(
		'altertable' => $tblprefix.'archives_'.$chid,
		'fieldtable' => $tblprefix.'fields',
		'wherestr' => "WHERE ename='$fieldename' AND chid=$chid",
		);
		list($fmode,$fnew,$fsave) = array('a',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		updatecache('fields',$chid);
		adminlog(lang('detail_mach_msg_field'));
		amessage('fieldeditfinish',axaction(2,"?entry=channels&action=channelfields&chid=$chid$param_suffix"));
	}
}elseif($action == 'channeldel' && $chid) {
	$channel = $channels[$chid];
	if(empty($confirm)){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=channels&action=channeldel&chid=".$chid."&confirm=1>".lang('delete')."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$message .= lang('giveupclick').">><a href=?entry=channels&action=channeledit>".lang('goback')."</a>";
		amessage($message);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}archives WHERE chid='$chid'")){
		amessage('channelnoarccandel', '?entry=channels&action=channeledit');
	}

	$customtable = "archives_$chid";
	$db->query("DROP TABLE IF EXISTS {$tblprefix}$customtable",'SILENT');
	$db->query("DELETE FROM {$tblprefix}channels WHERE chid='$chid'",'SILENT');
	$db->query("DELETE FROM {$tblprefix}fields WHERE chid='$chid'",'SILENT');
	//清除相关缓存
	del_cache('fields',$chid);
	@unlink(M_ROOT.'./dynamic/mguides/add_'.$chid.'.php');
	@unlink(M_ROOT.'./dynamic/mguides/search_'.$chid.'.php');
	adminlog(lang('del_arc_channel'));
	updatecache('channels');
	amessage('arcchanneldelfinish',"?entry=channels&action=channeledit");
}elseif($action == 'initfieldadd'){
	if(!$sid){
		$url_type = 'channel';include 'urlsarr.inc.php';
		url_nav(lang('achannel'),$urlsarr,'field');
	}
	if(!submitcheck('binitfieldadd')){
		tabheader(lang('add_common_field'),'initfieldadd',"?entry=channels&action=initfieldadd",2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('baddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('i',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('binitfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('initfieldadd');
	}else{
		$enamearr = $usednames['fields'];
		$fconfigarr = array(
			'errorurl' => '?entry=channels&action=initfieldsedit',
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'archives',
			'fieldtable' => $tblprefix.'fields',
			'sqlstr' => "iscustom='1',mcommon='1',available='1',tbl='main'",
			'filterstr' => "/[^a-zA-Z_0-9]+|^[0-9_]+|^ccid(.*?)/",
		);
		list($fmode,$fnew,$fsave) = array('i',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		updatecache('initfields');
		$chids = array_keys($channels);
		foreach($chids as $chid){
			$sqlstr = "ename='$fieldnew[ename]',cname='$fieldnew[cname]',issystem='0',iscustom='1',mcommon='1',chid='$chid',tbl='main'";
			foreach(array('datatype','length','notnull','nohtml','mode','guide','isadmin','mlimit','rpid','issearch','innertext','mcommon','min','max','regular','isfunc','func','vdefault','custom_1','custom_2',) as $var){
				isset($fieldnew[$var]) && $sqlstr .= (!$sqlstr ? '' : ',')."$var='".$fieldnew[$var]."'";
			}
			$db->query("INSERT INTO {$tblprefix}fields SET $sqlstr");
			updatecache('fields',$chid);
		}
		adminlog(lang('add_acm_field'));
		updatecache('usednames','fields');
		amessage('fieldaddfinish', '?entry=channels&action=initfieldsedit');
	}
}
elseif($action == 'initfieldsedit'){
	if(!$sid){
		$url_type = 'channel';include 'urlsarr.inc.php';
		url_nav(lang('achannel'),$urlsarr,'field');
	}
	if(!submitcheck('binitfieldsedit')){
		tabheader(lang('common_field_manager')."&nbsp; &nbsp; >><a href=\"?entry=channels&action=initfieldadd\">".lang('add')."</a>",'initfieldsedit','?entry=channels&action=initfieldsedit','5');
		trcategory(array(lang('delete'),array(lang('field_name'),'txtL'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($initfields as $k => $field) {
			fieldlist($k,$field,'init');
		}
		tabfooter('binitfieldsedit');
		a_guide('initfieldsedit');
	}else{
		if(!empty($delete)){
			$chids = array_keys($channels);
			foreach($delete as $fieldename){
				if($initfields[$fieldename]['iscustom']){
					if(!empty($initfields[$fieldename]['istxt'])){//删除相应的文件存储记录
						$query = $db->query("SELECT $fieldename FROM {$tblprefix}archives");
						while($row = $db->fetch_array($query)) txtunlink($row[$fieldename]);
					}
					dropfieldfromtbl('archives',$fieldename,$initfields[$fieldename]['datatype']);
					$db->query("DELETE FROM {$tblprefix}fields WHERE ename='$fieldename'");
					unset($initfields[$fieldename],$fieldsnew[$fieldename]);
				}
			}
			foreach($chids as $chid){
				updatecache('fields',$chid);
			}
			updatecache('usednames','fields');
		}
		foreach($initfields as $id => $field){
			$field['cname'] = trim($fieldsnew[$id]['cname']) ? trim($fieldsnew[$id]['cname']) : $field['cname'];
			$db->query("UPDATE {$tblprefix}fields SET cname='$field[cname]' WHERE ename='$id' AND chid='0'");
		}
		adminlog(lang('edit_acm_field_mlist'));
		updatecache('initfields');
		amessage('fieldeditfinish','?entry=channels&action=initfieldsedit');
	}
}elseif($action == 'initfielddetail' && $fieldename){
	if(empty($initfields[$fieldename])){
		amessage('choosefield', '?entry=channels&action=initfieldsedit');
	}
	$field = $initfields[$fieldename];
	if(!submitcheck('binitfielddetail')){
		tabheader(lang('common_field_manager'),'initfielddetail',"?entry=channels&action=initfielddetail&fieldename=$fieldename",2,0,1);
		$submitstr = '';
		list($fmode,$fnew,$fsave) = array('i',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('binitfielddetail');
		check_submit_func($submitstr);
		a_guide('initfielddetail');
	}else{
		$fconfigarr = array(
			'altertable' => $tblprefix.'archives',
			'fieldtable' => $tblprefix.'fields',
			'wherestr' => "WHERE ename='$fieldename' AND chid=0",
		);
		list($fmode,$fnew,$fsave) = array('i',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		adminlog(lang('detail_mac_msg_field'));
		updatecache('initfields');
		if(($field['datatype'] == 'cacc') && ($fieldnew['max'] != $field['max'])){
			$db->query("UPDATE {$tblprefix}fields SET max='$fieldnew[max]' WHERE ename='$field[ename]' AND chid<>'0'");
			foreach($channels as $k => $v) updatecache('fields',$k);
		}
		amessage('fieldmodifyfinish',axaction(6,'?entry=channels&action=initfieldsedit'));
	}
}
function fetch_arr(){
	global $db,$tblprefix;
	$items = array();
	$query = $db->query("SELECT * FROM {$tblprefix}channels ORDER BY vieworder,chid");
	while($item = $db->fetch_array($query)){
		$items[$item['chid']] = $item;
	}
	return $items;
}

?>
