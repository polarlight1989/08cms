<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('database') || amessage('no_apermission');
load_cache('initfields,channels');
$url_type = 'data';include 'urlsarr.inc.php';

if(empty($action)){
	url_nav(lang('dboperate'),$urlsarr,'txt');

	tabheader(lang('html_to_txt'),'','',5);
	trcategory(array(lang('sn'),lang('field_name'),lang('field_ename'),lang('table_to_txt'),lang('txt_to_table')));
	$i = 1;
	foreach($initfields as $k => $v){
		if($v['datatype'] == 'htmltext'){
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\">$i</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\">$k</td>\n".
				"<td class=\"txtC w120\">".(empty($v['istxt']) ? "<a href=\"?entry=fieldtotxt&action=totxt&fieldename=$k&chid=0\">>>".lang('start')."</a>" : '-')."</td>\n".
				"<td class=\"txtC w120\">".(!empty($v['istxt']) ? "<a href=\"?entry=fieldtotxt&action=tofield&fieldename=$k&chid=0\">>>".lang('start')."</a>" : '-')."</td>\n".
				"</tr>\n";
			$i ++;
		}
	}
	tabfooter();

	foreach($channels as $chid => $channel){
		$fields = read_cache('fields',$chid);
		foreach($fields as $k => $v){
			if($v['tbl'] != 'custom' || $v['datatype'] != 'htmltext') unset($fields[$k]);
		}
		if(!count($fields)) continue;
		tabheader(lang('htmltext_channel',$channel['cname']),'','',5);
		trcategory(array(lang('sn'),lang('field_name'),lang('field_ename'),lang('table_to_txt'),lang('txt_to_table')));
		$i = 1;
		foreach($fields as $k => $v){
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\">$i</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\">$k</td>\n".
				"<td class=\"txtC w120\">".(empty($v['istxt']) ? "<a href=\"?entry=fieldtotxt&action=totxt&fieldename=$k&chid=$chid\">>>".lang('start')."</a>" : '-')."</td>\n".
				"<td class=\"txtC w120\">".(!empty($v['istxt']) ? "<a href=\"?entry=fieldtotxt&action=tofield&fieldename=$k&chid=$chid\">>>".lang('start')."</a>" : '-')."</td>\n".
				"</tr>\n";
			$i ++;
		}
		tabfooter();
	}
	a_guide('fieldtotxt');
}elseif($action == 'totxt'){
	if(empty($confirm)){
		$message = lang('transto_txt_field !')."<br><br>";
		$message .= lang('confirmclick')."[<a href=?entry=fieldtotxt&action=totxt&fieldename=$fieldename&chid=$chid&confirm=1>".lang('start')."</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$message .= lang('giveupclick')."[<a href=?entry=fieldtotxt>".lang('goback')."</a>]";
		amessage($message);
	}
	if(empty($fieldename)) amessage('choosefield');
	if(empty($chid)){
		$fields = &$initfields;
		$customtable = 'archives';
	}else{
		$fields = read_cache('fields',$chid);
		$customtable = "archives_$chid";
	}
	if(empty($fields[$fieldename]) || $fields[$fieldename]['datatype'] != 'htmltext' || !empty($fields[$fieldename]['istxt'])) amessage('choosefield');
	$field = $fields[$fieldename];
	$query = $db->query("SELECT aid,$fieldename FROM {$tblprefix}$customtable");
	while($row = $db->fetch_array($query)){
		$namepre = saveastxt($row[$fieldename]);
		$db->query("UPDATE {$tblprefix}$customtable SET $fieldename='$namepre' WHERE aid='$row[aid]'");
	}
	$db->query("ALTER TABLE {$tblprefix}$customtable CHANGE $fieldename $fieldename varchar(30) NOT NULL default ''");
	if(empty($chid)){
		$db->query("UPDATE {$tblprefix}fields SET istxt='1' WHERE ename='$fieldename'");
		foreach($channels as $k => $v) updatecache('fields',$k);
	}else{
		$db->query("UPDATE {$tblprefix}fields SET istxt='1' WHERE ename='$fieldename' AND chid='$chid'");
		updatecache('fields',$chid);
	}
	amessage('operatesuc',"?entry=fieldtotxt");

}elseif($action == 'tofield'){
	if(empty($confirm)){
		$message = lang('transto_table_field !')."<br><br>";
		$message .= lang('confirmclick')."[<a href=?entry=fieldtotxt&action=tofield&fieldename=$fieldename&chid=$chid&confirm=1>".lang('start')."</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$message .= lang('giveupclick')."[<a href=?entry=fieldtotxt>".lang('goback')."</a>]";
		amessage($message);
	}
	if(empty($fieldename)) amessage('choosefield');
	if(empty($chid)){
		$fields = &$initfields;
		$customtable = 'archives';
	}else{
		$fields = read_cache('fields',$chid);
		$customtable = "archives_$chid";
	}
	if(empty($fields[$fieldename]) || $fields[$fieldename]['datatype'] != 'htmltext' || empty($fields[$fieldename]['istxt'])) amessage('choosefield');
	$field = $fields[$fieldename];
	$db->query("ALTER TABLE {$tblprefix}$customtable CHANGE $fieldename $fieldename text NOT NULL");
	$query = $db->query("SELECT aid,$fieldename FROM {$tblprefix}$customtable");
	while($row = $db->fetch_array($query)){
		$content = addslashes(readfromtxt($row[$fieldename]));
		$db->query("UPDATE {$tblprefix}$customtable SET $fieldename='$content' WHERE aid='$row[aid]'");
		@unlink(M_ROOT.'dynamic/htmltxt/'.$row[$fieldename].'.php');
	}
	if(empty($chid)){
		$db->query("UPDATE {$tblprefix}fields SET istxt='0' WHERE ename='$fieldename'");
		foreach($channels as $k => $v) updatecache('fields',$k);
	}else{
		$db->query("UPDATE {$tblprefix}fields SET istxt='0' WHERE ename='$fieldename' AND chid='$chid'");
		updatecache('fields',$chid);
	}
	amessage('operatesuc',"?entry=fieldtotxt");

}
?>
