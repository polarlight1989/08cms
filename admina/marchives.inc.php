<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('marchive') || amessage('no_apermission');
load_cache('matypes');
include_once M_ROOT."./include/marcedit.cls.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/upload.cls.php";
if(!$matypes) amessage('nomatype');
if($action == 'marchivesedit'){
	$matid = empty($matid) ? 0 : $matid;
	if(!$matid || empty($matypes)){
		$matids = array_keys($matypes);
		$matid = $matids[0];
	}
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : $viewdetail;
	$checked = isset($checked) ? $checked : '-1';
	$mname = empty($mname) ? '' : $mname;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));

	$wheresql = '';
	$fromsql = "FROM {$tblprefix}marchives_$matid";
	$checked != '-1' && $wheresql .= ($wheresql ? ' AND ' : '')."checked='$checked'";
	$mname && $wheresql .= ($wheresql ? ' AND ' : '')."mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($mname,'%_'))."%'";
	$indays && $wheresql .= ($wheresql ? ' AND ' : '')."createdate>'".($timestamp - 86400 * $indays)."'";
	$outdays && $wheresql .= ($wheresql ? ' AND ' : '')."createdate<'".($timestamp - 86400 * $outdays)."'";
	
	$filterstr = '';
	foreach(array('viewdetail','matid','checked','mname','indays','outdays') as $k){
		$filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	}
	$wheresql = $wheresql ? "WHERE ".$wheresql : '';
	if(!submitcheck('barcsedit')){
		$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheck'),'1' => lang('checked'));

		$matidsarr = array();
		foreach($matypes as $k => $v) $matidsarr[] = $matid == $k ? "<b>$v[cname]</b>" : "<a href=\"?entry=marchives&action=marchivesedit$param_suffix&matid=$k\">$v[cname]</a>";
		echo tab_list($matidsarr,6);

		tabheader(lang('filter0').$matypes[$matid]['cname'].viewcheck('viewdetail',$viewdetail,$actionid.'tbodyfilter').'&nbsp; &nbsp; '.strbutton('bfilter','filter0'),$actionid.'arcsedit',"?entry=marchives&action=marchivesedit&page=$page$param_suffix");
		echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
		trhidden('matid',$matid);
		trbasic(lang('check_state'),'',makeradio('checked',$checkedarr,$checked),'');
		trbasic(lang('search_member'),'mname',$mname,'text',lang('agsearchkey'));
		trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
		echo "</tbody>";
		tabfooter();

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * $fromsql $wheresql ORDER BY maid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($row = $db->fetch_array($query)){
			$maid = $row['maid'];
			view_marcurl($row);
			$editstr = "<a href=\"?entry=marchives&action=marchivedetail&matid=$k&maid=$row[maid]&matid=$matid\">".lang('edit').'</a>';
			$checkstr = empty($row['checked']) ? '-' : 'Y';
			$lookstr = "<a href=\"$row[arcurl]\" target=\"_blank\">".lang('look').'</a>';
			$itemstr .= "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$maid]\" value=\"$maid\"></td>\n".
				"<td class=\"txtL\">".$matypes[$row['matid']]['cname']."</td>\n".
				"<td class=\"txtC\">$row[mid]</td>\n".
				"<td class=\"txtC\">$row[mname]</td>\n".
				"<td class=\"txtC\">$lookstr</td>\n".
				"<td class=\"txtC\">$checkstr</td>\n".
				"<td class=\"txtC\">$editstr</td>\n".
				"</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts, $atpp, $page, "?entry=marchives&action=marchivesedit$param_suffix$filterstr");
		tabheader(lang('marchiveslist')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('matype'),lang('memberid'),lang('member_cname'),lang('look'),lang('check'),lang('edit')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		
		$checkedarr = array('0' => lang('uncheck'),'1' => lang('check'));
		tabheader(lang('operate_item'));
		$itemstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[delete]\" value=\"1\">&nbsp;".lang('delete_archive').'&nbsp; &nbsp; &nbsp; '.
		"<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[readd]\" value=\"1\">&nbsp;".lang('archive_readd')."&nbsp; &nbsp; &nbsp; ";
		trbasic(lang('choose_item'),'',$itemstr,'');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[checked]\" value=\"1\">&nbsp;".lang('check_archive'),'arcchecked',makeradio('arcchecked',$checkedarr,1),'');
		tabfooter('barcsedit');
		a_guide('marchivesedit');
	}else{
		if(empty($arcdeal) && empty($dealstr)) amessage('selectoperateitem',axaction(1,M_REFERER));
		if(empty($selectid) && empty($select_all)) amessage('selectarchive',axaction(1,M_REFERER));
		if(!empty($select_all)){
			if(empty($dealstr)){
				$dealstr = implode(',',array_keys(array_filter($arcdeal)));
			}else{
				$arcdeal = array();
				foreach(array_filter(explode(',',$dealstr)) as $k){
					$arcdeal[$k] = 1;
				}
			}

			$parastr = "";
			foreach(array('arcchecked',) as $k){
				$parastr .= "&$k=".$$k;
			}
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$pages = @ceil($counts / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "maid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT maid $fromsql $nwheresql ORDER BY maid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)){
					$selectid[] = $item['maid'];
				}
			}
			if(empty($selectid)) amessage('selectarchive',axaction(1,M_REFERER));
		}
		$aedit = new cls_marcedit;
		foreach($selectid as $maid){
			$aedit->set_id($maid,$matid);
			if(!empty($arcdeal['delete'])){
				$aedit->delete();
				continue;
			}
			if(!empty($arcdeal['checked'])){
				$aedit->check($arcchecked);
			}
			$aedit->updatedb();
			$aedit->init();
		}
		unset($aedit);
		if(!empty($select_all)){
			$npage ++;
			if($npage <= $pages){
				$fromid = min($selectid);
				$transtr = '';
				$transtr .= "&select_all=1";
				$transtr .= "&pages=$pages";
				$transtr .= "&npage=$npage";
				$transtr .= "&barcsedit=1";
				$transtr .= "&fromid=$fromid";
				amessage('operating',"?entry=marchives&action=marchivesedit&page=$page$param_suffix$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=marchives&action=marchivesedit&page=$page$param_suffix$filterstr\">",'</a>');
			}
		}
		adminlog(lang('marchivesedit'));
		amessage('marcfinish',"?entry=marchives&action=marchivesedit$param_suffix&page=$page$filterstr");
	}

}elseif($action == 'marchivedetail'){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);

	$maid = empty($maid) ? 0 : max(0,intval($maid));
	$matid = empty($matid) ? 0 : max(0,intval($matid));
	$aedit = new cls_marcedit;
	$aedit->set_id($maid,$matid,0);
	if(!$aedit->maid) amessage('choosemarchive');
	
	$fields = read_cache('mafields',$matid);
	if(!submitcheck('bmarchive')){
		$a_field = new cls_field;
		$submitstr = '';
		tabheader($aedit->matype['cname'].'&nbsp; -&nbsp; '.lang('contentsetting'),'marchive',"?entry=marchives&action=marchivedetail&matid=$matid&maid=$maid$param_suffix$forwardstr",2,1,1,1);
		foreach($fields as $k => $field){
			if($field['available'] && !$field['isfunc']){
				$a_field->init();
				$a_field->field = $field;
				$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
				$a_field->trfield('marchivenew','','ma',$matid);
				$submitstr .= $a_field->submitstr;
			}
		}
		unset($a_field);
	
		tabfooter('bmarchive');
		check_submit_func($submitstr);
	}else{
		$c_upload = new cls_upload;	
		$fields = fields_order($fields);
		$a_field = new cls_field;
		foreach($fields as $k => $v){
			if($v['available'] && !$v['isfunc']){
				$a_field->init();
				$a_field->field = $v;
				$a_field->oldvalue = isset($aedit->archive[$k]) ? $aedit->archive[$k] : '';
				$a_field->deal('marchivenew');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					amessage($a_field->error,M_REFERER);
				}
				$aedit->updatefield($k,$a_field->newvalue);
				if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $aedit->updatefield($k.'_'.$x,$y);
			}
		}
		unset($a_field);
	
		$aedit->updatedb();
		$c_upload->closure(1, $maid, 'marchives');
		$c_upload->saveuptotal(1);
		amessage('marceditfinish',$forward);
	}

}
?>
