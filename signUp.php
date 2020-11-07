<?php
    
    session_start();
    if (isset($_POST['login']))
    {
        $everythingOk=true;
        
        $login = $_POST['login'];
        //sprawadzenie loginu
        if((strlen($login)<3) || (strlen($login)>20))
        {
            $everythingOk=false;
            $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków!";
        }
        
        if(ctype_alnum($login)==false)
        {
            $everythingOk=false;
            $_SESSION['e_login'] = "Login może składać się wyłącznie z cyfr i liter bez polskich znaków!";
        }
        
        
        //sprawdzanie email
        $email = $_POST['email'];
        $emailOk = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if((filter_var($emailOk, FILTER_VALIDATE_EMAIL)==false) || ($emailOk!=$email))
        {
            $everythingOk=false;
            $_SESSION['e_email'] = "Podaj poprawny adres e-mail!";
        }


        //Sprawdzanie haseł
        $pass1 = $_POST['password1'];
        $pass2 = $_POST['password2'];
        
        if((strlen($pass1)<8) || (strlen($pass1)>20))
        {
            $everythingOk=false;
            $_SESSION['e_pass'] = "Hasło musi posiadać od 8 do 20 znaków!";
        }
        
        if($pass1 != $pass2)
        {
            $everythingOk=false;
            $_SESSION['e_pass'] = "Podane hasła nie są identyczne!";
        }
        
        $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
        
        //czy zaakceptowany regulamin
        if(!isset($_POST['rules']))
        {
            $everythingOk=false;
            $_SESSION['e_rules'] = "Potwierdź akcpetację regulaminu!";
        }
        
        //bot or not
        
        $secretKey ="6Lf_otgZAAAAAOUWCtf5YhHdiSlEvSx7d3dwYyvY";
        
        $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
        
        $answer = json_decode($check);
        
        if ($answer->success == false)
        {
            $everythingOk=false;
            $_SESSION['e_bot'] = "Potwierdź że nie jesteś botem!";
        }
        
        //Zapamętaj wprowadzone dane
        
        $_SESSION['fr_login'] = $login;
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_pass1'] = $pass1;
        $_SESSION['fr_pass2'] = $pass2;
        
        if(isset($_POST['rules'])) $_SESSION['fr_rules'] = true;
        
        require_once "connect.php";
        
        mysqli_report(MYSQLI_REPORT_STRICT);
        
        try
        {
            $connection = new mysqli( $host, $db_user, $db_password, $db_name );
            if ( $connection->connect_errno != 0 ) 
            {
                throw new Exception (mysqli_connect_errno());
            }
            else
            {
                
                //czy email istnieje w bazie
                $result = $connection->query("SELECT id FROM users WHERE email='$email'");
                
                if(!$result) throw new Exception($connection->error);
            
                $howManyEmails = $result->num_rows;
                if($howManyEmails>0)
                {
                    $everythingOk=false;
                    $_SESSION['e_email'] = "Istnieje użytkownik o takim adresie email!";
                }
                
                //czy login istnieje w bazie
                $result = $connection->query("SELECT id FROM users WHERE login='$login'");
                
                if(!$result) throw new Exception($connection->error);
            
                $howManyLogins = $result->num_rows;
                if($howManyLogins>0)
                {
                    $everythingOk=false;
                    $_SESSION['e_login'] = "Istnieje użytkownik o takim loginie!";
                }
                
                        //walidacja całej flagi
                if($everythingOk==true)
                {
                    if($connection->query("INSERT INTO users VALUES (NULL, '$login', '$pass_hash', '$email')"))
                    {
                        $_SESSION['successSignUp']=true;
                        
                        $userId = $connection->query("SELECT*FROM users WHERE login='$login'");
                        if(!$userId) throw new Exception($connection->error);
                        $record = $userId->fetch_assoc();
                        $id = $record['id'];

                        $incomesCategories = $connection->query("SELECT * FROM incomes_category_default");
                        if(!$incomesCategories) throw new Exception($connection->error);
                        
                        $howManyCatergoryRecords = $incomesCategories->num_rows;
                        for ($i = 1; $i <= $howManyCatergoryRecords; $i++) 
                        {
                            $categoryRecord = $incomesCategories->fetch_assoc();
                            $name = $categoryRecord['name'];
                            $connection->query("INSERT INTO incomes_category_assigned_to_users VALUES (NULL, '$id', '$name')");
                        }
                        
                        $expensesCategories = $connection->query("SELECT * FROM expenses_category_default");
                        if(!$expensesCategories) throw new Exception($connection->error);
                        
                        $howManyCatergoryRecords = $expensesCategories->num_rows;
                        for ($i = 1; $i <= $howManyCatergoryRecords; $i++) 
                        {
                            $categoryRecord = $expensesCategories->fetch_assoc();
                            $name = $categoryRecord['name'];
                            $connection->query("INSERT INTO expenses_category_assigned_to_users VALUES (NULL, '$id', '$name')");
                        }
                        
                        $methods = $connection->query("SELECT * FROM payment_methods_default");
                        if(!$methods) throw new Exception($connection->error);
                        
                        $howManyCatergoryRecords = $methods->num_rows;
                        for ($i = 1; $i <= $howManyCatergoryRecords; $i++) 
                        {
                            $categoryRecord = $methods->fetch_assoc();
                            $name = $categoryRecord['name'];
                            $connection->query("INSERT INTO payment_methods_assigned_to_users VALUES (NULL, '$id', '$name')");
                        }
                        
                        
                        header('Location: welcome.php');
                    }
                    else
                    {
                        throw new Exception($connection->error);
                    }
                }
            
                $connection->close();
            }
        }
        catch(Exception $e)
        {
            echo '<span style="color:red;">Błąd serwera, przepraszamy za niedogodności i prosimy o rejestrację w innym terminie </span>';
            echo '<br />Informacja deweloperska:'.$e;
        }
        

    }
