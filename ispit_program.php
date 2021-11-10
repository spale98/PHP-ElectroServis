<?php
session_start();
if(!$_SESSION['sesija']){
	header('Location: ispit_logovanje.php');
}
?>
<html>
<head>
	
<meta charset="UTF-8">
	<style>
body{
	background-image: linear-gradient( 175.9deg,  rgba(185,168,144,1) 15%, rgba(85,76,74,1) 87.6% );
}

	</style>
	<title>
	PRIJEM ROBE SA REKLAMACIJOM
	</title>




	  <link href="program.css" type="text/css" rel="stylesheet">
</head>


<body>

<div class="glavniNaslov">

<h3 class="glNas">PRIJEM ROBE SA REKLAMACIJOM</h3>
</div>

	
		
	
	<form class="forma" action="ispit_program.php" method="post" enctype="multipart/form-data">
	
<div class="naslov2">
	<div class="naslov">
		<h3>
		Klijent
		</h3>
		</div>
</div>
<input type="text" name="ime" maxlength="20" placeholder="Ime"> <br>
<input type="text" name="prezime" maxlength="25" placeholder="Prezime"><br>
<input type="text" name="brojLicneKarte" placeholder="Broj lične karte"><br>
<input type="text" name="brTel" placeholder="Kontakt telefon"><br><br>


<div class="naslov2">
	<div class="naslov">
		<h3>
		Uređaj
		</h3>
		</div>
</div>

<input type="text" name="imei" placeholder="IMEI uređaja"> <br>
<input type="text" name="proizvod" placeholder="Proizvod"><br>
<input type="text" name="brend" placeholder="Brend"><br>
<input type="text" name="model" placeholder="Šifra modela"><br><br>


</div>
<div class="naslov2">
	<div class="naslov">
		<h3>
		Reklamacija
		</h3>
		</div>
</div>
<input type="text" name="datum" placeholder="Datum prijema"><br>
<input type="text" name="kvar" placeholder="Vrsta kvara"><br>
<input type="text" name="cena" placeholder="Cena"><br>
Komentar:<br><textarea name="komentar" cols="50" rows="5" placeholder="Komentar"> </textarea><br><br>
<br>Odaberi sliku: <br>Slika: <br><input type="file" name="slika" /><br>

</div>


	
	<input type="submit" value="Unos reklamacije" name="btnReklamacija">
	

	
</form>
<?php
error_reporting(E_PARSE);
if(!($connect = mysqli_connect("localhost", "root", ""))){
	die("Neuspešna konekcija na server.");
}
if(!($baza = mysqli_select_db($connect, "servis"))){
	die("Neuspešno konektovanje na bazu.");
}
if(isset($_POST['btnReklamacija']))
{
	$dozvoljene_ekstenzije = array("jpg","jpeg","png","gif");
	$tip_slike = strtolower(pathinfo(basename($_FILES["slika"]["name"]),PATHINFO_EXTENSION));
	if((!$_POST['ime']) || (!$_POST['prezime']) || (!$_POST['brojLicneKarte']) || (!$_POST['brTel']) || (!$_POST['imei']) || (!$_POST['proizvod']) || (!$_POST['brend']) || (!$_POST['model']) || (!$_POST['datum']) || (!$_POST['kvar']) || (!$_POST['cena']) || (!$_POST['komentar']))
	{
		?><script type="text/javascript">alert("Korisniče, Sva polja moraju biti popunjena!");</script><?php
	}
	else if( !in_array($tip_slike,$dozvoljene_ekstenzije) ){
		?><script type="text/javascript">alert("Korisniče,Podržane ekstenzije za sliku uredjaja su JPG, JPEG, PNG, GIF");</script><?php
	}
	else{
	
		$klijent = "INSERT INTO klijent (ime, prezime, brLicneKarte, brTel)	
		VALUES ('".$_POST['ime']."','".$_POST['prezime']."','".$_POST['brojLicneKarte']."','".$_POST['brTel']."')";
		
			if(!($rezultatDodajA = mysqli_query($connect, $klijent))){
				?>
			<script type="text/javascript">alert("Korisniče, Molimo Vas da unesete ispravne podatke");</script><?php
			die(mysqli_error());
		}
		$slika_base64 = base64_encode(file_get_contents($_FILES['slika']['tmp_name']) );
		$slika = 'data:image/'.$tip_slike.';base64,'.$slika_base64;

		$uredjaj = "INSERT INTO uredjaj (imei, proizvod, brend, model, slika, brLicneKarteKlijenta)
		VALUES ('".$_POST['imei']."','".$_POST['proizvod']."','".$_POST['brend']."','".$_POST['model']."','".$slika."','".$_POST['brojLicneKarte']."')";
		
		if(!($rezultatDodajS = mysqli_query($connect, $uredjaj))){
			?>
			<script type="text/javascript">alert("Korisniče, Molimo Vas da unesete ispravne podatke");</script><?php
			die(mysqli_error());
		}
		
		$popravlja = "INSERT INTO popravlja (imeiUredjaja, idServisera, cena, datum, vrstaKvara, komentar)
		 VALUES ('".$_POST['imei']."','".$_SESSION['sesija']."','".$_POST['cena']."','".$_POST['datum']."','".$_POST['kvar']."','".$_POST['komentar']."')";
		
		
		if(!($rezultatDodajD = mysqli_query($connect, $popravlja))){
			?>
			<script type="text/javascript">alert("Korisniče, Molimo Vas da unesete ispravne podatke");</script><?php
			die(mysqli_error());
		}
		
}
}
?>
<div class = "pretraga">
	<form method="post">
		<input type="text" name="brLicneKartePretraga" class="pretr" placeholder="Unesite broj lične karte klijenta">
		<input type="submit" name="btnTraziLK" value="Prikaži">
		
	</form>

	

