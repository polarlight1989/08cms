<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
include_once M_ROOT."./include/subsetup.fun.php";
include_once M_ROOT."./include/fields.fun.php";
aheader();
backallow('subsite') || amessage('no_apermission');
$url_type = 'subsite';include 'urlsarr.inc.php';
url_nav(lang('subsitemanager'),$urlsarr,'setup');
sys_cache('fieldwords');
$subsetupdir = M_ROOT.'./dynamic/subsetup/';
if(!is_dir($subsetupdir) || !is_file($subsetupdir.'setupsids.cac.php') || !is_dir($subsetupdir.'cache/') || !is_dir($subsetupdir.'template/')) amessage('upinssubinidataupl','','dynamic/subsetup/');
//作个初始资料是否合法的分析????????????

$action = empty($action) ? 'subsites' : $action;
$setupsids = oread_cache('setupsids');//记录原始资料包中的子站id--$osid，及要安装成的子站id--$nsid
@extract($setupsids);

$stepeds = oread_cache('stepeds');//记录已经操作的步骤
$stepadds = oread_cache('stepadds');//记录每个步骤中添加的id记录,直接用表名来记录id
$idsmap = oread_cache('idsmap');//记录新旧id的对应关系

$stepsarr = array(//所有需要操作的步骤
'subsites' => lang('newsubset'),
'currencys' => lang('currencytype'),
'mchannels' => lang('mchannel'),
'grouptypes' => lang('grouptype'),
'usergroups' => lang('usergroup'),
'commus' => lang('commuitem'),//包含购买字段及送货方式等设置
'channels' => lang('achannel'),
'altypes' => lang('altype'),
'catalogs' => lang('catalog'),
'cotypes' => lang('cotypem'),
'coclasses' => lang('coclasssetting'),
'cnodes' => lang('catascnode'),//包含节点结构
'fchannels' => lang('affixchannel'),
'fcatalogs' => lang('affixcoclass'),
'freeinfos' => lang('isolutepage'),
'templates' => lang('template'),//包含标识，常规模板，功能模板
);

//显示页面顶部链接
$stepurls = array();
$i = 1;
foreach($stepsarr as $k => $v){
	$stepurls[] = "$i &nbsp;".($k == $action ? "<b>$v</b>" : "<a href=\"?entry=subsetup&action=$k\">$v</a>").(isset($stepeds[$k]) ? 'ok' : '');
	$i ++;
}
tabheader(lang('contsubsinst').'&nbsp; &nbsp; &nbsp; &nbsp; >><a href="?entry=subsetup&action=delupload">'.lang('delinupdatandrec').'</a>');
echo "<tr class=\"txt\"><td class=\"txtC\" colspan=\"2\">";
echo tab_list($stepurls,8);
echo "</td></tr>";
tabfooter();
unset($stepurls);

