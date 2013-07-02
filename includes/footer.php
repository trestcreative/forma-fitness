	</div>
	<footer class="container">
		<div class="col_4">
			<h1>Bio</h1>
			<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
		</div>
		<div class="col_4">
			<h1>Contact</h1>
			<h2><img src="images/icons/telephone.png"/>07833341131</h2>
			<h2><img src="images/icons/email.png"/>info@formafitnessgb.com</h2>
			<h2 style="display:inline;"><img src="images/icons/plane.png"/></h2>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<label style="width:15%;display:inline-block;" for="contact_name">Name:</label>
				<input style="width:40%;" type="text" name="contact_name"/></br>
				<label style="width:15%;margin-left:65px;display:inline-block;" for="contact_msg">Message:</label>
				<textarea style="height:75px; width:70%;" type="text" name="contact_msg"></textarea></br>
				<input style="width:41%;margin-left:26%;" type="submit" value="Send" name="contact_submit"/>
			</form>
		</div>
		
	
	</footer>
	
<!-- Wrapper close --></div>
<div style="width:92%; margin-left:4%;display:block;">
<div class="col_8  omega">
			<p style="display:inline-block;">Copyright © 2013 Forma Fitness GB. All rights reserved. </p>

			<p style="display:inline-block;float:right;">Website by <a href="http://www.trestcreative.com/"><img style="width:75px;" src="images/icons/trest.png"/></a></p>
</div>
</div>

</body>
</html>