<!DOCTYPE html>
<html lang="pl">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Wallet - Twoje finanse</title>
    <meta name="description" content="Narzędzie do zarządzania budżetem">
    <meta name="keywords" content="portfel, bank, wpływy, wydatki, przychody, dochody, odchody">
    <meta name="author" content="Mateusz Przybycień">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="shortcut icon" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/fontello.css" type="text/css" />
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">

    <!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->

</head>

<body>

    <header>

        <nav class="navbar navbar-light bg-secondary navbar-expand-lg">

            <a class="navbar-brand" href="home.php"><img src="img/logo.png" height="70" alt=""> </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainmenu">

                <ul class="navbar-nav mr-auto">

                    <li class="nav-item ">
                        <a class="nav-link" href="home.php"> Home </a>
                    </li>

                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle disabled" href="#" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true"> Sortuj </a>

                        <div class="dropdown-menu" aria-labelledby="submenu">
                            <a class="dropdown-item" href="#"> Od najnowszych </a>
                            <a class="dropdown-item" href="#"> Od najstarszych </a>
                        </div>

                    </li>

                    <li class="nav-item " data-toggle="modal" data-target="#filterModal">
                        <a class="nav-link" href="#"> Filtruj </a>
                    </li>

                </ul>
            </div>

        </nav>

    </header>

<main>
<div class="table-responsive">
<table class="table table-sm table-hover table-dark">


<?php

session_start();
                
require_once "connect.php";

$connection = @new mysqli( $host, $db_user, $db_password, $db_name );
 
mysqli_report(MYSQLI_REPORT_STRICT);+

$userId = $_SESSION['id'];
$monthStartDate = date('Ym00');
$previousMonthStart = date("Y-m-d", strtotime("first day of previous month"));
$previousMonthEnd = date("Y-m-d", strtotime("last day of previous month"));

if(isset($_SESSION['date1']) && isset($_SESSION['date1']))
{
if($_SESSION['date1']<=$_SESSION['date2']) 
{
    $date1 = $_SESSION['date1'];
    $date2 = $_SESSION['date2'];
}
else
{
    $date2 = $_SESSION['date1'];
    $date1 = $_SESSION['date2'];
}
}

