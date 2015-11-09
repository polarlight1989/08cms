<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/archive.cls.php';
include_once M_ROOT.'./include/follow.fun.php';

$aid = empty($aid) ? 0 : max(0,intval($aid));
!$aid && message('choosearchive');
$tname = empty($tname) ? '' : trim($tname);
empty($tname) && message('parammissing');
$temparr = array();
$temparr['tname'] = $tname;
$temparr['tmode'] = empty($tmode) ? 0 : 1;
$temparr['fid'] = empty($fid) ? 0 : max(0,intval($fid));
$arc = new cls_archive();
if(empty($auth)){
	follow_dynamic($aid,'down',$temparr);//统计全部统一到函数之中进行
}else{
	$midarr = explode("\t",authcode($auth,'DECODE'));
	if($midarr[0] == $memberid && $midarr[1] == $aid && $midarr[2] == $temparr['tname'] && $midarr[3] == $temparr['tmode'] && $midarr[4] == $temparr['fid']){
		$arc->arcid($aid);
		$arc->detail_data();
		if(!$arc->aid) message('choosearchive');
		if(!$arc->archive['checked']) message('poinarcnoche'); 

		if(empty($temparr['tmode'])){
			if($temp = @unserialize($arc->archive[$temparr['tname']])) $temp = @$temp[$temparr['fid']];
		}else $temp = @explode('#',$arc->archive[$temparr['tname']]);
		$url = view_atmurl(@$temp['remote']);
		unset($temp);
		empty($url) && message('noattach');
		down_url($url);
		mexit();
	}else message('attachdownerr');
}
?>
