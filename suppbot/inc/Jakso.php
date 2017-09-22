<?php
/**
*	Script: Support Bot
*	Version: 1.0.0
*	File: Jakso.php(class)
*	Author: Jakub "Jakso" Sochalec
*	Created: 19.08.2017
*	Last update: 11.09.2017
**/

class czat
{
	
	static $_mysql;
	static $_gmysql;
	public function dbConnect() //Funkcja odpowiadająca za połączenie z bazą danych
	{
		$dbhost = str_rot13("localhost"); // Serwer bazy danych MySQL zakodowany w ROT13 (funkcję można usunąć i podać bezpośrednie dane.)
		$dbuser = str_rot13("user"); // Użytkownik bazy danych MySQL zakodowany w ROT13 (funkcję można usunąć i podać bezpośrednie dane.)
		$dbpass = str_rot13("password"); // Hasło bazy danych MySQL zakodowane w ROT13 (funkcję można usunąć i podać bezpośrednie dane.)
		$dbname = str_rot13("database"); // Nazwa bazy danych MySQL zakodowana w ROT13 (funkcję można usunąć i podać bezpośrednie dane.)
		
		try
		{
			$_mysql = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		}
		catch (Exception $e) {
			return false;
		}
		return $_mysql;
	}
	
	public function globaldbConnect() //Funkcja odpowiadająca za połączenie się z globalną bazą danych sieci
	{
		$dbhost = str_rot13("localhost"); // Serwer globalnej bazy danych MySQL zakodowany w ROT13 (funkcję można usunąć i podać bezpośrednie dane.)
		$dbuser = str_rot13("user"); // Użytkownik globalnej bazy danych MySQL zakodowany w ROT13 (funkcję można usunąć i podać bezpośrednie dane.)
		$dbpass = str_rot13("password"); // Hasło globalnej bazy danych MySQL zakodowany w ROT13 (funkcję można usunąć i podać bezpośrednie dane.)
		$dbname = str_rot13("database"); // Nazwa globalnej bazy danych MySQL zakodowany w ROT13 (funkcję można usunąć i podać bezpośrednie dane.)
		
		try
		{
			$_gmysql = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		}
		catch (Exception $e) {
			return false;
		}
		return $_gmysql;
	}
	
	public function bot($column) // Funkcja pobierająca podaną kolumnę z tabeli `bot` w bazie danych
	{
		extract($GLOBALS);
		
		$q = $dbase->query("SELECT `{$column}` FROM `bot` LIMIT 1");
		if($q === FALSE && $from != $NrSkr)
		{
			die($class->msg("Błąd bazy danych, powiadom skryptera!"));
		}
		elseif($q === FALSE && $from == $NrSkr)
		{
			die($class->msg("MySQL Error: ".$dbase->error));
		}
		$q = $q->fetch_array();
		
		return $q[$column];
	}
	
	public function user($tresc) // Funkcja pobierająca dane podanego użytkownika
	{
		extract($GLOBALS);
		$q = $dbase->query("SELECT * FROM `users` WHERE `numer` = {$tresc} OR `nick` = {$tresc} LIMIT 1");
		$return = $q->fetch_assoc();
		return $return;
	}
	
	public function userDANE($nick) // Funkcja działa w taki sam sposób jak funkcja $class->user();
	{
		extract($GLOBALS);
		
		$ck = $class->checkUSER($nick);
		$class->checkQUERY($ck);
		if($ck == true)
		{
			$ud = $dbase->query("SELECT * FROM `users` WHERE `nick` = '{$nick}'");
			$q = $ud->fetch_assoc();
		}
		
		return $q;
	}
	
	public function msg($tresc) //Funkcja wysyłająca podaną wiadmośc do Osoby, która napisała do bota.
	{
		extract($GLOBALS);
		$tresc = str_replace("[l]", $linia, $tresc);
		$m->addText($tresc);
		$m->reply();
		$m->clear();
	}
	
	public function wiad($tresc, $do) //Funkcja wysyłająca podaną wiadomość do zdefiniowanyc Osób.
	{
		extract($GLOBALS);
		$tresc = str_replace("[l]", $linia, $tresc);
		$m->clear();
		$m->addText($tresc)->setRecipients($do);
		$p->push($m);
		$m->clear();
	}
	
