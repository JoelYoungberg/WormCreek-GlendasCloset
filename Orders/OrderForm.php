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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $(".confirmation3").fadeOut(10000);
    });
</script>

<script>//Auto submits form when leaving input field
function autoSubmit(){
    document.getElementById("clerkDate").submit();
}
</script>


</head>

<body>

<!--<img id="TitleBarPic" src="../Images/Site/OrderForm.png">No Pic on order page<br>-->
<div class="OrderPageWrap">
<div>
<a href="../Customer/CustomerSearchForm.php"><button class="mediumbtn">Select Customer</button></a>
<a href="../Customer/customer_input.php"><button class="mediumbtn">New Customer</button></a>
</div>
<br>
<div class="CustomerBox">
<?php //Grabbing selected customer id from url

    if(!empty($_GET['id'])){//Store Customer id in session variable
        $_SESSION["customerId"] = mysqli_real_escape_string($conn, $_GET['id']);//Grabbing customer by id
        }//End if not empty if statement 


        //Set id to session variable if it is not empty
        if(!empty($_SESSION["customerId"])){
            $id = $_SESSION["customerId"];
        }

        $skuArray = array(); //Creating sku array
        $picArray = array(); //Creating picture array
        $priceArray = array(); //Creating price array
        $item_name = array(); //Creating item name array
        $present = 0;
        //$totalPrice = 0;
        $subTotal = 0;


        //Pull customer data from sql database
        if(!empty($id)){
        $sql = "SELECT  first,
                    last,
                    business,
                    phone,
                    email
     

            FROM customer
            WHERE id = $id
            "; 

        $result = mysqli_query($conn, $sql);

        $row = mysqli_fetch_assoc($result); //Grabbing result of one name

        //Echo Results
       
        echo "<div><a href='clearCust.php'><button class='XbuttonCust'>X</button></a>
              
    <div class='CustomerOrderFont'>
      <span class='CustomerName'>" .$row['last']. ",
      " .$row['first']. "</span><br>
      " .$row['business']. "<br>
      " .$row['phone']. "<br>
      " .$row['email']. "

     </div>
        </div>";
        }//end if not empty id
    
?>
</div>

<div class="DateLine">
<?php echo "<div class='date'>" . date('M d, Y') . "</div>";?>
<br>

<!-- This form attaches to add item form/button -->
<form id="clerkDate" action="clerkDate.php" method="POST">
<input class="OutProcessor" onchange="autoSubmit()" type="text" name="clerk" placeholder="Order Processor Name" value="<?php if(isset($_SESSION['clerk'])){echo $_SESSION['clerk'];}?>">
<br>
<br>
Return Date
<input class="DueDateWidth" onchange="autoSubmit()" type="date" name="dDate" value="<?php if(isset($_SESSION['dDate'])){echo $_SESSION['dDate'];}?>">
</form>

<br>

</div>
<br><br>

    <form id="orderForm" action="LoadItem.php" method="POST">
    <div class="OrderFormSearchBGcolor">
      <input class="OrderFormInputBarShift" type="text" name="sku" size="30px" placeholder="Sku Number" form="orderForm">  
      </div>
		<button class="SkuSearchPosition btnSearch" type="submit" name="submit-search" form="orderForm">Add Item</button> <!--Search Button-->   
    </form> 

        <div id="placeHolder">
        <?php
        //If order was successfully submitted release message
        if(!empty($_GET['order'])){
            $_SESSION['message'] = "Order Successfully Submitted";
        }
        //If there is a status message - pull it from session storage
        if(!empty($_SESSION['message'])){
            $message = $_SESSION['message'];

	        echo "
	        <div class='confirmation3'>
		    $message
	        </div>
             ";
        }
        ?>
        </div>




<div class="listWidth">

<!--===============================================Display item list results here==================================================== -->

