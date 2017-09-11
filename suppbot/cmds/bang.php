<?php

	extract($GLOBALS);
	$class->checkPARTS(1, "nick/numer użytkownika do zbanowania");
	$class->checkPARTS(2, "powód globalnego bana");
	
	$bgt = $parts;
	unset($bgt[0], $bgt[1]);
	$bgt = trim(implode(' ', $bgt));
	
	$class->checkUSER($parts[1]);
	$userDane = $class->userDANE($parts[1]);
	
	$class->checkBANG($userDane['numer'], 2);
	
	if($userDane['numer'] == $NrSkr)
	{
		$class->wiad("Stwórco, użytkownik {$nickk}(GG:{$from}) próbował zbanować Cię globalnie!", $NrSkr);
		die($class->msg("Przepraszam, nie możesz zbanować mojego stwórcy. Wysłałem do niego informację o tej próbie. :)"));
	}
	
	$q = $gdbase->query("INSERT INTO `bans` (`numer`, `reason`, `banby`) VALUES ('{$userDane['numer']}', '{$bgt}', '{$nickk}')");
	$class->checkQUERY($q);
	
	if($q === TRUE)
	{
		$class->wiad("Zostałeś(aś) globalnie zbanowany(a)!\r\n[l]\r\nPowód: {$bgt}\r\nZbanowany(a) przez: {$nickk}!", $userDane['numer']);
		die($class->msg("Pomyślnie zbanowano globalnie użytkownika {$userDane['nick']}"));
	}

?>