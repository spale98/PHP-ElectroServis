<?php
session_start();
error_reporting(E_PARSE);
if(!($connect = mysqli_connect("localhost", "root", ""))){
	die("Neuspešna konekcija na server.");
}
if(!($baza = mysqli_select_db($connect, "servis"))){
	die("Neuspešno selektovanje baze podataka");
}
if(isset($_POST['btnRegistracija']))
{
	if((!$_POST['ime']) || (!$_POST['prezime']) || (!$_POST['id']) || (!$_POST['username']) || (!$_POST['sifra']) || (!$_POST['datum'])){
		?><script type="text/javascript">alert("Da biste se registrovali popunite sva polja!");</script><?php
	
	}
	else
	{
		$dodajUbazu = "INSERT INTO serviser (id, ime, prezime, username, password)	
		VALUES ('".$_POST['id']."','".$_POST['ime']."','".$_POST['prezime']."','".$_POST['username']."','".$_POST['sifra']."')";
		
		switch($_POST['izaberi']){
			case '1 - PICO Servis':
				$dodajZaposlenje = "INSERT INTO zaposlen (idServisa, idServisera, datumZaposlenja)
				VALUES (1,'".$_POST['id']."','".$_POST['datum']."')";
			break;
			case '2 - RIM Mobile':
				$dodajZaposlenje = "INSERT INTO zaposlen (idServisa, idServisera, datumZaposlenja)
				VALUES (2,'".$_POST['id']."','".$_POST['datum']."')";
			break;
			case '3 - REPAIR':
			$dodajZaposlenje = "INSERT INTO zaposlen (idServisa, idServisera, datumZaposlenja)
				VALUES (3,'".$_POST['id']."','".$_POST['datum']."')";
			break;
			default:	
			print ("Greska");
			
			header('Location: ispit_logovanje.php');
}

header('Location: ispit_registrovanje.php');
				
	if(!($rezultatDodajZaposlenog = mysqli_query($connect, $dodajUbazu)))
	{
		?><script type="text/javascript">alert("Da biste se registrovali popunite sva polja!");</script><?php
	die(mysqli_error());
	}
	
	if(!($rezultatDodajServis = mysqli_query($connect, $dodajZaposlenje)))
	{
	
	die(mysqli_error());
	

	}
	}
	
}

?>
<html>
<head>
<meta charset="UTF-8">
	<title>Registracija</title>

	<style>
body{
	background-image: linear-gradient( 175.9deg,  rgba(185,168,144,1) 15%, rgba(85,76,74,1) 87.6% );
}

</style>
	
  <link href="registrujme.css" type="text/css" rel="stylesheet">
</head>
<body>


<center>
<div class="navigacija">
		<a href="ispit_registrovanje.php"><button class="buttonMeni"><li>Registracija</li></a>
		<a href="ispit_logovanje.php"><button class="buttonMeni"><li>Prijava</li></a>
		</ul>
	</div>
</div>
</center>
<?php
?>
	
<div class="prijava">

	<center><h2>Registruj se:</h2></center>

	<center>
	<form action="#" method="post" name="logIn">
	<br>
		<input type="text" class="name" name="ime" placeholder="Ime"><br>
		<input type="text" class="userName" name="prezime" placeholder="Prezime">
		<br>
		<input type="text" class="userName" name="id" placeholder="ID">
		<br>
		<input type="text" class="userName" name="datum" placeholder="Datum zaposlenja">
		<br><br>
		Izaberite servis u kojem Ste zaposleni:  <select name="izaberi">
		  <option name="izbor" value="1 - PICO Servis">1 - PICO Servis</option>
		  <option name="izbor" value="2 - RIM Mobile">2 - RIM Mobile</option>
		  <option name="izbor" value="3 - REPAIR">3 - REPAIR</option>
		</select>
		<br><br>
		<input type="text" class="userName" name="username" placeholder="Korisničko ime">
		<br>
		<input type="password" class="userName" name="sifra" placeholder="Šifra">
		<br>
		
		<input type="submit" value="Registracija" class="prijava" name="btnRegistracija" >
	</form>
	</center>
</div>

</body>
</html>
