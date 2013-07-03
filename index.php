<?php # index.php
session_start();
ob_start();
ini_set("log_errors", 1);
ini_set("error_log", "./admin/core/php-error.log");
/* 
 *	This is the main page.
 *	This page includes the configuration file, 
 *	the templates, and any content-specific modules.
 */
if(!isset($_SESSION['username']) && (!isset($_SESSION['a_level']))){

header('Location: holding.php');
}
if(isset($_SESSION['a_level'])){
	if($_SESSION['a_level'] < 3){
		header('Location: holding.php');
	}
}
//Connect to database
require_once ('./includes/mysql_connection.php');

//General Functions
require_once ('./functions/general_functions.php');

//Classes
require_once ('./classes/article_class.php');
require_once ('./classes/training_class.php');
require_once ('./classes/client_class.php');


//Instance all class objects
$article = new articles($conn);
$training = new training($conn);
$client = new client($conn);

// Validate what page to show:
if (isset($_GET['p'])) {
	$p = $_GET['p'];
} elseif (isset($_POST['p'])) { // Forms
	$p = $_POST['p'];
} else {
	$p = NULL;
}

//Show login_strip if client is logged in
if(isset($_SESSION['client_username'])){
	$client_js = "
	<script>
		$(document).ready(function() {
			$('.login_strip').show();
			$('.login_strip_btn').hide();
			$('.profile_btn').show();
			$('.login_strip').css('position','relative');
		});
	</script>";
}
else{
	$client_js = "<script>
					$(document).ready(function() {
						$('.profile_btn').hide();
					});
				</script>";
}

// Determine what page to display:
switch ($p) {

	case 'home':
		$page = 'home.php';
		$page_title = 'Home';
		break;
	
	case 'training':
		$page = 'training.php';
		$page_title = 'Training';
		break;
	
	case 'articles':
		$page = 'articles.php';
		$page_title = 'Articles';
		break;
		
	case 'client':
		$page = 'client.php';
		$page_title = 'Client profile';
		break;	
		
	
		
	// Default is to include the main page.
	default:
		$page = 'home.php';
		$page_title = 'Site Home Page';
		break;
		
} // End of main switch.

// Make sure the file exists:
if (!file_exists('./pages/' . $page)) {
	$page = 'home.php';
	$page_title = 'Site Home Page';
}



// Include the header file:
include_once ('./includes/header.php');



// Include the content-specific module:
// $page is determined from the above switch.
include ('./pages/' . $page);

// Include the footer file to complete the template:
include_once ('./includes/footer.php');
ob_flush();
?>
