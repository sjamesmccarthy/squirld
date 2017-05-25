<?php

// Listings Index

class LocationDetailModel extends Listings {

	public $rating_array = array('price','noise','beer','cocktails','food');

	public function __construct() {
		
		$this->getConfig();
        $this->startDB();

	}
	
	public function getLocation($id) 
	{
		
		$sql = "SELECT location . * FROM location WHERE location.id ='" . $id ."'";
		$result = $this->mysqli->query($sql);

		if ($result->num_rows > 0) {
		    
			while($row = $result->fetch_assoc())
		        {
		            $data = $row;
		        }
		    
		} else {
			
			$this->data->results = "No Location Found";
		}	

		return($data);
	}
	
	public function getHappyHours($id) 
	{
		
		$sql = "SELECT * from location_happyhours where location_id ='" . $id ."'";
		$result = $this->mysqli->query($sql);

		if ($result->num_rows > 0) {
		    
			while($row = $result->fetch_assoc())
		        {
		            $data[] = $row;
		        }
		    
		} else {
			
			$this->data->results = "No Happy Hours Found";
		}	
		
		return($data);
	}

	public function getRatings($id)
	{

		foreach ($this->rating_array as $key => $value) {

			$sql = "select rating_key, AVG(rating_value) as rating_avg, COUNT(user_id) as recs from location_ratings where location_id=" . $id . " AND rating_key='" . $value . "'";

			$result = $this->mysqli->query($sql);

			if ($result->num_rows > 0) {
			    
				while($row = $result->fetch_assoc())
			        {
			            $data[$row['rating_key']] = $row['rating_avg'] . '/' . $row['recs'];
			        }
			    
			}

		}	

		return($data);

		/*
			select rating_key, AVG(rating_value) as AVG from location_ratings where location_id=72 AND rating_key='price'
		*/
	}
	
	// getHappyHoursByDay
}