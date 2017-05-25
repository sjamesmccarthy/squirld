<?php

// Listings Index


class Listings extends Core {
	

	public function __construct() {
		//$this->getConfig();
		include_once($_SERVER['DOCUMENT_ROOT'] . '/model/ListingsModel.php');
		$this->listingsModel = new ListingsModel();        
	}
	
	public function showSplash()
    	{
 
        	include($_SERVER['DOCUMENT_ROOT'] . '/view/view_listings_splash.php');
    	}
    	
	public function showStates()
    	{
    		
    		$this->data = $this->listingsModel ->getStates();
        	include($_SERVER['DOCUMENT_ROOT'] . '/view/view_listings.php');
        	
    	}
    
    	public function showCities($state)
    	{
        	$this->data = $this->listingsModel ->getCities($state);
        	include($_SERVER['DOCUMENT_ROOT'] . '/view/view_listings.php');
    	}
    	
    	public function showLocations($city, $state)
    	{
        	$this->data = $this->listingsModel->getLocations($city, $state);
        	include($_SERVER['DOCUMENT_ROOT'] . '/view/view_listings.php');
    	}

        public function showCurrentLocations($lat,$lng)
        {
            $this->data = $this->listingsModel ->getCurrentLocations($lat,$lng);
            return($this->data);
            // include($_SERVER['DOCUMENT_ROOT'] . '/view/view_listings_currentlocations.php');
        }


}
