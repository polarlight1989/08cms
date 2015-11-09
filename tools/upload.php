<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
isset($type)||$type='images';
isset($mode)||$mode='';
isset($mincount)||$mincount='0';
isset($maxcount)||$maxcount='1';
isset($crc)||$crc='';
$lfile=substr($type,-1)=='s'?substr($type,0,-1):$type;
if(!$memberid && $mode == 'swf'){
	$cklen = strlen($ckpre);
	foreach($_POST as $k => $v)if(substr($k,0,$cklen) == $ckpre) $m_cookie[(substr($k,$cklen))] = QUOTES_GPC ? $v : maddslashes($v);
	unset($cklen);
	$curuser->init();
	$curuser->currentuser();
	$memberid = $curuser->info['mid'];
}
$allow = $memberid || $curuser->upload_capacity() == -1;
if(isset($action)&&$action=='upload'){
	$fn = @$_GET['CKEditorFuncNum'];
	$allow || fckEditor($fn, '-1');
	include(M_ROOT.'include/upload.cls.php');
	$up=new cls_upload();
	if($mode=='zip'){
		$ret=$up->zip_upload('Filedata',$lfile);
		if(empty($ret['error'])){
			$up->closure();
			echo"0|$ret[count]";
			foreach($ret['remote'] as $v)echo'|'.tag2atm($v);
		}else{
			echo $ret['error'];
		}
	}elseif($fn){
		$ret=$up->local_upload('upload',$lfile);
		empty($ret['error']) && $ret['error'] = 0;
		$ret['error'] || $up->closure();
		fckEditor($fn, $ret['error'], $ret['error'] ? '' : tag2atm($ret['remote']));
	}else{
		$ret=$up->local_upload('Filedata',$lfile);
		if(empty($ret['error'])){
			$up->closure();
			echo '0|'.tag2atm($ret['remote']);
		}else{
			echo $ret['error'];
		}
	}
	if(empty($ret['error']))$up->saveuptotal(1);
}else{
	load_cache('localfiles');
	$tmp=array_key_exists($lfile,$localfiles)?$localfiles[$lfile]:array();
	$otype='';foreach($tmp as $v)if($v['islocal'])$otype.=",\"$v[extname]\":[$v[minisize],$v[maxsize]]";$otype=substr($otype,1);
	$pa=array();
	if(!empty($player)){
		if(in_array($lfile,array('media','flash'))){
			load_cache('players');
			$pa[] = '[0,"默认播放器"]';
			foreach($players as $plid => $player)($player['available'] && $player['ptype'] == $lfile) && $pa[] = "[$plid,\"".str_replace('"',"\\\"",$player['cname']).'"]';
		}
	}
#	$canbrowser = !$atmbrowser || ($atmbrowser == '1' && $memberid) || ($atmbrowser == '2' && $curuser->isadmin());
	$canbrowser = $curuser->isadmin();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>附件上传 - <?=$hostname?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>" />
<link href="<?=$cms_abs?>include/upload/upload.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$cms_abs?>include/js/langs.js"></script>
<script type="text/javascript" src="<?=$cms_abs?>include/upload/swfupload.js"></script>
<script type="text/javascript" src="<?=$cms_abs?>include/upload/upload.js"></script>
<script type="text/javascript">
var swfu,tbButtons,tbItems,opt,retVal={},
	base='<?=$cms_abs?>',
	canupload=<?=$allow ? 1 : 0?>,
	canbrowser=<?=$canbrowser ? 1 : 0?>,
	swfurl=base+'include/upload/swfupload.swf',
	swfuurl=base+"tools/upload.php?action=upload&mode=swf&type=<?=$type?>",
	$params={<?="'{$ckpre}msid':Cookie('{$ckpre}msid'),'{$ckpre}userauth':Cookie('{$ckpre}userauth')"?>},
	$setkey='<?=$ckpre?>userauth',
	uploadtype='<?=$type?>',
	issingle=uploadtype.charAt(uploadtype.length-1)!='s',
	players=[<?=join(',',$pa)?>],
	filelimit={<?=$otype?>},
	mincount=parseInt('<?=$mincount?>'),
	maxcount=parseInt('<?=$maxcount?>'),
	filenumber=0,
	FileIndex=0,
	win_id = '<?=str_replace("'","\\'",empty($win_id) ? '' : $win_id)?>',
	field_id = '<?=str_replace("'","\\'",empty($field_id) ? '' : $field_id)?>';
if(isNaN(mincount))mincount=0;
if(isNaN(maxcount)||!maxcount)maxcount=9999;
$WE = parent.$WE || (opener && opener.$WE) || {elements:{}};
opt = $WE.elements[field_id]
if(!opt){
	alert(lang('init_field_err'));
	winclose();
}
</script>
</head>
<body>
<div id="loading">加载中...</div>
<table class="selecttable" width="600" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td id="selectbutton" class="selectitem" style="display:none"><a>附件管理</a><a>普通上传</a><a>批量上传</a><a>打包上传</a><a>远程附件</a><a>默认设置</a><a class="endselectitem"></a><br class="clear" /></td>
	</tr>
	<tr>
		<td class="selectbody" align="center">
			<div id="divOLDUploadUI" style="display:none">
				<p>请等待完成一种操作之后再进行下一种操作，以免发生结果混乱。</p>
				<p>
					<input type="button" value="保存返回" onclick="getReturn()" style="width:60px; height: 22px;" />
					<input type="button" value="取消返回" onclick="if(confirm('确定要取消对附件的上传以及编辑？'))winclose()" style="width:60px; height: 22px;" />
				</p>
				<span class="legend">附件管理</span>
				<div class="fieldset intable">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr style="display:none">
							<td>
								<form action="?action=upload&type=<?=$type?>" method="post" enctype="multipart/form-data">
									<p style="display:none"><label for="Filedata">本地上传：</label><input class="data" type="file" name="Filedata" id="Filedata" /> <input id="btsubmit" class="submit" type="submit" value=" 上 传 " /></p>
									<p><label for="Filepath">远程地址：</label><input class="path" type="text" id="Filepath" /><?=$canbrowser?' <input id="btmanager" class="button" type="button" value=" 浏 览 " />':''?> <input id="btdelete" class="button" type="button" value=" 删 除 " /></p>
									<p><label for="Fileremark">描述信息：</label><input class="text" type="text" id="Fileremark" /></p>
								</form>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="divNOMUploadUI" style="display:none">
				<p>完成上传后，请到附件管理选项卡中补充描述信息，并返还结果。</p>
				<p>
					<input id="nomUpload" type="button" value="启动上传" disabled="disabled" style="width:60px; height: 22px;" onclick="newfileupload(this)" />
					<span id="nomStatus" style="margin-left:10px">0</span>个文件已上传！
				</p>
				<span class="legend">上传队列</span>
				<div class="fieldset intable">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr style="display:none">
							<td>
								<form action="?action=upload&type=<?=$type?>" method="post" enctype="multipart/form-data">
									<p><label for="Filedata">选择文件：</label><input class="data" type="file" name="Filedata" id="Filedata" /></p>
									<p id="name" style="display:none">已选文件：<input class="filename" type="text" value="%s" readonly="readonly" /><input id="btdelete" class="button" type="button" value=" 删 除 " /></p>
									<p id="state" style="display:none">等待上传...</p>
								</form>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="divSWFUploadUI" style="display:none">
				<p>完成上传后，请到附件管理选项卡中补充描述信息，并返还结果。</p>
				<p>
					<span id="spanButtonPlaceholder"></span>
					<input id="swfSelect" type="button" value="选择文件" style="width:60px; height: 22px;" />
					<input id="swfUpload" type="button" value="启动上传" disabled="disabled" style="width:60px; height: 22px;" />
					<input id="swfCancel" type="button" value="中止上传" disabled="disabled" style="width:60px; height: 22px;" />
					<span id="swfStatus" style="margin-left:10px">0</span>个文件已上传！
				</p>
				<span class="legend">上传队列</span>
				<div class="fieldset flash" id="fsUploadProgress"></div>
				<br style="clear: both; display:none" />
				
				<noscript class="remark">
					<p>抱歉，SWFUpload无法加载。要使用SWFUpload必须启用JavaScript。</p>
					<p style="color:#999">We're sorry.  SWFUpload could not load.  You must have JavaScript enabled to enjoy SWFUpload.</p>
				</noscript>
				<div id="divLoadingContent" class="content remark" style="display: none;">
					<p>SWFUpload正在加载中，请稍候...</p>
					<p style="color:#999">SWFUpload is loading. Please wait a moment...</p>
				</div>
				<div id="divLongLoading" class="content remark" style="display: none;">
					<p>SWFUpload加载超时或加载失败。请确保安装了正确版本的Adobe Flash Player并且启用了Flash插件。</p>
					<p style="color:#999">SWFUpload is taking a long time to load or the load has failed.  Please make sure that the Flash Plugin is enabled and that a working version of the Adobe Flash Player is installed.</p>
				</div>
				<div id="divAlternateContent" class="content remark" style="display: none;">
					<p>抱歉，SWFUpload无法加载。您可能需要安装或升级您的Flash Player。</p>
					<p style="color:#999">We're sorry.  SWFUpload could not load.  You may need to install or upgrade Flash Player.</p>
					<p>访问<a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe网站</a>获得Flash Player。</p>
					<p style="color:#999">Visit the <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a> to get the Flash Player.</p>
				</div>
			</div>
			<div id="divZIPUploadUI" style="display:none">
				<p>完成上传后，请到附件管理选项卡中补充描述信息，并返还结果。</p>
				<p><input type="button" value="上传解压" onclick="zipfileupload(this);" style="width:60px; height: 22px;" /></p>
				<span class="legend">打包上传</span>
				<div class="fieldset intable">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td>
								<form action="?action=upload&mode=zip&type=<?=$type?>" method="post" enctype="multipart/form-data" onsubmit="return false">
									<p><label for="Filedata_zip">选择压缩包：</label><input class="data" type="file" name="Filedata" id="Filedata_zip" onchange="canzipupload(this);" /> <input id="btdelete" class="button" type="button" onclick="delzipupload(this);" value=" 清 除 " /></p>
									<p>说明：只支持zip格式压缩包，不支持rar等格式。</p>
									<p>上传后会解压到当前类型附件目录下，并自动重命名。</p>
									<p>上传完成后请请写描述信息，并保存返回文档表单。</p>
								</form>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="divRMTUploadUI" style="display:none">
				<p>完成添加后，请到附件管理选项卡中补充描述信息，并返还结果。</p>
				<p><input type="button" value="完成导入" onclick="addrmtfiles()" style="width:60px; height: 22px;" /></p>
				<span class="legend">远程附件</span>
				<div class="fieldset intable">
					<form class="rmtform" onsubmit="return false">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="65"><label for="rmtpath" style="vertical-align:top">远程地址：</label></td>
								<td><textarea id="rmtpath" wrap="off"></textarea></td>
							</tr><?php if($canbrowser){?>
							<tr>
								<td>&nbsp;</td>
								<td><input id="btmanager" class="button" type="button" value="浏览服务器" onclick="severmanager(this.form.rmtpath,managerfunc)" /></td>
							</tr><?php }?>
							<tr>
								<td>批量添加</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><label for="subrmt">地址：</label><input class="rmturl" type="text" id="subrmt" onblur="pathType.apply(this)" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><label for="subrmk">描述：</label><input class="rmturl" type="text" id="subrmk" onblur="remark.apply(this)" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>数字<label for="subfrom">从</label><input class="rmtnum" type="text" id="subfrom" /><label for="subto">到</label><input class="rmtnum" type="text" id="subto" />，<label for="subnum">通配符长度</label><input class="rmtnum" type="text" id="subnum" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input class="button" type="button" value=" 生 成 " onclick="makeString(this.form)" /></td>
							</tr>
							<tr>
								<td colspan="2">
说明：<br />
远程地址每行代表一个地址，可以手动输入，也可以点击浏览服务器上的附件<br />
格式，每行用竖线分割，第一段为地址，第二段为描述，第三段（如果有）为播放器ID。<br />
默认描述 是指浏览服务器添加的附件使用这个描述，如果批量添加描述未填写，也使用这里的描述<br /><br />
批量添加功能，可以方便的创建多个包含共同特征的远程地址。 例如网站A提供了10个这样的文件地址， <br />
	http://www.a.com/01.zip、 <br />
	http://www.a.com/02.zip <br />
	... <br />
	http://www.a.com/10.zip， <br /><br />
这10个地址只有数字部分不同，用<font color="red">(?)</font>表示不同的部分，这些地址可以写成： <br />
	http://www.a.com/(?).zip， <br /><br />
同时，通配符长度指的是这些地址不同部分数字的长度，<br />
	例如： <br />
	从01.zip－10.zip，那通配符长度就是2，<br />
	从001.zip－010.zip时通配符长度就是3。<br /></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			<div id="divSETUploadUI" style="display:none">
				<p>没有指定附属设置的所有新添加附件都使用此默认设置。</p>
				<p style=" height: 22px; line-height: 22px;">&nbsp;</p>
				<span class="legend">默认设置</span>
				<div class="fieldset intable">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td>
								<form onsubmit="return false">
									<p><label for="defremark">默认描述：</label><input class="rmturl" type="text" id="defremark" onblur="remark.apply(this)" /></p>
								</form>
							</td>
						</tr>
						<tr>
							<td>
									<p>说明：</p>
									<p>在所以的新添加操作中，没有设定描述的情况下，则使用这里的描述。</p>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</td>
	</tr>
</table>
<div class="iepadding"></div>
</body>
</html>
<?php
}
function fckEditor($fn, $message, $fileurl = ''){
	$fn && $message = empty($message) ? '' : ($message < 0 ? '不允许游客上传操作！' : '上传失败，文件类型或大小等不合法！');
	exit($fn ? "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction($fn, '$fileurl', '$message');</script>" : "$message");
}?>