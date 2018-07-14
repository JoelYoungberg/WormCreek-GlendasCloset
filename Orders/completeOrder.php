<?php //Database Connection
ob_start();
        session_start();
        if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
            //If someone is logged in Do nothing here/Continue
        } else {
                header("Location: ../login.php?login=error");//Otherwise return to login screen
                exit();
        }
        include '../db_connect.php';
        date_default_timezone_set('America/Denver');
        
        //Session variables
        $customerId = $_SESSION["customerId"];//Setting customer id
        $skuArray = $_SESSION["skuArray"];//Bringing our session array back
        $priceArray = $_SESSION["priceArray"];//Bringing our session array back
        $picArray = $_SESSION['picArray'];//Bringing pic array back
        $item_name = $_SESSION["item_name"];//Bringing back array of item names
        $finalTotal = $_SESSION['total'];
        $subTotal = $_SESSION['subTotal'];
        $discount = $_SESSION['discount'];
        $discountType = $_SESSION['discountType'];
        $clerk = $_SESSION['clerk'];
        $dDate = $_SESSION['dDate'];


        //Declarations
        $items = "";
        $props = 0;
        $cost = 0;
        $scrip = 0;
        $num = 0;
        $numCost = 0;
        $orderID = 0;

        //Post variables
        $payStat = $_POST['payStat'];
        $amtPaid = $_POST['amtPaid'];


         //Pulling current date
         $date = date("y/m/d");


        //Begin by looping through session array of sku's ordered and perform the following:
        //Inject entire session array into items column on order table 
        //along with customer info and date

        //Concatenating session array of order items into one variable
        for ($i = 0; $i < count($skuArray); $i++){
           $items .= $skuArray[$i] . ',';
        }//end for loop

         //Concatenating session array of order items into one variable
         for ($i = 0; $i < count($picArray); $i++){
            $pics .= $picArray[$i] . ',';
         }//end for loop

          //Concatenating session array of order items into one variable
          for ($i = 0; $i < count($item_name); $i++){
            $itemNames .= $item_name[$i] . ',';
         }//end for loop

          //Concatenating session array of order items into one variable
          for ($i = 0; $i < count($priceArray); $i++){
            $prices .= $priceArray[$i] . ',';
         }//end for loop


         //Sort arrays by sku (skuArray) and have all parallel arrays follow!
         array_multisort($skuArray,$picArray,$item_name,$priceArray);


        //Pulling customer info with id
        $query = "SELECT *
                  FROM customer
                  WHERE id = $customerId
                  ";

        $result = mysqli_query($conn, $query);
        if(empty($result)){
            $_SESSION['message'] = 'No customer selected...';
            header("Location: OrderForm.php?error=nocustomer");//Otherwise return to login screen
            exit();
        }

        if(empty($clerk)) {
            $_SESSION['message'] = 'Please Enter an Order Processor Name';
            header("Location: OrderForm.php?error=noProcessor");//Otherwise return to login screen
            exit();
        }

        if(empty($dDate)) {
            $_SESSION['message'] = 'Please Enter a Due Date';
            header("Location: OrderForm.php?error=noDueDate");//Otherwise return to login screen
            exit();
        }

        //Fetch number of results
        $queryResults = mysqli_num_rows($result);

        if ($queryResults > 0) {//Output Results only if greater than zero rows in database - otherwise do nothing
			while ($row = mysqli_fetch_assoc($result)){//Getting customer info
                $last = $row['last'];
                $first = $row['first'];
                $business = $row['business'];
                $phone = $row['phone'];
                $email = $row['email'];
            }//End while loop
        }//End if statement
            

        //////////////////Inserting customer and order info/////////////////////////////////////////////
        $query = "INSERT INTO orders (date, last, first, business, phone, email, items, dDate, clerk, payStat, total, discount, discountType, subTotal, amtPaid, pics, itemNames, itemPrices)" .
        "VALUES ('$date', '$last', '$first', '$business', '$phone', '$email', '$items', '$dDate', '$clerk', '$payStat', '$finalTotal', '$discount', '$discountType', '$subTotal', '$amtPaid', '$pics', '$itemNames', '$prices')";

        mysqli_query($conn, $query)
        or die('Error pushing values to database');

        //Get and store newly created order id in session variable for receipt page 
        //Getting from order table
        //Getting automatically generated id from mysql database and plugging it into the mix!
        $query = "SELECT id
        FROM orders
        WHERE first = '$first'
        AND   last = '$last'
        AND   email = '$email'
        AND   items = '$items'
        AND   date = '$date'
        AND   clerk = '$clerk'
        AND   total = '$finalTotal'
        ";

        $getInfo = mysqli_query($conn, $query)//Note: SQL Query returns object here
        or die('Error querying database for id');

        //Looping through object to get paticular id cell and value
        while ($row = mysqli_fetch_array($getInfo)) {
        $orderID = $row['id'];
        }

        //Storing Order ID for Receipt Print Page auto pop up -- we will clear this individually on that page
        $_SESSION['orderID'] = $orderID;

        //Increment rent num and push date for each item in sku array
        for ($i = 0; $i < count($skuArray); $i++){

            //First determine which table sku is in: costumes, props, or scripts?

            //Is it in costumes?
            $query = "SELECT *
                      FROM costumes
                      WHERE item_num = '$skuArray[$i]'
                    ";
            $cost = mysqli_query($conn, $query);
            //Fetch number of results
            $numCost = mysqli_num_rows($cost);


            //Is it in props?
            $query = "SELECT *
                      FROM props
                      WHERE item_num = '$skuArray[$i]'
                    ";
            $prop = mysqli_query($conn, $query);
            //Fetch number of results
            $numProp = mysqli_num_rows($prop);


            //Is it in scripts?
            $query = "SELECT *
                      FROM scripts
                      WHERE item_num = '$skuArray[$i]'
                    ";
            $scrip = mysqli_query($conn, $query);
            //Fetch number of results
            $numScrip = mysqli_num_rows($scrip);




            //Setting num to 1 2 or 3 for costume, prop, script - respectively
            if($numCost > 0){
               // echo "Costume detected<br>";
                $num = 1;
            }

            if ($numProp > 0) {
               // echo "Prop detected<br>";
                $num = 2;
            }

            if ($numScrip > 0) {
               // echo "Script detected<br>";
                $num = 3;
            }



            //Switch statement here
            switch ($num) {
                case 1:
                    //Costumes push
                    $query = "UPDATE costumes
                              SET status = 3,
                                  status_word = 'not available',
                                  rent_num = rent_num + 1,
                                  last_rent = '$date'
                              WHERE item_num = '$skuArray[$i]'
                             ";
    
                            mysqli_query($conn, $query)
                            or die('Error incrementing costumes');
    
                            //At end of case, set query results to empty
                            $cost = 0;
                    break;
                case 2:
                    //Props Push
                     $query = "UPDATE props
                     SET status = 3,
                         status_word = 'not available',
                         rent_num = rent_num + 1,
                         last_rent = '$date'
                     WHERE item_num = '$skuArray[$i]'
                    ";

                   mysqli_query($conn, $query)
                   or die('Error incrementing costumes');

                   //At end of case, set query results to empty
                   $prop = 0;
                    break;

                case 3:
                    //Scripts Push
                     $query = "UPDATE scripts
                     SET status = 3,
                         status_word = 'not available',
                         rent_num = rent_num + 1,
                         last_rent = '$date'
                     WHERE item_num = '$skuArray[$i]'
                    ";

                   mysqli_query($conn, $query)
                   or die('Error incrementing costumes');

                   //At end of case, set query results to empty
                   $scrip = 0;
                    break;
        
                default:
                   echo "Error: Item in cart not in database...";
            }//End Switch

         }//end for loop
