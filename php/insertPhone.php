<?php
	session_start();
	if (empty($_SESSION['user'])){
		header("Location : /qrassassin");
		die("Not Logged In");
	}
	$uname = $_SESSION['user'];
	$phoneNum = $_SESSION['phone'];
	$code = $_SESSION['code'];

	if ($_POST['code'] != $code){
		setcookie("Invalid_Code","Your Code is Wrong!",time() + 1,"/qrassassin/html");
		header("Location: /qrassassin/html/phoneBind.php");
		exit;
	}


	if (strlen($phoneNum) == 10)
		$phoneNum = "+1" . $phoneNum;
	if (strlen($phoneNum) == 11)
		$phoneNum = "+" . $phoneNum;


	require("./connect_to_database.php");

	mysqli_query($conn, "UPDATE players SET phone = '{$phoneNum}' WHERE username = '{$uname}'");

	unset($_SESSION['code']);
	unset($_SESSION['phone']);
	header("Location: /qrassassin/html/phoneBind.php");
	exit;
?>