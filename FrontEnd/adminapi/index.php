<?php
header("Content-Type:application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Allow-Headers: X-Requested-With");

$conn=mysqli_connect();


$functions = ["cars", "locationlogs", "parkedlogs", "allparking", "allguestparking", "co2rates", "weeklyrates", "guestparking", "getusers", "getusers", "locations", "ratechange", "co2ratechange", "co2endtier", "co2starttier", "weeklyendtier", "weeklystarttier","weeklyratechange", "capacitychange"];

if(isset($_REQUEST['adminusername']) AND isset($_REQUEST['API-KEY'])) {

  
#verify API key is correct
$username = $_REQUEST['adminusername'];
$apikey = $_REQUEST['API-KEY'];

$query ="SELECT APIKey FROM adminUsers where adminuserName=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$APIKeyDB = $result->fetch_assoc();

if(in_array($_REQUEST['function'], $functions)) {

if ($APIKeyDB['APIKey'] == $apikey) {


##GET STATEMENT FOR CHECKING PARKING FOR LOCATION
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="cars") {

  $query ="SELECT * FROM parkingsystem ";
  $stmt = $conn->prepare($query);
  
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }
   echo "[";
   for ($x = 0; $x <= sizeof($rows)-1; $x++) {
     $response['id'] = $rows[$x]['id'];
     $response['userName']  = $rows[$x]['userName'];
     $response['isParked'] = $rows[$x]['isParked'];
     $response['make'] = $rows[$x]['make'];
     $response['colour'] = $rows[$x]['colour'];
     $response['co2Class'] = $rows[$x]['co2Class'];
     $response['timeParked'] = $rows[$x]['timeParked'];
     $response['totalDue'] = $rows[$x]['totalDue'];
     $json_response = json_encode($response);
     echo $json_response;
     if($x != sizeof($rows)-1) echo ",";
     } echo "]";mysqli_close($conn);
   }
}

##GET STATEMENT FOR CHECKING PARKING FOR LOCATION
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="locationlogs") {

  $location = $_REQUEST['location'];

  $query ="SELECT * FROM parkinglogs where location=? AND outTime IS NOT NULL";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $location);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }

   echo "[";
   for ($x = 0; $x <= sizeof($rows)-1; $x++) {
     $response['parkingID'] = $rows[$x]['parkingID'];
     $response['location']  = $rows[$x]['location'];
     $response['inTime'] = $rows[$x]['inTime'];
     $response['outTime'] = $rows[$x]['outTime'];
     $response['userName'] = $rows[$x]['userName'];
     $response['id'] = $rows[$x]['id'];
     $response['cost'] = $rows[$x]['cost'];
     $response['exitID'] = $rows[$x]['exitID'];
     $json_response = json_encode($response);
     echo $json_response;
     if($x != sizeof($rows)-1) echo ",";
     } echo "]";mysqli_close($conn);
   }

  else {
    echo json_encode("No history found for location.");
  }
}

##GET STATEMENT FOR CHECKING PARKING FOR LOCATION
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="parkedlogs") {

  $location = $_REQUEST['location'];

  $query ="SELECT * FROM parkinglogs where location=? AND outTime IS NULL";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $location);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }

   echo "[";
   for ($x = 0; $x <= sizeof($rows)-1; $x++) {
     $response['parkingID'] = $rows[$x]['parkingID'];
     $response['location']  = $rows[$x]['location'];
     $response['inTime'] = $rows[$x]['inTime'];
     $response['userName'] = $rows[$x]['userName'];
     $response['id'] = $rows[$x]['id'];
     $json_response = json_encode($response);
     echo $json_response;
     if($x != sizeof($rows)-1) echo ",";
     } echo "]";mysqli_close($conn);
   }

  else {
    echo json_encode("No currently parked cars at location.");
  }
}

##GET STATEMENT FOR CHECKING ALL PARKING LOGS
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="allparking") {

  $query ="SELECT * FROM parkinglogs WHERE outTime IS NOT NULL";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }

   echo "[";
   for ($x = 0; $x <= sizeof($rows)-1; $x++) {
     $response['parkingID'] = $rows[$x]['parkingID'];
     $response['location']  = $rows[$x]['location'];
     $response['inTime'] = $rows[$x]['inTime'];
     $response['outTime'] = $rows[$x]['outTime'];
     $response['userName'] = $rows[$x]['userName'];
     $response['id'] = $rows[$x]['id'];
     $response['cost'] = $rows[$x]['cost'];
     $response['exitID'] = $rows[$x]['exitID'];
     $json_response = json_encode($response);
     echo $json_response;
     if($x != sizeof($rows)-1) echo ",";
     } echo "]";mysqli_close($conn);
   }

  else {
    echo json_encode("No parking history found for ecopark.");
  }
}

