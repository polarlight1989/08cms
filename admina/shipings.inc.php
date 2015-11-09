<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
load_cache('shipings');
if($action == 'shipingadd'){
	if(!submitcheck('bshipingadd')){
		tabheader(lang('addshiping'),'shipingsadd','?entry=shipings&action=shipingadd');
		shipingmodule();
		tabfooter('bshipingadd',lang('add'));
		a_guide('shipingadd');
	}else{
		if($errorstr = shipingmodule(1)){
			amessage($errorstr,'?entry=shipings&action=shipingadd&shipingnew[cclass]='.$shipingnew['cclass']);
		}
		$db->query("INSERT INTO {$tblprefix}shipings SET 
				cname='$shipingnew[cname]',
				freetop='$shipingnew[freetop]',
				basefee='$shipingnew[basefee]',
				plus1mode='$shipingnew[plus1mode]',
				plus1='$shipingnew[plus1]',
				plus2mode='$shipingnew[plus2mode]',
				plus2='$shipingnew[plus2]',
				base1='$shipingnew[base1]',
				price1='$shipingnew[price1]',
				unit1='$shipingnew[unit1]',
				base2='$shipingnew[base2]',
				price2='$shipingnew[price2]',
				unit2='$shipingnew[unit2]'
				");
		updatecache('shipings');
		amessage('shiaddfin', '?entry=shipings&action=shipingsedit');
	}
}elseif($action == 'shipingsedit'){
	$fcclass = empty($fcclass) ? '' : $fcclass;
	if(!submitcheck('bshipingsedit')){
		tabheader(lang('shiiteadm').'&nbsp;&nbsp;&nbsp;&nbsp;[<a href="?entry=shipings&action=shipingadd">'.lang('add').'</a>]','shipingsedit',"?entry=shipings&action=shipingsedit",'7');
		trcategory(array(lang('delete'),lang('available'),lang('soushipname'),lang('order'),lang('freetop'),lang('edit')));
		foreach($shipings as $shid => $shiping){
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$shid]\" value=\"$shid\"></td>\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"shipingsnew[$shid][available]\" value=\"1\"".(empty($shiping['available']) ? '' : ' checked')."></td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"50\" maxlength=\"80\" name=\"shipingsnew[$shid][cname]\" value=\"".mhtmlspecialchars($shiping['cname'])."\"></td>\n".
				"<td class=\"txtC w80\"><input type=\"text\" size=\"5\" maxlength=\"5\" name=\"shipingsnew[$shid][vieworder]\" value=\"".mhtmlspecialchars($shiping['vieworder'])."\"></td>\n".
				"<td class=\"txtC w80\"><input type=\"text\" size=\"5\" maxlength=\"10\" name=\"shipingsnew[$shid][freetop]\" value=\"".mhtmlspecialchars($shiping['freetop'])."\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=shipings&action=shipingsdetail&shid=$shid\">".lang('detail')."</a></td></tr>\n";
		}
		tabfooter('bshipingsedit',lang('modify'));
		a_guide('shipingsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $shid){
				$db->query("DELETE FROM {$tblprefix}shipings WHERE shid='$shid'",'SILENT');
				unset($shipingsnew[$shid]);
			}
		}
		if(!empty($shipingsnew)){
			foreach($shipingsnew as $shid => $shipingnew){
				$shipingnew['cname'] = empty($shipingnew['cname']) ? $shipings[$shid]['cname'] : $shipingnew['cname'];
				$shipingnew['available'] = empty($shipingnew['available']) ? 0 : 1;
				$shipingnew['vieworder'] = max(0,intval($shipingnew['vieworder']));
				$shipingnew['freetop'] = max(0,intval($shipingnew['freetop']));
				$db->query("UPDATE {$tblprefix}shipings SET 
				cname='$shipingnew[cname]',
				vieworder='$shipingnew[vieworder]',
				freetop='$shipingnew[freetop]',
				available='$shipingnew[available]' 
				WHERE shid='$shid'");
			}
		}
		updatecache('shipings');
		adminlog(lang('edishimanlis'));
		amessage('shimodfin', "?entry=shipings&action=shipingsedit");
	}
}
elseif($action == 'shipingsdetail' && $shid){
	empty($shipings[$shid]) && amessage('chooseitem', '?entry=shipings&action=shipingsedit');
	$shiping = $shipings[$shid];
	if(!submitcheck('bshipingsdetail')) {
		tabheader(lang('shipingset'),'shipingsdetail','?entry=shipings&action=shipingsdetail&shid='.$shid);
		shipingmodule();
		tabfooter('bshipingsdetail',lang('modify'));
		a_guide('shipingsdetail');
	}
	else{
		if($errorstr = shipingmodule(1)){
			amessage($errorstr,'?entry=shipings&action=shipingsdetail&shid='.$shid);
		}
		$db->query("UPDATE {$tblprefix}shipings SET 
					cname='$shipingnew[cname]',
					freetop='$shipingnew[freetop]',
					basefee='$shipingnew[basefee]',
					plus1mode='$shipingnew[plus1mode]',
					plus1='$shipingnew[plus1]',
					plus2mode='$shipingnew[plus2mode]',
					plus2='$shipingnew[plus2]',
					base1='$shipingnew[base1]',
					price1='$shipingnew[price1]',
					unit1='$shipingnew[unit1]',
					base2='$shipingnew[base2]',
					price2='$shipingnew[price2]',
					unit2='$shipingnew[unit2]'
					WHERE shid='$shid'");
		updatecache('shipings');
		adminlog(lang('detaimodiship'));
		amessage('shimodfin','?entry=shipings&action=shipingsdetail&shid='.$shid);
	}

}
function shipingmodule($save=0){
	global $shiping,$shipingnew;
	if(!$save){
		trbasic(lang('soushipname'),'shipingnew[cname]',isset($shiping['cname']) ? $shiping['cname'] : '');
		trbasic(lang('freetopyuan'),'shipingnew[freetop]',isset($shiping['freetop']) ? $shiping['freetop'] : '');
		trbasic(lang('basedfeeyuan'),'shipingnew[basefee]',isset($shiping['basefee']) ? $shiping['basefee'] : '');
		trbasic(lang('plusfee1')."<input class=\"checkbox\" type=\"checkbox\" name=\"shipingnew[plus1mode]\" value=\"1\"".(empty($shiping['plus1mode']) ? '' : ' checked').">".lang('pluscontent'),'shipingnew[plus1]',isset($shiping['plus1']) ? $shiping['plus1'] : '');
		trbasic(lang('plusfee2')."<input class=\"checkbox\" type=\"checkbox\" name=\"shipingnew[plus2mode]\" value=\"1\"".(empty($shiping['plus2mode']) ? '' : ' checked').">".lang('pluscontent'),'shipingnew[plus2]',isset($shiping['plus2']) ? $shiping['plus2'] : '');
		trbasic(lang('overw1staweightKg'),'shipingnew[base1]',isset($shiping['base1']) ? $shiping['base1'] : '');
		trbasic(lang('overw1weighKg'),'shipingnew[unit1]',isset($shiping['unit1']) ? $shiping['unit1'] : '');
		trbasic(lang('overw1priyuan'),'shipingnew[price1]',isset($shiping['price1']) ? $shiping['price1'] : '');
		trbasic(lang('overw2stawKg'),'shipingnew[base2]',isset($shiping['base2']) ? $shiping['base2'] : '');
		trbasic(lang('overw2weigKg'),'shipingnew[unit2]',isset($shiping['unit2']) ? $shiping['unit2'] : '');
		trbasic(lang('overw2priyuan'),'shipingnew[price2]',isset($shiping['price2']) ? $shiping['price2'] : '');
	}else{
		if(empty($shipingnew['cname'])) return lang('shipdatamiss');
		$shipingnew['freetop'] = max(0,floatval($shipingnew['freetop']));
		$shipingnew['basefee'] = max(0,floatval($shipingnew['basefee']));
		$shipingnew['plus1mode'] = empty($shipingnew['plus1mode']) ? 0 : 1;
		$shipingnew['plus1'] = max(0,floatval($shipingnew['plus1']));
		$shipingnew['plus2mode'] = empty($shipingnew['plus2mode']) ? 0 : 1;
		$shipingnew['plus2'] = max(0,floatval($shipingnew['plus2']));
		$shipingnew['base1'] = max(0,floatval($shipingnew['base1']));
		$shipingnew['unit1'] = max(0,floatval($shipingnew['unit1']));
		$shipingnew['price1'] = max(0,floatval($shipingnew['price1']));
		$shipingnew['base2'] = max(0,floatval($shipingnew['base2']));
		$shipingnew['unit2'] = max(0,floatval($shipingnew['unit2']));
		$shipingnew['price2'] = max(0,floatval($shipingnew['price2']));
		return '';
	}
}

?>
