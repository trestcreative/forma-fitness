<?php
/*---------------------------
|							|
|							|
|	  	ARTICLES CLASS		|
|							|
|							|
----------------------------*/

/*
CLASS JOBS
	@ Order and echo each year 
	@ Order and echo each months articles
	@ Return article of the month image, id, title and author
	@ Pick the latest 6 topics by date and return their title and an image from an article within that topic
*/

class articles{
	public function __construct($conn)
	{
	        $this->conn = $conn;
	         return true;
	}

	public function articles_years(){
		$date = '';
		foreach ($this->conn->query("SELECT a_date FROM articles ORDER BY a_date ASC") as $row){
			$date .= date_to_year($row['a_date']) . ',';
		}
		$year = rtrim($date,',');
		$year = explode(',', $year);
		$year = array_unique($year);
		return $year;
	}
	
	public function articles_months($year){
		$date = '';
		foreach ($this->conn->query("SELECT a_date FROM articles WHERE a_date BETWEEN '".$year."-01-01 00:00:01' AND '".$year."-12-31 23:59:59' ORDER BY a_date ASC") as $row){
			$date .= date_to_month($row['a_date']) . ',';
		}
		$month = rtrim($date,',');
		$month = explode(',', $month);
		$month = array_unique($month);
		return $month;
	}
	
	public function a_of_month(){
		foreach ($this->conn->query("SELECT * FROM articles WHERE a_of_month = '1'") as $row){
			$month = date_to_month($row['a_date']);
			$year = date_to_year($row['a_date']);
			echo '	<a href="index.php?p=articles&filter=month&year='.$year.'&filter_desc='.$month.'&filter_result='.$row['a_title'].'&filter_result_id='.$row['a_id'].'">
						<div class="otm">
							<img src="./admin/database_images/'.$row['a_img'].'"/>
							<span><h2>Article of the Month</h2>
								<h2>'.$row['a_title'].'</h2>
							</span>
						</div>
					</a>';
		}	
	}
	
	public function article_breadcrumb($get){
		$bread = '<a href="index.php">Home</a> > ';
		if(isset($get['p'])){
			$bread .= '<a href="index.php?p='.$get['p'].'">'.upper_case_word($get['p']).'</a> > ';
		}
		if(isset($get['filter'])){
			if($get['filter'] == 'month'){
				$bread .= '<a href="index.php?p=articles">'.$get['year'].'</a> > <a href="index.php?p='.$get['p'].'&filter='.$get['filter'].'&year='.$get['year'].'&filter_desc='.$get['filter_desc'].'">'.$get['filter_desc'].'</a> > ';
			}
			if($get['filter'] == 'topic'){
				$bread .= '<a href="index.php?p='.$get['p'].'&filter='.$get['filter'].'&filter_desc='.$get['filter_desc'].'">'.$get['filter_desc'].'</a> > ';
			}
			if(($get['filter'] == 'topic') && (isset($get['filter_result']))){
				$bread .= '<a href="'.current_page().'">'.$get['filter_result'].'</a>';
			}
			if(($get['filter'] == 'month') && (isset($get['filter_result']))){
				$bread .= '<a href="'.current_page().'">'.$get['filter_result'].'</a>';
			}
		}
		echo $bread;
	}
	
	public function display_article($id){
		foreach ($this->conn->query("SELECT * FROM articles WHERE a_id = '".$id."'") as $row){
			$month = date_to_month($row['a_date']);
			$year = date_to_year($row['a_date']);
			echo '	<div class="col_3 alpha">
						<img src="./admin/database_images/'.$row['a_img'].'"/>
					</div>
						<h1>'.$row['a_title'].'</h1>		
		<hr style="margin-bottom:5px;">
		<div class="social">
			<!-- TWITTER BUTTON -->		
			<a href="https://twitter.com/share" class="twitter-share-button" data-text="'.current_page().'Latest Fitness Article by Forma Fitness" data-hashtags="formafitness">Tweet</a>
			<!-- FACEBOOK BUTTON -->		
			<div class="fb-like" data-href="'.current_page().'" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
			<!-- GOOGLE + BUTTON -->		
			<div class="g-plusone" data-size="medium"></div>
			<!-- PINTEREST BUTTON -->
			<a class="pinterst" href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.flickr.com%2Fphotos%2Fkentbrew%2F6851755809%2F&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="beside"><img style="width:auto !important;" src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
			<a class="pinterst" href="#" onClick=" window.print(); return false"><img style="height:18px;width:18px;" src="./images/icons/printer.png"/> Print this page</a>
			<hr style="margin-top:0px;margin-bottom:10px;">
		</div>
		<!-- ARTICLE CONTENT -->
		<em>'.$row['a_blurb'].'</em></br>
		<em style="color:#448ccb;">by '.$row['a_author'].' - '.$month.' / '.$year.'</em>
		<hr style="margin-top:10px;">
		
		<p>'.$row['a_desc'].'</p>
		<!-- ARTICLE CONTENT -->
		
		<hr style="margin-bottom:5px;">
		<div class="social">
			<!-- TWITTER BUTTON -->		
			<a href="https://twitter.com/share" class="twitter-share-button" data-text="'.current_page().'Latest Fitness Article by Forma Fitness" data-hashtags="formafitness">Tweet</a>
			<!-- FACEBOOK BUTTON -->		
			<div class="fb-like" data-href="'.current_page().'" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
			<!-- GOOGLE + BUTTON -->		
			<div class="g-plusone" data-size="medium"></div>
			<!-- PINTEREST BUTTON -->
			<a class="pinterst" href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.flickr.com%2Fphotos%2Fkentbrew%2F6851755809%2F&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="beside"><img style="width:auto !important;" src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
			<a class="pinterst" href="#" onClick=" window.print(); return false"><img style="height:18px;width:18px;" src="./images/icons/printer.png"/> Print this page</a>
			<hr style="margin-top:0px;margin-bottom:10px;">
		</div>
		
					';
		}
	}
	
