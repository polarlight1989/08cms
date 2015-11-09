<?
//会员举报添加。
!defined('M_COM') && exit('No Permission');
load_cache('currencys,mbfields,mchannels');
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/upload.cls.php";
$mid = empty($mid) ? 0 : max(0,intval($mid));
$cid = empty($cid) ? 0 : max(0,intval($cid));
$cuid=6;
//$amode = member==$mid ? 0 : 1;
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
if(!$mid) mcmessage('choosereportobject',$forward);

$actuser = new cls_userinfo;
$actuser->activeuser($mid);
if(!$actuser->info['mid']) mcmessage('choosemember');

if(!($mcommu = read_cache('mcommu',$cuid))) mcmessage('psmci');
if(empty($mcommu['available'])) mcmessage('mrfc',$forward);

$fieldsarr = empty($mcommu['setting']['fields']) ? array() : explode(',',$mcommu['setting']['fields']);
if(empty($cid)){//新加
	//分析是否允许重复添加
	if($cid = $db->result_one("SELECT cid FROM {$tblprefix}mreports WHERE mid='$mid' AND fromid='$memberid' ORDER BY cid")) mcmessage('pdrar',"?action=mreport&mid=$mid&cid=$cid$forwardstr");
	if(!$oldmsg = $db->fetch_one("SELECT * FROM {$tblprefix}mreports WHERE fromid='$memberid' ORDER BY cid DESC LIMIT 0,1")) $oldmsg = array();//读出旧记录
	tabheader(lang('addmember').$mcommu['cname'],'reportadd',"{$mspaceurl}report.php?mid=$mid$forwardstr",2,1,1);
	$submitstr = '';

	trbasic(lang('lookaddobject'),'',"<a href=\"{$mspaceurl}index.php?mid=$mid\" target=\"_blank\">>>&nbsp; ".$actuser->info['mname']."</a>",'');
	$submitstr .= tr_regcode('report');
	$a_field = new cls_field;
	foreach($mbfields as $k => $v){
		if(!$v['isadmin'] && !$v['isfunc'] && in_array($k,$fieldsarr)){
			$a_field->init();
			$a_field->field = $v;
			if(isset($oldmsg[$k])){
				$a_field->oldvalue = $oldmsg[$k];
			}else $a_field->isadd = 1;
			$a_field->trfield('reportnew','','mb');
			$submitstr .= $a_field->submitstr;
		}
	}
	unset($a_field);
	tabfooter('newcommu');
	check_submit_func($submitstr);

}else{//修改
	if(!$reportold = $db->fetch_one("SELECT * FROM {$tblprefix}mreports WHERE mid='$mid' AND fromid='$memberid' AND cid='$cid'")) mcmessage('choosereport',$forward);
	if(!submitcheck('breportdetail')){
		tabheader($mcommu['cname'].'&nbsp; -&nbsp; '.lang('basemessage'),'reportdetail',"?action=mreport&mid=$mid&cid=$cid$forwardstr",2,1,1);
		$submitstr = '';
		trbasic(lang('lookreportobject'),'',"<a href=\"{$mspaceurl}index.php?mid=$mid\" target=\"_blank\">>>&nbsp; ".$reportold['mname']."</a>",'');
		trbasic(lang('addtime'),'',date('Y-m-d H:i',$reportold['createdate']),'');
		tabfooter();

		$submitstr .= tr_regcode('report');
		$a_field = new cls_field;
		tabheader($mcommu['cname'].'&nbsp; -&nbsp; '.lang('submitmessage'));
		foreach($mbfields as $k => $v){
			if(!$v['isadmin'] && !$v['isfunc'] && in_array($k,$fieldsarr)){
				$a_field->init();
				$a_field->field = $v;
				$a_field->oldvalue = isset($reportold[$k]) ? $reportold[$k] : '';
				$a_field->trfield('reportnew','','mb');
				$submitstr .= $a_field->submitstr;
			}
		}
		unset($a_field);

		tabfooter('breportdetail','',strbutton('','goback',"redirect('$forward');"));
		check_submit_func($submitstr);
	}else{
		$c_upload = new cls_upload;	
		$mbfields = fields_order($mbfields);
		$sqlstr = '';
		$a_field = new cls_field;
		foreach($mbfields as $k => $v){
			if(!$v['isadmin'] && !$v['isfunc'] && in_array($k,$fieldsarr)){
				$a_field->init();
				$a_field->field = $v;
				$a_field->oldvalue = isset($reportold[$k]) ? $reportold[$k] : '';
				$a_field->deal('reportnew');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					mcmessage($a_field->error,M_REFERER);
				}
				$sqlstr .= ($sqlstr ? ',' : '')."$k='".$a_field->newvalue."'";
				if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ($sqlstr ? ',' : '').$k.'_'.$x."='$y'";
			}
		}
		unset($a_field);
		$c_upload->closure(1, $cid, 'mreports');
		$c_upload->saveuptotal(1);
		$db->query("UPDATE {$tblprefix}mreports SET
			$sqlstr
			WHERE cid='$cid'");
		//处理函数字段
		$sqlstr = '';
		foreach($mbfields as $k => $v){
			if($v['isfunc'] && in_array($k,$fieldsarr)){
				//得到原始数据的资料，带上当前文档资料
				if(!isset($sourcearr)){
					$sourcearr = $db->fetch_one("SELECT * FROM {$tblprefix}mreports WHERE cid='$cid'");
				}
				$sqlstr .= ($sqlstr ? ',' : '')."$k='".field_func($v['func'],$sourcearr,$arr2='')."'";
			}
		}
		unset($sourcearr);
		$sqlstr && $db->query("UPDATE {$tblprefix}mreports SET $sqlstr WHERE cid='$cid'");

		//处理自定义函数
		if(!empty($mcommu['func'])){//可以处理所有参数的变更
			$sourcearr = $db->fetch_one("SELECT * FROM {$tblprefix}mreports WHERE cid='$cid'");
			field_func($mcommu['func'],$sourcearr,$arr2='');
			unset($sourcearr);
		}		

		mcmessage('nameadminfin',$forward,$mcommu['cname']);
	}

}
?>