<?php
/*--------------------------------

		CLIENT ROUTINE PHP SECTION
		
---------------------------------*/
class client_routine{
	public function __construct($conn)
	    {
	        $this->conn = $conn;
	         return true;
	    }
	
	public function display_clients(){
		$rou = '';
		foreach ($this->conn->query("SELECT * FROM routine ORDER BY ro_title ASC") as $routine){
			$rou .= '<option value="'.$routine['ro_id'].'">'.$routine['ro_title'].'</option>';
		}
		
		
		
		foreach ($this->conn->query("SELECT * FROM clients ORDER BY c_last_name ASC") as $row){
			echo '	<div class="item_row client">
						<div class="desc">
							<img src="database_images/'.$row['c_img'].'"/>
							<h2>'.$row['c_last_name'].', '.$row['c_first_name'].' ('.$row['c_title'].')</h2>
						</div>
						<div class="controls">
							<button class="s-button ch_curr_btn" type="button">Current Challenges</button>
							<button class="s-button ch_new_btn" type="button">New Challenge</button>
						</div>
						<div class="ch_curr_div"><hr>
							<h2 style="color:#cccccc;">Current Challenges</h2>
							
								';
			$curr_ch = '';
			$count = 0;
		foreach ($this->conn->query("SELECT routine.ro_title, routine.ro_id, client_routine.date_set FROM clients INNER JOIN client_routine ON
		clients.c_id = client_routine.c_id INNER JOIN routine ON client_routine.ro_id = routine.ro_id WHERE clients.c_id = '".$row['c_id']."'") as $chal){
			$time_date = time_date($chal['date_set']);
			$count++;
			$curr_ch .= '	<div class="desc">
								<form action="clients.php" method="post" enctype="multipart/form-data">
									<h2> &middot; '.$chal['ro_title'].'</h2><em style="color:#cccccc;display:inline;"> - '.$time_date.'</em>
							</div>
							<div style="display:inline;" class="controls">
									<input type="hidden" name="cc_curr_ch" value="'.$chal['ro_id'].'"/>
									<input type="hidden" name="cc_curr_client" value="'.$row['c_id'].'"/>
									<input  type="submit" class="p-button" name="cc_curr_submit" value="Completed"/>
								</form>
							</div>';
		}
			echo $curr_ch;
			if ($count == 0){
				echo 'No Challenges Set';
			}
			echo '		</div>
						<div class="ch_new_div"><hr>
							<div class="desc">
								<form action="clients.php" method="post" enctype="multipart/form-data">
									<h2 style="color:#cccccc;">New Challenge Routine</h2></br>
									<select style="width:50%;" name="cc_new_ch">
									<option class="ex_default" value="false">--- Challenge Routine ---</option>
										'.$rou.'
									</select>
									<input type="hidden" name="cc_new_client" value="'.$row['c_id'].'"/>	
									<input type="submit" name="cc_new_submit" class="s-button" value="Add Challenge"/>
								</form>
							</div>							
						</div>
					</div>';
		}
	}
	
	public function new_challenge($routine_id, $client_id){
		$sql = "INSERT INTO client_routine (c_id, ro_id) VALUES (:c_id, :ro_id)";
		
		$query = $this->conn->prepare($sql);
			$query->bindParam(":c_id", $client_id);
			$query->bindParam(":ro_id", $routine_id);
		$query->execute();
	}

}
//Object Instance
$client_routine = new client_routine($conn);

//Success/Error Variables
$cc_new_success = '';

//If new challenge is submitted
if(isset($_POST['cc_new_submit'])){
	if($_POST['cc_new_ch'] == 'false'){
		$cc_new_success = 'No Challenge Routine was set!';
	}
	else{
		//Add routine and client ids with to client_routine table
		$client_routine->new_challenge($_POST['cc_new_ch'], $_POST['cc_new_client']);
		$cc_new_success = 'Challenge set!';
	}
}

if(isset($_POST['cc_curr_submit'])){
	

}
/*--------------------------------

		CLIENT ROUTINE ASSIGNING HTML SECTION
		
---------------------------------*/

$cc_html = $cc_new_success;
?>