	public function top_topics(){
		$topic = '';
		$i = 0;
		foreach ($this->conn->query("SELECT articles.t_title, articles.a_img, topics.t_id FROM articles INNER JOIN
		topics ON articles.t_title=topics.t_title ORDER BY articles.a_date DESC") as $row){
			
			if(($row['t_title'] == $topic) || ($i == 6) || (empty($row['a_img']))){
				return false;
			}
			else{
				echo '	<div class="col_4">
							<a href="index.php?p=articles&filter=topic&filter_desc='.$row['t_title'].'&filter_desc_id='.$row['t_id'].'">
								<img src="./admin/database_images/'.$row['a_img'].'"/>
								<span>
									<h3>'.$row['t_title'].'</h3>
									<h3>Click to view</h3>
								</span>
							</a>
						</div>';
				$topic = $row['t_title'];
				$i++;	
			}
		}
	}
	
	public function top_articles(){
		foreach ($this->conn->query("SELECT a_img, a_id, a_title, t_title FROM articles ORDER BY a_date DESC LIMIT 4") as $row){
			echo '	<div class="col_4">
						<a href="index.php?p=articles&filter=topic&filter_desc='.$row['t_title'].'&filter_result='.$row['a_title'].'&filter_result_id='.$row['a_id'].'">
							<img src="./admin/database_images/'.$row['a_img'].'"/>
							<span>
								<h3>'.$row['a_title'].'</h3>
								<h3>Click to view</h3>
							</span>
						</a>
					</div>';
		}
	}

	public function filter_results($filter, $filter_desc, $year){
		switch ($filter)
		{
		case 'month':
			$month = month_to_date($filter_desc);
			$date_from = $year.'-'.$month.'-01 00:00:00';
			$date_to = $year.'-'.$month.'-31 23:59:59';
			foreach ($this->conn->query("SELECT * FROM articles WHERE a_date BETWEEN '".$date_from."' AND '".$date_to."' ORDER BY a_date DESC") as $row){
				$month = date_to_month($row['a_date']);
				$year = date_to_year($row['a_date']);
				
				if(empty($row['a_img'])){
					echo '<a href="index.php?p=articles&filter=month&filter_desc='.$month.'&year='.$year.'&filter_result='.$row['a_title'].'&filter_result_id='.$row['a_id'].'">
							<div class="search_list_item">
								<div class="col_8 alpha omega">
									<h2>'.$row['a_title'].'</h2>
									<p>'.$row['a_blurb'].'</p>
									<em>'.$row['a_author'].' - '.$month.' '.$year.'</em>
								</div>
							</div>
						</a>';
						
				}
				if(!empty($row['a_img'])){
					echo '<a href="index.php?p=articles&filter=month&filter_desc='.$month.'&year='.$year.'&filter_result='.$row['a_title'].'&filter_result_id='.$row['a_id'].'">
							<div class="search_list_item">
								<div class="col_3 alpha">
									<img src="./admin/database_images/'.$row['a_img'].'"/>
								</div>
								<div class="col_5 omega">
									<h2>'.$row['a_title'].'</h2>
									<p>'.$row['a_blurb'].'</p>
									<em>'.$row['a_author'].' - '.$month.' '.$year.'</em>
								</div>
							</div>
						</a>';
				}
			}
		  break;
		case 'topic':
			foreach ($this->conn->query("SELECT * FROM articles WHERE t_title = '".$filter_desc."' ORDER BY a_date DESC") as $row){	
				$month = date_to_month($row['a_date']);
				$year = date_to_year($row['a_date']);
				if(empty($row['a_img'])){
					echo '<a href="index.php?p=articles&filter=topic&filter_desc='.$filter_desc.'&filter_result='.$row['a_title'].'&filter_result_id='.$row['a_id'].'">
							<div class="search_list_item">
								<div class="col_8 alpha omega">
									<h2>'.$row['a_title'].'</h2>
									<p>'.$row['a_blurb'].'</p>
									<em>'.$row['a_author'].' - '.$month.' '.$year.'</em>
								</div>
							</div>
						</a>';	
				}
				if(!empty($row['a_img'])){
					echo '<a href="index.php?p=articles&filter=topic&filter_desc='.$filter_desc.'&filter_result='.$row['a_title'].'&filter_result_id='.$row['a_id'].'">
							<div class="search_list_item">
								<div class="col_3 alpha">
									<img src="./admin/database_images/'.$row['a_img'].'"/>
								</div>
								<div class="col_5 omega">
									<h2>'.$row['a_title'].'</h2>
									<p>'.$row['a_blurb'].'</p>
									<em>'.$row['a_author'].' - '.$month.' '.$year.'</em>
								</div>
							</div>
						</a>';
				}
			}
		  break;
		default:
		  return false;
		}
	}
}



?>