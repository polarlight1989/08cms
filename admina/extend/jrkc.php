<?
$urlnavtitle = '假日课程';
$caid = $caid ? $caid : 12;
$urlskey = 'caid'.$caid;
$chid = 5;
$urlsarr = array();
$urlsarr['caid12'] = array('暑假',"?entry=extend&extend=jrkc&caid=12&chid=$chid");
$urlsarr['caid13'] = array('十一长假',"?entry=extend&extend=jrkc&caid=13&chid=$chid");
$urlsarr['caid14'] = array('圣诞',"?entry=extend&extend=jrkc&caid=14&chid=$chid");
$urlsarr['caid15'] = array('寒假',"?entry=extend&extend=jrkc&caid=15&chid=$chid");
$urlsarr['caid16'] = array('复活节',"?entry=extend&extend=jrkc&caid=16&chid=$chid");
include 'arcs.php';
?>
