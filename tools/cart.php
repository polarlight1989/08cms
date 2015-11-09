<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
#include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT."./include/admin.fun.php";
include_once M_ROOT."./include/adminm.fun.php";
load_cache('mlangs,mmsgs');
$langs = &$mlangs;
if(empty($memberid)){
	_header(0);
	mcmessage('nousnopurchasepermi');
}
include_once M_ROOT."./include/arcedit.cls.php";
include_once M_ROOT."./include/archive.fun.php";
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
load_cache('channels,currencys,commus,cotypes,shipings,pfields,permissions');
$oid = empty($oid) ? 0 : max(0,intval($oid));
$forward = empty($forward) ? M_REFERER : $forward;
$no_mcfooter = 1;
if(!submitcheck('bsettlement') && !submitcheck('bcartpay') && !submitcheck('bconfirm')){
#		m_guide(23);
	$itemstr = '';
	$oldsum = $ordersum = $weights = 0;
	$goods = empty($m_cookie["goods_$memberid"]) ? array() : explode(';', $m_cookie["goods_$memberid"]);
	$aids = array();
	$cids = array();
	foreach($goods as $v){
		$tmp = explode(',', $v);
		if(!is_numeric($tmp[0]))continue;
		if($tmp[1])$cids[] = $tmp[0];else $aids[] = $tmp[0];
	}
	$goods = array();
	if(!empty($aids)){
		$aids = join(',',$aids);
		$query = $db->query("SELECT a.aid,a.sid,a.createdate,a.caid,a.chid,a.subject,a.customurl,a.price,s.storage FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid WHERE a.aid IN ($aids)");
		$goods[0] = array();
		while($tmp = $db->fetch_array($query))$goods[0][] = $tmp;
	}
	if(!empty($cids)){
		$cids = join(',',$cids);
		$query = $db->query("SELECT a.aid,a.sid,a.createdate,a.caid,a.chid,a.subject,a.customurl,o.cid,o.mid,o.mname,o.oprice as price,o.storage FROM {$tblprefix}offers o LEFT JOIN {$tblprefix}archives a ON o.aid = a.aid WHERE cid IN ($cids) ORDER BY o.mid DESC");
		while($tmp = $db->fetch_array($query)){
			empty($goods[$tmp['mid']]) && $goods[$tmp['mid']] = array();
			$goods[$tmp['mid']][] = $tmp;
		}
	}
	if(empty($goods)){
		_header(0);
		mcmessage('nogoods');
	}
	_header(1);
	foreach($goods as $key => $tmp){
		tabheader(lang('mycartstep',empty($tmp[0]['mname']) ? lang('websiteseller') : $tmp[0]['mname']),"cart_$key",$_SERVER['PHP_SELF'],6);
		$catesarr = array(lang('delete'),lang('goodscname'),lang('price'),lang('dcprice'),lang('puamount'));
#			$enableship && $catesarr[] = lang('weight_kg');
		$enablestock && $catesarr[] = lang('stock');
#			$catesarr[] = lang('addtime');
		trcategory($catesarr);
		foreach($tmp as $item){
			$cid = $key ? "$item[cid],$item[mid]" : "$item[aid],0";
			$item['storage'] < 0 && $item['storage'] = '-';//不限库存
			$channel = read_cache('channel',$item['chid']);
			!($commu = read_cache('commu',$channels[$item['chid']]['cuid'])) &&
			(!$channel['offer'] || !($ocommu = read_cache('commu',$channel['offer'])) ||
			!($commu = read_cache('commu',$ocommu['setting']['purchase']))) && message('noavailableitemoper');
			$discount = !empty($commu['setting']['gtmode']) ? $curuser->gt_discount($commu['setting']['gtmode']) : 0;
			/*

			if(!empty($commu['setting']['allmode'])){
				$cn_discount = !empty($commu['setting']['cnmode']) ? cn_discount($item,$commu['setting']['cnmode']) : 0;
				$discount = caldiscount(array($gt_discount,$cn_discount,!empty($commu['setting']['discount']) ? $commu['setting']['discount'] : 0),$commu['setting']['allmode']);
			}//*/
			$item['arcurl'] = view_arcurl($item);
//				$item['dcprice'] = $item['price'];
			$item['dcprice'] = round($item['price'] * (1 - $discount / 100),2);
/*				$oldsum += $item['price'] * $item['nums'];
			$ordersum += $item['dcprice'] * $item['nums'];*/
#				$enableship && $weights += $item['weight'] * $item['nums'];
			echo "<tr><td class=\"item\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" onclick=\"del(this,'$cid')\"></td>\n".
				"<td class=\"item\"><a href=\"$item[arcurl]\" target=\"_blank\">".mhtmlspecialchars($item['subject'])."</a></td>\n".
				"<td class=\"item\" width=\"80\">$item[price]</td>\n".
				"<td class=\"item\" width=\"80\">$item[dcprice]</td>\n".
				"<td class=\"item\" width=\"60\"><input id=\"num_$cid\" type=\"text\" onchange=\"num(this,'$cid')\" size=\"4\"></td>\n".
#					(!$enableship ? '' :  "<td class=\"item\" width=\"60\">$item[weight]</td>\n").
				(!$enablestock ? '' : "<td class=\"item\" width=\"60\">$item[storage]</td>\n").
#					"<td class=\"item\" width=\"70\">$item[createdate]</td>".
				"</tr>\n";
		}
		echo "<tr><td class=\"item\" colspan=\"6\">".
			lang('oldpricesum',"<span id=\"oldsum_$key\"></span>").
			lang('dcpricesum',"<span id=\"ordersum_$key\"></span>").
#					($enableship ? lang('weightsum',$weights)."<input type=\"hidden\" name=\"cartnew[weights]\" value=\"$weights\">" : '').
			"</td></tr>";
		tabfooter();
		trhidden('oid', $key);
		echo "<input class=\"button\" type=\"submit\" name=\"bsettlement\" value=\"".lang('settlementcenter')."\"></form><div class=\"blank9\"></div>";
	}
}elseif(submitcheck('bsettlement')){
	_header();
	if($enableship){
		$shidsarr = array('0' => lang('nosetting'));
		foreach($shipings as $k =>$v) $shidsarr[$k] = $v['cname'];
	}
	$goods = empty($m_cookie["goods_$memberid"]) ? array() : explode(';', $m_cookie["goods_$memberid"]);
	$aids = array();
	$hash = array();
	foreach($goods as $v){
		$tmp = explode(',', $v);
		if(!is_numeric($tmp[0]))continue;
		if($tmp[1] == $oid){
			$aids[] = $tmp[0];
			$hash[$tmp[0]] = $tmp[2];
		}
	}
	empty($aids) && mcmessage('nogoods');
	if($oid){//商家
		$user = new cls_userinfo;
		$user->activeuser($oid,1);
		$shipingfee = array(1 => $user->info['shipingfee1'], 2 => $user->info['shipingfee2'], 3 => $user->info['shipingfee3']);
	}else{//网站
		for($i = 1; $i < 4; $i++)isset(${"shipingfee$i"}) || ${"shipingfee$i"} = -1;
		$shipingfee = array(1 => $shipingfee1, 2 => $shipingfee2, 3 => $shipingfee3);
	}
#	$ordersn = date('Ymd').'-'.$memberid.'-'.date('His').'-'.random(6,1);
	$spmodearr = array('0' => lang('noshiping'));
	foreach($shipingfee as $k => $v)$v >= 0 && $spmodearr[$k.'_'.$v] = lang("shipingfee$k")."($v)";

	$aids = join(',', $aids);
	$query = $db->query($oid ? "SELECT a.aid,a.chid,o.cid,o.mid,o.mname,o.oprice as price FROM {$tblprefix}offers o LEFT JOIN {$tblprefix}archives a ON o.aid = a.aid WHERE cid IN ($aids) ORDER BY o.mid DESC" : "SELECT aid,chid,price FROM {$tblprefix}archives WHERE aid IN ($aids)");
	$oldsum = 0;
	$goods = array();
	$k = $oid ? 'cid' : 'aid';
	while($item = $db->fetch_array($query)){
		empty($tomname) && $tomname = $oid ? $item['mname'] : lang('websiteseller');
		$num = max(0, intval($hash[$item[$k]]));
		$channel = read_cache('channel',$item['chid']);
		!($commu = read_cache('commu',$channels[$item['chid']]['cuid'])) &&
		(!$channel['offer'] || !($ocommu = read_cache('commu',$channel['offer'])) ||
		!($commu = read_cache('commu',$ocommu['setting']['purchase']))) && message('noavailableitemoper');
		$discount = !empty($commu['setting']['gtmode']) ? $curuser->gt_discount($commu['setting']['gtmode']) : 0;
		$oldsum += round($item['price'] * (1 - $discount / 100),2) * $num;
		$goods[] = $item['aid'] . ($oid ? ",$item[cid]" : ',0') . ",$item[price],$num";
	}
//		if(!$oldmsg = $db->fetch_one("SELECT shid FROM {$tblprefix}orders WHERE mid='$memberid' ORDER BY oid DESC LIMIT 0,1")) $oldmsg = array();
	tabheader(lang('settlementcenterstep',$tomname),'cart','?action=cart&forward='.urlencode($forward),2,1,1);
#	trbasic(lang('orderssncode'),'',$ordersn,'');
	trbasic(lang('goodsoldpricesum'),'',$oldsum.'&nbsp;'.lang('yuan'),'');
	trbasic(lang('goodsdcpricesum'),'',$oldsum.'&nbsp;'.lang('yuan'),'');
	trbasic(lang('shipingfee'),'shipingfee',makeradio('shipingfee',$spmodearr,-1),'');
/*		if(!empty($enableship)){
		trbasic(lang('goodsweightsum'),'',$cartnew['weights'].'&nbsp;kg','');
		trbasic(lang('shiping'),'cartnew[shid]',makeoption($shidsarr,empty($oldmsg['shid']) ? 0 : $oldmsg['shid']),'select');
	}*/
	tabfooter();
	$submitstr = "rmsg = checkcheck('shipingfee',form);\nif(rmsg){\n\tif(dom=\$id('alert_shipingfee'))dom.innerHTML = rmsg;\n\ti = false;\n}\n";

	tabheader(lang('ordersothermessage'));
	$a_field = new cls_field;
	foreach($pfields as $k => $field){
		if(!$field['isadmin']){
			$a_field->init();
			$a_field->field = $field;
			$a_field->oldvalue = !isset($oldmsg[$k]) ? '' : $oldmsg[$k];
			$a_field->trfield('','','p');
			$submitstr .= $a_field->submitstr;
		}
	}
	tabfooter();

	$spma = join(',',array_keys($spmodearr));
	$goods = join(';',$goods);
	trhidden('oid', $oid);
	trhidden('tomname', htmlspecialchars($tomname));
#	trhidden('ordersn', $ordersn);
	trhidden('orderfee', $oldsum);
	trhidden('goods', $goods);
	trhidden('spma', $spma);
#	trhidden('crc', my_crc($oid, $tomname, $ordersn, $oldsum, $spma, $goods));
	trhidden('crc', my_crc($oid, $tomname, $oldsum, $spma, $goods));
	echo "<input class=\"button\" type=\"submit\" name=\"bcartpay\" value=\"".lang('continue')."\"></form>";
//		echo "<input class=\"button\" type=\"button\" onclick=\"javasrcipt:history.go(-1);return false\" value=\"".lang('goback')."\">";
	check_submit_func($submitstr);
}elseif(submitcheck('bcartpay')){
	if($crc != my_crc($oid, $tomname, $orderfee, $spma, $goods) || !in_array($shipingfee, explode(',',$spma))){
		_header();
		mcmessage('crc_error');
	}
	$spmd = explode('_',$shipingfee);
	$spmd[1] = empty($spmd[1]) ? 0 : max(0, floatval($spmd[1]));
	$totalfee = $orderfee + $spmd[1];
	$sqlstr = "";
	$pfields = fields_order($pfields);
	$c_upload = new cls_upload;	
	$a_field = new cls_field;
	foreach($pfields as $k => $v){
		if(!$v['isadmin']){
			$a_field->init();
			$a_field->field = $v;
			$a_field->deal();
			if(!empty($a_field->error)){
				$c_upload->rollback();
				_header();
				mcmessage($a_field->error,M_REFERER);
			}
			$sqlstr .= ','.$k."='".$a_field->newvalue."'";
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ','.$k.'_'.$x."='$y'";
		}
	}
	$c_upload->saveuptotal(1);
	unset($a_field);

	$goods = explode(';', $goods);
	$nums = array();
	$k = $oid ? 1 : 0;
	foreach($goods as $i => $v){
		$goods[$i] = $v = explode(',', $v);
		$nums[$v[$k]] = intval($v[3]);
	}
	$aids = join(',', array_keys($nums));
	$err = 1;
	$query = $db->query($oid ? "SELECT cid as id,storage FROM {$tblprefix}offers WHERE cid in ($aids)" : "SELECT aid as id,storage FROM {$tblprefix}archives_sub WHERE aid IN ($aids)");
	if($db->num_rows($query)){
		$err = 0;
		while($row = $db->fetch_array($query)){
			if($row['storage'] < 0){//不限库存的
				$nums[$row['id']] = $row['storage'];
			}else{
				if($nums[$row['id']] > $row['storage']){$err=1;break;}
				$nums[$row['id']] = $row['storage'] - $nums[$row['id']];
			}
		}
	}
	if($err || $db->num_rows($query) != count($nums)){//商品信息不存在或库存不足
		_header();
		mcmessage('goods_nums_err');
	}

	do{
#		$ordersn = date('Ymd')."-$memberid-".date('His').'-'.random(6,1);
		$ordersn = date("Ymd-$memberid-His-").random(6,1);
	}while($db->fetch_one("SELECT oid FROM {$tblprefix}orders WHERE ordersn='$ordersn' LIMIT 0,1"));
	$db->query("INSERT INTO {$tblprefix}orders SET
				ordersn='$ordersn',
				orderfee='$orderfee',
				shipingmode='$spmd[0]',
				shipingfee='$spmd[1]',
				totalfee='$totalfee',
				mid='$memberid',
				mname='".$curuser->info['mname']."',
				tomid='$oid',
				tomname='$tomname',
				paymode='-1',
				createdate='$timestamp'
				$sqlstr
				");
	if($nid = $db->insert_id()){//统计库存量与商品统计及会员统计
		$c_upload->closure(1, $nid, 'orders');
		$gcookie = empty($m_cookie["goods_$memberid"]) ? array() : explode(';', $m_cookie["goods_$memberid"]);
		$ncookie = array();
		foreach($gcookie as $v){
			$tmp = explode(',', $v);
			if($tmp[1] != $oid)$ncookie[] = $v;
		}
		msetcookie("goods_$memberid", join(';', $ncookie));//更新cookie
		$sqlstr = array();
/*		foreach($nums as $k => $v)$sqlstr[] = "('$k','$v')";
		$sqlstr = ($oid ? "REPLACE INTO {$tblprefix}offers (cid,storage) VALUES " : "REPLACE INTO {$tblprefix}archives_sub (aid,storage) VALUES ") . join(',', $sqlstr);
		$sqlstr = ($oid ? "REPLACE INTO {$tblprefix}offers (cid,storage) VALUES " : "REPLACE INTO {$tblprefix}archives_sub (aid,storage) VALUES ") . join(',', $sqlstr);
		$db->query($sqlstr);//更新库存*/
		$table = $oid ? 'offers' : 'archives_sub';
		$key = $oid ? 'cid' : 'aid';
		foreach($nums as $k => $v){
			$sqlstr = "UPDATE $tblprefix$table SET storage=$v WHERE $key=$k";
			$db->query($sqlstr);//更新库存
		}
		$sqlstr = array();
		$mname = $curuser->info['mname'];
		foreach($goods as $v)$sqlstr[] = "('$v[0]','$v[1]','$v[2]','$memberid','$mname','$oid','$tomname','$v[3]','$nid','$timestamp')";
		$sqlstr = "INSERT INTO {$tblprefix}purchases (aid,tocid,price,mid,mname,tomid,tomname,nums,oid,createdate) VALUES " . join(',', $sqlstr);
		$db->query($sqlstr);
		_header();
		mcmessage('pugoodssucceed','',"<a href=\"adminm.php?action=orders&oid=$nid\">$ordersn</a>");
	}else{
		$c_upload->closure(1, 0, 'orders');
		_header();
		mcmessage('pugoodsfailed',$forward);
	}
	unset($c_upload);
}
?>
</body>
</html>
<?php
function my_crc(){
	global $cartkey;
	$ret = empty($cartkey) ? '1437dc8b0fdec546e6effcb9fc981927' : $cartkey;
	if($args = func_get_args()){
		foreach($args as $v){
			$i = 0;
			$v = "$v";
			$l = $hash = strlen($v);
			while($i < $l)$hash = ($hash << 7 | $hash >> 25) + ord($v{$i++});
			$ret .= dechex($hash);
		}
	}
	return md5($ret);
}
function _header($js=0){
	global $cmsname,$ckpre,$memberid,$mcharset,$cms_abs;//$mcharset,$mallowfloatwin,$mfloatwinwidth,$mfloatwinheight;
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>" />
<title><?=lang('madmincenter').' - '.$cmsname?></title>
<link type="text/css" href="<?=$cms_abs?>images/adminm/style.css" rel="stylesheet" />
<script type="text/javascript" src="<?=$cms_abs?>include/js/common.js"></script>
<script type="text/javascript" src="<?=$cms_abs?>include/js/langs.js"></script><?php
if($js){?>
<script type="text/javascript">
var ckey = '<?="{$ckpre}goods_$memberid"?>', hash = {}, count = 0;
function del(e, id){
	if(confirm(lang('confirm_del_goods'))){
		var o = e.form.oid.value;
		while(e = e.parentNode)if(e.tagName.toUpperCase() == 'TR'){
			e.parentNode.removeChild(e);
			Cookie(ckey, Cookie(ckey).replace(new RegExp('^' + id + ',\\d+;?|;' + id + ',\\d+'),''));
			delete hash[o][id];
			sum(o);
			break;
		}
	}else{
		e.checked = false;
		e.blur();
	}
}
function num(e, id){
	var n = parseInt(e.value, 10), o = e.form.oid.value, a = hash[o][id], E;
	if(isNaN(n) || n < 1)E = n = 1;
	if(a[2] >= 0 && n > a[2])E = n = a[2];
	a[3] = e.value = n;
	Cookie(ckey, Cookie(ckey).replace(new RegExp('\\b' + id + ',\\d+'), id + ',' + n));
	sum(o);
	if(E != undefined)alert(lang('set_goods_num_err', E));
}
function sum(o){
	var a = hash[o], d = 0, s = 0, k;
	for(k in a){
		d += a[k][0] * a[k][3];
		s += a[k][1] * a[k][3];
	}
	try{
		$id('oldsum_' + o).innerHTML = s;
		$id('ordersum_' + o).innerHTML = d;
	}catch(e){}
}
window.onload = function(){
	var c = Cookie(ckey), e, i, k, l, o, p, r, x;
	if(!c)return;
	c = c.split(';');
	for(i = 0, l = c.length; i < l; i++){
		x = c[i].split(',');
		e = $id('num_' + (x[3] = x[0] + ',' + x[1]));
		if(!e)continue;
		e.value = x[2] = parseInt(x[2], 10);
		o = e.form.oid.value;
		p = e.parentNode;
		do{if(p.tagName.toUpperCase() == 'TD')break}while(p = p.parentNode);
		r = p.parentNode.cells;
		if(!k)for(k = 0; k < r.length; k++)if(r[k] == p)break;
		count++;
		p = parseInt(r[k + 1].innerHTML, 10);
		if(isNaN(p)){
			p = -1;
		}else if(x[2] > p){
			Cookie(ckey, Cookie(ckey).replace(new RegExp('\\b' + x[3] + ',\\d+'), x[3] + ',' + p));
			e.value = x[2] = p;
		}
		hash[o] || (hash[o] = {});
		hash[o][x[3]] = [parseInt(r[k - 1].innerHTML, 10), parseInt(r[k - 2].innerHTML, 10), p, x[2]];
	}
	for(k in hash)sum(k);
};
</script><?php }?>
</head>

<body><?php }?>
