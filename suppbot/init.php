<?php
/**
*	Script: Support Bot
*	Version: 1.0.0
*	File: init.php
*	Author: Jakub "Jakso" Sochalec
*	Created: 18.08.2017
*	Last update: 21.08.2017
**/

//ERROR REPORTING
error_reporting(E_ALL);
ini_set("display_errors", 0);

//SOME VARIABLES
$vernr = "1.0.0";
$NrSkr = "63182428";
$networkname = "CzatyGG.pl";

//REQUIRED FILES
include_once("inc/Jakso.php"); //!!BOT HEART/CLASS FILE!!
include_once("inc/MessageBuilder.php");
include_once("inc/PushConnection.php");

//API VARIABLES
$ApiNmbr = "63501611";
$ApiLogin = str_rot13("wnxho@pmngltt.cy");
$ApiPass = str_rot13("wPCxqftnOZW9Bx6L");

//DON'T DELETE, THIS BOT WILL NOT WORK WITHOUT THIS VARIABLES!!
$p = new PushConnection($ApiNmbr, $ApiLogin, $ApiPass);
$m = new MessageBuilder();
$class = new czat();
$dbase = $class->dbConnect();
$gdbase = $class->globaldbConnect();

//BASIC VARIABLES
extract($_GET);
$from = $_GET['from'];
$parts = explode(' ', $msg);
$czas = time();
$msg = file_get_contents("php://input");
$user = $class->user($from);
$unick = $user['nick'];
if(!$user['nick'])//ZABEZPIECZENIE USUWAJĄCE BŁĄD PODCZAS REJESTRACJI
{
	$nickk = "";
}
else
{
	$nickk = $class->nick($user['nick'], $user['staff']);
}
$parts = explode(' ', $msg);
$linia = $class->bot("linia");

//BASIC SECURITY
if(strpos($_SERVER['HTTP_USER_AGENT'], "GG PeekBot" )===0)
{
	die;
}

$ip = $_SERVER['REMOTE_ADDR'];
if(!preg_match('/^91\.197\.15\.[0-9]{1,3}$/', $ip))
{
	die(header("location: http://czatygg.pl"));
}

$msg = str_ireplace(array("DROP","TRUNCATE","SELECT","INSERT","VALUE","VALUES","UPDATE","</body>","<body>","<html>","<?","?>"),'',$msg);
$msg = addslashes($msg);

//CHECK IF USER IS SET AS BOT
$class->checkisBOT($from);

//CHECK IF USER IS GLOBAL BANNED
$class->checkisGBANNED($from);

//CHECK IF USER IS REGISTERED
$q = $dbase->query("SELECT * FROM `users` WHERE `numer`= '{$from}' ");
if($q->num_rows == 0) //IF USER IS NOT REGISTERED MAKE AN ACCOUNT
{
	$class->msg("Witaj nieznajomy! Nazywam się Support i jestem botem :) A Ty jak się nazywasz? Podaj nick, którego chcesz używać :)");
	$dbase->query("INSERT INTO `users` (numer, nick) VALUES ('{$from}', '{$from}')");
	exit;
}
//CHECK IF USER CHOOSED HIS NICKNAME
if($from == $unick) //IF USER DIDN'T CHOOSED HIS NICKNAME YET
{
	$q = $dbase->query("SELECT * FROM `users` WHERE `nick`= '{$parts[0]}' ");
	if(strlen($parts[0]) < 3 || strlen($parts[0]) > 20)//IF NICK DOESN'T MATCH MORE THAN 3 LETTERS OR LESS THAN 20
	{
		die($class->msg("Wybacz ale nick musi mieć więcej niż 3 znaki oraz mniej niż 20! :("));
	}

	if(!strlen(preg_replace('/[a-zA-Zą-źĄ-Ź0-9-_łŁ]/', '', $parts[0])) == 0)//IF NICK HAVE SPECIAL LETTERS
	{
		die($class->msg("Wybacz ale nick nie może zawierać znaków specjalnych! :("));
	}

	if($q->num_rows != 0)//IF CHOSEN NICK IS ALREADY IN USE
	{
		die(msg("Wybacz ale wybrany przez Ciebie nick jest zajęty! :("));
	}
	$str = strlen(preg_replace('/\D*/', '', $parts[0]));

	if($str > 5)
	{
		die($class->msg("Wybacz ale możesz podać tylko 5 cyfr w nicku! :("));
	}
	$dbase->query("UPDATE `users` SET `nick` = '{$parts[0]}' WHERE `numer` = '{$from}'");
	$class->msg("Witaj {$parts[0]}! Miło mi Cię poznać! Aby uzyskać więcej informacji o mnie zapoznaj się z komendą /komendy!");
	exit;
}

//CHECK IF MESSAGE IS COMMAND
$cmd = strtolower($parts[0]);
$cmd = str_replace('.','',$cmd);
$cmd = str_replace('/','',$cmd);
if($parts[0] == '.' || $parts[0] == '/')
{
	die($class->msg("Przepraszam, ta komenda nie istnieje, może się pomyliłeś(aś)?\nSprawdź listę tutaj: /komendy :)"));
}

//IF MESSAGE IS COMMAND, READ FROM DATABASE
if(strpos($msg, '/') === 0 || strpos($msg, '.') === 0)
{
	if($cmd == "0")
	{
		die($class->msg("Przepraszam, musisz podać nazwę komendy! :("));
	}
	$q = $dbase->query("SELECT * FROM `komendy` WHERE `nazwa` = '{$cmd}' OR `skrot` = '{$cmd}'");
	$kom = $q->fetch_assoc();

	if($kom['staff'] > $user['staff'])
	{
		die($class->msg("Przepraszam, nie możesz użyć tej komendy! :(\r\nWymagany Staff: ".$kom['staff']."! :("));
	}

	if($q->num_rows != 0)
	{
		die(require_once("cmds/{$kom['nazwa']}.php"));
	}

	if($q->num_rows != 1)
	{
		die($class->msg("Przepraszam, ta komenda nie istnieje, może się pomyliłeś(aś)?\nSprawdź listę tutaj: /komendy :)"));
	}
	exit;
}

//DEFAULT MESSAGE
die($class->msg("~> Witaj na SuppBocie sieci {$networkname} :)\r\n> Aby uzyskać pomoc, zapoznaj się z komendą /komendy!"));

?>
