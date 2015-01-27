<?php

	require('utils.php');

	// Ensures that only AJAX requests can access the file
	//if(!isset(apache_request_headers()["X-Requested-With"]) || apache_request_headers()["X-Requested-With"] !== "XMLHttpRequest")exit();

	// Get the main speakers file
	$speakers_file = '../../speakers.json';

	// If the file is present procede 
	// otherwise error out and send a 
	// response to the client
	if(file_exists($speakers_file)) {

		// Decode the JSON from the
		// file and turn it into an
		// array
		$speakers = json_decode(file_get_contents($speakers_file), true);

		// If there are filters present
		// then procede, otherwise dump
		// the entire speakers db.
		if($_GET) {

			// Check for the filter used
			if($_GET['location']) {
				$filter = $_GET['location'];
				$filter_name = 'location';
			}

			elseif($_GET['subject']) {
				$filter = $_GET['subject'];
				$filter_name = 'subject';
			}
			elseif ($_GET['getCount']) {
				sendJson(array("count" => count($speakers), "status" => 200), function() {
					exit();
				});
			}
			// If no known ones are present
			// then error out.
			else {
				sendJson(array('error' => 'Unknown filter'), function(){
					exit();
				});
			}

			$result = array();

			for ($i=0; $i < count($speakers); $i++) { 
				$filter_val = $speakers[$i][$filter_name];

				// If the current filter value 
				// equals the one in the DB
				// then push the Object to the 
				// response array.
				if($filter_val === $filter) {
					array_push($result, $speakers[$i]);
				}
			}

			sendJson($result);

		} else {

			sendJson($speakers);

		}
		

	} else {
		$status = 404;
		sendJson(array('error' => 'Speakers file does not exist', 'status' => $status));
	}

?>