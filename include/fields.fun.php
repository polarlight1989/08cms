<?php
!defined('M_COM') && exit('No Permission');
load_cache('rprojects');
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
$limitarr = array(
	'' => lang('nolimitformat'),
	'int' => lang('int'),
	'number' => lang('number'),
	'letter' => lang('letter'),
	'numberletter' => lang('numberletter'),
	'tagtype' => lang('tagtype'),
	'date' => lang('date'),
	'email' => lang('email'),
);
$rpidsarr = array('0' => lang('notremote'));
foreach($rprojects as $rpid => $rproject){
	$rpidsarr[$rpid] = $rproject['cname'];
}
function fieldlist($fname,$field=array(),$mode='ch'){
	global $datatypearr,$chid,$mchid,$matid;
	if($mode == 'ch'){
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"".(!empty($field['mcommon']) || !empty($field['issystem']) ? ' disabled' : '')."></td>\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$fname][available]\" value=\"1\"".($field['available'] ? ' checked' : '').(!empty($field['issystem']) ? ' disabled' : '')."></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$fname][isadmin]\" value=\"1\"".(!empty($field['issystem']) ? ' disabled' : '').($field['isadmin'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"fieldsnew[$fname][vieworder]\" value=\"$field[vieworder]\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w50\"><a href=\"?entry=channels&action=fielddetail&chid=$chid&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a></td>\n".
			"</tr>";
	}elseif($mode == 'fch'){
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"".(!empty($field['issystem']) ? ' disabled' : '')."></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$fname][isadmin]\" value=\"1\"".(!empty($field['issystem']) ? ' disabled' : '').($field['isadmin'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"fieldsnew[$fname][vieworder]\" value=\"$field[vieworder]\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w50\"><a href=\"?entry=fchannels&action=fielddetail&chid=$chid&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a></td>\n".
			"</tr>";
	}elseif($mode == 'init'){
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"".(empty($field['iscustom']) ? ' disabled' : '')."></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w60\"><a href=\"?entry=channels&action=initfielddetail&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a></td>\n".
			"</tr>";
	}elseif($mode == 'initm'){
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"".($field['issystem'] ? ' disabled' : '')."></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w60\">".($field['issystem'] ? lang('system') : "<a href=\"?entry=mchannels&action=initmfielddetail&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a>")."</td>\n".
			"</tr>";
	}elseif($mode == 'member'){
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"".($field['mcommon'] ? ' disabled' : '')."></td>\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$fname][available]\" value=\"1\"".($field['available'] ? ' checked' : '').($field['issystem'] ? ' disabled' : '')."></td>\n".
			"<td class=\"txtC\"><input type=\"text\" size=\"20\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$fname][isadmin]\" value=\"1\"".(!empty($field['issystem']) ? ' disabled' : '').($field['isadmin'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"fieldsnew[$fname][vieworder]\" value=\"$field[vieworder]\"></td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w50\">".($field['issystem'] ? lang('system') : "<a href=\"?entry=mchannels&action=mfielddetail&mchid=$mchid&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a>")."</td>\n".
			"</tr>";
	}elseif($mode == 'ca'){
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"fieldsnew[$fname][vieworder]\" value=\"$field[vieworder]\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w60\"><a href=\"?entry=catalogs&action=cafielddetail&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a></td>\n".
			"</tr>";
	}elseif($mode == 'cc'){
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"fieldsnew[$fname][vieworder]\" value=\"$field[vieworder]\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w60\"><a href=\"?entry=cotypes&action=ccfielddetail&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a></td>\n".
			"</tr>";
	}elseif($mode == 'ma'){
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$fname][isadmin]\" value=\"1\"".(!empty($field['issystem']) ? ' disabled' : '').($field['isadmin'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"fieldsnew[$fname][vieworder]\" value=\"$field[vieworder]\"></td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w60\"><a href=\"?entry=matypes&action=fielddetail&matid=$matid&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a></td>\n".
			"</tr>";
	}elseif(in_array($mode,array('p','o','r','c','b',))){
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$fname][isadmin]\" value=\"1\"".($field['isadmin'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"fieldsnew[$fname][vieworder]\" value=\"$field[vieworder]\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w50\"><a href=\"?entry=cufields&action=".$mode."fielddetail&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a></td>\n".
			"</tr>";
	}elseif(in_array($mode,array('mf','ml','mc','mr','mb',))){
		$nowarr = array('mf' => 1,'ml' => 2,'mc' => 3,'mr' => 4,'mb' => 5,);
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$fname]\" value=\"$fname\"></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"25\" name=\"fieldsnew[$fname][cname]\" value=\"".mhtmlspecialchars($field['cname'])."\"></td>\n".
			"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"fieldsnew[$fname][isadmin]\" value=\"1\"".($field['isadmin'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"fieldsnew[$fname][vieworder]\" value=\"$field[vieworder]\"></td>\n".
			"<td class=\"txtC\">".mhtmlspecialchars($fname)."</td>\n".
			"<td class=\"txtC w100\">".$datatypearr[$field['datatype']]."</td>\n".
			"<td class=\"txtC w50\"><a href=\"?entry=mcufields&action=fielddetail&cu=$nowarr[$mode]&fieldename=$fname\" onclick=\"return floatwin('open_fielddetail',this)\">".lang('detail')."</a></td>\n".
			"</tr>";
	}
}
function dropfieldfromtbl($tbl,$ename,$datatype){
	global $db,$tblprefix;
	if(!$tbl || !$ename || !$datatype) return;
	$db->query("ALTER TABLE {$tblprefix}$tbl DROP $ename",'SILENT'); 
	if($datatype == 'map'){
		$db->query("ALTER TABLE {$tblprefix}$tbl DROP {$ename}_0",'SILENT'); 
		$db->query("ALTER TABLE {$tblprefix}$tbl DROP {$ename}_1",'SILENT'); 
	}
}
?>
