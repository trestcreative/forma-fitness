<?php
/*---------------------------
|							|
|							|
|	  	TRAINING CLASS		|
|							|
|							|
----------------------------*/

/*
CLASS JOBS
	@ Order and echo each exercise by category  
	@ Order and echo each routine by routine type
	@ Order and echo each routine by routine target
	@ Return Challenge (routine) of the month
*/

class training{
	
	public function __construct($conn)
	    {
	        $this->conn = $conn;
	         return true;
	} 
		
	public function exercise_cats(){
		$cat = '';
		foreach ($this->conn->query("SELECT e_cat_title FROM exercise_cats ORDER BY e_cat_title ASC") as $row){
			//check if any exercises are within this cat
			$query = $this->conn->prepare("SELECT e_title FROM exercise WHERE e_cat_title = '".$row['e_cat_title']."'");
			$query->execute();
			$count = $query->rowCount();
			if($count == 0){
			}
			else{
				$cat .= $row['e_cat_title'].',';
			}
		}
		$cat = rtrim($cat,',');
		$cat = explode(',', $cat);
		$cat = array_unique($cat);
		return $cat;
	}
	
	public function exercises($cat){
		$ex = '';
		foreach ($this->conn->query("SELECT e_title FROM exercise WHERE e_cat_title = '".$cat."'ORDER BY e_title ASC") as $row){
			$ex .= $row['e_title'].',';
		}
		$ex = rtrim($ex,',');
		$ex = explode(',', $ex);
		$ex = array_unique($ex);
		return $ex;
	}
	
	public function training_breadcrumb($get){
		$bread = '<a href="index.php">Home</a> > ';
		if(isset($get['p'])){
			$bread .= '<a href="index.php?p='.$get['p'].'">'.upper_case_word($get['p']).'</a> > ';
		}
		
		if(isset($get['level'])){
			$bread .= '<a href="index.php?p='.$get['p'].'&level='.$get['level'].'">'.$get['level'].'</a> > ';				
		}
		if(isset($get['cat'])){
			$bread .= '<a href="index.php?p='.$get['p'].'">'.$get['cat'].'</a> > ';				
		}
		if(isset($get['focus'])){
			$bread .= '<a href="index.php?p='.$get['p'].'&focus='.$get['focus'].'">'.$get['focus'].'</a> > ';				
		}
		if(isset($get['type'])){
			$get['type'] = comma_to_amp($get['type']);
			$bread .= '<a href="index.php?p='.$get['p'].'&type='.$get['type'].'">'.$get['type'].'</a> > ';				
		}
		
		
		
		if(isset($get['filter_result'])){
			$bread .= '<a href="'.current_page().'">'.$get['filter_result'].'</a>';
		}
		
		echo $bread;
	}
	
