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

//Plugins
require_once('plugins/article_plugin.php');
require_once('plugins/topic_plugin.php');
require_once('plugins/exercise_plugin.php');
require_once('plugins/routines_plugin.php');
require_once('plugins/recipe_plugin.php');

//Management Functions
class management{

	public function __construct($conn){
		$this->conn = $conn;
		return true;
	}
	
	public function delete_item($item_type, $item_id){
		switch ($item_type){
			case "article":
				$table = "articles";
				$init = "a";
			break;
			case "topic":
				$table = "topics";
				$init = "t";
  			break;
			case "exercise":
				$table = "exercise";
				$init = "e";
  			break;
			case "routine":
				$table = "routine";
				$init = "ro";
  			break;
			case "recipe":
				$table = "recipes";
				$init = "r";
  			break;
			default:
				echo "Incorrect type of item deletion attempt!";
		}
		
		$query = $this->conn->prepare("DELETE FROM ".$table." WHERE ".$init."_id = :id ");
		$query->bindParam(":id", $item_id);
		$query->execute();
	}	
	
	public function otm_update($type, $id){
		switch ($type){
			case "article":
				$table = "articles";
				$init = "a";
			break;
			case "routine":
				$table = "routine";
				$init = "ro";
  			break;
			case "recipe":
				$table = "recipes";
				$init = "r";
				
  			break;
			default:
				echo "Incorrect type of item deletion attempt!";
		}
		$query = $this->conn->prepare("UPDATE ".$table." SET ".$init."_of_month = 0");
		$query->execute();
		
		$query = $this->conn->prepare("UPDATE ".$table." SET ".$init."_of_month = 1 WHERE ".$init."_id = :id ");
		$query->bindParam(":id", $id);
		$query->execute(); 
		
	}
	
