<?php
/*--------------------------------

		CLIENT PROGRAMS PHP SECTION
		
---------------------------------*/
class client_programs{
	
	public function __construct($conn){
	        $this->conn = $conn;
	         return true;
	}
	
	public function insert_program($c_id){
		$check_sql = "SELECT * FROM client_program WHERE c_id = '".$c_id."'";
		$check_query = $this->conn->prepare($check_sql);
		$check_query->execute();
		$count = $check_query->rowCount();
		
		if($count == 0){		
			$current_date = date("Y-m-d H:i:s");
			$sql = "INSERT INTO client_program (c_id, cprog_dateset) VALUES (:c_id, :dateset)";
			$query = $this->conn->prepare($sql);
			$query->bindParam(":c_id", $c_id);
			$query->bindParam(":dateset", $current_date);
			$query->execute();
			
			$return_sql = "SELECT cprog_id FROM client_program WHERE c_id = :c_id";
			$return_query = $this->conn->prepare($return_sql);
			$return_query->bindParam(":c_id", $c_id);
			$return_query->execute();
			foreach ($return_query as $row){
				return $row['cprog_id'];
			}
		}
		else{
			foreach($check_query as $row){
				return $row['cprog_id'];
			}
		}
	}
	
	public function insert_program_routine($cprog_id, $ro_id){
		$sql = "INSERT INTO cprog_routine_link (cprog_id, ro_id) VALUES (:cprog_id, :ro_id)";
		$query = $this->conn->prepare($sql);
		$query->bindParam(":cprog_id", $cprog_id);
		$query->bindParam(":ro_id", $ro_id);
		$query->execute();
		
		return '<h2 class="success">Routine was added to program</h2>';
	}
	
