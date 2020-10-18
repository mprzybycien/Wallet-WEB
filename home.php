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
						<a class="nav-link" href="home.html"> Home </a>
					</li>
				
					<li class="nav-item dropdown ">
						<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true"> Zarządzaj kontem  </a>
						
						<div class="dropdown-menu" aria-labelledby="submenu">
							<a class="dropdown-item" data-toggle="modal" href="#" data-target="#changePassModal"> Zmień hasło </a>
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
      <div class="modal-body">
        <form>
			Kwota transakcji, PLN: <br/> <input type="text" name="amount" placeholder="Kwota" /> <br/>
			Data transakcji: <br/> <input type="date" name="date"  /> <br/>
			Rodzaj transakcji: <br/> <select class="payment-describe">
				<option>Pensja</option>
				<option>Odsetki lokaty</option>
				<option>Prowizja</option>
				<option>Premia</option>
			</select> <br/>
			Forma płatnosci: <br/> <select>
				<option>Gotówka</option>
				<option>Przelew</option>
			</select>
		</form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
        <button type="button" class="btn btn-primary">Dodaj!</button>
      </div>
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
        <form>
			Kwota transakcji, PLN: <br/> <input type="text" name="amount" placeholder="Kwota" /> <br/>
			Data transakcji: <br/> <input type="date" name="date"  /> <br/>
			Cel: <br/> <select class="payment-describe">
				<option>Czynsz</option>
				<option>Prąd</option>
				<option>Kablówka</option>
				<option>Żywność</option>
				<option>Ubrania</option>
				<option>Rozrywki</option>
			</select> <br/>
			Forma płatnosci: <br/> <select>
				<option>Płatność kartą</option>
				<option>Płatność gotówką</option>
				<option>Przelew</option>
			</select>
		</form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
        <button type="button" class="btn btn-primary">Dodaj!</button>
      </div>
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
      <div class="modal-body">
        <form>
			<input type="password" name="newPassword" placeholder="Nowe hasło" /> <br/>
			<input type="password" name="ConfirmPassword" placeholder="Potwierdź hasło" /> <br/>
		</form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
          <button type="button" class="btn btn-primary">Zmień hasło!</button>
      </div>
    </div>
  </div>
</div>	    
</body>
</html>