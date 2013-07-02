<?php
/*--------------------------------

		CLIENT BOOKING PHP SECTION
		
---------------------------------*/
class client_booking{
	public function __construct($conn)
	    {
	        $this->conn = $conn;
	         return true;
	    }
	
	public function insert_booking($c_id, $cb_date, $cb_hours, $cb_mins, $cb_ampm, $cb_length, $cb_package, $cb_location){
		//Convert into datetime for MYSQL db
		$year = substr($cb_date, 6, 4);
		$month = substr($cb_date, 0, 2);
		$day = substr($cb_date, 3, 2);
			if($cb_ampm == 'pm'){
				if($cb_hours == 12){
					$hours = $cb_hours;
				}
				else{
				$hours = $cb_hours + 12;
				}
			}
			else{
				if($cb_hours == 12){
					$hours = 00;
				}
				else{
					$hours = $cb_hours;
				}
			}
		$length_hop = false;
		$cb_session_type = 'Personal';
		if($cb_length == 'class'){
			$cb_length = '45';
			$length_hop = true;	
			$cb_session_type = 'Class';
		}
		$mins = $cb_mins;	
		$datetime = $year.'-'.$month.'-'.$day.' '.$hours.':'.$mins.':00';
		//Find end datetime based on session length
			$d = strtotime($datetime);
			$end_date = $d+(60*$cb_length);
		$datetime_end = date("Y-m-d H:i:s", $end_date);
		//Find day type by datetime
		$block_type = $this->finding_block_type($datetime, $hours, $mins);
		if($length_hop == true){
			$check = true;
		}
		else{
			$check = $this->check_bookings($datetime, $datetime_end);
		}
		if($check == true){
		//Remove any request made by client
		$req_sql = "DELETE FROM client_booking_reqs WHERE c_id = '".$c_id."' AND br_type = 'session'";
		$req_query = $this->conn->prepare($req_sql);
		$req_query->execute();
		
		$sql = "INSERT INTO client_bookings (c_id, cb_session_length, cb_day_type, cb_datetime, cb_datetime_end, cb_package, cb_location, cb_session_type) VALUES (:c_id, :cb_session_length, :cb_day_type, :cb_datetime, :cb_datetime_end, :cb_package, :cb_location, :cb_session_type)";
		$query = $this->conn->prepare($sql);
			$query->bindParam(":c_id", $c_id);
			$query->bindParam(":cb_session_length", $cb_length);
			$query->bindParam(":cb_day_type", $block_type);
			$query->bindParam(":cb_datetime", $datetime);
			$query->bindParam(":cb_datetime_end", $datetime_end);
			$query->bindParam(":cb_package", $cb_package);
			$query->bindParam(":cb_location", $cb_location);
			$query->bindParam(":cb_session_type", $cb_session_type);
		$query->execute();
			return '<h2 class="success">Client Session Booked!</h2>';
		}
		else{
			return '<h2 class="error">This session has already been booked,</br>Please select another time</h2>';
		}
		
	}
	
	public function finding_block_type($date, $hours, $mins){
		$time = ceil($hours.$mins);
	//If it is set on the weekend
	if(date('N', strtotime($date)) >= 6){
		$block_type = 'Peak';
	}
	//If it is not on the weekend
	if(!(date('N', strtotime($date)) >= 6)){
		
		if($time >= 629 && $time <= 715){
			$block_type = 'Extreme';
		}
		if($time >= 730 && $time < 1000){
			$block_type = 'Peak';
		}
		if($time >= 1000 && $time < 1200){
			$block_type = 'Off-Peak';
		}
		if($time >= 1200 && $time < 1400){
			$block_type = 'Peak';
		}
		if($time >= 1400 && $time < 1700){
			$block_type = 'Off-Peak';
		}
		if($time >= 1700 && $time < 2000){
			$block_type = 'Peak';
		}
		if($time >= 2000 && $time <= 2200){
			$block_type = 'Extreme';
		}
		elseif(($time >= 0000 && $time < 0630) || ($time > 2200 && $time < 2401)){
			$block_type = 'NO TIME';
		}
	}

	return $block_type;	
}
		