	public function display_routines($c_id){
		$return = '';
		$sql = 'SELECT cprog_dateset FROM client_program WHERE c_id = "'.$c_id.'"';
		$query = $this->conn->prepare($sql);
		$query->execute();
		$count = $query->rowCount();
		if($count == 0){
			
		}
		else{
			foreach(($this->conn->query($sql))as $row){
				$time_date = date("D jS F Y", strtotime($row['cprog_dateset']));
			}
			$return .= '<div class="item_row"><h2>Program Routines</h2><h3>Program set: '.$time_date.'</h3>';
			
			foreach ($this->conn->query("SELECT routine.ro_title, routine.ro_id FROM client_program
			INNER JOIN cprog_routine_link ON client_program.cprog_id = cprog_routine_link.cprog_id INNER JOIN routine ON
			cprog_routine_link.ro_id = routine.ro_id WHERE client_program.c_id = '".$c_id."'") as $row){
				$return .= '	<div class="desc">
									<form action="clients.php" method="post" enctype="multipart/form-data">
										<h3 style="color:#222222;">&middot; '.$row['ro_title'].'</h3>
								</div>
								<div style="display:inline;" class="controls">
										<input type="hidden" name="cg_curr_goal" value="'.$row['ro_id'].'"/>
										
									</form>
								</div>';
			}
			$return .= '</div>';
		}
		return $return;
	}
	
	public function list_routines(){
		$return = '';
		foreach ($this->conn->query("SELECT * FROM routine ORDER BY ro_title ASC") as $routine){
			$return .= '<option value="'.$routine['ro_id'].'">'.$routine['ro_title'].'</option>';
		}
		return $return;
	}

	public function program_chart($c_id){
		/*foreach routine of client program display:
			@ e_cat_titles of routine
			@ warmup
			@ exercises split into tables of ro_type_titles and their respective exercises
		*/
		
		//Program ID
		$query = $this->conn->prepare("SELECT * FROM client_program WHERE c_id = '".$c_id."'");
		$query->execute();
		$count = $query->rowCount();
		if($count == 0){
			return false;
		}
		else{
			foreach($query as $row){
				$prog_id = $row['cprog_id'];
			}
		
		$i = 1;
		$return = '';
		foreach($this->conn->query("SELECT * FROM cprog_routine_link INNER JOIN routine ON cprog_routine_link.ro_id = 
		routine.ro_id WHERE cprog_routine_link.cprog_id = '".$prog_id."'") as $row){
			
		$ro_id = $row['ro_id'];
		$ro_title = $row['ro_title'];
		$warmup = $row['ro_warm'];
		$query = $this->conn->prepare("SELECT * FROM routine WHERE ro_id = '".$ro_id."'");
		$query->execute();
		
		$return .= '<table class="ro_table">
						<tr style="background-color:#dfdfdf;">
							<th>Day '.$i.' Routine</th><th><h2>'.$ro_title.'</h2></th>
						</tr>
						<tr style="background-color:#448ccb;">
							<th>Warm-up</th><th>'.$warmup.'</th>
						</tr>
					</table>';
	
			foreach($this->conn->query("SELECT routine_type.rt_title FROM routine_type_link INNER JOIN routine_type ON 
			routine_type_link.rt_id = routine_type.rt_id WHERE routine_type_link.ro_id = '".$ro_id."'")as $row){
		
			$specific_type = $row['rt_title'];
			$return .= '</br><h2>'.$specific_type.' Exercises</h2>
						<table class="ex_table">
							<tr style="background-color:#dfdfdf;"><th>Exercise \ Date</th><th></th><th></th><th></th><th></th><th></th></tr>';
									
				foreach($this->conn->query("SELECT * FROM routine_exercise INNER JOIN exercise ON routine_exercise.e_id = exercise.e_id
						WHERE routine_exercise.ro_type_title = '".$specific_type."' AND routine_exercise.ro_id = '".$ro_id."'") as $exercise){
						
						$ex_sets = $exercise['sets'];
						$ex_reps = $exercise['reps'];
						$ex_reps_type = $exercise['reps_type'];
						$ex_burn = $exercise['burn'];
						$ex_title = $exercise['e_title'];
						if($ex_burn == 1){
							$burn = 'burn';
						}
						else{
							$burn = '';
						}
					$return .= '<tr class="'.$burn.'"><td>'.$ex_title.'</td><td></td><td></td><td></td><td></td><td></td></tr>';
				
					}
			$return .= '</table>';
			}	 
			$return .= '</br></br><hr><hr></br></br>';
		$i++;
		}
			
	return $return;
		}
		
	}
	
	
	
}
//Instance program object
$client_program = new client_programs($conn);

//Error variables
$routine_to_program_error = '';
$client_program_success = '';
$add_box_show = false;

if(isset($_POST['cprog_submit'])){
	if($_POST['cprog_new_ro'] == 0){
		$routine_to_program_error = '<em class="error">No routine was selected</em>';
		$add_box_show = true;
	}
	else{
		//Insert the routine to client_routine table 
		$cprog_id = $client_program->insert_program($action_id);
		$client_program_success = $client_program->insert_program_routine($cprog_id, $_POST['cprog_new_ro']);
	}
}




//UPDATE CLIENT CHOICE FORM DROP DOWN
$client_program_choice = '	<label for="cprog_choose">Choose Client:</label>
							<select name="cprog_choose" class="cprog_select" ONCHANGE="location = this.options[this.selectedIndex].value;">';
		if(isset($action)){
			foreach ($conn->query("SELECT c_last_name, c_first_name FROM clients WHERE c_id = '".$action_id."'") as $row){
				$client_program_choice .=  '<option value="clients.php?action=program&id='.$action_id.'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
			}
		}	
		foreach ($conn->query("SELECT c_id, c_last_name, c_first_name FROM clients ORDER BY c_last_name ASC") as $row){
$client_program_choice .=  '<option value="clients.php?action=program&id='.$row['c_id'].'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
		}
$client_program_choice .= '	</select>
							<button type="button" class="s-button cprog_choice">Select</button>
							<hr>';		

$client_program_html = '';							
if($client_program_bool == true){
$client_program_html .=	$client_program_success;					
$client_program_html .= '<h3>Current Program Routines</h3>
							<button type="button" class="s-button cprog_add_ro">Add Routine to Program</button>
							<button type="button" class="s-button cprog_chart_btn">View Program Chart</button>
							<div class="item_row cprog_add_div">	
							<h3>Add to Program</h3>
								<form action="clients.php?action=program&id='.$action_id.'" method="post" enctype="multipart/form-data">
									<label for="cprog_new_ro">Choose Routine:</label>
									<select name="cprog_new_ro">
									<option value="0"></option>';
$client_program_html .= $client_program->list_routines();
$client_program_html .= '			</select>
									<input class="s-button" type="submit" name="cprog_submit" value="Add Routine"/></br>';
$client_program_html .=		$routine_to_program_error;							
$client_program_html .=		'</form>
							</div>';							
$client_program_html .= $client_program->display_routines($action_id);
$client_program_html .= '<div class="item_row cprog_chart">
						<h2>Program Chart</h2></br>';
$client_program_html .= $client_program->program_chart($action_id);
$client_program_html .= '</div>';


}
?>