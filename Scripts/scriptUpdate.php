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

<?php
//This script injects all the field values into the mysql database!!

$item_num = $_POST['item_num'] ?? ''; //Note: Adding ?? '' for initialization
$item_name = $_POST['item_name'] ?? '';

//Note: The picture upload is a little more complex - so I am separating it here
$picture = $_FILES['picture'] ?? ''; //Getting file info in the form of an array from the picture form input here

$fileName = $_FILES['picture']['name']; //Getting file name from array - first slot name slot
$fileTmpName = $_FILES['picture']['tmp_name']; //Getting temporary upload file name from array
$fileSize = $_FILES['picture']['size']; //Getting file size from array
$fileError = $_FILES['picture']['error']; //Getting errors from array if any
$fileType = $_FILES['picture']['type']; //Getting file type from array

//Only allow image file types
$fileExt = explode('.', $fileName);
$fileActualExt = strtolower(end($fileExt));

$allowed = array('jpg', 'jpeg', 'png');

if(in_array($fileActualExt, $allowed)){
    if($fileError === 0){
       if($fileSize < 1000000){
        $fileNameNew = $item_num.".".$fileActualExt; //Concatenating Barcode to name for image name here 
        $fileDestination = '../Uploads/Costumes/' . $fileNameNew; //Destination path for file upload
        move_uploaded_file($fileTmpName, $fileDestination); //Actual file upload operation
       } else {
           echo "The image file is too big, please lower the file size to less than 1000mb";
       }
    }else{
        echo "There was an error uploading your file";
    }
} else {
    echo "Error: You cannot upload files of this type: only jpg jpeg and png files are allowed...";
}


$descr = $_POST['descr'] ?? '';

$loc = $_POST['loc'] ?? '';
$status = $_POST['status'] ?? '';
$price = $_POST['price'] ?? '';

$id = $_POST['id'] ?? ''; //From hidden input field on submission form!


if(!empty($_FILES['picture']['name'])){//In this case, picture is not empty so we submit all
$query = "UPDATE scripts
          SET item_num = '$item_num',
              item_name = '$item_name',
               picture = '$fileDestination',
               descr = '$descr',
               loc = '$loc',
               status = '$status',
               price = '$price' 

          WHERE id = '$id'";

} else {//Else in this case, picture is empty and we do not submit empty in order to preserve current picture
    $query = "UPDATE scripts
          SET item_num = '$item_num',
              item_name = '$item_name',
               
               descr = '$descr',
               loc = '$loc',
               status = '$status',
               price = '$price' 

          WHERE id = '$id'";

}//Close Else




mysqli_query($conn, $query)
or die('Error querying database');

header("Location: ../Scripts/scriptSearchSave.php");

mysqli_close($conn);
ob_endflush();
?>