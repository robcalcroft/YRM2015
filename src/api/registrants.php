<?php

	/***************************************
	  GET - Returns all registrants from the
      database. In Fname and truncated seco-
      nd name.

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

	// POST
	function newRegistrant() {
		// Validate data
		$db_file_path = '../../db.json';
		// Get POST data
		$registrantFName = $_POST['r_fname'];
		$registrantSName = $_POST['r_sname'];

		$newRegistrant  = array(
			'registrantFName' => $registrantFName,
			'registrantSName'  => $registrantSName             
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

			sendJson(array('success'=>1), null);

		} else {
			sendJson(array('error'=>'Please try again shortly, the database is currently processing'), null);
		}
	}

	// GET
	function getRegistrant() {
		$registrants = json_decode(file_get_contents('../../db.json'), true)['registrants'];
		$responseArr = array();
		for ($i=0; $i < count($registrants); $i++) { 
			$fname = $registrants[$i]['registrantFName'];
			$sname = substr($registrants[$i]['registrantSName'], 0,1);
			$fin   = $fname . " " . $sname;
			array_push($responseArr, $fin);
		}
		sendJson($responseArr,null);
	}


?>