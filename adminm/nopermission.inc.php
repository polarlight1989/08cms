<?php
!defined('M_COM') && exit('No Permission');
if(!$memberid){
	$message_class = 'curbox';
	echo '<div class="area col"><div class="conBox"><div class="con_con"><div class="main_area">';
	empty($handlekey) && $handlekey = '';
	$tmp=empty($infloat)?'':" onclick=\"floatwin('close_$handlekey');return floatwin('open_login',this)\"";
	mcmessage('loginmemcenter','',' [<a href="login.php"'.$tmp.'>'.lang('memberlogin').'</a>] [<a href="register.php" target="_blank">'.lang('register').'</a>]');
}elseif($curuser->info['isfounder']){
	mcmessage('foundernomc','','[<a href="login.php?action=logout">'.lang('logout').'</a>]');
}
?>