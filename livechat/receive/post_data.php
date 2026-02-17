<?php
require('sse.php');

$entityBody = file_get_contents('php://input');
error_log($entityBody);
sendMsg("1", "test entity");

?>