<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
$datatype = 'vote';
$isfunc = $fnew ? (isset($fieldnew['isfunc']) ? $fieldnew['isfunc'] : 0) : (isset($field['isfunc']) ? $field['isfunc'] : 0);
if(!$fsave){
	load_cache('permissions');
	trbasic(lang('field_type'),'',$datatypearr[$datatype],'');
	if($fnew){
		echo "<input type=\"hidden\" name=\"fieldnew[datatype]\" value=\"$datatype\">\n";
		echo "<input type=\"hidden\" name=\"fieldnew[isfunc]\" value=\"$isfunc\">\n";
	}
	trbasic(lang('field_cname'),'fieldnew[cname]',empty($field['cname']) ? '' : $field['cname']);
	$submitstr .= makesubmitstr('fieldnew[cname]',1,0,0,30);
	trbasic(lang('field_ename'),$fnew ? 'fieldnew[ename]' : '',empty($field['ename']) ? '' : $field['ename'],$fnew ? 'text' : '');
	if($fnew) $submitstr .= makesubmitstr('fieldnew[ename]',1,'tagtype',0,30);
	!in_array($fmode,array('cn')) && empty($field['issystem']) && trbasic(lang('field_pmid'),'fieldnew[pmid]',makeoption(pmidsarr('field',lang('frommsg')),empty($field['pmid']) ? 0 : $field['pmid']),'select');
	trbasic(lang('input_notnull'),'fieldnew[notnull]',empty($field['notnull']) ? 0 : $field['notnull'],'radio');
	trbasic(lang('form_guide'),'fieldnew[guide]',empty($field['guide']) ? '' : $field['guide'],'btext',lang('agguide'));
	$submitstr .= makesubmitstr('fieldnew[guide]',0,0,0,80);
	trbasic(lang('maxvote'),'fieldnew[min]',empty($field['min']) ? 1 : $field['min'],'text');
	$submitstr .= makesubmitstr('fieldnew[min]',1,'int',1,5,'int');
	trbasic(lang('maxvote'),'fieldnew[max]',empty($field['max']) ? 1 : $field['max'],'text');
	$submitstr .= makesubmitstr('fieldnew[max]',1,'int',1,10,'int');
	trbasic(lang('fornouservote'),'fieldnew[nohtml]',empty($field['nohtml']) ? 0 : $field['nohtml'],'radio');
	trbasic(lang('cannotrepevote'),'fieldnew[mode]',empty($field['mode']) ? 0 : $field['mode'],'radio');
	trbasic(lang('reptimintmin'),'fieldnew[length]',empty($field['length']) ? '' : $field['length'],'text');
	$submitstr .= makesubmitstr('fieldnew[length]',0,'int',0,300,'int');
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
		$db->query("ALTER TABLE $fconfigarr[altertable] ADD $fieldnew[ename] text NOT NULL default ''");
	}else{
		$fieldnew['cname'] = empty($fieldnew['cname']) ? $field['cname'] : $fieldnew['cname'];
	}
	$fieldnew['guide'] = empty($fieldnew['guide']) ? '' : trim($fieldnew['guide']);
	$fieldnew['vdefault'] = empty($fieldnew['vdefault']) ? '' : trim($fieldnew['vdefault']);
	foreach(array('min','max') as $key){
		$fieldnew[$key] = max(1,intval($fieldnew[$key]));
	}
	$fieldnew['regular'] = empty($fieldnew['regular']) ? '' : trim($fieldnew['regular']);
	foreach(array('datatype','ename','length','cname','notnull','nohtml','mode','guide','mlimit','rpid','issearch','innertext','min','max','regular','isfunc','func','vdefault','pmid','custom_1','custom_2',) as $var){
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