	public function find_routine_type($id){
		$return = '';
		foreach ($this->conn->query("SELECT rt_title FROM routine_type_link INNER JOIN routine_type ON routine_type_link.rt_id = routine_type.rt_id
		WHERE routine_type_link.ro_id = '".$id."'") as $row){	
			$return .= $row['rt_title'].',';
		}
	
	$return = trim($return, ",");
	return $return;	
	}
	
	public function routine_otm(){
		foreach ($this->conn->query("SELECT routine.ro_title, routine.ro_id, exercise_steps.e_step_img FROM
		routine INNER JOIN routine_exercise ON routine.ro_id = routine_exercise.ro_id INNER JOIN exercise ON routine_exercise.e_id = 
		exercise.e_id INNER JOIN exercise_steps ON exercise.e_id = exercise_steps.e_id WHERE routine.ro_of_month = '1' LIMIT 1") as $row){	
			 //Find routine types
			$types = $this->find_routine_type($row['ro_id']);
			if(empty($image)){
				$row['e_step_img'] = 'formalogo.png';
			}
			
			echo '	<a href="index.php?p=training&type='.$types.'&filter_result='.$row['ro_title'].'&filter_result_id='.$row['ro_id'].'">
						<div class="otm">
							<img src="./admin/database_images/'.$row['e_step_img'].'"/>
							<span><h2>Challenge of the Month</h2>
								<h2>'.$row['ro_title'].'</h2>
							</span>
						</div>
					</a>';
		}
	}
	
	public function display_results($get){
		$query_type = '';
			if(isset($get['type'])){
				$query_type .= 't';
				$type = urldecode($get['type']);
				$type = comma_to_amp($type);
			}
			if(isset($get['focus'])){
				$query_type .= 'f';
				$focus =  urldecode($get['focus']);
			}
			if(isset($get['level'])){
				$query_type .= 'l';
				$level = urldecode($get['level']);
			}
		
		$href = 'index.php?p=training';
		switch ($query_type)
		{
		case 't':
			$href .= '&type='.$type;
			$param = "rt_title = '".$type."'";
		break;
		
		case 'f':
			$href .= '&focus='.$focus;
			$param = "ro_focus = '".$focus."'";
		break;
		
		case 'l':
			$href .= '&level='.$level;
			$param = "ro_level = '".$level."'";
		break;
		
		case 'tf':
			$href .= '&type='.$type.'&focus='.$focus;
			$param = "rt_title = '".$type."' AND ro_focus = '".$focus."'";
		break;
		
		case 'tl':
			$href .= '&type='.$type.'&level='.$level;
			$param = "rt_title = '".$type."' AND ro_level = '".$level."'";
		break;
		
		case 'ft':
			$href .= '&type='.$type.'&focus='.$focus;
			$param = "rt_title = '".$type."' AND ro_focus = '".$focus."'";
		break;

		case 'fl':
			$href .= '&level='.$level.'&focus='.$focus;
			$param = "ro_level = '".$level."' AND ro_focus = '".$focus."'";
		break;
		
		case 'lt':
			$href .= '&type='.$type.'&level='.$level;
			$param = "rt_title = '".$type."' AND ro_level = '".$level."'";
		break;
		
		case 'lf':
			$href .= '&level='.$level.'&focus='.$focus;
			$param = "ro_level = '".$level."' AND ro_focus = '".$focus."'";
		break;
		
		case 'tfl':
			$href .= '&type='.$type.'&level='.$level.'&focus='.$focus;
			$param = "rt_title = '".$type."' AND ro_level = '".$level."' AND ro_focus = '".$focus."'";
		break; 
		
		case 'tlf':
			$href .= '&type='.$type.'&level='.$level.'&focus='.$focus;
			$param = "rt_title = '".$type."' AND ro_level = '".$level."' AND ro_focus = '".$focus."'";
		break; 
		
		case 'ltf':
			$href .= '&type='.$type.'&level='.$level.'&focus='.$focus;
			$param = "rt_title = '".$type."' AND ro_level = '".$level."' AND ro_focus = '".$focus."'";
		break; 
		
		case 'lft':
			$href .= '&type='.$type.'&level='.$level.'&focus='.$focus;
			$param = "rt_title = '".$type."' AND ro_level = '".$level."' AND ro_focus = '".$focus."'";
		break; 
		
		case 'flt':
			$href .= '&type='.$type.'&level='.$level.'&focus='.$focus;
			$param = "rt_title = '".$type."' AND ro_level = '".$level."' AND ro_focus = '".$focus."'";
		break; 
		
		case 'ftl':
			$href .= '&type='.$type.'&level='.$level.'&focus='.$focus;
			$param = "rt_title = '".$type."' AND ro_level = '".$level."' AND ro_focus = '".$focus."'";
		break; 
		
		default:
		  return false;
		}
		
		$query = $this->conn->prepare("SELECT routine.ro_id, routine.ro_title, routine_type.rt_title FROM routine LEFT JOIN routine_type_link 
		ON routine.ro_id = routine_type_link.ro_id RIGHT JOIN routine_type ON routine_type_link.rt_id = routine_type.rt_id 
		WHERE ".$param." ORDER BY routine.ro_date DESC");
		$query->execute();
		$count = $query->rowCount();
		if($count == 0){
			echo '<em>No results</br>Unfortunately, there are no routines based on your refinements</em>';
		}
		else{
			foreach ($query as $row){
				//Get image from steps
				foreach ($this->conn->query("SELECT exercise_steps.e_step_img FROM routine INNER JOIN routine_exercise ON routine.ro_id = routine_exercise.ro_id 
				INNER JOIN exercise ON routine_exercise.e_id = exercise.e_id INNER JOIN
				exercise_steps ON exercise.e_id = exercise_steps.e_id WHERE routine.ro_id = '".$row['ro_id']."' LIMIT 1") as $img){
					$image = $img['e_step_img']; 
				}
				//Echo final html
				//If no image
				if((empty($image)) || ($image == 'NULL')){
				echo '	<a href="'.$href.'&filter_result='.$row['ro_title'].'&filter_result_id='.$row['ro_id'].'">
						<div class="search_list_item">
							<div class="col_8 alpha omega">
								<h2>'.$row['ro_title'].'</h2>
								<em>'.$row['ro_type'].'</em>
							</div>
						</div>
						</a>';	
				}
				//If theres an image
				else{
					echo '	<a href="'.$href.'&filter_result='.$row['ro_title'].'&filter_result_id='.$row['ro_id'].'">
								<div class="search_list_item">
									<div class="col_3 alpha">
										<img src="./admin/database_images/'.$image.'"/>
									</div>
									<div class="col_5 omega">
										<h2>'.$row['ro_title'].'</h2>
										<em>'.$row['rt_title'].'</em>
									</div>
								</div>
							</a>';
				}
			}
		}	
	}
	
	public function display_exercise($title){
		$query = $this->conn->prepare("SELECT * FROM exercise WHERE e_title = '".$title."'");
		$query->execute();
		
		while ($row = $query->fetch(PDO::FETCH_ASSOC)){
			$title = $row['e_title'];
			$cat = $row['e_cat_title'];
			$id = $row['e_id'];
			$desc = $row['e_desc'];
			// Get steps
			$steps = '';
				foreach($this->conn->query("SELECT * FROM exercise_steps WHERE e_id = '".$id."'") as $step){
					$main_img = $step['e_step_img'];
					if(empty($step['e_step_img'])){
						$steps .= ' <div class="col_8 alpha omega step">
										<div style="margin-left:25%;" class="col_half1 alpha">
											<h1>'.$step['e_step_num'].':</h1>
										</div>
										<div class="col_5 omega">
											<p>'.$step['e_step_desc'].'</p>
										</div>
									</div><div class="clear"></div>';
					}
					else{
						$steps .= '<div class="col_8 alpha omega step">
										
										<div class="col_2 alpha">
											<img src="./admin/database_images/'.$step['e_step_img'].'"/>
										</div>
										<div class="col_half1">
											<h1>'.$step['e_step_num'].':</h1>
										</div>
										<div class="col_5 omega">
											<p>'.$step['e_step_desc'].'</p>											
										</div>
									</div><div class="clear"></div>';
					}
				}
			
			$return = '	
			<div>
				<div class="col_3 alpha">
					<img src="./admin/database_images/'.$main_img.'"/>
				</div>
					<h1>'.$title.'</h1>	
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
					
					<em style="color:#448ccb;">'.$cat.' Exercise</em>
					<hr style="margin-top:10px;">
					<em>'.$desc.'</em>
			</div><div class="clear" style="margin-bottom: 10px;"></div>			
				'.$steps.'
					<hr style="margin-top:0px;margin-bottom:5px;">
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
		echo $return;
		}
	}
	
	public function display_routine_table($id){
		$query = $this->conn->prepare("SELECT * FROM routine WHERE ro_id = '".$id."'");
		$query->execute();
		
		while ($row = $query->fetch(PDO::FETCH_ASSOC)){
			$warm_up = $row['ro_warm'];
			$title = $row['ro_title'];
			
			
			$routine_table = '	<div class="routine_table col_8 alpha omega">
									<div class="col_4">
										<h2>'.$title.'</h2>
									</div><div class="clear"></div>
									<div style="border-bottom:1px solid black;" class="col_4 warm">
										<h3>Warm-up Exercises</h3>
									</div>
									<div style="border-right:1px solid black;border-bottom:1px solid black;" class="col_4 warm">
										'.$warm_up.'
									</div><div class="clear"></div>
									
								';
			}
			foreach($this->conn->query("SELECT routine_type.rt_title FROM routine_type_link INNER JOIN routine_type ON 
			routine_type_link.rt_id = routine_type.rt_id WHERE routine_type_link.ro_id = '".$id."'")as $row){
				$specific_type = $row['rt_title'];
				$routine_table .= '	<div class="exers">
									<div class="col_4 head">
										<h2>'.$specific_type.' Exercises</h2>
									</div><div class="clear"></div>
									<div class="row">
										<div class="col_4 head">
											<h3>Exercises</h3>
										</div>
										<div class="col_2 head">
											<h3>Sets</h3>
										</div>
										<div class="col_2 head">
											<h3>Reps / Mins / Metres</h3>
										</div>
									</div>
										
									
									<div class="clear"></div>
									';
									
				foreach($this->conn->query("SELECT * FROM routine_exercise INNER JOIN exercise ON routine_exercise.e_id = exercise.e_id
						WHERE routine_exercise.ro_type_title = '".$specific_type."' AND routine_exercise.ro_id = '".$id."'") as $exercise){
						
						$ex_sets = $exercise['sets'];
						$ex_reps = $exercise['reps'];
						$ex_reps_type = $exercise['reps_type'];
						$ex_burn = $exercise['burn'];
						$ex_title = $exercise['e_title'];
						if($ex_burn == 1){
							$burn = 'burn';
						}
						else{
							$burn = '';
						}
					$routine_table .= '	<div class="row '.$burn.'">
											<div class="col_4">
												<p>'.$ex_title.'</p>
											</div>
											<div class="col_2">
												<p style="text-align:center;">'.$ex_sets.'</p>
											</div>
											<div class="col_2">
												<p style="text-align:center;">'.$ex_reps.' ('.$ex_reps_type.')</p>
											</div><div class="clear"></div>
										</div>
								';	
					}
				
				$routine_table .= '</div>';
			}
						
				
			$routine_table .= '<em>KEY</em><div style="background-color:#448ccb;width:15%;"><em>Warm-up</em></div><div style="background-color:#FF6666;width:15%;"><em>Burnout exercise</em></div>
			</div>';
		
		return $routine_table;
	}
	
	public function display_routine($id){
		$query = $this->conn->prepare("SELECT * FROM routine WHERE ro_id = '".$id."'");
		$query->execute();
		
		while ($row = $query->fetch(PDO::FETCH_ASSOC)){
			$title = $row['ro_title'];
			$type = comma_to_amp($this->find_routine_type($id));
			$desc = $row['ro_desc'];
			$focus = $row['ro_focus'];
			$level = $row['ro_level'];
			$otm = $row['ro_of_month'];
			// Get steps
			$exercises = '';
				foreach($this->conn->query("SELECT * FROM routine_exercise INNER JOIN exercise ON routine_exercise.e_id = exercise.e_id
				WHERE routine_exercise.ro_id = '".$id."'") as $exercise){
					$ex_desc = $exercise['ro_exer_desc'];
					$ex_sets = $exercise['sets'];
					$ex_reps = $exercise['reps'];
					$ex_reps_type = $exercise['reps_type'];
					$ex_burn = $exercise['burn'];
					$ex_id = $exercise['e_id'];
					$ex_title = $exercise['e_title'];
					$ex_cat = $exercise['e_cat_title'];
					
					$display_imgs = '';
					//Gather images for each routine
					foreach($this->conn->query("SELECT e_step_img FROM exercise_steps WHERE e_id = '".$ex_id."'") as $e_img){
						$ex_img = $e_img['e_step_img'];
						$display_imgs .= $e_img['e_step_img'];
					}
					
					$exercises .= '	<a class="no_print" href="index.php?p=training&cat='.$ex_cat.'&filter_result='.$ex_title.'&filter_result_id='.$ex_id.'">
									<div class="col_8 alpha omega step print">
										<div class="col_2 alpha">
											<img src="./admin/database_images/'.$ex_img.'"/>
										</div>
										<div class="col_4 omega">
											<h2 style="display:inline;margin-right:10px;">'.$ex_title.'</h2>
											<p>'.$ex_desc.'</p>											
										</div>
										<div class="col_2">
											<div class="sets_reps_box">
												<em>Sets</em>
												<h2 style="text-align:center;">'.$ex_sets.'</h2>
											</div>
											<div class="sets_reps_box">
												<em>'.$ex_reps_type.'</em>
												<h2 style="text-align:center;">'.$ex_reps.'</h2>
											</div>
										</div>
									</div>
									</a><div class="clear"></div>';
				}
				
			$return = '<div>
							<div class="col_3 alpha">
								<img src="./admin/database_images/'.$ex_img.'"/>
							</div>
								<h1>'.$title.'</h1>	
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
						
								
								<em style="color:#448ccb;"><i style="color:#222;">Type: </i>'.$type.' <i style="color:#222;">Level: </i>'.$level.' <i style="color:#222;">Split:</i> '.$focus.' </em>
								<hr style="margin-top:10px;">
								<em>'.$desc.'</em>
						</div><div class="clear" style="margin-bottom: 10px;"></div>	
						'.$exercises.'
						<hr style="margin-top:0px;margin-bottom:5px;">
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
						'.$this->display_routine_table($id).'
						';
			}
		echo $return;	
	}	
	
	/* public function display_routine_table($id){
		$query = $this->conn->prepare("SELECT * FROM routine WHERE ro_id = '".$id."'");
		$query->execute();
		
		while ($row = $query->fetch(PDO::FETCH_ASSOC)){
			$title = $row['ro_title'];
			$type = $row['ro_type'];
			$desc = $row['ro_desc'];
			$focus = $row['ro_focus'];
			$level = $row['ro_level'];
			$otm = $row['ro_of_month'];
			// Get steps
			$exercises = '';
				foreach($this->conn->query("SELECT * FROM routine_exercise INNER JOIN exercise ON routine_exercise.e_id = exercise.e_id
				WHERE routine_exercise.ro_id = '".$id."'") as $exercise){
					$ex_desc = $exercise['ro_exer_desc'];
					$ex_sets = $exercise['sets'];
					$ex_reps = $exercise['reps'];
					$ex_id = $exercise['e_id'];
					$ex_title = $exercise['e_title'];
					$ex_cat = $exercise['e_cat_title'];
					
					echo '	<table>
								<th>
									<td>Exercise</td>
									<td>Date</td>
									<td>Date</td>
									<td>Date</td>
						
								</th>
							</table>
					
					
					
					
					';

				}
			}
	} */	
}


?>