<?php
!defined('M_COM') && exit('No Permission');
load_cache('fcatalogs,fchannels,currencys,');
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/farcedit.cls.php";
if(empty($fcaid)){
	$nmuid = empty($nmuid) ? 0 : $nmuid;
	$u_checked = $u_valid = -1;
	if($nmuid && $u_url = read_cache('murl',$nmuid)){
		$u_tplname = $u_url['tplname'];
		$u_onlyview = empty($u_url['onlyview']) ? 0 : 1;
		$u_mtitle = $u_url['mtitle'];
		$u_guide = $u_url['guide'];
		$vars = array('caids');
		foreach($vars as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
	}
	if(!empty($u_caids)) foreach($fcatalogs as $k => $v) if(!in_array($k,$u_caids)) unset($fcatalogs[$k]); 
	if(empty($u_tplname)){
		$num = 4;
		$i = 0;
		tabheader(empty($u_mtitle) ? lang('addfreeinfo') : $u_mtitle,'','',$num);
		foreach($fcatalogs as $fcaid => $fcatalog){
			$fcatalog = read_cache('fcatalog',$fcaid);
			if($curuser->pmbypmids('fadd',$fcatalog['apmid'])){
				if(!($i % $num)) echo "<tr align=\"center\">";
				echo "<td class=\"item\" width=\"".(intval(100 / $num))."%\"><a href=\"?action=farchiveadd&fcaid=$fcaid\" onclick=\"return floatwin('open_farchiveadd',this)\">$fcatalog[title]</a></td>\n";
				$i ++;
				if(!($i % $num)) echo "</tr>\n";
			}
		}
		if($i % $num){
			while($i % $num){
				echo "<td class=\"item\" width=\"".(intval(100 / $num))."%\"></td>\n";
				$i ++;
			}
			echo "</tr>\n";
		}
		tabfooter();
		m_guide(@$u_guide);
	}else include(M_ROOT.$u_tplname);

}else{
	$fcaid = max(0,intval($fcaid));
	if(!$fcaid || !($fcatalog = read_cache('fcatalog',$fcaid))) mcmessage('choosemessagecoclass');
	if(empty($fcatalog['ucadd'])){
		!$curuser->checkforbid('issue') && mcmessage('userisforbid');
		!$curuser->pmbypmids('fadd',$fcatalog['apmid']) && mcmessage('nococlassaddpermi');
		$chid = $fcatalog['chid'];
		$fields = read_cache('ffields',$chid);
		$forward = empty($forward) ? M_REFERER : $forward;
		$forwardstr = '&forward='.urlencode($forward);
		if(!submitcheck('bfarchiveadd')){
			$a_field = new cls_field;
			tabheader(lang('add').$fcatalog['title'],'farchiveadd',"?action=farchiveadd&fcaid=$fcaid$forwardstr",2,1,1,1);
			$submitstr = '';
			$subject_table = 'farchives';
			foreach($fields as $k => $field){
				if(!$field['isadmin'] && !$field['isfunc']){
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
					trbasic(lang($var),"farchiveadd[$var]",'','calendar');
					$submitstr .= makesubmitstr("farchiveadd[$var]",0,0,0,0,'date');
				}
			}
			$submitstr .= tr_regcode('farchive');//显示验证码
			tabfooter('bfarchiveadd');
			check_submit_func($submitstr);
		}else{
			if(!regcode_pass('farchive',empty($regcode) ? '' : trim($regcode))) mcmessage('safecodeerr',axaction(2,M_REFERER));
			$c_upload = new cls_upload;	
			$fields = fields_order($fields);
			$a_field = new cls_field;
			$sqlcommon = "fcaid='$fcaid',chid='$chid',mid='".$curuser->info['mid']."',mname='".$curuser->info['mname']."',createdate='$timestamp',updatedate='$timestamp'";
			$sqlcustom = "";
			foreach($fields as $k => $v){
				if(!$v['isadmin'] && !$v['isfunc']){
					$a_field->init();
					$a_field->field = $v;
					$a_field->deal('farchiveadd');
					if(!empty($a_field->error)){
						$c_upload->rollback();
						mcmessage($a_field->error,axaction(2,M_REFERER));
					}
					$qvar = $v['issystem'] ? 'sqlcommon' : 'sqlcustom';
					$$qvar .= ($$qvar ? ',' : '')."$k='".$a_field->newvalue."'";
					if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $$qvar .= ($$qvar ? ',' : '').$k.'_'.$x."='$y'";
				}
			}
			unset($a_field);
			if(empty($fcatalog['nodurat'])){
				foreach(array('startdate','enddate') as $var){
					$farchiveadd[$var] = trim($farchiveadd[$var]);
					$farchiveadd[$var] = !isdate($farchiveadd[$var]) ? 0 : strtotime($farchiveadd[$var]);
					$sqlcommon .= ",$var='".max(0,intval($farchiveadd[$var]))."'";
				}
			}
			$db->query("INSERT INTO {$tblprefix}farchives SET ".$sqlcommon);
			if(!($aid = $db->insert_id())){
				$c_upload->closure(1);
				mcmessage('msgsaveerr',axaction(2,M_REFERER));
			}else{
				$c_upload->closure(1, $aid, 'farchives');
				$sqlcustom = "aid=$aid".($sqlcustom ? ','.$sqlcustom : '');
				$db->query("INSERT INTO {$tblprefix}farchives_$chid SET ".$sqlcustom);
				$aedit = new cls_farcedit;
				$aedit->set_aid($aid);
				$fcatalog['autocheck'] && $aedit->arc_check(1,0);
				$aedit->updatedb();
				unset($aedit);
			}
			$c_upload->saveuptotal(1);
			mcmessage('freeinfoaddfinish',axaction(10,$forward));
		
		}
	}else include(M_ROOT.$fcatalog['ucadd']);
}
?>
