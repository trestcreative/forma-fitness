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
	
	<?php
		echo $client_js;
	?>
	
	

</head>
<body>
	<div class="login_strip">
		<?php if(isset($_SESSION['client_username'])){
		?>
		<form method="post" action="index.php?p=client"/>
			<input name="c_logout" type="submit" value="Logout"/>
			<?php
			echo "<label>Welcome ".$_SESSION['client_username']."</label>";
			?>
		</form>
		<?php
		}else{
		?>
		<form method="post" action="index.php?p=client"/>
			<input type="text" name="c_login_username" placeholder="Username"/>
			<input type="password" name="c_login_password" placeholder="Password"/>
		
		<input name="c_login_submit" type="submit" value="Login"/>
		</form>
		<?php
		}
		?>
		
	</div>
	
	<div class="wrapper">
		
		<header class="container">
			<div class="col_2">
				<img style="width:75%;" src="./images/icons/logo.png"/>
			</div>
			<div class="col_1 push_5">
				<button type="button" class="login_strip_btn">Client Login</button>
				<a href="index.php?p=client"><button type="button" class="profile_btn">Profile</button></a>
			</div>
			
			<div class="clear"></div>
			<nav class="col_8">
				<ul>
					<li class="<?php if(($_GET['p'] == 'home') || (!isset($_GET['p']))){ echo 'active';}?>"><a href="index.php">Home</a></li>
					<li class="<?php if($_GET['p'] == 'training'){ echo 'active';}?>"><a href="index.php?p=training">Training</a></li>
					<li class="<?php if($_GET['p'] == 'nutrition'){ echo 'active';}?>"><a href="#">Nutrition</a></li>
					<li class="<?php if($_GET['p'] == 'articles'){ echo 'active';}?>"><a href="index.php?p=articles">Articles</a></li>
					<li class="<?php if($_GET['p'] == 'promotions'){ echo 'active';}?>"><a href="#">Promotions</a></li>
					<li class="<?php if($_GET['p'] == 'about'){ echo 'active';}?>"><a href="#">About</a></li>
				</ul>
			</nav>
		</header>
		<div class="container" style="border-bottom:1px solid #222222;">
		