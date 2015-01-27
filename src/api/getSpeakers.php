<?php

	require('utils.php');

	// Ensures that only AJAX requests can access the file
	if(!isset(apache_request_headers()["X-Requested-With"]) || apache_request_headers()["X-Requested-With"] !== "XMLHttpRequest")exit();

	$speakers_file = '../../speakers.json';

	if(file_exists($speakers_file)) {

		// Do stuff here

	} else {
		$status = 404;
		sendJson(array('error' => 'Speakers file does not exist', 'status' => $status));
		http_response_code($status);
	}

?>