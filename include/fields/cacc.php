<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
$datatype = 'cacc';
$flength = !isset($fieldnew['length']) ? $field['length'] : $fieldnew['length'];
if(!$fsave){
	load_cache('permissions,cotypes');
	trbasic(lang('field_type'),'',$datatypearr[$datatype],'');
	if($fnew) trhidden('fieldnew[datatype]',$datatype);
	trbasic(lang('field_cname'),'fieldnew[cname]',empty($field['cname']) ? '' : $field['cname']);
	$submitstr .= makesubmitstr('fieldnew[cname]',1,0,0,30);
	trbasic(lang('field_ename'),$fnew ? 'fieldnew[ename]' : '',empty($field['ename']) ? '' : $field['ename'],$fnew ? 'text' : '');
	if($fnew) $submitstr .= makesubmitstr('fieldnew[ename]',1,'tagtype',0,30);
	!in_array($fmode,array('cn')) && empty($field['issystem']) && trbasic(lang('field_pmid'),'fieldnew[pmid]',makeoption(pmidsarr('field',lang('frommsg')),empty($field['pmid']) ? 0 : $field['pmid']),'select');
	$coidsarr = array('0' => lang('catalog'));
	foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
	trbasic(lang('sourcecotype'),'',@$coidsarr[$flength],'');
	if($fnew) trhidden('fieldnew[length]',$flength);
	$setting = empty($field['innertext']) ? array() : unserialize($field['innertext']);
	if($flength){
		$sourcearr = array('0' => lang('allcoclass'));
		$sourcearr['1'] = lang('handpoint');
		sourcemodule(lang('cocllimi'),
					'settingnew[source]',
					$sourcearr,
					empty($setting['source']) ? '0' : $setting['source'],
					'1',
					'settingnew[ids]',
					ccidsarr($flength),
					empty($setting['ids']) ? array() : explode(',',$setting['ids'])
		);
	}else{
		load_cache('subsites,acatalogs');
		$catalogs = &$acatalogs;
		$sourcearr = array('0' => lang('all_catalog'),'2' => lang('msite').lang('all_catalog'));
		foreach($subsites as $k => $v) $sourcearr['-'.$k] = $v['sitename'].lang('all_catalog');
		$sourcearr['1'] = lang('handpoint');
		sourcemodule(lang('cataloglimi'),
					'settingnew[source]',
					$sourcearr,
					empty($setting['source']) ? '0' : $setting['source'],
					'1',
					'settingnew[ids]',
					caidsarr($catalogs),
					empty($setting['ids']) ? array() : explode(',',$setting['ids'])
		);
	}
	$relatearr = array(0 => lang('schoise'),2 => lang('smax2'),3 => lang('smax3'),4 => lang('smax4'),5 => lang('smax5'));
	if(!$fnew && in_array($fmode,array('a','m')) && $field['mcommon']){
		trbasic(lang('frelatecaid'),'',$relatearr[empty($field['max']) ? 0 : $field['max']],'');
	}else trbasic(lang('frelatecaid'),'',makeradio('fieldnew[max]',$relatearr,empty($field['max']) ? 0 : $field['max']),'',lang('agrelatecaid'));
	
	$vmodearr = array('0' => lang('vmode0'),'3' => lang('vmode3'),'4' => lang('vmode4'),);
	trbasic(lang('coclassvmode'),'',makeradio('fieldnew[mode]',$vmodearr,empty($field['mode']) ? 0 : $field['mode']),'');
	trbasic(lang('input_notnull'),'fieldnew[notnull]',empty($field['notnull']) ? 0 : $field['notnull'],'radio');
	trbasic(lang('form_guide'),'fieldnew[guide]',empty($field['guide']) ? '' : $field['guide'],'btext',lang('agguide'));
	$submitstr .= makesubmitstr('fieldnew[guide]',0,0,0,80);
	trbasic(lang('default_value'),'fieldnew[vdefault]',empty($field['vdefault']) ? '' : str_replace(",",'[##]',$field['vdefault']),'btext',lang('agmselectsplit'));
	if(in_array($fmode,array('a','i','m','im','ma')) && ($fnew || !empty($field['iscustom']))){
		$issearcharr = array('0' => lang('nosearch'),'1' => lang('onesearch'),'2' => lang('soninsearch'));
		trbasic(lang('issearch'),'',makeradio('fieldnew[issearch]',$issearcharr,empty($field['issearch']) ? '0' : $field['issearch']),'');
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
		$fieldnew['length'] = empty($fieldnew['length']) ? 0 : max(0,intval($fieldnew['length']));//在这里是类系选择
		$fieldnew['max'] = select_fnew($fieldnew['max']);
		$db->query("ALTER TABLE $fconfigarr[altertable] ADD $fieldnew[ename] ".($fieldnew['max'] ? "varchar(30) NOT NULL default ''" : "smallint(6) unsigned NOT NULL default 0"));
	}else{
		$fieldnew['cname'] = empty($fieldnew['cname']) ? $field['cname'] : $fieldnew['cname'];
		if(isset($fieldnew['max'])){
			$fieldnew['max'] = empty($fieldnew['max']) ? 0 : max(2,intval($fieldnew['max']));
			if(!select_alter($fieldnew['max'],$field['max'],$field['ename'],$fconfigarr['altertable'])) $fieldnew['max'] = $field['max'];
		}
	}
	$fieldnew['innertext'] = empty($settingnew) ? array() : addslashes(serialize($settingnew));
	$fieldnew['guide'] = empty($fieldnew['guide']) ? '' : trim($fieldnew['guide']);
	$fieldnew['vdefault'] = empty($fieldnew['vdefault']) ? '' : trim($fieldnew['vdefault']);
	$fieldnew['vdefault'] = str_replace('[##]',",",$fieldnew['vdefault']);
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
