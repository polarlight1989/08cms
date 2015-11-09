<?
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/marchive.cls.php';
//分析基本信息
parse_str(un_virtual($_SERVER['QUERY_STRING']));
$page = empty($page) ? 1 : max(1, intval($page));
if(empty($maid) || empty($matid)) message('choosemarchive');
$maid = max(0,intval($maid));
$matid = max(0,intval($matid));
$isp = empty($isp) ? 0 : 1;//是否权限控制页
$arc = new cls_marchive();
if(!$arc->arcid($maid,$matid)) message('choosemarchive');
if(!$arc->archive['checked'] && !$curuser->isadmin()) message('pointmarchinoch'); 

if($isp && !$curuser->pmbypmids('aread',$arc->matype['rpmid'])) message('nomarcreadpermission');
//分析模板来源
$tplname = $isp ? $arc->matype['parctpl'] : $arc->matype['arctpl'];
!$tplname && message('definereltem');

$_da = &$arc->archive;
marc_parse($_da);

$_mp = array();
$_mp['durlpre'] = view_url(en_virtual('marchive.php?maid='.$maid.'&matid='.$matid.($isp ? '&isp=1' : '').'&page={$page}',1));
$_mp['static'] = 0;
$_mp['nowpage'] = max(1,intval($page));

_aenter($_da,1);
@extract($btags);
extract($_da,EXTR_OVERWRITE);
tpl_refresh($tplname);
@include M_ROOT."template/$templatedir/pcache/$tplname.php";

$_content = ob_get_contents();
ob_clean();
mexit($_content);
?>

