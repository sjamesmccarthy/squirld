<?php

// Listings Index

class Core {
	
	public function __construct() 
	{
		parent::__construct();
	}
	
	public function getURI()
	{
	
	}
	
	public function getSession()
	{
	
	}
	
	public function startSession()
	{
	
	}
	
	public function buildLayout() 
	{
	
	}
	
	public function startDB() 
	{
		
		// check for dev
		$uri = explode('.', $_SERVER['SERVER_NAME']);
		if($uri[1] == 'dev') { $host = 'dev'; } else { $host = "prod"; }

		$servername = "localhost";
		$username = $this->config['database'][$host]['username'];
		$password = $this->config['database'][$host]['password'];
		$dbname = $this->config['database'][$host]['dbname'];
		
		// Create connection
		$this->mysqli  = new mysqli($servername, $username, $password, $dbname);
		
		/* check connection */
		if ($this->mysqli->connect_errno) {
		    printf("Connect failed: %s\n", $this->mysqli->connect_error);
		    exit();
		}

	}
	
	public function closeDB()
	{
		/* close connection */
		$this->mysqli->close();

	}
	
	public function getConfig()
	{
		
		$json_config = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/config.json"), true);
		$this->config = $json_config;
	}
        
}
