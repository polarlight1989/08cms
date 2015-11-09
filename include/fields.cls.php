<?php
!defined('M_COM') && exit('No Permission');
@set_time_limit(0);
class cls_field{
	var $field = array();
	var $oldvalue = '';
	var $newvalue = '';
	var $error = '';
	var $isadd = 0;
	var $searchstr = '';
	var $filterstr = '';
	var $submitstr = '';
	function __construct(){
		$this->cls_field();
	}
	function cls_field(){
		$this->init();
	}
	function init(){
		$this->field = array();
		$this->oldvalue = '';
		$this->newvalue = '';
		$this->error = '';
		$this->isadd = 0;
		$this->searchstr = '';
		$this->filterstr = '';
		$this->submitstr = '';
	}
	function trfield($varpre='',$noeditstr='',$ftype='',$typeid=0){
		if(empty($this->field['ename']) || empty($this->field['available'])) return;
		$trname = ($this->field['notnull'] ? '*' : '').$this->field['cname'].$noeditstr;
		$varname = !$varpre ? $this->field['ename'] : ($varpre.'['.$this->field['ename'].']');
		$oldstr = $this->isadd ? $this->field['vdefault'] : $this->oldvalue;//多项选择
		foreach(array('datatype','mode','guide','min','max',) as $var) $$var = $this->field[$var];
		if($datatype == 'cacc'){
			$setting = empty($this->field['innertext']) ? array() : unserialize($this->field['innertext']);
			trcacc($trname,$varname,$oldstr,$this->field['length'],@$setting['source'],@$setting['ids'],$mode,$max ? intval($max) : 0,$guide);
		}else{
			if(in_array($datatype,array('text','int','float'))){
				$oldstr = mhtmlspecialchars($oldstr);
				$datatype = 'text';
			}elseif($datatype == 'select'){
				$sourcearr = select_arr($this->field['innertext'],$this->field['fromcode']);
				$oldstr = !$mode ? makeoption($sourcearr,$oldstr) : makeradio($varname,$sourcearr,$oldstr);
			}elseif($datatype == 'mselect'){
				$sourcearr = select_arr($this->field['innertext'],$this->field['fromcode']);
				$oldarr = explode("\t",$oldstr);
				$oldstr = !$mode ? multiselect($varname.'[]',$sourcearr,$oldarr) : makecheckbox($varname.'[]',$sourcearr,$oldarr);
			}elseif($datatype == 'multitext'){
				$oldstr = mhtmlspecialchars($oldstr);
			}elseif($datatype == 'date'){
				$oldstr = $oldstr ? date('Y-m-d',$oldstr) : '';
			}elseif($datatype == 'map'){
				!$oldstr && $oldstr = $this->field['vdefault'];
				$min = $this->field['length'];
			}elseif($datatype == 'vote'){
				$mode = $ftype;
				$max = $min;
				$min = $typeid;
			}
			trspecial($trname,$varname,$oldstr,$datatype,$mode,$guide,$min,$max);
		}
		$this->make_submitstr($varname);
	}
	function deal_search($fpre = ''){//$fpre为查询字串中的表别名，如a.,c.,m.等
		if(!$this->field['available'] || !$this->field['issearch']) return;
		$fn = $this->field['ename'];
		global ${$fn},${$fn.'str'},${$fn.'from'},${$fn.'to'},${$fn.'_0'},${$fn.'_1'},${$fn.'diff'};
		if($this->field['datatype'] == 'select'){
			if($this->field['issearch'] == '1'){
				if(${$fn} != ''){
					$this->searchstr = $fpre.$fn."='".${$fn}."'";
					$this->filterstr = $fn."=".rawurlencode(stripslashes(${$fn}));
				}
			}else{
				if(!empty(${$fn})){
					${$fn.'str'} = implode("\t",${$fn});
				}elseif(!empty(${$fn.'str'})){
					${$fn} = explode("\t",${$fn.'str'});
				}else ${$fn.'str'} = '';
				if(${$fn.'str'} != ''){
					$this->searchstr = $fpre.$fn." ".multi_str(${$fn});
					$this->filterstr = $fn."str=".rawurlencode(stripslashes(${$fn.'str'}));
				}
			}
		}elseif($this->field['datatype'] == 'mselect'){
			if($this->field['issearch'] == '1'){
				if(${$fn} != ''){
					$this->searchstr = $fpre.$fn." LIKE '%".str_replace(array(' ','*'),'%',addcslashes(${$fn},'%_'))."%'";
					$this->filterstr = $fn."=".rawurlencode(stripslashes(${$fn}));
				}
			}else{
				if(!empty(${$fn})){
					${$fn.'str'} = implode("\t",${$fn});
				}elseif(!empty(${$fn.'str'})){
					${$fn} = explode("\t",${$fn.'str'});
				}else ${$fn.'str'} = '';
				if(${$fn.'str'} != ''){
					foreach(${$fn} as $v) $this->searchstr .= ($this->searchstr ? ' OR ' : '').$fpre.$fn." LIKE '%".str_replace(array(' ','*'),'%',addcslashes($v,'%_'))."%'";
					$this->searchstr = '('.$this->searchstr.')';
					$this->filterstr = $fn."str=".rawurlencode(stripslashes(${$fn.'str'}));
				}
			}
		}elseif($this->field['datatype'] == 'text'){
			${$fn} = empty(${$fn}) ? '' : cutstr(trim(${$fn}),20,'');
			if(${$fn} != ''){
				$this->searchstr = $this->field['issearch'] == 1 ? $fpre.$fn."='".${$fn}."'" : $fpre.$fn." LIKE '%".str_replace(array(' ','*'),'%',addcslashes(${$fn},'%_'))."%'";
				$this->filterstr = $fn."=".rawurlencode(stripslashes(${$fn}));
			}
		}elseif($this->field['datatype'] == 'cacc'){
			if(${$fn} = empty(${$fn}) ? 0 : max(0,intval(${$fn}))){
				$arr = $this->field['length'] ? read_cache('coclasses',$this->field['length']) : read_cache('acatalogs');
				$this->searchstr = caccsql($fpre.$fn,$this->field['issearch'] == 1 ? array(${$fn}) : cnsonids(${$fn},$arr),$this->field['max']);
				$this->filterstr = $fn."=".${$fn};
			}
		}elseif($this->field['datatype'] == 'map'){
			if(${$fn.'diff'} = empty(${$fn.'diff'}) ? 0 : abs(${$fn.'diff'})){
				$this->searchstr = mapsql(${$fn.'_0'},${$fn.'_1'},${$fn.'diff'},$this->field['issearch'],$fpre.$fn);
				$this->filterstr = $fn.'_0='.${$fn.'_0'}.'&'.$fn.'_1='.${$fn.'_1'}.'&'.$fn.'diff='.${$fn.'diff'};
			}
		}elseif(in_array($this->field['datatype'],array('int','float','date'))){
			if($this->field['issearch'] == '1'){
				${$fn} = trim(${$fn});
				if(($this->field['datatype'] == 'date' && !isdate(${$fn})) || (in_array($this->field['datatype'],array('int','float')) && !is_numeric(${$fn}))) ${$fn} = '';
				if(${$fn} != ''){
					$this->field['datatype'] == 'int' && ${$fn} = intval(${$fn});
					$this->field['datatype'] == 'float' && ${$fn} = floatval(${$fn});
					$this->searchstr = $this->field['datatype'] == 'date' ? $fpre.$fn."='".strtotime(${$fn})."'" : $fpre.$fn."='".${$fn}."'";
					$this->filterstr = $fn."=".rawurlencode(${$fn});
				}
			}else{
				${$fn.'from'} = trim(${$fn.'from'});
				if(($this->field['datatype'] == 'date' && !isdate(${$fn.'from'})) || (in_array($this->field['datatype'],array('int','float')) && !is_numeric(${$fn.'from'}))) ${$fn.'from'} = '';
				if(${$fn.'from'} != ''){
					$this->field['datatype'] == 'int' && ${$fn.'from'} = intval(${$fn.'from'});
					$this->field['datatype'] == 'float' && ${$fn.'from'} = floatval(${$fn.'from'});
					$this->searchstr = $this->field['datatype'] == 'date' ? $fpre.$fn.">='".strtotime(${$fn.'from'})."'" : $fpre.$fn.">='".${$fn.'from'}."'";
					$this->filterstr = $fn."from=".rawurlencode(${$fn.'from'});
				}
				${$fn.'to'} = trim(${$fn.'to'});
				if(($this->field['datatype'] == 'date' && !isdate(${$fn.'to'})) || (in_array($this->field['datatype'],array('int','float')) && !is_numeric(${$fn.'to'}))) ${$fn.'to'} = '';
				if(${$fn.'to'} != ''){
					$this->field['datatype'] == 'int' && ${$fn.'to'} = intval(${$fn.'to'});
					$this->field['datatype'] == 'float' && ${$fn.'to'} = floatval(${$fn.'to'});
					$this->searchstr .= ($this->searchstr ? " AND " : "").$fpre.$fn."<'".($this->field['datatype'] == 'date' ? strtotime(${$fn.'to'}) : ${$fn.'to'})."'";
					$this->filterstr .= ($this->filterstr ? '&' : '').$fn."to=".rawurlencode(${$fn.'to'});
				}
			}
		}
		return;
	}
	function deal($varpre=''){
		global $c_upload,$ftp_pwd;
		$datatype = $this->field['datatype'];
		$varname = empty($varpre) ? $this->field['ename'] : $varpre;
		global $$varname;
		$var = $$varname;
		$this->newvalue = empty($varpre) ? $var : (isset($var[$this->field['ename']]) ? $var[$this->field['ename']] : '');
		if($datatype == 'mselect'){
			$this->newvalue = !empty($this->newvalue) ? implode("\t",$this->newvalue) : '';
		}elseif(in_array($datatype,array('image','file','flash','media'))){
			$this->newvalue = upload_s($this->newvalue,$this->oldvalue,$datatype,$this->field['rpid']);
		}elseif(in_array($datatype,array('images','files','medias','flashs'))){//返回数组，以便分析数量限制
			$this->newvalue = upload_m($this->newvalue,$this->oldvalue,substr($datatype,0,strlen($datatype) - 1),$this->field['rpid']);
		}elseif($datatype == 'vote'){
			$this->oldvalue = empty($this->oldvalue) ? array() : unserialize($this->oldvalue);
			foreach($this->newvalue as $k => $v){
				$this->newvalue[$k]['totalnum'] = empty($this->oldvalue[$k]['totalnum']) ? 0 : $this->oldvalue[$k]['totalnum'];
				foreach($v['options'] as $x => $y){
					$this->newvalue[$k]['options'][$x]['votenum'] = empty($this->oldvalue[$k]['options'][$x]['votenum']) ? 0 : $this->oldvalue[$k]['options'][$x]['votenum'];
				}
			}
			$this->newvalue = empty($this->newvalue) ? '' : addslashes(serialize($this->newvalue));
		}
		$this->pre_deal();
		if(!$this->check_null()) return;
		if(!$this->check_limit()) return;
		if($this->field['rpid'] &&  in_array($this->field['datatype'],array('text','multitext','htmltext'))){
			$this->newvalue = addslashes($c_upload->remotefromstr(stripslashes($this->newvalue),$this->field['rpid']));
		}
		$ftp_pwd = false;
		$this->end_deal();
	}
	function pre_deal(){
		$min = $this->field['min'];
		$max = $this->field['max'];
		if(in_array($this->field['datatype'],array('htmltext','date','multitext','text','select','mselect'))){
			$this->field['nohtml'] && $this->newvalue = strip_tags($this->newvalue);
			if($this->field['datatype'] == 'htmltext' && !preg_match('/^\s*<[pP][^>]*>[\x00-\xff]*<[pP]\b/', $this->newvalue)){
				$this->newvalue = preg_replace('/^\s*<[pP][^>]*>\s*|(?:\s|&nbsp;)*<\/[pP]>(?:\s|&nbsp;)*$/', '', $this->newvalue);
			}else{
				$this->newvalue = trim($this->newvalue);
			}
			if(in_array($this->field['datatype'],array('htmltext','multitext','text'))){
				if($min && strlen($this->newvalue) < $min) $this->error = $this->field['cname']." &nbsp;".lang('lengsmalmilim');
				if($max && strlen($this->newvalue) > $max) $this->error = $this->field['cname']." &nbsp;".lang('lenglargmaxlimi');
			}
		}elseif($this->field['datatype'] == 'int'){
			$this->newvalue = intval($this->newvalue);
			if($min && $this->newvalue < $min) $this->error = $this->field['cname']." &nbsp;".lang('smallminilimi');
			if($max && $this->newvalue > $max) $this->error = $this->field['cname']." &nbsp;".lang('largmaxlimi');
		}elseif($this->field['datatype'] == 'float'){
			$this->newvalue = floatval($this->newvalue);
			if($min && $this->newvalue < $min) $this->error = $this->field['cname']." &nbsp;".lang('smallminilimi');
			if($max && $this->newvalue > $max) $this->error = $this->field['cname']." &nbsp;".lang('largmaxlimi');
		}elseif(in_array($this->field['datatype'],array('images','files','medias','flashs'))){
			$counts = $this->newvalue ? count($this->newvalue) : 0;
			if($min && $counts < $min) $this->error = $this->field['cname']." &nbsp;".lang('attatamosmaminili');
			if($max && $counts > $max) $this->newvalue = marray_slice($this->newvalue,0,$max);
			$this->newvalue = $this->newvalue ? addslashes(serialize($this->newvalue)) : '';
		}
		return;
	}
	function check_null(){
		if($this->field['notnull'] && empty($this->newvalue)){
			$this->error = $this->field['cname'].lang('notnull');
			return false;
		}
		return true;
	}
	function check_limit(){
		$mlimit = $this->field['mlimit'];
		if($this->field['datatype'] == 'date'){
			$mlimit = 'date';
		}elseif($this->field['datatype'] == 'int'){
			$mlimit = 'int';
		}elseif($this->field['datatype'] == 'float'){
			$mlimit = 'number';
		}
		if(empty($this->newvalue) || empty($mlimit)) return true;
		$cname = $this->field['cname'];
		if($mlimit == 'date' && !isdate($this->newvalue)){
			$this->error = "$cname ".lang('liminpda');
		}elseif($mlimit == 'int' && !is_numeric($this->newvalue)){
			$this->error = "$cname ".lang('liminpint');
		}elseif($mlimit == 'number' && !is_numeric($this->newvalue)){
			$this->error = "$cname ".lang('liminpnum');
		}elseif($mlimit == 'letter' && !preg_match("/^[a-z]+$/i",$this->newvalue)){
			$this->error = "$cname ".lang('limiinputlett');
		}elseif($mlimit == 'numberletter' && !preg_match("/^[0-9a-z]+$/i",$this->newvalue)){
			$this->error = "$cname ".lang('limitinputnumberl');
		}elseif($mlimit == 'tagtype' && !preg_match("/^[a-z]+\w*$/i",$this->newvalue)){
			$this->error = "$cname ".lang('limitinputtagtype');
		}elseif($mlimit == 'email' && !isemail($this->newvalue)){
			$this->error = "$cname ".lang('limitinputemail');
		}
		return $this->error ? false : true;
	}
	function end_deal(){
		if(!empty($this->newvalue)){
			if($this->field['datatype'] == 'date'){
				$this->newvalue = strtotime($this->newvalue);
			}elseif($this->field['datatype'] == 'htmltext'){
				html_atm2tag($this->newvalue);
			}
		}
		return;
	}
	function make_submitstr($varname=''){//需要当前值，单个图片可以处理，图集不要处理了,需要返回错误控件的焦点
		foreach(array('datatype','notnull','mlimit','regular','min','max',) as $var) $$var = $this->field[$var];
		if(in_array($datatype,array('select','mselect'))) return;
		if(in_array($datatype,array('images','flashs','medias','files'))){
			$extmode = substr($datatype,0,strlen($datatype)-1);
		}elseif(in_array($datatype,array('image','flash','media','file'))) $extmode = $datatype;
		$exts = '';
		if(!empty($extmode)){
			global $localfiles;
			load_cache('localfiles');
			$exts = implode(',',array_keys($localfiles[$extmode]));
		}
		if(!$notnull && !$mlimit && !$regular && !$min && !$max && !$exts && $datatype != 'date') return;
		$regular = addslashes($regular);
		if(in_array($datatype,array('image','flash','media','file'))){
			$this->submitstr = "rmsg = checksimple('$varname','$notnull','$exts');\n";
		}elseif(in_array($datatype,array('images','flashs','medias','files'))){
			$this->submitstr = "rmsg = checkmultiple('$varname','$notnull','$exts','$min','$max');\n";
		}elseif($datatype == 'htmltext'){
			$this->submitstr = "rmsg = checkhtmltext('$varname','$notnull','$min','$max');\n";
		}elseif($datatype == 'multitext'){
			$this->submitstr = "rmsg = checkmultitext('$varname','$notnull','$min','$max');\n";
		}elseif($datatype == 'text'){
			$this->submitstr = "rmsg = checktext('$varname','$notnull','$mlimit','$regular','$min','$max');\n";
		}elseif($datatype == 'date'){
			$this->submitstr = "rmsg = checkdate('$varname','$notnull','$min','$max');\n";
		}elseif($datatype == 'int'){
			$this->submitstr = "rmsg = checkint('$varname','$notnull','$regular','$min','$max');\n";
		}elseif($datatype == 'float'){
			$this->submitstr = "rmsg = checkfloat('$varname','$notnull','$regular','$min','$max');\n";
		}elseif(in_array($datatype,array('cacc','map'))){
			$this->submitstr = "rmsg = checktext('$varname','$notnull');\n";
		}
		$this->submitstr .= "if(rmsg){\n	if(dom=\$id('alert_$varname'))dom.innerHTML = rmsg;\n	i = false;\n}\n";
	}
}
function fields_order($fields){
	if(empty($fields) || !is_array($fields) || !function_exists('array_multisort')) return $fields;
	foreach($fields as $k => $field){
		if(in_array($field['datatype'],array('int','float','date','select','mselect','cacc','map'))){
			$fields[$k]['dorder'] = '0';
		}elseif(in_array($field['datatype'],array('text','multitext','htmltext'))){
			$fields[$k]['dorder'] = '1';
		}elseif(in_array($field['datatype'],array('image','flash','media','file'))){
			$fields[$k]['dorder'] = '2';
		}else $fields[$k]['dorder'] = '3';
		$dorder[$k] = $fields[$k]['dorder'];
	}
	array_multisort($dorder,SORT_ASC,$fields);
	return $fields;
}
function trspecial($trname,$varname,$value = '',$type = 'htmltext',$mode=0,$guide='',$min=0,$max=0,$width='25%'){
	global $cms_abs,$ftp_url,$cmsurl,$subject_table;
	$_mc = defined('M_MCENTER') ? 1 : 0;
	$_mc && $trname = '<b>'.$trname.'</b>';
	$lcls = $_mc ? 'item1' : 'txt txtright fB borderright';
	$rcls = $_mc ? 'item2' : 'txt txtleft';
	$addstr = "<div id=\"alert_$varname\" name=\"alert_$varname\" class=\"".($_mc ? 'red' : 'mistake0')."\"></div>";
	if($guide) $addstr .= $_mc ? "<font class=\"gray\">$guide</font>" : "<div class=\"tips1\">$guide</div>";
	if($type == 'htmltext'){
		echo !$mode ? "<tr><td colspan=\"2\" class=\"".($_mc ? 'item1 item4' : 'txt txtleft fB')."\">".$trname.$addstr."</td></tr><tr><td colspan=\"2\" class=\"$rcls\">\n" : "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td><td class=\" class=\"$rcls\">\n";
		echo "<textarea cols=\"80\" id=\"$varname\" name=\"$varname\" rows=\"10\">" . htmlspecialchars(tag2atm($value, 1)) . '</textarea>'.
			"<script type=\"text/javascript\">CKEDITOR.replace('$varname',{" . ($mode ? "toolbar : 'simple'" : 'height : 500') . '});</script>';
		if($mode) echo $addstr;
		echo "</td></tr>\n";
	}elseif(in_array($type,array('images','files','medias','flashs'))){
		$type = substr($type,0,strlen($type)-1);
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"$rcls\">\n";
		uploadmodule($varname,$value,$type,$mode,$min,$max);
		echo "$addstr</td></tr>\n";
	}elseif($type == 'image'){
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"$rcls\">\n";
		singlemodule($varname,$value,'image');
		echo "$addstr</td></tr>\n";
	}elseif(in_array($type,array('file','flash','media'))){
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"$rcls\">\n";
		singlemodule($varname,$value,$type,$mode);
		echo "$addstr</td></tr>\n";
	}elseif($type == 'text'){
		if($subject_table && ($varname == 'subject' || strpos($varname,'[subject]'))) $addstr = "&nbsp;&nbsp;<input type=\"button\" value=\"".lang('checksubject')."\" onclick=\"checksubject(this,'$subject_table','$varname');\">$addstr";
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"$rcls\"><input type=\"text\" size=\"".($mode ? 60 : 20)."\" id=\"$varname\" name=\"$varname\" value=\"".$value."\">$addstr</td></tr>\n";
	}elseif($type == 'multitext'){//
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"txt txtleft\"><textarea rows=\"".($mode ? 10 : 4)."\" id=\"$varname\" name=\"$varname\" cols=\"".($mode ? 90 : 50)."\">".$value."</textarea>$addstr</td></tr>\n";
	}elseif($type == 'select'){
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"$rcls\">".($mode ? $value : "<select name=\"$varname\">".$value."</select>")."$addstr</td></tr>\n";
	}elseif($type == 'mselect'){
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"txt txtleft\">".$value."$addstr</td></tr>\n";
	}elseif($type == 'date'){
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"$rcls\"><input type=\"text\" id=\"$varname\" size=\"10\" name=\"$varname\" value=\"".$value."\" onclick=\"ShowCalendar(this.id);\">$addstr</td></tr>\n";
	}elseif($type == 'map'){
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"$rcls\"><input class=\"imgbtn\" type=\"image\" src=\"{$cms_abs}images/admina/mapmarker.gif\" onmouseover=\"this.onfocus()\" onfocus=\"_08cms.map.setButton(this,'marker','$varname','','$min','$mode');\" align=\"absmiddle\" /><span>$value</span><input type=\"hidden\" id=\"$varname\" name=\"$varname\" value=\"$value\">\n";
		echo "$addstr</td></tr>\n";
	}elseif($type == 'vote'){
		//$value是一个序列化之后的字串
		echo "<tr><td width=\"$width\" class=\"$lcls\">".$trname."</td>\n";
		echo "<td class=\"$rcls\">";
		$value = $value ? unserialize($value) : array();
		$length = count($value);/*
		echo "<div vote=\"item\" style=\"display:none\"><span vote=\"subject\">{subject}</span>&nbsp;"
			."[<a href=\"javascript://\" onclick=\"_08cms.vote.editVote(this,'$varname','$min','$mode',{index})\">".lang('edit')."</a>]&nbsp;"
			."[<a href=\"javascript://\" onclick=\"_08cms.vote.delVote(this,'$varname',{index})\">".lang('delete')."</a>]</div>\n";*/
		foreach($value as $k => $v){
			echo "<div id=\"{$varname}[$k]\"><span vote=\"subject\">$v[subject]</span>&nbsp;"
				."[<a href=\"javascript://\" onclick=\"_08cms.vote.editVote(this,'$varname','$min','$mode',$k)\">".lang('edit')."</a>]&nbsp;"
				."[<a href=\"javascript://\" onclick=\"_08cms.vote.delVote(this,'$varname',$k)\">".lang('delete')."</a>]</div>\n";
			foreach($v as $x => $z){
				if(is_array($z)){
					foreach($z as $a => $b){
						if(is_array($b)){
							foreach($b as $c => $e){
								echo "<input type=\"hidden\" name=\"{$varname}[$k][$x][$a][$c]\" value=\"$e\">\n";
							}
						}else{
							echo "<input type=\"hidden\" name=\"{$varname}[$k][$x][$a]\" value=\"$b\">\n";
						}
					}
				}else{
					echo "<input type=\"hidden\" name=\"{$varname}[$k][$x]\" value=\"$z\">\n";
				}
			}
		}
		echo "<a href=\"javascript://\" onclick=\"_08cms.vote.addVote(this,'$varname','$min','$mode')\">[".lang('addvote')."]</a>\n";
		echo "$addstr</td></tr>\n";
	}
}
function trcacc($trname,$varname,$oldstr='',$coid=0,$source=0,$ids='',$vmode=0,$smode=0,$guide='',$width='25%'){//vmode:034smode:02345
	$_mc = defined('M_MCENTER') ? 1 : 0;
	$vmode != 4 && $arr = cacc_arr($coid,$source,$ids);
	$str = '';
	if(!$vmode && !$smode){
		$arr = array(0 =>array('title' => lang('p_choose'),'level' => 0)) + $arr;
		foreach($arr as $k => $v) $arr[$k]['title'] = str_repeat('&nbsp; &nbsp; ',$v['level']).$v['title'];
		$str = "<select name=\"$varname\" id=\"$varname\">".umakeoption($arr,$oldstr).'</select>';
	}elseif($vmode <= 3){
		foreach($arr as $k => $v) $str .= "[$k,$v[pid],'".addslashes($v['title'])."',".(empty($v['unsel']) ? 0 : 1) . '],';
		$str = "<script>var data = [$str];\nmake_mbox('', '$varname', data, '$oldstr',$smode);</script>";
	}else $str = "<div><script>make_mbox('', '$varname', 'action=cacc&coid=$coid&source=$source&ids=$ids', '$oldstr',$smode);</script></div>";
	if($_mc){
		$addstr = "<div id=\"alert_$varname\" name=\"alert_$varname\" class=\"red\"></div>";
		if($guide) $addstr .= "<font class=\"gray\">$guide</font>";
		echo "<tr><td width=\"$width\" class=\"item1\"><b>$trname</b></td>\n";
		echo "<td class=\"item2\">$str$addstr</td></tr>\n";
	}else{
		$addstr = "<div id=\"alert_$varname\" name=\"alert_$varname\" class=\"mistake0\"></div>";
		if($guide) $addstr .= "<div class=\"tips1\">$guide</div>";
		echo "<tr><td width=\"$width\" class=\"txt txtright fB borderright\">".$trname."</td>\n";
		echo "<td class=\"txt txtleft\">$str$addstr</td></tr>\n";
	}
}

