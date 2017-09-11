<?php

	extract($GLOBALS);
	
	if(!in_array($parts[1], array("new", "lista", "n", "l", "pokaz", "reply", "p", "r")))
	{
		die($class->msg("Witaj w centrum zgłoszeń sieci CzatyGG.pl!\r\n[l]\r\n• {$parts[0]} new (n) - Tworzy nowe zgłoszenie.\r\n• {$parts[0]} lista (l) - Pokazuje liste zgłoszeń.\r\n• {$parts[0]} pokaz (p) - Pokazuje dane zgłoszenie.\r\n• {$parts[0]} reply (r) - Odpowiada na zgłoszenie.\r\n[l]\r\nLitery w nawiasie oznaczają skróty komend!"));
	}

	if($parts[1] == 'new' OR $parts[1] == 'n')
	{
		if(!isset($parts[2]))
		{
			die($class->msg("Poprawne użycie komendy: {$parts[0]} {$parts[1]} [temat(max 5 słów)]|[treść]\r\nPrzykład: {$parts[0]} {$parts[1]} Problem z komendą|Witam [...]"));
		}
		
		$temat = $parts;//ROZBICIE TEKSTU NA CZĘŚCI
		unset($temat[0], $temat[1]);
		$temat = trim(implode(' ', $temat));
		
		$ntemat = explode('|',$temat);
		$nt = $ntemat[0];
		$tt = $ntemat[1];
		$ntemat = trim(implode(' ', $ntemat));
		
		$tematp = explode(' ', $nt);//LICZENIE SŁÓW W TEMACIE
		$iltemat = count($tematp);
		$tematp = trim(implode(' ', $tematp));
		
		if($iltemat > 5)
		{
			die($class->msg("Temat zgłoszenia może mieć tylko 5 słów!"));
		}
		
		$class->newSuppRequest($from, $nt, $tt);
	}
	if($parts[1] == 'lista' OR $parts[1] == 'l')
	{
		$class->showusrSuppRequests($from);
	}
	if($parts[1] == 'pokaz' OR $parts[1] == 'p')
	{
		$class->checkPARTS(2, "id zgłoszenia, które chcesz zobaczyć");
		
		$class->showusrSuppRequest($parts[2]);
	}
	if($parts[1] == 'reply' OR $parts[1] == 'r')
	{
		$class->checkPARTS(2, "id zgłoszenia, na które chcesz odpowiedzieć");
		$class->checkPARTS(3, "treść odpowiedzi");
		$rtresc = $parts;
		unset($rtresc[0], $rtresc[1], $rtresc[2]);
		$rtresc = trim(implode(' ', $rtresc));
		
		$class->replyusrSuppRequest($from, $parts[2], $rtresc);
	}
	
?>