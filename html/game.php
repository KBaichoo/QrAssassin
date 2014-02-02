<script type = "text/javascript">
	function joinGame(name, entryCode){
		$.post("php/joinGame.php", {
			gameName: name,
			gamePass: entryCode
		},function(data){
			alert(data);
		});
		
		return false;
	
	}
</script>
<?php

	

	$result = mysqli_query($conn, "SELECT * FROM players p INNER JOIN games g ON p.game_id = g.game_id WHERE p.username =  '$uname';");

	$game = mysqli_fetch_assoc($result);

	if (empty($game)){?>

	

	<form id="joinGameForm" method="POST" onsubmit = "return joinGame(this.elements[0],this.elements[1]);">

	<input type="text" name="gameName" placeholder="Game Name">

	<input type="text" name="gamePass" placeholder="Entry Code">

	<input type="submit" value="Join">

	</form>

	

	<?

	}else{

		if($game['started'] == 1){

		//kill log 

?>

			<h3>Recent Kills</h3>

			<div class="killLog">

				<table>

					<thead>

						<tr>

							<td>Time</td>

							<td>Killer</td>

							<td>Victim</td>

						</tr>

					</thead>

					<tbody>

		<?php

		$gameId = $game['game_id'];

		$killLog = mysqli_query($conn,"

		SELECT l.time, k.full_name \"killer\", v.full_name \"victim\"

		FROM kills l INNER JOIN players k ON l.killer_id = k.player_id INNER JOIN players v ON l.victim_id = v.player_id

		WHERE l.game_id = '$gameId'

		ORDER BY l.time DESC

		");

		$logLength = mysqli_num_rows($killLog);

		for ($i = 0;$i<$logLength;$i++){

			$kill = mysqli_fetch_assoc($killLog);

?>

				<tr>

					<td><?php echo date("m/d g:ia",strtotime($kill['time'])); ?></td>

					<td><?php echo $kill['killer']; ?></td>

					<td><?php echo $kill['victim']; ?></td>

				</tr>

<?php

		}

?>

				</tbody>

				</table>

			</div>

				<a href="php/leaveGame.php">Leave Game</a>



<?php

	}else{

?>

	<div class="gameInfo">Start Registration:<br> <?php echo $game['start_reg'];?></div>

	<div class="gameInfo">End Registration:<br> <?php echo $game['end_reg'];?></div>

	<div class="gameInfo">Start Game:<br> <?php echo $game['start_game'];?></div>

	<a href="php/leaveGame.php">Leave Game</a>







<?php

	}	

}

?>