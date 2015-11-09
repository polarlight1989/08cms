<?php
!defined('M_COM') && exit('No Permission');
function templatebox($trname,$varname,$template='',$rows,$cols){
	global $param_suffix;
	$insertstr = "<a class=\"btn\" href=\"?entry=btags$param_suffix\" target=\"mtagcodewin\">".lang("lookinittag")."</a>&nbsp;&nbsp;";
	foreach(array('utag','ctag','ptag','rtag') as $ttype) $insertstr .= "<a class=\"btn\" href=\"?entry=mtags&action=mtagsedit&ttype=$ttype$param_suffix\" target=\"mtagcodewin\">".lang("lookttype",$ttype)."</a>&nbsp;&nbsp;";	
	$insertstr .= "<a class=\"btn\" href=\"#\" onclick=\"javascript:if(document.selection.createRange().text != '') opennewwin('?entry=mtpls&action=mtagcode$param_suffix&createrange=' + document.selection.createRange().text,'mtagcode','800','600')\">".lang("lookselecttag")."</a><br>";
	echo "<tr><td class=\"txt txtright fB borderright\">$trname</td><td class=\"txt txtleft\">$insertstr</td></tr>".
	"<tr><td class=\"txt\" colspan=\"2\"><textarea class=\"textarea\" rows=\"$rows\" name=\"$varname\" id=\"$varname\" cols=\"$cols\">".htmlspecialchars(str_replace("\t","    ",$template))."</textarea></td></tr>";
}


?>