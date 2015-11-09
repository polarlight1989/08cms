<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'include/cheader.inc.php';
_header(lang('addpre'));
echo '<div style="width:680px; height:530px; overflow:hidden; padding:3px; margin:0 auto;">';

//分为两种，一种是普通添加，一种是合辑内的添加
$aid = empty($aid) ? 0 : max(0,intval($aid));
if(!$aid){
	load_cache('cotypes,channels,acatalogs');
	
	$nmuid = empty($nmuid) ? 0 : max(0,intval($nmuid));
	$u_coids = array();
	$u_chids = $u_nochids = '';
	if($nmuid && $u_url = read_cache('murl',$nmuid)){
		$u_mtitle = @$u_url['mtitle'];
		$u_guide = @$u_url['guide'];
		foreach(array('coids',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = explode(',',$u_url['setting'][$var]);
		foreach(array('chids','nochids',) as $var) if(!empty($u_url['setting'][$var])) ${'u_'.$var} = '&'.$var.'='.$u_url['setting'][$var];
	}
	$caid = empty($caid) ? 0 : max(0,intval($caid));
	foreach($cotypes as $k => $v) if(!$v['self_reg']) ${'ccid'.$k} = empty(${'ccid'.$k}) ? '' : trim(${'ccid'.$k});

	$nsid = isset($nsid) ? max(-1,intval($nsid)) : -1;
	if($caid) $nsid = $acatalogs[$caid]['sid'];
	if($nsid == -1) $catalogs = &$acatalogs;
	else load_cache('catalogs',$nsid);

	tabheader(empty($u_mtitle) ? lang('cata_choose') : $u_mtitle,'ajax_arc','',2,'',1);
	
	
	if($caid){
		trbasic('*'.lang('be_catalog'),'',@$catalogs[$caid]['title'],'');
		trhidden('caid',$caid);
	}else{
		tr_cns(lang('be_catalog'),'caid',0,$nsid,0,0,lang('p_choose'));
	}
	$js_frm = "[$ca_vmode,1,'caid','" . str_replace("'", "\\'", lang('be_catalog')) . "'],";

	foreach($u_coids as $k){
		$v = $cotypes[$k];
		if(empty(${'ccid'.$k})){
			tr_cns($v['cname'],"ccid$k",'',$nsid,$k,0,lang('p_choose'),0,$v['asmode']);
		}else{
			$coclasses = read_cache('coclasses',$k);
			trbasic($v['cname'],'',cnstitle(${'ccid'.$k},$v['asmode'],$coclasses),'');
			trhidden('ccid'.$k,${'ccid'.$k});
		}
		$js_frm .= "\n		[".$v['vmode'].",0,'ccid$k','" . str_replace("'", "\\'", $v['cname']) . "'],";
	}
	trbasic(lang('prompt_msg'),'','<div id="information"></div>','');
	trbasic(lang('allow_type'),'','<div id="setlink"></div>','');
	tabfooter();
	check_submit_func('return false;');
	$guest_info = $memberid ? '' : "lang('guest_info', '{$curuser->info['mname']}') + ";
	echo<<<EOT
<!--?> -->
</form>
<script type="text/javascript">
var form = document.forms['ajax_arc'],
	struct = [//类型(单选、下拉、弹出)，必选，名称，标题
		$js_frm
	],result = {};
function \$id(d){return typeof d == 'string' ? document.getElementById(d) : d}
function listen(dom,event,action){
	if(dom.attachEvent){
		var func=action;action=function(){func.apply(dom,arguments)};
		dom.attachEvent('on'+event,action);
	}else if(dom.addEventListener){
		dom.addEventListener(event,action,false);
	}else{
		if(!dom.listens)dom.listens=[];
		var x,e=dom.listens[event];
		if(!e){
			e=dom.listens[event]=[];
			if(dom['on'+event])e.push(dom['on'+event]);
			dom['on'+event]=function(m){
				for(var i=0,l=e.length;i<l;i++)e[i].call(dom,m);
			}
		}
		e.push(action);
	}
}
function ajax() {
	var a=window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest(),apply;
	function ok(){
		if(a.readyState==4 && a.status==200){
			apply(a);
		}
	}
	return {
		get:function(url,action){
			a.onreadystatechange=ok;
			apply=action;
			if(window.XMLHttpRequest) {
				a.open('GET',url);
				a.send(null);
			} else {
				a.open("GET",url,true);
				a.send();
			}
		},
		post:function(url,data,action) {
			if(typeof data=='object')data=this.param(data);
			a.onreadystatechange=ok;
			apply=action;
			a.open('POST',url);
			a.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			a.send(data);
		},
		param:function(a){
			var s=[],k,i,l,p;
			for(k in a){
				p=encodeURIComponent(k);
				if(a[k] instanceof Array){
					for(i=0,l=a[k].length;i<l;i++)s.push(p+'='+encodeURIComponent(a[k][i]));
				}else{
					s.push(p+'='+encodeURIComponent(a[k]));
				}
			}
			return s.join('&');
		}
	};
}
function initcheck(){
	var i, k, field;
	function F(f, i){ return function(){return itemcheck(f, i)}}
	for(i = 0; i < struct.length && struct[i]; i++){
		field = form[struct[i][2]];
		switch(struct[i][0]){
		case 0:
			listen(field, 'change', F(field, i));
			if(field.selectedIndex != -1)itemcheck(field, i, 1);
			break;
		case 1:
			for(k = 0; k < field.length; k++){
				listen(field[k], 'click', F(field[k], i));
				if(field[k].checked)itemcheck(field[k], i, 1);
			}
			break;
		case 2:
		case 3:
		case 4:
			field._callback = F(field, i);
			itemcheck(field, i, 1);
		}
	}
}
function itemcheck(field, ix, init){
	var a, i, flg, ret = [], rev = [], val, info = struct[ix];
	switch(info[0]){
	case 0:
		val = field.selectedIndex == -1 ? '' : field.options[field.selectedIndex].value.replace(/^\s+|\s+\$/g,'');
		break;
	case 1:
		val = field.checked ? field.value : '';
		break;
	case 2:
	case 3:
	case 4:
		val = field.value.replace(/^\s+|\s+\$/g,'');
	}
	if(!empty(val)){
		result[info[2]] = val;
	}else{
		delete result[info[2]];
	}
	val = [];
	for(i =0; i < struct.length && struct[i]; i++)if(result[struct[i][2]])ret.push(struct[i][3]);else if(struct[i][1])val.push(struct[i][3]);else rev.push(struct[i][3]);
	if(val.length)\$id('setlink').innerHTML = lang('wait_choose');
	flg = !val.length;
	ret = ret.length ? [lang('choosed') + ret.join(lang('`'))] : [];
	if(val.length)ret.push((ret.length ? lang('hai') : '') + lang('mb_choose') + val.join(lang('`')));else val = '<br />' + lang('click_add');
	if(rev.length)ret.push(lang('can_choose') + rev.join(lang('`')));
	ret = lang('nin') + ret.join(lang(',')) + lang('.');//*/
	if(typeof val == 'string')ret += val;
	if(flg){
		val = [];
		\$id('setlink').innerHTML = lang('loading');
		for(i in result)val.push(i + '=' + result[i]);
		var url = 'ajax.php?action=allowids&sid=$sid$u_chids$u_nochids&' + val.join('&');
		//url = 'test.xml';//
		rev = ret;
		ret = lang('wait_load_link');
		a = ajax();
		a.get(url, function(a){
			var ret;
			setTimeout("\$id('information').innerHTML = '" + {$guest_info}rev + "';", 20);
			eval('ret = ' + a.responseXML.lastChild.firstChild.nodeValue);
			rev = '<a href="archiveadd.php?' + (empty('$sid') ? '' : 'sid=$sid&') + val.join('&') + '&';
			val = [];
			for(i = 0; i < ret.length; i++)val.push(rev + (ret[i][2] ? 'atid=' : 'chid=') + ret[i][0] + '" onclick="return floatwin(\'open_archiveedit\',this)">>><b>' + ret[i][1] + '</b></a>&nbsp; ');
			\$id('setlink').innerHTML = val.length ? val.join(' ') : lang('no_use_item');
		});
	}
	if(!init || !struct[ix + 1])\$id('information').innerHTML = {$guest_info}ret;
}
window.onload = initcheck;
</script>
<!--<? -->
EOT;
	m_guide(@$u_guide);
}else{
	load_cache('cotypes,channels,permissions');
	load_cache('catalogs',$sid);
	include_once M_ROOT."./include/arcedit.cls.php";
	$niuid = empty($niuid) ? 0 : max(0,intval($niuid));
	$aedit = new cls_arcedit;
	$aedit->set_aid($aid);
	$aedit->basic_data(0);
	if(!$aedit->channel['isalbum']) mcmessage('choosealbum');
	if($aedit->archive['abover']) mcmessage('albumisover');
	if($aedit->channel['oneuser'] && ($memberid != $aedit->archive['mid'])) mcmessage('albumisoneuser');
	if($aedit->channel['onlyload']) mcmessage('albumisload');
	if($aedit->channel['maxnums']){
		$counts = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}albums WHERE pid='".$row['aid']."'");
		if($counts > $aedit->channel['maxnums']) mcmessage('albumovermax');
	}
	$str_suffix = "?pid=$aid";
	$str_suffix .= $niuid ? "&niuid=$niuid" : '';
	
	//两种情况：继承合辑，或事先指定了类目
	$cnarr = $incoids = array();
	if($aedit->channel['incoids']) $incoids = explode(',',$aedit->channel['incoids']);
	if(in_array('caid',$incoids) && $aedit->archive['caid']){
		$cnarr['caid'] = $aedit->archive['caid'];
	}elseif(!empty($caid)) $cnarr['caid'] = $caid;//手动指定了栏目
		
	foreach($cotypes as $k => $v){
		if(!$v['self_reg']){
			if(in_array($k,$incoids) && $aedit->archive['ccid'.$k]){
				$cnarr['ccid'.$k] = $aedit->archive['ccid'.$k];
			}elseif(!empty(${'ccid'.$k})) $cnarr['ccid'.$k] = 'ccid'.$k;//手动指定了分类
		}
	}

	tabheader(lang('add_inalbum',$aedit->archive['subject']));
	trbasic(lang('look_album'),'',"<a href=\"".view_arcurl($aedit->archive)."\" target=\"_blank\">>>".$aedit->archive['subject']."</a>",'');
	trbasic(lang('altype'),'',$aedit->channel['cname'],'');
	
	//列出已指定的类目
	foreach($cnarr as $k => $v){
		$str_suffix .= "&$k=$v";
		$coid = $k == 'caid' ? 0 : str_replace('ccid','',$k);
		$coclasses = !$coid ? @$catalogs : read_cache('coclasses',$coid);
		trbasic(lang('catas_pointed').' : '.($coid ? $cotypes[$coid]['cname'] : lang('catalog')),'',cnstitle($v,$coid ? $cotypes[$coid]['asmode'] : 0,$coclasses),'');
	}

	//直接在辑内添加的内容
	$chids = array('-1');//类目组合下允许的文档类型
	if($cnarr) $chids = $curuser->addidsfromcn($cnarr);
	$addarr = array();
	$inchids = empty($aedit->channel['inchids']) ? array() : explode(',',$aedit->channel['inchids']);
	foreach($inchids as $k) if(in_array(-1,$chids) || in_array($k,$chids)) $addarr[$channels[$k]['cname']] = 'archiveadd.php'.$str_suffix.'&chid='.$k;
	$addstr = '';
	if($addarr){
		$i = 1;
		foreach($addarr as $k => $v){
			$addstr .= ">><a href=\"$v\" onclick=\"return floatwin('open_addinabum',this)\"><b>$k</b></a>".($i % 5 ? ' &nbsp;' : '<br>');
			$i ++;
		}
	}else $addstr = lang('novalidtype');
	trbasic(lang('allow_type'),'',$addstr,'');
	tabfooter();

}
?>
</div>
</body>
</html>
