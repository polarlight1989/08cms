<?php
!defined('M_COM') && exit('No Permisson');
class cls_mysql{
	var $querynum = 0;
	var $link;
	function connect($dbhost,$dbuser,$dbpw,$dbname = '',$pconnect = 0,$halt = TRUE,$ncharset = ''){
		$func = !$pconnect ? 'mysql_connect' : 'mysql_pconnect';
		if(!$this->link = @$func($dbhost,$dbuser,$dbpw,1)){
			if($halt){
				$this->halt('Can not connect to MySQL server');
			}else return false;
		}else{
			if($this->version() > '4.1'){
				global $dbcharset,$mcharset;
				$ncharset = empty($ncharset) ? (empty($dbcharset) ? str_replace('-','',strtolower($mcharset)) : $dbcharset) : $ncharset;
				$serverset = $ncharset ? 'character_set_connection='.$ncharset.', character_set_results='.$ncharset.', character_set_client=binary' : '';
				$serverset .= $this->version() > '5.0.1' ? ((!$serverset ? '' : ',').'sql_mode=\'\'') : '';
				$serverset && mysql_query("SET $serverset", $this->link);
			}
			if($dbname && !@mysql_select_db($dbname, $this->link)){
				if($halt){
					$this->halt("Can not select database $dbname");
				}else return false;
			}
		}
		return true;
	}
	function select_db($dbname){
		return mysql_select_db($dbname, $this->link);
	}
	function fetch_array($query, $result_type = MYSQL_ASSOC){
		return mysql_fetch_array($query, $result_type);
	}
	function fetch_one($sql){
		return $this->fetch_array($this->query($sql));
	}
	function query($sql, $type = ''){
		$func = ($type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query')) ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link))){
			if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY'){
				$this->close();
				require M_ROOT.'./base.inc.php';
				$this->connect($dbhost,$dbuser,$dbpw,$dbname,$pconnect,true,$dbcharset);
				$this->query($sql,'RETRY'.$type);
			} elseif($type != 'SILENT' && substr($type, 5) != 'SILENT'){
				$this->halt('MySQL Query Error', $sql);
			}
		}
		$this->querynum++;
		return $query;
	}
	function affected_rows(){
		return mysql_affected_rows($this->link);
	}
	function error(){
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}
	function errno(){
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}
	function result($query, $row){
		$query = @mysql_result($query, $row);
		return $query;
	}
	function result_one($sql) {
		return $this->result($this->query($sql), 0);
	}
	function num_rows($query){
		$query = mysql_num_rows($query);
		return $query;
	}
	function num_fields($query){
		return mysql_num_fields($query);
	}
	function free_result($query){
		return mysql_free_result($query);
	}
	function insert_id(){
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}
	function fetch_row($query){
		$query = mysql_fetch_row($query);
		return $query;
	}
	function fetch_fields($query){
		return mysql_fetch_field($query);
	}
	function version(){
		return mysql_get_server_info($this->link);
	}
	function close(){
		return mysql_close($this->link);
	}
	function halt($message = '', $sql = ''){
		global $timestamp,$dbname,$tblprefix,$_no_dbhalt;
		if(empty($_no_dbhalt)) include M_ROOT.'./include/mysql.err.php';
	}
}

?>