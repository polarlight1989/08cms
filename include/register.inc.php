<?php
!defined('M_COM') && exit('No Permission');
include_once M_ROOT.'./include/cheader.inc.php';
load_cache('mtconfigs');
_header(lang('register'));
echo'<script type="text/javascript" src="include/js/register.js"></script>';
if(empty($mchid)){
	$num = 4;
	mtabheader(lang('choosememberchannel'),'','',$num);
	$i = 0;
	foreach($mchannels as $k => $v){
		if(!$v['userforbidadd']){
			if(!isset($tmchid))$tmchid=$k;
			if(!($i % $num)) echo "<tr>";
			echo "<td class=\"item2\" width=\"25%\"><input type=\"radio\" name=\"mchid\" id=\"mchid$k\" onclick=\"".(!empty($infloat)?"floatwin('update_$handlekey','?mchid=$k')":"location.href='?mchid=$k'")."\" value=\"$k\" style=\"border:none\" /><label for=\"mchid$k\">$v[cname]</label>\n";
			$i++;
			if(!($i % $num)) echo "</tr>\n";
		}
	}
	if($i % $num){
		while($i % $num){
			echo "<td class=\"item2\" width=\"25%\"></td>\n";
			$i++;
		}
		echo "</tr>\n";
	}
	mtabfooter();
}else{
	$mchannel = $mchannels[$mchid];
	$mfields = read_cache('mfields',$mchid);
	foreach(array('ccoids','additems') as $var) $$var = $mchannel[$var] ? explode(',',$mchannel[$var]) : array();

	mtabheader(lang('newreg'),'cmsregister',"?mchid=$mchid&forward=".rawurlencode($forward),2,1,1);
	$muststr = '<span style="color:red">*</span>';
	$submitstr = mtr_regcode('register') ? '' : "passinfo['code']=1;\n";
	mtrbasic($muststr.lang('membercname'),'mname');
	mtrbasic($muststr.lang('password'),'password','','password');
	mtrbasic($muststr.lang('repwd'),'password2','','password');
	mtrbasic($muststr.lang('email'),'email');

	$submitstr = "function checkChannel(form){\nvar i = true;\n$submitstr";
	if(in_array('mtcid',$additems)){
		mtrbasic(lang('spacetemplateproject'),'mtcid',makeoption(mtcidsarr($mchid)),'select');
	}
	if(in_array('caid',$ccoids) && in_array('caid',$additems)){
		$catalogs = &$acatalogs;
		mtrcns($muststr.lang('memberrelatecatalog'),'caid',0,-1,0,$mchid,1,lang('p_choose'));
		$submitstr .= makesubmitstr('caid',1,0,0,0,'common');
	}
	foreach($cotypes as $k => $v){
		if(in_array('ccid'.$k,$ccoids) && in_array('ccid'.$k,$additems)){
			mtrcns($muststr.lang('memberrelatecoclass').'&nbsp; -&nbsp; '.$v['cname'],"ccid$k",0,-1,$k,$mchid,1,lang('p_choose'));
			$submitstr .= makesubmitstr("ccid$k",1,0,0,0,'common');
		}
	}
	foreach($grouptypes as $k => $v){
		if(!$v['mode'] && !in_array($mchid,explode(',',$v['mchids'])) && in_array("grouptype$k",$additems)){
			mtrbasic($v['cname'],'grouptype'.$k,makeoption(ugidsarr($k,$mchid)),'select');
		}
	}
	$a_field = new cls_field;
	foreach($mfields as $k => $field){
		if(!$upload_nouser && in_array($field['datatype'],array('image','images','flash','flashs','media','medias','file','files')))continue;
		if(!$field['issystem'] && !$field['isfunc'] && !$field['isadmin'] && in_array($k,$additems)){
			$a_field->init(1);
			$a_field->field = read_cache('mfield',$mchid,$k);
			if($curuser->pmbypmids('field',$a_field->field['pmid'])){//字段附加权限设置
				$a_field->isadd = 1;
				$a_field->trfield();
				$submitstr .= $a_field->submitstr;
			}
		}
	}
	mtabfooter();
	$submitstr .= "return i}\n";
	echo '<input class="button" type="submit" name="register" value="'.lang('register').'"></form>'.
		"<script type=\"text/javascript\" language=\"javascript\" reload=\"1\">\n$submitstr</script>";
}
_footer();
?>