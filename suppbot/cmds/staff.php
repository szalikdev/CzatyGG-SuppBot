<?php

	extract($GLOBALS);
	
	$class->checkPARTS(1, "nick użytkownika");
	$class->checkPARTS(2, "staff dla podanego użytkownika");
	
	$checkUSER = $class->checkUSER($parts[1]);
	if($checkUSER == false)
	{
		die($class->msg("Przepraszam, podany użytkownik nie istnieje!"));
	}
	
	$tp = $parts;
	unset($tp[0], $tp[1], $tp[2]);
	$tp = trim(implode(' ', $tp));

	$userDane = $class->userDANE($parts[1]);
	
	switch($parts[2])
	{
		case 0:
			$info = "stracił(a)";
			$powod = $tp;
			switch($userDane['staff'])
			{
				case 1:
					$ranga = "Jr. Moderatora CCG";
				break;
				case 2:
					$ranga = "Moderatora CCG";
				break;
				case 3:
					$ranga = "Właściciela CCG";
				break;
			}
		case 1:
			if($userDane['staff'] == 2 OR $userDane['staff'] == 3)
			{
				$info = "został(a) zdegradowany(a) na";
				$ranga = "Junior Moderator CCG(Jr.MOD CCG™|)";
				$powod = $tp;
			}
			elseif($userDane['staff'] == 0)
			{
				$powod = $tp;
				$info = "otrzymał(a) awans na";
				$ranga = "Junior Moderator CCG(Jr.MOD CCG™|)";
			}
			else
			{
				$powod = $tp;
				$info = "otrzymał(a)";
				$ranga = "Junior Moderator CCG(Jr.MOD CCG™|";
			}
		break;
		case 2:
			if($userDane['staff'] == 3)
			{
				$info = "został(a) zdegradowany(a) na";
				$ranga = "Moderator CCG(MOD CCG™|)";
				$powod = $tp;
			}
			elseif($userDane['staff'] == 1)
			{
				$powod = $tp;
				$info = "otrzymał(a) awans na";
				$ranga = "Moderator CCG(MOD CCG™|)";
			}
			else
			{
				$powod = $tp;
				$info = "otrzymał(a)";
				$ranga = "Moderator CCG(MOD CCG™|";
			}
		break;
		case 3:
			if($userDane['staff'] == 2)
			{
				$info = "otrzymał(a) awans na";
				$ranga = "Właściciel Sieci(CCG™|)";
				$powod = $tp;
				
			}
			elseif($userDane['staff'] == 2)
			{
				$info = "otrzymał(a) awans na";
				$ranga = "Właściciel Sieci(CCG™|)";
				$powod = $tp;
			}
			else
			{
				$powod = $tp;
				$info = "otrzymał(a)";
				$ranga = "Właściciel Sieci(CCG™|)";
			}
		break;
	}
	
	if(!isset($powod))
	{
		$ppowod = "";
	}
	else
	{
		$ppowod = "\r\n~ Powód: ".$powod;
	}
	
	$class->wiad("{$nickk} ustawił Ci rangę: {$ranga}! :)", $userDane['numer']);
	$class->wiadglob($class->nick($userDane['nick'], $userDane['staff'])." właśnie {$info} rangę {$ranga}!{$ppowod}\r\n[l]\r\nNie możesz wyłączyć tej wiadomości gdyż jest to wiadomość informacyjna!");
	die($class->edytujUSER($parts[1], "staff", $parts[2]));
	
?>