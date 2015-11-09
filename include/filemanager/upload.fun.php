<?php
@set_time_limit(0);
include_once(dirname(__FILE__)."./../general.inc.php");
include_once M_ROOT."./include/upload.cls.php";
function fileUpload($resourceType,$currentFolder){
	$sErrorNumber = '0';
	$sfileName = '';
	$c_upload = new cls_upload;
	$c_upload->current_dir = $currentFolder;
	$uploadfile = $c_upload->local_upload('Newfile',$resourceType);
	unset($c_upload);
	if($uploadfile['error']){
		$sErrorNumber = '202' ;
	}else{
		$sErrorNumber = '0';
		$sfileName = tag2atm($uploadfile['remote']);
	}
	echo '<script type="text/javascript">' ;
	echo 'window.parent.frames["frmUpload"].OnUploadCompleted('.$sErrorNumber.',"'.str_replace('"','\\"',$sfileName).'");';
	echo '</script>' ;
	mexit() ;
}
?>
