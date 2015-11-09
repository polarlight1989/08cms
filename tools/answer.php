<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/archive.fun.php';
include_once M_ROOT.'./include/archive.cls.php';
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);

if(empty($action)){
	$aid = empty($aid) ? 0 : max(0,intval($aid));
	!$aid && message('choosearchive');
	$cuid = $db->result_one("SELECT c.cuid FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}channels c ON c.chid=a.chid WHERE a.aid='$aid'");
	if(!$cuid || !($commu = read_cache('commu',$cuid))) message('setcomitem');
	if(empty($commu['ucadd'])){
		if(!submitcheck('newcommu')){
			$arc = new cls_archive();
			$arc->arcid($aid);
			!$arc->aid && message('choosearchive');
			!$curuser->pmbypmids('cuadd',$commu['setting']['apmid']) && message('younoitempermis');
			($arc->archive['closed'] || $arc->archive['finishdate'] < $timestamp) && message('questionclosed');
	
			switch_cache($arc->archive['sid']);
			$sid = $arc->archive['sid'];
			if_siteclosed($sid);
			cache_merge($commu,'commu',$sid);
			if(!($tplname = @$commu['addtpl'])){
				load_cache('mlangs');
				include_once M_ROOT."./include/admin.fun.php";
				include_once M_ROOT."./include/adminm.fun.php";
				include_once M_ROOT."./include/cheader.inc.php";
				_header();
				tabheader(lang('add').$commu['cname'].'&nbsp; &nbsp; '."<a href=\"".view_arcurl($arc->archive)."\" target=\"_blank\">>>&nbsp; ".$arc->archive['subject']."</a>",'answeradd',"?aid=$aid$forwardstr",2,1,1);
				$submitstr = '';
				trbasic(lang('answer0'),'communew[answer]','','textarea');
				$submitstr .= makesubmitstr('communew[answer]',1,0,$commu['setting']['minlength'],$commu['setting']['maxlength'],'multitext');
				$submitstr .= tr_regcode('answer');
				tabfooter('newcommu');
				check_submit_func($submitstr);
			}else{
				$_da = &$arc->archive;
				arc_parse($_da);
				
				_aenter($_da,1);
				@extract($btags);
				extract($_da,EXTR_OVERWRITE);
				tpl_refresh($tplname);
				@include M_ROOT."template/$templatedir/pcache/$tplname.php";
				
				$_content = ob_get_contents();
				ob_clean();
				mexit($_content);
			}
		}else{
			include_once M_ROOT.'./include/arcedit.cls.php';
			include_once M_ROOT."./include/cheader.inc.php";
			$inajax ? aheader() : _header();
			!$memberid && mcmessage('nousernooperatepermis',axaction(2,M_REFERER));
			if(!regcode_pass('answer',empty($regcode) ? '' : trim($regcode))) mcmessage('regcodeerror',axaction(2,M_REFERER));
			!$curuser->checkforbid('answer') && mcmessage('userisforbid',axaction(2,M_REFERER));//屏蔽组
			$aedit = new cls_arcedit();
			$aedit->set_aid($aid);
			$aedit->basic_data();
			!$aedit->aid && mcmessage('choosearchive');
			!($commu = read_cache('commu',$aedit->channel['cuid'])) && mcmessage('setcomitem',axaction(2,M_REFERER));
			!$curuser->pmbypmids('cuadd',$commu['setting']['apmid']) && mcmessage('younoitempermis',axaction(2,M_REFERER));
			($aedit->archive['closed'] || $aedit->archive['finishdate'] < $timestamp) && mcmessage('questionclosed',axaction(2,M_REFERER));
			
			$communew['answer'] = empty($communew['answer']) ? '' : trim($communew['answer']);
			empty($communew['answer']) && mcmessage('inputanswercontent',axaction(2,M_REFERER));
			(!empty($commu['setting']['minlength']) && strlen($communew['answer']) < $commu['setting']['minlength']) && mcmessage('answeroverminlength');
			!empty($commu['setting']['maxlength']) && $communew['answer'] = cutstr($communew['answer'],$commu['setting']['maxlength']);
			$db->query("INSERT INTO {$tblprefix}answers SET
						 aid='$aid', 
						 answer='$communew[answer]', 
						 crid='".$aedit->archive['crid']."', 
						 cuid='".$commu['cuid']."', 
						 mid='".$curuser->info['mid']."', 
						 mname='".$curuser->info['mname']."', 
						 createdate='$timestamp'
						 ");
			if($cid = $db->insert_id()){
				$aedit->arc_nums('answers',1,1);
				$curuser->basedeal('answer',1,1,1);
			}
			mcmessage('answeraddfinish',axaction(10,$forward));
		}
	}else include(M_ROOT.$commu['ucadd']);
}elseif($action == 'vote'){
	$inajax = empty($inajax) ? 0 : 1;
	$cid = empty($cid) ? 0 : max(0,intval($cid));
	if(!$cid) cumessage('choosevoteobject');
	if(!$row = $db->fetch_one("SELECT * FROM {$tblprefix}answers WHERE cid='$cid'")) cumessage('choosevoteobject',$forward);
	if(!($commu = read_cache('commu',$row['cuid']))) cumessage('setcomitem',$forward);
	if(empty($commu['ucvote'])){
		if(!empty($commu['setting']['nouservote']) && !$memberid) cumessage('loginmember',$forward);
		if(empty($commu['setting']['repeatvote'])){
			if(empty($m_cookie['08cms_cuid_'.$commu['cuid'].'_vote_'.$aid.'_'.$cid])){
				msetcookie('08cms_cuid_'.$commu['cuid'].'_vote_'.$aid.'_'.$cid,'1',365 * 24 * 3600);
			}else cumessage('dontnrepeatvote',$forward);
		}
		$option = empty($option) ? 1 : min(5,max(1,intval($option)));
		$db->query("UPDATE {$tblprefix}answers SET votes$option = votes$option + 1 WHERE cid='$cid'",'SILENT');
		cumessage($inajax ? 'succeed' : 'votesucceed',$forward);
	}else include(M_ROOT.$commu['ucvote']);
}
?>