	public function display_bookings($id){
		$curr_time = date("Y-m-d H:i:s");
		$return = '';
		
		$query = $this->conn->prepare("SELECT * FROM client_bookings WHERE c_id = '".$id."' AND cb_datetime > '".$curr_time."'");
		$query->execute();
		$count = $query->rowCount();
		if($count > 0){
			foreach($query as $row){
			if($row['cb_session_length'] == 60){
				$length = '1 Hour';
			}
			else{
				$length = $row['cb_session_length'].' Mins';
			}
				$return .= '<div class="item_row">
									<div class="desc">
										<h2>'.date("g:i a F j, Y ", strtotime($row['cb_datetime'])).'</h2></br>
										<h3>'.$length.' ('.$row['cb_day_type'].') - @ '.$row['cb_location'].'</h3>
									</div>
									<div class="controls">
										<button type="button" class="x-button cb_remove">Delete</button></br></br></br>
										<div style="float:right;" class="cb_remove_div">
											<h3>Are you sure you want to delete this booking?</h3></br>
											<button type="button" class="s-button cb_remove_cnl">No</button>
											<a href="clients.php?action=booking&id='.$id.'&delete='.$row['cb_id'].'&package='.$row['cb_package'].'"><button class="x-button c_detail_btn" type="button">Yes</button></a>
										</div>
									</div>
							</div>';
				}
		}
		else{
			$return .= '<em>This client currently does not have any bookings</em></br></br>';
		}
		return $return;
	}
	
	public function display_packages($id){
		$return = '';
		$query = $this->conn->prepare("SELECT * FROM client_packages WHERE c_id = '".$id."'");
		$query->execute();
		$count = $query->rowCount();
		if($count > 0){
			foreach($query as $row){
				$return .= '<div class="item_row">
								<div class="desc">
									<h2>'.$row['cp_package'].'</h2></br>
									<h3>'.$row['cp_sessions'].' Sessions left - '.$row['cp_classes'].' Classes left</h3>
								</div>
								<div class="controls">
									<button type="button" class="x-button cp_remove">Delete</button></br></br></br>
									<div style="float:right;" class="cp_remove_div">
										<h3>Are you sure you want to delete this booking?</h3></br>
										<button type="button" class="s-button cp_remove_cnl">No</button>
										<a href="clients.php?action=booking&id='.$id.'&delete_p='.$row['cp_id'].'"><button class="x-button" type="button">Yes</button></a>
									</div>
								</div>
							</div>';
			}
		}
		else{
			$return .= '<em>This client currently does not have any packages</em></br></br>';
		}
		return $return;
	}
	
	public function check_bookings($start, $end){
		$query = $this->conn->prepare("SELECT * FROM client_bookings WHERE cb_datetime BETWEEN '".$start."' AND '".$end."'");
		$query->execute();
		$count = $query->rowCount();
		if ($count == 1){
			foreach($query as $row){
				$existing_start = $row['cb_datetime'];
				$existing_end = $row['cb_datetime_end'];
			}
			if($end == $existing_start){
				return true;
			}
			if($end > $existing_start && $end < $existing_end){
				return false;
			}
			if($start == $existing_end){
				return true;
			}
			if($start < $existing_end && $start > $existing_start){
				return false;
			}
		}
		else{
			return true;
		}
	}
	
	public function list_client_packages($id){
		$return = '';
		foreach($this->conn->query("SELECT cp_package, cp_id FROM client_packages WHERE c_id = '".$id."'") as $row){
			$return .= '<option value="'.$row['cp_id'].'">'.$row['cp_package'].'</option>';
		
		}
		return $return;
	}
	
	public function delete_booking($cb_id, $cp_id){
		foreach($this->conn->query("SELECT cb_session_type FROM client_bookings WHERE cb_id = '".$cb_id."'") as $row){
			$type = $row['cb_session_type'];
		}
		if($cp_id > 0){
			if($type == 'Class'){
				$this->add_package_class($cp_id);
			}
			else{
				$this->add_package_session($cp_id);
			}
		}
		$query = $this->conn->prepare("DELETE FROM client_bookings WHERE cb_id = :cb_id");
		$query->bindParam(":cb_id", $cb_id);
		$query->execute();
	}
	public function delete_package($cp_id){
		$query = $this->conn->prepare("DELETE FROM client_packages WHERE cp_id = :cp_id");
		$query->bindParam(":cp_id", $cp_id);
		$query->execute();
	}
	