	public function wiadglob($tresc) //Funkcja wysyłająca podaną wiadomość do wszystkich użytkowników bota, którzy mają włączoną zgodę
	{
		extract($GLOBALS);
		$tresc = str_replace("[l]", $linia, $tresc);
		$q = $dbase->query("SELECT * FROM `users` WHERE `zgoda` = 'tak' AND `wypisz` = 0");
		while($r = $q->fetch_assoc())
		{
			$do[] = $r['numer'];
		}
	
		$m->clear();
		$m->addText($tresc)->setRecipients($do);
		$p->push($m);
		$m->clear();
	}
	
	public function wiadob($tresc) //Funkcja wysyłająca podaną wiadomość do obsługi bota.
	{
		extract($GLOBALS);
		$tresc = str_replace("[l]", $linia, $tresc);
		$q = $dbase->query("SELECT * FROM `users` WHERE `staff` > 0");
		while($r = $q->fetch_assoc())
		{
			$do[] = $r['numer'];
		}
	
		$m->clear();
		$m->addText($tresc)->setRecipients($do);
		$p->push($m);
		$m->clear();
	}
	
	public function checkCMD($nazwa) //Funkcja sprawdzająca czy podana komenda istnieje.
	{
		extract($GLOBALS);
		$q = $dbase->query("SELECT * FROM `komendy` WHERE `nazwa` = '{$nazwa}'");
		
		if($q->num_rows != 1)
		{
			$return = false;
		}
		else
		{
			$return = true;
		}
		
		return $return;
	}
	
	public function checkSKROT($skrot) //Funkcja sprawdzająca czy podany skrót komendy istnieje.
	{
		extract($GLOBALS);
		$q = $dbase->query("SELECT * FROM `komendy` WHERE `skrot` = '{$skrot}'");
		
		if($q->num_rows != 1)
		{
			$return = false;
		}
		else
		{
			$return = true;
		}
		
		return $return;
	}
	
	public function checkPARTS($numer, $nazwa) //Funkcja sprawdzająca czy użytkownik podał podany parametr po nazwie komendy. (np. /komenda [parts1] [parts2])
	{
		extract($GLOBALS);
		
		if(!isset($parts[$numer]))
		{
			die($class->msg("Przepraszam, musisz podać {$nazwa}!"));
		}
	}
	
	public function checkexactPARTS($numer, $nazwa1, $nazwa2) //Funkcja sprawdzająca czy użytkownik podał jako pierwszy parametr komendy podaną wartość ($nazwa1 lub $nazwa2)
	{
		extract($GLOBALS);
		
		if(!in_array($parts[$numer], array($nazwa1,$nazwa2)))
		{
			die($class->msg("Przepraszam, musisz podać [{$nazwa1}] lub [{$nazwa2}] jako pierwszy parametr komendy!"));
		}
	}
	
	public function checkQUERY($query) //Funkcja sprawdzająca czy zapytanie MySQL wykonało się pomyślnie
	{
		extract($GLOBALS);
		
		if($query === FALSE && $from != $NrSkr)
		{
			die($class->msg("Błąd bazy danych, powiadom skryptera!"));
		}
		elseif($query === FALSE && $from == $NrSkr)
		{
			$query = "MySQL Error: ".$dbase->error;
		}
		
		return $query;
	}
	
	public function checkUSER($nick) // Funkcja sprawdzająca czy podany użytkownik istnieje w bazie danych
	{
		extract($GLOBALS);
		
		$q = $dbase->query("SELECT * FROM `users` WHERE `nick` = '{$nick}' OR `numer` = '{$nick}' LIMIT 1");
		
		if($q->num_rows != 1)
		{
			$return = false;
		}
		else
		{
			$return = true;
		}
		
		return $return;
	}
	
	public function checkCOLUMN($column, $table) //Funkcja sprawdzająca czy podany wiersz istnieje w podanej tabeli.
	{
		extract($GLOBALS);
		
		$q = $dbase->query("SELECT `{$column}` FROM `{$table}`");
		
		if($q->num_rows != 1)
		{
			$return = false;
		}
		else
		{
			$return = true;
		}
		
		return $return;
	}
	
