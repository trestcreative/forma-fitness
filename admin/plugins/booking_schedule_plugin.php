<?php
/*--------------------------------

		BOOKING_SCHEDULE PHP SECTION
		
---------------------------------*/
class booking_schedule{
	public function __construct($conn){
	        $this->conn = $conn;
	         return true;
	}
	
	public function client_by_id($id){
		foreach($this->conn->query("SELECT c_first_name, c_last_name FROM clients WHERE c_id = '".$id."'") as $row){
			return $row['c_first_name'].' '.$row['c_last_name'];
		}
	}
	
	public function display_blocks($date_passed){
	// @ Find start datetime and booking length
	
	$today_start = $date_passed.' 00:00:00';
	$today_end = $date_passed.' 23:59:59';
	$work_start = $date_passed.' 06:00:00';
	$work_end = $date_passed.' 22:00:00';

	$date = new DateTime($today_start); 
	$count = 24 * 60 / 15;  
	$arr = array(); 
		while($count--) { 
			$arr[] = $date->add(new DateInterval("P0Y0DT0H15M"))->format("H:i"); 
		} 
		$interval = '';
		foreach ($arr as $value){
			$interval .= $date_passed.' '.$value.':00,';
		}
		$gaps = explode(",", $interval);
		$return = '';
		foreach ($gaps as $gap){
		
			if(($gap >= $work_start) && ($gap < $work_end)){
				$query = $this->conn->prepare("SELECT cb_datetime, cb_session_type, c_id, cb_session_length FROM client_bookings WHERE cb_datetime <= '".$gap."' AND cb_datetime_end > '".$gap."' AND cb_datetime > '".$today_start."' AND cb_datetime_end < '".$today_end."' ");

				$query->execute();
				$return_count = $query->rowCount();
				$session_type = '';
				foreach($query as $row){
					$session_type = $row['cb_session_type'];
					$block_start = $row['cb_datetime'];
					$tooltip_date = strtotime($row['cb_datetime']);
					$tooltip_date = date("D jS g:i A", $tooltip_date);
					$tooltip_name = $this->client_by_id($row['c_id']);
					if($session_type == 'Class'){
						$tooltip = $tooltip_date.' - Class';
						$style = 'style="background-color:#006699;"';
					}
					else{						
						$tooltip = $tooltip_date.' - '.$tooltip_name;
						$style = '';
					}
				}
				if(($return_count == 1) || ($session_type == 'Class')){
					if($gap == $block_start){  
						$return .= '<div '.$style.' title="'.$tooltip.'" class="s_block1"></div>';
					}
					else{
						$return .= '<div '.$style.' title="'.$tooltip.'" class="s_block"></div>';
					}
				}
				else{
					$return .= '<div class="no_block">
									
								</div>';
				}
			}
			else{
			}
		}
		return $return;
	}
}	
//instance object
$schedule = new booking_schedule($conn);

//Set to decide what jquery is being used and shown	
$date_j = false;
$date_div = '';
if(isset($_GET['action'])){
	if($_GET['action'] == 'schedule'){
		$date_j = true;
		$date_set = strtotime($_GET['id']);
		
		$date_div = '<h2>By Date</h2></br>
						'.date("D jS F Y", $date_set).'</br>
					<div class="schedule_bar">
						<img style="display:block;" src="./core/css/images/schedule_head.png"/>
						'.$schedule->display_blocks(date("Y-m-d", $date_set)).'
					</div>
					';
	}
}
//tomorrows date
$tomorrow = date("Y-m-d").' 00:00:00';
$tomorrow = date('Y-m-d', strtotime($tomorrow . ' + 1 day'));

$schedule_html = '	<button type="button" class="sch_today s-button">Today</button>
					<button type="button" class="sch_tmrw s-button">Tomorrow</button>
					<label style="width:6%;">By date:</label>
					<p style="display:inline;"><input type="text" class="s_date datepicker" /></p>
					<div class="sch_today_div">
						<h2>Today</h2></br>
						'.date("D jS F Y").'</br>
						<div class="schedule_bar">
						<img style="display:block;" src="./core/css/images/schedule_head.png"/>
							'.$schedule->display_blocks(date("Y-m-d")).'
						</div>
					</div>
					<div class="sch_tmrw_div">
						<h2>Tomorrow</h2></br>
						'.date("D jS F Y", strtotime($tomorrow)).'</br>
						<div class="schedule_bar">
							<img style="display:block;" src="./core/css/images/schedule_head.png"/>
							'.$schedule->display_blocks($tomorrow).'
						</div>
					</div>
					<div class="sch_date_div">';
$schedule_html .= $date_div;					
$schedule_html .= '</div>';

