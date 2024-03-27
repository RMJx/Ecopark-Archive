<?php
header("Content-Type:application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Allow-Headers: X-Requested-With");

$conn=mysqli_connect();


$functions = ["cardetails", "guestcar", "getrates", "guesthistory", "userscars", "parkinghistoryuser", "singlehistory", "guestparkinglogs", "weeklyrates", "co2rates", "locationinfo", "parkinghistoryreg", "newcar", "newguest", "park", "guestpark", "deletecar"];

if(in_array($_REQUEST['function'], $functions)) {

if(isset($_REQUEST['username']) and isset($_REQUEST['API-KEY'])) {
#verify API key is correct
$username = $_REQUEST['username'];
$apikey = $_REQUEST['API-KEY'];

$query2 ="SELECT APIKey FROM adminUsers where APIKey=?";
$stmt2 = $conn->prepare($query2);
$stmt2->bind_param("s", $apikey);
$stmt2->execute();
$results = $stmt2->get_result();
$APIKeyDBAdmin = $results->fetch_assoc();

$query ="SELECT APIKey FROM users where userName=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$APIKeyDBUser = $result->fetch_assoc();

if(is_array($APIKeyDBAdmin)) $apikey2 = $APIKeyDBAdmin['APIKey'];
elseif(is_array($APIKeyDBUser)) $apikey2 = $APIKeyDBUser['APIKey'];
else $apikey2 = "";

function calculateCharge($timeIn, $co2Class, $timeOut, $location, $conn, $weekTimes){
  ##fetch rates for location.
  $result = mysqli_query($conn,"SELECT rate FROM `locationrates` WHERE location='$location'");
  $row = mysqli_fetch_array($result);
  $hourlyRate = $row['rate'];
  
  ##fetch rates for co2Charges.
  $stmt = $conn->prepare("SELECT charge,tier FROM co2Charges ORDER BY tier");
  $stmt->execute();
  $result = $stmt->get_result();
  $co2rates = array();
  while($row = $result->fetch_assoc()){
     $co2rates[] = $row;
  }
  #calculate flat rate
  $Charge = round((($timeOut - $timeIn)/3600 * $hourlyRate),2);

  #calculate additional co2 charge if necessary
  $co2ChargeExtra = 0;
  for($i=0; $i<sizeof($co2rates)-1; $i++) {
    if($co2Class == $co2rates[$i]['tier']) {
       $co2RateCharge = $co2rates[$i]['charge'];
       $co2ChargeExtra = round((($Charge* $co2RateCharge) - $Charge),2);
      }
  }

 ##fetch rates for weekly charges.
 $stmt = $conn->prepare("SELECT charge,tierStart,tierEnd,tier FROM weeklyCharges ORDER BY tier");
 $stmt->execute();
 $result = $stmt->get_result();
 $weeklyRates = array();
 while($row = $result->fetch_assoc()){
    $weeklyRates[] = $row;
 }

$weeklyChargeExtra = 0;
  #calculate extra charges for extra weekly parking
  for($i=0; $i<=sizeof($weeklyRates)-1; $i++) {
    if($weekTimes > $weeklyRates[$i]['tierStart'] and $weekTimes <= $weeklyRates[$i]['tierEnd']) {
      $weeklyRateCharge = $weeklyRates[$i]['charge'];
      $weeklyChargeExtra = round((($Charge* $weeklyRateCharge) - $Charge),2);
    }
  }

  $Charge = $Charge + $co2ChargeExtra + $weeklyChargeExtra;
  return array(round($Charge, 2),$weeklyRateCharge, $co2RateCharge, $hourlyRate);

}

function calculateSurplusCost($hoursExtra, $co2Class, $location, $conn){
  ##fetch rates for location.
  $result = mysqli_query($conn,"SELECT rate FROM `locationrates` WHERE location='$location'");
  $row = mysqli_fetch_array($result);
  $hourlyRate = $row['rate'];
  #calculate flat rate
  $Charge = ($hoursExtra) * $hourlyRate;
  #calculate additional co2 charge if necessary
  if ($co2Class == "1") {
      $Charge = $Charge*1.1;
  }
  elseif ($co2Class == "2"){
      $Charge = $Charge*1.2;
  }
  elseif ($co2Class == "3") {
      $Charge = $Charge*1.3;
  }
  elseif ($co2Class == "0") {
      $Charge = $Charge*0.9;
  }
  elseif ($co2Class == "4") {
      $Charge = $Charge*1.4;
  }

  return $Charge;

}

if ($apikey == $apikey2) {

##GET STATEMENT FOR CHECKING CARS
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="cardetails") {
	$id = $_GET['id'];

  $query = "SELECT * FROM parkingsystem WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){
	  $row = $result->fetch_assoc();
	  $response['id'] = $row['id'];
    $response['make'] = $row['make'];
    $response['colour'] = $row['colour'];
    $response['isParked'] = $row['isParked'];
    $response['timeParked'] = $row['timeParked'];
    $response['totalDue'] = $row['totalDue'];
    $response['userName'] = $row['userName'];
    $response['co2Class'] =  $row['co2Class'];
	  $json_response = json_encode($response);
    echo $json_response;
	  mysqli_close($conn);
	}
  else {
    echo json_encode("No car found.");
  }
}

##GET STATEMENT FOR CHECKING CARS
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="guestcar") {

	$id = $_GET['id'];

  $query = "SELECT * FROM guestparking WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){
	  $row = $result->fetch_assoc();
	  $response['id'] = $row['id'];
    $response['hours'] = $row['hours'];
    $response['co2Class'] = $row['co2Class'];
    $response['userName'] = $row['userName'];
    $response['location'] = $row['location'];
    $response['isParked'] = $row['isParked'];
	  $json_response = json_encode($response);
    echo $json_response;
	  mysqli_close($conn);
	}
  else {
    echo json_encode("No registered guest car found.");
  }
}


