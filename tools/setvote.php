<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT."./include/cheader.inc.php";
load_cache('alangs');
$langs = &$alangs;
empty($mode) && $mode = '';
(empty($chid) || empty($field)) && exit();
$field = read_cache($mode.'field', $chid, $field);
$settings = "{
	count : $field[min],
	items : $field[max]
}";
_header();
?>
<script type="text/javascript">
function addOption(title, votenum){
	var index = stack.options.length, i, option, vieworder;
	if(!title && index >= settings.items){
		alert(lang('vote_max_items'));
		return false;
	}
	if(!voteOption){
		voteOption = document.getElementById('voteOption');
		voteOption.table = voteOption.parentNode;
		voteOption.table.removeChild(voteOption);
		voteOption.style.display = '';
	}
	option = voteOption.cloneNode(true);
	voteOption.table.appendChild(option);
	option.item = option.getElementsByTagName('INPUT');
	option.item.title.value = title || '';
	option.item.votenum.value = votenum || 0;
	option.item.votenum.name = '';
	stack.options[option.index = index] = option;
}

function saveVote(form){
	var i, k, add, date, field;
	var options = [];
	var key = obj.key + '[' + obj.fid + ']';
	if(!voteForm || obj.fid == undefined)return false;
	try{opener._08cms.stack}catch(e){return false}
	for(i = 0; i < stack.options.length; i++){
		add = stack.options[i].item.title.value;
		empty(add) || options.push(add);
	}
	if(empty(voteForm.subject.value) || empty(voteForm.content.value) || options.length < 2){
		alert(lang('vote_info_miss'));
		return false;
	}

//set or add subject
	if(obj.addnew){
		add = obj.btn;
		while(add && (add.nodeType != 1 || add.getAttribute('vote') != 'item'))add = add.previousSibling;
		if(!add){
			field = opener.document.createElement('DIV');
			field.innerHTML = '<div vote="item" style="display:none"><span vote=\"subject\">{subject}</span>&nbsp;'
				+ '[<a href="javascript://" onclick="_08cms.vote.editVote(this,\'' + obj.key + '\',\'' + obj.chid + '\',\'' + obj.mode + '\',{index})"><?=lang('edit')?></a>]&nbsp;'
				+ '[<a href="javascript://" onclick="_08cms.vote.delVote(this,\'' + obj.key + '\',{index})"><?=lang('delete')?></a>]</div>';
			add = field.firstChild;
		}
		add = add.cloneNode(true);
		add.style.display = '';
		try{
			add.removeAttribute('vote');
		}catch(e){
			add.setAttribute('vote', null);
		}
		add.innerHTML = add.innerHTML.replace(/\{index\}/g, obj.fid).replace(/\{subject\}/g, voteForm.subject.value.replace(/&|<|"/g, function(a){return a=='&' ? '&amp;' : a=='<' ? '&lt;' : '&quot;'}));
		add.id = key;
		obj.btn.parentNode.insertBefore(add, obj.btn);
		add = add.lastChild;
	}else{
		add = obj.btn;
	}
	while(add && (add.nodeType != 1 || add.getAttribute('vote') != 'subject'))add = add.previousSibling;
	if(add)add.innerHTML = voteForm.subject.value.replace(/&|<|"/g, function(a){return a=='&' ? '&amp;' : a=='<' ? '&lt;' : '&quot;'});

//create hidden fields
	for(i = voteForm.elements.length - 1; i >= 0; i--){
		field = voteForm.elements[i];
		field.value = trim(field.value);
		if(field.name && field.name != 'title'){
			if(!(add = stack.elements[field.name])){
				add = stack.elements[field.name] = opener.document.createElement('INPUT');
				add.type = 'hidden';
				add.name = key + '[' + field.name + ']';
				obj.form.appendChild(add);
			}
			switch(field.name){
			case 'ismulti':
				add.value = voteForm.ismulti[0].checked ? 1 : 0;
				break;
			case 'enddate':
				date = 0;
				if(k = field.value.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/)){
					date = new Date();
					date.setTime(0);
					date.setFullYear(k[1]);
					date.setMonth(parseInt(k[2]) - 1);
					date.setDate(k[3]);
					date = Math.floor(date.getTime() / 1000);
				}
				add.value = date;
				break;
			default:
				add.value = field.value;
			}
		}
	}
	for(i = 0; i < options.length; i++){
		if(!empty(options[i])){
			field = key + '[options][' + i + '][title]';
			if(add =  stack.elements.options[i]){
				add = add.title;
			}else{
				add = opener.document.createElement('INPUT');
				add.type = 'hidden';
				add.name = field;
				obj.form.appendChild(add);
			}
			add.value = options[i];
		}
	}
	options = stack.elements.options;
	while(i < options.length){
		for(k in options[i])options[i][k].parentNode.removeChild(options[i][k]);
		i++;
	}
	window.close();
}

function moveUp(a){
	var c, i;
	if(a = getOption(a)){
		if(a.index == 0)return;
		i = stack.options[a.index - 1];
		stack.options[a.index--] = i;
		stack.options[i.index++] = a;
		i.parentNode.insertBefore(a, i);
//		if(a.index == 1){
//		}else{
//		}
	}
}

function moveDown(a){
	if(a = getOption(a)){
		if(a.index == stack.options.length - 1)return;
		var i = stack.options[a.index + 1];
		stack.options[a.index++] = i;
		stack.options[i.index--] = a;
		i.parentNode.removeChild(i);
		a.parentNode.insertBefore(i, a);
//		if(a.index == stack.options.length - 2){
//		}else{
//		}
	}
}

function delOption(a){
	if(a = getOption(a)){
		if(confirm(lang('vote_confirm_delete', a.index + 1))){
			a.parentNode.removeChild(a);
			stack.options.splice(a.index, 1);
			for(var i = a.index; i < stack.options.length; i++)stack.options[i].index = i;
		}
	}
}

function getOption(a){
	while(a && a.tagName != 'TR')a = a.parentNode;
	return a;
}

window.onload = function(){
	var key, len, options, field, name, date, i;
	voteForm = document.forms.voteDetail;
	if(!voteForm || !obj){
		alert(lang('not_find_form'));
		window.close();
		return;
	}
	if(obj.fid == undefined){
		key = obj.key;
		len = key.length;
		obj.fid = -1;
		options = {};
		for(i = obj.form.elements.length - 1; i >= 0; i--){
			field = obj.form.elements[i];
			if(field.name && field.name.slice(0, len) == key){
				name = field.name.slice(len).match(/^\[(\d+)\]/);
				if(name){
					options[name[1]] = true;
					if(parseInt(name[1]) > obj.fid)obj.fid = parseInt(name[1]);
				}
			}
		}
		len = 0;
		for(i in options)len++;
		if(len >= settings.count){
			alert(lang('vote_max_count', settings.count));
			window.close();
		}
		obj.fid++;
		obj.addnew = true;
	}else{
		key = obj.key + '[' + obj.fid + ']';
		len = key.length;
		options = [];
		for(i = obj.form.elements.length - 1; i >= 0; i--){
			field = obj.form.elements[i];
			if(field.name && field.name.slice(0, len) == key){
				name = field.name.slice(len).match(/^\[(\w+)\](?:\[(\d+)\](?:\[(\w+)\])?)?$/);
				if(!name)continue;
				switch(name[1]){
				case 'ismulti':
					voteForm.ismulti[0].checked = field.value == '1';
					break;
				case 'enddate':
					if(!empty(field.value) && field.value.match(/^\d+$/)){
						date = new Date();
						date.setTime(parseInt(field.value) * 1000);
						voteForm.enddate.value = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
					}
					break;
				case 'options':
					if(!options[name[2]]){
						options[name[2]] = {};
						stack.elements.options[name[2]] = {};
					}
					options[name[2]][name[3]] = field.value;
					stack.elements.options[name[2]][name[3]] = field;
					continue;
				default:
					if(voteForm[name[1]])voteForm[name[1]].value = field.value;
				}
				stack.elements[name[1]] = field;
			}
		}
		for(i = 0; i < options.length; i++)addOption(options[i].title, options[i].votenum);
	}
	window.focus();
};

var stack = '<?=$stack?>', _08cms = opener._08cms, obj = _08cms.stack.obj[stack];
var stack = {
	options : [],
	elements : {
		options : []
	}
},voteForm, voteOption, undefined;
var settings = <?=$settings?>;
setInterval(function(){try{opener._08cms.stack}catch(e){window.close()}}, 50);//When the opener refresh
</script>
	<form name="voteDetail" method="get" onsubmit="saveVote(this);return false">
		<table border="0" cellpadding="0" cellspacing="1" class="tabmain">
			<tr class="header">
				<td colspan="2"><b><?=lang('editvote')?></b></td>
			</tr>
			<tr>
				<td width="25%" class="item1"><b><?=lang('votetitle')?></b></td>
				<td class="item2"><input type="text" size="25" id="subject" name="subject" value="">
					<div id="alert_subject" name="alert_subject" class="red"></div></td>
			</tr>
			<tr>
				<td width="25%" class="item1"><b><?=lang('voteexpl')?></b></td>
				<td class="item2"><textarea rows="4" name="content" id="content" cols="60"></textarea>
					<div id="alert_content" name="alert_content" class="red"></div></td>
			</tr>
			<tr>
				<td width="25%" class="item1"><b><?=lang('voteenddate')?></b></td>
				<td class="item2"><input type="text" size="15" id="enddate" name="enddate" value="" onclick="ShowCalendar(this.id);">
					<div id="alert_enddate" name="alert_enddate" class="red"></div></td>
			</tr>
			<tr>
				<td width="25%" class="item1"><?=lang('weathermsel')?></td>
				<td class="item2"><input id="ismulti1" name="ismulti" value="1" type="radio" class="radio" />
					<label for="ismulti1"><?=lang('yes')?></label> &nbsp; &nbsp;
					<input id="ismulti0" name="ismulti" value="0" checked="checked" type="radio" class="radio" />
					<label for="ismulti0"><?=lang('no')?></label></td>
			</tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="1" class="tabmain">
			<tr class="header">
				<td colspan="4"><b><?=lang('voteoption')?> -- >> <a href="javascript://" onclick="addOption();"><?=lang('addvoteopt')?></a></b></td>
			</tr>
			<tr class="category" align="center">
				<td class="item2"><?=lang('optiontitle')?></td>
				<td class="item2"><?=lang('votenum')?></td>
				<td><?=lang('order')?></td>
				<td><?=lang('delete')?></td>
			</tr>
			<tr id="voteOption" style="display:none">
				<td class="item2"><input size="40" name="title" /></td>
				<td class="item2"><input size="10" name="votenum" disabled="disabled" /></td>
				<td><a href="javascript://" onclick="moveUp(this)"><?=lang('moveup')?></a> <a href="javascript://" onclick="moveDown(this)"><?=lang('movedown')?></a></td>
				<td><a href="javascript://" onclick="delOption(this)"><?=lang('delete')?></a></td>
			</tr>
		</table>
		<br />
		<br />
		<input class="submit" type="submit" value="  <?=lang('confirm')?>  ">
	</form>
</div>
</body>
</html>