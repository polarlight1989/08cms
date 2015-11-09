<?php
!defined('M_COM') && exit('No Permission');
load_cache('matypes');
include_once M_ROOT."./include/marcedit.cls.php";
if(empty($deal)){
	tabheader(lang('marchiveslist'),'','',10);
	trcategory(array('',lang('marctype'),lang('look'),lang('check'),lang('add'),lang('edit')));
	foreach($matypes as $k => $v){
		$row = $db->fetch_one("SELECT * FROM {$tblprefix}marchives_$k WHERE mid='$memberid'");
		$delstr = $row ? "<a href=\"?action=marchives&deal=del&matid=$k&maid=$row[maid]\">".lang('del').'</a>' : '-';
		$addstr = $row ? '-' : "<a href=\"?action=marchiveadd&matid=$k\">".lang('add').'</a>';
		$editstr = $row ? "<a href=\"?action=marchive&matid=$k&maid=$row[maid]\">".lang('edit').'</a>' : '-';
		$checkstr = empty($row['checked']) ? '-' : 'Y';
		$lookstr = '';
		if($row){
			view_marcurl($row);
			$lookstr = "<a href=\"$row[arcurl]\" target=\"_blank\">".lang('look').'</a>';
		}
		echo "<tr>\n".
			"<td class=\"item\" width=\"30\">$delstr</td>\n".
			"<td class=\"item2\">$v[cname]</td>\n".
			"<td class=\"item\">$lookstr</td>\n".
			"<td class=\"item\">$checkstr</td>\n".
			"<td class=\"item\">$addstr</td>\n".
			"<td class=\"item\">$editstr</td>\n".
			"</tr>\n";
	}
	tabfooter();
}elseif($deal == 'del'){
	if(empty($maid) || empty($matid)) mcmessage('selectmarchive',"?action=marchives");
	$aedit = new cls_marcedit;
	$aedit->set_id($maid,$matid,0);
	if($aedit->archive['mid'] != $memberid) mcmessage('selectyoumarc',"?action=marchives");
	if($aedit->archive['checked']) mcmessage('marcnotdel',"?action=marchives");
	$aedit->delete(1);
	unset($aedit);
	mcmessage('marcdelfin',"?action=marchives");
}

?>
