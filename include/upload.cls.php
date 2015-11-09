<?php
!defined('M_COM') && exit('No Permission');
@set_time_limit(0);
load_cache('rprojects,localfiles');
include_once M_ROOT."./include/http.cls.php";
class cls_upload{
	var $current_dir = '';//被指定的上传保存路径,用于通过文件管理器中上传文件,格式为/xxx/
	var $ufids = array();//记录上传的文件id
	var $upload_size = 0;//记录上传及缩图的文件大小(K)
	var $capacity;//会员上传空间余量(K),-1为不限
	function __construct(){
		$this->cls_upload();
	}
	function cls_upload(){
		global $curuser;
		$this->capacity = $curuser->upload_capacity();
	}
	function init(){
		global $curuser;
		$this->current_dir = '';
		$this->ufids = array();
		$this->upload_size = 0;
		$this->capacity = $curuser->upload_capacity();
	}
	function local_upload($localname,$type='image'){
		global $curuser,$memberid,$_FILES,$localfiles,$dir_userfile,$db,$tblprefix,$timestamp,$ftp_enabled;
		$uploadfile = $result = array();
		$file_saved = false;
		$localfile = $localfiles[$type];
		foreach($localfile as $k => $v){
			if(empty($v['islocal'])){
				unset($localfile[$k]);
			}
		}
		if(!$_FILES[$localname] || !mis_uploaded_file($_FILES[$localname]['tmp_name']) || !$_FILES[$localname]['tmp_name'] || !$_FILES[$localname]['name'] || $_FILES[$localname]['tmp_name'] == 'none'){
			$result['error'] = 1;//'不存在的上传文件!'
			return $result;
		}
		$uploadfile = $_FILES[$localname];
		$localfilename = addslashes($uploadfile['name']);
		$extension = strtolower(mextension($uploadfile['name']));
		$uploadfile['mid'] = $curuser->info['mid'];
		$uploadfile['mname'] = $curuser->info['mname'];

		if(empty($localfile)){//本地上传方案为空
			@unlink($uploadfile['tmp_name']);
			$result['error'] = 1;
			return $result;
		}
		if($this->capacity != -1 && $uploadfile['size'] > 1024 * $this->capacity){//超过空间
			@unlink($uploadfile['tmp_name']);
			$result['error'] = 1;
			return $result;
		}
		if(!in_array($extension,array_keys($localfile))){//文件类型不在本地上传方案中
			@unlink($uploadfile['tmp_name']);
			$result['error'] = 2;//'禁止上传文件类型!'
			return $result;
		}
		if(!empty($localfile[$extension]['minisize']) && ($uploadfile['size'] < 1024 * $localfile[$extension]['minisize'])){
			@unlink($uploadfile['tmp_name']);
			$result['error'] = 3;//'超出该文件类型大小限制!'
			return $result;
		}
		if(!empty($localfile[$extension]['maxsize']) && ($uploadfile['size'] > 1024 * $localfile[$extension]['maxsize'])){
			@unlink($uploadfile['tmp_name']);
			$result['error'] = 3;//'超出该文件类型大小限制!'
			return $result;
		}
		
		$uploadfile['filename'] = preg_replace("/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl)(\.|$)/i", "_\\1\\2", date('dHis').substr(md5($localfilename.microtime()),5,10).random(4,1).'.'.$extension);
		$uploadpath = $this->upload_path($type);
		$uploadfile['url'] = $uploadpath.$uploadfile['filename'];
		$target = M_ROOT.$uploadpath.$uploadfile['filename'];
		@chmod($target, 0644);
		if(@copy($uploadfile['tmp_name'], $target) || (function_exists('move_uploaded_file') && @move_uploaded_file($uploadfile['tmp_name'], $target))) {
			@unlink($uploadfile['tmp_name']);
			$file_saved = true;
		}
	
		if(!$file_saved && @is_readable($uploadfile['tmp_name'])) {
			@$fp = fopen($uploadfile['tmp_name'], 'rb');
			@flock($fp, 2);
			@$filebody = fread($fp, $uploadfile['size']);
			@fclose($fp);
	
			@$fp = fopen($target, 'wb');
			@flock($fp, 2);
			if(@fwrite($fp, $filebody)) {
				@unlink($uploadfile['tmp_name']);
				$file_saved = true;
			}
			@fclose($fp);
		}
	
		if(!$file_saved){
			@unlink($uploadfile['tmp_name']);
			$result['error'] = 5;//'无效的文件上传!'
			return $result;
		}
		if(in_array($extension, array('jpg','jpeg','gif','png','swf','bmp'))){
			if(!$infos = @getimagesize($target)){
				@unlink($target);
				$result['error'] = 6;//'无效的图片上传!'
				return $result;
			}
			if(in_array($extension,array('jpg', 'jpeg', 'gif', 'png', 'bmp'))){
				if(isset($infos[0]) && isset($infos[1])){
					$result['width'] = $infos[0];
					$result['height'] = $infos[1];
				}
				if($this->image_watermark($target)){
					$uploadfile['size'] = filesize($target);
				}
			}
		}
		if($ftp_enabled){
			include_once M_ROOT."./include/ftp.fun.php";
			ftp_upload($target,$uploadfile['url']);
		}
		$this->upload_size += ceil($uploadfile['size'] / 1024);
		if($this->capacity != -1){
			$this->capacity -= ceil($uploadfile['size'] / 1024);
			$this->capacity = max(0,$this->capacity);
		}
		$db->query("INSERT INTO {$tblprefix}userfiles SET
					filename='$uploadfile[filename]',
					url='$uploadfile[url]',
					type='$type',
					createdate='$timestamp',
					mid='$uploadfile[mid]',
					mname='$uploadfile[mname]',
					size='$uploadfile[size]'
					");
		if($ufid = $db->insert_id()) $this->ufids[] = $ufid;
		$result['remote'] = $uploadfile['url'];
		$result['size'] = $uploadfile['size'];
		unset($uploadfile);
		return $result;

	}
	function remote_upload($remotefile,$rpid,$jumpfile='*'){//jumpfile为允许的跳转文件格式
		//返回数组
		global $rprojects,$curuser,$memberid,$dir_userfile,$db,$tblprefix,$timestamp,$ftp_enabled;
		$result = array('remote' => $remotefile);
		if(!$this->capacity) return $result;
		if(empty($rpid) || empty($rprojects[$rpid]['rmfiles'])) return $result;
		if(islocal($remotefile,1)) return $result;
		if(!empty($rprojects[$rpid]['excludes'])){
			foreach($rprojects[$rpid]['excludes'] as $k){
				if(in_str($k,$remotefile)) return $result;
			}
		}
		$rmfiles = $rprojects[$rpid]['rmfiles'];
		$extension = strtolower(mextension($remotefile));
		if(in_array($extension,array_keys($rmfiles))){
			$rmfile = $rmfiles[$extension];
		}else return $result;
		
		$uploadfile = array();
		$uploadfile['mid'] = $curuser->info['mid'];
		$uploadfile['mname'] = $curuser->info['mname'];
		$file_saved = false;
		$uploadfile['filename'] = preg_replace("/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl)(\.|$)/i", "_\\1\\2", date('dHis').substr(md5($remotefile.microtime()),5,10).random(4,1).'.'.$rmfile['extname']);
		$uploadpath = $this->upload_path($rmfile['ftype']);
		$uploadfile['url'] = $uploadpath.$uploadfile['filename'];
		$target = M_ROOT.$uploadpath.$uploadfile['filename'];
		@chmod($target, 0644);

		$m_http = new http;
		if($rprojects[$rpid]['timeout']) $m_http->timeout = $rprojects[$rpid]['timeout'];
		$file_saved = $m_http->savetofile($remotefile,$target,$rmfile['maxsize']);
		unset($m_http);
		
		if(!$file_saved){
			@unlink($target);
			return $result;
		}
		if(filesize($target) < $rmfile['minisize'] * 1024){
			@unlink($target);
			return $result;
		}
		$uploadfile['size'] = filesize($target);
		if(in_array($rmfile['extname'], array('jpg', 'jpeg', 'gif', 'png', 'swf', 'bmp'))){//图片或是flash
			if(!$infos = @getimagesize($target)){
				@unlink($target);
				return $result;
			}
			if(in_array($rmfile['extname'], array('jpg', 'jpeg', 'gif', 'png', 'bmp'))){
				if($this->image_watermark($target)) $uploadfile['size'] = filesize($target);
				$uploadfile['width'] = $infos[0];
				$uploadfile['height'] = $infos[1];
			}
		}
		if($ftp_enabled){
			include_once M_ROOT."./include/ftp.fun.php";
			ftp_upload($target,$uploadfile['url']);
		}
		$this->upload_size += ceil($uploadfile['size'] / 1024);
		if($this->capacity != -1){
			$this->capacity -= ceil($uploadfile['size'] / 1024);
			$this->capacity = max(0,$this->capacity);
		}
		$db->query("INSERT INTO {$tblprefix}userfiles SET
					filename='$uploadfile[filename]',
					url='$uploadfile[url]',
					type='$rmfile[ftype]',
					createdate='$timestamp',
					mid='$uploadfile[mid]',
					mname='$uploadfile[mname]',
					size='$uploadfile[size]'
					");
		if($ufid = $db->insert_id()) $this->ufids[] = $ufid;
		$result['remote'] = $uploadfile['url'];
		$result['size'] = $uploadfile['size'];
		if(isset($uploadfile['width']) && isset($uploadfile['height'])){
			$result['width'] = $uploadfile['width'];
			$result['height'] = $uploadfile['height'];
		}
		unset($uploadfile);
		return $result;
	}
	function zip_upload($localname,$type='image'){
		global $curuser,$memberid,$_FILES,$localfiles,$dir_userfile,$db,$tblprefix,$timestamp,$ftp_enabled;
		include_once 'include/zip.cls.php';
		$uploadfile = $result = array();
		$file_saved = false;
		$localfile = $localfiles[$type];
		foreach($localfile as $k => $v){
			if(empty($v['islocal'])){
				unset($localfile[$k]);
			}
		}
		if(!$_FILES[$localname] || !mis_uploaded_file($_FILES[$localname]['tmp_name']) || !$_FILES[$localname]['tmp_name'] || !$_FILES[$localname]['name'] || $_FILES[$localname]['tmp_name'] == 'none'){
			$result['error'] = 1;//'不存在的上传文件!'
			return $result;
		}
		$uploadfile = $_FILES[$localname];
		$localfilename = addslashes($uploadfile['name']);
		$uploadfile['mid'] = $curuser->info['mid'];
		$uploadfile['mname'] = $curuser->info['mname'];
		$uploadpath = $this->upload_path($type);
		$fuploadpath = M_ROOT.$uploadpath;

		if(empty($localfile)){//本地上传方案为空
			@unlink($uploadfile['tmp_name']);
			$result['error'] = 1;
			return $result;
		}
		if($this->capacity != -1 && $uploadfile['size'] > 1024 * $this->capacity){//超过空间
			@unlink($uploadfile['tmp_name']);
			$result['error'] = 1;
			return $result;
		}
		$zip=new PHPZip($uploadfile['tmp_name']);
		$lst=$zip->filelist();
		$result['count'] = count($lst);
		$ret=array();
		$capacity=1024 * $this->capacity;
		$size=0;
		foreach($lst as $z){
			if($z['folder']){
				$result['count']--;
				continue;
			}
			$extension = strtolower(mextension($z['name']));
			if(!in_array($extension,array_keys($localfile))){//文件类型不在本地上传方案中
				continue;
			}
			if(!empty($localfile[$extension]['minisize']) && ($z['size'] < 1024 * $localfile[$extension]['minisize'])){//'超出该文件类型大小限制!'
				continue;
			}
			if(!empty($localfile[$extension]['maxsize']) && ($z['size'] > 1024 * $localfile[$extension]['maxsize'])){//'超出该文件类型大小限制!'
				continue;
			}
			$size+=$z['size'];
			if($this->capacity != -1 && $size > $capacity)break;
			$ret[]=$z['index'];
		}
		if(empty($ret)){
			$result['error'] = -2;
			return $result;
		}
		$tzip="$fuploadpath{$memberid}_".random(6).'/';
		$lst=$zip->Extract($tzip,$ret);
		@unlink($uploadfile['tmp_name']);
		$ret=array();
		foreach($lst as $k => $v){
			if(substr($k,-1)=='/')continue;
			$uploadfile['filename'] = preg_replace("/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl)(\.|$)/i", "_\\1\\2", date('dHis').substr(md5($k.microtime()),5,10).random(4,1).'.'.$extension);
			$uploadfile['url'] = $uploadpath.$uploadfile['filename'];
			$target = $fuploadpath.$uploadfile['filename'];
			if(!rename($tzip.$k,$target))continue;
			$uploadfile['thumbed'] = 0;
			if(in_array($extension, array('jpg','jpeg','gif','png','swf','bmp'))){
				if(!$infos = @getimagesize($target)){
					@unlink($target);
					continue;
				}
				if(in_array($extension,array('jpg', 'jpeg', 'gif', 'png', 'bmp'))){
/*					if(isset($infos[0]) && isset($infos[1])){
						$result['width'] = $infos[0];
						$result['height'] = $infos[1];
					}*/
					if($this->image_thumb($target)){
						$uploadfile['thumbed'] = 1;
					}
					if($this->image_watermark($target)){
						$uploadfile['size'] = filesize($target);
					}
				}
			}
			if($ftp_enabled){
				include_once M_ROOT."./include/ftp.fun.php";
				ftp_upload($target,$uploadfile);
			}
			$this->upload_size += ceil($uploadfile['size'] / 1024);
			if($this->capacity != -1){
				$this->capacity -= ceil($uploadfile['size'] / 1024);
				$this->capacity = max(0,$this->capacity);
			}
			$db->query("INSERT INTO {$tblprefix}userfiles SET
					filename='$uploadfile[filename]',
					url='$uploadfile[url]',
					type='$type',
					createdate='$timestamp',
					mid='$uploadfile[mid]',
					mname='$uploadfile[mname]',
					size='$uploadfile[size]',
					thumbed='$uploadfile[thumbed]'");
			if($ufid = $db->insert_id()) $this->ufids[] = $ufid;
			$ret[] = $uploadfile['url'];
		}
		unset($uploadfile);
		clear_dir($tzip,1);
		$result['remote']=$ret;
		return $result;
	}
	function thumb_pick($string,$datatype='htmltext',$rpid=0){//只处理已经stripslashes的文本。
		if(!$string) return '';
		$thumb = '';
		if(in_array($datatype,array('text','multitext','htmltext'))){
			if(preg_match("/src\s*=\s*([\"']?)(.{1,100}?)(gif|jpg|jpeg|bmp|png)\\1/is",$string,$matches)){
				$thumb = $matches[2].$matches[3];
				$thumb = tag2atm($thumb);
				if(!islocal($thumb,1) && $rpid){
					$filearr = $this->remote_upload($thumb,$rpid);
					$thumb = $filearr['remote'];
				}
				if(isset($filearr['width'])){
					$thumb .= '#'.$filearr['width'].'#'.$filearr['height'];
				}elseif($infos = @getimagesize(local_file($thumb))){
					$thumb .= '#'.$infos[0].'#'.$infos[1];
				}
			}
		}elseif($datatype == 'images'){
			$images = @unserialize($string);
			if(is_array($images)){
				$image = $images[min(array_keys($images))];
				$image['remote'] = tag2atm($image['remote']);
				if(!islocal($image['remote'],1) && $rpid){
					$image = $this->remote_upload($image['remote'],$rpid);
				}
				$thumb = $image['remote'];
				isset($image['width']) && $thumb .= '#'.$image['width'].'#'.$image['height'];
			}
		}elseif($datatype == 'image'){
			$image = array_filter(explode('#',$string));
			$image[0] = tag2atm($image[0]);
			if(!islocal($image[0],1) && $rpid){
				$filearr = $this->remote_upload($image[0],$rpid);
				$image[0] = $filearr['remote'];
				if(isset($filearr['width'])){
					$image[1] = $filearr['width'];
					$image[2] = $filearr['height'];
				}
			}
			$thumb = $image[0];
			isset($image[1]) && $thumb .= '#'.$image[1].'#'.$image[2];
		}
		return save_atmurl($thumb);
	}
	function remotefromstr($string,$rpid){
		global $rprojects;
		if(!$this->capacity) return $string;
		if(empty($rpid) || empty($rprojects[$rpid]['rmfiles'])) return $string;
		if(!preg_match_all("/(href|src)\s*=\s*(\"(.+?)\"|'(.+?)'|(.+?)(\s|\/?>))/is",$string,$matches)){
			return $string;
		}
		$remoteurls = array_filter(array_merge($matches[3],$matches[4],$matches[5]));
		foreach($remoteurls as $k => $v){
			if(islocal($v,1)){
				unset($remoteurls[$k]);
			}elseif(!empty($rprojects[$rpid]['excludes'])){
				foreach($rprojects[$rpid]['excludes'] as $i){
					if(in_str($i,$v)){
						unset($remoteurls[$k]);
						break;
					}
				}
			}
		}
		$remoteurls = array_unique($remoteurls);
		$oldurls = $newurls = array();
		foreach($remoteurls as $oldurl){
			$filearr = $this->remote_upload($oldurl,$rpid);
			$newurl = $filearr['remote'];//本地路径的图片也要加上<!cmsurl />，这跟直接存数据库的附件是不一样的。
			if(strpos($newurl,'://') === false && strpos($newurl,'<!ftpurl />') === false) $newurl = '<!cmsurl />'.$newurl;
			if($newurl != $oldurl){
				$oldurls[] = $oldurl;
				$newurls[] = $newurl;
			}
		}
		return str_replace($oldurls,$newurls,$string);
	}
	function upload_path($type){//格式：userfiles/image/xxxx/
		global $dir_userfile,$path_userfile;
		$uploadpath = $dir_userfile.'/'.$type;
		if($this->current_dir){
			$uploadpath .= $this->current_dir;
		}else{
			if(empty($path_userfile)){
				$uploadpath .= '/';
			}elseif($path_userfile == 'month'){
				$uploadpath .= '/'.date('Ym').'/';
			}elseif($path_userfile == 'day'){
				$uploadpath .= '/'.date('Ymd').'/';
			}
		}
		mmkdir(M_ROOT.$uploadpath);
		return $uploadpath;
	}
	function saveuptotal($updatedb=0){//整个过程结束后再一次性的更新用户上传量
		global $curuser;
		if($this->upload_size){
			$curuser->updateuptotal($this->upload_size,'add',0);
		}
		$updatedb && $curuser->updatedb();
	}

	function image_resize($target = '',$to_w = 0,$to_h = 0,$tofile = ''){
		$tofile = !$tofile ? $target.'s/'.$to_w.'_'.$to_h.'.jpg' : $tofile;
		mmkdir($tofile,0,1);
		$info = @getimagesize($target);
		$thumbed = false;
		if(in_array($info['mime'], array('image/jpeg','image/gif','image/png'))){
			$from_w = $info[0];
			$from_h = $info[1];
			$isanimated = 0;
			if($info['mime'] == 'image/gif'){
				$fp = fopen($target, 'rb');
				$im = fread($fp, filesize($target));
				fclose($fp);
				$isanimated = strpos($im,'NETSCAPE2.0') === FALSE ? 0 : 1;
			}
			if(!$isanimated && ($from_w > $to_w || $from_h > $to_h)){
				switch($info['mime']) {
					case 'image/jpeg':
						$im = imagecreatefromjpeg($target);
						break;
					case 'image/gif':
						$im = imagecreatefromgif($target);
						break;
					case 'image/png':
						$im = imagecreatefrompng($target);
						break;
				}
				$to_scale = $to_w / $to_h;
				$from_scale = $from_w / $from_h;
				if($to_scale <= $from_scale){
					$cut_x = round(($from_w - ($from_h / $to_h) * $to_w ) / 2); 
					$cut_y = 0; 
					$cut_w = round(($from_h / $to_h ) * $to_w); 
					$cut_h = $from_h; 
				}else{
					$cut_x = 0; 
					$cut_y = round(($from_h - ($from_w / $to_w) * $to_h) / 2); 
					$cut_w = $from_w; 
					$cut_h = round(($from_w / $to_w) * $to_h); 
				}
				$fto_x = $fto_y = 0; 
				$fto_w = $cut_w  > $to_w ? $to_w : $cut_w; 
				$fto_h = $cut_w  > $to_w ? $to_h : $cut_h; 

				if(function_exists("imagecreatetruecolor")){
					if($im_n = imagecreatetruecolor($fto_w,$fto_h)){ 
						imagecopyresampled($im_n,$im,$fto_x,$fto_y,$cut_x,$cut_y,$fto_w,$fto_h,$cut_w,$cut_h); 
					}elseif($im_n = imagecreate($fto_w,$fto_h)){
						imagecopyresized($im_n,$im,$fto_x,$fto_y,$cut_x,$cut_y,$fto_w,$fto_h,$cut_w,$cut_h); 
					} 
				}else{ 
					$im_n = imagecreate($fto_w,$fto_h); 
					imagecopyresized($im_n,$im,$fto_x,$fto_y,$cut_x,$cut_y,$fto_w,$fto_h,$cut_w,$cut_h); 
				}
				
				imagejpeg($im_n,$tofile); 
				imagedestroy($im); 
				imagedestroy($im_n); 
				$thumbed = true;
			}
		}
		if(!$thumbed) @copy($target,$tofile);
		return;
	}
	function image_watermark($target){
		global $watermarkstatus,$watermarktype,$watermarktrans,$watermarkquality;
		$watermark_file = $watermarktype ? M_ROOT.'./images/common/watermark.png' : M_ROOT.'./images/common/watermark.gif';
		$imageinfo = getimagesize($target);
		$watermarked = false;
		if(in_array($imageinfo['mime'], array('image/jpeg', 'image/gif', 'image/png'))) {
			if($watermarkstatus) {
				$watermarkinfo	= getimagesize($watermark_file);
				$watermark_logo = $watermarktype ? imagecreatefrompng($watermark_file) : imagecreatefromgif($watermark_file);
				$logo_w		= $watermarkinfo[0];
				$logo_h		= $watermarkinfo[1];
				$img_w		= $imageinfo[0];
				$img_h		= $imageinfo[1];
				$wmwidth	= $img_w - $logo_w;
				$wmheight	= $img_h - $logo_h;
	
				$isanimated = 0;
				if($imageinfo['mime'] == 'image/gif') {
					$fp = fopen($target, 'rb');
					$imagebody = fread($fp, filesize($target));
					fclose($fp);
					$isanimated = strpos($imagebody, 'NETSCAPE2.0') === FALSE ? 0 : 1;
				}
	
				if(is_readable($watermark_file) && $wmwidth > 10 && $wmheight > 10 && !$isanimated) {
					switch($imageinfo['mime']) {
						case 'image/jpeg':
							$dst_photo = imagecreatefromjpeg($target);
							break;
						case 'image/gif':
							$dst_photo = imagecreatefromgif($target);
							break;
						case 'image/png':
							$dst_photo = imagecreatefrompng($target);
							break;
					}
	
					switch($watermarkstatus) {
						case 1:
							$x = +5;
							$y = +5;
							break;
						case 2:
							$x = ($img_w - $logo_w) / 2;
							$y = +5;
							break;
						case 3:
							$x = $img_w - $logo_w - 5;
							$y = +5;
							break;
						case 4:
							$x = +5;
							$y = ($img_h - $logo_h) / 2;
							break;
						case 5:
							$x = ($img_w - $logo_w) / 2;
							$y = ($img_h - $logo_h) / 2;
							break;
						case 6:
							$x = $img_w - $logo_w;
							$y = ($img_h - $logo_h) / 2;
							break;
						case 7:
							$x = +5;
							$y = $img_h - $logo_h - 5;
							break;
						case 8:
							$x = ($img_w - $logo_w) / 2;
							$y = $img_h - $logo_h - 5;
							break;
						case 9:
							$x = $img_w - $logo_w - 5;
							$y = $img_h - $logo_h - 5;
							break;
					}
	
					if($watermarktype) {
						imagecopy($dst_photo, $watermark_logo, $x, $y, 0, 0, $logo_w, $logo_h);
					} else {
						imagealphablending($watermark_logo, true);
						imagecopymerge($dst_photo, $watermark_logo, $x, $y, 0, 0, $logo_w, $logo_h, $watermarktrans);
					}
	
					switch($imageinfo['mime']) {
						case 'image/jpeg':
							imagejpeg($dst_photo, $target, $watermarkquality);
							break;
						case 'image/gif':
							imagegif($dst_photo, $target);
							break;
						case 'image/png':
							imagepng($dst_photo, $target);
							break;
					}
					$watermarked = true;
				}
			}
		}
		return $watermarked;
	}
	function rollback(){
		$this->closure(1);
	}
	function closure($clear = 0, $aid = 0, $table = 'archives'){
		global $db, $tblprefix, $curuser, $m_cookie;
		$ckey = $curuser->info['msid'] . '_upload';
		$ids = implode(',', $this->ufids);
		empty($m_cookie[$ckey]) || $ids = $m_cookie[$ckey] . ($ids ? ",$ids" : '');
		if($clear){
			//表ID对应数组
			$tids = array(
					'archives' => 1,
					'farchives' => 2,
					'members' => 3,
					'marchives' => 4,
					'comments' => 16,
					'replys' => 17,
					'offers' => 18,
					'mcomments' => 32,
					'mreplys' => 33,
			);
			$tid = $table && isset($tids[$table]) ? $tids[$table] : 0;
			//防止别人修改cookie注入MySQL
			if(preg_match('/^\d+(?:,\d+)*$/', $ids)){
				if($aid){
					$tid && $db->query("UPDATE {$tblprefix}userfiles SET aid=$aid,tid=$tid WHERE aid=0 AND ufid IN ($ids)", 'UNBUFFERED');
				}elseif($clear == 1){
					$query = $db->query("SELECT url FROM {$tblprefix}userfiles WHERE mid={$curuser->info['mid']} AND ufid IN ($ids)");
					while($item = $db->fetch_array($query)) @unlink(local_file($item['url']));
					$db->query("DELETE FROM {$tblprefix}userfiles WHERE ufid IN ($ids)", 'UNBUFFERED');
				}
			}
			msetcookie($ckey, '', -31536000);
		}else{
			msetcookie($ckey, $ids, 31536000);
		}
	}
}
?>
