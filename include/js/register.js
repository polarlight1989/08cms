var passinfo={};
function $id(d){return typeof d == 'string' ? document.getElementById(d) : d}
Array.prototype.find = function(need, del){
	var ret;
	for(var i =0; i < this.length; i++)
		if(this[i] == need){
			ret = true;
			break;
		}
	if(ret && del)this.splice(i, 1);
	return ret;
};

function check_cmsregister_submit(f){
	var i,k;
	clearalerts(f);
	i=checkChannel(f);
	//if(i&&passinfo['code']&&passinfo['mname']&&passinfo['pwd']&&passinfo['pwd2']&&passinfo['email'])return true;
	if(!i){
		alert(lang('check_field'));
		return false;
	}
}

var lastregcode = lastmname = lastpassword = lastemail = null;
function checkregcode(obj){
	var regcode = obj.value;
	if(regcode === lastregcode) {
		return;
	} else {
		lastregcode = regcode;
	}
	if(!(/\w{4}/.test(regcode)))return warning(obj,lang('code_e1'));
	ajaxresponse(obj, 'action=checkregcode&regcode=' + regcode,'code');
}

function checkmname(obj) {
	var mname = trim(obj.value);
	if(mname === lastmname) {return;}
	else {lastmname = mname;}
	var unlen = strlen(mname);

	if(unlen < 3 || unlen > 15)return warning(obj, lang(unlen < 3 ? 'uname_low' : 'uname_hgh'));
	var x = new Ajax('XML');
	x.form('register.php?inajax=1',{'action':'checkmname','mname':mname}, function(s){ajaxresult(s, obj, 'mname')});
}

function checkpassword(obj, confirm){
	var pwd = trim(obj.value);
	if(!confirm && pwd === lastpassword) {
		return;
	} else {
		lastpassword = pwd;
	}
	if(pwd == '' || (/[\'\"\\]/.test(pwd))) {
		warning(obj, lang('pwd_e1'));
		passinfo['pwd']=0;
		return false;
	} else {
		var cp = $id('alert_password');
		cp.style.display = 'none';
		if(!confirm)checkpassword2(obj.form.password2, true);
		passinfo['pwd']=1;
		return true;
	}
}

function checkpassword2(obj, confirm){
	var obj2 = obj.form.password, pwd1 = trim(obj.value), pwd2 = trim(obj2.value), cp2 = $id('alert_password2');
	if(pwd2 != '')checkpassword(obj2, true);
	if(pwd1 == '' || (confirm && pwd2 == '')){
		cp2.style.display = 'none';
		passinfo['pwd2']=0;
		return;
	}
	if(pwd1 != pwd2) {
		warning(obj, lang('pwd_e2'));
		passinfo['pwd2']=0;
	} else {
		cp2.style.display = 'none';
		passinfo['pwd2']=1;
	}
}

function checkemail(obj) {
	var email = trim(obj.value);
	if(email === lastemail) {
		return;
	} else {
		lastemail = email;
	}
	if(!(/^[\-\.\w]+@[\.\-\w]+(\.\w+)+$/.test(email))) {
		warning(obj, lang('email_err'));
		passinfo['email']=0;
		return;
	}else{
		var ce = $id('alert_email');
		ce.style.display = 'none';
		passinfo['email']=1;
	}
}
function warning(obj, msg){
	var ton = obj.id;
	ton == 'password2' && (obj = $id('password'));
	obj.select();
	obj = $id('alert_' + ton);
	obj.style.display = '';
	obj.innerHTML = '<img src="images/default/check_error.gif" width="13" height="13" /> &nbsp; ' + msg;
	obj.className = "warning";
}

function ajaxresponse(obj, data, key) {
	var x = new Ajax('XML');
	x.get('register.php?inajax=1&' + data, function(s){ajaxresult(s, obj, key)});
}

function ajaxresult(s, o, k){
	if(s == 'succeed') {
		passinfo[k]=1;
		o = $id('alert_' + o.id);
		o.style.display = '';
		o.innerHTML = '<img src="images/default/check_right.gif" width="13" height="13" />';
		o.className = "warning";
	} else {
		passinfo[k]=0;
		warning(o, s);
	}
}

var fields = ['mname','password','password2','email','regcode'], initTimer = setInterval(function(){
	if(!fields.length)return clearInterval(initTimer);
	var e;
	while(e = $id(fields[0])){
		fields.shift();
		e.onblur = function(){return window['check' + this.id](this)}
	}
},50);
window.onload = function(){setTimeout(function(){clearInterval(initTimer)},1000)};
