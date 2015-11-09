<?
include_once M_ROOT.'./include/parse.fun.php';
class cls_marchive{
	var $maid = 0;
	var $matid = 0;
	var $archive = array();
	var $matype = array();
	var $page = 1;
	function __construct(){
		$this->cls_archive();
	}
	function cls_archive(){
	}
	function init(){
		$this->maid = 0;
		$this->matid = 0;
		$this->archive = array();
		$this->matype = array();
		$this->page = 1;
	}
	function arcid($maid=0,$matid=0){
		global $db,$tblprefix,$cotypes;
		$this->maid = max(0,intval($maid));
		$this->matid = max(0,intval($matid));
		if(!$this->maid = max(0,intval($maid))) return false;
		if(!$this->matid = max(0,intval($matid))) return false;
		if(!$this->matype = read_cache('matype',$this->matid)) return false;
		$sqlstr = 'm.mchid,m.caid';
		foreach($cotypes as $k => $v) $sqlstr .= ",m.ccid$k";
		if(!$this->archive = $db->fetch_one("SELECT ma.*,$sqlstr FROM {$tblprefix}marchives_{$this->matid} ma LEFT JOIN {$tblprefix}members m ON m.mid=ma.mid  WHERE maid='".$this->maid."'")){
			$this->init();
			return false;
		}
		arr_tag2atm($this->archive,'ma');
		return true;
	}
	function arc_mid($mid=0,$matid=0){//从会员id读取档案资料
		global $db,$tblprefix,$cotypes;
		if(!$mid = max(0,intval($mid))) return false;
		$this->matid = max(0,intval($matid));
		if(!$this->matid = max(0,intval($matid))) return false;
		if(!$this->matype = read_cache('matype',$this->matid)) return false;
		$sqlstr = 'm.mchid,m.caid';
		foreach($cotypes as $k => $v) $sqlstr .= ",m.ccid$k";
		if(!$this->archive = $db->fetch_one("SELECT ma.*,$sqlstr FROM {$tblprefix}members m LEFT JOIN {$tblprefix}marchives_{$this->matid} ma ON ma.mid=m.mid  WHERE mid='$mid'")){
			$this->init();
			return false;
		}
		if(!$this->maid = @$this->archive['maid']){
			$this->init();
			return false;
		}
		arr_tag2atm($this->archive,'ma');
		return true;
	}
}
?>
