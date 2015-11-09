<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('other') || amessage('no_apermission');
$page = empty($page) ? 1 : max(1, intval($page));
if(!submitcheck('bwordlinksadd') && !submitcheck('bwordlinksedit') && !submitcheck('bhotkeywords')){
	tabheader(lang('imphotkey'),'hotkeywords','?entry=wordlinks');
	trbasic(lang('impamomaxlim'),'hotimport[amount]',100);
	trbasic(lang('keyvpclar'),'hotimport[vpcs]',10);
	tabfooter('bhotkeywords',lang('import'));

	tabheader(lang('handaddswo'),'wordlinksadd',"?entry=wordlinks&page=$page");
	trbasic(lang('sword'),'wordlinkadd[sword]');
	trbasic(lang('relateurl'),'wordlinkadd[url]');
	tabfooter('bwordlinksadd',lang('add'));

	$pagetmp = $page;
	do{
		$query = $db->query("SELECT * FROM {$tblprefix}wordlinks ORDER BY wlid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
		$pagetmp--;
	} while(!$db->num_rows($query) && $pagetmp);
	$itemstr = '';
	while($item = $db->fetch_array($query)){
		$itemid = $item['wlid'];
		$item['rword'] = '<a href="'.view_url($item['url']).'" target="_blank">'.lang('look').'</a>';
		$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$itemid]\" value=\"$itemid\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($item['sword'])."</td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"60\" name=\"wordlinksnew[$itemid][url]\" value=\"$item[url]\"></td>\n".
			"<td class=\"txtC w70\">$item[pcs]</td>\n".
			"<td class=\"txtC w45\"><input class=\"checkbox\" type=\"checkbox\" name=\"wordlinksnew[$itemid][available]\" value=\"1\"".($item['available'] ? " checked" : "")."></td>\n".
			"<td class=\"txtC w40\">$item[rword]</td></tr>\n";
	}
	$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}wordlinks");
	$multi = multi($counts, $atpp, $page, "?entry=wordlinks");
	$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}wordlinks WHERE available=1");
	tabheader(lang('swordmanager',$counts),'wordlinksedit',"?entry=wordlinks&page=$page",6);
	trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form,'delete', 'chkall')\">".lang('del'),lang('sword'),lang('relateurl'),lang('vpcs'),lang('enable')."<input class=\"checkbox\" type=\"checkbox\" name=\"chkall1\" onclick=\"checkall(this.form, 'wordlinksnew','chkall1')\">",lang('look')));
	echo $itemstr;
	tabfooter();
	echo $multi;
	echo "<input class=\"button\" type=\"submit\" name=\"bwordlinksedit\" value=\"".lang('modify')."\">";
	a_guide('wordlinks');
}elseif(submitcheck('bwordlinksadd')){
	$wordlinkadd['sword'] = empty($wordlinkadd['sword']) ? '' : trim(strip_tags($wordlinkadd['sword']));
	$wordlinkadd['sword'] = str_replace(array(' ',chr(0xa1).chr(0xa1), chr(0xa1).chr(0x40), chr(0xe3).chr(0x80).chr(0x80)),'',$wordlinkadd['sword']);
	if(empty($wordlinkadd['sword']) || !preg_match('/^([\x7f-\xff_-]|\w){3,20}$/',$wordlinkadd['sword'])){
		amessage('swordillegal',"?entry=wordlinks&page=$page");
	}
	$wordlinkadd['url'] = empty($wordlinkadd['url']) ? '' : trim(strip_tags($wordlinkadd['url']));
	if(empty($wordlinkadd['url'])){
		amessage('pleinpreaurl',"?entry=wordlinks&page=$page");
	}
	$db->query("INSERT INTO {$tblprefix}wordlinks SET 
				sword='$wordlinkadd[sword]',
				url='$wordlinkadd[url]'
				");
	updatecache('wordlinks');
	adminlog(lang('addsword'));
	amessage('swoaddfinish',"?entry=wordlinks&page=$page");

}elseif(submitcheck('bhotkeywords')){
	!$hotkeywords && amessage('hotkeystfunclo');
	$query = $db->query("SELECT keyword,SUM(pcs) AS vpcs FROM {$tblprefix}keywords GROUP BY keyword");
	while($item = $db->fetch_array($query)){
		if($item['vpcs'] != 1){
			$db->query("DELETE FROM {$tblprefix}keywords WHERE keyword='$item[keyword]'");
			$db->query("INSERT INTO {$tblprefix}keywords SET keyword='$item[keyword]',pcs='$item[vpcs]'");
		}
	}
	$hotimport['amount'] = min(200,max(0,intval($hotimport['amount'])));
	$hotimport['amount'] = empty($hotimport['amount']) ? 200 : $hotimport['amount'];
	$hotimport['vpcs'] = max(0,intval($hotimport['vpcs']));
	$wheresql = $hotimport['vpcs'] ? "WHERE pcs>$hotimport[vpcs]" : '';
	$query = $db->query("SELECT * FROM {$tblprefix}keywords $wheresql ORDER BY pcs DESC LIMIT 0,$hotimport[amount]");
	while($item = $db->fetch_array($query)){
		$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}wordlinks WHERE sword='$item[keyword]'");
		if($counts){
			$db->query("UPDATE {$tblprefix}wordlinks SET pcs='$item[pcs]' WHERE sword='$item[keyword]'");
		}else{
			$item['keyword'] = addslashes($item['keyword']);
			$url = 'search.php?searchword='.rawurlencode($item['keyword']).'&searchsubmit=1&searchmode=keywords';
			$url = addslashes($url);
			$db->query("INSERT INTO {$tblprefix}wordlinks SET sword='$item[keyword]',pcs='$item[pcs]',url='$url'");
		}
	}
	adminlog(lang('imphotkey'));
	updatecache('wordlinks');
	amessage('keyimpfinish',"?entry=wordlinks");
}elseif(submitcheck('bwordlinksedit')){
	if(!empty($delete)){
		foreach($delete as $k){
			$db->query("DELETE FROM {$tblprefix}wordlinks WHERE wlid=$k");
			unset($wordlinksnew[$k]);
		}
	}
	if(!empty($wordlinksnew)){
		foreach($wordlinksnew as $wlid => $wordlinknew){
			$wordlinknew['url'] = empty($wordlinknew['url']) ? '' : trim(strip_tags($wordlinknew['url']));
			if(!empty($wordlinknew['url'])){
				$wordlinknew['available'] = empty($wordlinknew['available']) ? 0 : 1;
				$db->query("UPDATE {$tblprefix}wordlinks SET
							url='$wordlinknew[url]',
							available='$wordlinknew[available]'
							WHERE wlid=$wlid");
			}
		}
	}
	adminlog(lang('edswomanli'));
	updatecache('wordlinks');
	amessage('swmodfin',"?entry=wordlinks&page=$page");
}
?>
