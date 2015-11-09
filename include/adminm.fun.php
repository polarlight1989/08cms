<?php
!defined('M_COM') && exit('No Permission');
define('M_MCENTER', TRUE);
@set_time_limit(0);

function m_guide($mguide){
	if(empty($mguide)) return;
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\" class=\"tabmain\">\n".
		"<tr class=\"header\"><td>".lang('guide')."</td></tr>\n".
		"<tr><td class=\"item2\">$mguide</td></tr></table>\n";
}
function m_sites($urlpre = '',$num = 5){
	global $sid,$infloat,$handlekey;
	$sidsarr = array(0 => lang('msite')) + sidsarr(1);
	$i = 0;
	tabheader(lang('selectsite'),'','',$num);
	foreach($sidsarr as $k => $v){
		if(!($i % $num)) echo "<tr>";
		echo "<td class=\"item2\" width=\"".intval(100 / $num)."%\">>>".($sid == $k ? "<b>$v</b>" : "<a href=\"$urlpre".($k ? "&sid=$k" : '')."\"".($infloat?" onclick=\"floatwin('open_$handlekey',this)\"":'').">$v</a>")."</td>\n";
		$i ++;
		if(!($i % $num)) echo "</tr>\n";
	}
	if($i % $num){
		while($i % $num){
			echo "<td class=\"item2\" width=\"".intval(100 / $num)."%\"></td>\n";
			$i ++;
		}
		echo "</tr>\n";
	}
	tabfooter();
}
function noedit($var = '',$otherfbd = 0){
	global $useredits,$freeupdate;
	empty($useredits) && $useredits = array();
	return !$otherfbd && ($freeupdate || in_array($var,$useredits)) ? '' : '&nbsp; <img src="images/common/lock.gif" align="absmiddle">';
}
function murl_nav($arr = array(),$current='',$numpl=8){//针对所选择的链接，高亮当前页
	echo "<div class=\"itemtitle\"><ul class=\"tab1 tab0 bdtop\">\n";
	foreach($arr as $k => $v){
		$nclassstr = 'td24'.($k == $current ? ' current' : '');
		echo "<li".($nclassstr ? " class=\"$nclassstr\"" : '')."><a href=\"$v[1]\"><span>$v[0]</span></a></li>\n";
	}
	echo "</ul></div><div class=\"blank15h\"></div>";
}
function mcmessage($key='', $url = ''){
	global $mmsgs,$mmsgforwordtime,$inajax,$infloat,$handlekey,$no_mcfooter,$message_class;
	$msnum = $mmsgforwordtime ? $mmsgforwordtime : 1250;
	$str = @$mmsgs[$key] ? $mmsgs[$key] : $key;
	if(($num = func_num_args())>2){
		$ars = func_get_args();
		array_splice($ars, 1, 1);
		$ars[0] = &$str;
		$str = call_user_func_array('sprintf',$ars);
	}
	$class = empty($message_class) ? 'tabmain' : $message_class;
	if($url) {
		if($infloat){
			if(preg_match('/^javascript:/',$url)){
				$str .= "<script type=\"text/javascript\" reload=\"1\">var t = $msnum;".substr($url,11)."</script>";
			}else{
				$str .= "<br><br><br><a href=\"$url\" onclick=\"return floatwin('update_$handlekey', this);\">".lang('clickhere')."</a><script type=\"text/javascript\" reload=\"1\">setDelay(\"floatwin('update_$handlekey', '$url');\",$msnum);</script>";
			}
		}elseif(!(strpos($url,'history') === false)){
			$str .= "<br><br><a href=\"javascript:$url\">[".lang('rightnowjump')."]</a><script>setTimeout('$url',$msnum);</script>";
		}else $str .= "<br><br><a href=\"$url\">[".lang('rightnowjump')."]</a><script>setTimeout(\"redirect('$url');\",$msnum);</script>";
	}
	$str .= '&nbsp; <a href="javascript:window.close();"'.($infloat?" onclick=\"return floatwin('close_$handlekey')\"":'').'>['.lang('closewindow').']</a>';
	$infloat && print('<div style="position:relative;margin-top:-20px">');
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"center\">\n".
		"<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" align=\"center\" class=\"$class\">\n".
		"<tr class=\"header\"><td>".lang('promptmessage')."</td></tr><tr><td height=\"120\" align=\"center\" valign=\"middle\">$str</td></tr></table>\n".
		"</td></tr></table>\n".
		"<div class=\"blank9\"></div>";
	$infloat && print('</div>');
	if($no_mcfooter){
		if($inajax)afooter();else mexit('</div></div></body></html>');
	}else mcfooter();
}

function mcfooter(){
global $copyright,$cms_power,$cms_icpno,$inajax,$infloat,$cms_version;
	if($inajax){
		afooter();
	}
	if(!$infloat){?>
			<div class="blank9"></div>
		</div></td>
	</tr>
</table>
			</div>
		</div><!--con_con-->
	</div><!--conBox-->
</div>
<div class="blank9"></div>
<div class="area lineheight200 copy">
Copyright &copy; 2008-2012 <a href="http://www.08cms.com" target="_blank">08cms.com</a> All rights reserved.<br />
Powered by 08CMS v<?=$cms_version?> Code &copy; 2008-2009 08cms.com Corporation
</div>
<!--</div>--><?php
}else echo '</div></div>';?>
</body>
</html>
<?php mexit();}?>