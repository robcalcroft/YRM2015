<?php namespace Contentful;
	// Show errors
	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

	// Get contentful
	require "contentful.php/contentful.php";
	//create the cluent
	$client = Client::get ('TOKEN
');
	// Send the json response
	echo json_encode($client->contentType('3dxswhrfrWGWUqWmoyO6e0', '0848rj37p37z'));
	//echo json_encode($client->entry('5SAehzUShaIcIAakquoOui','0848rj37p37z'));
?>