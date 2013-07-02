<?php

class article{
	 
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
	
	public function article_insert($a_title, $a_desc, $a_blurb, $a_img, $a_author, $topic, $a_of_month){
		$article_sql = "INSERT INTO articles (a_title, a_desc, a_blurb, a_img, a_author, t_title, a_of_month) VALUES (:a_title, :a_desc, :a_blurb, :a_img, :a_author, :t_title, :a_of_month)";
		$article_query = $this->conn->prepare($article_sql);
			$article_query->bindParam(":a_title", $a_title);
			$article_query->bindParam(":a_desc", $a_desc);
			$article_query->bindParam(":a_blurb", $a_blurb);
			$article_query->bindParam(":a_img", $a_img);
			$article_query->bindParam(":a_author", $a_author);
			$article_query->bindParam(":t_title", $topic);
			$article_query->bindParam(":a_of_month", $a_of_month);
		$article_query->execute();
	}
	
	public function clear_otm(){
		$query = $this->conn->prepare("UPDATE articles SET a_of_month = 0");
		$query->execute();
	}
	
	public function display_articles(){
		foreach ($this->conn->query("SELECT * FROM articles ORDER BY a_date DESC") as $row){
		echo '<div class="item_row">
				<div class="desc">
					<h2>'.$row['a_title'].'<em> - '.$row['a_author'].'</em></h2>
					<h3>'.$row['a_date'].'</h3>
				</div>
				<div class="controls">
					<button class="a_remove x-button" type="button">Delete</button>
					<a href="manage.php?action=edit&type=article&id='.$row['a_id'].'"><button class="s-button" type="button">Edit</button></a>
					';
		if($row['a_of_month'] == 1){
			echo '<img class="star-button-sml" src="./core/css/images/star_on.png"/>';
		}
		else{
			echo '<a href="manage.php?action=otm&type=article&id='.$row['a_id'].'"><img class="star-button-sml" src="./core/css/images/star_off.png"/></a>';
		}
		
		echo	'
					<div class="a_remove_div">
						<h3>Are you sure you want to delete this item?</h3></br>
						<button class="a_remove_cnl s-button" type="button">No</button>
						<a href="manage.php?action=delete&type=article&id='.$row['a_id'].'"><button class="x-button" type="button">Yes</button></a>
					</div>
				</div>';
		echo '</div>';
		}
	}
	
