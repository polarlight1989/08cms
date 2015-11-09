<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
load_cache('currencys,crprojects,crprices');
$url_type = 'currency';include 'urlsarr.inc.php';
if($action == 'currencysedit'){
	backallow('currency') || amessage('no_apermission');
	url_nav(lang('crconfig'),$urlsarr,'type');
	if(!submitcheck('bcurrencyadd') && !submitcheck('bcurrencysedit')){
		$currencyslist = '';
		tabheader(lang('currency_manager'),'currencysedit','?entry=currencys&action=currencysedit','6');
		trcategory(array(lang('delete'),lang('currency_name'),lang('unit'),lang('reg_initval'),lang('detail')));
		foreach($currencys as $k => $v){
			echo "<tr  class=\"txt\"><td class=\"txtC\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\"></td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"12\" maxlength=\"30\" name=\"currencysnew[$k][cname]\" value=\"$v[cname]\"></td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"12\" maxlength=\"30\" name=\"currencysnew[$k][unit]\" value=\"$v[unit]\"></td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"12\" maxlength=\"30\" name=\"currencysnew[$k][initial]\" value=\"$v[initial]\"></td>\n".
				"<td class=\"txtC\"><a href=\"?entry=currencys&action=currencydetail&crid=$k\" onclick=\"return floatwin('open_currencydetail',this)\">".lang('setting')."</a></td></tr>\n";
		}
		tabfooter('bcurrencysedit',lang('modify'));

		tabheader(lang('add_currency'),'currencyadd','?entry=currencys&action=currencysedit');
		trbasic(lang('currency_name'),'currencyadd[cname]');
		trbasic(lang('currency_unit'),'currencyadd[unit]');
		trbasic(lang('reg_initval'),'currencyadd[initial]');
		tabfooter('bcurrencyadd',lang('add'));
		a_guide('currencysedit');
	}elseif(submitcheck('bcurrencyadd')){
		$currencyadd['cname'] = trim($currencyadd['cname']);
		$currencyadd['unit'] = trim($currencyadd['unit']);
		$currencyadd['initial'] = max(0,intval($currencyadd['initial']));
		if(empty($currencyadd['cname'])) amessage('datamissing','?entry=currencys&action=currencysedit');
		$db->query("INSERT INTO {$tblprefix}currencys SET 
					cname='$currencyadd[cname]', 
					unit='$currencyadd[unit]', 
					initial='$currencyadd[initial]'");
		$crid = $db->insert_id();
		$addfield = 'currency'.$crid;
		$db->query("ALTER TABLE {$tblprefix}members ADD $addfield float NOT NULL default 0", 'SILENT');
		updatecache('currencys');
		adminlog(lang('add_currency_type'),lang('add_currency_type'));
		amessage('currencyaddfinish','?entry=currencys&action=currencysedit');
	}elseif(submitcheck('bcurrencysedit')){
		if(!empty($delete)){
			foreach($delete as $crid){//积分删除需要作更多的分析，
				if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}crprices WHERE crid='$crid'")) continue;
				if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}crprojects WHERE scrid='$crid' OR ecrid='$crid'")) continue;
				unset($currencysnew[$crid]);
				$deletefield = 'currency'.$crid;
				$db->query("DELETE FROM {$tblprefix}currencys WHERE crid='$crid'", 'SILENT');
				$db->query("DELETE FROM {$tblprefix}crprices WHERE crid='$crid'", 'SILENT');
				$db->query("ALTER TABLE {$tblprefix}members DROP $deletefield", 'SILENT'); 
			}
		}
		if(!empty($currencysnew)){
			foreach($currencysnew as $crid => $currencynew){
				$currencynew['cname'] = empty($currencynew['cname']) ? $currencys[$crid]['cname'] : $currencynew['cname'];
				$currencynew['unit'] = trim($currencynew['unit']);
				$currencynew['initial'] = max(0,intval($currencynew['initial']));
				$db->query("UPDATE {$tblprefix}currencys SET 
							cname='$currencynew[cname]', 
							initial='$currencynew[initial]', 
							unit='$currencynew[unit]' 
							WHERE crid='$crid'");
			}
		}
		updatecache('currencys');
		adminlog(lang('edit_currency_type'),lang('modify_currency_mlist'));
		amessage('currencyfinish', '?entry=currencys&action=currencysedit');
	}
}elseif($action == 'currencydetail' && $crid){
	backallow('currency') || amessage('no_apermission');
	$currency = $currencys[$crid];
	if(!submitcheck('bcurrencydetail')){
		tabheader(lang('edit_currency'),'currencydetail',"?entry=currencys&action=currencydetail&crid=$crid");
		trbasic(lang('currency_name'),'currencynew[cname]',$currency['cname']);
		trbasic(lang('currency_unit'),'currencynew[unit]',$currency['unit']);
		trbasic(lang('reg_initval'),'currencynew[initial]',$currency['initial']);
		trbasic(lang('currency_allow_inout'),'currencynew[saving]',$currency['saving'],'radio');
		tabfooter();
		tabheader(lang('crpolicy'));
		trbasic(lang('issue_arc').'(+)','currencynew[archive]',isset($currency['archive']) ? $currency['archive'] : 0);
		trbasic(lang('issue_freeinfo').'(-)','currencynew[freeinfo]',isset($currency['freeinfo']) ? $currency['freeinfo'] : 0);
		trbasic(lang('issue_comment').'(+)','currencynew[comment]',isset($currency['comment']) ? $currency['comment'] : 0);
		trbasic(lang('purchase_goods').'(+)','currencynew[purchase]',isset($currency['purchase']) ? $currency['purchase'] : 0);
		trbasic(lang('question_answer').'(+)','currencynew[answer]',isset($currency['answer']) ? $currency['answer'] : 0);
		trbasic(lang('favorite_arc').'(-)','currencynew[favorite]',isset($currency['favorite']) ? $currency['favorite'] : 0);
		trbasic(lang('other_commu').'(+)','currencynew[commu]',isset($currency['commu']) ? $currency['commu'] : 0);
		trbasic(lang('website_vote').'(+)','currencynew[vote]',isset($currency['vote']) ? $currency['vote'] : 0);
		trbasic(lang('send_pm').'(-)','currencynew[pm]',isset($currency['pm']) ? $currency['pm'] : 0);
		trbasic(lang('website_search').'(-)','currencynew[search]',isset($currency['search']) ? $currency['search'] : 0);
		tabfooter('bcurrencydetail',lang('modify'));
		a_guide('currencydetail');
	}else{
		$sqlstr = '';
		foreach(array('archive','freeinfo','comment','purchase','answer','favorite','commu','vote','pm','search',) as $var){
			$currencynew[$var] = max(0,round($currencynew[$var],2));
			$sqlstr .= ($sqlstr ? ',': '')."$var='".$currencynew[$var]."'";
		}
		$db->query("UPDATE {$tblprefix}currencys SET
				cname='$currencynew[cname]',
				unit='$currencynew[unit]',
				saving='$currencynew[saving]',
				initial='$currencynew[initial]',
				$sqlstr
				WHERE crid='$crid'");
		updatecache('currencys');
		adminlog(lang('edit_currency_type'),lang('det_modify_cutype'));
		amessage('currencyeditfinish',axaction(6,"?entry=currencys&action=currencydetail&crid=$crid"));
	}
}elseif($action == 'crprices'){
	backallow('currency') || amessage('no_apermission');
	url_nav(lang('crconfig'),$urlsarr,'price');
	$cridsarr = cridsarr(1);
	empty($cridsarr) && amessage('definecurrencytype');
	if(!submitcheck('bcrpricesedit') && !submitcheck('bcrpricesadd')){
		tabheader(lang('price_prj_manager'),'crpricesedit','?entry=currencys&action=crprices','10');
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),lang('price_name'),lang('type'),lang('amount'),lang('arc_browse'),lang('arc_sale'),lang('arc_issue'),lang('att_operate'),lang('att_sale')));
		foreach($crprices as $k => $crprice){
			echo "<tr class=\"txt\">".
				"<td class=\"txtL w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\"></td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"20\" name=\"crpricesnew[$k][cname]\" value=\"$crprice[cname]\"></td>\n".
				"<td class=\"txtC w60\">".$cridsarr[$crprice['crid']]."</td>\n".
				"<td class=\"txtC w60\">".$crprice['crvalue']."</td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"crpricesnew[$k][tax]\" value=\"1\"".(empty($crprice['tax']) ? "" : " checked")."></td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"crpricesnew[$k][sale]\" value=\"1\"".(empty($crprice['sale']) ? "" : " checked")."></td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"crpricesnew[$k][award]\" value=\"1\"".(empty($crprice['award']) ? "" : " checked")."></td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"crpricesnew[$k][ftax]\" value=\"1\"".(empty($crprice['ftax']) ? "" : " checked")."></td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"crpricesnew[$k][fsale]\" value=\"1\"".(empty($crprice['fsale']) ? "" : " checked")."></td></tr>\n";
		}
		tabfooter('bcrpricesedit',lang('modify'));

		tabheader(lang('add_price_project'),'crpricesadd','?entry=currencys&action=crprices');
		trbasic(lang('price_name'),'crpriceadd[cname]');
		trbasic(lang('currencytype'),'crpriceadd[crid]',makeoption($cridsarr),'select');
		trbasic(lang('currency_amount'),'crpriceadd[crvalue]');
		tabfooter('bcrpricesadd',lang('add'));
		a_guide('crprices');
	}elseif(submitcheck('bcrpricesadd')){
		$crpriceadd['crvalue'] = empty($crpriceadd['crvalue']) ? 0 : round($crpriceadd['crvalue'],2);
		$crpriceadd['cname'] = trim($crpriceadd['cname']);
		if(empty($crpriceadd['crvalue']) || empty($crpriceadd['cname'])) amessage('datamissing','?entry=currencys&action=crprices');
		$crpriceadd['ename'] = $crpriceadd['crid'].'_'.$crpriceadd['crvalue'];
		if(in_array($crpriceadd['ename'],array_keys($crprices))) amessage('pricenamerepeat','?entry=currencys&action=crprices');
		$db->query("INSERT INTO {$tblprefix}crprices SET 
					cname='$crpriceadd[cname]', 
					ename='$crpriceadd[ename]', 
					crid='$crpriceadd[crid]', 
					crvalue='$crpriceadd[crvalue]'
					");
		updatecache('crprices');
		adminlog(lang('add_cu_price_prj'),lang('add_cu_price_prj'));
		amessage('currency price add finish', '?entry=currencys&action=crprices');
	}elseif(submitcheck('bcrpricesedit')){
		if(!empty($delete)){
			foreach($delete as $k){//将相关使用该积分的地方全部清空
				$db->query("UPDATE {$tblprefix}catalogs  SET taxcp='' WHERE taxcp='$k'",'SILENT');
				$db->query("UPDATE {$tblprefix}catalogs  SET awardcp='' WHERE awardcp='$k'",'SILENT');
				$db->query("UPDATE {$tblprefix}catalogs  SET ftaxcp='' WHERE ftaxcp='$k'",'SILENT');
				$db->query("UPDATE {$tblprefix}coclass  SET taxcp='' WHERE taxcp='$k'",'SILENT');
				$db->query("UPDATE {$tblprefix}coclass  SET awardcp='' WHERE awardcp='$k'",'SILENT');
				$db->query("UPDATE {$tblprefix}coclass  SET fsalecp='' WHERE fsalecp='$k'",'SILENT');
				$db->query("UPDATE {$tblprefix}archives SET salecp='' WHERE salecp='$k'",'SILENT');
				$db->query("UPDATE {$tblprefix}archives SET taxcp='' WHERE taxcp='$k'",'SILENT');
				$db->query("DELETE FROM {$tblprefix}crprices WHERE ename='$k'",'SILENT');
				unset($crpricesnew[$k]);
			}
		}
		if(!empty($crpricesnew)){
			foreach($crpricesnew as $k => $crpricenew){
				$crpricenew['cname'] = empty($crpricenew['cname']) ? $crprices[$k]['cname'] : $crpricenew['cname'];
				$sqlstr = "cname='$crpricenew[cname]'";
				$arr = explode('_',$k);
				foreach(array('tax','sale','award','ftax','fsale') as $varname){
					$crpricenew[$varname] = empty($crpricenew[$varname]) ? 0 : 1;
					if($arr[1] < 0 && $varname != 'award') $crpricenew[$varname] = 0;
					$sqlstr .= ",$varname=".$crpricenew[$varname];
				}
				$db->query("UPDATE {$tblprefix}crprices SET $sqlstr WHERE ename='$k'");
			}
		}
		updatecache('crprices');
		adminlog(lang('edit_cu_price_prj'),lang('edit_cu_prj_mlist'));
		amessage('currencypriceeditfinish','?entry=currencys&action=crprices');
	}
}elseif($action == 'crprojects'){
	backallow('currency') || amessage('no_apermission');
	url_nav(lang('crconfig'),$urlsarr,'project');
	$crids = cridsarr(1);
	if(count($crids) < 2) amessage('definemorecurrencytype');
	if(!submitcheck('bcrprojectsedit') && !submitcheck('bcrprojectsadd')){
		tabheader(lang('ex_prj_manager'),'crprojectsedit','?entry=currencys&action=crprojects','5');
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),lang('source_currency'),lang('src_cu_val'),lang('ex_currency'),lang('ex_cu_val')));
		foreach($crprojects as $crpid => $crproject){
			echo "<tr class=\"txt\">".
				"<td class=\"txtL w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$crpid]\" value=\"$crpid\"></td>\n".
				"<td class=\"txtC w100\">".$crids[$crproject['scrid']]."</td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"20\" name=\"crprojectsnew[$crpid][scurrency]\" value=\"$crproject[scurrency]\"></td>\n".
				"<td class=\"txtC w100\">".$crids[$crproject['ecrid']]."</td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"20\" name=\"crprojectsnew[$crpid][ecurrency]\" value=\"$crproject[ecurrency]\"></td></tr>\n";
		}
		tabfooter('bcrprojectsedit',lang('modify'));

		tabheader(lang('add_ex_prj'),'crprojectsadd','?entry=currencys&action=crprojects');
		trbasic(lang('source_currency'),'crprojectadd[scrid]',makeoption($crids),'select');
		trbasic(lang('src_cu_val'),'crprojectadd[scurrency]');
		trbasic(lang('ex_currency'),'crprojectadd[ecrid]',makeoption($crids),'select');
		trbasic(lang('ex_cu_val'),'crprojectadd[ecurrency]');
		tabfooter('bcrprojectsadd',lang('add'));
		a_guide('crprojects');
	}elseif(submitcheck('bcrprojectsadd')){
		if($crprojectadd['scrid'] == $crprojectadd['ecrid']) amessage('notexchangesame','?entry=currencys&action=crprojects');
		$crprojectadd['ename'] = $crprojectadd['scrid'].'_'.$crprojectadd['ecrid'];
		$enamearr = array();
		foreach($crprojects as $v) $enamearr[] = $v['ename'];
		if(in_array($crprojectadd['ename'], $enamearr)) amessage('exchangeexist','?entry=currencys&action=crprojects');
		$crprojectadd['scurrency'] = max(1,intval($crprojectadd['scurrency']));
		$crprojectadd['ecurrency'] = max(1,intval($crprojectadd['ecurrency']));
		$db->query("INSERT INTO {$tblprefix}crprojects SET 
					ename='$crprojectadd[ename]', 
					scrid='$crprojectadd[scrid]', 
					scurrency='$crprojectadd[scurrency]', 
					ecrid='$crprojectadd[ecrid]', 
					ecurrency='$crprojectadd[ecurrency]'
					");
		updatecache('crprojects');
		adminlog(lang('add_cu_ex_prj'),lang('add_cu_ex_prj'));
		amessage('exchangeaddfinish', '?entry=currencys&action=crprojects');
	}elseif(submitcheck('bcrprojectsedit')){
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}crprojects WHERE crpid=$k");
				unset($crprojectsnew[$k]);
			}
		}
		foreach($crprojectsnew as $crpid => $crprojectnew){
			$crprojectnew['scurrency'] = max(1,intval($crprojectnew['scurrency']));
			$crprojectnew['ecurrency'] = max(1,intval($crprojectnew['ecurrency']));
			$db->query("UPDATE {$tblprefix}crprojects SET 
						scurrency='$crprojectnew[scurrency]',
						ecurrency='$crprojectnew[ecurrency]'
						WHERE crpid='$crpid'");
		}
		updatecache('crprojects');
		adminlog(lang('edit_cu_ex_prj'),lang('edit_cu_ex_prj_mlist'));
		amessage('exchangemodifyfinish','?entry=currencys&action=crprojects');
	}
}elseif($action == 'currencysaving'){
	backallow('save') || amessage('no_apermission');
	$url_type = 'cysave';include 'urlsarr.inc.php';
	url_nav(lang('member_inout'),$urlsarr,'save');
	$crids = array(0 => lang('cash'));
	foreach($currencys as $k => $v) if($v['saving']) $crids[$k] = $v['cname'];
	empty($crids) && amessage('defineinoutcutype');
	if(!submitcheck('bcurrencysaving')){
		$savingmodearr = array('0' => lang('saving'),'1' => lang('deductvalue'));
		tabheader(lang('member_inout'),'currencysaving','?entry=currencys&action=currencysaving');
		trbasic(lang('member_cname'),'crsaving[mname]');
		trbasic(lang('choose_cutype'),'',makeradio('crsaving[crid]',$crids),'');
		trbasic(lang('operate_type'),'',makeradio('crsaving[savingmode]',$savingmodearr),'');
		trbasic(lang('currency_amount'),'crsaving[currency]');
		tabfooter('bcurrencysaving');
		a_guide('currencysaving');
	
	}else{
		$crsaving['mname'] = trim($crsaving['mname']);
		$crsaving['currency'] = max(0,round($crsaving['currency'],2));
		if(empty($crsaving['mname']) || empty($crsaving['currency'])) amessage('datamissing','?entry=currencys&action=currencysaving');
		$mnames = array_filter(explode(',',$crsaving['mname']));
		$actuser = new cls_userinfo;
		foreach($mnames as $v){
			$v = trim($v);
			if(empty($v)) continue;
			$actuser->activeuserbyname($v);
			$actuser->cridsaving($crsaving['crid'],$crsaving['savingmode'] ? 0 : 1,$crsaving['currency']);
		}
		unset($actuser);
		adminlog(lang('member_cu_saving'),lang('member_cu_saving'));
		amessage('currencyinoutfinish','?entry=currencys&action=currencysaving');
	}
}elseif($action == 'cradminlogs'){
	backallow('save') || amessage('no_apermission');
	$url_type = 'cysave';include 'urlsarr.inc.php';
	url_nav(lang('member_inout'),$urlsarr,'record');
	$page = empty($page) ? 1 : $page;
	$page = max(1, intval($page));
	$viewdetail = empty($viewdetail) ? '' : $viewdetail;
	$crid = !isset($crid) ? '-1' : $crid;
	$dealmode = empty($dealmode) ? '' : $dealmode;
	$mode = empty($mode) ? '' : $mode;
	$mnames = empty($mnames) ? '' : $mnames;
	$frommnames = empty($frommnames) ? '' : $frommnames;
	$startdate = empty($startdate) ? '' : strtotime($startdate);
	$enddate = empty($enddate) ? '' : strtotime($enddate);
	$filterstr = "&viewdetail=$viewdetail&crid=$crid&dealmode=$dealmode&mode=$mode&mnames=$mnames&frommnames=$frommnames&startdate=$startdate&enddate=$enddate";
	$currencyarr = array('-1' => lang('nolimit')) + cridsarr(1);
	$modearr = array('0' => lang('nolimit'),'add' => lang('add_val'),'reduce' => lang('reduce_val'));
	$dealmodearr = array('0' => lang('nolimit'),'saving' => lang('savingmode'),'award' => lang('addreduce'));
	tabheader(lang('filter_record')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('tbodyfilter')\"".(empty($viewdetail) ? '' : ' checked').">".lang('viewdetail'),'cradminlogs',"?entry=currencys&action=cradminlogs");
	trbasic(lang('currencytype'),'crid',makeoption($currencyarr,$crid),'select');
	trbasic(lang('operate_mode'),'dealmode',makeoption($dealmodearr,$dealmode),'select');
	echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
	trbasic(lang('crmode'),'mode',makeoption($modearr,$mode),'select');
	trbasic(lang('crrecordmname'),'mnames',$mnames);
	trbasic(lang('crrecordfrommname'),'frommnames',$frommnames);
	trbasic(lang('startdate'),'startdate',empty($startdate) ? '' : date('Y-n-j',$startdate),'calendar');
	trbasic(lang('enddate'),'enddate',empty($enddate) ? '' : date('Y-n-j',$enddate),'calendar');
	echo "</tbody>";
	tabfooter();
	echo "<input class=\"button\" type=\"submit\" name=\"bcradminlogs\" value=\"".lang('filter0')."\"></form>";
	
	$wheresql = '';
	if($crid != -1) $wheresql .= ($wheresql ? " AND " : "")."crid='$crid'";
	if(!empty($dealmode)) $wheresql .= ($wheresql ? " AND " : "")."dealmode='$dealmode'";
	if(!empty($mode)) $wheresql .= ($wheresql ? " AND " : "")."mode='$mode'";
	if(!empty($mnames)){
		$mnames = array_filter(explode(',',$mnames));
		$wheresql .= ($wheresql ? " AND " : "")."mname ".multi_str($mnames);
	}
	if(!empty($frommnames)){
		$frommnames = array_filter(explode(',',$frommnames));
		$wheresql .= ($wheresql ? " AND " : "")."frommname ".multi_str($frommnames);
	}
	if(!empty($startdate)) $wheresql .= ($wheresql ? " AND " : "")."createdate>'$startdate'";
	if(!empty($enddate)) $wheresql .= ($wheresql ? " AND " : "")."createdate<'$enddate'";
	$wheresql = $wheresql ? ("WHERE $wheresql") : "";
	$pagetmp = $page;
	do{
		$query = $db->query("SELECT * FROM {$tblprefix}cradminlogs $wheresql ORDER BY id DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
		$pagetmp--;
	} while(!$db->num_rows($query) && $pagetmp);
	$itemstr = '';
	$no = $pagetmp * $atpp;
	while($item = $db->fetch_array($query)){
		$no ++;
		$item['crname'] = empty($currencyarr[$item['crid']]) ? '-' : $currencyarr[$item['crid']];
		$item['mode'] = $item['mode'] == 'add' ? '+' : '-';
		$item['dealmode'] = $dealmodearr[$item['dealmode']];
		$item['createdate'] = date("$dateformat $timeformat", $item['createdate']);
		$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\">$no</td>\n".
			"<td class=\"txtC\">$item[mname]</td>\n".
			"<td class=\"txtC\">$item[frommname]</td>\n".
			"<td class=\"txtC w80\">$item[crname]</td>\n".
			"<td class=\"txtC w80\">$item[mode]</td>\n".
			"<td class=\"txtC w80\">$item[value]</td>\n".
			"<td class=\"txtC w80\">$item[dealmode]</td>\n".
			"<td class=\"txtC w120\">$item[createdate]</td></tr>\n";
	}
	$itemcount = $db->result_one("SELECT count(*) FROM {$tblprefix}cradminlogs $wheresql");
	$multi = multi($itemcount, $atpp, $page, "?entry=currencys&action=cradminlogs".$filterstr);

	tabheader(lang('cu_operate_record'),'','',8);
	trcategory(array(lang('sn'),lang('tomname'),lang('frommname'),lang('currencytype'),lang('addreduce'),lang('amount'),lang('operate_mode'),lang('operate_date')));
	echo $itemstr;
	tabfooter();
	echo $multi;

}

?>
