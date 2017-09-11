<?php

	extract($GLBOALS);
	
	$class->checkexactPARTS(1, "tak", "nie");
	
	if($parts[1] == "tak")
	{
		$class->edytujUSER($from, "zgoda", "tak", 0);
		die($class->msg("Pomyślnie wyrażono zgodę na odbieranie wiadomości globalnych!"));
	}
	if($parts[1] == "nie")
	{
		$class->edytujUSER($from, "zgoda", "nie", 0);
		die($class->msg("Pomyślnie wyrażono zgodę na odbieranie wiadomości globalnych!"));
	}

?>