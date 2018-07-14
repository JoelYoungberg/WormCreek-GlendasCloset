<?php 
ob_start();
//Note: This includes database connection code
    session_start();//Starting session here for costume added confirmation message
    if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
        //If someone is logged in Do nothing here/Continue
    } else {
            header("Location: ../login.php?login=error");//Otherwise return to login screen
            exit();
    }
    include '../db_connect.php';
?>


<!DOCTYPE html>
<html lang="en-us">

<head>
    <title>Missing Items</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<!--<script>
var conf = confirm("Are You Sure You Want To Delete This Entry? \n (Warning: Once you press OK this cannot be undone.)")
</script> -->

</head>
<body>

    <?php

        $item = $_GET['item'];//Grabbing id num

        
        //Matching up sku to sku in missing database table
           $query = "SELECT *
                        FROM missing
                        WHERE item = '$item'
                        ";

                $result = mysqli_query($conn, $query)//Note: processing query
                or die('Error querying database for id');

                //Getting particular row
                $row = mysqli_fetch_assoc($result);

                $itemCat = $row['itemCat'];

                
            
            ///Left off in here  
        //Before Deleting from missing table - change status in inventory to Available!
        $query = "UPDATE $itemCat
                              SET status = '2',
                                  status_word = 'mending'
                
                              WHERE item_num = '$item'
                             ";
    
                            mysqli_query($conn, $query)
                            or die('Error pushing to inventory');


        
        $query = "DELETE FROM missing
                         WHERE item = '$item'
                         ";         
        mysqli_query($conn, $query)
        or die('Error querying database for deletion');
        


       header("Location: ../Missing/MissingSearchForm.php?upload success");

        mysqli_close($conn);

        
   
        ob_end_flush();
	?><!--Close Php-->



</body>

</html>










    

  




