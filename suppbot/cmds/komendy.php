<?php

	extract($GLOBALS);
	$q = $dbase->query("SELECT * FROM `komendy` WHERE `staff` = '0' ORDER BY `staff` ASC");
	$class->checkQUERY($q);
	while($z = $q->fetch_assoc())
	{		
		if($z['skrot'] != '0')
		{
			$skrot = "/".$z['skrot'];
		}
		else
		{
			$skrot = "BRAK";
		}
		
		$nazwa = $z['nazwa'];
		$opis = $z['opis'];
		
		$cmds .= "~> /".$nazwa."\r\n> Skrót: ".$skrot."\r\n> Opis: ".$opis."\r\n";
	}
	if($user['staff'] > 0)
	{
		$cmdlist = "~> Oto lista dostępnych komend:\r\n[l]\r\n".$cmds."[l]\r\nKomendy dla Obsługi, dostępne są pod /komendy2!\r\n";
	}
	else
	{
		$cmdlist = "~> Oto lista dostępnych komend:\r\n[l]\r\n".$cmds;
	}
	
	$class->msg($cmdlist);
?>