?>
         <!--///////////////////////////////////////////// RECEIPT PAGE OUTPUT HERE /////////////////////////////////////////////////-->

<!DOCTYPE html>
<html lang="en-us">

<head>

<title>Receipt</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../style.css">
<script>
function myFunction() {
    window.print();
}
</script>
</head>

<body>


<button class="noPrint btnsm" onclick="myFunction()">Print</button>
<button class="noPrint btnsm" onclick="location.href='../OpeningPage/OpenPage.php';">Close</button>
<br>
 
<div class="pagesize">

<div class="GlendaText">
Glenda's Closet
</div>

<div class="ReceiptText">
Receipt
</div>

<br>

<?php echo"
<div class='RenterInfo'>
<span class='BoldName'> $first $last </span><br>"
.$business."<br>"
.$phone. "<br>"
.$email. "
</div>
";
?>

<div class="InvoiceInfo">
DATE:<br>
INVOICE#:<br><br>
DUE DATE:<br>
ORDER PROCESSOR:<br>
RETURN PROCESSOR:
</div>

<?php
    //Re-fetching dDate because it did not come through
    $sql = "SELECT dDate
            FROM orders
            WHERE id = $orderID;
            ";

        $result = mysqli_query($conn,$sql);

        //Looping through object to get paticular id cell and value
        while ($row = mysqli_fetch_array($result)) {
            $dDate = $row['dDate'];
            }

            function dateDisplay($dateVal) {
                //Only display date if not null
                         if($dateVal == "Nov 30, -0001") {
                           //Do Nothing
                       } else {
                           return $dateVal;
                       }
           }

