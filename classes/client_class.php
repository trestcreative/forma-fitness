<?php
/*---------------------------
|							|
|							|
|	  	CLIENT CLASS		|
|							|
|							|
----------------------------*/

/*
CLASS JOBS

*/

class client{
	
	public function __construct($conn){
	        $this->conn = $conn;
	         return true;
	} 
		
	public function get_full_name($username){
		foreach ($this->conn->query("SELECT c_first_name, c_last_name FROM clients WHERE c_username = '".$username."'") as $row){
			return $row['c_first_name'].' '.$row['c_last_name'];
		}
	}
	public function get_user_img($username){
		foreach ($this->conn->query("SELECT c_img FROM clients WHERE c_username = '".$username."'") as $row){
			return '<img src="./admin/database_images/'.$row['c_img'].'"/>';
		}
	}
	
	public function get_client_id($username){
		foreach ($this->conn->query("SELECT c_id FROM clients WHERE c_username = '".$username."'") as $row){
			return $row['c_id'];
		}
	}
	
	public function booking_request_check($id, $type){
		$curr_date = date("Y-m-d H:i:s");
		$sql = "SELECT * FROM client_booking_reqs WHERE c_id = '".$id."' AND br_type = '".$type."' LIMIT 1";
		$query = $this->conn->prepare($sql);
		$query->execute();
		$count = $query->rowCount();
		if($count == 0){
			$return = '<form method="post" action="index.php?p=client">
							<label for="book_req">Request a booking</label>
							<input type="hidden" value="'.$type.'" name="book_req_type"/></br>
							<input type="submit" name="book_req_submit" value="Request!"/>
						</form>';
		}
		else{
			$return = '<em>You have made a request for a booking</em>';
		}
		return $return;
	}
	
	public function insert_booking_request($id, $type){
		//Check one hasnt already been made
		$check_sql = "SELECT * FROM client_booking_reqs WHERE c_id = '".$id."' AND br_type = '".$type."'";
		$check_query = $this->conn->prepare($check_sql);
		$check_query->execute();
		$check_count = $check_query->rowCount();
		
		if($check_count > 0){
			return '<em>You have made a request for a booking</em>';
		}
		else{
			$sql = "INSERT INTO client_booking_reqs (c_id, br_type) VALUES (:c_id, :br_type)";
			$query = $this->conn->prepare($sql);
			$query->bindParam(":c_id", $id);
			$query->bindParam(":br_type", $type);
			$query->execute();
			return '<em>You have made a request for a booking</em>';
		}
	}
	
	public function get_next_session($id){
		$curr_date = date("Y-m-d H:i:s");
		$sql = "SELECT cb_datetime, cb_datetime_end, cb_session_length, cb_location, cb_session_type FROM client_bookings WHERE c_id = '".$id."' AND cb_datetime > '".$curr_date."' ORDER BY cb_datetime DESC LIMIT 1";
		$query = $this->conn->prepare($sql);
		$query->execute();
		$count = $query->rowCount();
		$type = 'session';
		//check for current booking requests
		if($count == 0){
			$return = '<p>You currently have no sessions booked</p></br>
						'.$this->booking_request_check($id, $type);
		}
		else{
			foreach ($query as $row){
				$return ='<p>Your next session is:</p></br>
						<h2>'.date("l jS M h:i a", strtotime($row['cb_datetime'])).'</h2>
						<h3 style="text-decoration:none;">'.$row['cb_session_length'].' Minute '.$row['cb_session_type'].' Session</h3>
						<em class="white">@ '.$row['cb_location'].'</em></br></br>';
			}
		}
		return $return;
	}
	