##GET STATEMENT FOR CHECKING ALL PARKING LOGS
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="allguestparking") {

  $query ="SELECT * FROM guestparkinglogs WHERE outTime IS NOT NULL";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }

   echo "[";
   for ($x = 0; $x <= sizeof($rows)-1; $x++) {
     $response['parkingID'] = $rows[$x]['parkingID'];
     $response['location']  = $rows[$x]['location'];
     $response['inTime'] = $rows[$x]['inTime'];
     $response['outTime'] = $rows[$x]['outTime'];
     $response['id'] = $rows[$x]['id'];
     $response['userName'] = $rows[$x]['userName'];
     $response['exitID'] = $rows[$x]['exitID'];
     $json_response = json_encode($response);
     echo $json_response;
     if($x != sizeof($rows)-1) echo ",";
     } echo "]";mysqli_close($conn);
   }

  else {
    echo json_encode("No parking history found for ecopark.");
  }
}

#GET STATEMENT FOR CO2 RATES
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="co2rates") {
  $stmt = $conn->prepare("SELECT * FROM co2Charges");
  $stmt->execute();
  $result = $stmt->get_result();
  $rows = array();


  if($result->num_rows>0){
  while($row = $result->fetch_assoc()){
    $rows[] = $row;
  }

  echo "[";
  for ($x = 0; $x <= sizeof($rows)-1; $x++) {
    $response['charge'] = $rows[$x]['charge'];
    $response['tier']  = $rows[$x]['tier'];
    $response['tierStart'] = $rows[$x]['tierStart'];
    $response['tierEnd'] = $rows[$x]['tierEnd'];
    $json_response = json_encode($response);
    echo $json_response;
    if($x != sizeof($rows)-1) echo ",";
    } echo "]";mysqli_close($conn);
  }

 else echo json_encode("No rates found on system.");
}

#GET STATEMENT FOR CO2 RATES
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="weeklyrates") {
  $stmt = $conn->prepare("SELECT * FROM weeklyCharges");
  $stmt->execute();
  $result = $stmt->get_result();
  $rows = array();


  if($result->num_rows>0){
  while($row = $result->fetch_assoc()){
    $rows[] = $row;
  }

  echo "[";
  for ($x = 0; $x <= sizeof($rows)-1; $x++) {
    $response['charge'] = $rows[$x]['charge'];
    $response['tier']  = $rows[$x]['tier'];
    $response['tierStart'] = $rows[$x]['tierStart'];
    $response['tierEnd'] = $rows[$x]['tierEnd'];
    $json_response = json_encode($response);
    echo $json_response;
    if($x != sizeof($rows)-1) echo ",";
    } echo "]";mysqli_close($conn);
  }

 else echo json_encode("No rates found on system.");
}

##GET STATEMENT FOR CHECKING GUEST PARKING
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="guestparking") {

  $query ="SELECT * FROM guestparking";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }

   echo "[";
   for ($x = 0; $x <= sizeof($rows)-1; $x++) {
     $response['id'] = $rows[$x]['id'];
     $response['hours']  = $rows[$x]['hours'];
     $response['co2Class'] = $rows[$x]['co2Class'];
     $response['isParked'] = $rows[$x]['isParked'];
     $response['userName'] = $rows[$x]['userName'];
     $response['location'] = $rows[$x]['location'];
     $json_response = json_encode($response);
     echo $json_response;
     if($x != sizeof($rows)-1) echo ",";
     } echo "]";mysqli_close($conn);
   }

  else {
    echo json_encode("No information found for guest parking.");
  }
}


#GET STATEMENT FOR ALL USERS
  if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="getusers") {
  $stmt = $conn->prepare("SELECT * FROM users");
  $stmt->execute();
  $result = $stmt->get_result();
  $rows = array();


  if($result->num_rows>0){
  while($row = $result->fetch_assoc()){
    $rows[] = $row;
  }

  echo "[";
  for ($x = 0; $x <= sizeof($rows)-1; $x++) {
    $response['userName'] = $rows[$x]['userName'];
    $response['email']  = $rows[$x]['email'];
    $response['fname'] = $rows[$x]['fname'];
    $response['lname'] = $rows[$x]['lname'];
    $response['postcode'] = $rows[$x]['postcode'];
    $response['verified'] = $rows[$x]['verified'];
    $response['grandTotal'] = $rows[$x]['grandTotal'];
    $json_response = json_encode($response);
    echo $json_response;
    if($x != sizeof($rows)-1) echo ",";
    } echo "]";mysqli_close($conn);
  }

 else echo json_encode("No users found on system.");
}