##GET STATEMENT FOR CHECKING LOCATION RATES
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="getrates") {

	$location = $_GET['location'];

  $stmt = "SELECT rate FROM locationrates WHERE location=?";
  $stmt = $conn->prepare($stmt);
  $stmt->bind_param("s", $location);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){
	  $row = $result->fetch_assoc();
	  $rate = $row['rate'];
    $response['rate']  =  $rate;
    $json_response = json_encode($response);
    echo $json_response;
	}
  else {
    echo json_encode("No rates for location found.");
  }
}

##GET STATEMENT FOR CHECKING guesthistory per user
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="guesthistory") {
	$username = $_GET['username'];

  $stmt = $conn->prepare("SELECT * FROM guestparking WHERE userName=?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }

   echo "[";
   for ($x = 0; $x <= sizeof($rows)-1; $x++) {
     $response['id'] = $rows[$x]['id'];
     $response['hours'] = $rows[$x]['hours'];
     $response['location']  = $rows[$x]['location'];
     $response['isParked']  = $rows[$x]['isParked'];
     $response['userName']  = $rows[$x]['userName'];
     $response['co2Class']  =  $rows[$x]['co2Class'];
     $json_response = json_encode($response);
     echo $json_response;
     if($x != sizeof($rows)-1) echo ",";
     } echo "]";mysqli_close($conn);
   }

  else {
    echo json_encode("No history found for user.");
  }
}

##GET STATEMENT FOR CHECKING USERS CARS
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="userscars") {
	$userName = $_GET['username'];

  $query = "SELECT * FROM parkingsystem WHERE userName=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $userName);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }

   echo "[";
   for ($x = 0; $x <= sizeof($rows)-1; $x++) {
     $response['id'] = $rows[$x]['id'];
     $response['make'] = $rows[$x]['make'];
     $response['colour']  = $rows[$x]['colour'];
     $response['isParked']  = $rows[$x]['isParked'];
     $response['timeParked']  = $rows[$x]['timeParked'];
     $response['totalDue'] = $rows[$x]['totalDue'];
     $response['userName']  = $rows[$x]['userName'];
     $response['co2Class']  =  $rows[$x]['co2Class'];
     $json_response = json_encode($response);
     echo $json_response;
     if($x != sizeof($rows)-1) echo ",";
     } echo "]";mysqli_close($conn);
   }

  else {
    echo json_encode("No cars found for user.");
  }
}

