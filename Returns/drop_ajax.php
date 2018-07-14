<?php
session_start();



$statusArray = $_SESSION['statusArray'];


//Pulling AJAX call from Return From Dropdown
$i = intval($_GET['i']);

$v = intval($_GET['v']);

//Echo for testing
echo "$i";
echo "$v";



$statusArray[$i] = $v;

//Throwing array into session storage for later use
$_SESSION['statusArray'] = $statusArray;


?>