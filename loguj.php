<style>
body {
font-size:15px;
font-family:Verdana;
line-height:1.8;
word-spacing:3px;
}
</style>

<?php
$ip = $_SERVER["REMOTE_ADDR"];
$data = date('Y-m-d H:i:s');
$minuty = floor((( strtotime( $data ) - strtotime( $czas ) ) / 60) % 60);
$dbhost="localhost"; $dbuser="vertim_pass11"; $dbpassword="vertim123"; $dbname="vertim_pass11"; //Połączenie z bazą danych
$polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
mysql_connect("localhost","vertim_pass11","vertim123");
mysql_select_db("vertim_pass11");
mysqli_select_db ($polaczenie, $dbname);
$rezultat1 = mysqli_query ($polaczenie, "SELECT COUNT(*) FROM `logi` WHERE `ip` LIKE '$ip' AND `czaslogowania` > (now() - interval 10 minute) AND `pomyslnosc`='NIE'");
$proby = mysqli_fetch_array($rezultat1, MYSQLI_NUM);
if($proby[0] >= 5)
{
  echo('<meta http-equiv="refresh" content="1;url=http://marbagcomp.gbzl.pl/blokada.html">');
}
session_start();
mysql_connect("localhost","vertim_pass11","vertim123");
mysql_select_db("vertim_pass11");
?>

<?php
if (isset($_COOKIE['wyloguj'])=="1") 
{	
	setcookie ("zalogowany", "false");
	session_destroy();
}
?>

<?php
$data = date('Y-m-d H:i:s');
$ip = filtruj($_SERVER['REMOTE_ADDR']);
function filtruj($zmienna) 
{
    if(get_magic_quotes_gpc())
        $zmienna = stripslashes($zmienna); // usuwamy slashe

	// usuwamy spacje, tagi html oraz niebezpieczne znaki
    return mysql_real_escape_string(htmlspecialchars(trim($zmienna))); 
}

if (isset($_POST['loguj'])) 
{
	$login = filtruj($_POST['login']);
	$haslo = filtruj($_POST['haslo']);
	$ip = filtruj($_SERVER['REMOTE_ADDR']);
	
	// sprawdzamy czy login i hasło są dobre
	if (mysql_num_rows(mysql_query("SELECT login, haslo FROM users WHERE login = '".$login."' AND haslo = '".md5($haslo)."';")) > 0) 
	{	
		// uaktualniamy date logowania oraz ip	
		setcookie ("zalogowany", "true");
		setcookie ("login", "$login");
		$_SESSION['login'] = $login;

		// zalogowany

	}
	else 
	echo "Wpisano złe dane";
	mysql_query ("INSERT INTO logi (`login`, `czaslogowania`, `ip`, `pomyslnosc`) VALUES ('$login', '$data', '$ip', 'NIE')");
}
$data = date('Y-m-d H:i:s');
$ip = filtruj($_SERVER['REMOTE_ADDR']);
$login = $_COOKIE['login'];

if ($_COOKIE['zalogowany']=="true")
{	$data = date('Y-m-d H:i:s');
	mysql_query ("INSERT INTO logi (`login`, `czaslogowania`, `ip`, `pomyslnosc`) VALUES ('$login', '$data', '$ip', 'TAK')");
	echo "Witaj <b>".$_COOKIE['login']."</b><br><br>";
	$ostlogowanie = mysqli_query ($polaczenie, "SELECT `czaslogowania` FROM `logi` WHERE `pomyslnosc`='NIE' ORDER BY `czaslogowania` DESC LIMIT 1");
	while ($wiersz11 = mysqli_fetch_array ($ostlogowanie))
	{
		$czasost = $wiersz11 [0];
	}
	echo "Data ostatniego nieudanego logowanania: ";
	echo $czasost;
	echo "<br>";
	echo '<a href="http://marbagcomp.gbzl.pl/chmura.php"><li>Przejdź do chmury</li></a></br>';
	echo '<a href="http://marbagcomp.gbzl.pl/zad7.php">[Wyloguj]</a>' ;
}
if ($_COOKIE['zalogowany']=="false") :
?>
<form method="POST" action="loguj.php">
<b>Login:</b> <input type="text" name="login" required></br>
<b>Hasło:</b> <input type="password" name="haslo" required></br>
<input type="submit" value="Zaloguj" name="loguj"></br><br>
<a href="http://marbagcomp.gbzl.pl/zad7rejestruj.html"><li>Zarejestruj się</li></a></br>
<a href="http://marbagcomp.gbzl.pl/zad7.php"><li>Wróć do strony głównej</li></a></br>
</form> 

<?php endif; ?>



<?php mysql_close(); ?>