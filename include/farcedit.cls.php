<?
class cls_farcedit{
	//需要重新检查收费与免费信息的处理过程的区别
	var $aid = 0;
	var $archive = array();
	var $channel = array();
	var $catalog = array();
	var $basiced = 0;
	var $detailed = 0;
	var $auser = '';//作者
	var $updatearr = array();
	function __construct(){
		$this->cls_farchive();
	}
	function cls_farchive(){
	}
	function init(){
		$this->aid = 0;
		$this->archive = array();
		$this->channel = array();
		$this->catalog = array();
		$this->basiced = 0;
		$this->detailed = 0;
		$this->auser = '';
		$this->updatearr = array();
	}
	function set_aid($aid){
		$this->aid = max(0,intval($aid));
	}
	function basic_data($auser=1){
		global $db,$tblprefix,$fcatalogs,$fchannels;
		if(empty($this->aid) || $this->basiced) return;
		if(!$this->archive = $db->fetch_one("SELECT * FROM {$tblprefix}farchives WHERE aid='".$this->aid."'")){
			$this->init();
			return;
		}
		$this->aid = $this->archive['aid'];
		$this->channel = $fchannels[$this->archive['chid']];
		$this->catalog = read_cache('fcatalog',$this->archive['fcaid']);
		if($auser){
			$this->auser = new cls_userinfo;
			$this->auser->activeuser($this->archive['mid']);
		}
		$this->basiced = 1;
	}
	function detail_data($auser=1){
		global $db,$tblprefix;
		if(empty($this->aid) || $this->detailed) return;
		$this->basic_data($auser);
		$customtable = 'farchives_'.$this->archive['chid'];
		if($archive = $db->fetch_one("SELECT * FROM {$tblprefix}$customtable WHERE aid='{$this->archive['aid']}'")){
			$this->archive = array_merge($this->archive, $archive);
		}
		arr_tag2atm($this->archive,'f');
		$this->detailed = 1;
		unset($archive);
	}
	function arc_check($checked=1,$updatedb=0){
		//如何解决分类收费属性变更的问题？比如由限期项目变为无限期项目，原先的很多项目会终止
		global $curuser,$timestamp;
		if(empty($this->aid)) return;
		$this->basic_data();
		if($this->archive['checked'] == $checked) return;
		$this->updatefield('checked',$checked,'main');
		$this->updatefield('editor',$curuser->info['mname'],'main');
		$updatedb && $this->updatedb();
	}
	function arc_delete($isuser=0){//会员中心的删除
		global $db,$tblprefix,$infohtmldir;
		if(empty($this->aid)) return;
		$this->basic_data();
		if($isuser && $this->archive['checked']) return; 
		$this->archive['arcurl'] && m_unlink($infohtmldir.'/'.substr($this->archive['arcurl'],0,-6).'{$page}.html');//删除相应的静态文件
		$db->query("DELETE FROM {$tblprefix}consults WHERE aid='".$this->aid."'");//删除咨询交互的内容
		$customtable = 'farchives_'.$this->archive['chid'];
		$db->query("DELETE FROM {$tblprefix}$customtable WHERE aid='".$this->aid."'", 'UNBUFFERED');
		$db->query("DELETE FROM {$tblprefix}farchives WHERE aid='".$this->aid."'", 'UNBUFFERED');
		$this->init();
	}
	function updatefield($fieldname,$newvalue,$mode='main'){
		if($mode == 'main' && !$this->basiced){
			$this->basic_data();
		}elseif($mode == 'custom' && !$this->detailed){
			$this->detail_data();
		}
		if($this->archive[$fieldname] != stripslashes($newvalue)){
			$this->archive[$fieldname] = stripslashes($newvalue);
			$this->updatearr[$mode][$fieldname] = $newvalue;
		}
	}
	function updatedb(){
		global $db,$tblprefix;
		if(empty($this->aid)) return;
		$this->basic_data();

		$fields = read_cache('ffields',$this->archive['chid']);
		foreach($fields as $k => $v){
			if($v['isfunc']){
				$this->detail_data();
				$v = read_cache('ffield',$this->archive['chid'],$k);
				$this->updatefield($k,field_func($v['func'],$this->archive,$arr2=''),'custom');
			}
		}

		foreach(array('main','custom') as $upmode){
			if(!empty($this->updatearr[$upmode])){
				$sqlstr = '';
				foreach($this->updatearr[$upmode] as $k => $v){
					$sqlstr .= ($sqlstr ? "," : "").$k."='".$v."'";
				}
				if(!empty($sqlstr)){
					$tablename = $upmode == 'main' ? 'farchives' : 'farchives_'.$this->channel['chid'];
					$db->query("UPDATE {$tblprefix}$tablename SET $sqlstr WHERE aid={$this->aid}");
				}
			}
		}
		$this->updatearr = array();
	}
}
?>
