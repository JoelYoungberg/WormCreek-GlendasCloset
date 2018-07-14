<?php
session_start();
if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
    //If someone is logged in Do nothing here/Continue
} else {
        header("Location: ../login.php?login=error");//Otherwise return to login screen
        exit();
}

?>

<!DOCTYPE html>
<html lang="en-us" id="OpenPageBG">

<head>

<title>Inventory Entry Form: Costumes</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>

<img id="TitleBarPic" src="../Images/Site/Title.png"><br>
<div class="OpenPageOpacity">
<br><br><br>
<a href="../Inventory/Inventory.php"><button class="LargeButton" type="button">Inventory</button></a>&nbsp;&nbsp;
<a href="../Orders/OrderForm.php"><button class="LargeButton" type="button">New Orders</button></a><br><br>
<a href="../Missing/MissingSearchForm.php"><button class="LargeButton" type="button">Missing Items</button></a>&nbsp;&nbsp;
<a href="../OpenOrders/OpenOrdersSearchForm.php"><button class="LargeButton" type="button">Open Orders</button></a><br><br>
<a href="../Customer/CustomerSearchForm.php"><button class="LargeButton" type="button">Customers</button></a>&nbsp;&nbsp;
<a href="../OrderHistory/OrderHistorySearchForm.php"><button class="LargeButton" type="button">Order History</button></a><br>
<br>
<br>

<!--Logout Button -->
<form action="../Users/logout.php" method="POST">
<button type="submit" name="submit" Class="mediumbtnCancel mediumDropShadow">Logout</button>
</form>

</div>
</body>

</html>