function uploadmodule($fieldname,$value = '',$mode = 'image',$vp = 0,$min = 0,$max = 0){
	global $localfiles;
	load_cache('localfiles');
	$upfilestr = '';
	${'exts_'.$mode} = implode(',',array_keys($localfiles[$mode]));
	if($value){
		$upfiles = @unserialize($value);
		if(is_array($upfiles)){
			$tmp=$vp && in_array($mode,array('media','flash'))?1:0;
			foreach($upfiles as $upfile){
				$upfile['remote'] = tag2atm($upfile['remote']);
				$upfilestr .= "\n$upfile[remote]|$upfile[title]".($tmp && !empty($upfile['player']) ? "|$upfile[player]" : '');
			}
		}
	}
	echo"<textarea id=\"$fieldname\" name=\"$fieldname\" wrap=\"off\" style=\"width:450px;height:200px;\">$upfilestr</textarea>&nbsp; <input type=\"button\" class=\"uploadbtn\" style=\"vertical-align: top;\" id=\"{$fieldname}select\" value=\"".lang('attachmentedit')."\" onclick=\"uploadwin('{$mode}s','$fieldname','$min','$max',$vp);\">";
}
function singlemodule($varname,$value = '',$mode = 'image',$vp = 0){
	$oldarr = explode('#',$value);
	$oldremote = tag2atm($oldarr[0]);
	global $localfiles;
	load_cache('localfiles');
	${'exts_'.$mode} = implode(',',array_keys($localfiles[$mode]));
	echo "<input type=\"text\" size=\"50\" id=\"$varname\" name=\"$varname\" value=\"$oldremote\">\n<input type=\"button\" class=\"uploadbtn\" id=\"{$varname}select\" value=\"".lang('attachmentedit')."\" onclick=\"uploadwin('$mode','$varname',1,1,$vp);\">\n";
}
function upload_s($newvalue,$oldvalue = '',$mode = 'image',$rpid=0){
	global $c_upload,$db,$tblprefix;
	if(!$newvalue) return '';
	$oldarr = explode('#',$oldvalue);
	$newarr = explode('#',$newvalue);
	if(!$newarr[0]) return '';
	if(basename($newarr[0]) == basename($oldarr[0])) return $oldvalue;
	if(islocal($newarr[0],1)){
		$filename = basename($newarr[0]);
		$newvalue = save_atmurl($newarr[0]);
		if($ufid = $db->result_one("SELECT ufid FROM {$tblprefix}userfiles WHERE filename='$filename' AND aid='0'")) $c_upload->ufids[] = $ufid;
	}else{
		$atm = $c_upload->remote_upload($newarr[0],$rpid);
		$newvalue = $atm['remote'];
		if(($mode == 'image') && !empty($atm['width']) && !empty($atm['height'])){
			$newvalue .= '#'.$atm['width'].'#'.$atm['height'];
			$sized = 1;
		}
	}
	if($mode == 'image'){
		if(empty($sized)){
			$info = @getimagesize(local_atm($newarr[0]));
			$newvalue .= '#'.(empty($info[0]) ? '' : $info[0]).'#'.(empty($info[1]) ? '' : $info[1]);
		}
	}else $newvalue .= !empty($newarr[1]) ? '#'.intval($newarr[1]) : '';
	unset($newarr,$atm,$info);
	return $newvalue;
}
function upload_m($newvalue,$oldvalue = '',$mode = 'image',$rpid=0){
	global $c_upload,$db,$tblprefix;
	if(!$newvalue) return '';
	
	$oldvalue = !$oldvalue ?  array() : unserialize($oldvalue);
	$oldarr = array();
	foreach($oldvalue as $k => $v) $oldarr[basename($v['remote'])] = $v;
	
	$temps = array_filter(explode("\n",$newvalue));
	if(!$temps) return '';
	$newarr = array();
	foreach($temps as $v){
		$v = str_replace(array("\n","\r"),'',$v);
		$row = explode('|',$v);
		$row[0] = trim($row[0]);
		if(!$row[0]) continue;
		$filename = basename($row[0]);
		$atm = array();
		if(array_key_exists($filename,$oldarr)){//旧数据
			$atm = $oldarr[$filename];
		}else{
			if(islocal($row[0],1)){//新的本地文件将附件id得到以便获取与文档的关联
				$atm['remote'] = save_atmurl($row[0]);
				if($info = $db->fetch_one("SELECT ufid,size FROM {$tblprefix}userfiles WHERE filename='$filename' AND aid='0'")){
					$c_upload->ufids[] = $info['ufid'];
					$atm['size'] = $info['size'];
				}
			}else $atm = $c_upload->remote_upload($row[0],$rpid);
		}
		$atm['title'] = empty($row[1]) ? '' : strip_tags($row[1]);
		if(!empty($row[2])) $atm['player'] = intval($row[2]);
		if($mode == 'image' && empty($atm['width']) && $info = @getimagesize(local_atm($row[0]))){//某些情况下的图片尺寸补全
			$atm['width'] = $info[0];
			$atm['height'] = $info[1];
		}
		$atm && $newarr[] = $atm;
	}
	unset($temps,$row,$atm,$info,$oldvalue,$oldarr);
	return $newarr;
}
function rm_filesize($url){
	$url = parse_url($url);
	if($fp = fsockopen($url['host'],empty($url['port']) ? 80 : $url['port'],$error)){
		fputs($fp,"GET ".(empty($url['path']) ? '/' : $url['path'])." HTTP/1.1\r\n");
		fputs($fp,"Host:$url[host]\r\n\r\n");
		while(!feof($fp)){
			$tmp = fgets($fp);
			if(trim($tmp) == ''){
				break;
			}elseif(preg_match('/Content-Length:(.*)/si',$tmp,$arr)){
				return trim($arr[1]);
			}
		}
	}
	return 0;
}

