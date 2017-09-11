<?php
	extract($GLOBALS);
	
	$ccgq = $dbase->query("SELECT * FROM `users` WHERE `staff` = '3' ORDER BY `id` DESC");
	$class->checkQUERY($ccgq);
	while($ccgw = $ccgq->fetch_assoc())
	{
		$z = $class->nick($ccgw['nick'], $ccgw['staff']);
		//Właściciel(e) Sieci
		$ccg .= "~ {$z} - Staff: {$ccgw['staff']}\r\n";
	}
	
	$mccgq = $dbase->query("SELECT * FROM `users` WHERE `staff` = '2' ORDER BY `id` DESC");
	$class->checkQUERY($mccgq);
	while($mccgw = $mccgq->fetch_assoc())
	{
		$z = $class->nick($mccgw['nick'], $mccgw['staff']);
		//Właściciel(e) Sieci
		$mccg .= "~ {$z} - Staff: {$mccgw['staff']}\r\n";
	}
	
	$jmccgq = $dbase->query("SELECT * FROM `users` WHERE `staff` = '1' ORDER BY `id` DESC");
	$class->checkQUERY($jmccgq);
	while($jmccgw = $jmccgq->fetch_assoc())
	{
		$z = $class->nick($jmccgw['nick'], $jmccgw['staff']);
		$jmccg .= "~ {$z} - Staff: {$jmccgw['staff']}\r\n";
	}
	
	if($jmccgq->num_rows === 0)
	{
		$jmccg = "~ BRAK";
	}
	
	$obsluga = "~> Obłsuga SuppBota:\r\n[l]\r\n> Właściciel(e) Sieci: \r\n{$ccg}\r\n> Moderatorzy Sieci: \r\n{$mccg}\r\n> Junior Moderatorzy Sieci: \r\n{$jmccg}";
	
	$class->msg($obsluga);
?>