	public function checkisBOT($numer) //Funkcja sprawdzająca czy podany użytkownik jest botem.
	{
		extract($GLOBALS);
		if($p->isBot($from) == true)
		{
			die($class->msg("Przepraszam, ale wykryłem że jesteś botem więc niestety nie mogę z Tobą rozmawiać gdyż zabronił mi tego mój stwórca. Możliwe że używasz autorespondera, jeśli tak, wyłącz go."));
		}
	}
	
	public function checkisGBANNED($gbnr) //Funkcja sprawdzająca czy podany użytkownik jest globalnie zbanowany.
	{
		extract($GLOBALS);
		
		$gbquery = $gdbase->query("SELECT * FROM `bans` WHERE `numer` = '{$gbnr}'");
		$gbq = $gbquery->fetch_assoc();
		$class->checkQUERY($gbquery);
		
		if($gbquery->num_rows != '0')
		{
			die($class->msg("Przepraszamy, nie możesz korzystać z tego bota gdyż zostałeś(aś) globalnie zbanowany(a) na sieci CzatyGG.pl!\r\nPowód bana: {$gbq['reason']}\r\nNałożony przez: {$gbq['banby']}\r\n[l]\r\nJeżeli ban globalny został według Ciebie niesłusznie nadany, skontaktuj się ze skrypterem!\r\nNumer: GG:{$NrSkr}"));
		}
	}
	
	public function checkBANG($bngnr, $ubg) //Funkcja sprawdzająca czy użytkownik posiada globalnego bana.
	{
		extract($GLOBALS);
		
		if(!isset($ubg))
		{
			$ubg = 0;
		}
		
		$gbquery = $gdbase->query("SELECT * FROM `bans` WHERE `numer` = '{$bngnr}'");
		$gbq = $gbquery->fetch_assoc();
		$class->checkQUERY($gbquery);
		
		if($ubg == 0 AND $gbquery->num_rows != '0')
		{
			$bang = true;
		}
		elseif($ubg == 0 AND $gbquery->num_rows != '1')
		{
			$bang = false;
		}
		elseif($ubg == 2 AND $gbquery->num_rows != '0')
		{
			die($class->msg("Podany przez Ciebie użytkownik posiada bana globalnego!"));
		}
		elseif($ubg == 1 AND $gbquery->num_rows != '1')
		{
			die($class->msg("Podany przez Ciebie użytkownik nie posiada bana globalnego!"));
		}
		
		return $bang;
	}
	
	/*public function checkWYPISZ() //Funkcja sprawdzająca czy użytkownik wypisał się z bota.
	
	FUNKCJA NIE DZIAŁA, PROBLEM Z KODEM/BOT MASTEREM
	
	{
		extract($GLOBALS);
		
		if($msg == "WYPISZ")
		{
			die($dbase->query("UPDATE `users` SET `wypisz` = '1' WHERE `numer` = '{$from}'"));
		}
		if($msg == "ZAPISZ")
		{
			die($dbase->query("UPDATE `users` SET `wypisz` = '0' WHERE `numer` = '{$from}'"));
		}
	}
	
	FUNKCJA NIE DZIAŁA PROBLEM Z KODEM/BOT MASTEREM
	
	*/
	
	public function dodajCMD($nazwa, $staff) //Funkcja dodająca komendę do bazy danych
	{
		extract($GLOBALS);
		
		$checkCMD = $class->checkCMD($nazwa);
		$checkSKROT = $class->checkSKROT($nazwa);
	
		if($checkCMD == true OR $checkSKROT == true)
		{
			die($class->msg("Przepraszam, podana przez Ciebie komenda już istnieje!"));
		}
		
		$q = $dbase->query("INSERT INTO `komendy` (nazwa, staff) VALUES ('{$nazwa}', '{$staff}')");
		if($q === TRUE)
		{
			die($class->msg("Pomyślnie dodano komendę {$nazwa}!"));
		}
		elseif($q === FALSE && $from != $NrSkr)
		{
			die($class->msg("Błąd bazy danych, powiadom skryptera!"));
		}
		elseif($q === FALSE && $from == $NrSkr)
		{
			$q = "MySQL Error: ".$dbase->error;
		}
		
		return $q;
	}
	
