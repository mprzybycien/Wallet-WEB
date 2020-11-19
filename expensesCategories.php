<?php
    session_start(); 
?>

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
                        <a class="nav-link" href="incomesCategories.php"> Kategorie przychodów </a>
                    </li>

                    <li class="nav-item dropdown ">
                        <a class="nav-link" href="methodsCategories.php"> Kategorie metod płatności </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="table-responsive">
        <table class="table table-sm table-hover table-dark">
            <?php
                require_once "connect.php";
                mysqli_report(MYSQLI_REPORT_STRICT);
                try
                {
                $connection = @new mysqli( $host, $db_user, $db_password, $db_name );

                if ( $connection->connect_errno != 0 ) throw new Exception (mysqli_connect_errno());
                else
                {
                    $userId=$_SESSION['id'];
                    $result = $connection->query("
                    SELECT * 
                    FROM expenses_category_assigned_to_users 
                    WHERE user_id='$userId' 
                    ORDER BY name ASC");
                    
                    if(!$result) throw new Exception($connection->error);
                    $howManyRecords = $result->num_rows;
                    
                    if($howManyRecords>0) 
                    {
                        
                    echo<<<END
                    <thead>
                        <tr>
                            <th><center>Tabela kategorii wydatków</center></th>
                            <th><div id= addNew data-toggle="modal" data-target="#AddNewExpenseCategoryModal"><a href="#" class=addLink title="Dodaj nową kategorię" > + Dodaj</a></div></th>
                            
                        </tr>
                        <tr>
                        <th>Kategoria </th>
                        <th>Edytuj/usuń</th>
                        </tr>
                    </thead>
                    END;
                    
                    
                    for ($i = 0; $i < $howManyRecords; $i++)
                    {
                        $categoryRecord = $result->fetch_assoc();
                        $name = $categoryRecord['name'];
                            
                        echo<<<END
                        <tr>
                        <td>$name</td>
                        <td>
                        <div class=categoryEdit><a href="#" class=buttonLink title="Edytuj"><i class="icon-pencil"></i></a></div>
                        <div class=categoryRemove><a href="#" class=buttonLink title="Usuń"><i class="icon-trash"></i></a></div>
                        <div style="clear: both;"></div>
                        </td>
                        </tr>
                        END;
                    } 
                    }else echo "<br />Nie znaleziono żadnego rekordu w bazie";
                    
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

    <div class=error>
        <?php 
    if(isset($_SESSION['e_cat_add'])) echo $_SESSION['e_cat_add'];  
    unset ($_SESSION['e_cat_add']);
    ?>
    </div>
    <div class=success>
        <?php 
    if(isset($_SESSION['addCatSuccess'])) echo $_SESSION['addCatSuccess'];
    unset ($_SESSION['addCatSuccess']);
    ?>
    </div>




    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <script src="js/bootstrap.min.js"></script>



    <!-- MODAL kategorii wydatku -->

    <div class="modal fade" id="AddNewExpenseCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Podaj nazwę nowej kategorii</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="addExpenseCategory.php" method="post">
                    <div class="modal-body">
                        <input type="text" name="newExpenseCategory" placeholder="Nazwa katergorii" /> <br />
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                            <input type="submit" value="Zapisz!">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
