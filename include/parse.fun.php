<?php
@set_time_limit(0);
load_cache('btags,channels,cotypes,fchannels,fcatalogs,commus,mcommus,currencys,permissions,votes,vcps,mchannels,dbsources,vcatalogs,acatalogs,freeinfos');
load_cache('catalogs,sptpls',$sid);
cache_merge($channels,'channels',$sid);
cache_merge($btags,'btags',$sid);
include_once M_ROOT.'./include/refresh.fun.php';
include_once M_ROOT."./include/cparse.fun.php";
include_once M_ROOT."./include/parse/general.php";
include_once M_ROOT."./include/parse/func.php";

function _aenter(&$v,$init=0,$add=array()){
	global $_actid,$_midarr,$grouptypes,$cotypes;
	if($init) $_actid = $_midarr = array();
	$arr = array('aid','mid','sid','ucid','chid','mchid','mcaid','fcaid','vid','addid','fid','matid','rgid',);
	foreach($grouptypes as $x => $y) $arr[] = 'grouptype'.$x;
	if($add) $arr = array_merge($arr,$add);
	foreach($arr as $x) if(isset($v[$x])) $_midarr[$x] = $v[$x];
	$arr = array('caid');
	foreach($cotypes as $x => $y) $arr[] = 'ccid'.$x;
	foreach($arr as $x) if(isset($v[$x])) $_midarr[$x] = cnoneid($v[$x]);
	array_unshift($_actid,$_midarr);
}
function _aquit(){
	global $_actid,$_midarr;
	array_shift($_actid);
	$_midarr = @$_actid[0];
}

function _utag_parse($tag=array()){
	global $db,$tblprefix,$_mp;
	switch($tag['tclass']){
		case 'image':
			return one_image($tag);
		break;
		case 'file':
			return one_file($tag);
		break;
		case 'media':
			return one_media($tag);
		break;
		case 'flash':
			return one_media($tag);
		break;
		case 'images':
			return arr_images($tag);
		break;
		case 'files':
			return arr_files($tag);
		break;
		case 'medias':
			return arr_medias($tag);
		break;
		case 'flashs':
			return arr_medias($tag);
		break;
		case 'fromid':
			return one_fromid($tag);
		break;
		case 'arcfee':
			return arr_arcfee($tag);
		break;
//----------------------------------------------------------------
		case 'date':
			return one_date($tag);
		break;
		case 'odeal':
			return one_odeal($tag['tname'],$tag);
		break;
		case 'field':
			return one_field($tag);
		break;
	}	
}

function _ctag_parse($tag=array()){
	switch($tag['tclass']){
		case 'archives':
			return arr_archives($tag);
		break;
		case 'alarchives':
			return arr_alarchives($tag);
		break;
		case 'albums':
			return arr_albums($tag);
		break;
		case 'relates':
			return arr_relates($tag);
		break;
		case 'outinfos':
			return arr_outinfos($tag);
		break;
		case 'members':
			return arr_members($tag);
		break;
		case 'commus':
			return arr_commus($tag);
		break;
		case 'mcommus':
			return arr_mcommus($tag);
		break;
		case 'catalogs':
			return arr_catalogs($tag);
		break;
		case 'mccatalogs':
			return arr_mccatalogs($tag);
		break;
		case 'mcatalogs':
			return arr_mcatalogs($tag);
		break;
		case 'marchives':
			return arr_marchives($tag);
		break;
		case 'farchives':
			return arr_farchives($tag);
		break;
		case 'channels':
			return arr_channels($tag);
		break;
		case 'mchannels':
			return arr_mchannels($tag);
		break;
		case 'usergroups':
			return arr_usergroups($tag);
		break;
		case 'matypes':
			return arr_matypes($tag);
		break;
		case 'subsites':
			return arr_subsites($tag);
		break;
		case 'keywords':
			return arr_keywords($tag);
		break;
		case 'votes':
			return arr_votes($tag);
		break;
		case 'vote'://投票选项列表
			return arr_vote($tag);
		break;
		case 'nownav':
			return arr_nownav($tag);
		break;
		case 'mnownav':
			return arr_mnownav($tag);
		break;
		//----------------------------------------------------------------------------------------
		case 'archive':
			return one_archive($tag);
		break;
		case 'farchive':
			return one_farchive($tag);
		break;
		case 'userinfos':
			return one_user($tag);
		break;
		case 'marchive':
			return one_marchive($tag);
		break;
		case 'cnode':
			return one_cnode($tag);
		break;
		case 'cnmod':
			return one_cnmod($tag);
		break;
		case 'mcnode':
			return one_mcnode($tag);
		break;
		case 'context':
			return one_context($tag);
		break;
		case 'acontext':
			return one_acontext($tag);
		break;
		case 'arcscount':
			return one_arcscount($tag);
		break;
		case 'memscount':
			return one_memscount($tag);
		break;
		case 'inscount'://辑内数量统计，需要新加标识//????????????????????????????????
			return one_inscount($tag);
		break;
		//----------------------------------------------------------------------------------------
		case 'freeurl':
			return one_freeurl($tag);
		break;
	}
}

