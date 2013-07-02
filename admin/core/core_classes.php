<?php
/*-------------------------------------------
|  											|
|  											|
|  			TREST CREATIVE					|
|  			CMS								|
|  			CORE CLASSES					|
|  											|
-------------------------------------------*/

/*------------IMAGE CLASS----------*/
class images{
	
	public function __construct($conn)
    {
        $this->conn = $conn;
         return true;
    } 

	//Single image insert
	public function single_image_insert($file){

		$allowedExts = array("jpg", "JPEG", "JPG", "jpeg", "gif", "GIF", "png", "PNG");
		$tmp = explode(".", $file["name"]);
		$extension = end($tmp);
		if ((($file["type"] == "image/gif")
		|| ($file["type"] == "image/GIF")
		|| ($file["type"] == "image/jpeg")
		|| ($file["type"] == "image/JPG")
		|| ($file["type"] == "image/jpg")
		|| ($file["type"] == "image/JPEG")
		|| ($file["type"] == "image/png")
		|| ($file["type"] == "image/PNG")
		|| ($file["type"] == "image/pjpeg"))
		&& ($file["size"] < 100000000)
		&& in_array($extension, $allowedExts))
		{
			//Check for file error
			if ($file["error"] > 0){
				//Echo an error if there is an issue with the file upload
				echo "Return Code: " . $file["error"] . " <br>";
			}
			
			//Else attempt to add to upload file
			else{
				//Check if file already exists first
				if (file_exists("database_images/" . $file["name"])){
					//If so, change to new name
					$file_name_split = explode(".", $file["name"]);
					$rand_num = rand(1, 1000);
					$new_file_name = $file_name_split[0] . $rand_num . "." . $file_name_split[1];
					
					//Move the temp file into permenent posistion in productimages directory
					move_uploaded_file($file["tmp_name"],
					"database_images/" . $new_file_name);
							
					$file = $new_file_name;
					return $file;
				}
				//Else if no errors, upload file
				else{
					//Move the temp file into permenent posistion in productimages directory
					move_uploaded_file($file["tmp_name"],
					"database_images/" . $file["name"]);
					
					$file = $file["name"];
					return $file;
				}
			} 
		}
		else{
			//if file has an error 
			echo "Invalid file or fields were left empty";
		}
	}
	
	//Single image array insert
	public function single_image_array_insert($file, $img){

		$allowedExts = array("jpg", "JPG", "jpeg", "gif", "png", "PNG");
		$tmp = explode(".", $file["name"][$img]);
		$extension = end($tmp);
		if ((($file["type"][$img] == "image/gif")
		|| ($file["type"][$img] == "image/jpeg")
		|| ($file["type"][$img] == "image/JPEG")
		|| ($file["type"][$img] == "image/JPG")
		|| ($file["type"][$img] == "image/png")
		|| ($file["type"][$img] == "image/PNG")
		|| ($file["type"][$img] == "image/pjpeg"))
		&& ($file["size"][$img] < 1000000000)
		&& in_array($extension, $allowedExts))
		{
			//Check for file error
			if ($file["error"][$img] > 0){
				//Echo an error if there is an issue with the file upload
				echo "Return Code: " . $file["error"][$img] . " <br>";
			}
			
			//Else attempt to add to upload file
			else{
				//Check if file already exists first
				if (file_exists("database_images/" . $file["name"][$img])){
					//If so, change to new name
					$file_name_split = explode(".", $file["name"][$img]);
					$rand_num = rand(1, 1000);
					$new_file_name = $file_name_split[0] . $rand_num . "." . $file_name_split[1];
					
					//Move the temp file into permenent posistion in productimages directory
					move_uploaded_file($file["tmp_name"][$img],
					"database_images/" . $new_file_name);
							
					$file = $new_file_name;
					return $file;
				}
				//Else if no errors, upload file
				else{
					//Move the temp file into permenent posistion in productimages directory
					move_uploaded_file($file["tmp_name"][$img],
					"database_images/" . $file["name"][$img]);
					
					$file = $file["name"][$img];
					return $file;
				}
			} 
		}
		else{
			//if file has an error 
		}
	}

}



/*-----------------------
	GENERAL FUNCTIONS
------------------------*/

function random_password(){
	//Generate random password
		$chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
		srand((double)microtime()*1000000); 
		$i = 0; 
		$pass = '' ; 

		while ($i <= 7) { 
			$num = rand() % 33; 
			$tmp = substr($chars, $num, 1); 
			$pass = $pass . $tmp; 
			$i++; 
		}
		return $pass;
}

function calculate_month($month){
	switch ($month)
		{
		case 'January':
			$month = '01';
		break;
		case 'February':
			$month = '02';
		break;
		case 'March':
			$month = '03';
		break;
		case 'April':
			$month = '04';
		break;
		case 'May':
			$month = '05';
		break;
		case 'June':
			$month = '06';
		break;
		case 'July':
			$month = '07';
		break;
		case 'August':
			$month = '08';
		break;
		case 'September':
			$month = '09';
		break;
		case 'October':
			$month = '10';
		break;
		case 'November':
			$month = '11';
		break;
		case 'December':
			$month = '12';
		break;
		default:
		  $month = '01';
		}
	return $month;
}

function time_date($timestamp){
	return date("g:i a F j, Y ", strtotime($timestamp));
}

function generate_password(){
	$length = 8;
    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;
  }



 
 
 
?>