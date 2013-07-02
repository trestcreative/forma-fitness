<?php
class recipe{
	 
	public function __construct($conn)
    {
        $this->conn = $conn;
         return true;
    } 
	public function insert_recipe($r_title, $r_ing, $r_img, $r_of_month){
		$sql = "INSERT INTO recipes (r_title, r_ing, r_img, r_of_month) VALUES (:r_title, :r_ing, :r_img, :r_of_month)";
		$query = $this->conn->prepare($sql);
		$query->bindParam(":r_title", $r_title);
		$query->bindParam(":r_ing", $r_ing);
		$query->bindParam(":r_img", $r_img);
		$query->bindParam(":r_of_month", $r_of_month);
		$query->execute();
	}
	
	public function insert_recipe_steps($r_step, $r_step_num, $r_title){
		$sql = "INSERT INTO recipe_steps (r_step_desc, r_step_num, r_title) VALUES (:r_step, :r_step_num, :r_title)";
		$query = $this->conn->prepare($sql);
		$query->bindParam(":r_step", $r_step);
		$query->bindParam(":r_step_num", $r_step_num);
		$query->bindParam(":r_title", $r_title);
		$query->execute();
	}
	
	public function clear_otm(){
		$clear = 0;
		$query = $this->conn->prepare("UPDATE recipes SET r_of_month = :clear");
			$query->bindParam(":clear", $clear);
		$query->execute();
	}
	
	public function display_recipes(){
	
		foreach ($this->conn->query("SELECT * FROM recipes ORDER BY r_date DESC") as $row){
		echo '<div class="item_row">
				<div class="desc">
					<h2>'.$row['r_title'].'</h2>
				</div>
				<div class="controls">
					<button type="button" class="x-button r_remove">Delete</button>
					';
		if($row['r_of_month'] == 1){
		echo '<img class="star-button-sml" src="./core/css/images/star_on.png"/>';
		}
		else{
		echo '<a href="manage.php?action=otm&type=recipe&id='.$row['r_id'].'"><img class="star-button-sml" src="./core/css/images/star_off.png"/></a>';
		}
		echo '			</br></br>
						<div class="r_remove_div">
							<h3>Are you sure you want to delete this item?</h3></br>
							<button type="button" class="s-button r_remove_cnl">No</button>
							<a href="manage.php?action=delete&type=recipe&id='.$row['r_id'].'"><button type="button" class="x-button">Yes</button></a>
						</div>
					</div>
				</div>';
		}
	}
	

}
/*--------------------------------

		recipeS SECTION
		
---------------------------------*/
//Success Variable
$r_success = '';
//Error variables
$recipe_title_error = '';
$recipe_ing_error = '';
$recipe_img_error = '';
$recipe_step_error = '';
$recipe_steps = '';

//Instance a recipe object
$new_recipe = new recipe($conn);
$new_recipe_image = new images($conn);

//Step form input data if not submitted
if (!isset ($_POST['r_submit'])){
	$r_step_input = '<div class="r_step_div">
				<label for="r_step[]">Recipe Step:</label>
				<textarea class="r_step ro_ex_desc" name="r_step[]" placeholder="Recipe Step"></textarea></br>
			</div>';
}
			
if (isset ($_POST['r_submit'])){
	$recipe_error = false;

	//If recipe Title is empty
	if(empty($_POST['r_title'])){
		$recipe_title_error = '<em class="error">Recipe Title is required</em>';
		 $recipe_error = true; 
	}	
	//If Recipe ingredients is empty
	$temp_r_ing = str_replace('<br>', '', $_POST['r_ing']);
	if(empty($temp_r_ing)){
		$recipe_ing_error = '<em class="error">Recipe Ingredients is required</em>';
		 $recipe_error = true; 
	}
	//If Recipe Image is empty
/* 	if(empty($_POST['r_img'])){
		$recipe_img_error = "Recipe Image is required</em>';
		 $recipe_error = true; 
	} */
	//If Recipe Step is empty
	if(empty($_POST['r_step'][0])){
		$recipe_step_error = '<em class="error">Recipe Step is required</em>';
		$recipe_error = true;
	}
		
	if($recipe_error == true){
		$r_step_input = '';
		foreach ($_POST['r_step'] as $key){
			$r_step_input .= '<div class="r_step_div">
				<label for="r_step[]">Recipe Step:</label>
				<textarea class="r_step ro_ex_desc" name="r_step[]" placeholder="Recipe Step">' . $key . '</textarea></br>
			</div>'; 
		}
	}
	
	else{
		//check 'of the month' post
		if($_POST['r_of_month'] == '1'){
			$new_recipe->clear_otm();
		}
		//First insert into recipe table
		$recipe_img = $new_recipe_image->single_image_insert($_FILES['r_img']);
		$new_recipe->insert_recipe($_POST['r_title'], $_POST['r_ing'], $recipe_img, $_POST['r_of_month']); 

		//second, add steps to recipe_Steps
		$i = 1;
		foreach ($_POST['r_step'] as $key){
				$new_recipe->insert_recipe_steps($key, $i, $_POST['r_title']);
				$i++;
			}
		
		
		$r_success = '<h2 class="success">Recipe Inserted Successfully!</h2>';
		$r_step_input = '<div class="r_step_div">
			<label for="r_step[]">Recipe Step:</label>
			<textarea class="r_step ro_ex_desc" name="r_step[]" placeholder="Recipe Step"></textarea></br>
		</div>';
		//Input the data
	}
}

//Pre Form Submit
$recipe_html1 =  $r_success;
$recipe_html1 .= '<form action="index.php" method="post" enctype="multipart/form-data">
			<label for="r_title">Recipe Title:</label>
			<input name="r_title" placeholder=Recipe Title" type="text" />';
$recipe_html1 .= $recipe_title_error;			
$recipe_html1 .= '</br>
				<label for="r_img">Recipe Image:</label>
				<input name="r_img" type="file">';
$recipe_html1 .= $recipe_img_error;				
$recipe_html1 .= '</br>
					<label for="r_ing">Recipe Ingredients:</label>
			<textarea class="wys" name="r_ing"></textarea>';
$recipe_html1 .= $recipe_ing_error;
$recipe_html1 .='</br>
				<h3>Recipe Steps</h3>
			<div class="r_steps">';
$recipe_html1 .= $recipe_step_error;
$recipe_steps = $r_step_input;
$recipe_html2 = '<button type="button" class="s-button r_add_step">Add Step</button>
				<button type="button" class="x-button r_remove_step">Remove Step</button>
			</div>

			<label for="r_of_month">Recipe of the Month:</label>
			<button type="button" class="star-button r_of_month_btn"><img src="./core/css/images/star_on.png"/></button>
			<input name="r_of_month" class="r_of_month" type="hidden" value="0"/></br>

			<input class="s-button" type="submit" value="Submit" name="r_submit" />
		</form>';


	
	

/*----------END OF recipeS SECTION----------*/
?>