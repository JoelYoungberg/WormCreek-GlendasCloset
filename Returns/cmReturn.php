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
        $orderID = $_SESSION["orderId"];
        $customerId = $_SESSION["customerId"];//Setting customer id -note this is not setting
        $skuArray = $_SESSION["skuArray"];//Bringing our session array back
        
        $picArray = $_SESSION['picArray'];//Bringing pic array back
        
        $finalTotal = $_SESSION['totalPrice'];
        $subTotal = $_SESSION['subTotal'];
        $discount = $_SESSION['discount'];
        $discountType = $_SESSION['discountType'];
        $inDate = $_SESSION['inDate'];
        //$dDate = $_SESSION['dDate'];


        $sql = "SELECT *
                FROM orders
                WHERE id = $orderID
                ";

                $result = mysqli_query($conn, $sql);

                $row = mysqli_fetch_assoc($result); //Grabbing result of one name

                $item_name = $row['itemNames'];
                $itemPrices = $row['itemPrices'];


        //Pull everything from orders table for particular order being returned
        //Pull order data from sql database - orders table with session stored orderID from open orders list
        if(!empty($orderID)){
            $sql = "SELECT *
                    FROM orders
                    WHERE id = $orderID
                    "; 
    
            $result = mysqli_query($conn, $sql);
    
            $row = mysqli_fetch_assoc($result); //Grabbing result of one name - creating row array holding column names with associated values

            //Assigning needed variables from row values here:
            $clerk = $row['clerk'];
            $last = $row['last'];
            $first = $row['first'];
            $business = $row['business'];
            $phone = $row['phone'];
            $email = $row['email'];
            $date = $row['date'];
            $dDate = $row['dDate'];

        }//End if not empty id


        //Declarations
        $items = "";
        $props = 0;
        $cost = 0;
        $scrip = 0;
        $num = 0;
        $numCost = 0;
    
        $statusArray = array();
        $statusWordArray = array();

        //Post variables
        $payStat = $_POST['payStat'];
        $amtPaid = $_POST['amtPaid'];
        $returnClerk = $_POST['returnClerk'];

        /*
        if(empty($returnClerk)) {
            $_SESSION['message'] = 'Please Enter Return Processor Name';
            header("Location: ReturnForm.php?error=noReturnProcessor");//Otherwise return to login screen
            exit();
        }
        */

        //This loop is gathering all of the items statuses from the previous page (particularly if they have been changed)
        //And pushing them neatly into status array, mirroring the sku array. 
        for($i=0; $i<count($skuArray); $i++){
            $statusArray[] = $_POST[$i];
        }

        //Utilize skuArray and statusArray to set a status word array
        for($i=0; $i<count($skuArray); $i++){

            switch ($statusArray[$i]){
                case 0: 
                    $statusWordArray[] = 'available';
                break;
                case 1:
                    $statusWordArray[] = 'cleaning';
                break;
                case 2:
                    $statusWordArray[] = 'mending';
                break;
                case 3:
                    $statusWordArray[] = 'not available';
                break;
                case 4:
                    $statusWordArray[] = 'missing';
                break;

                default:
                   echo "Error: Not an item in drop down list...";
            }
        }


         //Pulling current date
         $returnDate = date("y/m/d");


        //Begin by looping through session array of sku's ordered and perform the following:
        //Inject entire session array into items column on return table 
        //along with customer info and date

        //Concatenating session array of return items into one variable
        for ($i = 0; $i < count($skuArray); $i++){
           $items .= $skuArray[$i] . ',';
        }//end for loop

         //Concatenating session array of return pics into one variable
         for ($i = 0; $i < count($picArray); $i++){
            $pics .= $picArray[$i] . ',';
         }//end for loop



        //Change inventory status for each item in sku array
        for ($i = 0; $i < count($skuArray); $i++){

            //First determine which table sku is in: costumes, props, or scripts?
            //For each iteration of loop check if there is a result on any of the three tables

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



            //If there is a result in a respective table = assign a number for switch statement below
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
            print_r($statusArray) . "<br>";
            echo $statusArray[$i] . "<br>";
            echo $statusWordArray[$i] . "<br>";
            echo $skuArray[$i] . "<br>";

            //Switch statement here
            switch ($num) {
                case 1:
                    //Costumes push
                    $query = "UPDATE costumes
                              SET status = $statusArray[$i],
                                  status_word = '$statusWordArray[$i]'
                
                              WHERE item_num = '$skuArray[$i]'
                             ";
    
                            mysqli_query($conn, $query)
                            or die('Error pushing to costumes');

                            //If an item has a status of missing (4) also push to missing items table
                            if($statusArray[$i] == 4) {
                                $sql = "INSERT INTO missing (date, last, first, phone, email, dDate, business, item, itemCat, pic)" .
                                "VALUES ('$date', '$last', '$first', '$phone', '$email', '$dDate', '$business', '$skuArray[$i]', 'costumes', '$picArray[$i]')";
                        
                                mysqli_query($conn, $sql)
                                or die('Error pushing values to database cost');
                            }
    
                            //At end of case, set query results to empty
                            $cost = 0;
                    break;
                case 2:
                    //Props Push
                    $query = "UPDATE props
                    SET status = $statusArray[$i],
                        status_word = '$statusWordArray[$i]'
      
                    WHERE item_num = '$skuArray[$i]'
                   ";

                   mysqli_query($conn, $query)
                   or die('Error pushing to props');

                   //If an item has a status of missing (4) also push to missing items table
                   if($statusArray[$i] == 4) {
                    $sql = "INSERT INTO missing (date, last, first, phone, email, dDate, business, item, itemCat, pic)" .
                    "VALUES ('$date', '$last', '$first', '$phone', '$email', '$dDate', '$business', '$skuArray[$i]', 'props', '$picArray[$i]')";
            
                    mysqli_query($conn, $sql)
                    or die('Error pushing values to database prop');
                }

                   //At end of case, set query results to empty
                   $prop = 0;
                    break;

                case 3:
                    //Scripts Push
                    $query = "UPDATE scripts
                    SET status = $statusArray[$i],
                        status_word = '$statusWordArray[$i]'
      
                    WHERE item_num = '$skuArray[$i]'
                   ";

                   mysqli_query($conn, $query)
                   or die('Error pushing to scripts');

                   //If an item has a status of missing (4) also push to missing items table
                   if($statusArray[$i] == 4) {
                    $sql = "INSERT INTO missing (date, last, first, phone, email, dDate, business, item, itemCat, pic)" .
                    "VALUES ('$date', '$last', '$first', '$phone', '$email', '$dDate', '$business', '$skuArray[$i]', 'scripts', '$picArray[$i]')";
            
                    mysqli_query($conn, $sql)
                    or die('Error pushing values to database script');
                }

                   //At end of case, set query results to empty
                   $scrip = 0;
                    break;
        
                default:
                   echo "Error: Item in cart not in database...";
            }//End Switch

         }//end for loop
?>
        

<?php
        //Exploding item_name and itemPrices arrays for receipt
        //item name Explode
        $item_name = explode(",",$item_name);
        array_pop($item_name);


        //itemPrices Explode
        $itemPrices = explode(",",$itemPrices);
        array_pop($itemPrices);

?>

      
      <?php 
      function dateDisplay($dateVal) {
        //Only display date if not null
                 if($dateVal == "Nov 30, -0001") {
                   return "<br>";
               } else {
                   return $dateVal;
               }
   }


 //Execute delete function on orders table removing order from open orders
         $sql = "DELETE FROM orders
                 WHERE id = $orderID
                ";

                mysqli_query($conn, $sql)
                or die('Error deleting order after return in open orders area');
    


        $uid = $_SESSION['u_id'];
        //Clear all associated session variables + array to reset for new order
         session_unset();
         //Note - need to save and reinitialize login session variable
        $_SESSION['u_id'] = $uid; //Keeping user logged in


         //header("Location: ../OpeningPage/OpenPage.php?order='success'");

         ob_end_flush();
?>
</div>

</body>

</html>
