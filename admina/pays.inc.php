<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('pay') || amessage('no_apermission');
load_cache('currencys');
$pmodearr = array('0' => lang('visitingpay'),'1' => lang('onlinepay'),'2' => lang('banktransfer'),'3' => lang('postoffremit'));
$pays = array(
	'alipay' => array(@$cfg_alipay, @$cfg_alipay_keyt, @$cfg_alipay_partnerid),
	'tenpay' => array(@$cfg_tenpay, @$cfg_tenpay_keyt)
);
$poids = array();
foreach(array('alipay' => 2, 'tenpay' => 3) as $k => $v)($cfg_paymode & (1 << $v)) && !in_array('', $pays[$k]) && $poids[$k] = lang($k);
if($action == 'paysedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? '' : $viewdetail;
	$pmode = isset($pmode) ? $pmode : '-1';
	$receive = isset($receive) ? $receive : '-1';
	$trans = isset($trans) ? $trans : '-1';
	$poid = empty($poid) ? '' : $poid;
	$mname = empty($mname) ? '' : $mname;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));

	$filterstr = '';
	foreach(array('viewdetail','pmode','trans','receive','poid','mname','indays','outdays') as $k){
		$filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	}

	$wheresql = '';
	if($pmode != '-1') $wheresql .= ($wheresql ? " AND " : "")."pmode='$pmode'";
	if($receive != '-1') $wheresql .= ($wheresql ? " AND " : "")."receivedate".($receive ? '>' : '=')."0";
	if($trans != '-1') $wheresql .= ($wheresql ? " AND " : "")."transdate".($trans ? '>' : '=')."0";
	if(!empty($poid)) $wheresql .= ($wheresql ? " AND " : "")."poid='$poid'";
	if(!empty($mname)) $wheresql .= ($wheresql ? " AND " : "")."mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($mname,'%_'))."%'";
	if(!empty($indays)) $wheresql .= ($wheresql ? " AND " : "")."senddate>'".($timestamp - 86400 * $indays)."'";
	if(!empty($outdays)) $wheresql .= ($wheresql ? " AND " : "")."senddate<'".($timestamp - 86400 * $outdays)."'";
	$wheresql = $wheresql ? "WHERE $wheresql" : '';

	if(!submitcheck('barcsedit')){
		$pmodearr = array('-1' => lang('nolimit')) + $pmodearr;
		$receivearr = array('-1' => lang('nolimit'),'0' => lang('noarrive'),'1' => lang('arrived'));
		$transarr = array('-1' => lang('nolimit'),'0' => lang('notrans'),'1' => lang('transed'));
		$poidsarr = array('' => lang('nolimit')) + $poids;
		tabheader(lang('filpayrec').viewcheck('viewdetail',$viewdetail,'tbodyfilter')."&nbsp; &nbsp; ".strbutton('bfilter','filter0'),'arcsedit',"?entry=pays&action=paysedit&page=$page");
		echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
		trbasic(lang('paymode'),'',makeradio('pmode',$pmodearr,$pmode),'');
		trbasic(lang('casweaarr'),'',makeradio('receive',$receivearr,$receive),'');
		trbasic(lang('currweattra'),'',makeradio('trans',$transarr,$trans),'');
		trbasic(lang('onlpayinter'),'',makeradio('poid',$poidsarr,$poid),'');
		trbasic(lang('paymember'),'mname',$mname,'text',lang('agsearchkey'));
		trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
		echo "</tbody>";
		tabfooter();
		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * FROM {$tblprefix}pays $wheresql ORDER BY pid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$stritem = '';
		while($item = $db->fetch_array($query)){
			$pid = $item['pid'];
			$pmodestr = $pmodearr[$item['pmode']];
			$poidstr = empty($item['poid']) ? '-' : $poids[$item['poid']];
			$sendstr = date("$dateformat",$item['senddate']);
			$receivestr = empty($item['receivedate']) ? '-' : date("$dateformat",$item['receivedate']);
			$transstr = empty($item['transdate']) ? '-' : date("$dateformat",$item['transdate']);
			$stritem .= "<tr class=\"txt\"><td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$pid]\" value=\"$pid\"></td>\n".
				"<td class=\"txtL\">$item[mname]</td>\n".
				"<td class=\"txtC w80\">$item[amount]</td>\n".
				"<td class=\"txtC w60\">$pmodestr</td>\n".
				"<td class=\"txtC w60\">$poidstr</td>\n".
				"<td class=\"txtC w80\">$sendstr</td>\n".
				"<td class=\"txtC w80\">$receivestr</td>\n".
				"<td class=\"txtC w80\">$transstr</td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=pays&action=paydetail&pid=$pid\">".lang('look')."</a></td></tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}pays $wheresql");
		$multi = multi($counts, $atpp, $page, "?entry=pays&action=paysedit$filterstr");

		tabheader(lang('payrecolist')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('paymember'),lang('payamount'),lang('paymode'),lang('payinter'),lang('recodate'),lang('arrivedate'),lang('savdate'),lang('detail')));
		echo $stritem;
		tabfooter();
		echo $multi;
		
		$receivearr = array('0' => lang('noarrive'),'1' => lang('arrived'));
		tabheader(lang('operate_item'));
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[delete]\" value=\"1\">&nbsp;".lang('delpayrec'),'',lang('onlynoartrarecdel'),'');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[receive]\" value=\"1\">&nbsp;".lang('setarrsta'),'arcreceive',makeradio('arcreceive',$receivearr,1),'');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[trans]\" value=\"1\">&nbsp;".lang('formemcasaccsav'),'',lang('payarrcansav'),'');
		tabfooter('barcsedit');
	}else{
		if(empty($arcdeal) && empty($dealstr)){
			amessage('selectoperateitem',"?entry=pays&action=paysedit&page=$page$filterstr");
		}
		if(empty($selectid) && empty($select_all)){
			amessage('selectpayrec',"?entry=pays&action=paysedit&page=$page$filterstr");
		}
		if(!empty($select_all)){
			if(empty($dealstr)){
				$dealstr = implode(',',array_keys(array_filter($arcdeal)));
			}else{
				$arcdeal = array();
				foreach(array_filter(explode(',',$dealstr)) as $k) $arcdeal[$k] = 1;
			}

			$parastr = "";
			foreach(array('arcreceive') as $k) $parastr .= "&$k=".$$k;
			
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}pays $wheresql");
				$pages = @ceil($counts / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "pid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT pid FROM {$tblprefix}pays $nwheresql ORDER BY pid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)){
					$selectid[] = $item['pid'];
				}
			}
		}
		if(!empty($arcdeal['delete'])){
			$db->query("DELETE FROM {$tblprefix}pays WHERE pid ".multi_str($selectid)." AND (receivedate=0 OR transdate>0)",'SILENT');
		}else{
			if(!empty($arcdeal['receive'])){
				$db->query("UPDATE {$tblprefix}pays SET receivedate='".(empty($arcreceive) ? 0 : $timestamp)."' WHERE pid ".multi_str($selectid)." AND transdate=0",'SILENT');
			}
			if(!empty($arcdeal['trans'])){
				$auser = new cls_userinfo;
				$query = $db->query("SELECT * FROM {$tblprefix}pays WHERE pid ".multi_str($selectid));
				while($item = $db->fetch_array($query)){
					if(!$item['amount'] || !$item['receivedate'] || $item['transdate']) continue;
					$auser->activeuser($item['mid']);
					$auser->updatecrids(array(0 => $item['amount']),1,lang('cashsav'));
					$db->query("UPDATE {$tblprefix}pays SET transdate='$timestamp' WHERE pid='$item[pid]'",'SILENT');
					$auser->init();
				}
				unset($actuser);
			}
		}
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
				amessage('operating',"?entry=pays&action=paysedit&page=$page$filterstr$transtr$parastr&dealstr=$dealstr",$pages,$npage,"<a href=\"?entry=pays&action=paysedit&page=$page$filterstr\">",'</a>');
			}
		}
		adminlog(lang('cashsavadmin'),lang('paysavlisadmoper'));
		amessage('casvadmopefin',"?entry=pays&action=paysedit&page=$page$filterstr");
	}
}
elseif($action == 'paydetail' && $pid){
	$forward = empty($forward) ? M_REFERER : $forward;
	empty($pid) && amessage('choosepay',$forward);
	if(!$item = $db->fetch_one("SELECT * FROM {$tblprefix}pays WHERE pid=$pid")) amessage('choosepayrec',$forward);
	include_once M_ROOT."./include/fields.cls.php";
	if(!submitcheck('bpaydetail')){
		if(!$item['transdate']){
			tabheader(lang('paymessamod'),'paydetail','?entry=pays&action=paydetail&pid='.$pid.'&forward='.rawurlencode($forward),2,1);
		}else{
			tabheader(lang('paymesslook'));
		}
		trbasic(lang('member_cname'),'',$item['mname'],'');
		trbasic(lang('paymode'),'',$pmodearr[$item['pmode']],'');
		trbasic(lang('payamourmbi'),'itemnew[amount]',$item['amount']);
		trbasic(lang('handrmbi'),'',$item['handfee'],'');
		trbasic(lang('payinter'),'',$item['poid'] ? $poids[$item['poid']] : '-','');
		trbasic(lang('payorderidsn'),'',$item['ordersn'] ? $item['ordersn'] : '-','');
		trbasic(lang('messsentim'),'',date("$dateformat $timeformat",$item['senddate']),'');
		trbasic(lang('casarrtim'),'',$item['receivedate'] ? date("$dateformat $timeformat",$item['receivedate']) : '-','');
		trbasic(lang('currsavtime'),'',$item['transdate'] ? date("$dateformat $timeformat",$item['transdate']) : '-','');
		trbasic(lang('contaname'),'itemnew[truename]',$item['truename']);
		trbasic(lang('contatelep'),'itemnew[telephone]',$item['telephone']);
		trbasic(lang('contactemail'),'itemnew[email]',$item['email']);
		trbasic(lang('remark'),'itemnew[remark]',br2nl($item['remark']),'textarea');
		trspecial(lang('paywarrant')."&nbsp; &nbsp; ["."<a href=\"".$item['warrant']."\" target=\"_blank\">".lang('bigimage')."</a>"."]",'itemnew[warrant]',$item['warrant'],'image');
		if($item['transdate']){
			tabfooter();
			echo "<input class=\"button\" type=\"submit\" name=\"\" value=\"".lang('goback')."\" onclick=\"history.go(-1);\">";
		}else{
			tabfooter('bpaydetail',lang('modify'));
		}
		a_guide('paydetail');
	}else{
		include_once M_ROOT."./include/upload.cls.php";
		$itemnew['amount'] = max(0,round(floatval($itemnew['amount']),2));
		empty($itemnew['amount']) && amessage('inppayamo',M_REFERER);
		$itemnew['truename'] = trim(strip_tags($itemnew['truename']));
		$itemnew['telephone'] = trim(strip_tags($itemnew['telephone']));
		$itemnew['email'] = trim(strip_tags($itemnew['email']));
		$itemnew['remark'] = mnl2br(mhtmlspecialchars($itemnew['remark']));
		$c_upload = new cls_upload;	
		$itemnew['warrant'] = upload_s($itemnew['warrant'],$item['warrant'],'image');
		$c_upload->closure(1, $pid, 'pays');
		$c_upload->saveuptotal(1);
		unset($c_upload);
		$db->query("UPDATE {$tblprefix}pays SET
					 amount='$itemnew[amount]',
					 truename='$itemnew[truename]',
					 telephone='$itemnew[telephone]',
					 email='$itemnew[email]',
					 remark='$itemnew[remark]',
					 warrant='$itemnew[warrant]' 
					 WHERE pid='$pid'
					 ");
		amessage('paymesmodfin',$forward);
	}
}
?>
