<?php

	extract($GLOBALS);
	$class->checkPARTS(1, "nazwę komendy, do której chcesz dodać skrót");
	$class->checkPARTS(2, "skrót komendy, który chcesz ustawić");
	
	$checkSKROT = $class->checkSKROT($parts[2]);
	if($checkSKROT == true)
	{
		die($class->msg("Przepraszam, podany przez Ciebie skrót, jest używany przez inną komendę!"));
	}
	
	$edytujCMD = $class->edytujCMD($parts[1], "skrot", $parts[2]);
	$class->checkQUERY($edytujCMD);
	if($edytujCMD === TRUE)
	{
		die($class->msg("Pomyślnie dodano skrót {$parts[2]} do komendy {$parts[1]}!"));
	}

?>