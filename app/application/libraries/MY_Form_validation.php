<?php
class MY_Form_validation extends CI_Form_validation{    
	function __construct($config = array()){
		parent::__construct($config);
	}
	function mynum($val){
		$rval = intval($val);
		if(is_int($rval) && $rval > 0){
			return true;
		}else{
			return false;
		};
	}
	function check_equal_less($val, $field){
		$num = intval($_POST[$field]);
		return ($val >= $num ) ? true : false;
	}
	function chk_date($val){
		$strdate = str_replace(",", "", $val);
		$date = date('Y-m-d', strtotime($strdate));
		$cdate = date('Y-m-d');
		
		if($cdate < $date){
			return true;
		} else {
			return false;
		}
	}
}