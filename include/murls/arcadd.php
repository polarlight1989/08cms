<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	$coidsarr = array();
	foreach($cotypes as $k => $v) if(!$v['self_reg']) $coidsarr[$k] = $v['cname'];
	trbasic(lang('view_coids')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallcoids\" onclick=\"checkall(this.form,'coidsnew','chkallcoids')\">".lang('selectall'),'',makecheckbox('coidsnew[]',$coidsarr,empty($murl['setting']['coids']) ? array() : explode(',',$murl['setting']['coids']),5),'');
	trbasic(lang('yes_arange').lang('achannel')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallchids\" onclick=\"checkall(this.form,'chidsnew','chkallchids')\">".lang('selectall'),'',makecheckbox('chidsnew[]',chidsarr(1),empty($murl['setting']['chids']) ? array() : explode(',',$murl['setting']['chids']),5),'',lang('agnoselect'));
	trbasic(lang('no_arange').lang('achannel')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallnochids\" onclick=\"checkall(this.form,'nochidsnew','chkallnochids')\">".lang('selectall'),'',makecheckbox('nochidsnew[]',chidsarr(1),empty($murl['setting']['nochids']) ? array() : explode(',',$murl['setting']['nochids']),5),'',lang('agnoselect'));
	trbasic(lang('adm_title'),'murlnew[mtitle]',$murl['mtitle'],'text',lang('aga_title'));
	trbasic(lang('adm_guide'),'murlnew[guide]',$murl['guide'],'textarea',lang('aga_title'));
}else{
	foreach(array('coids','chids','nochids') as $var){
		$murlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$murlnew['url'] = "tools/addpre.php?nmuid=$muid";
}
?>