<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-Ua-Compatible" content="IE=edge">
  <title> Wallet - Twoje finanse </title>
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="css/fontello.css" type="text/css" />
  <link rel="shortcut icon" href="img/logo.png">
</head>

<body>
	<div id="container">
		<img src="img/logo.png" alt="logo">
	<form action="login.php" method="post">
		<input type="text" name="login" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'" />
		<input type="password" name="password"  placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'"/>
		<input type="submit" value="Zaloguj się"/>
		<input type="button" onClick="location.href='SignUp.html'" value="Zarejestruj się">
	</form>
	</div>
</body>
</html>