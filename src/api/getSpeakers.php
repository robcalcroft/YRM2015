<?php

	require('utils.php');

	// Ensures that only AJAX requests can access the file
	//if(!isset(apache_request_headers()["X-Requested-With"]) || apache_request_headers()["X-Requested-With"] !== "XMLHttpRequest")exit();

	$speakers_file = '../../speakers.json';

	if(file_exists($speakers_file)) {

		$speakers = json_decode(file_get_contents($speakers_file), true);

		if($_GET) {

			if($_GET['location']) {
				$filter = $_GET['location'];
				$filter_name = 'location';
			}

			elseif($_GET['subject']) {
				$filter = $_GET['subject'];
				$filter_name = 'subject';
			}

			$result = array();

			for ($i=0; $i < count($speakers); $i++) { 
				$filter_val = $speakers[$i][$filter_name];

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