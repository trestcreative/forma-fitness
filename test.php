<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $page_title;?></title>
	<meta name="description" content="">
	<meta name="author" content="Trest Creative">
  
	<link rel="stylesheet" href="./css/styles.css">
	<link rel="stylesheet" href="./css/print.css" media="print">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/grid.css">
	<link rel="stylesheet" href="./css/text.css">
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="./js/misc_scripts.js"></script>


</head>
<?php

require_once ('./includes/mysql_connection.php');
require_once ('./functions/general_functions.php');

//Test class
require_once ('./classes/client_class.php');
//test obj

$a = 0;
		$ret = '';
		$z = 0;
		foreach($_POST['cprog_ex_sets'] as $value => $row){
			$i = 0;
			$curr_date = date("Y-m-d H:i:s");
			while($i < $row)
			{	
			$b = $a + $i;
				if(empty($_POST['cprog_ex_reps'][$b])){
					$_POST['cprog_ex_reps'][$b] = 0;
				}
				if(empty($_POST['cprog_ex_w'][$b])){
					$_POST['cprog_ex_w'][$b] = 0;
				}
				echo $curr_date;
				echo $_POST['ex_id'][$z].'</br>';
				echo $_POST['ro_id'].'</br>';
				echo $_POST['cprog_id'].'</br>';
				echo $_POST['cprog_ex_reps'][$b].'</br>';
				echo $_POST['cprog_ex_type'][$value].'</br>';
				echo $_POST['cprog_ex_w'][$b].'</br>';
				
				echo '<hr>';
				$i++;
				
				//insert into 
			}
			
			$a = $a + $row;
			$z++;
		} 

/* 
		 */ 
//Put in reps into an array

/* foreach($_POST['cprog_ex_reps'] as $key => $value){
	echo $value;

} */
/* print_r($sets);
$i = 0;
foreach ($sets as $set){
	
} */


?>

