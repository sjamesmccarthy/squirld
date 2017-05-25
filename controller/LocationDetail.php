<?php

// Listings Index

class LocationDetail extends Core {

	public $data = array();
	
	public function __construct() {
        	$this->getConfig();
        	include($_SERVER['DOCUMENT_ROOT'] . '/model/LocationDetailModel.php');
		$this->locationDetailModel = new LocationDetailModel;
	}
	
	
	public function showLocation($id)
    	{
    		
    		$this->data['location'] = $this->locationDetailModel ->getLocation($id);
            $this->data['happyhours'] = $this->locationDetailModel ->getHappyHours($id);
    		$this->data['ratings'] = $this->locationDetailModel ->getRatings($id);
    		
        	include($_SERVER['DOCUMENT_ROOT'] . '/view/view_location_detail.php');
        	
    	}
    

}