<?php
    if(!empty($_SESSION["present"])){
    $present = $_SESSION["present"];//Grabbing sku status
    if ($present = 0){
        echo "Sku not present in database";
    }//end if present = 0
 }//end if not empty

 if(!empty($_SESSION["item_name"])){
    $item_name = $_SESSION["item_name"];//Bringing our session array back out into ACTION!!
 }
 if(!empty($_SESSION["picArray"])){
    $picArray = $_SESSION["picArray"];//Bringing our session array back out into ACTION!!
 }
 if(!empty($_SESSION["priceArray"])){
    $priceArray = $_SESSION["priceArray"];//Bringing our session array back out into ACTION!!
 }
 if(!empty($_SESSION["skuArray"])){
    $skuArray = $_SESSION["skuArray"];//Bringing our session array back out into ACTION!!

    //Reversing our 4 parallel arrays for display to populate newest item on top
    $item_name = array_reverse($item_name);
    $picArray = array_reverse($picArray);
    $priceArray = array_reverse($priceArray);
    $skuArray = array_reverse($skuArray);

 

    for ($i = 0; $i < count($skuArray); $i++){
        echo "

            <div class='OrderItemList BlueLineDivide'>
            <span class='SkuMove'>".$skuArray[$i]."</span><br><span class='PriceMove'>$".$priceArray[$i]."</span> <img class='itemImg' src='".$picArray[$i]."'>
            <a href='deleteItem.php?index=$i'><button class='Xbutton'>X</button></a>
            </div>

        ";
    }//end for loop
 }//if not empty sku array

        //Clear session variable to stop multiple outputs
        $_SESSION['message'] = '';

?>

</div><!--Close div list width-->

<br>

<div class="SubTotal">
  <span class="SubWordingMove"><?php
  //Loop through price array and get total
    for($i = 0; $i < count($priceArray); $i++){
        $subTotal += $priceArray[$i];
    }
    echo "Subtotal: " . money_format('$%i', $subTotal);
    $_SESSION['subTotal'] = $subTotal;//Storing in session variable for database insert later
    ?></span>
</div>
<br>
  <!--Discount Form - Modifying total price based on radio selection -->
    <form class="discForm" action='discount.php' method='POST'>
       <div class="PercentMove">
        
        <input class='discForm' type='text' name='discount' size='20px' placeholder='Discount Amt/%' value="<?php if(isset($_SESSION['discount'])){echo $_SESSION['discount'];}?>">
       
        <input type="radio" name="discountType" value="percent" checked> Percentage<br>
        <div  class="AmountSelectMove">
        <input type="radio" name="discountType" value="amount"> Amount<br>
        </div>
      <div class="PercentButtons">
		<button class="btnsm" type='submit' name='submit-discount'>Apply</button> <!--Apply Button-->
        <button class="btnsmDelete" type='submit' name='submit-clear'>Clear</button> <!--Clear Button-->
    </div>
    </div>
    </form>

<br>
<br>

<div class="FinalTotalMove">
    <div class="FinalTotal">
  <?php
  //Discounted Total
    //If there is no discount, simply match total to subtotal
    $totalPrice = $subTotal;

    if(empty($_SESSION['totalPrice'])) {
        $_SESSION['totalPrice'] = $totalPrice; //If there is no discount -> set session to subtotal amount for complete order page
    }


    //If there is a discount, set total to amount determined by discount.php which was stored in totalPrice session variable
    //Grabbing any changes made to total price in discount.php
    if(!empty($_SESSION['totalPrice'])){
       $totalPrice = $_SESSION['totalPrice'];
    }


    echo "Recommended: " . money_format('$%i', $totalPrice);

    $_SESSION['total'] = $totalPrice; //Storing in separate session variable so it does not get cleared and passes to next page!

    $_SESSION['totalPrice'] = NULL;//Resetting session variable
    ?>
</div>


  <!-- Payment status and Complete Order-->
<form id="compOrder" class="compOrderForm" method="POST" action="completeOrder.php">
<select class="PaidStatus" name="payStat" form="compOrder">
        <option value="Pending" selected="selected">Pending</option>
        <option value="Paid">Paid</option>
        <option value="Exempt">Exempt</option>
    </select>
</form>
</div>


<div Class="AmountPaid">
Amount Donated:&nbsp;&nbsp;$
<input class="AmountPaidInput" id="compOrder" type='number' min="0.00" step="0.01" name='amtPaid' size='20px' placeholder='Amount Paid' form="compOrder">
</div>



    <input class="mediumbtn" type="submit" name="completeOrder" id="completeOrder" value="Complete Order" form="compOrder">
   

<form class="compOrderForm" method="post" action="cancelOrder.php">
    <input class="mediumbtnCancel" type="submit" name="cancelOrder" id="cancelOrder" value="Cancel Order">
</form>

</div>
</body>

</html>
