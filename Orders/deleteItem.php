<?php
    session_start();
    if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
        //If someone is logged in Do nothing here/Continue
    } else {
            header("Location: ../login.php?login=error");//Otherwise return to login screen
            exit();
    }
    include '../db_connect.php';
    
    //Defining arrays
    $skuArray = array(); //Creating sku array
    $picArray = array(); //Creating picture array
    $priceArray = array(); //Creating price array

    //Get array index
    $index = mysqli_real_escape_string($conn, $_GET['index']);//Grabbing array index to delete

        $skuArray = $_SESSION["skuArray"];//Bringing our session array back out into ACTION!!
        $picArray = $_SESSION["picArray"];//Bringing our session array back out into ACTION!!
        $priceArray = $_SESSION["priceArray"];//Bringing our session array back out into ACTION!!

        unset($skuArray[$index]);//Removing deleted index
        $skuArray = array_values($skuArray);//Re-Indexing Array

        unset($picArray[$index]);//Removing deleted index
        $picArray = array_values($picArray);//Re-Indexing Array

        unset($priceArray[$index]);//Removing deleted index
        $priceArray = array_values($priceArray);//Re-Indexing Array

        $_SESSION["skuArray"] = $skuArray;//Putting modified arrays back into session storage after adding item sku and pic url
        $_SESSION["picArray"] = $picArray;//Putting modified arrays back into session storage after adding item sku and pic url
        $_SESSION["priceArray"] = $priceArray;//Putting modified arrays back into session storage after adding item sku and pic url

        header("Location: OrderForm.php");
?>