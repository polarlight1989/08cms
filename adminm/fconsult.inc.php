<?
!defined('M_COM') && exit('No Permission');
load_cache('fcatalogs,fchannels,currencys,');
include_once M_ROOT."./include/farcedit.cls.php";

$qstatearr = array(
'new' => lang('nosettle'),
'dealing' => lang('dealing'),
'end' => lang('settled'),
'close' => lang('closed'),
);
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.urlencode($forward);
$aedit = new cls_farcedit;
$aedit->set_aid($aid);
$aedit->basic_data();
!$aedit->aid && mcmessage('choosemessage');	
$chid = $aedit->archive['chid'];
$caid = $aedit->archive['caid'];
if(!($fcatalog = read_cache('fcatalog',$caid)) || !$fcatalog['cumode']) mcmessage('consultchannel');
if(!submitcheck('bfconsultadd')){
	tabheader(lang('consultbasemessage'),'fconsult',"?action=fconsult&aid=$aid$forwardstr",2,1,0,1);
	trbasic(lang('consulttitle'),'',$fcatalog['title'].'&nbsp; -&nbsp; '.$aedit->archive['subject']."&nbsp;&nbsp;<a href=\"?action=farchive&aid=".$aedit->archive['aid']."\" onclick=\"return floatwin('open_consult',this)\">>>".lang('detail')."</a>",'');
	trbasic(lang('addtime'),'',date("$dateformat $timeformat",$aedit->archive['createdate']),'');
	trbasic(lang('qstate'),'',@$qstatearr[$aedit->archive['qstate']],'');
	tabfooter();

	tabheader(lang('consultcommulist'));
	$query = $db->query("SELECT * FROM {$tblprefix}consults WHERE aid='$aid' ORDER BY cid");
	while($item = $db->fetch_array($query)){
		$cid = $item['cid'];
		trbasic('<b>'.$item['mname'].'</b>&nbsp; &nbsp; '.(empty($item['reply']) ? lang('consult') : lang('reply')).'&nbsp; :<br>'.date("$dateformat $timeformat",$item['createdate']),'','<br>'.$item['content'].'<br>&nbsp;','');
	}
	tabfooter();
	if(($aedit->archive['qstate'] != 'close')){
		tabheader(lang('continueconsult'),'fconsultadd','?action=consult&aid='.$aid.'&forward='.rawurlencode($forward));
		trbasic(lang('consultcontent'),'contentadd','','btextarea');
		tabfooter('bfconsultadd');
	}else{
		tabheader(lang('continueconsult'));
		trbasic(lang('consultcontent'),'',lang('overconsult'),'');
		tabfooter();
	}

}else{
	$aedit->archive['qstate'] == 'close' && mcmessage('thconiteclo',axaction(2,M_REFERER));
	$contentadd = empty($contentadd) ? '' : trim($contentadd);
	empty($contentadd) && mcmessage('datamissing',axaction(2,M_REFERER));
	$fcatalog['culength'] && $contentadd = cutstr($contentadd,$fcatalog['culength']);
	$contentadd = mnl2br(mhtmlspecialchars($contentadd));
	$db->query("INSERT INTO {$tblprefix}consults SET
				 aid='$aid', 
				 content='$contentadd', 
				 mid='$memberid', 
				 mname='".$curuser->info['mname']."', 
				 createdate='$timestamp'
				 ");
	$db->query("UPDATE {$tblprefix}farchives SET qstate='new',updatedate='$timestamp' WHERE aid='$aid'");
	mcmessage('addconsultsucceed',axaction(6,"?action=fconsult&aid=$aid"));
}

?>
