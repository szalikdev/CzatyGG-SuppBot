<?php

	extract($GLOBALS);
	$class->checkPARTS(1, "treść wiadomości globalnej");
	
	$txt = $parts;
	unset($txt[0]);
	$txt = trim(implode(' ', $txt));
	
	$q = $dbase->query("SELECT * FROM `users` WHERE `zgoda` = 'tak' AND `wypisz` = 0");
	$class->checkQUERY($q);
	$l = 1;
	while($r = $q->fetch_assoc())
	{
		$od[] = $r['numer'];
		$l++;
	}
	
	$class->wiad("Wiadomość globalna wysłana przez {$nickk}\r\n[l]\r\n{$txt}\r\n[l]\r\n> Jeśli nie wyrażasz zgody na otrzymywanie wiadomości globalnych, użyj komendy /zgoda nie :)", $od);
	$class->msg("Wiadomość dotarła do {$l} Osób! :)");
?>