<?php
session_start();
if(isset($_SESSION['u_id']) || isset($_SESSION['u_email'])){
    //If someone is logged in Do nothing here/Continue
} else {
        header("Location: ../login.php?login=error");//Otherwise return to login screen
        exit();
}

if(isset($_POST['submit'])){

    include_once '../db_connect.php';

    $first = mysqli_real_escape_string($conn,$_POST['first']);//Note real escape string = no sql injection protect
    $last = mysqli_real_escape_string($conn,$_POST['last']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $uid = mysqli_real_escape_string($conn,$_POST['uid']);
    $pwd = mysqli_real_escape_string($conn,$_POST['pwd']);

    //Error Handlers
    //Check for empty fields
    if(empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd)){
        header("Location: user_inputForm.php?login=empty");//Note: make this a page outside the program for security - send to first login screen
        exit();
    } else { //else 1
        //Check if input characters are valid
        if(!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last)){
            header("Location: user_inputForm.php?login=invalid");//Note: make this a page outside the program for security - send to first login screen
            exit();
        } else {
            //Check if email is valid
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                header("Location: user_inputForm.php?login=email");//Note: make this a page outside the program for security - send to first login screen
                exit();
            } else {
                $sql = "SELECT *
                        FROM users
                        WHERE user_uid='$uid'
                        ";
                        $result = mysqli_query($conn, $sql);
                        $resultCheck = mysqli_num_rows($result);

                        if($resultCheck > 0){
                            header("Location: user_inputForm.php?login=usertaken");//Note: make this a page outside the program for security - send to first login screen
                            exit();
                        } else {
                            //Hashing password
                            $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
                            //Insert user into database
                            $sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd)
                                                VALUES ('$first', '$last', '$email', '$uid', '$hashedPwd')";
                            mysqli_query($conn, $sql);
                            header("Location: user_inputForm.php?login=success");//Note: make this a page outside the program for security - send to first login screen
                            exit();
                        }
            }
        }
    }//End else 1

} else {
    header("Location: user_inputForm.php");//Note: make this a page outside the program for security - send to first login screen
    exit();
}
?>