<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('webparam') || amessage('no_apermission');
load_cache('mconfigs,currencys,commus,channels,cotypes');
load_cache('mtpls',$sid);
cache_merge($channels,'channels',$sid);
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
if($sid) $subsite = $subsites[$sid];
$url_type = 'mconfig';include 'urlsarr.inc.php';
if($action == 'cfsite'){
	url_nav(lang('webparam'),$urlsarr,'cfsite',12);
	if(!submitcheck('bmconfigs')){
		tabheader(lang('sitemessaadmi'),'cfsite',"?entry=mconfigs&action=cfsite$param_suffix",2,1);
		if(!$sid){
			trbasic(lang('hostname'),'mconfigsnew[hostname]',$mconfigs['hostname']);
			trbasic(lang('hosturl'),'mconfigsnew[hosturl]',$mconfigs['hosturl'],'text',lang('aghosturl'));
			trbasic(lang('cmsname'),'mconfigsnew[cmsname]',$mconfigs['cmsname']);
			trbasic(lang('cmsurl'),'mconfigsnew[cmsurl]',$mconfigs['cmsurl'],'text',lang('agcmsurl'));
			trbasic(lang('mspacedir'),'mconfigsnew[mspacedir]',$mconfigs['mspacedir'],'text',lang('agmspacedir'));
			trbasic(lang('memberdir'),'mconfigsnew[memberdir]',$mconfigs['memberdir'],'text',lang('agmemberdir'));
			trspecial(lang('cmslogo'),'mconfigsnew[cmslogo]',$mconfigs['cmslogo'],'image');
			trbasic(lang('sitetitle'),'mconfigsnew[cmstitle]',$mconfigs['cmstitle'],'btext');
			trbasic(lang('sitekeyword'),'mconfigsnew[cmskeyword]',$mconfigs['cmskeyword'],'btext');
			trbasic(lang('sitedescrip'),'mconfigsnew[cmsdescription]',$mconfigs['cmsdescription'],'textarea');
			trbasic(lang('siteicpno'),'mconfigsnew[cms_icpno]',$mconfigs['cms_icpno'],'btext');
			trbasic(lang('bazscert'),'mconfigsnew[bazscert]',$mconfigs['bazscert'],'btext');
			trbasic(lang('copyrightmessage'),'mconfigsnew[copyright]',$mconfigs['copyright'],'textarea');
		}else{
			trspecial(lang('cmslogo'),'subsitenew[cmslogo]',$subsite['cmslogo'],'image');
			trbasic(lang('subsitetitle'),'subsitenew[cmstitle]',$subsite['cmstitle'],'btext');
			trbasic(lang('subkeyword'),'subsitenew[cmskeyword]',$subsite['cmskeyword'],'btext');
			trbasic(lang('subsitedescrip'),'subsitenew[cmsdescription]',$subsite['cmsdescription'],'textarea');
		}
		tabfooter('bmconfigs');
		a_guide('cfsite');
	}else{
		if(!$sid){
			if(empty($mconfigsnew['hosturl']) || !in_str('http://',$mconfigsnew['hosturl'])){
				amessage('hosturlillegal',M_REFERER);
			}
			$mconfigsnew['hosturl'] = strtolower($mconfigsnew['hosturl']);
			$mconfigsnew['cmsurl'] = empty($mconfigsnew['cmsurl']) ? '/' : trim(strtolower($mconfigsnew['cmsurl']));
			$mconfigsnew['cmsurl'] .= !ereg("/$",$mconfigsnew['cmsurl']) ? '/' : '';
			
			foreach(array('mspacedir','memberdir',) as $var){
				$mconfigsnew[$var] = strtolower($mconfigsnew[$var]);
				if($mconfigsnew[$var] == $mconfigs[$var]) continue;
				if(!$mconfigsnew[$var] || preg_match("/[^a-z_0-9]+/",$mconfigsnew[$var])){
					$mconfigsnew[$var] = $mconfigs[$var];
					continue;
				}
				if(is_dir(M_ROOT.$mconfigs[$var])){
					if(!rename(M_ROOT.$mconfigs[$var],M_ROOT.$mconfigsnew[$var])) $mconfigsnew[$var] = $mconfigs[$var];
				}else mmkdir(M_ROOT.$mconfigsnew[$var],0);
			}
			
			$c_upload = new cls_upload;	
			$mconfigsnew['cmslogo'] = upload_s($mconfigsnew['cmslogo'],$mconfigs['cmslogo'],'image');
			if($k = strpos($mconfigsnew['cmslogo'],'#')) $mconfigsnew['cmslogo'] = substr($mconfigsnew['cmslogo'],0,$k);

			saveconfig('site');
		}else{
			$c_upload = new cls_upload;	
			$subsitenew['cmslogo'] = upload_s($subsitenew['cmslogo'],$subsite['cmslogo'],'image');
			if($k = strpos($subsitenew['cmslogo'],'#')) $subsitenew['cmslogo'] = substr($subsitenew['cmslogo'],0,$k);
			$db->query("UPDATE {$tblprefix}subsites SET 
			cmslogo='$subsitenew[cmslogo]',
			cmstitle='$subsitenew[cmstitle]',
			cmskeyword='$subsitenew[cmskeyword]',
			cmsdescription='$subsitenew[cmsdescription]' 
			WHERE sid='$sid'");
			updatecache('subsites');
		}
		$c_upload->closure(2, $sid, 'mconfigs');
		$c_upload->saveuptotal(1);
		unset($c_upload);
		adminlog(lang('websiteset'),lang('sitemessaadmi'));
		amessage('websitesetfinish',M_REFERER);
	}
}elseif($action == 'cfbasic'){
	url_nav(lang('webparam'),$urlsarr,'cfbasic',12);
	if(!submitcheck('bmconfigs')){
		tabheader(lang('base_setting'),'cfbasic','?entry=mconfigs&action=cfbasic');
		$chklvarr = array(1 => lang('level1'),2 => lang('level2'),3 => lang('level3'),);
		trbasic(lang('max_chklv'),'',makeradio('mconfigsnew[max_chklv]',$chklvarr,empty($mconfigs['max_chklv']) ? 1 : $mconfigs['max_chklv']),'',lang('agmax_chklv'));
		$ca_vmodearr = array('0' => lang('vmode0'),'1' => lang('vmode1'),'2' => lang('vmode2'),'3' => lang('vmode3'),'4' => lang('vmode4'));
		trbasic(lang('catacholismod'),'',makeradio('mconfigsnew[ca_vmode]',$ca_vmodearr,empty($mconfigs['ca_vmode']) ? 0 : $mconfigs['ca_vmode']),'');
		trbasic(lang('catahidden'),'mconfigsnew[catahidden]',$mconfigs['catahidden'],'radio');
		trbasic(lang('arcautbstlen'),'mconfigsnew[autoabstractlength]',$mconfigs['autoabstractlength']);
		trbasic(lang('enablerss'),'mconfigsnew[rss_enabled]',$mconfigs['rss_enabled'],'radio');
		trbasic(lang('rss_ttl'),'mconfigsnew[rss_ttl]',$mconfigs['rss_ttl']);
		trbasic(lang('nousersearch'),'mconfigsnew[nousersearch]',$mconfigs['nousersearch'],'radio');
		trbasic(lang('seamaxreamomembcen'),'mconfigsnew[search_max]',$mconfigs['search_max']);
		trbasic(lang('seatiintlimsec'),'mconfigsnew[search_repeat]',$mconfigs['search_repeat']);
		$tzarr=array(
			'+12'=>'(GMT-12) International Date Line (West)',
			'+11'=>'(GMT-11) Midway Island,Samoa',
			'+10'=>'(GMT-10) Hawaii,Honolulu',
			'+9'=>'(GMT-9) Alaska',
			'+8'=>'(GMT-8) Pacific Standard Time,US,Canada',
			'+7'=>'(GMT-7) British Columbia N.E.,Santa Fe,Mountain Time',
			'+6'=>'(GMT-6) Central America,Chicago,Guatamala,Mexico City',
			'+5'=>'(GMT-5) US,Canada,Bogota,Boston,New York',
			'+4'=>'(GMT-4) Canada,Santiago,Atlantic Standard Time',
			'+3'=>'(GMT-3) Brazilia,Buenos Aires,Georgetown,Greenland',
			'+2'=>'(GMT-2) Mid-Atlantic',
			'+1'=>'(GMT-1) Azores,Cape Verde Is.,Western Africa Time',
			'0'=>'(GMT) London,Iceland,Ireland,Morocco,Portugal',
			'-1'=>'(GMT+1) Amsterdam,Berlin,Bern,Madrid,Paris,Rome',
			'-2'=>'(GMT+2) Athens,Cairo,Cape Town,Finland,Greece,Israel',
			'-3'=>'(GMT+3) Ankara,Aden,Baghdad,Beruit,Kuwait,Moscow',
			'-4'=>'(GMT+4) Abu Dhabi,Baku,Kabul,Tehran,Tbilisi,Volgograd',
			'-5'=>'(GMT+5) Calcutta,Colombo,Islamabad,Madras,New Dehli',
			'-6'=>'(GMT+6) Almaty,Dhakar,Kathmandu,Colombo,Sri Lanka',
			'-7'=>'(GMT+7) Bangkok,Hanoi,Jakarta,Phnom Penh,Australia',
			'-8'=>'(GMT+8) Beijing,Hong Kong,Singapore,Taipei',
			'-9'=>'(GMT+9) Seoul,Tokyo,Central Australia',
			'-10'=>'(GMT+10) Brisbane,Canberra,Guam,Melbourne,Sydney',
			'-11'=>'(GMT+11) Magadan,New Caledonia,Solomon Is.',
			'-12'=>'(GMT+12) Auckland,Fiji,Kamchatka,Marshall,Wellington'
		);
		trbasic(lang('sitetimez'),'mconfigsnew[timezone]',makeoption($tzarr,isset($timezone)?$timezone:-8),'select');
		tabfooter();

		tabheader(lang('websitstat'));
		trbasic(lang('enabelstat'),'mconfigsnew[enabelstat]',$mconfigs['enabelstat'],'radio');
		trbasic(lang('clickscachetime'),'mconfigsnew[clickscachetime]',$mconfigs['clickscachetime']);
		trbasic(lang('mclickscircle'),'mconfigsnew[mclickscircle]',$mconfigs['mclickscircle']);
		trbasic(lang('mactivetime'),'mconfigsnew[onlinetimecircle]',$mconfigs['onlinetimecircle']);
		$statarr = array('ca' => lang('catalog'));
		foreach($cotypes as $k => $v) if(!$v['self_reg']) $statarr[$k] = $v['cname'];		
		trbasic(lang('cotypestats'),'',makecheckbox('mconfigsnew[cotypestats][]',$statarr,empty($mconfigs['cotypestats']) ? array() : explode(',',$mconfigs['cotypestats']),6),'');
		trbasic(lang('cnodestatcir'),'mconfigsnew[cnodestatcircle]',$mconfigs['cnodestatcircle']);
		$statarr = array(
		'clicks' => lang('clicks'),
		'comments' => lang('comments'),
		'orders' => lang('purchamount'),
		'ordersum' => lang('purcallam'),
		'favorites' => lang('favorite_pics'),
		'praises' => lang('praise_pics'),
		'debases' => lang('debase_pics'),
		'downs' => lang('downlopic'),
		'plays' => lang('playpics'),
		'offers' => lang('offerpics'),
		'replys' => lang('replpic'),
		);
		trbasic(lang('wanmonstatitem'),'',makecheckbox('mconfigsnew[monthstats][]',$statarr,empty($mconfigs['monthstats']) ? array() : explode(',',$mconfigs['monthstats']),6),'');
		trbasic(lang('wanweestatitem'),'',makecheckbox('mconfigsnew[weekstats][]',$statarr,empty($mconfigs['weekstats']) ? array() : explode(',',$mconfigs['weekstats']),6),'');
		trbasic(lang('inalbum_sum_item'),'',makecheckbox('mconfigsnew[albumstats][]',$statarr + array('bytenum' => lang('bytenum')),empty($mconfigs['albumstats']) ? array() : explode(',',$mconfigs['albumstats']),6),'');
		trbasic(lang('inausttiminterh'),'mconfigsnew[albumstatcircle]',$mconfigs['albumstatcircle']);
		trbasic(lang('album_newstat'),'mconfigsnew[album_newstat]',empty($mconfigs['album_newstat']) ? 0 : $mconfigs['album_newstat']);
		tabfooter('bmconfigs');
		a_guide('cfbasic');
	}else{
		$mconfigsnew['max_chklv'] = max(0,intval($mconfigsnew['max_chklv']));
		if($mconfigsnew['max_chklv'] < $max_chklv){
			$db->query("UPDATE {$tblprefix}channels SET chklv=LEAST($mconfigsnew[max_chklv],chklv)");
			updatecache('channels');
		}
		$mconfigsnew['autoabstractlength'] = max(10,intval($mconfigsnew['autoabstractlength']));
		!$mconfigsnew['autoabstractlength'] && $mconfigsnew['autoabstractlength'] = 100;
		$mconfigsnew['rss_ttl'] = empty($mconfigsnew['rss_ttl']) ? 30 : max(0,intval($mconfigsnew['rss_ttl']));
		$mconfigsnew['search_max'] = max(0,intval($mconfigsnew['search_max']));
		$mconfigsnew['search_repeat'] = max(0,intval($mconfigsnew['search_repeat']));

		$mconfigsnew['clickscachetime'] = max(0,intval($mconfigsnew['clickscachetime']));
		$mconfigsnew['mclickscircle'] = max(0,intval($mconfigsnew['mclickscircle']));
		$mconfigsnew['onlinetimecircle'] = max(0,intval($mconfigsnew['onlinetimecircle']));
		$mconfigsnew['cotypestats'] = empty($mconfigsnew['cotypestats']) ? '' : implode(',',$mconfigsnew['cotypestats']);
		$mconfigsnew['cnodestatcircle'] = max(5,intval($mconfigsnew['cnodestatcircle']));
		$mconfigsnew['monthstats'] = empty($mconfigsnew['monthstats']) ? '' : implode(',',$mconfigsnew['monthstats']);
		$mconfigsnew['weekstats'] = empty($mconfigsnew['weekstats']) ? '' : implode(',',$mconfigsnew['weekstats']);
		$mconfigsnew['albumstats'] = empty($mconfigsnew['albumstats']) ? '' : implode(',',$mconfigsnew['albumstats']);
		$mconfigsnew['albumstatcircle'] = max(1,intval($mconfigsnew['albumstatcircle']));
		$mconfigsnew['album_newstat'] = max(0,intval($mconfigsnew['album_newstat']));
		saveconfig('basic');
		adminlog(lang('websiteset'),lang('websibaseset'));
		amessage('websitesetfinish','?entry=mconfigs&action=cfbasic');
	}
}elseif($action == 'cfvisit'){
	url_nav(lang('webparam'),$urlsarr,'cfvisit',12);
	if(!submitcheck('bmconfigs')){
		tabheader(lang('webvisiset'),'cfvisit','?entry=mconfigs&action=cfvisit');
		trbasic(lang('siteclose'),'mconfigsnew[cmsclosed]',$mconfigs['cmsclosed'],'radio');
		trbasic(lang('siteclosereason'),'mconfigsnew[cmsclosedreason]',$mconfigs['cmsclosedreason'],'btext');
		trbasic(lang('adminipaccess'),'mconfigsnew[adminipaccess]',$mconfigs['adminipaccess'],'textarea',lang('agipaccess'));
		trbasic(lang('spaceclose'),'mconfigsnew[mspacedisabled]',$mconfigs['mspacedisabled'],'radio');
		trbasic(lang('sid_self'),'mconfigsnew[sid_self]',$mconfigs['sid_self'],'radio');
		tabfooter();

		tabheader(lang('wap_set'));
		$sitestat	= array('1' => lang('wap_open'), '0' => lang('wap_close'));
		$charset	= array('0' => 'UNICODE', '1' => 'UTF-8');
		trbasic(lang('wap_status'),'',makeradio('mconfigsnew[wap_status]',$sitestat,@$mconfigs['wap_status']), '');
		trbasic(lang('wap_charset'),'',makeradio('mconfigsnew[wap_charset]',$charset,@$mconfigs['wap_charset']), '');
		tabfooter();

		tabheader(lang('memlogset'));
		trbasic(lang('minerrtime'),'mconfigsnew[minerrtime]',$mconfigs['minerrtime']);
		trbasic(lang('maxerrtimes'),'mconfigsnew[maxerrtimes]',$mconfigs['maxerrtimes']);
		tabfooter();
		tabheader(lang('memregset'));
		trbasic(lang('siteclosereg'),'mconfigsnew[registerclosed]',$mconfigs['registerclosed'],'radio');
		trbasic(lang('regclosereason'),'mconfigsnew[regclosedreason]',$mconfigs['regclosedreason'],'btext');
		trbasic(lang('censoruser'),'mconfigsnew[censoruser]',$mconfigs['censoruser'],'textarea',lang('agcensor'));
		tabfooter();
		
		tabheader(lang('regcodeset'));
		trbasic(lang('regcode_width'),'mconfigsnew[regcode_width]',$mconfigs['regcode_width']);
		trbasic(lang('regcode_height'),'mconfigsnew[regcode_height]',$mconfigs['regcode_height']);
		$regcodearr = array(
		'register' => lang('memberregis'),
		'login' => lang('memblogin'),
		'admin' => lang('backalogin'),
		'archive' => lang('add_archive'),
		'farchive' => lang('freeinfo'),
		'comment' => lang('issue_comment'),
		'answer' => lang('addanswer'),
		'reply' => lang('add_reply'),
		'offer' => lang('addoffer'),
		'report' => lang('addpickbug'),
		'payonline' => lang('cashpay'),
		'pm' => lang('send_pm'),
		);
		trbasic(lang('enableregco'),'',makecheckbox('mconfigsnew[cms_regcode][]',$regcodearr,empty($mconfigs['cms_regcode']) ? array() : explode(',',$mconfigs['cms_regcode']),6),'');
		tabfooter('bmconfigs');
		a_guide('cfvisit');
	}else{
		$mconfigsnew['regcode_width'] = max(60,intval($mconfigsnew['regcode_width']));
		$mconfigsnew['regcode_height'] = max(20,intval($mconfigsnew['regcode_height']));
		$mconfigsnew['cms_regcode'] = empty($mconfigsnew['cms_regcode']) ? '' : implode(',',$mconfigsnew['cms_regcode']);
		saveconfig('visit');
		adminlog(lang('websiteset'),lang('visiandregset'));
		amessage('websitesetfinish','?entry=mconfigs&action=cfvisit');
	}
}elseif($action == 'cfview'){
	url_nav(lang('webparam'),$urlsarr,'cfview',12);
	if(!submitcheck('bmconfigs')){
		tabheader(lang(!$sid ? 'pagebasedset' : 'subsiteset'),'cfview',"?entry=mconfigs&action=cfview$param_suffix");
		if(!$sid){
			$htmlmodearr = array('month' => lang('yearmonth').'('.lang('eg').'200701)','day' => lang('yearmonthday').'('.lang('eg').'20070101)',);
			$dateformatarr = array('Y-m-d' => lang('eg').'2008-01-01','Y-n-j' => lang('eg').'2008-1-1',);
			$timeformatarr = array('H:i' => lang('eg').'20:30','H:i:s' => lang('eg').'20:30:30',);
			trbasic(lang('gzipenable'),'mconfigsnew[gzipenable]',$mconfigs['gzipenable'],'radio');
			trbasic(lang('dateformat'),'mconfigsnew[dateformat]',makeoption($dateformatarr,$mconfigs['dateformat']),'select');
			trbasic(lang('timeformat'),'mconfigsnew[timeformat]',makeoption($timeformatarr,$mconfigs['timeformat']),'select');
			trbasic(lang('commsgforwordtime'),'mconfigsnew[msgforwordtime]',$mconfigs['msgforwordtime']);
			tabfooter();
	
			tabheader(lang('starelset'));
			trbasic(lang('weaenasta'),'mconfigsnew[enablestatic]',$mconfigs['enablestatic'],'radio');
			trbasic(lang('homedefault'),'mconfigsnew[homedefault]',$mconfigs['homedefault']);
			trbasic(lang('indestaticc'),'mconfigsnew[indexcircle]',$mconfigs['indexcircle']);
			trbasic(lang('contpagestaticci'),'mconfigsnew[archivecircle]',$mconfigs['archivecircle']);
			trbasic(lang('mcnindexcircle'),'mconfigsnew[mcnindexcircle]',$mconfigs['mcnindexcircle']);
			trbasic(lang('cn_max_addno'),'mconfigsnew[cn_max_addno]',empty($mconfigs['cn_max_addno']) ? 0 : $mconfigs['cn_max_addno']);
			trbasic(lang('max_addno'),'mconfigsnew[max_addno]',empty($mconfigs['max_addno']) ? 0 : $mconfigs['max_addno']);
			trbasic(lang('mcn_max_addno'),'mconfigsnew[mcn_max_addno]',empty($mconfigs['mcn_max_addno']) ? 0 : $mconfigs['mcn_max_addno']);
			$tnstr = "<input type=\"text\" size=\"25\" id=\"mconfigsnew[cnhtmldir]\" name=\"mconfigsnew[cnhtmldir]\" value=\"$mconfigs[cnhtmldir]\">&nbsp; 
					<input class=\"checkbox\" type=\"checkbox\" name=\"mconfigsnew[disable_htmldir]\" id=\"mconfigsnew[disable_htmldir]\" value=\"1\"".(empty($mconfigs['disable_htmldir']) ? '' : ' checked').">".lang('disable_htmldir');
			trbasic(lang('cnhtmldir'),'',$tnstr,$mconfigs['cnhtmldir']);
			trbasic(lang('infohtmldir'),'mconfigsnew[infohtmldir]',$mconfigs['infohtmldir']);
			trbasic(lang('marchtmldir'),'mconfigsnew[marchtmldir]',$mconfigs['marchtmldir']);
			trbasic(lang('arc_static_url_format'),'mconfigsnew[arccustomurl]',empty($mconfigs['arccustomurl']) ? '' : $mconfigs['arccustomurl'],'btext',lang('agcustomurl'));
			for($i = 0;$i <= $cn_max_addno;$i ++){
				$pvar = $i ? lang('addp').$i : lang('index');
				$configstr = lang('staticfomart')."<input type=\"text\" size=\"25\" id=\"mconfigsnew[cn_urls][$i]\" name=\"mconfigsnew[cn_urls][$i]\" value=\"".@$cn_urls[$i]."\">";
				$configstr .= "&nbsp; |&nbsp; ".lang('staticperiod')."<input type=\"text\" size=\"5\" id=\"mconfigsnew[cn_periods][$i]\" name=\"mconfigsnew[cn_periods][$i]\" value=\"".@$cn_periods[$i]."\">";
				trbasic(lang('catascnode').$pvar.lang('setting'),'',$configstr,'',!$i ? lang('agcnstaticfomart') : '');
			}			
			trbasic(lang('liststaticnum'),'mconfigsnew[liststaticnum]',$mconfigs['liststaticnum']);
			trbasic(lang('hiddensinurl'),'mconfigsnew[hiddensinurl]',empty($mconfigs['hiddensinurl']) ? '' : $mconfigs['hiddensinurl'],'btext',lang('aghiddensinurl'));
			trbasic(lang('dynamipage').'url'.lang('virtuastatic'),'mconfigsnew[virtualurl]',$mconfigs['virtualurl'],'radio');
			trbasic(lang('rewritephp'),'mconfigsnew[rewritephp]',$mconfigs['rewritephp'],'text',lang('agrewritephp'));
			trbasic(lang('archtmlmode'),'mconfigsnew[archtmlmode]',makeoption($htmlmodearr,$mconfigs['archtmlmode']),'select');
			tabfooter();
			
			tabheader(lang('cacherelaset'));
			trbasic(lang('cache1circle'),'mconfigsnew[cache1circle]',$mconfigs['cache1circle']);
			trbasic(lang('listcachenum'),'mconfigsnew[listcachenum]',$mconfigs['listcachenum']);
			trbasic(lang('cachemscircle'),'mconfigsnew[cachemscircle]',$mconfigs['cachemscircle']);
			trbasic(lang('mslistcachenum'),'mconfigsnew[mslistcachenum]',$mconfigs['mslistcachenum']);
			trbasic(lang('cachejscircle'),'mconfigsnew[cachejscircle]',$mconfigs['cachejscircle']);
			trbasic(lang('clearoldcache'),'mconfigsnew[clearoldcache]',$mconfigs['clearoldcache']);
		}else{
			trbasic(lang('subsstadir'),'subsitenew[dirname]',$subsite['dirname'],'text');
		}
		tabfooter('bmconfigs');
		a_guide('cfview');

	}else{
		if(!$sid){
			foreach(array('cnhtmldir','infohtmldir','marchtmldir') as $var){
				$mconfigsnew[$var] = strtolower($mconfigsnew[$var]);
				if($mconfigsnew[$var] == $mconfigs[$var]) continue;
				if(!$mconfigsnew[$var] || preg_match("/[^a-zA-Z_0-9]+/",$mconfigsnew[$var])){
					$mconfigsnew[$var] = $mconfigs[$var];
					continue;
				}
				if($mconfigs[$var] && is_dir(M_ROOT.$mconfigs[$var])){
					if(!rename(M_ROOT.$mconfigs[$var],M_ROOT.$mconfigsnew[$var])){
						$mconfigsnew[$var] = $mconfigs[$var];
					}
				}else mmkdir(M_ROOT.$mconfigsnew[$var]);
			}
			$mconfigsnew['homedefault'] = trim(strip_tags($mconfigsnew['homedefault']));
			$mconfigsnew['arccustomurl'] = preg_replace("/^\/+/",'',trim($mconfigsnew['arccustomurl']));
			$mconfigsnew['cn_max_addno'] = min(empty($_sys_cnaddmax) ? 2 : $_sys_cnaddmax,max(0,intval($mconfigsnew['cn_max_addno'])));
			if($mconfigsnew['cn_max_addno'] < $cn_max_addno){
				$db->query("UPDATE {$tblprefix}cnodes SET addnum=LEAST($mconfigsnew[cn_max_addno],addnum)");
				foreach($subsites as $k => $v) updatecache('cnodes','',$k);
				
			}
			$mconfigsnew['mcn_max_addno'] = min(empty($_sys_mcnaddmax) ? 0 : $_sys_mcnaddmax,max(0,intval($mconfigsnew['mcn_max_addno'])));
			if($mconfigsnew['mcn_max_addno'] < $mcn_max_addno){
				$db->query("UPDATE {$tblprefix}mcnodes SET addnum=LEAST($mconfigsnew[mcn_max_addno],addnum)");
				updatecache('mcnodes');
			}
			$mconfigsnew['max_addno'] = min(empty($_sys_addmax) ? 3 : $_sys_addmax,max(0,intval($mconfigsnew['max_addno'])));
			if($mconfigsnew['max_addno'] < $max_addno){
				$db->query("UPDATE {$tblprefix}channels SET addnum=LEAST($mconfigsnew[max_addno],addnum)");
				updatecache('channels');
			}
			$mconfigsnew['cn_urls'] = empty($mconfigsnew['cn_urls']) ? '' : implode(',',$mconfigsnew['cn_urls']);
			$mconfigsnew['cn_periods'] = empty($mconfigsnew['cn_periods']) ? '' : implode(',',$mconfigsnew['cn_periods']);
			$mconfigsnew['disable_htmldir'] = empty($mconfigsnew['disable_htmldir']) ? 0 : 1;
			$mconfigsnew['msgforwordtime'] = max(0,intval($mconfigsnew['msgforwordtime']));
			$mconfigsnew['indexcircle'] = max(0,intval($mconfigsnew['indexcircle']));
			$mconfigsnew['archivecircle'] = max(0,intval($mconfigsnew['archivecircle']));
			$mconfigsnew['cache1circle'] = max(0,intval($mconfigsnew['cache1circle']));
			$mconfigsnew['liststaticnum'] = max(0,intval($mconfigsnew['liststaticnum']));
			$mconfigsnew['listcachenum'] = max(0,intval($mconfigsnew['listcachenum']));
			$mconfigsnew['cachemscircle'] = max(0,intval($mconfigsnew['cachemscircle']));
			$mconfigsnew['mslistcachenum'] = max(0,intval($mconfigsnew['mslistcachenum']));
			$mconfigsnew['cachejscircle'] = max(0,intval($mconfigsnew['cachejscircle']));
			saveconfig('view');
		}else{
			$subsitenew['dirname'] = empty($subsitenew['dirname']) ? '' : strtolower(trim($subsitenew['dirname']));
			if(!$subsitenew['dirname'] || ($subsite['dirname'] == $subsitenew['dirname']) || preg_match("/[^a-zA-Z_0-9]+/",$subsitenew['dirname']) || !rename(M_ROOT.$subsite['dirname'],M_ROOT.$subsitenew['dirname'])){
				$subsitenew['dirname'] = $subsite['dirname'];
			}
			$db->query("UPDATE {$tblprefix}subsites SET dirname='$subsitenew[dirname]' WHERE sid='$sid'");
			updatecache('subsites');
		}
		adminlog(lang('websiteset'),lang('pagandtemset'));
		amessage('websitesetfinish',"?entry=mconfigs&action=cfview$param_suffix");
	}
}elseif($action == 'cfppt'){
	url_nav(lang('webparam'),$urlsarr,'cfppt',12);
	if(!submitcheck('bmconfigs')){
		tabheader(lang('uc_clientconfig'),'cfppt','?entry=mconfigs&action=cfppt');
		trbasic(lang('enableucent'),'mconfigsnew[enable_uc]',$mconfigs['enable_uc'],'radio');
		trbasic(lang('uc_api'),'mconfigsnew[uc_api]',$mconfigs['uc_api']);
		trbasic(lang('uc_ip'),'mconfigsnew[uc_ip]',$mconfigs['uc_ip']);
		trbasic(lang('uc_dbhost'),'mconfigsnew[uc_dbhost]',$mconfigs['uc_dbhost']);
		trbasic(lang('uc_dbname'),'mconfigsnew[uc_dbname]',$mconfigs['uc_dbname']);
		trbasic(lang('uc_dbuser'),'mconfigsnew[uc_dbuser]',$mconfigs['uc_dbuser']);
		trbasic(lang('uc_dbpwd'),'mconfigsnew[uc_dbpwd]',$mconfigs['uc_dbpwd'],'password');
		trbasic(lang('uc_dbpre'),'mconfigsnew[uc_dbpre]',$mconfigs['uc_dbpre']);
		trbasic(lang('uc_appid'),'mconfigsnew[uc_appid]',$mconfigs['uc_appid']);
		trbasic(lang('uc_key'),'mconfigsnew[uc_key]',$mconfigs['uc_key']);
		tabfooter();
		$pfilearr = array('08cms' => '08CMS','phpwind' => 'PHPwind',);
		$pcharsetarr = array('gbk' => 'GBK/GB2312','utf-8' => 'UTF-8','big5' => 'BIG5',);
		$pptenable = array(1 => lang('enable'), 0 => lang('disable'));
		$pptmode = array(1 => lang('server'), 0 => lang('client'));
#		tabheader(lang('pptoutsett'));
#		trbasic(lang('enablepptout'),'mconfigsnew[enable_pptout]',$mconfigs['enable_pptout'],'radio');
#		trbasic(lang('enablepptin'),'mconfigsnew[enable_pptin]',$mconfigs['enable_pptin'],'radio');
		tabheader(lang('pptset'));
		trbasic('','',makeradio('is_enable_ppt', $pptenable, $mconfigs['enable_pptout'] || $mconfigs['enable_pptin']),'');
		trbasic(lang('pptmode'),'',makeradio('ppt_mode', $pptmode, $mconfigs['enable_pptout'] ? 1 : ($mconfigs['enable_pptin'] ? 0 : -1)),'');
		trbasic(lang('pptout_file'),'mconfigsnew[pptout_file]',makeoption($pfilearr,$mconfigs['pptout_file']),'select');
		trbasic(lang('pptout_charset'),'mconfigsnew[pptout_charset]',makeoption($pcharsetarr,$mconfigs['pptout_charset']),'select');
		trbasic(lang('pptkey'),'ppt_key',$mconfigs['pptin_key'] ? $mconfigs['pptin_key'] : $mconfigs['pptout_key']);
		echo '<tr><td class="txt txtleft fB borderright" colspan="2"><div style="margin:0 100px; padding:0 10px;color:#134D9D; background:#F1F7FD">'.lang('server').'</div></td></tr>';
		trbasic(lang('pptin_url'),'mconfigsnew[pptout_url]',$mconfigs['pptout_url']);
#		trbasic(lang('pptoutkey'),'mconfigsnew[pptout_key]',$mconfigs['pptout_key']);
#		tabfooter();
#		tabheader(lang('pptinset'));
#		trbasic(lang('pptinkey'),'mconfigsnew[pptin_key]',$mconfigs['pptin_key']);
		echo '<tr><td class="txt txtleft fB borderright" colspan="2"><div style="margin:0 100px; padding:0 10px;color:#134D9D; background:#F1F7FD">'.lang('client').'</div></td></tr>';
		trbasic(lang('pptin_expire'),'mconfigsnew[pptin_expire]',$mconfigs['pptin_expire']);
		trbasic(lang('pptin_url'),'mconfigsnew[pptin_url]',$mconfigs['pptin_url']);
		trbasic(lang('pptin_register'),'mconfigsnew[pptin_register]',$mconfigs['pptin_register']);
		trbasic(lang('pptin_login'),'mconfigsnew[pptin_login]',$mconfigs['pptin_login']);
		trbasic(lang('pptin_logout'),'mconfigsnew[pptin_logout]',$mconfigs['pptin_logout']);
		tabfooter('bmconfigs');
		a_guide('cfppt');
	}else{
		if(($mconfigsnew['enable_uc'] && empty($mconfigs['enable_uc']) || !$is_enable_ppt)){
			//使用UC
			$mconfigsnew['enable_pptout'] = 0;
			$mconfigsnew['enable_pptin']  = 0;
		}else{
			$mconfigsnew['enable_uc'] = 0;
			if(empty($ppt_mode)){
				//使用客户端
				$mconfigsnew['enable_pptout'] = 0;
				$mconfigsnew['enable_pptin']  = 1;
				$mconfigsnew['pptin_key']	  = $ppt_key;
				$mconfigsnew['pptout_key']	  = '';
			}else{
				//使用服务端
				$mconfigsnew['enable_pptout'] = 1;
				$mconfigsnew['enable_pptin']  = 0;
				$mconfigsnew['pptin_key']	  = '';
				$mconfigsnew['pptout_key']	  = $ppt_key;
			}
		}

		saveconfig('ppt');
		adminlog(lang('websiteset'),lang('webpptpptset'));
		amessage('websitesetfinish','?entry=mconfigs&action=cfppt');
	}
}elseif($action == 'cfpay'){
	url_nav(lang('webparam'),$urlsarr,'cfpay',12);
	if(!submitcheck('bmconfigs')){
		tabheader(lang('busrelbasset'),'cfpay','?entry=mconfigs&action=cfpay');
		trbasic(lang('onlpayarrautsav'),'mconfigsnew[onlineautosaving]',$mconfigs['onlineautosaving'],'radio');
		trbasic(lang('enagoostosta'),'mconfigsnew[enablestock]',isset($mconfigs['enablestock']) ? $mconfigs['enablestock'] : 0,'radio');
		trbasic(lang('cartgooamolim'),'mconfigsnew[cartmaxlimited]',!empty($mconfigs['cartmaxlimited']) ? $mconfigs['cartmaxlimited'] : '','text');
		trbasic(lang('cartkey'),'mconfigsnew[cartkey]',!empty($mconfigs['cartkey']) ? $mconfigs['cartkey'] : '','text');
		trbasic(lang('extract_mincount'),'mconfigsnew[extract_mincount]',!empty($mconfigs['extract_mincount']) ? $mconfigs['extract_mincount'] : 50,'text');
		tabfooter();

		tabheader(lang('mypaymode'));
		$pmodearr = array('0' => lang('paynext'),'1' => lang('paycurrency'),'2' => lang('payalipay'),'3' => lang('paytenpay'));
		$omodearr = array('0' => lang('be_confirm'),'1' => lang('no_confirm'));
		$payarr = array();
		for($i = 0; $i < 32; $i++)if(@$mconfigs['cfg_paymode'] & (1 << $i))$payarr[] = $i;
		for($i = 1; $i < 4; $i++)${"sp$i"} = isset($mconfigs["shipingfee$i"]) ? $mconfigs["shipingfee$i"] : -1;
		trbasic(lang('paymode'),'',makecheckbox('paymodenew[]',$pmodearr,$payarr),'');
		trbasic(lang('ordmode'),'',makeradio('mconfigsnew[cfg_ordermode]',$omodearr,@$cfg_ordermode),'');
		trbasic('<input name="spmd[1]" type="checkbox" class="checkbox" value="1"'.($sp1<0?'':' checked="checked"').' />'.lang('shipingfee1'),'shipingfee[1]',$sp1<0?0:$sp1);
		trbasic('<input name="spmd[2]" type="checkbox" class="checkbox" value="1"'.($sp2<0?'':' checked="checked"').' />'.lang('shipingfee2'),'shipingfee[2]',$sp2<0?0:$sp2);
		trbasic('<input name="spmd[3]" type="checkbox" class="checkbox" value="1"'.($sp3<0?'':' checked="checked"').' />'.lang('shipingfee3'),'shipingfee[3]',$sp3<0?0:$sp3);
		tabfooter();

		tabheader(lang('onlpayset',lang('alipey')));
		trbasic(lang('partnerid'),'mconfigsnew[cfg_alipay]',@$mconfigs['cfg_alipay']);
		trbasic(lang('alipay_partner'),'mconfigsnew[cfg_alipay_partnerid]',@$mconfigs['cfg_alipay_partnerid']);
		trbasic(lang('paykey'),'mconfigsnew[cfg_alipay_keyt]',@$mconfigs['cfg_alipay_keyt']);
		tabfooter();
		tabheader(lang('onlpayset',lang('tenpay')));
		trbasic(lang('partnerid'),'mconfigsnew[cfg_tenpay]',@$mconfigs['cfg_tenpay']);
		trbasic(lang('paykey'),'mconfigsnew[cfg_tenpay_keyt]',@$mconfigs['cfg_tenpay_keyt']);
		tabfooter();

		echo "<input class=\"button\" type=\"submit\" name=\"bmconfigs\" value=\"".lang('submit')."\"></form>";
		a_guide('cfpay');
	}else{
		$mconfigs['enablestock'] = empty($mconfigs['enablestock']) ? 0 : 1;
		if($mconfigs['enablestock'] != $mconfigsnew['enablestock']){
			alter_purchase();
		}
		$mconfigsnew['cartmaxlimited'] = max(0,intval($mconfigsnew['cartmaxlimited']));
		$mconfigsnew['extract_mincount'] = round(max(0,floatval($mconfigsnew['extract_mincount'])), 2);
		$mconfigsnew['cartkey'] || $mconfigsnew['cartkey'] = random(32);
		$mconfigsnew['cfg_paymode'] = 0;
		empty($paymodenew) && $paymodenew = array();
		foreach($paymodenew as $v)$mconfigsnew['cfg_paymode'] = $mconfigsnew['cfg_paymode'] | (1 << $v);
		foreach($shipingfee as $k => $v)$mconfigsnew["shipingfee$k"] = empty($spmd[$k])?-1:max(0, round(floatval($v),2));
		saveconfig('pay');
		adminlog(lang('websbuspayset'),lang('websbuspayset'));
		amessage('paysetfinish','?entry=mconfigs&action=cfpay');
	}
}elseif($action == 'cfupload'){
	url_nav(lang('webparam'),$urlsarr,'cfupload',12);
	$vftp_password = $tftp_password = '';
	if(!empty($mconfigs['ftp_password'])){
		$tftp_password = authcode($mconfigs['ftp_password'],'DECODE',md5($authkey));
		$vftp_password = $tftp_password{0}.'********'.$tftp_password{strlen($tftp_password) - 1};
	}
	if(!submitcheck('bmconfigs')){
		$upatharr = array('0' => lang('default').'('.lang('attachmenttype').')','month' => lang('attachmenttype').'+'.lang('month'),'day' => lang('attachmenttype').'+'.lang('date'));
		$watermarktypearr = array('0' => 'GIF'.lang('imagewaterm'),'1' => 'PNG'.lang('imagewaterm'));
		$atmbrowserarr = array('0' => lang('alluser'),'1' => lang('allmember'),'2' => lang('onlyadmini'));
		tabheader(lang('uplattaset'),'cfupload','?entry=mconfigs&action=cfupload');
		trbasic(lang('dir_userfile'),'mconfigsnew[dir_userfile]',$mconfigs['dir_userfile']);
		trbasic(lang('attacsmal'),'mconfigsnew[atm_smallsite]',$mconfigs['atm_smallsite']);
		trbasic(lang('path_userfile'),'mconfigsnew[path_userfile]',makeoption($upatharr,$mconfigs['path_userfile']),'select');
		trbasic(lang('player_width'),'mconfigsnew[player_width]',$mconfigs['player_width']);
		trbasic(lang('player_height'),'mconfigsnew[player_height]',$mconfigs['player_height']);
		trbasic(lang('allnouupl'),'mconfigsnew[upload_nouser]',$mconfigs['upload_nouser'],'radio');
		trbasic(lang('attbroperset'),'',makeradio('mconfigsnew[atmbrowser]',$atmbrowserarr,$mconfigs['atmbrowser']),'');
		tabfooter();
				
		tabheader(lang('imawateset'));
		$str = "<table cellspacing=\"0\" cellpadding=\"4\" border=\"0\" style=\"margin-bottom: 3px; margin-top:3px;\">".
		"<tr class=\"txt\"><td colspan=\"3\"><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"0\"".(empty($mconfigs['watermarkstatus']) ? " checked" : "").">".lang('notaddwater')."</td>".
		"</tr><tr align=\"center\" class=\"txt\">".
		"<td><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"1\"".($mconfigs['watermarkstatus']==1 ? " checked" : "").">".lang('lefttop')."</td>".
		"<td><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"2\"".($mconfigs['watermarkstatus']==2 ? " checked" : "").">".lang('centertop')."</td>".
		"<td><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"3\"".($mconfigs['watermarkstatus']==3 ? " checked" : "").">".lang('righttop')."</td>".
		"</tr><tr align=\"center\" class=\"txt\">".
		"<td><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"4\"".($mconfigs['watermarkstatus']==4 ? " checked" : "").">".lang('leftcenter')."</td>".
		"<td><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"5\"".($mconfigs['watermarkstatus']==5 ? " checked" : "").">".lang('center')."</td>".
		"<td><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"6\"".($mconfigs['watermarkstatus']==6 ? " checked" : "").">".lang('rightcenter')."</td>".
		"</tr><tr align=\"center\" class=\"txt\">".
		"<td><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"7\"".($mconfigs['watermarkstatus']==7 ? " checked" : "").">".lang('leftbottom')."</td>".
		"<td><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"8\"".($mconfigs['watermarkstatus']==8 ? " checked" : "").">".lang('centerbottom')."</td>".
		"<td><input class=\"radio\" type=\"radio\" name=\"mconfigsnew[watermarkstatus]\" value=\"9\"".($mconfigs['watermarkstatus']==9 ? " checked" : "").">".lang('rightbottom')."</td>".
		"</tr></table>";
		trbasic(lang('uplimaaddwate'),'',$str,'');
		trbasic(lang('imawattyp'),'mconfigsnew[watermarktype]',makeoption($watermarktypearr,$mconfigs['watermarktype']),'select');
		trbasic(lang('watermarktrans'),'mconfigsnew[watermarktrans]',$mconfigs['watermarktrans']);
		trbasic(lang('watermarkquality'),'mconfigsnew[watermarkquality]',$mconfigs['watermarkquality']);
		tabfooter();
		tabheader(lang('rematftpset'));
		trbasic(lang('enaatftpupl'),'mconfigsnew[ftp_enabled]',$mconfigs['ftp_enabled'],'radio');
		trbasic(lang('ftp_host'),'mconfigsnew[ftp_host]',$mconfigs['ftp_host']);
		trbasic(lang('ftp_port'),'mconfigsnew[ftp_port]',$mconfigs['ftp_port']);
		trbasic(lang('ftp_user'),'mconfigsnew[ftp_user]',$mconfigs['ftp_user']);
		trbasic(lang('ftp_password'),'mconfigsnew[ftp_password]',$vftp_password);
		trbasic(lang('ftp_timeout'),'mconfigsnew[ftp_timeout]',$mconfigs['ftp_timeout']);
		trbasic(lang('ftp_pasv'),'mconfigsnew[ftp_pasv]',$mconfigs['ftp_pasv'],'radio');
		trbasic(lang('ftp_ssl'),'mconfigsnew[ftp_ssl]',$mconfigs['ftp_ssl'],'radio');
		trbasic(lang('ftp_dir'),'mconfigsnew[ftp_dir]',$mconfigs['ftp_dir']);
		trbasic(lang('ftp_url'),'mconfigsnew[ftp_url]',$mconfigs['ftp_url']);
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bmconfigs\" value=\"".lang('submit')."\">&nbsp; &nbsp;".
		"<input class=\"button\" type=\"submit\" name=\"ftpcheck\" value=\"".lang('ftpcheck')."\" onclick=\"this.form.action='?entry=checks&action=ftpcheck';this.form.target='ftpcheckiframe';\"><iframe name=\"ftpcheckiframe\" style=\"display: none\"></iframe>".
		"</form>";
		a_guide('cfupload');
	}else{
		$mconfigsnew['dir_userfile'] = trim(strip_tags($mconfigsnew['dir_userfile']));
		$mconfigsnew['atm_smallsite'] = strtolower(trim($mconfigsnew['atm_smallsite']));
		$mconfigsnew['atm_smallsite'] .= !ereg("/$",$mconfigsnew['atm_smallsite']) ? '/' : '';
		$mconfigsnew['atm_smallsite'] = (!eregi("http://",$mconfigsnew['atm_smallsite']) || eregi($hosturl,$mconfigsnew['atm_smallsite'])) ? '' : $mconfigsnew['atm_smallsite'];
		$mconfigsnew['player_width'] = max(0,intval($mconfigsnew['player_width']));
		$mconfigsnew['player_height'] = max(0,intval($mconfigsnew['player_height']));
		$mconfigsnew['watermarktrans'] = min(100,max(0,intval($mconfigsnew['watermarktrans'])));
		$mconfigsnew['watermarkquality'] = min(100,max(0,intval($mconfigsnew['watermarkquality'])));
		$mconfigsnew['ftp_host'] = trim(strip_tags($mconfigsnew['ftp_host']));
		$mconfigsnew['ftp_port'] = max(1,intval($mconfigsnew['ftp_port']));
		$mconfigsnew['ftp_user'] = trim(strip_tags($mconfigsnew['ftp_user']));
		if($mconfigsnew['ftp_password'] != $vftp_password){
			$mconfigsnew['ftp_password'] =  $mconfigsnew['ftp_password'] ? authcode($mconfigsnew['ftp_password'],'ENCODE',md5($authkey)) : '';
		}else $mconfigsnew['ftp_password'] = $mconfigs['ftp_password'];
		$mconfigsnew['ftp_timeout'] = max(0,intval($mconfigsnew['ftp_timeout']));
		$mconfigsnew['ftp_dir'] = trim(strip_tags($mconfigsnew['ftp_dir']));
		$mconfigsnew['ftp_url'] = trim(strip_tags($mconfigsnew['ftp_url']));
		saveconfig('upload');
		adminlog(lang('websiteset'),lang('upanddownset'));
		amessage('websitesetfinish','?entry=mconfigs&action=cfupload');
	}
}elseif($action == 'cfmobmail'){
	url_nav(lang('webparam'),$urlsarr,'cfmobmail',12);
	if(!submitcheck('bmconfigs')){
		$modearr = array(1 => lang('mailmode1'),2 => lang('mailmode2'),3 => lang('mailmode3'),);
		$delimiterarr = array(1 => lang('maildelimiter1'),2 => lang('maildelimiter2'),3 => lang('maildelimiter3'),);
		tabheader(lang('emaiset'),'cfmail','?entry=mconfigs&action=cfmobmail');
		echo "<tr class=\"txt\"><td class=\"txt txtright fB borderright\">".lang('emaisenmod')."</td>\n".
		"<td class=\"txtL\">\n".
		"<input class=\"radio\" type=\"radio\" name=\"mconfigsnew[mail_mode]\" value=\"1\" onclick=\"\$id('mail_mod1').style.display = 'none';\$id('mail_mod2').style.display = 'none';\"".($mconfigs['mail_mode'] <= 1 ? ' checked' : '').">".lang('mailmode1')."<br>\n".
		"<input class=\"radio\" type=\"radio\" name=\"mconfigsnew[mail_mode]\" value=\"2\" onclick=\"\$id('mail_mod1').style.display = '';\$id('mail_mod2').style.display = '';\"".($mconfigs['mail_mode'] == 2 ? ' checked' : '').">".lang('mailmode2')."<br>\n".
		"<input class=\"radio\" type=\"radio\" name=\"mconfigsnew[mail_mode]\" value=\"3\" onclick=\"\$id('mail_mod1').style.display = '';\$id('mail_mod2').style.display = 'none';\"".($mconfigs['mail_mode'] == 3 ? ' checked' : '').">".lang('mailmode3')."<br>\n".
		"</td></tr>\n";
		echo "<tbody id=\"mail_mod1\" style=\"display:".($mconfigs['mail_mode'] > 1 ? '' : 'none')."\">";
		trbasic(lang('mail_smtp'),'mconfigsnew[mail_smtp]',$mconfigs['mail_smtp']);
		trbasic(lang('mail_port'),'mconfigsnew[mail_port]',$mconfigs['mail_port']);
		echo "</tbody>";
		echo "<tbody id=\"mail_mod2\" style=\"display:".($mconfigs['mail_mode'] == 2 ? '' : 'none')."\">";
		trbasic(lang('mail_auth'),'mconfigsnew[mail_auth]',$mconfigs['mail_auth'],'radio');
		trbasic(lang('mail_from'),'mconfigsnew[mail_from]',$mconfigs['mail_from']);
		trbasic(lang('mail_user'),'mconfigsnew[mail_user]',$mconfigs['mail_user']);
		trbasic(lang('mail_pwd'),'mconfigsnew[mail_pwd]',$mconfigs['mail_pwd'],'password');
		echo "</tbody>";	
		trbasic(lang('mail_delimiter'),'mconfigsnew[mail_delimiter]',makeoption($delimiterarr,$mconfigs['mail_delimiter']),'select');
		trbasic(lang('mail_silent'),'mconfigsnew[mail_silent]',$mconfigs['mail_silent'],'radio');
		trbasic(lang('mail_to'),'mconfigsnew[mail_to]');
		trbasic(lang('mail_sign'),'mconfigsnew[mail_sign]');
		
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bmconfigs\" value=\"".lang('submit')."\">&nbsp; &nbsp;".
		"<input class=\"button\" type=\"button\" name=\"mailcheck\" value=\"".lang('mailtest')."\" onclick=\"var url=this.form.action;this.form.action='?entry=checks&action=mailcheck';this.form.target='mailcheckiframe';this.form.submit();this.form.action=url;this.form.target='_self'\"><iframe name=\"mailcheckiframe\" style=\"display: none\"></iframe>".
		"</form>";

		tabheader(lang('mobileset'),'cfmobile','?entry=mconfigs&action=cfmobmail');
		echo "<tr class=\"txt\"><td class=\"txt txtright fB borderright\">".lang('msgcodemode')."</td>\n".
		"<td class=\"txtL\">\n".
		"<input class=\"radio\" type=\"radio\" id=\"msgcode_mode2\" name=\"mconfigsnew[msgcode_mode]\" value=\"2\" onclick=\"\$id('msgcode1').style.display = 'none';\$id('msgcode2').style.display = '';\"".(@$mconfigs['msgcode_mode'] == 2 ? ' checked="checked"' : '')."><label for=\"msgcode_mode2\">".lang('msgcode2')."</label> ".
		"<input class=\"radio\" type=\"radio\" id=\"msgcode_mode1\" name=\"mconfigsnew[msgcode_mode]\" value=\"1\" onclick=\"\$id('msgcode1').style.display = '';\$id('msgcode2').style.display = 'none';\"".(@$mconfigs['msgcode_mode'] == 1 ? ' checked="checked"' : '')."><label for=\"msgcode_mode1\">".lang('msgcode1')."</label> ".
		"<input class=\"radio\" type=\"radio\" id=\"msgcode_mode0\" name=\"mconfigsnew[msgcode_mode]\" value=\"0\" onclick=\"\$id('msgcode1').style.display = 'none';\$id('msgcode2').style.display = 'none';\"".(@!$mconfigs['msgcode_mode'] ? ' checked="checked"' : '')."><label for=\"msgcode_mode0\">".lang('msgcode0')."</label> ".
		"</td></tr>\n";
		echo "<tbody id=\"msgcode2\" style=\"display:".(@$mconfigs['msgcode_mode'] == 2 ? '' : 'none')."\">";
		trbasic(lang('msggate'),'','<input class="radio" type="radio" id="msgcode_gate1" name="mconfigsnew[msgcode_gate]" value="1"'.(@$mconfigs['msgcode_gate'] == 1 ? ' checked="checked"' : '')."><label for=\"msgcode_gate1\">".lang('msggate1')."</label>",'');
		trbasic(lang('msgcode_sp1'),'mconfigsnew[msgcode_sp1]',@$mconfigs['msgcode_sp1']);
		trbasic(lang('msgcode_pw1'),'mconfigsnew[msgcode_pw1]',@$mconfigs['msgcode_pw1']);
		trbasic(lang('msggate'),'','<input class="radio" type="radio" id="msgcode_gate2" name="mconfigsnew[msgcode_gate]" value="2"'.(@$mconfigs['msgcode_gate'] != 1 ? ' checked="checked"' : '')."><label for=\"msgcode_gate2\">".lang('msggate2')."</label>",'');
		trbasic(lang('msgcode_sp2'),'mconfigsnew[msgcode_sp2]',@$mconfigs['msgcode_sp2']);
		trbasic(lang('msgcode_pw2'),'mconfigsnew[msgcode_pw2]',@$mconfigs['msgcode_pw2']);
		trbasic(lang('msgcode_sms'),'mconfigsnew[msgcode_sms]',@$mconfigs['msgcode_sms'],'textarea',lang('msgcode_sms_tip'));
		echo "</tbody>";
		echo "<tbody id=\"msgcode1\" style=\"display:".(@$mconfigs['msgcode_mode'] == 1 ? '' : 'none')."\">";
		trbasic(lang('msgcode_msg'),'mconfigsnew[msgcode_msg]',@$mconfigs['msgcode_msg'],'textarea',lang('msgcode_msg_tip'));
		echo "</tbody>";
		tabfooter();
		echo '<input class="button" type="submit" name="bmconfigs" value="'.lang('submit').'"/>&nbsp; &nbsp;'
			.'<input type="hidden" name="mobmode" value="1"/>'/*
			.'<input class="button" type="button" name="mobilecheck" value="'.lang('mobiletest').'" onclick="var url=this.form.action;this.form.action=\'?entry=checks&action=mobilecheck\';this.form.target=\'mobilecheckiframe\';this.form.submit();this.form.action=url;this.form.target=\'_self\'"/><iframe name="mobilecheckiframe" style="display: none"></iframe>'*/
			.'</form>';
		a_guide('cfmail');
	}else{
		if(empty($mobmode)){
			$mconfigsnew['mail_smtp'] = trim($mconfigsnew['mail_smtp']);
			$mconfigsnew['mail_port'] = trim($mconfigsnew['mail_port']);
			$mconfigsnew['mail_from'] = trim($mconfigsnew['mail_from']);
			$mconfigsnew['mail_user'] = trim($mconfigsnew['mail_user']);
			$mconfigsnew['mail_pwd'] = trim($mconfigsnew['mail_pwd']);
			unset($mconfigsnew['mail_sign'],$mconfigsnew['mail_to']);
		}
		
		saveconfig('mail');
		adminlog(lang('mailset'),lang('upanddownset'));
		amessage(empty($mobmode) ? 'mailsetfinish' : 'mobilesetfinish','?entry=mconfigs&action=cfmobmail');
	}
}
function saveconfig($cftype){
	global $mconfigs,$mconfigsnew,$db,$tblprefix;
	foreach($mconfigsnew as $k => $v){
		if(!isset($mconfigs[$k]) || $mconfigs[$k] != $v) $db->query("REPLACE INTO {$tblprefix}mconfigs (varname,value,cftype) VALUES ('$k','$v','$cftype')");
	}
	updatecache('mconfigs');
}
function alter_purchase(){
	global $db,$tblprefix,$mconfigsnew,$channels,$commus;
	$chids = array();
	foreach($channels as $k => $v) if(@$commus[$v['cuid']]['cclass'] == 'purchase') $chids[] = $k;
	if($chids){
		$db->query("UPDATE {$tblprefix}fields SET available=".(empty($mconfigsnew['enablestock']) ? 0 : 1)." WHERE ename='storage' AND chid ".multi_str($chids));
		foreach($chids as $chid) updatecache('fields',$chid);
	}
}

?>
