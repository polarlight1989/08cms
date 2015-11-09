<?php
!defined('M_COM') && exit('No Permission');
@set_time_limit(0);
function autokeyword($str){
	global $a_split;
	$str = preg_replace("/&#?\\w+;/", '', strip_tags($str));
	$result = $a_split->GetIndexText($a_split->SplitRMM($str),100);
	return $result;
}
function sizecount($filesize){
	if($filesize >= 1073741824){
		$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
	}elseif($filesize >= 1048576){
		$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
	}elseif($filesize >= 1024){
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	}else $filesize = $filesize . ' Bytes';
	return $filesize;
}
function backallow($name){
	global $a_funcs,$curuser;
	return $curuser->info['isfounder'] || in_array(-1,$a_funcs) || in_array($name,$a_funcs) ? true : false; 
}
function back_follow($bkname='',$params = array()){
	global $bkfollows;
	if(!$bkname) return;
	sys_cache('bkfollows');
	if(($arr = @$bkfollows[$bkname]) && ($url = $arr['url'])){
		$str = '';
		foreach($params as $k => $v) $str .= '&'.$k.'='.rawurlencode($v);
		$str && $url .= (in_str('?',$url) ? '&' : '?').substr($str,1);
		amessage(empty($arr['msg']) ? lang('msgfollow') : $arr['msg'],$url);
	}
	return;
}
function adminlog($action='',$detail=''){
	global $curuser,$timestamp,$onlineip,$grouptypes;
	if(empty($action)) return;
	if($curuser->info['isfounder']){
		$agtname = lang('founder');	
	}else{
		$usergroups = read_cache('usergroups',2);
		$agtname = $usergroups[$curuser->info['grouptype2']]['cname'];	
	}
	$record = mhtmlspecialchars(
		$timestamp."\t".
		$curuser->info['mid']."\t".
		$curuser->info['mname']."\t".
		$agtname."\t".
		$onlineip."\t".
		$action."\t".
		$detail);
	record2file('adminlog',$record);
}
function ordercatalogs(&$catalogs,$pid) {
	$tempids = $ocatalogs = array();
	$tempids = son_ids($catalogs,$pid,$tempids);
	foreach($tempids as $tempid) $ocatalogs[$tempid] = $catalogs[$tempid];
	unset($tempids);
	return $ocatalogs;
}
function umakeoption($sarr=array(),$selected=''){
	$optionstr = '';
	foreach($sarr as $k => $v) $optionstr .= isset($v['unsel']) ? "<optgroup label=\"$v[title]\" style=\"background-color :#E0ECF2;\"></optgroup>\n" : "<option value=\"$k\"".($k == $selected ? ' selected' : '').">$v[title]</option>\n";			
	return $optionstr;
}
function umakeradio($varname,$arr=array(),$selectid='',$ppr=0){
	$str = '';
	$i = 0;
	foreach($arr as $k => $v){
		if(empty($v['unsel'])){
			$checked = $selectid == $k || (!$i && $selectid == '') ? 'checked' : '';
			$str .= "<input class=\"radio\" type=\"radio\" name=\"$varname\" id=\"_$varname$k\" value=\"$k\" $checked><label for=\"_$varname$k\">$v[title]</label>";
			$i ++;
			$str .= !$ppr || ($i % $ppr) ? '&nbsp;  &nbsp;' : '<br />';
		}
	}
	return $str;
}
function makeoption($arr, $key='', $dfstr='') {
	$str = $dfstr ? "<option value=\"\">$dfstr</option>\n" : '';
	if(is_array($arr)) foreach($arr as $k => $v) $str .= "<option value=\"$k\"".(($k == $key && empty($k) == empty($key)) ? ' selected' : '').">$v</option>\n";
	return $str;
}
function makeradio($varname,$arr=array(),$selectid='',$ppr=0,$onclick=''){
	$str = '';
	$i = 0;
	foreach($arr as $k => $v){
		$checked = $selectid == $k && empty($k) == empty($selectid) || (!$i && $selectid == '') ? ' checked' : '';
		$checked .= $onclick ? " onclick=\"$onclick\"" : '';
		$str .= "<input class=\"radio\" type=\"radio\" name=\"$varname\" id=\"_$varname$k\" value=\"$k\"$checked><label for=\"_$varname$k\">$v</label>";
		$i ++;
		$str .= !$ppr || ($i % $ppr) ? '&nbsp;  &nbsp;' : '<br />';
	}
	return $str;
}
function makecheckbox($varname,$sarr,$value=array(),$ppr=0,$pad=0){//$ppr每行单元数
	$str = '';
	$i = 0;
	foreach($sarr as $k => $v){
		$checked = in_array($k,$value) ? 'checked' : '';
		$str .= "<input class=\"checkbox\" type=\"checkbox\" name=\"$varname\" id=\"_$varname$k\" value=\"$k\" $checked><label for=\"_$varname$k\">$v</label>";
		$i++;
		$str .= ($ppr && !($i % $ppr)) || ($pad && $i == $pad) ?  '<br />' : '&nbsp;  &nbsp;';
	}
	return $str;
}
function sourcemodule($trname,$svar,$sarr,$svalue,$sview,$idsvar,$idsarr,$idsvalue=array(),$width='25%',$rshow=1, $rowid='',$vmode = 0){
	echo "<tr" . ($rowid ? " id=\"$rowid\"" : '') . ($rshow ? '' : ' style="display:none"') . "><td width=\"$width\" class=\"txt txtright fB borderright\">".$trname."</td>\n";
	echo "<td class=\"txt txtleft\">\n";
	echo "<select style=\"vertical-align: middle;\" name=\"$svar\" onchange=\"checkidsarr(this.value,'$sview','".$idsvar."')\">".makeoption($sarr,$svalue)."</select>";
	echo "<input id=\"$idsvar\" name=\"$idsvar\" onfocus=\"setidswithi(this,'$vmode')\" type=\"\"".($svalue == $sview ? '' : ' style="visibility:hidden"')." value=\"" . implode(',', $idsvalue) . "\" />";
	if(!$vmode){
		echo "<br /><select id=\"mselect_$idsvar\" onchange=\"setidswiths(this)\" size=\"5\" multiple=\"multiple\" style=\"display:".($svalue == $sview ? '' : 'none').";width: 40%\">\n";
		foreach($idsarr as $k => $v)  echo "<option value=\"$k\"".(in_array($k,$idsvalue) ? ' selected' : '').">{$v}</option>";
		echo "</select>";
	}else{
		echo '<div id="mselect_'.$idsvar.'_area"'.($svalue == $sview ? '' : ' style="display:none"').'>';
		$i = 0;
		foreach($idsarr as $k => $v){
			$checked = in_array($k,$idsvalue) ? 'checked' : '';
			echo "<input class=\"checkbox\" type=\"checkbox\" id=\"mselect_$idsvar\" onchange=\"setidswiths(this,1)\" value=\"$k\" $checked>$v";
			$i++;
			echo !($i % 6) ?  '<br />' : '&nbsp;  &nbsp;';
		}
		echo '</div>';
	}
	echo "</td></tr>\n";
}

