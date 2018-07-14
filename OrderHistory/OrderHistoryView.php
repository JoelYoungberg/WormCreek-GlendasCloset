<?php //Database Connection
        session_start();
        if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
            //If someone is logged in Do nothing here/Continue
        } else {
                header("Location: ../login.php?login=error");//Otherwise return to login screen
                exit();
        }
        include '../db_connect.php';
        date_default_timezone_set('America/Denver');
?>

<!DOCTYPE html>
<html lang="en-us" id="NewOrderBG">

<head>

<title>New Order</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../style.css">

<!-- Including google hosted jquery libraries for AJAX -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


</head>

<body>
<p id="test"></p>

<div class="OrderPageWrap">
<div class="CustomerBox">
<?php //Grabbing selected order id from url

        $id = mysqli_real_escape_string($conn, $_GET['id']);//Grabbing order by id

        $_SESSION['orderID'] = $id; // Setting id to session for receipt page
        


        //Array declarations
        $item_name = array();//Creating item names array
        $itemPrices = array(); //Creating item prices array
        $skuArray = array(); //Creating sku array
        $picArray = array(); //Creating picture array
        $statusArray = array(); //Creating status array
       
        $present = 0;
        //$totalPrice = 0;
        $subTotal = 0;


        //Pull order data from sql database - orders table
        $sql = "SELECT *
                FROM returns
                WHERE orderID = $id
                "; 

        $result = mysqli_query($conn, $sql);

        $row = mysqli_fetch_assoc($result); //Grabbing result of one name

        //Echo Customer Data//////////////////////////////////////
       
        echo "<div>
              
    <div class='CustomerOrderFont'>
      <span class='CustomerName'>" .$row['last']. ",
      " .$row['first']. "</span><br>
      " .$row['business']. "<br>
      " .$row['phone']. "<br>
      " .$row['email']. "

     </div>
        </div>";

    //End Customer Data//////////////////////////////////////
?>
</div>
<!--/////////////////////Order Info//////////////////////-->
<div class="ReturnDateLine">
<span class="blackline"></span>
<div class="ReturnInfo">
Order Number:&nbsp;<br>
Check-Out Date:&nbsp;<br>
Order Processor:&nbsp;<br>
<span class="blackline"></span>
Check-In Date:&nbsp;<br>
<span class="bold">Return Processor:&nbsp;</span>
</div>

<div class="ReturnFills">
<?php echo $id; ?><br>
<?php echo "<span class='bold'>" . date_format(date_create($row['date']),'M d, Y') . "</span>";?><br>
<?php echo "<span class='clerk'>" . $row['clerk'] . "</span>"; ?><br><br>
<?php echo "<span class='ReturnDate'>" . date('M d, Y') . "</span>";?><br>
<?php echo "<span class='clerk'>" . $row['returnClerk'] . "</span>"; ?>
</div>
<!--/////////////////////End Order Info//////////////////////-->

<br><br>

<!-- This attaches to bottom return order form/button -->
 
</div><!--End ReturnDateLine-->


        <div id="placeHolder">
        <!--Note: This is a great test spot-->
        </div><!--Leaving this div in here for placement only - no longer functions as message place-->

        <!--Pulling Order Items Into Session Storage for return-->
        <?php
        //First we need to de-concatenate this string by breaking it on commas and injecting the results into the sku session array
        //Sku Explode
        $skuArray = explode(",",$row['items']);
        array_pop($skuArray);

        $_SESSION['skuArray'] = $skuArray; //Throwing into session array

        //Repeat for pics explode
        $picArray = explode(",",$row['pics']);
        array_pop($picArray);

        $_SESSION['picArray'] = $picArray; //Throwing into session array

        //Repeat for prices explode
        $priceArray = explode(",",$row['itemPrices']);
        array_pop($priceArray);

        $_SESSION['priceArray'] = $priceArray; //Throwing into session array


        //Initialize status array to length of sku array and set all values to available (or zero)
        //Then send to session for ajax

        for($i=0; $i<count($skuArray); $i++) {
            $statusArray[] = 0;
        }

        //$_SESSION['statusArray'] = $statusArray;

        ?>





        <div class="listWidth">
        
        <!--===============================================Display item list results here==================================================== -->
        
        <?php
        
         if(!empty($_SESSION["item_name"])){
            $item_name = $_SESSION["item_name"];//Bringing our session array back out into ACTION!!
         }
         if(!empty($_SESSION["picArray"])){
            $picArray = $_SESSION["picArray"];//Bringing our session array back out into ACTION!!
         }
       
         if(!empty($_SESSION["skuArray"])){
            $skuArray = $_SESSION["skuArray"];//Bringing our session array back out into ACTION!!
         ?>


        
        <?php
            for ($i = 0; $i < count($skuArray); $i++){//Looping and outputting sku array with dropdown here
                echo "

               
        
                <div class='OrderItemList BlueLineDivide'>
                <span class='SkuMove'>".$skuArray[$i]."</span><br><span class='PriceMove'>$".$priceArray[$i]."</span> <img class='itemImg' src='".$picArray[$i]."'>
                </div>
        
                ";
            }//end for loop
         }//if not empty sku array
        
        ?>



        </div><!--Close div list width-->
        
        <br>

        <!-- /////////////////  Discount Form and Totals  ////////////////////////////////// -->
        <!-- /////////////////                            ////////////////////////////////// -->

        
        <div class="SubTotal">
          <span class="SubWordingMove">

            <?php
            //Get total from above database pull
            $subTotal = $row['subTotal'];
            echo "Subtotal: $" . $subTotal;
            ?>

          </span>
        </div>
        <br>
          <!--////////////////////Discount Form /////////////////////////////////- Modifying total price based on radio selection -->
          <!--First check if discount was set on initial renting-->

            <div class="discForm2">
                             
            &nbsp;&nbsp; Discount Amount:&nbsp;<?php echo $row['discount'];?> 
                 <br>
               
            &nbsp;&nbsp; Discount Type:<?php echo $row['discountType'];?>
              </div>
        <!--/////////////////////End Discount Form///////////////////////-->
        <br>
        <br>
        
        <div class="FinalTotalMove">
            <div class="FinalTotal">
          <?php
          
          //Discounted Total
            //Grabbing any changes made to total price in discount.php
            if(isset($_SESSION['totalPrice'])){
               $totalPrice = $_SESSION['totalPrice'];
            } else {
                $totalPrice = $subTotal;
            }
        
            echo "Recommended: " . money_format('$%i', $totalPrice) . "<span class='PayStatHistory'>" . $row['payStat'] . "</span>";
            ?>
        </div>
            
        
        </div>
        
        
        <div Class="AmountPaid">
        Amount Donated:&nbsp;&nbsp;$ <?php echo $row['amtPaid'];?>
        </div>
<!-- Payment status and Return Order-->

    <a href="OrderHistoryReceipt.php"><button class="mediumbtn">Print</button></a>

    <a href="OrderHistorySearchForm.php"><button class="mediumbtn">Cancel</button></a>

</div>
</body>



</html>



