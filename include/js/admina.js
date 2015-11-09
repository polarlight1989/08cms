var userAgent = navigator.userAgent.toLowerCase(),
	is_webtv = userAgent.indexOf('webtv') != -1,
	is_kon = userAgent.indexOf('konqueror') != -1,
	is_mac = userAgent.indexOf('mac') != -1,
	is_saf = userAgent.indexOf('applewebkit') != -1 || navigator.vendor == 'Apple Computer, Inc.',
	is_opera = userAgent.indexOf('opera') != -1 && opera.version(),
	is_moz = (navigator.product == 'Gecko' && !is_saf) && userAgent.substr(userAgent.indexOf('firefox') + 8, 3),
	is_ns = userAgent.indexOf('compatible') == -1 && userAgent.indexOf('mozilla') != -1 && !is_opera && !is_webtv && !is_saf,
	is_ie = (userAgent.indexOf('msie') != -1 && !is_opera && !is_saf && !is_webtv) && userAgent.substr(userAgent.indexOf('msie') + 5, 3),

	ctrlobjclassName,cssloaded=[],ajaxdebug,Ajaxs=[],AjaxStacks=[0,0,0,0,0,0,0,0,0,0],attackevasive=isUndefined(attackevasive) ? 0 : attackevasive,ajaxpostHandle=0,loadCount=0,hiddenobj=[],floatscripthandle=[],InFloat='';

function $id(d){return typeof d == 'string' ? document.getElementById(d) : d}
function $ce(tag){return document.createElement(tag)}

function empty(val){
	var i,ret = !val;
	if(!ret){
		if(typeof val == 'string')
			ret =/^[\s|0]*$/.test(val);
		else if(val instanceof Array)
			ret = !val.length;
		else if(val instanceof Object){
			ret = true;
			for(i in val){ret = false;break}
		}
	}
	return ret;
}

function in_array(needle, haystack){
	if(typeof needle == 'string'){
		for(var i in haystack){
			if(haystack[i] == needle){
					return true;
			}
		}
	}
	return false;
}

function checkall(form, prefix, checkall){
	checkall = checkall ? checkall : 'chkall';
	for(var i = 0; i < form.elements.length; i++){
		var e = form.elements[i];
		if(e.name != checkall && (!prefix || !e.name.indexOf(prefix))){
			e.checked = form.elements[checkall].checked;
		}
	}
}
function checkallvalue(form, value, checkall){
	checkall = checkall ? checkall : 'chkall';
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(e.type == 'checkbox' && e.value == value) {
			e.checked = form.elements[checkall].checked;
		}
	}
}

function redirect(url) {
	window.location.replace(url);
}

function getcookie(name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}

function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}

function trim(str) {
	return str?str.replace(/^\s+|\s+$/,''):'';
}

