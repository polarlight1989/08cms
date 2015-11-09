<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('other') || amessage('no_apermission');
if($sid && $sid_self) amessage('msiteadmitem');
if(!submitcheck('bbadwordsadd') && !submitcheck('bbadwordsedit')){
	tabheader(lang('add_badword'),'badwordsadd','?entry=badwords');
	trbasic(lang('badword'),'badwordadd[wsearch]');
	trbasic(lang('rword'),'badwordadd[wreplace]');
	tabfooter('bbadwordsadd',lang('add'));

	tabheader(lang('badword_manager'),'badwordsedit','?entry=badwords','3');
	trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),lang('badword'),lang('rword')));
	$query = $db->query("SELECT * FROM {$tblprefix}badwords ORDER BY bwid");
	while($badword = $db->fetch_array($query)){
		echo "<tr class=\"txt\">".
			"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$badword[bwid]]\" value=\"$badword[bwid]\"></td>\n".
			"<td class=\"txtC\"><input type=\"text\" size=\"40\" name=\"badwordsnew[$badword[bwid]][wsearch]\" value=\"$badword[wsearch]\"></td>\n".
			"<td class=\"txtC\"><input type=\"text\" size=\"40\" name=\"badwordsnew[$badword[bwid]][wreplace]\" value=\"$badword[wreplace]\"></td></tr>\n";
	}
	tabfooter('bbadwordsedit',lang('modify'));
	a_guide('badwords');
}elseif(submitcheck('bbadwordsadd')){
	if(!trim($badwordadd['wsearch'])) {
		amessage('datamissing', '?entry=badwords');
	}
	if(trim($badwordadd['wsearch']) == trim($badwordadd['wreplace'])) {
		amessage('badwordsamerword', '?entry=badwords');
	}
	$badwordadd['wsearch'] = trim($badwordadd['wsearch']);
	$badwordadd['wreplace'] = trim($badwordadd['wreplace']);
	$db->query("INSERT INTO {$tblprefix}badwords SET 
				wsearch='$badwordadd[wsearch]',
				wreplace='$badwordadd[wreplace]'
				");
	adminlog(lang('add_badword'));
	updatecache('badwords');
	amessage('badwordaddfinish', '?entry=badwords');

}elseif(submitcheck('bbadwordsedit')){
	if(isset($delete)){
		foreach($delete as $k){
			$db->query("DELETE FROM {$tblprefix}badwords WHERE bwid=$k");
			unset($badwordsnew[$k]);
		}
	}
	if(isset($badwordsnew)){
		foreach($badwordsnew as $bwid => $badwordnew){
			if($badwordnew['wsearch'] && ($badwordnew['wsearch'] != $badwordnew['wreplace'])){
				$db->query("UPDATE {$tblprefix}badwords SET
							wsearch='$badwordnew[wsearch]',
							wreplace='$badwordnew[wreplace]'
							WHERE bwid=$bwid");
			}
		}
	}
	adminlog(lang('edit_badword_mlist'));
	updatecache('badwords');
	amessage('badwordmodifyfinish', '?entry=badwords');
}
?>
