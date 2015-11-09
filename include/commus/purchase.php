<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($commu['uconfig'])){
	if($action == 'commudetail'){
		if(empty($submitmode)){
			global $pfields;
			load_cache('pfields');
			trbasic(lang('operate_permi_set'),'communew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($commu['setting']['apmid']) ? 0 : $commu['setting']['apmid']),'select');
			$dcmodearr = array('0' => lang('discount_nov'),'1' => lang('maxmode'),'2' => lang('addmode'));
			trbasic(lang('discount2'),'communew[setting][gtmode]',makeoption($dcmodearr,isset($commu['setting']['gtmode']) ? $commu['setting']['gtmode'] : '0'),'select');
			$itemsarr = array();
			foreach($ucotypes as $k => $v) if($v['cclass'] == $commu['cclass']) $itemsarr['uccid'.$k] = $v['cname'];
			foreach($pfields as $k => $v) $itemsarr[$k] = $v['cname'];
			trbasic(lang('cu_citems')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_1\" onclick=\"checkall(this.form,'citemsnew','chkall_1')\">".lang('selectall'),'',makecheckbox('citemsnew[]',$itemsarr,empty($commu['setting']['citems']) ? array() : explode(',',$commu['setting']['citems']),5),'');
		}else{
			foreach(array('citems',) as $var) $communew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
		}
	}elseif($action == 'commulink'){
		trbasic(lang('purchase_pick_url'),'','{$cms_abs}tools/purchase.php?aid={aid}','');
		trbasic(lang('purchase_pick_url1'),'','{$cms_abs}tools/purchase.php?aid={aid}&cid={cid}','');
		trbasic(lang('cart_pick_url'),'','{$cms_abs}tools/cart.php','');
		trbasic(lang('goods_pu_record_url'),'','{$cms_abs}purchases.php?aid={aid}','');
	}
}else include(M_ROOT.$commu['uconfig']);


?>