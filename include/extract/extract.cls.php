<?php
define('EX_DISCOUNT', 'ex_discount');
define('EX_MINCOUNT', 'ex_mincount');

class extract_cash{
	var $crcuarr;
	var $extract;
	var $isadmin;

	function init(){
		if($this->crcuarr)return;
		load_cache('currencys,crprojects,grouptypes');
		global $db, $tblprefix, $curuser, $currencys, $crprojects, $grouptypes, $extract_mincount;
		$discount = array();
#		$mincount = empty($extract_mincount) ? array() : array($extract_mincount);
		$mincount = array(isset($extract_mincount) ? $extract_mincount : 50);
		foreach($grouptypes as $k => $v){
			if(!$v['issystem'] && $curuser->info['grouptype'.$k]){
				$usergroup = read_cache('usergroup',$k,$curuser->info['grouptype'.$k]);
				empty($usergroup[EX_DISCOUNT]) || $discount[] = $usergroup[EX_DISCOUNT];
#				empty($usergroup[EX_MINCOUNT]) || $mincount[] = $usergroup[EX_MINCOUNT];
			}
		}
#		$discount = array(90);$mincount = array(10);#test code
		(empty($discount) || empty($mincount)) && mcmessage('no_extract_permission');
		$this->crcuarr = array(
			'ex' => array(
						'cname'		=> lang('cashaccount'),
						'unit'		=> lang('yuan'),
						'count'		=> $curuser->info['currency0'],
						'discount'	=> max($discount),
						'mincount'	=> min($mincount)
			)
		);
		foreach($crprojects as $v){
			$k = $curuser->info["currency$v[scrid]"];
			if($v['ecrid'] == 0 && $k){
				$this->crcuarr[$v['scrid']] = array(
						'cname'		=> $currencys[$v['scrid']]['cname'],
						'unit'		=> $currencys[$v['scrid']]['unit'],
						'count'		=> $k,
						'discount'	=> round($v['ecurrency'] / $v['scurrency'], 4) * 100
				);
			}
		}
	}

