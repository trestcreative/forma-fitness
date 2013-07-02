<?php
require_once('includes/mysql_connection.php');
require_once('includes/top_header.php');
session_start();
date_default_timezone_set("Europe/London");
ini_set("log_errors", 1);
ini_set("error_log", "./core/php-error.log");


//Core Plugins
require_once('core/cms_user.php');
require_once('core/core_classes.php');

if (isset($_SESSION['username'])){
	
	//State the current tab after submit
	if (isset ($_POST['c_submit'])){
			echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 1 });
					}); 
				</script>";
	}
	if (isset ($_POST['c_e_submit'])){
			echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 2 });
					}); 
				</script>";
	}
	if(isset($_POST['cc_new_submit'])){
	echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 3 });
					}); 
				</script>";
	}
	if(isset($_POST['cm_submit'])){
	echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 4 });
					}); 
				</script>";
	}
	if(isset($_POST['cb_submit'])){
	echo "<script>
					$(document).ready(function() { 
						$('.cb_add_div').show();
					}); 
				</script>";
	}
	if(!isset($_POST['cb_submit'])){
	echo "<script>
					$(document).ready(function() { 
						$('.cb_add_div').hide();
					}); 
				</script>";
	}
	
	
	
	
	
	
	//State update variable 
	$client_update = false;
	$client_delete = false;
	$client_measurement = false;
	$client_booking_bool = false;
	$client_program_bool = false;
	
	
	
	if (isset($_GET['action'])){
		$action = $_GET['action'];
		$action_id = $_GET['id'];
		
		if($action == 'edit'){
			echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 2 });
					}); 
				</script>";
			$client_update = true;	
		}
		if($action == 'delete'){
			echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 2 });
					}); 
				</script>";
			$client_delete = true;
		}
		if($action == 'measurement'){
			echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 4 });
					}); 
				</script>";
			$client_measurement = true;
		}
		if($action == 'booking'){
			echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 6 });
					}); 
				</script>";
			$client_booking_bool = true;
		}
		if($action == 'schedule'){
			echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 7 });
					}); 
				</script>";
		}
		if($action == 'program'){
			echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 5 });
					}); 
				</script>";
			$client_program_bool = true;	
		}
		if($action == 'goal'){
			echo "<script>
					$(document).ready(function() { 
						$( '#tabs' ).tabs({ active: 3 });
					}); 
				</script>";
		}
	}
	
	
	//Plugins
	require_once('plugins/article_plugin.php');
	require_once('plugins/topic_plugin.php');
	require_once('plugins/exercise_plugin.php');
	require_once('plugins/recipe_plugin.php');
	require_once('plugins/client_plugin.php');
	require_once('plugins/client_goals_plugin.php');
	require_once('plugins/client_measurement_plugin.php');
	require_once('plugins/client_booking_plugin.php');
	require_once('plugins/booking_schedule_plugin.php');
	require_once('plugins/client_program_plugin.php');
	
	
	
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
			<li class="active"><a href="clients.php">Clients</a></li>
			<li><a href="admin.php">Admin</a></li>
		</ul>	
	</div>
