<?php
session_start();
require("connect_to_database.php");


$uname = $_POST['uname'];
$pword = $_POST['pword'];

$result = mysqli_query($conn, "SELECT username, password FROM players WHERE username = '$uname';");
$user = mysqli_fetch_assoc($result);
if ($user['password'] == md5($pword))
$_SESSION['user'] = $user['username'];
else
die("Invalid Username or Password");

header("Location: /qrassassin");
exit;
?>