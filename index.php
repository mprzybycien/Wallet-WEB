<?php
    session_start(); 
    if ((isset($_SESSION['loggedIn'])) && ($_SESSION['loggedIn'] == true))
    {
        header('Location: home.php');
        exit();
    }
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
	<form action="logIn.php" method="post">
		<input type="text" name="login" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'" />
		<input type="password" name="password"  placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'"/>
		<input type="submit" value="Zaloguj się"/>
		<input type="button" onClick="location.href='signUp.php'" value="Zarejestruj się">
	</form>
	</div>
    <div class="loginError">
        <?php
            if(isset($_SESSION['error1']))
            echo $_SESSION['error1'];
        ?>
    </div>


</body>
</html>