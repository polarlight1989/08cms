<?php
!defined('M_COM') && exit('No Permission');
$pays = array(
	'alipay' => array(@$cfg_alipay, @$cfg_alipay_keyt, @$cfg_alipay_partnerid),
	'tenpay' => array(@$cfg_tenpay, @$cfg_tenpay_keyt)
);
$poids = array();
foreach(array('alipay' => 2, 'tenpay' => 3) as $k => $v)($cfg_paymode & (1 << $v)) && !in_array('', $pays[$k]) && $poids[$k] = lang($k);
empty($poids) && mcmessage('nvopi');
if(empty($deal)){
	$amount = empty($amount) ? '' : max(0,round($amount,2));
	if(!$oldmsg = $db->fetch_one("SELECT * FROM {$tblprefix}pays WHERE mid='$memberid' ORDER BY pid DESC LIMIT 0,1")) $oldmsg = array();
	tabheader(lang('onlinepay'),'paynew','?action=payonline&deal=confirm',2,0,1);
	trbasic(lang('payinterface'),'paynew[poid]',makeoption($poids),'select');
	trbasic(lang('payamount'),'paynew[amount]',$amount,'text',lang('payamountrmbi'));
	trbasic(lang('contactorname'),'paynew[truename]',empty($oldmsg['truename']) ? '' : $oldmsg['truename'],'btext');
	trbasic(lang('contacttel'),'paynew[telephone]',empty($oldmsg['telephone']) ? '' : $oldmsg['telephone'],'btext');
	trbasic(lang('contactemail'),'paynew[email]',empty($oldmsg['email']) ? '' : $oldmsg['email'],'btext');
	$submitstr = '';
	$submitstr .= makesubmitstr('paynew[amount]',1,'number',0,15);
	$submitstr .= makesubmitstr('paynew[truename]',0,0,0,80);
	$submitstr .= makesubmitstr('paynew[telephone]',0,0,0,30);
	$submitstr .= makesubmitstr('paynew[email]',0,'email',0,100);
	$submitstr .= tr_regcode('payonline');
	tabfooter('submit',lang('continue'));
	check_submit_func($submitstr);
}elseif($deal == 'confirm'){
	if(!regcode_pass('payonline',empty($regcode) ? '' : trim($regcode))) mcmessage('regcodeerror','?action=payonline');
	$paynew['amount'] = max(0,round(floatval($paynew['amount']),2));
	empty($paynew['amount']) && mcmessage('pinputpayamount','?action=payonline');
	array_key_exists($paynew['poid'], $poids) || mcmessage('errorpaymode','?action=payonline');
/*
	$paynew['handfee'] = 0;
	if(!empty($payonline['percent'])){
		$paynew['handfee'] = round($paynew['amount'] * $payonline['percent'] / 100,2);
	}
	$paynew['total'] = $paynew['amount'] + $paynew['handfee'];*/
	$paynew['truename'] = trim(strip_tags($paynew['truename']));
	$paynew['telephone'] = trim(strip_tags($paynew['telephone']));
	$paynew['email'] = trim(strip_tags($paynew['email']));
#	$paynew['ordersn'] = $payonline['partner'].date('Ymd').date('His').random(4,1);
	tabheader(lang('confirm_pay_info'),'paynew','?action=payonline&deal=send');
#	trbasic(lang('pay idsn'),'',$paynew['ordersn'],'');
	trbasic(lang('payinterface'),'',$poids[$paynew['poid']],'');
	trbasic(lang('payamount'),'',$paynew['amount'],'',lang('payamountrmbi'));
#	trbasic(lang('handfeermb'),'',$paynew['handfee'],'',lang('rmbi ( yuan )'));
	trbasic(lang('contactorname'),'',$paynew['truename'],'');
	trbasic(lang('contacttel'),'',$paynew['telephone'],'');
	trbasic(lang('contactemail'),'',$paynew['email'],'');
#	echo "<input type=\"hidden\" name=\"paynew[ordersn]\" value=\"$paynew[ordersn]\">\n";
	echo "<input type=\"hidden\" name=\"paynew[poid]\" value=\"$paynew[poid]\">\n";
	echo "<input type=\"hidden\" name=\"paynew[amount]\" value=\"$paynew[amount]\">\n";
#	echo "<input type=\"hidden\" name=\"paynew[handfee]\" value=\"$paynew[handfee]\">\n";
#	echo "<input type=\"hidden\" name=\"paynew[total]\" value=\"$paynew[total]\">\n";
	echo "<input type=\"hidden\" name=\"paynew[truename]\" value=\"$paynew[truename]\">\n";
	echo "<input type=\"hidden\" name=\"paynew[telephone]\" value=\"$paynew[telephone]\">\n";
	echo "<input type=\"hidden\" name=\"paynew[email]\" value=\"$paynew[email]\">\n";
	tabfooter('submit',lang('confirm_pay'));
}elseif($deal == 'send'){
	array_key_exists($paynew['poid'], $poids) || mcmessage('errorpaymode','?action=payonline');
	do{
		$ordersn = date("YmdHis").random(6,1);
	}while($db->fetch_one("SELECT pid FROM {$tblprefix}pays WHERE ordersn='$ordersn' LIMIT 0,1"));
	$db->query("INSERT INTO {$tblprefix}pays SET
				 mid='".$curuser->info['mid']."', 
				 mname='".$curuser->info['mname']."', 
				 ordersn='$ordersn',
				 pmode='1',
				 poid='$paynew[poid]',
				 amount='$paynew[amount]',
				 handfee=0,
				 truename='$paynew[truename]',
				 telephone='$paynew[telephone]',
				 email='$paynew[email]',
				 senddate='$timestamp',
				 ip='$onlineip'
				 ");
	if($pid = $db->insert_id()){
		require_once(M_ROOT . 'paygate/pay_base.php');
		$poid = $pays[$paynew['poid']];
		$pay = new pay_base($paynew['poid']);
		$pay->account = $poid[0];
		$pay->keyt = $poid[1];
		$paynew['poid'] == 'alipay' && $pay->partner = $poid[2];
		$pay->by = 'pays';
		$pay->dataok = 1;
		$pay->order_id = $pid;
		$pay->status = 0;
		$pay->totalfee = $paynew['amount'];
		$pay->send($ordersn, lang('account_plaza', $hostname), '');
	}else{
		mcmessage('systemerror');
	}
}elseif($deal == 'receive'){
	empty($pid) && mcmessage('confirmchoosepays');
	if(!$item = $db->fetch_one("SELECT * FROM {$tblprefix}pays WHERE pid=$pid")) mcmessage('choosepayrecord');
	$flagarr = array(
	0 => lang('member cash pay saving succeed !'),
	2 => lang('from online pay interface goback pay failed message'),
	3 => lang('pay mount and record not same , please wait administrator deal !'),
	4 => lang('arrived pay record , please dont repeat operate'),
	5 => lang('cash arrived , member currency auto saving not succeed , please notice administrator !'),
	6 => lang('cash arrived , auto saving function closed , please wait administrator check !'),
	);
	tabheader(lang('online pay message look'));
	trbasic(lang('pay result state'),'',$flagarr[$flag],'');
	trbasic(lang('pay amount ( rmbi )'),'',$item['amount'],'');
	trbasic(lang('handfee ( rmbi )'),'',$item['handfee'],'');
	trbasic(lang('pay interface'),'',$item['poid'] ? $poids[$item['poid']] : '-','');
	trbasic(lang('pay orders idsn'),'',$item['ordersn'] ? $item['ordersn'] : '-','');
	trbasic(lang('message send time'),'',date("$dateformat $timeformat",$item['senddate']),'');
	trbasic(lang('cash arrive time'),'',$item['receivedate'] ? date("$dateformat $timeformat",$item['receivedate']) : '-','');
	trbasic(lang('currency saving time'),'',$item['transdate'] ? date("$dateformat $timeformat",$item['transdate']) : '-','');
	tabfooter();
}
?>