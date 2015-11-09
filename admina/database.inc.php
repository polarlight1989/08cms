<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
include_once M_ROOT."./include/database.fun.php";
aheader();
backallow('database') || amessage('no_apermission');
$tabletype = $db->version() > '4.1' ? 'Engine' : 'Type';
if(!($backupdir = $db->result_one("SELECT value FROM {$tblprefix}mconfigs WHERE varname='backupdir'"))) {
	$backupdir = random(6);
	$db->query("REPLACE INTO {$tblprefix}mconfigs (varname, value) values ('backupdir','$backupdir')");
}
$backupdir = 'backup_'.$backupdir;
mmkdir(M_ROOT.'./dynamic/'.$backupdir);
$url_type = 'data';include 'urlsarr.inc.php';
if($action == 'dbexport'){
	if(!submitcheck('bdbexport')){
		url_nav(lang('dboperate'),$urlsarr,'dbbackup');

		$dbtables = array();
		$query = $db->query("SHOW TABLES FROM `$dbname`");
		while($dbtable = $db->fetch_row($query)){//如果有外来表，会出现什么情况?
			$dbtable[0] = preg_replace("/^".$tblprefix."(.*?)/s","\\1",$dbtable[0]);
			$dbtables[] = $dbtable[0];
		}
		$num = 3;
		tabheader(lang('choose_table').'<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('selectall'),'dbexport','?entry=database&action=dbexport',2 * $num);
		$i = 0;
		foreach($dbtables as $dbtable){
			if(!($i % $num)) echo "<tr class=\"txt\">";
			echo "<td class=\"txtC w5B\"><input class=\"checkbox\" type=\"checkbox\" name=\"tables[]\" value=\"$dbtable\"></td>\n".
			"<td class=\"txtL w30B\">$dbtable</td>\n";
			$i ++;
			if(!($i % $num)) echo "</tr>\n";
		}
		if($i % $num){
			while($i % $num){
				echo "<td class=\"txtL w5B\"></td>\n".
					"<td class=\"txtL w30B\"></td>\n";
				$i ++;
			}
			echo "</tr>\n";
		}
		tabfooter();
		
		$sqlcompatarr = array('0' => lang('default'),'MYSQL40' => 'MySQL 3.23/4.0.x','MYSQL41' => 'MySQL 4.1.x/5.x');
		$sqlcharsetarr = array('0' => lang('default'),'gbk' => 'GBK','utf8' => 'UTF-8');
		tabheader(lang('backup_param_set'));
		trbasic(lang('dbsizelimit'),'sizelimit','2048');
		trbasic(lang('dbfilename'),'filename',date('ymd').'_'.random(6));
		trbasic(lang('sqlcompat'),'sqlcompat',makeoption($sqlcompatarr),'select');
		trbasic(lang('sqlcharset'),'sqlcharset',makeoption($sqlcharsetarr),'select');
		trbasic(lang('usehex'),'usehex','0','radio');
		tabfooter('bdbexport',lang('backup'));
		a_guide('dbexport');
	}else{
		(!$filename || preg_match("/(\.)(exe|jsp|asp|aspx|cgi|fcgi|pl)(\.|$)/i", $filename)) && amessage('filenameillegal','?entry=database&action=dbexport');
		(empty($tables) && empty($tablestr)) && amessage('selecttable','?entry=database&action=dbexport');
		
		if(empty($tables)){
			$tables = array_filter(explode(',',$tablestr));
		}else{
			$tablestr = implode(',',$tables);
		}

		$db->query('SET SQL_QUOTE_SHOW_CREATE=0', 'SILENT');

		$volume = empty($volume) ? 1 : (intval($volume) + 1);
		$idstring = '# DatafileID: '.base64_encode("$timestamp,08CMS,$cms_version,$volume")."\n";

		$dumpcharset = $sqlcharset ? $sqlcharset : str_replace('-', '',$mcharset);
		$setnames = ($sqlcharset && $db->version() > '4.1' && (!$sqlcompat || $sqlcompat == 'MYSQL41')) ? "SET NAMES '$dumpcharset';\n\n" : '';
		if($db->version() > '4.1') {
			if($sqlcharset) {
				$db->query("SET NAMES '".$sqlcharset."';\n\n");
			}
			if($sqlcompat == 'MYSQL40') {
				$db->query("SET SQL_MODE='MYSQL40'");
			} elseif($sqlcompat == 'MYSQL41') {
				$db->query("SET SQL_MODE=''");
			}
		}

		$backupfilename = './dynamic/'.$backupdir.'/'.str_replace(array('/', '\\', '.'), '', $filename);
		$sqldump = '';
		$tableid = empty($tableid) ? 0 : intval($tableid);
		$startfrom = empty($startfrom) ? 0 : intval($startfrom);
		$complete = TRUE;
		for(; $complete && $tableid < count($tables) && strlen($sqldump) + 500 < $sizelimit * 1000; $tableid++){
			$sqldump .= sqldumptable($tblprefix.$tables[$tableid], $startfrom, strlen($sqldump));
			if($complete) {//单个数据表的完成标记
				$startfrom = 0;
			}
		}
		$dumpfile = $backupfilename."-%s".'.sql';
		!$complete && $tableid --;//数据表分割在两个卷的情况
		if(trim($sqldump)){
			$sqldump = "$idstring".
				"# <?exit();?>\n".
				"# 08cms Multi-Volume Data Dump Vol.$volume\n".
				"# Version: 08cms $cms_version\n".
				"# Date: ".date("Y-m-d",$timestamp)."\n".
				"# Made By: ".$curuser->info['mname']."\n".
				"# ----------------------------------------------\n".
				"# 08cms Home: \n".
				"# ----------------------------------------------\n\n\n".
				"$setnames".
				$sqldump;
			$dumpfilename = sprintf($dumpfile, $volume);
			@$fp = fopen($dumpfilename, 'wb');
			@flock($fp, 2);
			if(@!fwrite($fp, $sqldump)) {
				@fclose($fp);
				amessage('tableexportfailed','?entry=database&action=dbexport');
			} else {
				fclose($fp);
				unset($sqldump);
				$parastr = "&bdbexport=1";
				$parastr .= "&startfrom=".$startrow;
				foreach(array('filename','sizelimit','volume','tableid','sqlcompat','sqlcharset','usehex','tablestr') as $k){
					$parastr .= "&$k=".$$k;
				}
				amessage('backuping',"?entry=database&action=dbexport$parastr",count($tables),$tableid,$volume);
			}
		}
		adminlog(lang('dbbackup'));
		amessage('dbbackupfinish','?entry=database&action=dbexport');
	}
}
elseif($action == 'dbimport'){
	if(!submitcheck('bdbimport') && !submitcheck('bbddelete')){
		url_nav(lang('dboperate'),$urlsarr,'dbimport');
		$expfiles = array();
		if(is_dir(M_ROOT.'./dynamic/'.$backupdir)){
			$expfiles = findfiles(M_ROOT.'./dynamic/'.$backupdir,'sql');
		}
		$itemstr = '';
		foreach($expfiles as $k => $expfile){
			$infos = array();
			$fp = fopen(M_ROOT.'./dynamic/'.$backupdir.'/'.$expfile,'rb');
			$identify = explode(',', base64_decode(preg_replace("/^# DatafileID:\s*(\w+).*/s", "\\1", fgets($fp, 256))));
			fclose ($fp);
			$infos['filename'] = $expfile;
			$infos['createdate'] = date("$dateformat $timeformat",@filemtime(M_ROOT.'./dynamic/'.$backupdir.'/'.$expfile));
			$infos['filesize'] = ceil(@filesize(M_ROOT.'./dynamic/'.$backupdir.'/'.$expfile) / 1024);
			$infos['cmsname'] = empty($identify[1]) ? '' : $identify[1];
			$infos['version'] = empty($identify[2]) ? '' : $identify[2];
			$infos['volume'] = empty($identify[3]) ? '' : $identify[3];
			$infos['download'] = "<a href=\"?entry=database&action=download&filename=$infos[filename]\">".lang('download')."</a>";
			$infos['import'] = ($infos['volume'] == '1' && $infos['cmsname'] == '08CMS') ? "<a href=\"?entry=database&action=dbimport&bdbimport=1&filename=$infos[filename]\">".lang('import')."</a>" : "-";
			$itemstr .= "<tr class=\"txt\"><td class=\"txtC\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$infos[filename]]\" value=\"$infos[filename]\">\n".
				"<td class=\"txtL\"><a href=\"".$cms_abs."dynamic/$backupdir/$infos[filename]\">$infos[filename]</a></td>\n".
				"<td class=\"txtC\">$infos[version]</td>\n".
				"<td class=\"txtC\">$infos[volume]</td>\n".
				"<td class=\"txtC\">$infos[filesize]</td>\n".
				"<td class=\"txtC\">$infos[createdate]</td>\n".
				"<td class=\"txtC\">$infos[download]</td>\n".
				"<td class=\"txtC\">$infos[import]</td></tr>\n";
		}
		tabheader(lang('backup_file_list'),'dbimport','?entry=database&action=dbimport',8);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form)\">",lang('backup_file_name'),lang('version'),lang('volume'),lang('sizek'),lang('backup_time'),lang('download'),lang('import')));
		echo $itemstr;
		tabfooter('bbddelete',lang('delete'));
		a_guide('dbimport');
		
	}elseif(submitcheck('bbddelete')){
		empty($selectid) && amessage('selectbackupfile','?entry=database&action=dbimport');
		foreach($selectid as $filename){
			@unlink(M_ROOT.'./dynamic/'.$backupdir.'/'.$filename);
		}
		adminlog(lang('del_db_backup_file'));
		amessage('backupfiledelfinish','?entry=database&action=dbimport');
	}elseif(submitcheck('bdbimport')){
		empty($filename) && amessage('selectbackupfile','?entry=database&action=dbimport');
		$volume = empty($volume) ? 1 : intval($volume);
		$datafile = M_ROOT.'./dynamic/'.$backupdir.'/'.$filename;
		$sqldump = '';
		if(@$fp = fopen($datafile, 'rb')){
			$dumpinfo = fgets($fp, 256);
			$dumpinfo = explode(',', base64_decode(preg_replace("/^# DatafileID:\s*(\w+).*/s", "\\1", $dumpinfo)));
			if(($dumpinfo[1] == '08CMS') && ($dumpinfo[3] == $volume)){
				$sqldump = fread($fp, filesize($datafile));
			}
			fclose($fp);
		}
		if(!empty($sqldump)){
			$sqlquery = splitsql($sqldump);
			unset($sqldump);
			
			foreach($sqlquery as $sql) {
				$sql = syntablestruct(trim($sql), $db->version() > '4.1', $dbcharset);
				if($sql != '') {
					$db->query($sql, 'SILENT');
					if(($sqlerror = $db->error()) && $db->errno() != 1062) {
						$db->halt('MySQL Query Error', $sql);
					}
				}
			}
		}
				
		$filename_next = preg_replace("/-($volume)(\..+)$/","-".($volume + 1)."\\2",$filename);
		if(file_exists(M_ROOT.'./dynamic/'.$backupdir.'/'.$filename_next)){
			$volume ++;
			amessage('volumeexporting',"?entry=database&action=dbimport&bdbimport=1&volume=$volume&filename=$filename_next",
					 $volume - 1,$volume,"<a href=\"?entry=database&action=dbimport\">",'</a>');		
		}else{
			adminlog(lang('import_db_backup'));
			rebuild_cache(-1);
			amessage('importdbsucceed');
		}
	}
}elseif($action == 'dboptimize'){
	if(!submitcheck('bdboptimize') && !submitcheck('bdbrepair')){
		url_nav(lang('dboperate'),$urlsarr,'dboptimize');
		$dbtables = array();
		$query = $db->query("SHOW TABLES FROM `$dbname`");
		while($dbtable = $db->fetch_row($query)){
			$dbtable[0] = preg_replace("/^".$tblprefix."(.*?)/s","\\1",$dbtable[0]);
			$dbtables[] = $dbtable[0];
		}

		$num = 3;
		tabheader(lang('choose_table').'<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('selectall'),'dbexport','?entry=database&action=dboptimize',2 * $num);
		$i = 0;
		foreach($dbtables as $dbtable){
			if(!($i % $num)){
				echo "<tr class=\"txt\">";
			}
			echo "<td class=\"txtC w5B\"><input class=\"checkbox\" type=\"checkbox\" name=\"tables[]\" value=\"$dbtable\"></td>\n".
			"<td class=\"txtL w30B\">$dbtable</td>\n";
			$i ++;
			if(!($i % $num)){
				echo "</tr>\n";
			}
		}
		if($i % $num){
			while($i % $num){
				echo "<td class=\"txtL w5B\"></td>\n".
					"<td class=\"txtL w30B\"></td>\n";
				$i ++;
			}
			echo "</tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdboptimize\" value=\"".lang('optimize')."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input class=\"button\" type=\"submit\" name=\"bdbrepair\" value=\"".lang('repair')."\">";
		a_guide('dboptimize');
	}else{
		empty($tables) && amessage('selecttable','?entry=database&action=dboptimize');
		$dealstr = submitcheck('bdboptimize') ? 'OPTIMIZE' : 'REPAIR';
		$tablestr = '';
		foreach($tables as $table){
			$tablestr .= ($tablestr ? ',' : '').$tblprefix.$table;
		}
		$tablestr && $db->query("$dealstr TABLE $tablestr");
		adminlog(lang('db_tb_'.submitcheck('bdboptimize') ? 'optimize' : 'repair'));
		amessage('tableoperatefinish','?entry=database&action=dboptimize');
	}
}elseif($action == 'dbsql'){
	if(!submitcheck('bdbsql')){
		url_nav(lang('dboperate'),$urlsarr,'dbsql');
		tabheader(lang('run_sql_code'),'dbsql','?entry=database&action=dbsql');
		echo "<tr class=\"txt\"><td class=\"txtL w25B\">".lang('im_sql_code_content')."</td><td class=\"txtL\"><textarea rows=\"15\" name=\"sqlcode\" cols=\"100\"></textarea></td></tr>";
		tabfooter('bdbsql');
		a_guide('dbsql');
	}else{
		empty($sqlcode) && amessage('inputsqlcode','?entry=database&action=dbsql');
		$sqlquery = splitsql(str_replace(array(' cms_', ' {tblprefix}', ' `cms_'), array(' '.$tblprefix, ' '.$tblprefix, ' `'.$tblprefix), $sqlcode));
		$affected_rows = 0;
		foreach($sqlquery as $sql){
			if(trim($sql) != '') {
				$db->query(stripslashes($sql),'SILENT');
				if($sqlerror = $db->error()){
					break;
				}else{
					$affected_rows += intval($db->affected_rows());
				}
			}
		}
		adminlog(lang('run_sql_code'));
		amessage('sqlresult','?entry=database&action=dbsql',$affected_rows);
	}
}
elseif($action == 'download' && $filename){
	adminlog(lang('dl_db_backup_file'));
	file_down(M_ROOT.'./dynamic/'.$backupdir.'/'.$filename);
}
?>