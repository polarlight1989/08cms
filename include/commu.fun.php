<?php
!defined('M_COM') && exit('No Permission');
load_cache('commus');
function cu_addfields($chid=0,$cuid=0){
	global $db,$tblprefix,$commus,$enableship,$enablestock;
	if(!$chid || !$cuid || empty($commus[$cuid])) return;
	$cufields = array();
	if($commus[$cuid]['cclass'] == 'answer'){
		$cufields['currency'] = array (
		  'cname' => lang('rewarcurrenval'),
		  'mcommon' => '0',
		  'available' => '1',
		  'tbl' => 'main',
		  'innertext' => '',
		  'issearch' => '2',
		  'length' => '10',
		  'datatype' => 'int',
		  'notnull' => '1',
		  'mlimit' => 'number',
		) ;
		$cufields['question'] = array (
		  'cname' => lang('question'),
		  'mcommon' => '0',
		  'available' => '1',
		  'tbl' => 'custom',
		  'innertext' => '',
		  'issearch' => '0',
		  'length' => '2000',
		  'datatype' => 'multitext',
		  'notnull' => '1',
		  'mlimit' => '0',
		) ;
	}elseif($commus[$cuid]['cclass'] == 'purchase'){
		$cufields['price'] = array (
		  'cname' => lang('price'),
		  'mcommon' => '0',
		  'available' => '1',
		  'tbl' => 'main',
		  'innertext' => '',
		  'issearch' => '2',
		  'length' => '',
		  'datatype' => 'float',
		  'notnull' => '0',
		  'mlimit' => '',
		) ;
		$cufields['storage'] = array (
		  'cname' => lang('stock'),
		  'mcommon' => '0',
		  'available' => $enablestock ? 1 : 0,
		  'tbl' => 'sub',
		  'innertext' => '',
		  'issearch' => '0',
		  'length' => '10',
		  'datatype' => 'int',
		  'notnull' => '0',
		  'mlimit' => 'number',
		) ;
	}
	foreach($cufields as $k => $field){
		$db->query("INSERT INTO {$tblprefix}fields SET 
		ename='$k', 
		cname='$field[cname]', 
		chid='$chid', 
		issystem='1', 
		iscustom='0', 
		mcommon='$field[mcommon]', 
		available='$field[available]', 
		tbl='$field[tbl]', 
		issearch='$field[issearch]', 
		innertext='$field[innertext]', 
		length='$field[length]', 
		datatype='$field[datatype]', 
		notnull='$field[notnull]', 
		mlimit='$field[mlimit]' 
		");
	}	
}

function cu_fields_deal($cuid=0,$varpre='archivenew',&$oldarr){
	global $$varpre,$currencys,$commus,$enablestock,$useredits,$curuser;
	if(!$cuid || !($commu = read_cache('commu',$cuid))) return '';
	$freeupdate = empty($oldarr['checked']) || empty($useredits) || $curuser->check_allow('freeupdatecheck');
	if($commu['cclass'] == 'answer'){
		if(isset(${$varpre}['question'])){
			if($freeupdate || in_array('question',$useredits)){
				${$varpre}['question'] = trim(${$varpre}['question']);
				if(empty(${$varpre}['question'])) return lang('questcontnotn');
			}
		}
		if(isset(${$varpre}['currency'])){
			if($freeupdate || in_array('currency',$useredits)){
				if(empty($commu['setting']['crid']) || empty($currencys[$commu['setting']['crid']])) return lang('choose_reward_cutype');
				if(isset($oldarr['crid']) && $commu['setting']['crid'] != $oldarr['crid']) return lang('rewcurtychdomoarc');
				${$varpre}['crid'] = $commu['setting']['crid'];
				${$varpre}['currency'] = max(0,intval(${$varpre}['currency']));
				if(!empty($oldarr['checked']) && isset($oldarr['currency']) && ${$varpre}['currency'] < $oldarr['currency']) return lang('dontredrewcur');
				if(!empty($commu['setting']['mini']) && ${$varpre}['currency'] < $commu['setting']['mini']) return lang('recusmmiva');
				if(!empty($commu['setting']['max']) && ${$varpre}['currency'] > $commu['setting']['max']) ${$varpre}['currency'] = $commu['setting']['max'];
			}
		}
	}elseif($commu['cclass'] == 'purchase'){
		if(isset(${$varpre}['price'])){
			if($freeupdate || in_array('price',$useredits)){
				${$varpre}['price'] = max(0,round(${$varpre}['price'],2));
			}
		}
		if(isset(${$varpre}['storage'])){
			if($freeupdate || in_array('storage',$useredits)){
				$enablestock && ${$varpre}['storage'] = max(0,intval(${$varpre}['storage']));
			}
		}
	}
	return '';
}

function cu_sqls_deal($cuid=0,&$nowarr,&$sqlmain,&$sqlsub,&$sqlcustom){//添加时不可取消
	global $commus;
	if(!$cuid || empty($commus[$cuid])) return;
	if($commus[$cuid]['cclass'] == 'answer'){
		$sqlmain .= ($sqlmain ? ',' : '')."crid='".$nowarr['crid']."'";	//??????????????????????????????
	}
}
?>