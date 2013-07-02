<?php
/*--------------------------------

		CLIENT PHP SECTION
		
---------------------------------*/
class client{
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
							<button class="s-button c_detail_btn" type="button">Details</button>
						</div>
						<div class="c_details"><hr>
							<div class="c_details_desc">
							
								<h3>Telephone - </h3><em>'.$row['c_telephone'].'</em></br>
								<h3>Email - </h3><em>'.$row['c_email'].'</em></br>
								<h3>Age - </h3><em>'.$row['c_age'].'</em></br>
								<h3>d.o.b - </h3><em>'.$row['c_dob'].' - (yyyy-mm-dd)</em>
							</div>
							<div class="controls">
								<a href="clients.php?action=edit&id='.$row['c_id'].'"><button type="button" class="s-button">Edit</button></a>
							</div>
						</div>	
					</div>';
		}
	}
	
	public function insert_client($first_name, $last_name, $gender, $title, $img, $telephone, $email, $dob_day, $dob_month, $dob_year){
		
		$password = random_password();
		$password = md5($password);
		$month = calculate_month($dob_month);
		
		$dob = $dob_year . '-' . $month . '-' . $dob_day;
		
		$date = new DateTime($dob);
		$now = new DateTime();
		$interval = $now->diff($date);
		$age = $interval->y;
		
		$random_nbr = rand(1,10);
		$username = $first_name . '.' . $last_name . $random_nbr; 
		
		$sql = "INSERT INTO clients (c_title, c_first_name, c_last_name, c_gender, c_img, c_telephone, c_email, c_dob, c_age, c_username, c_password )
		VALUES (:c_title, :c_first_name, :c_last_name, :c_gender, :c_img, :c_telephone, :c_email, :c_dob, :c_age, :c_username, :c_password)";
		$query = $this->conn->prepare($sql);
			$query->bindParam(":c_title", $title);
			$query->bindParam(":c_first_name", $first_name);
			$query->bindParam(":c_last_name", $last_name);
			$query->bindParam(":c_gender", $gender);
			$query->bindParam(":c_img", $img);
			$query->bindParam(":c_telephone", $telephone);
			$query->bindParam(":c_email", $email);
			$query->bindParam(":c_dob", $dob);
			$query->bindParam(":c_age", $age);
			$query->bindParam(":c_username", $username);
			$query->bindParam(":c_password", $password);
		$query->execute();
	}
			
	public function update_client($first_name, $last_name, $gender, $title, $img, $telephone, $email, $dob_day, $dob_month, $dob_year, $id){
		$month = calculate_month($dob_month);
		
		$dob = $dob_year . '-' . $month . '-' . $dob_day;
		
		$date = new DateTime($dob);
		$now = new DateTime();
		$interval = $now->diff($date);
		$age = $interval->y;
		
		$query = $this->conn->prepare("UPDATE clients SET c_first_name = :c_first_name, c_last_name = :c_last_name, c_gender = :c_gender, c_title = :c_title, c_img = :c_img, c_telephone = :c_telephone, c_email = :c_email, c_dob = :c_dob,	c_age = :c_age WHERE c_id = :c_id");
			$query->bindParam(":c_title", $title);
			$query->bindParam(":c_first_name", $first_name);
			$query->bindParam(":c_last_name", $last_name);
			$query->bindParam(":c_gender", $gender);
			$query->bindParam(":c_img", $img);
			$query->bindParam(":c_telephone", $telephone);
			$query->bindParam(":c_email", $email);
			$query->bindParam(":c_dob", $dob);
			$query->bindParam(":c_age", $age);
			$query->bindParam(":c_id", $id);
		$query->execute();
	}
	
	public function update_client_no_img($first_name, $last_name, $gender, $title, $telephone, $email, $dob_day, $dob_month, $dob_year, $id){
		$month = calculate_month($dob_month);
		
		$dob = $dob_year . '-' . $month . '-' . $dob_day;
		
		$date = new DateTime($dob);
		$now = new DateTime();
		$interval = $now->diff($date);
		$age = $interval->y;
		
		$query = $this->conn->prepare("UPDATE clients SET c_first_name = :c_first_name, c_last_name = :c_last_name, c_gender = :c_gender, c_title = :c_title, 
		c_telephone = :c_telephone, c_email = :c_email, c_dob = :c_dob,	c_age = :c_age WHERE c_id = :c_id");
			$query->bindParam(":c_title", $title);
			$query->bindParam(":c_first_name", $first_name);
			$query->bindParam(":c_last_name", $last_name);
			$query->bindParam(":c_gender", $gender);
			$query->bindParam(":c_telephone", $telephone);
			$query->bindParam(":c_email", $email);
			$query->bindParam(":c_dob", $dob);
			$query->bindParam(":c_age", $age);
			$query->bindParam(":c_id", $id);
		$query->execute();
	}
	
	public function delete_client($action_id){
		$query = $this->conn->prepare("DELETE FROM clients WHERE c_id = :id ");
		$query->bindParam(":id", $action_id);
		$query->execute();
		
	}
}
//Object Instance
$client = new client($conn);
$image = new images($conn);
/*--------------------------------

		CLIENT ADDING HTML SECTION
		
---------------------------------*/
//Success variables
$c_success =  '';
//Error Variables
$client_title_error = '';
$client_first_name_error = '';
$client_last_name_error = '';
$client_gender_error = '';
$client_tele_error = '';
$client_email_error = '';
$client_dob_error = '';