function multiselect($varname,$sarray,$value=array(),$width='50%'){
	$value = is_array($value)?$value:array();
	$selectstr = "<select name=\"$varname\" id=\"$varname\" size=\"5\" multiple=\"multiple\" style=\"width:".$width."\">\n";
	foreach($sarray as $k => $v) $selectstr .= "<option value=\"$k\"".(in_array($k,$value) ? ' selected' : '').">$v";
	$selectstr .= "</select>";
	return $selectstr;
}
function autoabstract($str){
	global $autoabstractlength;
	if(!$autoabstractlength || empty($str)) return '';
	$str = str_replace(chr(0xa1).chr(0xa1),' ',html2text($str));
	$str = preg_replace("/&([^;&]*)(;|&)/s",' ',$str);
	$str = preg_replace("/\s+/s",' ',$str);
	return cutstr(trim($str),$autoabstractlength);
}

function multi($num, $perpage, $curpage, $mpurl, $maxpages = 0, $page = 10, $simple = 0, $onclick = '') {
	global $infloat,$handlekey;
	$multipage = '';
	$mpurl .= in_str('?',$mpurl) ? '&amp;' : '?';
	$onclick && $onclick .='(event);';
	$infloat && $onclick .= "return floatwin('open_$handlekey',this)";
	$onclick && $onclick = " onclick=\"$onclick\"";
	if($num > $perpage) {//只有超过1页时，才显示分页导航
		$offset = 2;//当前页码之前显示的页码数

		$realpages = @ceil($num / $perpage);
		$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;//需要统计的页数

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}

		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1" class="p_redirect"'.$onclick.'>1...</a>' : '').
			($curpage > 1 && !$simple ? '<a href="'.$mpurl.'page='.($curpage - 1).'" class="p_redirect"><<</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<a class="p_curpage">'.$i.'</a>' :
				'<a href="'.$mpurl.'page='.$i.'" class="p_num"'.$onclick.'>'.$i.'</a>';
		}

		$multipage .= ($curpage < $pages && !$simple ? '<a href="'.$mpurl.'page='.($curpage + 1).'" class="p_redirect"'.$onclick.'>>></a>' : '').
			($to < $pages ? '<a href="'.$mpurl.'page='.$pages.'" class="p_redirect"'.$onclick.'>...'.$pages.'</a>' : '').
			(!$simple && $pages > $page ? '<a class="p_pages" style="padding: 0px"><input class="p_input" type="text" name="custompage" onKeyDown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; return false;}"></a>' : '');
		$multipage = $multipage ? '<div class="p_bar">'.(!$simple ? '<a class="p_total">&nbsp;'.$num.'&nbsp;</a>' : '').$multipage.'</div>' : '';
	}
	return $multipage;
}

