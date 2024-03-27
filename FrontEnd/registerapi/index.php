<?php
header("Content-Type:application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Allow-Headers: X-Requested-With");


$conn=mysqli_connect();


##GET STATEMENT FOR CHECKING ACCOUNT

$functions = ["usercheck", "user", "verify", "passchange", "usernamechange", "lockaccount"];

if(isset($_REQUEST['function'])) {
if(in_array($_REQUEST['function'], $functions)) {

if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="usercheck") {
	
  $email = $_REQUEST['email'];
	$userNo = $_REQUEST['id'];
  
  $query = "SELECT * FROM users WHERE email=? AND userName=?";
  
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $email, $userNo);
  $stmt->execute();
  $result = $stmt->get_result();

	if($result->num_rows>0){
    $result = $result->fetch_assoc();
	  $response['userName'] = $result['userName'];
    $response['email'] = $result['email'];
    $response['password'] = $result['password'];
    $response['verified'] = $result['verified'];
    $response['postcode'] = $result['postcode'];
    $response['fname'] = $result['fname'];
    $response['lname'] = $result['lname'];
    echo json_encode($response);
	  mysqli_close($conn);
	}
  else {
    echo json_encode("No account found.");
  }
}


##GET STATEMENT FOR RETURNING ACCOUNT DETAILS
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="user") {
	
	$userNo = $_REQUEST['id'];
  
  $query = "SELECT userName, email, fname, lname, postcode, grandTotal FROM users WHERE userName=?";
  
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $userNo);
  $stmt->execute();
  $result = $stmt->get_result();

	if($result->num_rows>0){
    $result = $result->fetch_assoc();
	  $response['userName'] = $result['userName'];
    $response['email'] = $result['email'];
    $response['postcode'] = $result['postcode'];
    $response['fname'] = $result['fname'];
    $response['lname'] = $result['lname'];
    $response['grandTotal'] = $result['grandTotal'];
    echo json_encode($response);
	  mysqli_close($conn);
	}
  else {
    echo json_encode("No account found.");
  }
}


##GET STATEMENT FOR VERIFYING ACCOUNT VIA EMAIL LINK
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="verify") {

	$userNo = $_GET['id'];
  $verificationcode = $_GET['verify'];

  $query = "SELECT verification FROM `userverify` WHERE userName=?";
  
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $userNo);
  $stmt->execute();
  $result = $stmt->get_result();

	if($result->num_rows>0){
    $result = $result->fetch_assoc();
    if($result['verification'] == $verificationcode) {
      $query = "UPDATE users SET verified=1  WHERE userName=?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $userNo);
      $stmt->execute();

      $query = "DELETE FROM userverify WHERE userName=?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $userNo);
      $stmt->execute();

      mysqli_commit($conn);
      header('Location: '.'../ecopark/userverified.php');
    }
    else {header('Location: '.'../ecopark/index.php');}
	}
}


#PUT STATEMENT FOR CHANGING PASSWORD
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="passchange") {

	$userName = $_REQUEST['id'];
  $newp = hash('sha256', $_REQUEST['newp']);

  $query = "UPDATE users set password=? WHERE userName=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $newp, $userName);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("Password changed successfully.");
}

#PUT STATEMENT FOR CHANGING PASSWORD
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="usernamechange") {

	$userName = $_REQUEST['id'];
  $newuserName = $_REQUEST['id2'];

  $query = "UPDATE users set userName=? WHERE userName=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $newuserName, $userName);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("Username changed successfully.");
}

#PUT STATEMENT FOR LOCKING ACCOUNT
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="lockaccount") {

	$userName = $_REQUEST['id'];
  
  $query = "UPDATE users set verified=!verified WHERE userName=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $userName);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("Account lock set successfully.");
}
}
else {
  http_response_code(400);
  echo json_encode("Error: Function not allowed. Please check spelling or refer to API documentation."); 
}
}
else {
  http_response_code(400);
  echo json_encode("Error: Function not specified. Please check request or refer to API documentation."); 
}

##POST STATEMENT FOR ADDING NEW ACCOUNT
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  $email = $_REQUEST['email'];
  $userNo = $_REQUEST['id'];
  $fname = $_REQUEST['fname'];
  $lname = $_REQUEST['lname'];
  $pcode = $_REQUEST['pcode'];
  $pass=hash('sha256', $_REQUEST['pass']);


  ##check account doesn't exist in db already
  $query = "SELECT * FROM `users` WHERE email=? or userName=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $email, $userNo);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows >0) { 
    http_response_code(400);
    echo json_encode("Account already exists in database.");
  }
  else {
    ##add new user
    $APIKey = generateAPIKey();
    $query = "INSERT INTO users (userName, password, email, verified, fname, lname, postcode, APIKey) VALUES (?,?,?,0,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $userNo, $pass, $email, $fname, $lname, $pcode, $APIKey);
    $stmt->execute();

    ##generate random string for verification purposes
    $verification = generateRandomString();
    $query = "INSERT INTO userverify (userName, verification) VALUES (?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $userNo, $verification);
    $stmt->execute();
    mysqli_commit($conn);
    http_response_code(200);
    echo json_encode("User ". $userNo . " has been registered, please check email to verify account.");

    include '../ecopark/verificationlinksend.php';
  }
  
}


##generate random string for verification purposes
function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

##generate random string for API-Key purposes
function generateAPIKey($length = 50) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}



?>