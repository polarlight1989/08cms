<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	$coidsarr = array();
	foreach($cotypes as $k => $v) if(!$v['self_reg']) $coidsarr[$k] = $v['cname'];
	trbasic(lang('view_coids')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallcoids\" onclick=\"checkall(this.form,'coidsnew','chkallcoids')\">".lang('selectall'),'',makecheckbox('coidsnew[]',$coidsarr,empty($aurl['setting']['coids']) ? array() : explode(',',$aurl['setting']['coids']),5),'');
	trbasic(lang('yes_arange').lang('achannel')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallchids\" onclick=\"checkall(this.form,'chidsnew','chkallchids')\">".lang('selectall'),'',makecheckbox('chidsnew[]',chidsarr(1),empty($aurl['setting']['chids']) ? array() : explode(',',$aurl['setting']['chids']),5),'',lang('agnoselect'));
	trbasic(lang('no_arange').lang('achannel')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallnochids\" onclick=\"checkall(this.form,'nochidsnew','chkallnochids')\">".lang('selectall'),'',makecheckbox('nochidsnew[]',chidsarr(1),empty($aurl['setting']['nochids']) ? array() : explode(',',$aurl['setting']['nochids']),5),'',lang('agnoselect'));
}else{
	foreach(array('coids','chids','nochids') as $var){
		$aurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$aurlnew['url'] = "?entry=addpre&nauid=$auid";
}
?>