function cridsarr($cash=0){
	global $currencys;
	$narr = $cash ? array(0 => lang('cash')) : array();
	foreach($currencys as $k => $v) $narr[$k] = $v['cname'];
	return $narr;
}
function caidsarr(&$arr,$chid = 0,$nospace = 0){
	$narr = array();
	foreach($arr as $k => $v) if(!$chid || in_array($chid,array_filter(explode(',',$v['chids'])))) $narr[$k] = ($nospace ? '' : str_repeat('&nbsp; &nbsp; ',$v['level'])).$v['title'];
	return $narr;
}
function chksarr($checks,$ischk = 1){
	global $max_chklv;
	$narr = array();
	if(!$checks) return $narr;
	if($ischk){
		if($max_chklv == 1){
			if(array_intersect(array(-1,1),$checks)) $narr['check1'] = lang('check');
		}else{
			in_array('-1',$checks) && $narr['check-1'] = lang('check_4');
			for($i = 1;$i <= $max_chklv;$i++) in_array($i,$checks) && $narr['check'.$i] = lang('check_'.$i);
		}
	}else{
		if($max_chklv == 1){
			if(array_intersect(array(-1,1),$checks)) $narr['check11'] = lang('uncheck');
		}else{
			for($i = $max_chklv;$i > 0;$i --) if(array_intersect(array(-1,$i),$checks)) $narr['check'.(10 + $i)] = lang('uncheck_'.$i);
		}
	}
	return $narr;
}
function myneedchkstr(){
	global $channles,$a_checks,$max_chklv;
	if(empty($a_checks)) return '';
	if(in_array(-1,$a_checks)) return "a.checked=0";
	$sqlstr = '';
	foreach($a_checks as $v) $sqlstr .= " OR a.chkstate=".($v - 1); 
	return $sqlstr ? 'a.checked=0 AND ('.substr($sqlstr,4).')' : '';
}
function ccidsarr($coid,$nospace = 0){
	global $cotypes;
	$narr = array();
	if(empty($cotypes[$coid])) return $narr;
	$sarr = read_cache('coclasses',$coid);
	foreach($sarr as $k => $v) $narr[$k] = ($nospace ? '' : str_repeat('&nbsp; &nbsp; ',$v['level'])).$v['title'];
	return $narr;
}

