<?
define('M_ANONYMOUS', TRUE);
include_once dirname(__FILE__).'/include/general.inc.php';
include_once dirname(__FILE__).'/include/common.fun.php';
if(empty($cmscached)){
	message(lang('dealing0 system cache , please wait ...'),'inscache.php?cmscached=1')
}elseif($cmscached == 1){
	rebuild_cache(-1);
	@unlink("./../install.php");
	message(lang('system cache create finish , enter admin backarea ...'),'inscache.php?cmscached=2')
}elseif($cmscached == 2){
	$url = './../admina.php';
	mheader("location:$url");
}

?>

