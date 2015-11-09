<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
load_cache('btagnames,channels,fchannels');
aheader();
$url_type = 'btags';include 'urlsarr.inc.php';
url_nav(lang('originallogo'),$urlsarr,'search');
$bclasses = array(
	'common' => lang('common_message'),
	'archive' => lang('archive_related'),
	'cnode' => lang('catas_related'),
	'freeinfo' => lang('freeinfo_related'),
	'commu' => lang('commu_message'),
	'member' => lang('member_related'),
	'other' => lang('other'),
	);
$datatypearr = array(
	'text' => lang('text'),
	'multitext' => lang('multitext'),
	'htmltext' => lang('htmltext'),
	'image' => lang('image_f'),
	'images' => lang('images'),
	'flash' => lang('flash'),
	'flashs' => lang('flashs'),
	'media' => lang('media'),
	'medias' => lang('medias'),
	'file' => lang('file_f'),
	'files' => lang('files_f'),
	'select' => lang('select'),
	'mselect' => lang('mselect'),
	'cacc' => lang('cacc'),
	'date' => lang('date_f'),
	'int' => lang('int'),
	'float' => lang('float'),
	'map' => lang('map'),
	'vote' => lang('vote'),
);
tabheader(lang('search_initag'),'btagsearch','?entry=btagsearch');
trbasic(lang('tagid_inc_string'),'bsearch[ename]',empty($bsearch['ename']) ? '' : $bsearch['ename']);
trbasic(lang('tagname_inc_string'),'bsearch[cname]',empty($bsearch['cname']) ? '' : $bsearch['cname']);
trbasic(lang('tag_coclass'),'bsearch[bclass]',makeoption(array('' => lang('nolimit')) + $bclasses,empty($bsearch['bclass']) ? '' : $bsearch['bclass']),'select');
tabfooter('bbtagsearch',lang('search'));
if(submitcheck('bbtagsearch')){
	$ename = trim(strtolower($bsearch['ename']));
	$cname = trim($bsearch['cname']);
	$bclass = trim($bsearch['bclass']);
	if(empty($ename) && empty($cname) && empty($bclass)) amessage('inputsearchstring');
	tabheader(lang('initag_search_result'),'','','8');
	trcategory(array(lang('sn'),lang('tagname'),array(lang('use_style').'1','txtL'),array(lang('use_style').'2','txtL'),array(lang('use_style').'3','txtL'),lang('tagclass'),lang('detail_coclass'),lang('field_type')));
	$i = 1;
	foreach($btagnames as $k => $v){
		if((!$ename || in_str($ename,$v['ename'])) 
			&& (!$cname || in_str($cname,$v['cname']))
			&& (!$bclass || $v['bclass'] == $bclass)){
			$sclasses = array();
			if($v['bclass'] == 'archive'){
				foreach($channels as $chid => $channel){
					$sclasses[$chid] = $channel['cname'];
				}
			}elseif($v['bclass'] == 'cnode'){
				$sclasses = array(
					'catalog' => lang('catalog'),
					'coclass' => lang('coclass'),
				);
			}elseif($v['bclass'] == 'freeinfo'){
				foreach($fchannels as $chid => $channel){
					$sclasses[$chid] = $channel['cname'];
				}
			}elseif($v['bclass'] == 'commu'){
				$sclasses = array(
					'comment' => lang('comment'),
					'purchase' => lang('purchase'),
					'answer' => lang('answer'),
				);
			}elseif($v['bclass'] == 'other'){
				$sclasses = array(
					'attachment' => lang('attachment'),
					'vote' => lang('vote'),
				);
			}
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w40\">$i</td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtL\">{<b>$v[ename]</b>}</td>\n".
				"<td class=\"txtL\">{\$<b>$v[ename]</b>}</td>\n".
				"<td class=\"txtL\">{\$<b>v[$v[ename]]</b>}</td>\n".
				"<td class=\"txtC w80\">".@$bclasses[$v['bclass']]."</td>\n".
				"<td class=\"txtC w80\">".(empty($sclasses[$v['sclass']]) ? '-' : $sclasses[$v['sclass']])."</td>\n".
				"<td class=\"txtC w80\">".$datatypearr[$v['datatype']]."</td>\n".
				"</tr>";
			$i ++;
		}
	}
	tabfooter();
}
?>