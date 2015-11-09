<?php
!defined('M_COM') && exit('No Permission');
/////////////////////////////////////////////进行管理后台或会员中心中列表类管理页面的布局
//关键词的筛选固定下来。


//筛选区、列表区、操作区
//需要得到 $wheresql,$filterstr,$filters,$lists,$operates
//其实不需要想得太复杂，只要根据nauid及相应的管理权限，得到相应的范围数组即可，其它的内容还是在页面本身来体现。
//对于权限，就只有a_caids，
//页面范围有：isalbum，checked，valid，chids，atids，ccids18，ccids12




class cls_layout{
	//需要的结果内容
	var $wheresql,$filterstr;
	var $filters = array(),$lists = array(),$operates = array();

	//实际的页面传递参数
	var $nauid,$chid,$caid,$valid,$checked,$keyword,$viewdetail,$indays,$outdays;
	var $ccidarr = array();

	//页面管理最终范围值，根据页面定义，管理范围，及相应的下级运算后得到
	var $chids = array(),$caids = array(),

	function __construct(){
		$this->cls_layout();
	}
	function cls_layout(){
	}
}
?>