function atm_size($value,$datatype,$mode=0){//使用没有经过addslashes的值,以k为单位
	if(empty($value)) return 0;
	$size = 0;
	if(in_array($datatype,array('image','flash','media','file'))){
		$temps = explode('#',$value);
		if($url = tag2atm($temps[0])) $size = islocal($url) ? filesize(local_file($url)) : rm_filesize($url);
	}elseif(in_array($datatype,array('images','flashs','medias','files'))){
		if($temps = @unserialize($value)){
			foreach($temps as $v){
				if($url = tag2atm($v['remote'])){
					$size += isset($v['size']) ? $v['size'] : (islocal($url) ? filesize(local_file($url)) : rm_filesize($url));
					if($mode) break;
				}
			}
		}
	}
	unset($temps,$url);
	return intval($size / 1024);
}

function atm_byte($value, $datatype){
	return ccstrlen($datatype == 'htmltext' ? html2text($value) : $value);
}
function select_arr($innertext='',$fromcode=0){
	if(!$innertext) return array();
	$rets = array();
	if(!$fromcode){
		$temps = explode("\n",$innertext);
		foreach($temps as $v){
			$temparr = explode('=',str_replace(array("\r","\n"),'',$v));
			$temparr[1] = isset($temparr[1]) ? $temparr[1] : $temparr[0];
			$rets[$temparr[0]] = $temparr[1];
		}
		unset($temps,$temparr);
		
	}else{
		include_once M_ROOT."./dynamic/function/fields.fun.php";
		$rets = @eval($innertext);
	}
	return !$rets ? array() : $rets;
}

?>