<?php
!defined('M_COM') && exit('No Permission');
load_cache('currencys');
include_once M_ROOT."./include/fields.cls.php";
if(!submitcheck('bpaymanager')){
	$curuser->detail_data();
	$pmodearr = array('0' => lang('paynext'),'1' => lang('paycurrency'),'2' => lang('payalipay'),'3' => lang('paytenpay'));
	$omodearr = array('0' => lang('be_confirm'),'1' => lang('no_confirm'));
	$payarr = array();
	for($i = 0; $i < 32; $i++)if($curuser->info['paymode'] & (1 << $i))$payarr[] = $i;
	for($i = 1; $i < 4; $i++)${"sp$i"} = $curuser->info["shipingfee$i"];
	tabheader(lang('mypaymode'),'paymanager','?action=paymanager',2,1,1);
	trbasic(lang('paymode'),'',makecheckbox('paymodenew[]',$pmodearr,$payarr),'');
	trbasic(lang('ordmode'),'',makeradio('ordermodenew',$omodearr,$curuser->info['ordermode']),'');
	trbasic('<input name="spmd[1]" type="checkbox" class="checkbox" value="1"'.($sp1<0?'':' checked="checked"').' />'.lang('shipingfee1'),'shipingfee[1]',$sp1<0?0:$sp1);
	trbasic('<input name="spmd[2]" type="checkbox" class="checkbox" value="1"'.($sp2<0?'':' checked="checked"').' />'.lang('shipingfee2'),'shipingfee[2]',$sp2<0?0:$sp2);
	trbasic('<input name="spmd[3]" type="checkbox" class="checkbox" value="1"'.($sp3<0?'':' checked="checked"').' />'.lang('shipingfee3'),'shipingfee[3]',$sp3<0?0:$sp3);
	trbasic(lang('alipay_account'),'alipay_account',$curuser->info['alipay'],'btext');
	trbasic(lang('alipay_partner'),'alipay_partner',$curuser->info['alipid']);
	trbasic(lang('alipay_keyt'),'alipay_keyt',$curuser->info['alikeyt'],'btext');
	trbasic(lang('tenpay_account'),'tenpay_account',$curuser->info['tenpay'],'btext');
	trbasic(lang('tenpay_keyt'),'tenpay_keyt',$curuser->info['tenkeyt'],'btext');

	$submitstr  = makesubmitstr('shipingfee[1]',0,'number',0,10);
	$submitstr .= makesubmitstr('shipingfee[2]',0,'number',0,10);
	$submitstr .= makesubmitstr('shipingfee[3]',0,'number',0,10);
	$submitstr .= makesubmitstr('alipay_account',0,'email',0,100);
	$submitstr .= makesubmitstr('alipay_partner',0,'number',16,16);
	tabfooter('bpaymanager');
	check_submit_func($submitstr);
}else{
	$pmode = 0;
	empty($paymodenew) && $paymodenew = array();
	foreach($paymodenew as $v)$pmode = $pmode | (1 << $v);
	foreach($shipingfee as $k => $v)$shipingfee[$k] = empty($spmd[$k])?-1:max(0, round(floatval($v),2));
	$alipay_account = substr(trim(strip_tags($alipay_account)), 0, 50);
	$alipay_partner = substr(trim($alipay_partner), 0, 16);
	is_numeric($alipay_partner) || $alipay_partner = '';
	$alipay_keyt = substr(trim(strip_tags($alipay_keyt)), 0, 50);
	$tenpay_account = substr(trim(strip_tags($tenpay_account)), 0, 50);
	$tenpay_keyt = substr(trim(strip_tags($tenpay_keyt)), 0, 50);
	$db->query("UPDATE {$tblprefix}members_sub SET
				 ordermode='$ordermodenew',
				 shipingfee1='$shipingfee[1]',
				 shipingfee2='$shipingfee[2]',
				 shipingfee3='$shipingfee[3]',
				 paymode='$pmode',
				 alipay='$alipay_account',
				 alipid='$alipay_partner',
				 alikeyt='$alipay_keyt',
				 tenpay='$tenpay_account',
				 tenkeyt='$tenpay_keyt'
				 WHERE mid=$memberid
				 ");
	mcmessage('paymodefinish','?action=paymanager');
}
?>
