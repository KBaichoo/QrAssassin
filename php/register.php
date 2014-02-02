<?php 
require("connect_to_database.php");
if(!preg_match("/^[a-zA-z0-9]*$/", $_POST['uname']))
	die("invalid characters in username");
if(!preg_match("/^[a-zA-z0-9]*$/", $_POST['pword']))
	die("invalid characters in password");

$uname = mysqli_real_escape_string($conn, $_POST['uname']);
$pword = mysqli_real_escape_string($conn, $_POST['pword']);
$fname = mysqli_real_escape_string($conn, $_POST['fname']);

$isTakenResult = mysqli_query($conn, "SELECT username FROM players WHERE username = '$uname';");
$isTaken = mysqli_fetch_assoc($isTakenResult);

if (!empty($isTaken))
die("username taken");
if (strlen($uname) >= 20)
die("username too long (20 character limit)");
$pword = md5($pword);

mysqli_query($conn, "INSERT INTO players (username, password, full_name) VALUES ('$uname', '$pword', '$fname');");
header("Location: /qrassassin");
exit;
?>