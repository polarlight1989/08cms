<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
switch($url_type){
	case 'tpl':
		$urlsarr = array(
		'base' => array(lang('base_setting'),"?entry=tplconfig&action=tplbase$param_suffix"),
		'retpl' => array(lang('mtplstore'),"?entry=mtpls&action=mtplsedit$param_suffix"),
		'futpl' => array(lang('sptplstore'),"?entry=sptpls&action=sptplsedit$param_suffix"),
		);
		$urlsarr['channel'] = array(lang('arctpl'),"?entry=tplconfig&action=tplchannel$param_suffix");
		!$sid && $urlsarr['mchannel'] = array(lang('membertpl'),"?entry=tplconfig&action=tplmchannel$param_suffix");
		!$sid && $urlsarr['fcatalog'] = array(lang('freeinfo'),"?entry=tplconfig&action=tplfcatalog$param_suffix");
		$urlsarr['commu'] = array(lang('archivecommu'),"?entry=tplconfig&action=tplcommu$param_suffix");
		!$sid && $urlsarr['mcommu'] = array(lang('membercommu'),"?entry=tplconfig&action=tplmcommu$param_suffix");
		!$sid && $urlsarr['matype'] = array(lang('marchive'),"?entry=tplconfig&action=tplmatype$param_suffix");
		$urlsarr['cnodes'] = array('>>'.lang('catascnode'),"?entry=cnodes&action=cnodescommon$param_suffix");
	break;
	case 'othertpl':
		$urlsarr = array(
		'cssjs' => array(lang('cssandjsmanage'),"?entry=csstpls$param_suffix"),
		'db' => array(lang('dbsource'),"?entry=dbsources&action=dbsourcesedit$param_suffix"),
		'tcah' => array(lang('rebtplcache'),"?entry=tplcache$param_suffix"),
		);
	break;
	case 'mtdetail':
		$urlsarr = array(
		'base' => array(lang('base_setting'),"?entry=mtconfigs&action=mtconfigdetail&mtcid=$mtcid$param_suffix"),
		'tpl' => array(lang('cnt_tpl'),"?entry=mtconfigs&action=mtconfigtpl&mtcid=$mtcid$param_suffix"),
		);
	break;
	case 'backarea':
		$urlsarr = array(
		'bkparam' => array(lang('admbacrelset'),"?entry=backparams&action=bkparams$param_suffix"),
		'config' => array(lang('amconfig'),"?entry=amconfigs&action=amconfigsedit$param_suffix"),
		'url' => array(lang('aurl'),"?entry=aurls&action=aurlsedit$param_suffix"),
		'inurl' => array(lang('inurl'),"?entry=inurls&action=inurlsedit$param_suffix"),
		'm' => array(lang('mainmenu'),"?entry=menus&action=menusedit$param_suffix"),
		's' => array(lang('submenu'),"?entry=menus&action=menusedit&issub=1$param_suffix"),
		'ausual' => array(lang('usualurl0'),"?entry=usualurls&action=usualurlsedit"),
		'auser' => array(lang('userurl'),"?entry=userurls&action=userurlsedit"),
		);
	break;
	case 'mcenter':
		$urlsarr = array(
		'mcparam' => array(lang('memcentrelaset'),"?entry=backparams&action=mcparams$param_suffix"),
		'c' => array(lang('menumanage'),"?entry=mmenus&action=mmenusedit$param_suffix"),
		'mu' => array(lang('murl'),"?entry=murls&action=murlsedit$param_suffix"),
		'in' => array(lang('inmurl'),"?entry=inmurls&action=inmurlsedit$param_suffix"),
		'musual' => array(lang('usualurl0'),"?entry=usualurls&action=usualurlsedit&ismc=1"),
		'muser' => array(lang('userurl'),"?entry=userurls&action=userurlsedit&ismc=1"),
		);
	break;
	case 'amconfigdetail':
		$urlsarr = array(
		'base' => array(lang('base_setting'),"?entry=amconfigs&action=amconfigdetail&amcid=$amcid$param_suffix"),
		'ablock' => array(lang('ablock'),"?entry=amconfigs&action=amconfigablock&amcid=$amcid$param_suffix"),
		'fblock' => array(lang('fblock'),"?entry=amconfigs&action=amconfigfblock&amcid=$amcid$param_suffix"),
		'mblock' => array(lang('mblock'),"?entry=amconfigs&action=amconfigmblock&amcid=$amcid$param_suffix"),
		);
	break;
	case 'btags':
		$urlsarr = array(
			'btag' => array(lang('btaglist'),"?entry=btags$param_suffix"),
			'search' => array(lang('search_initag'),"?entry=btagsearch$param_suffix"),
			'update' => array(lang('updatebtag'),"?entry=btags&action=update$param_suffix"),
		);
	break;
	case 'channel':
		$urlsarr = array(
		'channel' => array(lang('channeladmin'),"?entry=channels&action=channeledit$param_suffix"),
		'field' => array(lang('common_field_manager'),"?entry=channels&action=initfieldsedit$param_suffix"),
		);
	break;
	case 'channeldetail':
		$urlsarr = array(
		'detail' => array(lang('base_setting'),"?entry=channels&action=channeldetail&chid=$chid$param_suffix"),
		'filed' => array(lang('channel_field'),"?entry=channels&action=channelfields&chid=$chid$param_suffix"),
		'album' => array(lang('abfunc'),"?entry=channels&action=channelalbum&chid=$chid$param_suffix"),
		'commu' => array(lang('commu_sett'),"?entry=channels&action=channelcommu&chid=$chid$param_suffix"),
		'allowance' => array(lang('allowance_and_vp'),"?entry=channels&action=allowance&chid=$chid$param_suffix"),
		'url' => array(lang('inurl0'),"?entry=channels&action=channelurl&chid=$chid$param_suffix"),
		);
	break;
	case 'cnode';
		$urlsarr = array(
		'scnodescommon' => array(lang('scnodemanager'),"?entry=cnodes&action=cnodescommon$param_suffix"),
		'cnodescommon' => array(lang('mcnodemanager'),"?entry=cnodes&action=cnodescommon&ism=1$param_suffix"),
		'scnodesupdate' => array(lang('updatescnode'),"?entry=cnodes&action=scnodesupdate$param_suffix"),
		'cnconfigsadd' => array(lang('cnconfigsadd'),"?entry=cnodes&action=cnconfigsadd$param_suffix"),
		'cnconfigs' => array(lang('cnconfigadmin'),"?entry=cnodes&action=cnconfigs$param_suffix"),
		'cnodesupdate' => array(lang('cnodesupdate'),"?entry=cnodes&action=cnodesupdate$param_suffix"),
		'tpl' => array('>>'.lang('othertpl'),"?entry=tplconfig&action=tplbase$param_suffix"),
		);
	break;
	case 'cufield':
		$urlsarr_1 = array(
		in_str('pfield',$action) ? '<b>-'.lang('pu_field_manager').'-</b>' : "<a href=\"?entry=cufields&action=pfieldsedit\">".lang('pu_field_manager')."</a>",
		in_str('ofield',$action) ? '<b>-'.lang('offer_field_manager').'-</b>' : "<a href=\"?entry=cufields&action=ofieldsedit\">".lang('offer_field_manager')."</a>",
		in_str('rfield',$action) ? '<b>-'.lang('reply_field_manager').'-</b>' : "<a href=\"?entry=cufields&action=rfieldsedit\">".lang('reply_field_manager')."</a>",
		in_str('cfield',$action) ? '<b>-'.lang('comment_field_manager').'-</b>' : "<a href=\"?entry=cufields&action=cfieldsedit\">".lang('comment_field_manager')."</a>",
		in_str('bfield',$action) ? '<b>-'.lang('pickbug_field_manager').'-</b>' : "<a href=\"?entry=cufields&action=bfieldsedit\">".lang('pickbug_field_manager')."</a>",
		);
	case 'commu';
		$urlsarr = array(
		'commu' => array(lang('commu_item_set'),"?entry=commus&action=commusedit"),
		'coclass' => array(lang('ucoclass_admin'),"?entry=ucotypes&action=ucotypesedit"),
		'field' => array(lang('commu_field_manager'),"?entry=cufields&action=pfieldsedit"),
		);
	break;
	case 'cucata':
		$urlsarr = array(
		'commu' => array(lang('commu_item_set'),"?entry=commus&action=commusedit"),
		'rc' => array(lang('reply_class_manager'),"?entry=cucatalogs&action=rcatalogsedit"),
		'p' => array(lang('pu_field_manager'),"?entry=cufields&action=pfieldsedit"),
		'o' => array(lang('offer_field_manager'),"?entry=cufields&action=ofieldsedit"),
		'r' => array(lang('reply_field_manager'),"?entry=cufields&action=rfieldsedit"),
		'c' => array(lang('comment_field_manager'),"?entry=cufields&action=cfieldsedit"),
		'b' => array(lang('pickbug_field_manager'),"?entry=cufields&action=bfieldsedit"),
		);
	break;
	case 'currency':
		$urlsarr = array(
			'type' => array(lang('currencytype'),"?entry=currencys&action=currencysedit"),
			'project' => array(lang('crproject'),"?entry=currencys&action=crprojects"),
			'price' => array(lang('crprice'),"?entry=currencys&action=crprices"),
		);
	break;
	case 'cysave':
		$urlsarr = array(
			'save' => array(lang('member_inout'),"?entry=currencys&action=currencysaving"),
			'record' => array(lang('cu_operate_record'),"?entry=currencys&action=cradminlogs"),
		);
	break;
	case 'data':
		$urlsarr = array(
		'dbbackup' => array(lang('dbbackup'),"?entry=database&action=dbexport"),
		'dbimport' => array(lang('import_db_backup'),"?entry=database&action=dbimport"),
		'dboptimize' => array(lang('dbopre'),"?entry=database&action=dboptimize"),
		'dbsql' => array(lang('dbsql'),"?entry=database&action=dbsql"),
		'txt' => array(lang('fieldtotxt'),"?entry=fieldtotxt"),
		'dbdict' => array(lang('dbdict'),"?entry=dbdict"),
		);
	break;
	case 'fcata':
		$urlsarr = array(
		'channel' => array(lang('freeinfo_channel'),"?entry=fchannels&action=fchannelsedit"),
		'coclass' => array(lang('message_coclass'),"?entry=fcatalogs&action=fcatalogsedit"),
		);
	break;
	case 'fchannel':
		$urlsarr = array(
		'channel' => array(lang('freeinfo_channel'),"?entry=fchannels&action=fchannelsedit"),
		'coclass' => array(lang('message_coclass'),"?entry=fcatalogs&action=fcatalogsedit"),
		);
	break;
	case 'gmiss':
		$urlsarr = array(
		'admin' => array(lang('gather_mission_manager'),"?entry=gmissions&action=gmissionsedit$param_suffix"),
		'model' => array(lang('gather_model_manager'),"?entry=gmodels&action=gmodeledit$param_suffix"),
		);
	break;
	case 'grule':
		$urlsarr = array(
		'netsite' => array(lang('netsite_gather'),"?entry=gmissions&action=gmissionurls&gsid=$gsid$param_suffix"),
		'content' => array(lang('content_gather'),"?entry=gmissions&action=gmissionfields&gsid=$gsid$param_suffix"),
		'output' => array(lang('content_output'),"?entry=gmissions&action=gmissionoutput&gsid=$gsid$param_suffix"),
		'test' => array(lang('test_rule'),"?entry=gmissions&action=urlstest&gsid=$gsid$param_suffix"),
		);
	break;
	case 'langs':
		$urlsarr = array(
		'alang' => array(lang('alang_manager'),"?entry=alangs&action=alangsedit"),
		'mlang' => array(lang('mlang_manager'),"?entry=mlangs&action=mlangsedit"),
		'clang' => array(lang('clang_manager'),"?entry=clangs&action=clangsedit"),
		'amsg' => array(lang('amsg_manager'),"?entry=amsgs&action=amsgsedit"),
		'mmsg' => array(lang('mmsg_manager'),"?entry=mmsgs&action=mmsgsedit"),
		'cmsg' => array(lang('cmsg_manager'),"?entry=cmsgs&action=cmsgsedit"),
		'email' => array(lang('emaltpl'),"?entry=splangs&action=splangsedit")
		);
	break;
	case 'mchannel':
		$urlsarr = array(
		'channel' => array(lang('member_channel_manager'),"?entry=mchannels&action=mchannelsedit"),
		'field' => array(lang('m_cfield'),"?entry=mchannels&action=initmfieldsedit"),
		);
	break;
	case 'mcufield':
		$urlsarr = array(
		'mcommu' => array(lang('memcomitad'),"?entry=mcommus&action=mcommusedit"),
		'2' => array(lang('spaceflink').lang('field'),"?entry=mcufields&action=fieldsedit&cu=2"),
		'3' => array(lang('membercomment').lang('field'),"?entry=mcufields&action=fieldsedit&cu=3"),
		'4' => array(lang('memberreply').lang('field'),"?entry=mcufields&action=fieldsedit&cu=4"),
		'5' => array(lang('memberreport').lang('field'),"?entry=mcufields&action=fieldsedit&cu=5"),
		);
	break;
	case 'mconfig':
		$urlsarr = array('cfsite' => array(lang('sitemessaadmi'),"?entry=mconfigs&action=cfsite$param_suffix"));
		if(!$sid) $urlsarr['cfbasic'] = array(lang('base_setting'),"?entry=mconfigs&action=cfbasic$param_suffix");
		if(!$sid) $urlsarr['cfvisit'] = array(lang('webvisiset'),"?entry=mconfigs&action=cfvisit");
		$urlsarr['cfview'] = array(lang('pagebasedset'),"?entry=mconfigs&action=cfview$param_suffix");
		if(!$sid) $urlsarr['cfppt'] = array(lang('passport'),"?entry=mconfigs&action=cfppt$param_suffix");
		if(!$sid) $urlsarr['cfpay'] = array(lang('ecommerce'),"?entry=mconfigs&action=cfpay$param_suffix");
		if(!$sid) $urlsarr['cfupload'] = array(lang('annexset'),"?entry=mconfigs&action=cfupload$param_suffix");
		if(!$sid) $urlsarr['cfmail'] = array(lang('emaiset'),"?entry=mconfigs&action=cfmail$param_suffix");
		if(!$sid) $urlsarr['localfile'] = array(lang('localproject'),"?entry=localfiles&action=localfilesedit$param_suffix");
		if(!$sid) $urlsarr['rproject'] = array(lang('remodedown'),"?entry=rprojects&action=rprojectedit$param_suffix");
		if(!$sid) $urlsarr['player'] = array(lang('player'),"?entry=players&action=playersedit$param_suffix");
	break;
	case 'member':
		$urlsarr = array(
			'edit' => array(lang('member').lang('admin'),"?entry=members&action=memberedit"),
			'add' => array(lang('add').lang('member'),"?entry=members&action=memberadd"),
		);
	break;
	case 'order':
		$urlsarr = array(
		'order' => array(lang('orders_admin'),"?entry=orders&action=ordersedit"),
		'purchase' => array(lang('purchase_record'),"?entry=purchases&action=purchasesedit"),
		);
	break;
	case 'pms':
		$urlsarr = array(
		'batch' => array(lang('pmadmin'),"?entry=pms&action=batchpms"),
		'clear' => array(lang('pmclear'),"?entry=pms&action=clearpms"),
		);
	break;
	case 'record':
		$urlsarr = array(
		'bad' => array(lang('loginerrrecord'),"?entry=records&action=badlogin"),
		'admin' => array(lang('adminoperate'),"?entry=records&action=adminlog"),
		'currency' => array(lang('crrecord'),"?entry=records&action=currencylog"),
		);
	break;
	case 'static':
		$urlsarr = array(
		'index' => array(lang('index_static'),"?entry=static&action=index$param_suffix"),
		'cnodes' => array(lang('cnodes_static'),"?entry=static&action=cnodes$param_suffix"),
		'archives' => array(lang('content_static'),"?entry=static&action=archives$param_suffix"),
		'freeinfos' => array(lang('isolute_page_manager'),"?entry=freeinfos&action=freeinfosedit$param_suffix"),
		);
	break;
	case 'subsite':
		$urlsarr = array(
		'admin' => array(lang('subsitemanager'),"?entry=subsites&action=subsitesedit"),
		'add' => array(lang('addsubsite'),"?entry=subsites&action=subsiteadd"),
		'tosub' => array(lang('msitetranstsubsite'),"?entry=subsites&action=tosubsite"),
		'setup' => array(lang('contsubsinst'),"?entry=subsetup"),
		);
	break;
	case 'usualtags':
		$urlsarr = array(
		'usualtags' => array(lang('usualtags'),"?entry=usualtags$param_suffix"),
		'tagclasses' => array(lang('usualtagclass'),"?entry=usualtags&action=tagclasses$param_suffix"),
		);
	break;
	case 'vote':
		$urlsarr = array(
		'vcata' => array(lang('vcatalog'),"?entry=vcatalogs&action=vcatalogsedit"),
		'admin' => array(lang('voteadmin'),"?entry=votes&action=votesedit"),
		'add' => array(lang('addvote'),"?entry=votes&action=voteadd"),
		);
	break;
}
//$url_type = 'channeldetail';include M_ROOT.'./include/urlsarr.inc.php';
?>