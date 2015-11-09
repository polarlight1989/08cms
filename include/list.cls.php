<?
include_once M_ROOT.'./include/archive.fun.php';
include_once M_ROOT.'./include/marchive.fun.php';
class cls_list{
	var $tmode = 'c';
	var $tclass = '';
	var $tname = '';
	var $tag = array();
	var $items = array();
	var $nowpage = 1;
	var $temparr = array();
	var $midarr = array();
	var $item = array();
	var $no_ctag = 0;//是否要解析标识内的c标记
	var $ret_null = 0;//当标识内容为空时，整个标识返回空
	var $cell_fill = 0;
	function __construct(){
		$this->cls_list();
	}
	function cls_list(){
	}
	function parse($tname,&$tag,&$temparr,$tmode='c'){
		$this->tmode = $tmode;
		$this->tname = $tname;
		$this->temparr = $temparr;
		$this->nowpage = empty($temparr['nowpage']) ? 1 : intval(max(1,$temparr['nowpage']));
		$this->tag = &$tag;
		$this->tclass = $this->tag['tclass'];
		$this->pre_deal();
		if($this->ret_null) return '';
		$result = $this->layout();
		return $result;
	}
	function layout(){
		$template = $this->tag['template'];
		$icount = min($this->temparr['limits'],count($this->items));
		list($rowblock,$colblock) = $this->fetch_block($template);
		$colcount = empty($colblock) ? 1 : (empty($this->tag['cols']) ? 1 : $this->tag['cols']);
		$rowcount = ceil($icount / $colcount);
		$newrows = $newcols = $newrow = '';
		for($i = 0;$i < $colcount*$rowcount;$i++){//如果是空集以下的操作跳过
			$filled = isset($this->items[$i]);
			$this->item = $filled ? $this->items[$i] : array();
			$this->item['sn_cell'] = $i + 1;//在列表中以原始数据来解析当前的单元序号
			$filled ? $this->deal_incell() : $this->fill_blank();
			if($colblock){
				$this->item['sn_row'] = ceil(($i + 1) / $colcount);//在列表中以原始数据来解析当前的行序号
				if($filled || $this->cell_fill){
					$newcol = $colblock;
					nreplace($newcol,'up',$this->item);
					!$this->no_ctag && nreplace($newcol,'c',$this->midarr);//单元内的c标识
					nreplace($newcol,'u',$this->item);
					nreplace($newcol,'b',$this->item);
				}else $newcol = '';
				$newcols .= $newcol;
				if(!(($i+1) % $colcount)){
					$newrow = $this->block_replace('col',$newcols,$rowblock);
					nreplace($newrow,'up',$this->item);
					!$this->no_ctag && nreplace($newrow,'c',$this->midarr);//单元外的c标识
					nreplace($newrow,'u',$this->item);
					nreplace($newrow,'b',$this->item);
					$newrows .= $newrow;
					$newcols = $newrow = '';
				}
			}else{
				$this->item['sn_row'] = $i + 1;//在列表中以原始数据来解析当前的行序号
				$newrow = $rowblock;
				nreplace($newrow,'up',$this->item);
				!$this->no_ctag && nreplace($newrow,'c',$this->midarr);//单元内的c标识
				nreplace($newrow,'u',$this->item);
				nreplace($newrow,'b',$this->item);
				$newrows .= $newrow;
				$newrow = '';
			}
		}
		$template = $this->block_replace('row',$newrows,$template);
		nreplace($template,'up',$this->temparr);
		!$this->no_ctag && nreplace($template,'c',$this->temparr);
		nreplace($template,'u',$this->temparr);
		nreplace($template,'b',$this->temparr);
		return $template;
	}
	function fetch_block($template){//将内嵌的c,u标识用临时符号取代，得到行列块之后，再替换回去。
		if(preg_match_all("/\{(u|c)\\$(.+?)\s+(.*?)\{\/\\1\\$\\2\}/is",$template,$matches)){
			foreach($matches[0] as $k => $v) $template = str_replace($v,'{{'.$matches[1][$k].'-'.$matches[2][$k].'}}',$template);
		}
		$rets = array();
		$rets[0] = preg_match("/\[row\](.+?)\[\/row\]/is",$template,$smatches) ? $smatches[1] : $template;
		$rets[1] = preg_match("/\[col\](.+?)\[\/col\]/is",$rets[0],$smatches) ? $smatches[1] : '';
		if(!empty($matches)){
			foreach($matches[0] as $k => $v){
				$rets[0] = str_replace('{{'.$matches[1][$k].'-'.$matches[2][$k].'}}',$v,$rets[0]);
				$rets[1] = str_replace('{{'.$matches[1][$k].'-'.$matches[2][$k].'}}',$v,$rets[1]);
			}
		}
		unset($matches,$smatches,$template);
		return $rets;
	}
	function block_replace($mode='row',&$tostr,$template){//将内嵌的c,u标识去除后替换其中行列内容块
		if(preg_match_all("/\{(u|c)\\$(.+?)\s+(.*?)\{\/\\1\\$\\2\}/is",$template,$matches)){
			foreach($matches[0] as $k => $v) $template = str_replace($v,'',$template);
		}
		if($mode == 'row'){
			$ret = preg_match("/\[row\](.+?)\[\/row\]/is",$template) ? preg_replace("/\[row\](.+?)\[\/row\]/is",$tostr,$template) : $tostr;
		}else $ret = preg_replace("/\[col\](.+?)\[\/col\]/is",$tostr,$template);
		unset($matches,$template);
		return $ret;
	}
	function pre_deal(){
		$this->temparr['limits'] = empty($this->tag['limits']) ? 10 : $this->tag['limits'];
		switch($this->tclass){
			case 'archives':
				global $db,$tblprefix;
				if($sqlstr = arc_sqlstr($this->tag,$this->temparr,$this->tmode)){
					$sqlstr .= " LIMIT ".($this->tmode == 'p' ? ($this->nowpage - 1) * $this->temparr['limits'] : (empty($this->tag['startno']) ? 0 : $this->tag['startno'])).",".$this->temparr['limits'];
					$query = $db->query($sqlstr);
					while($archive = $db->fetch_array($query)) $this->items[] = $archive;
				}
			break;
			case 'marchives':
				global $db,$tblprefix;
				if($sqlstr = marc_sqlstr($this->tag,$this->temparr,$this->tmode)){
					$sqlstr .= " LIMIT ".($this->tmode == 'p' ? ($this->nowpage - 1) * $this->temparr['limits'] : (empty($this->tag['startno']) ? 0 : $this->tag['startno'])).",".$this->temparr['limits'];
					$query = $db->query($sqlstr);
					while($archive = $db->fetch_array($query)) $this->items[] = $archive;
				}
			break;
			case 'alarchives'://辑内文档列表
				global $db,$tblprefix;
				if($sqlstr = alarc_sqlstr($this->tag,$this->temparr,$this->tmode)){
					$sqlstr .= " LIMIT ".(($this->tmode == 'p') ? (($this->nowpage - 1) * $this->temparr['limits']) : (empty($this->tag['startno']) ? 0 : $this->tag['startno'])).",".$this->temparr['limits'];
					$query = $db->query($sqlstr);
					while($archive = $db->fetch_array($query)) $this->items[] = $archive;
				}
			break;
			case 'functions'://自定函数列表
				global $templatedir;
				@include_once M_ROOT."./template/$templatedir/function/utags.fun.php";
				$this->temparr['nowpage'] = $this->nowpage;
				$this->items = functions_arr($this->tag,$this->temparr,$this->tmode);
			break;
			case 'relates'://相关文档列表
				global $db,$tblprefix;
				if($sqlstr = rel_sqlstr($this->tag,$this->temparr,$this->tmode)){
					$sqlstr .= " LIMIT ".(($this->tmode == 'p') ? (($this->nowpage - 1) * $this->temparr['limits']) : 0).",".$this->temparr['limits'];
					$query = $db->query($sqlstr);
					while($archive = $db->fetch_array($query)) $this->items[] = $archive;
				}
			break;
			case 'farchives'://附属信息列表
				global $db,$tblprefix,$m_thumb;
				if($sqlstr = farc_sqlstr($this->tag,$this->temparr,$this->tmode)){
					$sqlstr .= " LIMIT ".(($this->tmode == 'p') ? (($this->nowpage - 1) * $this->temparr['limits']) : (empty($this->tag['startno']) ? 0 : $this->tag['startno'])).",".$this->temparr['limits'];
					$query = $db->query($sqlstr);
					while($row = $db->fetch_array($query)){
						$row['arcurl'] = view_farcurl($row['aid'],$row['arcurl']);
						arr_tag2atm($row,'f');
						$this->items[] = $row;
					}
				}
			break;
			case 'commus'://交互记录列表
				global $db,$tblprefix,$commus,$fields,$curuser;
				if($sqlstr = cu_sqlstr($this->tag,$this->temparr,$this->tmode)){
					$sqlstr .= " LIMIT ".(($this->tmode == 'p') ? (($this->nowpage - 1) * $this->temparr['limits']) : 0).",".$this->temparr['limits'];
					$query = $db->query($sqlstr);
					while($row = $db->fetch_array($query)) $this->items[] = $row;
				}
			break;
			case 'mcommus'://交互记录列表
				global $db,$tblprefix,$mcommus,$fields,$curuser;
				if($sqlstr = mcu_sqlstr($this->tag,$this->temparr,$this->tmode)){
					$sqlstr .= " LIMIT ".(($this->tmode == 'p') ? (($this->nowpage - 1) * $this->temparr['limits']) : 0).",".$this->temparr['limits'];
					$query = $db->query($sqlstr);
					while($row = $db->fetch_array($query)) $this->items[] = $row;
				}
			break;
			case 'members'://会员列表
				global $db,$tblprefix;
				if($sqlstr = mem_sqlstr($this->tag,$this->temparr,$this->tmode)){
					$sqlstr .= " LIMIT ".(($this->tmode == 'p') ? (($this->nowpage - 1) * $this->temparr['limits']) : 0).",".$this->temparr['limits'];
					$query = $db->query($sqlstr);
					while($row = $db->fetch_array($query)) $this->items[] = $row;
				}
				$this->no_ctag = 1;
			break;
			case 'searchs'://搜索文档列表
				global $db,$tblprefix,$timestamp;
				$sqlstr = empty($this->tag['detail']) || empty($this->temparr['chid']) ? "SELECT a.* " : "SELECT a.*,c.*";
				$validstr = !empty($this->tag['validperiod']) ? " AND (a.enddate='0' OR a.enddate>'$timestamp')" : '';//有效期分析
				$sqlstr .= $this->temparr['sqlstr'].$validstr.' '.$this->temparr['orderstr']." LIMIT ".(($this->nowpage - 1) * $this->temparr['limits']).",".$this->temparr['limits'];
				$query = $db->query($sqlstr);
				while($archive = $db->fetch_array($query)) $this->items[] = $archive;
			break;
			case 'masearchs'://搜索文档列表
				global $db,$tblprefix,$timestamp,$coyptes;
				$sqlstr = 'ma.*,m.mchid,m.caid';
				foreach($cotypes as $k => $v) $sqlstr .= ",m.ccid$k";
				$sqlstr .= $this->temparr['sqlstr'] ." LIMIT ".($this->nowpage - 1) * $this->temparr['limits'].",".$this->temparr['limits'];
				$query = $db->query($sqlstr);
				while($archive = $db->fetch_array($query)) $this->items[] = $archive;
			break;
			case 'msearchs'://搜索文档列表
				global $db,$tblprefix;
				if($this->tmode == 'c'){//可以引用$timestamp及其它公用参数
					$sqlstr = "SELECT m.*,s.* ";
					$this->tag['sqlstr'] = stripslashes(str_replace('{$tblprefix}',$tblprefix,$this->tag['sqlstr']));
					$this->tag['sqlstr'] = sqlstr_replace($this->tag['sqlstr'],$this->temparr);
				}else $sqlstr = empty($this->tag['detail']) || empty($this->temparr['mchid']) ? "SELECT m.*,s.* " : "SELECT m.*,s.*,c.*";
				$sqlstr .= ($this->tmode == 'p' ? $this->temparr['sqlstr'] : $this->tag['sqlstr'])." LIMIT ".($this->tmode == 'p' ? ($this->nowpage - 1) * $this->temparr['limits'] : 0).",".$this->temparr['limits'];
				$query = $db->query($sqlstr);
				while($row = $db->fetch_array($query)){
					$this->items[] = $row;
				}
			break;
			case 'outinfos'://自由调用列表
				$this->items = outinfos_arr($this->tag,$this->temparr,$this->tmode,$this->nowpage);
			break;
			case 'catalogs'://类目列表//只需要先取出id
				global $acatalogs,$cotypes,$sid;
				$nsid = empty($this->tag['nsid']) ? 0 : intval($this->tag['nsid']);
				if(!$nsid){
					$nsid = isset($temparr['nsid']) ? $temparr['nsid'] : $sid;//当前子站需要分析是否有传下来的id，否则认为是全局id
				}elseif($nsid == -1) $nsid = 0;
				if($nsid == -2){
					$ncatalogs = &$acatalogs;
					$nsid = $sid;//为没有栏目因素的节点
				}else $ncatalogs = read_cache('catalogs','','',$nsid);

				if($this->tag['listby'] == 'ca'){
					$caids = array();
					if(empty($this->tag['casource'])){
						foreach($ncatalogs as $k => $v) if(empty($v['level'])) $caids[] = $k;
					}elseif($this->tag['casource'] == 1){
						if(!empty($this->tag['caids'])){
							$tcaids = explode(',',$this->tag['caids']);
							foreach($tcaids as $k) if(isset($ncatalogs[$k])) $caids[] = $k;
						}
					}elseif($this->tag['casource'] == 2){//激活栏目的子栏目
						if(!empty($this->temparr['caid'])){
							foreach($ncatalogs as $k => $v) if($v['pid'] == $this->temparr['caid']) $caids[] = $k;
						}
					}elseif($this->tag['casource'] == 3){
						if(!empty($this->temparr['caid'])){
							foreach($ncatalogs as $k => $v) if($v['pid'] == $ncatalogs[$this->temparr['caid']]['pid']) $caids[] = $k;
						}
					}elseif($this->tag['casource'] == 4){
						foreach($ncatalogs as $k => $v) if($v['level'] == 1) $caids[] = $k;
					}elseif($this->tag['casource'] == 5){
						foreach($ncatalogs as $k => $v) if($v['level'] == 2) $caids[] = $k;
					}
					if(!empty($this->tag['orderstr']) && $caids){
						$dorder = array();
						foreach($caids as $k){
							$catalog = read_cache('catalog',$k,'',$ncatalogs[$k]['sid']);
							if(!isset($catalog[$this->tag['orderstr']])) break;
							$dorder[] = $catalog[$this->tag['orderstr']];
						}
						if($dorder) array_multisort($dorder,empty($this->tag['asc']) ? SORT_DESC : SORT_ASC,$caids);
					}
					foreach($caids as $k) if($this->temparr['limits'] > count($this->items)) $this->items[] = array('caid' => $k,'sid' => $ncatalogs[$k]['sid']);
				}else{
					$coid = str_replace('co','',$this->tag['listby']);
					if(!$coclasses = read_cache('coclasses',$coid)) return;
					$ccids = array();
					if(empty($this->tag['cosource'.$coid])){
						foreach($coclasses as $k => $v) if(empty($v['level'])) $ccids[] = $k;
					}elseif(($this->tag['cosource'.$coid] == 1) && !empty($this->tag['ccids'.$coid])){
						$tccids = explode(',',$this->tag['ccids'.$coid]);
						foreach($tccids as $k) if(isset($coclasses[$k])) $ccids[] = $k;
					}elseif(($this->tag['cosource'.$coid] == 2) && !empty($this->temparr['ccid'.$coid])){
						foreach($coclasses as $k => $v) if($v['pid'] == $this->temparr['ccid'.$coid]) $ccids[] = $k;
					}elseif(($this->tag['cosource'.$coid] == 3) && !empty($this->temparr['ccid'.$coid])){
						foreach($coclasses as $k => $v) if($v['pid'] == $coclasses[$this->temparr['ccid'.$coid]]['pid']) $ccids[] = $k;
					}elseif($this->tag['cosource'.$coid] == 4){
						foreach($coclasses as $k => $v) if($v['level'] == 1) $ccids[] = $k;
					}elseif($this->tag['cosource'.$coid] == 5){
						foreach($coclasses as $k => $v) if($v['level'] == 2) $ccids[] = $k;
					}
					if(!empty($this->tag['orderstr']) && $ccids){
						$dorder = array();
						foreach($ccids as $k){
							$coclass = read_cache('coclass',$coid,$k);
							if(!isset($coclass[$this->tag['orderstr']])) break;
							$dorder[] = $coclass[$this->tag['orderstr']];
						}
						if($dorder) array_multisort($dorder,empty($this->tag['asc']) ? SORT_DESC : SORT_ASC,$ccids);
					}
					foreach($ccids as $k) if($this->temparr['limits'] > count($this->items)) $this->items[] = array('ccid' => $k,'sid' => $nsid);
				}
				unset($ncatalogs,$coclasses,$catalog,$coclass);
			break;
			case 'mcatalogs';//会员空间的类目列表
				global $mcatalogs,$uclasses;//这里的空间栏目必须首先是当前所用模板方案的有效栏目
				if($this->tag['listby'] == 'ca'){//全部栏目
					$caids = array();
					if(empty($this->tag['casource'])){
						foreach($mcatalogs as $k => $v) $this->items[] = $v;
					}elseif($this->tag['casource'] == 1){//指定栏目
						if(!empty($this->tag['caids'])){
							$caids = explode(',',$this->tag['caids']);
							foreach($mcatalogs as $k => $v) if(in_array($k,$caids)) $this->items[] = $v;
						}
					}
				}elseif($this->tag['listby'] == 'uc'){
					if(!empty($this->temparr['mcaid'])){//一定必须是激活栏目下的分类
						foreach($uclasses as $k => $v) if($v['mcaid'] == $this->temparr['mcaid']) $this->items[] = $v;
					}
				}
			break;
			case 'vote'://投票模块
				global $db,$tblprefix,$timestamp;
				$this->no_ctag = 1;
				$vid = empty($this->tag['vid']) ? (empty($this->temparr['vid']) ? 0 : $this->temparr['vid']) : $this->tag['vid'];
				if(!$vid){
					$this->ret_null = 1;
					return;
				}
				if(!($vote = $db->fetch_one("SELECT * FROM {$tblprefix}votes WHERE vid='$vid' AND checked=1 AND (enddate=0 OR enddate>$timestamp)"))){
					$this->ret_null = 1;
					return;
				}
				$vote['content'] = mnl2br($vote['content']);
				foreach(array('vid','subject','content','totalnum','enddate','mid','mname','createdate') as $var){
					$this->temparr[$var] = $vote[$var];
				}
				$query = $db->query("SELECT * FROM {$tblprefix}voptions WHERE vid='$vid' ORDER BY vieworder,vopid");
				while($item = $db->fetch_array($query)){
					$item['input'] = !$vote['ismulti'] ? "<input type=\"radio\" value=\"".$item['vopid']."\" name=\"vopids[]\">" : "<input type=\"checkbox\" value=\"".$item['vopid']."\" name=\"vopids[]\">";
					$item['percent'] = $vote['totalnum'] ? @round($item['votenum'] / $vote['totalnum'],3) : 0;
					$item['percent'] = ($item['percent'] * 100).'%';
					$this->items[] = $item;
				}
				unset($vote,$item);
			break;
			case 'votes'://投票列表
				global $db,$tblprefix;
				if($sqlstr = v_sqlstr($this->tag,$this->tmode)){
					$sqlstr .= " LIMIT ".(($this->tmode == 'p') ? (($this->nowpage - 1) * $this->temparr['limits']) : 0).",".$this->temparr['limits'];
					$query = $db->query($sqlstr);
					while($item = $db->fetch_array($query)){
						$this->items[] = $item;
					}
				}
			break;
			case 'images'://有分页
				$tempfiles = @marray_slice(unserialize($this->temparr[$this->tag['tname']]),($this->nowpage - 1) * $this->temparr['limits'],$this->temparr['limits']);
				if(!empty($tempfiles)){
					foreach($tempfiles as $k => $v){
						$v['fid'] = $k;
						$v['url'] = view_atmurl($v['remote']);
						$this->items[] = $v;
					}
				}
				unset($tempfiles,$v);
				$this->no_ctag = 1;
			break;
			case 'files':
				$tempfiles = @marray_slice(unserialize($this->temparr[$this->tag['tname']]),0,$this->temparr['limits']);
				if(!empty($tempfiles)){
					foreach($tempfiles as $k => $v){
						$item = array();
						$item['fid'] = $k;
						$item['url'] = view_atmurl($v['remote']);
						$item['title'] = $v['title'];
						!empty($this->temparr['aid']) && $item['aid'] = $this->temparr['aid'];
						$item['tname'] = $this->tag['tname'];
						$this->items[] = $item;
					}
				}
				unset($tempfiles,$v,$item);
				$this->no_ctag = 1;
			break;
			case 'medias':
				$tempfiles = @marray_slice(unserialize($this->temparr[$this->tag['tname']]),0,$this->temparr['limits']);
				if(!empty($tempfiles)){
					foreach($tempfiles as $k => $v){
						$item = array();
						$item['fid'] = $k;
						$item['url'] = view_atmurl($v['remote']);
						$item['title'] = $v['title'];
						$item['player'] = empty($v['player']) ? 0 : $v['player'];
						!empty($this->temparr['aid']) && $item['aid'] = $this->temparr['aid'];
						$item['tname'] = $this->tag['tname'];
						$this->items[] = $item;
					}
				}
				unset($tempfiles,$v,$item);
				$this->no_ctag = 1;
			break;
			case 'flashs':
				$tempfiles = @marray_slice(unserialize($this->temparr[$this->tag['tname']]),0,$this->temparr['limits']);
				if(!empty($tempfiles)){
					foreach($tempfiles as $k => $v){
						$item = array();
						$item['fid'] = $k;
						$item['url'] = view_atmurl($v['remote']);
						$item['title'] = $v['title'];
						$item['player'] = empty($v['player']) ? 0 : $v['player'];
						!empty($this->temparr['aid']) && $item['aid'] = $this->temparr['aid'];
						$item['tname'] = $this->tag['tname'];
						$this->items[] = $item;
					}
				}
				unset($tempfiles,$v,$item);
				$this->no_ctag = 1;
			break;
			case 'arcfee':
				$temps = fee_arr($this->tag,$this->temparr);
				empty($temps) && $this->ret_null = 1;
				foreach($temps as $k => $v){
					$this->items[] = $v;
				}
				unset($temps,$v);
				$this->no_ctag = 1;
			break;
			case 'keywords':
				global $uwordlinks;
				load_cache('uwordlinks');
				if(empty($uwordlinks)) return;
				$temps = @marray_slice($uwordlinks,0,$this->temparr['limits']);
				foreach($temps['swords'] as $k =>$v){
					$this->items[] = array('word' => $v,'wordlink' => $temps['rwords'][$k]);
				}
				unset($temps,$k,$v);
				$this->no_ctag = 1;
			break;
			case 'subsites':
				global $subsites,$cmsname;
				if(empty($this->tag['source']) || (($this->tag['source'] == 2) && is_array($this->tag['sids']) && in_array('0',$this->tag['sids']))){
					$this->items[] = array('sid' => 0,'siteurl' => view_siteurl(0),'sitename' => $cmsname);
				}
				$i = 1;
				foreach($subsites as $k => $v){
					if($i > $this->temparr['limits']) break;
					if(($this->tag['source'] < 2) || (($this->tag['source'] == 2) && in_array($k,explode(',',$this->tag['sids'])))){
						$this->items[] = array('sid' => $k,'siteurl' => view_siteurl($k),'sitename' => $v['sitename']);
						$i ++;
					}
				}
			break;
			case 'channels':
				global $channels;
				if(empty($channels) || (!empty($this->tag['chsource']) && empty($this->tag['chids']))) return;
				$i = 1;
				foreach($channels as $k => $v){
					if($i > $this->temparr['limits']) break;
					if(empty($this->tag['chsource']) || (!empty($this->tag['chsource']) && in_array($k,explode(',',$this->tag['chids'])))){
						$this->items[] = array('chid' => $k,'title' => $v['cname']);
						$i ++;
					}
				}
			break;
			case 'mchannels':
				global $mchannels;
				if(empty($mchannels) || (!empty($this->tag['chsource']) && empty($this->tag['chids']))) return;
				$i = 1;
				foreach($mchannels as $k => $v){
					if($i > $this->temparr['limits']) break;
					if(empty($this->tag['chsource']) || (!empty($this->tag['chsource']) && in_array($k,explode(',',$this->tag['chids'])))){
						$this->items[] = array('mchid' => $k,'title' => $v['cname']);
						$i ++;
					}
				}
			break;
		}
	}
	function fill_blank(){
		if($this->tclass == 'images'){
			$this->item['url'] = $this->item['url_s'] = view_atmurl($this->tag['emptyurl']);
			$this->item['width'] = $this->tag['maxwidth'];
			$this->item['height'] = $this->tag['maxheight'];
			$this->cell_fill = 1;
		}
	}
	function deal_incell(){
		global $cotypes,$sid,$orelays,$acatalogs,$m_thumb;
		if(in_array($this->tclass,array('archives','alarchives','relates','searchs',))){
			arc_parse($this->item,$this->temparr['m_thumbid']);
		}elseif(in_array($this->tclass,array('marchives','masearchs',))){
			marc_parse($this->item,$this->temparr['m_thumbid']);
		}elseif($this->tclass == 'catalogs'){//这是一个特例(含节点)，在标识中会加入新元素，需要先处理midarr,
			//读取继承参数
			parse_str(cnstr($this->temparr),$midarr);
			if(isset($midarr['caid']) && @$this->tag['cainherit'] != 'active') unset($midarr['caid']);
			foreach($cotypes as $k => $v){
				if(isset($midarr['ccid'.$k]) && @$this->tag['coinherit'.$k] != 'active') unset($midarr['ccid'.$k]);
			}

			$listby = $this->tag['listby'] == 'ca' ? 0 : intval(str_replace('co','',$this->tag['listby']));
			if(!$listby){//先处理非列表项目，因为列表项id要放在最后
				foreach($cotypes as $k => $v){//读取手动指定的参数
					if($v['sortable'] && !empty($this->tag['coinherit'.$k]) && is_numeric($this->tag['coinherit'.$k])) $midarr['ccid'.$k] = $this->tag['coinherit'.$k];
				}
				unset($midarr['caid']);
				$midarr['caid'] = $this->item['caid'];
			}else{
				$coid = $listby;
				if(!empty($this->tag['cainherit']) && is_numeric($this->tag['cainherit'])) $midarr['caid'] = $this->tag['cainherit'];
				foreach($cotypes as $k => $v){
					(($k != $coid) && $v['sortable'] && !empty($this->tag['coinherit'.$k]) && is_numeric($this->tag['coinherit'.$k])) && $midarr['ccid'.$k] = $this->tag['coinherit'.$k];
				}
				unset($midarr['ccid'.$coid]);
				$midarr['ccid'.$coid] = $this->item['ccid'];
			}
			if(!empty($this->tag['urlmode']) && !empty($midarr[$this->tag['urlmode']])) $midarr = array_merge(array($this->tag['urlmode'] => $midarr[$this->tag['urlmode']]),$midarr);
			
			$nsid = empty($this->tag['nsid']) ? 0 : intval($this->tag['nsid']);
			if(!$nsid){
				$nsid = isset($temparr['nsid']) ? $temparr['nsid'] : $sid;//当前子站需要分析是否有传下来的id，否则认为是全局id
			}elseif($nsid == -1){
				$nsid = 0;
			}elseif($nsid == -2) $nsid = $sid;

			$cnstr = cnstr($midarr);
			$this->item = cn_parsearr($cnstr,$nsid,$listby,$this->temparr['m_thumbid']);
			$cnode = cnodearr($cnstr,$this->item['sid']);
			re_cnode($this->item,$cnstr,$cnode);
			unset($cnode,$midarr);
		}elseif($this->tclass == 'farchives'){
			$m_thumb->config[$this->temparr['m_thumbid']] = array('id' => $this->item['aid'],'mode' => 'fa','smode' => $this->item['chid'],);
		}elseif($this->tclass == 'commus'){
			$m_thumb->config[$this->temparr['m_thumbid']] = array('id' => $this->item['cid'],'mode' => 'cu','smode' => @$this->tag['cuid'],);
		}elseif($this->tclass == 'mcommus'){
			$m_thumb->config[$this->temparr['m_thumbid']] = array('id' => $this->item['cid'],'mode' => 'mcu','smode' => @$this->tag['cuid'],);
		}elseif($this->tclass == 'mcatalogs'){
			if($this->tag['listby'] == 'ca'){
				$this->item['indexurl'] = mcn_url($this->item['mcaid']);
				$this->item['listurl'] = mcn_url($this->item['mcaid'],0,1);
			}elseif($this->tag['listby'] == 'uc'){
				$this->item['indexurl'] = mcn_url($this->item['mcaid'],$this->item['ucid']);
				$this->item['listurl'] = mcn_url($this->item['mcaid'],$this->item['ucid'],1);
			}
		}elseif($this->tclass == 'images'){
			if($this->tmode == 'p') $m_thumb->config[$this->temparr['m_thumbid']] = $m_thumb->config['main'];
			if(@$this->tag['thumb'] && @$this->tag['maxwidth'] && @$this->tag['maxheight'] && islocal($this->item['url'],1)){//生成缩略图或启用缩略图
				$true_local = islocal($this->item['url'],2);
				if(($true_local && is_file(local_atm($this->item['url']).'s/'.$this->tag['maxwidth'].'_'.$this->tag['maxheight'].'.jpg')) || (!$true_local && in_str($this->tag['maxwidth'].'_'.$this->tag['maxheight'],@$this->item['thumbs']))){//已生成缩略图
					$this->item['url_s'] = $this->item['url'].'s/'.$this->tag['maxwidth'].'_'.$this->tag['maxheight'].'.jpg';
				}else $this->item['url_s'] = @$m_thumb->thumb($this->item['url'],$this->temparr['m_thumbid'],$this->tag['tname'],$this->item['fid'],@$this->tag['maxwidth'],@$this->tag['maxheight']);
			}else $this->item['url_s'] = $this->item['url'];
			if($this->item['url_s'] != $this->item['url']){//真正启用了缩略图,直接使用设定的宽高
				$this->item['width'] = @$this->tag['maxwidth'];
				$this->item['height'] = @$this->tag['maxheight'];
			}else{//使用原图来重计宽高
				$wh = imagewh($this->item['url'],@$this->item['width'],@$this->item['height'],@$this->tag['maxwidth'],@$this->tag['maxheight']);
				$this->item['width'] = $wh['width'];
				$this->item['height'] = $wh['height'];
			}
		}elseif($this->tclass == 'members'){
			global $grouptypes;
			foreach($grouptypes as $k => $v){
				$this->item['grouptype'.$k.'name'] = '';
				if(!empty($this->item['grouptype'.$k])){
					$usergroups = read_cache('usergroups',$k);
					$this->item['grouptype'.$k.'name'] = $usergroups[$this->item['grouptype'.$k]]['cname'];
				}
			}
			$m_thumb->config = array('id' => $this->item['mid'],'mode' => 'm','smode' => $this->item['mchid'],);
			unset($usergroups,$v);
		}
		//从上级标识接受参数作为当前原始标识
		if(!empty($this->tag['rrelays'])){
			$midarr = array();
			$relays = relays2arr($this->tag['rrelays']);
			foreach($relays as $k => $v) if(isset($this->temparr[$v])) $midarr[$k] = $this->temparr[$v];
			$this->item += $midarr;
			unset($midarr);
		}
		$this->item += $this->temparr;

		//从当前原始标识向下级标识传送参数
		$this->midarr = $this->temparr;
		$relays = $orelays;
		!empty($this->tag['relays']) && $relays = relays2arr($this->tag['relays']) + $relays;
		foreach($relays as $k => $v) if(isset($this->item[$v])) $this->midarr[$k] = $this->item[$v];
		unset($relays);
	}
}
	
?>