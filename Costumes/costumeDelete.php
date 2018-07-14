<?php //Note: This includes database connection code
ob_start();
    session_start();
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
    <title>Costume Delete</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Costume.css">

<!--<script>
var conf = confirm("Are You Sure You Want To Delete This Entry? \n (Warning: Once you press OK this cannot be undone.)")
</script> -->

</head>
<body>

    <?php

        $item_num = mysqli_real_escape_string($conn, $_GET['item_num']);//Grabbing item by sku/item num

        //Getting automatically generated id from mysql database and plugging it into the mix!
           $query = "SELECT id
                        FROM costumes
                        WHERE item_num = '$item_num'
                        ";

                $getInfo = mysqli_query($conn, $query)//Note: SQL Query returns object here
                or die('Error querying database for id');

                //Looping through object to get paticular id cell and value
                while ($row = mysqli_fetch_array($getInfo)) {
                $id = $row['id'];
                }
              
        $query = "DELETE FROM costumes
                         WHERE item_num = '$item_num'
                         AND id = $id
                         ";         
        mysqli_query($conn, $query)
        or die('Error querying database for deletion');


        $Del_jpg = '../Uploads/Costumes/' . $item_num . '.jpg';
        $Del_jpeg = '../Uploads/Costumes/' . $item_num . '.jpeg';
        $Del_png = '../Uploads/Costumes/' . $item_num . '.png';

        
        echo "<script type='text/javascript'>alert('$Del_jpg');</script>";

        unlink($Del_jpg);


        header("Location: ../Costumes/CostumeSearchForm.php?upload success");

        mysqli_close($conn);

        
   
ob_end_flush();
	?><!--Close Php-->



</body>

</html>










    

  




