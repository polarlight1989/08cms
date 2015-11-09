<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('static') || amessage('no_apermission');
load_cache('freeinfos');
load_cache('mtpls',$sid);
include_once M_ROOT."./include/parse.fun.php";
include_once M_ROOT."./include/fcn_static.fun.php";
if($action == 'freeinfosedit'){
	if(!submitcheck('bfreeinfoadd') && !submitcheck('bfreeinfosedit')){
		$url_type = 'static';include 'urlsarr.inc.php';
		url_nav(lang('staticadmin'),$urlsarr,'freeinfos');

		tabheader(lang('isolute_page_manager'),'freeinfosedit',"?entry=freeinfos&action=freeinfosedit$param_suffix",'5');
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),'ID',lang('isolute_page_cname'),lang('page_template'),lang('page_pick_url'),lang('static')));
		foreach($freeinfos as $fid => $freeinfo){
			if($freeinfo['sid'] == $sid){
				if(empty($freeinfo['arcurl'])){
					$arcurl = $cms_abs."info.php?fid=$fid";
					$staticstr = "<a href=\"?entry=freeinfos&action=fstatic&fid=$fid$param_suffix\">".lang('create')."</a>";
				}else{
					$arcurl = ($sid ? view_url($subsites[$sid]['dirname'].'/') : view_url($infohtmldir.'/')).$freeinfo['arcurl'];
					$staticstr = "<a href=\"?entry=freeinfos&action=funstatic&fid=$fid$param_suffix\">".lang('cancel')."</a>";
				}
				$staticstr = empty($freeinfo['arcurl']) ? "<a href=\"?entry=freeinfos&action=fstatic&fid=$fid$param_suffix\">".lang('create')."</a>" : "<a href=\"?entry=freeinfos&action=funstatic&fid=$fid$param_suffix\">".lang('cancel')."</a>";
				echo "<tr class=\"txt\">".
					"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fid]\" value=\"$fid\"></td>\n".
					"<td class=\"txtC w40\">$fid</td>\n".
					"<td class=\"txtC w150\"><input type=\"text\" size=\"25\" name=\"freeinfosnew[$fid][cname]\" value=\"$freeinfo[cname]\"></td>\n".
					"<td class=\"txtC w120\"><select style=\"vertical-align: middle;\" name=\"freeinfosnew[$fid][tplname]\">".makeoption(mtplsarr('other'),$freeinfo['tplname'],lang('noset'))."</select></td>\n".
					"<td class=\"txtL\"><a href=\"$arcurl\" target=\"_blank\">$arcurl</a></td>\n".
					"<td class=\"txtC w40\">$staticstr</td></tr>\n";
			}
		}
		tabfooter('bfreeinfosedit');
		tabheader(lang('add_isolute_page'),'freeinfoadd',"?entry=freeinfos&action=freeinfosedit$param_suffix");
		trbasic(lang('isolute_page_cname'),'freeinfoadd[cname]');
		trbasic(lang('isolute_page_template'),'freeinfoadd[tplname]',makeoption(array('' => lang('noset')) + mtplsarr('other')),'select');
		tabfooter('bfreeinfoadd',lang('add'));
		a_guide('freeinfosedit');
	}elseif(submitcheck('bfreeinfoadd')){
		$freeinfoadd['cname'] = trim($freeinfoadd['cname']);
		if(empty($freeinfoadd['cname'])) amessage('datamissing', "?entry=freeinfos&action=freeinfosedit$param_suffix");
		$db->query("INSERT INTO {$tblprefix}freeinfos SET 
					sid='$sid', 
					cname='$freeinfoadd[cname]', 
					tplname='$freeinfoadd[tplname]'
					");
		updatecache('freeinfos');
		amessage('pageaddfin',"?entry=freeinfos&action=freeinfosedit$param_suffix");
	}elseif(submitcheck('bfreeinfosedit')){
		if(!empty($delete)){
			foreach($delete as $fid){//需要删除相应的静态文件
				$db->query("DELETE FROM {$tblprefix}freeinfos WHERE fid='$fid'");
				unset($freeinfos[$fid]);
			}
		}
		foreach($freeinfos as $fid => $freeinfo){
			if($freeinfo['sid'] == $sid){
				$freeinfo['cname'] = empty($freeinfosnew[$fid]['cname']) ? $freeinfo['cname'] : $freeinfosnew[$fid]['cname'];
				$freeinfo['tplname'] = $freeinfosnew[$fid]['tplname'];
				$db->query("UPDATE {$tblprefix}freeinfos SET 
							cname='$freeinfo[cname]',
							tplname='$freeinfo[tplname]' 
							WHERE fid='$fid'");
			}
		}
		updatecache('freeinfos');
		amessage('pagemodfin',"?entry=freeinfos&action=freeinfosedit$param_suffix");
	
	}
}elseif($action == 'fstatic' && $fid){
	if(!$fid || empty($freeinfos[$fid])) amessage('chooseisopa',"?entry=freeinfos&action=freeinfosedit$param_suffix");
	fcn_static($fid);
	updatecache('freeinfos');
	amessage('pagestafin', "?entry=freeinfos&action=freeinfosedit$param_suffix");
}elseif($action == 'funstatic' && $fid){
	if(!$fid || empty($freeinfos[$fid])) amessage('chooseisopa',"?entry=freeinfos&action=freeinfosedit$param_suffix");
	fcn_unstatic($fid);
	updatecache('freeinfos');
	amessage('pastacanfin',"?entry=freeinfos&action=freeinfosedit$param_suffix");
}
?>