//Initial Fields
$client_first_name = '';
$client_last_name = '';
$client_gender = '';
$client_title = '';
$client_tele = '';
$client_email = '';
$client_day = '';
$client_month = '';
$client_year = '';

if(isset($_POST['c_submit'])){
	
	$client_first_name = $_POST['c_first_name'];
	$client_last_name = $_POST['c_last_name'];
	$client_gender = $_POST['c_gender'];
	$client_title = $_POST['c_title'];
	$client_tele = $_POST['c_telephone'];
	$client_email = $_POST['c_email'];
	$client_day = $_POST['c_dob_day'];
	$client_month = $_POST['c_dob_month'];
	$client_year = $_POST['c_dob_year'];
	
	if(empty($_POST['c_title'])){
		$client_title_error = 'A Title is required!';
	}
	if(empty($_POST['c_first_name'])){
		$client_first_name_error = 'A First Name is required!';
	}
	if(empty($_POST['c_last_name'])){
		$client_last_name_error = 'A Last Name is required!';
	}
	if(empty($_POST['c_gender'])){
		$client_gender_error = 'A Gender selection is required!';
	}
	if(!empty($_POST['c_telephone'])){
		if( strlen($_POST['c_telephone']) <> 11) { 
			$client_tele_error = 'A valid Telephone number is required!';
		}
	}
	if(!empty($_POST['c_email'])){
		if( !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $_POST['c_email']) ) {
			$client_email_error = 'A valid Email address is required';
		}
	}
	if(empty($_POST['c_dob_day']) || (empty($_POST['c_dob_month'])) || (empty($_POST['c_dob_year']))){
		$client_dob_error = 'Please fill in every Date of Birth field!';
	}
		
	else{
			//Insert image and receive its location for input
			if(empty($_FILES['c_img']['name'])){
				if($_POST['c_gender'] == 'Male'){
					$client_img = 'default_profile_m.png';//Default profile image
				}
				else{
					$client_img = 'default_profile_f.png';//Default profile image
				}
			}
			else{
				$client_img = $image->single_image_insert($_FILES['c_img']);
			}
			//Insert new client
			$client->insert_client($_POST['c_first_name'], $_POST['c_last_name'], $_POST['c_gender'], $_POST['c_title'], $client_img, $_POST['c_telephone'], $_POST['c_email'], $_POST['c_dob_day'], $_POST['c_dob_month'], $_POST['c_dob_year']);
			//Clear fields if completed
			$client_first_name = '';
			$client_last_name = '';
			$client_gender = '';
			$client_title = '';
			$client_tele = '';
			$client_email = '';
			$client_day = '';
			$client_month = '';
			$client_year = '';
			//Success variable
			$c_success = 'Your Client has been added!';
	}	
}
$client_add = $c_success;
$client_add .= '	<form action="clients.php" method="post" enctype="multipart/form-data">
					<label for="c_title">Title:</label>
					<select name="c_title">
						<option>'.$client_title.'</option>
						<option>Mr</option>
						<option>Mrs</option>
						<option>Miss</option>
						<option>Ms</option>
						<option>Master</option>
						<option>Dr</option>
						<option>Prof</option>
					</select>';
