<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/archive.cls.php';
include_once M_ROOT.'./include/follow.fun.php';
$aid = empty($aid) ? 0 : max(0,intval($aid));
!$aid && message('choosearchive');
$tname = empty($tname) ? '' : trim($tname);
empty($tname) && message('poiattaconsoufie');
$temparr = array();
$temparr['tname'] = $tname;
$temparr['tmode'] = empty($tmode) ? 0 : 1;
$temparr['fid'] = empty($fid) ? 0 : max(0,intval($fid));
$arc = new cls_archive();
follow_dynamic($aid,'media',$temparr);
?>
