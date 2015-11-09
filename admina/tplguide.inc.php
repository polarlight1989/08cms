<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
load_cache('channels,altypes,fcatalogs,commus,mcommus,');
$arr = array(
"<a href=\"?entry=mconfigs&action=cfview$param_suffix#template\">".lang(($sid ? 'subsite' : 'msite')).lang('indextemplate')."</a>",
"<a href=\"?entry=cnodes&action=cnodescommon$param_suffix\">".lang('cataspagetem')."</a>",
);
tabheader(lang('basemset'));
echo '<tr><td class="item2">';
echo tab_list($arr,5);
echo '</td></tr>';
tabfooter();

$arr = array();
foreach($channels as $k => $v) $arr[] = "<a href=\"?entry=channels&action=channeldetail&chid=$k$param_suffix#template\">$v[cname]</a>";
tabheader(lang('arcconpagtemset'));
echo '<tr><td class="item2">';
echo tab_list($arr,5);
echo '</td></tr>';
tabfooter();

$arr = array();
foreach($altypes as $k => $v) $arr[] = "<a href=\"?entry=altypes&action=altypedetail&atid=$k$param_suffix\">$v[cname]</a>";
tabheader(lang('albuconpagtemset'));
echo '<tr><td class="item2">';
echo tab_list($arr,5);
echo '</td></tr>';
tabfooter();

if(!$sid){
	$arr = array();
	foreach($fcatalogs as $k => $v) $arr[] = "<a href=\"?entry=fcatalogs&action=fcatalogdetail&caid=$k$param_suffix#template\">$v[title]</a>";
	tabheader(lang('freconpagtemset'));
	echo '<tr><td class="item2">';
	echo tab_list($arr,5);
	echo '</td></tr>';
	tabfooter();
}

$arr = array();
foreach($commus as $k => $v){
	$v = read_cache('commu',$k);
	if($v['addable'] || $v['sortable']) $arr[] = "<a href=\"?entry=commus&action=commudetail&cuid=$k$param_suffix\">$v[cname]</a>";
}
tabheader(lang('arccomtemset'));
echo '<tr><td class="item2">';
echo tab_list($arr,5);
echo '</td></tr>';
tabfooter();

if(!$sid){
	$arr = array();
	foreach($mcommus as $k => $v){
		$v = read_cache('mcommu',$k);
		if($v['addable']) $arr[] = "<a href=\"?entry=mcommus&action=mcommudetail&cuid=$k\">$v[cname]</a>";
	}
	tabheader(lang('memcomtemset'));
	echo '<tr><td class="item2">';
	echo tab_list($arr,5);
	echo '</td></tr>';
	tabfooter();
}

$arr = array();
$query = $db->query("SELECT cname FROM {$tblprefix}sptpls ORDER BY vieworder");
while($item = $db->fetch_array($query)) $arr[] = "<a href=\"?entry=sptpls&action=sptplsedit$param_suffix\">$item[cname]</a>";
tabheader(lang('sppagtemset'));
echo '<tr><td class="item2">';
echo tab_list($arr,5);
echo '</td></tr>';
tabfooter();

?>