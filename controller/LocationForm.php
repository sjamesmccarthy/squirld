<?php

// Listings Index

class LocationForm extends Core {

	
	public function __construct() {
        $this->getConfig();
        include($_SERVER['DOCUMENT_ROOT'] . '/model/LocationFormModel.php');
	$this->location_form_model = new LocationFormModel;
     
	}
	
	public function showForm()
    	{
    		
        	include($_SERVER['DOCUMENT_ROOT'] . '/view/view_location_form.php');
        	
    	}
	

	public function LocationFormAdd()
	{
	
		$this->location_form_model->save();
		header('location:/thankyou.php?city=' . urlencode($this->location_form_model->city) . '&state=' . urlencode($this->location_form_model->state) . '&id=' . $this->location_form_model->newId);

	}
	
	public function LocationFormEdit()
	{
	
	}
	
	public function LocationFormDelete() 
	{
	
	}
	
	public function getLatLng() 
	{
	
	}
	
	public function populateForm() 
	{
	
	}
        
}
