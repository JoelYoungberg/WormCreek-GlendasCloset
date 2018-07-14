<?php
ob_start();
session_start();

if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
    //If someone is logged in Do nothing here/Continue
} else {
        header("Location: ../login.php?login=error");//Otherwise return to login screen
        exit();
}

$cancelReturn = $_POST['cancelReturn'];

if(isset($cancelReturn)){

        $uid = $_SESSION['u_id'];
        //Clear all associated session variables + array to reset for new order
         session_unset();
         //Note - need to save and reinitialize login session variable
        $_SESSION['u_id'] = $uid; //Keeping user logged in

    header("Location: ..//OpenOrders/OpenOrdersSearchForm.php?order='cancel'");
}



ob_end_flush();

?>