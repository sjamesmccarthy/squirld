<?php

// Listings Index

class LocationFormModel extends LocationForm {

	public $newid;
	public $city;
	public $state;
	public $data;
	
	public function __construct() {
		
		$this->getConfig();
        	$this->startDB();
	}
	
	public function getStateList() 
	{
		
		$sql = "SELECT * FROM data_states";
		$result = $this->mysqli->query($sql);

		if ($result->num_rows > 0) {
		    
			while($row = $result->fetch_assoc())
		        {
		            $data[] = $row;
		        }
		    
		} else {
			
			$data = "No States Found";
		}	
		
		return($data);
	}
	
	public function save()
    	{

		// check user_id
		$user_id = $this->checkUser();
    		$this->city = $_REQUEST['loc_city'];
    		$this->state = $_REQUEST['loc_state'];
    		
		// fetch lat/lng 
		$address_merged = $_REQUEST['loc_address'] . "+" . $_REQUEST['loc_city'] . "+" . $_REQUEST['loc_state'];		
		$address = urlencode($address_merged );
		$url = "http://maps.google.com/maps/api/geocode/json?address=$address";		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		$lat = $response_a->results[0]->geometry->location->lat;
		$long = $response_a->results[0]->geometry->location->lng;
		$zip =  $response_a->results[0]->address_components[6]->long_name;
		$geolocation = $lat . " , " . $long;
    		
    		//print "<pre>";
    		//print_r($response_a);
    		//print $geolocation . ' -zip:' . $zip;
    		//print "</pre>";
    		//exit;
    		
    		$loc_address = ucwords(strtolower($_REQUEST['loc_address']));

    		
    	// tbl::location
    	// add: weblink, beer, wine, cocktails, food, info, $user_id, created, status, lat, lng
    	$sql = "INSERT INTO location (user_id, name, address, city, state, zip, phone, lat, lng, weblink, beer, wine, cocktails, food, ambiance, notify_steward, created, status)
		VALUES ('" . 
		 $user_id . "','" .
		 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_name']) . "','" .
		 mysqli_real_escape_string($this->mysqli, $loc_address) . "','" .
		 $_REQUEST['loc_city'] . "','" .  
		 $_REQUEST['loc_state'] . "','" .
		 $zip . "','" .
		 $_REQUEST['loc_phone'] . "','" .
		 $lat . "','" .
		 $long . "','" .
		 $_REQUEST['loc_weblink'] . "','" .
		 $_REQUEST['loc_beer'] . "','" .
		 $_REQUEST['loc_wine'] . "','" .
		 $_REQUEST['loc_cocktails'] . "','" .
		 $_REQUEST['loc_food'] . "','" .
		 $_REQUEST['loc_ambiance'] . "','" . 
		 $_REQUEST['loc_notify'] . "','" .
		 date ("Y-m-d H:i:s", time()) . "','" .
		 '1' . "')";

		
		if ($this->mysqli->query($sql) === TRUE) {
		    $new_location_id = $this->mysqli->insert_id;
		    $this->newId = $new_location_id;
		    //echo "<br />(m)New location " . $_REQUEST['loc_name'] . " created successfully, id:" . $new_location_id;
		    //echo "<br />(m)geolocation: ", $geolocation;
		    //echo "<br />(m)zip: ", $zip, " city: ", $_REQUEST['loc_city'], " state: ", $_REQUEST['loc_state'];
		    //echo "<br />(m)api_query: ", $url;
		    
		} else {
		    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
		}

		// insert into tbl::location_happyhours
		// Always insert record for M-F add: location_id, start, end, days(M,T,W,R,F,S,U), description, active
		
		// split apart time
		// $hours_MF = explode('-', $_REQUEST['loc_hours_MF_time']);
		
		// format days
		// loc_hours_WK_group[]
		// $hours_days_MF = "1,2,3,4,5";
		
		if (isset($_REQUEST['loc_hours_WK_group'])) {
		    $optionArray = $_POST['loc_hours_WK_group'];
		    for ($i=0; $i<count($optionArray); $i++) {
		        $hours_days_MF .= $optionArray[$i].",";
		    }
		    
		    $hours_days_MF = substr_replace($hours_days_MF , "", -1);
		}
		
		
		 $sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
			VALUES ('" . 
			 $new_location_id . "','" .
			 '1' . "','" .
			 $_REQUEST['loc_hours_MF_time_START'] . "','" .
			 $_REQUEST['loc_hours_MF_time_END'] . "','" .
			 $hours_days_MF . "','" .
			 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hours_MF_desc']) . "','" .
			 date ("Y-m-d H:i:s", time()) . "','" .
			 '1' . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) {
			    //echo "<br />(m)New M-F Happy Hour created successfully: " . $hours_days . " " . $_REQUEST['loc_hours_MF_time_START'] . " - " . $_REQUEST['loc_hours_MF_time_END'];
			    
		   } else {
			    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
		   }

		// second happy hour
		if (isset($_REQUEST['loc_hours_WK2_group'])) {
		    $optionArray = $_POST['loc_hours_WK2_group'];
		    for ($i=0; $i<count($optionArray); $i++) {
		        $hours_days_MF2 .= $optionArray[$i].",";
		    }
		    
		    $hours_days_MF2 = substr_replace($hours_days_MF2 , "", -1);
		}
		

		if($_REQUEST['loc_hours_MF_time_START_2'] != "-") {
			 $sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
				VALUES ('" . 
				 $new_location_id . "','" .
				 '3' . "','" .
				 $_REQUEST['loc_hours_MF_time_START_2'] . "','" .
				 $_REQUEST['loc_hours_MF_time_END_2'] . "','" .
				 $hours_days_MF2 . "','" .
				 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hours_MF_desc_2']) . "','" .
				 date ("Y-m-d H:i:s", time()) . "','" .
				 '1' . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				    //echo "<br />(m)New M-F second Happy Hour created successfully: " . $hours_days . " " . $_REQUEST['loc_hours_MF_time_START_2'] . " - " . $_REQUEST['loc_hours_MF_time_END_2'];
				    
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		 }

		
		// check for Monday Special
		if($_REQUEST['loc_hours_special_M'] != "") {
	 	$sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
			VALUES ('" . 
			 $new_location_id . "','" .
			 '2' . "','" .
			 $_REQUEST['loc_hours_MF_time_START'] . "','" .
			 $_REQUEST['loc_hours_MF_time_END'] . "','" .
			 '1'. "','" .
			 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hours_special_M']) . "','" .
			 date ("Y-m-d H:i:s", time()) . "','" .
			 '1' . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) { 
		   	//echo "<br />(m)New Monday Happy Hour Special created successfully. "; 
		   } else { 
		   	echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error; 
		   }
		}
		
		// check for Tuesday Special
		if($_REQUEST['loc_hours_special_T'] != "") {
	 	$sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
			VALUES ('" . 
			 $new_location_id . "','" .
			  '2' . "','" .
			 $_REQUEST['loc_hours_MF_time_START'] . "','" .
			 $_REQUEST['loc_hours_MF_time_END'] . "','" .
			 '2'. "','" .
			 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hours_special_T']) . "','" .
			 date ("Y-m-d H:i:s", time()) . "','" .
			 '1' . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) { 
		   	//echo "<br />(m)New Tuesday Happy Hour Special created successfully. "; 
		   } else { 
		   	echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error; 
		   }
		}
		
		// check for Wednesday Special
		if($_REQUEST['loc_hours_special_W'] != "") {
	 	$sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
			VALUES ('" . 
			 $new_location_id . "','" .
			  '2' . "','" .
			 $_REQUEST['loc_hours_MF_time_START'] . "','" .
			 $_REQUEST['loc_hours_MF_time_END'] . "','" .
			 '3'. "','" .
			 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hours_special_W']) . "','" .
			 date ("Y-m-d H:i:s", time()) . "','" .
			 '1' . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) { 
		   	//echo "<br />(m)New Wednesday Happy Hour Special created successfully. "; 
		   } else { echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error; }
		}
		
		// check for Thursday Special
		if($_REQUEST['loc_hours_special_R'] != "") {
	 	$sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
			VALUES ('" . 
			 $new_location_id . "','" .
			  '2' . "','" .
			 $_REQUEST['loc_hours_MF_time_START'] . "','" .
			 $_REQUEST['loc_hours_MF_time_END'] . "','" .
			 '4'. "','" .
			 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hours_special_R']) . "','" .
			 date ("Y-m-d H:i:s", time()) . "','" .
			 '1' . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) { 
		   	//echo "<br />(m)New Thursday Happy Hour Special created successfully. "; 
		   } else { echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error; }
		}
		
		// check for Friday Special
		if($_REQUEST['loc_hours_special_F'] != "") {
	 	$sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
			VALUES ('" . 
			 $new_location_id . "','" .
			  '2' . "','" .
			 $_REQUEST['loc_hours_MF_time_START'] . "','" .
			 $_REQUEST['loc_hours_MF_time_END'] . "','" .
			 '5'. "','" .
			 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hours_special_F']) . "','" .
			 date ("Y-m-d H:i:s", time()) . "','" .
			 '1' . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) { 
		   	//echo "<br />(m)New Friday Happy Hour Special created successfully. "; 
		   } else { echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error; }
		}
		
		// check for Saturday Special
		if($_REQUEST['loc_hours_special_S'] != "") {
	 	$sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
			VALUES ('" . 
			 $new_location_id . "','" .
			  '2' . "','" .
			 $_REQUEST['loc_hours_MF_time_START'] . "','" .
			 $_REQUEST['loc_hours_MF_time_END'] . "','" .
			 '5'. "','" .
			 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hours_special_S']) . "','" .
			 date ("Y-m-d H:i:s", time()) . "','" .
			 '1' . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) { 
		   	//echo "<br />(m)New Saturday Happy Hour Special created successfully. "; 
		   } else { echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error; }
		}
		
		// check for Sunday Special
		if($_REQUEST['loc_hours_special_U'] != "") {
	 	$sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
			VALUES ('" . 
			 $new_location_id . "','" .
			  '2' . "','" .
			 $_REQUEST['loc_hours_MF_time_START'] . "','" .
			 $_REQUEST['loc_hours_MF_time_END'] . "','" .
			 '5'. "','" .
			 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hours_special_U']) . "','" .
			 date ("Y-m-d H:i:s", time()) . "','" .
			 '1' . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) { 
		   	//echo "<br />(m)New SundayHappy Hour Special created successfully. "; 
		   } else { echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error; }
		}
		
		// check for weekend hours; if TRUE check for desc
		if($_REQUEST['loc_hours_SU_time_START'] != "") {
			// split apart time
			// $hours_SU = explode('-', $_REQUEST['loc_hours_SU_time']);
			
			// format days
			if (isset($_REQUEST['loc_hours_WKND_group'])) {
		    $optionArray = $_POST['loc_hours_WKND_group'];
		    for ($i=0; $i<count($optionArray); $i++) {
		        $hours_days_WKND .= $optionArray[$i].",";
		    }
		    
		    $hours_days_WKND = substr_replace($hours_days_WKND , "", -1);
		}
			
			 $sql = "INSERT INTO location_happyhours (location_id, type, start, end, days, description, created, active)
				VALUES ('" . 
				 $new_location_id . "','" .
				  '1' . "','" .
				 $_REQUEST['loc_hours_SU_time_START'] . "','" .
				 $_REQUEST['loc_hours_SU_time_END'] . "','" .
				 $hours_days_WKND . "','" .
				 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_hoursSU_desc']) . "','" .
				 date ("Y-m-d H:i:s", time()) . "','" .
				 '1' . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				    //echo "<br />(m)New Sat/Sun Happy Hour created successfully: " . $hours_days . " " . $_REQUEST['loc_hours_SU_time_START'] . " - " . $_REQUEST['loc_hours_SU_time_END'];
				    
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		}
		
		// insert ratings
		if($_REQUEST['loc_rating_price'] != "-") {
			
			 $sql = "INSERT INTO location_ratings (location_id, user_id, rating_key, rating_value)
				VALUES ('" . 
				 $new_location_id . "','" .
				 $user_id . "','" .
				 'price' . "','" .
				 $_REQUEST['loc_rating_price'] . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				    //echo "<br />(m)New RATING:PRICE created successfully: " . $_REQUEST['loc_rating_price'];
				    
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		}
		
		if($_REQUEST['loc_rating_ambiance'] != "-") {
			
			 $sql = "INSERT INTO location_ratings (location_id, user_id, rating_key, rating_value)
				VALUES ('" . 
				 $new_location_id . "','" .
				 $user_id . "','" .
				 'ambiance' . "','" .
				 $_REQUEST['loc_rating_ambiance'] . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				    //echo "<br />(m)New RATING:AMBIANCE created successfully: " . $_REQUEST['loc_rating_ambiance'];
				    
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		}
		
		if($_REQUEST['loc_rating_noise'] != "-") {
			
			 $sql = "INSERT INTO location_ratings (location_id, user_id, rating_key, rating_value)
				VALUES ('" . 
				 $new_location_id . "','" .
				 $user_id . "','" .
				 'noise' . "','" .
				 $_REQUEST['loc_rating_noise'] . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				    //echo "<br />(m)New RATING:NOISE created successfully: " . $_REQUEST['loc_rating_noise'];
				    
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		}
		
		if($_REQUEST['loc_rating_beer'] != "-") {
			
			 $sql = "INSERT INTO location_ratings (location_id, user_id, rating_key, rating_value)
				VALUES ('" . 
				 $new_location_id . "','" .
				 $user_id . "','" .
				 'beer' . "','" .
				 $_REQUEST['loc_rating_beer'] . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				    //echo "<br />(m)New RATING:BEER created successfully: " . $_REQUEST['loc_rating_beer'];
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		}
		
		if($_REQUEST['loc_rating_wine'] != "-") {
			
			 $sql = "INSERT INTO location_ratings (location_id, user_id, rating_key, rating_value)
				VALUES ('" . 
				 $new_location_id . "','" .
				 $user_id . "','" .
				 'wine' . "','" .
				 $_REQUEST['loc_rating_wine'] . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				    //echo "<br />(m)New RATING:WINE created successfully: " . $_REQUEST['loc_rating_wine'];
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		}
		
		if($_REQUEST['loc_rating_cocktails'] != "-") {
			
			 $sql = "INSERT INTO location_ratings (location_id, user_id, rating_key, rating_value)
				VALUES ('" . 
				 $new_location_id . "','" .
				 $user_id . "','" .
				 'cocktails' . "','" .
				 $_REQUEST['loc_rating_cocktails'] . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				   // echo "<br />(m)New RATING:COCKTAILS created successfully: " . $_REQUEST['loc_rating_cocktails'];
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		}
		
		if($_REQUEST['loc_rating_food'] != "-") {
			
			 $sql = "INSERT INTO location_ratings (location_id, user_id, rating_key, rating_value)
				VALUES ('" . 
				 $new_location_id . "','" .
				 $user_id . "','" .
				 'food' . "','" .
				 $_REQUEST['loc_rating_food'] . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				    //echo "<br />(m)New RATING:FOOD created successfully: " . $_REQUEST['loc_rating_food'];
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		}
		
		// add feedback to tbl::user_feedback
		if($_REQUEST['loc_feedback'] != "") {
			
			 $sql = "INSERT INTO user_feedback (user_id, location_id, msg, created, status)
				VALUES ('" . 
				 $user_id . "','" .
				 $new_location_id . "','" .
				 mysqli_real_escape_string($this->mysqli, $_REQUEST['loc_feedback']) . "','" .
				 date ("Y-m-d H:i:s", time()) . "','" .
			 	 '1' . "')";
				 
			   if ($this->mysqli->query($sql) === TRUE) {
				    //echo "<br />(m)New FEEDBACK created successfully. ";
			   } else {
				    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
			   }
		}
		
		$POSTjson = json_encode($_POST);

		$sql = "INSERT INTO transactions (data)
			VALUES ('" . 
			 mysqli_real_escape_string($this->mysqli, $POSTjson) . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) {
			    //echo "<br />(m)New POST->json data transaction created successfully. ";
		   } else {
			    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
		   }

    }
	

	public function edit()
	{
	

	}
	
	
	public function delete()
	{
	

	}
	
	/* move this into user model or core model once built */
	public function checkUser() 
	{
	
		// select id from user where email = '%email%'
		// if exists, return(id)
		
		$sql = "SELECT id from user where email = '" . $_REQUEST['loc_user'] . "'";
		$result = $this->mysqli->query($sql);

		if ($result->num_rows > 0) {
		
		    while($row = $result->fetch_assoc()) {
		        $user_id = $row["id"];
		    }
		    
		    //echo "<br />(m)User " . $_REQUEST['loc_user'] . " found, id:" . $user_id;  
		    
		} else {
		   
		   // else insert into tbl::user (email, created)
		   $sql = "INSERT INTO user (email,created,status)
			VALUES ('" . 
			 $_REQUEST['loc_user'] . "','" .
			 date ("Y-m-d H:i:s", time()) . "','" .
			 '1' . "')";
			 
		   if ($this->mysqli->query($sql) === TRUE) {
			    $new_user_id= $this->mysqli->insert_id;
			    //echo "<br />(m)New user created successfully: " . $new_user_id;
		   } else {
			    echo "<br />Error: " . $sql . "<br>" . $this->mysqli->error;
		   }

		}
		
		return($user_id);

	}
}