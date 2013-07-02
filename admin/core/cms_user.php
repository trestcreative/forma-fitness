<?php
/*--------------------------------

		USER LOGIN SECTION
		
---------------------------------*/
//Check if user is logged in. if not,
if (!isset($_SESSION['username'])){

	//Set variables as empty if no errors are set
	$bad_login = '';
	$empty = '';
	//Check if both password and username is set
	if (isset($_POST['username']) && isset($_POST['password'])) { 

		//If one field is empty echo "please fill in every field"
		if ( empty($_POST['username']) || empty($_POST['password']) ) {
			$empty = '<p class="formError">Please fill in every field</p>';
		}
		else {
			//Hash encrypt the password
			$password = md5($_POST['password']);

			$query = $conn->prepare("SELECT * FROM admin_users WHERE username =
			:username AND password = :password LIMIT 1");
			$query->bindParam(":username", $_POST['username']);
			$query->bindParam(":password", $password);
			$query->execute();
			$count = $query->rowCount();
			$pass = $query->fetch( PDO::FETCH_ASSOC );

			
			if ($count<=0) { 
				$bad_login = '<p>Incorrect username/password</p>'; 
			}
			else{
				$_SESSION['username'] = $_POST['username']; 
				$_SESSION['a_level'] = $pass['a_level'];
				
				
				header('Location: index.php');	
			
				exit;
			} 
		}
	}
	?>
	<script>
	$(document).ready(function() {
		$(window).load(function() {
			var body_height = $(window).height();
			var login_box = $('.user-login').height();
			var box_margin = (body_height - login_box) / 2;
			$('.user-login').css( "margin-top", box_margin);
		});
	});
	
	</script>
	<div class="user-login">
			<img src="core/css/images/trestlogo.png"/>
	<form action="index.php" method="post">
						<label for="username">Username:</label>
						<input name="username" placeholder="Username" type="text" />
						</br></br>
						<label for="password">Password:</label>
						<input type="password" name="password" placeholder="Password" /></br></br>
						<input class="p-button" type="submit" value="Login"/>
	<?php 	echo $bad_login;
			echo $empty;?>
	</form>
	</div>
<?php	
}
/*----------END OF USER LOGIN SECTION----------*/
/*--------------------------------

		USER LOGOUT SECTION
		
---------------------------------*/
if (isset($_POST['cms_logout'])){
	session_destroy();
	header("Location: index.php");

}

/*----------END OF USER LOGOUT SECTION----------*/
?>