	public function update_article($a_title, $a_desc, $a_blurb, $a_author, $topic, $id){
		$query = $this->conn->prepare("UPDATE articles SET a_title = :a_title, a_desc = :a_desc, a_blurb = :a_blurb, a_author = :a_author, t_title = :t_title WHERE
			a_id = :id");
			$query->bindParam(":a_title", $a_title);
			$query->bindParam(":a_desc", $a_desc);
			$query->bindParam(":a_blurb", $a_blurb);
			$query->bindParam(":a_author", $a_author);
			$query->bindParam(":t_title", $topic);
			$query->bindParam(":id", $id);
		$query->execute();
	}
	
}
/*--------------------------------

		ARTICLES PHP SECTION
		
---------------------------------*/
//Success Variable
$a_success = '';
//Article form error(s) variables
$article_title_error = '';
$article_topic_error = '';
$article_desc_error = '';
$article_blurb_error = '';
$article_author_error = '';

//Instance objects
$article_obj = new article($conn);
$article_img = new images($conn);

//If an article is posted
if (isset ($_POST['a_submit'])){
	//Check for required fields
	if (empty($_POST['a_title']) ||
		(empty($_POST['at_title_text']) && (empty ($_POST['at_title_select']))) ||
		(empty($_POST['a_desc'])) ||
		(empty($_POST['a_author']))
		){
			if (empty($_POST['a_title'])){
				$article_title_error = '<em class="error">An Article Title is required</em>';
			}
			if (empty($_POST['at_title_text']) && (empty ($_POST['at_title_select']))){
				$article_topic_error = '<em class="error">An Article Topic is required</em>';
			}
			if (empty($_POST['a_desc'])){
				$article_desc_error = '<em class="error">Article Text Content is required</em>';
			}
			if (empty($_POST['a_blurb'])){
				$article_blurb_error = '<em class="error">An Article Blurb is required</em>';
			}
			if (empty($_POST['a_author'])){
				$article_author_error = '<em class="error">An Article Author is required</em>';
			}
		}
	else{
		//check which topic field is entered
		if (empty($_POST['at_title_text']) && (!empty($_POST['at_title_select']))){
			$article_topic = $_POST['at_title_select'];
			$article_type_bool = 0;
			$update_topic_type = 0;
		}
		if (!empty($_POST['at_title_text']) && (!empty($_POST['at_title_select']))){
			$article_topic = $_POST['at_title_select'];
			$article_type_bool = 0;	
			$update_topic_type = 1;			
		}
		if (!empty($_POST['at_title_text']) && (empty($_POST['at_title_select']))){
			$article_topic = $_POST['at_title_text'];
			$article_type_bool = 1;	
			$update_topic_type = 1;
		}
		
		//Check if form data has been sent from manage page
		if (isset($_POST['a_edit'])){
			$article_type_bool = 3;	
			$article_id = $_POST['a_edit'];
		}
		
		//If select topic is chosen then just insert article
		if($article_type_bool == 0){	
			$conn->beginTransaction();
				//Set article image variable incase no image is uploaded
				$article_img_file = '';
				//If image is set, run single image insert function from admin_functions.php
				if (!empty($_FILES['a_img']['name'])){
					$article_img_file = $article_img->single_image_insert($_FILES['a_img']);
				}
				//Check of the month number
				if($_POST['a_of_month'] == 1){
					$article_obj->clear_otm();
				}
				
				//Call article_insert function from admin_functions.php
				$article_obj->article_insert($_POST['a_title'], $_POST['a_desc'], $_POST['a_blurb'], $article_img_file, $_POST['a_author'], $article_topic, $_POST['a_of_month']);
			$conn->commit();
			$a_success = '<h2 class="success">Article Posted!</h2>';
		}
		//if insert topic and article together
		if($article_type_bool == 1){
			$conn->beginTransaction();
				//Call topic_insert function from admin_functions.php
				
				$article_obj->insert_topic($article_topic);
				//Set article image variable incase no image is uploaded
				$article_img_file = '';
				if (!empty($_FILES['a_img']['name'])){
					$article_img_file = $article_img->single_image_insert($_FILES['a_img']);
				}
				//Check of the month number
				if($_POST['a_of_month'] == 1){
					$article_obj->clear_otm();
				}
				
				//Call article_insert function from admin_functions.php
				$article_obj->article_insert($_POST['a_title'], $_POST['a_desc'], $_POST['a_blurb'], $article_img_file, $_POST['a_author'], $article_topic, $_POST['a_of_month']);
			$conn->commit();
			$a_success = '<h2 class="success">Article Posted and Topic Created!</h2>';
		}
		//If data was sent for updating by management
		if($article_type_bool == 3){
			if($update_topic_type == 0){
				$article_obj->update_article($_POST['a_title'], $_POST['a_desc'], $_POST['a_blurb'], $_POST['a_author'], $article_topic, $article_id);
				$a_success = '<h2 class="success">Article Updated!</h2>';
			}
			if($update_topic_type == 1){
				$article_topic = $_POST['at_title_text'];
				$article_obj->insert_topic($article_topic);
				$article_obj->update_article($_POST['a_title'], $_POST['a_desc'], $_POST['a_blurb'], $_POST['a_author'], $article_topic, $article_id);
				$a_success = '<h2 class="success">Article and Topic Updated!</h2>';
			}
		}
		
	}
}
/*----------END OF ARTICLES PHP SECTION----------*/
/*--------------------------------

		ARTICLES HTML SECTION
		
---------------------------------*/



$a_html = $a_success;
$a_html .= <<<EOD
		<form action="index.php" method="post" enctype="multipart/form-data" >
			<label class="left_col" for="a_title">Article Title:</label>
			<input name="a_title" type="text" placeholder="Article Title"/>
EOD;
$a_html .= $article_title_error;
$a_html .= <<<EOD
</br>
			<label class="left_col" for="at_title_select">Topic:</label>
			<select name="at_title_select">
			<option></option>
EOD;

$a_html2 = <<<EOD
</select>
			or
			<input name="at_title_text" type="text"/>
EOD;
$a_html2 .= $article_topic_error;
$a_html2 .= '</br>
			<label for="a_blurb">Article Blurb:</label>
			<textarea class="ro_ex_desc" name="a_blurb"></textarea>';
$a_html2 .= $article_blurb_error;			
$a_html2 .= <<<EOD
<hr>
	<label for="a_desc">Article Text:</label>
	<textarea class="wys" name="a_desc"></textarea>
EOD;
$a_html2 .= $article_desc_error;
$a_html2 .= <<<EOD
</br>
	<label for="a_img">Article Image:</label>
	<input name="a_img" type="file"></br>
	<label for="a_author">Author:</label>
	<input name="a_author" type="text" placeholder="Author"/>
EOD;
$a_html2 .= $article_author_error;
$a_html2 .= <<<EOD
</br>
	<label for="a_of_month">Article of the Month:</label>
	<button type="button" class="star-button a_of_month_btn"><img src="./core/css/images/star_on.png"/></button>
	<input name="a_of_month" class="a_of_month" type="hidden" value="0"/>
	</br>	
	<input class="s-button" type="submit" name="a_submit"/>
</form>

EOD;
/*----------END OF ARTICLES HTML SECTION----------*/

		






?>






