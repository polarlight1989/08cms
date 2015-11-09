<?php
load_cache('rprojects');
class linkparse{
	var $html = '';
	var $links = array();
	var $reflink = '';
	var $rpid = 0;
	var $jumpfile = '';
	function __construct(){
		$this->linkparse();
	}
	function linkparse(){
	}
	function setsource($html,$reflink,$rpid=0,$jumpfile=''){
		$this->html = $html;
		$this->reflink = $reflink;
		$this->rpid = $rpid;
		$this->jumpfile = $jumpfile;
	}
	function handlelinks(){
		$links = array();
		$aregions = array();
		$regex = "/<a(.+?)href\s*=\s*(\"(.+?)\"|'(.+?)'|(.+?)(\s|\/?>))/is";
		if(preg_match_all($regex,$this->html,$matches)){
			$aregions = array_filter(array_unique(array_merge($matches[3],$matches[4],$matches[5])));
			foreach($aregions as $aregion){
				$nregion = fillurl($aregion,$this->reflink);
				$links[] = $nregion;
				$regex1 = preg_quote($aregion,'/');
				$regex1 = "/href[ ]*=[ |'|\"]*".$regex1."[ |'|\"]+/is";
				$this->html = preg_replace($regex1,"href=\"$nregion\" ",$this->html);
			}
		}
		$regex = "/<[img|embed]([^<|>]+?)src\s*=\s*(\"(.+?)\"|'(.+?)'|(.+?)(\s|\/?>))/is";
		if(preg_match_all($regex,$this->html,$matches)){
			$aregions = array_filter(array_unique(array_merge($matches[3],$matches[4],$matches[5])));
			foreach($aregions as $aregion){
				$nregion = fillurl($aregion,$this->reflink);
				$nregion = view_url($this->remotefile($nregion));
				$links[] = $nregion;
				$regex1 = preg_quote($aregion,'/');
				$regex1 = "/src[ ]*=[ |'|\"]*".$regex1."[ |'|\"]+/is";
				$this->html = preg_replace($regex1,"src=\"$nregion\" ",$this->html);
			}
		}
		$this->links = $links;
		unset($links,$regex,$matches,$aregions,$nregion);
	}
	function handlelink($link){
		if(!$link) return '';
		$link = fillurl($link,$this->reflink);
		$link = $this->remotefile($link);
		return $link;
	}
	function remotefile($remotefile){
		global $c_upload;
		$filearr = $c_upload->remote_upload($remotefile,$this->rpid,$this->jumpfile);
		return $filearr['remote'];
	}		
}

?>
