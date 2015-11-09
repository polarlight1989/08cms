<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
!defined('M_COM') && exit();
empty($enabelstat) && exit();
include_once M_ROOT.'./include/counter.fun.php';
$strmsession = 'msclicks=msclicks+1';
if($curuser->info['mid']){
	if(empty($mclickcircle) || (!empty($mclickcircle) && $curuser->info['msclicks'] >= $mclickscircle)){
		$db->query("UPDATE {$tblprefix}members SET clicks=clicks+".($curuser->info['msclicks'] + 1)." WHERE mid='{$curuser->info['mid']}'", 'UNBUFFERED');
		$strmsession = "msclicks='0'";
	}
}
$db->query("UPDATE {$tblprefix}msession SET $strmsession WHERE msid='".$curuser->info['msid']."'", 'UNBUFFERED');

if(!empty($aid)){
	if($clickscachetime){
		$cachefile = M_ROOT.'./dynamic/stats/aclicks.cac';
		$cachetimefile = M_ROOT.'./dynamic/stats/aclicks_time.cac';
		if(($timestamp - @filemtime($cachetimefile)) > $clickscachetime){
			if(@$clicksarr = file($cachefile)){
				if(@$fp = fopen($cachefile,'w')) fclose($fp);//清空内容
				if(@$fp = fopen($cachetimefile,'w')) fclose($fp);
				$clicksarr = array_count_values($clicksarr);
				recent2main();
				foreach($clicksarr as $id => $clicks){
					if($clicks && $id = intval($id)){
						$db->query("UPDATE {$tblprefix}archives SET clicks=clicks+$clicks WHERE aid=$id",'UNBUFFERED');
						updaterecent($id,'clicks',$clicks);
					}
				}
			}
		}
		if(@$fp = fopen($cachefile,'a')){
			fwrite($fp,"$aid\n");
			fclose($fp);
		}
	}else{
		$db->query("UPDATE {$tblprefix}archives SET clicks=clicks+1 WHERE aid='$aid'", 'UNBUFFERED');
		recent2main();
		updaterecent($aid,'clicks',1);
	}
}
if(!empty($mid)){
	if($clickscachetime){
		$cachefile = M_ROOT.'./dynamic/stats/msclicks.cac';
		$cachetimefile = M_ROOT.'./dynamic/stats/msclicks_time.cac';
		if($timestamp - @filemtime($cachetimefile) > $clickscachetime){
			if($clicksarr = @file($cachefile)){
				if(@$fp = fopen($cachefile,'w')) fclose($fp);//清空内容
				if(@$fp = fopen($cachetimefile,'w')) fclose($fp);
				$clicksarr = array_count_values($clicksarr);
				foreach($clicksarr as $id => $clicks){
					if($clicks && $id = intval($id)){
						$db->query("UPDATE {$tblprefix}members_sub SET msclicks=msclicks+$clicks WHERE mid='$id'",'UNBUFFERED');
					}
				}
			}
		}
		if(@$fp = fopen($cachefile,'a')){
			fwrite($fp,"$mid\n");
			fclose($fp);
		}
	}else{
		$db->query("UPDATE {$tblprefix}members_sub SET msclicks=msclicks+1 WHERE mid='$mid'",'UNBUFFERED');
	}
}
album_statsum();
cn_statsum();
album_new();
exit();
?>