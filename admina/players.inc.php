<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('webparam') || amessage('no_apermission');
load_cache('players');
$ptypearr = array('media' => lang('mediaplayer'),'flash' => lang('flashplayer'));
$url_type = 'mconfig';include 'urlsarr.inc.php';
url_nav(lang('webparam'),$urlsarr,'player',12);
if($action == 'playersedit'){
	if(!submitcheck('bplayersedit') && !submitcheck('bplayeradd')) {
		tabheader(lang('playermanager'),'playersedit','?entry=players&action=playersedit','7');
		trcategory(array(lang('delete'),lang('available'),lang('playercname'),lang('playertype'),lang('defplayfileformat'),lang('order'),lang('detail')));
		foreach($players as $plid => $player){
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$plid]\" value=\"$plid\"".(!empty($player['issystem']) ? ' disabled' : '')."></td>\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"playersnew[$plid][available]\" value=\"1\"".(!empty($player['available']) ? ' checked' : '')."></td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"25\" maxlength=\"30\" name=\"playersnew[$plid][cname]\" value=\"$player[cname]\"></td>\n".
				"<td class=\"txtC w100\">".$ptypearr[$player['ptype']]."</td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"25\" maxlength=\"50\" name=\"playersnew[$plid][exts]\" value=\"$player[exts]\"></td>\n".
				"<td class=\"txtC w50\"><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"playersnew[$plid][vieworder]\" value=\"$player[vieworder]\"></td>\n".
				"<td class=\"txtC w50\"><a href=\"?entry=players&action=playerdetail&plid=$plid\">".lang('setting')."</a></td>\n".
				"</tr>\n";
		}
		tabfooter('bplayersedit',lang('modify'));
	
		tabheader(lang('addplayer'),'playeradd','?entry=players&action=playersedit');
		trbasic(lang('playercname'),'playeradd[cname]');
		trbasic(lang('playertype'),'playeradd[ptype]',makeoption($ptypearr),'select');
		trbasic(lang('defplayfileformat'),'playeradd[exts]');
		tabfooter('bplayeradd',lang('add'));
		a_guide('playersedit');
	}
	elseif(submitcheck('bplayeradd')){
		if(!$playeradd['cname']) {
			amessage('inpplanam', '?entry=players&action=playersedit');
		}
		if(preg_match("/[^a-z,A-Z0-9]+/",$playeradd['exts'])){
			amessage('fileextill', '?entry=players&action=playersedit');
		}
		$playeradd['exts'] = strtolower($playeradd['exts']);
	
		$db->query("INSERT INTO {$tblprefix}players SET 
					cname='$playeradd[cname]',
					ptype='$playeradd[ptype]',
					exts='$playeradd[exts]',
					available='1'
					");
		updatecache('players');
		adminlog(lang('addmedplay'),lang('addmedplay'));
		amessage('playaddfin','?entry=players&action=playersedit');
	
	}elseif(submitcheck('bplayersedit')){
		if(!empty($delete)){
			foreach($delete as $plid){
				$db->query("DELETE FROM {$tblprefix}players WHERE plid=$plid");
				unset($playersnew[$plid]);
			}
		}
		foreach($playersnew as $plid => $playernew){
			$playernew['cname'] = empty($playernew['cname']) ? $players[$plid]['cname'] : $playernew['cname'];
			$playernew['exts'] = preg_match("/[^a-z,A-Z0-9]+/",$playernew['exts']) ? $players[$plid]['exts'] : strtolower($playernew['exts']);
			$playernew['available'] = empty($playernew['available']) ? 0 : $playernew['available'];
			$db->query("UPDATE {$tblprefix}players SET 
						cname='$playernew[cname]',
						exts='$playernew[exts]',
						available='$playernew[available]',
						vieworder='$playernew[vieworder]' 
						WHERE plid='$plid'");
		}
		updatecache('players');
		adminlog(lang('edimed'),lang('edimedplalis'));
		amessage('playedifin','?entry=players&action=playersedit');
	}
}elseif($action == 'playerdetail' && !empty($plid)){
	empty($players[$plid]) && amessage('choosepla','?entry=players&action=playersedit');
	$player = read_cache('player',$plid);
	if(!submitcheck('bplayerdetail')){
		tabheader(lang('playerset'),'playerdetail','?entry=players&action=playerdetail&plid='.$plid);
		trbasic(lang('playercname'),'playernew[cname]',$player['cname'],'text');
		trbasic(lang('playertype'),'',$ptypearr[$player['ptype']],'');
		trbasic(lang('defplayfileformat'),'playernew[exts]',$player['exts'],'text');
		echo "<tr class=\"txt\"><td class=\"txtL\">".lang('playertemplate')."</td><td class=\"txtL\"><textarea rows=\"25\" name=\"playernew[template]\" id=\"playernew[template]\" cols=\"100\">".mhtmlspecialchars(str_replace("\t","    ",$player['template']))."</textarea></td></tr>";
		tabfooter('bplayerdetail');
		a_guide('playerdetail');
	}else{
		if(!$playernew['template']) {
			amessage('inpplatem','?entry=players&action=playerdetail&plid='.$plid);
		}
		$playernew['cname'] = empty($playernew['cname']) ? $players[$plid]['cname'] : $playernew['cname'];
		$playernew['exts'] = preg_match("/[^a-z,A-Z0-9]+/",$playernew['exts']) ? $players[$plid]['exts'] : strtolower($playernew['exts']);
		$db->query("UPDATE {$tblprefix}players SET 
					cname='$playernew[cname]',
					exts='$playernew[exts]',
					template='$playernew[template]' 
					WHERE plid='$plid'");
		updatecache('players');
		adminlog(lang('detmodmedpla'),lang('detmodmedpla'));
		amessage('playmodfin','?entry=players&action=playersedit');

	}
}

?>