	public function minus_package_session($c_id, $cp_id){
		foreach($this->conn->query("SELECT cp_sessions, cp_id FROM client_packages WHERE c_id = '".$c_id."' AND cp_id = '".$cp_id."'") as $row){
			$current_sessions = $row['cp_sessions'];
		}
		$new_sessions = $current_sessions - 1;
		if($new_sessions == 0){
			$this->delete_package($cp_id);
		}
		else{
			$query = $this->conn->prepare("UPDATE client_packages SET cp_sessions = :new_sessions WHERE c_id = '".$c_id."' AND cp_id = '".$cp_id."'");
				$query->bindParam(":new_sessions", $new_sessions);
			$query->execute();	
		}
	}
	
	public function minus_package_class($c_id, $cp_id){
		foreach($this->conn->query("SELECT cp_classes, cp_id FROM client_packages WHERE c_id = '".$c_id."' AND cp_id = '".$cp_id."'") as $row){
			$current_classes = $row['cp_classes'];
		}
		$new_classes = $current_classes - 1;
		if($new_classes == 0){
			$this->delete_package($cp_id);
		}
		else{
			$query = $this->conn->prepare("UPDATE client_packages SET cp_classes = :new_classes WHERE c_id = '".$c_id."' AND cp_id = '".$cp_id."'");
				$query->bindParam(":new_classes", $new_classes);
			$query->execute();	
		}
	}
	
	public function add_package_session($cp_id){
		foreach($this->conn->query("SELECT cp_sessions, cp_id FROM client_packages WHERE cp_id = '".$cp_id."'") as $row){
			$current_sessions = $row['cp_sessions'];
		}
		$new_sessions = $current_sessions + 1;
		
			$query = $this->conn->prepare("UPDATE client_packages SET cp_sessions = :new_sessions WHERE cp_id = '".$cp_id."'");
				$query->bindParam(":new_sessions", $new_sessions);
			$query->execute();	
	}
	
	public function add_package_class($cp_id){
		foreach($this->conn->query("SELECT cp_classes, cp_id FROM client_packages WHERE cp_id = '".$cp_id."'") as $row){
			$current_classes = $row['cp_classes'];
		}
		$new_classes = $current_classes + 1;
		
			$query = $this->conn->prepare("UPDATE client_packages SET cp_classes = :new_classes WHERE cp_id = '".$cp_id."'");
				$query->bindParam(":new_classes", $new_classes);
			$query->execute();	
	}
	
	public function insert_package($id, $package){
		switch ($package)
		{
						
		case '5 x 30 Minutes':
			$sessions = 5;
			$classes = 0;
		break;
		case '10 x 30 Minutes':
			$sessions = 10;
			$classes = 0;
		break;
		case '5 x 45 Minutes':
			$sessions = 5;
			$classes = 0;
		break;
		case '10 x 45 Minutes':
			$sessions = 10;
			$classes = 0;
		break;
		case '4 Weeks (2 x 30 minutes per week)':
			$sessions = 8;
			$classes = 0;
		break;
		case '4 Weeks (3 x 30 minutes per week)':
			$sessions = 12;
			$classes = 0;
		break;
		case '4 Weeks (2 x 45 minutes per week)':
			$sessions = 8;
			$classes = 0;
		break;
		case '4 Weeks (3 x 45 minutes per week)':
			$sessions = 12;
			$classes = 0;
		break;
		case 'Elite Program: 5 Weeks (2 x 30 minutes + 1 class per week)':
			$sessions = 10;
			$classes = 5;
		break;
		case 'Hlete-F: 5 Weeks ( 3 x 15 minutes per week)':
			$sessions = 15;
			$classes = 0;
		break;
		case 'Businessman: 5 Weeks  (2 x 15 minutes + 1 class per week)':
			$sessions = 10;
			$classes = 5;
		break;
		
		default:
			return '<em class="error">An error has occurred</em>';
		}
		//Remove any request made by client
		$req_sql = "DELETE FROM client_booking_reqs WHERE c_id = '".$id."' AND br_type = 'package'";
		$req_query = $this->conn->prepare($req_sql);
		$req_query->execute();
		
		$sql = "INSERT INTO client_packages (c_id, cp_package, cp_sessions, cp_classes) VALUES (:c_id, :cp_package, :cp_sessions, :cp_classes)";
		$query = $this->conn->prepare($sql);
			$query->bindParam(":c_id", $id);
			$query->bindParam(":cp_package", $package);
			$query->bindParam(":cp_sessions", $sessions);
			$query->bindParam(":cp_classes", $classes);
		$query->execute();	
		return '<h2 class="success">Client Package successfully added</h2>';
	}
}		

