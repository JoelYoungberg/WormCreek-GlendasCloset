<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en-us" id="OpenPageBG">

<head>

<title>Glenda's Closet Login</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
    $(".confirmationLogin").fadeOut(10000);
    });
</script>

<body>

<img id="TitleBarPic" src="Images/Site/Title.png"><br>
<div>
<div class="loginSpace">
<?php
//If there is a status message - pull it from session storage
        if(!empty($_SESSION['message'])){
            $message = $_SESSION['message'];

	        echo "
	        <div class='confirmationLogin'>
		    $message
	        </div>
             ";
        }

        //Clear message in session
        $_SESSION['message'] = "";
?>
</div>

<form action="Users/login.inc.php" method="POST">
<input class="LoginBox" type="text" name="uid" placeholder="username/email"><br> <br>
<input class="LoginBox" type="password" name="pwd" placeholder="password"><br><br>
<button class="btnsm" type="submit" name="submit">Login</button></a><br><br>
</form>
<br>
</div>


</body>

</html>