<?php
    session_start(); 
    if (!isset($_SESSION['successSignUp']))
    {
        header('Location: index.php');
        exit();
    }
    else
    {
        unset($_SESSION['successSignUp']);
    }

//Usuwanie zmiennych pamietających wartości wpisane do formularza
    if(isset($_SESSION['fr_login'])) unset($_SESSION['fr_login']);
    if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
    if(isset($_SESSION['fr_pass1'])) unset($_SESSION['fr_pass1']);
    if(isset($_SESSION['fr_pass2'])) unset($_SESSION['fr_pass2']);
    if(isset($_SESSION['fr_rules'])) unset($_SESSION['fr_rules']);

    if(isset($_SESSION['e_login'])) unset($_SESSION['e_login']);
    if(isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
    if(isset($_SESSION['e_pass'])) unset($_SESSION['e_pass']);
    if(isset($_SESSION['e_rules'])) unset($_SESSION['e_rules']);
    if(isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title> Wallet - Twoje finanse </title>
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/fontello.css" type="text/css" />
  <link rel="shortcut icon" href="img/logo.png">
</head>

<body>
	<div class="container">
		<img src="img/logo.png" alt="logo">
		Dziękujęmy za rejestrację w serwisie. Możesz teraz zalogować się na swoje konto!
		<input type="button" onClick="location.href='Index.php'" value="Zaloguj się na swoje konto">
	</div>

</body>
</html>