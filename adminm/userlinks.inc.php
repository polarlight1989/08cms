<?
!defined('M_COM') && exit('No Permission');
load_cache('utypes,userurls');
if(!empty($utid)){
	if(empty($utypes[$utid]) || !empty($utypes[$utid]['pid'])) mcmessage('chooseuserurlparam');
	if(!$curuser->pmbypmids('menu',$utypes[$utid]['pmid'])) mcmessage('nopermission');
	m_sites("?action=userlinks&utid=$utid");
	if(in_array($sid,$utypes[$utid]['sids'] === '' ? array() : explode(',',$utypes[$utid]['sids']))) mcmessage('nopermission');
	foreach($utypes as $k => $v){
		if($v['pid'] == $utid && $curuser->pmbypmids('menu',$v['pmid']) && !in_array($sid,$v['sids'] === '' ? array() : explode(',',$v['sids']))){
			$num = 4;
			$i = 0;
			tabheader($v['title'],'','',$num);
			foreach($userurls as $uid => $userurl){
				if($userurl['utid'] == $k){
					if($curuser->pmbypmids('menu',$userurl['pmid']) && !in_array($sid,$userurl['sids'] === '' ? array() : explode(',',$userurl['sids']))){
						if(!($i % $num)) echo "<tr align=\"center\">";
						echo "<td class=\"item2\" width=\"".(intval(100 / $num))."%\"><a href=\"$userurl[url]".(empty($userurl['actsid']) ? '' : $param_suffix)."\"".(empty($userurl['newwin']) ? '' : ' target="_blank"').">$userurl[title]</a></td>\n";
						$i ++;
						if(!($i % $num)) echo "</tr>\n";
					}
				}
			}
			if($i % $num){
				while($i % $num){
					echo "<td class=\"item2\" width=\"".(intval(100 / $num))."%\"></td>\n";
					$i ++;
				}
				echo "</tr>\n";
			}
			tabfooter();
		}
	
	}
}

?>