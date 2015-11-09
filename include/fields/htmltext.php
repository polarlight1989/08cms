<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
$datatype = 'htmltext';
$isfunc = $fnew ? (isset($fieldnew['isfunc']) ? $fieldnew['isfunc'] : 0) : (isset($field['isfunc']) ? $field['isfunc'] : 0);
if(!$fsave){
	load_cache('permissions');
	trbasic(lang('field_type'),'',$datatypearr[$datatype],'');
	if($fnew){
		echo "<input type=\"hidden\" name=\"fieldnew[datatype]\" value=\"$datatype\">\n";
		echo "<input type=\"hidden\" name=\"fieldnew[isfunc]\" value=\"$isfunc\">\n";
	}
	if(in_array($fmode,array('i','a'))){//处理存文本字段
		if($fnew){
			trbasic(lang('istxt_field'),'fieldnew[istxt]',0,'radio');
		}else{
			trbasic(lang('istxt_field'),'',lang(empty($field['istxt']) ? 'no' : 'yes'),'');
		}
	}
	trbasic(lang('field_cname'),'fieldnew[cname]',empty($field['cname']) ? '' : $field['cname']);
	$submitstr .= makesubmitstr('fieldnew[cname]',1,0,0,30);
	trbasic(lang('field_ename'),$fnew ? 'fieldnew[ename]' : '',empty($field['ename']) ? '' : $field['ename'],$fnew ? 'text' : '');
	if($fnew) $submitstr .= makesubmitstr('fieldnew[ename]',1,'tagtype',0,30);
	if(!$isfunc){
		!in_array($fmode,array('cn')) && empty($field['issystem']) && trbasic(lang('field_pmid'),'fieldnew[pmid]',makeoption(pmidsarr('field',lang('frommsg')),empty($field['pmid']) ? 0 : $field['pmid']),'select');
		empty($field['issystem']) && trbasic(lang('input_notnull'),'fieldnew[notnull]',empty($field['notnull']) ? 0 : $field['notnull'],'radio');
		trbasic(lang('form_guide'),'fieldnew[guide]',empty($field['guide']) ? '' : $field['guide'],'btext',lang('agguide'));
		$submitstr .= makesubmitstr('fieldnew[guide]',0,0,0,80);
		trbasic(lang('controller_mode'),'fieldnew[mode]',makeradio('fieldnew[mode]',array(0 => lang('normal_editor'),1 => lang('simple_editor')),empty($field['mode']) ? '' : $field['mode']),'');
		trrange(lang('value_length'),array('fieldnew[min]',(!isset($field['min']) || $field['min'] == '') ? '' : $field['min'],'','&nbsp; -&nbsp; ',5),array('fieldnew[max]',(!isset($field['max']) || $field['max'] == '') ? '' : $field['max'],'','',5));
		trbasic(lang('remote_download'),'fieldnew[rpid]',makeoption($rpidsarr,empty($field['rpid']) ? '0' : $field['rpid']),'select');
	}
	if($isfunc){
		trbasic(lang('php_func'),'fieldnew[func]',empty($field['func']) ? '' : $field['func'],'textarea');
	}
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
		if(empty($fieldnew['istxt'])){
			$db->query("ALTER TABLE $fconfigarr[altertable] ADD $fieldnew[ename] text NOT NULL");
		}else $db->query("ALTER TABLE $fconfigarr[altertable] ADD $fieldnew[ename] varchar(30) NOT NULL default ''");
	}else{
		$fieldnew['cname'] = empty($fieldnew['cname']) ? $field['cname'] : $fieldnew['cname'];
	}
	if(!$isfunc){
		$fieldnew['guide'] = empty($fieldnew['guide']) ? '' : trim($fieldnew['guide']);
		foreach(array('min','max') as $key){
			$fieldnew[$key] = max(0,intval($fieldnew[$key]));
			$fieldnew[$key] = empty($fieldnew[$key]) ? '' : $fieldnew[$key];
		}
	}else{
		$fieldnew['func'] = empty($fieldnew['func']) ? '' : trim($fieldnew['func']);
	}
	foreach(array('datatype','ename','length','cname','notnull','nohtml','mode','guide','mlimit','rpid','issearch','innertext','min','max','regular','isfunc','func','vdefault','pmid','istxt','custom_1','custom_2',) as $var){
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
