<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/arcedit.cls.php';
$forward = empty($forward) ? M_REFERER : $forward;

$aid = empty($aid) ? 0 : max(0,intval($aid));
!$aid && cumessage('choosearchive');
!$memberid && cumessage('nousernofavoritepermis',$forward);
!($commu = read_cache('commu',4)) && cumessage('choosecommuitem');
if(empty($commu['ucadd'])){
	!$curuser->pmbypmids('cuadd',$commu['setting']['apmid']) && cumessage('younoitempermis',$forward);
	$aedit = new cls_arcedit();
	$aedit->set_aid($aid);
	$aedit->basic_data();
	!$aedit->aid && cumessage('choosearchive');
	!$aedit->archive['checked'] && cumessage('poinarcnoche',$forward); 
	
	$curuser->sub_data();
	if(!empty($commu['setting']['max']) && $curuser->info['favorites'] >= $commu['setting']['max']){
		cumessage('favoriteamooverlimit',$forward);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}favorites WHERE mid='$memberid' AND aid='$aid'")){
		cumessage('archivealreadyfavorite',$forward);
	}
	$abnew = empty($a_edit->archive['abnew']) ? 0 : $a_edit->archive['abnew'];
	$db->query("INSERT INTO {$tblprefix}favorites SET mid='$memberid',aid='$aid',createdate='$timestamp',abnew='$abnew'",'SILENT');//加入收藏记录
	$aedit->arc_nums('favorites',1,1);
	$curuser->basedeal('favorite',1,1,1);
	cumessage($inajax ? 'succeed' : 'favoritesucceed',$forward);
}else include(M_ROOT.$commu['ucadd']);
?>