</div>

<?php
if(isset($_POST['btnTraziLK'])){
	$traziLicnuKartu = "SELECT klijent.*, uredjaj.*, popravlja.cena, popravlja.datum, popravlja.vrstaKvara, popravlja.komentar
	FROM klijent
	INNER JOIN uredjaj on klijent.brLicneKarte = uredjaj.brLicneKarteKlijenta
	INNER JOIN popravlja on uredjaj.imei = popravlja.imeiUredjaja
	WHERE klijent.brLicneKarte = '".$_POST['brLicneKartePretraga']."'";

if(!($rezultatPretrage = mysqli_query($connect, $traziLicnuKartu))){
	print "Nije moguće izvršiti upit.";
	die(mysqli_error());
}
if($_POST['brLicneKartePretraga']==""){
	print "Da biste izvršili pretragu klijenata po broju lične karte, popunite polje.";
}
else{
if(!($red = mysqli_fetch_assoc($rezultatPretrage))){
	?>
			<script type="text/javascript">alert("Korisniče, Ne postoji klijent sa traženim brojem lične karte");</script><?php
}
else{
	$ime = $red['ime'];
	$prezime = $red['prezime'];
	$brojLicneKarte = $red['brLicneKarte'];
	$brTel = $red['brTel'];
	
	$cena = $red['cena'];
	$date = $red['datum'];
	$vrstaKvara = $red['vrstaKvara'];
	$komentar=$red['komentar'];
	
	
	
	
	$imei = $red['imei'];
	$proizvod = $red['proizvod'];
	$brend = $red['brend'];
	$model = $red['model'];
	$pic = $red['slika'];
	
	print "<center><div><b><i>Podaci o klijentu:</i></b><br>
	IME: $ime <br>
	PREZIME: $prezime <br>
	BROJ LIČNE KARTE: $brojLicneKarte <br>
	BROJ TELEFONA: $brTel <br><br>
	<b><i>Podaci o uređaju:</i></b><br>
	IMEI: $imei <br>
	PROIZVOD: $proizvod <br>
	BREND: $brend <br>
	MODEL: $model <br><br>
	<b><i>Podaci o reklamaciji</i></b><br>
	DATUM: $date <br>
	VRSTA KVARA: $vrstaKvara <br>
	CENA: $cena dinara<br>
	KOMENTAR: $komentar<br><br>
	SLIKA UREĐAJA:<BR>
	<img src=$pic>
	</div>
	
	</center>
	";
}
}
}


?>

<div class = "brisanje">
	<form method="post">
		<input type="text" name="brisanjeLicneKarte" class="brisi" placeholder="Unesite broj lične karte klijenta"><br>
		<input type="text" name="brisanjeImei" class="brisi" placeholder="Unesite broj IMEI uređaja za brisanje">
		<input type="submit" name="btnObrisiLicnuKartu" value="Obriši">
		
	</form>

	

</div>

<?php
if(isset($_POST['btnObrisiLicnuKartu'])){
	if($_POST['brisanjeLicneKarte']!=""){
		$brisanje = "DELETE FROM popravlja WHERE imeiUredjaja= '".$_POST['brisanjeImei']."' && idServisera = '".$_SESSION['sesija']."';
		DELETE FROM uredjaj WHERE imei = '". $_POST['brisanjeImei']."';
		DELETE FROM klijent WHERE brLicneKarte = '".$_POST['brisanjeLicneKarte']."' ;";
			if(!($rezultatOBRISI= mysqli_multi_query($connect, $brisanje))){
?>
			<script type="text/javascript">alert("Korisniče, Kako biste izbršili brisanje klijenata, unesite brojčane vrednosti za BROJ LIČNE KARTE i IMEI uređaja!");</script><?php
}
else{
	print "Uspešno brisanje klijenta sa brojem lične karte: '".$_POST['brisanjeLicneKarte']."', za proizvod: '".$_POST['brisanjeImei']."'";
}
		


}
else{
		?><script type="text/javascript">alert("Korisniče,Da biste izvršili brisanje, morate popuniti oba polja iznad.");</script><?php

}
}

?>

<form action="ispit_update.php" class="levo">
<input type="submit" name="update" value="Izmena podataka"><br>
</form>
<form action="ispit_program.php" method="post" class="levo">
<input type="submit" name="odjava" value="Odjavi se">
</form>

<?php
if(isset($_POST['odjava'])){
	session_destroy();
	header('Location: ispit_logovanje.php');
}

?>
</body>
</html>
