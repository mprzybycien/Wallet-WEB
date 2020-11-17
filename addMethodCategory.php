<?php

session_start();

require_once "connect.php";

mysqli_report(MYSQLI_REPORT_STRICT);
try
{
$connection = @new mysqli( $host, $db_user, $db_password, $db_name );
    
if ( $connection->connect_errno != 0 ) throw new Exception (mysqli_connect_errno());

    
else {
    $newMethodCategory = $_POST['newMethodCategory'];
    $userId=$_SESSION['id'];
    
    $result = $connection->query("SELECT id FROM payment_methods_assigned_to_users WHERE name='$newMethodCategory'");
    if(!$result) throw new Exception($connection->error);
    $howManyRecords = $result->num_rows;
    
    if(strlen($newMethodCategory)>30)
        {
            $_SESSION['e_cat_add'] = "Nie dodano kategorii. Jej nazwa może się składać z maksymalnie 30 znaków!";
            $connection->close();
            header('Location: methodsCategories.php');
        }
    else if($howManyRecords>0)
        {
            $_SESSION['e_cat_add'] = "Istnieje już taka kategoria w bazie!";
            $connection->close();
            header('Location: methodsCategories.php');
        }
    else
        {
            if($connection->query("INSERT INTO payment_methods_assigned_to_users VALUES (NULL, '$userId', '$newMethodCategory')"))
            {
                $_SESSION['addCatSuccess'] = "Dodano nową kategorię!";
                header('Location: methodsCategories.php');
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
?>
