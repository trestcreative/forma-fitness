<?php
ob_start();
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
	
	//Plugins
	require_once('plugins/article_plugin.php');
	require_once('plugins/topic_plugin.php');
	require_once('plugins/exercise_plugin.php');
	require_once('plugins/routines_plugin.php');
	require_once('plugins/recipe_plugin.php');
	
	echo "	<script>
			$(document).ready(function() { 
				$( '.add_items1' ).hide();
				$( '.add_items2' ).hide();
				$( '.add_items3' ).hide();
				$( '.add_items4' ).hide();
				$( '.add_items5' ).hide();
				
				$('.add_item1').click(function(){
					$( '.add_items1' ).slideToggle();
				});
				$('.add_item2').click(function(){
					$( '.add_items2' ).slideToggle();
				});
				$('.add_item3').click(function(){
					$( '.add_items3' ).slideToggle();
				});
				$('.add_item4').click(function(){
					$( '.add_items4' ).slideToggle();
				});
				$('.add_item5').click(function(){
					$( '.add_items5' ).slideToggle();
				});
			}); 
			</script>";
	
	//State return GET variables if returned from management
	if(isset($_GET['return'])){
		$return = $_GET['return'];	
			//Return scripts
		if($return == 'article'){
			echo "<script>
			$(document).ready(function() { 
				$( '#tabs' ).tabs({ active: 0 });
			}); 
			</script>";
		}
		if($return == 'topic'){
		echo "<script>
			$(document).ready(function() { 
				$( '#tabs' ).tabs({ active: 1 });
			}); 
			</script>";
		}
		if($return == 'exercise'){
		echo "<script>
			$(document).ready(function() { 
				$( '#tabs' ).tabs({ active: 2 });
			}); 
			</script>";
		}
		if($return == 'routine'){
		echo "<script>
			$(document).ready(function() { 
				$( '#tabs' ).tabs({ active: 3 });
			}); 
			</script>";
		}
		if($return == 'recipe'){
		echo "<script>
			$(document).ready(function() { 
				$( '#tabs' ).tabs({ active: 4 });
			}); 
			</script>";
		}		
	}
	else{
		//State the current tab after submit
		if (isset ($_POST['a_submit'])){
			echo "<script>
		$(document).ready(function() { 
			$( '#tabs' ).tabs({ active: 0 });
			$( '.add_items1' ).show();
		}); 
		</script>";
		}
		if (isset ($_POST['t_submit'])){
			echo "<script>
		$(document).ready(function() { 
			$( '#tabs' ).tabs({ active: 1 });
			$( '.add_items2' ).show();
		}); 
		</script>";
		}
		if (isset ($_POST['e_submit'])){
			echo "<script>
		$(document).ready(function() { 
			$( '#tabs' ).tabs({ active: 2 });
			$( '.add_items3' ).show();
		}); 
		</script>";
		}
		if (isset ($_POST['r_submit'])){
			echo "<script>
		$(document).ready(function() { 
			$( '#tabs' ).tabs({ active: 4 });
			$( '.add_items5' ).show();
		}); 
		</script>";
		} 
		if (isset ($_POST['ro_submit'])){
			echo "<script>
		$(document).ready(function() { 
			$( '#tabs' ).tabs({ active: 3 });
			$( '.add_items4' ).show();
		}); 
		</script>";
		} 
	}	
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
			<li class="active"><a href="index.php">General</a></li>
			<li><a href="clients.php">Clients</a></li>
			<li><a href="admin.php">Admin</a></li>
		</ul>	
	</div>
</div>
<div class="main_col">
	<div id="tabs">
		<ul>
			<!-- MAKE SURE TO ADD TABS WHEN ADDING PLUGINS -->
			<li><a href="#tabs-1">Articles</a></li>
			<li><a href="#tabs-2">Topics</a></li>
			<li ><a href="#tabs-3">Exercises</a></li>
			<li ><a href="#tabs-4">Routines</a></li>
			<li ><a href="#tabs-5">Recipes</a></li>
		</ul>
	<!-- ARTICLE HTML -->	
<div id="tabs-1">
		<h2>Articles</h2>
		<button type="button" class="add_item1 s-button">Show/Hide add item</button><hr>
	<div class="add_items1">
	<script>
	//For adding 'of the month' star
	$(document).ready(function() { 
		$('.a_of_month_btn img').hide();
			$('.a_of_month_btn').click(function(){
				$('.a_of_month_btn img').toggle();
				if($('.a_of_month_btn img').is(':visible')){
					$('.a_of_month').attr('value', '1');
				}
				else{
					$('.a_of_month').attr('value', '0');
				}
			});
		
		$('.a_remove_div').hide();
			$('.a_remove').click(function(){
				$(this).siblings('.a_remove_div').slideToggle();
			});
			$('.a_remove_cnl').click(function(){
				$(this).parent('.a_remove_div').slideToggle();
			});
	});
	</script>
	<?php 	
		//ARTICLES HTML ECHO
		echo $a_html;
		//QUERY FOR EXISTING TOPICS TO FILL OPTION DROP DOWN
		foreach ($conn->query("SELECT * FROM topics") as $row) echo "
					<option>" . $row['t_title'] . "</option>";
		echo $a_html2;
	?>
	<hr>
	<button type="button" class="add_item1 s-button">Show/Hide add item</button><hr>
	</div>
	<div class="display_items">
	<?php
	$article_obj->display_articles();
	?>
	</div>
