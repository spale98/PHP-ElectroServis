<?php
session_start();
if(isset($_POST['btnPrijava'])){
	if(($_POST['username'] != "") && ($_POST['sifra'] != "")){
		$con = mysqli_connect("localhost", "root", "", "servis");
		if (!$con) die("Nije uspela konekcija na server!");
		$upit= "SELECT id FROM serviser WHERE username LIKE '".$_POST['username']."' AND password LIKE '".$_POST['sifra']."'";
		
		if($rezultatUpita = mysqli_query($con, $upit)){
			if(!($red = mysqli_fetch_assoc($rezultatUpita))){
				?><script type="text/javascript">alert("Molimo Vas unesite ispravne korisničke podatke!");</script><?php
				session_destroy();
				
			}
		else{
			$_SESSION['sesija'] = $red['id'];
		header('Location: ispit_program.php');}
		}
		else{
		print "Greska";}
		}
}




?>

<html>
<head>
<meta charset="UTF-8">
	<title>LOG IN</title>
	<meta charset="UTF-8">
<link href="log.css" type="text/css" rel="stylesheet">
	<style>
body{
	background-image: linear-gradient( 175.9deg,  rgba(185,168,144,1) 15%, rgba(85,76,74,1) 87.6% );
}

	</style>
</head>
<body>
<center>
<div class="navigacija">
		<a href="ispit_registrovanje.php"><button class="buttonMeni"><li>Registracija</li></a>
		<a href="ispit_logovanje.php"><button class="buttonMeni"><li>Prijava</li></a>
		</ul>
	</div>
</div>


<div class="prijava">

	<h2>Prijavi se:</h2>
</center><br><br><br>
	<center>
	<form action="#" method="post" name="logIn">
	<br>

		<input type="text" class="userName" name="username" placeholder="Korisničko ime">
		<br><br>
		
		<input type="password" class="userName" name="sifra" placeholder="Šifra">
		<br>
		<input type="submit" value="Prijava" class="prijava" name="btnPrijava">
	</form>
	</center>
</div>

	
</body>
</html>
