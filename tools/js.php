<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
if(empty($tname)) mexit();
$_da = empty($data) ? array() : $data;
$querystr = md5($_SERVER['QUERY_STRING']);
$refarr = @parse_url($_SERVER['HTTP_REFERER']);
if(!$jsrefsource || empty($refarr['host']) || in_array($refarr['host'],explode("\r\n",$jsrefsource))){
	if($cachejscircle && empty($is_p)){
		$cachefile = htmlcac_dir('js','',1).cac_namepre($querystr).'.php';
		if(is_file($cachefile) && (filemtime($cachefile) > ($timestamp - $cachejscircle * 60))){
			js_write(read_htmlcac($cachefile));
			mexit();
		}
	}
	@extract($btags);
	@extract($_da);
	_aenter($_da,1);
	ob_clean();
	@include M_ROOT."template/$templatedir/pcache/js".(empty($is_p) ? '' : '_p')."_$tname.php";
	$_content = ob_get_contents();
	ob_clean();
	js_write($_content);
	$cachejscircle && empty($is_p) && save_htmlcac($_content,$cachefile);
}
mexit();
function tpl_permission($pmid=0){
	global $curuser;
	return $curuser->pmbypmids('tpl',$pmid);
}

?>
