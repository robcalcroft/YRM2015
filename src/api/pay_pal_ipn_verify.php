<?php
	
	/**
	 * Validates a PayPal IPN notification
	 * @param  array $req The $_POST variable for the request
	 * @return String     Returns the validity: 'VALID' or 'INVALID'
	 */
	function verifyPayPalIPN($req) {

		// Base URL for the php validation
		$baseURL = 'https://www.paypal.com/cgi-bin/webscr?cmd=_notify-validate&';

		// Loop through the POST parameters to 
		// create the validaton URL
		foreach ($req as $key => $value) {
			$baseURL .= $key . '=' . urlencode($value) . '&';
		}

		// Strip the last & off of the URL
		$baseURL         = substr($baseURL, 0, -1);

		// Send the request to PayPal to confirm 
		// whether the request to the script is 
		// from PayPal
		$requestValidity = system("curl $baseURL");

		// The $requestValidity variable will now
		// be either 'VALID' or 'INVALID'. Do what
		// you will with this information from here
		
		return $requestValidity;
	}

?>
