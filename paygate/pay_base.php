<?php
/*
 *	说明
 *		构造参数 $mode 为支付接口文件夹名
 *		文件夹内要求三个文件
 *			index.php 支付接口网关程序 入口函数为
 *      	   string makeurl(
 *					string	$order_no,	//订单号
 *					float	$total_fee,	//订单总额
 *					string	subject,		//标题，买家付款看到的提示
 *					string	content		//详细说明
 *				)
 *				要求返回支付接口网关地址
 *
 *			$pay->by.php 支付接口通知程序
 *				需要在程序中建立一个本对象实例，调用本对象的
 *				bool $pay->getData() 方法获得数据，本方法使用 order_no 属性查找，扩展接口在本函数中指定要获得的字段
 *				在验证合法后
 *				调用本对象的
 *				bool setStatus(
 *					integer	$status		//要设置的订单状态码 取值如下的常量定义
 *					string	$remark		//操作说明，非必需
 *				) 方法设置订单状态
 *
 *				
 *				
 *				
 */

define('PAY_DIR', dirname(__FILE__) . '/');//定义本文件的服务器绝对路径。以”/“结尾，下同
empty($db) && require_once(dirname(PAY_DIR) . '/include/general.inc.php');
include_once M_ROOT."./include/arcedit.cls.php";
define('PAY_PATH', $cms_abs.'paygate/');//定义本文件的URL绝对路径

error_reporting(0);

//订单状态常量
define('PAY_FAIL', -2);
define('PAY_FINISHED', -1);
define('PAY_WAIT_PAY', 1);
define('PAY_WAIT_GOODS', 2);
define('PAY_CONFIRM_GOODS', 3);

class pay_base{

	var $by;
	var $mode;
	var $service;
	var $notify_url;
	var $return_url;
	var $order_sn;
	var $order_id;
	var $status;
	var $totalfee;
	var $subject;
	var $content;
	var $dataok;

	function pay_base($mode){
		$this->__construct($mode);
	}

	function __construct($mode){
		$this->mode = $mode;
	}

	function send($sn, $subject, $content){
		$this->order_sn		= $sn;
		$this->subject		= $subject;
		$this->content		= $content;
		$this->service		= "$this->mode/index.php";
		$this->notify_url	= "$this->mode/$this->by.php";
		if(!is_dir(PAY_DIR.$this->mode) || !is_file(PAY_DIR.$this->service) || !is_file(PAY_DIR.$this->notify_url))
			$this->message(
				array(
					'title'		=>	'调用错误',
					'content'	=>	'指定支付接口不在在或不完整！'
				)
			);
		if(!$this->getData())
			$this->message(
				array(
					'title'		=>	'调用错误',
					'content'	=>	'获取指定参数失败！'
				)
			);
		$this->notify_url	= PAY_PATH . $this->notify_url;
		$this->return_url	= PAY_PATH . "$this->mode/return_$this->by.php";
		require_once(PAY_DIR.$this->service);
		if(!function_exists('makeurl'))
			$this->message(
				array(
					'title'		=>	'调用错误',
					'content'	=>	'未定义指定支付接口函数 makeurl！'
				)
			);
		if(!$url = makeurl($this))
			$this->message(
				array(
					'title'		=>	'调用错误',
					'content'	=>	'指定支付接口函数 makeurl 返回网关地址失败！'
				)
			);
	    ob_end_clean();
		$GLOBALS['gzipenable'] ? ob_start('ob_gzhandler') : ob_start();
		$this->show($url);
	}

	function getData(){
		if($this->dataok)return true;
		global $db, $tblprefix, $cfg_alipay, $cfg_alipay_partnerid, $cfg_alipay_keyt, $cfg_tenpay, $cfg_tenpay_keyt;
		switch($this->mode){
		case 'alipay':
			$fields = array(
				'alipay'		=> 'account',
				'alipid'		=> 'partner',
				'alikeyt'		=> 'keyt',
			);
			$system = array(
				'account'		=> $cfg_alipay,
				'partner'		=> $cfg_alipay_partnerid,
				'keyt'			=> $cfg_alipay_keyt,
			);
			break;
		case 'tenpay':
			$fields = array(
				'tenpay'		=> 'account',
				'tenkeyt'		=> 'keyt',
			);
			$system = array(
				'account'		=> $cfg_tenpay,
				'keyt'			=> $cfg_tenpay_keyt,
			);
			break;
		}
		switch($this->by){
		case 'orders':
			$tmp  = '';
			foreach($fields as $k => $v)$tmp .= ",$k as $v";
			$key  = 'tomid';
			$sql1 = "SELECT $key,mid,oid as order_id,state as status,totalfee FROM {$tblprefix}orders WHERE ordersn='$this->order_sn'";
			$sql2 = "SELECT " . substr($tmp, 1) . " FROM {$tblprefix}members_sub WHERE mid=";
			break;
		case 'pays':
			$sql1 = "SELECT pid as order_id,mid,amount as totalfee FROM {$tblprefix}pays WHERE ordersn='$this->order_sn'";
			$this->status = 0;
			break;
		default :
			$sql1 = '';
		}
		if($sql1 && $tmp = $db->fetch_one($sql1)){
			if(empty($key) || empty($tmp[$key]) || empty($sql2)){
				foreach($system as $k => $v)$this->$k = $v;
			}else{
				if(!$sql2 = $db->fetch_one($sql2 . $tmp[$key]))return false;
				foreach($sql2 as $k => $v)$this->$k = $v;
			}
			if(!empty($key) && !empty($tmp[$key]))unset($tmp[$key]);
			foreach($tmp as $k => $v)$this->$k = $v;
			$this->dataok = 1;
			return true;
		}else{
			return false;
		}
	}

