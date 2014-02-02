<?php
session_start();
if (empty($_SESSION['user'])){
	header("Location : /qrassassin");
	die("Not Logged In");
}
require("connect_to_database.php");
$uname = $_SESSION['user'];
//sets the quitter's killer's target id to the quiters target i

$result = mysqli_query($conn, "SELECT p.player_id, p.target_id, p.game_id, g.started
								FROM players p INNER JOIN games g
								ON p.game_id = g.game_id
								WHERE username = '$uname';");
$playerInfo = mysqli_fetch_assoc($result);

$uId = $playerInfo['player_id'];
$tId = $playerInfo['target_id'];
$gId = $playerInfo['game_id'];
$isStart = $playerInfo['started'];


mysqli_query($conn, "UPDATE players 
					SET target_id = '$tId'
					WHERE target_id = '$uId';");

mysqli_query($conn, "UPDATE players 
					SET game_id = NULL, target_id=NULL, kill_code=NULL
					WHERE username = '$uname';");



if ($isStart == 1){
mysqli_query($conn, "INSERT INTO kills (killer_id, victim_id, game_id)
					VALUES ('$uId','$uId','$gId');");
}

header("Location: /qrassassin");
exit;
?>r("Location: /qrassassin");
exit;
?>
?>
}

header("Location: /qrassassin");
exit;
?>