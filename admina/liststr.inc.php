<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
load_cache('commus,mcommus,matypes');
$tclass = empty($tclass) ? 'archives' : $tclass;
$tclassarr = array(
	'archives' => lang('archive_list'),
	'alarchives' => lang('inaarcli'),
	'members' => lang('memberlist'),
	'farchives' => lang('freelist'),
	'coclass' => lang('class_condition_set'),
	'albums' => lang('inalbumlist'),
	'catalogs' => lang('cataslist'),
);
$tablearr = array(
	'archives' => array('archives' => 'a.','archives_rec' => 'r.',),
	'alarchives' => array('archives' => 'a.','archives_rec' => 'r.',),
	'members' => array('members' => 'm.','members_sub' => 's.',),
	'farchives' => array('farchives' => 'a.',),
	'coclass' => array('archives' => '{$pre}',),
	'albums' => array('archives' => 'a.',),
	'catalogs' => array('catalogs' => '','coclass' => '',),
);

foreach($commus as $k => $v){
	if($v['available'] && $v['sortable']){
		$tclassarr['commus_'.$k] = $v['cname'].lang('list');
		$tablearr['commus_'.$k] = array($v['cclass'].'s' => '',);
	}
}
foreach($mcommus as $k => $v){
	if($v['available'] && $v['sortable']){
		$tclassarr['mcommus_'.$k] = 'm'.$v['cname'].lang('list');
		$tablearr['mcommus_'.$k] = array('m'.$v['cclass'].'s' => '',);
	}
}
foreach($matypes as $k => $v){
	$tclassarr['marchives_'.$k] = $v['cname'].lang('list');
	$tablearr['marchives_'.$k] = array('marchives_'.$k => 'ma.',);
}


$orderbyarr = array('' => '','ASC' => lang('asc'),'DESC' => lang('desc'),);
$dbtypearr = array(1 => array('text','mediumtext','longtext','char','varchar','tinytext',), 2 => array('tinyint','smallint','int','mediumint','bigint','float','double','decimal','bit','bool','binary',));
$modearr = array(
	'=' => 0,
	'>' => 1,
	'>=' => 1,
	'<' => 1,
	'<=' => 1,
	'!=' => 0,
	'LIKE' => 0,
	'NOT LIKE' => 0,
	'LIKE %...%' => 2,
	'LIKE %...' => 2,
	'LIKE ...%' => 2,
	'REGEXP' => 2,
	'NOT REGEXP' => 2,
	'IS NULL' => 0,
	'IS NOT NULL' => 0,
);
$filterbox = lang('choose_list_tag_type').'&nbsp; :&nbsp; ';
$filterbox .= "<select style=\"vertical-align: middle;\" name=\"tclass\" onchange=\"redirect('?entry=liststr&tclass=' + this.options[this.selectedIndex].value);\">";
foreach($tclassarr as $k => $v){
	$filterbox .= "<option value=\"$k\"".($tclass == $k ? ' selected' : '').">$v</option>";
}
$filterbox .= "</select>";
tabheader($filterbox);
tabfooter();
if(!empty($dbnews) && $tclass){
	$wherestr = $orderstr = '';
	$orderarr = array();
	foreach($dbnews as $k => $v){
		if(!empty($v['mode'])){
			if(in_array($v['mode'],array('IS NULL','IS NOT NULL',))){
				$wherestr .= ($wherestr ? ' AND ' : '').$k.' '.$v['mode'];
			}elseif(in_array($v['mode'],array('LIKE','NOT LIKE','REGEXP','NOT REGEXP',)) && $v['value'] != ''){
				$wherestr .= ($wherestr ? ' AND ' : '').$k." ".$v['mode']." '".$v['value']."'";
			}elseif(in_array($v['mode'],array('LIKE %...%','LIKE ...%','LIKE %...',)) && $v['value'] != ''){
				$wherestr .= ($wherestr ? ' AND ' : '').$k." ".str_replace(array('%...%','...%','%...'),array("'%".$v['value']."%'","'".$v['value']."%'","'%".$v['value']."'"),$v['mode']);
			}else{
				$wherestr .= ($wherestr ? ' AND ' : '').$k.' '.$v['mode']." '".$v['value']."'";
			}
		}
		if(!empty($v['order'])){
			$orderarr[$k.' '.$v['order']] = intval($v['prior']);
		}
	}
	if(!empty($orderarr)){
		asort($orderarr);
		foreach($orderarr as $k => $v) $orderstr .= ($orderstr ? ',' : '').$k;
	}
	tabheader(lang('query_str_result'));
	trbasic(lang('filter_string'),'view_wherestr',$wherestr,'textarea');
	trbasic(lang('order_string'),'view_orderstr',$orderstr,'textarea');
	tabfooter();
}

tabheader(lang('ct_listtag_querystring'),'liststr',"?entry=liststr&tclass=$tclass",7);
trcategory(array(lang('sn'),lang('field_name'),lang('field_type'),('filter0 mode'),lang('value'),lang('order'),lang('order_prior')));
foreach($tablearr[$tclass] as $dbtable => $pre){
	echo "<tr class=\"txt\"><td class=\"txtC\" colspan=\"7\">".lang('table')."&nbsp; -&nbsp; <b>$dbtable</b></td></tr>";
	$query = $db->query("SHOW FULL COLUMNS FROM {$tblprefix}$dbtable",'SILENT');
	$tblfields = array();
	while($row = $db->fetch_array($query)){
		$types = explode(' ',$row['Type']);
		$tblfields[$row['Field']] = strtolower($types[0]);
	}
	$i = 1;
	foreach($tblfields as $k => $v){
		$var = $pre.$k;
		echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\">$i</td>\n".
			"<td class=\"txtL\"><b>$k</b></td>\n".
			"<td class=\"txtL\">$v</td>\n".
			"<td class=\"txtC\"><select style=\"vertical-align: middle;\" name=\"dbnews[$var][mode]\">".makeoption(thismodearr($v),empty($dbnews[$var]['mode']) ? '' : $dbnews[$var]['mode'])."</select></td>\n".
			"<td class=\"txtC\"><input type=\"text\" size=\"20\" name=\"dbnews[$var][value]\" value=\"".(empty($dbnews[$var]['value']) ? '' : mhtmlspecialchars(stripslashes($dbnews[$var]['value'])))."\"></td>\n".
			"<td class=\"txtC w50\"><select style=\"vertical-align: middle;\" name=\"dbnews[$var][order]\">".makeoption($orderbyarr,empty($dbnews[$var]['order']) ? '' : $dbnews[$var]['order'])."</select></td>\n".
			"<td class=\"txtC w80\"><input type=\"text\" size=\"4\" name=\"dbnews[$var][prior]\" value=\"".(empty($dbnews[$var]['prior']) ? 0 : mhtmlspecialchars(stripslashes($dbnews[$var]['prior'])))."\"></td>\n".
			"</tr>";
		$i ++;
	}

}
tabfooter('bliststr',lang('create'));
function thismodearr($type){
	global $modearr,$dbtypearr;
	$type = str_replace(strstr($type,'('),'',$type);
	$retarr = array('' => '');
	foreach($modearr as $k => $v){
		if(!$v || !in_array($type,$dbtypearr[$v])) $retarr[$k] = $k;
	}
	return $retarr;
}
?>