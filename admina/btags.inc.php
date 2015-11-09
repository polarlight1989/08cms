<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
load_cache('btagnames,channels,fchannels,mchannels');
aheader();
backallow('tpl') || amessage('no_apermission');
$bclasses = array(
	'common' => lang('common_message'),
	'archive' => lang('archive_related'),
	'cnode' => lang('catas_related'),
	'freeinfo' => lang('freeinfo_related'),
	'commu' => lang('commu_message'),
	'member' => lang('member_related'),
	'mcommu' => lang('membercommu'),
	'marchive' => lang('marchive'),
	'other' => lang('other'),
	);
$datatypearr = array(
	'text' => lang('text'),
	'multitext' => lang('multitext'),
	'htmltext' => lang('htmltext'),
	'image' => lang('image_f'),
	'images' => lang('images'),
	'flash' => lang('flash'),
	'flashs' => lang('flashs'),
	'media' => lang('media'),
	'medias' => lang('medias'),
	'file' => lang('file_f'),
	'files' => lang('files_f'),
	'select' => lang('select'),
	'mselect' => lang('mselect'),
	'cacc' => lang('cacc'),
	'date' => lang('date_f'),
	'int' => lang('int'),
	'float' => lang('float'),
	'map' => lang('map'),
	'vote' => lang('vote'),
);
$url_type = 'btags';include 'urlsarr.inc.php';
if(empty($action)){
	url_nav(lang('originallogo'),$urlsarr,'btag');

	$bclass = empty($bclass) ? 'common' : $bclass;
	$arr = array();
	foreach($bclasses as $k => $v) $arr[] = $bclass == $k ? "<b>-$v-</b>" : "<a href=\"?entry=btags&bclass=$k\">$v</a>";
	echo tab_list($arr,9,0);
	
	$sclasses = array();
	if($bclass == 'archive'){
		foreach($channels as $chid => $channel){
			$sclasses[$chid] = $channel['cname'];
		}
	}elseif($bclass == 'cnode'){
		$sclasses = array(
			'catalog' => lang('catalog'),
			'coclass' => lang('coclass'),
		);
	}elseif($bclass == 'freeinfo'){
		foreach($fchannels as $chid => $channel){
			$sclasses[$chid] = $channel['cname'];
		}
	}elseif($bclass == 'member'){
		foreach($mchannels as $chid => $channel){
			$sclasses[$chid] = $channel['cname'];
		}
	}elseif($bclass == 'commu'){
		$sclasses = array(
			'comment' => lang('comment'),
			'purchase' => lang('purchase'),
			'answer' => lang('answer'),
			'reply' => lang('reply'),
			'offer' => lang('offer'),
		);
	}elseif($bclass == 'mcommu'){
		$sclasses = array(
			'comment' => lang('comment'),
			'reply' => lang('reply'),
			'flink' => lang('flink'),
		);
	}elseif($bclass == 'other'){
		$sclasses = array(
			'mp' => lang('pt'),
			'attachment' => lang('attachment'),
			'vote' => lang('vote'),
		);
	}
	if(!in_array($bclass,array('commu','mcommu','other'))){
		tabheader($bclasses[$bclass].lang('initag_common'),'','',5);
		trcategory(array(array('&nbsp; &nbsp; '.lang('tagname'),'txtL'),array(lang('use_style').'1','txtL'),array(lang('use_style').'2','txtL'),array(lang('use_style').'3','txtL'),lang('field_type')));
		foreach($btagnames as $btagname){
			if(($btagname['bclass'] == $bclass) && !$btagname['sclass']){
				echo "<tr class=\"txt\"><td class=\"txtL w30B\">&nbsp; &nbsp; $btagname[cname]</td>\n".
				"<td class=\"txtL\">{<b>$btagname[ename]</b>}</td>\n".
				"<td class=\"txtL\">{\$<b>$btagname[ename]</b>}</td>\n".
				"<td class=\"txtL\">{\$<b>v[$btagname[ename]]</b>}</td>\n".
				"<td class=\"txtC w20B\">".$datatypearr[$btagname['datatype']]."</td></tr>\n";
			}
		}
		tabfooter();
	}
	
	foreach($sclasses as $k => $v){
		tabheader($v.lang('related_tag'),'','',5);
		if(in_array($bclass,array('commu','mcommu','other'))) trcategory(array(array('&nbsp; &nbsp; '.lang('tagname'),'txtL'),array(lang('use_style').'1','txtL'),array(lang('use_style').'2','txtL'),array(lang('use_style').'3','txtL'),lang('field_type')));
		foreach($btagnames as $btagname){
			if(($btagname['bclass'] == $bclass) && ($btagname['sclass'] == $k)){
				echo "<tr class=\"txt\"><td class=\"txtL w30B\">&nbsp; &nbsp; $btagname[cname]</td>\n".
				"<td class=\"txtL\">{<b>$btagname[ename]</b>}</td>\n".
				"<td class=\"txtL\">{\$<b>$btagname[ename]</b>}</td>\n".
				"<td class=\"txtL\">{\$<b>v[$btagname[ename]]</b>}</td>\n".
				"<td class=\"txtC w20B\">".$datatypearr[$btagname['datatype']]."</td></tr>\n";
			}
		}
		tabfooter();
	}
	a_guide('btagslist');
}elseif($action == 'update'){
	url_nav(lang('originallogo'),$urlsarr,'update');
	if(!isset($confirm) || $confirm != 'ok') {
		$message = lang('btag_update')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=btags&action=update&confirm=ok$param_suffix><b>".lang('update')."</b></a><br>";
		$message .= lang('giveupclick').">><a href=?entry=btags$param_suffix>".lang('goback')."</a>";
		amessage($message);
	}
	deal_btagnames();
	amessage(lang('btagupdatefin'),"?entry=btags$param_suffix");
}
function deal_btagnames(){
	global $btagnames;
	$commus = reload_cache('commus');
	sys_cache('btagnames');
	$commoned = 0;
	$channels = reload_cache('channels');
	foreach($channels as $chid => $channel){
		$fields = reload_cache('fields',$chid);
		foreach($fields as $k => $field){
			if($field['mcommon']){
				!$commoned && $btagnames[] = array('ename' => $k,'cname' => $field['cname'],'bclass' => 'archive','sclass' => '','datatype' => $field['datatype'],);
			}else{
				$btagnames[] = array('ename' => $k,'cname' => $field['cname'],'bclass' => 'archive','sclass' => $chid,'datatype' => $field['datatype'],);
			}
		}
		if(@$commus[$channel['cuid']]['cclass'] == 'answer'){
			$btagnames[] = array('ename' => 'appeals','cname' => lang('appealamount'),'bclass' => 'archive','sclass' => $chid,'datatype' => 'int',);
			$btagnames[] = array('ename' => 'appealdate','cname' => lang('appeendtime'),'bclass' => 'archive','sclass' => $chid,'datatype' => 'date',);
		}
		$commoned = 1;
	}
	unset($channels,$fields);
	$commoned = 0;
	$fchannels = reload_cache('fchannels');
	foreach($fchannels as $chid => $fchannel){
		$fields = reload_cache('ffields',$chid);
		foreach($fields as $k => $field){
			if($k == 'subject'){
				!$commoned && $btagnames[] = array('ename' => $k,'cname' => $field['cname'],'bclass' => 'freeinfo','sclass' => '','datatype' => $field['datatype'],);
			}else{
				$btagnames[] = array('ename' => $k,'cname' => $field['cname'],'bclass' => 'freeinfo','sclass' => $chid,'datatype' => $field['datatype'],);
			}
		}
		$commoned = 1;
	}
	unset($fchannels,$fields);
	$cotypes = reload_cache('cotypes');
	foreach($cotypes as $coid =>$cotype){
		$btagnames[] = array('ename' => 'ccid'.$coid.'title','cname' => $cotype['cname'].lang('cocname'),'bclass' => 'archive','sclass' => '','datatype' => 'text',);
		$btagnames[] = array('ename' => "ccid$coid",'cname' => $cotype['cname'].lang('coclass').'id','bclass' => 'archive','sclass' => '','datatype' => 'int',);
	}
	unset($cotypes,$cotype);
	$cafields = reload_cache('cafields');
	foreach($cafields as $field){
		$btagnames[] = array('ename' => $field['ename'],'cname' => $field['cname'],'bclass' => 'cnode','sclass' => 'catalog','datatype' => $field['datatype'],);
	}
	unset($cafields,$field);

	$ccfields = reload_cache('ccfields');
	foreach($ccfields as $field){
		$btagnames[] = array('ename' => $field['ename'],'cname' => $field['cname'],'bclass' => 'cnode','sclass' => 'coclass','datatype' => $field['datatype'],);
	}
	unset($ccfields,$field);

	$commoned = 0;
	$mchannels = reload_cache('mchannels');
	foreach($mchannels as $chid => $mchannel){
		$mfields = reload_cache('mfields',$chid);
		foreach($mfields as $k => $field){
			if($field['mcommon']){
				(!$commoned && !in_array($k,array('password'))) && $btagnames[] = array('ename' => $k,'cname' => $field['cname'],'bclass' => 'member','sclass' => '','datatype' => $field['datatype'],);
			}else{
				$btagnames[] = array('ename' => $k,'cname' => $field['cname'],'bclass' => 'member','sclass' => $chid,'datatype' => $field['datatype'],);
			}
		}
		$commoned = 1;
	}
	unset($mchannels,$mfields,$field);

	$currencys = reload_cache('currencys');
	foreach($currencys as $crid => $currency){
		$btagnames[] = array('ename' => 'currency'.$crid,'cname' => $currency['cname'].lang('amount'),'bclass' => 'member','sclass' => '','datatype' => 'int',);
	}
	unset($currencys,$currency);
	$grouptypes = reload_cache('grouptypes');
	foreach($grouptypes as $gtid =>$grouptype){
		$btagnames[] = array('ename' => 'grouptype'.$gtid,'cname' => $grouptype['cname'].lang('usergroup').'id','bclass' => 'member','sclass' => '','datatype' => 'int',);
		$btagnames[] = array('ename' => 'grouptype'.$gtid.'name','cname' => $grouptype['cname'].lang('usergroup'),'bclass' => 'member','sclass' => '','datatype' => 'text',);
	}
	unset($grouptypes,$grouptype);
	$bnames = array();
	foreach($btagnames as $k => $v){
		if(!array_key_exists($v['ename'],$bnames)){
			$bnames[$v['ename']] = $v['cname'];
		}elseif(!in_array($v['cname'],array_filter(explode(' | ',$bnames[$v['ename']])))){
			$bnames[$v['ename']] .= ' | '.$v['cname'];
		}
	}
	cache2file($btagnames,'btagnames');
	cache2file($bnames,'bnames');
	unset($btagnames,$bnames);
}

?>
