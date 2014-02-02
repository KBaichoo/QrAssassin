<?
	require("connect_to_database.php");
	$result = mysqli_query($conn, "SELECT * FROM games WHERE ISNULL(winner) AND NOT(started);");

	$timeResult = mysqli_query($conn, "SELECT NOW() FROM DUAL;");
	$time = mysqli_fetch_array($timeResult);
	$time = $time[0];
	$time = strtotime($time);
	
	$numRows = mysqli_num_rows($result);

	// loops thorugh that aren't over (don't have a winner)
	for ($i=0;$i<$numRows; $i++){
		
		$row = mysqli_fetch_assoc($result);
		print_r($row);
		$startTime = strtotime($row['start_game']);
	echo $startTime ."<".$time;

		//if the game isn't started, and should have started
		if($time>= $startTime && $row['started'] == 0){
			$gameId = $row['game_id'];
			$players = mysqli_query($conn, "SELECT * FROM players WHERE game_id = $gameId;");
			$numPlayers = mysqli_num_rows($players);
			//loop through all players registered for that game and assing kill codes
			$playersArray = array();
			for ($i=0;$i<$numPlayers;$i++){
				$player = mysqli_fetch_assoc($players);
				$playerId = $player['player_id'];
				//generate a kill code
				//KC = randomNum.username.gameid -> MD5-ed
				$playerKC = md5(rand(1,1000) . $player['username'] . $gameId);
				//sets player killcode in the table
				mysqli_query($conn, "UPDATE players SET kill_code = '$playerKC' WHERE player_id='$playerId';");
				$playersArray[$i] = $player;
			}
			print_r($playersArray);
			foreach ($playersArray as $key => $player) {
				$playerId = $player['player_id'];
				if ($key+1>=count($playersArray))
					$targetId = $playersArray[0]['player_id'];
				else
					$targetId = $playersArray[$key+1]['player_id'];
				mysqli_query($conn, "UPDATE players SET target_id = '$targetId' WHERE player_id='$playerId';");
			}


			mysqli_query($conn, "UPDATE games SET started = 1 WHERE game_id='$gameId';");
		}



	}

?>);
		}



	}

?>