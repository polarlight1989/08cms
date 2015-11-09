<?
$catalogs = read_cache('catalogs');
 
$urlnavtitle = '假日课程';
$caid = $caid ? $caid : 18;
$urlskey = 'caid'.$caid;
$chid = 6;
$urlsarr = array();
$cpid = 17;
foreach($catalogs as $v){
	if($v['pid'] == $cpid){
		$urlsarr['caid'.$v['caid']] = array($v['title'],"?entry=extend&extend=works&caid=".$v['caid']."&chid=$chid");
	}
}
 
include 'arcs.php';
?>
