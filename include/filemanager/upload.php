<?php
@set_time_limit(0);
include_once(dirname(__FILE__)."./../general.inc.php");
include_once M_ROOT."./include/upload.cls.php";
$c_upload = new cls_upload;
$uploadfile = $c_upload->local_upload('Newfile',$_GET['type']);
unset($c_upload);
if($uploadfile['error']){
	SendResults( '202' ) ;
}else{
	$sErrorNumber = '0';
	SendResults('0',tag2atm($uploadfile['remote'])) ;
}
function SendResults($errorNumber,$fileUrl='',$fileName='',$customMsg='')
{
	echo '<script type="text/javascript">' ;
	echo 'window.parent.OnUploadCompleted('.$errorNumber.',"'.str_replace('"','\\"',$fileUrl ).'","'.str_replace('"','\\"',$fileName).'","'.str_replace('"','\\"',$customMsg).'");';
	echo '</script>' ;
	mexit() ;
}

?>