</div>
<!-- ARTICLE HTML END -->

<!-- TOPICS HTML -->	
<div id="tabs-2">
<h2>Topics</h2>
<button type="button" class="add_item2 s-button">Show/Hide add item</button><hr>
	<script>
	$(document).ready(function() { 
		$('.t_remove_div').hide();
			$('.t_remove').click(function(){
				$(this).siblings('.t_remove_div').slideToggle();
			});
			$('.t_remove_cnl').click(function(){
				$(this).parent('.t_remove_div').slideToggle();
			});
	});
	</script>
	<div class="add_items2">	
	<?php
		echo $t_html;
	?>
	
	<hr><button type="button" class="add_item2 s-button">Show/Hide add item</button>
	<hr></div>
	<div class="display_items">
	<?php
		$new_topic->display_topics();
	?>
	</div>
</div>
<!-- TOPICS HTML END -->

<!-- EXERCISES HTML -->	
		<div id="tabs-3">
		<h2>Exercises</h2>
		<button type="button" class="add_item3 s-button">Show/Hide add item</button><hr>
		<script>
		$(document).ready(function() { 
				$('.e_remove_step').hide();
				
					//Add Step function
					$('.e_add_step').click( function(){
						var e_last_step = $('.e_steps .e_step_div').last();
						e_last_step.after('<div class="e_step_div"><label for="e_step[]">Step:</label><textarea name="e_step[]" class="ro_ex_desc e_step" placeholder="Step"></textarea><label for="e_img[]">Step Image:</label><input name="e_img[]" type="file"></div>');
						 var e_steps = $('.e_steps .e_step_div').size();
						 e_steps = e_steps + 1;  
						
							if (e_steps > 1){
								$('.e_remove_step').show();
							}
							else{
								$('.e_remove_step').hide();
							}  
					});
					
					//Remove Step button
					$('.e_remove_step').click( function(){
						var e_last_step_div = $('.e_steps .e_step_div').last();
						e_last_step_div.remove();
						e_steps = $('.e_steps .e_step_div').size();
						e_steps = parseInt(e_steps);
						if (e_steps == 1){
							$('.e_remove_step').hide();
						} 
					});
					
					$('.e_remove_div').hide();
						$('.e_remove').click(function(){
							$(this).siblings('.e_remove_div').slideToggle();
						});
						$('.e_remove_cnl').click(function(){
							$(this).parent('.e_remove_div').slideToggle();
					});
				}); 
		</script>
		<div class="add_items3">
			
			<?php 
				echo $exercise_html1;
				//QUERY FOR EXISTING WORKOUT CATEGORIES TO FILL OPTION DROP DOWN
				foreach ($conn->query("SELECT * FROM exercise_cats") as $row) echo "
							<option>" . $row['e_cat_title'] . "</option>";
				echo $exercise_html2;
				echo $exercise_steps;
				echo $exercise_html3;
			?>
		<hr>
		<button type="button" class="add_item3 s-button">Show/Hide add item</button><hr>
		</div>
		<div class="display_items">
			<?php
				$new_exercise->display_exercises();
			?>
		</div>
</div>
<!-- EXERCISES HTML END -->

<!-- ROUTINES HTML -->
<div id="tabs-4">

