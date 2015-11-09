<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT."./include/arcedit.cls.php";
$aid = empty($aid) ? 0 : max(0,intval($aid));
$forward = rawurlencode(M_REFERER);
!$aid && message('choosegoods');
empty($memberid) && message('nousnopurchasepermi');
$aedit = new cls_arcedit;
$aedit->set_aid($aid);
$aedit->basic_data();
empty($cid) && $cid = 0;
!($aid = $aedit->aid) && message('choosegoods');

!($commu = read_cache('commu',$aedit->channel['cuid'])) &&
(!$aedit->channel['offer'] || !($ocommu = read_cache('commu',$aedit->channel['offer'])) ||
!($commu = read_cache('commu',$ocommu['setting']['purchase']))) && message('noavailableitemoper');

$commu['cclass'] != 'purchase' && message('noavailableitemoper');
!$curuser->pmbypmids('cuadd',$commu['setting']['apmid']) && message('younoitempermis');

$goods = empty($m_cookie["goods_$memberid"]) ? array() : explode(';', $m_cookie["goods_$memberid"]);
$cartmaxlimited && count($goods)>$cartmaxlimited && message('carovermaxgoodamo',"cart.php?forward=$forward");
foreach($goods as $v){
	$tmp = explode(',', $v);
	($tmp[1] ? $tmp[0] == $cid : $tmp[0] == $aid) && message('goodalreadyexist',"cart.php?forward=$forward");
}
$cid && !($mid = $db->result_one("SELECT mid FROM {$tblprefix}offers WHERE aid=$aid AND cid = $cid")) && message('choosegoods');
$tmp = $cid ? "$cid,$mid,1" : "$aid,0,1";//第二个参数为商家id,0表网站商品，第3个参数为数量
msetcookie("goods_$memberid", empty($m_cookie["goods_$memberid"]) ? $tmp : $m_cookie["goods_$memberid"].';'.$tmp);
message('goodsaddfinish',"cart.php?forward=$forward");

?>