	public function usunCMD($nazwa) //Funkcja usuwająca komendę z bazy danych
	{
		extract($GLOBALS);
		
		$checkCMD = $class->checkCMD($nazwa);
	
		if($checkCMD == false)
		{
			die($class->msg("Przepraszam, podana przez Ciebie komenda nie istnieje!"));
		}
		
		$q = $dbase->query("DELETE FROM `komendy` WHERE `komendy`.`nazwa` = '{$nazwa}'");
		if($q === TRUE)
		{
			die($class->msg("Pomyślnie usunięto komendę {$parts[2]}!"));
		}
		elseif($q === FALSE && $from != $NrSkr)
		{
			die($class->msg("Błąd bazy danych, powiadom skryptera!"));
		}
		elseif($q === FALSE && $from == $NrSkr)
		{
			$q = "MySQL Error: ".$dbase->error;
		}
		
		return $q;
	}
	
	public function edytujCMD($nazwa, $edit, $wartosc) //Funkcja edytująca komendę w bazie danych
	{
		extract($GLOBALS);
		
		$checkCMD = $class->checkCMD($nazwa);
	
		if($checkCMD == false)
		{
			die($class->msg("Przepraszam, podana przez Ciebie komenda nie istnieje!"));
		}
		
		$q = $dbase->query("UPDATE `komendy` SET `{$edit}` = '{$wartosc}' WHERE `nazwa` = '{$nazwa}'");
		if($q === TRUE)
		{
			die($class->msg("Pomyślnie zmieniono {$edit} na {$wartosc} dla komendy {$nazwa}!"));
		}
		elseif($q === FALSE && $from != $NrSkr)
		{
			die($class->msg("Błąd bazy danych, powiadom skryptera!"));
		}
		elseif($q === FALSE && $from == $NrSkr)
		{
			$q = "MySQL Error: ".$dbase->error;
		}
		
		return $q;
	}
	
	public function edytujUSER($nick, $edit, $wartosc, $show) //Funkcja edytująca użytkownika w bazie danych
	{
		extract($GLOBALS);
		
		$checkUSER = $class->checkUSER($nick);
		
		if($show != 1)
		{
			$show = 0;
		}
	
		if($checkUSER == false)
		{
			die($class->msg("Przepraszam, podany przez Ciebie użytkownik nie istnieje!"));
		}
		
		$q = $dbase->query("UPDATE `users` SET `{$edit}` = '{$wartosc}' WHERE `nick` = '{$nick}' OR `numer` = '{$nick}'");
		if($q === TRUE && $show == 1)
		{
			die($class->msg("Pomyślnie zmieniono {$edit} na {$wartosc} dla użytkownika {$nick}!"));
		}
		elseif($q === FALSE && $from != $NrSkr)
		{
			die($class->msg("Błąd bazy danych, powiadom skryptera!"));
		}
		elseif($q === FALSE && $from == $NrSkr)
		{
			$q = "MySQL Error: ".$dbase->error;
		}
		
		return $q;
	}
	
	public function edytujBOT($edit, $wartosc) //Funkcja edytująca ustawienia bota w bazie danych
	{
		extract($GLOBALS);
		
		$q = $dbase->query("UPDATE `bot` SET `{$edit}` = '{$wartosc}'");
		if($q === TRUE)
		{
			die($class->msg("Pomyślnie ustawiono {$wartosc} dla ustawienia {$parts[1]}!"));
		}
		$class->checkQUERY($q);
		
		return $q;
	}
	
	public function nick($pnick, $staff) //Funkcja tworząca wygląd nicku
	{
		extract($GLOBALS);
		if(!isset($staff))
		{
			die($class->msg("Błąd! W funkcji nick() nie podano staffu użytkownika!"));
		}
		
		switch($staff)
		{
			case 0:
				$nz = "";
			break;
			case 1:
				$nz = "Jr.MOD CCG™|";
			break;
			case 2:
				$nz = "MOD CCG™|";
			break;
			case 3:
				$nz = "CCG™|";
			break;
		}
		
		$fnick = "[".$nz.$pnick."]";
		
		return $fnick;
	}
	
