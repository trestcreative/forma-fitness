<?php
# articles.php
/* 
 *	This is the articles page.
 
 @ Display archive articles by month and year
 @ Display large article of the month
 @ Breadcrumb system
 @ Call 6 topics (LIMIT 6)
 */
 ?>
<!-- Jquery Scripts for article.php page -->
<script>
	$(document).ready(function() {
		var cur_year = '<?php echo date('Y');?>';
		$('aside > ul > li').each(function( index ){
			each_year = parseInt($(this).text());
			if(each_year == cur_year){
				$(this).next('ul').show();
			}
			else{
				$(this).next('ul').hide();
			}
		
		});
		$('aside ul li').click(function(){
			$(this).next('ul').slideToggle();
		});
		
		
			$('.otm img').load(function () {
				var otm_img_height = $('.otm img').height();
				$('.otm').height(otm_img_height);
			});	
			
			$(window).load(function () {
				var section_divs = (300 / 2);
				$('section div').height(section_divs);
				$('section div').each(function(){
					var section_imgs = $(this).find('img').height();
					var marg = ((section_divs - section_imgs) / 2);
					$(this).find('img').css("margin-top", marg);
					var bottom = (section_imgs - section_divs);
					bottom = (bottom + marg) + 30;
					$(this).find('span').css("bottom", bottom); 
					var rise = parseInt($(this).find('span').attr('style').split(':')[1]);
					rise = rise + 30;
					$(this).hover(
						  function () {
							$(this).find('span').css("bottom", rise);
							
						  },
						  function () {
							$(this).find('span').css("bottom", bottom);
						  }
						);
				});
			});	
				
			
	});
</script> 
 

 
<div class="col_8 bread">
	<!-- Bread Crumb -->
<?php
	$article->article_breadcrumb($_GET);
?>
</div><div class="clear"></div>
<div class="col_8">
	<!-- Page Title -->
	<h1 class="no_print">Articles</h1>
<hr>
</div><div class="clear"></div>
<aside class="col_2">
	<!-- Achieve accordion -->
	<h2>Archive</h2>
	<ul>
<?php
		foreach ($article->articles_years() as $year){
	echo '<li>'.$year.'</li>';
	echo '<ul>';
		foreach($article->articles_months($year) as $month){
			echo '<li><a href="index.php?p=articles&filter=month&year='.$year.'&filter_desc='.$month.'">'.$month.'</a></li>';
		}
	echo "</ul>";
	}
?>
	</ul>
</aside>
<?php
//If filter is set
if((isset($_GET['filter'])) && (!isset($_GET['filter_result']))){
?>
<div class="col_3 search_list">
	<!-- Filter search list-->
	<h2><?php echo $_GET['filter_desc'];?></h2><hr style="margin-bottom:0px;">
	<?php
	if($_GET['filter'] == 'month'){
		$year = $_GET['year'];	
	}
	if($_GET['filter'] == 'topic'){
		$year = '';
	}
		 $article->filter_results($_GET['filter'], $_GET['filter_desc'], $year); 
?>	


		
</div>
<section class="col_3">
	<!-- Top Article boxes -->
	<?php
		$article->top_articles();
	?>
	
</section>
<?php
}
?>
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

<?php
//If article is called
if(isset($_GET['filter_result'])){
		echo '<article class="col_6">';
		$id = $_GET['filter_result_id'];
		$article->display_article($id);
?>
<?php		
		echo '</article>';
}


elseif (!isset($_GET['filter'])){
?>
<div class="col_3">
	<!--Of the month-->
	<?php
		$article->a_of_month();
	?>
</div>
<section class="col_3">
	<!-- Article Topic boxes -->
	<?php
		$article->top_topics();
?>
	
</section>
<?php
}
?>