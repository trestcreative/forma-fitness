<?php
/*--------------------------------

		TOPICS PHP SECTION
		
---------------------------------*/
class topic{
	 
	public function __construct($conn)
    {
        $this->conn = $conn;
         return true;
    } 
	public function insert_topic($t_title){
		$sql = "INSERT INTO topics (t_title) VALUES (:t_title)";
		$query = $this->conn->prepare($sql);
		$query->bindParam(":t_title", $t_title);
		$query->execute();
	}
	
	public function topic_check($t_title){
		$query = $this->conn->prepare("SELECT * FROM topics WHERE t_title =
		:topic LIMIT 1");
		$query->bindParam(":topic", $t_title);
		$query->execute();
		$count = $query->rowCount();
		$pass = $query->fetch( PDO::FETCH_ASSOC );
		
		if ($count == 1){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function display_topics(){
		foreach ($this->conn->query("SELECT * FROM topics ORDER BY t_date DESC") as $row){
		echo '<div class="item_row">
				<div class="desc">
					<h2>'.$row['t_title'].'</h2>
				</div>
				<div class="controls">
					<button type="button" class="x-button t_remove">Delete</button>
					<a href="manage.php?action=edit&type=topic&id='.$row['t_id'].'"><button class="s-button" type="button">Edit</button></a>
					<div class="t_remove_div">
						<h3>Are you sure you want to delete this item?</h3></br>
						<button type="button" class="s-button t_remove_cnl">No</button>
						<a href="manage.php?action=delete&type=topic&id='.$row['t_id'].'"><button class="x-button" type="button">Yes</button></a>
					</div>
				</div>';
			
		echo '</div>';
		}
	}
	
	public function update_topic($topic, $id){
		$query = $this->conn->prepare("UPDATE topics SET t_title = :t_title WHERE
			t_id = :id");
			$query->bindParam(":t_title", $topic);
			$query->bindParam(":id", $id);
		$query->execute();
	}

}

//Success variable
$t_success = '';
//Error variables
$topic_title_error = '';

//Instance objects
$new_topic = new topic($conn);

if (isset ($_POST['t_submit'])){
	//Check to see if topic is entered
	if (empty($_POST['t_title'])){
		$topic_title_error = '<em class="error">A Topic Title is required!</em>';
	}	
	else{
		$topic_check = $new_topic->topic_check($_POST['t_title']);
		if ($topic_check == true){
			$topic_title_error = '<em class="error">This Topic has already been made!</em>';
		}
		else{
			if(isset($_POST['t_edit'])){
				$topic_id = $_POST['t_edit'];
					$new_topic->update_topic($_POST['t_title'], $topic_id);
					$t_success = '<h2 class="success">Topic Updated!</h2>';
			}
			else{
				$conn->beginTransaction();
					$new_topic->insert_topic($_POST['t_title']);
				$conn->commit();
				$t_success = '<h2 class="success">Topic Created!</h2>';
			}	
		}
	}
}
/*----------END OF TOPICS PHP SECTION----------*/


/*--------------------------------

		TOPICS HTML SECTION
		
---------------------------------*/

$t_html = $t_success;
$t_html .= <<<EOD
		<form action="index.php" method="post">
			<label for="t_title">Topic Title:</label>
			<input name="t_title" placeholder="Topic" type="text" />
EOD;
$t_html .= $topic_title_error;
$t_html .= <<<EOD
<br>
			<input class="s-button" type="submit" value="Submit" name="t_submit" />
		</form>
EOD;



/*----------END OF TOPICS HTML SECTION----------*/
?>