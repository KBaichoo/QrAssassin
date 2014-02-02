<?
	session_start();
	if (empty($_SESSION['user'])){
		header("Location : /qrassassin");
		die("Not Logged In");
	}



require("connect_to_database.php");


$name = mysqli_real_escape_string($conn, $_POST['gameName']);
$pass = $_POST['gamePass'];


$result = mysqli_query($conn, "SELECT * FROM games WHERE name = '$name';");
$game = mysqli_fetch_assoc($result);
$timeResult = mysqli_query($conn, "SELECT NOW() FROM DUAL;");
	$time = mysqli_fetch_array($timeResult);
	$time = $time[0];
	$time = strtotime($time);

	echo $time;
	echo count($game);
	echo $name;

if (strtotime($game['start_reg'])>$time)
	die('Registration is not open yet');
if (strtotime($game['end_reg'])<$time)
	die('Registration is closed');



if ($game['password'] == $pass){
	$uname = $_SESSION['user'];
	$gameId = $game['game_id'];
	mysqli_query($conn, "UPDATE players SET game_id='$gameId' WHERE username = '$uname';");
}else{
die('Invalid Name/Password');
}
header("Location: /qrassassin");
exit;
?>