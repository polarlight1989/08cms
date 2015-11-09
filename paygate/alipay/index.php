<?php
/*
	*功能：设置商品有关信息及物流信息
	*版本：2.0
	*日期：2008-08-01
	'说明：
	'以下代码只是方便商户测试，提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	'该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
*/

require_once("classes/alipay_service.php");

function makeurl(&$pay){
	global $mcharset, $cms_abs;
	$parameter = array(
		"service"			=> "create_direct_pay_by_user",	//交易类型
		"partner"			=> $pay->partner,			//合作商户号
		"return_url"		=> $pay->return_url,		//同步返回
		"notify_url"		=> $pay->notify_url,		//异步返回
		"_input_charset"	=> $mcharset,				//字符集，默认为GBK
		"subject"			=> $pay->subject,			//商品名称，必填
		"body"				=> $pay->content,			//商品描述，必填
		"out_trade_no"		=> $pay->order_sn,			//商品外部交易号，必填（保证唯一性）
		"price"				=> $pay->totalfee,			//商品单价，必填（价格不能为0）
		"payment_type"		=> '1',						//默认为1,不需要修改
/*		"quantity"			=> '1',						//商品数量，必填

		"logistics_fee"		=> '0.00',					//物流配送费用
		"logistics_payment"	=> 'BUYER_PAY',				//物流费用付款方式：SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
		"logistics_type"	=> 'EXPRESS',				//物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)
*/
		"show_url"			=> $cms_abs,				//商品相关网站
		"seller_email"		=> $pay->account			//卖家邮箱，必填
	);
	$alipay = new alipay_service($parameter,$pay->keyt,'MD5', $mcharset, 'http');
	return $alipay->create_url();
}
?>