function parseurl(str, mode) {
	var x='([^>=\]"\'\/]|^)((((https?|ftp):\/\/)|www\.)([\w\-]+\.)*[\w\-\u4e00-\u9fa5]+\.([\.a-zA-Z0-9]+|\u4E2D\u56FD|\u7F51\u7EDC|\u516C\u53F8)((\?|\/|:)+[\w\.\/=\?%\-&~`@\':+!]*)+\.(jpg|gif|png|bmp))',y='([^>=\]"\'\/]|^)((((https?|ftp|gopher|news|telnet|rtsp|mms|callto|bctp|ed2k):\/\/)|www\.)([\w\-]+\.)*[\w\-\u4e00-\u9fa5]+\.([\.a-zA-Z0-9]+|\u4E2D\u56FD|\u7F51\u7EDC|\u516C\u53F8)((\?|\/|:)+[\w\.\/=\?%\-&~`@\':+!#]*)*)';
	str = str.replace(new RegExp(x,'ig'), mode == 'html' ? '$1<img src="$2" border="0">' : '$1[img]$2[/img]');
	str = str.replace(new RegExp(y,'ig'), mode == 'html' ? '$1<a href="$2" target="_blank">$2</a>' : '$1[url]$2[/url]');
	str = str.replace(/([^\w>=\]:"']|^)(([\-\.\w]+@[\.\-\w]+(\.\w+)+))/ig, mode == 'html' ? '$1<a href="mailto:$2">$2</a>' : '$1[email]$2[/email]');
	return str;
}
function fullurl(url){
	if(!/[0-9_a-z]+:/i.test(url)){
		var u=location.href;
		u=u.substr(0,u.indexOf('?')<0?u.length:u.indexOf('?'));
		if(url.substr(0,1)=='?')
			url=u+url;
		else{
			var d=/([0-9_a-z]+:\/*[^\/]+)/i.exec(u),h=d[1],d=u.lastIndexOf('/');
			d=d==0?'':u.substring(h.length+1,d+1);
			if(url.substr(0,1)=='/'){
				url=h+url;
			}else{
				var i=1,s=url.indexOf('?');u=s<0?url:url.substr(0,s);s=s<0?'':url.substr(s);
				u=(d+u).split('/');
				while(i<u.length){
					if(u[i]=='..'){
						u.splice(--i,2);
						if(i<1)i=1;
					}else{
						i++;
					}
				}
				i=0;
				while(u[i]=='..')i++;
				u.splice(0,i);
				url=h+'/'+u.join('/')+s;
			}
		}
	}
	return url;
}

function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}
function findtags(parentobj, tag) {
	if(!isUndefined(parentobj.getElementsByTagName)) {
		return parentobj.getElementsByTagName(tag);
	} else if(parentobj.all && parentobj.all.tags) {
		return parentobj.all.tags(tag);
	} else {
		return null;
	}
}
function alterview(tname){
	if($id(tname)!=null){
		if($id(tname).style.display=='none'){
			$id(tname).style.display='';
		}else{
			$id(tname).style.display='none';
		}
	}
}
function clearalerts(form){
	tags = findtags(form,'div');
	if(!tags) return;
	var reg = /^alert_/;
	for(k in tags){
		if(reg.test(tags[k].id)){
			try{
				var div = document.createElement('div');
				div.id =tags[k].id;
				div.className = tags[k].className;
				tags[k].parentNode.replaceChild(div,tags[k]);
			}catch(e){
				tags[k].innerHTML = '';
			}
		}
	}
}
function strlen(str){
	var tmp =window.charset == 'utf-8' ? '***' : '**';
	return str.replace(/[^\x00-\xff]/g, tmp).length;
}
function isdate(str){
	var ret = str.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
	if(ret == null) return false;
	ret[2] --;
	var d = new Date(ret[1],ret[2],ret[3]);
	return d.getFullYear() == ret[1] && d.getMonth() == ret[2] && d.getDate() == ret[3];
}
function isnumber(str){
	var reg = /^(-?\d+)(\.\d+)?$/;
	return reg.test(str);
}
function isnumberletter(str){
	var reg = /^\w+$/;
	return reg.test(str);
}
function istagtype(str){
	var reg = /^[a-zA-Z]+\w*$/;
	return reg.test(str);
}
function isletter(str){
	var reg = /^[a-zA-Z]+$/;
	return reg.test(str);
}
function isint(str){
	var reg = /^-?\d+$/;
	return reg.test(str);
}
function isemail(str){
	var reg = /([\w|_|\.|\+]+)@([-|\w]+)\.([A-Za-z]{2,4})/;
	return reg.test(str);
}
function strmatch(str,matchstr){
	return matchstr.test(str);	
}
function mtagcodecopy(obj) {
	obj.focus();
	obj.select();
	if(document.all){
		obj.createTextRange().execCommand("Copy");
	}
}
function opennewwin(url,wname,width,height){
	if(is_ie){
		var posLeft = window.event.clientX-100;
		var posTop = window.event.clientY; 
	}else{
		var posLeft = 100;
		var posTop = 100;
	}
	window.open(url,wname,"scrollbars=yes,resizable=yes,statebar=no,width=" + width + ",height=" + height + ",left=" + posLeft + ", top=" + posTop);
}

function checkidsarr(value,vvalue,idsname){
	var o = $id('mselect_' + idsname + '_area') || $id('mselect_' + idsname);
	if(value == vvalue){
		$id(idsname).style.visibility = 'visible';
		o.style.display = '';
	}else{
		$id(idsname).style.visibility = 'hidden';
		o.style.display = 'none';
	}
}
function initidscheckboxtoi(I){
	var O = document.getElementsByTagName('INPUT'),
	S = 'mselect_' + I.id,
	T = O.length;
	I.checkbox = [];
	for(var i = 0; i < T; i++)
		O[i].type == 'checkbox' && O[i].id == S && I.checkbox.push(O[i]);
	return I.checkbox;
}
function setidswithi(I, type){
	var X = I.value, K = '_$_oldValues_$_', O , S , T;
	if(type){
		O = I.checkbox || initidscheckboxtoi(I);
	}else{
		O = $id('mselect_' + I.id).options;
	}
	T = setInterval(function (){
		if(I.value != X){
			X = I.value;
//			X = I.value.replace(/,+/g, ',').replace(/^,/, '');
			var A = {}, m = X.split(','), v = [], i = 0, l = m.length;
			while(i < l){
//				m[i] in A || v.push(m[i]);
				if(/^\d+$/.test(m[i]))A[m[i]] = 1;
				i++;
			}
//			X = I.value = v.join(',');
			if(type)
				for(var i = 0, l = O.length; i < l; i++)O[i].checked = O[i].value in A;
			else
				for(var i = 0, l = O.length; i < l; i++)O[i].selected = O[i].value in A;
			I[K] = []
			for(v in A)I[K].push(v);
		}
	}, 50);
	I.onblur = function(){
		setTimeout(function(){
			clearInterval(T);
		}, 50);
	};
}

function setidswiths(S, type){
	var I = $id(S.id.substr(8)), X = I.value.replace(/,+$/, ''), O = type ? I.checkbox || initidscheckboxtoi(I) : S.options, K = '_$_oldValues_$_',A = {}, o = I[K],n = [], a = [], i =0, l = O.length, e, j, k, p;
	if(!o){
		o = [];
		while(i < l){
			(O[i].defaultSelected || O[i].defaultChecked) && o.push(O[i].value);
			(O[i].selected || O[i].checked) && n.push(O[i].value);
			i++;
		}
	}else{
		while(i < l){
			(O[i].selected || O[i].checked) && n.push(O[i].value);
			i++;
		}
	}
	I[K] = n;
	for(i = 0, l = n.length, k = o.length; i < l; i++){
		p = 0;
		for(j = 0; j < k; j++){
			if(n[i] == o[j]){
				p = 1;
				break;
			}
		}
		if(p){
			o.splice(j,1);
			k--;
		}else{
			a.push(n[i]);
		}
	}
	X = X.replace(/^,+|,+$/g, '').split(',');
	for(i = 0, l = X.length; i < l; i++)A[X[i]] = 1;
	delete A[''];
	for(i = 0, l = o.length; i < l; i++)delete A[o[i]];
	X = [];
	for(k in A)X.push(k);
	I.value = X.concat(a).join(',');
}

function setIdWithI(I){
	var S = $id('mselect_' + I.id), K = '_$_oldValues_$_', X = I.value, T, i;
	if(!S[K]){
		S[K] = {};
		for(i = 0; i < S.options.length; i++)S[K][S.options[i].value] = i;
	}
	T = setInterval(function (){
		if(I.value != X){
			X = I.value;
			S.selectedIndex = S[K][X] ? S[K][X] : -1;
		}
	}, 50);
	I.onblur = function(){
		setTimeout(function(){
			clearInterval(T);
		}, 50);
	};
}

function setIdWithS(S){
	var I = $id(S.id.substr(8));
	I.value = S.options[S.selectedIndex].value;
}

function single_list_set(radio, same, diff){
	function O(v){var k = same + '$' + diff;if(v)window[k] = v;else{return window[k]}}
	var o = O(), n = radio.value, a, i, l, p;
	if(o === undefined){
		p = radio.form[radio.name];
		for(i = 0, l = p.length; i < l; i++)
			if(p[i].defaultChecked){
				o = p[i].value;
				break;
			}
	}
	a = [$id(same + o), $id(same + n)];
	if(diff)a = a.concat([$id(diff + n), $id(diff + o)]);
	i = 0;
	l = a.length;
	while(i < l && (p = a[i++]));
	if(p){
		O(n);
		i = 0;
		while(i < l)a[i].style.display = i++ % 2 ? '' : 'none';
	}else{
		O(o);
		p = radio.form[radio.name];
		for(i = 0, l = p.length; i < l; i++)
			p[i].checked = p[i].value == o;
		alert(lang('wait_err_load'));
	}
}

function AC_GetArgs(args, classid, mimeType) {
	var ret = new Object();
	ret.embedAttrs = new Object();
	ret.params = new Object();
	ret.objAttrs = new Object();
	for (var i = 0; i < args.length; i = i + 2){
		var currArg = args[i].toLowerCase();
		switch (currArg){
			case "classid":break;
			case "pluginspage":ret.embedAttrs[args[i]] = 'http://www.macromedia.com/go/getflashplayer';break;
			case "src":ret.embedAttrs[args[i]] = args[i+1];ret.params["movie"] = args[i+1];break;
			case "codebase":ret.objAttrs[args[i]] = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0';break;
			case "onafterupdate":case "onbeforeupdate":case "onblur":case "oncellchange":case "onclick":case "ondblclick":case "ondrag":case "ondragend":
			case "ondragenter":case "ondragleave":case "ondragover":case "ondrop":case "onfinish":case "onfocus":case "onhelp":case "onmousedown":
			case "onmouseup":case "onmouseover":case "onmousemove":case "onmouseout":case "onkeypress":case "onkeydown":case "onkeyup":case "onload":
			case "onlosecapture":case "onpropertychange":case "onreadystatechange":case "onrowsdelete":case "onrowenter":case "onrowexit":case "onrowsinserted":case "onstart":
			case "onscroll":case "onbeforeeditfocus":case "onactivate":case "onbeforedeactivate":case "ondeactivate":case "type":
			case "id":ret.objAttrs[args[i]] = args[i+1];break;
			case "width":case "height":case "align":case "vspace": case "hspace":case "class":case "title":case "accesskey":case "name":
			case "tabindex":ret.embedAttrs[args[i]] = ret.objAttrs[args[i]] = args[i+1];break;
			default:ret.embedAttrs[args[i]] = ret.params[args[i]] = args[i+1];
		}
	}
	ret.objAttrs["classid"] = classid;
	if(mimeType) {
		ret.embedAttrs["type"] = mimeType;
	}
	return ret;
}

function AC_FL_RunContent() {
	var ret = AC_GetArgs(arguments, "clsid:d27cdb6e-ae6d-11cf-96b8-444553540000", "application/x-shockwave-flash");
	var str = '';
	if(is_ie && !is_opera) {
		str += '<object ';
		for (var i in ret.objAttrs) {
			str += i + '="' + ret.objAttrs[i] + '" ';
		}
		str += '>';
		for (var i in ret.params) {
			str += '<param name="' + i + '" value="' + ret.params[i] + '" /> ';
		}
		str += '</object>';
	} else {
		str += '<embed ';
		for (var i in ret.embedAttrs) {
			str += i + '="' + ret.embedAttrs[i] + '" ';
		}
		str += '></embed>';
	}
	return str;
}

function doane(event) {
	e=event ? event : window.event;
	if(is_ie) {
		e.returnValue=false;
		e.cancelBubble=true;
	} else if(e) {
		e.stopPropagation();
		e.preventDefault();
	}
}

function mb_strlen(str) {
	var len=0;
	for(var i=0; i < str.length; i++) {
		len +=str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (charset=='utf-8' ? 3 : 2) : 1;
	}
	return len;
}

function mb_cutstr(str,maxlen,dot) {
	var i,len=0,ret='';
	dot=!dot ? '...' : '';
	maxlen=maxlen - dot.length;
	for(i=0; i < str.length; i++) {
		len +=str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (charset=='utf-8' ? 3 : 2) : 1;
		if(len > maxlen)break;
	}
	ret =str.substr(0,i+1)+(i==str.length?'':dot);
	return ret;
}

function sz_substr(str,maxlen,dot){
	var i,len=0,ret='';
	dot=!dot?'...':'';
	maxlen=maxlen-dot.replace(/[^\x00-\xff]/g,'**').length;
	for(i=0;i<str.length&&len<maxlen;i++)len+=str.charCodeAt(i)<0||str.charCodeAt(i)>255?2:1;
	return str.substr(0,i+1)+(i==str.length?'':dot);
}

function choose(e,obj) {
	var links=obj.getElementsByTagName('a');
	if(links[0]) {
		if(is_ie) {
			links[0].click();
			window.event.cancelBubble=true;
		} else {
			if(e.shiftKey) {
				window.open(links[0].href);
				e.stopPropagation();
				e.preventDefault();
			} else {
				window.location=links[0].href;
				e.stopPropagation();
				e.preventDefault();
			}
		}
		hideMenu();
	}
}

function display(id) {
	$id(id).style.display=$id(id).style.display=='' ? 'none' : '';
}

function display_opacity(id,n) {
	if(!$id(id)) {
		return;
	}
	if(n >=0) {
		n -=10;
		$id(id).style.filter='progid:DXImageTransform.Microsoft.Alpha(opacity=' + n + ')';
		$id(id).style.opacity=n / 100;
		setTimeout('display_opacity(\'' + id + '\',' + n + ')',50);
	} else {
		$id(id).style.display='none';
		$id(id).style.filter='progid:DXImageTransform.Microsoft.Alpha(opacity=100)';
		$id(id).style.opacity=1;
	}
}

var evalscripts=new Array();
function evalscript(s) {
	if(s.indexOf('<script')==-1) return s;
	var p=/<script[^\>]*?>([^\x00]*?)<\/script>/ig;
	var arr=new Array();
	while(arr=p.exec(s)) {
		var p1=/<script[^\>]*?src=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?(?:charset=\"([\w\-]+?)\")?><\/script>/i;
		var arr1=new Array();
		arr1=p1.exec(arr[0]);
		if(arr1) {
			appendscript(arr1[1],'',arr1[2],arr1[3]);
		} else {
			p1=/<script(.*?)>([^\x00]+?)<\/script>/i;
			arr1=p1.exec(arr[0]);
			appendscript('',arr1[2],arr1[1].indexOf('reload=') !=-1);
		}
	}
	return s;
}

function appendscript(src,text,reload,charset) {
	var id=hash(src + text);
	if(!reload && in_array(id,evalscripts)) return;
	if(reload && $id(id)) {
		$id(id).parentNode.removeChild($id(id));
	}

	evalscripts.push(id);
	var scriptNode=$ce("script");
	scriptNode.type="text/javascript";
	scriptNode.id=id;
	scriptNode.charset=charset ? charset : (is_moz ? document.characterSet : document.charset);
	try {
		if(src) {
			scriptNode.src=src;
		} else if(text){
			scriptNode.text=text;
		}
		$id('append_parent').appendChild(scriptNode);
	} catch(e) {}
}

function stripscript(s) {
	return s.replace(/<script.*?>.*?<\/script>/ig,'');
}

function ajaxupdateevents(obj,tagName) {
	tagName=tagName ? tagName : 'A';
	var objs=obj.getElementsByTagName(tagName);
	for(k in objs) {
		var o=objs[k];
		ajaxupdateevent(o);
	}
}

function ajaxupdateevent(o) {
	if(typeof o=='object' && o.getAttribute) {
		if(o.getAttribute('ajaxtarget')) {
			if(!o.id) o.id=Math.random();
			var ajaxevent=o.getAttribute('ajaxevent') ? o.getAttribute('ajaxevent') : 'click';
			var ajaxurl=o.getAttribute('ajaxurl') ? o.getAttribute('ajaxurl') : o.href;
			_attachEvent(o,ajaxevent,newfunction('ajaxget',ajaxurl,o.getAttribute('ajaxtarget'),o.getAttribute('ajaxwaitid'),o.getAttribute('ajaxloading'),o.getAttribute('ajaxdisplay')));
			if(o.getAttribute('ajaxfunc')) {
				o.getAttribute('ajaxfunc').match(/(\w+)\((.+?)\)/);
				_attachEvent(o,ajaxevent,newfunction(RegExp.$1,RegExp.$2));
			}
		}
	}
}

function ajaxget(url,showid,waitid,loading,display,recall) {
	showloading();
	waitid=typeof waitid=='undefined' || waitid===null ? showid : waitid;
	var x=new Ajax();
	x.setLoading(loading);
	x.setWaitId(waitid);
	x.display=typeof display=='undefined' || display==null ? '' : display;
	x.showId=$id(showid);
	if(x.showId) x.showId.orgdisplay=typeof x.showId.orgdisplay==='undefined' ? x.showId.style.display : x.showId.orgdisplay;

	if(url.substr(strlen(url) - 1)=='#') {
		url=url.substr(0,strlen(url) - 1);
		x.autogoto=1;
	}

	url=url + '&inajax=1&ajaxtarget=' + showid;
	x.get(url,function(s,x) {
		showloading('none');
		if(x.showId) {
			x.showId.style.display=x.showId.orgdisplay;
			x.showId.style.display=x.display;
			x.showId.orgdisplay=x.showId.style.display;
			ajaxinnerhtml(x.showId,s);
			ajaxupdateevents(x.showId);
			if(x.autogoto) scroll(0,x.showId.offsetTop);
		}
		if(recall) {eval(recall);}
	});
}

function ajaxpost(formid,showid,handlekey,showidclass,submitbtn,newwin) {
//	var waitid=typeof waitid=='undefined' || waitid===null ? showid : (waitid !=='' ? waitid : '');
	var showidclass=!showidclass ? '' : showidclass;
	if(ajaxpostHandle !=0) {
		return false;
	}
	showloading();
	var ajaxframeid='ajaxframe';
	var ajaxframe=$id(ajaxframeid);
	if(ajaxframe==null) {
		if (is_ie && !is_opera) {
			ajaxframe=$ce("<iframe name='" + ajaxframeid + "' id='" + ajaxframeid + "'></iframe>");
		} else {
			ajaxframe=$ce("iframe");
			ajaxframe.name=ajaxframeid;
			ajaxframe.id=ajaxframeid;
		}
		ajaxframe.style.display='none';
		$id('append_parent').appendChild(ajaxframe);
	}
	var form=$id(formid);
	form.target=ajaxframeid;
	ajaxpostHandle=[showid,ajaxframeid,formid,$id(formid).target,showidclass,submitbtn,newwin,handlekey,form.action];
	if(ajaxframe.attachEvent) {
		ajaxframe.detachEvent ('onload',ajaxpost_load);
		ajaxframe.attachEvent('onload',ajaxpost_load);
	} else {
		ajaxframe.removeEventListener('load',ajaxpost_load,true);
		ajaxframe.addEventListener('load',ajaxpost_load,false);
	}
	form.action +=(form.action.indexOf('?')<0?'?':'&')+'infloat=1&inajax=1&handlekey='+(newwin?'post_':'') + handlekey + '&ajaxtarget=' + showid;
	return true;
}

function ajaxpost_load() {
	showloading('none');
	var d,h=ajaxpostHandle,s='';
	try {
		d=$id(h[1]).contentWindow.document;
		s=is_ie?d.XMLDocument.text:d.documentElement.firstChild.nodeValue;
	} catch(e) {
		if(ajaxdebug) {
			var error=mb_cutstr(d.body.innerText.replace(/\r?\n/g,'\\n').replace(/"/g,'\\\"'),200);
			s='<root>ajaxerror<script type="text/javascript" reload="1">alert(\'Ajax Error: \\n' + error + '\');</script></root>';
		}
	}
	evaled=false;
	if(s && s.indexOf('ajaxerror') !=-1) {
		evalscript(s);
		evaled=true;
	}
	if(h[4]) {
		$id(h[0]).className=h[4];
		if(h[5])h[5].disabled=false;
	}
	if(!evaled && (typeof ajaxerror=='undefined' || !ajaxerror)) {
		var layer=(h[6]?'post_':'')+h[7],layerid='floatwin_'+layer;
		floatwin('center_'+h[7]);
		if(h[6])floatwin('open_'+layer,-1,0,0,'',h[7]);
		floatscripthandle[layer]=[h[8],layerid];
		ajaxinnerhtml($id(layerid),s);
		if(!evaled)evalscript(s);
		setMenuPosition($id(layerid).ctrlid,0);
		setTimeout("hideMenu()",3000);
	}
	ajaxerror=null;
	if($id(h[2]))$id(h[2]).target=h[3];
	ajaxpostHandle=0;
}

function ajaxmenu(e,ctrlid,timeout,func,cache,duration,ismenu,divclass,optionclass) {
	showloading();
	if(jsmenu['active'][0] && jsmenu['active'][0].ctrlkey==ctrlid) {
		hideMenu();
		doane(e);
		return;
	} else if(is_ie && is_ie < 7 && document.readyState.toLowerCase() !='complete') {
		return;
	}
	if(isUndefined(timeout)) timeout=3000;
	if(isUndefined(func)) func='';
	if(isUndefined(cache)) cache=1;
	if(isUndefined(divclass)) divclass='popupmenu_popup';
	if(isUndefined(optionclass)) optionclass='popupmenu_option';
	if(isUndefined(ismenu)) ismenu=1;
	if(isUndefined(duration)) duration=timeout > 0 ? 0 : 3;
	var div=$id(ctrlid + '_menu');
	if(cache && div) {
		showMenu(ctrlid,e.type=='click',0,duration,timeout,0,ctrlid,400,1);
		if(func) setTimeout(func + '(' + ctrlid + ')',timeout);
		doane(e);
	} else {
		if(!div) {
			div=$ce('div');
			div.ctrlid=ctrlid;
			div.id=ctrlid + '_menu';
			div.style.display='none';
			div.className=divclass;
			$id('append_parent').appendChild(div);
		}

		var x=new Ajax();
		var href=!isUndefined($id(ctrlid).href) ? $id(ctrlid).href : $id(ctrlid).attributes['href'].value;
		x.div=div;
		x.etype=e.type;
		x.optionclass=optionclass;
		x.duration=duration;
		x.timeout=timeout;
		x.get(href + '&inajax=1&ajaxmenuid='+ctrlid+'_menu',function(s) {
			evaled=false;
			if(s.indexOf('ajaxerror') !=-1) {
				evalscript(s);
				evaled=true;
				if(!cache && duration !=3 && x.div.id) setTimeout('$id("append_parent").removeChild($id(\'' + x.div.id + '\'))',timeout);
			}
			if(!evaled && (typeof ajaxerror=='undefined' || !ajaxerror)) {
				if(x.div) x.div.innerHTML='<div class="' + x.optionclass + '">' + s + '</div>';
				showMenu(ctrlid,x.etype=='click',0,x.duration,x.timeout,0,ctrlid,400,1);
				if(func) setTimeout(func + '("' + ctrlid + '")',x.timeout);
			}
			if(!evaled) evalscript(s);
			ajaxerror=null;
			showloading('none');
		});
		doane(e);
	}
}

//得到一个定长的hash值， 依赖于 stringxor()
function hash(string,length) {
	var length=length ? length : 32;
	var start=0;
	var i=0;
	var result='';
	filllen=length - string.length % length;
	for(i=0; i < filllen; i++){
		string +="0";
	}
	while(start < string.length) {
		result=stringxor(result,string.substr(start,length));
		start +=length;
	}
	return result;
}

function stringxor(s1,s2) {
	var s='';
	var hash='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var max=Math.max(s1.length,s2.length);
	for(var i=0; i<max; i++) {
		var k=s1.charCodeAt(i) ^ s2.charCodeAt(i);
		s +=hash.charAt(k % 52);
	}
	return s;
}

function checkByCell(e){
	e = e || event;
	if(e.button == 2)return;
	e = e.target || e.srcElement;
	if(in_array(e.tagName, ['INPUT','TEXTAREA','A','IMG']))return;
	while(e && e.tagName != 'TR')e = e.parentNode;
	if(!e)return;
	var b = e.getElementsByTagName('INPUT'), fix = window._clickprefix ? window._clickprefix : 'selectid', len = fix.length, i, x;
	for(i = 0; i < b.length; i++)if(b[i].name && b[i].name.substr(0, len) == fix){
		x = b[i];
		while(x && x.tagName != 'TR')x = x.parentNode;
		if(x != e)continue;
		b[i].checked = !b[i].checked;break
	}
}
listen(document.documentElement, 'mousedown', checkByCell);