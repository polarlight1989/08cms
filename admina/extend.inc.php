<?php
include_once dirname(dirname(__FILE__))."/include/general.inc.php";
$extend = $_GET['extend'];
 
if($extend){
	include dirname(__FILE__)."/extend/{$extend}.php";
}

?>