	function addnew(){
		global $db, $tblprefix, $curuser;
		$this->init();
		if($this->extract = $db->fetch_one("SELECT * FROM {$tblprefix}extracts WHERE checkdate=0 AND mid={$curuser->info['mid']} LIMIT 0,1"))
			return $this->modify();
		$crcuarr = $this->crcuarr;
		$ex = $crcuarr['ex'];
		unset($crcuarr['ex']);
		if(!submitcheck('bextract')){
			$ex_tip = str_replace(
					array(
						'%n',
						'%v',
						'%u',
						'%d',
						'%c'
					),
					array(
						$ex['cname'],
						$ex['count'],
						$ex['unit'],
						$ex['discount'] . '%',
						$ex['mincount']
					),
			lang('currency0_extract'));
			$jscrcus = '';
			$jscrcudis = '';
			foreach($this->crcuarr as $k => $v){
				$jscrcus .= ",'$k':$v[count]";
				$jscrcudis .= ",'$k':$v[discount]";
			}
			$jscrcus = '{' . substr($jscrcus, 1) . '}';
			$jscrcudis = '{' . substr($jscrcudis, 1) . '}';
			$find = array("'", "\r", "\n", "\\");
			$repl = array("\\'", "\\r", "\\n", "\\\\");
			$lang_other_tip = str_replace($find, $repl, lang('currency_trade_tip'));
			$lang_total_tip = str_replace($find, $repl, lang('extract_total_tip'));
			$lang_total = str_replace($find, $repl, lang('extract_total'));
			$lang_confirm = str_replace($find, $repl, lang('extract_confirm'));
			$lang_min_tip = str_replace($find, $repl, lang('extract_mincount_tip'));
			echo "
<script type=\"text/javascript\">
var extract_mincount = $ex[mincount], ex_val_cnt = $jscrcus, ex_val_dis = $jscrcudis, ex_val_cvt = {},
	extract_langs = {
		other_tip:'$lang_other_tip',
		total_tip:'$lang_total_tip',
		total:'$lang_total',
		confirm:'$lang_confirm',
		min_tip:'$lang_min_tip'
	};
</script>
<script type=\"text/javascript\" src=\"include/extract/extract.js\"></script>
<form id=\"extract_form\" action=\"\" method=\"post\" onsubmit=\"return ex_form_check(this)\">";
			if(!empty($crcuarr)){
				$str = lang('crproject_tip');
				tabheader(lang('currency_list_tip'),'','',3);
				foreach($crcuarr as $k => $v){
					$tip = str_replace(
						array(
							'%n',
							'%v',
							'%u',
							'%d'
						),
						array(
							$v['cname'],
							$v['count'],
							$v['unit'],
							"$v[discount]%"
						),
					$str);
					echo "
			<tr><td class=\"item2\">$tip</td>
			<td class=\"item2\"><input type=\"text\" name=\"extractnew[$k]\" onfocus=\"ex_item_check(this)\" /></td>
			<td class=\"item2\" id=\"currency_tip_$k\"></tr>";
				}
				tabfooter();
			}
			$row = $db->fetch_one("SELECT remark FROM {$tblprefix}extracts WHERE mid={$curuser->info['mid']} LIMIT 0,1");
			echo "
	<div style=\"margin-top:20px;text-align:left\">$ex_tip</div>
	<div id=\"extract_button\" style=\"margin-top:20px" . ($ex['count'] < $ex['mincount'] ? ';display:none' : '') . "\">";
			tabheader('<span id="extract_total"></span>');
			trbasic(lang('extract_count'), '', "<input type=\"text\" name=\"extractnew[total]\" onfocus=\"ex_item_check(this)\" value=\"$ex[mincount]\" /> " . lang('yuan') . '&nbsp;<span id="currency_tip_total">' . str_replace('%v', round($ex['mincount'] * $ex['discount'] / 100, 2), lang('extract_total_tip')) . '</span>', '');
			trbasic(lang('remark'), 'extractnew[remark]', empty($row['remark']) ? lang('extract_remark') : $row['remark'], 'textarea');
			tabfooter('bextract', lang('submit_extract'));
			echo "
	</div>
	<div id=\"extract_message\" style=\"margin:20px" . ($ex['count'] < $ex['mincount'] ? '' : ';display:none') . "\">" . lang('less_than_mincount') . "</div>
</form>";
		}else{
			global $timestamp, $extractnew;
			$total = round(floatval($extractnew['total']), 2);
			$total < $ex['mincount'] && mcmessage('less_than_mincount', M_REFERER, $ex['mincount']);
			$crcuedit = array(0 => - $total);
			foreach($crcuarr as $k => $v){
				if(!empty($extractnew[$k])){
					$val = round(floatval($extractnew[$k]), 2);
					($val < 0 || $v['count'] < $val) && mcmessage('currency_muns_lack', M_REFERER, $v['cname']);
					if($val){
						$crcuedit[$k] = - $val;
						$crcuedit[0] += round($val * $v['discount'] / 100, 2);
					}
				}
			}
			$crcuedit[0] + $ex['count'] < 0 && mcmessage('extract_muns_lack', M_REFERER);
			$count = round($total * $ex['discount'] / 100, 2);
			$db->query("INSERT INTO {$tblprefix}extracts SET 
				mid={$curuser->info['mid']},
				mname='{$curuser->info['mname']}',
				integral=$total,
				total=$count,
				rate=$ex[discount],
				checkdate=0,
				remark='$extractnew[remark]',
				createdate=$timestamp");
			if($eid = $db->insert_id()){
				$curuser->updatecrids($crcuedit, 1);
				mcmessage('extract_operate_finish', axaction(6, M_REFERER));
			}else{
				mcmessage('extract_error', M_REFERER);
			}
		}
	}
	
	function modify(){
		global $db, $tblprefix, $curuser;
		$this->init();
		$this->extract || $this->extract = $db->fetch_one("SELECT * FROM {$tblprefix}extracts WHERE checkdate=0 AND mid={$curuser->info['mid']} LIMIT 0,1");
		$this->extract || mcmessage('noedit_extract_record');
		$ex = $this->crcuarr['ex'];
		if(!submitcheck('bmodify')){
			$tip = lang('extract_total_tip');
			$total = $ex['count'] + $this->extract['integral'];
			$find = array("'", "\r", "\n", "\\");
			$repl = array("\\'", "\\r", "\\n", "\\\\");
			$lang_total_tip = str_replace($find, $repl, $tip);
			$lang_confirm = str_replace($find, $repl, lang('extract_confirm'));
			$lang_min_tip = str_replace($find, $repl, lang('extract_mincount_tip'));
			$lang_no_modify = str_replace($find, $repl, lang('no_modify_action'));
			echo "
<script type=\"text/javascript\">
var extract_mincount = $ex[mincount], ex_val_cnt = {ex:$total,ed:{$this->extract['integral']}}, ex_val_dis = {ex:{$this->extract['rate']}}, ex_val_cvt = {},
	extract_langs = {
		total_tip:'$lang_total_tip',
		confirm:'$lang_confirm',
		min_tip:'$lang_min_tip',
		no_modify:'$lang_no_modify'
	};
</script>
<script type=\"text/javascript\" src=\"include/extract/extract.js\"></script>
<form id=\"extract_modify\" action=\"?$_SERVER[QUERY_STRING]\" method=\"post\" onsubmit=\"return ex_form_check(this)\">\n";
			tabheader(lang('extract_record_modify'));
			trbasic(lang('needtime'), '', date('Y-m-d H:i:s', $this->extract['createdate']), '');
			trbasic(lang('extract_count'), '', "<input type=\"text\" name=\"extractnew[total]\" onfocus=\"ex_item_check(this)\" value=\"{$this->extract['integral']}\" /> " . lang('yuan'), '');
			trbasic(lang('extract_discount'), '', $this->extract['rate'] . '%', '');
			trbasic(lang('prompt_msg'), '', '<span id="currency_tip_total">' . str_replace('%v', $this->extract['total'], $tip) . '</span>', '');
			trbasic(lang('remark'), 'extractnew[remark]', $this->extract['remark'], 'textarea');
			tabfooter('bmodify');
		}else{
			global $timestamp, $extractnew;
			$total = round(floatval($extractnew['total']), 2);
			$total < $ex['mincount'] && mcmessage('less_than_mincount', M_REFERER, $ex['mincount']);
			$crcuedit = array(0 => round($this->extract['integral'] - $total, 2));
			$crcuedit[0] + $ex['count'] < 0 && mcmessage('extract_muns_lack', M_REFERER);
			$count = round($total * $this->extract['rate'] / 100, 2);
			$db->query("UPDATE {$tblprefix}extracts SET 
				integral=$total,
				total=$count,
				remark='$extractnew[remark]',
				createdate=$timestamp 
				WHERE eid={$this->extract['eid']}");
			if($db->affected_rows()){
				$curuser->updatecrids($crcuedit, 1);
				mcmessage('extract_modify_finish', axaction(6, M_REFERER));
			}else{
				mcmessage('extract_error', M_REFERER);
			}
		}
	}

	function showlist(){
		global $db, $tblprefix, $curuser, $eid, $atpp, $page, $mid, $mname, $checked, $dmode, $date1, $date2;
		if($eid)return $this->isadmin ? $this->check() : ($eid == 'new' ? $this->addnew() : $this->show());
		if(!submitcheck('bextedit')){
			if(defined('M_MCENTER')){
				$css = array('L' => 'item2', 'R' => 'item right', 'C' => 'item');
				$membercname = lang('membercname');
				$checkstate = lang('checkstate');
			}else{
				$css = array('L' => 'txtL', 'R' => 'txtR', 'C' => 'txtC');
				$membercname = lang('member_cname');
				$checkstate = lang('check_state');
			}
			$page = !empty($page) ? max(1, intval($page)) : 1;
			submitcheck('bfilter') && $page = 1;
			if($this->isadmin){
				$wheresql = ' 1=1';
				$u_lists = array('mname', 'integral', 'total', 'rate', 'checkdate', 'createdate', 'view');
			}else{
				$wheresql = " mid={$curuser->info['mid']}";
				$u_lists = array('integral', 'total', 'rate', 'checkdate', 'createdate', 'view');
			}
			$mname && $wheresql .= " AND mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($mname,'%_'))."%'";
			isset($checked) || $checked = '-1';
			$checked != '-1' && $wheresql .= ' AND checkdate' . ($checked ? '!' : '') . '=0';
			$datefield = $dmode ? 'checkdate' : 'createdate';
			if($date1 && preg_match("/\s*(\d{4})-(\d{1,2})-(\d{1,2})(?:\s+(\d{1,2}):(\d{1,2}):(\d{1,2}))?\s*$/", $date1, $match)){
				$date = mktime(empty($match[4]) ? 0 : $match[4], empty($match[5]) ? 0 : $match[5], empty($match[6]) ? 0 : $match[6], $match[2], $match[3], $match[1]);
				$date && $date > 0 && $wheresql .= " AND $datefield>='$date'";
			}
			if($date2 && preg_match("/\s*(\d{4})-(\d{1,2})-(\d{1,2})(?:\s+(\d{1,2}):(\d{1,2}):(\d{1,2}))?\s*$/", $date2, $match)){
				$date = mktime(empty($match[4]) ? 24 : $match[4], empty($match[5]) ? 59 : $match[5], empty($match[6]) ? 59 : $match[6], $match[2], $match[3], $match[1]);
				$date && $date > 0 && $wheresql .= " AND $datefield<='$date'";
			}

			echo form_str('extract_list',"?$_SERVER[QUERY_STRING]");
			if($this->isadmin){
				//搜索区块
				tabheader_e();
				echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
				//关键词固定显示
				echo $membercname."&nbsp; <input class=\"text\" name=\"mname\" type=\"text\" value=\"$mname\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
				$checkarr = array('-1' => lang('nolimit'), '0' => lang('nocheck'), '1' => lang('checked'));
				echo "<select style=\"vertical-align: middle;\" name=\"checked\">" . makeoption($checkarr, $checked) . "</select>&nbsp; ";
				$dmodearr = array('0' => lang('needtime'), '1' => lang('checkdate'));
				echo "<select style=\"vertical-align: middle;\" name=\"dmode\">" . makeoption($dmodearr, $dmode) . "</select>&nbsp; " .
					"<input class=\"text\" id=\"extract_date1\" name=\"date1\" type=\"text\" value=\"$date1\" onclick=\"ShowCalendar(this.id);\" style=\"vertical-align: middle;width:120px\">&nbsp; -&nbsp; " .
					"<input class=\"text\" id=\"extract_date2\" name=\"date2\" type=\"text\" value=\"$date2\" onclick=\"ShowCalendar(this.id);\" style=\"vertical-align: middle;width:120px\">&nbsp; " .
					"<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">&nbsp;" .
					"</td></tr>";
				tabfooter();
			}

			$pagetmp = $page;
			do{
				$query = $db->query("SELECT * FROM {$tblprefix}extracts WHERE $wheresql ORDER BY $datefield DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
				$pagetmp--;
			}while(!$db->num_rows($query) && $pagetmp);
			$count = $db->result_one("SELECT count(*) FROM {$tblprefix}extracts WHERE $wheresql");
			$view = lang('message');
			tabheader(lang('extract_list') . ($this->isadmin ? '' : "&nbsp;[<a href=\"?$_SERVER[QUERY_STRING]&eid=new\" onclick=\"return floatwin('open_extractview',this)\">" . lang('submit_extract') . '</a>]'), '', '', count($u_lists) + 1);
			$cy_arr = array();
			$this->isadmin && $cy_arr[] = '<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form, \'selectid\', \'chkall\')">';
			in_array('mname',$u_lists) && $cy_arr[] = array($membercname, $css['L']);
			in_array('integral',$u_lists) && $cy_arr[] = lang('extract_count');
			in_array('total',$u_lists) && $cy_arr[] = lang('extract_getcount');
			in_array('rate',$u_lists) && $cy_arr[] = lang('extract_discount');
			in_array('checkdate',$u_lists) && $cy_arr[] = lang('checkdate');
			in_array('createdate',$u_lists) && $cy_arr[] = lang('needtime');
#			in_array('delstate',$u_lists) && $cy_arr[] = lang('delstate');
			in_array('view',$u_lists) && $cy_arr[] = $view;
			trcategory($cy_arr);
			while($item = $db->fetch_array($query)){
#				$checked = $item['checked'] ? 'Y' : '-';
#				$delete = $item['delstate'] ? 'Y' : '-';
				$checkdate = $item['checkdate'] ? date('Y-m-d', $item['checkdate']) : '-';
				$createdate = date('Y-m-d', $item['createdate']);
				$itemstr = '<tr class="txt">';
				$this->isadmin && $itemstr .= "<td class=\"$css[C] w40\" ><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$item[eid]]\" value=\"$item[eid]\"></td>\n";
				in_array('mname',$u_lists) && $itemstr .= "<td class=\"$css[L]\">$item[mname]</td>\n";
				in_array('integral',$u_lists) && $itemstr .= "<td class=\"$css[C]\">$item[integral]</td>\n";
				in_array('total',$u_lists) && $itemstr .= "<td class=\"$css[C]\">$item[total]</td>\n";
				in_array('rate',$u_lists) && $itemstr .= "<td class=\"$css[C]\">$item[rate]%</td>\n";
				in_array('checkdate',$u_lists) && $itemstr .= "<td class=\"$css[C]\">$checkdate</td>\n";
				in_array('createdate',$u_lists) && $itemstr .= "<td class=\"$css[C]\">$createdate</td>\n";
#				in_array('delstate',$u_lists) && $itemstr .= "<td class=\"$css[C]\">$delete</td>\n";
				in_array('view',$u_lists) && $itemstr .= "<td class=\"$css[C]\"><a href=\"?$_SERVER[QUERY_STRING]&eid=$item[eid]\" onclick=\"return floatwin('open_extractview',this)\">$view</a></td>\n";
				$itemstr .= "</tr>\n";
				echo $itemstr;
			}
			tabfooter();
			echo multi($count, $atpp, $page, preg_replace("/[?&]page=\d+$|([?&])page=\d+&/", '$1', "?$_SERVER[QUERY_STRING]"));
			if($this->isadmin){
				tabheader(lang('operate_item'));
				trbasic(lang('choose_item'), '', '<input class="checkbox" type="checkbox" name="extdeal[delete]" id="extdeal_delete" value="1"><label for="extdeal_delete" >' . lang('delete') . '</label>&nbsp;<input class="checkbox" type="checkbox" name="extdeal[check]" id="extdeal_check" value="1"><label for="extdeal_check" >' . lang('check') . '</label>&nbsp;', '');
				tabfooter('bextedit');
			}
		}elseif($this->isadmin){
			global $selectid, $extdeal, $timestamp;
			if(function_exists('mcmessage')){
				$message = 'mcmessage';
				$empty_item = 'selectoperateitem';
			}else{
				$message = 'amessage';
				$empty_item = 'selectoperateitem';
			}
			empty($extdeal) && $message($empty_item , axaction(1, M_REFERER));
			empty($selectid) && $message('select_extract', axaction(1, M_REFERER));
			$wheresql	= $this->isadmin ? '' : " AND mid={$curuser->info['mid']}";
			$user = new cls_userinfo;
			foreach($selectid as $eid){
				if(!empty($extdeal['delete'])){
					if($row = $db->fetch_one("SELECT mid,integral,checkdate FROM {$tblprefix}extracts WHERE eid='$eid'$wheresql LIMIT 0,1")){
/*						if($this->isadmin){
							$sql = $row['delstate'] == 2 || !$row['checked'] ? "DELETE FROM {$tblprefix}extracts" :($row['delstate'] == 0 ? "UPDATE {$tblprefix}extracts SET delstate=1" : '');
						}else{
							$sql = $row['delstate'] == 1 || !$row['checked'] ? "DELETE FROM {$tblprefix}extracts" :($row['delstate'] == 0 ? "UPDATE {$tblprefix}extracts SET delstate=2" : '');
						}
						$sql && $db->query("$sql WHERE eid='$eid'");*/
						if($row['checkdate'] == 0){
							$user->activeuser($row['mid']);
							$user->updatecrids(array( '0' => $row['integral']), 1);
						}
						$db->query("DELETE FROM {$tblprefix}extracts WHERE eid='$eid'");
					}
					continue;
				}
				$this->isadmin && $db->query("UPDATE {$tblprefix}extracts SET checkdate=$timestamp WHERE checkdate=0 AND eid='$eid'");
			}
			$this->isadmin && !empty($extdeal['delete']) && adminlog(lang('extract_admin'),lang('extract_operate'));
			$message('extract_operate_finish', M_REFERER);
		}
	}

	function show(){
		global $db, $tblprefix, $curuser, $eid;
		$this->extract || $this->extract = $db->fetch_one("SELECT * FROM {$tblprefix}extracts WHERE eid='$eid' AND mid={$curuser->info['mid']} LIMIT 0,1");
		$this->extract || mcmessage('invalid_extract_record');
		if(!$this->extract['checkdate'])return $this->modify();
		tabheader(lang('extract_record_info'));
		trbasic(lang('needtime'), '', date('Y-m-d H:i:s', $this->extract['createdate']), '');
		trbasic(lang('checkdate'), '', date('Y-m-d', $this->extract['checkdate']), '');
		trbasic(lang('extract_count'), '', $this->extract['integral'] . lang('yuan'), '');
		trbasic(lang('extract_discount'), '', $this->extract['rate'] . '%', '');
		trbasic(lang('extract_getcount'), '', $this->extract['total'] . lang('yuan'), '');
		trbasic(lang('remark'), '', str_replace("\n", '<br />', htmlspecialchars($this->extract['remark'])), '');
		tabfooter();
	}
	function check(){
		global $db, $tblprefix, $eid, $timestamp, $forward;
		if(submitcheck('bconfirm')){
			$db->query("UPDATE {$tblprefix}extracts SET checkdate=$timestamp WHERE eid='$eid' AND checkdate=0");
			amessage($db->affected_rows() ? 'extract_check_finish' : 'invalid_extract_record', axaction(6, $forward ? $forward : M_REFERER));
		}
		$this->extract || $this->extract = $db->fetch_one("SELECT * FROM {$tblprefix}extracts WHERE eid='$eid' LIMIT 0,1");
		$this->extract || amessage('invalid_extract_record');
		tabheader(lang('extract_record_check'));
		trbasic(lang('needtime'), '', date('Y-m-d H:i:s', $this->extract['createdate']), '');
		trbasic(lang('checkdate'), '', $this->extract['checkdate'] ? date('Y-m-d H:i:s', $this->extract['checkdate']) : '-', '');
		trbasic(lang('extract_count'), '', $this->extract['integral'] . lang('yuan'), '');
		trbasic(lang('extract_discount'), '', $this->extract['rate'] . '%', '');
		trbasic(lang('extract_getcount'), '', $this->extract['total'] . lang('yuan'), '');
		trbasic(lang('remark'), '', str_replace("\n", '<br />', htmlspecialchars($this->extract['remark'])), '');
		tabfooter();
		if(!$this->extract['checkdate'])echo '<form action="?' . $_SERVER['QUERY_STRING'] . '" method="post"><input class="bigButton" type="submit" name="bconfirm" value="' . lang('check') . '"></form>';
	}
}
?>