//instance object
$client_booking = new client_booking($conn);

//Package error variables
$client_package_error = '';
$client_package_success = '';
//If package is set
if(isset($_POST['cp_p_submit'])){
	if($_POST['cp_p_package'] == 'package'){ 
		$client_package_error = '<em class="error">No package was chosen</em></br>';
	 }
	else{
	//Insert into client_packages table
		$client_package_success = $client_booking->insert_package($_GET['id'], $_POST['cp_p_package']);
	} 
}

//Error variables
$booking_date_error = '';
$booking_time_error = '';
$booking_length_error = '';
$booking_location_error = '';
$booking_success = '';
//Success variable
$client_booking_success = '';

if(isset($_GET['delete'])){
	$client_booking->delete_booking($_GET['delete'], $_GET['package']);
}
if(isset($_GET['delete_p'])){
	$client_booking->delete_package($_GET['delete_p']);
}

//If booking is set
if(isset($_POST['cb_submit'])){
	if(empty($_POST['cb_date'])){
		$booking_date_error = '<em class="error"> A booking date is required</em>';
	}
	if($_POST['cb_hours'] == 'Hours'){
		$booking_time_error = '<em class="error"> A booking date is required</em>';
	}
	if($_POST['cb_mins'] == 'Mins'){
		$booking_time_error = '<em class="error"> Booking time is required</em>';
	}
	if($_POST['cb_ampm'] == 'ampm'){
		$booking_time_error = '<em class="error"> Booking time is required</em>';
	}
	if($_POST['cb_length'] == 'Length'){
		$booking_length_error = '<em class="error"> A Session length is required</em>';
	}
	if(empty($_POST['cb_location'])){
		$booking_location_error = '<em class="error"> A Session location is required</em>';
	}
	
	else{
 		if($_POST['cb_package'] != 0){
			if($_POST['cb_length'] == 'class'){
				$client_booking->minus_package_class($_POST['c_id'], $_POST['cb_package']);
			}
			else{
				$client_booking->minus_package_session($_POST['c_id'], $_POST['cb_package']);
			}
		}
		$client_booking_success = $client_booking->insert_booking($_POST['c_id'], $_POST['cb_date'], $_POST['cb_hours'], $_POST['cb_mins'], $_POST['cb_ampm'], $_POST['cb_length'], $_POST['cb_package'], $_POST['cb_location']);
	
	}
}


//UPDATE CLIENT CHOICE FORM DROP DOWN
$client_booking_choice = '	<label for="c_choose">Choose Client:</label>
							<select name="c_choose" class="cb_select" ONCHANGE="location = this.options[this.selectedIndex].value;">';
		if(isset($action)){
			foreach ($conn->query("SELECT c_last_name, c_first_name FROM clients WHERE c_id = '".$action_id."'") as $row){
				$client_booking_choice .=  '<option value="clients.php?action=booking&id='.$action_id.'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
			}
		}	
		foreach ($conn->query("SELECT c_id, c_last_name, c_first_name FROM clients ORDER BY c_last_name ASC") as $row){
$client_booking_choice .=  '<option value="clients.php?action=booking&id='.$row['c_id'].'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
		}
$client_booking_choice .= '	</select>
							<button type="button" class="s-button cb_choice">Select</button>
							<hr>';	
							


