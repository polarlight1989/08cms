<?
include_once M_ROOT.'./include/archive.fun.php';
include_once M_ROOT."./include/arc_static.fun.php";

load_cache('cotypes');
class cls_arcedit{
	var $aid = 0;
	var $archive = array();
	var $basiced = 0;
	var $detailed = 0;
	var $auser = '';
	var $channel = array();
	var $fields = array();
	var $namepres = array();//暂存txt字段的文件名
	var $updatearr = array();
	
	function __construct(){
		$this->cls_arcedit();
	}
	function cls_arcedit(){
	}
	function init(){
		$this->aid = 0;
		$this->archive = array();
		$this->auser = '';
		$this->basiced = 0;
		$this->detailed = 0;
		$this->channel = array();
		$this->fields = array();
		$this->namepres = array();
		$this->updatearr = array();
	}
	function set_aid($aid){
		$this->aid = max(0,intval($aid));
	}
	function readcopy($acid){//只是将副本中已保存的内容替换当前文本的内容
		global $db,$tblprefix;
		if(empty($acid)) return;
		if(!($datas = $db->result_one("SELECT datas FROM {$tblprefix}acopys WHERE acid='$acid'"))) return;
		$datas = empty($datas) ? array() : unserialize($datas);
		foreach($datas as $k => $v) $this->archive[$k] = $v;
		$this->basiced = $this->detailed = 1;
		unset($datas);
	}
	function cnstr(){//得到类目字串，可用于静态通知系统。
		global $cotypes,$cmsinfos;
		include_once M_ROOT."./include/cparse.fun.php";
		$arr = array();
		$arr['caid'] = $this->archive['caid'];
		foreach($cotypes as $k => $v){
			$this->archive["ccid$k"] && $arr['ccid'.$k] = $this->archive["ccid$k"];
		}
		$cnstr = cnstr($arr);
		unset($arr);
		return $cnstr;
	}
	function basic_data($auser=1){
		global $db,$tblprefix,$catalogs,$channels,$enablestatic;
		if(empty($this->aid) || $this->basiced) return;
		if(!$this->archive = $db->fetch_one("SELECT a.*,s.*,r.* FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}archives_sub s ON s.aid=a.aid LEFT JOIN {$tblprefix}archives_rec r ON r.aid=a.aid WHERE a.aid=".$this->aid)){
			$this->init();
			return;
		}
		$this->channel = read_cache('channel',$this->archive['chid']);
		$this->fields = read_cache('fields',$this->archive['chid']);
		arc_checkend($this->archive);
		$this->fetch_txt(0);
		if($auser){
			$this->auser = new cls_userinfo;
			$this->auser->activeuser($this->archive['mid']);
		}
		$this->basiced = 1;
	}
	function detail_data($auser=1){
		global $db,$tblprefix;
		if(empty($this->aid) || $this->detailed) return;
		$this->basic_data($auser);
		if($r = $db->fetch_one("SELECT * FROM {$tblprefix}archives_".$this->archive['chid']." WHERE aid='".$this->aid."'")){
			$this->archive = array_merge($r,$this->archive);
		}
		unset($r);
		$this->fetch_txt(1);
		$this->detailed = 1;
	}
	function set_cpid($id){
		$this->updatefield('cpid',$id ? $id : $this->aid,'main');
	}
	function set_arcurl(){//初始化文件的静态链接
		global $arccustomurl;
		$this->basic_data();
		$arcurl = arc_format($this->archive);
		for($i = 0;$i <= $this->channel['addnum'];$i++){
			arc_blank($this->aid,$i,M_ROOT.m_parseurl($arcurl,array('addno' => arc_addno($i,$this->channel['addnos']),'page' => 1,)));
		}
	}
	function fetch_txt($detail = 0){
		foreach($this->fields as $k => $v){
			if(!empty($v['istxt']) && isset($this->archive[$k]) && ((!$detail && $v['tbl'] == 'main') || ($detail && $v['tbl'] == 'custom'))){
				$this->namepres[$k] = $this->archive[$k];
				$this->archive[$k] = readfromtxt($this->archive[$k]);
			}
		}
	}
	function addcopy($coid=0,$ccid=0){//复制到某个栏目或某个分类中，其它的分类如何处理呢?是保持还是放弃//类系中需要一个参数，新增复件是否需要保持属性。//只有单选类系才可能复制
		global $cotypes,$timestamp,$db,$tblprefix,$arc;
		if(!$ccid) return false;
		$this->detail_data();
		$archivenew = &$this->archive;
		if($archivenew[$coid ? "ccid$coid" : 'caid'] == $ccid) return false;
		$chid = $archivenew['chid'];
		foreach(array('coidscp','cpkeeps') as $var) $$var = $this->channel[$var] ? explode(',',$this->channel[$var]) : array();
		if(!in_array($coid ? $coid : 'caid',$coidscp)) return false;

		$pre_cns = array('caid' => $coid ? $this->archive['caid'] : $ccid);//复制到其它类系时，原栏目要保持
		foreach($cotypes as $k => $v){//其它要保持的类系。
			if(!$v['self_reg'] && in_array($k,$cpkeeps)){
				if($coid != $k){
					$archivenew["ccid$k"] && $pre_cns["ccid$k"] = $archivenew["ccid$k"];
				}else $pre_cns["ccid$k"] = $ccid;
			}
		}
		if(!$this->auser->allow_arcadd($archivenew['chid'],$pre_cns)) return false;
		$sqlmain = $sqlsub = $sqlcustom = '';
		foreach($pre_cns as $k => $v) $sqlmain .= ($sqlmain ? ',' : '')."$k='$v'";
		$keeps = array('sid','chid','mid','mname','rpmid','dpmid','salecp','fsalecp','atmsize','enddate','jumpurl',);
		foreach($keeps as $k) $sqlmain .= ($sqlmain ? ',' : '')."$k='".addslashes($archivenew[$k])."'";
		$sqlmain .= ($sqlmain ? ',' : '')."refreshdate='$timestamp',createdate='$timestamp'";

		$fields = read_cache('fields',$chid);
		foreach($fields as $k => $v){
			if($v['available'] && !$v['isfunc']){
				if(!empty($v['istxt'])){
					$txtname = saveastxt($archivenew[$k]);
					${'sql'.$v['tbl']} .= (${'sql'.$v['tbl']} ? ',' : '').$k."='".addslashes($txtname)."'";
				}else{
					${'sql'.$v['tbl']} .= (${'sql'.$v['tbl']} ? ',' : '').$k."='".addslashes($archivenew[$k])."'";
					if($arr = multi_val_arr($archivenew[$k],$v)) foreach($arr as $x => $y) ${'sql'.$v['tbl']} .= (${'sql'.$v['tbl']} ? ',' : '').$k.'_'.$x."='$y'";
				}
			}
		}

		cu_sqls_deal($this->channel['cuid'],$archivenew,$sqlmain,$sqlsub,$sqlcustom);//将字段之外的交互资料写入
		$db->query("INSERT INTO {$tblprefix}archives SET ".$sqlmain);
		if(!$aid = $db->insert_id()){
			return false;
		}else{
			$db->query("INSERT INTO {$tblprefix}archives_rec SET aid='$aid'");
			$sqlsub = "aid='$aid'".($sqlsub ? ',' : '').$sqlsub;
			$sqlsub .= ",needstatics='".$archivenew['needstatics']."'";
			$sqlsub .= ",arctpls='".$archivenew['arctpls']."'";
			$db->query("INSERT INTO {$tblprefix}archives_sub SET ".$sqlsub);
			$sqlcustom = "aid='$aid'".($sqlcustom ? ',' : '').$sqlcustom;
			$db->query("INSERT INTO {$tblprefix}archives_$chid SET ".$sqlcustom);
			$this->auser->basedeal('archive',1);

			$aedit = new cls_arcedit;
			$aedit->set_aid($aid);
			$aedit->set_arcurl();
			$aedit->set_cpid(empty($archivenew['cpid']) ? $this->aid : $archivenew['cpid']);
			$this->auser->pmautocheck($channel['autocheck']) && $aedit->arc_check(1,0);
			$aedit->updatedb();
			unset($aedit);

			if($this->channel['autostatic']){
				arc_static($aid);
				unset($arc);
			}
		}
		return true;
	}
	function updatecopy($mode=0){//当更新文档时，同时更新其它的副本。
		global $cotypes,$timestamp,$db,$tblprefix,$arc;
		if(!$mode) return false;
		$cpids = array();
		$naid = $this->aid;
		$query = $db->query("SELECT aid FROM {$tblprefix}archives WHERE aid != '$naid' AND cpid='".$this->archive['cpid']."'");
		while($row = $db->fetch_array($query)) $cpids[] = $row['aid'];
		if(!$cpids) return false;

		$this->init();
		$this->set_aid($naid);
		$this->detail_data();
		$archivenew = &$this->archive;
		$archivenew = maddslashes($archivenew);
		$chid = $archivenew['chid'];
		$fields = read_cache('fields',$chid);

		$aedit = new cls_arcedit;
		foreach($cpids as $aid){
			$aedit->set_aid($aid);
			$aedit->detail_data();

			$aedit->updatefield('rpmid',$archivenew['rpmid'],'main');
			$aedit->updatefield('dpmid',$archivenew['dpmid'],'main');
			$aedit->updatefield('salecp',$archivenew['salecp'],'main');
			$aedit->updatefield('fsalecp',$archivenew['fsalecp'],'main');
			$aedit->sale_define();
			$aedit->updatefield('arctpls',$archivenew['arctpls'],'sub');
			$aedit->updatefield('jumpurl',$archivenew['jumpurl'],'sub');
	
			foreach($fields as $k => $v){
				if($v['available'] && !$v['isfunc'] && (!in_array($k,array('subject','keywords','thumb','abstract',)) || $mode == 1)){
					if(!empty($v['istxt'])){
						$txtname = saveastxt(stripslashes($archivenew[$k]),$aedit->namepres[$k]);
						$aedit->updatefield($k,$txtname,$v['tbl']);
					}else{
						$aedit->updatefield($k,$archivenew[$k],$v['tbl']);
						if($arr = multi_val_arr($archivenew[$k],$v)) foreach($arr as $x => $y) $aedit->updatefield($k.'_'.$x,$y,$v['tbl']);
					}
				}
			}
			$aedit->updatedb();
			if($this->channel['autostatic']){
				arc_static($aid);
				unset($arc);
			}
			$aedit->init();
		}
		return true;
	}
	function edit_cudata(&$newarr,$updateuser=0){//是否更新会员数据库
		//这是个不更新文档数据库的函数，需要包含在其它更新动作里面
		//用于文档修改时对公证的修改或补上公证记录
		global $commus,$db,$tblprefix,$timestamp,$enableship,$enablestock,$useredits;
		if(!$this->channel['cuid'] || !($commu = read_cache('commu',$this->channel['cuid']))) return;
		if($commu['cclass'] == 'answer'){
			if(!$this->archive['checked']){
				if(isset($newarr['currency'])) $this->updatefield('currency',$newarr['currency'],'main');//$u_lists之外
			}else{
				if(isset($newarr['currency']) && (empty($useredits) || in_array('currency',$useredits))){
					if(($newarr['currency'] > $this->archive['currency']) && $this->auser->crids_enough(array($this->archive['crid'] => $newarr['currency'] - $this->archive['currency']))){
						$this->auser->updatecrids(array($this->archive['crid'] => $this->archive['currency'] - $newarr['currency']),$updateuser,lang('answer_reward'));
						$this->updatefield('currency',$newarr['currency'],'main');
						$this->updatefield('spare',$this->archive['spare'] + ($newarr['currency'] - $this->archive['currency']),'sub');
					}
				}
			}
			if(isset($newarr['question']) && (!$this->archive['checked'] || empty($useredits) || in_array('question',$useredits))){
				$this->updatefield('question',$newarr['question'],'custom');
			}
		}elseif($commu['cclass'] == 'purchase'){
			if(isset($newarr['price']) && (!$this->archive['checked'] || empty($useredits) || in_array('price',$useredits))){
				$this->updatefield('price',$newarr['price'],'main');
			}
			if(isset($newarr['storage']) && (!$this->archive['checked'] || empty($useredits) || in_array('storage',$useredits))){
				$enablestock && $this->updatefield('storage',$newarr['storage'],'sub');
			}
		}
	}
	function new_cudata($updateuser=0){//是否更新会员数据库//应以审核的时间为准
		global $commus,$db,$tblprefix,$timestamp;
		if(!$this->channel['cuid'] || !($commu = read_cache('commu',$this->channel['cuid']))) return true;
		if($commu['cclass'] == 'answer'){
			if(!$this->auser->crids_enough(array($this->archive['crid'] => $this->archive['currency']))) return false;
			//closed,crid,currency,finishdate在主表
			$commu['setting']['vdays'] = empty($commu['setting']['vdays']) ? 0 : $commu['setting']['vdays'];
			$this->updatefield('finishdate',$timestamp + $commu['setting']['vdays'] * 24 *3600,'main');
			$this->updatefield('spare',$this->archive['currency'],'sub');
			$this->auser->updatecrids(array($this->archive['crid'] => -$this->archive['currency']),$updateuser,lang('answer_reward'));
		}
		return true;
	}
	function autokeyword($updatedb=0){
		$this->detail_data();
		$fields = read_cache('fields',$this->archive['chid']);
		if($fields['keywords']['available'] && $this->channel['autokeyword'] && empty($this->archive['keywords']) && !empty($this->archive[$this->channel['autokeyword']])){
			$keywords = autokeyword($this->archive[$this->channel['autokeyword']]);
			$this->updatefield('keywords',keywords(addslashes($keywords)),'main');
		}
		unset($fields,$keywords);
		$updatedb && $this->updatedb();
	}
	function autoabstract($updatedb=0){
		$this->detail_data();
		$fields = read_cache('fields',$this->archive['chid']);
		if($fields['abstract']['available'] && $this->channel['autoabstract'] && empty($this->archive['abstract']) && !empty($this->archive[$this->channel['autoabstract']])){
			$this->updatefield('abstract',addslashes(autoabstract($this->archive[$this->channel['autoabstract']])),'main');
		}
		unset($fields);
		$updatedb && $this->updatedb();
	}
	function autosize($updatedb=0){
		$this->detail_data();
		if($this->channel['autosize'] && isset($this->archive[$this->channel['autosize']])){
			$fields = read_cache('fields',$this->archive['chid']);
			$this->updatefield('atmsize',addslashes(atm_size($this->archive[$this->channel['autosize']],$fields[$this->channel['autosize']]['datatype'],$this->channel['autosizemode'])),'main');
		}
		unset($fields);
		$updatedb && $this->updatedb();
	}
	function autothumb($updatedb=0){
		global $c_upload;
		$this->detail_data();
		$fields = read_cache('fields',$this->archive['chid']);
		if($fields['thumb']['available'] && $this->channel['autothumb'] && empty($this->archive['thumb']) && !empty($this->archive[$this->channel['autothumb']])){
			$thumb = $c_upload->thumb_pick($this->archive[$this->channel['autothumb']],$fields[$this->channel['autothumb']]['datatype'],$fields['thumb']['rpid']);
			$this->updatefield('thumb',addslashes($thumb),'main');
		}
		unset($fields,$field);
		$updatedb && $this->updatedb();
	}
	function clear_cudata($updateuser=0){//附带在解审的操作中//其实也处在删除的操作中
		global $commus,$db,$tblprefix,$timestamp;
		if(!$this->channel['cuid'] || !($commu = read_cache('commu',$this->channel['cuid']))) return;
		if($commu['cclass'] == 'answer'){
			$this->auser->updatecrids(array($this->archive['crid'] => $this->archive['spare']),$updateuser,lang('answer_reward'));
			$this->updatefield('spare',0,'sub');
		}
	}
	function pre_check($mode = -1,$updatedb=0){//-1,1,2,3,13(到2的状态),12,11(退到0的状态)
		global $a_checks,$curuser;
		$bmode = 1;
		if($mode > 10) { $mode -= 10; $bmode = 0; }
		if(!array_intersect(array(-1,$mode),$a_checks)) return;
		if($this->archive['chkstate'] > $this->channel['chklv']) $this->updatefield('chkstate',$this->archive['checked'] ? $this->channel['chklv'] : $this->channel['chklv'] - 1,'main');
		if(!$this->archive['checked'] && $this->archive['chkstate'] == $this->channel['chklv']) $this->updatefield('chkstate',$this->channel['chklv'] - 1,'main');
		if($this->archive['checked'] && $this->archive['chkstate'] != $this->channel['chklv']) $this->updatefield('chkstate',$this->channel['chklv'],'main');
		$mode = min($mode,$this->channel['chklv']);
		if($bmode){
			if(in_array($mode,array(-1,$this->archive['chkstate'] + 1))){
				$mode == -1 && $mode = $this->channel['chklv'];
				$this->updatefield('chkstate',$mode,'main');
				$this->updatefield('editorid'.$mode,$curuser->info['mid'],'sub');
				$this->updatefield('editor'.$mode,$curuser->info['mname'],'sub');
				if($mode == $this->channel['chklv']) $this->arc_check(1);
			}
		}else{
			if($mode == $this->archive['chkstate']){
				$this->updatefield('chkstate',$mode - 1,'main');
				$this->updatefield('editorid'.$mode,0,'sub');
				$this->updatefield('editor'.$mode,'','sub');
				if($mode == $this->channel['chklv']) $this->arc_check(0);
			}
		}
		$updatedb && $this->updatedb();
	}
	function arc_check($check=1,$updatedb=0){//$check执行审核或解审的操作
		global $cotypes,$curuser,$vcps,$timestamp;
		if(empty($this->aid)) return;
		$this->basic_data();
		if($check){
			if($this->archive['checked']) return;
			$catalog = read_cache('catalog',$this->archive['caid'],'',$this->archive['sid']);
			if(!$this->new_cudata(0)) return;
			$this->auser->basedeal('check',1);
			$crids = array();
			if(@!empty($vcps['award'][$catalog['awardcp']])){
				$cparr = explode('_',$catalog['awardcp']);
				$crids[$cparr[0]] = $cparr[1];
			}
			foreach($cotypes as $k => $v){
				if(!empty($this->archive["ccid$k"]) && $v['awardcp']){
					$ccids = array_filter(explode(',',$this->archive["ccid$k"]));
					foreach($ccids as $ccid){
						$coclass = read_cache('coclass',$k,$ccid);
						if(@!empty($vcps['award'][$coclass['awardcp']])){
							$cparr = explode('_',$coclass['awardcp']);
							$crids[$cparr[0]] = isset($crids[$cparr[0]]) ? $crids[$cparr[0]] + $cparr[1] : $cparr[1];
						}
					}
				}
			}
			unset($coclass,$catalog);
			if($crids){
				if(!$this->auser->crids_enough($crids)) return;
				$this->auser->updatecrids($crids,0,lang('awardcurrency'));			
			}
			$this->updatefield('overupdate',0,'sub');
			$this->updatefield('needupdate',0,'sub');
		}else{
			if(!$this->archive['checked']) return;
			$this->auser->basedeal('check',0);
			$this->clear_cudata(0);
		}
		$this->auser->updatedb();
		$this->updatefield('checked',$check,'main');
		$this->updatefield('editorid',$check ? $curuser->info['mid'] : 0,'sub');
		$this->updatefield('editor',$check ? $curuser->info['mname'] : '','sub');
		$this->sale_define();
		$updatedb && $this->updatedb();
	}
	function sale_define($updatedb=0){//只要文档的任一分类有不能出售的条款，则该文档不允许出售
		global $cotypes,$vcps;
		if(!$this->archive['checked']) return;
		$clearsale = !empty($this->archive['salecp']) && !isset($vcps['sale'][$this->archive['salecp']]);
		$clearfsale = !empty($this->archive['fsalecp']) && !isset($vcps['fsale'][$this->archive['fsalecp']]);
		$catalog = read_cache('catalog',$this->archive['caid'],'',$this->archive['sid']);
		(!$clearsale && !empty($this->archive['salecp']) && !$catalog['allowsale']) && $clearsale = 1;
		(!$clearfsale && !empty($this->archive['fsalecp']) && !$catalog['allowfsale']) && $clearfsale = 1;
		foreach($cotypes as $k => $v){//多选类系，只要其中有一个不能出售则不能出售
			if($this->archive["ccid$k"]){
				$ccids = array_filter(explode(',',$this->archive["ccid$k"]));
				foreach($ccids as $ccid){
					$coclass = read_cache('coclass',$k,$ccid);
					(!$clearsale && !empty($this->archive['salecp']) && $v['sale'] && !$coclass['allowsale']) && $clearsale = 1;
					(!$clearfsale && !empty($this->archive['fsalecp']) && $v['fsale'] && !$coclass['allowfsale']) && $clearfsale = 1;
				}
			}
		}
		unset($catalog,$coclass,$ccids);
		$clearsale && $this->updatefield('salecp','','main');
		$clearfsale && $this->updatefield('fsalecp','','main');
		$updatedb && $this->updatedb();
	}
	function arc_caid($caid=0,$updatedb=0){//修改栏目
		global $catalogs,$vcps;
		if(!$caid) return;
		$this->basic_data();
		if($caid == $this->archive['caid']) return;
		if(!($catalog = read_cache('catalog',$caid,'',$this->archive['sid']))) return;
		if(!$this->auser->pmbypmids('aadd',$catalog['apmid'])) return;//检查会员在该栏目中的发布权限
		if(!in_array($this->archive['chid'],explode(',',$catalog['chids']))) return;
		if($this->archive['checked']){//只有已审的文档才去奖励积分
			$crids = array();
			if(!empty($catalog['awardcp']) && !empty($vcps['award'][$catalog['awardcp']])){
				$cparr = explode('_',$catalog['awardcp']);
				$crids[$cparr[0]] = $cparr[1];
			}
			if($crids){
				if(!$this->auser->crids_enough($crids)) return;
				$this->auser->updatecrids($crids,1,lang('awardcurrency'));
			}
		}
		$this->updatefield('caid',$caid,'main');
		$this->sale_define();//校正当前文档出售设置
		unset($catalog);
		$this->set_arcurl();
		$updatedb && $this->updatedb();
	}
	function arc_ccid($ids,$coid,$updatedb=0){//修改分类或取消分类的操作
		global $cotypes,$timestamp,$vcps;
		$this->basic_data();
		if($ids == $this->archive["ccid$coid"]) return;
		if($ids = array_filter(explode(',',$ids))){
			$oids = array_filter(explode(',',$this->archive["ccid$coid"]));
			foreach($ids as $k => $id){
				if(in_array($id,$oids)) continue;
				if(!($coclass = read_cache('coclass',$coid,$id)) || !$this->auser->pmbypmids('aadd',$coclass['apmid']) || !in_array($this->archive['chid'],explode(',',$coclass['chids']))){ unset($ids[$id]);continue; }
				if($this->archive['checked'] && $cotypes[$coid]['awardcp']){//只有已审的文档才去奖励积分
					$crids = array();
					if(!empty($coclass['awardcp']) && !empty($vcps['award'][$coclass['awardcp']])){
						$cparr = explode('_',$coclass['awardcp']);
						$crids[$cparr[0]] = $cparr[1];
					}
					if($crids){
						if(!$this->auser->crids_enough($crids)){ unset($ids[$id]);continue; }
						$this->auser->updatecrids($crids,1,lang('awardcurrency'));			
					}
				}
			}
			unset($coclass);
		}
		$this->updatefield("ccid$coid",$ids ? (empty($cotypes[$coid]['asmode']) ? $ids[0] : (','.implode(',',$ids).',')) : '','main');
		if($arr = multi_val_arr($this->archive["ccid$coid"],$cotypes[$coid],1)) foreach($arr as $x => $y) $this->updatefield("ccid{$coid}_$x",$y,'main');
		$this->sale_define();//校正当前文档出售设置
		$updatedb && $this->updatedb();
	}
	function arc_delete($isuser=0){
		global $db,$tblprefix,$enablestatic,$cotypes;
		if(empty($this->aid)) return false;
		$this->basic_data();
		if($isuser && $this->archive['checked']) return false; 

		//删除相应的txt存储文本
		$this->detail_data();
		foreach($this->namepres as $k) txtunlink($k);

		$wherestr = "WHERE aid='".$this->aid."'";
		foreach(array('comments','favorites','subscribes','answers','arecents','purchases','offers','replys',) as $var){//????????????????
			$db->query("DELETE FROM {$tblprefix}$var $wherestr", 'UNBUFFERED');
		}
		$db->query("DELETE FROM {$tblprefix}albums WHERE aid='".$this->aid."' OR pid='".$this->aid."'", 'UNBUFFERED');//合辑关系全部删除

		//删除相关已生成的静态文件
		$arcurl = arc_format($this->archive);
		for($i = 1;$i <= $this->channel['addnum'];$i ++) m_unlink(m_parseurl($arcurl,array('addno' => $i)));

		$db->query("DELETE FROM {$tblprefix}archives_".$this->archive['chid']." $wherestr", 'UNBUFFERED');
		$db->query("DELETE FROM {$tblprefix}archives_sub $wherestr", 'UNBUFFERED');
		$db->query("DELETE FROM {$tblprefix}archives_rec $wherestr", 'UNBUFFERED');
		$db->query("DELETE FROM {$tblprefix}archives $wherestr", 'UNBUFFERED');
		
		//数量统计
		$this->auser->basedeal('archive',0);
		$this->archive['checked'] && $this->auser->basedeal('check',0);

		$uploadsize = 0;
		$query = $db->query("SELECT * FROM {$tblprefix}userfiles WHERE tid='1' AND aid='".$this->aid."'");
		while($item = $db->fetch_array($query)){
			$ufile = local_file($item['url']);
			@unlink($ufile);
			clear_dir($ufile.'_s',true);
			$uploadsize += ceil($item['size'] / 1024);
		}
		$this->auser->updateuptotal($uploadsize,'reduce',1);
		$db->query("DELETE FROM {$tblprefix}userfiles $wherestr", 'UNBUFFERED');
		$this->init();
		return true;
	}
	function arc_nums($dname,$add=1,$updatedb=0){
		$this->basic_data();
		if(in_array($dname,array('orders','ordersum','comments','answers','adopts','favorites','praises','debases','scores','downs','plays','offers','replys','reports'))){
			$this->updatefield($dname,max(0,$this->archive[$dname] + $add),in_array($dname,array('reports')) ? 'sub' : 'main');
			updaterecent($this->aid,$dname,$add);
		}
		$updatedb && $this->updatedb();
	}
	function newoffer($price = 0,$storage = -1){//只可以存在会员中心或前台，返回错误提示
		global $timestamp,$db,$tblprefix,$curuser;
		$this->basic_data(0);
		if(!$curuser->info['mid']) return 'nousernoofferpermis';
		if(!$this->aid || !$this->archive['checked']) return 'chooseproduct';
		if($cid = $db->result_one("SELECT cid FROM {$tblprefix}offers WHERE mid='".$curuser->info['mid']."' AND aid='".$this->aid."'")) return 'offerexist';
		if(!$this->channel['offer'] || !($commu = read_cache('commu',$this->channel['offer']))) return 'nooffercommu';
		if($commu['allowance'] && @$curuser->info['cuallowance'] <= @$curuser->info['cuaddmonth']) return 'owancecommuamooverlim';
		if(!$curuser->pmbypmids('cuadd',$commu['setting']['apmid'])) return 'noofferpms';
		
		$db->query("INSERT INTO {$tblprefix}offers SET
			aid='".$this->aid."',
			cuid='$commu[cuid]',
			mid='".$curuser->info['mid']."',
			mname='".$curuser->info['mname']."',
			checked='".(empty($commu['setting']['autocheck']) ? 0 : 1)."',
			oprice='$price',
			storage='$storage',
			createdate='$timestamp',
			enddate='".(empty($commu['setting']['vdays']) ? 0 : $timestamp + 86400 * $commu['setting']['vdays'])."',
			refreshdate='$timestamp',
			updatedate='$timestamp'
			");
		if($commu['allowance']) $curuser->updatefield('cuaddmonth',$curuser->info['cuaddmonth'] + 1,'main');//限额文档统计
		$curuser->basedeal('offer',1,1,1);
		$this->arc_nums('offers',1,1);
		return '';
	}
	function inalbumsqlstr($pre='',$inits=array()){//粗选当前合辑允许加载的已有内容//排除当前的模型
		if(!$this->channel['isalbum'] || !$this->channel['inchids']) return '';
		$inchids = array_diff(explode(',',$this->channel['inchids']),array($this->archive['chid']));
		$inits && $inchids = array_intersect($inchids,$inits);
		if(!$inchids) return '';
		$sqlstr = "WHERE {$pre}aid!=".$this->aid." AND {$pre}chid ".multi_str($inchids);//排除自身
		$this->channel['oneuser'] && $sqlstr .= " AND {$pre}mid='".$this->archive['mid']."'";
		return $sqlstr;
	}
	function set_chidstr($pre='',$inits=array()){//取得允许归入的合辑类型id的查询字串//不能归入到同类的模型中
		global $channels;
		$chids = $uchids = array();//后者中个人合辑的合辑类型数组。
		$chidstr = $uchidstr = '';
		foreach($channels as $k => $v){
			$v = read_cache('channel',$k);
			if(!$v['isalbum'] || ($k == $this->archive['chid']) || $v['onlyload'] || !in_array($this->archive['chid'],explode(',',$v['inchids']))) continue;//如果不允许归辑或只是加载性合辑，则不能归入
			if(!$inits || in_array($k,$inits)) ${$v['oneuser'] ? 'uchids' : 'chids'}[] = $k;
		}
		$chids && $chidstr = "{$pre}chid ".multi_str($chids);
		$uchids && $uchidstr = "({$pre}chid ".multi_str($uchids)." AND mid='".$this->archive['mid']."')";
		$sqlstr = $chidstr && $uchidstr ? "($chidstr OR $uchidstr)" : $chidstr.$uchidstr;
		unset($chids,$uchids,$chidstr,$uchidstr,$v);
		if(!$sqlstr) return '';
		$sqlstr .= " AND {$pre}aid!=".$this->aid;//排除自身
		return $sqlstr;
	
	}
	function set_album($aid=0,$load=0){//将当前文件归入到$aid的合辑中//$load表示归辑方式，1为主动载入
		global $db,$tblprefix,$enablestatic;
		if(empty($aid)) return false;
		$this->basic_data(0);
		if($aid == $this->archive['aid']) return false;
		if(!($row = $db->fetch_one("SELECT aid,chid,mid,sid FROM {$tblprefix}archives WHERE aid=$aid"))) return false;//$aid不存在
		if($this->archive['chid'] == $row['chid']) return false;//不能归入到相同的模型中
		if(!($channel = read_cache('channel',$row['chid'])) && !$channel['isalbum']) return false;//$aid不是合辑
		if($channel['oneuser'] && ($row['mid'] != $this->archive['mid'])) return false;//个人性质的合辑
		if($channel['onlyload'] && !$load) return false;//载入型合辑，只能在载入动作时归辑
		if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}albums WHERE aid='".$this->archive['aid']."' AND pid='$row[aid]'")) return false;//此合辑记录已存在
		if($channel['onlyone'] && $db->result_one("SELECT COUNT(*) FROM {$tblprefix}albums WHERE aid='".$this->archive['aid']."' AND pchid='$row[chid]'")) return false;//已经归入到其它同类型合辑中
		if($channel['maxnums']){//$aid中的辑内最大数量限制
			$counts = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}albums WHERE pid='".$row['aid']."'");
			if($counts > $channel['maxnums']) return false;
		}
		if(!in_array($this->archive['chid'],explode(',',$channel['inchids']))) return false;
		$sqlstr = "aid='".$this->archive['aid']."',pid='$aid',chid='".$this->archive['chid']."',pchid='$row[chid]'";//合辑记录的基本信息
		$channel['inautocheck'] && $sqlstr .= ",checked=1";
		$db->query("INSERT INTO {$tblprefix}albums SET $sqlstr");
		unset($row);
		return true;
	}	
	function readd($backarea=0,$updatedb=0){//$backarea表示是否在管理后台操作//与限额是否有关?????
		global $timestamp;
		$this->basic_data();
		if(!$this->aid || !$this->archive['checked']) return false;
		if(!$this->channel['readd']) return false;
		if(!$backarea && $this->channel['readd'] == 1) return false;
		if($this->channel['reinterval'] && $timestamp - $this->archive['refreshdate'] < $this->channel['reinterval'] * 3600) return false;

		//是否有权限进行重发布,没有添加权限则没有重发权限
		if(!$this->auser->checkforbid('issue')) return false;//屏蔽组
		if(!($catalog = read_cache('catalog',$this->archive['caid'],'',$this->archive['sid'])) || !$this->auser->pmbypmids('aadd',$catalog['apmid'])) return false;//所在栏目不让发表
		if(!$this->auser->pmbypmids('aadd',$this->channel['apmid'])) return false;//没有模型发布权限
		if($this->channel['allowance'] && @$this->auser->info['arcallowance']){
			$adds = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}archives WHERE mid='".$this->archive['mid']."' AND chid='".$this->archive['chid']."'");
			if($adds >= $this->auser->info['arcallowance']) return false;
		}
		$this->updatefield('refreshdate',$timestamp,'main');
		$updatedb && $this->updatedb();
	}
	function reset_validperiod($days=0,$updatedb=0){//
		global $timestamp;
		$this->basic_data();
		if(!$this->aid || !$this->archive['checked']) return false;

		if(!$this->channel['validperiod']) return false;
		if(($this->channel['validperiod'] == 1) && ($this->archive['enddate'] > $timestamp)) return false;//过期后重设有效期模式

		//是否有权限进行重设有效期,没有添加权限则没有重设有效期权限
		if(!$this->auser->checkforbid('issue')) return false;//屏蔽组
		if(!($catalog = read_cache('catalog',$this->archive['caid'],'',$this->archive['sid'])) || !$this->auser->pmbypmids('aadd',$catalog['apmid'])) return false;//所在栏目不让发表
		if(!$this->auser->pmbypmids('aadd',$this->channel['apmid'])) return false;//没有模型发布权限
		
		//如何分析会员的权限期限????????????????????/
		$days = max(0,intval($days));
		if($this->channel['mindays']) $days = max($days,$this->channel['mindays']);
		if($this->channel['maxdays']) $days = min($days,$this->channel['maxdays']);
		$this->updatefield('enddate',$timestamp + $days * 24 * 3600,'main');
		$updatedb && $this->updatedb();
	}
	function updatefield($fieldname,$newvalue,$mode='main'){
		if(in_array($mode,array('main','sub'))){
			$this->basic_data();
		}elseif($mode == 'custom'){
			$this->detail_data();
		}
		if($this->archive[$fieldname] != stripslashes($newvalue)){
			$this->archive[$fieldname] = stripslashes($newvalue);
			$this->updatearr[$mode][$fieldname] = $newvalue;
		}
	}
	function updatedb(){
		global $db,$tblprefix,$timestamp;
		if(empty($this->aid)) return;
		
		//在这里分析函数字段的值的变化
		foreach($this->fields as $k => $v){//一旦有更新，重新计算函数字段。
			if($v['available'] && $v['isfunc']){
				$this->detail_data();//可以使用所有字段的值来计算函数值。
				if(empty($v['istxt'])){
					$this->updatefield($k,field_func($v['func'],$this->archive,$arr2=''),$v['tbl']);
				}else saveastxt(stripslashes(field_func($v['func'],$this->archive,$arr2='')),$aedit->namepres[$k]);
			}
		}

		foreach(array('main','sub','custom') as $upmode){
			if(!empty($this->updatearr[$upmode])){//只要数组存在，就是内容作了修改
				$this->updatearr['main']['updatedate'] = $timestamp;
				$sqlstr = '';
				foreach($this->updatearr[$upmode] as $k => $v){
					$sqlstr .= ($sqlstr ? "," : "").$k."='".$v."'";
				}
				if(!empty($sqlstr)){
					$tablename = $upmode == 'main' ? 'archives' : ($upmode == 'sub' ? 'archives_sub' : 'archives_'.$this->channel['chid']);
					$db->query("UPDATE {$tblprefix}$tablename SET $sqlstr WHERE aid={$this->aid}");
				}
			}
		}
		$this->updatearr && notice_static($this->aid);
		$this->updatearr = array();
	}
}
?>
