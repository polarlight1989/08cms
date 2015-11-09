function redirect(url) {
	window.location.replace(url);
}
function $(id) {
	return document.getElementById(id);
}
function infos(str){
	$('infos').style.display = 'block';
	$('infos').innerHTML = str;
}