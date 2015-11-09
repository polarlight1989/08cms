<?
$catalogs = read_cache('catalogs');
 
$urlnavtitle = 'Student Work';
$caid = $caid ? $caid : 34;
$urlskey = 'caid'.$caid;
$chid = 6;
$urlsarr = array();
$cpid = 33;
foreach($catalogs as $v){
	if($v['pid'] == $cpid){
		$urlsarr['caid'.$v['caid']] = array($v['title'],"?entry=extend&extend=en_works&caid=".$v['caid']."&chid=$chid");
	}
}
 
include 'arcs.php';
?>
