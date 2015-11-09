<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
function missionfield($cname,$ename,$setting=array(),$datatype='text'){
	global $rprojects;
	$mcell = in_array($datatype,array('images','files','flashs','medias')) ? 1 : 0;//是否是多集模式字段
	$noremote = in_array($datatype,array('int','float','select','mselect','date')) ? 1 : 0;//是否不存在附件下载因素的字段
	${'clearhtml'.$ename} = (isset($setting['clearhtml']) && !$mcell) ? explode(',',$setting['clearhtml']) : array();
	$rpidsarr = array('0' => lang('notremote'));
	foreach($rprojects as $rpid => $rproject){
		$rpidsarr[$rpid] = $rproject['cname'];
	}
	$frompagearr = array('0' => lang('based_content_page0'),'1' => lang('netsilistpage'),'2' => lang('content_trace_page0_1'),'3' => lang('content_trace_page0_2'));
	
	echo "<tr class=\"category\"><td><b>[".mhtmlspecialchars($cname)."]</b></td><td colspan=\"3\"></td></tr>";
	echo "<tr>\n".
		"<td width=\"15%\" class=\"item1\">".lang('contensourcpage')."</td>\n".
		"<td width=\"35%\" class=\"item2\"><select style=\"vertical-align: middle;\" name=\"fsettingsnew[$ename][frompage]\">".makeoption($frompagearr,empty($setting['frompage']) ? 0 : $setting['frompage'])."</select></td>\n".
		"<td width=\"15%\" class=\"item1\">".lang('resultdealfunc')."</td>\n".
		"<td class=\"item2\"><input type=\"text\" size=\"25\" name=\"fsettingsnew[$ename][func]\" value=\"".(empty($setting['func']) ? '' : mhtmlspecialchars($setting['func']))."\"></td>\n".
		"</tr>\n";
	if(!$mcell){
		echo "<tr>\n".
			"<td width=\"15%\" class=\"item1\">".lang('fiecontgathpatt')."</td>\n".
			"<td class=\"item2\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][ftag]\" cols=\"40\">".(isset($setting['ftag']) ? mhtmlspecialchars($setting['ftag']) : '')."</textarea></td>\n".
			"<td width=\"15%\" class=\"item1\">".lang('clear')."Html<br><input class=\"checkbox\" type=\"checkbox\" name=\"chk$ename\" onclick=\"checkall(this.form,'clearhtml$ename','chk$ename')\">".lang('selectall')."</td>\n".
			"<td class=\"item2\">".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"1\"".(in_array('1',${'clearhtml'.$ename}) ? " checked" : "").">&lt;a&gt;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"2\"".(in_array('2',${'clearhtml'.$ename}) ? " checked" : "").">&lt;br&gt;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"3\"".(in_array('3',${'clearhtml'.$ename}) ? " checked" : "").">&lt;table&gt;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"4\"".(in_array('4',${'clearhtml'.$ename}) ? " checked" : "").">&lt;tr&gt;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"5\"".(in_array('5',${'clearhtml'.$ename}) ? " checked" : "").">&lt;td&gt;&nbsp;&nbsp;<br>\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"6\"".(in_array('6',${'clearhtml'.$ename}) ? " checked" : "").">&lt;p&gt;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"7\"".(in_array('7',${'clearhtml'.$ename}) ? " checked" : "").">&lt;font&gt;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"8\"".(in_array('8',${'clearhtml'.$ename}) ? " checked" : "").">&lt;div&gt;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"9\"".(in_array('9',${'clearhtml'.$ename}) ? " checked" : "").">&lt;span&gt;&nbsp;&nbsp;<br>\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"10\"".(in_array('10',${'clearhtml'.$ename}) ? " checked" : "").">&lt;tbody&gt;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"11\"".(in_array('11',${'clearhtml'.$ename}) ? " checked" : "").">&lt;b&gt;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"12\"".(in_array('12',${'clearhtml'.$ename}) ? " checked" : "").">&amp;nbsp;&nbsp;&nbsp;\n".
			"<input type=\"checkbox\" class=\"checkbox\" name=\"clearhtml{$ename}[]\" value=\"13\"".(in_array('13',${'clearhtml'.$ename}) ? " checked" : "").">&lt;script&gt;&nbsp;&nbsp;\n".
			"</td>\n".
			"</tr>\n";
		echo "<tr>\n".
			"<td width=\"15%\" class=\"item1\">".lang('replmesssouront')."</td>\n".
			"<td class=\"item2\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][fromreplace]\" cols=\"40\">".(isset($setting['fromreplace']) ? mhtmlspecialchars($setting['fromreplace']) : '')."</textarea></td>\n".
			"<td width=\"15%\" class=\"item1\">".lang('repmessagresulcont')."</td>\n".
			"<td class=\"item2\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][toreplace]\" cols=\"40\">".(isset($setting['toreplace']) ? mhtmlspecialchars($setting['toreplace']) : '')."</textarea></td>\n".
			"</tr>\n";
	}else{
		echo "<tr>\n".
			"<td width=\"15%\" class=\"item1\">".lang('lisregigathpatt')."</td>\n".
			"<td class=\"item2\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][ftag]\" cols=\"40\">".(isset($setting['ftag']) ? mhtmlspecialchars($setting['ftag']) : '')."</textarea></td>\n".
			"<td width=\"15%\" class=\"item1\">".lang('liscellsplitag')."</td>\n".
			"<td class=\"item2\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][splittag]\" cols=\"40\">".(isset($setting['splittag']) ? mhtmlspecialchars($setting['splittag']) : '')."</textarea></td>\n".
			"</tr>\n";
		echo "<tr>\n".
			"<td width=\"15%\" class=\"item1\">".lang('cellurlgathpatte')."</td>\n".
			"<td class=\"item2\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][remotetag]\" cols=\"40\">".(isset($setting['remotetag']) ? mhtmlspecialchars($setting['remotetag']) : '')."</textarea></td>\n".
			"<td width=\"15%\" class=\"item1\">".lang('celltitlgathepatt')."</td>\n".
			"<td class=\"item2\"><textarea rows=\"4\" name=\"fsettingsnew[$ename][titletag]\" cols=\"40\">".(isset($setting['titletag']) ? mhtmlspecialchars($setting['titletag']) : '')."</textarea></td>\n".
			"</tr>\n";
	
	}
	if(!$noremote){
		echo "<tr>\n".
			"<td width=\"15%\" class=\"item1\">".lang('remote_download')."</td>\n".
			"<td width=\"35%\" class=\"item2\"><select style=\"vertical-align: middle;\" name=\"fsettingsnew[$ename][rpid]\">".makeoption($rpidsarr,empty($setting['rpid']) ? 0 : $setting['rpid'])."</select></td>\n".
			"<td width=\"15%\" class=\"item1\">".lang('downjumfilsty')."</td>\n".
			"<td class=\"item2\"><input type=\"text\" size=\"25\" name=\"fsettingsnew[$ename][jumpfile]\" value=\"".(empty($setting['jumpfile']) ? '' : mhtmlspecialchars($setting['jumpfile']))."\"></td>\n".
			"</tr>\n";
	}
}

?>