function _ptag_parse($tag=array()){//获取当前标识的值，
	global $_mp;
	switch($tag['tclass']){
		case 'normal':
			return one_odeal(@$_mp['bodys'][$_mp['nowpage']],$tag);
		break;
		case 'images':
			return arr_images($tag,'p');
		break;
		case 'archives':
			return arr_archives($tag,'p');
		break;
		case 'alarchives':
			return arr_alarchives($tag,'p');
		break;
		case 'farchives':
			return arr_farchives($tag,'p');
		break;
		case 'outinfos':
			return arr_outinfos($tag,'p');
		break;
		case 'members':
			return arr_members($tag,'p');
		break;
		case 'commus':
			return arr_commus($tag,'p');
		break;
		case 'mcommus':
			return arr_mcommus($tag,'p');
		break;
		case 'marchives':
			return arr_marchives($tag,'p');
		break;
		case 'searchs':
			return arr_searchs($tag,'p');
		break;
		case 'msearchs':
			return arr_msearchs($tag,'p');
		break;
		case 'masearchs':
			return arr_masearchs($tag,'p');
		break;
	}
}

function _mpinfo($tag){
	global $db,$_mp,$liststaticnum,$mpnav,$mptitle,$timestamp;
	$_mp['pcount'] = $_mp['acount'] = 1;
	if(!$tag || empty($tag['tclass'])) return;
	$_mp['ptname'] = $tag['ename'];//记录分页标识名称
	$limits = empty($tag['limits']) ? 10 : intval($tag['limits']);
	switch($tag['tclass']){
		case 'normal':
			$limits = 1;
			if($bodysarr = preg_split("/\[#(.*?)#\]/is",$tag['tname'],-1,PREG_SPLIT_DELIM_CAPTURE)){
				$i = 0;
				foreach($bodysarr as $k => $v){
					if(!($k % 2) && !preg_match("/^[\s|　| |\&nbsp;|<p>|<\/p>|<br \/>]*$/is",$v)){
						$i++;
						$_mp['titles'][$i] = !isset($bodysarr[$k-1]) ? '' : $bodysarr[$k-1];
						$_mp['bodys'][$i] = $v;
					}
				}
				if($i) $_mp['acount'] = $i;
			}
			unset($bodysarr);
		break;
		case 'images':
			if($images = @unserialize($tag['tname'])) $_mp['acount'] = count($images);
			unset($images);
		break;
		case 'archives':
			if($sqlstr = arc_sqlstr($tag,'p',1)) $_mp['acount'] = $db->result_one($sqlstr);
		break;
		case 'marchives':
			if($sqlstr = marc_sqlstr($tag,'p',1)) $_mp['acount'] = $db->result_one($sqlstr);
		break;
		case 'farchives':
			if($sqlstr = farc_sqlstr($tag,'p',1)) $_mp['acount'] = $db->result_one($sqlstr);
		break;
		case 'searchs':
			$_da = $GLOBALS['_da'];
			if($sqlstr = $_da['sqlstr']){
				if(!empty($tag['validperiod'])) $sqlstr .= " AND (a.enddate='0' OR a.enddate>'$timestamp')";
				$_mp['acount'] = $db->result_one("SELECT COUNT(*) $sqlstr");
			}
			unset($_da);
		break;
		case 'msearchs':
			$_da = $GLOBALS['_da'];
			if($sqlstr = $_da['sqlstr']) $_mp['acount'] = $db->result_one("SELECT COUNT(*) $sqlstr");
			unset($_da);
		break;
		case 'masearchs':
			$_da = $GLOBALS['_da'];
			if($sqlstr = $_da['sqlstr']) $_mp['acount'] = $db->result_one("SELECT COUNT(*) $sqlstr");
			unset($_da);
		break;
		case 'outinfos':
			$_mp['acount'] = outinfos_nums($tag);
		break;
		case 'commus':
			if($sqlstr = cu_sqlstr($tag,'p',1)) $_mp['acount'] = $db->result_one($sqlstr);
		break;
		case 'mcommus':
			if($sqlstr = mcu_sqlstr($tag,'p',1)) $_mp['acount'] = $db->result_one($sqlstr);
		break;
		case 'members':
			if($sqlstr = mem_sqlstr($tag,'p',1)) $_mp['acount'] = $db->result_one($sqlstr);
		break;
		case 'alarchives':
			if($sqlstr = alarc_sqlstr($tag,'p',1)) $_mp['acount'] = $db->result_one($sqlstr);
		break;
	}
	if(@$tag['alimits']) $_mp['acount'] = min($_mp['acount'],$tag['alimits']);
	$_mp['pcount'] = ceil($_mp['acount'] / $limits);
	$_mp['nowpage'] = max(1,min($_mp['nowpage'],$_mp['pcount']));
	$_mp['simple'] = empty($tag['simple']) ? 0 : 1;
	$mptitle = !isset($_mp['titles'][$_mp['nowpage']]) ? '' : $_mp['titles'][$_mp['nowpage']];
	$mpnav = mpnav($_mp);
	unset($tag);
}
?>
