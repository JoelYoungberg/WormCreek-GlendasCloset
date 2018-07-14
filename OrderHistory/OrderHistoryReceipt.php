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
        $orderID = $_SESSION["orderID"];


        $sql = "SELECT *
                FROM returns
                WHERE orderID = $orderID
                ";

                $result = mysqli_query($conn, $sql);

                $row = mysqli_fetch_assoc($result); //Grabbing result of one row with order id#

                //Assigning needed variables from row values here:
                $itemNames = $row['itemNames'];
                $itemPrice = $row['itemPrices'];
                $items = $row['items'];
                $clerk = $row['clerk'];
                $last = $row['last'];
                $first = $row['first'];
                $business = $row['business'];
                $phone = $row['phone'];
                $email = $row['email'];
                $date = $row['date'];
                $dDate = $row['dDate'];
                $payStat = $row['payStat'];
                $amtPaid = $row['amtPaid'];
                $returnClerk = $row['returnClerk'];
                $total = $row['total'];

        //Declarations
        $skuArray = array();
        $item_name = array();
        $itemPrices = array();
    
        ?>
        

<?php
        //Exploding sku array for receipt
        //item sku Explode
        $skuArray = explode(",",$items);
        array_pop($skuArray);

        //Exploding item_name and itemPrices arrays for receipt
        //item name Explode
        $item_name = explode(",",$itemNames);
        array_pop($item_name);


        //itemPrices Explode
        $itemPrices = explode(",",$itemPrice);
        array_pop($itemPrices);

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
      
      <div class="InputInfo">
      <span class="BoldDate"><?php echo date_format(date_create($date),'M d, Y');?></span><br><!--Pulling Today's Date -->
      <?php echo $orderID;?><br><br><!--Pulling Id (id from return table) -->
      <span class="BoldDate"><?php echo date_format(date_create($dDate),'M d, Y');?></span><br><!--Pulling Due Date -->
      <?php echo $clerk;?><br><!--Pulling Order Processor Name -->
      <?php echo $returnClerk;?><!--Pulling Order Processor Name -->
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
              <td>' . $itemPrices[$i] . '</td>
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
            <td>' . $itemPrices[$i] . '</td>
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
          <?php echo "$" . number_format($total, 2);?>
          <br><br>
          <?php echo "$" . number_format($amtPaid, 2);?>
      </div>
      </span>
<!--End of Reciept Page /////////////////////////////////////////////-->
<?php

        $uid = $_SESSION['u_id'];
        //Clear all associated session variables + array to reset for new order
         session_unset();
         //Note - need to save and reinitialize login session variable
        $_SESSION['u_id'] = $uid; //Keeping user logged in


         //header("Location: ../OpeningPage/OpenPage.php?order='success'");

         ob_end_flush();
?>
