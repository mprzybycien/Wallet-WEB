<?php

session_start();

require_once "connect.php";


mysqli_report(MYSQLI_REPORT_STRICT);
try
{
$connection = @new mysqli( $host, $db_user, $db_password, $db_name );
    
if ( $connection->connect_errno != 0 ) throw new Exception (mysqli_connect_errno());

    
else {
    
    $expenseAmount = $_POST['expenseAmount'];
    $expenseTarget = $_POST['expenseTarget'];
    $expenseDate = $_POST['expenseDate'];
    $paymentMethod = $_POST['paymentMethod'];
    $expenseComment = $_POST['expenseComment'];
    $userId = $_SESSION['id'];
    
    $result = $connection->query("
    SELECT id 
    FROM expenses_category_assigned_to_users 
    WHERE name='$expenseTarget'
    AND user_id='$userId'");
        
    $record = $result->fetch_assoc();
    $expenseTargetId = $record['id'];
    
    $result = $connection->query("
    SELECT id 
    FROM payment_methods_assigned_to_users 
    WHERE name='$paymentMethod'
    AND user_id='$userId'");
        
    $record = $result->fetch_assoc();
    $paymentMethodId = $record['id'];
    


    if($connection->query("INSERT INTO expenses VALUES 
    (NULL, 
    '$userId', 
    '$expenseTargetId',
    '$paymentMethodId',
    '$expenseAmount', 
    '$expenseDate', 
    '$expenseComment')"))
            {
                $_SESSION['formSuccess'] = "Dodano nowy wydatek!";
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