?>


<div class="InputInfo">
<span class="BoldDate"><?php echo date('M d, Y');?></span><br><!--Pulling Today's Date -->
<?php echo $orderID;?><br><br><!--Pulling Order Id (id from auto increment id field on order table) -->
<span class="BoldDate"><?php echo dateDisplay(date_format(date_create($dDate),'M d, Y'));?></span><br><!--Pulling Due Date -->
<?php echo $clerk;?><br><!--Pulling Order Processor Name -->
<br>
</div>
<br>


<div class="clear"></div><!--/////////////////////////////Clearing Header Material before table//////////////////////////////////-->

<!--Table 1 Left hand static headings-->

<table class="table1">
  <tr>
    <th>Item Sku</th>
    <th>Item Name</th> 
    <th>Amount</th>
  </tr>

<!--Populate items here - odd side-->
<?php 
  for($i=0; $i < count($skuArray); $i += 2){
    echo'
      <tr>
        <td>' . $skuArray[$i] . '</td>
        <td>' . $item_name[$i] . '</td>
        <td>' . $priceArray[$i] . '</td>
      </tr>
    ';//End echo
  }//End for loop
?>

</table><!--End Table Outside of loop and php we don't want to loop the table close-->


<!--Table 2 Right hand side -->

<table class="table2">
  <tr>
    <th>Item Sku</th>
    <th>Item Name</th>
    <th>Amount</th>
  </tr>

  <?php 
  for($i=1; $i < count($skuArray); $i += 2){
    echo'
      <tr>
      <td>' . $skuArray[$i] . '</td>
      <td>' . $item_name[$i] . '</td>
      <td>' . $priceArray[$i] . '</td>
      </tr>
    ';//End echo
  }//End for loop
?>

</table><!--End Table Outside of loop and php we don't want to loop the table close-->



<br><br><br>
<span class="RightSide">

<div class="TotalItems"> 
Number of Items:
<br><br>
Recommended Donation:
<br><br>
Total Donated:
</div>

<!--Totals information-->
<div class="TotalItemsInfo">
    <?php echo count($skuArray);?>
    <br><br>
    <?php echo "$" . number_format($finalTotal, 2);?>
    <br><br>
    <?php echo "$" . number_format($amtPaid, 2);?>
</div>
</span>

<?php
$uid = $_SESSION['u_id'];
        //Clear all associated session variables + array to reset for new order
         session_unset();
         //Note - need to save and reinitialize login session variable
        $_SESSION['u_id'] = $uid; //Keeping user logged in


         //header("Location: OrderForm.php?order='success'");

         ob_end_flush();
?>
<br>

</div>


</body>

</html>