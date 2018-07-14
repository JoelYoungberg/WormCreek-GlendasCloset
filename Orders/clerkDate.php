<?php
session_start();
if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
    //If someone is logged in Do nothing here/Continue
} else {
        header("Location: ../login.php?login=error");//Otherwise return to login screen
        exit();
}

$clerk = $_POST['clerk'];//Grabbing clerk for session storage
$dDate = $_POST['dDate'];//Grabbing due date for session storage

//Storing these two in session for page refresh and complete order page
$_SESSION['clerk'] = $clerk;
$_SESSION['dDate'] = $dDate;

header("Location: OrderForm.php");

?>