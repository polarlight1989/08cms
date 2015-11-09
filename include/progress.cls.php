<?php
class Progress{
	function Progress($str = ''){
		$this->__construct($str);
	}

	function __construct($str = ''){
		echo '<script type="text/javascript" src="include/js/progress.js"></script><script type="text/javascript">var progress = new Progress("' . str_replace(array("\\", "\r", "\n", '"'), array("\\\\", "\\r", "\\n", '\"'), $str) . '")</script>';
		ob_implicit_flush();
	}
	
	function rate($rate){
		echo '<script type="text/javascript">progress.rate(' . $rate . ')</script>';
		ob_flush();
	}

	function show(){
		echo '<script type="text/javascript">progress.show()</script>';
		ob_flush();
	}

	function hide(){
		echo '<script type="text/javascript">progress.hide()</script>';
		ob_flush();
	}
	
	function pagecount($num){
		echo "<script type=\"text/javascript\">progress.pagecount($num)</script>";
		ob_flush();
	}
	
	function linkcount($num){
		echo "<script type=\"text/javascript\">progress.linkcount($num)</script>";
		ob_flush();
	}
	
	function content($num){
		echo "<script type=\"text/javascript\">progress.content($num)</script>";
		ob_flush();
	}
	
	function output($num){
		echo "<script type=\"text/javascript\">progress.output($num)</script>";
		ob_flush();
	}
}
?>