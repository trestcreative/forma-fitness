<?php
# home.php
/* 
 *	This is the home page.
 
 @ Allow visiters to view all aspects of what the site offers
 @ People can find the material they're looking for easily 
 */
 ?>
<script>
$(document).ready(function(){
	$("#slideshow > div").hide();
	$('#slideshow > div.active').show();
	
	var i = 0;
	setInterval(function() { 
	i = i + 1;
	var max = $('#slideshow > div').size();
	if(i == max){
		$('#slideshow > div.active').hide().removeClass('active');
		$('#slideshow > div:first').addClass('active').fadeIn(1000);
		$('#slideshow span div div.active').removeClass('active');
		$('#slideshow span div div:first').addClass('active');
		i = 0
	}
	else{
		$('#slideshow > div.active').hide().removeClass('active').next('div').addClass('active').fadeIn(1000);
		$('#slideshow span div div.active').removeClass('active').next('div').addClass('active');
	}
	},  7500); 

	
	/*++++++++++
	POSITIONING 
	+++++++++++*/
	var slideshow_side_divs = $('#slideshow span div .tab');
	var slideshow_div = $('#slideshow');
	var otm_div = $('.otm div');
	var otm_div_h3 = $('.otm div h3');
	
	$(window).load(function(){
		slideshow_side_divs.height(((slideshow_div.height() / 100) * 80) / 3);
		var otm_div_new = ((slideshow_div.height() / 3) - 10);
		otm_div.height(otm_div_new);
		otm_div_h3.css("margin-top", (otm_div_new - otm_div_h3.height()) / 2); 
	});
	$(window).resize(function(){
		slideshow_side_divs.height(((slideshow_div.height() / 100) * 80) / 3);
		var otm_div_new = ((slideshow_div.height() / 3) - 10);
		otm_div.height(otm_div_new);
		otm_div_h3.css("margin-top", (otm_div_new - otm_div_h3.height()) / 2); 
	});
}); 
</script>
 
<!-- 1/4 Block promotion link boxes --> 
<div class="quick_link_boxes">
	<a href="">
	<div class="col_2">
		<img src="images/4Box - Bike.png"/>
		<h1>Quality Workouts</h1>
	</div>
	</a>
	<a href="">
	<div class="col_2">
		<img src="images/box4c.jpg"/>
		<h1>Nutritional Information</h1>
	</div>
	</a>
	<a href="index.php?p=articles">
	<div class="col_2">
		<img src="images/box4.jpg"/>
		<h1>Fitness</br> Articles</h1>
	</div>
	</a>
	<a href="">
	<div class="col_2">
		<img src="images/box4b.jpg"/>
		<h1>Latest Promotions</h1>
	</div>
	</a>
</div>
<!-- 1/4 Block promotion link boxes END --> 
<!-- SLIDESHOW BOX --> 
<div class="clear" style="height:10px;"></div>
<div class="col_6" id="slideshow">
	<div class="active">
		<img src="images/Dumbells.png"/>
	</div>
	<div class="">
		<img src="images/Xab.png"/>
	</div>
	<div class="">
		<img src="images/Flies.png"/>
	</div>
	
	<span>
		<div class="col_3 alpha">
			<div class="col_6 alpha tab active">
				<h2>Full range of workouts</h2>
			</div>
			<div class="col_6 alpha tab">
				<h2>Tailored workout programs</h2>
			</div>
			<div class="col_6 alpha tab">
				<h2>Promotions and deals</h2>
			</div>
			
		</div>
	</span>
</div>
<div class="col_2 otm">
	<div>
		<h3>Challenge of the month</h3>
	</div>
	<div>
		<h3>Recipe of the month</h3>
	
	</div>
	<div>
		<h3>Client of the month</h3>
	
	</div>
</div>