<script>
	//For adding 'of the month' star
	$(document).ready(function() { 
		$('.ro_of_month_btn img').hide();
			$('.ro_of_month_btn').click(function(){
				$('.ro_of_month_btn img').toggle();
				if($('.ro_of_month_btn img').is(':visible')){
					$('.ro_of_month').attr('value', '1');
				}
				else{
					$('.ro_of_month').attr('value', '0');
				}
			});
		$('.ro_remove_div').hide();
			$('.ro_remove').click(function(){
				$(this).siblings('.ro_remove_div').slideToggle();
			});
			$('.ro_remove_cnl').click(function(){
				$(this).parent('.ro_remove_div').slideToggle();
			});	
	});
	</script>
	<h2>Routines</h2>
	<button type="button" class="add_item4 s-button">Show/Hide add item</button><hr>
	<?php
	echo "
		<script>
		$(document).ready(function() { 
				$('.ro_remove_step').hide();
				//Exercises list
				var exercise_list = '";$new_routine->display_exercises();
				echo "';";
	echo <<<EOD
				//Add Step function
					$('.ro_add_step').click( function(){
						var ro_last_step = $('.ro_steps .ro_step_div').last();
						ro_last_step.after('<div class="ro_step_div"><hr><label for="ro_exer[]">Exercise:</label><select name="ro_exer[]"><option class="ex_default" value="false"></option>' + exercise_list + '</select> Sets <input type="text" class="s-and-rs" name="sets[]"> Of <input type="text" class="s-and-rs" name="reps[]"> <select name="reps_type[]"><option>Reps</option><option>Mins</option><option>Metres</option></select></br><label for="ro_ex_desc">Exercise Description:</label><textarea class="ro_ex_desc" name="ro_exer_desc[]"></textarea></br></br><label for="ro_type[]">Routine Type:</label><select name="ro_type[]"><option  value="7"></option><option  value="6">Cutting</option><option  value="1">Bulking</option><option  value="2">Power</option><option  value="3">Speed</option><option  value="5">Stamina</option><option  value="4">Strength</option></select><label style="margin-left:1%;" for="ro_ex_burn[]">Burnout Exercise:</label><select name="ro_ex_burn[]"><option>No</option><option value="1">Yes</option></select><hr></div>');
						 var ro_steps = $('.ro_steps .ro_step_div').size();
						 ro_steps = ro_steps + 1;  
						
							if (ro_steps > 1){
								$('.ro_remove_step').show();
							}
							else{
								$('.ro_remove_step').hide();
							}  
					});
					//Remove Step button
					$('.ro_remove_step').click( function(){
						var ro_last_step_div = $('.ro_steps .ro_step_div').last();
						ro_last_step_div.remove();
						ro_steps = $('.ro_steps .ro_step_div').size();
						ro_steps = parseInt(ro_steps);
						if (ro_steps == 1){
							$('.ro_remove_step').hide();
						} 
					});
				}); 
		</script>
EOD;
	?>
	<script>
		$(document).ready(function() { 
			//Show/hide exercises of routines
			$('.exs').hide();
			$('.show_exs').click(function() {
				$(this).prev('.exs').slideToggle();
			});
		});
	</script>	
	<div class="add_items4">	
	<?php
		echo $ro_html;
		$new_routine->display_exercises();
		echo $ro_html2;
	?>
	<hr>
	<button type="button" class="add_item4 s-button">Show/Hide add item</button><hr>
	</div>
	<div class="display_items">
	<?php
		$new_routine->display_routines();
	?>
	</div>
</div>
<!-- ROUTINES HTML END -->	
<!-- RECIPES HTML -->
<div id="tabs-5">
	<h2>Recipes</h2>
	<button type="button" class="add_item5 s-button">Show/Hide add item</button><hr>	
			<script>
				$(document).ready(function() { 
				var r_steps = $('.r_steps .r_step_div').size();
				var r_steps = parseInt(r_steps);
				var r_count = '';
				//Hide remove button
				$('.r_remove_step').hide();
				//check if already submitted to show hide remove _step
				if (r_steps > 1){
					$('.r_remove_step').show();
				}
				else{
					$('.r_remove_step').hide();
				}
					//Add Step function
					$('.r_add_step').click( function(){
						var r_last_step = $('.r_steps .r_step').last();
						r_last_step.next('br').after('<div class="r_step_div"><label for="r_step[]">Recipe Step:</label><textarea name="r_step[]" class="ro_ex_desc r_step" placeholder="Recipe Step"></textarea></br></div>');
						 var r_count = r_steps + 1; 
							if (r_steps > 1 || r_count > 1){
								$('.r_remove_step').show();
							}
							else{
								$('.r_remove_step').hide();
							}
					});
					
					//Remove Step button
					$('.r_remove_step').click( function(){
						var r_last_step_div = $('.r_steps .r_step_div').last();
						r_last_step_div.remove();
						var r_steps = $('.r_steps .r_step_div').size();
						var r_steps = parseInt(r_steps);
						if (r_steps == 1){
							$('.r_remove_step').hide();
						}
					});
					
				
					//For adding 'of the month' star
					$('.r_of_month_btn img').hide();
						$('.r_of_month_btn').click(function(){
							$('.r_of_month_btn img').toggle();
							if($('.r_of_month_btn img').is(':visible')){
								$('.r_of_month').attr('value', '1');
							}
							else{
								$('.r_of_month').attr('value', '0');
							}
						});
					
					$('.r_remove_div').hide();
						$('.r_remove').click(function(){
							$(this).siblings('.r_remove_div').slideToggle();
						});
						$('.r_remove_cnl').click(function(){
							$(this).parent('.r_remove_div').slideToggle();
					});
				}); 
			</script>
	<div class="add_items5">		
		<?php 
			echo $recipe_html1;
			echo $recipe_steps;
			echo $recipe_html2;
		?>
	<hr>
	<button type="button" class="add_item5 s-button">Show/Hide add item</button><hr></div>
	<div class="display_items">
		<?php
			$new_recipe->display_recipes();
		?>
	</div>
</div>
<!-- RECIPES HTML END -->	
	
	</div>
</div>

</div>
<?php
	
require_once('includes/footer.php');
}
ob_flush();
?>


 
  
   
	
</body>
</html>