<?php
class exercise{
	 
	public function __construct($conn)
    {
        $this->conn = $conn;
         return true;
    } 
	
	public function insert_cat($cat){
		$sql = "INSERT INTO exercise_cats (e_cat_title) VALUES (:e_title)";
		$query = $this->conn->prepare($sql);
		$query->bindParam(":e_title", $cat);
		$query->execute();
	}
	
	public function insert_exercise($title, $cat, $desc){
		$sql = "INSERT INTO exercise (e_title, e_cat_title, e_desc) VALUES (:e_title, :e_cat_title, :e_desc)";
		$query = $this->conn->prepare($sql);
		$query->bindParam(":e_title", $title);
		$query->bindParam(":e_cat_title", $cat);
		$query->bindParam(":e_desc", $desc);
		$query->execute();
	}
	
	public function insert_exercise_step($step_desc, $step_num, $exercise_title, $step_img, $e_id){
		$sql = "INSERT INTO exercise_steps (e_step_desc, e_step_num, e_title, e_step_img, e_id) VALUES (:e_step_desc, :e_step_num, :e_title, :e_step_img, :e_id)";
		$query = $this->conn->prepare($sql);
		$query->bindParam(":e_step_desc", $step_desc);
		$query->bindParam(":e_step_num", $step_num);
		$query->bindParam(":e_title", $exercise_title);
		$query->bindParam(":e_step_img", $step_img);
		$query->bindParam(":e_id", $e_id);
		$query->execute();
	}
	
	public function last_exercise_id(){
		$sql = "SELECT e_id FROM exercise ORDER BY e_id DESC LIMIT 1";
		$query = $this->conn->prepare($sql);
		$query->execute();
		foreach($query as $id){
			return $id['e_id'];
		}
	}
	
	public function display_exercises(){
		foreach ($this->conn->query("SELECT * FROM exercise") as $row){
		echo '<div class="item_row">
				<div class="desc">
					<h2>'.$row['e_title'].'</h2>
					<h3>'.$row['e_cat_title'].'</h3>
				</div>
				<div class="controls">
				<button type="button" class="x-button e_remove">Delete</button>
				<a href="manage.php?action=edit&type=exercise&id='.$row['e_id'].'"><button type="button" class="s-button">Edit</button></a>
					</br>
					<div class="e_remove_div">
						<h3>Are you sure you want to delete this item?</h3></br>
						<a href="manage.php?action=delete&type=exercise&id='.$row['e_id'].'"><button type="button" class="x-button">Yes</button></a>
						<button type="button" class="s-button e_remove_cnl">No</button>
					</div>
				</div>';
			
		echo '</div>';
		}
	}
	
	public function update_exercise($e_title, $e_cat, $e_desc, $id){
		$query = $this->conn->prepare("UPDATE exercise SET e_title = :e_title, e_cat_title = :e_cat_title, e_desc = :e_desc WHERE e_id = :e_id");
			$query->bindParam(":e_title", $e_title);
			$query->bindParam(":e_cat_title", $e_cat);
			$query->bindParam(":e_desc", $e_desc);
			$query->bindParam(":e_id", $id);
		$query->execute();
	}
	
	public function update_exercise_step($step, $step_num, $e_title, $e_id){
		$query = $this->conn->prepare("UPDATE exercise_steps SET e_title = :e_title, e_step_desc = :e_step_desc, e_step_num = :e_step_num WHERE e_id = :e_id");
			$query->bindParam(":e_title", $e_title);
			$query->bindParam(":e_cat_title", $e_cat);
			$query->bindParam(":e_id", $e_id);
		$query->execute();
	}
}
/*--------------------------------

		exerciseS SECTION
		
---------------------------------*/
//Success Variable
$e_success = '';
//Error variables
$exercise_title_error = '';
$exercise_step_error = '';
$exercise_cat_error = '';
$exercise_desc_error = '';
$exercise_error = false;
$exercise_steps = '';

//Instance exercise object
$new_exercise = new exercise($conn);
$new_exercise_img = new images($conn);

//Step form input data if not submitted
if (!isset ($_POST['e_submit'])){
	$e_step_input = '<div class="e_step_div">
				<label for="e_step[]">Step:</label>
				<textarea class="ro_ex_desc e_step" name="e_step[]" placeholder="Step"></textarea>
				<label style="width:10%;" for="e_img[]">Step Image:</label><input name="e_img[]" type="file">
			</div>';
}
			
