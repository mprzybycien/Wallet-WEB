<?php

    session_start();

    if((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
    {
        header('Location: index.php');
        exit();
    }

    require_once "connect.php";

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    if($connection->connect_errno!=0)
    {
        echo "Error: ".$connection->connect_errno;
    }
    else
    {
        $login = $_POST['login'];
        $pass = $_POST['password'];
        
        $sql = "SELECT*FROM users WHERE login='$login' AND password='$pass'";
        
        if ($result = $connection->query($sql))
        {
            $ilu_userow = $result->num_rows;
            if($ilu_userow>0)
            {
                $_SESSION['loggedIn'] = true;
                
                $wiersz = $result->fetch_assoc();
                $_SESSION['id'] = $wiersz['id'];
                $_SESSION['user'] = $wiersz['login'];
                $_SESSION['email'] = $wiersz['email'];
                
                $result->close();
                
                unset($_SESSION['blad']);
                
                header('Location: home.php');
                
            }else{
                $_SESSION['blad'] = "Nieprawidłowe dane logowania!";
                header('Location: index.php'); 
            }
        }
        
        $connection->close();
    }
    
?>