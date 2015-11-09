<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
load_cache('cmsinfos');
$updatetime = @filemtime(M_ROOT.'./dynamic/cache/cmsinfos.cac.php');
load_cache('cmsinfos');
$lic_file = M_ROOT.'./dynamic/license.cert';
$now_svr = strtolower($_SERVER["SERVER_NAME"]);
$opsarr = array(
'ck' => "checked='1'",
'nock' => "checked='0'",
'm' => "createdate>'".($timestamp-30*24*3600)."'",
'w' => "createdate>'".($timestamp-7*24*3600)."'",
'd3' => "createdate>'".($timestamp-3*24*3600)."'",
'd1' => "createdate>'".($timestamp-24*3600)."'",
);
$tblarr = array(
'archive' => 'archives',
'comment' => 'comments',
'reply' => 'replys',
'offer' => 'offers',
'answer' => 'answers',
'orders' => 'orders',
'member' => 'members',
'amember' => 'members',
'mtran' => 'mtrans',
'utran' => 'utrans',
'mcomment' => 'mcomments',
'mreply' => 'mreplys',
);
if($timestamp - $updatetime > 3600 * 4){
	$cmsinfos['dbversion'] = $db->result_one("SELECT VERSION()");
	$cmsinfos['dbsize'] = 0;
	$query = $db->query("SHOW TABLE STATUS LIKE '$tblprefix%'", 'SILENT');
	while($table = $db->fetch_array($query)) {
		$cmsinfos['dbsize'] += $table['Data_length'] + $table['Index_length'];
	}
	$cmsinfos['dbsize'] = $cmsinfos['dbsize'] ? sizecount($cmsinfos['dbsize']) : lang('unknow');
	$cmsinfos['attachsize'] = $db->result_one("SELECT SUM(size) FROM {$tblprefix}userfiles");
	$cmsinfos['attachsize'] = is_numeric($cmsinfos['attachsize']) ? sizecount($cmsinfos['attachsize']) : lang('unknow');
	$cmsinfos['sys_mail'] = @ini_get('sendmail_path') ? 'Unix Sendmail ( Path: '.@ini_get('sendmail_path').')' : (@ini_get('SMTP') ? 'SMTP ( Server: '.ini_get('SMTP').')' : 'Disabled');
	$cmsinfos['serverip'] = $_SERVER["SERVER_ADDR"];
	$cmsinfos['servername'] = $_SERVER["SERVER_NAME"];
	foreach($tblarr as $k => $v){
		foreach($opsarr as $x => $y){
			if($k == 'orders'){
				$x == 'ck' && $y = "state='1'";
				$x == 'nock' && $y = "state<>'1'";
			}elseif($k == 'member'){
				$x == 'm' && $y = "regdate>'".($timestamp-30*24*3600)."'";
				$x == 'w' && $y = "regdate>'".($timestamp-7*24*3600)."'";
				$x == 'd3' && $y = "regdate>'".($timestamp-3*24*3600)."'";
				$x == 'd1' && $y = "regdate>'".($timestamp-24*3600)."'";
			}elseif($k == 'amember'){
				$x == 'ck' && $y = "grouptype2<>0 AND checked='1'";
				$x == 'nock' && $y = "grouptype2<>0 AND checked='0'";
				$x == 'm' && $y = "grouptype2<>0 AND regdate>'".($timestamp-30*24*3600)."'";
				$x == 'w' && $y = "grouptype2<>0 AND regdate>'".($timestamp-7*24*3600)."'";
				$x == 'd3' && $y = "grouptype2<>0 AND regdate>'".($timestamp-3*24*3600)."'";
				$x == 'd1' && $y = "grouptype2<>0 AND regdate>'".($timestamp-24*3600)."'";
			}
			$cmsinfos[$k][$x] = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}$v WHERE $y");
		}
	}
	$cmsinfos['lic_str'] = '';
	if($lic_infos = @file($lic_file)){
		$lic_infos = array_filter($lic_infos);
		$cmsinfos['lic_str'] = str_replace(array("\r","\n"),'',implode('-',$lic_infos));
	}
	if(!preg_match("/[\d\.]{7,15}/",$now_svr)){
		$upstr = '';
		$cms_up_url = $cms_upurl;
		$updates = array('mcharset' => $mcharset,'cms_version' => $cms_version,'lan_version' => $lan_version,'server_name' => $now_svr,'cmsname' => $cmsname,'lic_str' => $cmsinfos['lic_str']);
		foreach($updates as $k => $v){
			$upstr .= $k.'='.rawurlencode($v).'&';
		}
		$cms_up_url .= '/upmsg.php?update='.rawurlencode(base64_encode($upstr)).'&md5sign='.substr(md5($_SERVER['HTTP_USER_AGENT'].$upstr.$timestamp),8,8).'&uptime='.$timestamp;
		unset($updates,$upstr);
	}
	cache2file($cmsinfos,'cmsinfos');
}
$archivestr = $memberstr = '';
foreach($tblarr as $k => $v){
	$var = in_array($k,array('archive','comment','reply','answer','orders','offer',)) ? 'archivestr' : 'memberstr';
	$$var .= '<tr><td class="bgc_E7F5FE fB">'.lang($k).'</td><td class="bgc_FFFFFF">'.@$cmsinfos[$k]['ck'].'</td><td class="bgc_FFFFFF">'.@$cmsinfos[$k]['nock'].'</td><td class="bgc_FFFFFF">'.@$cmsinfos[$k]['m'].'</td><td class="bgc_FFFFFF">'.@$cmsinfos[$k]['w'].'</td><td class="bgc_FFFFFF">'.@$cmsinfos[$k]['d3'].'</td><td class="bgc_FFFFFF">'.@$cmsinfos[$k]['d1'].'</td></tr>';
}
$lic_str = empty($cmsinfos['lic_str']) ? lang('lic_uk') . ' <a href="http://www.08cms.com" target="_blank" class="cRed">' . lang('lic_by') . '</a>' : lang('lic_no') . $cmsinfos['lic_str'].'<a href="http://www.08cms.com" target="_blank" class="cRed">' . lang('lic_ck') . '</a>';
$cmsinfos['server'] = PHP_OS.'/PHP '.PHP_VERSION;
$cmsinfos['safe_mode'] = @ini_get('safe_mode') ? 'ON' : 'OFF';
$cmsinfos['max_upload'] = @ini_get('upload_max_filesize') ? @ini_get('upload_max_filesize') : 'Disabled';
$cmsinfos['allow_url_fopen'] = @ini_get('allow_url_fopen') ? "YES" : "NO";
$cmsinfos['gdpic'] = (function_exists("imagealphablending") && function_exists("imagecreatefromjpeg") && function_exists("ImageJpeg")) ? 'YES' : 'NO';
$cmsinfos['servertime'] = date("Y-m-d  H:i");

