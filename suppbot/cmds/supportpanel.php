<?php

	extract($GLOBALS);
	
	if(!in_array($parts[1], array("reply", "close", "r", "c", "pokaz", "usun", "p", "u")))
	{
		die($class->msg("Witaj w panelu centrum zgłoszeń sieci CzatyGG.pl!\r\n[l]\r\n• {$parts[0]} reply (r) - Odpowiada na zgłoszenie.\r\n• {$parts[0]} close (c) - Zamyka zgłoszenie.\r\n• {$parts[0]} pokaz (p) - Pokazuje dane zgłoszenie.\r\n• {$parts[0]} usun (u) - Usuwa zgłoszenie.\r\n[l]\r\nLitery w nawiasie oznaczają skróty komend!"));
	}

	if($parts[1] == 'reply' OR $parts[1] == 'r')
	{
		$class->checkPARTS(2, "id zgłoszenia, na które chcesz odpowiedzieć");
		$class->checkPARTS(3, "treść odpowiedzi");
		$rtresc = $parts;
		unset($rtresc[0], $rtresc[1], $rtresc[2]);
		$rtresc = trim(implode(' ', $rtresc));
		
		$class->replyobSuppRequest($from, $parts[2], $rtresc);
	}
	
	if($parts[1] == 'close' OR $parts[1] == 'c')
	{
		$class->checkPARTS(2, "id zgłoszenia, które chcesz zamknąć");
		
		$class->closeSuppRequest($parts[2]);
	}
	
	if($parts[1] == 'pokaz' OR $parts[1] == 'p')
	{
		$class->checkPARTS(2, "id zgłoszenia, które chcesz zobaczyć");
		
		$class->showobSuppRequest($parts[2]);
	}
	
	if($parts[1] == 'usun' OR $parts[1] == 'u')
	{
		$class->checkPARTS(2, "id zgłoszenia, które chcesz usunąć");
		
		$class->deleteSuppRequest($parts[2]);
	}
	
?>