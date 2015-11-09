<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cfmcommu') || amessage('no_apermission');
load_cache('grouptypes,currencys,rprojects,channels,permissions');
sys_cache('fieldwords');
load_cache('mtpls',$sid);
include_once M_ROOT."./include/fields.fun.php";
$cclassarr = array(
	'score' => lang('score'),
	'friend' => lang('friend'),
	'flink' => lang('flink'),
	'comment' => lang('comment'),
	'reply' => lang('reply'),
	'report' => lang('pickbug'),
	'favorite' => lang('favorite'),
	);
$action = empty($action) ? 'mcommusedit' : $action;
$url_type = 'mcufield';include 'urlsarr.inc.php';
if($action == 'mcommusedit'){
	$mcommus = fetch_arr();
	if(!submitcheck('bmcommusedit')){
		url_nav(lang('memberinterconfig'),$urlsarr,'mcommu');

		tabheader(lang('memcomitad'),'mcommusedit',"?entry=mcommus&action=mcommusedit",'7');
		trcategory(array(lang('delete'),lang('enable'),array(lang('item_name'),'txtL'),lang('type'),array(lang('pick_url_style'),'txtL'),lang('copy'),lang('edit')));
		foreach($mcommus as $cuid => $mcommu){
			$cclass = $cclassarr[$mcommu['cclass']];
			$pickurl = '{$mspaceurl}'.$mcommu['cclass'].'.php?mid={mid}';
			if($mcommu['cclass'] == 'score') $pickurl .= '&score=xx (xx-'.lang('score_amount').')';
			echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$cuid]\" value=\"$cuid\"".($mcommu['issystem'] || $sid ? ' disabled' : '')."></td>\n".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"mcommusnew[$cuid][available]\" value=\"1\"".(empty($mcommu['available']) ? '' : ' checked')."></td>\n".
			"<td class=\"txtL w200\"><input type=\"text\" size=\"30\" maxlength=\"30\" name=\"mcommusnew[$cuid][cname]\" value=\"$mcommu[cname]\"></td>\n".
			"<td class=\"txtC w60\">$cclass</td>\n".
			"<td class=\"txtL\">$pickurl</td>\n".
			"<td class=\"txtC w30\">".($mcommu['ch'] ? "<a href=\"?entry=mcommus&action=mcommucopy&cuid=$cuid\" onclick=\"return floatwin('open_mcommusedit',this)\">".lang('copy')."</a>" : '-')."</td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=mcommus&action=mcommudetail&cuid=$cuid\" onclick=\"return floatwin('open_mcommusedit',this)\">".lang('detail')."</a></td></tr>\n";
		}
		tabfooter('bmcommusedit',lang('modify'));
		a_guide('mcommusedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $cuid){
				if($mcommus[$cuid]['issystem']) continue;
				if($mcommus[$cuid]['ch']){
					if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}m".$mcommus[$cuid]['cclass']."s WHERE cuid='$cuid'")) continue;//可删除项目需要检查是否有相关的交互记录
				}
				$db->query("DELETE FROM {$tblprefix}mcommus WHERE cuid='$cuid'");
				unset($mcommusnew[$cuid]);
			}
		}
		if(!empty($mcommusnew)){
			foreach($mcommusnew as $cuid => $mcommunew){
				$mcommunew['cname'] = empty($mcommunew['cname']) ? $mcommus[$cuid]['cname'] : $mcommunew['cname'];
				$mcommunew['available'] = empty($mcommunew['available']) ? 0 : 1;
				$db->query("UPDATE {$tblprefix}mcommus SET cname='$mcommunew[cname]',available='$mcommunew[available]' WHERE cuid='$cuid'");
			}
		}
		updatecache('mcommus');	
		adminlog(lang('edmemcomitmanli'));
		amessage('itemmodifyfinish', "?entry=mcommus&action=mcommusedit");
	}
}elseif($action == 'mcommucopy' && $cuid){
	$mcommu = $db->fetch_one("SELECT * FROM {$tblprefix}mcommus WHERE cuid='$cuid'");
	if(empty($mcommu) || !$mcommu['ch']) amessage('chooseitem', '?entry=mcommus&action=mcommusedit');
	if(!submitcheck('bmcommucopy')){
		tabheader(lang('memcomitco'),'mcommucopy',"?entry=mcommus&action=mcommucopy&cuid=$cuid");
		trbasic(lang('soc_citem_name'),'',$mcommu['cname'],'');
		trbasic(lang('soc_citem_type'),'',$cclassarr[$mcommu['cclass']],'');
		trbasic(lang('new_citem_name'),'mcommunew[cname]');
		tabfooter('bmcommucopy');
		a_guide('mcommucopy');
	}else{
		$mcommunew['cname'] = empty($mcommunew['cname']) ? '' : trim(strip_tags($mcommunew['cname']));
		empty($mcommunew['cname']) && amessage('inputcommuname',M_REFERER);
		$sqlstr = "cname='$mcommunew[cname]'";
		foreach($mcommu as $k => $v) if(!in_array($k,array('cuid','cname','issystem','uconfig'))) $sqlstr .= ",$k='".addslashes($v)."'";
		$db->query("INSERT INTO {$tblprefix}mcommus SET $sqlstr");
		$cuid = $db->insert_id();
		updatecache('mcommus');
		adminlog(lang('copymcomitem'));
		amessage('mcommucopyfinish',"?entry=mcommus&action=mcommudetail&cuid=$cuid");
	}
}elseif($action == 'mcommudetail' && $cuid){
	$mcommu = fetch_one($cuid);
	empty($mcommu) && amessage('chooseitem');
	if(!submitcheck('bmcommudetail')) {
		tabheader(lang('memcomitset'),'mcommudetail',"?entry=mcommus&action=mcommudetail&cuid=$cuid",2,0,0,1);
		trbasic(lang('item_type'),'',$cclassarr[$mcommu['cclass']],'');
		trbasic(lang('item_name'),'mcommunew[cname]',$mcommu['cname']);
		if(!empty($mcommu['usetting'])){
			$str = '';
			foreach($mcommu['usetting'] as $k => $v) $str .= $k.'='.$v."\n";	
			$mcommu['usetting'] = $str;
			unset($str);
		}
		include M_ROOT.'./include/mcommus/'.$mcommu['cclass'].'.php';
		tabfooter('bmcommudetail',lang('modify'));
		a_guide('mcommudetail');
	}else{
		$submitmode = true;
		@include M_ROOT.'./include/mcommus/'.$mcommu['cclass'].'.php';
		$mcommunew['cname'] = empty($mcommunew['cname']) ? $mcommu['cname'] : $mcommunew['cname'];
		$mcommunew['cutpl'] = empty($mcommunew['cutpl']) ? '' : $mcommunew['cutpl'];
		$mcommunew['addtpl'] = empty($mcommunew['addtpl']) ? '' : $mcommunew['addtpl'];
		$mcommunew['func'] = empty($mcommunew['func']) ? '' : $mcommunew['func'];
		$mcommunew['setting'] = !empty($mcommunew['setting']) ? addslashes(serialize($mcommunew['setting'])) : '';
		$mcommunew['ucadd'] = empty($mcommunew['ucadd']) ? '' : trim($mcommunew['ucadd']);
		$mcommunew['ucvote'] = empty($mcommunew['ucvote']) ? '' : trim($mcommunew['ucvote']);
		$mcommunew['uadetail'] = empty($mcommunew['uadetail']) ? '' : trim($mcommunew['uadetail']);
		$mcommunew['umdetail'] = empty($mcommunew['umdetail']) ? '' : trim($mcommunew['umdetail']);
		if(!empty($mcommunew['usetting'])){
			$mcommunew['usetting'] = str_replace("\r","",$mcommunew['usetting']);
			$temps = explode("\n",$mcommunew['usetting']);
			$mcommunew['usetting'] = array();
			foreach($temps as $v){
				$temparr = explode('=',str_replace(array("\r","\n"),'',$v));
				if(!isset($temparr[1]) || !($temparr[0] = trim($temparr[0]))) continue;
				$mcommunew['usetting'][$temparr[0]] = trim($temparr[1]);
			}
			unset($temps,$temparr);
		}
		$mcommunew['usetting'] = !empty($mcommunew['usetting']) ? addslashes(serialize($mcommunew['usetting'])) : '';
		$db->query("UPDATE {$tblprefix}mcommus SET 
					cname='$mcommunew[cname]',
					setting='$mcommunew[setting]',
					cutpl='$mcommunew[cutpl]',
					addtpl='$mcommunew[addtpl]',
					usetting='$mcommunew[usetting]',
					ucadd='$mcommunew[ucadd]',
					ucvote='$mcommunew[ucvote]',
					uadetail='$mcommunew[uadetail]',
					umdetail='$mcommunew[umdetail]',
					func='$mcommunew[func]'
					WHERE cuid='$cuid'");
		updatecache('mcommus');
		adminlog(lang('demomecomit'));
		amessage('itemmodifyfinish',axaction(10,"?entry=mcommus&action=mcommudetail&cuid=$cuid"));
	}

}
function fetch_arr(){
	global $db,$tblprefix;
	$mcommus = array();
	$query = $db->query("SELECT * FROM {$tblprefix}mcommus WHERE isbk='0' ORDER BY issystem DESC,cuid ASC");
	while($mcommu = $db->fetch_array($query)){
		if($mcommu['setting'] && is_array($setting = unserialize($mcommu['setting']))){$mcommu['setting'] = $setting;}
		else{$mcommu['setting'] = array();}
		$mcommus[$mcommu['cuid']] = $mcommu;
	}
	return $mcommus;
}
function fetch_one($cuid){
	global $db,$tblprefix;
	$mcommu = $db->fetch_one("SELECT * FROM {$tblprefix}mcommus WHERE cuid='$cuid'");
	foreach(array('setting','usetting') as $var){
		if($mcommu[$var] && is_array($setting = unserialize($mcommu[$var]))){$mcommu[$var] = $setting;}
		else{$mcommu[$var] = array();}
	}
	return $mcommu;
}
?>
