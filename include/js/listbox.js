function make_mbox(dox, field, data, def, mode, notip, layout){//mode可以选几个
	var mbox = [], B = [], Z = [], X = 0, lnk = {0:{B:B,X:0}}, xbox, box, dov, i, l, x;
	function showInfo(){
		var id = field + '_' + xbox.count + '_' + (new Date).getTime();
		box = document.createElement('INPUT');
		box.type = 'checkbox';
		box.id = id;
		box.title = xbox.text;
		box.value = xbox.value;
		box.onclick = function(){
			delete xbox.vals[this.value];
			xbox.count--;
			makeResult();
			dov.removeChild(this.T);
			dov.removeChild(this.L);
			dov.removeChild(this);
		};
		dov.appendChild(box);
		box.checked = true;
		box.L = document.createElement('LABEL');
		box.L.setAttribute('for', box.L.htmlFor = id);
		box.L.title = xbox.text;
		box.L.appendChild(document.createTextNode(xbox.text));
		dov.appendChild(box.L);
		dov.appendChild(box.T = layout ? document.createElement('BR') : document.createTextNode(' '));
		xbox.vals[xbox.value] = 1;
		xbox.count++;
		makeResult();
	}
	function setItem(B, box){
		var c = def.pop(), x = box.options.length = 1, z = 0;
		for(i = 0, l = B.length; i < l; i++){
			if(c == B[i].A[0])z = i + 1;
			box.options[x] = new Option(B[i].A[2], B[i].A[3] ? '' : B[i].A[0]);
			box.options[x++].B = B[i].B;
		}
		box.selectedIndex = z;
		z && box.onchange();
	}
	function makeResult(){
		var z = [];
		for(x in xbox.vals)z.push(x);
		xbox.box.value = z.length ? ',' + z.join(',') + ',' : '';
	}
	function guid(){
		return '__' + (new Date).getTime() + Math.random().toString().substr(2);
	}
	if(!dox){
		dox = guid();
		document.write('<span id="'+dox+'"></span>');
		i = document.getElementById(dox);
		dox = i.parentNode;
		dox.removeChild(i);
	}else if(typeof dox == 'string')
		dox = document.getElementById(dox);
	if(!dox)return;
	if(dox.xbox){
		xbox = dox.xbox;
	}else{
		dox.xbox = xbox = {box : box = document.createElement('INPUT'), vals : {}, count : 1};
		box.type = 'hidden';
		box.name = box.id = field;
		dox.appendChild(box);
	}
	if(typeof data == 'string'){
		i = guid();
		x = document.createElement('SCRIPT');
		x.type = 'text/javascript';
		x.src = CMS_ABS + 'tools/ajax.php?varname=' + i + '&' + data;
		document.getElementsByTagName('HEAD')[0].appendChild(x);
		X = arguments.callee;
		x = setInterval(function(){
			if(window[i]){
				clearInterval(x);
				X(dox, field, window[i], def, mode,notip, layout);
			}
		}, 50);
		return;
	}
//format data and find default list
	for(i = 0, l = data.length; i < l; i++){
		if(x = data[i])
			if(x[1] in lnk)
				lnk[x[1]].B.push(lnk[x[0]] = {A:x,B:[],X:lnk[x[1]].X+1});
	}
	!function(B){
		for(var i = 0, l = B.length; i < l; i++){
			B[i].B.length && arguments.callee(B[i].B);
			if(B[i].A[3] && !B[i].B.length){
				delete lnk[B[i].A[0]];
				B.splice(i--, 1);
				l--;
			}
		}
	}(B);
	for(i in lnk)if(X < lnk[i].X)X = lnk[i].X;
	if(!X)return;
	for(i = 0; i < X; i++){
		dox.appendChild(mbox[i] = document.createElement('SELECT'));
		dox.appendChild(layout ? document.createElement('BR') : document.createTextNode(' '));
		mbox[i].disabled = true;
		mbox[i].options[0] = new Option(lang('choose'), '');
		mbox[i].onchange = function(x){
			return function(){
				i = mbox[x].options[mbox[x].selectedIndex];
				xbox.value = i.value || x == 0 ? i.value : mbox[x - 1].options[mbox[x - 1].selectedIndex].value;
				xbox.text = i.value ? i.text : !x == 0 ? mbox[x - 1].options[mbox[x - 1].selectedIndex].text : '';
				if(!mode){
					if(empty(xbox.box.value = xbox.value) && (x != 0 || mbox[x].selectedIndex))
						xbox.alert.lastChild || xbox.alert.appendChild(document.createTextNode(lang('choose_again')));
					else
						xbox.alert.lastChild && xbox.alert.removeChild(xbox.alert.lastChild);
					xbox.box._callback && xbox.box._callback();
				}
				if(!(box = mbox[x+1]))return;
				B = i.B;
				for(i = x+1; i < mbox.length; i++){
					mbox[i].disabled = i > x+1 || !this.selectedIndex || !B || !B.length;
					mbox[i].options.length = 1;
					mbox[i].selectedIndex = 0;
				}
				if(!B || !B.length)return;
				setItem(B, box);
			}
		}(i);
	}
	if(mode){
		box = document.createElement('INPUT');
		box.type = 'button';
		box.value = lang('chooseto');
		box.onclick = function(){
			if(xbox.count > mode)return alert(lang('out_num_limit', mode));
			if(!xbox.text)xbox.value = '';
			if(!xbox.value)return alert(lang('choose_again'));
			if(xbox.value in xbox.vals)
				return alert(lang('result_exists'));
			showInfo();
			xbox.box._callback && xbox.box._callback();
		};
		dox.appendChild(box);
		dox.appendChild(dov = document.createElement('DIV'));
	}
	if(!empty(def)){
		if(mode){
			x = def.toString().split(',');
			def = [];
			for(i = 0, l = x.length; i < l; i++)if(!empty(x[i]) && x[i] in lnk)def.push(x[i]);
			for(i = 0, l = def.length; i < l; i++){
				xbox.value = def[i];
				xbox.text = lnk[def[i]].A[2];
				showInfo();
			}
			def = def.length ? [x = def[l-1]] : [];
		}else{
			def = [x = def];
		}
		if(x in lnk){
			while(x = lnk[x].A[1])def.push(x);
		}else{
			def = [];
		}
	}else{
		def = [];
	}
	if(!mode && !notip){
		xbox.alert = document.createElement('DIV');
		xbox.alert.style.color = 'red';
		dox.appendChild(xbox.alert);
	}else xbox.alert = {};
	box = mbox[0];
	box.disabled = false;
	setItem(B, box);
	return xbox.box;
}
