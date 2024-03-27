
<?php
$curl = curl_init();
echo "<div class='carlist-parent'>";
for ($x = 0; $x< sizeof($cars); $x ++) {
  echo "<div class='carlist-child'>";
  $reg = strtoupper($cars[$x]['id']); 
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://driver-vehicle-licensing.api.gov.uk/vehicle-enquiry/v1/vehicles",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"{\n\t\"registrationNumber\": \"$reg\"\n}",
    CURLOPT_HTTPHEADER => array(
      "x-api-key: jT5frQ4sQO5i2GLayhkQt1eiFWHxxhv01Sbtf2qW",
      "Content-Type: application/json"
   ),
  ));

  $response = curl_exec($curl);

/*parse json response into a readable format, in this case an array */
  $json = json_decode($response, true);

  echo "<h2> Details for $reg </h2>";
/* check to see if response from API is correct, if not, then throw error message, else print details for student */
  if(count($json) < 2) echo "<h3> Registration Number '" . $reg .  "' not found in DVLA Database. </h3>"; 
  else {
  

  echo "<h3> Registration Number </h3>"; 
  echo "<p>" .  $json['registrationNumber'] . "</p>";

  echo "<h3> Ecopark CO2 Classification </h3>";
  echo '<p class="lid-' . $cars[$x]['co2Class'] . '"' . '>' . $cars[$x]['co2Class'] . '</p>';

  echo "<h3> CO2 Emissions</h3>"; 
  echo '<p class="lid-' . $cars[$x]['co2Class'] . '"' . '>' . $json['co2Emissions'] . '</p>';

  echo "<h3> Engine Capacity</h3>"; 
  echo "<p>" .  $json['engineCapacity'] . "cc" ."</p>";

  echo "<h3> Fuel Type </h3>"; 
  echo "<p>" .  $json['fuelType'] . "</p>";

  echo "<h3> MOT Status </h3>"; 
  if($json['motStatus'] == "Valid") echo "<p> <i class='fas fa-check-circle' style='color:green;'></i>" .  $json['motStatus'] . "</p>";
  elseif($json['motStatus'] == "No details held by DVLA") echo "<p> <i class='fas fa-question' style='color:orange;'></i>" .  $json['motStatus'] . "</p>";
  else echo "<p><i class='fas fa-question' style='color:orange;'></i> " .  $json['motStatus'] . "</p>";

  echo "<h3> MOT Expiry Date </h3>"; 
  if(!array_key_exists('motExpiryDate', $json)) echo "<p><i class='fas fa-question' style='color:orange;' title='Warning: MOT Due!'></i> No details held by DVLA </p>";
  elseif($json['motExpiryDate'] < gmdate("Y-m-d",strtotime('+30 days'))) {
    echo "<p> <i class='fas fa-exclamation-triangle style='color:red;'' title='Warning: MOT Due!'></i>" .  $json['motExpiryDate'] . "</p>";
  }
  else echo "<p><i class='fas fa-check-circle' style='color:green;'></i>" .  $json['motExpiryDate'] . "</p>";

  echo "<h3> Colour </h3>"; 
  echo "<p>" .  $json['colour'] . "</p>";

  echo "<h3> Make </h3>"; 
  echo "<p>" .  $json['make'] . "</p>";

  echo "<h3> Year of Manufacture </h3>"; 
  echo "<p>" .  $json['yearOfManufacture'] . "</p>";

  echo "<h3> Tax Status </h3>"; 
  if($json['taxStatus']=="SORN") echo "<p> <i class='fas fa-exclamation-triangle' style='color:red;'></i>" .  "SORN" . "</p>";
  elseif($json['taxStatus']=="Untaxed") echo "<p> <i class='fas fa-exclamation-triangle' title='Warning: Tax Expired!'></i>" .  "Untaxed" . "</p>";
  else echo "<p> <i class='fas fa-check-circle' style='color:green;'></i>" .  $json['taxStatus'] . "</p>"; 

/* Ensures that if the tax has expired on vehicle that it does not throw an error due to absence of the taxDueDate field if SORN'd */
  echo "<h3> Tax Due Date </h3>";
  if($json['taxStatus']=="SORN") {
    echo "<p> <i class='fas fa-exclamation-triangle' style='color:red;' title='Warning: Car is SORN'd and not legal for road use!'></i>" .  "N/A" . "</p>";
  }
  elseif($json['taxDueDate'] < gmdate("Y-m-d",strtotime('+30 days'))) {
    echo "<p> <i class='fas fa-exclamation-triangle' style='color:red;' title='Warning: Tax Due!'></i>" .  $json['taxDueDate'] . "</p>";
  }
  else echo "<p><i class='fas fa-check-circle' style='color:green;'></i>" .  $json['taxDueDate'] . "</p>";

  echo "<h3> Date of Last V5C Issue </h3>"; 
  echo "<p>" .  $json['dateOfLastV5CIssued'] . "</p>";

  echo '<td> <button class="deletebtn" onClick="deleteCar(' . "'" . $json['registrationNumber'] . "'" . ')"> Remove Car </button> </td>';


  } echo "</div>";
  echo "<br>";
}  
curl_close($curl);
echo "</div>";
?>