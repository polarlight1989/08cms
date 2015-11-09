<?
!defined('M_COM') && exit('No Permission');
load_cache('channels,acatalogs,currencys');
include_once M_ROOT."./include/arcedit.cls.php";
$catalogs = &$acatalogs;
$page = empty($page) ? 1 : max(1, intval($page));
$keyword = empty($keyword) ? '' : $keyword;
$filterstr = '';
foreach(array('keyword') as $k){
	$filterstr .= "&$k=".rawurlencode($$k);
}
$wheresql = "WHERE cu.mid=$memberid AND cu.oid>0";
$keyword && $wheresql .= " AND a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";

echo form_str($action.'arcsedit',"?action=$action&page=$page");
tabheader_e();
echo "<tr><td class=\"item2\">";
echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" style=\"vertical-align: middle;\">&nbsp; ";
echo strbutton('bfilter','filter0').'</td></tr>';
tabfooter();

$pagetmp = $page;
do{
	$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject FROM {$tblprefix}purchases cu LEFT JOIN {$tblprefix}archives a ON (a.aid=cu.aid) $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
	$pagetmp--;
} while(!$db->num_rows($query) && $pagetmp);
$itemstr = '';
$i = $pagetmp * $mrowpp;
while($item = $db->fetch_array($query)){
	$i ++;
	$item['arcurl'] = view_arcurl($item);
	$item['catalog'] = empty($catalogs[$item['caid']]) ? lang('nocata') : $catalogs[$item['caid']]['title'];
	$item['createdate'] = date("$dateformat", $item['ucreatedate']);
	$item['checkedstr'] = $item['oid'] ? 'Y' : '-';
	$item['orderstr'] = $item['oid'] ? "<a href=\"?action=orders&oid=$item[oid]\">".lang('look')."</a>" : '-';
	$itemstr .= "<tr><td class=\"item\" width=\"30\">$i</td>\n".
		"<td class=\"item2\"><a href=\"$item[arcurl]\" target=\"_blank\">".mhtmlspecialchars($item['asubject'])."</a></td>\n".
		"<td class=\"item\" width=\"80\">$item[catalog]</td>\n".
		"<td class=\"item\" width=\"40\">$item[nums]</td>\n".
		"<td class=\"item\" width=\"40\">$item[price]</td>\n".
		"<td class=\"item\" width=\"40\">$item[orderstr]</td>\n".
		"<td class=\"item\" width=\"100\">$item[createdate]</td></tr>\n";
}
$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}purchases cu LEFT JOIN {$tblprefix}archives a ON (a.aid=cu.aid) $wheresql");
$multi = multi($counts, $mrowpp, $page, "?action=purchases$filterstr");

tabheader(lang('purchasedgoodslist'),'','',9);
trcategory(array(lang('sn'),array(lang('goodscname'),'left'),lang('catalog'),lang('amount'),lang('price'),lang('orders'),lang('purchasedate')));
echo $itemstr;
tabfooter();
echo $multi;
?>