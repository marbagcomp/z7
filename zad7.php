<?php
setcookie ("wyloguj", "1");
setcookie ("zalogowany", "false");
setcookie ("login", "");

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
?>
<form method="POST" action="loguj.php">
<b>Login:</b> <input type="text" name="login" required><br>
<b>Hasło:</b> <input type="password" name="haslo" required><br>
<input type="submit" value="Zaloguj" name="loguj"><br><br>
<a href="http://marbagcomp.gbzl.pl/zad7rejestruj.html"><li>Zarejestruj się</li></a></br>
<a href="http://marbagcomp.gbzl.pl/zad7.php"><li>Wróć do strony głównej</li></a></br>
</form>