if (isset ($_POST['e_submit'])){
	//Check if data has been sent from management
	$edit_exercise = false;
	if(isset ($_POST['e_edit'])){
		$edit_id = $_POST['e_edit'];
		$edit_exercise = true;
	}
	
	$e_step_input = '';
	//If exercise Title is empty
	if(empty($_POST['e_title'])){
		$exercise_title_error = '<em class="error">Exercise Title is required!</em>';
		$exercise_error = true;
	}
	if(empty($_POST['e_desc'])){
		$exercise_desc_error = '<em class="error">Exercise Description is required!</em>';
		$exercise_error = true;
	}
	//Check if exercise category is empty 
	if (empty($_POST['e_cat_text']) && (empty ($_POST['e_cat_select']))){
		$exercise_cat_error = '<em class="error">Exercise Category is required!</em>';
		$exercise_error = true;
	}
	if (($_POST['e_cat_text'] == $_POST['e_cat_select']) && (!empty($_POST['e_cat_text']) && (!empty($_POST['e_cat_select'])))){
		$exercise_cat_error = '<em class="error">This Category already exists, Please choose from drop down menu instead.</em>';
		$exercise_error = true;
	}
	
	//Step form input data if error with other fields
	if ($exercise_error == true){
		foreach ($_POST['e_step'] as $key){
				$e_step_input .= '<div class="e_step_div">
					<label for="e_step[]">Step:</label>
					<textarea class="ro_ex_desc e_step" name="e_step[]">' . $key . '</textarea>
					<label style="width:10%;" for="e_img[]">Step Image:</label><input name="e_img[]" type="file">
				</div>'; 
			}
	}
	
	else{		
		//check which exercise category field is entered
		if (empty($_POST['e_cat_text']) && (!empty($_POST['e_cat_select']))){
			$exercise_cat = $_POST['e_cat_select'];
			$exercise_type_bool = true;
		}
		if (!empty($_POST['e_cat_text']) && (!empty($_POST['e_cat_select']))){
			$exercise_cat = $_POST['e_cat_select'];
			$exercise_type_bool = true;
			//Check for edit management
			if($edit_exercise == true){
				$exercise_cat = $_POST['e_cat_text'];
				$exercise_type_bool = false;
			}
		}
		if (!empty($_POST['e_cat_text']) && (empty($_POST['e_cat_select']))){
			$exercise_cat = $_POST['e_cat_text'];
			$exercise_type_bool = false;
		}	
		
		//Check if data is from management
		if($edit_exercise == true){
			if ($exercise_type_bool == true){
				//update exercise
				$new_exercise->update_exercise($_POST['e_title'], $exercise_cat, $_POST['e_desc'], $edit_id);
			}
			if ($exercise_type_bool == false){
				//Insert new category
				$new_exercise->insert_cat($exercise_cat);
				//Update exercise
				$new_exercise->update_exercise($_POST['e_title'], $exercise_cat, $_POST['e_desc'], $edit_id);
			}
			$e_success = '<h2 class="success">Your exercise has been updated!</h2>';
		}
		
		else{
			if ($exercise_type_bool == true){
					
				//insert title, cat_title into exercise
				$new_exercise->insert_exercise($_POST['e_title'], $exercise_cat, $_POST['e_desc']); 
				
				//Foreach each step add the desc and img
				//also note i++ for step_num in steps table
				$step_images = '';	
				foreach(array_keys($_FILES['e_img']['name']) as $img){
					$step_images .= $new_exercise_img->single_image_array_insert($_FILES['e_img'], $img) . ',';
				} 
				$step_images = rtrim($step_images, ",");
				$step_images = explode(",",$step_images);
				
				//Find newly added exercise ID to add to each step
				$e_id = $new_exercise->last_exercise_id();
				
				$si = 0;
				$i = 1;
				foreach ($_POST['e_step'] as $key){
						$new_exercise->insert_exercise_step($key, $i, $_POST['e_title'], $step_images[$si], $e_id);
						$i++;
						$si++;
				}
			}
		
			if ($exercise_type_bool == false){
		
				$new_exercise->insert_cat($exercise_cat); 
				//insert title, cat_title into exercise
				$new_exercise->insert_exercise($_POST['e_title'], $exercise_cat, $_POST['e_desc']); 
				//Foreach each step add the desc and img
				//also note i++ for step_num in steps table
				$step_images = '';	
				foreach(array_keys($_FILES['e_img']['name']) as $img){
					$step_images .= $new_exercise_img->single_image_array_insert($_FILES['e_img'], $img) . ',';
				} 
				$step_images = rtrim($step_images, ",");
				$step_images = explode(",",$step_images);
				
				//Find newly added exercise ID to add to each step
				$e_id = $new_exercise->last_exercise_id();
				
				$si = 0;
				$i = 1;
				foreach ($_POST['e_step'] as $key){
						$new_exercise->insert_exercise_step($key, $i, $_POST['e_title'], $step_images[$si], $e_id);
						$i++;
						$si++;
				}
			}
		}
		$e_success = '<h2 class="success">Your exercise has been added!</h2>';
		$e_step_input = '<div class="e_step_div">
				<label for="e_step[]">Step:</label>
				<textarea class="ro_ex_desc e_step" name="e_step[]" placeholder="Step"></textarea>
				<label style="width:10%;" for="e_img[]">Step Image:</label><input name="e_img[]" type="file">
			</div>';
	}
}
//Pre Form Submit
$exercise_html1 =  $e_success;
$exercise_html1 .= '<form action="index.php" method="post" enctype="multipart/form-data">
			<label for="e_title">Exercise Title:</label>
			<input name="e_title" placeholder="Exercise Title" type="text" />';
$exercise_html1 .= $exercise_title_error;
$exercise_html1 .= '</br>
			<label for="e_desc">Exercise Description:</label>
			<textarea class="ro_ex_desc" name="e_desc" placeholder="Exercise Description"></textarea>';
$exercise_html1 .= $exercise_desc_error;				
$exercise_html1 .= '</br>
			<label for="e_cat">Category:</label>
			<select name="e_cat_select">
			<option></option>';
$exercise_html2 = '</select>
			or
			<input name="e_cat_text" type="text"/>';
$exercise_html2 .= $exercise_cat_error;			
$exercise_html2 .= '<hr>
<h3>Exercise Steps</h3>
<div class="e_steps">';
$exercise_steps = $e_step_input;
$exercise_html3 = '</div><button type="button" class="s-button e_add_step">Add Step</button>
				<button type="button" class="x-button e_remove_step">Remove Step</button><hr>
			
			<input class="s-button" type="submit" value="Submit" name="e_submit" />
		</form>';
/*----------END OF exerciseS SECTION----------*/
?>