<?php
//The only role of index is to launch the controller and call the run function.
    require('finalController.php');
	$controller = new finalController();
	$controller->run();
?>
