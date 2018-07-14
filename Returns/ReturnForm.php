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
<script>
$(document).ready(function(){
    $(".confirmation3").fadeOut(10000);
    });
</script>


</head>

<body>
<p id="test"></p>

<div class="OrderPageWrap">
<div class="CustomerBox">
<?php //Grabbing selected order id from url

    if(!empty($_GET['id'])){//Store order id in session variable
        $_SESSION["orderId"] = mysqli_real_escape_string($conn, $_GET['id']);//Grabbing order by id
        $return = mysqli_real_escape_string($conn, $_GET['return']);
        }//End if not empty if statement 


        //Set order id to session variable if it is not empty
        if(!empty($_SESSION["orderId"])){
            $id = $_SESSION["orderId"];
        }


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
        if(!empty($id)){
        $sql = "SELECT *
                FROM orders
                WHERE id = $id
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
        }//end if not empty id

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
Check-In Date:&nbsp;
</div>

<div class="ReturnFills">
<?php echo $id; ?><br>
<?php echo "<span class='bold'>" . date_format(date_create($row['date']),'M d, Y') . "</span>";?><br>
<?php echo "<span class='clerk'>" . $row['clerk'] . "</span>"; ?><br><br>
<?php echo "<span class='ReturnDate'>" . date('M d, Y') . "</span>";
    $_SESSION['inDate'] = date("y/m/d"); // Throwing today's date into session variable for next page 
?>


</div>
<!--/////////////////////End Order Info//////////////////////-->

<br><br>

<!-- This attaches to bottom return order form/button -->
<input class="ReturnProcessor2" type="text" name="returnClerk" placeholder="Return Processor Name" form="returnOrder">
</div><!--End ReturnDateLine-->


        <div id="placeHolder">
        <?php
       
        //If there is a status message - pull it from session storage
        if(!empty($_SESSION['message'])){
            $message = $_SESSION['message'];

	        echo "
	        <div class='confirmation3'>
		    $message
	        </div>
             ";
        }

         //Clear session variable to stop multiple outputs
         $_SESSION['message'] = '';

        ?>
        </div>

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
                    
                    <span class='SkuMove'>".$skuArray[$i]."</span><br><span class='StatusMove'>


                   
                    <select name='$i' ";	switch($row['status']){
							case 0:
							echo "style='color: #022702'";//Green
							break;
							case 1:
							echo "style='color: #ff9507'";//Orange
							break;
							case 2:
							echo "style='color: #ff9507'";//Orange
							break;
							case 3:
							echo "style='color: #760000'";//Red
							break;
							case 4:
							echo "style='color: #760000'";//Red
							break;
							default:
							echo "style='color: #022702'";//Green
						}//End Switch

					
					echo"
					
					class='selectReturns' form='returnOrder'>
					
					<!--============= Break in echo - If Database pull = dropdown value add selected to attributes, otherwise continue echo ===========-->

					  <option class='available'      ";		if($row['status'] == 0)	{echo "selected = 'selected'";} echo" value='0'>Available</option>

					  <option class='cleaning'          ";		if($row['status'] == 1)	{echo "selected = 'selected'";} echo" value='1'>Cleaning</option>

					  <option class='mending'        ";		if($row['status'] == 2)	{echo "selected = 'selected'";} echo" value='2'>Mending</option>

					  <option class='notavailable'  ";		if($row['status'] == 3)	{echo "selected = 'selected'";} echo" value='3'>Not Available</option>

					  <option class='missing'  ";		if($row['status'] == 4)	{echo "selected = 'selected'";} echo" value='4'>Missing</option>
					  

					</select> 
                    
                    </span> <img class='itemImgReturns' src='".$picArray[$i]."'>
                    
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
            
            echo "Subtotal: " . money_format('$%i', $subTotal);
            $_SESSION['subTotal'] = $subTotal;//Storing in session variable for database insert later
            ?>

          </span>
        </div>
        <br>
          <!--////////////////////Discount Form /////////////////////////////////- Modifying total price based on radio selection -->
          <!--First check if discount was set on initial renting-->
          <?php
           //If return is set - then set session variables to initial database values previously stored in database
             if(isset($return)){
                $_SESSION['totalPrice'] = $row['total'];
                $_SESSION['discountType'] = $row['discountType'];
                $_SESSION['discount'] = $row['discount'];
                //$_SESSION['amtPaid'] = $row['amtPaid'];
             }
          ?>


            <form class="discForm" action='discountReturns.php' method='POST'>
               <div class="PercentMove">
                
                  <input class='discForm' type='text' name='discount' size='20px' placeholder='Discount Amt/%' value="<?php if(isset($_SESSION['discount'])){echo $_SESSION['discount'];}?>">
               
                  <input type="radio" name="discountType" value="percent" <?php if($_SESSION['discountType'] == 'percent' || $_SESSION['discountType'] == NULL){echo 'checked';} ?>> Percentage<br>
                <div  class="AmountSelectMove">
                  <input type="radio" name="discountType" value="amount" <?php if($_SESSION['discountType'] == 'amount'){echo 'checked';} ?>> Amount <br>
                </div>

              <div class="PercentButtons">
                <button class="btnsm" type='submit' name='submit-discount'>Apply</button> <!--Apply Button-->
                <button class="btnsmDelete" type='submit' name='submit-clear'>Clear</button> <!--Clear Button-->
            </div>
            </div>
            </form>
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
        
            echo "Recommended: " . money_format('$%i', $totalPrice);
            ?>
        </div>
        
        
          <!-- Payment status and Complete Order-->
        
            <select class="PaidStatus" name="payStat" form="returnOrder">
                <option value="Pending" <?php if(isset($return) && $row['payStat'] == "Pending"){echo "selected = 'selected'";} ?>>Pending</option>
                <option value="Paid" <?php if(isset($return) && $row['payStat'] == "Paid"){echo "selected = 'selected'";} ?>>Paid</option>
                <option value="Exempt" <?php if(isset($return) && $row['payStat'] == "Exempt"){echo "selected = 'selected'";} ?>>Exempt</option>
            </select>
        
        </div>
        
        
        <div Class="AmountPaid">
        Amount Donated:&nbsp;&nbsp;$
        <input class="AmountPaidInput" type='number' min="0.00" step="0.01" name='amtPaid' size='20px' <?php if(isset($return)){echo "value = ". $row['amtPaid'] ."";}?> placeholder='Amount Paid' form="returnOrder">
        </div>
        <br>
<!-- Payment status and Return Order-->
<form id="returnOrder" method="POST" action="returnOrder.php">

    <input class="mediumbtn SaveContinueCancelSpacing" type="submit" name="returnOrder" id="returnOrder" value="Return Order">
    <input class="mediumbtn SaveContinueCancelSpacing" type="submit" name="cmReturn" value="Staff Return">
    <input class="mediumbtnCancel" type="submit" name="cancelReturn" id="cancelReturn" formaction="cancelReturn.php" value="Cancel Return">
</form>

</div>
</body>



</html>



