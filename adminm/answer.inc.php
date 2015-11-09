<?
!defined('M_COM') && exit('No Permission');
include_once M_ROOT."./include/arcedit.cls.php";
load_cache('currencys,acatalogs');
$cid = empty($cid) ? 0 : max(0,intval($cid));
$cuid = $db->result_one("SELECT cuid FROM {$tblprefix}mcomments WHERE cid='$cid'");
if(!$cuid || !($commu = read_cache('commu',$cuid))) mcmessage('setcommuitem');
if(empty($commu['umdetail'])){
	$amode = empty($amode) ? 0 : max(0,intval($amode));
	if(!($answer = $db->fetch_one("SELECT * FROM {$tblprefix}answers WHERE cid=".$cid))) mcmessage('chooseanswer');
	$aedit = new cls_arcedit();
	$aedit->set_aid($answer['aid']);
	$aedit->basic_data();
	if(!$aedit->aid) mcmessage('choosearchive');
	if($memberid != ($amode ? $aedit->archive['mid'] : $answer['mid'])) mcmessage('chooseanswer');
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(!submitcheck('banswerdetail')){
		tabheader(lang('editanswer'),'answerdetail',"?action=answer&cid=$cid$forwardstr");
		trbasic(lang('questiontitle'),'',$aedit->archive['subject'],'');
		trbasic(lang('answercontent'),'answernew[answer]',br2nl($answer['answer']),'btextarea');
		tabfooter($amode ? '' : 'banswerdetail');
	}else{
		$commu = read_cache('commu',$aedit->channel['cuid']);
		($aedit->archive['closed'] || $aedit->archive['finishdate'] < $timestamp) && mcmessage('questionclose',axaction(2,M_REFERER));
		$answernew['answer'] = empty($answernew['answer']) ? '' : trim($answernew['answer']);
		empty($answernew['answer']) && mcmessage('inputanswer',axaction(2,M_REFERER));
		(!empty($commu['setting']['minlength']) && strlen($answernew['answer']) < $commu['setting']['minlength']) && mcmessage('answerlength',axaction(2,M_REFERER));
		!empty($commu['setting']['maxlength']) && $answernew['answer'] = cutstr($answernew['answer'],$commu['setting']['maxlength']);
		$db->query("UPDATE {$tblprefix}answers SET answer='$answernew[answer]' WHERE cid='$cid'");
		mcmessage('answereditfinish',axaction(6,$forward));
	}
}else include(M_ROOT.$commu['umdetail']);
?>