<?php
  /**
   * Catches the IPN request from PayPal and adds the user
   * to the database.
   */

	// Send an empty HTTP 200 OK response to acknowledge receipt of the notification 
  header('HTTP/1.1 200 OK'); 

	// Build the required acknowledgement message out of the notification just received
  $req = 'cmd=_notify-validate';               

	  
	foreach ($_POST as $key => $value) {
	   $value = urlencode(stripslashes($value));
	   $req  .= "&$key=$value";
	}

  	// Set up the acknowledgement request headers
	$header  = "POST /cgi-bin/webscr HTTP/1.1\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Host: www.sandbox.paypal.com:443\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n"; 
	$header .= "Connection: close\r\n\r\n";

  	// Open a socket for the acknowledgement request
  $fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

	// Send the HTTP POST request back to PayPal for validation
  fputs($fp, $header . $req);

  $res = fgets($fp, 1024);

  if(!file_exists('../../db.json')) {
    system('echo \'{"registrants":[]}\' > ../../db.json');
  }

  if (strcmp ($res, "VERIFIED") !== false) {

    // Get POST data
    $id            = $_POST['payer_id'];
    $firstName     = $_POST['first_name'];
    $lastName      = $_POST['last_name'];
    $email         = $_POST['payer_email'];
    $txnId         = $_POST['txn_id'];
    $paymentStatus = $_POST['payment_status'];
    $verifySign    = $_POST['verify_sign'];

    $newRegistrant  = array(
      'id'            => $id,
      'firstName'     => $firstName,
      'lastName'      => $lastName,
      'email'         => $email,
      'txnId'         => $txnId,
      'paymentStatus' => $paymentStatus,
      'verifySign'    => $verifySign
    );

    $replyToEmail = "support@yrm2015.co.uk";

    function addRegistrantToDatabase($newRegistrant) {
      // Database file
      $db_file_path = '../../db.json';

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

        return true;

      } else {
        return false;
      }
    }
    // If the file is in use sleep for 7
    // seconds then try again
    if(!addRegistrantToDatabase($newRegistrant)) {
      sleep(7);
      if(!addRegistrantToDatabase($newRegistrant)) {
        mail($_POST['payer_email'], "YRM2015 Processing Error", "Hi, \r\n\r\nSorry, something went wrong when trying to process your order. Your payment is fine however it may not have been recorded in our database. Please reply to this email to report this error and quote your transaction ID: $txnId \r\n\r\nRegards,\r\n\r\nYRM2015 Support", "From: YRM2015\r\nReply-To: $replyToEmail");
      }
    }

  } 

  else if (strcmp ($res, "INVALID") !== false) {

  // Tries to contact the email in the POST request
    if(isset($_POST['payer_email'])) {
    	mail($_POST['payer_email'], "Invalid Purchase", "Hi, \r\n\r\nWe have received an invalid payment request from this email address via PayPal. If you have tried to purchase a YRM2015 ticket recently your payment pay not have come through.\r\n\r\nIf this is the case, please reply to this email to contact our support line.\r\n\r\nRegards,\r\n\r\nYRM2015 Support", "From: YRM2015\r\nReply-To: $replyToEmail");
    }
  }

	fclose($fp);

?>