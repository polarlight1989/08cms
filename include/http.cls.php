<?php
@set_time_limit(0);
class http{
	var $link, $host, $port, $url, $status;
	var $ret, $content, $timestamp, $jump = 3, $timeout = 5;
	var $gets, $data, $cookie;
	var $puts = array(), $cookies = array(), $datas = array();
	function open(){//连接到服务器
		$this->link && fclose($this->link);
		if(!$this->link = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout))return false;
		socket_set_timeout($this->link, $this->timeout);
		return true;
	}
	function close(){
		$this->link && fclose($this->link);
		$this->link = 0;
	}
	function setHeader($key,$val){//添加自定义HTTP头
		$this->puts[$key]=$val;
	}
	function setCookie($key,$val){//发送COOKIE
		$this->cookies[$key]=$val;
	}
	function setCookies($str){//输入cookies字串
	
	}
	function setData($key,$val){//POST数据
		$this->datas[]=array($key,$val);
	}
	function query($uri,$mode='HEAD',$jump=0){//发送HTTP请求，并获得响应数据
		$this->ret=$ret=false;
		$jump || $this->timestamp=time();
		if($uri{0}!='/'){
			$uri=parse_url($uri);
			if(empty($uri['host']))return false;
			$this->host=$uri['host'];
			$this->port=isset($uri['port']) ? $uri['port'] : 80;
			$path=isset($uri['path']) ? $uri['path'] : '/';
			$uri=isset($uri['query']) ? "$path?$uri[query]" : $path;
		}else{
			$path=strpos($uri,'?');
			$path=$path===false ? $uri : substr($uri,0,$path);
		}
		if($jump>$this->jump||!$this->open())return false;
		$mode=strtoupper($mode);
		$this->putHeader($uri,$mode);//发送HTTP请求
		$this->gets=array();
		$flag = 0;
		while(!feof($this->link)){//获得HTTP头
			if(time()-$this->timestamp > $this->timeout)return false;
			if($tmp = rtrim(fgets($this->link, 4096))){
				if(!$flag){
					$flag = 1;
					$tmp=explode(' ', $tmp);
					if(empty($tmp[1]) || !is_numeric($tmp[1]))return false;
					$this->status=$tmp[1];//HTTP状态码
				}else{
					$tmp=explode(':',$tmp);
					$key=strtolower($tmp[0]);
					$tmp[1] = trim($tmp[1]);
					if($key=='set-cookie'){
						(empty($this->gets[$key]) && $this->gets[$key] = array($tmp[1])) || $this->gets[$key][] = $tmp[1];
						preg_match('/(.+?)=(.+?)(;|$)/', $tmp[1], $tmp) && $this->cookies[$tmp[1]] = $tmp[2];
					}else{
						$this->gets[$key] = $tmp[1];
					}
				}
			}elseif($flag){
				break;
			}
		}
		switch($this->status{0}){
		case '3'://重定向
			$uri = $this->fullurl($this->gets['location'], $path);
			if($uri){
				$this->timeout -= time() - $this->timestamp;
				$ret = $this->query($uri, $mode, $jump+1);
			}
			break;
		case '2':
			$ret = true;
		}
		return $ret;
	}
	function size($url,$all=0){//取得远程文件大小
		$this->query($url);
		return isset($this->gets['content-length']) ? intval($this->gets['content-length']) : ($all && $this->content() ? strlen($this->content) : false);
	}
	function exists($url){//远程文件是否存在
		return $this->query($url);
	}
	function istext($url=''){
		$url && $this->query($url);
		return isset($this->status) && isset($this->gets['content-type']) && $this->status{0}=='2' && strpos(strtolower($this->gets['content-type']),'text')===0;
	}
	function content($url='',$maxsize=0,$mode='GET'){//获得远程内容
		$url && $ret=$this->query($url,$mode);
		if($url && !$ret)return false;
		if($this->ret)return $this->content!=false;
		$maxsize*=1024;
		$size=0;
		$this->ret=true;
		$this->content='';
		while(!feof($this->link)){
			$size+=1024;
			if(time()-$this->timestamp > $this->timeout || ($maxsize && $size > $maxsize)){
				$this->close();
				$this->content=false;
				return false;
			}
			$this->content.=fread($this->link, 1024);
		}
		if(!empty($this->gets['content-encoding']) && preg_match('/\bgzip\b/', $this->gets['content-encoding']))$this->content = $this->gzdecode($this->content);
		return true;
	}
	function fetchtext($url,$mode='GET'){//取得远程文本
		isset($this->puts['Accept-Encoding']) || $this->puts['Accept-Encoding'] = 'gzip, deflate';
		$this->query($url,$mode);
		if(!$this->istext()){
			$this->close();
			return '';
		}
		return $this->content() ? $this->content : '';
	}
	function savetofile($url,$savename,$maxsize=0,$mode='GET'){//取得远程文件保存到本地，会覆盖同名文件
		if(!$this->query($url,$mode))return false;
		if($maxsize && isset($this->gets['content-length']) && intval($this->gets['content-length']) > $maxsize * 1024 || !$this->content('',$maxsize))return false;
		if(!$fp = fopen($savename,'wb'))return false;
		fwrite($fp,$this->content);
		fclose($fp);
		return true;
	}
	function gzdecode($data){
		$flags = ord(substr($data, 3, 1));
		$headerlen = 10;
		$extralen = 0;
		$filenamelen = 0;
		if($flags & 4) {
			$extralen = unpack('v' ,substr($data, 10, 2));
			$extralen = $extralen[1];
			$headerlen += 2 + $extralen;
		}
		// Filename
		if($flags & 8)$headerlen = strpos($data, chr(0), $headerlen) + 1;
		// Comment
		if($flags & 16)$headerlen = strpos($data, chr(0), $headerlen) + 1;
		// CRC at end of file
		if($flags & 2)$headerlen += 2;
		$unpacked = @gzinflate(substr($data, $headerlen));
		if($unpacked === false)$unpacked = $data;
		return $unpacked;
	}
	function putHeader($uri,$mode){//发送HTTP头
		$str="$mode $uri HTTP/1.1\r\nHost: $this->host\r\nAccept: */*\r\nUser-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		
		if(empty($this->cookie))$this->cookie='';elseif(!preg_match('/;\s$/',$this->cookie))$this->cookie.='; ';
		if(!empty($this->cookies)){
			foreach($this->cookies as $k => $v)$this->cookie.="$k=".urlencode($v).'; ';
			$this->cookies=array();
		}
		foreach($this->puts as $k => $v)$str.="$k: $v\r\n";
		empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) || $str .= "Accept-Language: $_SERVER[HTTP_ACCEPT_LANGUAGE]\r\n";
		empty($this->cookie) || $str.="Cookie: $this->cookie\r\n";
		if($mode=='POST'){
			empty($this->data) && $this->data='';
			if(!empty($this->datas)){
				foreach($this->datas as $v)$this->data.="&$v[0]=".urlencode($v[1]);
				$this->datas=array();
				$this->data{0}=='&' && $this->data=substr($this->data,1);
			}
			$str.="Content-type: application/x-www-form-urlencoded\r\nContent-length: " . strlen($this->data) . "\r\n";
		}
		$str.="Connection: close\r\n\r\n";
		fputs($this->link, $str);
		if($mode=='POST' && $this->data)fputs($this->link, $this->data);//发送POST数据
	}
	function fullurl($u,$p){//地址修正
		if(!$u || strpos($u,'://'))return $u;
		if($u{0}=='?')$u="$p$u";elseif($u{0}!='/')$u=substr($p,0,strrpos($p,'/')+1).$u;
		while(($s=strpos($u,'/../'))!==false)$u=($s ? substr($u,0,strrpos(substr($u,0,$s),'/')+1) : '/').substr($u,$s+4);
		return $u;
	}
}
?>