##GET STATEMENT FOR CHECKING PARKING HISTORY FOR REPORTING
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="parkinghistoryuser") {
	$username = $_GET['username'];

  $query = "SELECT * FROM parkinglogs WHERE userName=? and outTime IS NOT NULL";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }
    echo "[";
    for ($x = 0; $x <= sizeof($rows)-1; $x++) {
	    $response['parkingID'] = $rows[$x]['parkingID'];
      $response['location'] = $rows[$x]['location'];
      $response['inTime']  = $rows[$x]['inTime'];
      $response['outTime']  = $rows[$x]['outTime'];
      $response['userName']  = $rows[$x]['userName'];
      $response['id'] = $rows[$x]['id'];
      $response['cost']  = $rows[$x]['cost'];
      $response['exitID']  =  $rows[$x]['exitID'];
      $response['weeklyCharge'] = $rows[$x]['weeklyCharge'];
      $response['co2Charge']  = $rows[$x]['co2Charge'];
      $response['baseRate']  =  $rows[$x]['baseRate'];
      $json_response = json_encode($response);
      echo $json_response;
      if($x != sizeof($rows)-1) echo ",";
      } echo "]";mysqli_close($conn);
    }

  
    else {echo json_encode("No parking history found.");}
}

##GET STATEMENT FOR CHECKING SINGULAR PARKING HISTORY FOR INVOICING
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="singlehistory") {
	$username = $_GET['username'];
  $id = $_GET['parkingID'];

  $query = "SELECT * FROM parkinglogs WHERE parkingID=? AND userName=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $id, $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){
	  $rows = $result->fetch_assoc();
    $response['parkingID'] = $rows['parkingID'];
    $response['location'] = $rows['location'];
    $response['inTime']  = $rows['inTime'];
    $response['outTime']  = $rows['outTime'];
    $response['userName']  = $rows['userName'];
    $response['id'] = $rows['id'];
    $response['cost']  = $rows['cost'];
    $response['exitID']  =  $rows['exitID'];
    $response['weeklyCharge'] = $rows['weeklyCharge'];
    $response['co2Charge']  = $rows['co2Charge'];
    $response['baseRate']  =  $rows['baseRate'];
    $json_response = json_encode($response);
    echo $json_response;
	}
  else {
    echo json_encode("No history for Parking ID found.");
  }

  
}

##GET STATEMENT FOR CHECKING PARKING HISTORY FOR REPORTING
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="guestparkinglogs") {
	$username = $_GET['username'];

  $query = "SELECT * FROM guestparkinglogs WHERE userName=? and outTime IS NOT NULL";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }
    echo "[";
    for ($x = 0; $x <= sizeof($rows)-1; $x++) {
	    $response['parkingID'] = $rows[$x]['parkingID'];
      $response['location'] = $rows[$x]['location'];
      $response['inTime']  = $rows[$x]['inTime'];
      $response['outTime']  = $rows[$x]['outTime'];
      $response['userName']  = $rows[$x]['userName'];
      $response['id'] = $rows[$x]['id'];
      $response['exitID']  =  $rows[$x]['exitID'];
      $json_response = json_encode($response);
      echo $json_response;
      if($x != sizeof($rows)-1) echo ",";
      } echo "]";mysqli_close($conn);
    }

  
    else {echo json_encode("No parking history found.");}
}

##GET STATEMENT FOR CHECKING PARKING HISTORY FOR REPORTING
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="co2rates") {

  $query = "SELECT * FROM co2Charges order by tier";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }
    echo "[";
    for ($x = 0; $x <= sizeof($rows)-1; $x++) {
	    $response['tier'] = $rows[$x]['tier'];
      $response['charge'] = $rows[$x]['charge'];
      $response['tierStart'] = $rows[$x]['tierStart'];
      $response['tierEnd'] = $rows[$x]['tierEnd'];
      $json_response = json_encode($response);
      echo $json_response;
      if($x != sizeof($rows)-1) echo ",";
      } echo "]";mysqli_close($conn);
    }

  
    else {echo json_encode("No co2 rates found.");}
}


##GET STATEMENT FOR CHECKING PARKING HISTORY FOR REPORTING
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="weeklyrates") {

  $query = "SELECT * FROM weeklyCharges order by tier";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }
    echo "[";
    for ($x = 0; $x <= sizeof($rows)-1; $x++) {
	    $response['tier'] = $rows[$x]['tier'];
      $response['charge'] = $rows[$x]['charge'];
      $response['tierStart'] = $rows[$x]['tierStart'];
      $response['tierEnd'] = $rows[$x]['tierEnd'];
      $json_response = json_encode($response);
      echo $json_response;
      if($x != sizeof($rows)-1) echo ",";
      } echo "]";mysqli_close($conn);
    }

  
    else {echo json_encode("No weekly rates found.");}
}

