<?php

include('config.php');
$ID = $_GET['id'];
mysqli_query($conn, "DELETE FROM list WHERE id=$ID");
header('location: list.php')

?>