<?php
include_once M_ROOT."./include/arcedit.cls.php";
load_cache('inmurls');
$aid = empty($aid) ? 0 : max(0,intval($aid));
empty($isat) && $isat = 0;
if(!$aid) mcmessage('confchoosarchi');
$aedit = new cls_arcedit;
$aedit->set_aid($aid);
$aedit->basic_data(0);
$channel = &$aedit->channel;
if(!$aedit->aid) mcmessage('confchoosarchi');
$imuids = $channel['imuids'] ? explode(',',$channel['imuids']) : array();
if(empty($imuids)) foreach($inmurls as $k => $v) $v['issys'] && $imuids[] = $k;
tabheader(lang('archiveadmin')." &nbsp; &nbsp;<a href=\"".view_arcurl($aedit->archive)."\" target=\"_blank\">>>".$aedit->archive['subject']."</a>");
foreach($imuids as $k){
	if(!empty($inmurls[$k]) && ($channel['isalbum'] || in_array($inmurls[$k]['uclass'],array('edit','setalbum','reply','answer','custom',)))){
		trbasic(">><a href=\"".$inmurls[$k]['url']."$aid\" onclick=\"return floatwin('open_newinarchive',this)\">".$inmurls[$k]['cname']."</a>",'',$inmurls[$k]['remark'],'');
	}
}
tabfooter();

?>