##GET STATEMENT FOR CHECKING location information
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="locationinfo") {

  $stmt = $conn->prepare("SELECT * FROM locationrates");
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }

   echo "[";
   for ($x = 0; $x <= sizeof($rows)-1; $x++) {
     $response['rate'] = $rows[$x]['rate'];
     $response['location']  = $rows[$x]['location'];
     $response['capacity'] = $rows[$x]['capacity'];
     $response['free'] = $rows[$x]['free'];
     $json_response = json_encode($response);
     echo $json_response;
     if($x != sizeof($rows)-1) echo ",";
     } echo "]";mysqli_close($conn);
   }

  else {
    echo json_encode("No information found for location.");
  }
}

##GET STATEMENT FOR CHECKING PARKING HISTORY FOR REPORTING
if ($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['function']=="parkinghistoryreg") {
	$id = $_GET['id'];
  $username = $_GET['username'];

  $query = "SELECT * FROM parkinglogs WHERE id=? and userName=? and outTime IS NOT NULL";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $id, $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows>0){

	  while($row = $result->fetch_assoc()){
      $rows[] = $row;
   }
    echo "[";
    for ($x = 0; $x <= sizeof($rows)-1; $x++) {
	    $response['parkingID'] = $rows[$x]['parkingID'];
      $response['location'] = $rows[$x]['location'];
      $response['inTime']  = $rows[$x]['inTime'];
      $response['outTime']  = $rows[$x]['outTime'];
      $response['userName']  = $rows[$x]['userName'];
      $response['id'] = $rows[$x]['id'];
      $response['cost']  = $rows[$x]['cost'];
      $response['exitID']  =  $rows[$x]['exitID'];
      $json_response = json_encode($response);
      echo $json_response;
      if($x != sizeof($rows)-1) echo ",";
      } echo "]";mysqli_close($conn);
    }

  
    else {echo json_encode("No parking history found.");}
}

##POST STATEMENT FOR ADDING NEW CARS
if ($_SERVER['REQUEST_METHOD'] === "POST" && $_REQUEST['function']=="newcar") {
  $id = $_REQUEST['id'];
  $make = $_REQUEST['make'];
  $colour = $_REQUEST['colour'];
  $userName = $_REQUEST['username'];
  $co2Class = $_REQUEST['co2Class'];

   ##fetch co2 classification numerics
 $stmt = $conn->prepare("SELECT charge,tierStart,tierEnd,tier FROM co2Charges ORDER BY tier");
 $stmt->execute();
 $result = $stmt->get_result();
 $co2Rates = array();
 while($row = $result->fetch_assoc()){
    $co2Rates[] = $row;
 }
  #calculate co2 class dynamically
  for($i=0; $i<=sizeof($co2Rates)-1; $i++) {
    if($co2Class >= $co2Rates[$i]['tierStart'] and $co2Class <= $co2Rates[$i]['tierEnd']) {
      $co2Class = $co2Rates[$i]['tier'];
    }
  }

  ##check car doesn't exist in db already
  $query = "SELECT * FROM parkingsystem WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  $query = "SELECT * FROM guestparking WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result2 = $stmt->get_result();

  if($result->num_rows >0 or $result2->num_rows > 0) { echo json_encode("Error: Car already exists in database.");}
  else {
    $query = "INSERT INTO parkingsystem (id, make, colour, userName, co2Class) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $id, $make, $colour, $userName, $co2Class);
    $stmt->execute();
    mysqli_commit($conn);
    echo json_encode(strtoupper($id) . " has been added to account.");
  } 
}

##POST STATEMENT FOR ADDING NEW GUESTS
if ($_SERVER['REQUEST_METHOD'] === "POST" && $_REQUEST['function']=="newguest") {
  
  $id = $_REQUEST['id'];
  $userName = $_REQUEST['username'];
  $co2Class = $_REQUEST['co2Class'];
  $hoursadded = $_REQUEST['hours'];
  $location = $_REQUEST['location'];

  #check car isn't already registered as a static car
  $query = "SELECT * FROM parkingsystem WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows >0) {
    echo json_encode("Error: Car is already registered for Pay as you Park parking.");
  }
  else {
    ##check car doesn't exist in guest table already
    $query = "SELECT * FROM guestparking WHERE id=? and userName=? and location=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $id, $userName, $location);
    $stmt->execute();
    $result = $stmt->get_result();
    ##if does, then append on current hours.
    if($result->num_rows >0) {
      $row = mysqli_fetch_array($result);
      $hours = $row['hours'];
      $hours = $hours + $hoursadded;

      $query = "UPDATE guestparking set hours=? where id=? and userName=? and location=?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ssss", $hours,  $id, $userName, $location);
      $stmt->execute();
      echo json_encode("Guest parking updated for car.");
    }
    ##if car doesnt exist then add new entry.
    elseif($result->num_rows < 1) {
      $query = "INSERT INTO guestparking (id, hours, co2Class, userName, location, isParked) VALUES (?, ?, ?, ?, ?,0)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sssss", $id,  $hoursadded, $co2Class, $userName, $location);
      $stmt->execute();
    
      echo json_encode("Guest parking added for user.");
    }
  }
  mysqli_commit($conn);
}

