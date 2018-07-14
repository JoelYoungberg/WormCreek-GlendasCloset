<?php
session_start();
if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
    //If someone is logged in Do nothing here/Continue
} else {
        header("Location: ../login.php?login=error");//Otherwise return to login screen
        exit();
}
$_SESSION['customerId'] = NULL;//Pulling down customer id
header("Location: OrderForm.php");

?>