<?php

// Listings Index

class ListingsModel extends Listings {

	public function __construct() {
		$this->getConfig();
        $this->startDB();
	}
	
	public function getStates() 
	{
		
		$sql = "SELECT location.state, data_states.statename, COUNT(DISTINCT location.city) as cities FROM `location` 
			left join data_states ON data_states.stateabr = location.state
			WHERE location.status=1 
			GROUP BY location.state
			ORDER BY location.state ASC";
		$result = $this->mysqli->query($sql);

		if ($result->num_rows > 0) {
		
		     while($row = $result->fetch_assoc()) {
		       $results[] = $row["state"] . "/" . $row["statename"] . "/" . $row["cities"];
		    }
		    
		} else {
			
			$results[] = "No States Found";
		}	
		
		return($results);
	}
	
	public function getCities($state) 
	{
		
		$sql = "SELECT location.city, state, COUNT( * ) AS total
			FROM  `location` 
			WHERE location.status =1
			AND location.state ='" . $state . "'
			GROUP BY location.city
			ORDER BY location.city ASC";
		$result = $this->mysqli->query($sql);

		if ($result->num_rows > 0) {
		
		     while($row = $result->fetch_assoc()) {
		       $results[] = $row["city"] . "/" . $row["state"] . "/" . $row['total'];
		    }
		    
		} else {
			
			$results[] = "No Cities Found";
		}	
		
		return($results);
	}

	public function getCurrentLocations($lat=NULL,$lng=NULL) 
	{
		if($lat == 0 && $lng == 0) 
		{
			return(false);
		} else {
			// $lat = $lat + 2;
			// $lng = $lng + 20;
		}
		
		$sql = "SELECT location.id, location.beer, location.wine, location.cocktails, location.food, location.address, location.name, location.lat, location.lng, location_happyhours.start, location_happyhours.end, location.city, 69 * DEGREES(ACOS(COS(RADIANS($lat))
			 * COS(RADIANS(location.lat))
			 * COS(RADIANS(location.lng) - RADIANS($lng))
			 + SIN(RADIANS($lat))
			 * SIN(RADIANS(location.lat))))
			 AS distance
			FROM location

			INNER JOIN location_happyhours ON location.id = location_happyhours.location_id
GROUP BY location.name
HAVING distance <= 5.1
			ORDER BY distance ASC";
			
		$result = $this->mysqli->query($sql);

		if ($result->num_rows > 0) {
		
		     while($row = $result->fetch_assoc()) {
		       // $results[] = $row['id'] . "/" . $row["name"] . "/" .number_format((float)$row["distance"], 2, '.', '') . "/" . $row['address'] . '/' . $row['beer'] . ',' .  $row['wine'] . ',' . $row['cocktails'] . ',' . $row['food'] . "/" . $row['start'] . "/" . $row['end'] ;
			$results[] = $row;
		    }
		    
		  // loop through and add ratings; moved above else
		$i=0;
		foreach ($results as $key => $value) {

			$sql_rating = "select rating_key, AVG(rating_value) as rating_avg from location_ratings where location_id=" . $value['id'] . " AND rating_key='noise'";

			$result_rating = $this->mysqli->query($sql_rating);

			if ($result_rating->num_rows > 0) {
			    
				while($row_rating = $result_rating->fetch_assoc())
			    {
		            $results[$i++]['ratings'][$row_rating['rating_key']] = $row_rating['rating_avg'];
			    }
			    
			}

		}	
		
		} else {
			
			$results[] = "0";
		}	

		return($results);
	}
	
	public function getLocations($city, $state) 
	{

		$rating_array = array('price','noise','beer','cocktails','food');

		$sql = "SELECT location.*, location_happyhours.days, location_happyhours.start, location_happyhours.end FROM location 
			INNER JOIN location_happyhours ON location.id = location_happyhours.location_id
			WHERE 
			-- location_happyhours.days = '1,2,3,4,5' 
			-- OR location_happyhours.days = '6,7'
			location.status =1 
			AND location.city = '" . $city . "' 
			AND location.state = '" . $state . "' 
			GROUP BY location.name
			ORDER BY location.name ASC";

		$result = $this->mysqli->query($sql);

		if ($result->num_rows > 0) {
		
			while($row = $result->fetch_assoc()) {

				$results[] = $row;
		       	
		    	}

		// loop through and add ratings; moved above else
		$i=0;
		foreach ($results as $key => $value) {

			$sql_rating = "select rating_key, AVG(rating_value) as rating_avg from location_ratings where location_id=" . $value['id'] . " AND rating_key='noise'";

			$result_rating = $this->mysqli->query($sql_rating);

			if ($result_rating->num_rows > 0) {
			    
				while($row_rating = $result_rating->fetch_assoc())
			    {
		            $results[$i++]['ratings'][$row_rating['rating_key']] = $row_rating['rating_avg'];
			    }
			    
			}

		}	

		} else {
			
			$results[] = "No Locations Found";
		}	


		return($results);
	}
	
	
}