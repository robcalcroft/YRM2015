<?php

	/***************************************

	  GET - Returns all registrants from the
      database. In Fname and truncated seco-
      nd name.

      e.g. $.getJSON('/YRM2015/src/api/registrants.php')

	***************************************/

	require('utils.php');

	// Ensures that only AJAX requests can access the file
	if(!isset(apache_request_headers()["X-Requested-With"]) || apache_request_headers()["X-Requested-With"] !== "XMLHttpRequest")exit();

	// Checks to see if the database file
	// exists. 

	$db_file = '../../db.json';

	if(!file_exists($db_file)) {
		// Create database here!!!!
		sendJson(array('error' => 'Database file not found'), function(){
			exit();
		});
	}

	$registrants = json_decode(file_get_contents($db_file), true)['registrants'];
	$responseArr = array();
	for ($i=0; $i < count($registrants); $i++) { 
		// $fname = $registrants[$i]['firstName'];
		// $sname = substr($registrants[$i]['lastName'], 0,1);
		// $fin   = $fname . " " . $sname;
		$l = 'Participant';
		array_push($responseArr, $l);
	}
	sendJson($responseArr,null);


?>