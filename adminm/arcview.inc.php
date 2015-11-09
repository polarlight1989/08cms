<?php
include_once M_ROOT."./include/arcedit.cls.php";
include_once M_ROOT."./include/commu.fun.php";
load_cache('permissions,vcps,channels,cotypes,acatalogs');
!defined('M_COM') && exit('No Permission');
$aid = empty($aid) ? 0 : max(0,intval($aid));

//关于文档的个人分类
$uclasses = loaduclasses($curuser->info['mid']);
$ucidsarr = array();
foreach($uclasses as $k => $v) if(!$v['cuid']) $ucidsarr[$k] = $v['title'];

$aedit = new cls_arcedit;
$aedit->set_aid($aid);
$aedit->detail_data();
$chid = $aedit->archive['chid'];

tabheader(lang('basemessage'));
trbasic(lang('archivetitle'),'',$aedit->archive['subject'],'');
trbasic(lang('membercname'),'',$aedit->archive['mname'],'');
trbasic(lang('addtime'),'',date("Y-m-d H:i:s",$aedit->archive['createdate']),'');
trbasic(lang('updatetime'),'',date("Y-m-d H:i:s",$aedit->archive['updatedate']),'');
trbasic(lang('retime'),'',date("Y-m-d H:i:s",$aedit->archive['refreshdate']),'');
trbasic(lang('endtime'),'',$aedit->archive['enddate'] ? date("Y-m-d H:i:s",$aedit->archive['enddate']) : '-','');
trbasic(lang('checkstate'),'',($aedit->archive['checked'] ? lang('check') : lang('uncheck')).'&nbsp;&nbsp;/&nbsp;&nbsp;'.($aedit->archive['editor'] ? $aedit->archive['editor'] : '-'),'');
trbasic(lang('clickcomment'),'',$aedit->archive['clicks'].'&nbsp;&nbsp;/&nbsp;&nbsp;'.$aedit->archive['comments'],'');
tabfooter();
tabheader(lang('othermessage'));
trbasic(lang('channel'),'',$aedit->archive['chid'] ? $channels[$aedit->archive['chid']]['cname'] : '-','');
//trbasic(lang('browse archive deductvalue'),'',empty($aedit->archive['tax_price']) ? '-' : $aedit->archive['tax_price'],'');
//trbasic(lang('browse archive saleprice'),'',empty($aedit->archive['sale_price']) ? '-' : $aedit->archive['sale_price'],'');
//trbasic(lang('attachment deductvalue').'/'.lang('piece'),'',empty($aedit->archive['f_tax_price']) ? '-' : $aedit->archive['f_tax_price'],'');
//trbasic(lang('attachment saleprice').'/'.lang('piece'),'',empty($aedit->archive['f_sale_price']) ? '-' : $aedit->archive['f_sale_price'],'');
tabfooter();
?>
