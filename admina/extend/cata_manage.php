<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('catalog') || amessage('no_apermission');
load_cache('cotypes,channels,grouptypes,permissions,vcps,rprojects,cafields');
sys_cache('fieldwords');
load_cache('catalogs,mtpls',$sid);
cache_merge($channels,'channels',$sid);
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.fun.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/cnode.fun.php";
$catalog = read_cache('catalog',$caid,'',$sid);

if(!submitcheck('bcatalogdetail')){
		$submitstr = '';
		 
		$a_field = new cls_field;
		#$addfieldstr = $sid ? '' : ("&nbsp; &nbsp; >><a href=\"?entry=catalogs&action=cafieldsedit\">".lang('iscustom_catalog_field').'</a>');
		$allow = array_filter(explode(",",$allow));
		tabheader('页面内容设置'."&nbsp;&nbsp;[$catalog[title]]",'catalogdetail',"",2,1,1);
 
		 
		foreach($cafields as $field){
			if(!in_array($field['ename'],$allow)) continue;
			$a_field->init();
			$a_field->field = $field;
			$a_field->oldvalue = isset($catalog[$field['ename']]) ? $catalog[$field['ename']] : '';
			$a_field->trfield('catalognew','','ca');
			$submitstr .= $a_field->submitstr;
		}
		tabfooter('bcatalogdetail');
		check_submit_func($submitstr);
		a_guide('catalogdetail');
	}else{
		$forward = empty($forward) ? M_REFERER : $forward;
		 
		$catalognew['dirname'] = strtolower($catalognew['dirname']);
 
		 
		$c_upload = new cls_upload;	
		$cafields = fields_order($cafields);
		$a_field = new cls_field;
		$sqlstr = "";
		foreach($cafields as $k => $v){
			$a_field->init();
			$a_field->field = $v;
			$a_field->oldvalue = isset($catalog[$k]) ? $catalog[$k] : '';
			$a_field->deal('catalognew');
			if(!empty($a_field->error)){
				$c_upload->rollback();
				amessage($a_field->error,$forward);
			}
			$sqlstr .= ','.$k."='".$a_field->newvalue."'";
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
		}
		$c_upload->closure(1, $caid, 'catalogs');
		$c_upload->saveuptotal(1);
		unset($a_field,$c_upload);

		$leveldiff = $catalognew['level'] - $catalog['level'];
		 
		$db->query("UPDATE {$tblprefix}catalogs SET
		
			apmid=0$sqlstr
			WHERE caid='$caid'");
		adminlog(lang('detail_modify_catalog'));
		updatecache('catalogs','',$sid);
		amessage('catalogsetfinish', $forward);
	}