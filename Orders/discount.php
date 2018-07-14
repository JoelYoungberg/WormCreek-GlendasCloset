<?php
//Database Connection
session_start();
if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
    //If someone is logged in Do nothing here/Continue
} else {
        header("Location: ../login.php?login=error");//Otherwise return to login screen
        exit();
}
include '../db_connect.php';

//Check if clear button was pressed
        if(isset($_POST['submit-clear'])){
            $discountType = 'none';
        } else {//if clear button was not pressed - then apply was pressed - calculate discount here
        //Get Discount and calculate it
        $calc = 0;
        $multiPercent = 0;
        //Grabing discount type and value here
        if(isset($_POST['discount'])){
           $discountType = $_POST['discountType'];
           $discount = $_POST['discount'];
           $totalPrice = $_SESSION['subTotal'];//Setting totalPrice to start as subtotal value
        }
    }//End else


        //Store discount type and discount in session variables for records
        $_SESSION['discountType'] = $discountType;
        $_SESSION['discount'] = $discount;

        switch($discountType){
            case none:
                //Set discount to zero (in case it was accidentally set)
                $_SESSION['discount'] = NULL;
                $_SESSION['totalPrice'] = $_SESSION['subTotal'];
                header("Location: OrderForm.php?discount='none'");
                echo "Testing case none";
                exit();
                break;

            case percent:
                //Modify Total by multiplying by percent and subtracting
                $multiPercent = $discount / 100; //Getting decimal value from percent for multiplication

                //Multiplying Total by discount decimal
                $calc = $multiPercent * $totalPrice;

                $totalPrice -= $calc; //Subtracting multiplied amount from total price to create discount

                //Plugging back into session for order page
                $_SESSION['totalPrice'] = $totalPrice;
                header("Location: OrderForm.php?discount='percent'");
                exit();
                break;

                case amount:
                //Set discount to simple subtraction of entered amount
                //Plugging back into session for order page
                $totalPrice -= $discount;

                $_SESSION['totalPrice'] = $totalPrice;
                header("Location: OrderForm.php?discount='amount'");
                exit();
                break;

                default:
                echo "Error, not a valid entry";
                header("Location: OrderForm.php?discount='none'");
                exit();
    }








?>