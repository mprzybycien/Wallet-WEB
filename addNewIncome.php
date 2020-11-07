<?php

session_start();

require_once "connect.php";


mysqli_report(MYSQLI_REPORT_STRICT);
try
{
$connection = @new mysqli( $host, $db_user, $db_password, $db_name );
    
if ( $connection->connect_errno != 0 ) throw new Exception (mysqli_connect_errno());

    
else {
    
    $incomeAmount = $_POST['incomeAmount'];
    $incomeSource = $_POST['incomeSource'];
    $incomeDate = $_POST['incomeDate'];
    $incomeComment = $_POST['incomeComment'];
    $userId=$_SESSION['id'];
    
    $result = $connection->query("
    SELECT id 
    FROM incomes_category_assigned_to_users 
    WHERE name='$incomeSource'
    AND user_id='$userId'");
        
    $record = $result->fetch_assoc();
    $incomeSourceId = $record['id'];


    if($connection->query("INSERT INTO incomes VALUES 
    (NULL, 
    '$userId', 
    '$incomeSourceId', 
    '$incomeAmount', 
    '$incomeDate', 
    '$incomeComment')"))
            {
                $_SESSION['formSuccess'] = "Dodano nowy przychód!";
                header('Location: home.php');
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
