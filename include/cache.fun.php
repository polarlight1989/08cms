<?php
!defined('M_COM') && exit('No Permission');
function rebuild_cache($sid = 0,$except = ''){//$sid = -1为整站缓存更新
	$excepts = array();
	if($except) $excepts = explode(',',$except);

	//不区分子站的缓存
	$cacarr = array(
	'channels','fchannels','mchannels','players','fcatalogs','currencys','grouptypes','cotypes','rprojects','permissions',
	'crprojects','initfields','initmfields','mtconfigs','amconfigs','commus','mcommus','sitemaps','mconfigs',
	'localfiles','crprices','cafields','ccfields','pfields','ofields','rfields','cfields','bfields','vcatalogs','badwords','wordlinks','dbsources','splangs',
	'subsites','mmenus','mprojects','uprojects','freeinfos','userurls','usualurls','dbfields','mcatalogs','mffields','mlfields','mcfields','mrfields','mbfields',
	'alangs','mlangs','clangs','amsgs','cmsgs','mmsgs','matypes','aurls','inurls','murls','inmurls','ucotypes','repugrades','faces','domains','mcnodes',
	);
	foreach($cacarr as $k) if(!in_array($k,$excepts)) updatecache($k);
	if(!in_array('menus',$excepts)){
		foreach(array(0,1) as $k) updatecache('menus',$k);//后台菜单
	}
	if(!in_array('usednames',$excepts)){
		foreach(array('fields','mfields','cafields','ccfields','ffields','pfields','ofields','rfields','cfields','bfields','mffields','mlfields','mcfields','mrfields','mbfields',) as $k){
			updatecache('usednames',$k);
		}
	}
	if(!in_array('fields',$excepts)){
		$vars = array_keys(reload_cache('channels'));
		foreach($vars as $k) updatecache('fields',$k);
	}
	if(!in_array('ffields',$excepts)){
		$vars = array_keys(reload_cache('fchannels'));
		foreach($vars as $k) updatecache('ffields',$k);
	}
	if(!in_array('mafields',$excepts)){
		$vars = array_keys(reload_cache('matypes'));
		foreach($vars as $k) updatecache('mafields',$k);
	}
	if(!in_array('mfields',$excepts)){
		$vars = array_keys(reload_cache('mchannels'));
		foreach($vars as $k) updatecache('mfields',$k);
	}
	if(!in_array('usergroups',$excepts)){
		$vars = array_keys(reload_cache('grouptypes'));
		foreach($vars as $k) updatecache('usergroups',$k);
	}
	if(!in_array('coclasses',$excepts)){
		$vars = array_keys(reload_cache('cotypes'));
		foreach($vars as $k) updatecache('coclasses',$k);
	}

	//处理需要区分子站的缓存
	if($sid == -1){
		$sids = array_keys(reload_cache('subsites'));
		$sids[] = 0;
	}else $sids = array($sid);
	$s_cacarr = array('catalogs','cnconfigs','cnodes','gmodels','gmissions',);
	foreach($sids as $v){
		foreach($s_cacarr as $k) if(!in_array($k,$excepts)) updatecache($k,'',$v);
	}
	
}
function updatecache($cname,$mode='',$sid=0){
	global $db,$tblprefix;
	switch($cname){
		case 'subsites':
			$$cname = mfetch_array('subsites','sid','*','','vieworder,sid','channels,commus');
			cache2file($$cname,$cname);
			break;
		case 'channels':
			$$cname = mfetch_array('channels','chid','*','','vieworder,chid','usetting');
			cache2file($$cname,$cname);
			break;
		case 'fchannels':
			$$cname = mfetch_array('fchannels','chid','*','','chid');
			cache2file($$cname,$cname);
			break;
		case 'fcatalogs':
			$$cname = mfetch_array('fcatalogs','fcaid','*','','vieworder,fcaid','usetting');
			$$cname = order_arr($$cname,0);
			cache2file($$cname,$cname);
			break;
		case 'mcatalogs':
			$$cname = mfetch_array('mcatalogs','mcaid','*','','vieworder,mcaid');
			cache2file($$cname,$cname);
			break;
		case 'mchannels':
			$$cname = mfetch_array('mchannels','mchid','*','','mchid');
			cache2file($$cname,$cname);
			break;
		case 'dbsources':
			$$cname = mfetch_array('dbsources','dsid','*','','dsid');
			cache2file($$cname,$cname);
			break;
		case 'players':
			$$cname = mfetch_array('players','plid','*','','vieworder,plid');
			cache2file($$cname,$cname);
			break;
		case 'gmodels':
			$$cname = mfetch_array('gmodels','gmid','*',"sid=$sid",'gmid','gfields');
			cache2file($$cname,$cname,'',$sid);
			break;
		case 'gmissions':
			$$cname = mfetch_array('gmissions','gsid','*',"sid=$sid",'gsid','fsettings,dvalues');
			cache2file($$cname,$cname,'',$sid);
			break;
		case 'matypes':
			$$cname = mfetch_array('matypes','matid','*','','vieworder,matid','','');
			cache2file($$cname,$cname);
			break;
		case 'catalogs':
			include_once M_ROOT."./include/parse/general.php";
			$catalogs = mfetch_array('catalogs','caid','*',"sid=$sid",'vieworder,caid');
			$catalogs = order_arr($catalogs,0);
			$i = 0;
			foreach($catalogs as $k => $v){
				$db->query("UPDATE {$tblprefix}catalogs SET trueorder=$i WHERE caid=$k",'SILENT');
				$i ++;
			}
			arr_tag2atm($catalogs,'ca');
			cache2file($catalogs,$cname,'',$sid);
			$acatalogs = mfetch_array('catalogs','caid','caid,pid,sid,level,title,dirname,isframe','','sid,trueorder,caid');
			cache2file($acatalogs,'acatalogs');
			break;
		case 'currencys':
			$$cname = mfetch_array('currencys','crid','*','','crid');
			cache2file($$cname,$cname);
			break;
		case 'grouptypes':
			$$cname = mfetch_array('grouptypes','gtid','*','','gtid');
			cache2file($$cname,$cname);
			break;
		case 'usergroups':
			$$cname = mfetch_array('usergroups','ugid','*',"gtid='$mode'",'currency DESC,prior,ugid','amcids');
			cache2file($$cname,$cname.$mode,$cname);
			break;
		case 'cotypes':
			$$cname = mfetch_array('cotypes','coid','*','','vieworder,coid');
			cache2file($$cname,$cname);
			break;
		case 'ucotypes':
			$$cname = mfetch_array('ucotypes','ucoid','*','','vieworder,ucoid');
			cache2file($$cname,$cname);
			foreach($ucotypes as $k => $v){
				$ucoclasses = mfetch_array('ucoclass','uccid','*',"ucoid='$k'",'vieworder,uccid');
				cache2file($ucoclasses,'ucoclasses'.$k);
			}
			break;
		case 'coclasses':
			include_once M_ROOT."./include/parse/general.php";
			$coclasses = mfetch_array('coclass','ccid','*',"coid='$mode'",'vieworder','conditions');
			$coclasses = order_arr($coclasses,0);
			$i = 0;
			foreach($coclasses as $k => $v){
				$db->query("UPDATE {$tblprefix}coclass SET trueorder=$i WHERE ccid=$k",'SILENT');
				$i ++;
			}
			arr_tag2atm($coclasses,'cc');
			cache2file($coclasses,$cname.$mode,$cname);
			break;
		case 'splangs':
			$$cname = mfetch_array('splangs','ename','*','','vieworder,slid');
			foreach($splangs as $k => $v) $splangs[$k] = $v['content'];
			cache2file($$cname,$cname);
			break;
		case 'rprojects':
			$$cname = mfetch_array('rprojects','rpid','*','','rpid','rmfiles','excludes');
			cache2file($$cname,$cname);
			break;
		case 'mprojects':
			$$cname = mfetch_array('mprojects','mpid','*','','mpid');
			cache2file($$cname,$cname);
			break;
		case 'uprojects':
			$$cname = mfetch_array('uprojects','upid','*','','gtid,upid');
			cache2file($$cname,$cname);
			break;
		case 'permissions':
			$$cname = mfetch_array('permissions','pmid','*','','vieworder,pmid');
			cache2file($$cname,$cname);
			break;
		case 'cnconfigs':
			$$cname = mfetch_array('cnconfigs','cncid','*',"sid=$sid",'level,vieworder','configs');
			cache2file($$cname,$cname,'',$sid);
			break;
		case 'crprojects':
			$$cname = mfetch_array('crprojects','crpid','*','','crpid');
			cache2file($$cname,$cname);
			break;
		case 'initfields':
			$$cname = mfetch_array('fields','ename','*','chid=0','issystem DESC,fid ASC');
			cache2file($$cname,$cname);
			break;
		case 'initmfields':
			$$cname = mfetch_array('mfields','ename','*','mchid=0','issystem DESC,mfid ASC');
			cache2file($$cname,$cname);
			break;
		case 'mtconfigs':
			$$cname = mfetch_array('mtconfigs','mtcid','*','','mtcid','setting,arctpls');
			cache2file($$cname,$cname);
			break;
		case 'amconfigs':
			$$cname = mfetch_array('amconfigs','amcid','*','','amcid','anodes,fnodes,mnodes');
			cache2file($$cname,$cname);
			break;
		case 'commus':
			$$cname = mfetch_array('commus','cuid','*','isbk=0','issystem DESC,cuid ASC','setting,usetting');
			cache2file($$cname,$cname);
			break;
		case 'mcommus':
			$$cname = mfetch_array('mcommus','cuid','*','isbk=0','issystem DESC,cuid ASC','setting,usetting');
			cache2file($$cname,$cname);
			break;
		case 'sitemaps':
			$$cname = mfetch_array('sitemaps','ename','*','','vieworder','setting');
			cache2file($$cname,$cname);
			break;
		case 'cnodes':
			cnodes_update($cname,$sid);
			break;
		case 'mcnodes':
			mcnodes_update($cname);
			break;
		case 'freeinfos':
			$$cname = mfetch_array('freeinfos','fid','*','','fid');
			cache2file($$cname,$cname);
			break;
		case 'mconfigs':
			global $mcharset,$cms_version,$homedefault,$cms_abs;
			$btags = array();
			$$cname = mfetch_array('mconfigs','varname','*',"cftype<>''",'cftype');
			$bvarnames = array('hostname','hosturl','cmsname','cmsurl','cmslogo','cmstitle','cmskeyword',
				'cmsdescription','cms_icpno','bazscert','copyright',);
			$expnames = array('cn_urls','cn_periods',);
			foreach($mconfigs as $k => $v){
				$mconfigs[$k] = $v['value'];
				in_array($k,$bvarnames) && $btags[$k] = $v['value'];
				in_array($k,$expnames) && $mconfigs[$k] = explode(',',$v['value']);
			}
			$mconfigs['cms_abs'] = $btags['cms_abs'] = strpos($mconfigs['cmsurl'],$mconfigs['hosturl']) === FALSE ? ($mconfigs['hosturl'].$mconfigs['cmsurl']) : $mconfigs['cmsurl'];
			$mconfigs['cms_rel'] = $btags['cms_rel'] = strpos($mconfigs['cmsurl'],$mconfigs['hosturl']) === FALSE ? $mconfigs['cmsurl'] : str_replace($mconfigs['hosturl'],'',$mconfigs['cmsurl']);
			$mconfigs['memberurl'] = $btags['memberurl'] = empty($cms_abs) ? $mconfigs['cms_abs'].$mconfigs['memberdir'].'/' : view_url($mconfigs['memberdir'].'/');
			$mconfigs['mspaceurl'] = $btags['mspaceurl'] = empty($cms_abs) ? $mconfigs['cms_abs'].$mconfigs['mspacedir'].'/' : view_url($mconfigs['mspacedir'].'/');
			$btags['mcharset'] = $mcharset;
			$btags['version'] = $cms_version;
			$btags['tplurl'] = $mconfigs['cms_abs'].'template/'.$mconfigs['templatedir'].'/';
			$btags['cms_counter'] = "<script type=\"text/javascript\" src=\"$mconfigs[cms_abs]tools/counter.php\"></script>";
			cache2file($btags,'btags');
			cache2file($$cname,$cname);
			break;
		case 'localfiles':
			$inits = mfetch_array('localfiles','lfid','*','','lfid');
			foreach($inits as $v){
				$localfiles[$v['ftype']][$v['extname']] = $v;
			}
			cache2file($$cname,$cname);
			break;
		case 'fields':
			$$cname = mfetch_array('fields','ename','*',"chid='$mode'",'vieworder,issystem DESC,mcommon DESC,fid ASC');
			cache2file($$cname,$cname.$mode,$cname);
			break;
		case 'mfields':
			$$cname = mfetch_array('mfields','ename','*',"mchid='$mode'",'vieworder,issystem DESC,mcommon DESC,mfid ASC');
			cache2file($$cname,$cname.$mode,$cname);
			break;
		case 'ffields':
			$$cname = mfetch_array('ffields','ename','*',"chid='$mode'",'vieworder,issystem DESC,fid ASC');
			cache2file($$cname,$cname.$mode,$cname);
			break;
		case 'mafields':
			$$cname = mfetch_array('mafields','ename','*',"matid='$mode'",'vieworder,issystem DESC,fid ASC');
			cache2file($$cname,$cname.$mode,$cname);
			break;
		case 'crprices':
			$$cname = mfetch_array('crprices','ename','*','','crid,crvalue');
			$vcps = array('tax' => array(),'sale' => array(),'award' => array(),'ftax' => array(),'fsale' => array(),);
			foreach($crprices as $k => $v){
				foreach(array('tax','sale','award','ftax','fsale') as $var){
					$v[$var] && $vcps[$var][$v['ename']] = $v['cname'];
				}
			}
			cache2file($vcps,'vcps');
			cache2file($$cname,$cname);
			break;
		case 'cafields':
			$$cname = mfetch_array('cnfields','ename','*','iscc=0','vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'ccfields':
			$$cname = mfetch_array('cnfields','ename','*','iscc=1','vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'pfields':
			$$cname = mfetch_array('cufields','ename','*',"cu=1",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'ofields':
			$$cname = mfetch_array('cufields','ename','*',"cu=2",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'rfields':
			$$cname = mfetch_array('cufields','ename','*',"cu=3",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'cfields':
			$$cname = mfetch_array('cufields','ename','*',"cu=4",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'bfields':
			$$cname = mfetch_array('cufields','ename','*',"cu=5",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'mffields':
			$$cname = mfetch_array('mcufields','ename','*',"cu=1",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'mlfields':
			$$cname = mfetch_array('mcufields','ename','*',"cu=2",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'mcfields':
			$$cname = mfetch_array('mcufields','ename','*',"cu=3",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'mrfields':
			$$cname = mfetch_array('mcufields','ename','*',"cu=4",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'mbfields':
			$$cname = mfetch_array('mcufields','ename','*',"cu=5",'vieworder,fid');
			cache2file($$cname,$cname);
			break;
		case 'vcatalogs':
			$$cname = mfetch_array('vcatalogs','caid','*','','vieworder,caid');
			cache2file($$cname,$cname);
			break;
		case 'badwords':
			$badwords = array();
			$query = $db->query("SELECT * FROM {$tblprefix}badwords ORDER BY bwid");
			while($badword = $db->fetch_array($query)){
				$badwords['wreplace'][] = $badword['wreplace'];
				$badword['wsearch'] = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($badword['wsearch'],'/'));
				$badwords['wsearch'][] = '/'.$badword['wsearch'].'/i';
			}
			cache2file($$cname,$cname);
			break;
		case 'wordlinks':
			$wordlinks = $uwordlinks = array();
			$query = $db->query("SELECT * FROM {$tblprefix}wordlinks WHERE available=1 ORDER BY pcs DESC,wlid ASC");
			while($row = $db->fetch_array($query)){
				$wordlinks['swords'][] = '/'.preg_quote($row['sword'],'/').'/i';
				$wordlinks['rwords'][] = '<a href="'.view_url($row['url']).'" target="_blank">'.$row['sword'].'</a>';
				$uwordlinks['swords'][] = $row['sword'];
				$uwordlinks['rwords'][] = view_url($row['url']);
			}
			cache2file($$cname,$cname);
			cache2file($uwordlinks,'uwordlinks');
			break;
		case 'domains':
			$domains = array('from' => array(),'to' => array(),);
			$query = $db->query("SELECT domain,folder,isreg FROM {$tblprefix}domains ORDER BY vieworder,id");
			while($row = $db->fetch_array($query)){
				$domains['from'][] = $row['isreg'] ? $row['folder'] : u_regcode($row['folder']);
				$domains['to'][] = $row['domain'];
			}
			cache2file($$cname,$cname);
			break;
		case 'faces':
			$faceicons = array('from' => array(),'to' => array(),);
			$jsstr = 'var FACEICONS = [';
			$query = $db->query("SELECT * FROM {$tblprefix}facetypes WHERE available=1 ORDER BY vieworder,ftid");
			while($row = $db->fetch_array($query)){
				$jsstr .= '[\''.$row['cname'].'\',[';
				$query1 = $db->query("SELECT * FROM {$tblprefix}faces WHERE ftid='$row[ftid]' AND available=1 ORDER BY vieworder,id");
				while($row1 = $db->fetch_array($query1)){
					$faceicons['from'][] = $row1['ename'];
					$faceicons['to'][] = 'images/face/'.$row['facedir'].'/'.$row1['url'];
					$jsstr .= '[\''.$row1['ename'].'\',\'images/face/'.$row['facedir'].'/'.$row1['url'].'\'],';
				}
				$jsstr .= ']],';
			}
			$jsstr .= '];';
			str2file($jsstr,M_ROOT.'./dynamic/cache/faceicons.js');
			cache2file($faceicons,'faceicons');
			break;
		case 'dbfields':
			$dbfields = array();
			$query = $db->query("SELECT * FROM {$tblprefix}dbfields ORDER BY dfid");
			while($row = $db->fetch_array($query)){
				$dbfields[$row['ddtable'].'_'.$row['ddfield']] = $row['ddcomment'];
			}
			cache2file($$cname,$cname);
			break;
		case 'usednames':
			$$cname = reload_cache($cname);
			$arr = array('fields' => 'archives,archives_sub','mfields' => 'members,members_sub','cafields' => 'catalogs','ccfields' => 'coclass','ffields' => 'farchives',
			'pfields' => 'orders','ofields' => 'offers','rfields' => 'replys','cfields' => 'comments','bfields' => 'reports',
			'mffields' => 'mfriends','mlfields' => 'mflinks','mcfields' => 'mcomments','mrfields' => 'mreplys','mbfields' => 'mreports',
			);
			$usednames[$mode] = mfetch_fields($arr[$mode]);
			cache2file($$cname,$cname);
			break;
		case 'menus':
			$mhcac = 'mnheaders'.($mode ? 's' : '');
			$mmcac = 'mnmenus'.($mode ? 's' : '');
			$mlcac = 'mnlangs'.($mode ? 's' : '');
			${$mhcac} = ${$mmcac} = ${$mlcac} = array();
			$query1 = $db->query("SELECT * FROM {$tblprefix}mtypes WHERE issub='$mode' ORDER BY vieworder,mtid");
			while($row1 = $db->fetch_array($query1)){
				${$mhcac}[$row1['mtid']] = $row1['url'];
				${$mlcac}['menutype_'.$row1['mtid']] = $row1['title'];
				${$mmcac}[$row1['mtid']] = array();
				$query2 = $db->query("SELECT * FROM {$tblprefix}menus WHERE mtid='$row1[mtid]' AND available=1 AND issub='$mode' AND isbk=0 ORDER BY vieworder,mnid");
				while($row2 = $db->fetch_array($query2)){
					${$mlcac}['menuitem_'.$row2['mnid']] = $row2['title'];
					${$mmcac}[$row1['mtid']][$row2['mnid']] = $row2['url'];
				}
				if(empty(${$mmcac}[$row1['mtid']])) unset(${$mhcac}[$row1['mtid']],${$mmcac}[$row1['mtid']]);
			}
			foreach(array($mhcac,$mmcac,$mlcac) as $var) cache2file(${$var},$var);
			break;
		case 'userurls':
			$utypes = mfetch_array('utypes','utid','*','','vieworder,utid');
			foreach($utypes as $k => $v){
				if(!$v['pid']) $utypes[$k]['url'] = !$v['ismc'] ? "?entry=userlinks&utid=$k" : "?action=userlinks&utid=$k";
			}
			$utypes = order_arr($utypes,0);
			cache2file($utypes,'utypes');
			$userurls = mfetch_array('userurls','uid','*','','vieworder,uid');
			cache2file($userurls,'userurls');
			break;
		case 'usualurls':
			$usualurls = mfetch_array('usualurls','uid','*','','vieworder,uid');
			cache2file($usualurls,'usualurls');
			break;
		case 'repugrades':
			$rows = mfetch_array('repugrades','rgid','*','','rgid');
			$repugrades = array();
			foreach($rows as $k => $v){
				if(isset($oldvalue) && $oldvalue >= $v['rgbase']) break;
				$oldvalue = $v['rgbase'];
				$repugrades[$k] = $v;
			}
			unset($rows);
			cache2file($repugrades,'repugrades');
			break;
		case 'mmenus':
			$mmnmenus = $mmnlangs = array();
			$query = $db->query("SELECT * FROM {$tblprefix}mmtypes ORDER BY vieworder,mtid");
			while($row0 = $db->fetch_array($query)){
				$mmnlangs['mmenutype_'.$row0['mtid']] = $row0['title'];
				$query1 = $db->query("SELECT * FROM {$tblprefix}mmenus WHERE mtid='$row0[mtid]' AND available=1 AND isbk='0' ORDER BY vieworder,mnid");
				while($row1 = $db->fetch_array($query1)){
					$mmnlangs['mmenuitem_'.$row1['mnid']] = $row1['title'];
					$mmnmenus[$row0['mtid']][$row1['mnid']] = array($row1['url'],$row1['pmid'],$row1['newwin'],$row1['onclick'],);
				}
			}
			cache2file($mmnmenus,'mmnmenus');
			cache2file($mmnlangs,'mmnlangs');
			break;
		case 'alangs':
			$alangs = mfetch_array('alangs','ename','*','','ename,lid');
			foreach($alangs as $k => $v) $alangs[$k] = $v['content'];
			cache2file($alangs,'alangs');
			break;
		case 'clangs':
			$clangs = mfetch_array('clangs','ename','*','','ename,lid');
			foreach($clangs as $k => $v) $clangs[$k] = $v['content'];
			cache2file($clangs,'clangs');
			break;
		case 'mlangs':
			$mlangs = mfetch_array('mlangs','ename','*','','ename,lid');
			foreach($mlangs as $k => $v) $mlangs[$k] = $v['content'];
			cache2file($mlangs,'mlangs');
			break;
		case 'amsgs':
			$amsgs = mfetch_array('amsgs','ename','*','','ename,lid');
			foreach($amsgs as $k => $v) $amsgs[$k] = $v['content'];
			cache2file($amsgs,'amsgs');
			break;
		case 'cmsgs':
			$cmsgs = mfetch_array('cmsgs','ename','*','','ename,lid');
			foreach($cmsgs as $k => $v) $cmsgs[$k] = $v['content'];
			cache2file($cmsgs,'cmsgs');
			break;
		case 'mmsgs':
			$mmsgs = mfetch_array('mmsgs','ename','*','','ename,lid');
			foreach($mmsgs as $k => $v) $mmsgs[$k] = $v['content'];
			cache2file($mmsgs,'mmsgs');
			break;
		case 'aurls':
			$$cname = mfetch_array('aurls','auid','*','available=1 AND isbk=0','vieworder,auid','setting');
			cache2file($$cname,$cname);
			break;
		case 'inurls':
			$$cname = mfetch_array('inurls','iuid','*','available=1 AND isbk=0','vieworder,iuid','setting');
			cache2file($$cname,$cname);
			break;
		case 'murls':
			$$cname = mfetch_array('murls','muid','*','available=1 AND isbk=0','vieworder,muid','setting');
			cache2file($$cname,$cname);
			break;
		case 'inmurls':
			$$cname = mfetch_array('inmurls','imuid','*','available=1 AND isbk=0','vieworder,imuid','setting');
			cache2file($$cname,$cname);
			break;
		case 'memcerts':
			$memcerts = array();
			$query = $db->query("SELECT * FROM {$tblprefix}memcerts ORDER BY level DESC");
			while($row = $db->fetch_array($query)){
				$row['email'] && !preg_match("/(^|,)$row[email](,|$)/", $row['fields']) && $row['fields'] = $row['fields'] ? "$row[email],$row[fields]" : $row['email'];
				$row['mobile'] && !preg_match("/(^|,)$row[mobile](,|$)/", $row['fields']) && $row['fields'] = $row['fields'] ? "$row[mobile],$row[fields]" : $row['mobile'];
				if(!$row['fields'])continue;
				$row['mchids'] = ',' . $row['mchids'] . ',';
				$memcerts[$row['mcid']] = $row;
			}
			cache2file($memcerts,$cname);
			break;
	}
}
function mfetch_array($tbl,$key,$fieldstr = '',$where = '',$orderby = '',$unserializes = '',$explodes = ''){
	global $db,$tblprefix;
	$rets = array();
	if(empty($tbl) || empty($key)) return $rets;
	!$fieldstr && $fieldstr = '*';
	$sqlstr = "SELECT $fieldstr FROM {$tblprefix}$tbl".(empty($where) ? '' : " WHERE $where").(empty($orderby) ? '' : " ORDER BY $orderby");
	$query = $db->query($sqlstr);
	while($row = $db->fetch_array($query)){
		if($unserializes && is_array($unarr = array_filter(explode(',',$unserializes)))){
			foreach($unarr as $v){
				if(empty($row[$v]) || !is_array($row[$v] = @unserialize($row[$v]))) $row[$v] = array();
			}
		}
		if($explodes && is_array($exarr = array_filter(explode(',',$explodes)))){
			foreach($exarr as $v){
				$row[$v] = $row[$v] == '' ? array() : explode(',',$row[$v]);
			}
		}
		$rets[$row[$key]] = $row;
	}
	return $rets;
}
function mfetch_one($tbl,$where,$fstr='*',$unserializes = '',$explodes = ''){
	global $db,$tblprefix;
	$rets = array();
	if(!$tbl || !$where || !$fstr) return $rets;
	if($rets = $db->fetch_one("SELECT $fstr FROM {$tblprefix}$tbl WHERE $where")){
		if($unserializes && is_array($arr = array_filter(explode(',',$unserializes)))){
			foreach($arr as $v){
				if(empty($rets[$v]) || !is_array($rets[$v] = @unserialize($rets[$v]))) $rets[$v] = array();
			}
		}
		if($explodes && is_array($arr = array_filter(explode(',',$explodes)))){
			foreach($arr as $v){
				$rets[$v] = $rets[$v] == '' ? array() : explode(',',$rets[$v]);
			}
		}
	}
	return $rets;
}

function mfetch_fields($tbls = ''){
	global $db,$tblprefix;
	$fields = array();
	if($tbls && is_array($tblarr = explode(',',$tbls))){
		foreach($tblarr as $table){
			$query = $db->query("SELECT * FROM {$tblprefix}$table");
			while($field = $db->fetch_fields($query)){
				$fields[] = $field->name;
			}
		}
		$fields = array_unique($fields);
	}
	return $fields;
}

?>
