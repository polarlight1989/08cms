<?
!defined('M_COM') && exit('No Permission');
class cls_thumb{
	var $config = array();
	var $records = array();
	function __construct(){
		$this->cls_thumb();
	}
	function cls_thumb(){
	}
	function init(){
	}
	function thumb($url = '',$source = '',$name = '',$type = -1,$width = 0,$height = 0){//如何生成不成功，则返回原url
		global $cms_abs,$cmsurl,$ftp_url,$atm_smallsite;
		if(!$url || !$source || !$name || !$width || !$height) return $url;
		include_once M_ROOT."./include/upload.cls.php";
		if($ftp_url && preg_match(u_regcode($ftp_url),$url)){//ftp上的文件
			include_once M_ROOT."./include/http.cls.php";
			include_once M_ROOT."./include/ftp.fun.php";
			//下载原图
			$tempfile = M_ROOT.'./dynamic/imcache/'.basename($url);
			mmkdir($tempfile,0,1);
			$m_http = new http;
			$m_http->savetofile($url,$tempfile);
			unset($m_http);
			//生成缩略图
			$m_upload = new cls_upload;
			$m_upload -> image_resize($tempfile,$width,$height,$tempfile.'.s.jpg');
			@unlink($tempfile);
			unset($m_upload);
			//上传缩略图
			$ftpfile = preg_replace(u_regcode($ftp_url),'',$url).'s/'.$width.'_'.$height.'.jpg';//根据url得到缩略上传到的位置
			$tempfile .= '.s.jpg';
			if(ftp_upload($tempfile,$ftpfile)){
				$this->refresh_record($source,$name,$type,$width,$height);//将缩略图规格写入数据库
				return $url.'s/'.$width.'_'.$height.'.jpg';
			}else return $url;
			@unlink($tempfile);
		
		}else{//本地服务器上的文件
			$m_upload = new cls_upload;
			$localfile = local_atm($url);
			$m_upload -> image_resize($localfile,$width,$height,$localfile.'s/'.$width.'_'.$height.'.jpg');
			unset($m_upload);
			return $url.'s/'.$width.'_'.$height.'.jpg';
		}
	}
	function refresh_record($source = '',$name = '',$type = -1,$width = 0,$height = 0){
		global $db,$tblprefix;
		if($source && $name && $this->config[$source]){//防止同一规格的缩略图信息重复写入
			$record = array('id' => $this->config[$source]['id'],'mode' => $this->config[$source]['mode'],'smode' => $this->config[$source]['smode'],'field' => $name,'fid' => $type,'width' => $width,'height' => $height,);
			if($record['id'] && $record['mode'] && !in_array($record,$this->records)){
				$this->records[] = $record;
				switch($record['mode']){
					case 'a':
						if(!$record['smode']) return;
						if(!$field = read_cache('field',$record['smode'],$record['field'])) return;
						$table = $field['tbl'] == 'main' ? 'archives' : 'archives_'.$record['smode'];
						$idvar = 'aid';
					break;
					case 'fa':
						if(!$record['smode']) return;
						$table = 'farchives_'.$record['smode'];
						$idvar = 'aid';
					break;
					case 'ma':
						if(!$record['smode']) return;
						$table = 'marchives_'.$record['smode'];
						$idvar = 'maid';
					break;
					case 'm':
						if(!$record['smode']) return;
						if(!$field = read_cache('mfield',$record['smode'],$record['field'])) return;
						$table = $field['tbl'] == 'sub' ? 'members_sub' : 'members_'.$record['smode'];
						$idvar = 'mid';
					break;
					case 'ca':
					break;
					case 'cc':
					break;
					case 'cu':
						if(!$record['smode']) return;
						if(!$commu = read_cache('commu',$record['smode'])) return;
						$table = $commu['cclass'].'s';
						$idvar = 'cid';
					break;
					case 'mcu':
						if(!$record['smode']) return;
						if(!$mcommu = read_cache('mcommu',$record['smode'])) return;
						$table = 'm'.$mcommu['cclass'].'s';
						$idvar = 'cid';
					break;
				}
				if(!$table || !$idvar) return;
				if($type == -1){
					$db->query("UPDATE {$tblprefix}$table SET $name = CONCAT($name,'#".($width.'_'.$height)."') WHERE $idvar='$record[id]'",'SILENT');
				}else{
					$result = $db->result_one("SELECT $name FROM {$tblprefix}$table WHERE $idvar='$record[id]'");
					if($result && ($result = @unserialize($result)) && is_array($result) && isset($result[$type])){
						$result[$type]['thumbs'] = empty($result[$type]['thumbs']) ? $width.'_'.$height : $result[$type]['thumbs'].'#'.$width.'_'.$height;
						$result = addslashes(serialize($result));
						$db->query("UPDATE {$tblprefix}$table SET $name = '$result' WHERE $idvar='$record[id]'",'SILENT');
					}
				}
			}
		}
		return;
	}
}
?>
