<?php
session_start();

$_SESSION['defaultFilter'] = true;

$_SESSION['transactionType']=$_POST['transactionType'];
$_SESSION['peroidFlag']=$_POST['peroid'];
$_SESSION['date1']=$_POST['date1'];
$_SESSION['date2']=$_POST['date2'];

header('Location: balance.php');
?>