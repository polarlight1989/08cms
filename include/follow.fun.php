<?php
!defined('M_COM') && exit('No Permission');
include_once M_ROOT.'./include/archive.fun.php';
function follow_dynamic($aid=0,$mode='down',$temparr=array()){
	global $db,$tblprefix,$arc,$sptpls,$memberid,$sid,$timestamp,$cms_abs,$cache1circle,$currencys,$curuser,$templatedir,
	$btags,$mconfigs,$_mp,$_actid,$_midarr,$_a_vars,$_a_var,$mpnav,$mptitle;
	@extract($mconfigs,EXTR_SKIP);

	$arc->arcid($aid);
	if(empty($arc->aid)) message(lang('confchoosarchi'));
	!$arc->archive['checked'] && message(lang('poinarchnoch')); 
	switch_cache($arc->archive['sid']);
	$sid = $arc->archive['sid'];
	if_siteclosed($sid);
	if(!arc_allow($arc->archive,'down')) message(lang('noarchivbrowpermis'));

	if($crids = $arc->arc_crids(1)){//需要对当前用户扣值
		$cridstr = '';
		foreach($crids['total'] as $k => $v) $cridstr .= ($cridstr ? ',' : '').abs($v).$currencys[$k]['unit'].$currencys[$k]['cname'];
		$commu = read_cache('commu',8);
		if(empty($commu['setting']['autoatm'])){//不自动扣值的情况：提示出订阅链接，选择是否订阅
			message(lang('subattachwanpaycur').$cridstr."<br><br><a href=\"{$cms_abs}tools/subscribe.php?aid=$aid&isatm=1\">>>".lang('subscribe')."</a>");
		}else{//自动扣值,当前会员扣值及向出售者支付积分
			if(!$curuser->crids_enough($crids['total'])) message(lang('subattachwanpaycur').$cridstr.lang('younosuatwaencur'));
			$curuser->updatecrids($crids['total'],0,lang('subsattach'));
			$curuser->payrecord($arc->aid,1,$cridstr,1);
			if(!empty($crids['sale'])){
				$actuser = new cls_userinfo;
				$actuser->activeuser($arc->archive['mid']);
				foreach($crids['sale'] as $k => $v) $crids['sale'][$k] = -$v;
				$actuser->updatecrids($crids['sale'],1,lang('saleattach'));
				unset($actuser);
			}
		}
	}

	$arc->detail_data();
	$_da = &$arc->archive;
	arc_parse($_da);


	if(empty($temparr['tmode'])){
		if($temp = @unserialize($_da[$temparr['tname']])) $temp = @$temp[$temparr['fid']];
	}else $temp = @explode('#',$arc->archive[$temparr['tname']]);
	$_da['url'] = view_atmurl(@$temp['remote']);
	$_da['player'] = @$temp['player'];
	unset($temp);
	empty($_da['url']) && message(lang('noattach'));
	
	save_nums($aid,$mode);//统计下载或播放数
	if(!($tplname = $sptpls[$mode])) follow_notpl($mode,$_da['url'],$_da['player']);

	if($mode == 'down'){
		$auth = authcode($memberid."\t".$aid."\t".$temparr['tname']."\t".$temparr['tmode']."\t".$temparr['fid'],'ENCODE');
		$_da['trueurl'] = $cms_abs."tools/down.php?auth=$auth&aid=".$arc->aid."&tname=$temparr[tname]&tmode=$temparr[tmode]&fid=$temparr[fid]";//真实下载地址
	}elseif($cache1circle){
		$auth = authcode($temparr['tname']."\t".$temparr['tmode']."\t".$temparr['fid'],'ENCODE');
		$cachefile = htmlcac_dir('fw',date('Ym',$arc->archive['createdate']),1).cac_namepre($arc->aid,$arc->archive['createdate']).'_'.$auth.'.php';
		if(is_file($cachefile) && (filemtime($cachefile) > ($timestamp - $cache1circle * 60))){
			mexit(read_htmlcac($cachefile));
		}
	}
	_aenter($_da,1,array('url','player'));
	@extract($btags);
	extract($_da,EXTR_OVERWRITE);
	tpl_refresh($tplname);
	@include M_ROOT."template/$templatedir/pcache/$tplname.php";
	
	$_content = ob_get_contents();
	ob_clean();
	if($cache1circle && $mode != 'down') save_htmlcac($_content,$cachefile);
	mexit($_content);
}
function follow_notpl($mode,$url,$player){
	global $players,$player_width,$player_height;
	if($mode == 'down'){
		down_url($url);
	}else{
		$item = array('url' => $url);
		load_cache('players');
		$plid = empty($player) ? 0 : $player;
		if(!$plid){
			$ext = strtolower(mextension($url));
			foreach($players as $k => $player){
				if($player['available'] && ($player['ptype'] == $mode) && in_array($ext,array_filter(explode(',',$player['exts'])))){
					$plid = $k;
					break;
				}
			}
		}
		!$plid && message(lang('noplayer'));
		$player = read_cache('player',$plid);
		$item['width'] = empty($player_width) ? '100%' : $player_width;
		$item['height'] = empty($player_height) ? '100%' : $player_height;
		$_content = sqlstr_replace($player['template'],$item);
		mexit($_content);
	}
}
function down_url($url){
	if(islocal($url)){
		file_down(local_file($url));
	}else{
		header("location:$url");
	}
	mexit();
}
function save_nums($aid=0,$mode='down'){//统计文档的下载数或播放数
	global $sid;
	include_once M_ROOT.'./include/arcedit.cls.php';
	$aedit = new cls_arcedit();
	$aedit->set_aid($aid);
	$aedit->basic_data();
	$aedit->arc_nums($mode == 'down' ? 'downs' : 'plays',1,1);
	unset($aedit);
}
?>