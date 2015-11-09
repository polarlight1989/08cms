function $id(d){return typeof d == 'string' ? document.getElementById(d) : d}

function loaderror(){
	window.onload = function(){
		$id('loading').innerHTML = lang('init_field_err');
	}
}

function checkall2(form, prefix, checkall){
	checkall = checkall ? checkall : 'chkall';
	for(var i = 0; i < form.elements.length; i++){
		var e = form.elements[i];
		if(e.name != checkall && (!prefix || (prefix && e.name.match(prefix)))){
			e.checked = form.elements[checkall].checked;
			checkalbum({target:e});
		}
	}
}

function winclose(c){
	try{try{$WE.elements[show_id].focus()}catch(e){}delete $WE.elements[field_id];delete $WE.elements[show_id]}catch(e){}
	try{window.close();}catch(e){}
	try{var w=window.parent;w.floatwin('close_'+win_id,0,0,0,0,0,1);w.$id('floatwin_'+win_id+'_content').innerHTML=''}catch(e){}
}

function setretval(){
	var val=[],txt=[],k,s;
	try{
		f=$WE.elements[field_id];
		s=$WE.elements[show_id];
		for(k in retval){val.push(k);txt.push(retval[k])}
		f.value = val.join(',');
		if(f._data){
			for(k in f._data)delete f._data[k];
			for(k in retval)f._data[k] = retval[k];
		}
		if(s)try{s.innerHTML = txt.join(App == 'cata' ? ',' : '<br />') || lang('choose')}catch(e){s.value = txt.join(App == 'cata' ? ',' : '\n') || lang('choose')}
		try{f._callback && f._callback(val)}catch(e){}
	}catch(e){
		alert(lang('win_field_err'));
	}
	winclose();
}

function checkcata(e){
	e = e || event;
	var v, a = e.target || e.srcElement;
	if(a.tagName.toLowerCase() == 'a' && (v = a.getAttribute('rev'))){
		a.blur();
		if(retval[v]){
			a.parentNode.className = a.parentNode.tagName.toLowerCase() == 'li' ? a.parentNode.className.replace(/\s?selecting/,'') : '';
			delete retval[v];
			res_count--;
			if(res_count < 0)res_count = 0;
		}else{
			if(!mode){
				res_count = 0;
				retval = {};
			}
			if(res_count >= res_limit){
				alert(lang('out_num_limit', res_limit));
				return;
			}
			res_count++;
			retval[v] = hash[v];
			a.parentNode.className += a.parentNode.tagName.toLowerCase() == 'li' ? ' selecting' : 'h1selecting';
		}
		mode || setretval();
	}
}

function checkalbum(e){
	e = e || event;
	var k, a = e.target || e.srcElement;
	if(a.tagName.toLowerCase() == 'input' && a.name && a.name.substr(0,8) == 'selectid'){
		a.blur();
		if(a.checked){
			if(!selecteddoms[a.value]){
				if(!mode){
					res_count = 0;
					for(k in retval)delete retval[k];
				}
				if(res_count >= res_limit){
					a.checked = false;
					alert(lang('out_num_limit', res_limit));
					return;
				}
				retval[a.value] = a.parentNode.parentNode.cells[1].getElementsByTagName('a')[0].innerHTML;
				selecteddoms[a.value] = [a];
				setcheckalbum(a.value);
			}
		}else if(retval[a.value]){
			delete retval[a.value];
			selectedalbum.removeChild(selecteddoms[a.value][1]);
			delete selecteddoms[a.value];
			res_count--;
			if(res_count < 0)res_count = 0;
		}
		mode || setretval();
	}
}

function setcheckalbum(k){
	if(!selecteddoms[k])selecteddoms[k] = [];
	if(selecteddoms[k][1])return;
	var b = document.createElement('a');
	selecteddoms[k][1] = b;
	b.innerHTML = retval[k];
	b.onclick = function(){
		if(selecteddoms[k][0])selecteddoms[k][0].checked = false;
		selectedalbum.removeChild(b);
		delete retval[k];
		delete selecteddoms[k];
		res_count--;
		if(res_count < 0)res_count = 0;
	};
	selectedalbum.appendChild(b);
	res_count++;
}

var hash = {}, selecteddoms = {}, res_count =0, selectedalbum;
function initcata(){
	window.App = 'cata';
	var i, v, a = document.getElementsByTagName('a');
	for(i = 0; i < a.length; i++)if(v = a[i].getAttribute('rev'))hash[v] = a[i].title = a[i].innerHTML;
	a = retval;retval = {};
	for(i = 0; i < a.length; i++)if(hash[a[i]]){retval[a[i]] = hash[a[i]];res_count++}
	document.onclick = checkcata;
	var d = document.createElement('div');
	with(d.style){
//		display = 'none';
		width = '0px';
		height = '0px'
	}
	d.className = 'selecting';
	var h = document.createElement('div');
	h.className = 'rlsbg';
	d.appendChild(h);
	document.body.appendChild(d);
	$id('loading').style.display = 'none';
	$id('content').style.display = '';
	if($id('btn_ok'))$id('btn_ok').style.display = '';
}
function initalbum(){
	window.App = 'album';
	var i, v, a = document.getElementsByTagName('input');
	mode = 1;
	retval = $WE.elements[field_id]._tmp;
	selectedalbum = $id('selectedalbum');
	for(i = 0; i < a.length; i++)if(a[i].name && a[i].name.substr(0,8) == 'selectid' && retval[a[i].value]){a[i].checked = true;selecteddoms[a[i].value] = [a[i]]}
	for(i in retval)setcheckalbum(i);
	document.onclick = checkalbum;
	var d = document.createElement('div');
	with(d.style){
//		display = 'none';
		width = '0px';
		height = '0px'
	}
	d.className = 'selecting';
	document.body.appendChild(d);
	$id('loading').style.display = 'none';
	$id('content').style.display = '';
	if($id('btn_ok'))$id('btn_ok').style.display = '';
}
