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
?>

<?php
//Starting session here for costume added confirmation message

//This script injects all the field values into the mysql database!!

$item_num = $_POST['item_num'] ?? ''; //Note: Adding ?? '' for initialization
$item_name = $_POST['item_name'] ?? '';

$_SESSION['message'] = $item_name;//Storing item name just entered into message session variable for item added message


//Checking to ensure that user is not entering a duplicate sku!!!!
//Costume database check
$sql = "SELECT *
FROM scripts
WHERE item_num = '$item_num'
";

$result = mysqli_query($conn, $sql)
or die('Error querying scripts database');
$numResultRowsCost = mysqli_num_rows($result);//Extracting number of result rows from costume table

if ($numResultRowsCost > 0){
  $_SESSION['message'] = $item_name . " sku already in database: not ";//Storing item name just entered into message session variable for item added message
  header("Location: script_input.php");
  exit();
}





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
       if($fileSize < 10000000){
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
$status = 0;// Initializing status variable to 0/available here
$status_word = 'available';
$price = $_POST['price'] ?? '';

$query = "INSERT INTO scripts (item_num, item_name, picture, descr, loc, status, price, status_word)" .
"VALUES ('$item_num', '$item_name', '$fileDestination', '$descr', '$loc', '$status', '$price', '$status_word')";

mysqli_query($conn, $query)
or die('Error querying database');

//If the save button was clicked, return to search results
//If the save and continue button was clicked, return to form for another submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //something posted
    header("Location: ../Scripts/scriptSearchForm.php?upload success");
    if (isset($_POST['save'])) {
        // btnDelete
    } else {
        //assume Save and Continue was pressed
        header("Location: ../Scripts/script_input.php");
    }
}


mysqli_close($conn);
ob_endflush();
?>