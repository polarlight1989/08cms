<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
if(!empty($mnid)){
	$forward = empty($forward) ? M_REFERER : $forward;
	if(!submitcheck('bmguide')){
		load_cache('mmnlangs');
		$content = '';
		if(@include(M_ROOT."./dynamic/mguides/mguide_$mnid.php")) $content = $mguide;
		tabheader(lang('memcenpaggui'),'mguide',"?entry=mguides&mnid=$mnid&forward=".rawurlencode($forward),2,0,1);
		trbasic(lang('memcenpacna'),'',$mmnlangs["mmenuitem_$mnid"],'');
		trbasic(lang('guidecontent'),'contentnew',$content,'btextarea');
		tabfooter('bmguide');
		$submitstr = '';
		$submitstr .= makesubmitstr('contentnew',0,0,0,500);
		check_submit_func($submitstr);
		a_guide('mguides');
	}else{
		$contentnew = stripslashes(strip_tags(trim($contentnew)));
		mmkdir(M_ROOT.'./dynamic/mguides/');
		if(@$fp = fopen(M_ROOT."./dynamic/mguides/mguide_$mnid.php",'wb')){
			fwrite($fp,"<?php\n\$mguide = '".addcslashes($contentnew,'\'\\')."';\n?>");
			fclose($fp);
		}
		amessage('mecenpagusetfin',$forward);
	}
}
?>