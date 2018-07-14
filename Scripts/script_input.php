<?php
ob_start();
session_start();
if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
  //If someone is logged in Do nothing here/Continue
} else {
      header("Location: ../login.php?login=error");//Otherwise return to login screen
      exit();
}
?>
<!DOCTYPE html>
<html lang="en-us" id="ScriptPageBG">

<head>

<title>Inventory Entry Form: Scripts</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../style.css">

<script type='text/javascript'>
function preview_image(event) 
{
 var reader = new FileReader();
 reader.onload = function()
 {
  var output = document.getElementById('output_image');
  output.src = reader.result;
 }
 reader.readAsDataURL(event.target.files[0]);
}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $(".confirmation2").fadeOut(10000);
    });
</script>

</head>


<body>
   
<img class="TitleBarPic" src="../Images/Site/TitleScript.png">


<div class="lighten">
<br>

<form method="post" action="scriptConnect.php" enctype="multipart/form-data">

<div class="float"><div class="item_num"><label for="item_num">Item #: </label></div>
<input type="text" id="item_num" name="item_num" size="20"></div>

<div class="float"><div class="item_name"><label for="item_name">Item Name: </label></div>
<input type="text" id="item_name" name="item_name"  size="60"></div> <br>


  <!--Image Uploader.............................................................-->
<div class="imgBackground">

    <div class="bar">
      <input type="file" name="picture" accept="image/*" onchange="preview_image(event)">
      
    </div> <!--End Bar-->
<br>
<img id="output_image"/>
<br>
<br>

</div> <!--End imgback -->
<!--End Image Uploader..........................................................-->

<br>

<div class="float"><div class="descr"><label for="descr">Item Description: </label></div>
<input type="text" id="descr" name="descr" size="87" height="20%"></div>
<br>

<div class="float"><div class="loc"><label for="loc">Item Location: </label></div>
<input type="text" id="loc" name="loc" size="55"></div>

<div class="float"><div class="price"><label for="price">Item Price: </label></div>
<input type="number" id="price" name="price" size="25"></div>

<br><br>

</div><!--Close Lighten-->


<br><br>
<input class="mediumbtn SaveContinueCancelSpacing" type="submit" name="save" value="Save">
<input class="mediumbtn SaveContinueCancelSpacing" type="submit" name="saveAndCont" value="Save &amp; Continue">
</form><!--Closing Form Tag-->

<a href="scriptSearchForm.php"><button class="mediumbtn">Cancel</button></a>

<?php
//If there is a name stored in the message variable - output it temporarily here to confirm it was added
if(!empty($_SESSION['message'])) {
	$message = $_SESSION['message'];
	echo "
	  <div class='confirmation2'>
		Script " . $message . " added
	  </div>
	";
}
?>
<?php
//Clear session variable to stop multiple outputs
$_SESSION['message'] = '';
ob_endflush();
?>

</body>

</html>