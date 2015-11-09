<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/archive.fun.php';
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);

$aid = empty($aid) ? 0 : max(0,intval($aid));
if(!$aid) cumessage('choosearchive');
$cuid = $db->result_one("SELECT c.report FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}channels c ON c.chid=a.chid WHERE a.aid='$aid'");
if(!$cuid || !($commu = read_cache('commu',$cuid))) message('setcomitem');
if(empty($commu['ucadd'])){
	if(!submitcheck('newcommu')){
		include_once M_ROOT.'./include/archive.cls.php';
		$arc = new cls_archive();
		$arc->arcid($aid);
		if(!$arc->aid) message('choosearchive');
		if(!$arc->archive['checked']) message('poinarcnoche');
		if(empty($commu['setting']['repeat']) || !empty($commu['setting']['repeattime'])){
			if(!empty($m_cookie['08cms_cuid_'.$commu['cuid'].'_'.$aid])) message(empty($commu['setting']['repeat']) ? 'norepeatoper' : 'overquick');
		}
		switch_cache($arc->archive['sid']);
		$sid = $arc->archive['sid'];
		if_siteclosed($sid);
		cache_merge($commu,'commu',$sid);
		if(!($tplname = $commu['addtpl'])){
			unset($arc);
			load_cache('mlangs,bfields,ucotypes');
			include_once M_ROOT."./include/admin.fun.php";
			include_once M_ROOT."./include/adminm.fun.php";
			include_once M_ROOT."./include/fields.cls.php";
			include_once M_ROOT."./include/arcedit.cls.php";
			include_once M_ROOT."./include/cheader.inc.php";
			_header();
			
			$aedit = new cls_arcedit;
			$aedit->set_aid($aid);
			$aedit->detail_data();
			$citems = empty($commu['setting']['citems']) ? array() : explode(',',$commu['setting']['citems']);
			tabheader(lang('add').$commu['cname'],'reportadd',"?aid=$aid$forwardstr",2,1,1,1);
			$submitstr = '';
	
			foreach($ucotypes as $k => $v){
				if(in_array('uccid'.$k,$citems) && $v['umode'] != 2){
					trbasic($v['cname'],'',mu_cnselect("communew[uccid$k]",0,$k,lang('p_choose'),$v['emode'],"communew[uccid{$k}date]",''),'');
					$submitstr .= makesubmitstr("communew[uccid$k]",$v['notblank'],0,0,0,'common');
					$v['emode'] == 2 && $submitstr .= makesubmitstr("communew[uccid{$k}date]",1,0,0,0,'date');
				}
			}
			$a_field = new cls_field;
			foreach($bfields as $k => $v){
				if(!$v['isadmin'] && !$v['isfunc'] && in_array($k,$citems)){
					$a_field->init();
					$a_field->field = $v;
					$a_field->isadd = 1;
					$a_field->trfield('communew','','b');
					$submitstr .= $a_field->submitstr;
				}
			}
			unset($a_field);
			$submitstr .= tr_regcode('report');
			tabfooter('newcommu');
			check_submit_func($submitstr);
			_footer();
		}else{
			$_da = &$arc->archive;
			arc_parse($_da);
			
			_aenter($_da,1);
			@extract($btags);
			extract($_da,EXTR_OVERWRITE);
			tpl_refresh($tplname);
			@include M_ROOT."template/$templatedir/pcache/$tplname.php";
			
			$_content = ob_get_contents();
			ob_clean();
			mexit($_content);
		}
	}else{//数据处理
		load_cache('bfields,ucotypes');
		include_once M_ROOT."./include/fields.cls.php";
		include_once M_ROOT."./include/upload.cls.php";
		include_once M_ROOT."./include/arcedit.cls.php";
		include_once M_ROOT."./include/cheader.inc.php";
		$inajax ? aheader() : _header();
		if(!regcode_pass('report',empty($regcode) ? '' : trim($regcode))) mcmessage('regcodeerror',axaction(2,M_REFERER));
		if(!$curuser->checkforbid('report')) mcmessage('userisforbid',axaction(2,M_REFERER));//屏蔽组
		$aedit = new cls_arcedit;
		$aedit->set_aid($aid);
		$aedit->basic_data();
		if(!$aedit->aid) mcmessage('choosereportobject',axaction(2,M_REFERER));
		if(!$aedit->archive['checked']) mcmessage('poinarcnoche');
		if(!$curuser->pmbypmids('cuadd',$commu['setting']['apmid'])) mcmessage('younoitempermis',axaction(2,M_REFERER));
		if(empty($commu['setting']['repeat']) || !empty($commu['setting']['repeattime'])){
			if(empty($m_cookie['08cms_cuid_'.$commu['cuid'].'_'.$aid])){
				msetcookie('08cms_cuid_'.$commu['cuid'].'_'.$aid,'1',empty($commu['setting']['repeat']) ? 365 * 24 * 3600 : $commu['setting']['repeattime'] * 60);
			}else mcmessage(empty($commu['setting']['repeat']) ? 'norepeatoper' : 'overquick',axaction(2,M_REFERER));
		}
		$citems = empty($commu['setting']['citems']) ? array() : explode(',',$commu['setting']['citems']);
		foreach($bfields as $k => $v) if(in_array($k,$citems)) $fields[$k] = $v;
		$fields = fields_order($fields);
	
		$sqlstr = '';
		$c_upload = new cls_upload;	
		$a_field = new cls_field;
		foreach($ucotypes as $k => $v){
			if(in_array('uccid'.$k,$citems) && $v['umode'] != 2){
				if($v['notblank'] && empty($communew['uccid'.$k])) mcmessage('notnull',axaction(2,M_REFERER),$v['cname']);
				$sqlstr .= ",uccid$k='".$communew['uccid'.$k]."'";
				if($v['emode']){
					$communew["uccid{$k}date"] = !isdate($communew["uccid{$k}date"]) ? 0 : strtotime($communew["uccid{$k}date"]);
					if($communew["uccid$k"] && !$communew["uccid{$k}date"] && $v['emode'] == 2) mcmessage('setcoclass',axaction(2,M_REFERER),$v['cname']);
					!$communew["uccid$k"] && $communew["uccid{$k}date"] = 0;
					$sqlstr .= ",uccid{$k}date='".$communew["uccid{$k}date"]."'";
				}
			}
		}
		foreach($fields as $k => $v){
			if(!$v['isfunc'] && !$v['isadmin']){
				if($curuser->pmbypmids('field',$v['pmid'])){
					$a_field->init();
					$a_field->field = $v;
					$a_field->oldvalue = '';
					$a_field->deal('communew');
					if(!empty($a_field->error)){
						$c_upload->rollback();
						mcmessage($a_field->error,axaction(2,M_REFERER));
					}
					$sqlstr .= ",$k='".$a_field->newvalue."'";
					if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
				}
			}
		}
		unset($a_field);
		$c_upload->saveuptotal(1);
	
		$db->query("INSERT INTO {$tblprefix}reports SET
			aid='$aid',
			cuid='$commu[cuid]',
			mid='$memberid',
			mname='".$curuser->info['mname']."',
			createdate='$timestamp',
			updatedate='$timestamp'
			$sqlstr
			");
		$c_upload->closure(1, $db->insert_id(), 'reports');
		$curuser->basedeal('report',1,1,1);
		$aedit->arc_nums('reports',1,1);
		mcmessage('submitsucceed',axaction(10,$forward));
	}
}else include(M_ROOT.$commu['ucadd']);

?>

