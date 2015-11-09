<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('orders') || amessage('no_apermission');
include_once M_ROOT."./include/arcedit.cls.php";
include_once M_ROOT."./include/archive.fun.php";
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
load_cache('channels,acatalogs,currencys,commus,pfields,cotypes');
$catalogs = &$acatalogs;
$url_type = 'order';include 'urlsarr.inc.php';

$statearr = array('*' => lang('nolimit'),'0' => lang('wait_cpcheck'),'1' => lang('wait_pay'),'2' => lang('wait_send'),'3' => lang('goods_send'),'-1' => lang('order_ok'),'-2' => lang('order_cancel'));
$pmodearr = array('*' => lang('nolimit'),'0' => lang('noshiping'),'1' => lang('shipingfee1'),'2' => lang('shipingfee2'),'3' => lang('shipingfee3'));

if($action == 'ordersedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$state = isset($state) && strlen($state) ? $state : '*';
	$pmode = isset($pmode) && strlen($pmode) ? $pmode : '*';
	$keyword = empty($keyword) ? '' : $keyword;

	$filterstr = '';
	foreach(array('state','pmode','keyword') as $k)$$k && $filterstr .= "&$k=".rawurlencode($$k);

	$wheresql = "tomid=0 AND delstate!=1";
	$state != '*' && $wheresql .= " AND state='$state'";
	$pmode != '*' && $wheresql .= " AND paymode='$pmode'";
	if($keyword){
		$keyword = str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'));
		$wheresql .= " AND (mname LIKE '%".$keyword."%' OR ordersn LIKE '%".$keyword."%')";
	}
	
	if(!submitcheck('barcsedit')){
		url_nav(lang('goodsorder'),$urlsarr,'order');
		echo form_str($action.'arcsedit',"?entry=orders&action=ordersedit&page=$page");
		tabheader_e();
		echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" style=\"vertical-align: middle;width:200px\">&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"state\">".makeoption($statearr,$state)."</select>&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"pmode\">".makeoption($pmodearr,$pmode)."</select>&nbsp; ";
		echo strbutton('bfilter','filter0').'</td></tr>';
		tabfooter();

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * FROM {$tblprefix}orders WHERE $wheresql ORDER BY oid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$strorder = '';
		while($order = $db->fetch_array($query)){
			$oid = $order['oid'];
			$statestr = $statearr[$order['state']];
			$order['createdate'] = date("$dateformat",$order['createdate']);
			$strorder .= "<tr class=\"txt\">".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$oid]\" value=\"$oid\" /></td>\n".
				"<td class=\"txtL\">$order[ordersn]</td>\n".
				"<td class=\"txtC\">$order[mname]</td>\n".
				"<td class=\"txtC w60\">$order[totalfee]</td>\n".
				"<td class=\"txtC w60\">$order[payed]</td>\n".
				"<td class=\"txtC w100\">$statestr</td>\n".
				"<td class=\"txtC w100\">$order[createdate]</td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=orders&action=orderdetail&oid=$order[oid]\">".lang('detail')."</a></td></tr>\n";
		}
		$ordercount = $db->result_one("SELECT count(*) FROM {$tblprefix}orders WHERE $wheresql");
		$multi = multi($ordercount, $atpp, $page, "?entry=orders&action=ordersedit$filterstr");

		tabheader(lang('orderslist')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('orderssncode'),lang('member'),lang('ordallamo'),lang('payed'),lang('orderstate'),lang('ordersdate'),lang('edit')));
		echo $strorder;
		tabfooter();
		echo $multi;
		
		tabheader(lang('operate_item'));
		echo "<tr class=\"txt\"><td class=\"txtL\" colspan=\"2\">".
		"<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[confirm]\" value=\"1\">&nbsp;".lang('confirmorders').'&nbsp; &nbsp; &nbsp; '.
		"<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[cancel]\" value=\"1\">&nbsp;".lang('cancelorders').'&nbsp; &nbsp; &nbsp; '.
		"<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[delete]\" value=\"1\">&nbsp;".lang('deleteorders').'&nbsp; &nbsp; &nbsp; '.
		"</td></tr>";
		tabfooter('barcsedit');
		a_guide('ordersdetail');
	}else{
		empty($arcdeal) && empty($dealstr) && amessage('selectoperateitem',"?entry=orders&action=ordersedit&page=$page$filterstr");
		empty($selectid) && empty($select_all) && amessage('selectorder',"?entry=orders&action=ordersedit&page=$page$filterstr");
		if(!empty($select_all)){
			if(empty($dealstr)){
				$dealstr = implode(',',array_keys(array_filter($arcdeal)));
			}else{
				$arcdeal = array();
				foreach(array_filter(explode(',',$dealstr)) as $k) $arcdeal[$k] = 1;
			}

			$parastr = "";
			foreach(array('arccheck,arcsend','arcreceive') as $k) $parastr .= "&$k=".$$k;
			
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$ordercount = $db->result_one("SELECT count(*) FROM {$tblprefix}orders WHERE $wheresql");
				$pages = @ceil($ordercount / $atpp);
			}
			if($npage <= $pages){
				$nwheresql = $wheresql . (empty($fromid) ? "" : "AND oid<$fromid");
				$query = $db->query("SELECT oid FROM {$tblprefix}orders WHERE $nwheresql ORDER BY oid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)){
					$selectid[] = $item['oid'];
				}
			}
		}
		!empty($arcdeal['confirm']) && !empty($arcdeal['cancel']) && amessage('select_both_cc',"?entry=orders&action=ordersedit&page=$page$filterstr");

		foreach($selectid as $oid){
			if($order = $db->fetch_one("SELECT * FROM {$tblprefix}orders WHERE oid=$oid")){
				if(!empty($arcdeal['confirm']) && !$order['state']){
					$db->query("UPDATE {$tblprefix}orders SET state=1,updatedate='$timestamp' WHERE oid='$oid'");
					continue;
				}
				if(!empty($arcdeal['cancel']) && (!$order['state'] || $order['state'] == 1)){//取消订单，返还库存
					$order['state'] = -2;
					$db->query("UPDATE {$tblprefix}orders SET state=-2,updatedate='$timestamp' WHERE oid='$oid'");
					$query = $db->query("SELECT aid,nums FROM {$tblprefix}purchases WHERE oid='$oid'");
					while($row = $db->fetch_array($query))$db->query("UPDATE {$tblprefix}archives_sub SET storage=storage+$row[nums] WHERE aid=$row[aid] AND storage>=0");
				}
				if(!empty($arcdeal['delete']) && $order['state'] < 0){//取消或完成的订单才能删除
					$sql = $order['delstate'] ?
							"DELETE FROM {$tblprefix}orders WHERE tomid=$memberid AND delstate=2 AND oid='$oid'" ://对方也删除，物理删除本订单
							"UPDATE {$tblprefix}orders SET delstate=1 WHERE oid='$oid'";//买家还未删除，只设定状态;2为买家
					$db->query($sql);
					$order['delstate'] && $order['state'] == -2 && $db->query("DELETE FROM {$tblprefix}purchases WHERE oid='$oid'");//删除购物记录
				}
			}
		}
		unset($aedit,$auser);
		if(!empty($select_all)){
			$npage ++;
			if($npage <= $pages){
				$fromid = min($selectid);
				$transtr = '';
				$transtr .= "&select_all=1";
				$transtr .= "&pages=$pages";
				$transtr .= "&npage=$npage";
				$transtr .= "&barcsedit=1";
				$transtr .= "&fromid=$fromid";
				amessage('operating',"?entry=orders&action=ordersedit&page=$page$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=orders&action=ordersedit&page=$page$filterstr\">",'</a>');
			}
		}
		adminlog(lang('orders_admin'),lang('orders_list_admin'));
		amessage('ordopefin',"?entry=orders&action=ordersedit&page=$page$filterstr");
	}
}elseif($action == 'orderdetail' && $oid){
	$forward = empty($forward) ? M_REFERER : $forward;
	empty($oid) && amessage('chooseord',$forward);
	if(!$order = $db->fetch_one("SELECT * FROM {$tblprefix}orders WHERE tomid=0 AND delstate!=1 AND oid=$oid"))amessage('chooseord',$forward);
	$modify = $order['state'] == -1 && $order['paymode'] > 1 && $order['payed'] != $order['totalfee'];

	if(submitcheck('borderdetail')){//确认订单
		$order['state'] && $order['state'] != 1 && amessage('cheordcanmod',$forward);
		$sqlstr = '';
		/////////////////////////////////////////////重计各种费用
		$ordernew['orderfee'] = round(floatval($ordernew['orderfee']),2);
		$ordernew['shipingfee'] = round(floatval($ordernew['shipingfee']),2);
		$totalfee = $ordernew['orderfee'] + $ordernew['shipingfee'];
		$ordernew['orderfee'] != $order['orderfee'] && $sqlstr .= "orderfee='$ordernew[orderfee]',";
		$ordernew['shipingfee'] != $order['shipingfee'] && $sqlstr .= "shipingfee='$ordernew[shipingfee]',";
		$sqlstr && $sqlstr .= "totalfee='$totalfee',";
		$order['state'] & $sqlstr .= 'state=1,';
		$ordernew['shipingmode'] != $order['shipingmode'] && $sqlstr .= "shipingmode='$ordernew[shipingmode]',";
		$sqlstr && $db->query("UPDATE {$tblprefix}orders SET {$sqlstr}updatedate='$timestamp' WHERE oid='$oid'");
		amessage('ordmodfin',$forward);
	}elseif(submitcheck('borderpay')){//已收款订单
		$ordernew['payed'] = round(floatval($ordernew['payed']),2);
		($ordernew['payed'] < 0 || ($order['state'] && $order['state'] != 1)) && amessage('cheordcanmod',$forward);
		$db->query("UPDATE {$tblprefix}orders SET state=-1,updatedate=$timestamp,payed=payed+$ordernew[payed] WHERE state>=0 AND oid='$oid'");
		$query = $db->query("SELECT aid,price,nums FROM {$tblprefix}purchases WHERE oid='$oid'");
		$nums = 0;
		$aedit = new cls_arcedit();//商家商品
		while($row = $db->fetch_array($query)){
			$nums += $row['nums'];
			$aedit->init();
			$aedit->set_aid($row['aid']);
			$aedit->arc_nums('orders', $row['nums']);
			$aedit->arc_nums('ordersum', $row['price'] * $row['nums'], 1);
		}
		$user = new cls_userinfo();
		$user->activeuser($order['mid'], 1);
		$user->basedeal('purchase', 1, $nums, 1);
		amessage('ordmodfin',$forward);
	}elseif(submitcheck('bordercancel')){//取消订单，返还库存
		$order['state'] && $order['state'] != 1 && amessage('cheordcanmod',$forward);
		$db->query("UPDATE {$tblprefix}orders SET state=-2,updatedate='$timestamp' WHERE oid='$oid'");
		$query = $db->query("SELECT aid,nums FROM {$tblprefix}purchases WHERE oid='$oid'");
		while($row = $db->fetch_array($query))$db->query("UPDATE {$tblprefix}archives_sub SET storage=storage+$row[nums] WHERE aid=$row[aid] AND storage>=0");
		amessage('ordmodfin',$forward);
	}elseif(submitcheck('bordermodify')){
		$modify || amessage('cheordcanmod',$forward);//修改订单已付金额
		$ordernew['payed'] = round(floatval($ordernew['payed']), 2);
		$db->query("UPDATE {$tblprefix}orders SET payed=$ordernew[payed] WHERE oid='$oid'");
		amessage('ordmodpay',$forward);
	}elseif(submitcheck('borderdelete')){
		$order['state'] < 0 || amessage('cheordcanmod',$forward);//取消或完成的订单才能删除
		$sql = $order['delstate'] ?
				"DELETE FROM {$tblprefix}orders WHERE oid='$oid'" ://对方也删除，物理删除本订单
				"UPDATE {$tblprefix}orders SET delstate=1 WHERE oid='$oid'";//对方还未删除，只设定状态
		$db->query($sql);
		$order['delstate'] && $order['state'] == -2 && $db->query("DELETE FROM {$tblprefix}purchases WHERE oid='$oid'");//删除购物记录
		amessage('orddelfin',$forward);
	}elseif(submitcheck('borderinfo')){//修改买家信息
		!$order['state'] || $order['state'] == 1 || amessage('cheordcanmod',$forward);
		$c_upload = new cls_upload;	
		$pfields = fields_order($pfields);
		$a_field = new cls_field;
		$sqlstr = "";
		foreach($pfields as $k => $v){
			$a_field->init();
			$a_field->field = $v;
			$a_field->deal('cartnew');
			if(!empty($a_field->error)){
				$c_upload->rollback();
				amessage($a_field->error,M_REFERER);
			}
			$sqlstr .= ($sqlstr ? ',': '').$k."='".$a_field->newvalue."'";
			if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $sqlstr .= ($sqlstr ? ',': '').$k.'_'.$x."='$y'";

		}
		$c_upload->closure(1, $oid, 'orders');
		$c_upload->saveuptotal(1);
		unset($a_field,$c_upload);
		$db->query("UPDATE {$tblprefix}orders SET $sqlstr WHERE oid='$oid'");
		amessage('ordmodfin',$forward);
	}else{
		url_nav(lang('goodsorder'),$urlsarr,'order');
		$pmodearr = array(0 => 'paynext',1 => 'paycurrency',2 => 'payalipay',3 => 'paytenpay');
		$spmodearr = array('0' => lang('noshiping'));
		for($i = 1; $i < 4; $i++)isset(${"shipingfee$i"}) && ${"shipingfee$i"} >= 0 && $spmodearr[$i] = lang("shipingfee$i");
		tabheader(lang('ordebasedset'),'orderdetail','?entry=orders&action=orderdetail&oid='.$oid.'&forward='.urlencode($forward));
		trbasic(lang('orderssncode'),'',$order['ordersn'],'');
		trbasic(lang('ordersstate'),'',$statearr[$order['state']],'');
		trbasic(lang('member_cname'),'',$order['mname'],'');
		$order['state'] && $order['state'] != 1 ? trbasic(lang('goodfeeyuan'),'',$order['orderfee'],'') : trbasic(lang('goodfeeyuan'),'ordernew[orderfee]',$order['orderfee']);
		$order['state'] && $order['state'] != 1 ? trbasic(lang('shipfeeyuan'),'',$order['shipingfee'],'') : trbasic(lang('shipfeeyuan'),'ordernew[shipingfee]',$order['shipingfee']);
		trbasic(lang('ordfeeallamyua'),'',$order['totalfee'],'');
		$modify ? trbasic(lang('payedcashyuan'),'ordernew[payed]',$order['payed']) : trbasic(lang('payedcashyuan'),'confirm_payed',$order['payed'],'');
		$order['state'] && $order['state'] != 1 && $order['state'] != -2 && trbasic(lang('paymode'),'',lang($pmodearr[$order['paymode']]),'');
		$order['state'] && $order['state'] != 1 ? trbasic(lang('shiping'),'',lang($order['shipingmode'] ? "shipingfee$order[shipingmode]" : 'noshiping'),'') : trbasic(lang('shiping'),'ordernew[shipingmode]',makeoption($spmodearr, $order['shipingmode']),'select');
		tabfooter();

		tabheader(lang('ordergoodlist'),'','',5);
		trcategory(array(/*lang('delete'),*/lang('goodscname'),lang('catalog'),lang('channel'),lang('price'),lang('amount')));
		$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject FROM {$tblprefix}purchases cu LEFT JOIN {$tblprefix}archives a ON (a.aid=cu.aid) WHERE oid='$oid'");
		while($item = $db->fetch_array($query)){
			$cid = $item['cid'];
			$item['arcurl'] = view_arcurl($item);
			$item['catalog'] = empty($catalogs[$item['caid']]) ? lang('nocata') : $catalogs[$item['caid']]['title'];
			$item['channel'] = $channels[$item['chid']]['cname'];
			$item['ucreatedate'] = date("$dateformat", $item['ucreatedate']);
			echo "<tr class=\"txt\">".
#				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$cid]\" value=\"$cid\">\n".
				"<td class=\"txtL\"><a href=\"$item[arcurl]\" target=\"_blank\">".mhtmlspecialchars($item['asubject'])."</a></td>\n".
				"<td class=\"txtC\">$item[catalog]</td>\n".
				"<td class=\"txtC\">$item[channel]</td>\n".
				"<td class=\"txtC w40\">$item[price]</td>\n".
#				"<td class=\"txtC w70\"><input type=\"text\" size=\"4\" name=\"purchasesnew[$cid][nums]\" value=\"$item[nums]\"><input type=\"hidden\" name=\"purchasesnew[$cid][oldnums]\" value=\"$item[nums]\"></td></tr>\n";
				"<td class=\"txtC w70\">$item[nums]</td></tr>\n";
			}
		tabfooter();
		echo '
<script type="text/javascript">
function orderPayedConfirm(form){
	var field = $id("alert_confirm_payed");
	if(field.tagName == "INPUT"){
		if(field.value > 0){
			if(confirm("' . str_replace(array("\\","\r", "\n", '"'), array("\\\\", "\\r", "\\n", '\\"'),lang('confirm_set_payed')) . '".replace("%s", field.value)))return true;
		}else{
			alert("' . str_replace(array("\\","\r", "\n", '"'), array("\\\\", "\\r", "\\n", '\\"'),lang('please_set_payed')) . '");
		}
	}else{
		field.parentNode.innerHTML = "<input name=\"ordernew[payed]\" id=\"" + field.id + "\" value=\"" + (parseInt($id("ordernew[orderfee]").value) + parseInt($id("ordernew[shipingfee]").value)) + "\" />";
		alert("' . str_replace(array("\\","\r", "\n", '"'), array("\\\\", "\\r", "\\n", '\\"'),lang('set_payed')) . '");
	}
	return false;
}
</script>';
		if(!$order['state'] || $order['state'] == 1){
			print('<input type="submit" name="borderdetail" value="' . lang('modify_confirm') . '">&nbsp;&nbsp;<input type="submit" name="borderpay" value="' . lang('orderspayed') . '" onclick="return orderPayedConfirm()"/>');
		}elseif($modify){
			print('<input type="submit" name="bordermodify" value="' . lang('modify_payed') . '">');
		}
		echo '</form><div class="clear"></div>';
		tabheader(lang('ordemessset'),'orderdetail','?entry=orders&action=orderdetail&oid='.$oid.'&forward='.urlencode($forward));
		$submitstr = '';
		if($order['state'] && $order['state'] != 1){
			foreach($pfields as $k => $field)trbasic($field['cname'],'',!isset($order[$k]) ? '' : htmlspecialchars($order[$k]),'');
			$order['state'] < 0 ? tabfooter('borderdelete',lang('deleteorders')) : print('</form>');
		}else{
			$a_field = new cls_field;
			foreach($pfields as $k => $field){
				$a_field->init();
				$a_field->field = $field;
				$a_field->oldvalue = !isset($order[$k]) ? '' : $order[$k];
				$a_field->trfield('cartnew','','p');
				$submitstr .= $a_field->submitstr;
			}
			tabfooter();
			print('<input type="submit" name="borderinfo" value="' . lang('modify') . '">');
			(!$order['state'] || $order['state'] == 1) && print('&nbsp;&nbsp;<input type="submit" name="bordercancel" value="' . lang('cancelorders') . '">');
			echo '</form>';
			check_submit_func($submitstr);
		}
		a_guide('orderdetail');
	}
}
?>