$gid = $curuser->info['grouptype2'];
$group = read_cache('usergroups', 2);
$group = $gid && isset($group[$gid]) ? $group[$gid]['cname'] : lang('unknow');


function show_tip($key){
	if(@include(M_ROOT.'./dynamic/aguides/'.$key.'.php'))echo $aguide;
}
$registeropenstr = $registerclosed ? lang('closed') : lang('enable');
$mspaceopenstr = $mspacedisabled ? lang('closed') : lang('enable');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>">
<style type="text/css">
/* resett.css
>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
body {text-align:center;margin:0;padding:0;background:#FFF;font-size:12px;color:#000;}
div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,button,textarea,p,blockquote,th,td{margin:0;padding:0;border:0;}
ul,li{list-style-type:none;}
img{vertical-align:top; border:0;}
strong{font-weight:normal;}
em{font-style:normal;}
h1,h2,h3,h4,h5,h6{margin:0;padding:0;font-size:12px;font-weight:normal;}
input, textarea, select{margin:2px 0px;border:1px solid #CCCCCC;font:12px Arial, Helvetica, sans-serif;line-height: 1.2em;color: #006699;background:#FFFFFF;}
textarea {overflow:auto;}
cite {float:right; font-style:normal;}

.area{margin:0 auto; width:98%; padding:4px; background:#fafafa;  clear:both;}

/* Link */
a:link {color: #333; text-decoration:none;}
a:hover {color: #134d9d; text-decoration:underline;}
a:active {color: #103d7c;}
a:visited {color: #333;text-decoration:none;}
/* Color */
.cRed,a.cRed:link,a.cRed:visited{ color:#f00; }
.cBlue,a.cBlue:link,a.cBlue:visited,a.cBlue:active{color:#1f3a87;}
.cDRed,a.cDRed:link,a.cDRed:visited{ color:#bc2931;}
.cGray,a.cGray:link,a.cGray:visited{ color: #4F544D;}
.csGray,a.csGray:link,a.csGray:visited{ color: #999;}
.cDGray,a.cDGray:link,a.cDGray:visited{ color: #666;}
.cWhite,a.cWhite:link,a.cWhite:visited{ color:#fff;}
.cBlack,a.cBlack:link,a.cBlack:visited{color:#000;}a.cBlack:hover{color:#bc2931;}
.cYellow,a.cYellow:link,a.cYellow:visited{color:#ff0;}
.cGreen,a.cGreen:link,a.cGreen:visited{color:#008000;}
/* Font  */
.fn{font-weight:normal;}
.fB{font-weight:bold;}
.f12px{font-size:12px;}
.f14px{font-size:14px;}
.f16px{font-size:16px;}
.f18px{font-size:18px;}
.f24px{font-size:24px;}
/* Other */
.left{ float: left;}
.right{ float: right;}
.clear{ clear: both; font-size:1px; width:1px; height:0; visibility: hidden; }
.clearfix:after{content:"."; display:block; height: 0; clear: both; visibility: hidden;} /* only FF */
.hidden {display: none;}
.unLine ,.unLine a{text-decoration: none;}
.noBorder{border:none;}
.txtleft{text-align:left;}
.txtright{text-align:right;}
.nobg { background:none;}
.txtindent12 {text-indent:12px;}
.txtindent24 {text-indent:24px;}
.lineheight24{line-height:24px;}
.lineheight20{line-height:20px;}
.lineheight16{line-height:16px;}
.lineheight200{line-height:200%;}
.blank1{ height:1px; clear:both;display:block; font-size:1px;overflow:hidden;}
.blank3{ height:3px; clear:both;display:block; font-size:1px;overflow:hidden;}
.blank9{ height:9px; font-size:1px;display:block; clear:both;overflow:hidden;}
.blank6{height:6px; font-size:1px; display:block;clear:both;overflow:hidden;}
.blankW6{ height:6px; display:block;background:#fff; clear:both;overflow:hidden;}
.blankW9{ height:9px; display:block;background:#fff; clear:both;overflow:hidden;}
.blank12{ height:12px; font-size:1px;clear:both;overflow:hidden;}
.blank18{ height:18px; font-size:1px;clear:both;overflow:hidden;}
.blank36{ height:36px; font-size:1px;clear:both;overflow:hidden;}
.bgc_E7F5FE { background:#E7F5FE;}
.bgc_FFFFFF { background:#FFFFFF;}
.bgc_71ACD2 { background:#71ACD2;}
.bgc_c6e9ff { background:#c6e9ff;}

/*border*/
.borderall {border:1px #134d9d solid;}
.borderall2 {border:1px #CCCCCC solid; border-right:1px #666 solid; border-bottom:1px #666 solid; }
.borderleft {border-left:1px #CCC solid;}
.borderright {border-right:1px #CCC solid;}
.bordertop {border-top:1px #CCC solid;}
.borderbottom {border-bottom:1px #005584 solid;}
.borderno {border:none;}
.borderbottom_no {border-bottom:none;}

.nav1 { height:50px; line-height:50px;}
.nav2 { padding:9px; background:#FFF;}
.table_frame { clear:both; padding:0 9px;}
.w48 { width:48%;}
</style>
</head>
<body>

<div class="area">
	<div class="blank9"></div>
    <div class="nav1">
        <font class=" left f24px"><?=lang('welcome_platform')?></font><font class="right"><?="08CMS V$cms_version $lic_str"?></font>	
    </div>
    <div class="nav2 borderall" style="background:#FFF;">
        <div class="blank12"></div>
        <h1 class=" lineheight200 txtindent12 txtleft fB f14px"><?=lang('08cms_dynamic')?></h1>
        <ul class="txtleft txtindent12 lineheight200" id="_08cms_dynamic_info">
            <li></li>
        </ul>
        <div class="blank12"></div>
        <h1 class=" lineheight200 txtindent12 txtleft fB f14px"><?=lang('08cms_service')?></h1>
        <div class="blank6"></div>
        <ul class="txtindent12 lineheight200">
            <li><font class="left f14px w48"><a href="http://bbs.08cms.com" class="cBlue" target="_blank">>><?=lang('08cms_bbs')?></a></font> <font class="right f14px w48"><a href="http://www.08cms.com" class="cBlue" target="_blank">>><?=lang('08cms_biz_service')?></a></font> </li>
        </ul>
        <div class="blank6"></div>
    </div>
	<div class="blank18"></div>
    <div class="nav2 borderall">
        <div class="table_frame">
        <ul class="left w48 lineheight200">
            <table width="100%" border="0" cellspacing="1" cellpadding="0" class="bgc_71ACD2">
				 <tr>
					<td colspan="8" class="bgc_c6e9ff fB txtleft txtindent12"><?=lang('arc_stat')?></td>
				</tr>
				<tr>
					<td class="bgc_E7F5FE fB"><?=lang('stat')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('checked')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('nocheck')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('nowmonth')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('nowweek')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('day_3')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('day_1')?></td>
				</tr>
				<?=$archivestr?>
    		</table>
        </ul>
        <ul class=" right w48 lineheight200">
            <table width="100%" border="0" cellspacing="1" cellpadding="0" class="bgc_71ACD2">
                    <tr>
                        <td colspan="8" class="bgc_c6e9ff fB txtleft txtindent12"><?=lang('mem_stat')?></td>
                    </tr>
                    <tr>
					<td class="bgc_E7F5FE fB"><?=lang('stat')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('checked')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('nocheck')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('nowmonth')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('nowweek')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('day_3')?></td>
					<td class="bgc_E7F5FE fB"><?=lang('day_1')?></td>
                    </tr>
					<?=$memberstr?>
    		</table>
        </ul>
        <div class="blank9"></div>
        </div>
    </div>
	<div class="blank18"></div>
    <div class="nav2 borderall">
        <div class="blank6"></div>
        <div class="table_frame txtleft">
            <ul class="left w48 lineheight200">
                <li><?=lang('master_level', $group)?></li>
                <li><?=lang('openreg')?><?=$registeropenstr?></li>
                <li><?=lang('openspace')?><?=$mspaceopenstr?></li>
                <li><?=lang('server_domain', $cmsinfos['servername'])?></li>
                <li><?=lang('user_ip', $onlineip)?></li>
                <li><?=lang('08cms_version', "08CMS V$cms_version")?></li>			
                <li><?=lang('server_ip', $cmsinfos['serverip'])?></li>
                <li><?=lang('server_time', $cmsinfos['servertime'])?></li>			
                 <li><?=lang('last_patch', $last_patch)?></li>			
           </ul>
            <ul class="right w48 lineheight200">
                <li><?=lang('server_info', $cmsinfos['server'])?></li>
                <li><?=lang('php_safemode', $cmsinfos['safe_mode'])?></li>
                <li><?=lang('mysql_version', $cmsinfos['dbversion'])?></li>
                <li><?=lang('allow_url_fopen', $cmsinfos['allow_url_fopen'])?></li>
                <li><?=lang('php_gd_pic', $cmsinfos['gdpic'])?></li>
                <li><?=lang('php_max_upload', $cmsinfos['max_upload'])?></li>
                <li><?=lang('db_use_size', $cmsinfos['dbsize'])?></li>
                <li><?=lang('attach_size', $cmsinfos['attachsize'])?></li>
                <li><?=lang('php_mail_mode', $cmsinfos['sys_mail'])?></li>
            </ul>
        <div class="blank3"></div>
        </div>
    </div>
	<div class="blank18"></div>
    <div class="nav2 borderall">
        <?=show_tip('08cms_group')?>
    </div>
	<br><br>
    <div class="footer"><hr size="0" noshade color="#86B9D6" width="100%">