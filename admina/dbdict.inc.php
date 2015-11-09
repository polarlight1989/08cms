<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('database') || amessage('no_apermission');
load_cache('dbfields');
$url_type = 'data';include 'urlsarr.inc.php';

if(empty($action)){
	$dbtable = empty($dbtable) ? $tblprefix.'archives' : $dbtable;
	if(!submitcheck('bdbdict')){
		url_nav(lang('dboperate'),$urlsarr,'dbdict');

		$dbtables = array('' => lang('select_table'));
		$query = $db->query("SHOW TABLES FROM $dbname");
		while($v = $db->fetch_row($query)) $dbtables[$v[0]] = $v[0];
		$filterbox = lang('choose_table').'&nbsp; &nbsp;';
		$filterbox .= "<select style=\"vertical-align: middle;\" name=\"dbtable\" onchange=\"redirect('?entry=dbdict&dbtable=' + this.options[this.selectedIndex].value);\">";
		foreach($dbtables as $k => $v){
			$filterbox .= "<option value=\"$k\"".($dbtable == $k ? ' selected' : '').">$v</option>";
		}
		$filterbox .= "</select>";
		tabheader($filterbox);
		tabfooter();
	
		$tblfields = array();
		$PRI='';
		if($dbtable){
			$query = $db->query("SHOW FULL COLUMNS FROM $dbtable",'SILENT');
			while($row = $db->fetch_array($query)){
				$tblfields[$row['Field']] = strtolower($row['Type']);
				if('PRI'==$row['Key'])$PRI=$row['Field'];
			}
		}
		tabheader(lang('db_field_list').'&nbsp; -&nbsp; '.$dbtable,'dbdict',"?entry=dbdict&dbtable=$dbtable",5);
		trcategory(array(lang('sn'),lang('field_name'),lang('field_type'),lang('content_replace'),lang('field_remark')));
		$i = 1;
		$ddtable = substr($dbtable,strlen($tblprefix));
		foreach($tblfields as $k => $v){
			$key = $ddtable.'_'.$k;
			echo "<tr>".
				"<td class=\"txtC w30\">$i</td>\n".
				"<td class=\"txtL\"><b>$k</b></td>\n".
				"<td class=\"txtL\">$v</td>\n".
				"<td class=\"txtC\">".($k==$PRI?'':"<a href=\"?entry=dbdict&action=dbreplace&dbtable=$dbtable&dbfield=$k\">>>".lang('replace')."</a>")."</td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"60\" name=\"dbfieldsnew[$ddtable][$k]\" value=\"".(empty($dbfields[$key]) ? '' : mhtmlspecialchars($dbfields[$key]))."\"></td>\n".
				"</tr>";
			$i ++;
		}
		tabfooter('bdbdict',lang('modify'));
		a_guide('dbfieldsremark');
	}else{
		if(!empty($dbfieldsnew)){
			foreach($dbfieldsnew as $k => $v){
				if(!empty($v)){
					foreach($v as $k1 => $v1){
						if(empty($v1)){
							$db->query("DELETE FROM {$tblprefix}dbfields WHERE ddtable='$k' AND ddfield='$k1'");
						}else{
							if(!isset($dbfields[$k.'_'.$k1])){
								$db->query("INSERT INTO {$tblprefix}dbfields SET ddtable='$k',ddfield='$k1',ddcomment='$v1'");
							}else $db->query("UPDATE {$tblprefix}dbfields SET ddcomment='$v1' WHERE ddtable='$k' AND ddfield='$k1'");
						}
					}
				}
				
			}
		}
		updatecache('dbfields');
		amessage('dataiodfin',"?entry=dbdict&dbtable=$dbtable");

	}

}elseif($action == 'dbreplace'){
	if(empty($dbtable)) amessage('choosetable');
	if(empty($dbfield)) amessage('choosefield');
	if(!submitcheck('bdbreplace')){
		$mode0arr = array(0 => lang('normal'),1 => lang('regular'));
		tabheader(lang('field_cre_operate'),'dbreplace',"?entry=dbdict&action=$action&dbtable=$dbtable&dbfield=$dbfield",2);
		trbasic(lang('current_table'),'',$dbtable,'');
		trbasic(lang('current_field'),'',$dbfield,'');
		trbasic(lang('search_mode').'&nbsp; [<a href="http://dev.mysql.com/doc/refman/5.1/zh/regexp.html" target="_blank">'.lang('regular_help').'</a>]','mode',makeradio('mode',$mode0arr,0),'');
		trbasic(lang('search_txt'),'rpstring','','textarea');
		trbasic(lang('replace_txt'),'tostring','','textarea');
		trbasic(lang('where_plus_string'),'where','','btext',lang('dont_inc_where'));
		tabfooter('bdbreplace',lang('start_replace'));
		a_guide('dbreplace');
	}else{
		if(!isset($mode)||!$rpstring||!$tostring)amessage('modseareptxtnot',M_REFERER);
		$rs=$db->query("SHOW COLUMNS FROM $dbtable",'SILENT');
		unset($key);
		while($row=$db->fetch_array($rs))
			if('PRI'==$row['Key']){
				$key=$row['Field'];
				break;
			}
		if(1==$mode){
			if(!isset($key))amessage('notablekey',M_REFERER);
			if($dbfield == $key)amessage('ondeal',M_REFERER);
			$rpstring=stripslashes($rpstring);
			$tostring=stripslashes($tostring);
			$where=$where?" and $where":'';
			$rs=$db->query("select `$key`,`$dbfield` from `$dbtable` where `$dbfield` REGEXP '".str_replace(array("\\","'"),array("\\\\","\\'"),$rpstring)."'$where");
			$count=$db->num_rows($rs);
			if(0==$count)amessage('notablerecord',M_REFERER);
			$replace=0;
			while($row=$db->fetch_array($rs))
				if($db->query("update `$dbtable` set `$dbfield` = '".addslashes(eregi_replace($rpstring,$tostring,$row[$dbfield]))."' where `$key` = '".addslashes($row[$key])."'")) $replace++;
			amessage('find_replace',M_REFERER,$count,$replace);
		}else{
			if(isset($key)&&$dbfield == $key)amessage('ondeal',M_REFERER);
			$where = $where ? " where $where" : '';
			$db->query("update `$dbtable` set `$dbfield`=replace(`$dbfield`,'$rpstring','$tostring')$where");
			amessage('succrepl',M_REFERER,$db->affected_rows());
		}
	}
}


?>
