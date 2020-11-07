<?php
    session_start(); 
    if(!isset($_SESSION['loggedIn']))
    {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Wallet - Twoje finanse</title>
	<meta name="description" content="Narzędzie do zarządzania budżetem">
	<meta name="keywords" content="portfel, bank, wpływy, wydatki, dochody, odchody">
	<meta name="author" content="Mateusz Przybycień">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<link rel="shortcut icon" href="img/logo.png">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>

<body>

	<header>
	
		<nav class="navbar navbar-light bg-secondary navbar-expand-lg">
		
			<a class="navbar-brand" href="#"><img src="img/logo.png" height="70" alt=""> </a>
		
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>
		
			<div class="collapse navbar-collapse" id="mainmenu">
			
				<ul class="navbar-nav mr-auto">
				
					<li class="nav-item ">
						<a class="nav-link" href="home.php"> Home </a>
					</li>
				
					<li class="nav-item dropdown ">
						<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true"> Zarządzaj kontem  </a>
						
						<div class="dropdown-menu" aria-labelledby="submenu">
							<a class="dropdown-item" data-toggle="modal" data-target="#changePassModal"> Zmień hasło </a>
                            <a class="dropdown-item" href="incomesCategories.php"> Kategorie przychodów </a>
                            <a class="dropdown-item" href="expensesCategories.php"> Kategorie wydatków </a>
                            <a class="dropdown-item" href="methodsCategories.php"> Kategorie metod płatności </a>
						</div>
						
					</li>
					
					<li class="nav-item">
						<a class="nav-link disabled" href="#"> O autorach </a>
					</li>
					
					<li class="nav-item ">
						<a class="nav-link" href="logOut.php"> 
                            <?php echo "Wyloguj się! (".$_SESSION['user'].")"; ?> 
                        </a>
					</li>
				</ul>
			</div>
		
		</nav>
	
	</header>

	<main>
        <!--<div class="mainContainer">-->
		<section class="tiles">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-4 btn btn-grey" data-toggle="modal" data-target="#incomeModal">
						<div class="tile">
							Dodaj przychód
							<div class="img1">
							<img src="img/income.png" alt="income">
							</div>
						</div>
						
					</div>
					<div class="col-md-4 btn btn-grey" data-toggle="modal" data-target="#expenseModal">
						<div class="tile">
							Dodaj wydatek
							<div class="img1">
								<img src="img/expense.png" alt="expense">
							</div>
						</div>
					</div>
					<div class="col-md-4 btn btn-grey">
						<a href="balance.html">
						<div class="tile">
							Pokaż bilans
							<div class="img1">
								<img src="img/chart.png" alt="chart">
							</div>
						</div>
						</a>
					</div>
					</div>
				</div>
		</section>
       <!-- </div>-->
		
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>

<div class="modal fade" id="incomeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Dodaj nowy przychód</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="addNewIncome.php" method="post">
      <div class="modal-body">
			Kwota transakcji, PLN: <br/> <input value="0" type="number" name="incomeAmount" step="0.01" min="0.01" /> <br/>
			Źródło przychodu: <br/>                 
                    <?php
                        require_once "connect.php";
                        mysqli_report(MYSQLI_REPORT_STRICT);
                        try
                        {
                            $connection =  @new mysqli($host, $db_user, $db_password, $db_name);
                            if ( $connection->connect_errno != 0 ) throw new Exception (mysqli_connect_errno());    
                            
                            $id = $_SESSION['id'];
                            $catergories = $connection->query
                            ("
                            SELECT name FROM incomes_category_assigned_to_users
                            WHERE incomes_category_assigned_to_users.user_id='$id'
                            ORDER BY name ASC
                            ");

                            if(!$catergories) throw new Exception($connection->error);
                            $howManyRows = $catergories->num_rows;

                            echo '<select name="incomeSource">';
                            for ($i = 1; $i <= $howManyRows; $i++) 
                            {
                                $categoryRecord = $catergories->fetch_assoc();
                                echo '<option>';
                                echo $categoryRecord['name'];
                                echo '</option>';
                            }
                            echo '</select>';
                            $connection->close();
                        }
                        catch(Exception $e)
                        {
                            echo '<span style="color:red;">Błąd serwera, przepraszamy za niedogodności</span>';
                            echo '<br />Informacja deweloperska:'.$e;
                        }
                    ?> 
                <br/>
                Data transakcji: <br />
                <input type="date" value="<?php echo date('Y-m-d'); ?>" name="incomeDate">  
                Komentarz do transakcji: <br/> <input type="text" name="incomeComment" placeholder="Twój komentarz" /> <br/>
		
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
        <!--<button type="submit" class="btn btn-primary">Dodaj!</button>-->
          <input type="submit" value="Dodaj!">
        </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Dodaj nowy wydatek</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="addNewExpense.php">
			Kwota transakcji, PLN: <br/> <input value="0" type="number" name="incomeAmount" step="0.01" min="0.01" /> <br/> 
			Data transakcji: <br/> <input type="date" name="date"  /> <br/>
			Cel: <br />
            <?php
                    require_once "connect.php";
                        mysqli_report(MYSQLI_REPORT_STRICT);
                        try
                        {
                            $connection =  @new mysqli($host, $db_user, $db_password, $db_name);
                            if ( $connection->connect_errno != 0 ) throw new Exception (mysqli_connect_errno());    
                            $id = $_SESSION['id'];
                        
                            $catergories = $connection->query
                            ("
                            SELECT name FROM expenses_category_assigned_to_users
                            WHERE expenses_category_assigned_to_users.user_id='$id'
                            ORDER BY name ASC
                            ");

                            if(!$catergories) throw new Exception($connection->error);
                            $howManyRows = $catergories->num_rows;

                            echo '<select name="expenseTarget">';
                            for ($i = 1; $i <= $howManyRows; $i++) 
                            {
                                $categoryRecord = $catergories->fetch_assoc();
                                echo '<option>';
                                echo $categoryRecord['name'];
                                echo '</option>';
                            }
                            echo '</select> <br />';
                            
                            
                            $catergories = $connection->query
                            ("
                            SELECT name FROM payment_methods_assigned_to_users
                            WHERE payment_methods_assigned_to_users.user_id='$id'
                            ORDER BY name ASC
                            ");

                            if(!$catergories) throw new Exception($connection->error);
                            $howManyRows = $catergories->num_rows;
                            echo 'Metoda płatności <br />';
                            echo '<select name="paymentMethods">';
                            for ($i = 1; $i <= $howManyRows; $i++) 
                            {
                                $categoryRecord = $catergories->fetch_assoc();
                                echo '<option>';
                                echo $categoryRecord['name'];
                                echo '</option>';
                            }
                            echo '</select>';
                            
                            $connection->close();
                        }
                        catch(Exception $e)
                        {
                            echo '<span style="color:red;">Błąd serwera, przepraszamy za niedogodności</span>';
                            echo '<br />Informacja deweloperska:'.$e;
                        }
                    ?> 
            Komentarz do transakcji: <br/> <input type="text" name="incomeComment" placeholder="Twój komentarz" /> <br/>
            
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
        <input type="submit" value="Dodaj!">
      </div>
    </form>
    </div>
  </div>
</div>
	
<div class="modal fade" id="changePassModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Zmiana hasła</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form action="changePass.php" method="post">
            <div class="modal-body">
            <input type="password" name="oldPassword" placeholder="Aktualne hasło" /> <br/>
			<input type="password" name="newPassword" placeholder="Nowe hasło" /> <br/>
			<input type="password" name="ConfirmedPassword" placeholder="Potwierdź hasło" /> <br/>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
            <input type="submit" value="Zmień!">
            </div>
            </div>
        </form>
    </div>
  </div>
</div>
<div class=error>
    <?php 
    if(isset($_SESSION['formError'])) echo $_SESSION['formError'];  
    unset ($_SESSION['formError']);
    ?>
</div>
<div class=success>
    <?php 
    if(isset($_SESSION['formSuccess'])) echo $_SESSION['formSuccess'];
    unset ($_SESSION['formSuccess']);
    ?>
</div>
</body>
</html>