if($action == 'subsites'){
	$n_operated = empty($nsid) ? false : true;
	load_cache('subsites');
	$do_enable = $n_operated ? false : true;
	$undo_enable = !$n_operated || isset($stepeds['currencys']) ? false : true;
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		if(!$n_operated){
			$submitstr = '';
			tabheader(lang('addconsub'),'subsiteadd',"?entry=subsetup&action=subsites",2,1,1);
			trbasic(lang('subsitecname'),'subsitenew[sitename]','','text');
			trbasic(lang('subsstadir'),'subsitenew[dirname]','','text');
			trbasic(lang('substempldir'),'subsitenew[templatedir]','','text');
			$submitstr .= makesubmitstr('subsitenew[sitename]',1,0,0,80);
			$submitstr .= makesubmitstr('subsitenew[dirname]',1,'tagtype',0,15);
			$submitstr .= makesubmitstr('subsitenew[templatedir]',1,'tagtype',0,15);
			tabfooter('bdo',lang('nextstep'));
			check_submit_func($submitstr);
		}else{
			tabheader(lang('addconsub'),'subsiteadd',"?entry=subsetup&action=subsites");
			trbasic(lang('subsiteid'),'',$nsid,'');
			trbasic(lang('subsitecname'),'',$subsites[$nsid]['sitename'],'');
			trbasic(lang('subsstadir'),'',$subsites[$nsid]['dirname'],'');
			trbasic(lang('substempldir'),'',$subsites[$nsid]['templatedir'],'');
			tabfooter();
			echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
			echo "</form>\n";
		}

	}elseif(submitcheck('bdo')){
		if(!$do_enable) amessage('donrepoper');
		$subsitenew['sitename'] = trim(strip_tags($subsitenew['sitename']));
		$subsitenew['dirname'] = trim(strip_tags($subsitenew['dirname']));
		$subsitenew['templatedir'] = trim(strip_tags($subsitenew['templatedir']));
		if(!$subsitenew['sitename'] || !$subsitenew['dirname'] || !$subsitenew['templatedir']) amessage('subdatamiss');
		if(preg_match("/[^a-zA-Z_0-9]+/",$subsitenew['dirname'])) amessage('substadirill');
		if(preg_match("/[^a-zA-Z_0-9]+/",$subsitenew['templatedir'])) amessage('subtemdirill');
		$subsitenew['dirname'] = strtolower($subsitenew['dirname']);
		$subsitenew['templatedir'] = strtolower($subsitenew['templatedir']);
		if(!mmkdir(M_ROOT.$subsitenew['dirname'],0)) anmessage('nowcresubstadir');
		if(!mmkdir(M_ROOT.'template/'.$subsitenew['templatedir'],0)) amessage('nowcresubtemdir');

		$db->query("INSERT INTO {$tblprefix}subsites SET 
					sitename='$subsitenew[sitename]',
					dirname='$subsitenew[dirname]',
					templatedir='$subsitenew[templatedir]',
					ineedstatic='$timestamp'
					");
		if($nsid = $db->insert_id()){
			updatecache('subsites');
			$subsites = reload_cache('subsites');
			include_once M_ROOT."./include/cparse.fun.php";
			cn_blank('','i',$nsid);

			$setupsids['nsid'] = $nsid;
			ocache2file($setupsids,'setupsids');

			$stepeds['subsites'] = 1;
			ocache2file($stepeds,'stepeds');

			amessage('operatesuc','?entry=subsetup&action=currencys');
		}else amessage('subaddfai');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');

		clear_dir(M_ROOT.'template/'.$subsites[$nsid]['templatedir'],true);
		clear_dir(M_ROOT.$subsites[$nsid]['dirname'],true);
		$db->query("DELETE FROM {$tblprefix}subsites WHERE sid='$nsid'",'SILENT');
		updatecache('subsites');

		$setupsids['nsid'] = 0;
		ocache2file($setupsids,'setupsids');

		unset($stepeds['subsites']);
		ocache2file($stepeds,'stepeds');

		amessage('undo succeed','?entry=subsetup&action=subsites');
	}

}elseif($action == 'currencys'){
	load_cache('currencys');
	$ocurrencys = oread_cache('currencys','','','cache');
	$n_operated = isset($stepeds['currencys']);
	$do_enable = $n_operated || !isset($stepeds['subsites']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['mchannels']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('currtypetran').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'currencys','?entry=subsetup&action=currencys','3');
		trcategory(array(lang('sourceid'),lang('sourcecurrencycname'),lang('tranurrentsys')));
		foreach($ocurrencys as $k => $v){
			echo "<tr class=\"txt\"><td class=\"txtL\">$k</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"currencysnew[$k]\">".makeoption(array(0 => lang('add')) + cridsarr(),empty($idsmap['crids'][$k]) ? 0 : $idsmap['crids'][$k])."</select></td></tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		if(!$do_enable) amessage('donrepoper');
		if(!empty($currencysnew)){
			foreach($currencysnew as $k => $v){
				if(empty($v)){//新增id
					$sqlstr = '';
					foreach($ocurrencys[$k] as $key => $val){
						!in_array($key,array('crid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
					}
					$db->query("INSERT INTO {$tblprefix}currencys SET $sqlstr");
					if(!($crid = $db->insert_id())){
						amessage('subsetupcancel');//?????????创建积分不成功，需要将本步当前已进行的操作全部自动撤消
					}
					$db->query("ALTER TABLE {$tblprefix}members ADD currency$crid int(10) NOT NULL default 0", 'SILENT');
					$stepadds['currencys'][] = $crid;//将新加入的crid记录在案
					$idsmap['crids'][$k] = $crid;//将新旧id的对应关系记录在案
				}else{//将id指定为对应现有id
					$idsmap['crids'][$k] = $v;
				}
			}
			
			//将子站中的价格方案转入进来
			load_cache('crprices');
			$ocrprices = oread_cache('crprices','','','cache');
			foreach($ocrprices as $k => $v){//记录积分方案的id对应关系
				if(!isset($crprices[$idsmap['crids'][$v['crid']].'_'.$v['crvalue']])){//如果子站的价格方案不存在，则需要加入
					$sqlstr = '';
					$sqlstr .= (!$sqlstr ? '' : ',')."crid='".$idsmap['crids'][$v['crid']]."'";
					$sqlstr .= (!$sqlstr ? '' : ',')."ename='".($idsmap['crids'][$v['crid']].'_'.$v['crvalue'])."'";
					foreach($v as $key => $val){
						!in_array($key,array('cpid','crid','ename')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
					}
					$db->query("INSERT INTO {$tblprefix}crprices SET $sqlstr");
					if(!($cpid = $db->insert_id())){
						amessage('subsetupcancel');//?????????创建不成功，需要将本步当前已进行的操作全部自动撤消
					}
					$stepadds['crprices'][] = $cpid;
				}
				$idsmap['crprices'][$k] = $idsmap['crids'][$v['crid']].'_'.$v['crvalue'];
			}
			
			//将子站中的积分兑换方案转入进来
			load_cache('crprojects');
			$ocrprojects = oread_cache('crprojects','','','cache');
			foreach($ocrprojects as $k => $v){
				$nename = $idsmap['crids'][$v['scrid']].'_'.$idsmap['crids'][$v['ecrid']];
				$nexist = 0;
				foreach($crprojects as $nk => $nv){
					if($nename == $nv['ename']){
						$idsmap['crprojects'][$k] = $nk;
						$nexist = 1;
						break;
					}
				}
				if(!$nexist){//需要新增积分兑换方案
					$sqlstr = '';
					$sqlstr .= (!$sqlstr ? '' : ',')."scrid='".$idsmap['crids'][$v['scrid']]."'";
					$sqlstr .= (!$sqlstr ? '' : ',')."ecrid='".$idsmap['crids'][$v['ecrid']]."'";
					$sqlstr .= (!$sqlstr ? '' : ',')."ename='".($idsmap['crids'][$v['scrid']].'_'.$idsmap['crids'][$v['ecrid']])."'";
					foreach($v as $key => $val){
						!in_array($key,array('crpid','ename','scrid','ecrid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
					}
					$db->query("INSERT INTO {$tblprefix}crprojects SET $sqlstr");
					if(!($crpid = $db->insert_id())){
						amessage('subsetupcancel');//?????????创建不成功，需要将本步当前已进行的操作全部自动撤消
					}
					$stepadds['crprojects'][] = $crpid;
					$idsmap['crprojects'][$k] = $crpid;
				}
			
			}
			//更新缓存
			updatecache('currencys');
			updatecache('crprices');
			updatecache('crprojects');
		}
		$stepeds['currencys'] = 1;
		ocache2file($stepeds,'stepeds');
		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		amessage('operatesuc','?entry=subsetup&action=mchannels');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['currencys'])){
			$db->query("DELETE FROM {$tblprefix}currencys WHERE crid ".multi_str($stepadds['currencys']),'SILENT');
			foreach($stepadds['currencys'] as $crid){
				$db->query("ALTER TABLE {$tblprefix}members DROP currency$crid",'SILENT');
				$db->query("DELETE FROM {$tblprefix}crprices WHERE crid='$crid'",'SILENT');
				$db->query("DELETE FROM {$tblprefix}crprojects WHERE scrid='$crid' OR  ecrid='$crid'",'SILENT');
			}
		}

		if(!empty($stepadds['crprices'])){
			$db->query("DELETE FROM {$tblprefix}crprices WHERE cpid ".multi_str($stepadds['crprices']),'SILENT');
		}
		if(!empty($stepadds['crprojects'])){
			$db->query("DELETE FROM {$tblprefix}crprojects WHERE crpid ".multi_str($stepadds['crprojects']),'SILENT');
		}
		updatecache('currencys');
		updatecache('crprices');
		updatecache('crprojects');

		unset($stepeds['currencys'],$idsmap['crids'],$idsmap['crprices'],$idsmap['crprojects'],$stepadds['currencys'],$stepadds['crprices'],$stepadds['crprojects']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=currencys');
	}
}elseif($action == 'mchannels'){
//需要处理会员通用字段
//会员模型
//模型定义字段，就不要处理了.
//会员模型变更方案
	load_cache('mchannels');
	$omchannels = oread_cache('mchannels','','','cache');
	$n_operated = isset($stepeds['mchannels']);
	$do_enable = $n_operated || !isset($stepeds['currencys']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['grouptypes']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('memchantransto').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'mchannels','?entry=subsetup&action=mchannels','3');
		trcategory(array(lang('soumemchaid'),lang('soumemchname'),lang('tranurrentsys')));
		foreach($omchannels as $k => $v){
			echo "<tr class=\"txt\"><td class=\"txtL\">$k</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')) + mchidsarr(),empty($idsmap['mchids'][$k]) ? 0 : $idsmap['mchids'][$k])."</select></td></tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		if(!$do_enable) amessage('invoperate');
		if(!empty($transtonew)){

			//处理会员模型
			load_cache('initmfields');
			foreach($omchannels as $k => $v){
				if(empty($transtonew[$k])){//新建会员模型
					$sqlstr = '';
					foreach($v as $key => $val){
						!in_array($key,array('mchid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
					}
					$db->query("INSERT INTO {$tblprefix}mchannels SET $sqlstr");
					if($mchid = $db->insert_id()){
						$db->query("CREATE TABLE {$tblprefix}members_$mchid (mid mediumint(8) unsigned NOT NULL default '0',PRIMARY KEY (mid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM"));
						//将系统的已有通用字段转入进来
						foreach($initmfields as $field){
							$sqlstr = "mchid='$mchid',available='1'";
							foreach($field as $key => $val){
								!in_array($key,array('mfid','mchid','available')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
							}
							$db->query("INSERT INTO {$tblprefix}mfields SET $sqlstr");
							if(!($mfid = $db->insert_id())) continue;
							$stepadds['mfields'][] = $mfid;
						}

						//将模型专用的字段转入进来
						$fields = oread_cache('mfields',$k,'','cache');
						foreach($fields as $k1 => $v1){
							if($v1['tbl'] == 'custom'){//只转入模型定义的字段
								$fieldnew = oread_cache('mfield',$k,$k1,'cache');
								$fieldnew = maddslashes($fieldnew);
								$fconfigarr = array(
									'errorurl' => '',
									'enamearr' => $usednames['mfields'],
									'altertable' => $tblprefix.'members_'.$mchid,
									'fieldtable' => $tblprefix.'mfields',
									'sqlstr' => "mchid=$mchid,iscustom='1',available='1',tbl='custom'",
									'filterstr' => "/[^a-zA-Z_0-9]+|^[0-9_]+|^grouptype(.*?)|^currency(.*?)/",
								);
								list($fmode,$fnew,$fsave) = array('m',true,true);
								include M_ROOT."./include/fields/$v1[datatype].php";
								if(!($mfid = $db->insert_id())) continue;//通用字段记录
								$stepadds['mfields'][] = $mfid;
							}
						}

						$stepadds['mchannels'][] = $mchid;//将新增的id记录在案
						$idsmap['mchids'][$k] = $mchid;//将新旧id的对应关系记录在案
					}
					updatecache('mfields',$mchid);

				}else{//指定对应会员模型
					$idsmap['mchids'][$k] = $transtonew[$k];//将新旧id的对应关系记录在案
				}
				
			}
			updatecache('mchannels');
			$mchannels = reload_cache('mchannels');


			//将子站中的通用会员字段转入进来
			$oinitmfields = oread_cache('initmfields','','','cache');
			$enamearr = $usednames['mfields'];
			foreach($mchannels as $k => $v){
				$fields = read_cache('mfields',$k);
				$enamearr = array_unique(array_merge($enamearr,array_keys($fields)));
			}
			$fconfigarr = array(
				'errorurl' => '',
				'enamearr' => $enamearr,
				'altertable' => $tblprefix.'members_sub',
				'fieldtable' => $tblprefix.'mfields',
				'sqlstr' => "iscustom='1',available='1',tbl='sub'",
				'filterstr' => "/[^a-zA-Z_0-9]+|^[0-9_]+|^grouptype(.*?)|^currency(.*?)/",
			);
			$mchids = array_keys($mchannels);
			foreach($oinitmfields as $k => $v){
				if(!in_array($v['ename'],$enamearr)){
					$fieldnew = &$v;
					$fieldnew = maddslashes($fieldnew);
					list($fmode,$fnew,$fsave) = array('im',true,true);
					include M_ROOT."./include/fields/$v[datatype].php";
					if(!($mfid = $db->insert_id())) continue;//通用字段记录
					$stepadds['mfields'][] = $mfid;
					$stepadds['initmfields'][] = $v['ename'];//记录了增加的通用字段的id
					foreach($mchids as $mchid){//将通用字段加入到每个会员模型中
						$sqlstr = "issystem='0',iscustom='1',mcommon='1',mchid='$mchid',tbl='sub'";
						foreach($v as $key => $val){
							!in_array($key,array('fid','issystem','iscustom','mcommon','mchid','tbl')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='$val'";
						}
						$db->query("INSERT INTO {$tblprefix}mfields SET $sqlstr");
						if(!($mfid = $db->insert_id())) continue;
						$stepadds['mfields'][] = $mfid;
						updatecache('mfields',$mchid);
					}
				}
			}
			updatecache('initmfields');
			updatecache('usednames','mfields');
			
			//将模型变更方案转入进来
			$omprojects = oread_cache('mprojects','','','cache');
			foreach($omprojects as $k => $v){
				$v['smchid'] = str2newid($v['smchid'],'chids');
				$v['tmchid'] = str2newid($v['tmchid'],'chids');
				$v['ename'] = $v['smchid'].'_'.$v['tmchid'];
				$sqlstr = '';
				foreach($v as $key => $val){
					!in_array($key,array('mpid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='$val'";
				}
				$db->query("INSERT INTO {$tblprefix}mprojects SET $sqlstr");
				if(!($mpid = $db->insert_id())) continue;
				$stepadds['mprojects'][] = $mpid;
			}
			updatecache('mprojects');

			ocache2file($stepadds,'stepadds');
			ocache2file($idsmap,'idsmap');
		}
		$stepeds['mchannels'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=grouptypes');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['mchannels'])){//清除所增会员模型的所有资料及缓存
			foreach($stepadds['mchannels'] as $mchid){
				$db->query("DROP TABLE IF EXISTS {$tblprefix}members_$mchid",'SILENT');
				$db->query("DELETE FROM {$tblprefix}mchannels WHERE mchid='$mchid'",'SILENT');
				$db->query("DELETE FROM {$tblprefix}mfields WHERE mchid='$mchid'",'SILENT');
				//清除相关缓存
				del_cache('mfields',$mchid);
			}
		}
		if(!empty($stepadds['initmfields'])){//删除通用字段在主表中增加的字段
			foreach($stepadds['initmfields'] as $ename){
				$db->query("ALTER TABLE {$tblprefix}members_sub DROP $ename",'SILENT');//会员通用字段是放在sub表中的
			}
		}
		updatecache('initmfields');

		if(!empty($stepadds['mfields'])){//删除在mfields表中增加的记录
			$db->query("DELETE FROM {$tblprefix}mfields WHERE mfid ".multi_str($stepadds['mfields']),'SILENT');
		}

		if(!empty($stepadds['mprojects'])){//删除模型变更方案
			$db->query("DELETE FROM {$tblprefix}mprojects WHERE mpid ".multi_str($stepadds['mprojects']),'SILENT');
		}
		updatecache('mprojects');

		updatecache('mchannels');
		$mchannels = reload_cache('mchannels');
		foreach($mchannels as $k => $v){
			updatecache('mfields',$k);
		}

		unset($stepeds['mchannels'],$idsmap['mchids'],$stepadds['mchannels'],$stepadds['initmfields'],$stepadds['mfields'],$stepadds['mprojects']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=mchannels');
	}

}elseif($action == 'grouptypes'){
	load_cache('grouptypes');
	$ogrouptypes = oread_cache('grouptypes','','','cache');
	$n_operated = isset($stepeds['grouptypes']);
	$do_enable = $n_operated || !isset($stepeds['mchannels']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['usergroups']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		$gtidsarr = array();
		foreach($grouptypes as $k => $v){
			if(!$v['issystem']) $gtidsarr[$k] = $v['cname'];
		}
		tabheader(lang('grouptypetransto').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'grouptypes','?entry=subsetup&action=grouptypes','3');
		trcategory(array(lang('sogrouptypeid'),lang('sogroupname'),lang('tranurrentsys')));
		foreach($ogrouptypes as $k => $v){
			if(!$v['issystem']){
				echo "<tr class=\"txt\"><td class=\"txtL\">$k</td>\n".
					"<td class=\"txtL\">$v[cname]</td>\n".
					"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')) + $gtidsarr,empty($idsmap['gtids'][$k]) ? 0 : $idsmap['gtids'][$k])."</select></td></tr>\n";
			}
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		foreach($ogrouptypes as $k => $v){
			if(!$v['issystem']){
				if(empty($transtonew[$k])){
					$sqlstr = "crid='".str2newid($v['crid'],'crids')."',mchids='".str2newid($v['mchids'],'mchids')."'";
					foreach($v as $key => $val){
						!in_array($key,array('gtid','crid','mchids')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
					}
					$db->query("INSERT INTO {$tblprefix}grouptypes SET $sqlstr");
					if($gtid = $db->insert_id()){
						$addfieldid = 'grouptype'.$gtid;
						$addfielddate = 'grouptype'.$gtid.'date';
						$db->query("ALTER TABLE {$tblprefix}members ADD $addfieldid smallint(6) unsigned NOT NULL default 0", 'SILENT');
						$db->query("ALTER TABLE {$tblprefix}members ADD $addfielddate int(10) unsigned NOT NULL default 0", 'SILENT');
						$stepadds['grouptypes'][] = $gtid;//将新增的id记录在案
						$idsmap['gtids'][$k] = $gtid;//将新旧id的对应关系记录在案
					}
				}else{
					$idsmap['gtids'][$k] = $transtonew[$k];//将新旧id的对应关系记录在案
				}
			}
		}
		updatecache('grouptypes');
		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		$stepeds['grouptypes'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=usergroups');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['grouptypes'])){
			$db->query("DELETE FROM {$tblprefix}grouptypes WHERE gtid ".multi_str($stepadds['grouptypes']),'SILENT');
			foreach($stepadds['grouptypes'] as $gtid){
				$addfieldid = 'grouptype'.$gtid;
				$addfielddate = 'grouptype'.$gtid.'date';
				$db->query("ALTER TABLE {$tblprefix}members DROP $addfieldid", 'SILENT');
				$db->query("ALTER TABLE {$tblprefix}members DROP $addfielddate", 'SILENT');
				del_cache('usergroups',$gtid);
			}
		}
		updatecache('grouptypes');

		unset($stepeds['grouptypes'],$idsmap['gtids'],$stepadds['grouptypes']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=grouptypes');
	}
}elseif($action == 'usergroups'){
	load_cache('grouptypes');
	$ogrouptypes = oread_cache('grouptypes','','','cache');
	$n_operated = isset($stepeds['usergroups']);
	$do_enable = $n_operated || !isset($stepeds['grouptypes']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['commus']) ? false : true;//如果下一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('usertransto').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'usergroups','?entry=subsetup&action=usergroups','4');
		trcategory(array(lang('sourcegrouptype'),lang('souusergrid'),lang('souusename'),lang('tranurrentsys')));
		foreach($ogrouptypes as $k => $v){
			if(!$v['issystem']){
				$ousergroups = oread_cache('usergroups',$k,'','cache');
				foreach($ousergroups as $k1 => $v1){
					echo "<tr class=\"txt\"><td class=\"txtL\">$v[cname]</td>\n<td class=\"item2\">$k1</td>\n".
					"<td class=\"txtL\">$v1[cname]</td>\n".
					"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k1]\">".makeoption(array(0 => lang('add')) + ugidsarr($idsmap['gtids'][$k]),empty($idsmap['ugids'][$k1]) ? 0 : $idsmap['ugids'][$k1])."</select></td></tr>\n";
				}
			}
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		foreach($ogrouptypes as $k => $v){
			if(!$v['issystem']){
				$ousergroups = oread_cache('usergroups',$k,'','cache');
				foreach($ousergroups as $k1 => $v1){
					if(empty($transtonew[$k1])){
						$v1 = oread_cache('usergroup',$k,$k1,'cache');
						$sqlstr = "gtid='".$idsmap['gtids'][$k]."',mchids='".str2newid($v1['mchids'],'mchids')."'";
						foreach($v1 as $key => $val){
							!in_array($key,array('ugid','gtid','mchids')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
						}
						$db->query("INSERT INTO {$tblprefix}usergroups SET $sqlstr");
						if($ugid = $db->insert_id()){
							$stepadds['usergroups'][] = $ugid;//将新增的id记录在案
							$idsmap['ugids'][$k1] = $ugid;//将新旧id的对应关系记录在案
						}
					}else{
						$idsmap['ugids'][$k1] = $transtonew[$k1];//将新旧id的对应关系记录在案
					}
				}
				updatecache('usergroups',$idsmap['gtids'][$k]);
			}
		}

		//将会员组变更方案转入进来
		$ouprojects = oread_cache('uprojects','','','cache');
		foreach($ouprojects as $k => $v){
			$v['gtid'] = str2newid($v['gtid'],'gtids');
			$v['sugid'] = str2newid($v['sugid'],'ugids');
			$v['tugid'] = str2newid($v['tugid'],'ugids');
			$v['ename'] = $v['sugid'].'_'.$v['tugid'];
			$sqlstr = '';
			foreach($v as $key => $val){
				!in_array($key,array('upid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='$val'";
			}
			$db->query("INSERT INTO {$tblprefix}uprojects SET $sqlstr");
			if(!($upid = $db->insert_id())) continue;
			$stepadds['uprojects'][] = $upid;
		}
		updatecache('uprojects');


		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		$stepeds['usergroups'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=commus');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['usergroups'])){
			$db->query("DELETE FROM {$tblprefix}usergroups WHERE ugid ".multi_str($stepadds['usergroups']),'SILENT');
			foreach($grouptypes as $k => $v){//删除缓存
				updatecache('usergroups',$k);
			}
		}
		if(!empty($stepadds['uprojects'])){//删除会员组变更方案
			$db->query("DELETE FROM {$tblprefix}uprojects WHERE upid ".multi_str($stepadds['uprojects']),'SILENT');
		}
		updatecache('uprojects');

		unset($stepeds['usergroups'],$idsmap['ugids'],$stepadds['usergroups'],$stepadds['uprojects']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=usergroups');
	}
}elseif($action == 'commus'){
	load_cache('commus');
	$ocommus = oread_cache('commus','','','cache');
	load_cache('shipings');
	$oshipings = oread_cache('shipings','','','cache');
	$n_operated = isset($stepeds['commus']);
	$do_enable = $n_operated || !isset($stepeds['usergroups']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['channels']) ? false : true;//如果下一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('commuitemtrans').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'commus','?entry=subsetup&action=commus','3');
		trcategory(array(lang('soucomitemid'),lang('soucomitemname'),lang('tranurrentsys')));
		$cuidsarr = array();
		foreach($commus as $k => $v){
			if($v['ch']) $cuidsarr[$k] = $v['cname'];
		}
		foreach($ocommus as $k => $v){
			if($v['ch']){
				echo "<tr class=\"txt\"><td class=\"txtL\">$k</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')) + $cuidsarr,empty($idsmap['cuids'][$k]) ? 0 : $idsmap['cuids'][$k])."</select></td></tr>\n";
			}
		}
		tabfooter();

		tabheader(lang('shipingtransto'),'','',3);
		trcategory(array(lang('sourshipid'),lang('soushipname'),lang('tranurrentsys')));
		$shidsarr = array();
		foreach($shipings as $k => $v){
			$shidsarr[$k] = $v['cname'];
		}
		foreach($oshipings as $k => $v){
			echo "<tr class=\"txt\"><td class=\"txtL\">$k</td>\n".
			"<td class=\"txtL\">$v[cname]</td>\n".
			"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew1[$k]\">".makeoption(array(0 => lang('add')) + $shidsarr,empty($idsmap['shids'][$k]) ? 0 : $idsmap['shids'][$k])."</select></td></tr>\n";
		}
		tabfooter();

		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		$s_commus = array();
		foreach($ocommus as $k => $v){
			$v = oread_cache('commu',$k,'','cache');
			if($v['ch']){
				if(empty($transtonew[$k])){
					foreach($v['setting'] as $k1 => $v1){//先处理一下设置中一些要转换的id
						if($k1 == 'crid') $v['setting'][$k1] = str2newid($v['setting'][$k1],'crids');
						if($k1 == 'ugids') $v['setting'][$k1] = str2newid($v['setting'][$k1],'ugids');
					}
					$v['setting'] = empty($v['setting']) ? '' : serialize($v['setting']);
					$sqlstr = "issystem='0'";
					foreach($v as $key => $val){
						!in_array($key,array('cuid','issystem')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
					}
					$db->query("INSERT INTO {$tblprefix}commus SET $sqlstr");
					if($cuid = $db->insert_id()){
						$stepadds['commus'][] = $cuid;//将新增的id记录在案
						$idsmap['cuids'][$k] = $cuid;//将新旧id的对应关系记录在案
					}
				}else{
					$idsmap['cuids'][$k] = $transtonew[$k];//将新旧id的对应关系记录在案
				}
			}
			$s_commus[str2newid($k,'cuids')]['available'] = $v['available'];
			$s_commus[str2newid($k,'cuids')]['cutpl'] = $v['cutpl'];
			$s_commus[str2newid($k,'cuids')]['addtpl'] = $v['addtpl'];
		}
		updatecache('commus');

		$s_commus = empty($s_commus) ? '' : addslashes(serialize($s_commus));
		$db->query("UPDATE {$tblprefix}subsites SET commus='$s_commus' WHERE sid='$nsid'");
		updatecache('subsites');


		//转入送货方式
		foreach($oshipings as $k => $v){
			if(empty($transtonew1[$k])){
				$sqlstr = '';
				foreach($v as $key => $val){
					!in_array($key,array('shid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
				}
				$db->query("INSERT INTO {$tblprefix}shipings SET $sqlstr");
				if($shid = $db->insert_id()){
					$stepadds['shipings'][] = $shid;//将新增的id记录在案
					$idsmap['shids'][$k] = $shid;//将新旧id的对应关系记录在案
				}
			}else{
				$idsmap['shids'][$k] = $transtonew1[$k];//将新旧id的对应关系记录在案
			}
		}
		updatecache('shipings');

		//将子站中的购买信息字段转入进来
		$opfields = oread_cache('pfields','','','cache');
		$enamearr = empty($usednames['pfields']) ? array() : $usednames['pfields'];
		$fconfigarr = array(
			'errorurl' => '',
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'orders',
			'fieldtable' => $tblprefix.'cufields',
			'sqlstr' => "cu='1'",
		);
		foreach($opfields as $k => $v){
			if(!in_array($v['ename'],$enamearr)){
				$fieldnew = &$v;
				$fieldnew = maddslashes($fieldnew);
				list($fmode,$fnew,$fsave) = array('cu',true,true);
				include M_ROOT."./include/fields/$v[datatype].php";
				if(!($fid = $db->insert_id())) continue;//字段记录
				$stepadds['pfields'][] = $v['ename'];//以英文id来记录增加的字段
			}
		}
		updatecache('pfields');
		updatecache('usednames','pfields');

		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		$stepeds['commus'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=channels');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['commus'])){
			$db->query("DELETE FROM {$tblprefix}commus WHERE cuid ".multi_str($stepadds['commus']),'SILENT');
		}
		updatecache('commus');

		if(!empty($stepadds['shipings'])){
			$db->query("DELETE FROM {$tblprefix}shipings WHERE shid ".multi_str($stepadds['shipings']),'SILENT');
		}
		updatecache('shipings');

		if(!empty($stepadds['pfields'])){
			foreach($stepadds['pfields'] as $ename){
				$db->query("ALTER TABLE {$tblprefix}orders DROP $ename",'SILENT'); 
				$db->query("DELETE FROM {$tblprefix}cufields WHERE ename='$ename' AND cu='1'",'SILENT'); 
			
			}
		}
		updatecache('pfields');
		updatecache('usednames','pfields');

		$db->query("UPDATE {$tblprefix}subsites SET commus='' WHERE sid='$nsid'");
		updatecache('subsites');

		unset($stepeds['commus'],$idsmap['cuids'],$idsmap['shids'],$stepadds['commus'],$stepadds['shipings'],$stepadds['pfields']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=commus');
	}
}elseif($action == 'channels'){
	include_once M_ROOT."./include/commu.fun.php";
	load_cache('channels');
	$ochannels = oread_cache('channels','','','cache');
	$n_operated = isset($stepeds['channels']);
	$do_enable = $n_operated || !isset($stepeds['commus']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['altypes']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('arcchatrans').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'channels','?entry=subsetup&action=channels','3');
		trcategory(array(lang('souarcchaid'),lang('souarcchaname'),lang('tranurrentsys')));
		foreach($ochannels as $k => $v){
			echo "<tr align=\"center\"><td class=\"item1\">$k</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')) + chidsarr(1),empty($idsmap['chids'][$k]) ? 0 : $idsmap['chids'][$k])."</select></td></tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		if(!$do_enable) amessage('invoperate');
		if(!empty($transtonew)){

			//处理文档模型
			load_cache('initfields');
			$s_channels = array();//新建子站的模型有关的设置
			foreach($ochannels as $k => $v){
				$v = oread_cache('channel',$k,'','cache');
				if(empty($transtonew[$k])){//新建会员模型
					$v['ugids'] = empty($v['ugids']) ? '' : implode(',',$v['ugids']);
					$v['ugids'] = str2newid($v['ugids'],'ugids');
					$v['cuid'] = str2newid($v['cuid'],'cuids');
					$sqlstr = '';
					foreach($v as $key => $val){
						!in_array($key,array('chid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
					}
					$db->query("INSERT INTO {$tblprefix}channels SET $sqlstr");
					if($chid = $db->insert_id()){
						$db->query("CREATE TABLE {$tblprefix}archives_$chid (aid mediumint(8) unsigned NOT NULL default '0',PRIMARY KEY (aid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM"));
						//将系统的已有通用字段转入进来
						foreach($initfields as $field){
							$sqlstr = "chid='$chid',available='1'";
							foreach($field as $key => $val){
								!in_array($key,array('fid','chid','available')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
							}
							$db->query("INSERT INTO {$tblprefix}fields SET $sqlstr");
							if(!($fid = $db->insert_id())) continue;
							$stepadds['fields'][] = $fid;
						}
						cu_addfields($chid,$v['cuid']);

						//将模型专用的字段转入进来
						$fields = oread_cache('fields',$k,'','cache');
						foreach($fields as $k1 => $v1){
							if($v1['tbl'] == 'custom'){//只转入模型定义的字段
								$fieldnew = oread_cache('field',$k,$k1,'cache');
								$fieldnew = maddslashes($fieldnew);
								$fconfigarr = array(
								'errorurl' => '',
								'enamearr' => $usednames['fields'],
								'altertable' => $tblprefix.'archives_'.$chid,
								'fieldtable' => $tblprefix.'fields',
								'sqlstr' => "chid=$chid,iscustom='1',available='1',tbl='custom'",
								'filterstr' => "/[^a-zA-Z_0-9]+|^[0-9_]+|^ccid(.*?)/",
								);
								list($fmode,$fnew,$fsave) = array('a',true,true);
								include M_ROOT."./include/fields/$v1[datatype].php";
								if(!($fid = $db->insert_id())) continue;
								$stepadds['fields'][] = $fid;//记录增加字段
							}
						}
						$stepadds['channels'][] = $chid;//将新增的id记录在案
						$idsmap['chids'][$k] = $chid;//将新旧id的对应关系记录在案
					}
					updatecache('fields',$chid);

				}else{//指定对应模型
					$idsmap['chids'][$k] = $transtonew[$k];//将新旧id的对应关系记录在案
				}
				
				//将新建子站的模型有关的设置加入进来
				$s_channels[str2newid($k,'chids')]['available'] = $v['available'];
				$s_channels[str2newid($k,'chids')]['arctpl'] = $v['arctpl'];
				$s_channels[str2newid($k,'chids')]['pretpl'] = $v['pretpl'];
				$s_channels[str2newid($k,'chids')]['srhtpl'] = $v['srhtpl'];
			}

			$s_channels = empty($s_channels) ? '' : addslashes(serialize($s_channels));
			$db->query("UPDATE {$tblprefix}subsites SET channels='$s_channels' WHERE sid='$nsid'");
			updatecache('subsites');

			updatecache('channels');
			$channels = reload_cache('channels');

			//将子站中的通用字段转入进来
			$oinitfields = oread_cache('initfields','','','cache');
			$enamearr = $usednames['fields'];
			foreach($channels as $k => $v){
				$fields = read_cache('fields',$k);
				$enamearr = array_unique(array_merge($enamearr,array_keys($fields)));
			}

			$fconfigarr = array(
				'errorurl' => '',
				'enamearr' => $enamearr,
				'altertable' => $tblprefix.'archives',
				'fieldtable' => $tblprefix.'fields',
				'sqlstr' => "iscustom='1',mcommon='1',available='1',tbl='main'",
				'filterstr' => "/[^a-zA-Z_0-9]+|^[0-9_]+|^ccid(.*?)|^album(.*?)/",
			);
			$chids = array_keys($channels);
			foreach($oinitfields as $k => $v){
				if(!in_array($v['ename'],$enamearr)){
					$fieldnew = &$v;
					$fieldnew = maddslashes($fieldnew);
					list($fmode,$fnew,$fsave) = array('i',true,true);
					include M_ROOT."./include/fields/$v[datatype].php";
					if(!($fid = $db->insert_id())) continue;//通用字段记录
					$stepadds['fields'][] = $fid;
					$stepadds['initfields'][] = $v['ename'];//记录了增加的通用字段的id
					foreach($chids as $chid){//将通用字段加入到每个会员模型中
						$sqlstr = "issystem='0',iscustom='1',mcommon='1',chid='$chid',tbl='main'";
						foreach($v as $key => $val){
							!in_array($key,array('fid','issystem','iscustom','mcommon','chid','tbl')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='$val'";
						}
						$db->query("INSERT INTO {$tblprefix}fields SET $sqlstr");
						if(!($fid = $db->insert_id())) continue;
						$stepadds['fields'][] = $fid;
						updatecache('fields',$chid);
					}
				}
			}
			updatecache('initfields');
			updatecache('usednames','fields');
			ocache2file($stepadds,'stepadds');
			ocache2file($idsmap,'idsmap');
		}
		$stepeds['channels'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=altypes');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['channels'])){//清除所增文档模型的所有资料及缓存
			foreach($stepadds['channels'] as $chid){
				$db->query("DROP TABLE IF EXISTS {$tblprefix}archives_$chid",'SILENT');
				$db->query("DELETE FROM {$tblprefix}channels WHERE chid='$chid'",'SILENT');
				$db->query("DELETE FROM {$tblprefix}fields WHERE chid='$chid'",'SILENT');
				//清除相关缓存
				del_cache('fields',$chid);
			}
		}
		if(!empty($stepadds['initfields'])){//删除通用字段在主表中增加的字段
			foreach($stepadds['initfields'] as $ename){
				$db->query("ALTER TABLE {$tblprefix}archives DROP $ename",'SILENT');
				//还要删除每个模型中该通用字段的缓存
			}
		}

		if(!empty($stepadds['fields'])){//删除在mfields表中增加的记录
			$db->query("DELETE FROM {$tblprefix}fields WHERE fid ".multi_str($stepadds['fields']),'SILENT');
		}

		updatecache('initfields');
		updatecache('channels');
		$channels = reload_cache('channels');
		foreach($channels as $k => $v){
			updatecache('fields',$k);
		}

		$db->query("UPDATE {$tblprefix}subsites SET channels='' WHERE sid='$nsid'");
		updatecache('subsites');

		unset($stepeds['channels'],$idsmap['chids'],$stepadds['channels'],$stepadds['initfields'],$stepadds['fields']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=channels');
	}
}elseif($action == 'altypes'){
	load_cache('altypes',$nsid);
	$oaltypes = oread_cache('altypes','','','cache');
	$n_operated = isset($stepeds['altypes']);
	$do_enable = $n_operated || !isset($stepeds['channels']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['catalogs']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('altypetransto').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'altypes','?entry=subsetup&action=altypes','3');
		trcategory(array(lang('sourcealtypeid'),lang('souraltyname'),lang('tranurrentsys')));
		foreach($oaltypes as $k => $v){
			echo "<tr class=\"txt\"><td class=\"item1\">$k</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')) + atidsarr(),empty($idsmap['atids'][$k]) ? 0 : $idsmap['atids'][$k])."</select></td></tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		foreach($oaltypes as $k => $v){
			$v = oread_cache('altype',$k,'','cache');
			$v['chid'] = str2newid($v['chid'],'chids');
			$v['chids'] = empty($v['chids']) ? '' : implode(',',$v['chids']);
			$v['chids'] = str2newid($v['chids'],'chids');
			$v['atids'] = empty($v['atids']) ? '' : implode(',',$v['atids']);//atids还需要转换
			$v['ugids'] = empty($v['ugids']) ? '' : implode(',',$v['ugids']);
			$v['ugids'] = str2newid($v['ugids'],'ugids');
			$sqlstr = "sid='$nsid'";
			foreach($v as $key => $val){
				!in_array($key,array('atid','sid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
			}
			$db->query("INSERT INTO {$tblprefix}altypes SET $sqlstr");
			if(!($atid = $db->insert_id())) continue;
			$stepadds['altypes'][] = $atid;
			$idsmap['atids'][$k] = $atid;//将新旧id的对应关系记录在案
		}

		$query = $db->query("SELECT * FROM {$tblprefix}altypes WHERE sid='$nsid' ORDER BY atid");
		while($row = $db->fetch_array($query)){
			$row['atids'] = str2newid($row['atids'],'atids');
			$db->query("UPDATE {$tblprefix}altypes SET atids='$row[atids]' WHERE atid='$row[atid]'");
		}
		updatecache('altypes');

		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		$stepeds['altypes'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=catalogs');
		
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['altypes'])){
			$db->query("DELETE FROM {$tblprefix}altypes WHERE atid ".multi_str($stepadds['altypes']),'SILENT');
		}
		updatecache('altypes');

		unset($stepeds['altypes'],$idsmap['atids'],$stepadds['altypes']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=altypes');
	}

}elseif($action == 'catalogs'){
	load_cache('catalogs',$nsid);
	$ocatalogs = oread_cache('catalogs','','','cache');
	$n_operated = isset($stepeds['catalogs']);
	$do_enable = $n_operated || !isset($stepeds['altypes']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['cotypes']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('catalogtransto').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'catalogs','?entry=subsetup&action=catalogs','3');
		trcategory(array(lang('soucatid'),lang('sourcataname'),lang('tranurrentsys')));
		foreach($ocatalogs as $k => $v){
			$space ='';
			for($i=0;$i<$v['level'];$i++) $space .= "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
			echo "<tr class=\"txt\"><td class=\"txtC w80\">$k</td>\n".
				"<td class=\"txtL\">$space$v[title]</td>\n".
				"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')) + caidsarr(),empty($idsmap['caids'][$k]) ? 0 : $idsmap['caids'][$k])."</select></td></tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		//将子站中的栏目信息字段转入进来
		$ocafields = oread_cache('cafields','','','cache');
		$enamearr = $usednames['cafields'];
		$fconfigarr = array(
			'errorurl' => '',
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'catalogs',
			'fieldtable' => $tblprefix.'cnfields',
			'sqlstr' => "iscc='0'",
		);
		foreach($ocafields as $k => $v){
			if(!in_array($v['ename'],$enamearr)){
				$fieldnew = &$v;
				$fieldnew = maddslashes($fieldnew);
				list($fmode,$fnew,$fsave) = array('cn',true,true);
				include M_ROOT."./include/fields/$v[datatype].php";
				if(!($fid = $db->insert_id())) continue;//字段记录
				$stepadds['cafields'][] = $v['ename'];//以英文id来记录增加的字段
			}
		}
		updatecache('cafields');
		updatecache('usednames','cafields');

		foreach($ocatalogs as $k => $v){//需要先转栏目字段
			$v = oread_cache('catalog',$k,'','cache');
			$v['pid'] = str2newid($v['pid'],'caids');
			$v['chids'] = str2newid($v['chids'],'chids');
			$v['atids'] = str2newid($v['atids'],'atids');
			$v['taxcp'] = str2newid($v['taxcp'],'crprices');
			$v['awardcp'] = str2newid($v['awardcp'],'crprices');
			$v['ftaxcp'] = str2newid($v['ftaxcp'],'crprices');
			$sqlstr = "sid='$nsid'";
			foreach($v as $key => $val){
				!in_array($key,array('caid','sid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
			}
			$db->query("INSERT INTO {$tblprefix}catalogs SET $sqlstr");
			if(!($caid = $db->insert_id())) continue;
			$stepadds['catalogs'][] = $caid;
			$idsmap['caids'][$k] = $caid;//将新旧id的对应关系记录在案
		}

		updatecache('catalogs','',$nsid);

		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		$stepeds['catalogs'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=cotypes');
		
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');

		if(!empty($stepadds['cafields'])){//处理栏目字段
			foreach($stepadds['cafields'] as $ename){
				$db->query("ALTER TABLE {$tblprefix}catalogs DROP $ename",'SILENT'); 
				$db->query("DELETE FROM {$tblprefix}cnfields WHERE ename='$ename' AND iscc='0'",'SILENT'); 
			}
		}
		updatecache('cafields');
		updatecache('usednames','cafields');

		if(!empty($stepadds['catalogs'])){//处理栏目
			$db->query("DELETE FROM {$tblprefix}catalogs WHERE caid ".multi_str($stepadds['catalogs']),'SILENT');
		}
		updatecache('catalogs','',$nsid);

		unset($stepeds['catalogs'],$idsmap['caids'],$stepadds['catalogs'],$stepadds['cafields']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=catalogs');
	}

}elseif($action == 'cotypes'){
	load_cache('cotypes');
	$ocotypes = oread_cache('cotypes','','','cache');
	$n_operated = isset($stepeds['cotypes']);
	$do_enable = $n_operated || !isset($stepeds['catalogs']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['coclasses']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		$coidsarr = array();
		foreach($cotypes as $k => $v){
			$coidsarr[$k] = $v['cname'];
		}
		tabheader(lang('cotypetransto').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'cotypes','?entry=subsetup&action=cotypes','3');
		trcategory(array(lang('sogrouptypeid'),lang('sogroupname'),lang('tranurrentsys')));
		foreach($ocotypes as $k => $v){
			echo "<tr class=\"txt\"><td class=\"txtL\">$k</td>\n".
			"<td class=\"txtL\">$v[cname]</td>\n".
			"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')) + $coidsarr,empty($idsmap['coids'][$k]) ? 0 : $idsmap['coids'][$k])."</select></td></tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		foreach($ocotypes as $k => $v){
			if(empty($transtonew[$k])){
				$sqlstr = "chids='".str2newid($v['chids'],'chids')."',atids='".str2newid($v['atids'],'atids')."'";
				foreach($v as $key => $val){
					!in_array($key,array('coid','chids','atids')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
				}
				$db->query("INSERT INTO {$tblprefix}cotypes SET $sqlstr");
				if($coid = $db->insert_id()){
					$db->query("ALTER TABLE {$tblprefix}archives ADD ccid$coid smallint(6) unsigned NOT NULL default 0",'SILENT');
					$stepadds['cotypes'][] = $coid;//将新增的id记录在案
					$idsmap['coids'][$k] = $coid;//将新旧id的对应关系记录在案
				}
			}else{
				$idsmap['coids'][$k] = $transtonew[$k];//将新旧id的对应关系记录在案
			}
		}
		updatecache('cotypes');
		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		$stepeds['cotypes'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=coclasses');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['cotypes'])){
			$db->query("DELETE FROM {$tblprefix}cotypes WHERE coid ".multi_str($stepadds['cotypes']),'SILENT');
			foreach($stepadds['cotypes'] as $coid){
				$db->query("ALTER TABLE {$tblprefix}archives DROP ccid$coid",'SILENT');
				del_cache('coclasses',$coid);
			}
		}
		updatecache('cotypes');

		unset($stepeds['cotypes'],$idsmap['coids'],$stepadds['cotypes']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=cotypes');
	}


}elseif($action == 'coclasses'){
	load_cache('cotypes');
	$ocotypes = oread_cache('cotypes','','','cache');
	$n_operated = isset($stepeds['coclasses']);
	$do_enable = $n_operated || !isset($stepeds['cotypes']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['cnodes']) ? false : true;//如果下一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('coclasstransto').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'coclasses','?entry=subsetup&action=coclasses','4');
		trcategory(array(lang('sourcecotype'),lang('soucocid'),lang('soucoclcname'),lang('tranurrentsys')));
		foreach($ocotypes as $k => $v){
			$ococlasses = oread_cache('coclasses',$k,'','cache');
			foreach($ococlasses as $k1 => $v1){
				$space ='';
				for($i=0;$i<$v1['level'];$i++) $space .= "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
				echo "<tr class=\"txt\"><td class=\"txtC w80\">$v[cname]</td>\n".
				"<td class=\"txtC w80\">$k1</td>\n".
				"<td class=\"txtL\">$space$v1[title]</td>\n".
				"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k1]\">".makeoption(array(0 => lang('add')) + ccidsarr($idsmap['coids'][$k]),empty($idsmap['ccids'][$k1]) ? 0 : $idsmap['ccids'][$k1])."</select></td></tr>\n";
			}
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		//将子站中的分类信息字段转入进来
		$occfields = oread_cache('ccfields','','','cache');
		$enamearr = $usednames['ccfields'];
		$fconfigarr = array(
			'errorurl' => '',
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'coclass',
			'fieldtable' => $tblprefix.'cnfields',
			'sqlstr' => "iscc='1'",
		);
		foreach($occfields as $k => $v){
			if(!in_array($v['ename'],$enamearr)){
				$fieldnew = &$v;
				$fieldnew = maddslashes($fieldnew);
				list($fmode,$fnew,$fsave) = array('cn',true,true);
				include M_ROOT."./include/fields/$v[datatype].php";
				if(!($fid = $db->insert_id())) continue;//字段记录
				$stepadds['ccfields'][] = $v['ename'];//以英文id来记录增加的字段
			}
		}
		updatecache('ccfields');
		updatecache('usednames','ccfields');

		foreach($ocotypes as $k => $v){
			$ococlasses = oread_cache('coclasses',$k,'','cache');
			foreach($ococlasses as $k1 => $v1){
				if(empty($transtonew[$k1])){
					$v1 = oread_cache('coclass',$k,$k1,'cache');
					$v1['coid'] = $idsmap['coids'][$k];
					$v1['pid'] = str2newid($v1['pid'],'ccids');
					$v1['chids'] = str2newid($v1['chids'],'chids');
					$v1['atids'] = str2newid($v1['atids'],'atids');
					$v1['taxcp'] = str2newid($v1['taxcp'],'crprices');
					$v1['awardcp'] = str2newid($v1['awardcp'],'crprices');
					$v1['ftaxcp'] = str2newid($v1['ftaxcp'],'crprices');
					$v1['conditions'] = empty($v1['conditions']) ? '' : serialize($v1['conditions']);
					$sqlstr = '';
					foreach($v1 as $key => $val){
						!in_array($key,array('ccid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
					}
					$db->query("INSERT INTO {$tblprefix}coclass SET $sqlstr");
					if($ccid = $db->insert_id()){
						$stepadds['coclasses'][] = $ccid;//将新增的id记录在案
						$idsmap['ccids'][$k1] = $ccid;//将新旧id的对应关系记录在案
					}
				}else{
					$idsmap['ccids'][$k1] = $transtonew[$k1];//将新旧id的对应关系记录在案
				}
			}
			updatecache('coclasses',$idsmap['coids'][$k]);
		}
		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		$stepeds['coclasses'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=cnodes');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');

		if(!empty($stepadds['ccfields'])){//处理栏目字段
			foreach($stepadds['ccfields'] as $ename){
				$db->query("ALTER TABLE {$tblprefix}coclass DROP $ename",'SILENT'); 
				$db->query("DELETE FROM {$tblprefix}cnfields WHERE ename='$ename' AND iscc='1'",'SILENT'); 
			}
		}
		updatecache('ccfields');
		updatecache('usednames','ccfields');

		if(!empty($stepadds['coclasses'])){
			$db->query("DELETE FROM {$tblprefix}coclass WHERE ccid ".multi_str($stepadds['coclasses']),'SILENT');
			foreach($cotypes as $k => $v){//删除缓存
				updatecache('coclasses',$k);
			}
		}

		unset($stepeds['coclasses'],$idsmap['ccids'],$stepadds['coclasses'],$stepadds['ccfields']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=coclasses');
	}

}elseif($action == 'cnodes'){
	$ocnconfigs = oread_cache('cnconfigs','','','cache');
	$n_operated = isset($stepeds['cnodes']);
	$do_enable = $n_operated || !isset($stepeds['coclasses']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['fchannels']) ? false : true;//如果下一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('catcnotran').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'coclasses','?entry=subsetup&action=cnodes','4');
		trcategory(array(lang('soucatconfigid'),lang('sourataonfigname'),lang('tranurrentsys')));
		foreach($ocnconfigs as $k => $v){
			echo "<tr class=\"txt\"><td class=\"txtC w100\">$k</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')))."</select></td></tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		foreach($ocnconfigs as $k => $v){//先处理节点结构
			$v['sid'] = $nsid;
			$idsarr = array();//重新组建数组
			foreach($v['idsarr'] as $k1 => $v1){
				if($k1 == 'ca'){
					$idsarr['ca'] = array();
					foreach($v1 as $key => $val) $idsarr['ca'][] = str2newid($val,'caids');
				}else{
					$coid = str2newid($k1,'coids');
					$idsarr[$coid] = array();
					foreach($v1 as $key => $val) $idsarr[$coid][] = str2newid($val,'ccids');
				}
			}
			$v['idsarr'] = empty($idsarr) ? '' : serialize($idsarr);
			$sqlstr = '';
			foreach($v as $key => $val){
				!in_array($key,array('cncid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
			}
			$db->query("INSERT INTO {$tblprefix}cnconfigs SET $sqlstr");
			if($cncid = $db->insert_id()){
				$stepadds['cnconfigs'][] = $cncid;//将新增的id记录在案
				$idsmap['cncids'][$k1] = $cncid;//将新旧id的对应关系记录在案
			}
		}
		updatecache('cnconfigs','',$nsid);
	
		$ocnodes = oread_cache('cnodes','','','cache');//处理节点
		foreach($ocnodes as $k => $v){
			$v = oread_cache('cnode',$k,'','cache');
			$v['sid'] = $nsid;
			$v['cncid'] = str2newid($v['cncid'],'cncids');
			$v['mainline'] = str2newid($v['mainline'],'coids');
			$v['caid'] = str2newid($v['caid'],'caids');
			$v['ename'] = cnstr2newid($v['ename']);
			$sqlstr = '';
			foreach($v as $key => $val){
				!in_array($key,array('cnid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
			}
			$db->query("INSERT INTO {$tblprefix}cnodes SET $sqlstr");
			if($cnid = $db->insert_id()){
				$stepadds['cnodes'][] = $v['ename'];//将新增的id记录在案//以ename保存便于删除细分缓存
			}
		}
		updatecache('cnodes','',$nsid);

		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		$stepeds['cnodes'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=fchannels');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['cnconfigs'])){
			$db->query("DELETE FROM {$tblprefix}cnconfigs WHERE cncid ".multi_str($stepadds['cnconfigs']),'SILENT');
		}
		updatecache('cnconfigs','',$nsid);

		if(!empty($stepadds['cnodes'])){
			$db->query("DELETE FROM {$tblprefix}cnodes WHERE ename ".multi_str($stepadds['cnodes'])." AND sid='$nsid'",'SILENT');
		}
		updatecache('cnodes','',$nsid);

		unset($stepeds['cnodes'],$idsmap['cncids'],$stepadds['cnconfigs'],$stepadds['cnodes']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=cnodes');
	}
}elseif($action == 'fchannels'){
	load_cache('fchannels');
	$ofchannels = oread_cache('fchannels','','','cache');
	$n_operated = isset($stepeds['fchannels']);
	$do_enable = $n_operated || !isset($stepeds['cnodes']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['fcatalogs']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('freechantransto').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'fchannels','?entry=subsetup&action=fchannels','3');
		trcategory(array(lang('soufrechaid'),lang('sourfreechanname'),lang('tranurrentsys')));
		foreach($ofchannels as $k => $v){
			echo "<tr class=\"txt\"><td class=\"txtL\">$k</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')) + fchidsarr(1),empty($idsmap['fchids'][$k]) ? 0 : $idsmap['fchids'][$k])."</select></td></tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		if(!$do_enable) amessage('invoperate');
		if(!empty($transtonew)){

			//处理文档模型
			foreach($ofchannels as $k => $v){
				if(empty($transtonew[$k])){//新建会员模型
					$sqlstr = '';
					foreach($v as $key => $val){
						!in_array($key,array('chid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
					}
					$db->query("INSERT INTO {$tblprefix}fchannels SET $sqlstr");
					if($fchid = $db->insert_id()){
						$db->query("CREATE TABLE {$tblprefix}farchives_$fchid (aid mediumint(8) unsigned NOT NULL default '0',PRIMARY KEY (aid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM"));
						//将模型专用的字段转入进来
						$fields = oread_cache('ffields',$k,'','cache');
						foreach($fields as $k1 => $v1){
							$fieldnew = oread_cache('ffield',$k,$k1,'cache');
							$fieldnew = maddslashes($fieldnew);
							if(!$v1['issystem']){//只转入模型定义的字段
								$fconfigarr = array(
								'errorurl' => '',
								'enamearr' => $usednames['ffields'],
								'altertable' => $tblprefix.'farchives_'.$fchid,
								'fieldtable' => $tblprefix.'ffields',
								'sqlstr' => "chid=$fchid,available='1'",
								);
								list($fmode,$fnew,$fsave) = array('fa',true,true);
								include M_ROOT."./include/fields/$v1[datatype].php";
								if(!($fid = $db->insert_id())) continue;
								$stepadds['ffields'][] = $fid;//记录增加字段
							}else{//subject字段
								$sqlstr = "chid='$fchid'";
								foreach($field as $key => $val){
									!in_array($key,array('fid','chid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
								}
								$db->query("INSERT INTO {$tblprefix}ffields SET $sqlstr");
								if(!($fid = $db->insert_id())) continue;
								$stepadds['ffields'][] = $fid;
							}
						}

						$stepadds['fchannels'][] = $fchid;//将新增的id记录在案
						$idsmap['fchids'][$k] = $fchid;//将新旧id的对应关系记录在案
					}
					updatecache('ffields',$fchid);

				}else{//指定对应模型
					$idsmap['fchids'][$k] = $transtonew[$k];//将新旧id的对应关系记录在案
				}
				
			}
			updatecache('fchannels');
			updatecache('usednames','ffields');
			ocache2file($stepadds,'stepadds');
			ocache2file($idsmap,'idsmap');
		}
		$stepeds['fchannels'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=fcatalogs');
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');
		if(!empty($stepadds['fchannels'])){//清除所增文档模型的所有资料及缓存
			foreach($stepadds['fchannels'] as $fchid){
				$db->query("DROP TABLE IF EXISTS {$tblprefix}farchives_$fchid",'SILENT');
				$db->query("DELETE FROM {$tblprefix}fchannels WHERE chid='$fchid'",'SILENT');
				$db->query("DELETE FROM {$tblprefix}ffields WHERE chid='$fchid'",'SILENT');
				//清除相关缓存
				del_cache('ffields',$fchid);
			}
		}
		updatecache('fchannels');

		if(!empty($stepadds['ffields'])){//删除在ffields表中增加的记录
			$db->query("DELETE FROM {$tblprefix}ffields WHERE fid ".multi_str($stepadds['ffields']),'SILENT');
		}


		unset($stepeds['fchannels'],$idsmap['fchids'],$stepadds['fchannels'],$stepadds['ffields']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');

		amessage('undosuc','?entry=subsetup&action=fchannels');
	}

}elseif($action == 'fcatalogs'){
	load_cache('fcatalogs');
	$ofcatalogs = oread_cache('fcatalogs','','','cache');
	$n_operated = isset($stepeds['fcatalogs']);
	$do_enable = $n_operated || !isset($stepeds['fchannels']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['freeinfos']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('freecoctran').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'fcatalogs','?entry=subsetup&action=fcatalogs','3');
		trcategory(array(lang('soufrecocid'),lang('soufrecoccna'),lang('tranurrentsys')));
		foreach($ofcatalogs as $k => $v){
			echo "<tr class=\"txt\"><td class=\"txtC w80\">$k</td>\n".
				"<td class=\"txtC\">$v[title]</td>\n".
				"<td class=\"txtC\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')) + fcaidsarr(),empty($idsmap['fcaids'][$k]) ? 0 : $idsmap['fcaids'][$k])."</select></td></tr>\n";
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		foreach($ofcatalogs as $k => $v){//需要先转栏目字段
			if(empty($transtonew[$k])){
				$v = oread_cache('fcatalog',$k,'','cache');
				$v['chid'] = str2newid($v['chid'],'fchids');
				$v['rugids'] = str2newid($v['rugids'],'ugids');
				$v['taxcrid'] = str2newid($v['taxcrid'],'crids');
				$v['ugids'] = implode(',',$v['ugids']);
				$v['ugids'] = str2newid($v['ugids'],'ugids');
				$sqlstr = '';
				foreach($v as $key => $val){
					!in_array($key,array('caid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
				}
				$db->query("INSERT INTO {$tblprefix}fcatalogs SET $sqlstr");
				if(!($fcaid = $db->insert_id())) continue;
				$stepadds['fcatalogs'][] = $fcaid;
				$idsmap['fcaids'][$k] = $fcaid;//将新旧id的对应关系记录在案
			}else{
				$idsmap['fcaids'][$k] = $transtonew[$k];//将新旧id的对应关系记录在案
			}
		}

		updatecache('fcatalogs');

		ocache2file($stepadds,'stepadds');
		ocache2file($idsmap,'idsmap');
		$stepeds['fcatalogs'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=freeinfos');
		
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');

		if(!empty($stepadds['fcatalogs'])){//处理栏目
			$db->query("DELETE FROM {$tblprefix}fcatalogs WHERE caid ".multi_str($stepadds['fcatalogs']),'SILENT');
		}
		updatecache('fcatalogs');

		unset($stepeds['fcatalogs'],$idsmap['fcaids'],$stepadds['fcatalogs']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');
		amessage('undosuc','?entry=subsetup&action=fcatalogs');
	}
}elseif($action == 'freeinfos'){
	$ofreeinfos = oread_cache('freeinfos','','','cache');
	$n_operated = isset($stepeds['freeinfos']);
	$do_enable = $n_operated || !isset($stepeds['fcatalogs']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated || isset($stepeds['templates']) ? false : true;//如果上一步没有撤消，这一步不能撤消
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('isolpagtrans').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'freeinfos','?entry=subsetup&action=freeinfos','3');
		trcategory(array(lang('souisopagid'),lang('souisopagcna'),lang('tranurrentsys')));
		foreach($ofreeinfos as $k => $v){
			if($v['sid'] == $osid){
				echo "<tr><td  class=\"txtC w80\">$k</td>\n".
				"<td class=\"txtC\">$v[cname]</td>\n".
				"<td class=\"txtC\"><select style=\"vertical-align: middle;\" name=\"transtonew[$k]\">".makeoption(array(0 => lang('add')))."</select></td></tr>\n";
			}
		}
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		foreach($ofreeinfos as $k => $v){//需要先转栏目字段
			$v['sid'] = $nsid;
			$v['arcurl'] = '';
			$sqlstr = '';
			foreach($v as $key => $val){
				!in_array($key,array('fid')) && $sqlstr .= (!$sqlstr ? '' : ',')."$key='".addslashes($val)."'";
			}
			$db->query("INSERT INTO {$tblprefix}freeinfos SET $sqlstr");
			if(!($fid = $db->insert_id())) continue;
			$stepadds['freeinfos'][] = $fid;
		}
		updatecache('freeinfos');

		ocache2file($stepadds,'stepadds');
		$stepeds['freeinfos'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=templates');
		
	}elseif(submitcheck('bundo')){
		if(!$undo_enable) amessage('invoperate');

		if(!empty($stepadds['freeinfos'])){//处理栏目
			$db->query("DELETE FROM {$tblprefix}freeinfos WHERE fid ".multi_str($stepadds['freeinfos']),'SILENT');
		}
		updatecache('freeinfos');

		unset($stepeds['freeinfos'],$stepadds['freeinfos']);
		ocache2file($stepeds,'stepeds');
		ocache2file($idsmap,'idsmap');
		ocache2file($stepadds,'stepadds');
		amessage('undosuc','?entry=subsetup&action=freeinfos');
	}
}elseif($action == 'templates'){
	$n_operated = isset($stepeds['templates']);
	$do_enable = $n_operated || !isset($stepeds['freeinfos']) ? false : true;//如果上一步没有完成，这一步不能开始
	$undo_enable = !$n_operated ? false : true;//如果上一步没有撤消，这一步不能撤消
	$true_tpldir = M_ROOT."./template/".$subsites[$nsid]['templatedir'].'/';
	if(!submitcheck('bdo') && !submitcheck('bundo')){
		tabheader(lang('subtemtra').'&nbsp; -&nbsp; '.($n_operated ? '&nbsp; Y' : '&nbsp; N'),'templates','?entry=subsetup&action=templates');
		echo "<tr class=\"txt\"><td class=\"txtC\" colspan=\"2\"><br>";
		echo empty($n_operated) ? lang('templatesdo') : lang('templatesundo');
		echo "<br><br></td></tr>";
		tabfooter();
		echo "<input class=\"button\" type=\"submit\" name=\"bdo\" value=\"".lang('nextstep')."\"".($do_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "<input class=\"button\" type=\"submit\" name=\"bundo\" value=\"".lang('undosetting')."\"".($undo_enable ? '' : ' disabled').">&nbsp; &nbsp; ";
		echo "</form>\n";
	}elseif(submitcheck('bdo')){
		if(!dir_copy($subsetupdir.'template/',$true_tpldir,1,1)) amessage('telcopyerror');
		$tagsarr = $tplsarr = array();

		$omtpls = oread_cache('mtpls','','','template');
		foreach($omtpls as $k => $v){
			$tplsarr[] = $k;
		}

		$osptpls = oread_cache('sptpls','','','template');
		foreach($osptpls as $k => $v){
			$tplsarr[] = $v;
		}

		foreach($tplsarr as $v){
			$str = @file2str($subsetupdir.'template/'.$v);
			oreplace($str,'p');
			oreplace($str,'c');
			str2file($str,$true_tpldir.$v);
		}

		$tagsarr[] = array('mtpls','');
		$tagsarr[] = array('sptpls', '');

		$tagsarr[] = array('rtags','');
		$ortags = oread_cache('rtags','','','template');
		foreach($ortags as $k => $v){
			$tagsarr[] = array('rtag',$k);
		}

		$tagsarr[] = array('ptags','');
		$optags = oread_cache('ptags','','','template');
		foreach($optags as $k => $v){
			$tagsarr[] = array('ptag',$k);
		}

		$tagsarr[] = array('ctags','');
		$octags = oread_cache('ctags','','','template');
		foreach($octags as $k => $v){
			$tagsarr[] = array('ctag',$k);
		}

		$tagsarr[] = array('utags','');
		$outags = oread_cache('utags','','','template');
		foreach($outags as $k => $v){
			$tagsarr[] = array('utag',$k);
		}


		foreach($tagsarr as $k => $v){
			$ocache = oread_cache($v[0],$v[1],'','template');
			if(!$v[1]){
				cache2file($ocache,$v[0],$v[0],$nsid);
			}else{
				if(!empty($ocache['template'])){
					oreplace($ocache['template'],'p');
					oreplace($ocache['template'],'c');
				}
				cache2file($ocache,cache_name($v[0],$v[1]),$v[0],$nsid);
			}
		}
		
		//将一些站点信息写入进来
		$omconfigs = oread_cache('mconfigs','','','cache');
		$sqlstr = '';
		foreach(array('cmslogo','cmstitle','cmskeyword','cmsdescription','hometpl',) as $var){
			isset($omconfigs[$var]) && $sqlstr .= ($sqlstr ? ',' : '')."$var='".addslashes($omconfigs[$var])."'";
		}
		$db->query("UPDATE {$tblprefix}subsites SET $sqlstr WHERE sid='$nsid'");
		updatecache('subsites');

		$stepeds['templates'] = 1;
		ocache2file($stepeds,'stepeds');
		amessage('operatesuc','?entry=subsetup&action=templates');

	}elseif(submitcheck('bundo')){
		clear_dir($true_tpldir);
		unset($stepeds['templates']);
		ocache2file($stepeds,'stepeds');
		amessage('undosuc','?entry=subsetup&action=templates');
	}
}elseif($action == 'delupload'){
	if(empty($confirm)){
		$message = lang('delsubinsupdatandrec').'<br><br>'.lang('del_alert')."<br><br>";
		$message .= lang('confirmclick')."[<a href=?entry=subsetup&action=delupload&confirm=1>".lang('delete')."</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$message .= lang('giveupclick')."[<a href=?entry=subsetup>".lang('goback')."</a>]";
		amessage($message);
	}
	clear_dir(M_ROOT."dynamic/subsetup/",false);
	amessage('recdelfin',"?entry=subsetup");
}
?>
