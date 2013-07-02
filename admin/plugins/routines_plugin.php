<?php
/*--------------------------------

		ROUTINES PHP SECTION
		
---------------------------------*/
class routine{
	 
	public function __construct($conn)
    {
        $this->conn = $conn;
         return true;
    } 
	public function insert_routine($ro_title, $ro_level, $ro_focus, $ro_desc, $ro_of_month, $ro_warm){
		$sql = "INSERT INTO routine (ro_title, ro_level, ro_focus, ro_desc, ro_of_month, ro_warm) VALUES (:ro_title, :ro_level, :ro_focus, :ro_desc, :ro_of_month, :ro_warm)";
		$query = $this->conn->prepare($sql);
			$query->bindParam(":ro_title", $ro_title);
			$query->bindParam(":ro_level", $ro_level);
			$query->bindParam(":ro_focus", $ro_focus);
			$query->bindParam(":ro_desc", $ro_desc);
			$query->bindParam(":ro_of_month", $ro_of_month);
			$query->bindParam(":ro_warm", $ro_warm);
		$query->execute();
		
		foreach ($this->conn->query("SELECT ro_id FROM routine ORDER BY ro_id DESC LIMIT 1") as $row){
		$new_id = $row['ro_id'];
		}
		return $new_id;
	}
	
	public function insert_routine_exers($ro_exer_id, $ro_exer_desc, $ro_id, $sets, $reps, $ro_type_id, $reps_type, $burn){
		$rt = 0;
		$rep_t = 0;
		$s = 0;
		$r = 0;
		$d = 0;
		$b = 0;
		foreach ($ro_exer_id as $e_id){
			foreach ($this->conn->query("SELECT rt_title FROM routine_type WHERE rt_id = '".$ro_type_id[$rt]."'") as $row){
				$ro_type_title = $row['rt_title'];
			}
			$sql = "INSERT INTO routine_exercise (e_id, ro_id, ro_exer_desc, sets, reps, ro_type_title, reps_type, burn) VALUES (:e_id, :ro_id, :ro_exer_desc, :sets, :reps, :ro_type_title, :reps_type, :burn)";
			$query = $this->conn->prepare($sql);
				$query->bindParam(":e_id", $e_id);
				$query->bindParam(":ro_id", $ro_id);
				$query->bindParam(":sets", $sets[$s]);
				$query->bindParam(":reps", $reps[$r]);
				$query->bindParam(":ro_exer_desc", $ro_exer_desc[$d]);
				$query->bindParam(":ro_type_title", $ro_type_title);
				$query->bindParam(":reps_type", $reps_type[$rep_t]);
				$query->bindParam(":burn", $burn[$b]);
			$query->execute();	
				$s++;
				$r++;
				$d++;
				$b++;
				$rt++;
				$rep_t++;
		}
	}
	
	public function insert_routine_type_link($type_id, $ro_id){

		foreach ($type_id as $type){
			$sql = "INSERT INTO routine_type_link (rt_id, ro_id) VALUES (:rt_id, :ro_id)";
			$query = $this->conn->prepare($sql);
			$query->bindParam(":rt_id", $type);
			$query->bindParam(":ro_id", $ro_id);
			$query->execute();	
		}
	}
	
	public function clear_otm(){
		$query = $this->conn->prepare("UPDATE routine SET ro_of_month = 0");
		$query->execute();
	}
	
	public function update_routine($ro_title, $ro_desc, $ro_id){
		$query = $this->conn->prepare("UPDATE routine SET ro_title = :ro_title, ro_desc = :ro_desc WHERE ro_id = :id");
			$query->bindParam(":ro_title", $ro_title);
			$query->bindParam(":ro_desc", $ro_desc);
			$query->bindParam(":id", $ro_id);
		$query->execute();
	}

	public function display_exercises(){
			$return = '';
			foreach ($this->conn->query("SELECT e_id, e_title FROM exercise ORDER BY e_cat_title ASC") as $row){
				$return .= '<option value="'.$row["e_id"].'">'.$row["e_title"].'</option>';
			}
		echo $return;
	}
	
