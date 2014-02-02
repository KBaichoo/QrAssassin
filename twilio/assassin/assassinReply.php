<?php 
	include('./inc/dbconnect.php');
	include('./inc/parse.php');
	include ('./inc/outbound.php');
	
	/**
	 *	$response will hold what we will send to the Texter.
	 *	If the person texting isn't registered with a number, then we return nothing.
	 */
	$response = "";
	
	$text = parseURL($_REQUEST['Body']);
	$number = $_REQUEST['From'];
	
	/**
	 *	Cases:
	 *	Who is my target ?  
	 *	QR Code
	 *	Empty Results => not a person. AND the persons target_qr code will be null thus, after this initial info nothing else is needed.
	 */
	
	//make assoc
	$query = "SELECT Killer.phone,Killer.player_id 'killer_id',Victim.player_id,Victim.kill_code,Killer.full_name,Killer.game_id 
	FROM players Killer JOIN players Victim ON Killer.target_id = Victim.player_id 
	WHERE Killer.phone='{$number}'";
	
	$result = mysqli_query($conn,$query);
	$result = mysqli_fetch_array($result);
	
	
	
	if(empty($result)){
		$query = "Select full_name from players where phone = '{$number}'";
		$isRegistered = mysqli_query($conn,$query);
		$isRegistered = mysqli_fetch_array($isRegistered);
		if(!empty($isRegistered)){
			$response = $isRegistered[0] . " you have no target.";
		}
		// Number isn't in database and thus doesn't have the right to query
		// or the player is DEAD thus their target_id is null 
	}else{
		// Variables to store data from the $result Assoc Array
		$killerPhone = $result['phone'];
		$killerId = $result['killer_id'];
		$killerName = $result['full_name'];
		$gameId = $result['game_id'];
		$victimId = $result['player_id'];
		$victimCode = $result['kill_code'];
		
		if(preg_match('/my target/i',$text)){
			//If request target
			$queryTarget = "SELECT full_name FROM players WHERE player_id='{$victimId}'";
			$queryResult =	mysqli_query($conn,$queryTarget);
			$queryResult = mysqli_fetch_row($queryResult);
			$response = "\n" . $queryResult[0] . " is the name of your target";
		}else{
			$patternToTest = "/" . substr($victimCode,0,10) . "/i";
			if($text != $_REQUEST['Body']){
				$patternToTest = "/" . $victimCode . "/i";
			}
			
			if(preg_match($patternToTest,$text)){
				
				/**
					Gets the new Target
					['target_id'] -> the new target
					['full_namee'] -> the new targets name
					['who_died'] -> the person in $victimId(killed)
					[phone] ->number of the victim.
				*/
				$getNewTarget = "SELECT killer.target_id,killer.full_name 'who_died',victim.full_name,killer.phone FROM players killer JOIN players victim ON 
				killer.target_id = victim.player_id WHERE killer.player_id='{$victimId}';";
				$newTarget = mysqli_query($conn,$getNewTarget);
				$newTarget = mysqli_fetch_array($newTarget);
				//Assigns new target

				mysqli_query($conn,"UPDATE players SET target_id='{$newTarget['target_id']}' WHERE player_id='{$killerId}'");
				
				//Updates the person killed and records kill
				mysqli_query($conn,"UPDATE players SET game_id = NULL, target_id = NULL, kill_code=NULL WHERE player_id='{$victimId}'");
				mysqli_query($conn,"INSERT INTO kills (killer_id, victim_id, game_id) VALUES ('{$killerId}', '{$victimId}', '{$gameId}')");

				// If the person killed has a phone we text them.
				if(!is_null($newTarget['phone'])){
					$victimCleanNum = ltrim($newTarget['phone'],'+1');
					$deathNote = "You have been Killed By " . $killerName;
					sendOutboundOnlyMessage($victimCleanNum,$deathNote);
				}
				
				$response = "You killed:" . $newTarget['who_died']  . ".Your new target is " . $newTarget['full_name'] . ".";
				//If the person won the game
				if($newTarget['target_id'] == $killerId){
					$response = "Congradulations! Your a weiner!";
					mysqli_query($conn,"UPDATE players SET game_id = NULL, target_id = NULL, kill_code=NULL WHERE player_id='{$killerId}'");
					mysqli_query($conn,"UPDATE games SET winner='{$killerId}' WHERE game_id = '{$gameId}' ");
				}
				
			}else{
				$response = "You failed to kill your target.";
			}
		}
	}
	ob_clean();
	
	
	/**
	*	$_REQUEST - Superglobal that contains info abou the message recieved.
	*	$_SESSION - Superglobal that can store temp data for up to 4 hrs.
	*	
	*/
	header('content-type: text/xml');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
	<Sms><?php echo $response; ?></Sms>
</Response>