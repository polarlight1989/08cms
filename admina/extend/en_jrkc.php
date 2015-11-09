<?
$catalogs = read_cache('catalogs');
$urlnavtitle = '假日课程';
$caid = $caid ? $caid : 26;
$urlskey = 'caid'.$caid;
$chid = 5;
$urlsarr = array();
$cpid = 24;
foreach($catalogs as $v){
	if($v['pid'] == $cpid){
		$urlsarr['caid'.$v['caid']] = array($v['title'],"?entry=extend&extend=en_jrkc&caid=".$v['caid']."&chid=$chid");
	}
}
include 'arcs.php';
?>
