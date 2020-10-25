<?php

session_start();

if ( ( !isset( $_POST['login'] ) ) && ( !isset( $_POST['haslo'] ) ) ) {
    header( 'Location: index.php' );
    exit();
}

require_once "connect.php";

$connection = @new mysqli( $host, $db_user, $db_password, $db_name );

if ( $connection->connect_errno != 0 ) {
    echo "Error: ".$connection->connect_errno;
    
} else {
    $login = $_POST['login'];
    $pass = $_POST['password'];

    $login = htmlentities( $login, ENT_QUOTES, "UTF-8" );

    $sql = "SELECT*FROM users WHERE login='$login'";
    

    if ( $result = $connection->query(
        sprintf( "SELECT*FROM users WHERE login='%s'",
        mysqli_real_escape_string( $connection, $login ) ) ) ) {
            $howManyUsers = $result->num_rows;
            if ($howManyUsers>0 ) {
                $wiersz = $result->fetch_assoc();
   

                if (password_verify($pass, $wiersz['password'] ) ) {
                    $_SESSION['loggedIn'] = true;

                    $_SESSION['id'] = $wiersz['id'];
                    $_SESSION['user'] = $wiersz['login'];
                    $_SESSION['email'] = $wiersz['email'];

                    $result->close();

                    unset( $_SESSION['error1'] );

                    header( 'Location: home.php' );
                } else {
                    $_SESSION['error1'] = "Nieprawidłowe dane logowania!";
                    header( 'Location: index.php' );
                }
            }
        else {
            $_SESSION['error1'] = "Nieprawidłowe dane logowania!";
            header( 'Location: index.php' );

        }

        $connection->close();
    }
    }
?>