	public function newSuppRequest($suppnr, $supp1, $supp2) //Funkcja tworząca nowy ticket w systemie zgłoszeń
	{
		extract($GLOBALS);
		$q = $dbase->query("INSERT INTO `support` (`numer`, `temat`, `tresc`) VALUES ('{$suppnr}', '{$supp1}', '{$supp2}')");
		$class->checkQUERY($q);
		
		if($q === TRUE)
		{
			$class->wiadob("Użytkownik {$nickk}(GG:{$suppnr}) napisał nowe zgłoszenie!\r\nAby je odczytać, wpisz /supportpanel pokaz ID!");
			die($class->msg("Zgłoszenie utworzone pomyślnie!"));
		}
	}
	
	public function showusrSuppRequests($suppusrnr) //Funkcja pokazująca zgłoszenia dla użytkownika
	{
		extract($GLOBALS);
		$q = $dbase->query("SELECT * FROM `support` WHERE `numer` = '{$suppusrnr}' AND `status` != '0'");
		$class->checkQUERY($q);
		while($susr = $q->fetch_assoc())
		{
			
			switch($susr['status'])
			{
				case 1:
					$status = "Otwarte";
				break;
				case 2:
					$status = "Udzielono odpowiedzi";
				break;
				case 3:
					$status = "Zamknięte";
				break;
			}
			
			$ret .= "\r\nID: {$susr['id']}\r\n";
			$ret .= "Nazwa: {$susr['temat']}\r\n";
			$ret .= "Status: {$status}\r\n";
			$return = "Lista twoich zgłoszeń:\r\n[l]\r\n{$ret}";
		}
		
		return $class->msg($return);
	}
	
	public function showusrSuppRequest($id) //Funkcja pokazująca zgłoszenie dla użytkownika
	{
		extract($GLOBALS);
		$q = $dbase->query("SELECT * FROM `support` WHERE `numer` = '{$from}' AND `id` = '{$id}'");
		$class->checkQUERY($q);
		while($susr = $q->fetch_assoc())
		{
			switch($susr['status'])
			{
				case 1:
					$status = "Otwarte";
				break;
				case 2:
					$status = "Udzielono odpowiedzi";
				break;
				case 3:
					$status = "Zamknięte";
				break;
			}
			
			switch($susr['odpowiedz'])
			{
				case !0:
					$odpowiedz = "Odpowiedź: {$susr['odpowiedz']}\r\nOdpowiedział(a): {$susr['replyby']}\r\nAby udzielić odpowiedzi użyj {$parts[0]} [reply] [treść].";
				break;
				case 0:
					$odpowiedz = "Odpowiedź: BRAK";
				break;
			}
			
			$return = "Zgłoszenie ID:{$susr['id']}(Status: {$status})\r\n[l]\r\nNazwa zgłoszenia: {$susr['nazwa']}\r\nTreść: {$susr['tresc']}\r\n[l]\r\n{$odpowiedz}";
		}
		
		return $class->msg($return);
	}
	
	public function showobSuppRequest($id) //Funkcja pokazująca zgłoszenie dla członka obsługi
	{
		extract($GLOBALS);
		$q = $dbase->query("SELECT * FROM `support` WHERE `id` = '{$id}'");
		$class->checkQUERY($q);
		while($susr = $q->fetch_assoc())
		{
			switch($susr['status'])
			{
				case 1:
					$status = "Otwarte";
				break;
				case 2:
					$status = "Udzielono odpowiedzi";
				break;
				case 3:
					$status = "Zamknięte";
				break;
			}
			
			switch($susr['odpowiedz'])
			{
				case !0:
					$odpowiedz = "Odpowiedź: {$susr['odpowiedz']}\r\nOdpowiedział(a): {$susr['replyby']}\r\nAby udzielić odpowiedzi użyj {$parts[0]} [reply] [treść].";
				break;
				case 0:
					$odpowiedz = "Odpowiedź: BRAK";
				break;
			}
			
			$return = "Zgłoszenie ID:{$susr['id']}(Status: {$status})\r\n[l]\r\nNazwa zgłoszenia: {$susr['nazwa']}\r\nTreść: {$susr['tresc']}\r\n[l]\r\n{$odpowiedz}";
		}
		
		return $class->msg($return);
	}
	
