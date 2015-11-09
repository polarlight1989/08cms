<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
include_once M_ROOT."./include/database.fun.php";
load_cache('channels,fchannels');
aheader();
if($action == 'tplpack'){
	if(!submitcheck('btplpack') && !submitcheck('bdelpack')){
		$sqlcompatarr = array('0' => lang('default'),'MYSQL40' => 'MySQL 3.23/4.0.x','MYSQL41' => 'MySQL 4.1.x/5.x');
		tabheader(lang('expsyscondat'),'tplpack','?entry=package&action=tplpack');
		trbasic(lang('dbfilename'),'filename',date('ymd').'_'.random(6));
		trbasic(lang('sqlcompat'),'sqlcompat',makeoption($sqlcompatarr),'select');
		trbasic(lang('usehex'),'usehex','0','radio');
		tabfooter('btplpack');

		$expfiles = array();
		if(is_dir(M_ROOT.'./dynamic/export')){
			$expfiles = findfiles(M_ROOT.'./dynamic/export','sql');
		}
		$itemstr = '';
		foreach($expfiles as $k => $expfile){
			$infos = array();
			$fp = fopen(M_ROOT.'./dynamic/export/'.$expfile,'rb');
			$idsarr = explode(',', base64_decode(preg_replace("/^# DatafileID:\s*(\w+).*/s", "\\1", fgets($fp, 256))));
			fclose ($fp);
			$infos['filename'] = $expfile;
			$infos['createdate'] = date("$dateformat $timeformat",$idsarr[0]);
			$infos['cmsname'] = $idsarr[1].'&nbsp;v'.$idsarr[2];
			$infos['version'] = ($idsarr[4].$idsarr[3]);
			$infos['filesize'] = ceil(@filesize(M_ROOT.'./dynamic/export/'.$expfile) / 1024);
			$infos['download'] = "<a href=\"?entry=package&action=download&filename=$infos[filename]\">".('download')."</a>";
			$itemstr .= "<tr class=\"txt\"><td class=\"txtC\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$infos[filename]]\" value=\"$infos[filename]\">\n".
				"<td class=\"txtL\"><a href=\"".$cms_abs."dynamic/export/$infos[filename]\">$infos[filename]</a></td>\n".
				"<td class=\"txtC\">$infos[cmsname]</td>\n".
				"<td class=\"txtC\">$infos[version]</td>\n".
				"<td class=\"txtC\">$infos[filesize]</td>\n".
				"<td class=\"txtC\">$infos[createdate]</td>\n".
				"<td class=\"txtC\">$infos[download]</td></tr>\n";
		}
		tabheader(lang('expdafilli'),'delpack','?entry=package&action=tplpack',7);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form)\">",lang('confilecnam'),lang('cname'),lang('version'),lang('size_k'),lang('exportime'),lang('download')));
		echo $itemstr;
		tabfooter('bdelpack',lang('delete'));
		a_guide('tplpack');

	}elseif(submitcheck('btplpack')){
		(!$filename || preg_match("/(\.)(exe|jsp|asp|aspx|cgi|fcgi|pl)(\.|$)/i", $filename)) && amessage('filenameillegal','?entry=package&action=tplpack');
		//&nbsp;不处理的表'asession','badwords','langs','mconfigs','msession','wordlinks',
		$structables = array(//仅需要表结构
		'archives','archives_sub','farchives','members','members_sub','orders',		
		);
		foreach($channels as $v) $structables[] = 'archives_'.$v['chid'];
		foreach($fchannels as $v) $structables[] = 'farchives_'.$v['chid'];
		$datatables = array(//表结构与数据
		'amconfigs','amenus','catalogs','channels','cnconfigs','cnfields','cnodes',
		'coclass','commus','cotypes','crprices','crprojects','currencys','fcatalogs','fchannels',
		'ffields','fields','freeinfos','gmissions','gmodels','grouptypes','localfiles','mfields',
		'mtags','mtconfigs','mtpls','permissions','players','rprojects','shipings','sitemaps',
		'sptpls','usergroups','vcatalogs','cufields',
		);

		$cleartables = array(//不需要结构与数据，只需要在原系统上重建即可。
		'answers','arecents','comments','cradminlogs','favorites','forders','gurls',
		'pays','pms','purchases','taxs','uclasses','userfiles',
		'voptions','votes','consults','keywords',
		);
		$db->query('SET SQL_QUOTE_SHOW_CREATE=0', 'SILENT');
		$sqlcharset = $dumpcharset = $dbcharset ? $dbcharset : str_replace('-', '', $mcharset);

		$setnames = $db->version() > '4.1' && (!$sqlcompat || $sqlcompat == 'MYSQL41') ? "SET NAMES '$sqlcharset';\n\n" : '';
		if($db->version() > '4.1'){
			$db->query("SET NAMES '".$sqlcharset."';\n\n");
			$sqlcompat == 'MYSQL40' ? $db->query("SET SQL_MODE='MYSQL40'") : $db->query("SET SQL_MODE=''");
		}
		$dumpfile = './dynamic/export/'.str_replace(array('/', '\\', '.'), '',stripslashes($filename)).'.sql';
		$sqldump = '';
		foreach($structables as $table) $sqldump .= pack_sqldump($table,0);
		foreach($datatables as $table) $sqldump .= pack_sqldump($table,1);
		foreach($cleartables as $table){
			$ntable = '{$tblprefix}'.$table;
			$sqldump .= "TRUNCATE $ntable;\n";
		}
		$idstring = '# DatafileID: '.base64_encode("$timestamp,08CMS,$cms_version,$dumpcharset,$lan_version")."\n";
		$sqldump = "$idstring".
				"# <?exit();?>\n".
				"# 08CMS ConfigPack Data Dump\n".
				"# Version: 08CMS v$cms_version\n".
				"# Date: ".date("Y-m-d",$timestamp)."\n".
				"# --------------------------------------------------------\n".
				"# Home: www.08cms.com\n".
				"# --------------------------------------------------------\n\n\n".
				"$setnames".
			$sqldump;

		$confs = array(
			array('hometpl',$hometpl,'view'),
			array('regcode_width', $regcode_width,'visit'),
			array('regcode_height',$regcode_height,'visit'),
			array('cms_regcode',$cms_regcode,'visit'),
			array('thumbwidth',$thumbwidth,'upload'),
			array('thumbheight',$thumbheight,'upload'),
		);
		foreach($confs as $v){
			$sqldump .= "REPLACE INTO ".'{$tblprefix}'."mconfigs (varname, value, cftype) VALUES ('$v[0]','$v[1]','$v[2]');\n";
		}

		@$fp = fopen($dumpfile, 'wb');
		@flock($fp, 2);
		adminlog(lang('expsyscondat'));
		amessage(@!fwrite($fp, $sqldump) ? 'dataexportfailed' : 'dataexportfinish','?entry=package&action=tplpack');
		
	}elseif(submitcheck('bdelpack')){
		empty($selectid) && amessage('chooseconfigfile','?entry=package&action=tplpack');
		foreach($selectid as $filename){
			@unlink(M_ROOT.'./dynamic/export/'.$filename);
		}
		adminlog(lang('delsyscondafil'));
		amessage('configfiledelfinish','?entry=package&action=tplpack');
	}
}elseif($action == 'packsetup'){
	!$curuser->info['isfounder'] && amessage('onlyfounderconfig');
	if(!submitcheck('bpacksetup')){
		tabheader(lang('instwebscon'),'packsetup','?entry=package&action=packsetup');
		trbasic(lang('uplfolcnam'),'sourcepath');
		trbasic(lang('tempfoldcnam'),'tpltarget');
		trbasic(lang('sameversion'),'sameversion','1','radio');
		tabfooter('bpacksetup');
		a_guide('packsetup');
	
	}else{
		if(empty($sourcepath) || empty($tpltarget)){
			amessage('inputuportplfolder', '?entry=package&action=packsetup');
		}
		if(preg_match("/[^a-z_A-Z0-9]+/",$sourcepath) || preg_match("/[^a-z_A-Z0-9]+/",$tpltarget)){
			amessage('uportplfolderillegal', '?entry=package&action=packsetup');
		}
		$truesource = M_ROOT.'./dynamic/import/'.$sourcepath;
		$truetarget = M_ROOT.'./template/'.$tpltarget;
		if(!is_dir($truesource) || !is_file($truesource.'/package.sql') || !is_dir($truesource.'/template')){
			amessage('uploadfilemiss', '?entry=package&action=packsetup');
		}
		if(is_dir($truetarget)){
			amessage('tplfolderused', '?entry=package&action=packsetup');
		}
		if(!($fp = @fopen($truesource.'/package.sql','rb'))){
			amessage('psqlfileillegal', '?entry=package&action=packsetup');
		}
		$idsarr = explode(',', base64_decode(preg_replace("/^# DatafileID:\s*(\w+)./s", "\\1", @fgets($fp, 256))));
		if(!is_array($idsarr) || count($idsarr) != 5 || $idsarr[1] != '08CMS'){
			amessage('psqlfileerror', '?entry=package&action=packsetup');
		}
		if($sameversion && ($idsarr[2] != $cms_version || $idsarr[3] != str_replace('-', '', $mcharset) || $idsarr[4] != $lan_version)){
			amessage('cfgfilevererror', '?entry=package&action=packsetup');
		}
		if(!dircopy($truesource.'/template',$truetarget)){
			amessage('temcopfai', '?entry=package&action=packsetup');
		}
		if(is_dir($truesource.'/function') && !dircopy($truesource.'/function',M_ROOT.'./dynamic/function')){
			amessage('funcdircopyfailed', '?entry=package&action=packsetup');
		}
		$sqldump = fread($fp, filesize($truesource.'/package.sql'));
		fclose($fp);
		if(!empty($sqldump)){
			$sqlquery = splitsql($sqldump);
			unset($sqldump);
			foreach($sqlquery as $sql) {
				$sql = syntablestruct(trim($sql), $db->version() > '4.1', $dbcharset);
				if($sql != '') {
					$sql = str_replace(' {$tblprefix}', " {$tblprefix}", $sql);
					$db->query($sql, 'SILENT');
					if(($sqlerror = $db->error()) && $db->errno() != 1062) {
						$db->halt('MySQL Query Error', $sql);
					}
				}
			}
		}
		$db->query("REPLACE INTO {$tblprefix}mconfigs (varname, value, cftype) VALUES ('templatedir','$tpltarget','view')");
		$db->query("INSERT INTO {$tblprefix}members (mid, mname, isfounder, password, email, checked) VALUES ('$memberid', '".$curuser->info['mname']."', '1', '".$curuser->info['password']."', '".$curuser->info['email']."', '1');");
		adminlog(lang('instwebscon'));
		rebuild_cache(-1);
	}
}elseif($action == 'download' && $filename){
	adminlog(lang('downsyscondatfi'));
	file_down(M_ROOT.'./dynamic/export/'.$filename);
}
function dircopy($source,$destination,$child = 1){
	if(!is_dir($source)) return false;
	mmkdir($destination);
	$handle=dir($source);
	while($entry=$handle->read()){
		if(($entry != ".") && ($entry != "..")){
			if(is_dir($source."/".$entry)){
				dircopy($source."/".$entry,$destination."/".$entry,$child);
			}else{
				copy($source."/".$entry,$destination."/".$entry);
			}
		}
	}
	return true;
}