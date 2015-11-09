<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('tpl') || amessage('no_apermission');
load_cache('dbsources');
$charsetarr = array('gbk' => 'GBK','big5' => 'BIG5','utf8' => 'UTF-8','latin1' => 'Latin1',);
$url_type = 'othertpl';include 'urlsarr.inc.php';
if($action == 'dbsourcesedit'){
	url_nav(lang('tplrelated'),$urlsarr,'db');
	if(!submitcheck('bdbsourcesedit') && !submitcheck('bdbsourceadd')){
		tabheader(lang('db_src_manager'),'dbsourcesedit','?entry=dbsources&action=dbsourcesedit','10');
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),lang('db_src_name'),lang('db_server'),lang('db_user'),lang('db_name'),lang('db_charset'),lang('db_config'),lang('detail')));
		foreach($dbsources as $k => $dbsource){
			echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\"></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"dbsourcesnew[$k][cname]\" value=\"$dbsource[cname]\"></td>\n".
			"<td class=\"txtC\">$dbsource[dbhost]</td>\n".
			"<td class=\"txtC\">$dbsource[dbuser]</td>\n".
			"<td class=\"txtC\">$dbsource[dbname]</td>\n".
			"<td class=\"txtC\">".$charsetarr[$dbsource['dbcharset']]."</td>\n".
			"<td class=\"txtC\"><a href=\"?entry=dbsources&action=viewconfigs&dsid=$k\" target=\"_blank\">".lang('look')."</a></td>\n".
			"<td class=\"txtC w40\"><a href=\"?entry=dbsources&action=dbsourcedetail&dsid=$k\">".lang('setting')."</a></td>\n".
			"</tr>";
		}
		tabfooter('bdbsourcesedit');

		tabheader(lang('add_db_src'),'dbsourceadd',"?entry=dbsources&action=dbsourcesedit");
		trbasic(lang('db_src_name'),'dbsourcenew[cname]');
		trbasic(lang('db_server'),'dbsourcenew[dbhost]');
		trbasic(lang('db_user'),'dbsourcenew[dbuser]');
		trbasic(lang('db_pwd'),'dbsourcenew[dbpw]');
		trbasic(lang('db_name'),'dbsourcenew[dbname]');
		trbasic(lang('db_charset'),'dbsourcenew[dbcharset]',makeoption($charsetarr),'select');
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdbsourceadd\" value=\"".lang('add')."\">&nbsp; &nbsp;".
		"<input class=\"button\" type=\"submit\" name=\"dbcheck\" value=\"".lang('dbcheck')."\" onclick=\"this.form.action='?entry=checks&action=dbcheck&deal=add';this.form.target='dbcheckiframe';\"><iframe name=\"dbcheckiframe\" style=\"display: none\"></iframe>".
		"</form>";
		a_guide('dbsourcesedit');
	
	}elseif(submitcheck('bdbsourceadd')){
		$dbsourcenew['cname'] = trim(strip_tags($dbsourcenew['cname']));
		$dbsourcenew['dbhost'] = trim(strip_tags($dbsourcenew['dbhost']));
		$dbsourcenew['dbuser'] = trim(strip_tags($dbsourcenew['dbuser']));
		$dbsourcenew['dbname'] = trim(strip_tags($dbsourcenew['dbname']));
		if(empty($dbsourcenew['cname']) || empty($dbsourcenew['dbhost']) || empty($dbsourcenew['dbuser']) || empty($dbsourcenew['dbname'])){
			amessage('datamissing', '?entry=dbsources&action=dbsourcesedit');
		}
		$dbsourcenew['dbpw'] = trim($dbsourcenew['dbpw']);
		$dbsourcenew['dbpw'] = empty($dbsourcenew['dbpw']) ? '' : authcode(trim($dbsourcenew['dbpw']),'ENCODE',md5($authkey));
		$db->query("INSERT INTO {$tblprefix}dbsources SET 
					cname='$dbsourcenew[cname]', 
					dbhost='$dbsourcenew[dbhost]', 
					dbuser='$dbsourcenew[dbuser]', 
					dbpw='$dbsourcenew[dbpw]', 
					dbname='$dbsourcenew[dbname]', 
					dbcharset='$dbsourcenew[dbcharset]'
					");
		adminlog(lang('add_db_src'));
		updatecache('dbsources');
		amessage('dbaddfin','?entry=dbsources&action=dbsourcesedit');
	}elseif(submitcheck('bdbsourcesedit')){
		if(!empty($delete)){
			foreach($delete as $k) {
				$db->query("DELETE FROM {$tblprefix}dbsources WHERE dsid='$k'");
				unset($dbsourcesnew[$k]);
			}
		}

		if(!empty($dbsourcesnew)){
			foreach($dbsourcesnew as $k => $v){
				$v['cname'] = empty($v['cname']) ? $dbsources[$k]['cname'] : $v['cname'];
				if($v['cname'] != $dbsources[$k]['cname']){
					$db->query("UPDATE {$tblprefix}dbsources SET
								cname='$v[cname]'
								WHERE dsid='$k'");
				}
			}
		}
		adminlog(lang('edit_db_src'));
		updatecache('dbsources');
		amessage('dbmodfin','?entry=dbsources&action=dbsourcesedit');
	}
}elseif($action == 'dbsourcedetail' && $dsid){
	url_nav(lang('tplrelated'),$urlsarr,'db');
	empty($dbsources[$dsid]) && amessage('choosedbs','?entry=dbsources&action=dbsourcesedit');
	$dbsource = $dbsources[$dsid];
	$dbsource['vdbpw'] = $dbsource['tdbpw'] = '';
	if(!empty($dbsource['dbpw'])){
		$dbsource['tdbpw'] = authcode($dbsource['dbpw'],'DECODE',md5($authkey));
		$dbsource['vdbpw'] = $dbsource['tdbpw']{0}.'********'.$dbsource['tdbpw']{strlen($dbsource['tdbpw']) - 1};
	}
	if(!submitcheck('bdbsourcedetail')){
		tabheader(lang('edit_db_src'),'dbsourcedetail',"?entry=dbsources&action=dbsourcedetail");
		trbasic(lang('db_src_name'),'dbsourcenew[cname]',$dbsource['cname']);
		trbasic(lang('db_server'),'dbsourcenew[dbhost]',$dbsource['dbhost']);
		trbasic(lang('db_user'),'dbsourcenew[dbuser]',$dbsource['dbuser']);
		trbasic(lang('db_pwd'),'dbsourcenew[dbpw]',$dbsource['vdbpw']);
		echo "<input type=\"hidden\" name=\"dbsourcenew[dbpw0]\" value=\"$dbsource[dbpw]\">\n";
		echo "<input type=\"hidden\" name=\"dsid\" value=\"$dsid\">\n";
		trbasic(lang('db_name'),'dbsourcenew[dbname]',$dbsource['dbname']);
		trbasic(lang('db_charset'),'dbsourcenew[dbcharset]',makeoption($charsetarr,$dbsource['dbcharset']),'select');
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdbsourcedetail\" value=\"".lang('modify')."\">&nbsp; &nbsp;".
		"<input class=\"button\" type=\"submit\" name=\"dbcheck\" value=\"".lang('dbcheck')."\" onclick=\"this.form.action='?entry=checks&action=dbcheck&deal=edit';this.form.target='dbcheckiframe';\"><iframe name=\"dbcheckiframe\" style=\"display: none\"></iframe>".
		"</form>";
		a_guide('dbsourcedetail');
	}else{
		$dbsourcenew['cname'] = trim(strip_tags($dbsourcenew['cname']));
		$dbsourcenew['dbhost'] = trim(strip_tags($dbsourcenew['dbhost']));
		$dbsourcenew['dbuser'] = trim(strip_tags($dbsourcenew['dbuser']));
		$dbsourcenew['dbname'] = trim(strip_tags($dbsourcenew['dbname']));
		if(empty($dbsourcenew['cname']) || empty($dbsourcenew['dbhost']) || empty($dbsourcenew['dbuser']) || empty($dbsourcenew['dbname'])){
			amessage('datamissing', '?entry=dbsources&action=dbsourcesedit');
		}
		if($dbsourcenew['dbpw'] == $dbsource['vdbpw']){
			$dbsourcenew['dbpw'] = $dbsource['dbpw'];
		}else{
			$dbsourcenew['dbpw'] = trim($dbsourcenew['dbpw']);
			$dbsourcenew['dbpw'] = empty($dbsourcenew['dbpw']) ? '' : authcode(trim($dbsourcenew['dbpw']),'ENCODE',md5($authkey));
		}
		$db->query("UPDATE {$tblprefix}dbsources SET 
					cname='$dbsourcenew[cname]', 
					dbhost='$dbsourcenew[dbhost]', 
					dbuser='$dbsourcenew[dbuser]', 
					dbpw='$dbsourcenew[dbpw]', 
					dbname='$dbsourcenew[dbname]', 
					dbcharset='$dbsourcenew[dbcharset]'
					WHERE dsid='$dsid'
					");
		adminlog(lang('det_modify_db_src'));
		updatecache('dbsources');
		amessage('dbmodfin','?entry=dbsources&action=dbsourcesedit');
	}
}elseif($action == 'viewconfigs'){
	$dsid = empty($dsid) ? 0 : max(0,intval($dsid));
	$dbtable = empty($dbtable) ? '' : trim($dbtable);
	if($dsid && empty($dbsources[$dsid])) amessage('choosedbs',$forward);
	if(!$dsid){
		$ndb = &$db;
		$dbsource['cname'] = lang('current_system');
		$dbsource['dbname'] = $dbname;
	}else{
		$dbsource = $dbsources[$dsid];
		$dbsource['dbpw'] && $dbsource['dbpw'] = authcode($dbsource['dbpw'],'DECODE',md5($authkey));
		if(empty($dbsource['cname']) || empty($dbsource['dbhost']) || empty($dbsource['dbuser']) || empty($dbsource['dbname'])){
			amessage('dbdatamis');
		}
		$ndb = new cls_mysql;
		if(!$ndb->connect($dbsource['dbhost'],$dbsource['dbuser'],$dbsource['dbpw'],$dbsource['dbname'],0,false,$dbsource['dbcharset'])){
			amessage('dbconerr');
		}
	}

	$dbtables = array('' => lang('select_table'));
	$query = $ndb->query("SHOW TABLES FROM $dbsource[dbname]");
	while($v = $ndb->fetch_row($query)){
		$dbtables[$v[0]] = $v[0];
	}
	$dsidsarr = array(0 => lang('current_system'));
	foreach($dbsources as $k => $v) $dsidsarr[$k] = $v['cname'];
	$filterbox = lang('choose_db_src').'&nbsp; :&nbsp; ';
	$filterbox .= "<select style=\"vertical-align: middle;\" name=\"dsid\" onchange=\"redirect('?entry=dbsources&action=viewconfigs&dsid=' + this.options[this.selectedIndex].value);\">";
	foreach($dsidsarr as $k => $v){
		$filterbox .= "<option value=\"$k\"".($dsid == $k ? ' selected' : '').">$v</option>";
	}
	$filterbox .= "</select>";			
	$filterbox .= '&nbsp; &nbsp; &nbsp; '.lang('choose_table').'&nbsp; &nbsp;';
	$filterbox .= "<select style=\"vertical-align: middle;\" name=\"dbtable\" onchange=\"redirect('?entry=dbsources&action=viewconfigs&dsid=$dsid&dbtable=' + this.options[this.selectedIndex].value);\">";
	foreach($dbtables as $k => $v){
		$filterbox .= "<option value=\"$k\"".($dbtable == $k ? ' selected' : '').">$v</option>";
	}
	$filterbox .= "</select>";			
	tabheader($filterbox);
	tabfooter();
	$tblfields = array();
	if($dbtable){
		$query = $ndb->query("SHOW FULL COLUMNS FROM $dbtable",'SILENT');
		while($row = $ndb->fetch_array($query)){
			$types = explode(' ',$row['Type']);
			$tblfields[$row['Field']] = strtolower($types[0]);
		}
	}
	tabheader(lang('create_query_string'),'dbsqlstr',"?entry=dbsources&action=viewconfigs&dsid=$dsid&dbtable=$dbtable",8);
	trcategory(array(lang('sn'),lang('field_name'),lang('field_type'),'<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('choose'),lang('query_mode'),lang('value'),lang('order'),lang('order_prior')));
	$i = 1;
	$orderarr = array('' => '','ASC' => lang('asc'),'DESC' => lang('desc'),);
	$dbtypearr = array(1 => array('text','mediumtext','longtext','char','varchar','tinytext',),
				2 => array('tinyint','smallint','int','mediumint','bigint','float','double','decimal','bit','bool','binary',));
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
	foreach($tblfields as $k => $v){
		echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\">$i</td>\n".
			"<td class=\"txtL\"><b>$k</b></td>\n".
			"<td class=\"txtL\">$v</td>\n".
			"<td class=\"txtC w45\"><input class=\"checkbox\" type=\"checkbox\" name=\"dbnews[$k][adopt]\" value=\"1\"".(empty($dbnews[$k]['adopt']) ? '' : ' checked').">\n".
			"<td class=\"txtC\"><select style=\"vertical-align: middle;\" name=\"dbnews[$k][mode]\">".makeoption(thismodearr($v),empty($dbnews[$k]['mode']) ? '' : $dbnews[$k]['mode'])."</select></td>\n".
			"<td class=\"txtC\"><input type=\"text\" size=\"20\" name=\"dbnews[$k][value]\" value=\"".(empty($dbnews[$k]['value']) ? '' : mhtmlspecialchars(stripslashes($dbnews[$k]['value'])))."\"></td>\n".
			"<td class=\"txtC w50\"><select style=\"vertical-align: middle;\" name=\"dbnews[$k][order]\">".makeoption($orderarr,empty($dbnews[$k]['order']) ? '' : $dbnews[$k]['order'])."</select></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"dbnews[$k][prior]\" value=\"".(empty($dbnews[$k]['prior']) ? 0 : mhtmlspecialchars(stripslashes($dbnews[$k]['prior'])))."\"></td>\n".
			"</tr>";
		$i ++;
	}
	tabfooter('bdbsqlstr',lang('create'));
	if(!empty($dbnews) && $dbtable){
		$selectstr = '';
		$selectnum = $nprior = 0;
		$wherestr = $orderstr = $sqlstr = '';
		$orderarr = array();
		foreach($dbnews as $k => $v){
			if(!empty($v['adopt'])){
				$selectstr .= ($selectstr ? ',' : '').$k;
				$selectnum ++;
			}
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
		$selectstr = $selectnum == count($dbnews) ? '*' : $selectstr;
		if($selectstr){
			$sqlstr = 'SELECT '.$selectstr.' FROM `'.$dbtable.'`';
			if($wherestr) $sqlstr .= ' WHERE '.$wherestr;
			if($orderstr) $sqlstr .= ' ORDER BY '.$orderstr;
		}
		tabheader(lang('query_str_result'));
		trbasic(lang('query_string'),'view_sqlstr',$sqlstr,'btextarea');
		tabfooter();
	}
}
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