$client_add .= $client_title_error;
$client_add .= '	</br>
					
					<label for="c_first_name">First Name:</label>
					<input name="c_first_name" placeholder="First name" value="'.$client_first_name.'" type="text" /> ';
$client_add .= $client_first_name_error;
$client_add .= '	</br>
					
					<label for="c_last_name">Last Name:</label>
					<input name="c_last_name" placeholder="Last name" value="'.$client_last_name.'" type="text" /> ';
$client_add .= $client_last_name_error;
$client_add .= '	</br>
					
					<label for="c_gender">Gender:</label>
					<select name="c_gender"> 
						<option>'.$client_gender.'</option>
						<option>Male</option>
						<option>Female</option>
					</select>';
$client_add .= $client_gender_error;
$client_add .= '	</br>
					
					<label for="c_img">Client Profile Image:</label>
					<input name="c_img" type="file"></br>
					
					<label for="c_telephone">Telephone number:</label>
					<input name="c_telephone" placeholder="Telephone" value="'.$client_tele.'" type="text" />';
$client_add .= $client_tele_error;						
$client_add .= '	</br>
					<label for="c_email">E-mail:</label>
					<input name="c_email" placeholder="Email" value="'.$client_email.'" type="text" /> ';
$client_add .= $client_email_error;				
$client_add .= '	</br>
					
					<label>Date of Birth:</label>
					</br>
					<label for="c_dob_day">Day:</label>
					<select class="c_dob" name="c_dob_day">
						<option>'.$client_day.'</option>
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
					</select> ';
$client_add .= $client_dob_error;
$client_add .= '</br>
					
					<label for="c_dob_month">Month:</label>
					<select class="c_dob" name="c_dob_month">
						<option>'.$client_month.'</option>
						<option>January</option>
						<option>February</option>
						<option>March</option>
						<option>April</option>
						<option>May</option>
						<option>June</option>
						<option>July</option>
						<option>August</option>
						<option>September</option>
						<option>October</option>
						<option>November</option>
						<option>December</option>
					</select></br>
					
					<label for="c_dob_year">Year:</label>
					<select placeholder="Year" class="c_dob" name="c_dob_year">
						<option>'.$client_year.'</option>
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
					</select></br>';
$client_add .='					
					<input class="s-button" type="submit" name="c_submit" value="Submit"/>
				</form>';

/*--------------------------
	EDIT CLIENTS SECTION
----------------------------*/
//Success variables
$c_e_success =  '';
//Error Variables
$client_e_title_error = '';
$client_e_first_name_error = '';
$client_e_last_name_error = '';
$client_e_gender_error = '';
$client_e_tele_error = '';
$client_e_email_error = '';
$client_e_dob_error = '';

//Initial Fields
$client_e_first_name = '';
$client_e_last_name = '';
$client_e_gender = '';
$client_e_title = '';
$client_e_tele = '';
$client_e_email = '';
$client_e_day = '';
$client_e_month = '';
$client_e_year = '';

if($client_update == true){
	
	foreach ($conn->query("SELECT * FROM clients WHERE c_id = '".$action_id."'") as $row){
	//Initial Fields
	$client_e_first_name = $row['c_first_name'];
	$client_e_last_name = $row['c_last_name'];
	$client_e_gender = $row['c_gender'];
	$client_e_title = $row['c_title'];
	$client_e_tele = $row['c_telephone'];
	$client_e_email = $row['c_email'];
	$client_e_day = substr($row['c_dob'],8,2);
	$client_e_month = substr($row['c_dob'],5,2);
	$client_e_year = substr($row['c_dob'],0,4);
	}
}