	public function get_package($id){
		$sql = "SELECT * FROM client_packages WHERE c_id = '".$id."'";
		$query = $this->conn->prepare($sql);
		$query->execute();
		$count = $query->rowCount();
		$type = 'package';
		if($count == 0){
			$return = '<p>You currently have no session packages booked<p></br>'
			.$this->booking_request_check($id, $type);
		}
		
		else{
			$return = "<p>Your current package is:</p></br>";
			foreach($query as $row){
				$cp_sessions = $row['cp_sessions'];
				$cp_classes = $row['cp_classes'];
				$cp_package = $row['cp_package'];
			}
			$blocks = 15 - $cp_sessions;
			$class_blocks = 5 - $cp_classes;
			
			$return .= '<h2 style="word-wrap: break-word">'.$cp_package.' Package</h2></br>';
			$return .= '<em>Sessions Left</em></br>';
			$return .= '<div class="session_box" title="'.$cp_sessions.'">';
			$return .= str_repeat("<div class='block_box'></div>", $cp_sessions); 	
			$return .= str_repeat("<div class='empty_box'></div>", $blocks); 				
			$return .= '</div></br>';
			$return .= '<em>Classes Left</em></br>';
			$return .= '<div class="class_box" title="'.$cp_classes.'">';
			$return .= str_repeat("<div class='block_box'></div>", $cp_classes); 	
			$return .= str_repeat("<div class='empty_box'></div>", $class_blocks); 	
			$return .= '</div>';
			
		}
		return $return;
	}
	