	public function display_routines(){
		$sql = "SELECT * FROM routine";
		$routines = $this->conn->query($sql);
    	foreach ($routines as $row){
			$sql2 = "SELECT e_title, routine_exercise.sets, routine_exercise.reps, routine_exercise.reps_type FROM routine INNER JOIN routine_exercise ON routine_exercise.ro_id = routine.ro_id INNER JOIN 
			exercise ON exercise.e_id = routine_exercise.e_id WHERE routine.ro_id = ".$row['ro_id']." ORDER BY exercise.e_id ASC";
			$routine_exercises = $this->conn->query($sql2);

			echo '	<div class="item_row">
						<div class="desc">
							<h2>'.$row['ro_title'].'</h2>
							<div class="exs">
								<ol>';
								foreach ($routine_exercises as $exercise){
									echo '<li><strong>'.$exercise['e_title'].'</strong> - '.$exercise['sets'].' Sets of '.$exercise['reps'].' '.$exercise['reps_type'].'</li>';
								}		
			echo	'			</ol>
							</div>
							<em class="show_exs">Show/Hide Exercises >>></em>
						</div>
						<div class="controls">
							<button type="button" class="x-button ro_remove">Delete</button>
							<a href="manage.php?action=edit&type=routine&id='.$row['ro_id'].'"><button type="button" class="s-button">Edit</button></a>
							
					';
		if($row['ro_of_month'] == 1){
			echo '<img class="star-button-sml" src="./core/css/images/star_on.png"/>';
		}
		else{
			echo '<a href="manage.php?action=otm&type=routine&id='.$row['ro_id'].'"><img class="star-button-sml" src="./core/css/images/star_off.png"/></a>';
		}
			echo 	'	
							<div class="ro_remove_div">
								<h3>Are you sure you want to delete this item?</h3></br>
								<a href="manage.php?action=delete&type=routine&id='.$row['ro_id'].'"><button type="button" class="x-button">Yes</button></a>
								<button type="button" class="s-button ro_remove_cnl">No</button>
							</div>
						</div>
					</div>';
		}		
	}
}

//Success variable
$ro_success = '';
//Error variables
$routine_title_error = '';
$routine_type_error = '';
$routine_desc_error = '';
$routine_level_error = '';
$routine_focus_error = '';
$routine_warm_error = '';


//Instance objects
$new_routine = new routine($conn);

if (isset ($_POST['ro_submit'])){

	if(!isset($_POST['ro_edit'])){
		while (list($key, $val) = each($_POST['ro_type'])) {
			if(empty($val)){
				$r_t_e = '1';
			}
			else{
				$r_t_e = '';
			}
		}
		if($r_t_e == '1'){
			$routine_type_error = '<em class="error">Routine types must be set</em>';
		}	
		if (!isset($_POST['ro_level'])){
			$routine_level_error = '<em class="error">A Routine Level is required</em>';
		}
		if (!isset($_POST['ro_focus'])){
			$routine_focus_error = '<em class="error">A Routine Focus is required</em>';
		}
		if (empty($_POST['ro_warm'])){
			$routine_warm_error = '<em class="error">A Routine Warm-up is required</em>';
		}
	}
	
	

	
	//Check to see if routine is entered
	if (empty($_POST['ro_title'])){
		$routine_title_error = '<em class="error">A Routine Title is required</em>';
	}	
	
	if (empty($_POST['ro_desc'])){
		$routine_desc_error = '<em class="error">A Routine description is required</em>';
	}	
	
	else{		
		//Check if data has been sent from management
		if(isset($_POST['ro_edit'])){
			$routine_edit = true;
			$routine_id = $_POST['ro_edit'];
			//Update routine
			$new_routine->update_routine($_POST['ro_title'], $_POST['ro_desc'], $routine_id);
			$ro_success = '<h2 class="success">Routine Updated!</h2>';
		}
		else{
			if($_POST['ro_of_month'] == 1){
				$new_routine->clear_otm();
			}
		
			$new_id = $new_routine->insert_routine($_POST['ro_title'], $_POST['ro_level'], $_POST['ro_focus'], $_POST['ro_desc'], $_POST['ro_of_month'], $_POST['ro_warm'] );
			$ro_type = array_unique($_POST['ro_type']);
			$new_routine->insert_routine_type_link($ro_type, $new_id);
			//Take the array of ro_ex types, remove repeating values then take these and the new ID and ....
			//Insert routine_type_link row
			
			//for each ro_ex_type, insert into routine_exercise table
			$new_routine->insert_routine_exers($_POST['ro_exer'], $_POST['ro_exer_desc'], $new_id, $_POST['sets'], $_POST['reps'], $_POST['ro_type'], $_POST['reps_type'], $_POST['ro_ex_burn']);
		
			$ro_success = '<h2 class="success">Routine Created!</h2>';
		}
	}
	
}
/*----------END OF ROUTINES PHP SECTION----------*/