if(isset($_POST['c_e_submit'])){
	$client_update = true;
	$client_error_bool = false;
	
	$client_e_first_name = $_POST['c_e_first_name'];
	$client_e_last_name = $_POST['c_e_last_name'];
	$client_e_gender = $_POST['c_e_gender'];
	$client_e_title = $_POST['c_e_title'];
	$client_e_tele = $_POST['c_e_telephone'];
	$client_e_email = $_POST['c_e_email'];
	$client_e_day = $_POST['c_e_dob_day'];
	$client_e_month = $_POST['c_e_dob_month'];
	$client_e_year = $_POST['c_e_dob_year'];
	
	if(empty($_POST['c_e_title'])){
		$client_e_title_error = 'A Title is required!';
		$client_error_bool = true;
	}
	if(empty($_POST['c_e_first_name'])){
		$client_e_first_name_error = 'A First Name is required!';
		$client_error_bool = true;
	}
	if(empty($_POST['c_e_last_name'])){
		$client_e_last_name_error = 'A Last Name is required!';
		$client_error_bool = true;
	}
	if(empty($_POST['c_e_gender'])){
		$client_e_gender_error = 'A Gender selection is required!';
		$client_error_bool = true;
	}
	if(!empty($_POST['c_e_telephone'])){
		if( strlen($_POST['c_e_telephone']) <> 11) { 
			$client_e_tele_error = 'A valid Telephone number is required!';
			$client_error_bool = true;
		}
	}
	if(!empty($_POST['c_e_email'])){
		if( !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $_POST['c_e_email']) ) {
			$client_email_error = 'A valid Email address is required';
			$client_error_bool = true;
		}
	}
	if(empty($_POST['c_e_dob_day']) || (empty($_POST['c_e_dob_month'])) || (empty($_POST['c_e_dob_year']))){
		$client_e_dob_error = 'Please fill in every Date of Birth field!';
		$client_error_bool = true;
	}
		
	elseif($client_error_bool == false){
			//Insert image and receive its location for input
			if(empty($_FILES['c_e_img']['name'])){
				//if updated
				//Update new client
				$client->update_client_no_img($_POST['c_e_first_name'], $_POST['c_e_last_name'], $_POST['c_e_gender'], $_POST['c_e_title'], $_POST['c_e_telephone'], $_POST['c_e_email'], $_POST['c_e_dob_day'], $_POST['c_e_dob_month'], $_POST['c_e_dob_year'], $_POST['c_edit']);
			}
			else{
				$client_e_img = $image->single_image_insert($_FILES['c_e_img']);
				//Update new client
				$client->update_client($_POST['c_e_first_name'], $_POST['c_e_last_name'], $_POST['c_e_gender'], $_POST['c_e_title'], $client_e_img, $_POST['c_e_telephone'], $_POST['c_e_email'], $_POST['c_e_dob_day'], $_POST['c_e_dob_month'], $_POST['c_e_dob_year'], $_POST['c_edit']);
			
			}
			
			//Clear fields if completed
			$client_e_first_name = '';
			$client_e_last_name = '';
			$client_e_gender = '';
			$client_e_title = '';
			$client_e_tele = '';
			$client_e_email = '';
			$client_e_day = '';
			$client_e_month = '';
			$client_e_year = '';
			//Success variable
			$c_e_success = 'Your Client has been Updated!';
	}	
}
//UPDATE CLIENT CHOICE FORM DROP DOWN
$client_update_choice = '	<label for="c_choose">Choose Client:</label>
							<select name="c_choose" class="c_select" ONCHANGE="location = this.options[this.selectedIndex].value;">';
		if(isset($action)){
			foreach ($conn->query("SELECT c_last_name, c_first_name FROM clients WHERE c_id = '".$action_id."'") as $row){
				$client_update_choice .=  '<option value="clients.php?action=edit&id='.$action_id.'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
			}
		}	
		foreach ($conn->query("SELECT c_id, c_last_name, c_first_name FROM clients ORDER BY c_last_name ASC") as $row){
$client_update_choice .=  '<option value="clients.php?action=edit&id='.$row['c_id'].'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
		}
