<?php
!defined('M_COM') && exit('No Permission');
load_cache('grouptypes,currencys,mchannels,commus,mcommus');
$curuser->sub_data();

$usergroupstr = '';
foreach($grouptypes as $k => $v){
	if($curuser->info['grouptype'.$k]){
		$usergroups = read_cache('usergroups',$k);
		$usergroupstr .=  '<font class="cBlue">'.$usergroups[$curuser->info['grouptype'.$k]]['cname'].'</font> &nbsp;';
	}
}
$repugradestr = lang('yourrepugrade').' : <font class="cBlue">'.$repugrades[$curuser->info['rgid']]['cname'].($repugrades[$curuser->info['rgid']]['thumb'] ? '&nbsp; <img src="'.view_atmurl($repugrades[$curuser->info['rgid']]['thumb']).'" height="18">' : '').'</font>';
$currencystr=lang('cashaccount').' : <font class="cRed">'.$curuser->info['currency0'].'</font><font class="cBlue"> '.lang('yuan').'</font>&nbsp; ';
foreach($currencys as $v){
	$tmp = $curuser->info['currency'.$v['crid']];
	$currencystr .= " $v[cname] : <font class=\"cRed\">$tmp</font><font class=\"cBlue\"> $v[unit]</font>&nbsp; ";
}
$friendnum = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}mfriends WHERE mid='$memberid' AND checked=1");
$friendstr = '';
$query = $db->query("SELECT * FROM {$tblprefix}mfriends WHERE mid='$memberid' AND checked=1 ORDER BY cid DESC LIMIT 0,10");
while($row = $db->fetch_array($query)){
	$friendstr .= "<li><a href=\"{$mspaceurl}index.php?mid=$row[fromid]\" target=\"_blank\">$row[fromname]</a></li>";
}
$msgstr = '';
$row = $db->fetch_one("SELECT COUNT(pmid) AS pms,SUM(viewed) AS views FROM {$tblprefix}pms WHERE toid='$memberid'");
$msgstr .= '<tr><td>'.lang('receives').lang('pm')." : <font class=\"cRed\">$row[pms]</font></td>";
$msgstr .= '<td>'.lang('noread')." : <font class=\"cRed\">".($row['pms'] - $row['views'])."</font></td></tr>";
$query = $db->query("SELECT cuid,COUNT(cid) AS cids,SUM(uread) AS ureads FROM {$tblprefix}replys cu LEFT JOIN {$tblprefix}archives a ON cu.aid=a.aid WHERE a.mid='$memberid' GROUP BY cu.cuid");
while($row = $db->fetch_array($query)){
	$msgstr .= '<tr><td>'.lang('receives').@$commus[$row['cuid']]['cname']." : <font class=\"cRed\">$row[cids]</font></td>";
	$msgstr .= '<td>'.lang('noread')." : <font class=\"cRed\">".($row['cids'] - $row['ureads'])."</font></td></tr>";
}
$query = $db->query("SELECT cuid,COUNT(cid) AS cids,SUM(uread) AS ureads FROM {$tblprefix}mreplys WHERE mid='$memberid' GROUP BY cuid");
while($row = $db->fetch_array($query)){
	$msgstr .= '<tr><td>'.lang('receives').@$mcommus[$row['cuid']]['cname']." : <font class=\"cRed\">$row[cids]</font></td>";
	$msgstr .= '<td>'.lang('noread')." : <font class=\"cRed\">".($row['cids'] - $row['ureads'])."</font></td></tr>";
}
$statearr = array('0' => lang('wait_cpcheck'),'1' => lang('wait_pay'),'2' => lang('wait_send'),'3' => lang('goods_send'),'-1' => lang('order_ok'),'-2' => lang('order_cancel'));
$query = $db->query("SELECT state,COUNT(oid) AS orders FROM {$tblprefix}orders WHERE tomid='$memberid' GROUP BY state");
while($row = $db->fetch_array($query)){
	$msgstr .= '<tr><td>'.$statearr[$row['state']].lang('sorders')."</td><td><font class=\"cRed\">$row[orders]</font></td></tr>";
}

?>
		<div class="index_con">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
			<div class="basic left">
				<ul>
					<dl class="info1 left">
						<dt><font class="f18px txtindent12 left fB txtleft"><?=$curuser->info['mname']?></font><font class="right lineheight200 cGray"> <?=lang('lastloginip')?>:<?=$curuser->info['lastip']?> &nbsp; <?=lang('lastlogintime')?>:<?=date('Y-m-d H:i',$curuser->info['lastvisit'])?></font></dt>							
					</dl>
					<div class="blank6"></div>
					<dl class="info2 left txtleft lineheight300">
						<dd><?=lang('mb_state',$usergroupstr)?></dd>
						<dd><?=lang('mb_type','<font class="cBlue">'.$mchannels[$curuser->info['mchid']]['cname'].'</font>')?></dd>
						<dd><?=$repugradestr?></dd>
						<dd><?=$currencystr?><?php if(($commu = read_cache('commu',9)) && !empty($commu['available'])){?>&nbsp;<a id="get_spread" href="javascript:" onmouseover="return showInfo(this.id,'?action=spread')"><b style="color:red"><?=lang('spread_firend')?></b></a><?php }?></dd>
					</dl>
					<div class="blank18"></div>
					<ul class="info3 left">
						<h3 class="txtleft txtindent12"><font class="fB left info3txt1"><img src="images/adminm/message1.gif" width="22" height="18" align="absmiddle" /> <b><?=lang('mymsg')?></b></font><font class="right">>><a href="?action=pmsend"><?=lang('sendpm')?></a>&nbsp;</font></h3>
						<table width="100%" border="0" cellspacing="0" cellpadding="0"><?=$msgstr?></table>
					</ul>
				</ul>
				<div class="blank6"></div>
			</div>
					</td>
					<td width="265">
			<div class="info_Statistics borderleft txtleft">
				<ul>
					<h1 class="fB txtindent12"><?=lang('messagestat')?></h1>
					<div class="blank6"></div>
					<li><?=lang('addarcamount',$curuser->info['archives'])?></li>
					<li><?=lang('issuearcamount',$curuser->info['checks'])?></li> 
					<li><?=lang('membercomments',$curuser->info['comments'])?></li>
					<li><?=lang('arcsubscribeamount',$curuser->info['subscribes'])?></li>
					<li><?=lang('adjsubscribeamount',$curuser->info['fsubscribes'])?></li>
					<li><?=lang('uploadedadjunct',$curuser->info['uptotal'])?></li>
					<li><?=lang('downloadedadjunct',$curuser->info['downtotal'])?></li>
				</ul>
				<ul>
					<h1 class="fB txtindent12"><?=lang('friendlist')?>(<?=$friendnum?>)</h1>
					<div class="blank6"></div>
					<?=$friendstr?>
				</ul>
				<div class="blank9"></div>
			</div>
					</td>
				</tr>
			</table>
		</div>

	