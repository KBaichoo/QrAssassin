<?php
	session_start();
	if (empty($_SESSION['user'])){
		header("Location : /qrassassin");
		die("Not Logged In");
}
	$phoneNum = $_POST['phone'];
	
	//Test if Phone Number Legit
	if (preg_match('/\A[0-9]{9,10}\z/', $phoneNum) != 1){
		setcookie("Phone_Error","This is no a real phone number",time() + 1,"/qrassassin/html");
		header("Location: /qrassassin/html/phoneBind.php");
		exit;
	}
	
	//Tests if this request isn't being recorded.
	if(!isset($_SESSION['requestBind'])){
		header("Location: /qrassassin/html/phoneBind.php");
		exit;
	}
	
	
	$_SESSION['requestBind'] = $_SESSION['requestBind'] + 1;
	$_SESSION['phone'] = $phoneNum;
	
	setcookie("phone","Your code has been sent to " . $phoneNum . ".\nPlease be patient; it could take up to 2 minutes.",time() + 1,"/qrassassin/html");
	
	require("./connect_to_database.php");
	
	// Gets the server with less hits and updates that one's hit counter.
	$serverRequest = mysqli_query($conn,"SELECT url,hits FROM servers WHERE hits = (Select Min(hits) from servers);");
	$base = mysqli_fetch_assoc($serverRequest);
	$hits = $base['hits'] + 1;
	$base = $base['url'];
	
	mysqli_query($conn,"Update servers set hits={$hits} where url = '{$base}'");

	$randomNumber = rand(10000,99999);
	

	$qry_str = "?code=" . $randomNumber . "&phone=$phoneNum";
	$ch = curl_init();
	$fullStr = $base . $qry_str;
	
	// Set query data here with the URL
	curl_setopt($ch, CURLOPT_URL,$fullStr); 

	curl_setopt($ch, CURLOPT_HEADER, false);

	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:23.0) Gecko/20100101 Firefox/23.0');
	
	curl_setopt($ch,CURLOPT_COOKIEJAR,'text.txt');
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_COOKIESESSION, true);

	$content = curl_exec($ch);
	curl_close($ch);
	
	$_SESSION['code'] = $randomNumber;
	header("Location: /qrassassin/html/phoneBind.php");
	exit;
?>