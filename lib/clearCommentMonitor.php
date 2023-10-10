<?php 

/**
* @author : Teddy Patriarca
* @uses : Clear the number of comments allowed per ip.
*/

include('../config.php');

$statement = $db->prepare("DELETE FROM userIpCommentMonitor");
$result = $statement->execute();
?>