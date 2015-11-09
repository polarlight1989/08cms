<?php

//---------------------------------------------------------
//财付通中介担保支付应答（处理回调）示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once ('classes/PayResponseHandler.class.php');

/* 创建支付应答对象 */
$resHandler = new PayResponseHandler();

require_once(dirname(dirname(__FILE__)) . '/pay_base.php');
$pay = new pay_base('tenpay');
$pay->by = 'orders';
$pay->order_sn = $resHandler->getParameter("attach");
if(!$pay->getData()){
	echo "<br/>非法请求<br/>";
	exit;
}

$resHandler->setKey($pay->keyt);

//判断签名
if($resHandler->isTenpaySign()) {
	
	//交易单号
	$transaction_id = $resHandler->getParameter("transaction_id");
	
	//金额,以分为单位
	$total_fee = intval($resHandler->getParameter("total_fee")) / 100;
	
	//支付结果
	$pay_result = $resHandler->getParameter("pay_result");
	
	if( "0" == $pay_result ) {
	
		//------------------------------
		//处理业务开始
		//------------------------------
		
		//注意交易单不要重复处理
		//注意判断返回金额

		$pay->setStatus(PAY_FINISHED);

		//------------------------------
		//处理业务完毕
		//------------------------------	
		
		//调用doShow, 打印meta值跟js代码,告诉财付通处理成功,并在用户浏览器显示$show页面.
		$resHandler->doShow($pay->show_url());
	
	} else {
		//当做不成功处理
		echo "<br/>" . "支付失败" . "<br/>";
	}
	
} else {
	echo "<br/>" . "认证签名失败" . "<br/>";
}

//echo $resHandler->getDebugInfo();

?>