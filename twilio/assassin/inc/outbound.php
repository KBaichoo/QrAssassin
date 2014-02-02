<?php

function sendOutboundOnlyMessage($number, $message){
	$ch = curl_init();
	$textingService = "http://textbelt.com/text";
	
	$data = array(
			'number' => $number,
			'message' => $message	
	);
	
	
	// opens the url and sends the POST data
	curl_setopt($ch,CURLOPT_URL,$textingService);
	curl_setopt($ch,CURLOPT_POST,2);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	$result = curl_exec($ch);
	curl_close($ch);
	
	
}
?>