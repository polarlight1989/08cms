<?php
define('M_ANONYMOUS', TRUE);
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
$mode = empty($mode) ? 'arc' : trim($mode);
if(!$enablestatic) mexit();//没有启用静态
include_once M_ROOT.'./include/common.fun.php';
if($mode == 'arc'){
	include_once M_ROOT.'./include/arc_static.fun.php';
	if(!$aid = max(0,intval(@$aid))) mexit();
	$addno = empty($addno) ? 0 : max(0,intval($addno));
	if(!$debugtag){
		if(!$needstatics = $db->result_one("SELECT needstatics FROM {$tblprefix}archives_sub WHERE aid='$aid'")) mexit();
		if(!($needstatics = explode(',',$needstatics)) || empty($needstatics[$addno]) || $needstatics[$addno] > $timestamp) mexit();
	}
	arc_static($aid,$addno,1);
}elseif($mode == 'cnindex'){
	include_once M_ROOT.'./include/cn_static.fun.php';
	parse_str($_SERVER['QUERY_STRING'],$temparr);
	$cnstr = cnstr($temparr);
	$addno = max(0,intval(@$addno));
	if(!$debugtag){
		if($cnstr){
			if(!$needstatics = $db->result_one("SELECT needstatics FROM {$tblprefix}cnodes WHERE ename='$cnstr' AND sid='$sid'")) mexit();
			if(!($needstatics = explode(',',$needstatics)) || empty($needstatics[$addno]) || $needstatics[$addno] > $timestamp) mexit();
		}elseif($sid){
			if(!$needstatic = $db->result_one("SELECT ineedstatic FROM {$tblprefix}subsites WHERE sid='$sid'")) mexit();
		}else{
			if(!$needstatic = $db->result_one("SELECT value FROM {$tblprefix}mconfigs WHERE varname='ineedstatic'")) mexit();
		}
	}
	index_static($cnstr,$addno,1);
}elseif($mode == 'mcnode'){
	include_once M_ROOT.'./include/mcn_static.fun.php';
	parse_str($_SERVER['QUERY_STRING'],$temparr);
	$cnstr = mcnstr($temparr);
	$addno = max(0,intval(@$addno));
	if(!$debugtag){
		if(!$cnstr){
			if(!$needstatic = $db->result_one("SELECT value FROM {$tblprefix}mconfigs WHERE varname='mcnneedstatic'")) mexit();
		}else{
			if(!$needstatics = $db->result_one("SELECT needstatics FROM {$tblprefix}mcnodes WHERE ename='$cnstr'")) mexit();
			if(!($needstatics = explode(',',$needstatics)) || empty($needstatics[$addno]) || $needstatics[$addno] > $timestamp) mexit();
		}
	}
	mindex_static($cnstr,$addno,1);
}
mexit();

?>
