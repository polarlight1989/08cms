<?
class cls_mcuedit{
	var $cid = 0;
	var $mid = 0;//接收者
	var $fromid = 0;//发送者
	var $cclass = '';
	var $info = array();
	var $mcommu = array();
	var $fields = array();
	var $updatearr = array();
	var $func = 0;//是否需要处理函数
	
	function __construct(){
		$this->cls_mcuedit();
	}
	function cls_mcuedit(){
	}
	function init(){
		$this->cid = 0;
		$this->mid = 0;
		$this->fromid = 0;
		$this->info = array();
		$this->mcommu = array();
		$this->fields = array();
		$this->updatearr = array();
		$this->func = 0;
	}
	function read($cid=0,$cclass='reply'){
		global $db,$tblprefix,$mbfields,$mrfields,$mcfields,$mlfields,$mchannels;
		if(!in_array($cclass,array('report','reply','comment','flink'))) return $this->err_no(1);
		if(!$cid) return $this->err_no(1);
		if($this->info) return 0;
		if(!$this->info = $db->fetch_one("SELECT m.mchid,cu.* FROM {$tblprefix}m".$cclass."s cu LEFT JOIN {$tblprefix}members m ON m.mid=cu.mid WHERE cu.cid='$cid'")){
			return $this->err_no(1);//指定的交互记录不存在
		}
		$this->cid = $cid;
		$this->cclass = $cclass;
		if(!($this->mid = $this->info['mid'])) return $this->err_no(2);//主体不存在
		$this->fromid = $this->info['fromid'];
		if($this->cclass == 'report'){
			$cuid = 6;
			$fvar = 'mbfields';
		}elseif($this->cclass == 'flink'){
			$cuid = 3;
			$fvar = 'mlfields';
		}else{
			$mchid = $db->result_one("SELECT mchid FROM {$tblprefix}members WHERE mid='".$this->mid."'");
			$cuid = @$mchannels[$mchid][$cclass];
			$fvar = 'm'.substr($cclass,0,1).'fields';
		
		}
		if(!($this->mcommu = read_cache('mcommu',$cuid))) return $this->err_no(3);//交互设置不存在
		$fieldsarr = empty($this->mcommu['setting']['fields']) ? array() : explode(',',$this->mcommu['setting']['fields']);
		foreach($$fvar as $k => $v){
			if(in_array($k,$fieldsarr)) $this->fields[$k] = $v;
		}
		unset($v,$fieldsarr);
		return 0;
	}
	function err_no($errno=0){
		if($errno) $this->init();
		return $errno;
	}
	function delete($isuser=0){
		global $db,$tblprefix;
		if($isuser && in_array($this->cclass,array('comment','reply')) && $this->info['checked']) return;
		$db->query("DELETE FROM {$tblprefix}m".$this->cclass."s WHERE cid='".$this->cid."'",'SILENT');
		$this->init();
		return;
	}
	function updatefield($var,$val){
		if($this->info[$var] != stripslashes($val)){
			$this->info[$var] = stripslashes($val);
			$this->updatearr[$var] = $val;
		}
	}
	function updatedb(){
		global $db,$tblprefix,$timestamp;
		foreach($this->fields as $k => $v) if($v['isfunc']) $this->updatefield($k,field_func($v['func'],$this->info));
		if(!empty($this->mcommu['func'])) field_func($this->mcommu['func'],$this->info);
		if(!empty($this->updatearr)){
			$sqlstr = '';
			foreach($this->updatearr as $k => $v) $sqlstr .= ($sqlstr ? "," : "").$k."='".$v."'";
			if($sqlstr) $db->query("UPDATE {$tblprefix}m".$this->cclass."s SET $sqlstr WHERE cid={$this->cid}");
		}
		$this->updatearr = array();
	}
}
?>
