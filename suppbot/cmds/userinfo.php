<?php

	extract($GLOBALS);
	if(!isset($parts[1]))
	{
		die($class->msg("Przepraszam, musisz podać nick lub numer użytkownika, którego chcesz sprawdzić!"));
	}
	
	$userDane = $class->userDANE($parts[1]);
	$checkBANG = $class->checkBANG($parts[1]);
	$checkUSER = $class->checkUSER($parts[1]);
	
	if($checkUSER == false)
	{
		die($class->msg("Przepraszam, podany użytkownik nie istnieje!"));
	}
	
	switch($userDane['wypisz'])
	{
		case 0:
			$wypisz = "NIE";
		break;
		case 1:
			$wypisz = "TAK";
		break;
	}
	
	switch($checkBANG)
	{
		case true:
			$bang = "TAK";
		break;
		case false:
			$bang = "NIE";
		break;
	}
	
	$ui = "Informacje o Użytkowniku {$userDane['nick']}:\r\n[l]\r\n> Numer: GG:{$userDane['numer']}\r\n> Zgoda na Globale: {$userDane['zgoda']}\r\n> Wypisany(a): {$wypisz}\r\n> Zbanowany(a) globalnie: {$bang}\r\n[l]\r\nInformacje zawarte w tej komendzie,\r\nnie podlegają udostępnianiu publicznemu!";
	$class->msg($ui);
?>