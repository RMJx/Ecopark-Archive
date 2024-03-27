<?php

session_start();

include 'loginConnect.php';

//check username & password against DB


$studentno=$_POST['studentno'];
$pw=hash('sha256', $_POST['password']);

$loginstmt=("SELECT userName, password, verified, APIKey, email FROM users WHERE userName='$studentno' AND password='$pw'");
$result = mysqli_query($connection, $loginstmt);

$row = $result->fetch_assoc();

if(mysqli_num_rows($result)>0) {
  if($row['verified'] == 1) {
    session_regenerate_id();
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['name'] = $_POST['studentno'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['APIKey'] = $row['APIKey'];
    header('Location: home.php');
  }
  else {
    header('Location: '.'accountnotverified.php');
  }
}
else {
  header('Location: '.'loginerror.php');
}



?>