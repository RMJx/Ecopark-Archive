<?php

session_start();

include '../loginConnect.php';

//check username & password against DB

$adminusername=$_POST['adminusername'];
$pw=hash('sha256', $_POST['password']);

$loginstmt=("SELECT adminuserName, password, APIKey FROM adminUsers WHERE adminuserName='$adminusername' AND password='$pw'");
$result = mysqli_query($connection, $loginstmt);

$row = $result->fetch_assoc();

if(mysqli_num_rows($result)>0) {
    session_regenerate_id();
    $_SESSION['adminloggedin'] = TRUE;
    $_SESSION['APIKey'] = $row['APIKey'];
    $_SESSION['adminusername'] = $adminusername;
    header('Location: adminhome.php');
}
else {
  header('Location: '.'loginerror.php');
}



?>