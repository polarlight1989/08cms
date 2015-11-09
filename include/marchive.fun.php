<?php
!defined('M_COM') && exit('No Permission');
function marc_parse(&$item){
	global $cms_abs;
	view_marcurl($item);
	$item['cms_counter'] = "<script type=\"text/javascript\" src=\"".$cms_abs."counter.php?maid=".$item['maid']."&matid=".$item['matid']."\"></script>";
	arr_tag2atm($item,'ma');
}
?>