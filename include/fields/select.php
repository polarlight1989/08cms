<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
$datatype = 'select';
if(!$fsave){
	load_cache('permissions');
	trbasic(lang('field_type'),'',$datatypearr[$datatype],'');
	if($fnew) echo "<input type=\"hidden\" name=\"fieldnew[datatype]\" value=\"$datatype\">\n";
	trbasic(lang('field_cname'),'fieldnew[cname]',empty($field['cname']) ? '' : $field['cname']);
	$submitstr .= makesubmitstr('fieldnew[cname]',1,0,0,30);
	trbasic(lang('field_ename'),$fnew ? 'fieldnew[ename]' : '',empty($field['ename']) ? '' : $field['ename'],$fnew ? 'text' : '');
	if($fnew) $submitstr .= makesubmitstr('fieldnew[ename]',1,'tagtype',0,30);
	!in_array($fmode,array('cn')) && empty($field['issystem']) && trbasic(lang('field_pmid'),'fieldnew[pmid]',makeoption(pmidsarr('field',lang('frommsg')),empty($field['pmid']) ? 0 : $field['pmid']),'select');
	trbasic(lang('table_fieldlength'),'fieldnew[length]',empty($field['length']) ? '' : $field['length'],'text',lang('agtlength'));
	empty($field['issystem']) && trbasic(lang('input_notnull'),'fieldnew[notnull]',empty($field['notnull']) ? 0 : $field['notnull'],'radio');
	trbasic(lang('form_guide'),'fieldnew[guide]',empty($field['guide']) ? '' : $field['guide'],'btext',lang('agguide'));
	$submitstr .= makesubmitstr('fieldnew[guide]',0,0,0,80);
	trbasic(lang('default_value'),'fieldnew[vdefault]',empty($field['vdefault']) ? '' : $field['vdefault']);
	trbasic(lang('controller_mode'),'',makeradio('fieldnew[mode]',array(0 => lang('schoise_list'),1 => lang('schoise_box').'(radio)'),empty($field['mode']) ? 0 : 1),'');
	$submitstr .= makesubmitstr('fieldnew[length]',0,'int',0,3);
	if(in_array($fmode,array('a','i','m','im','ma')) && ($fnew || !empty($field['iscustom']))){
		$issearcharr = array('0' => lang('nosearch'),'1' => lang('onesearch'),'2' => lang('multisearch'));
		trbasic(lang('issearch'),'',makeradio('fieldnew[issearch]',$issearcharr,empty($field['issearch']) ? '0' : $field['issearch']),'');
	}
	$fromcodestr = '<br><input class="checkbox" type="checkbox" name="fieldnew[fromcode]" value="1"'.(empty($field['fromcode']) ? '' : ' checked').'>'.lang('fromcode');
	trbasic(lang('choose_content_set').$fromcodestr,'fieldnew[innertext]',empty($field['innertext']) ? '' : $field['innertext'],'textarea',lang('aginnertext'));
	trbasic(lang('custom_1'),'fieldnew[custom_1]',empty($field['custom_1']) ? '' : $field['custom_1'],'text',lang('agcustom_1'));
	trbasic(lang('custom_2'),'fieldnew[custom_2]',empty($field['custom_2']) ? '' : $field['custom_2'],'text',lang('agcustom_1'));
}else{
	$sqlstr = empty($fconfigarr['sqlstr']) ? "" : $fconfigarr['sqlstr'];
	$fieldnew['cname'] = trim(strip_tags($fieldnew['cname']));
	if($fnew){
		$filterstr = empty($fconfigarr['filterstr']) ? "/[^a-zA-Z_0-9]+|^[0-9_]+/" : $fconfigarr['filterstr'];
		(empty($fieldnew['ename']) || empty($fieldnew['cname'])) && amessage('field_data_miss',$fconfigarr['errorurl']);
		preg_match($filterstr,$fieldnew['ename']) && amessage('field_ename_illegal',$fconfigarr['errorurl']);
		$fieldnew['ename'] = strtolower($fieldnew['ename']);
		in_array($fieldnew['ename'], $fconfigarr['enamearr']) && amessage('field_ename_repeat',$fconfigarr['errorurl']);
		in_array($fieldnew['ename'], $fieldwords) && amessage('field_ename_notuse',$fconfigarr['errorurl']);
		$fieldnew['length'] = empty($fieldnew['length']) ? 10 : min(255,max(1,intval($fieldnew['length'])));
		$db->query("ALTER TABLE $fconfigarr[altertable] ADD $fieldnew[ename] varchar($fieldnew[length]) NOT NULL default ''");
	}else{
		$fieldnew['cname'] = empty($fieldnew['cname']) ? $field['cname'] : $fieldnew['cname'];
		if(isset($fieldnew['length'])){
			$fieldnew['length'] = empty($fieldnew['length']) ? 10 : min(255,max(1,intval($fieldnew['length'])));
			if($field['length'] != $fieldnew['length']){
				$db->query("ALTER TABLE $fconfigarr[altertable] CHANGE $field[ename] $field[ename] varchar($fieldnew[length]) NOT NULL default ''");
			}
		}
	}
	$fieldnew['fromcode'] = empty($fieldnew['fromcode']) ? 0 : 1;
	if(isset($fieldnew['innertext'])){
		$fieldnew['innertext'] = empty($fieldnew['fromcode']) ? str_replace("\r","",$fieldnew['innertext']) : trim($fieldnew['innertext']);
	}
	$fieldnew['guide'] = empty($fieldnew['guide']) ? '' : trim($fieldnew['guide']);
	$fieldnew['vdefault'] = empty($fieldnew['vdefault']) ? '' : trim($fieldnew['vdefault']);
	foreach(array('datatype','ename','length','cname','notnull','nohtml','mode','guide','mlimit','rpid','issearch','innertext','fromcode','min','max','regular','isfunc','func','vdefault','pmid','custom_1','custom_2',) as $var){
		isset($fieldnew[$var]) && $sqlstr .= (!$sqlstr ? '' : ',')."$var='".$fieldnew[$var]."'";
	}
	if($fnew){
		$db->query("INSERT INTO $fconfigarr[fieldtable] SET $sqlstr");
	}else{
		$wherestr = empty($fconfigarr['wherestr']) ? "WHERE ename='$field[ename]'" : $fconfigarr['wherestr'];
		$db->query("UPDATE $fconfigarr[fieldtable] SET $sqlstr $wherestr");
	}
}
?>
