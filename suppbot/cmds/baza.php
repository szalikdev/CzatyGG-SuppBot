<?php

	extract($GLOBALS);
	$parts[1] = strtolower($parts[1]);
	
	$class->checkexactPARTS(1, "dodaj", "usun");
	$class->checkPARTS(2, "nazwę komendy");	

	if($parts[1] == 'dodaj')
	{
		$class->checkPARTS(3, "staff dla komendy");
		$class->dodajCMD($parts[2], $parts[3]);
	}
	
	if($parts[1] == 'usun')
	{
		$class->usunCMD($parts[2], $parts[3]);
	}
?>