function fcaidsarr($chid = '0'){
	global $fcatalogs;
	$narr = array();
	foreach($fcatalogs as $k => $v) if(!$chid || $chid == $v['chid']) $narr[$k] = $v['title'];
	return $narr;
}
function mcaidsarr($nowmstpl=0,$onlyadd=0){//$onlyadd为1时只列出允许添加分类的空间栏目
	global $mcatalogs,$mstpls;
	$narr = array();
	foreach($mcatalogs as $k => $v) if($v['maxucid'] || !$onlyadd) $narr[$k] = $v['title'];
	if($nowmstpl) $narr = marray_intersect_key($narr,$mstpls);
	return $narr;
}
function mtcidsarr($mchid = '0'){
	global $mtconfigs;
	$narr = array();
	foreach($mtconfigs as $k => $v) if(!$mchid || in_array($mchid,explode(',',$v['mchids']))) $narr[$k] = $v['cname'];
	return $narr;
}
function pmidsarr($mode = 'aread',$addstr=''){
	global $permissions;
	$narr = array('0' => !$addstr ? lang('allopen') : $addstr);
	foreach($permissions as $k => $v) if(!empty($v[$mode])) $narr[$k] = $v['cname'];
	return $narr;
}
function vcaidsarr(){
	global $vcatalogs;
	$narr = array();
	foreach($vcatalogs as $k => $v) $narr[$v['caid']] = $v['title'];
	return $narr;
}
function volidsarr($aid,$cut=1){
	global $db,$tblprefix,$mcharset;
	$narr = array();
	$query = $db->query("SELECT * FROM {$tblprefix}vols WHERE aid=$aid ORDER BY volid");
	while($row = $db->fetch_array($query)) $narr[$row['volid']] = $cut ? cutstr($row['vtitle'],$mcharset == 'utf-8' ? 15 : 10,'..') : $row['vtitle'];
	return $narr;
}
function sidsarr($all=0){
	global $subsites;
	$narr = array();
	foreach($subsites as $k => $v) if($all || !$v['closed']) $narr[$v['sid']] = $v['sitename'];
	return $narr;
}
function chidsarr($all=0){
	global $channels;
	$narr = array();
	foreach($channels as $k => $v) if($all || $v['available']) $narr[$k] = $v['cname'];
	return $narr;
}
function fchidsarr(){
	global $fchannels;
	$narr = array();
	foreach($fchannels as $k => $v) $narr[$k] = $v['cname'];
	return $narr;
}
function mchidsarr(){
	global $mchannels;
	$narr = array();
	foreach($mchannels as $k => $v) $narr[$k] = $v['cname'];
	return $narr;
}
function ugidsarr($gtid,$mchid=0){
	global $grouptypes,$mchannels;
	$narr = array();
	if(empty($grouptypes[$gtid])) return $narr;
	$usergroups = read_cache('usergroups',$gtid);
	foreach($usergroups as $k => $v) if(!$mchid || in_array($mchid,explode(',',$v['mchids']))) $narr[$k] = $v['cname'];
	return $narr;
}
function cuidsarr($cclass=''){//ch类交互id
	global $commus;
	$narr = array();
	if(empty($commus)) return $narr;
	foreach($commus as $k => $v) if($v['ch'] && (!$cclass || $cclass == $v['cclass'])) $narr[$k] = $v['cname'];
	return $narr;
}
function mcuidsarr($cclass=''){//ch类交互id
	global $mcommus;
	$narr = array();
	if(empty($mcommus)) return $narr;
	foreach($mcommus as $k => $v) if($v['ch'] && (!$cclass || $cclass == $v['cclass'])) $narr[$k] = $v['cname'];
	return $narr;
}
function mtplsarr($tpclass = 'archive'){
	global $mtpls;
	$narr = array();
	if(empty($mtpls)) return $narr;
	foreach($mtpls as $k => $v) if($v['tpclass'] == $tpclass) $narr[$k] = $v['cname'];
	return $narr;
}
function check_submit_func($str=''){
		echo "<script type=\"text/javascript\" reload=\"1\">\n".
		"function check_$GLOBALS[checkfname]_submit(form){\n".
		"try{\n".
		"var i = true,dom,rmsg;\n".
		"clearalerts(form);\n".
		$str.
		"if(!i){alert('".lang('cheforcon')."');}\n return i;\n".
		"}catch(e){alert('".lang('cheforcon')."\\n\\n'+(e.description||e));return false;}\n".
		"}\n".
		"</script>\n";
}
function tabheader($tname='',$fname='',$furl='',$col=2,$fupload=0,$checksubmit=0,$newwin=0){
	
	$_mc = defined('M_MCENTER') ? 1 : 0;	
	$tablestr = '';
	if($fname) $tablestr .= form_str($fname,$furl,$fupload,$checksubmit,$newwin);
	if($_mc){
		$tablestr .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"tabmain\">\n";
		$tablestr .= "<tr class=\"header\"><td colspan=\"$col\"><b>$tname</b></td></tr>\n";
	}else{
		$tablestr .= "<div class=\"conlist1\">$tname</div>";
		$tablestr .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\" tb tb2 bdbot\">\n";
	}
	echo $tablestr;
}
function form_str($fname='',$furl='',$fupload=0,$checksubmit=0,$newwin=0){
	global $infloat,$ajaxtarget,$handlekey;
	$GLOBALS['checkfname']=$fname;
	$ques = strpos($furl, '?') === false ? '?' : '&';
	return "<form name=\"$fname\" id=\"$fname\" method=\"post\"".(!$fupload ? "" : " enctype=\"multipart/form-data\"")." action=\"$furl".($infloat?"{$ques}infloat=$infloat&handlekey=$handlekey":'')."\" onsubmit=\"var f=1;".($checksubmit ? "f=check_{$fname}_submit(this);":'').($newwin ? "return f?ajaxform(this):f":'return f')."\">\n";//ajaxpost('$fname', '$ajaxtarget', '$handlekey'".($newwin?",'','',1":'').")
}
function tabheader_e(){
	echo defined('M_MCENTER') ? "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"tabmain\">\n" : "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\" tb tb2 bdbot\">\n";
}
function tabfooter($bname='',$bvalue='',$addstr='',$fmclose=1){//$fmclose是否关闭form
	$_mc = defined('M_MCENTER') ? 1 : 0;	
	$bvalue = empty($bvalue) ? lang('submit') : $bvalue;
	$tablestr = "</table>\n";
	if($bname) $tablestr .= ($_mc ? '' : '<br />')."<input class=\"".($_mc ? 'submit' : 'btn')."\" type=\"submit\" name=\"$bname\" value=\"$bvalue\">\n";
	if($addstr) $tablestr .= $addstr;
	if($bname && $fmclose) $tablestr .= "</form>\n";
	!$_mc && $tablestr .= "<div class=\"blank9\"></div>";
	echo $tablestr;
}
function trcategory($arr = array()){
	$_mc = defined('M_MCENTER') ? 1 : 0;	
	echo $_mc ? "<tr class=\"category\" align=\"center\">\n" : "<tr class=\"title txt w40\">\n";
	foreach($arr as $v) echo $_mc ? "<td".(is_array($v) ? " class=\"$v[1]\"" : '').">".(is_array($v) ? $v[0] : $v)."</td>\n" : "<td class=\"title ".(is_array($v) ? $v[1] : 'txtC')."\">".(is_array($v) ? $v[0] : $v)."</td>\n";
	echo "</tr>\n";
}
function trhidden($varname,$value){
	echo "<input type=\"hidden\" name=\"$varname\" value=\"$value\">\n";
}
function viewcheck($name = '',$value = '',$body = '',$noblank = 0){
	return ($noblank ? '' : '&nbsp; &nbsp; ')."<input class=\"checkbox\" type=\"checkbox\" name=\"$name\" value=\"1\" onclick=\"alterview('$body')\"".(empty($value) ? '' : ' checked').">".lang($name);
}
function strbutton($name,$value='',$onclick = ''){
	return "<input class=\"".(defined('M_MCENTER') ? 'button' : 'btn')."\" type='".($onclick ? 'button' : 'submit')."' name=\"$name\" value=\"".lang(!$value ? 'submit' : $value)."\"".($onclick ?  " onclick=\"$onclick\"" : '').">";
}

