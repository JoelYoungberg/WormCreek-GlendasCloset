<?php //Database Connection
ob_start();
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
    <title>Prop DropDown</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>


<?php

  $item_num = mysqli_real_escape_string($conn, $_GET['item_num']);//Grabbing item by sku/item num

  $status = $_POST['taskOption'] ?? '';//Grabbing dropdown selection and Throwing value into status variable for table

  switch($status){
    case 0:
    $status_word = 'available';
    break;
    case 1:
    $status_word = 'cleaning';
    break;
    case 2:
    $status_word = 'mending';
    break;
    case 3:
    $status_word = 'not available';
    break;
    case 4:
    $status_word = 'missing';
    break;

    default:
    $status_word = 'available';
  }

  //Getting automatically generated id from mysql database and plugging it into the mix!
  $query = "SELECT id
    FROM props
    WHERE item_num = '$item_num'
  ";

  $getInfo = mysqli_query($conn, $query)//Note: SQL Query returns object here
  or die('Error querying database for id');

  //Looping through object to get paticular id cell and value
  while ($row = mysqli_fetch_array($getInfo)) {
    $id = $row['id'];
  }

  $query = "UPDATE props
    SET status = '$status',
    status_word = '$status_word'
    
    WHERE id = '$id'";


    mysqli_query($conn, $query)
    or die('Error querying database for status change');

    header("Location: propSearchForm.php#$id");

    mysqli_close($conn);

ob_endflush();
?>

</body>
</html>

