<?php
/*--------------------------------

		CLIENT MEASUREMENT PHP SECTION
		
---------------------------------*/
class measurement{
	 
	public function __construct($conn)
    {
        $this->conn = $conn;
         return true;
    }

	public function insert_new_measurement($weight, $height, $c_id){
		$sql = "INSERT INTO client_measurements (c_id, c_weight, c_height) VALUES (:c_id, :c_weight, :c_height)";
		$query = $this->conn->prepare($sql);
			$query->bindParam(":c_id", $c_id);
			$query->bindParam(":c_weight", $weight);
			$query->bindParam(":c_height", $height);
		$query->execute();
	}
}

//Instance object
$measurement = new measurement($conn);

//UPDATE CLIENT CHOICE FORM DROP DOWN
$client_measure_choice = '	<label for="c_choose">Choose Client:</label>
							<select name="cm_choose" class="cm_select" ONCHANGE="location = this.options[this.selectedIndex].value;">';
		if(isset($action)){
			foreach ($conn->query("SELECT c_last_name, c_first_name FROM clients WHERE c_id = '".$action_id."'") as $row){
				$client_measure_choice .=  '<option value="clients.php?action=measurement&id='.$action_id.'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
			}
		}	
		foreach ($conn->query("SELECT c_id, c_last_name, c_first_name FROM clients ORDER BY c_last_name ASC") as $row){
$client_measure_choice .=  '<option value="clients.php?action=measurement&id='.$row['c_id'].'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
		}
$client_measure_choice .= '	</select>
							<button type="button" class="s-button cm_choice">Select</button>
							<hr>';	

							
							
//Success variable
$client_measure_success = '';
//Error variables
$client_weight_error = '';
$client_height_error = '';
$client_measurement_error = false;
//Empty variable
$client_measure = '';

if(isset($_POST['cm_submit'])){
	if(empty($_POST['cm_weight'])){
		$client_weight_error = 'A weight is required!';
		$client_measurement = true;
		$action_id = $_POST['cm_client'];
	}
	
	if(empty($_POST['cm_height'])){
		$client_height_error = 'A height is required!';
		$client_measurement = true;
		$action_id = $_POST['cm_client'];
	}
	
	else{
		//Add to client_measurement table
		$measurement->insert_new_measurement($_POST['cm_weight'], $_POST['cm_height'], $_POST['cm_client']);
		$client_measure_success = 'New measurement was taken!</br>';
		$client_measurement = false;
	}
}
//If a client is selected
if($client_measurement == true){
	
$client_measure = '	<form action="clients.php" method="post" enctype="multipart/form-data">
						<label for="cm_weight">Client Weight:</label>
						<input style="width:10%;" type="text" name="cm_weight"/> <em style="color:#cccccc;">(KG)</em> ';
$client_measure .=	$client_weight_error;						
$client_measure .=	'</br>
						<label for="cm_height">Client Height:</label>
						<input style="width:10%;" type="text" name="cm_height"/> <em style="color:#cccccc;">(CM)</em> ';
$client_measure .=	$client_height_error;
$client_measure .=	'</br>
						<input type="hidden" name="cm_client" value="'.$action_id.'"/>
						<input type="submit" name="cm_submit" class="s-button" value="Submit"/>
					</form>';
}