function url_nav($title='',$arr = array(),$current='',$numpl=8){//针对所选择的链接，高亮当前页
	$multi = count($arr) < $numpl ? 0 : 1;
	echo "<div class=\"itemtitle\"><h3".(!$multi ? '' : ' class=h3other').">$title</h3><ul class=\"tab1".(!$multi ? '' : '  tab0 bdtop')."\">\n";
	foreach($arr as $k => $v){
		$nclassstr = (!$multi ? '' : 'td24').($k == $current ? ' current' : '');
		echo "<li".($nclassstr ? " class=\"$nclassstr\"" : '')."><a href=\"$v[1]\"><span>$v[0]</span></a></li>\n";
	}
	echo "</ul></div><div class=\"blank15h\"></div>";
}
function makesubmitstr($varname,$notnull = 0,$mlimit = 0,$min = 0,$max = 0,$type = 'text',$regular = ''){
	if(!$notnull && !$mlimit && !$regular && !$min && !$max && $type != 'date') return '';
	$regular = addslashes($regular);
	if($type == 'text'){
		$submitstr = "rmsg = checktext('$varname','$notnull','$mlimit','$regular','$min','$max');\n";
	}elseif($type == 'int'){
		$submitstr = "rmsg = checkint('$varname','$notnull','$regular','$min','$max');\n";
	}elseif($type == 'float'){
		$submitstr = "rmsg = checkfloat('$varname','$notnull','$regular','$min','$max');\n";
	}elseif($type == 'date'){
		$submitstr = "rmsg = checkdate('$varname','$notnull','$min','$max');\n";
	}elseif($type == 'htmltext'){
		$submitstr = "rmsg = checkhtmltext('$varname','$notnull','$min','$max');\n";
	}elseif(in_array($type,array('image','flash','media','file'))){
		$submitstr = "rmsg = checksimple('$varname','$notnull','$mlimit');\n";
	}elseif(in_array($type,array('images','flashs','medias','files'))){
		$submitstr = "rmsg = checkmultiple('$varname','$notnull','$mlimit','$min','$max');\n";
	}elseif($type == 'multitext'){
		$submitstr = "rmsg = checkmultitext('$varname','$notnull','$min','$max');\n";
	}else{
		if(!$notnull)return '';
		$submitstr = "rmsg = checkempty('$varname',form,'$mlimit','$regular','$min','$max');\n";
	}
	return $submitstr . "if(rmsg){\n	if(dom=\$id('alert_$varname'))dom.innerHTML = rmsg;\n	i = false;\n}\n";
}
function tr_ugids($trname,$varname = 'ugidsnew',$oldarr = array(),$noall=0,$guide='',$width = '25%'){
	global $grouptypes;
	$items = $noall  ? array() : array('-1' => array(lang('allusergroup'),0));
	foreach($grouptypes as $gtid => $grouptype){
		if(!$grouptype['forbidden']){
			$ugids = ugidsarr($gtid);
			foreach($ugids  as $k => $v) $items[$k] = array($v,$gtid);
		}
	}
	echo "<tr><td width=\"$width\" class=\"txt txtright fB borderright\">".$trname."<br /><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_$varname\" onclick=\"checkall(this.form, '".$varname."', 'chkall_$varname')\">".lang('selectall')."</td>\n";
	echo "<td class=\"txt txtleft\">\n";
	$oldgtid = 0;
	foreach($items as $k => $v){
		echo ($oldgtid != $v[1] ? '<br />' : '')."<input class=\"checkbox\" type=\"checkbox\" name=\"".$varname."[$k]\" value=\"$k\"".(in_array($k,$oldarr) ? " checked" : "").">$v[0] &nbsp;";
		$oldgtid = $v[1];
	}
	if($guide) echo "<div class=\"tips1\">$guide</div>";
	echo "</td></tr>\n";
}
function trrange($trname,$arr1,$arr2,$type='text',$guide='',$width = '25%'){
	$_mc = defined('M_MCENTER') ? 1 : 0;
	$_mc && $trname = '<b>'.$trname.'</b>';
	echo "<tr><td width=\"$width\" class=\"".($_mc ? 'item1' : 'txt txtright fB borderright')."\">$trname</td>\n";
	echo "<td class=\"".($_mc ? "item2" : "txt txtleft")."\">\n";
	echo (empty($arr1[2]) ? '' : $arr1[2])."<input type=\"text\" size=\"".(empty($arr1[4]) ? 10 : $arr1[4])."\" id=\"$arr1[0]\" name=\"$arr1[0]\" value=\"".mhtmlspecialchars($arr1[1])."\"".($type == 'calendar' ? " onclick=\"ShowCalendar(this.id);\"" : '')."><span id=\"alert_$arr1[0]\" name=\"alert_$arr1[0]\" class=\"red\"></span>".(empty($arr1[3]) ? '' : $arr1[3]);
	echo (empty($arr2[2]) ? '' : $arr2[2])."<input type=\"text\" size=\"".(empty($arr2[4]) ? 10 : $arr2[4])."\" id=\"$arr2[0]\" name=\"$arr2[0]\" value=\"".mhtmlspecialchars($arr2[1])."\"".($type == 'calendar' ? " onclick=\"ShowCalendar(this.id);\"" : '')."><span id=\"alert_$arr2[0]\" name=\"alert_$arr2[0]\" class=\"red\"></span>".(empty($arr2[3]) ? '' : $arr2[3]);
	if($guide) echo $_mc ? "<br /><font class=\"gray\">$guide</font>" : "<div class=\"tips1\">$guide</div>";
	echo "</td></tr>";
}
function tr_regcode($rname){
	global $cms_regcode,$cms_abs,$timestamp;
	$submitstr = '';
	if($cms_regcode && in_array($rname,explode(',',$cms_regcode))){
		if(defined('M_MCENTER')){
			echo "<tr><td class=\"item1\"><b>".lang('safecode')."</b></td>".
			"<td class=\"item2\"><input type=\"text\" name=\"regcode\" id=\"regcode\" size=\"4\" maxlength=\"4\">&nbsp;&nbsp;".
			"<img src=\"{$cms_abs}tools/regcode.php?t=$timestamp\" alt=\"".lang('safetips')."\" style=\"vertical-align: middle;cursor:pointer;\" onclick=\"this.src+=1\">".
			"<div id=\"alert_regcode\" name=\"alert_regcode\" class=\"red\"></div><font class=\"gray\">".lang('safemark')."</font>".
			"</td></tr>";
		}else{
			echo "<tr><td class=\"txt txtright fB borderright\">".lang('regcode')."<font class=\"gray\">&nbsp; ".lang('agregcode')."</font>"."&nbsp; <div id=\"alert_regcode\" name=\"alert_regcode\" class=\"red\"></div></td>".
			"<td class=\"txt txtleft\"><input type=\"text\" name=\"regcode\" id=\"regcode\" size=\"4\" maxlength=\"4\">&nbsp;&nbsp;".
			"<img src=\"{$cms_abs}tools/regcode.php?t=$timestamp\" alt=\"".lang('re_regcode')."\" style=\"vertical-align: middle;cursor:pointer;\" onclick=\"this.src+=1\"></td></tr>";
		}
		$submitstr = makesubmitstr('regcode',1,'number',4,4);
	}
	return $submitstr;
}
function tab_list($arr = array(),$num = 2){
	if(empty($arr)) return '';
	$ret = "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	$i = 0;
	$width = floor(100 / $num).'%';
	foreach($arr as $v){
		if(!($i % $num)) $ret .= "<tr>";
		$ret .= "<td class=\"txt\" width=\"$width\">$v</td>\n";
		$i ++;
		if(!($i % $num)) $ret .= "</tr>\n";
	}
	if($i % $num){
		while($i % $num){
			$ret .= "<td class=\"txt\" width=\"$width\"></td>\n";
			$i ++;
		}
		$ret .= "</tr>\n";
	}
	$ret .= "</table><div class=\"blank9\"></div>\n";
	return $ret;
}
function trbasic($trname,$varname,$value = '',$type = 'text',$guide='',$width = '25%',$rshow = 1, $rowid = '') {
	$_mc = defined('M_MCENTER') ? 1 : 0;
	echo "<tr" . ($rowid ? " id=\"$rowid\"" : '') . ($rshow ? '' : ' style="display:none"') . "><td width=\"$width\" class=\"".($_mc ? 'item1' : 'txt txtright fB borderright')."\">".($_mc ? "<b>$trname</b>" : $trname)."</td>\n";
	echo "<td class=\"".($_mc ? 'item2' : 'txt txtleft')."\">\n";
	if($type == 'radio') {
		$check = array();
		$value ? $check['true'] = "checked" : $check['false'] = "checked";
		$value ? $check['false'] = '' : $check['true'] = '';
		echo "<input type=\"radio\" class=\"radio\" id=\"$varname\" name=\"$varname\" value=\"1\" $check[true]> ".lang('yes')." &nbsp; &nbsp; \n".
			"<input type=\"radio\" class=\"radio\" id=\"$varname\" name=\"$varname\" value=\"0\" $check[false]> ".lang('no')." \n";
	}elseif($type == 'select') {
		echo "<select style=\"vertical-align: middle;\" id=\"$varname\" name=\"$varname\">$value</select>";
	}elseif($type == 'text' || $type == 'password' || $type == 'btext'){
		echo "<input type=\"".($type == 'password' ? $type : 'text')."\" size=\"".($type == 'btext' ? 50 : 25)."\" id=\"$varname\" name=\"$varname\" value=\"".mhtmlspecialchars($value)."\">\n";
	}elseif($type == 'calendar') {
		echo "<input type=\"text\" size=\"15\" id=\"$varname\" name=\"$varname\" value=\"".mhtmlspecialchars($value)."\" onclick=\"ShowCalendar(this.id);\">\n";
	}elseif($type == 'textarea' || $type == 'btextarea'){
		$rows = $type == 'textarea' ? 4 : 10;
		$cols = $type == 'textarea' ? 60 : 90;
		echo "<textarea rows=\"$rows\" name=\"$varname\" id=\"$varname\" cols=\"$cols\">".mhtmlspecialchars($value)."</textarea>\n";
	}else{
		echo $value;
	}
	echo "<div id=\"alert_$varname\" name=\"alert_$varname\" class=\"".($_mc ? 'red' : 'mistake0')."\"></div>";
	if($guide) echo $_mc ? "<font class=\"gray\">$guide</font>" : "<div class=\"tips1\">$guide</div>";
	echo "</td></tr>\n";
}
function tralbums($trname,$varname,$chid = 0,$isopen = 0){
	trbasic($trname,'',"<input type=\"hidden\" id=\"$varname\" name=\"$varname\" /><input type=\"button\" class=\"uploadbtn\" onclick=\"albumarea('albuminfo$isopen','$varname',$chid,0,$isopen,1);return false\" value=\"".lang('albumchoose')."\">&nbsp; <span id=\"albuminfo$isopen\"></span>",'');
}
function tr_cns($trname,$varname,$value = 0,$sid = 0,$coid = 0,$chid = 0,$addstr='',$framein=0,$max=0,$notip=0,$emode=0,$evarname='',$evalue=0){
	trbasic($trname,'',cn_select($varname,$value,$sid,$coid,$chid,$addstr,$framein,$max,$notip,$emode,$evarname,$evalue),'');
}
function cn_select($varname,$value = 0,$sid = 0,$coid = 0,$chid = 0,$addstr='',$framein=0,$max=0,$notip=0,$emode=0,$evarname='',$evalue=0){//$addstr为空时的字符，也是提示性字符//$framein不排除结构性栏目
	global $ca_vmode,$cotypes;
	$_mc = defined('M_MCENTER') ? 1 : 0;
	$vmode = $coid ? @$cotypes[$coid]['vmode'] : $ca_vmode;
	if($max && $vmode < 2) $vmode = 3;
	if(!$vmode){
		$str = "<select style=\"vertical-align: middle;\" name=\"$varname\">".umakeoption(($addstr ? array('0' => array('title' => $addstr)) : array()) + uccidsarr($coid,$chid,$framein,0),$value)."</select>";
	}elseif($vmode == 1){
		$str = umakeradio($varname,($addstr ? array('0' => array('title' => $addstr)) : array()) + uccidsarr($coid,$chid,$framein,1),$value);
	}elseif($vmode == 2){
		global $acatalogs;
		$items = $coid ? read_cache('coclasses',$coid) : ($sid == -1 ? $acatalogs : read_cache('catalogs','','',$sid));
		$str = "<input type=\"hidden\" name=\"$varname\" value=\"$value\"><input onclick=\"cataarea('scatainfo$coid','$varname',$sid,$coid,$chid,0,".($max ? 1 : 0).");return false\" class=\"uploadbtn\" type=\"button\" value=\"".($addstr ? $addstr : lang('p_choose'))."\" />&nbsp; <span id=\"scatainfo$coid\">".cnstitle($value,$max,$items)."</span>";
	}elseif($vmode == 3){
		$arr = uccidsarr($coid,$chid,$framein,1,1);
		$str = "<script>var data = [";
		foreach($arr as $k => $v) $str .= "[$k,$v[pid],'".addslashes($v['title'])."',".(empty($v['unsel']) ? 0 : 1) . '],';
		$str .= "];\nmake_mbox('', '$varname', data, '$value',$max,$notip);</script>";
		unset($arr);
	}else{
		$data = $coid ? "coid&coid=$coid" : 'caid';
		$data .= "&chid=$chid&framein=$framein&sid=$sid";
		$str = "<span><script>make_mbox('', '$varname', 'action=$data', '$value',$max,$notip);</script></span>";
	}
	if($emode){
		!$evalue && $evalue = '';
		$str .= lang('enddate1').($emode > 1 ? '*' : '')."<input type=\"text\" size=\"10\" id=\"$evarname\" name=\"$evarname\" value=\"$evalue\" onclick=\"ShowCalendar(this.id);\"><span id=\"alert_$evarname\" name=\"alert_$evarname\" class=\"".($_mc ? 'red' : 'mistake0')."\"></span>\n";
	}
	return $str;
}
function mu_cnselect($varname,$value = 0,$ucoid = 0,$addstr='',$emode=0,$evarname='',$evalue=0){
	global $ucotypes;
	$_mc = defined('M_MCENTER') ? 1 : 0;
	if(empty($ucotypes[$ucoid])) return '';
	$ucoclasses = read_cache('ucoclasses',$ucoid);
	$uccidsarr = array();
	foreach($ucoclasses as $k => $v) $uccidsarr[$k] = $v['title'];
	if(empty($ucotypes[$ucoid]['vmode'])) $str = "<select style=\"vertical-align: middle;\" name=\"$varname\">".makeoption(($addstr ? array('0' => $addstr) : array()) + $uccidsarr,$value)."</select>";
	else $str = makeradio($varname,($addstr ? array('0' => $addstr) : array()) + $uccidsarr,$value);
	if($emode){
		!$evalue && $evalue = '';
		$str .= lang('enddate1').($emode > 1 ? '*' : '')."<input type=\"text\" size=\"10\" id=\"$evarname\" name=\"$evarname\" value=\"$evalue\" onclick=\"ShowCalendar(this.id);\"><span id=\"alert_$evarname\" name=\"alert_$evarname\" class=\"".($_mc ? 'red' : 'mistake0')."\"></span>\n";
	}
	return $str;
}

