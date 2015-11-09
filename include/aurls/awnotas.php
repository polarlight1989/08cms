<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('pageresult'));
	trbasic(lang('customapage'),'aurlnew[tplname]',$aurl['tplname'],'text',lang('agcustomapage'));
	$listsarr = array(
	'mname' => lang('member'),
	'award' => lang('award'),
	'spare' => lang('spare'),
	'appeals' => lang('appeals'),
	'answers' => lang('answers'),
	'adopts' => lang('adopts'),
	'catalog' => lang('catalog'),
	'channel' => lang('achannel'),
	'admin' => lang('admin'),//合辑管理工具
	);
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($aurl['setting']['lists']) ? array() : explode(',',$aurl['setting']['lists']),5),'',lang('agnoselect1'));
}else{
	foreach(array('lists',) as $var){
		$aurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$aurlnew['url'] = "?entry=awnotas&action=awnotasedit&nauid=$auid";
}
?>