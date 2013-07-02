<?php
/*--------------------------------

		ADMIN SECTION PHP SECTION
		
---------------------------------*/

class admin{
	public function __construct($conn){
	        $this->conn = $conn;
	         return true;
	}
	
	public function clients_password_reset(){
		$return = '';
		foreach($this->conn->query("SELECT * FROM clients") as $row){
			$return .= '<div class="item_row">
							<div class="desc">
								<img src="database_images/'.$row['c_img'].'"/>
								<h2>'.$row['c_last_name'].', '.$row['c_first_name'].' ('.$row['c_title'].')</h2>
							</div>
							<div class="controls">
								<button class="x-button cpwd_btn" type="button">RESET PASSWORD</button>
								<div class="cpwd_reset_div">
									<h3>Are you sure you want to reset this password?</h3>
									<button class="s-button cpwd_btn_cnl" type="button">CANCEL</button>
									<button class="x-button cpwd_btn_cnf" type="button">CONFIRM</button>
									<div class="cpwd_admin_div" style="background-color:#9a9a9a;padding:0.5em;">
										<form action="admin.php" method="post">
											<label style="display:block;float:none;width:100%;">Enter admin password to confirm</label>
											<input style="width:100%;" type="password" name="admin_pass"/></br>
											<input type="hidden" name="c_id" value="'.$row['c_id'].'"/>
											<input type="submit" value="RESET" name="cpwd_reset" class="x-button"/>
										</form>
									</div>
								</div>
							</div>
						</div>';
		}
		return $return;
	}
	
	public function reset_password($admin_pass, $c_id){
		$query = $this->conn->prepare("SELECT password FROM admin_users WHERE username = '".$_SESSION['username']."' AND password = '".md5($admin_pass)."'");
		$query->execute();
		$count = $query->rowCount();
		
		if($count == 1){
			$password = random_password();
			$sql = "UPDATE clients SET c_password = :pwd WHERE c_id = :c_id";
			$query = $this->conn->prepare($sql);
				$query->bindParam(":pwd", $password);
				$query->bindParam(":c_id", $c_id);
			$query->execute();
			
			foreach($this->conn->query("SELECT * clients WHERE c_id = '".$c_id."'")as $row){
				$email = $row['c_email'];
				$name = $row['c_first_name'].' '.$row['c_last_name'];
			}
			
			$to = "alex@trestcreative.com";
			$subject = "Test mail FORMA";
			$message = "Dear ".$name.",</br> 
						Your password has been reset. This is your new password: </br>
						<h2>".$password."</h2></br>
						Please change your password once you have logged in using this temporary password";
			$from = "alex@trestcreative.com";
			$headers = "From:" . $from;
			mail($to,$subject,$message,$headers);

			return '<h2 class="success">Password RESET!</h2>';
		}
		else{
			return "<em>Incorrect Admin Password!</em>";
		}	
	}
}

//instance admin object
$admin = new admin($conn);

$pwd_reset_success = '';
$pwd_reset_error = '';

if(isset($_POST['cpwd_reset'])){
	if(empty($_POST['admin_pass'])){
		$pwd_reset_success = 'Password field empty!';
	}
	else{
		$pwd_reset_error = $admin->reset_password($_POST['admin_pass'], $_POST['c_id']);
	}
}

/*--------------------------------

		ADMIN SECTION HTML SECTION
		
---------------------------------*/
$c_pwd = $pwd_reset_success;
$c_pwd = $pwd_reset_error;
$c_pwd .= $admin->clients_password_reset();
?>