##PUT STATEMENT FOR UPDATING STATIC PARKING COSTS.
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="park") {
  $id = $_REQUEST['id'];
  $time = $_REQUEST['time'];
  $location = $_REQUEST['location'];

  $query = "SELECT timeParked, isParked, co2Class, totalDue, userName FROM `parkingsystem` WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result = $stmt->get_result();

    if($result->num_rows>0) {
      $row = mysqli_fetch_array($result);
      $isParked = $row['isParked'];
      $co2Class = $row['co2Class'];
      $timeParked = $row['timeParked'];
      $totalDue = floatval($row['totalDue']);
      $accountNo = $row['userName'];

      ##update status of car to being unparked
      if($isParked == '1') {
        ##check amount parked in last week.
        $weekEnd = time();
        $weekStart = $weekEnd - 604800;
        
        $query = "SELECT * FROM `parkinglogs` WHERE userName=? AND inTime>=? AND inTime<?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $accountNo, $weekStart, $weekEnd);
        $stmt->execute();
        $result2 = $stmt->get_result();

        if($result2->num_rows > 0){
          $weekTimes = mysqli_fetch_array($result2);
          $weekTimes = sizeof($weekTimes);
        }
        else $weekTimes = 0; 

        list($charge,$weeklyCharge,$co2Charge, $baseRate) = calculateCharge($timeParked, $co2Class, $time, $location, $conn, $weekTimes);

        $charge = round($charge, 2);
        $totalDue = $charge + $totalDue;

        $query = "UPDATE parkingsystem set isParked=0, timeParked=0, totalDue=? where id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $totalDue,  $id);
        $stmt->execute();
        
        $response['isParked'] = '0';
        $response['totalDue'] = $totalDue;
        $response['charge'] = $charge;
        $response['timeParked'] = $timeParked;

        #update grandTotal column in users table
        $query = "UPDATE users set grandTotal=grandTotal+? where userName=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $charge,  $accountNo);
        $stmt->execute();

        #update free spaces in location column in users table
        $query = "UPDATE locationrates set free=free+1 where location=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $location);
        $stmt->execute();       

        ##log the parking in database
        $timeStamp = strftime("%Y%m%d%H%M%S", $time);
        $exitid = ("$timeStamp" . "$id"); 
        $query = "UPDATE parkinglogs set outTime=?, cost=?, exitID=?, baseRate=?, co2Charge=?, weeklyCharge=? where id=? and outTime is NULL";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $time,  $charge, $exitid,$baseRate, $co2Charge, $weeklyCharge, $id);
        $stmt->execute();

        echo json_encode($response);
      }

      ##update status of car to being parked
      if($isParked == '0') {
        $query = "UPDATE parkingsystem set isParked=1, timeParked=? where id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $time,  $id);
        $stmt->execute();

        $response['timeParked'] = $time;
        $response['isParked'] = '1';
        $response['totalDue'] = $totalDue;

        #update free spaces in location column in users table
        $query = "UPDATE locationrates set free=free-1 where location=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $location);
        $stmt->execute();     
        
        ##log the parking in database
        $timeStamp = strftime("%Y%m%d%H%M%S", $time);
        $parkingid = ("$timeStamp" . "$id"); 
        $query = "INSERT INTO parkinglogs (parkingID, inTime, userName, id, location) VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $parkingid,  $time, $accountNo, $id, $location);
        $stmt->execute();

        echo json_encode($response);
      }
     }
    mysqli_commit($conn);
}

