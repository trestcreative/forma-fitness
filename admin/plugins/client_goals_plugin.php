<?php
/*--------------------------------

		CLIENT GOALS PHP SECTION
		
---------------------------------*/
class client_goals{
	public function __construct($conn)
	    {
	        $this->conn = $conn;
	         return true;
	    }
	
	public function display_clients(){
		foreach ($this->conn->query("SELECT * FROM clients ORDER BY c_last_name ASC") as $row){
			echo '	<div class="item_row client">
						<div class="desc">
							<img src="database_images/'.$row['c_img'].'"/>
							<h2>'.$row['c_last_name'].', '.$row['c_first_name'].' ('.$row['c_title'].')</h2>
						</div>
						<div class="controls">
							<button class="s-button cg_curr_btn" type="button">Current Goals</button>
							<button class="s-button cg_new_btn" type="button">New Goal</button>
						</div>
						<div class="cg_curr_div">
							<hr>
							<h2 style="color:#cccccc;">Current Goals</h2>
						
							'.$this->current_goals($row['c_id']).'
						
							
						</div>
						<div class="cg_new_div">
							<hr>
							<h2 style="color:#cccccc;">New Goal</h2>
							<form action="clients.php?action=goal&id='.$row['c_id'].'" method="post" enctype="multipart/form-data">
								<label for="cg_new_goal">Goal Description</label>
								<textarea class="wys" name="cg_new_goal"></textarea></br>							
								<label for="cg_new_date">Goal Completion Date:</label>
								<input name="cg_new_date" type="text" class="datepicker"/>
								<input type="hidden" name="cg_client" value="'.$row['c_id'].'"/></br>
								<input type="submit" value="Set Goal" name="cg_submit" class="p-button"/>
							</form>
						</div>
							
					</div>';
		}
	}
	
	public function insert_goal($goal, $date, $c_id){
		$date = date("Y-m-d H:i:s", strtotime($date));
		$sql = "INSERT INTO client_goals (cg_goal, cg_target_date, c_id) VALUES (:cg_goal, :cg_target_date, :c_id)";
		$query = $this->conn->prepare($sql);
		$query->bindParam(":cg_target_date", $date);
		$query->bindParam(":cg_goal", $goal);
		$query->bindParam(":c_id", $c_id);
		$query->execute();
		return '<h2 class="success">Client goal inserted!</h2>';
	}
	
	public function current_goals($id){
		$return = '';
		foreach($this->conn->query("SELECT * FROM client_goals WHERE c_id = '".$id."'") as $row){
			$return .= '<div class="desc">
							'.$row['cg_goal'].'<em style="color:#cccccc;display:inline;">Target date: '.date("D jS F Y", strtotime($row['cg_target_date'])).'</em>
							</div>
							<div class="controls">
								<form action="clients.php?action=goal&id='.$id.'" method="post" enctype="multipart/form-data">
									<input type="hidden" name="cg_curr_goal" value="'.$row['cg_id'].'"/>
									<input  type="submit" class="p-button" name="cg_curr_submit" value="Set as Completed"/>
								</form>	
							</div>
							<hr>';
					}
		return $return;
	}
	
	public function move_completed_goal($cg_id){
		foreach($this->conn->query("SELECT * FROM client_goals WHERE cg_id = '".$cg_id."'") as $row){
			$c_id = $row['c_id'];
			$goal = $row['cg_goal'];
			$target_date = $row['cg_target_date'];
			$set_date = $row['cg_set_date'];
		}
		$sql = "INSERT INTO client_completed_goals (c_id, cg_id, cg_goal, cg_target_date, cg_set_date) VALUES (:c_id, :cg_id, :cg_goal, :cg_target_date, :cg_set_date)";
		$query = $this->conn->prepare($sql);
			$query->bindParam(":c_id", $c_id);
			$query->bindParam(":cg_id", $cg_id);
			$query->bindParam(":cg_goal", $goal);
			$query->bindParam(":cg_target_date", $target_date);
			$query->bindParam(":cg_set_date", $set_date);
		$query->execute();
		$sql = "DELETE FROM client_goals WHERE cg_id = '".$cg_id."'";
		$query = $this->conn->prepare($sql);
			$query->bindParam(":cg_id", $cg_id);
		$query->execute();
		return '<h2 class="success">Goal Completed</h2>';
	}
	

}
//Object Instance
$client_goal = new client_goals($conn);

//Success/Error Variables
$cg_new_success = '';

//If new challenge is submitted
if(isset($_POST['cg_submit'])){
	if(empty($_POST['cg_new_goal'])){
		$cg_new_success = 'No Goal was set!';
	}
	else{
		//Add routine and client ids with to client_routine table
		$cg_new_success = $client_goal->insert_goal($_POST['cg_new_goal'],$_POST['cg_new_date'], $_POST['cg_client']);
	}
}

if(isset($_POST['cg_curr_submit'])){
	$cg_new_success = $client_goal->move_completed_goal($_POST['cg_curr_goal']);
}
/*--------------------------------

		CLIENT GOAL ASSIGNING HTML SECTION
		
---------------------------------*/

$cg_html = $cg_new_success;
?>