<?
load_cache('cotypes');
class cls_marcedit{
	var $maid = 0;
	var $matid = 0;
	var $archive = array();
	var $matype = array();
	var $auser = '';//作者
	var $updatearr = array();
	function __construct(){
		$this->cls_marchive();
	}
	function cls_marchive(){
	}
	function init(){
		$this->maid = 0;
		$this->matid = 0;
		$this->archive = array();
		$this->matype = array();
		$this->auser = '';
		$this->updatearr = array();
	}
	function set_id($maid=0,$matid=0,$auser=1){//将会员的模型id及关联类目读取进来
		global $db,$tblprefix,$cotypes;
		$this->maid = max(0,intval($maid));
		$this->matid = max(0,intval($matid));
		if(!$this->maid = max(0,intval($maid))) return;
		if(!$this->matid = max(0,intval($matid))) return;
		if(!$this->matype = read_cache('matype',$this->matid)) return;
		$sqlstr = 'm.mchid,m.caid';
		foreach($cotypes as $k => $v) $sqlstr .= ",m.ccid$k";
		if(!$this->archive = $db->fetch_one("SELECT ma.*,$sqlstr FROM {$tblprefix}marchives_{$this->matid} ma LEFT JOIN {$tblprefix}members m ON m.mid=ma.mid  WHERE maid='".$this->maid."'")){
			$this->init();
			return;
		}
		if($auser){
			$this->auser = new cls_userinfo;
			$this->auser->activeuser($this->archive['mid']);
		}
		return;
	}
	function check($checked=1,$updatedb=0){
		//如何解决分类收费属性变更的问题？比如由限期项目变为无限期项目，原先的很多项目会终止
		global $curuser,$timestamp;
		if(empty($this->maid) || $this->archive['checked'] == $checked) return;
		$this->updatefield('checked',$checked);
		$this->updatefield('editor',$curuser->info['mname']);
		$updatedb && $this->updatedb();
	}
	function delete($isuser=0){//会员中心的删除
		global $db,$tblprefix,$marchtmldir;
		if(empty($this->maid)) return;
		if($isuser && $this->archive['checked']) return; 
		if($this->archive['arcurl']) m_unlink($marchtmldir.'/'.substr($this->archive['arcurl'],0,-6).'{$page}.html');//删除相应的静态文件
		$db->query("DELETE FROM {$tblprefix}marchives_".$this->archive['matid']." WHERE maid='".$this->maid."'", 'UNBUFFERED');
		$this->init();
	}
	function updatefield($fieldname,$newvalue){
		if($this->archive[$fieldname] != stripslashes($newvalue)){
			$this->archive[$fieldname] = stripslashes($newvalue);
			$this->updatearr[$fieldname] = $newvalue;
		}
	}
	function updatedb(){
		global $db,$tblprefix;
		if(empty($this->maid)) return;
		$fields = read_cache('mafields',$this->matid);
		foreach($fields as $k => $v){
			if($v['isfunc']){
				$v = read_cache('mafield',$this->matid,$k);
				$this->updatefield($k,field_func($v['func'],$this->archive,$arr2=''));
			}
		}

		if(!empty($this->updatearr)){
			$sqlstr = '';
			foreach($this->updatearr as $k => $v) $sqlstr .= ($sqlstr ? "," : "").$k."='".$v."'";
			if(!empty($sqlstr)) $db->query("UPDATE {$tblprefix}marchives_{$this->matid} SET $sqlstr WHERE maid={$this->maid}");
		}
		$this->updatearr = array();
	}
}
?>
