<?php

	extract($GLOBALS);
	$class->checkPARTS(1, "nazwę komendy, do której chcesz dodać skrót");
	$class->checkPARTS(2, "staff komendy, który chcesz ustawić");
	
	$edytujCMD = $class->edytujCMD($parts[1], "staff", $parts[2]);
?>