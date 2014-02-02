<?php
	session_start();
	require("php/connect_to_database.php");

	if (!isset($_SESSION['user']))
		require("html/loginScreen.php");
	else
		require("html/home.php");
?>
	<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
	<a class="domainAd" href="http://www.freedomain.co.nr/" target="_blank" title="Free Domain"><img src="http://usrmsna.imdrv.net/coimage.gif" width="88" height="31" border="0" alt="Free Domain" /></a>
