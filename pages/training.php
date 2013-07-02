<?php
# training.php
/* 
 *	This is the Training page.
 
 @ Display Individual exercises by category 
 @ Display symbol links to routine types 
 @ Display diagram links to routine target 
 @ Display large challenge of the month
 @ Breadcrumb system
 */
 ?>
<!-- Jquery Scripts for article.php page -->
<script>
$(document).ready(function() {
	$('aside ul ul').hide();
	$('aside ul li').click(function(){
		$(this).next('ul').slideToggle();
	});
	
	$('.otm img').load(function () {
		var otm_img_height = $('.otm img').height();
		$('.otm').height(otm_img_height);
	});	
	
	
	/* $('.filter_link img').load(function () {
		var filter_link = ($('.filter_link img').height()) + ($('.filter_link h3').height());
		$('.filter_link').height(filter_link);
	}); */
});	
</script>

<!-- TWITTER SCRIPT -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<!-- TWITTER SCRIPT END-->

<!-- FACEBOOK SCRIPT -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- FACEBOOK SCRIPT END-->

<!-- GOOGLE + SCRIPT -->
<script type="text/javascript">
  window.___gcfg = {lang: 'en-GB'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<!-- GOOGLE + SCRIPT END -->
<!-- PINTEREST SCRIPT -->
<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
<!-- PINTEREST SCRIPT END -->
<div class="col_8 bread">
	<!-- Bread Crumb -->
	<?php
		$training->training_breadcrumb($_GET);
	?>
</div><div class="clear"></div>
<div class="col_8">
	<!-- Page Title -->
	<h1 class="no_print">Training</h1>
<hr>
</div><div class="clear"></div>
<aside class="col_2">
	<!-- Achieve accordion -->
	<h2>Exercises</h2>
	<ul>
	<?php
	foreach ($training->exercise_cats() as $cat){
		echo '<li>'.$cat.'</li>';
		echo '<ul>';
			foreach ($training->exercises($cat) as $ex){
				echo '<li><a href="index.php?p=training&cat='.$cat.'&filter_result='.$ex.'">'.$ex.'</a></li>';
			}
		echo '</ul>';
	}	
	
	?>
	</ul>
</aside>
<?php
if ((isset($_GET['type'])) || (isset($_GET['level'])) || (isset($_GET['focus']))){
	$refine = 'Refine Search';
	$clear_refine = '<h2>Clear Search</h2>
						<a href="index.php?p=training" class="col_2 alpha filter_link">
							<img src="images/icons/clear.png"/>
							<h3>Clear</h3>
						</a>
					<div class="clear"></div>
							<hr>';
}
else{
	$refine = 'Routines';
	$clear_refine = '';
}

$by_level = '
	<h2>'.$refine.' by Level</h2>
	<a href="'.current_page().'&level=Beginner" class="col_2 alpha filter_link">
		<img src="images/icons/level1.png"/>
		<h3>Beginner</h3>
	</a>
	<a href="'.current_page().'&level=Intermediate" class="col_2 filter_link">
		<img src="images/icons/level2.png"/>
		<h3>Intermediate</h3>
	</a>
	<a href="'.current_page().'&level=Hard" class="col_2 omega filter_link">
		<img src="images/icons/level3.png"/>
		<h3>Hard</h3>
	</a>
	
	<div class="clear"></div>
	<hr>';

$by_type = '	
	<h2>'.$refine.' by Type</h2>
	<a href="'.current_page().'&type=Bulking" class="col_2 alpha filter_link">
		<img src="images/icons/bulking.png"/>
		<h3>Bulking</h3>
	</a>
	<a href="'.current_page().'&type=Power" class="col_2 filter_link">
		<img src="images/icons/power.png"/>
		<h3>Power</h3>
	</a>
	<a href="'.current_page().'&type=Speed" class="col_2 omega filter_link">
		<img src="images/icons/speed.png"/>
		<h3>Speed</h3>
	</a>
	
	<div class="clear"></div>
	<a href="'.current_page().'&type=Strength" class="col_2 alpha filter_link">
		<img src="images/icons/strength.png"/>
		<h3>Strength</h3>
	</a>
	<a href="'.current_page().'&type=Stamina" class="col_2 filter_link">
		<img src="images/icons/stamina.png"/>
		<h3>Stamina</h3>
	</a>
	<a href="'.current_page().'&type=Cutting" class="col_2 omega filter_link">
		<img src="images/icons/cutting.png"/>
		<h3>Cutting</h3>
	</a>
	<div class="clear"></div>
	<hr>
	';
$by_focus = '
	<h2>'.$refine.' by Body Focus</h2>
	
	<a href="'.current_page().'&focus=Full%20Body" class="col_2 alpha filter_link">
		<img src="images/icons/fullbody.png"/>
		<h3>Full Body</h3>
	</a>
	<a href="'.current_page().'&focus=Lower%20Body" class="col_2 filter_link">
		<img src="images/icons/lowerbody.png"/>
		<h3>Lower Body</h3>
	</a>
	<a href="'.current_page().'&focus=Upper%20Body" class="col_2 omega filter_link">
		<img src="images/icons/upperbody.png"/>
		<h3>Upper Body</h3>
	</a>
	<div class="clear"></div>
	<hr>';

if(isset($_GET['filter_result'])){
	if(isset($_GET['cat'])){
	//Display result by category
		echo '<article class="col_6">';
			$training->display_exercise($_GET['filter_result']);
		echo '</article>';
	}
	else{
	//final training
		echo '<article class="col_6">';
			$training->display_routine($_GET['filter_result_id']);
		echo '</article>';
	}
}

if ((!isset($_GET['type'])) && (!isset($_GET['level'])) && (!isset($_GET['focus'])) && (!isset($_GET['filter_result']))){
?>
<div class="col_3">
	<!--Of the month-->
	<?php
	$training->routine_otm();
	?>
</div>
<section class="col_3">
	<?php
	echo $by_level.$by_type.$by_focus;
	?>
</section>
<?php
}

	
if((isset($_GET['type'])) && (!isset($_GET['filter_result'])) || (isset($_GET['level'])) && (!isset($_GET['filter_result'])) || (isset($_GET['focus'])) && (!isset($_GET['filter_result']))){
//Filter search results
?>
<div class="col_3 search_list">
<?php
	
	$training->display_results($_GET);
?>
</div>
<?php	
//Filter refinery search
	if(isset($_GET['type']) && (isset($_GET['level'])) && (isset($_GET['focus'])) || (isset($_GET['filter_result']))){
		$by_focus = '';
		$by_level = '';
		$by_type = '';
		echo '<section class="col_3">
				<h2>Clear Search</h2>
					<a href="index.php?p=training" class="col_2 alpha filter_link">
						<img src="images/icons/clear.png"/>
						<h3>Clear</h3>
					</a>
					<div class="clear"></div><hr>
				</section>';
		$clear_refine = '';					
	}
	
	if(isset($_GET['type']) && (!isset($_GET['focus'])) && (!isset($_GET['level']))){
		$by_type = '';
		echo '	<section class="col_3">
					'.$by_level.$by_type.$by_focus.$clear_refine.'
				</section>';
	}
	if(isset($_GET['level']) && (!isset($_GET['focus'])) && (!isset($_GET['type']))){
		$by_level = '';
		echo '	<section class="col_3">					
					'.$by_level.$by_type.$by_focus.$clear_refine.'
				</section>';
	}
	if(isset($_GET['focus']) && (!isset($_GET['type'])) && (!isset($_GET['level']))){
		$by_focus = '';
		echo '	<section class="col_3">			
					'.$by_level.$by_type.$by_focus.$clear_refine.'
				</section>';
	}
	if(isset($_GET['type']) && (isset($_GET['level']))){
		$by_type = '';
		$by_level = '';
		echo '	<section class="col_3">
					'.$by_level.$by_type.$by_focus.$clear_refine.'
				</section>';
	}
	if(isset($_GET['type']) && (isset($_GET['focus']))){
		$by_type = '';
		$by_focus = '';
		echo '	<section class="col_3">
					'.$by_level.$by_type.$by_focus.$clear_refine.'
				</section>';
	}
	if(isset($_GET['focus']) && (isset($_GET['level']))){
		$by_focus = '';
		$by_level = '';
		echo '	<section class="col_3">
					'.$by_level.$by_type.$by_focus.$clear_refine.'
				</section>';
	}
	if(isset($_GET['focus']) && (isset($_GET['level'])) && (isset($_GET['type']))){
		
		
	}

}



?>
