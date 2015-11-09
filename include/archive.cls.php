<?
include_once M_ROOT.'./include/parse.fun.php';
include_once M_ROOT.'./include/archive.fun.php';
class cls_archive{
	var $aid = 0;
	var $archive = array();
	var $channel = array();
	var $fields = array();
	var $detailed = 0;
	function __construct(){
		$this->cls_archive();
	}
	function cls_archive(){
	}
	function init(){
		$this->aid = 0;
		$this->archive = array();
		$this->channel = array();
		$this->fields = array();
		$this->detailed = 0;
	}
	function arcid($aid){
		global $db,$tblprefix;
		$this->init();
		$aid = max(0,intval($aid));
		if(!$this->archive = $db->fetch_one("SELECT a.*,s.*,r.* FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid LEFT JOIN {$tblprefix}archives_rec r ON r.aid=a.aid WHERE a.aid='$aid'")){
			return false;
		}
		$this->channel = read_cache('channel',$this->archive['chid']);
		$this->fields = read_cache('fields',$this->archive['chid']);
		cache_merge($this->channel,'channel',$this->archive['sid']);
		arc_checkend($this->archive);
		$this->aid = $this->archive['aid'];
		return true;
	}
	function detail_data(){
		global $db,$tblprefix;
		if($this->detailed) return;
		if($row = $db->fetch_one("SELECT * FROM {$tblprefix}archives_".$this->archive['chid']." WHERE aid='".$this->aid."'")){
			$this->archive = array_merge($this->archive,$row);
			$this->detailed = 1;
			unset($row);
		}
	}
	function urlpre($addno=0,$static=0){//分页url模印含{$page}的可变参数
		$novus = empty($this->channel['novus']) ? array() : explode(',',$this->channel['novus']);
		if(defined('WAP_MODE')){
			return view_url('wap/archive.php?aid='.$this->aid.($addno ? '&addno='.$addno : '').'&page={$page}');
		}else  return $static ? view_url(m_parseurl(arc_format($this->archive),array('addno' => arc_addno($addno,$this->channel['addnos'])))) : view_url(en_virtual('archive.php?aid='.$this->aid.($addno ? '&addno='.$addno : '').'&page={$page}',1,@$novus[$addno]));
	}
	function m_urlpre($addno=''){
		global $mspaceurl;
		return $mspaceurl.en_virtual('archive.php?mid='.$this->archive['mid'].'&aid='.$this->aid.($addno ? '&addno='.$addno : '').'&page={$page}',1);
	}
	function filepre($addno=0){
		return m_parseurl(arc_format($this->archive),array('addno' => arc_addno($addno,$this->channel['addnos'])));
	}
	function update_needstatic($addnos = array(),$next = 0){
		global $archivecircle,$timestamp,$db,$tblprefix;
		if(!is_array($addnos)) $addnos = array($addnos);
		if(!$addnos) return;
		$needstatics = explode(',',$this->archive['needstatics']);
		$periods = explode(',',$this->channel['periods']);
		$nneedstatics = '';
		for($i = 0;$i <=$this->channel['addnum'];$i++){
			$period = $next ? (empty($periods[$i]) ? (!$archivecircle ? 86400*365 : $archivecircle * 60) : $periods[$i] * 60) : 0;
			$nneedstatics .= (in_array($i,$addnos) ? $timestamp + $period : @$needstatics[$i]).',';
		}
		$db->query("UPDATE {$tblprefix}archives_sub SET needstatics='$nneedstatics' WHERE aid='".$this->aid."'");
	}
	function arc_crids($isatm = 0){//计算当前文档或附件对当前用户所需要扣除的积分值数组,需要分别统计税、售、合计等三套积分。
		global $curuser,$catalogs,$cotypes,$vcps;
		if($curuser->info['mid'] && $curuser->info['mid'] == $this->archive['mid']) return 0;//自已的文章
		if(!$this->archive['checked']) return 0;//未审核的文章
		if($curuser->paydeny($this->aid,$isatm)) return 0;//已经付费或免费订阅的用户

		//统计扣值设置
		$retarr = $crids = array();
		$tax = !$isatm ? 'tax' : 'ftax';
		if(($catalog = read_cache('catalog',$this->archive['caid'],'',$this->archive['sid'])) && $catalog[$tax.'cp'] && !empty($vcps[$tax][$catalog[$tax.'cp']])){
			$cparr = explode('_',$catalog[$tax.'cp']);
			$crids[$cparr[0]] = -$cparr[1];
		}
		foreach($cotypes as $k => $cotype){
			if(!empty($this->archive["ccid$k"]) && $cotype[$tax.'cp']){
				$coclass = read_cache('coclass',$k,$this->archive["ccid$k"]);
				if($coclass[$tax.'cp'] && !empty($vcps[$tax][$coclass[$tax.'cp']])){
					$cparr = explode('_',$coclass[$tax.'cp']);
					$crids[$cparr[0]] = isset($crids[$cparr[0]]) ? $crids[$cparr[0]] - $cparr[1] : -$cparr[1];
				}
			}
		}
		$crids && $retarr['tax'] = $retarr['total'] = $crids;
		unset($catalog,$coclass,$crids);

		//统计出售设置
		$sale = !$isatm ? 'sale' : 'fsale';
		if(@!empty($vcps[$sale][$this->archive[$sale.'cp']])){
			$cparr = explode('_',$this->archive[$sale.'cp']);
			$retarr['sale'][$cparr[0]] = -$cparr[1];
			$retarr['total'][$cparr[0]] = isset($retarr['total'][$cparr[0]]) ? $retarr['total'][$cparr[0]] - $cparr[1] : -$cparr[1];
		}
		return $retarr ? $retarr : 0;
	}
}

?>