#GET STATEMENT FOR ALL USERS
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="locations") {
  $stmt = $conn->prepare("SELECT location FROM locationrates");
  $stmt->execute();
  $result = $stmt->get_result();
  $rows = array();


  if($result->num_rows>0){
  while($row = $result->fetch_assoc()){
    $rows[] = $row;
  }

  echo "[";
  for ($x = 0; $x <= sizeof($rows)-1; $x++) {
    $response['location'] = $rows[$x]['location'];
    $json_response = json_encode($response);
    echo $json_response;
    if($x != sizeof($rows)-1) echo ",";
    } echo "]";mysqli_close($conn);
  }

 else echo json_encode("No users found on system.");
}

#PUT STATEMENT FOR CHANGING RATES
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="ratechange") {

	$location = $_REQUEST['location'];
  $rate = $_REQUEST['rate'];

  $query = "UPDATE locationrates set rate=? WHERE location=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $rate, $location);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("Rate changed successfully.");
}

#PUT STATEMENT FOR CHANGING CO2 RATES
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="co2ratechange") {

	$tier = $_REQUEST['tier'];
  $charge = $_REQUEST['charge'];

  $query = "UPDATE co2Charges set charge=? WHERE tier=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $charge, $tier);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("CO2 Charge changed successfully.");
}

#PUT STATEMENT FOR CHANGING CO2 RATES END TIER
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="co2endtier") {

	$tier = $_REQUEST['tier'];
  $tierEnd = $_REQUEST['tierend'];

  $query = "UPDATE co2Charges set tierEnd=? WHERE tier=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $tierEnd, $tier);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("CO2 End Tier changed successfully.");
}

#PUT STATEMENT FOR CHANGING CO2 RATES START TIER
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="co2starttier") {

	$tier = $_REQUEST['tier'];
  $tierStart = $_REQUEST['tierstart'];

  $query = "UPDATE co2Charges set tierStart=? WHERE tier=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $tierStart, $tier);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("CO2 Start Tier changed successfully.");
}

#PUT STATEMENT FOR CHANGING WEEKLY RATES END TIER
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="weeklyendtier") {

	$tier = $_REQUEST['tier'];
  $tierEnd = $_REQUEST['tierend'];

  $query = "UPDATE weeklyCharges set tierEnd=? WHERE tier=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $tierEnd, $tier);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("Weekly End Tier changed successfully.");
}

#PUT STATEMENT FOR CHANGING WEEKLY RATES START TIER
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="weeklystarttier") {

	$tier = $_REQUEST['tier'];
  $tierStart = $_REQUEST['tierstart'];

  $query = "UPDATE weeklyCharges set tierStart=? WHERE tier=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $tierStart, $tier);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("Weekly Start Tier changed successfully.");
}

#PUT STATEMENT FOR CHANGING WEEKLY RATES
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="weeklyratechange") {

	$tier = $_REQUEST['tier'];
  $charge = $_REQUEST['charge'];

  $query = "UPDATE weeklyCharges set charge=? WHERE tier=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $charge, $tier);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("Weekly Charge changed successfully.");
}

#PUT STATEMENT FOR CHANGING CAPACITY
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="capacitychange") {

	$location = $_REQUEST['location'];
  $capacity = $_REQUEST['capacity'];

  $query = "UPDATE locationrates set capacity=capacity+? WHERE location=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $capacity, $location);
  $stmt->execute();
  
  $query = "UPDATE locationrates set free=free+? WHERE location=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $capacity, $location);
  $stmt->execute();
  mysqli_commit($conn);
  echo json_encode("Capacity changed successfully.");
}

#DELETE STATEMENT FOR REMOVING LOCATIONS
if ($_SERVER['REQUEST_METHOD'] === "DELETE") {

	$location = $_REQUEST['location'];

  $query = "DELETE FROM locationrates WHERE location=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $location);
  $stmt->execute();
  
  mysqli_commit($conn);
  echo json_encode("Location deleted.");
}
}

else {
  http_response_code(400);
  echo json_encode("Error: Invalid API Key.");
}
}
else {
  http_response_code(400);
  echo json_encode("Error: Function not allowed. Please check spelling or refer to API documentation."); 
}
}
else {
  http_response_code(400);
  echo json_encode("Error: Username or API Key not specified. Please check spelling or refer to API documentation.");
}

?>