<?php
	session_start();
	if (empty($_SESSION['user'])){
		header("Location : /qrassassin");
		die("Not Logged In");
}
	require("../php/connect_to_database.php");
	
	/**
	 *	$_SESSION['requestBind'] is the counter of the number of texts they have requested.
	 *	We cap it at 3 and then set a timelimit when they can try again.
	 */
	if(!isset($_SESSION['requestBind'])){
		$_SESSION['requestBind'] = 0;
	}
	
	if($_SESSION['requestBind'] == 3){
		$_SESSION['timeLimit'] = time() + (3600 * 12);
	}
	
	
	// Gets Binded Phone Number
	$uname = $_SESSION['user'];

	$result = mysqli_query($conn, "SELECT phone FROM players WHERE username = '$uname';");
	$phoneArray = mysqli_fetch_assoc($result);
?>
	<link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
	<div class="content">
		<div class="info">
			After your phone is binded you:
			<ul>
				<li>Can text "my target" to 347-429-6872 to see your target</li>
				<li>Can text your targets qr code text to 347-429-6872 to kill your target</li>
				<li>Recieve a text message when you are killed</li>
			</ul>
		</div>
		<div class="currentPhone">
	<?php
		if (empty($phoneArray['phone'])){
			echo "No phone binded";
		} 
		else{
			echo $phoneArray['phone'] . " is binded to this account \n";
		}
		?>
		</div>
		<div class="pendingActivation">
		<?php
		if(isset($_COOKIE["Phone_Error"])){
			echo $_COOKIE["Phone_Error"];
		}else{
			if(!empty($_COOKIE['phone'])){
			echo $_COOKIE['phone'];
			}
		}
	?>
		</div>
	<?php
		if(isset($_SESSION['timeLimit']) && $_SESSION['timeLimit'] > time()){
			echo 'Try phonebinding again tomorrow.';
		}else{
			echo	'<form class="askForText" action="../php/sendCode.php" method="POST">
				<input type="text" placeholder="Phone Number" name="phone">
				<input type="submit" value="Send Code">
				</form>';	
		}
		
		if(isset($_COOKIE["Invalid_Code"])){
			echo $_COOKIE["Invalid_Code"];
		}
	?>	


		<form class="phoneBind" action="../php/insertPhone.php" method="POST">
			<input type="text" placeholder="Code" name="code" method="POST">
			<input type="submit" value="Bind Phone">
		</form>

		<a href="/qrassassin/">Back</a>
	</div>
