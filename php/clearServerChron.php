<?php
    require("./connect_to_database.php");
    mysqli_query($conn,"Update servers SET hits = 0");
?>