?>


<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-Ua-Compatible" content="IE=edge">
  <title> Wallet - Twoje finanse </title>
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="shortcut icon" href="img/logo.png">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>  
</head>

<body>
	<div class="container">
	<img src="img/logo.png" alt="logo">
		<form method="post">
            <input type="text" value="<?php
                    if(isset($_SESSION['fr_login']))
                    {
                        echo $_SESSION['fr_login'];
                        unset($_SESSION['fr_login']);
                    }?>" name="login" placeholder="Nadaj login" onfocus="this.placeholder=''" onblur="this.placeholder='Nadaj login'"/>
            <?php
                if(isset($_SESSION['e_login']))
                {
                    echo '<div class="signUpError">'.$_SESSION['e_login'].'</div>';
                    unset($_SESSION['e_login']);
                }
                ?>
			<input type="text" value="<?php
                    if(isset($_SESSION['fr_email']))
                    {
                        echo $_SESSION['fr_email'];
                        unset($_SESSION['fr_email']);
                    }?>" name="email" placeholder="Podaj adres e-mail" onfocus="this.placeholder=''" onblur="this.placeholder='Podaj adres e-mail'"/> 
                <?php
                if(isset($_SESSION['e_email']))
                {
                    echo '<div class="signUpError">'.$_SESSION['e_email'].'</div>';
                    unset($_SESSION['e_email']);
                }
                ?>
			<input type="password" value="<?php
                    if(isset($_SESSION['fr_pass1']))
                    {
                        echo $_SESSION['fr_pass1'];
                        unset($_SESSION['fr_pass1']);
                    }?>" name="password1" placeholder="Ustaw hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Ustaw hasło'"/>
            <input type="password" value="<?php
                    if(isset($_SESSION['fr_pass2']))
                    {
                        echo $_SESSION['fr_pass2'];
                        unset($_SESSION['fr_pass2']);
                    }?>" name="password2" placeholder="Powtórz hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Powtórz hasło'"/>
                <?php
                if(isset($_SESSION['e_pass']))
                {
                    echo '<div class="signUpError">'.$_SESSION['e_pass'].'</div>';
                    unset($_SESSION['e_pass']);
                }
                ?>
            <label>
                <br /><br /><input type="checkbox" value="<?php
                    if(isset($_SESSION['fr_rules']))
                    {
                        echo checked;
                        unset($_SESSION['fr_rules']);
                    }?>" name="rules" /> Akceptuję <a href="rules.html">regulamin</a>  <br /><br />
            </label>
                <?php
                if(isset($_SESSION['e_rules']))
                {
                    echo '<div class="signUpError">'.$_SESSION['e_rules'].'</div>';
                    unset($_SESSION['e_rules']);
                }
                ?>
            <div class="g-recaptcha" data-sitekey="6Lf_otgZAAAAAJa0U1XRWcQsw5jxb9Kh3gyzsm6E"></div>
                <?php
                if(isset($_SESSION['e_bot']))
                {
                    echo '<div class="signUpError">'.$_SESSION['e_bot'].'</div>';
                    unset($_SESSION['e_bot']);
                }
                ?>
            
            <input type="submit" value="Utwórz konto">
		</form>
	</div>
</body>
</html>