function a_guide($str){
	@include M_ROOT.'./dynamic/aguides/'.$str.'.php';
	echo "<!--$str-->";
	if(!empty($aguide)){
		echo "<div class=\"blank12\"></div><div class=\"tiShiTitle\">".lang('guide')."</div><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"txtleft lineheight20 tiShi\">\n".
			"<tr><td>$aguide</td></tr></table>\n";
	}
}
function amessage($key='', $url = '') {
	global $amsgs,$amsgforwordtime,$inajax,$infloat,$handlekey;
	$msnum = $amsgforwordtime ? $amsgforwordtime : 1250;
	$str = @$amsgs[$key] ? $amsgs[$key] : $key;
	if(($num = func_num_args())>2){
		$ars = func_get_args();
		array_splice($ars, 1, 1);
		$ars[0] = &$str;
		$str = call_user_func_array('sprintf',$ars);
	}
	if($url) {
		if($infloat){
			if(preg_match('/^javascript:/',$url)){
				$str .= "<script type=\"text/javascript\" reload=\"1\">var t = $msnum;".substr($url,11)."</script>";
			}else{
				$str .= "<br /><br /><br /><a href=\"$url\" onclick=\"return floatwin('update_$handlekey', this);\">".lang('clickhere')."</a><script type=\"text/javascript\" reload=\"1\">setDelay(\"floatwin('update_$handlekey', '$url');\",$msnum);</script>";
			}
		}elseif(!(strpos($url,'history') === false)){
			$str .= "<br /><br /><br /><a href=\"javascript:$url\">".lang('clickhere')."</a><script>setTimeout('$url',$msnum);</script>";
		}else $str .= "<br /><br /><br /><a href=\"$url\">".lang('clickhere')."</a><script>setTimeout(\"redirect('$url');\",$msnum);</script>";
	}
	$inajax || print("<br /><br /><br /><br /><br /><br />");
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"center\">\n".
		"<table width=\"500\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#FFFFFF\" class=\"tabmain\">\n".
		"<tr><td><div class=\"conlist1 bdbot fB\">".lang('prompt_msg')."</div></td></tr>\n".
		"<tr height=\"150\"><td class=\"txtcenter lineheight200\" align=\"center\">$str</td></tr></table>\n".
		"</td></tr></table>\n";
	$inajax || print("<br /><br /><br /><br /><br />\n");
	afooter();
	mexit();
}
function afooter(){
	global $copyright,$cms_power,$inajax,$infloat,$no_afooter,$callback;
	if(!empty($callback)){
		$s = ob_get_contents();
		ob_clean();
		mexit("js_callback('" . addcslashes($s, "\\\r\n'") . "','$callback')");
	}
	if($inajax){
		$s = ob_get_contents(); ob_end_clean();
		$s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
		$s = str_replace(array(chr(0), ']]>'), array(' ', ']]&gt;'), $s);
		echo $s.']]></root>';
		mexit();
	}
	if(empty($no_afooter)){
		if(!$infloat){
?>
</div>
<div class="blank9"></div>
<div class="copyFoot">
	<p>Copyright &copy; 2008-2012 <a href="http://www.08cms.com" target="_blank">08CMS</a> <?=lang('dingyue_com')?> All rights reserved.</p><?php }?>
</div>
<div class="blank9"></div>
</body>
</html>
<?
	}
}

