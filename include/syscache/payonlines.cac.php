<?php
$payonlines = array (
  'chinabank' => 
  array (
    'enable' => 0,
    'cname' => '网银在线',
    'partner' => '',
    'key' => '',
    'percent' => 0,
    'send' => 'https://pay.chinabank.com.cn/select_bank',
    'receive' => 'payonline/chinabank.php',
  ),
  'alipay' => 
  array (
    'enable' => 0,
    'cname' => '支付宝',
    'partner' => '',
    'key' => '',
    'percent' => 0,
    'send' => 'http://www.alipay.com/cooperate/gateway.do',
    'receive' => 'payonline/alipay.php',
  ),
  'tenpay' => 
  array (
    'enable' => 0,
    'cname' => '财付通',
    'partner' => '',
    'key' => '',
    'percent' => 0,
    'send' => 'https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi?',
    'receive' => 'payonline/tenpay.php',
  ),
  '99bill' => 
  array (
    'enable' => 0,
    'cname' => '快钱',
    'partner' => '',
    'key' => '',
    'percent' => 0,
    'send' => 'https://www.99bill.com/webapp/receiveMerchantInfoAction.do?',
    'receive' => 'payonline/99bill.php',
  ),
  'cncard' => 
  array (
    'enable' => 0,
    'cname' => '云网支付',
    'partner' => '',
    'key' => '',
    'percent' => 0,
    'send' => 'https://www.cncard.net/purchase/getorder.asp?',
    'receive' => 'payonline/cncard.php',
  ),
) ;
?>