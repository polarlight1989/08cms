<?
!defined('M_COM') && exit('No Permission');
load_cache('currencys');
$pmodearr = array('0' => lang('visitingpay'),'1' => lang('onlinepay'),'2' => lang('banktransfer'),'3' => lang('postofficeremit'));
$pays = array(
	'alipay' => array(@$cfg_alipay, @$cfg_alipay_keyt, @$cfg_alipay_partnerid),
	'tenpay' => array(@$cfg_tenpay, @$cfg_tenpay_keyt)
);
$poids = array();
foreach(array('alipay' => 2, 'tenpay' => 3) as $k => $v)($cfg_paymode & (1 << $v)) && !in_array('', $pays[$k]) && $poids[$k] = lang($k);
if(empty($pid)){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$pmode = isset($pmode) ? $pmode : '-1';
	$receive = isset($receive) ? $receive : '-1';
	$trans = isset($trans) ? $trans : '-1';
	$poid = empty($poid) ? '' : $poid;

	$wheresql = "WHERE mid=$memberid";
	if($pmode != '-1') $wheresql .= ($wheresql ? " AND " : "")."pmode='$pmode'";
	if($receive != '-1') $wheresql .= ($wheresql ? " AND " : "")."receivedate".($receive ? '>' : '=')."0";
	if($trans != '-1') $wheresql .= ($wheresql ? " AND " : "")."transdate".($trans ? '>' : '=')."0";
	if(!empty($poid)) $wheresql .= ($wheresql ? " AND " : "")."poid='$poid'";
	
	$filterstr = '';
	foreach(array('pmode','trans','receive','poid') as $k){
		$filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	}
	if(!submitcheck('barcsedit')){
		$pmodearr = array('-1' => lang('nolimit')) + $pmodearr;
		$receivearr = array('-1' => lang('nolimit'),'0' => lang('noarrive'),'1' => lang('arrived'));
		$transarr = array('-1' => lang('nolimit'),'0' => lang('notrans'),'1' => lang('transed'));
		$poidsarr = array('' => lang('nolimit')) + $poids;
		echo form_str($action.'arcsedit',"?action=$action&page=&page");
		tabheader_e();
		echo "<tr><td class=\"item2\">";
		echo "<select style=\"vertical-align: middle;\" name=\"receive\">".makeoption($receivearr,$receive)."</select>&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"trans\">".makeoption($transarr,$trans)."</select>&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"pmode\">".makeoption($pmodearr,$pmode)."</select>&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"poid\">".makeoption($poidsarr,$poid)."</select>&nbsp; ";
		echo strbutton('bfilter','filter0').'</td></tr>';
	tabfooter();
		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * FROM {$tblprefix}pays $wheresql ORDER BY pid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$stritem = '';
		while($item = $db->fetch_array($query)){
			$pid = $item['pid'];
			$pmodestr = $pmodearr[$item['pmode']];
			$poidstr = empty($item['poid']) ? '-' : $poids[$item['poid']];
			$sendstr = date("$dateformat",$item['senddate']);
			$receivestr = empty($item['receivedate']) ? '-' : date("$dateformat",$item['receivedate']);
			$transstr = empty($item['transdate']) ? '-' : date("$dateformat",$item['transdate']);
			$stritem .= "<tr><td class=\"item\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$pid]\" value=\"$pid\"></td>\n".
				"<td class=\"item\">$pmodestr</td>\n".
				"<td class=\"item\" width=\"80\">$item[amount]</td>\n".
				"<td class=\"item\" width=\"60\">$poidstr</td>\n".
				"<td class=\"item\" width=\"70\">$sendstr</td>\n".
				"<td class=\"item\" width=\"70\">$receivestr</td>\n".
				"<td class=\"item\" width=\"70\">$transstr</td>\n".
				"<td class=\"item\" width=\"30\"><a href=\"?action=pays&pid=$pid\">".lang('look')."</a></td></tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}pays $wheresql");
		$multi = multi($counts, $mrowpp, $page, "?action=pays$filterstr");

		tabheader(lang('payrecordlist'),'','',9);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('paymode'),lang('payamount'),lang('payinterface'),lang('recorddate'),lang('arrivedate'),lang('savingdate'),lang('detail')));
		echo $stritem;
		tabfooter();
		echo $multi;
		tabfooter('barcsedit',lang('delete'));
	}else{
		empty($selectid) && mcmessage('selectpayrecord',"?action=pays&page=$page$filterstr");
		$db->query("DELETE FROM {$tblprefix}pays WHERE mid=$memberid AND pid ".multi_str($selectid)." AND receivedate=0",'SILENT');
		mcmessage('csmds',"?action=pays&page=$page$filterstr");
	}
}else{
	$forward = empty($forward) ? M_REFERER : $forward;
	empty($pid) && mcmessage('confirmchoosepays',$forward);
	if(!$item = $db->fetch_one("SELECT * FROM {$tblprefix}pays WHERE pid=$pid")) mcmessage('choosepayrecord',$forward);
	include_once M_ROOT."./include/fields.cls.php";
	if(!submitcheck('bpaydetail')){
		if(!$item['transdate']){
			tabheader(lang('modifypaymessage'),'paydetail','?action=pays&pid='.$pid.'&forward='.rawurlencode($forward),2,1);
		}else{
			tabheader(lang('lookpaymessage'));
		}
		trbasic(lang('membercname'),'',$item['mname'],'');
		trbasic(lang('paymode'),'',$pmodearr[$item['pmode']],'');
		trbasic(lang('payamountrmbi'),'',$item['amount'],'');
		trbasic(lang('handfeermb'),'',$item['handfee'],'');
		trbasic(lang('payinterface'),'',$item['poid'] ? $poids[$item['poid']] : '-','');
		trbasic(lang('payordersidsn'),'',$item['ordersn'] ? $item['ordersn'] : '-','');
		trbasic(lang('messagesendtime'),'',date("$dateformat $timeformat",$item['senddate']),'');
		trbasic(lang('cashartime'),'',$item['receivedate'] ? date("$dateformat $timeformat",$item['receivedate']) : '-','');
		trbasic(lang('currencysavtime'),'',$item['transdate'] ? date("$dateformat $timeformat",$item['transdate']) : '-','');
		trbasic(lang('contactorname'),'itemnew[truename]',$item['truename']);
		trbasic(lang('contacttel'),'itemnew[telephone]',$item['telephone']);
		trbasic(lang('contactemail'),'itemnew[email]',$item['email']);
		trbasic(lang('remark'),'itemnew[remark]',br2nl($item['remark']),'textarea');
		trspecial(lang('paywarrant')."&nbsp; &nbsp; ["."<a href=\"".$item['warrant']."\" target=\"_blank\">".lang('bigimage')."</a>"."]",'itemnew[warrant]',$item['warrant'],'image');
		if($item['transdate']){
			tabfooter();
			echo "<input class=\"button\" type=\"submit\" name=\"\" value=\"".lang('goback')."\" onclick=\"history.go(-1);\">";
		}else{
			tabfooter('bpaydetail',lang('modify'));
		}
	}else{
		if($item['transdate']) mcmessage('paynomodify');
		include_once M_ROOT."./include/upload.cls.php";
		$itemnew['truename'] = trim(strip_tags($itemnew['truename']));
		$itemnew['telephone'] = trim(strip_tags($itemnew['telephone']));
		$itemnew['email'] = trim(strip_tags($itemnew['email']));
		$itemnew['remark'] = mnl2br(mhtmlspecialchars($itemnew['remark']));
		$c_upload = new cls_upload;	
		$itemnew['warrant'] = upload_s($itemnew['warrant'],$item['warrant'],'image');
		$c_upload->saveuptotal(1);
		$db->query("UPDATE {$tblprefix}pays SET
					 truename='$itemnew[truename]',
					 telephone='$itemnew[telephone]',
					 email='$itemnew[email]',
					 remark='$itemnew[remark]',
					 warrant='$itemnew[warrant]' 
					 WHERE pid='$pid'
					 ");
		$c_upload->closure(1, $pid, 'pays');
		unset($c_upload);
		mcmessage('paymodifyfinish',$forward);
	}
}
?>
