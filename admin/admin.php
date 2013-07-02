<?php
require_once('includes/mysql_connection.php');
require_once('includes/top_header.php');
session_start();
date_default_timezone_set("Europe/London");
ini_set("log_errors", 1);
ini_set("error_log", "./admin/core/php-error.log");

//Core Plugins
require_once('core/cms_user.php');
require_once('core/core_classes.php');
require_once('core/admin_plugin.php');

if (isset($_SESSION['username'])){

	
?>
<!-- Start of tabs and content -->
<?php
if(isset($_SESSION['username'])){
?>
<div class="time_panel">
<div style="padding:0.4em;float:left;color:#ffffff;display:inline;">
Forma Fitness Admin
</div>
<div id="clock" style="padding:0.4em;display:inline;color:#ffffff;float:right;border-left:1px solid white;"></div>
<div style="padding:0.4em;display:inline;color:#ffffff;float:right;border-left:1px solid white;"><?php echo date("D jS M Y");?></div>
</div>
<?php
}
?>
<header>
	<div class="logo">
		<img src="../images/icons/logo.png"/>
	</div>
	<div class="title">
		
		<!--Logout button--><form action="index.php" method="post"><input class="s-button" type="submit" value="Logout" name="cms_logout"/></form> 
	</div>
	
</header>
<div class="container">
<div class="side_col">
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
		<h2 class="summery_title">
				<?php 
					echo "Welcome " . $_SESSION['username'];
				?>
		</h2>
		<hr/>
		<ul>
			<li><a href="index.php">General</a></li>
			<li><a href="clients.php">Clients</a></li>
			<li class="active"><a href="admin.php">Admin</a></li>
		</ul>	
	</div>
</div>
<div class="main_col">
	<div id="tabs">
		<ul>
			<!-- MAKE SURE TO ADD TABS WHEN ADDING PLUGINS -->
			<li><a href="#tabs-1">Client Passwords</a></li>
			<li ><a href="#tabs-2">General Settings</a></li>
			<li ><a href="#tabs-3">System Log</a></li>
			<?php
				if ($_SESSION['username'] == 'trest_master'){
					echo '<li ><a href="#tabs-4">Trest Admin Settings</a></li>';
				}
			?>
		</ul>	
	<!-- CLIENT PASSWORDS SECTION -->
		<div id="tabs-1">
			<script>
				$(document).ready(function() {
					$('.cpwd_reset_div').hide();
					$('.cpwd_admin_div').hide();
					$('.cpwd_btn, .cpwd_btn_cnl').click(function(){
						$('.cpwd_reset_div').slideToggle();
					});
					$('.cpwd_btn_cnf').click(function(){
						$('.cpwd_admin_div').slideToggle();
					});
					
					
				});
			</script>
			<h2>Client Passwords</h2>
			<?php
				echo $c_pwd;
			?>
		</div>
	<!-- CLIENT PASSWORDS SECTION END -->

	<!-- GENERAL SETTINGS SECTION -->	
		<div id="tabs-2">
			<h2>General Settings</h2>
		</div>
	<!-- GENERAL SETTINGS SECTION END -->	
	<!-- LOG SECTION -->	
		<div id="tabs-3">
			<?php
				if(isset($_SESSION['a_level'])){
					if($_SESSION['a_level'] == 3){
						$log = file('./core/php-error.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			?>	
			<h2>System Log</h2>
			<div class="s_log">
				<p>
				<?php
				
					foreach ($log as $log_line){
						echo $log_line.'</br>';
					}
				?><img src="./core/css/images/Thin_cursor.gif"/>
				</p>
				
			</div>
			<?php
					}
				}
				else {
			?>
			<h2>System Log</h2>
			<em class="error">You do not have the correct access level for this section</em>
			<?php
			}
			?>
		</div>
	<!-- LOG SECTION END -->	
	
	<?php
		if ($_SESSION['username'] == 'trest_master'){	
			echo '	<!-- TREST ADMIN SETTINGS SECTION -->	
						<div id="tabs-4">
							<h2>Trest Admin Settings</h2>
						</div>
					<!-- TREST ADMIN SETTINGS SECTION END -->';
		}
	?>
	</div>
</div>
</div>
<?php
require_once('includes/footer.php');
}
?>
</body>
</html>