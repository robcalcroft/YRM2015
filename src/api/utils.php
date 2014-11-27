<?php

	function sendJson($response, $callback) {
		echo json_encode($response);
		if(!is_null($callback))$callback();
	}

?>