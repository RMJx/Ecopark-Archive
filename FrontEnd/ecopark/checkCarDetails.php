<?php

$curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://driver-vehicle-licensing.api.gov.uk/vehicle-enquiry/v1/vehicles",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"{\n\t\"registrationNumber\": \"$newReg\"\n}",
    CURLOPT_HTTPHEADER => array(
      "x-api-key: jT5frQ4sQO5i2GLayhkQt1eiFWHxxhv01Sbtf2qW",
      "Content-Type: application/json"
   ),
  ));
  


  $response = curl_exec($curl);

/*parse json response into a readable format, in this case an array */
  $json = json_decode($response, true);
 curl_close($curl);
?>