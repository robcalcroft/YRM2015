<?php

	/***************************************
	  GET - Returns all registrants from the
      database.

      POST - takes params and adds to data-
      base
	***************************************/

	require('sendJson.php');

	// Checks to see if the database file
	// exists. 

	$db_file = '../../db.json';

	if(!file_exists($db_file)) {
		// Create database here!!!!
		sendJson(array('error' => 'Database file not found'), function(){
			exit();
		});
	}

	switch ($_SERVER['REQUEST_METHOD']) {
		case 'POST':
			newRegistrant();
			break;
		
		case 'GET':
			getRegistrant();
			break;
	}

	function newRegistrant() {
		// Validate data
		$db_file_path = '../../db.json';
		// Get POST data
		$registrantName = $_POST['name'];
		$registrantAge  = $_POST['age'];

		$newRegistrant  = array(
			'registrantName' => $registrantName,
			'registrantAge'  => $registrantAge             
		);

		// Get current database
		$db_file = fopen($db_file_path, 'r+');

		if(flock($db_file, LOCK_EX)) {

			// Convert the json db into a php array
			$db = json_decode(file_get_contents($db_file_path), true);
			
			// Add new data to database
			array_push($db['registrants'], $newRegistrant);
			
			// Rewrite and reencode the database file
			fwrite($db_file, json_encode($db));

			flock($db_file, LOCK_UN);

			sendJson(array('success'=>'Geezer added'), null);

		} else {
			sendJson(array('error'=>'Please try again shortly, the database is currently processing'), null);
		}
	}

	function getRegistrant() {
		sendJson(json_decode(file_get_contents('../../db.json'), true), null);
	}


?>