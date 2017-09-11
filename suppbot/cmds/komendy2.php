<?php

	extract($GLOBALS);
	$q = $dbase->query("SELECT * FROM `komendy` WHERE `staff` != 0 ORDER BY `staff` ASC");
	$class->checkQUERY($q);
	while($z2 = $q->fetch_assoc())
	{		
		if($z2['skrot'] != '0')
		{
			$skrot = "/".$z2['skrot'];
		}
		else
		{
			$skrot = "BRAK";
		}
		
		$staff = $z2['staff'];
		$nazwa = $z2['nazwa'];
		$opis = $z2['opis'];
		
		//$cmds2 = "";
		$cmds2 .= "~> /".$nazwa."\r\n>Staff: ".$staff."\r\n> Skrót: ".$skrot."\r\n> Opis: ".$opis."\r\n";
	}
	$cmdlist = "~> Oto lista dostępnych komend obsługi:\r\n[l]\r\n".$cmds2."[l]\r\nJesteś w obsłudze więc używaj władzy rozsądnie!\r\n";
	
	$class->msg($cmdlist);
?>