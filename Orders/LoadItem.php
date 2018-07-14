<?php //Database Connection
        session_start();
        ob_start();
        if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
            //If someone is logged in Do nothing here/Continue
        } else {
                header("Location: ../login.php?login=error");//Otherwise return to login screen
                exit();
        }
        include '../db_connect.php';
?>

<!--Adding html header to get css link-->
<!DOCTYPE html>
<html lang="en-us">

    <head>

        <title>Order Item</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../style.css">

        <script>
   
        </script>

</head>

<body></body>


<?php
        $sku = $_POST['sku'];//Getting Sku from previous page/form submit

        //Defining variables
        $numResultRowsCost = 0;
        $numResultRowsProps = 0;
        $numResultRowsScripts = 0;
        $query = '';
        $skuArray = $_SESSION["skuArray"];//Bringing our session array back out into ACTION!!
        $picArray = $_SESSION["picArray"];//Bringing our session array back out into ACTION!!
        $priceArray = $_SESSION["priceArray"];//Bringing our session array back out into ACTION!!
        $item_name = $_SESSION['item_name'];
        $_SESSION["message"] = '';//Clearing message session variable
        $avail = 0;
        
        $caseNum = 0;


        //Call to database and check all three tables against sku for existence


        //Costume database check
        $query = "SELECT picture, price, status, item_name
                  FROM costumes
                  WHERE item_num = '$sku'
        ";
        $result = mysqli_query($conn, $query)
        or die('Error querying costume database');
        $numResultRowsCost = mysqli_num_rows($result);//Extracting number of result rows from costume table
        
        if ($numResultRowsCost > 0){
            $row = mysqli_fetch_array($result);//Breaking Object into array to extract string from column name
            $avail = $row['status'];
        }

        //Props database check
        $query = "SELECT picture, price, status, item_name
                  FROM props
                  WHERE item_num = '$sku'
        ";
        $result = mysqli_query($conn, $query)
        or die('Error querying prop database');
        $numResultRowsProps = mysqli_num_rows($result);//Extracting number of result rows from costume table
        if ($numResultRowsProps > 0){
            $row = mysqli_fetch_array($result);//Breaking Object into array to extract string from column name
            $avail = $row['status'];
        }

        //Scripts database check
        $query = "SELECT picture, price, status, item_name
                  FROM scripts
                  WHERE item_num = '$sku'
        ";
        $result = mysqli_query($conn, $query)
        or die('Error querying script database');
        $numResultRowsScripts = mysqli_num_rows($result);//Extracting number of result rows from costume table
        if ($numResultRowsScripts > 0){
            $row = mysqli_fetch_array($result);//Breaking Object into array to extract string from column name
            $avail = $row['status'];
        }


        //Check 1 If there is not a matching sku in the database, return to Order Form Without adding to array - display message
        if ($numResultRowsCost + $numResultRowsProps + $numResultRowsScripts <= 0){
            $caseNum = 1;
        } 

        //If sku is already in checkout - it cannot be added again!
        if(in_array($sku,$skuArray)){
            $caseNum = 2;
          }

          //If status is not available(3) or missing(4) set case num to 4
          if($avail == 3 || $avail == 4){
            $caseNum = 4;
           }
       
        //Check 3 - If there is a matching sku in the database and it is not already in the list, and is available, add it to the array
         if ($numResultRowsCost + $numResultRowsProps + $numResultRowsScripts > 0 && $caseNum != 2 && $caseNum != 4){
                $caseNum = 3;
           } 

         
   

        
        switch($caseNum) {
            case 1:
                //Sku not present in database
                $_SESSION["present"] = 0;
                $_SESSION['message'] = "Sku # not present in database";
                header("Location: OrderForm.php");
                break;
            case 2:
                $_SESSION['message'] = "Sku already in checkout, check for typo or choose another";
                header("Location: OrderForm.php");
                break;
            case 3:
                //there is a matching sku in the database and it is not already in the list
                $_SESSION['message'] = "Item added to order";
                

                //Adding new item to arrays
                $skuArray[] = $sku;
                $picArray[] = $row['picture'];
                $priceArray[] = $row['price'];
                $item_name[] = $row['item_name'];

                $_SESSION["skuArray"] = $skuArray;//Putting modified arrays back into session storage after adding item sku and pic url
                $_SESSION["picArray"] = $picArray;//Putting modified arrays back into session storage after adding item sku and pic url
                $_SESSION["priceArray"] = $priceArray;//Putting modified arrays back into session storage after adding item sku and pic url
                $_SESSION["item_name"] = $item_name;//Storing item name for receipt page

                if(!empty($_SESSION['customerId'])){
                    $id = $_SESSION['customerId'];//Pulling down customer id
                    header("Location: OrderForm.php?=$id");
                } else {
                    header("Location: OrderForm.php");
                }

                break;
            case 4:
                $_SESSION['message'] = "Item is not available - please check status...";
                header("Location: OrderForm.php");
                break;
            
            default:
            echo "Default case triggered";
            header("Location: OrderForm.php");
        }
        ob_end_flush();
?>

</html>