	public function update_client_details($id, $post){
		if(empty($post['c_first_name']) || (empty($post['c_last_name'])) || (empty($post['c_email'])) || (empty($post['c_telephone']))){
			$return = '<h2 style="background-color:#FF0033;padding:0.25em;">Some fields were left empty!</h2>';
		}
		else{
			$dob = $post['c_dob_year'].'-'.$post['c_dob_month'].'-'.$post['c_dob_day'].' 00:00:00';
			$dob = date("Y-m-d H:i:s", strtotime($dob));
			$date = new DateTime($dob);
			$now = new DateTime();
			$interval = $now->diff($date);
			$age = $interval->y;
			
			
			$query = $this->conn->prepare("UPDATE clients SET c_title = :c_title, c_first_name = :c_first_name, c_last_name = :c_last_name, c_gender = :c_gender,
			c_telephone = :c_telephone, c_email = :c_email, c_dob = :c_dob, c_age  = :c_age WHERE c_id = '".$id."'");
				$query->bindParam(":c_title", $post['c_title']);
				$query->bindParam(":c_first_name", $post['c_first_name']);
				$query->bindParam(":c_last_name", $post['c_last_name']);
				$query->bindParam(":c_gender", $post['c_gender']);
				$query->bindParam(":c_telephone", $post['c_telephone']);
				$query->bindParam(":c_email", $post['c_email']);
				$query->bindParam(":c_dob", $dob);
				$query->bindParam(":c_age", $age);
			$query->execute();
			$return = '<h2 style="background-color:#99FF66;padding:0.25em;">Your personal details have been updated!</h2>';
		
		}
		return $return;
	}
	
	public function update_client_password($c_id, $post){
		if(empty($post['curr_pwd']) || empty($post['new_pwd']) || empty($post['new_pwd2'])){
			$return = '<h2 style="background-color:#FF0033;padding:0.25em;">Some fields were left empty!</h2>';
		}
		$check_sql = "SELECT c_password FROM clients WHERE c_id = '".$c_id."' AND c_password = '".$post['curr_pwd']."'";
		$check_query = $this->conn->prepare($check_sql);
		$check_query->execute();
		$count = $check_query->rowCount();
		if($count == 0){
			$return = '<h2 style="background-color:#FF0033;padding:0.25em;">Your current password was incorrect, please try again</h2>';
		}
		if($post['new_pwd'] != $post['new_pwd2']){
			$return = '<h2 style="background-color:#FF0033;padding:0.25em;">Your new passwords do not match, please try again</h2>';			
		}
		else{
			
			$sql = "UPDATE clients SET c_password = :new_pwd WHERE c_id = '".$c_id."'";
			$pwd = md5($post['new_pwd']);
			$query = $this->conn->prepare($sql);
			$query->bindParam(":new_pwd", $pwd);
			$query->execute();
			$return = '<h2 style="background-color:#99FF66;padding:0.25em;">Your password has been updated!</h2>';
		}
		
		return $return;
	}
	
	public function display_pd_form($id){
		foreach($this->conn->query("SELECT * FROM clients WHERE c_id = '".$id."'") as $row){
			$dob = $row['c_dob'];
			$dob_year = substr($dob, 0, 4);
			$dob_month = date("F", strtotime($dob));
			$dob_day = substr($dob, 8, 2);			
			$dob_month_num = substr($dob, 5, 2);			
			
			
			$return = '
						<form method="POST" action="index.php?p=client&c=details">
							<label for="c_title">Title:</label>
							<select name="c_title">
								<option value="'.$row['c_title'].'">'.$row['c_title'].'</option>
								<option>Mr</option>
								<option>Mrs</option>
								<option>Miss</option>
								<option>Ms</option>
								<option>Master</option>
								<option>Dr</option>
								<option>Prof</option>
							</select></br>
							
							<label for="c_first_name">First Name:</label>
							<input type="text" name="c_first_name" value="'.$row['c_first_name'].'"/></br>
							
							<label for="c_last_name">Last Name:</label>
							<input type="text" name="c_last_name" value="'.$row['c_last_name'].'"/></br>
							
							<label for="c_gender">Gender</label>
							<select name="c_gender">
								<option value="'.$row['c_gender'].'">'.$row['c_gender'].'</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select></br>
							
							<label for="c_telephone">Telephone</label>
							<input type="text" name="c_telephone" value="'.$row['c_telephone'].'"/></br>
							
							<label for="c_email">Email:</label>
							<input type="text" value="'.$row['c_email'].'" name="c_email"/></br>
							
							<label style="width:19.6%; for="c_dob">Date of birth:</label>
							<label style="width:10%;" for="c_dob_day">Day:</label>
							<select style="width:10.4%;" name="c_dob_day">
								<option value="'.$dob_day.'">'.$dob_day.'</option>
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5</option>
								<option>6</option>
								<option>7</option>
								<option>8</option>
								<option>9</option>
								<option>10</option>
								<option>11</option>
								<option>12</option>
								<option>13</option>
								<option>14</option>
								<option>15</option>
								<option>16</option>
								<option>17</option>
								<option>18</option>
								<option>19</option>
								<option>20</option>
								<option>21</option>
								<option>22</option>
								<option>23</option>
								<option>24</option>
								<option>25</option>
								<option>26</option>
								<option>27</option>
								<option>28</option>
								<option>29</option>
								<option>30</option>
								<option>31</option>
							</select></br>
							
							<label style="width:10%; margin-left:20%;" for="c_dob_month">Month:</label>
							<select style="width:10.4%;" name="c_dob_month">
								<option value="'.$dob_month_num.'">'.$dob_month.'</option>
								<option value="01">January</option>
								<option value="02">February</option>
								<option value="03">March</option>
								<option value="04">April</option>
								<option value="05">May</option>
								<option value="06">June</option>
								<option value="07">July</option>
								<option value="08">August</option>
								<option value="09">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select></br>
							
							<label style="width:10%; margin-left:20%;" for="c_dob_year">Year:</label>
							<select style="width:10.4%;" name="c_dob_year">
								<option value="'.$dob_year.'">'.$dob_year.'</option>
								<option>1950</option>
								<option>1951</option>
								<option>1952</option>
								<option>1953</option>
								<option>1954</option>
								<option>1955</option>
								<option>1956</option>
								<option>1957</option>
								<option>1958</option>
								<option>1959</option>
								<option>1960</option>
								<option>1961</option>
								<option>1962</option>
								<option>1963</option>
								<option>1964</option>
								<option>1965</option>
								<option>1966</option>
								<option>1967</option>
								<option>1968</option>
								<option>1969</option>
								<option>1970</option>
								<option>1971</option>
								<option>1972</option>
								<option>1973</option>
								<option>1974</option>
								<option>1975</option>
								<option>1976</option>
								<option>1977</option>
								<option>1978</option>
								<option>1979</option>
								<option>1980</option>
								<option>1981</option>
								<option>1982</option>
								<option>1982</option>
								<option>1983</option>
								<option>1984</option>
								<option>1985</option>
								<option>1986</option>
								<option>1987</option>
								<option>1988</option>
								<option>1989</option>
								<option>1990</option>
								<option>1991</option>
								<option>1992</option>
								<option>1993</option>
								<option>1994</option>
								<option>1995</option>
								<option>1996</option>
								<option>1997</option>
								<option>1998</option>
								<option>1999</option>
								<option>2000</option>
								<option>2001</option>
								<option>2002</option>
								<option>2003</option>
								<option>2004</option>
								<option>2005</option>
								<option>2006</option>
								<option>2007</option>
								<option>2008</option>
								<option>2009</option>
								<option>2010</option>
							</select></br>
							
							<input type="submit" value="Update" name="c_update"/>
						</form>	';
							
		}
		return $return;
	}

}	