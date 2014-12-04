<?php

	function sendJson($response, $callback) {
		echo json_encode($response);
		if(!is_null($callback))$callback();
	}

	function getOrderIds() {
		$db = json_decode(file_get_contents('../../db.json'), true);

		$registrants = $db['registrants'];

		$orderIds = array();

		for ($i=0; $i < count($registrants); $i++) { 
			
			array_push($orderIds, $registrants[$i]['orderId']);

		}

		return $orderIds;
	}

	function getTransactionIds() {
		$db = json_decode(file_get_contents('../../db.json'), true);

		$registrants = $db['registrants'];

		$transactionIds = array();

		for ($i=0; $i < count($registrants); $i++) { 
			
			array_push($transactionIds, $registrants[$i]['transactionId']);

		}

		return $transactionIds;
	}

?>