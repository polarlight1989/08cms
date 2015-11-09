<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('farchive') || amessage('no_apermission');
load_cache('fcatalogs,fchannels,currencys,');
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/farcedit.cls.php";
include_once M_ROOT."./include/farchive.cls.php";
include_once M_ROOT."./include/farc_static.fun.php";
if($action == 'farchiveadd' && empty($fcaid)){
	$num = 4;
	$i = 0;
	tabheader(lang('add_freeinfo'),'','',$num);
	foreach($fcatalogs as $fcaid => $fcatalog){
		$fcatalog = read_cache('fcatalog',$fcaid);
		if($curuser->pmbypmids('fadd',$fcatalog['apmid'])){
			if(!($i % $num)) echo "<tr align=\"center\">";
			echo "<td class=\"item2\" width=\"".(intval(100 / $num))."%\"><a href=\"?entry=farchive&action=farchiveadd&fcaid=$fcaid\" onclick=\"return floatwin('open_farchive',this)\">$fcatalog[title]</a></td>\n";
			$i ++;
			if(!($i % $num)) echo "</tr>\n";
		}
	}
	if($i % $num){
		while($i % $num){
			echo "<td class=\"item2\" width=\"".(intval(100 / $num))."%\"></td>\n";
			$i ++;
		}
		echo "</tr>\n";
	}
	tabfooter();
	a_guide('farchiveadd0');
}elseif($action == 'farchiveadd' && $fcaid){//只有指定分类才能添加
	//首先分析当前会员在分类中的发布权限
	$fcaid = max(0,intval($fcaid));
	if(!$fcaid || !($fcatalog = read_cache('fcatalog',$fcaid))) amessage('choosemescoc');
	if(empty($fcatalog['uaadd'])){
		!$curuser->pmbypmids('fadd',$fcatalog['apmid']) && amessage('nothicocaddper');
		$chid = $fcatalog['chid'];
		$fields = read_cache('ffields',$chid);
		if(!submitcheck('bfarchiveadd')){
			$submitstr = '';
			$a_field = new cls_field;
			$subject_table = 'farchives';
			tabheader($fcatalog['title'].'-'.lang('farcissue'),'farchiveadd',"?entry=farchive&action=farchiveadd&fcaid=$fcaid",2,1,1,1);
			foreach($fields as $k => $field){
				if(!$field['isfunc']){
					$a_field->init();
					$a_field->field = $field;
					$a_field->isadd = 1;
					$a_field->trfield('farchiveadd','','f',$chid);
					$submitstr .= $a_field->submitstr;
				}
			}
			unset($a_field);
			if(empty($fcatalog['nodurat'])){
				foreach(array('startdate','enddate') as $var){
					trbasic(lang($var),"farchiveadd[$var]",'','calendar',lang('agdate'));
					$submitstr .= makesubmitstr("farchiveadd[$var]",0,0,0,0,'date');
				}
			}
			tabfooter('bfarchiveadd');
			check_submit_func($submitstr);
			a_guide('farchiveadd');
		}else{
			$c_upload = new cls_upload;
			$fields = fields_order($fields);
			$a_field = new cls_field;
			$sqlcommon = "fcaid=$fcaid,chid='$chid',mid='$memberid',mname='".$curuser->info['mname']."',createdate='$timestamp'";
			if(empty($fcatalog['nodurat'])){
				foreach(array('startdate','enddate') as $var){
					$farchiveadd[$var] = trim($farchiveadd[$var]);
					$farchiveadd[$var] = !isdate($farchiveadd[$var]) ? 0 : strtotime($farchiveadd[$var]);
					$sqlcommon .= ",$var='$farchiveadd[$var]'";
				}
			}
			
			$sqlcustom = "";
			foreach($fields as $k => $v){
				if(!$field['isfunc']){
					$a_field->init();
					$a_field->field = $v;
					$a_field->deal('farchiveadd');
					if(!empty($a_field->error)){
						$c_upload->rollback();
						amessage($a_field->error,axaction(2,M_REFERER));
					}
					$qvar = $v['issystem'] ? 'sqlcommon' : 'sqlcustom';
					$$qvar .= ($$qvar ? ',' : '')."$k='".$a_field->newvalue."'";
					if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $$qvar .= ($$qvar ? ',' : '').$k.'_'.$x."='$y'";
				}
			}
			unset($a_field);
	
			$db->query("INSERT INTO {$tblprefix}farchives SET $sqlcommon");
			if(!($aid = $db->insert_id())){
				$c_upload->closure(1, $aid, 'farchives');
				amessage('mesaddfai',M_REFERER);
			}else{
				$c_upload->closure(1, $aid, 'farchives');
				$sqlcustom = "aid=$aid".($sqlcustom ? ','.$sqlcustom : '');
				$db->query("INSERT INTO {$tblprefix}farchives_$chid SET $sqlcustom");
				//函数定段的处理及自动审核
				$aedit = new cls_farcedit;
				$aedit->set_aid($aid);
				$fcatalog['autocheck'] && $aedit->arc_check(1,0);
				$aedit->updatedb();
				unset($aedit);
			}
			$c_upload->saveuptotal(1);
			adminlog(lang('add_freeinfo'));
			amessage('freaddfin',axaction(6,M_REFERER));
		}
	}else include(M_ROOT.$fcatalog['uaadd']);
}elseif($action == 'farchivedetail' && $aid){
	empty($aid) && amessage('choosemesid');
	$fcaid = $db->result_one("SELECT fcaid FROM {$tblprefix}farchives WHERE aid='$aid'");
	if(!($fcatalog = read_cache('fcatalog',$fcaid))) amessage('choosearctype');
	if(empty($fcatalog['uadetail'])){
		$aedit = new cls_farcedit;
		$aedit->set_aid($aid);
		$aedit->detail_data();
		!$aedit->aid && amessage('choose_msg_id');
		$chid = $aedit->channel['chid'];
		$fcatalog = &$aedit->catalog;
		$fields = read_cache('ffields',$chid);
		$forward = empty($forward) ? M_REFERER : $forward;
		$forwardstr = '&forward='.urlencode($forward);
		if(!submitcheck('bfarchivedetail')) {
			$a_field = new cls_field;
			tabheader($fcatalog['title'].'-'.lang('freeinfo'),'farchivedetail',"?entry=farchive&action=farchivedetail&aid=$aid$forwardstr",2,1,1,1);
			$submitstr = '';
			trbasic(lang('order'),'farchivenew[vieworder]',$aedit->archive['vieworder']);
			if(empty($fcatalog['nodurat'])){
				foreach(array('startdate','enddate') as $var){
					trbasic(lang($var),"farchivenew[$var]",$aedit->archive[$var] ? date('Y-m-d',$aedit->archive[$var]) : '','calendar',lang('agdate'));
					$submitstr .= makesubmitstr("farchivenew[$var]",0,0,0,0,'date');
				}
			}
			$subject_table = 'farchives';
			foreach($fields as $k => $field){
				if(!$field['isfunc']){
					$a_field->init();
					$a_field->field = $field;
					$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
					$a_field->trfield('farchivenew','','f',$chid);
					$submitstr .= $a_field->submitstr;
				}
			}
			unset($a_field);
			tabfooter('bfarchivedetail');
			check_submit_func($submitstr);
			a_guide('farchivedetail');
		}else{
			$aedit->updatefield('vieworder',max(0,intval($farchivenew['vieworder'])),'main');
			if(empty($fcatalog['nodurat'])){
				foreach(array('startdate','enddate') as $var){
					$farchivenew[$var] = trim($farchivenew[$var]);
					$farchivenew[$var] = !isdate($farchivenew[$var]) ? 0 : strtotime($farchivenew[$var]);
					$aedit->updatefield($var,max(0,intval($farchivenew[$var])),'main');
				}
			}
			$c_upload = new cls_upload;	
			$fields = fields_order($fields);
			$a_field = new cls_field;
			foreach($fields as $k => $v){
				if(!$v['isfunc']){
					$a_field->init();
					$a_field->field = $v;
					$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
					$a_field->deal('farchivenew');
					if(!empty($a_field->error)){
						$c_upload->rollback();
						amessage($a_field->error,axaction(2,M_REFERER));
					}
					$aedit->updatefield($k,$a_field->newvalue,$v['issystem'] ? 'main' : 'custom');
					if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $aedit->updatefield($k.'_'.$x,$y,$v['issystem'] ? 'main' : 'custom');
				}
			}
			unset($a_field);
	
			$aedit->updatedb();
			$c_upload->closure(1, $aid, 'farchives');
			$c_upload->saveuptotal(1);
			adminlog(lang('detail0_modify_freeinfo'));
			amessage('mmsgeditfin',axaction(10,$forward));
		
		}
	}else include(M_ROOT.$fcatalog['uadetail']);
}elseif($action == 'fconsult' && $aid){
	$qstatearr = array(
	'new' => lang('nosettle'),
	'dealing' => lang('dealing0'),
	'end' => lang('settled'),
	'close' => lang('closed'),
	);
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.urlencode($forward);
	$aedit = new cls_farcedit;
	$aedit->set_aid($aid);
	$aedit->basic_data();
	!$aedit->aid && amessage('choosemes');	
	$chid = $aedit->archive['chid'];
	$fcaid = $aedit->archive['fcaid'];
	if(!($fcatalog = read_cache('fcatalog',$fcaid)) || !$fcatalog['cumode']) amessage('poconcoc');
	if(!submitcheck('bfconsult') && !submitcheck('bfconsultadd')){
		tabheader(lang('consult_based_msg'),'fconsult',"?entry=farchive&action=fconsult&aid=$aid$forwardstr",2,1,0,1);
		trbasic(lang('consult_coclass_title'),'',$fcatalog['title'].'&nbsp; -&nbsp; '.$aedit->archive['subject']."&nbsp;&nbsp;<a href=\"?entry=farchive&action=farchivedetail&aid=".$aedit->archive['aid']."\" onclick=\"return floatwin('open_consult',this)\">>>".lang('detail')."</a>",'');
		trbasic(lang('consult_member_add_update'),'',$aedit->archive['mname'].'&nbsp; /&nbsp; '.date("$dateformat $timeformat",$aedit->archive['createdate']).'&nbsp; /&nbsp; '.date("$dateformat $timeformat",$aedit->archive['updatedate']),'');
		trbasic(lang('set_state'),'qstatenew',makeradio('qstatenew',$qstatearr,$aedit->archive['qstate']),'');
		tabfooter();

		tabheader(lang('consult_commu_list'));
		$query = $db->query("SELECT * FROM {$tblprefix}consults WHERE aid='$aid' ORDER BY cid");
		while($item = $db->fetch_array($query)){
			$cid = $item['cid'];
			trbasic('<b>'.$item['mname'].'</b>&nbsp; &nbsp; '.(empty($item['reply']) ? lang('consult') : lang('reply')).'&nbsp; :<br>'.date("$dateformat $timeformat",$item['createdate'])."<br><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$cid]\" value=\"$cid\">".lang('del'),'','<br>'.$item['content'].'<br>&nbsp;','');
		}
		tabfooter('bfconsult');
		if($aedit->archive['qstate'] != 'close'){
			tabheader(lang('adminreply'),'fconsultadd',"?entry=farchive&action=fconsult&aid=$aid$forwardstr",2,1,0,1);
			trbasic(lang('reply_content'),'contentadd','','btextarea');
			tabfooter('bfconsultadd',lang('add'));
		}
		a_guide('fconsult');

	}elseif(submitcheck('bfconsult')){
		!empty($delete) && $query = $db->query("DELETE FROM {$tblprefix}consults WHERE aid='$aid' AND cid ".multi_str($delete)." ORDER BY cid");
		$query = $db->query("UPDATE {$tblprefix}farchives SET qstate='$qstatenew',updatedate='$timestamp' WHERE aid='$aid'");
		amessage('staetsuc',axaction(2,"?entry=farchive&action=fconsult&aid=$aid"));
	}elseif(submitcheck('bfconsultadd')){
		$aedit->archive['qstate'] == 'close' && amessage('thconiteclo',axaction(2,M_REFERER));
		$contentadd = empty($contentadd) ? '' : trim($contentadd);
		empty($contentadd) && amessage('datamissing',axaction(2,M_REFERER));
		$fcatalog['culength'] && $contentadd = cutstr($contentadd,$fcatalog['culength']);
		$contentadd = mnl2br(mhtmlspecialchars($contentadd));
		$db->query("INSERT INTO {$tblprefix}consults SET
					 aid='$aid', 
					 content='$contentadd', 
					 mid='$memberid', 
					 mname='".$curuser->info['mname']."', 
					 createdate='$timestamp',
					 reply=1
					 ");
		$db->query("UPDATE {$tblprefix}farchives SET qstate='end',updatedate='$timestamp' WHERE aid='$aid'");
		amessage('addrepsuc',axaction(6,"?entry=farchive&action=fconsult&aid=$aid"));
	}
}

?>
