<?php
/*---------------------------
|							|
|							|
|	  GENRAL FUNCTIONS		|
|							|
|							|
----------------------------*/

function date_to_year($date){
	$date = substr($date, 0, 4);
	return $date;
}

function date_to_month($date){
	$month = substr($date, 5, 2);
	switch ($month)
		{
		case '01':
			$month = 'January';
		break;
		case '02':
			$month = 'February';
		break;
		case '03':
			$month = 'March';
		break;
		case '04':
			$month = 'April';
		break;
		case '05':
			$month = 'May';
		break;
		case '06':
			$month = 'June';
		break;
		case '07':
			$month = 'July';
		break;
		case '08':
			$month = 'August';
		break;
		case '09':
			$month = 'September';
		break;
		case '10':
			$month = 'October';
		break;
		case '11':
			$month = 'November';
		break;
		case '12':
			$month = 'December';
		break;
		default:
		  $month = 'January';
		}
	return $month;
}

function month_to_date($month){
	switch ($month)
		{
		case 'January':
			$month = '01';
		break;
		case 'February':
			$month = '02';
		break;
		case 'March':
			$month = '03';
		break;
		case 'April':
			$month = '04';
		break;
		case 'May':
			$month = '05';
		break;
		case 'June':
			$month = '06';
		break;
		case 'July':
			$month = '07';
		break;
		case 'August':
			$month = '08';
		break;
		case 'September':
			$month = '09';
		break;
		case 'October':
			$month = '10';
		break;
		case 'November':
			$month = '11';
		break;
		case 'December':
			$month = '12';
		break;
		default:
		  $month = '01';
		}
	return $month;
}

function upper_case_word($word){
	return ucfirst($word);
}

function comma_to_amp($str){
	$arr = explode(",", $str);
	$return = '';
	foreach($arr as $value){
		$return .= $value.' & ';
	}
	$return = rtrim($return, " &");
	return $return;
}

function mulitple_array_bool($str){
	$arr = explode(",",$str);
	$count = count($arr);
	if($count > 1){
		return true;
	}
	else{
		echo false;
	}
}

function current_page(){
	$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} 
	else 
	{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}


?>