/* //Error variables
$client_booking_error = false; */
//Empty variable
$client_booking_html = '';

							
//If a client is selected
if($client_booking_bool == true){
$client_booking_html .= $client_booking_success;	
$client_booking_html .= $client_package_success;	
$client_booking_html .= '<h3>Current Packages</h3>';
$client_booking_html .= $client_booking->display_packages($_GET['id']);
$client_booking_html .= $client_package_error;
$client_booking_html .= '<button type="button" class="cb_add_p s-button">Add Client Package</button></br>
						<div class="cb_add_p_div item_row">
							<form action="clients.php?action=booking&id='.$_GET['id'].'" method="post" enctype="multipart/form-data">

							<label style="width:8%;" for="cb_package">Package:</label>
							<select name="cp_p_package">
								<option value="package">Package</option>
								<option value="5 x 30 Minutes">5 x 30 Minutes</option>
								<option value="10 x 30 Minutes">10 x 30 Minutes</option>
								<option value="5 x 45 Minutes">5 x 45 Minutes</option>
								<option value="10 x 45 Minutes">10 x 45 Minutes</option>
								<option value="4 Weeks (2 x 30 minutes per week)">4 Weeks (2 x 30 minutes per week)</option>
								<option value="4 Weeks (3 x 30 minutes per week)">4 Weeks (3 x 30 minutes per week)</option>
								<option value="4 Weeks (2 x 45 minutes per week)">4 Weeks (2 x 45 minutes per week)</option>
								<option value="4 Weeks (3 x 45 minutes per week)">4 Weeks (3 x 45 minutes per week)</option>
								<option value="Elite Program: 5 Weeks (2 x 30 minutes + 1 class per week)">Elite Program: 5 Weeks (2 x 30 minutes + 1 class per week)</option>
								<option value="Hlete-F: 5 Weeks ( 3 x 15 minutes per week)">Hlete-F: 5 Weeks ( 3 x 15 minutes per week)</option>
								<option value="Businessman: 5 Weeks  (2 x 15 minutes + 1 class per week)">Businessman: 5 Weeks  (2 x 15 minutes + 1 class per week)</option>
							</select> 
							</br></br>
							<input type="hidden" name="c_id" value="'.$_GET['id'].'"/>
							<input type="submit" class="p-button" name="cp_p_submit" value="Set Booking"/>
							</form>
						</div>';
$client_booking_html .= '<h3>Current Bookings</h3>';
$client_booking_html .= $client_booking->display_bookings($_GET['id']);
$client_booking_html .= '<button type="button" class="cb_add s-button">Add Client Booking</button></br>
						<div class="cb_add_div item_row">
							<form action="clients.php?action=booking&id='.$_GET['id'].'" method="post" enctype="multipart/form-data">
							
							<label for="cb_date">Date:</label>
							<p style="display:inline;"><input name="cb_date" type="text" class="datepicker" /></p>';
$client_booking_html .= $booking_date_error; 							
$client_booking_html .= '	</br>
							<label for="cb_hours">Time:</label>
							<select style="width:10%;" name="cb_hours">
								<option value="Hours">Hours</option>
								<option value="01">1</option>
								<option value="02">2</option>
								<option value="03">3</option>
								<option value="04">4</option>
								<option value="05">5</option>
								<option value="06">6</option>
								<option value="07">7</option>
								<option value="08">8</option>
								<option value="09">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select> :
							<select style="width:10%;" name="cb_mins">
								<option value="Mins">Mins</option>
								<option value="00">00</option>
								<option value="15">15</option>
								<option value="30">30</option>
								<option value="45">45</option>
							</select>
							<select style="width:9%;" name="cb_ampm">
								<option value="ampm">AM / PM</option>
								<option value="am">AM</option>
								<option value="pm">PM</option>
							</select>';
$client_booking_html .= $booking_time_error; 							
$client_booking_html .= '	</br>
							<label for="cb_length">Session Length:</label>
							<select style="width:10%;" name="cb_length">
								<option value="Length">Length</option>
								<option value="15">15 Mins</option>
								<option value="30">30 Mins</option>
								<option value="45">45 Mins</option>
								<option value="60">1 Hour</option>
								<option value="class">Class</option>
							</select>';
$client_booking_html .= $booking_length_error; 						
$client_booking_html .= '</br>
							<label for="cb_package">Part of package?:</label>
							<select style="width:31%;" name="cb_package">
							<option value="0"></option>';
$client_booking_html .=	$client_booking->list_client_packages($_GET['id']);	
$client_booking_html .= '	<option value="0">No package</option>					
						</select></br>
						<label style="margin-top:3px;" for="cb_location">Location:</label>
						<input type="text" name="cb_location"/>';
$client_booking_html .= $booking_location_error; 						
$client_booking_html .= '	</br>
							<input type="hidden" name="c_id" value="'.$_GET['id'].'"/>
							<input type="submit" class="p-button" name="cb_submit" value="Set Booking"/>
						</form>
						</div>';
							
}							