try
{
    $connection = new mysqli( $host, $db_user, $db_password, $db_name );
    if ( $connection->connect_errno != 0 ) 
    {
        throw new Exception (mysqli_connect_errno());
    }
    else 
    {
        if(!isset($_SESSION['defaultFilter']))
        {
            $result = $connection->query(
            "SELECT expenses_category_assigned_to_users.name AS expenses_name, expenses.id, payment_methods_assigned_to_users.name AS payment_name, expenses.amount, expenses.date_of_expense, expenses.expense_comment 
            FROM expenses, expenses_category_assigned_to_users, payment_methods_assigned_to_users 
            WHERE expenses.user_id='$userId' 
            AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id 
            AND expenses.payment_method_assigned_to_user_id=payment_methods_assigned_to_users.id 
            AND expenses.date_of_expense>='$monthStartDate'
            ORDER BY expenses.date_of_expense");

            $howManyRecords = $result->num_rows;
        }
        else if(isset($_SESSION['defaultFilter']))
        {

            if(($_SESSION['transactionType'] == 'incomes') && ($_SESSION['peroidFlag'] == 1))
            {
            $result = $connection->query(
                "SELECT incomes_category_assigned_to_users.name AS incomes_name, incomes.id, incomes.amount, incomes.date_of_income, incomes.income_comment 
                FROM incomes, incomes_category_assigned_to_users 
                WHERE incomes.user_id='$userId' 
                AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id 
                AND incomes.date_of_income>='$monthStartDate'
                ORDER BY incomes.date_of_income");

            $howManyRecords = $result->num_rows;
            }
            else if (($_SESSION['transactionType'] == 'incomes') && ($_SESSION['peroidFlag'] == 2))
            {
                $result = $connection->query(
                "SELECT incomes_category_assigned_to_users.name AS incomes_name, incomes.id, incomes.amount, incomes.date_of_income, incomes.income_comment 
                FROM incomes, incomes_category_assigned_to_users 
                WHERE incomes.user_id='$userId' 
                AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id 
                AND incomes.date_of_income>='$previousMonthStart' 
                AND incomes.date_of_income<='$previousMonthEnd'
                ORDER BY incomes.date_of_income");

                $howManyRecords = $result->num_rows;
            }
            else if (($_SESSION['transactionType'] == 'incomes') && ($_SESSION['peroidFlag'] == 3))
            {
                $result = $connection->query(
                "SELECT incomes_category_assigned_to_users.name AS incomes_name, incomes.id, incomes.amount, incomes.date_of_income, incomes.income_comment 
                FROM incomes, incomes_category_assigned_to_users 
                WHERE incomes.user_id='$userId' 
                AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id 
                AND incomes.date_of_income>='$date1' 
                AND incomes.date_of_income<='$date2'
                ORDER BY incomes.date_of_income");

                $howManyRecords = $result->num_rows;
            }
            
            
            
            if(($_SESSION['transactionType'] == 'expenses') && ($_SESSION['peroidFlag'] == 1))
            {
            $result = $connection->query(
                "SELECT expenses_category_assigned_to_users.name AS expenses_name, expenses.id, payment_methods_assigned_to_users.name AS payment_name, expenses.amount, expenses.date_of_expense, expenses.expense_comment 
                FROM expenses, expenses_category_assigned_to_users, payment_methods_assigned_to_users 
                WHERE expenses.user_id='$userId' 
                AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id 
                AND expenses.payment_method_assigned_to_user_id=payment_methods_assigned_to_users.id 
                AND expenses.date_of_expense>='$monthStartDate'
                ORDER BY expenses.date_of_expense");

            $howManyRecords = $result->num_rows;
            }
            else if (($_SESSION['transactionType'] == 'expenses') && ($_SESSION['peroidFlag'] == 2))
            {
                $result = $connection->query(
                "SELECT expenses_category_assigned_to_users.name AS expenses_name, expenses.id, payment_methods_assigned_to_users.name AS payment_name, expenses.amount, expenses.date_of_expense, expenses.expense_comment 
                FROM expenses, expenses_category_assigned_to_users, payment_methods_assigned_to_users 
                WHERE expenses.user_id='$userId' 
                AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id 
                AND expenses.payment_method_assigned_to_user_id=payment_methods_assigned_to_users.id 
                AND expenses.date_of_expense>='$previousMonthStart' 
                AND expenses.date_of_expense<='$previousMonthEnd'
                ORDER BY expenses.date_of_expense");

                $howManyRecords = $result->num_rows;
            }
            else if (($_SESSION['transactionType'] == 'expenses') && ($_SESSION['peroidFlag'] == 3))
            {
                $result = $connection->query(
                "SELECT expenses_category_assigned_to_users.name AS expenses_name, expenses.id, payment_methods_assigned_to_users.name AS payment_name, expenses.amount, expenses.date_of_expense, expenses.expense_comment 
                FROM expenses, expenses_category_assigned_to_users, payment_methods_assigned_to_users 
                WHERE expenses.user_id='$userId' 
                AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id 
                AND expenses.payment_method_assigned_to_user_id=payment_methods_assigned_to_users.id 
                AND expenses.date_of_expense>='$date1' 
                AND expenses.date_of_expense<='$date2'
                ORDER BY expenses.date_of_expense");

                $howManyRecords = $result->num_rows;
            }
        }
        
        if(!isset($_SESSION['defaultFilter']) || $_SESSION['transactionType']=='expenses')
        {
        if(!isset($_SESSION['defaultFilter'])) $tabletitle="Zestawienie wydatków z bierzącego miesiąca";
        else if($_SESSION['peroidFlag']==1) $tabletitle='Zestawienie wydatków z bierzącego miesiąca';
        else if ($_SESSION['peroidFlag']==2) $tabletitle='Zestawienie wydatków z poprzedniego miesiąca';
        else if ($_SESSION['peroidFlag']==3) $tabletitle='Zestawienie wydatków z okresu od '.$date1.' do '.$date2; 
        if($howManyRecords>0) 
            {
            echo<<<END
            <thead>
                <tr>
                <td colspan="5">
                <center>$tabletitle</center>
                <td>
                </tr>
                <tr>
                <th>Cel </th>
                <th>Metoda płatności </th>
                <th>Data</th>
                <th>Kwota</th>
                <th>Komentarz</th>
                <th>Edytuj/usuń</th>
                </tr>
            </thead>
            END;

            for ($i=1; $i<=$howManyRecords; $i++)
            {
                $row = $result->fetch_assoc();

                $expenseCat = $row['expenses_name'];
                $paymentMethod = $row['payment_name'];
                $expenseDate = $row['date_of_expense'];
                $expenseAmount = $row['amount'];
                $expenseComment = $row['expense_comment'];

                echo<<<END
                <tr>
                <td>$expenseCat</td>
                <td>$paymentMethod</td>
                <td>$expenseDate</td>
                <td>$expenseAmount zł</td>
                <td>$expenseComment</td>
                <td>
                <div class=categoryEdit><a href="#" class=buttonLink title="Edytuj"><i class="icon-pencil"></i></a></div>
                <div class=categoryRemove><a href="#" class=buttonLink title="Usuń"><i class="icon-trash"></i></a></div>
                <div style="clear: both;"></div>
                </td>
                </tr>
                END; 
            }}else echo "<br />Nie znaleziono żadnego rekordu w bazie";
            
            unset($_SESSION['defaultFilter']);
            
        }
        else if ($_SESSION['transactionType'] == 'incomes')
        { 
        if(($_SESSION['peroidFlag']==1) || !isset($_SESSION['defaultFilter'])) $tabletitle='Zestawienie przychodów z bierzącego miesiąca:';
        else if ($_SESSION['peroidFlag']==2) $tabletitle='Zestawienie przychodów z poprzedniego miesiąca:';
        else if ($_SESSION['peroidFlag']==3) $tabletitle='Zestawienie przychodów z okresu od '.$date1.' do '.$date2.':';    
            if($howManyRecords>0) 
            {
            echo<<<END
            <thead>
                <tr>
                <td colspan="4">
                <center>$tabletitle</center>
                <td>
                </tr>
                <tr>
                <th>Źródło </th>
                <th>Data</th>
                <th>Kwota</th>
                <th>Komentarz</th>
                <th>Edytuj/usuń</th>
                </tr>
            </thead>
            END;

            for ($i=1; $i<=$howManyRecords; $i++)
            {
                $row = $result->fetch_assoc();

                $incomeCat = $row['incomes_name'];
                $incomeDate = $row['date_of_income'];
                $incomeAmount = $row['amount'];
                $incomeComment = $row['income_comment'];

                echo<<<END
                <tr>
                <td>$incomeCat</td>
                <td>$incomeDate</td>
                <td>$incomeAmount zł</td>
                <td>$incomeComment</td>
                <td>
                <div class=categoryEdit><a href="#" class=buttonLink title="Edytuj"><i class="icon-pencil"></i></a></div>
                <div class=categoryRemove><a href="#" class=buttonLink title="Usuń"><i class="icon-trash"></i></a></div>
                <div style="clear: both;"></div>
                </td>
                </tr>
                END;
            }}else echo "<br />Nie znaleziono żadnego rekordu w bazie";
        unset($_SESSION['defaultFilter']);
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
            </table>
        </div>

    </main>

    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Ustaw filtry</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="filter.php" method="post">

                        Rodzaj transakcji: <br />
                        <select name=transactionType>
                            <option value=incomes>Dochody</option>
                            <option value=expenses>Odchody</option>
                        </select>

                        <br />Okres: <br />
                        <input checked type="radio" value=1 name="peroid" onClick="document.getElementById('hidden').style.display='none';"> Bierzący miesiąc <br />
                        <input type="radio" value=2 name="peroid" onClick="document.getElementById('hidden').style.display='none';"> Poprzedni miesiąc <br />
                        <input type="radio" value=3 name="peroid" onclick="document.getElementById('hidden').style.display='block';"> Inny okres: <br />
                        <div style="display: none" id="hidden">
                            <hr />
                            <input type="date" name="date1">
                            <input type="date" name="date2">
                            <hr>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                            <input type="submit" value="Filtruj">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <script src="js/bootstrap.min.js"></script>


</body>

</html>
