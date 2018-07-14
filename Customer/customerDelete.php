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
    <title>Customer Delete</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<!--<script>
var conf = confirm("Are You Sure You Want To Delete This Entry? \n (Warning: Once you press OK this cannot be undone.)")
</script> -->

</head>
<body>

    <?php

        $id = mysqli_real_escape_string($conn, $_GET['id']);//Grabbing id num

        //Getting automatically generated id from mysql database and plugging it into the mix!
           $query = "SELECT id
                        FROM customer
                        WHERE id = 'id'
                        ";

                $getInfo = mysqli_query($conn, $query)//Note: SQL Query returns object here
                or die('Error querying database for id');

                //Looping through object to get paticular id cell and value
                while ($row = mysqli_fetch_array($getInfo)) {
                $id = $row['id'];
                }
              
        $query = "DELETE FROM customer
                         WHERE id = '$id'
                         ";         
        mysqli_query($conn, $query)
        or die('Error querying database for deletion');


       header("Location: ../Customer/CustomerSearchForm.php?upload success");

        mysqli_close($conn);

        
   
        ob_end_flush();
	?><!--Close Php-->



</body>

</html>










    

  