	function setStatus($status, $remark = ''){
		global $db, $tblprefix, $timestamp, $onlineautosaving;
		switch($this->status = $status){
		case PAY_FINISHED ://交易完成
			$remark || $remark = '交易完成';
			break;
		case PAY_FAIL ://交易失败
			$remark || $remark = '交易失败';
			break;
		case PAY_WAIT_PAY ://等待付款
			$remark || $remark = '等待付款';
			break;
		case PAY_WAIT_GOODS ://等待发货
			$remark || $remark = '等待发货';
			break;
		case PAY_CONFIRM_GOODS ://等待买家确认收货
			$remark || $remark = '等待买家确认收货';
			break;
		default :
			$this->message(
				array(
					'title'		=>	'调用错误',
					'content'	=>	"未定义的交易状态 “$status”！"
				)
			);
		}
		switch($this->by){
		case 'orders':
			$upsql = ",remark='" . addslashes($remark) . "'";
			($status == PAY_WAIT_GOODS || ($status == PAY_FINISHED && !$this->totalfee)) && $upsql .= ",payed=totalfee";
			$db->query("UPDATE {$tblprefix}orders SET state=$status,updatedate=$timestamp$upsql WHERE ($status<0 OR state<$status) AND oid='{$this->order_id}'");
			if($ret = $db->affected_rows()){
				$query = $db->query("SELECT aid,tocid as cid,price,nums FROM {$tblprefix}purchases WHERE oid='{$this->order_id}'");
				$nums = 0;
				$aedit = new cls_arcedit();
				while($row = $db->fetch_array($query)){
					$nums += $row['nums'];
					if($row['cid']){//商家商品
					}else{//网站商品
						$aedit->init();
						$aedit->set_aid($row['aid']);
						$aedit->arc_nums('orders', $row['nums']);
						$aedit->arc_nums('ordersum', $row['price'] * $row['nums'], 1);
					}
				}
				$user = new cls_userinfo();
				$user->activeuser($this->mid, 1);
				$user->basedeal('purchase', 1, $nums, 1);
			}
			break;
		case 'pays':
			$upsql = $onlineautosaving ? ",transdate=$timestamp" : '';
			$db->query("UPDATE {$tblprefix}pays SET receivedate=$timestamp$upsql WHERE receivedate=0 AND pid='{$this->order_id}'");
			$ret = $db->affected_rows();
			if($ret && $upsql){
				$user = new cls_userinfo();
				$user->activeuser($this->mid);
				$user->updatecrids(array(0 => $this->totalfee), 1);
			}
			break;
		default :
			$this->message(
				array(
					'title'		=>	'调用错误',
					'content'	=>	"未定义的交易！"
				)
			);
		}
		return $ret;
	}
	function show_url(){
		return M_ROOT . "tools/show.php?by=$this->by&sn=$this->order_sn&id=$this->order_id&status=$this->status";
	}
	function show($url = ''){
		$url || $url = $this->show_url();
		echo '<html><head>'.
			 '<meta http-equiv="expires" content="0">'.
			 '<meta http-equiv="Pragma" content="no-cache">'.
			 '<meta http-equiv="Cache-Control" content="no-cache">'.
			 '<meta http-equiv="refresh" content="0;url='.$url.'">'.
			 '</head><body></body></html>';
		exit;
	}

	function message($info){
	    ob_end_clean();
		$GLOBALS['gzipenable'] ? ob_start('ob_gzhandler') : ob_start();
		exit(
			"<html><body><span class=\"title\" style=\"color:red\">$info[title]</span><br /><span class=\"content\" style=\"line-height:150%\">$info[content]</span></body></html>"
		);
	}
}
?>