	public function display_edit_item($item_type, $item_id){
		
		
		switch ($item_type){
			case "article":
				//Select option query
				$t_option = '';
				foreach ($this->conn->query("SELECT * FROM topics") as $option){
							$t_option .= "<option>". $option['t_title'] . "</option>";
						}
				
				$sql = "SELECT * FROM articles WHERE a_id = ".$item_id."";
				$query = $this->conn->query($sql);
				foreach ($query as $row){
					
					
					
				$edit_form = "<script>
								$(document).ready( function() {
								  $('#at_text').hide();
								  
									$('#add_at_text').click(function(){
										$('#at_text').show();
										$(this).hide();
									});
									
									$('#remove_at_text').click(function(){
										$('#at_text').find('input').val('');
										 $('#at_text').hide();
										 $('#add_at_text').show();
									});
								});
							</script>";	
					
				$edit_form .= '
								<form action="index.php" method="post" enctype="multipart/form-data" >
								<label class="left_col" for="a_title">Article Title:</label>
								<input name="a_title" type="text" placeholder="Article Title" value="'.$row['a_title'].'"/></br>
								
								<label class="left_col" for="at_title_select">Topic:</label>
								<select id="at_select" name="at_title_select">
								<option class="ex_default">'.$row['t_title'].'</option>';
				$edit_form .= $t_option;
				$edit_form .= '</select>
								or
								<button type="button" class="s-button" id="add_at_text">New Topic</button> 
								<div style="display:inline;" id="at_text">
									
									<input name="at_title_text" type="text"/>
									<button type="button" class="x-button" id="remove_at_text">Remove</button> </br>
								</div>
								<label for="a_blurb">Article Blurb:</label>
								<textarea class="ro_ex_desc" name="a_blurb">'.$row['a_blurb'].'</textarea>
								
								<hr>
								<label for="a_desc">Article Text:</label>
								<textarea class="wys" name="a_desc">'.$row['a_desc'].'</textarea></br>
								<label for="a_author">Author:</label>
								<input name="a_author" type="text" placeholder="Author" value="'.$row['a_author'].'"/></br>
								<input name="a_edit" type="hidden" value="'.$row['a_id'].'"/>
								<input class="s-button" type="submit" name="a_submit" value="Update"/>
							</form>';
				}
				return $edit_form;
			break;
			case "topic":
				$sql = "SELECT * FROM topics WHERE t_id = ".$item_id."";
				$query = $this->conn->query($sql);
				foreach ($query as $row){
				$edit_form = '<form action="index.php" method="post">
								<label for="t_title">Topic Title:</label>
								<input name="t_title" placeholder="Topic" type="text" value="'.$row['t_title'].'" />
								<input name="t_edit" type="hidden" value="'.$row['t_id'].'"/>
								<input class="s-button" type="submit" value="Update" name="t_submit" />
							</form>';
				}
				return $edit_form;
  			break;
			
			
			case "exercise":
				//Exercise cats drop down
				$e_option = '';
				foreach ($this->conn->query("SELECT * FROM exercise_cats") as $option){
					$e_option .= "<option>" . $option['e_cat_title'] . "</option>";
				}	
				
				//Recover steps from exercise_steps table
				$sql = "SELECT * FROM exercise INNER JOIN exercise_steps ON exercise.e_title = exercise_steps.e_title WHERE exercise.e_id = ".$item_id."";
				$query = $this->conn->query($sql);
				$e_step_input = '';
				foreach ($query as $key){
					$e_step_input .= '	<div class="e_step_div">
											<label for="e_step[]">Step:</label>
											<textarea class="e_step" name="e_step[]">' . $key['e_step_desc'] . '</textarea>
										</div>'; 
				}
				
				//Find remaining data
				$sql = "SELECT * FROM exercise WHERE e_id = ".$item_id."";
				$query = $this->conn->query($sql);
				foreach ($query as $row){
					//Script for this form
					$edit_form = "<script>
								$(document).ready( function() {
								  $('#e_text').hide();
								  
									$('#add_e_cat_text').click(function(){
										$('#e_text').show();
										$(this).hide();
									});
									
									$('#remove_e_cat_text').click(function(){
										$('#e_text').find('input').val('');
										 $('#e_text').hide();
										 $('#add_e_cat_text').show();
									});
								});
							</script>";	
					
					$edit_form .= '	<form action="index.php" method="post" enctype="multipart/form-data">
										<label for="e_title">Exercise Title:</label>
										<input name="e_title" placeholder="Exercise Title" type="text" value="'.$row['e_title'].'" /></br>
						
										<label for="e_desc">Exercise Description:</label>
										<textarea class="ro_ex_desc" name="e_desc" placeholder="Exercise Description">'.$row['e_desc'].'</textarea></br>

										<label for="e_cat">Category:</label>
										<select name="e_cat_select">
										<option>'.$row['e_cat_title'].'</option>';
					$edit_form .= $e_option;
					$edit_form .= '		</select>
										or
											<button type="button" class="s-button" id="add_e_cat_text">New Category</button> 
										<div style="display:inline;" id="e_text">
											<input name="e_cat_text" type="text"/>
											<button type="button" class="x-button" id="remove_e_cat_text">Remove</button> 
										</div></br>
										
										<input name="e_edit" type="hidden" value="'.$row['e_id'].'"/>
										<input class="s-button" type="submit" value="Update" name="e_submit" />
									</form>';
					return $edit_form;
				}
  			break;
			case "routine":
				
				
				$sql = "SELECT * FROM routine WHERE ro_id = ".$item_id."";
				$query = $this->conn->query($sql);
				foreach ($query as $row){
					$edit_form = '	<form action="index.php" method="post">
										<label for="ro_title">Routine Title:</label>
										<input name="ro_title" placeholder="Routine" type="text" value="'.$row['ro_title'].'" /></br><hr>
										
											<hr>
											
										<label for="ro_desc">Routine Description:</label>
										<textarea class="wys" name="ro_desc">'.$row['ro_desc'].'</textarea>
									<input type="hidden" value="'.$row['ro_id'].'" name="ro_edit" />
									<input style="margin-top:5px;" class="s-button" type="submit" value="Update" name="ro_submit" />
									</form>';
					return $edit_form;
				}
  			break;
			case "recipe":
				$table = "recipes";
				$init = "r";
  			break;
			default:
				echo "Incorrect type of item deletion attempt!";
		}
	}

}


if (isset($_SESSION['username'])){
	
	//Instance Management object
	$management = new management($conn);
	
	//Find out what action and type
	$action = $_GET['action'];
	$type = $_GET['type'];
	$id = $_GET['id'];
	
	//Variables
	$stage = '';
	
	switch ($action)
	{
	case "delete":
		$management->delete_item($type, $id);
		header('Location:index.php?return='.$type.'');
	break;
	case "edit":
		$stage = $management->display_edit_item($type, $id);
  	break;
	case "otm":
		$management->otm_update($type, $id);
		header('Location:index.php?return='.$type.'');
  	break;
	default:
	header('Location:index.php');
	}


?>
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
		<div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
			<h2 class="summery_title">Edit <?php echo $type;?></h2>
			<a href="index.php?return=<?php echo $type;?>"><button type="button" class="s-button">Back</button></a>
			
		</div>
		<div class="main-stage">
		
	<?php
		echo $stage;
	
	?>
		</div>
	</div>
</div>

<?php	


require_once('includes/footer.php');
}

?>