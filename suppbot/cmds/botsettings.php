<?php

	extract($GLOBALS);
	$class->checkPARTS(1, "ustawienie, które chcesz zmienić");
	$class->checkPARTS(2, "wartość ustawienia, które chcesz zmienić");
	
	$set = $parts;
	unset($set[0],$set[1]);
	$set = trim(implode(' ', $set));
	
	$class->edytujBOT($parts[1], $set);
?>