$client_update_choice .= '	</select>
							<button type="button" class="s-button c_choice">Select</button>
							<hr>';					
								

//UPDATE FORM
$client_update_form = '	<form action="clients.php" method="post" enctype="multipart/form-data">
					<label for="c_e_title">Title:</label>
					<select name="c_e_title">
						<option>'.$client_e_title.'</option>
						<option>Mr</option>
						<option>Mrs</option>
						<option>Miss</option>
						<option>Ms</option>
						<option>Master</option>
						<option>Dr</option>
						<option>Prof</option>
					</select>';
$client_update_form .= $client_e_title_error;
$client_update_form .= '	</br>
					
					<label for="c_e_first_name">First Name:</label>
					<input name="c_e_first_name" placeholder="First name" value="'.$client_e_first_name.'" type="text" /> ';
$client_update_form .= $client_e_first_name_error;
$client_update_form .= '	</br>
					
					<label for="c_e_last_name">Last Name:</label>
					<input name="c_e_last_name" placeholder="Last name" value="'.$client_e_last_name.'" type="text" /> ';
$client_update_form .= $client_e_last_name_error;
$client_update_form .= '	</br>
					
					<label for="c_e_gender">Gender:</label>
					<select name="c_e_gender"> 
						<option>'.$client_e_gender.'</option>
						<option>Male</option>
						<option>Female</option>
					</select>';
$client_update_form .= $client_e_gender_error;
$client_update_form .= '	</br>
					<label for="c_e_img">Client Profile Image:</label>
					<button class="x-button c_update_img_cnl_btn" type="button">Cancel</button>
					<button class="s-button c_update_img_btn" type="button">New Profile Image</button>
					
					<div style="margin-left:15%;" class="c_update_img">
						
					<input name="c_e_img" type="file"></br>
					
					</div>
					</br>
					<label for="c_e_telephone">Telephone number:</label>
					<input name="c_e_telephone" placeholder="Telephone" value="'.$client_e_tele.'" type="text" />';
$client_update_form .= $client_e_tele_error;						
$client_update_form .= '	</br>
					<label for="c_e_email">E-mail:</label>
					<input name="c_e_email" placeholder="Email" value="'.$client_e_email.'" type="text" /> ';
$client_update_form .= $client_e_email_error;				
$client_update_form .= '	</br>
					
					<label>Date of Birth:</label>
					</br>
					<label for="c_e_dob_day">Day:</label>
					<select class="c_e_dob" name="c_e_dob_day">
						<option>'.$client_e_day.'</option>
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
					</select> ';
$client_update_form .= $client_e_dob_error;
$client_update_form .= '</br>
					
					<label for="c_e_dob_month">Month:</label>
					<select class="c_e_dob" name="c_e_dob_month">
						<option>'.$client_e_month.'</option>
						<option>January</option>
						<option>February</option>
						<option>March</option>
						<option>April</option>
						<option>May</option>
						<option>June</option>
						<option>July</option>
						<option>August</option>
						<option>September</option>
						<option>October</option>
						<option>November</option>
						<option>December</option>
					</select></br>
					
					<label for="c_e_dob_year">Year:</label>
					<select placeholder="Year" class="c_dob" name="c_e_dob_year">
						<option>'.$client_e_year.'</option>
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
					</select></br>';
if (($client_update == true) && (isset($action_id))){
	$client_update_form .= '<input type="hidden" name="c_edit" value="'.$action_id.'"/>';

$client_update_form .='					
					<input class="s-button" type="submit" name="c_e_submit" value="Update"/>
				</form></br>
					<button type="button" class="c_remove x-button">Delete Content</button>
					<div class="c_remove_div">
						<h3>Are you sure you want to delete this client?</h3>
						<a href="clients.php?action=delete&id='.$action_id.'"><button class="x-button" type="button">Yes</button></a>
						<button type="button" class="s-button c_remove_cnl">No</button>
					</div>';
	}				
/*--------------------------
	DELETE CLIENTS SECTION
----------------------------*/
if($client_delete == true){
	//Delete client
	$client->delete_client($action_id);
	header('Location: clients.php');
}
	
	
	
	
	
	
	

?>