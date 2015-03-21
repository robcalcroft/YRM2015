<?php
  /**
   * Catches the IPN request from PayPal and adds the user
   * to the database.
   */
  
  date_default_timezone_set("Europe/London");

  require('utils.php');

  // Emails send out:
  // Payment Error - Payment didn't come through as completed
  // Processing Error - DB file was in use when request was made
  // Invalid Purchase - Non verified request come through
  // Ticket Confirmation - Payment is complete and has been recorded in the database
  
  // Add line here to ensure the reciever email matches this
  // to confirm the $ has come through to us not someone else
  $payPalEmail = "";

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
    system('echo \'{"accomodationGuests":[], "registrants":[], "conferenceDinner":[]"}\' > ../../db.json');
  }

  if (strcmp ($res, "VERIFIED") !== false) {

    // Get POST data
    $payerId            = $_POST['payer_id'];
    $date               = $_POST['payment_date'];         
    $firstName          = $_POST['first_name'];
    $lastName           = $_POST['last_name'];
    $email              = $_POST['payer_email'];
    $transactionId      = $_POST['txn_id'];
    $paymentStatus      = $_POST['payment_status'];
    $verifySign         = $_POST['verify_sign'];
    $itemName           = $_POST['item_name'];
    $itemId             = $_POST['item_number'];
    $dietaryPreferences = $_POST['option_selection1'];

    // Create orderId
    $orderId = date('Y') * 5 . substr($payerId, 0, 3) . date('is') . substr($payerId, -3);

    $newConferenceDinner  = array(
      'payerId'            => $payerId,
      'orderId'            => $orderId,
      'date'               => $date,
      'firstName'          => $firstName,
      'lastName'           => $lastName,
      'email'              => $email,
      'transactionId'      => $transactionId,
      'paymentStatus'      => $paymentStatus,
      'verifySign'         => $verifySign,
      'itemName'           => $itemName,
      'itemId'             => $itemId,
      'dietaryPreferences' => $dietaryPreferences
    );

    $replyToEmail = "info@yrm2015.co.uk";

    function addConferenceDinner($newConferenceDinner) {
      // Database file
      $db_file_path = '../../db.json';

      // Get current database
      $db_file = fopen($db_file_path, 'r+');

      if(flock($db_file, LOCK_EX)) {

        // Convert the json db into a php array
        $db = json_decode(file_get_contents($db_file_path), true);

        // Add new data to database
        array_push($db['conferenceDinner'], $newConferenceDinner);
        
        // Rewrite and reencode the database file
        fwrite($db_file, json_encode($db));

        flock($db_file, LOCK_UN);

        return true;

      } else {
        return false;
      }
    }
    
    // Checks to see if the order id exists

    $ordIds = getConferenceDinnerOrderIds();

    for ($i=0; $i < count($ordIds); $i++) { 
      if($orderId === $ordIds[$i]) {
        mail($_POST['payer_email'], "YRM2015 Payment Error", "Hi, $firstName\r\n\r\nWe have recieved a payment notification from PayPal for your registration at the YRM2015 conference dinner, however it looks like the order ID has already been used. Please get in contact so we can investigate this and quote your order ID: $orderId. You can reply to this email to report this error.\r\n\r\nRegards,\r\n\r\nThe YRM2015 Team", "From: YRM2015 <info@yrm2015.co.uk>\r\nReply-To: $replyToEmail");
        exit();
      }
    }

    // Checks to see if the transaction id exists

    $transactionIds = getConferenceDinnerTransactionIds();

    for ($i=0; $i < count($transactionIds); $i++) { 
      if($transactionId === $transactionIds[$i]) {
        mail($_POST['payer_email'], "YRM2015 Payment Error", "Hi, $firstName\r\n\r\nWe have recieved a payment notification from PayPal for your registration at the YRM2015 conference dinner, however it looks like the transaction ID has already been used. This could mean your payment has come through twice in which case please check PayPal have not charged you twice. If this is the first email you have recieved from YRM2015, please get in contact so we can investigate this and quote your order ID: $transactionId. You can reply to this email to report this error.\r\n\r\nRegards,\r\n\r\nThe YRM2015 Team", "From: YRM2015 <info@yrm2015.co.uk>\r\nReply-To: $replyToEmail");
        exit();
      }
    }

    if($paymentStatus !== "Completed") {
      mail($_POST['payer_email'], "YRM2015 Payment Error", "Hi, $firstName\r\n\r\nWe have recieved a payment notification from PayPal for your registration at the YRM2015 conference dinner, however it looks like the payment is not completed. Please contact us so we can check whether your payment has cleared. You can reply to this email to report this error and quote your order ID: $orderId \r\n\r\nRegards,\r\n\r\nThe YRM2015 Team", "From: YRM2015 <info@yrm2015.co.uk>\r\nReply-To: $replyToEmail");
    }

    // If the file is in use sleep for 7
    // seconds then try again
    if(!addConferenceDinner($newConferenceDinner)) {
      sleep(7);

      if(!addConferenceDinner($newConferenceDinner)) {
        mail($_POST['payer_email'], "YRM2015 Processing Error", "Hi, $firstName\r\n\r\nSorry, something went wrong when trying to process your order. Your payment is fine however it may not have been recorded in our database. Please reply to this email to report this error and quote your order ID: $orderId \r\n\r\nRegards,\r\n\r\nThe YRM2015 Team", "From: YRM2015 <info@yrm2015.co.uk>\r\nReply-To: $replyToEmail");
      }
    } else {
      $count = countConferenceDinner();
      if($count <= 99) {
        mail($_POST['payer_email'], "YRM2015 Conference Dinner Confirmation", "Hi, $firstName\r\n\r\nWe can confirm that you have a place at the conference dinner option at YRM2015. Your dietary preferences are: $dietaryPreferences\r\n\r\nYour order ID is: $orderId\r\n\r\nRegards,\r\n\r\nThe YRM2015 Team", "From: YRM2015 <info@yrm2015.co.uk>\r\nReply-To: $replyToEmail");
      } else {
        mail($_POST['payer_email'], "YRM2015 Conference Dinner Confirmation", "Hi, $firstName\r\n\r\nAt present the conference dinner option at YRM2015 is fully booked. We hope to release more places soon, and we can confirm that you are on the waiting list. Your dietary preferences are: $dietaryPreferences\r\n\r\nYour order ID is: $orderId\r\n\r\nRegards,\r\n\r\nThe YRM2015 Team", "From: YRM2015 <info@yrm2015.co.uk>\r\nReply-To: $replyToEmail");
      }
    }

  } 

  else if (strcmp ($res, "INVALID") !== false) {

  // Tries to contact the email in the POST request
    if(isset($_POST['payer_email'])) {
    	mail($_POST['payer_email'], "YRM2015 Invalid Purchase", "Hi, \r\n\r\nWe have received an invalid payment request from this email address via PayPal. If you have tried to purchase a conference dinner ticket recently your payment pay not have come through.\r\n\r\nIf this is the case, please reply to this email to contact our support line.\r\n\r\nRegards,\r\n\r\nThe YRM2015 Team", "From: YRM2015 <info@yrm2015.co.uk>\r\nReply-To: $replyToEmail");
    }
  }

	fclose($fp);

?>