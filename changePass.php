<?php
        session_start(); 
        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        
        try
        {
        $connection = @new mysqli( $host, $db_user, $db_password, $db_name );
            if ( $connection->connect_errno != 0 ) 
            {
                throw new Exception (mysqli_connect_errno());
            }
        if(isset($_POST['oldPassword']))
        {
            $logedUserId = $_SESSION['id'];
            $oldPassword = $_POST['oldPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmedPassword = $_POST['ConfirmedPassword'];
            $changePassSuccess = true;

            $result = $connection->query("SELECT*FROM users WHERE id = '$logedUserId'");
            if(!$result) throw new Exception($connection->error);
            
            $howManyUsers = $result->num_rows;
            
            if ($howManyUsers>0 ) 
            {
                $row = $result->fetch_assoc();

                    if((strlen($newPassword)<8) || (strlen($confirmedPassword)>20))
                    {
                        $changePassSuccess=false;
                        $_SESSION['formError'] = "Hasło nie zostało zmienione! Nowe hasło musi posiadać od 8 do 20 znaków!";
                        header( 'Location: home.php' );
                    }
                    
                    if($newPassword != $confirmedPassword)
                    {
                        $changePassSuccess=false;
                        $_SESSION['formError'] = "Hasło nie zostało zmienione! Podane nowe hasła są różne";
                        header( 'Location: home.php' );
                    }
                    
                    if (!password_verify($oldPassword, $row['password'] ) )
                    {
                        $changePassSuccess=false;
                        $_SESSION['formError'] = "Hasło nie zostało zmienione! Podaj właściwe aktualne hasło!";
                        header( 'Location: home.php' );
                    }
                
                    if ($changePassSuccess == true)
                    {
                        $pass_hash = password_hash($newPassword, PASSWORD_DEFAULT);
                        if($connection->query("UPDATE users SET password='$pass_hash' WHERE id='$logedUserId'"))
                        {
                            $_SESSION['formSuccess'] = "Hasło zostało zmienione";
                            header( 'Location: home.php' );
                        }  
                        else
                        {
                            throw new Exception($connection->error);
                        }
                    }
            }
        }
            $connection->close();
        }
        catch(Exception $e)
        {
            echo '<span style="color:red;">Błąd serwera, przepraszamy za niedogodności</span>';
            echo '<br />Informacja deweloperska:'.$e;
        }
?>