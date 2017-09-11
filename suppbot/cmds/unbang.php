<?php

	extract($GLOBALS);
	$class->checkPARTS(1, "nick/numer użytkownika do zbanowania");
	
	$bgt = $parts;
	unset($bgt[0], $bgt[1]);
	$bgt = trim(implode(' ', $bgt));
	
	$class->checkUSER($parts[1]);
	$userDane = $class->userDANE($parts[1]);
	
	$class->checkBANG($userDane['numer'], 1);
	
	$q = $gdbase->query("DELETE FROM `bans` WHERE `numer` = '{$userDane['numer']}'");
	$class->checkQUERY($q);
	
	if($q === TRUE)
	{
		$class->wiad("Zostałeś(aś) globalnie odbanowany(a)!\r\n[l]\r\nOdbanowany(a) przez: {$nickk}!", $userDane['numer']);
		die($class->msg("Pomyślnie odbanowano globalnie użytkownika {$userDane['nick']}"));
	}

?>