##PUT STATEMENT FOR UPDATING GUEST PARKING
if ($_SERVER['REQUEST_METHOD'] === "PUT" && $_REQUEST['function']=="guestpark") {

  $id = $_REQUEST['id'];
  $location = $_REQUEST['location'];
  $time = $_REQUEST['time'];
  $userName = $_REQUEST['username'];

  $query = "SELECT * FROM `guestparking` WHERE id=? and location=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $id, $location);
  $stmt->execute();
  $result = $stmt->get_result();


    ##if match found
    if($result->num_rows>0) {
      ##process parking
      $row = mysqli_fetch_array($result);
      $co2Class = $row['co2Class'];
      $hours = $row['hours'];
      $isParked = $row['isParked'];
      ##check if car already parked
      if($isParked == '1') {

        ##calculate hours used
        $query = "SELECT inTime FROM `guestparkinglogs` WHERE id=? and location=? and outTime is NULL";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $id, $location);
        $stmt->execute();
        $result = $stmt->get_result();
        $row2 = mysqli_fetch_array($result);
        
        $timeIn = $row2['inTime'];
        $hours = $hours - (($time - $timeIn)/3600);

        #check hours do not go over allocated amount, if they do then calculate extra due
        if($hours < 0) { 
          #calculate extra owed
          $surpluscharge = calculateSurplusCost(abs($hours), $co2Class, $location, $conn);

          #update total due on account
          $query = "UPDATE users set grandTotal=grandTotal+? where userName=?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("ss", $surpluscharge, $userName);
          $stmt->execute();
          
          #return response informing user of extra charge.
          $response['info'] = "Your parking has exceeded the account's prepaid amount by " . number_format(abs($hours),2) . " hours, a charge of £" . number_format($surpluscharge,2) . " has been added to your account's total balance.";     
          #finally delete entry for guest parking on account as they are past 0 hours.
          $query = "DELETE FROM guestparking WHERE id=? AND location=? AND userName=?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("sss", $id,$location, $userName);
          $stmt->execute();
        }

        $query = "UPDATE guestparking set isParked=0, hours=? where id=? and location=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $hours, $id, $location);
        $stmt->execute();

        $response['timeParked'] = $time;
        $response['isParked'] = '0';
        $response['hours'] = number_format($hours,2);

        #update free spaces in location column in users table
        $query = "UPDATE locationrates set free=free+1 where location=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $location);
        $stmt->execute();  

        ##log the parking in database
        $timeStamp = strftime("%Y%m%d%H%M%S", $time);
        $exitid = ("$timeStamp" . "$id"); 
        mysqli_query($conn, "UPDATE guestparkinglogs set outTime=$time, exitID='$exitid' where id='$id' and outTime is NULL and location='$location'");
        
        $query = "UPDATE guestparkinglogs set outTime=?, exitID=? where id=? and outTime is NULL and location=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $time, $exitid, $id, $location);
        $stmt->execute();

        echo json_encode($response);
      }
      }

      ##update status of car to being parked
      if($isParked == '0') {

        $query = "UPDATE guestparking set isParked=1 where id=? and location=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $id, $location);
        $stmt->execute();

        $response['timeParked'] = $time;
        $response['isParked'] = '1';
        $response['hours'] = $hours;
        
        ##log the parking in database
        $timeStamp = strftime("%Y%m%d%H%M%S", $time);
        $parkingid = ("$timeStamp" . "$id"); 
        
        $query = "INSERT INTO guestparkinglogs (parkingID, inTime, userName, id, location) VALUES (? ,?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $parkingid, $time, $userName, $id ,$location);
        $stmt->execute();

        #update free spaces in location column in users table
        $query = "UPDATE locationrates set free=free-1 where location=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $location);
        $stmt->execute();  

        echo json_encode($response);
      }
    mysqli_commit($conn);
}

#DELETE STATEMENT FOR DELETING CARS.
if ($_SERVER['REQUEST_METHOD'] === "DELETE" && $_REQUEST['function'] == "deletecar") {
  $id = $_REQUEST['id'];

  ##check car is on system
  $result = mysqli_query($conn, "SELECT * FROM parkingsystem WHERE id = '$id';");
  
  if ($result->num_rows > 0) {

    $row = mysqli_fetch_array($result);

    if($row['totalDue'] == 0.00) {
      $result = mysqli_query($conn,"DELETE from parkingsystem WHERE id='$id'");
      echo json_encode("Car deleted.");
    }
    else {
      echo json_encode("Cannot delete car as balance is still due.");
    }
  } 
  elseif ($result->num_rows == 0) {
    echo json_encode("Car not found in database.");

  }  mysqli_commit($conn);
}
}
else { 
  http_response_code(400);
  echo json_encode("Error: API-Key invalid.");
}
} else {
  http_response_code(400);
  echo json_encode("Error: Username or API Key not specified. Please check spelling or refer to API documentation.");
} 
}else {
  http_response_code(400);
  echo json_encode("Error: Function specified is not a valid function.");
}

?>