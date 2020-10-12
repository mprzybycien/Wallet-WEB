<?php

    session_start();

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
                $wiersz = $result->fetch_assoc();
                $_SESSION['user'] = $wiersz['login'];
                
                $result->close();
                
                header('Location: home.php');
                
            }else{}
        }
        
        $connection->close();
    }
    
?>