/*------------------------

	MISC JQUERY SCRIPTS
	
------------------------*/

//Login_strip Jqury
$(document).ready(function() {
	$('.login_strip').hide();
	
	$('.login_strip_btn').click(function(){
		$('.login_strip').slideToggle();
	
	});
});