	public function checkSuppReq($id, $set) //Funkcja sprawdzająca zgłoszenie
	{
		extract($GLOBALS);
		if(!isset($set))
		{
			$set = 0;
		}
		$q = $dbase->query("SELECT * FROM `support` WHERE `id` = '{$id}'");
		$class->checkQUERY($q);
		$qq = $q->fetch_assoc();
		
		switch($set)
		{
			case 1:
				switch($qq['status'])
				{
					case 2:
						die($class->msg("Przepraszam, zgłoszenie otrzymało już odpowiedź!"));
					break;
					case 3:
						die($class->msg("Przepraszam, zgłoszenie jest zamknięte!"));
					break;
				}
			break;
			case !2:
				switch($qq['status'])
				{
					case 1:
						die($class->msg("Przepraszam, zgłoszenie nie otrzymało jeszcze odpowiedzi!"));
					break;
					case 3:
						die($class->msg("Przepraszam, zgłoszenie jest zamknięte!"));
					break;
				}
			break;
		}
	}
	
	public function replyusrSuppRequest($numer, $id, $tr) //Funkcja odpowiadająca za odpowiedzenie na zgłoszenie przez użytkownika
	{
		extract($GLOBALS);
		$class->checkSuppReq($id);
		$q = $dbase->query("UPDATE `support` SET `odpowiedz` = '{$tr}', `replyby` = '{$nickk}', `status` = '1' WHERE `id` = '{$id}' ");
		$class->checkQUERY($q);
		
		
		if($q === TRUE)
		{
			$class->wiadob("Użytkownik {$nickk}(GG:{$numer}) odpowiedział na zgłoszenie o ID:{$id}!\r\nAby je odczytać, wpisz /supportpanel pokaz {$id}!");
			die($class->msg("Odpowiedź wysłana pomyślnie!"));
		}
	}
	
	public function replyobSuppRequest($numer, $id, $tr) //Funkcja odpowiadająca za odpowiedzenie na zgłoszenie przez obsługę
	{
		extract($GLOBALS);
		$class->checkSuppReq($id, 1);
		$q = $dbase->query("UPDATE `support` SET `odpowiedz` = '{$tr}', `replyby` = '{$nickk}', `status` = '2' WHERE `id` = '{$id}' ");
		$class->checkQUERY($q);
		$q1 = $dbase->query("SELECT * FROM `support` WHERE `id` = '{$id}'");
		$class->checkQUERY($q1);
		$qq = $q1->fetch_assoc();
		
		$class->wiad($nickk." (GG:{$numer}) odpowiedział na twoje zgłoszenie o ID:{$id}!\r\nAby je odczytać, wpisz /support pokaz {$id}!", $qq['numer']);
		die($class->msg("Odpowiedź wysłana pomyślnie!"));
	}
	
	public function closeSuppRequest($id) //Funkcja zamykająca zgłoszenie
	{
		extract($GLOBALS);
		$class->checkSuppReq($id, 1);
		$q = $dbase->query("UPDATE `support` SET `status` = '3' WHERE `id` = '{$id}'");
		$class->checkQUERY($q);
		$q1 = $dbase->query("SELECT * FROM `support` WHERE `id` = '{$id}'");
		$class->checkQUERY($q1);
		$qq = $q1->fetch_assoc();
		
		
		if($q === TRUE)
		{
			$class->wiad("Użytkownik {$nickk}(GG:{$from}) zamknął twoje zgłoszenie o ID:{$id}!", $qq['numer']);
			die($class->msg("Zgłoszenie zostało zamknięte!"));
		}
	}
	
	public function deleteSuppRequest($id) //Funkcja usuwająca zgłoszenie
	{
		extract($GLOBALS);
		$class->checkSuppReq($id, 2);
		$q1 = $dbase->query("SELECT * FROM `support` WHERE `id` = '{$id}'");
		$class->checkQUERY($q1);
		$qq = $q1->fetch_assoc();
		$q = $dbase->query("UPDATE `support` SET `status` = '0' WHERE `id` = '{$id}'");
		$class->checkQUERY($q);
		
		
		if($q === TRUE)
		{
			$class->wiad("{$nickk}(GG:{$from}) usunął twoje zgłoszenie o ID:{$id}!", $qq['numer']);
			$class->wiad("{$nickk}(GG:{$from}) usunął zgłoszenie o ID: {$id}!", $NrSkr);
			die($class->msg("Zgłoszenie zostało usunięte!"));
		}
	}
}
?>
