<?php
	session_start();
	if (empty($_SESSION['user'])){
	header("Location : /qrassassin");
	die("Not Logged In");
}
	$uname = $_SESSION['user'];

	require("../php/connect_to_database.php");

	$result = mysqli_query($conn, "SELECT * FROM players WHERE username = '$uname';");
	$userInfo = mysqli_fetch_assoc($result);
	if (empty($userInfo['kill_code'])){
		if (empty($userInfo['game_id']))
			echo "You are not in a game";
		else
			echo "The game hasn't started yet";
	}else{
?>
	<img src="http://api.qrserver.com/v1/create-qr-code/?data=http://www.qrassassin.co.nr/php/kill.php?kill_code=<?php echo $userInfo['kill_code'];?>">
	<br><span style="font: 46px Arial"class="killCode"><?php echo substr($userInfo['kill_code'],0,10);?></span>
<?php
}
?>