</div>
<div class="main_col">
	<div id="tabs">
		<ul>
			<!-- MAKE SURE TO ADD TABS WHEN ADDING PLUGINS -->
			<li><a href="#tabs-1">Clients</a></li>
			<li><a href="#tabs-2">Add Client</a></li>
			<li ><a href="#tabs-3">Edit Clients</a></li>
			<li ><a href="#tabs-4">Client Goals</a></li>
			<li ><a href="#tabs-5">Client Measurements</a></li>
			<li ><a href="#tabs-6">Client Programs</a></li>
			<li ><a href="#tabs-7">Client Booking</a></li>
			<li ><a href="#tabs-8">Schedule</a></li>
		</ul>
	<!-- VIEW CLIENTS HTML -->	
		<div id="tabs-1">
			<h2>Clients</h2>
			<div class="display_items">
			<script>
				$(document).ready(function() { 
					$('.c_details').hide();
					
					$('.c_detail_btn').click(function(){
						$(this).parent().next('.c_details').slideToggle();
						
					});
				});
			</script>
			<?php
				
				$client->display_clients();			
			?>
			</div>
		</div>
	<!-- VIEW CLIENTS HTML END -->
	<!-- ADD CLIENTS HTML -->	
		<div id="tabs-2">
			<h2>Add Client</h2>
			<div class="display_items">
			<?php
				echo $client_add;			
			?>
			</div>
		</div>

	<!-- ADD CLIENTS HTML END -->
	<!-- EDIT CLIENTS HTML -->	
		<div id="tabs-3">
		<script>
				$(document).ready(function() { 
					$('.c_update_img').hide();
					$('.c_update_img_cnl_btn').hide();
					
					$('.c_update_img_btn').click(function(){
						$('.c_update_img').toggle();
						$('.c_update_img_cnl_btn').show();
						$('.c_update_img_btn').hide();
					});
					$('.c_update_img_cnl_btn').click(function(){
						$('.c_update_img').toggle();
						$('.c_update_img_cnl_btn').hide();
						$('.c_update_img_btn').show();
					});
					
					$('.c_choice').click(function(){
						var c_selected = $('.c_select option:selected').attr('value');
						window.location = c_selected;
					});
					
					$('.c_remove_div').hide();
					$('.c_remove, .c_remove_cnl').click(function(){
						$('.c_remove_div').slideToggle();
					});
				});
		</script>
		
		<h2>Edit Clients</h2>
			<?php
			echo $client_update_choice;
			echo $c_e_success;
			if(($client_update == true) && (empty($c_e_success))){
					echo $client_update_form;
			}
			?>
		</div>
	<!-- EDIT CLIENTS HTML END -->	
	<!-- CLIENT GOALS HTML -->
		<div id="tabs-4">
		<h2>Client Goals</h2>
		<script>
				$(document).ready(function() { 
					$('.cg_curr_div').hide();
					$('.cg_new_div').hide();
					
					$('.cg_curr_btn').click(function(){
						$(this).parent().siblings('.cg_curr_div').slideToggle();
					});
					$('.cg_new_btn').click(function(){
						$(this).parent().siblings('.cg_new_div').slideToggle();
					});
					
				});
		</script>
			<?php
				echo $cg_html;
				$client_goal->display_clients();
			?>
		</div>
	<!-- CLIENT GOALS END-->
	<!-- CLIENT MEASUREMENTS HTML -->	
		<div id="tabs-5">
			<script>
				$(document).ready(function() { 
					$('.cm_choice').click(function(){
								var cm_selected = $('.cm_select option:selected').attr('value');
								 window.location = cm_selected;
					});
				});
			</script>
			<h2>Client Measurements</h2>
			<?php
				
				echo $client_measure_choice;
				echo $client_measure_success;
				echo $client_measure;
			?>
		</div>
	<!-- CLIENT MEASUREMENTS HTML END -->
	<!-- CLIENT PROGRAM HTML -->
		<div id="tabs-6">
		<h2>Client Programs</h2>
		<?php
			if($add_box_show == true){
				echo "	<script>
						$(document).ready(function() { 
							$('.cprog_add_div').show();
						});
						</script>";
			}
			else{
				echo "	<script>
						$(document).ready(function() { 
							$('.cprog_add_div').hide();
						});
						</script>";
			}
			?>
		<script>
				$(document).ready(function() { 
					$('.cprog_choice').click(function(){
								var cprog_selected = $('.cprog_select option:selected').attr('value');
								 window.location = cprog_selected;
					});
					
					
					$('.cprog_add_ro').click(function(){
						$('.cprog_add_div').slideToggle();
					});
					
					$('.cprog_chart').hide();
					$('.cprog_chart_btn').click(function(){
						$('.cprog_chart').slideToggle();
					});
					
				});
		</script>
			<?php
				echo $client_program_choice;
				echo $client_program_html;
			?>
		</div>
	<!-- CLIENT PROGRAM END-->
	<!-- CLIENT BOOKINGS HTML -->	
		<div id="tabs-7">
			<script>
				$(document).ready(function() { 
					$('.cb_choice').click(function(){
								var cb_selected = $('.cb_select option:selected').attr('value');
								window.location = cb_selected;
							});
					
					$(function() {
						$( ".datepicker" ).datepicker();
					});	
					
					
					$('.cb_add').click(function(){
						$('.cb_add_div').slideToggle();
					});
					$('.cb_add_p_div').hide();
					$('.cb_add_p').click(function(){
						$('.cb_add_p_div').slideToggle();
					});
					
					$('.cb_remove_div').hide();
						$('.cb_remove').click(function(){
							$(this).siblings('.cb_remove_div').slideToggle();
						});
						$('.cb_remove_cnl').click(function(){
							$(this).parent('.cb_remove_div').slideToggle();
					});	
					
					$('.cp_remove_div').hide();
						$('.cp_remove').click(function(){
							$(this).siblings('.cp_remove_div').slideToggle();
						});
						$('.cp_remove_cnl').click(function(){
							$(this).parent('.cp_remove_div').slideToggle();
					});	
				});
			</script>
			<h2>Client Booking</h2>
			<?php
				
				echo $client_booking_choice;
				echo $client_booking_html;
			?>
		</div>
	<!-- CLIENT BOOKINGS HTML END -->
	<!-- SCHEDULE HTML -->	
		<div id="tabs-8">
			<script>
				$(document).ready(function() { 	
					$('.sch_tmrw_div').hide();
					$('.sch_tmrw').click(function(){
						$('.sch_today_div').hide();
						$('.sch_date_div').hide();
						$('.sch_tmrw_div').slideDown();
					});
					$('.sch_today').click(function(){
						$('.sch_tmrw_div').hide();
						$('.sch_date_div').hide();
						$('.sch_today_div').slideDown();
					});
					$('.block_a').click(function(e){
						e.preventDefault();
					});
					$(function() {
						$('.s_block, .s_block1' ).tooltip();
					});
					$('.s_date').change(function(){
						window.location.href = 'clients.php?action=schedule&id=' + $(this).val();
					});
				});
			</script>
	<?php
		if($date_j == true){
			echo "<script>
					$(document).ready(function() { 	
						$('.sch_tmrw_div').hide();
						$('.sch_today_div').hide();
						$('.sch_date_div').show();
					});
				</script>";
		}
	?>
			<h2>Schedule</h2>
			<div class="item_row">
				<?php
					echo $schedule_html;
				?>
			</div>
			
		</div>
	<!-- SCHEDULE HTML END -->
	</div>
	
</div>

</div>
<?php
require_once('includes/footer.php');
}
?>
</body>
</html>