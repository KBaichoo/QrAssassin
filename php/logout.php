<?php
	session_start();
	session_destroy();

	header("Location: /qrassassin");
	exit;

?>