<?php
session_start(); 
if(!$_SESSION['sesija']){
	header('Location: ispit_logovanje.php'); 
}
if(!($connect = mysqli_connect("localhost", "root", ""))){
	die("Ne možete se konektovati na MySQL server");
}
if(!($baza = mysqli_select_db($connect, "servis"))){
	die("Ne može se selektovati baza podataka");
}

if(isset($_POST['odjava'])){
	session_destroy();
	header('Location: ispit_logovanje.php');
}
?>


<html>
<head>

<style>
body{
	background-image: linear-gradient( 175.9deg,  rgba(185,168,144,1) 15%, rgba(85,76,74,1) 87.6% );
}
</style>

<meta charset="UTF-8">
<link href="izmena.css" type="text/css" rel="stylesheet">
	<meta charset="ISO-8859-1">
</head>
<body>
<div class="glavniNaslov">

<h3 class="glNas">IZMENA KORISNIČKIH PODATAKA</h3>
</div>

<div class="pomeri">
	<form action="ispit_program.php" method="post">
	<input type="submit" name="pocetna"value="Početna">
	</form>

	<form action="ispit_update.php" method="post">
	<input type="submit" name="odjava" value="Odjavi se" method="post">
</div>
<div class="idZaposlenog">
	<form method="post">
		<input type="text" name="idZaposlenog" class="poc" placeholder="ID prijavljenog korisnika">
		<input type="submit" name="btnTrazi" value="Prikaži">
	</form>
</div>


<?php
if(isset($_POST['btnTrazi'])){
	$traziID = "SELECT * FROM serviser WHERE id = '".$_POST['idZaposlenog']."';";;
if(!($rezultatID = mysqli_query($connect, $traziID))){
	print "Ne može se izvrsiti upit.";
	die(mysqli_error($connect));
} 
if(!($red = mysqli_fetch_assoc($rezultatID))){
	print "Pogrešan unos";
}

else{
	$ime = $red['ime'];
	$prezime = $red['prezime'];
	$id = $red['id'];
	$username = $red['username'];
	$password = $red['password'];
	
	echo '
		<form action="ispit_update.php" method="POST">
		<div class="form-container">
			Ime:<br><input type="text" name="ime" value="'.$ime.'" disabled> <br>
			Prezime:<br><input type="text" name="prezime" value="'.$prezime.'" disabled> <br>
			ID:<br><input type="text" name="id" value="'.$id.'" disabled> <br>
			USERNAME:<br><input type="text" name="username" value="'.$username.'"> <br> 
			PASSWORD:<br><input type="text" name="password" value="'.$password.'"> <br> 
		<input type="submit" name="btnUpdate" value="Potvrdi">
		
		
	</form>';
		
}
}

if(isset($_POST['btnUpdate'])){
$update = "UPDATE serviser
			SET username = '".$_POST['username']."', password = '".$_POST['password']."'
			WHERE id = '".$_SESSION['sesija']."';";
			$rezultatUpdate = mysqli_query($connect, $update); 
			if (!$rezultatUpdate)
			{
			die("Neuspesno!");
			mysqli_close($con);
			}
			print "Uspešno Ste promenili username i password";
	}
?>


</body>
</html>
