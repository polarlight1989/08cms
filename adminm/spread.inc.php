<?php
!defined('M_COM') && exit('No Permission');
if(!($commu = read_cache('commu',9)) || empty($commu['available']))if($inajax){print(lang('spread_closed'));mcfooter();}else mcmessage(lang('spread_closed'));
load_cache('currencys');?>
<script type="text/javascript" reload="1">
function copyToClipboard(txt){
	if(window.clipboardData){
		window.clipboardData.clearData();
		window.clipboardData.setData("Text", txt);
	}else if (window.netscape){
		try{
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		}catch (e){
			return false;
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip)return false;
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans)return false;
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext = txt;
		str.data = copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip)return false;
		clip.setData(trans,null,clipid.kGlobalClipboard);
	}else{
		return false;
	}
	alert("copy_ok");
	return false
}
</script><?php
$idx = "$cms_abs?uid={$curuser->info['mname']}";?>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class=" tb tb2 bdbot">
	<tr>
		<td align="left"><b style="color:red"><?=lang('spread_firend_show')?></b> <br />
			<a href="<?=$idx?>" onclick="return copyToClipboard(this.href);"><?=$idx?></a> (<b><?=lang('click_and_copy')?></b>)<?php
if(!empty($commu['setting'][0]['value'])){?>
			<div><?=lang('spread_award', lang('visitor'), $commu['setting'][0]['value'], $currencys[$commu['setting'][0]['crid']]['cname'])?></div><?php
}
$reg = "{$cms_abs}register.php?uid={$curuser->info['mname']}";?>
		</td>
	</tr>
	<tr>
		<td align="left"><b style="color:red"><?=lang('spread_firend_club')?></b> <br />
			<a href="<?=$reg?>" onclick="return copyToClipboard(this.href);"><?=$reg?></a> (<b><?=lang('click_and_copy')?></b>)<?php
if(!empty($commu['setting'][1]['value'])){?>
			<div><?=lang('spread_award', lang('member').lang('register'), $commu['setting'][1]['value'], $currencys[$commu['setting'][1]['crid']]['cname'])?></div><?php
}?>
		</td>
	</tr>
</table>
<div class="blank9"></div>