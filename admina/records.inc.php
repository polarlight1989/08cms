<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('record') || amessage('no_apermission');
!in_array($action, array('badlogin','adminlog','currencylog')) && amessage('noexrecord');
$aps = array('badlogin' => 109,'adminlog' => 110,'currencylog' => 111);
unset($aps);
$rname = $action;
$yearmonth = date('Ym',$timestamp);
$recorddir = M_ROOT.'./dynamic/records/';
$recordfile = $recorddir.$yearmonth.'_'.$rname.'.php';

$records = (array)@file($recordfile);
$filesize = @filesize($recordfile);

if($filesize < 500000){
	$dir = opendir($recorddir);
	$length = strlen($rname);
	$maxid = $id = 0;
	while($file = readdir($dir)){
		if(in_str($yearmonth.'_'.$rname,$file)) {
			$id = intval(substr($file, $length + 8));
			$id > $maxid && $maxid = $id;
		}
	}
	closedir($dir);

	if($maxid){
		$rnamefile2 = $recorddir.$yearmonth.'_'.$rname.'_'.$maxid.'.php';
	}else{
		$lastyearmonth = date('Ym',$timestamp - 86400 * 28);
		$rnamefile2 = $recorddir.$lastyearmonth.'_'.$rname.'.php';
	}

	if(file_exists($rnamefile2) && $records2 = @file($rnamefile2)) {
		$records = array_merge($records2, $records);
	}
}

$page = empty($page) ? 1 : max(1,intval($page));
$start = ($page - 1) * $atpp;
$records = array_reverse($records);
$num = count($records);
$multi = multi($num,$atpp,$page,"?entry=records&action=$action");
$records = array_slice($records,$start,$atpp);
$url_type = 'record';include 'urlsarr.inc.php';

if($action == 'badlogin'){
	url_nav(lang('sitelogs'),$urlsarr,'bad');

	$itemrecord = '';
	$no = $start;
	foreach($records as $recordstr){
		$record = explode("\t",$recordstr);
		if(empty($record[1])){
			continue;
		}
		$no ++;
		$record[1] = date('y-n-j H:i',$record[1]);
		$itemrecord .= "<tr class=\"txt\"><td class=\"txtC w50\">$no</td>\n".
			"<td class=\"txtC\">$record[2]</td>\n".
			"<td class=\"txtC\">$record[3]</td>\n".
			"<td class=\"txtC w120\">$record[4]</td>\n".
			"<td class=\"txtC w120\">$record[1]</td></tr>\n";
	}
	tabheader(lang('loginerrrecord'),'','',7);
	trcategory(array(lang('sn'),lang('tryusercname'),lang('trypassword'),'IP'.lang('address'),lang('operatetime')));
	echo $itemrecord;
	tabfooter();
	echo $multi;

}elseif($action == 'adminlog'){
	url_nav(lang('sitelogs'),$urlsarr,'admin');

	$itemrecord = '';
	foreach($records as $recordstr){
		$record = explode("\t",$recordstr);
		if(empty($record[1])){
			continue;
		}
		$record[1] = date('y-n-j H:i',$record[1]);
		$itemrecord .= "<tr class=\"txt\"><td class=\"txtC w40\">$record[2]</td>\n".
			"<td class=\"txtL w80\">$record[3]</td>\n".
			"<td class=\"txtC w80\">$record[4]</td>\n".
			"<td class=\"txtC w100\">$record[5]</td>\n".
			"<td class=\"txtC\">$record[6]</td>\n".
			"<td class=\"txtC\">$record[7]</td>\n".
			"<td class=\"txtC w110\">$record[1]</td></tr>\n";
	}
	tabheader(lang('adminoperate'),'','',7);
	trcategory(array(lang('userid'),lang('usercname'),lang('usergroup'),'IP'.lang('address'),lang('operate'),lang('detail'),lang('time')));
	echo $itemrecord;
	tabfooter();
	echo $multi;
}elseif($action == 'currencylog'){
	url_nav(lang('sitelogs'),$urlsarr,'currency');

	$itemrecord = '';
	foreach($records as $recordstr){
		$record = explode("\t",$recordstr);
		if(empty($record[1])){
			continue;
		}
		$record[1] = date('y-n-j H:i',$record[1]);
		$itemrecord .= "<tr class=\"txt\"><td class=\"txtC w40\">$record[2]</td>\n".
			"<td class=\"txtL w80\">$record[3]</td>\n".
			"<td class=\"txtC w60\">$record[4]</td>\n".
			"<td class=\"txtC w40\">$record[5]</td>\n".
			"<td class=\"txtC w40\">$record[6]</td>\n".
			"<td class=\"txtC\">$record[7]</td>\n".
			"<td class=\"txtC w110\">$record[1]</td></tr>\n";
	}
	tabheader(lang('crrecord'),'','',7);
	trcategory(array(lang('userid'),lang('usercname'),lang('currencytype'),lang('mode1'),lang('amount'),lang('reason'),lang('time')));
	echo $itemrecord;
	tabfooter();
	echo $multi;
}
?>