function aheader() {
	global $mcharset,$inajax,$infloat,$ajaxtarget,$handlekey,$callback,$cms_abs;
	if(!empty($callback))return;
	if($inajax){
		ob_start();
		header("Expires: -1");header("Pragma: no-cache");
		header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		header("Content-type: application/xml; charset=$mcharset");
		echo '<?xml version="1.0" encoding="'.$mcharset.'"?><root><![CDATA[';
		return;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>">
<link href="images/admina/contentsAdmin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var IXDIR = 'images/admina/', charset = '<?=$mcharset?>',allowfloatwin = '<?=$GLOBALS['aallowfloatwin']?>',floatwidth='<?=$GLOBALS['afloatwinwidth']?>',floatheight='<?=$GLOBALS['afloatwinheight']?>'</script>
<script type="text/javascript" src="include/js/langs.js"></script>
<script type="text/javascript" src="include/js/common.js"></script>
<script type="text/javascript" src="include/js/admina.js"></script>
<script type="text/javascript" src="include/js/floatwin.js"></script>
<script type="text/javascript" src="include/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="include/js/calendar.js"></script>
<script type="text/javascript" src="include/js/tree.js"></script>
<script type="text/javascript" src="include/js/listbox.js"></script>
</head>
<body style="overflow-x:hidden;">
<div id="append_parent"></div>

<?
	print($infloat ? '<div class="floatBox">' : '<div class="blank9"></div><div class="mainBox">');
}
?>