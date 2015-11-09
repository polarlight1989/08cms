<?php
//---------------------------------------------------------
//财付通中介担保支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once ("classes/PayRequestHandler.class.php");

function makeurl(&$pay){
	global $mcharset, $cms_abs;

	$order_sn = $pay->account . date('Ymd0000') . substr($pay->order_sn, strlen($pay->order_sn) - 6, 6);
	
	/* 创建支付请求对象 */
	$reqHandler = new PayRequestHandler();
	$reqHandler->init();
	$reqHandler->setKey($pay->keyt);
	
	//----------------------------------------
	//设置支付参数
	//----------------------------------------
	$reqHandler->setParameter("bargainor_id", $pay->account);					//商户号
	$reqHandler->setParameter("sp_billno", $pay->order_id);						//商户订单号
	$reqHandler->setParameter("transaction_id", $order_sn);						//财付通交易单号
	$reqHandler->setParameter("total_fee", $pay->totalfee * 100);				//商品总价，单位为分
	$reqHandler->setParameter("return_url", $pay->notify_url);					//返回处理地址
	$reqHandler->setParameter("desc", $pay->subject);							//商品名称
	$reqHandler->setParameter("attach", $pay->order_sn);						//商家数据包，原样返回
	//用户ip,测试环境时不要加这个ip参数，正式环境再加此参数
	#$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);

	//请求的URL
	return $reqHandler->getRequestURL();
	//debug信息
	//$debugInfo = $reqHandler->getDebugInfo();
	
	//echo "<br/>" . $reqUrl . "<br/>";
	//echo "<br/>" . $debugInfo . "<br/>";
	
	//重定向到财付通支付
	//$reqHandler->doSend();
}
?>