/*--------------------------------

		ROUTINES HTML SECTION
		
---------------------------------*/

$ro_html = $ro_success;
$ro_html .= <<<EOD
		<form action="index.php" method="post">
			<label for="ro_title">Routine Title:</label>
			<input name="ro_title" placeholder="Routine" type="text" />
			
EOD;
$ro_html .= $routine_title_error;
$ro_html .= <<<EOD
</br>
			<label for="ro_of_month">Challenge of the Month:</label>
			<button type="button" class="star-button ro_of_month_btn"><img src="./core/css/images/star_on.png"/></button>
			<input name="ro_of_month" class="ro_of_month" type="hidden" value="0"/></br>
<hr>
EOD;
$ro_html .= <<<EOD


		<div class="radio_input">
		<label for="ro_type">Routine Level:</label></br>
			<input name="ro_level" type="radio" value="Beginner"><label>Beginner</label></br>
			<input style="margin-left:20%;" name="ro_level" type="radio" value="Intermediate"><label>Intermediate</label></br>
			<input name="ro_level" type="radio" value="Hard"><label>Hard</label>
EOD;
$ro_html .= $routine_level_error;	
$ro_html .= '</div>';


$ro_html .= <<<EOD


		<div class="radio_input" style="border-right:none;">
		<label for="ro_focus">Routine Focus:</label></br>
			<input name="ro_focus" type="radio" value="Full Body"><label style="width:30%;">Full Body</label></br>
			<input name="ro_focus" type="radio" value="Lower Body" style="margin-left:20%;"><label style="width:30%;">Lower Body</label></br>
			<input name="ro_focus" type="radio" value="Upper Body"><label style="width:30%;">Upper Body</label>
EOD;
$ro_html .= $routine_focus_error;
$ro_html .=	'	</div>
			<hr>';


$ro_html .= <<<EOD
		</br>
		<label for="ro_desc">Routine Description:</label>
		<textarea class="wys" name="ro_desc"></textarea>
EOD;
$ro_html .= $routine_desc_error;
$ro_html .= <<<EOD
<hr><hr>
<h2>Routine Exercises</h2>
<label for="ro_warm">Warmup Exercise:</label>
<textarea class="wys" name="ro_warm"></textarea>
EOD;
$ro_html .= $routine_warm_error;
$ro_html .= '
<h3>Main Exercises</h3>
';
$ro_html .= $routine_type_error;
$ro_html .= <<<EOD
<div class="ro_steps">
<div class="ro_step_div">
						<label for="ro_exer[]">Exercise:</label>
						<select name="ro_exer[]">
						<option class="ex_default" value="false"></option>
EOD;
$ro_html2 = <<<EOD
</select>	
						Sets
						<input type="text" class="s-and-rs" name="sets[]">
						Of
						<input type="text" class="s-and-rs" name="reps[]">
						
						<select name="reps_type[]">
							<option>Reps</option>
							<option>Mins</option>
							<option>Metres</option>
						</select>
						</br>
						<label for="ro_ex_desc">Exercise Description:</label>
						<textarea class="ro_ex_desc" name="ro_exer_desc[]"></textarea></br>
						</br>
						<label for="ro_type[]">Routine Type:</label>
						<select name="ro_type[]">
							<option  value="7"></option>
							<option  value="6">Cutting</option>
							<option  value="1">Bulking</option>
							<option  value="2">Power</option>
							<option  value="3">Speed</option>
							<option  value="5">Stamina</option>
							<option  value="4">Strength</option>
						</select>
						<label style="margin-left:1%;" for="ro_ex_burn[]">Burnout Exercise:</label>
						<select name="ro_ex_burn[]">
							<option>No</option>
							<option value="1">Yes</option>
						</select>
						
					</div>
		
</div><button type="button" class="s-button ro_add_step">Add Exercise</button>
				<button type="button" class="x-button ro_remove_step">Remove Exercise</button></br>
<hr>
				
				
				<input class="s-button" type="submit" value="Submit" name="ro_submit" />
		</form>
EOD;


/*----------END OF ROUTINES HTML SECTION----------*/
?>