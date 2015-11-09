var Head = document.getElementsByTagName('head')[0],
	all_operates = {}, curr_operate;
function $id(d){return typeof d == 'string' ? document.getElementById(d) : d}
function listen(dom,event,action){
	if(dom.attachEvent){
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
function setsite(dm){
	window.open('?sid=' + dm.options[dm.selectedIndex].value, '_self');
}
function doane(e){
	if(document.all){
		e.returnValue=false;
		e.cancelBubble=true;
	}else{
		e.stopPropagation();
		e.preventDefault();
	}
}
function initaMenu(Ul, ck){
	var c, o, co, oa, s = [];
	function F(i, k, ul){
		var x, t;
		i.onclick = function(e, v){
/*
v		action
1		init all nodes
4		current page
7		hide other nodes
8		show parent nodes
*/
			e = e || event;
			if(v == 4){
				if(o){
					O(o.a);
					return;
				}else if(c){
					c.onclick({target:c});
					return;
				}
			}
			if(v != 1){
				doane(e);
				(e.target || e.srcElement).blur();
				switch(v){
				case 7:
					x = 0;
					break;
				case 8:
					x = 1;
					break;
				default:
					if(o == i && !v)return O(o.a);
					if(o && o.className != 'jia')o.className = o == i ? 'dian0' : o.oc ? o.oc : '';
					if(o && o != i && o.em)o.em.className = o.em.className.replace(/\s?dj0/,'');
					while(o && o != i.parentNode && o != Ul){
						if(o.onclick)o.onclick({target:o}, 7);
						o = o.parentNode;
					}
					o = i.parentNode;
					while(o && o != Ul){
						if(o.onclick)o.onclick({target:o}, 8);
						o = o.parentNode;
					}
					o = i;
					if(i.a){
						if(!i.em.className.match(/\bdj0/))i.em.className += ' dj0';
						i.className = 'dian0';
						O(i.a, v == 3);
					}
					x = !x;
					ck && Cookie(ck, k, '9Y');
				}
			}
			if(ul){
				if(i.className != 'dian0')i.className = x ? '' : 'jia';else i.oc = x ? '' : 'jia';
				ul.style.display = x ? '' : 'none';
			}
		};
		i.onmouseover = function(e){
			doane(e || event);
			if(i.className != 'dian0'){
				i.oc = i.className;
				i.className += ' hover0';
			}
		};
		i.onmouseout = function(e){
			doane(e || event);
			i.className = i.className.replace(/ ?hover0/,'');
		};
	}
	function G(ul){
		var a = [], i, z, cu, li = ul.childNodes;
		for(i = li.length-1; i >= 0; i--)li[i].nodeType == 1 && a.push(li[i]);
		if(!c)c = a[a.length-1];
		while(i = a.pop()){
			cu = i.getElementsByTagName('ul')[0];
			i.em = i.getElementsByTagName('em')[0];
			z = i.em && (i.a = i.em.getElementsByTagName('a')[0]) && (z = i.a.onclick) && (z = z.toString().match(/\((\d+)/)) ? z[1] : null;
			if(cu){
				F(i,z,cu);
				G(cu);
				i.onclick({target:i}, 1);
			}else{
				F(i,z);
			}
			if(co && co == z)c = i;
		}
	}
	function O(a, e){
		a.onclick ? a.onclick() : e ? 0 : setTimeout(function(){main.location.replace(a.href.replace(/[?&]isframe\b[^&]*$|([?&])isframe\b[^&]*&/g,'$1'))},20);
	}
	if(ck)co = Cookie(ck);
	G(Ul);
}
var initMenus = [], currMenu, currSub, currItem;
function setMenu(id, no){
	var s ,i = -1, k = /\D/.test(id), a = $id((k ? '' : 'mainmenu_') + id), lm = $id('leftmenu'), oi = $id('operateitem');
	if(a)a.blur();
	$id('urlmenus').style.display = 'none';
	if(!no && !window.main) return alert(lang('wait_err_load'));
	if(currMenu){
		if(currMenu == a.parentNode)return;
		currMenu.className = '';
	}
	if(a)(currMenu = a.parentNode).className = 's1';
	if(currSub)currSub.style.display = 'none';
	a = $id((k ? '' : 'submenus_') + id).getElementsByTagName('a');
	while(++i < a.length)if(s = a[i].href.match(/^javascript:\/\/(\w+)/)){
		s = {
			'content':'catamenu',
			'fcontent':'plugmenu',
			'mcontent':'clubmenu'
		}[s[1]];
		break;
	}
	if(s){
		if(s != 'catamenu' && !initMenus[id])initaMenu(initMenus[id] = $id(s));else initMenus[id] = $id(s);
		curr_operate = null;
		ini_operateitem();
		oi.style.display = '';
		lm.className = 'col1';
	}else{
		oi.style.display = 'none';
		lm.className = 'col2';
		if(!initMenus[id])initaMenu(initMenus[id] = $id((k ? '' : 'submenus_') + id));
	}
	(currSub = initMenus[id]).style.display = '';
	a = currSub.getElementsByTagName('li')[0];
	if(!no){if(a)a.onclick({target:a}, 4);else main.location.href = 'about:blank'}
}
function toggleMenu(url, key) {
	hideMenu();
	if(url.match(/^javascript:/))return;
	var i, x, k = /\D/.test(key), a = $id(key ? ((k ? '' : 'submenus_') + key) : 'leftmenu').getElementsByTagName('a');
	if(!key)url = url.replace(/[?&]isframe\b[^&]*$|([?&])isframe\b[^&]*&/g,'$1');
	k = location.href.length - (location.hash ? location.hash.length : 0);
	for(i = 0; i < a.length; i++){
		x = a[i].href.replace(/[?&]isframe\b[^&]*$|([?&])isframe\b[^&]*&/g,'$1');
		if(a[i].href == url || a[i].href.substr(a[i].href.indexOf('?')) == url || x == url || x.substr(x.indexOf('?')) == url){
			if(a[i].href.charAt(k) == '#' && a[i].href.substr(0, k) + location.hash == location.href)continue;
			x = a[i];
			while(x.tagName.toLowerCase() != 'li')x = x.parentNode;
			if(!key)key = x.parentNode.id.match(/\d+/)[0];
			setMenu(key, 1);
			if(x.onclick)x.onclick({target:a[i]}, 3);
			break;
		}
	}
}
function initCpMap(m,t) {
	var i, j, k, s, sa, ma = $id(t).getElementsByTagName('a'), fix = '',ret = '';
	for(j = 0; j < ma.length; j++) {
		k = ma[j].id.match(/\d+/)[0];
		sa = $id('submenus_' + k).getElementsByTagName('a');
		if(!sa.length)continue;
		i = -1;while(++i < sa.length)if(s = sa[i].href.match(/^javascript:\/\/(\w+)/)){s = s[1]; break;}
		switch(s){
		case 'content':
		case 'fcontent':
		case 'mcontent':
			fix += '<li><a href="javascript:" target="main" onclick="showMap();setMenu('+k+');return false">' + ma[j].innerHTML + '</a></li>';
			break;
		default:
			ret += '<td valign="top"><ul class="cmblock"><li><h4>' + ma[j].innerHTML + '</h4></li>';
			for(var i = 0; i < sa.length; i++) {
				ret += '<li><a href="' + sa[i].href + '" target="main" onclick="toggleMenu(this.href,'+k+')">' + sa[i].innerHTML + '</a></li>';
			}
			ret += '</ul></td>';
		}
	}
	if(fix)fix = '<td valign="top"><ul class="cmblock"><li><h4>' + lang('fixlink') + '</h4></li>' + fix + '</ul></td>';
	sa = $id('urlmenus').getElementsByTagName('a');
	if(sa.length){
		fix += '<td valign="top"><ul class="cmblock"><li><h4>' + lang('urllink') + '</h4></li>';
		for(var i = 0; i < sa.length; i++)fix += '<li><a href="' + sa[i].href.replace(/[?&]isframe\b[^&]*$|([?&])isframe\b[^&]*&/g,'$1') + '" target="main" onclick="toggleMenu(this.href,\'urlmenus\')">' + sa[i].innerHTML + '</a></li>';
		fix += '</ul></td>';
	}
	ret	= '<ul class="cnote"><li>' + lang('map_tip') + '</li></ul><table class="cmlist"><tr>' + fix + ret + '</tr></table>';
	$id(m).innerHTML = ret;
}
function showMap() {
	showMenu('cpmap', true);
}
function get_operate(caid, type){
	var a, i, p, t, u;
	type = type || 0;
	i = all_operates[type] || (all_operates[type] = {});
	if(curr_operate === caid){
		i[caid] && ini_operate(caid, type);
		return;
	}
	curr_operate = caid;
	if(i[caid]){
			ini_operate(caid, type);
	}else{
		p = document.createElement('li');
		p.className = 'loading';
		p.appendChild(document.createTextNode(lang('load_item')));
		ini_operateitem().appendChild(p);
		a = Ajax();
		u = 'tools/ajax.php?action=' + (
			type == 0 ? ('ablock&sid=' + site_id +'&caid=' + caid) :
			type == 1 ? ('fblock&caid=' + caid) :
						('mblock&mchid=' + caid)
			) + '&t=' + (new Date).getTime();
		a.get(u, function(x){
				  clearTimeout(t);
				eval('i[caid] = ' + x);
				ini_operate(caid, type);
		});
		t = setTimeout(function(){a=null;p.innerHTML = 'time out'},30000);
	}
}
function ini_operate(caid, type){
	if(curr_operate !== caid)return;
	var a, l, i, u, d = ini_operateitem(), p = all_operates[type][caid];
	function F(m){var c; m.onmouseover = function(){c = m.className; m.className = 'btnon'}; m.onmouseout = function(){if(m.className == 'btnon')m.className = c}}
	for(i = 0; i < p.length && p[i]; i++){
		a = document.createElement('a');
		a.href = !p[i][1] || p[i][1] == '#' ? 'javascript:' : p[i][1];
		a.target = 'main';
		a.onclick = function(){this.blur();if(d._curr_)d._curr_.className = '';d._curr_ = this.parentNode;d._curr_.className = 'btnok';};
		a.appendChild(document.createTextNode(p[i][0]));
		F(l = document.createElement('li'));
		l.appendChild(a);
		d.appendChild(l);
		if(i == 0){
			u = a.href
			setTimeout(function(){main.location.replace(u)},20);
			a.onclick();
		}
	}
}
function ini_operateitem(){
	var p = $id('operateitem'), d = p.childNodes, i = -1;
	while(++i < d.length)if(d[i].nodeType == 1 && d[i].tagName.toLowerCase() == 'ul'){d[i].parentNode.removeChild(d[i]);break};
	d = document.createElement('ul');
	p.appendChild(d);
	return d;
}
function redef_keydown(e) {
	e = e ? e : window.event;
	actualCode = e.keyCode ? e.keyCode : e.charCode;
	if(actualCode == 27) {
		showMap();
	}
	if(actualCode == 116 && parent.main){//F5
		parent.main.location.reload();
		if(document.all) {
			e.keyCode = 0;
			e.returnValue = false;
		} else {
			e.cancelBubble = true;
			e.preventDefault();
		}
	}
	if(actualCode == 122){//F11
		var div = $id('header');
		if(e.ctrlKey){
			if(document.all) {
				e.keyCode = 0;
				e.returnValue = false;
			}else{
				e.cancelBubble = true;
				e.preventDefault();
			}
		}else if(div){
			div.fullScreen = !div.fullScreen;
		}
		div && click_setscreen(null, div, e.ctrlKey ? !div.style.display : div.fullScreen);
	}
}
function click_setscreen(button, div, tag){
	div = div || $id('header');
	if(!div)return;
	tag = tag || !div.style.display;
	floatminheight = tag ? 0 : 90;
	if(!div.dom){
		var dom = div.parentNode;
		while(dom && dom.tagName != 'TD')dom = dom.parentNode;
		div.dom = dom || 1;
	}
	if(div.dom.nodeType){
		div.style.display = tag ? 'none' : '';
		tag ? div.dom.removeAttribute('height') : div.dom.setAttribute('height', aframe_topheader_height);
		aframe_topheader_height = div.dom.offsetHeight;
		window.onresize && window.onresize();
	}
	if(button)button.style.backgroundPosition = tag ? '0 0' : -button.offsetWidth + 'px 0';
}
function main_onload(f){
	var a, u, li, ul, id = '_'+'0'+'8'+'c'+'m'+'s'+'_'+'d'+'y'+'n'+'a'+'m'+'i'+'c'+'_'+'i'+'n'+'f'+'o', c = Cookie(id), w = f.contentWindow || window.main, d = w.document;
	listen(d.documentElement, 'keydown', redef_keydown);
	if(!w.location.href.match(/\bentry=home\b/i))return;
	ul = d.getElementById(id);
	if(ul){
		li = d.createElement('li');
		li.appendChild(d.createTextNode('L'+'o'+'a'+'d'+'i'+'n'+'g'+'.'+'.'+'.'));
		ul.appendChild(li)
	}
	if(ul || !c){
		eval("u='http://www.08cms.com/ajax.php?'+(ul?('a'+'c'+'t'+'i'+'o'+'n'+'='+'d'+'y'+'n'+'a'+'m'+'i'+'c'):('t='+(new Date).getTime()))");
	}
}
listen(document.documentElement, 'keydown', redef_keydown);
