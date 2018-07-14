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
<html lang="en-us" id="IventoryPageBG">

<head>

<title>Inventory Entry Form: Costumes</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>

<img id="TitleBarPic" src="../Images/Site/TitleInven.png"><br>

<div class="InventoryOpacity">
<br><br><br>
<a href="../Costumes/CostumeSearchForm.php"><button class="LargeButton" type="button">Costumes</button></a><br> <br>
<a href="../Props/propSearchForm.php"><button class="LargeButton" type="button">Props</button></a><br><br>
<a href="../Scripts/scriptSearchForm.php"><button class="LargeButton" type="button">Scripts</button></a><br><br><br>
<a href="../OpeningPage/OpenPage.php"><button Class="mediumbtnCancel mediumDropShadow" Type="button">Back</button></a>
</div>
</body>

</html>