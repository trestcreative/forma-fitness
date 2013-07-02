<?php
# client.php
/* 
 *	This is the client profile page.
 
 @ Validate and login client
 @ Allow Client to view progress of challenges, weight progress
 @ Allow Client to view Nutrition 
 @ Allow Client to view their details
 @ Allow Client to view Training Session blocks left
 @ Message system 
 @ Allow Client to Change Password
 */
?>
 <!-- Jquery Scripts for article.php page -->
<script>
$(document).ready(function() {
	$('#stage_2, #stage_3').hide();
	$('.stage_btn').click(function(){
		$('.stage_btn').removeClass('active');
		$(this).addClass('active');
		var stage_called = $(this).attr('id').substr(10, 1);
		$('#stage_2, #stage_3, #stage_1').hide();
		$('#stage_' + stage_called).show();
	});
	
	$('.pwd_chng_div').hide();
	$('.pwd_chng').click(function(){
		$('.pwd_chng_div').slideToggle();
	});
});	
</script>
<?php
//Logout user if requested
if (isset($_POST['c_logout'])){
	session_destroy();
	header('Location: index.php');
}

//Check if user is logged in
if (!isset($_SESSION['client_username'])){

	//Set variables as empty if no errors are set
	$bad_login = '';
	$empty = '';
	//Check if both password and username is set
	if (isset($_POST['c_login_username']) && isset($_POST['c_login_password'])) { 

		//If one field is empty echo "please fill in every field"
		if ( empty($_POST['c_login_username']) || empty($_POST['c_login_password']) ) {
			$empty = '<em class="error">Please fill in every field</em>';
		}
		else {
			//Hash encrypt the password
			$password = md5($_POST['c_login_password']);

			$query = $conn->prepare("SELECT * FROM clients WHERE c_username =
			:username AND c_password = :password LIMIT 1");
			$query->bindParam(":username", $_POST['c_login_username']);
			$query->bindParam(":password", $password);
			$query->execute();
			$count = $query->rowCount();
			$pass = $query->fetch( PDO::FETCH_ASSOC );

			
			if ($count<=0) { 
				$bad_login = '<em class="error">Incorrect username/password</em>'; 
			}
			else{
				$_SESSION['client_username'] = $_POST['c_login_username']; 
				header('Location: index.php?p=client');	
			} 
		}
	}
	?>
	
	<form class="col_2 push_3 login_box" action="index.php?p=client" method="post">
						<label for="username">Username:</label>
						<input name="c_login_username" placeholder="Username" type="text" />
						</br></br>
						<label for="password">Password:</label>
						<input type="password" name="c_login_password" placeholder="Password" /></br></br>
						<input class="btn" name="c_login_submit" type="submit" value="Login"/></br></br>
	<?php 	echo $bad_login;
			echo $empty;?>
	</form>
	
<?php
}
else{
$c_id = $client->get_client_id($_SESSION['client_username']);

//error variables
$client_update_success = '';
$client_pwd_success = '';
$client_input_success = '';

//Form processing
if(isset($_POST['book_req_submit'])){
	$client->insert_booking_request($c_id, $_POST['book_req_type']);
}
if(isset($_POST['c_update'])){
	$client_update_success = $client->update_client_details($c_id, $_POST);
}

if(isset($_POST['pwd_chng'])){
	$client_pwd_success = $client->update_client_password($c_id, $_POST);
}

if(isset($_POST['cprog_submit'])){
	$client_input_success = $client->progress_insert($_POST, $c_id);
}




?>

<div class="col_8">
	<h1><?php echo $client->get_full_name($_SESSION['client_username']); ?></h1>
	<hr>
</div>
<div class="col_2 client_sidebar">

	<?php echo $client->get_user_img($_SESSION['client_username']);?>

	<aside>
		<ul>
			<li class="<?php if(!isset($_GET['c'])) echo 'active';?>"><a href="index.php?p=client">Profile</a></li>
			<li class="<?php if(isset($_GET['c']) && ($_GET['c'] == 'sessions')) echo 'active';?>"><a href="index.php?p=client&c=sessions">Sessions</a></li>
			<li class="<?php if(isset($_GET['c']) && ($_GET['c'] == 'details')) echo 'active';?>"><a href="index.php?p=client&c=details">Personal Detials</a></li>
			<li class="<?php if(isset($_GET['c']) && ($_GET['c'] == 'messages')) echo 'active';?>"><a href="index.php?p=client&c=messages">Messages</a></li>
		</ul>
		
	</aside>
</div>
<?php
		if(!isset($_GET['c']) || ($_GET['c'] == 'home')){
		// CLIENT PROFILE HOME PAGE
		?>
<div class="col_6 client_main">
	<div class="col_8 alpha omega">
		<ul>
			<li class="active stage_btn" id="stage_btn_1"><h3>Summery</h3></li>
			<li class="stage_btn" id="stage_btn_2"><h3>Programs</h3></li>
			<li class="stage_btn" id="stage_btn_3"><h3>Stats</h3></li>
		<ul>
	</div>
	<div class="alpha omega stage" id="stage_1">
		<div class="col_2">
			<div class="notifications">
			<h3>Trainer Notifications</h3>
				<p>No current notifications at the moment</p>
			</div>
		</div>
		<div class="col_6">
		
			<h3>Sessions</h3>
			
					<?php echo $client->get_next_session($c_id);?>
					<hr>
			<h3>Packages</h3>
					<?php
					 echo $client->get_package($c_id); 
					?>
		</div>
	</div>
	<div class="alpha omega stage" id="stage_2">
		<h2>main stage 2</h2>
	</div>
	<div class="alpha omega stage" id="stage_3">
		<h2>main stage 3</h2>
	</div>
</div>

<?php 
	}
	// CLIENT SESSIONS PAGE
	if(isset($_GET['c']) && ($_GET['c'] == 'sessions')){
	?>
<script>
	$(document).ready(function() {
	$('.cpp_ro_div').hide();
		$('.cpp_ro_btn').click(function(){
			$(this).next('.cpp_ro_div').slideToggle();
		});
	});
</script>
<div class="col_6 client_main">
	<div class="col_8 alpha omega">
		<ul>
			<li class="active stage_btn" id="stage_btn_1"><h3>Workout Session Input</h3></li>
			<li class="stage_btn" id="stage_btn_2"><h3>Program Chart</h3></li>
		<ul>
	</div>
	<div class="alpha omega stage" id="stage_1">
		<div style="padding:0.5em;margin-right:0.5em;margin-left:0.5em;">
			<?php echo $client_input_success;?>
			<?php $client->display_program_input($c_id);?>
		</div>
		
	</div>
	<div class="alpha omega stage" id="stage_2">
		<h2>main stage 2</h2>
	</div>
	
</div>	
<?php
		
	
	}
	// CLIENT MESSAGES PAGE
	if(isset($_GET['c']) && ($_GET['c'] == 'messages')){
	
		echo 'message';
	
	}
	// CLIENT DETAILS PAGE
	if(isset($_GET['c']) && ($_GET['c'] == 'details')){
		//tabs return if statements
		if(isset($_GET['return']) && ($_GET['c'] == 'details') && ($_GET['return'] == 'pwd')){
			echo "	<script>
					$(document).ready(function() {
						$('#stage_1').hide();
						$('.stage_btn').removeClass('active');
						$('#stage_btn_2').addClass('active');
						$('#stage_2').show();
					});	
					</script>";
		}
	
	?>
		<div class="col_6 client_main">
			<div class="col_8 alpha omega">
				<ul>
					<li class="active stage_btn" id="stage_btn_1"><h3>Details</h3></li>
					<li class="stage_btn" id="stage_btn_2"><h3>Password</h3></li>
				<ul>
			</div>
			<div class="alpha omega stage" id="stage_1">
				<div style="padding:0px;" class="col_8">
					<div class="client_update" style="padding:0.5em;">
					<h3>Personal Details</h3>
				
					<?php
					echo $client_update_success;
					echo $client->display_pd_form($c_id);
					?>
					</div>
				</div>
			</div>
			<div class="alpha omega stage" id="stage_2">
				<div style="padding:0px;" class="col_8">
					<div class="client_update" style="padding:0.5em;">
					<h3>Passwords</h3>
					<?php
					echo $client_pwd_success;
					?>
					<button type="button" class="pwd_chng">Change Password</button>
						<div class="pwd_chng_div">
							<form method="post" action="index.php?p=client&c=details&return=pwd">
								<label for="curr_pwd">Enter Current Password</label>
								<input type="password" name="curr_pwd"/></br>
								<hr>
								<label for="new_pwd">New Password</label>
								<input type="password" name="new_pwd"/></br>
								
								<label for="new_pwd2">Re-type New Password</label>
								<input type="password" name="new_pwd2"/></br>
								
								<input type="submit" name="pwd_chng" value="Update"/>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>
		
		
	<?php
	}
}
?>