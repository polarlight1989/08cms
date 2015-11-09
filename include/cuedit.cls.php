<?
include_once M_ROOT."./include/arcedit.cls.php";
class cls_cuedit{
	var $cid = 0;
	var $aid = 0;
	var $cclass = '';
	var $info = array();
	var $commu = array();
	var $fields = array();
	var $citems = array();
	var $useredits = array();
	var $updatearr = array();
	var $func = 0;//是否需要处理函数
	
	function __construct(){
		$this->cls_cuedit();
	}
	function cls_cuedit(){
	}
	function init(){
		$this->cid = 0;
		$this->aid = 0;
		$this->info = array();
		$this->commu = array();
		$this->citems = array();
		$this->fields = array();
		$this->useredits = array();
		$this->updatearr = array();
		$this->func = 0;
	}
	function read($cid=0,$cclass='offer'){
		global $db,$tblprefix,$ofields,$rfields,$cfields;
		if(!in_array($cclass,array('offer','reply','comment',))) return $this->err_no(1);
		if(!$cid) return $this->err_no(1);
		if($this->info) return 0;
		if(!$this->info = $db->fetch_one("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject FROM {$tblprefix}".$cclass."s cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid WHERE cu.cid='$cid'")){
			return $this->err_no(1);//指定的交互记录不存在
		}
		$this->cid = $cid;
		$this->cclass = $cclass;
		if(!($this->aid = $this->info['aid'])) return $this->err_no(2);//文档主体不存在
		
		$channel = read_cache('channel',$this->info['chid']);
		if(!$channel[$cclass] || !($this->commu = read_cache('commu',$channel[$cclass]))) return $this->err_no(3);//当前文档指定的交互设置不存在

		cu_checkend($this->info,$cclass);
		$this->citems = empty($this->commu['setting']['citems']) ? array() : explode(',',$this->commu['setting']['citems']);
		$this->useredits = empty($this->commu['setting']['useredits']) ? array() : explode(',',$this->commu['setting']['useredits']);

		$fvar = substr($cclass,0,1).'fields';
		global $$fvar;
		foreach($$fvar as $k => $v){
			if(in_array($k,$this->citems)){
				$this->fields[$k] = $v;
				if($v['isfunc']) $this->func = 1;
			}
		}
		if($this->commu['func']) $this->func = 1;
		unset($channel,$v);
		return 0;
	}
	function err_no($errno=0){
		if($errno) $this->init();
		return $errno;
	}
	function delete($isuser=0){
		global $db,$tblprefix;
		if($isuser && in_array($this->cclass,array('comment','reply')) && $this->info['checked']) return;
		$db->query("UPDATE {$tblprefix}archives SET ".$this->cclass."s=GREATEST(0,".$this->cclass."s-1) WHERE aid='".$this->aid."'",'SILENT');
		$db->query("UPDATE {$tblprefix}members_sub SET ".$this->cclass."s=GREATEST(0,".$this->cclass."s-1) WHERE mid='".$this->info['mid']."'",'SILENT');
		$db->query("DELETE FROM {$tblprefix}".$this->cclass."s WHERE cid='".$this->cid."'",'SILENT');
		$this->init();
		return;
	}
	function avg_price(){
		global $db,$tblprefix,$timestamp;
		if(($this->cclass != 'offer') || !isset($this->updatearr['oprice']) || !$this->info['checked'] || empty($this->commu['setting']['average']) || empty($this->commu['setting']['ptable']) || empty($this->commu['setting']['pename'])) return;
		$aedit = new cls_arcedit;
		$aedit->set_aid($this->aid);
		$aedit->basic_data(0);
		$average = $db->result_one("SELECT AVG(oprice) FROM {$tblprefix}offers WHERE aid='".$this->aid."' AND checked='1' AND (enddate='0' OR enddate>'$timestamp')");
		$aedit->updatefield($this->commu['setting']['pename'],$average,$this->commu['setting']['ptable']);
		$aedit->updatedb();
	}
	function updatefield($var,$val){
		if($this->info[$var] != stripslashes($val)){
			$this->info[$var] = stripslashes($val);
			$this->updatearr[$var] = $val;
		}
	}
	function updatedb(){
		global $db,$tblprefix,$timestamp;
		if($this->func){
			$aedit = new cls_arcedit;
			$aedit->set_aid($this->aid);
			$aedit->detail_data(0);
			foreach($this->fields as $k => $v) if($v['isfunc']) $this->updatefield($k,field_func($v['func'],$this->info,$aedit->archive));
		}
		if(!empty($this->updatearr)){
			$this->updatearr['updatedate'] = $timestamp;
			$sqlstr = '';
			foreach($this->updatearr as $k => $v) $sqlstr .= ($sqlstr ? "," : "").$k."='".$v."'";
			if($sqlstr) $db->query("UPDATE {$tblprefix}".$this->cclass."s SET $sqlstr WHERE cid={$this->cid}");
			$this->avg_price();//平均价需要资料更改完成后写入
		}
		if($this->func){
			if($this->commu['func']) field_func($this->commu['func'],$this->info,$aedit->archive);
			unset($aedit);
		}
		$this->updatearr = array();
	}
}
?>
