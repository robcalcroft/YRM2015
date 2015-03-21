<?php

	function sendJson($response, $callback) {
		echo json_encode($response);
		if(!is_null($callback))$callback();
	}

	function getRegistrantOrderIds() {
		$db = json_decode(file_get_contents('../../db.json'), true);

		$registrants = $db['registrants'];

		$orderIds = array();

		for ($i=0; $i < count($registrants); $i++) { 
			
			array_push($orderIds, $registrants[$i]['orderId']);

		}

		return $orderIds;
	}

	function getAccomodationOrderIds() {
		$db = json_decode(file_get_contents('../../db.json'), true);

		$accomodationGuests = $db['accomodationGuests'];

		$orderIds = array();

		for ($i=0; $i < count($accomodationGuests); $i++) { 
			
			array_push($orderIds, $accomodationGuests[$i]['orderId']);

		}

		return $orderIds;
	}

	function getConferenceDinnerOrderIds() {
		$db = json_decode(file_get_contents('../../db.json'), true);

		$conferenceDinner = $db['conferenceDinner'];

		$orderIds = array();

		for ($i=0; $i < count($conferenceDinner); $i++) { 
			
			array_push($orderIds, $conferenceDinner[$i]['orderId']);

		}

		return $orderIds;
	}

	function countConferenceDinner() {
		$db = json_decode(file_get_contents('../../db.json'), true);

		return count($db['conferenceDinner']);
	}

	function getRegistrantTransactionIds() {
		$db = json_decode(file_get_contents('../../db.json'), true);

		$registrants = $db['registrants'];

		$transactionIds = array();

		for ($i=0; $i < count($registrants); $i++) { 
			
			array_push($transactionIds, $registrants[$i]['transactionId']);

		}

		return $transactionIds;
	}

	function getAccomodationTransactionIds() {
		$db = json_decode(file_get_contents('../../db.json'), true);

		$accomodationGuests = $db['accomodationGuests'];

		$transactionIds = array();

		for ($i=0; $i < count($accomodationGuests); $i++) { 
			
			array_push($transactionIds, $accomodationGuests[$i]['transactionId']);

		}

		return $transactionIds;
	}

	function getConferenceDinnerTransactionIds() {
		$db = json_decode(file_get_contents('../../db.json'), true);

		$conferenceDinner = $db['conferenceDinner'];

		$transactionIds = array();

		for ($i=0; $i < count($conferenceDinner); $i++) { 
			
			array_push($transactionIds, $conferenceDinner[$i]['transactionId']);

		}

		return $transactionIds;
	}

?>