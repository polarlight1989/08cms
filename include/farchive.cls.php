<?
include_once M_ROOT.'./include/farcedit.cls.php';
include_once M_ROOT.'./include/parse.fun.php';
class cls_farchive{
	var $aid = 0;
	var $archive = array();
	var $channel = array();
	var $catalog = array();
	var $page = 1;
	function __construct(){
		$this->cls_archive();
	}
	function cls_archive(){
	}
	function init(){
		$this->aid = 0;
		$this->archive = array();
		$this->channel = array();
		$this->catalog = array();
		$this->page = 1;
	}
	function arcid($aid){
		global $db,$tblprefix,$fcatalogs,$fchannels;
		$aid = max(0,intval($aid));
		if(!$aid) return false;
		if(!$this->archive = $db->fetch_one("SELECT * FROM {$tblprefix}farchives WHERE aid='$aid'")){
			$this->init();
			return false;
		}
		$this->aid = $this->archive['aid'];
		$this->channel = $fchannels[$this->archive['chid']];
		$this->catalog = read_cache('fcatalog',$this->archive['fcaid']);
		if($archive = $db->fetch_one("SELECT * FROM {$tblprefix}farchives_".$this->archive['chid']." WHERE aid='".$this->aid."'")){
			$this->archive = array_merge($this->archive, $archive);
			unset($archive);
		}
		arr_tag2atm($this->archive,'f');
		$this->archive['catalog'] = $fcatalogs[$this->archive['fcaid']]['title'];
		$this->archive['channle'] = $this->channel['cname'];
		return true;
	}
	function name_pre(){
		return date('dHis',$this->archive['createdate']).substr(md5($this->aid.$this->archive['createdate']),5,10);
	}
}
?>
