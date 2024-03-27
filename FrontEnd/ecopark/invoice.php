<?php 

session_start();
$studentno = $_SESSION['name'];



if(!isset($_SESSION['loggedin'])){
  header('Location: ../notloggedin.php');
  exit();
}

$parkingRef = $_REQUEST['parkingID'];
$datePrinted = date('jS F Y');


//CURL PARKING DETAILS FOR INVOICING
$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $_SESSION['name'] ."&function=singlehistory&API-KEY=" . $_SESSION['APIKey'] . "&parkingID=" . $parkingRef;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
$headers = array(
    "Accept: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
$resp = curl_exec($curl);
curl_close($curl);
$json_raw_str = ltrim($resp, chr(239).chr(187).chr(191));
$parkinghistory = json_decode($json_raw_str, true);


//CURL USER DETAILS FOR INVOICING
$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/registerapi/?id=" . $_SESSION['name'] ."&function=user&API-KEY=" . $_SESSION['APIKey'];

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
$headers = array(
    "Accept: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
$resp = curl_exec($curl);
curl_close($curl);
$json_raw_str = ltrim($resp, chr(239).chr(187).chr(191));
$userdetails = json_decode($json_raw_str, true);

$inTime = $parkinghistory['inTime'];
$outTime = $parkinghistory['outTime'];
$duration = ($outTime - $inTime)/3600;

$baseCost = round($parkinghistory['baseRate'] * $duration,2);
$weeklyChargeCost = round(($baseCost* $parkinghistory['weeklyCharge']) - $baseCost,2);
$co2ChargeCost = round(($baseCost * $parkinghistory['co2Charge']) - $baseCost,2);

echo '<!DOCTYPE html>
<html>
	<head>
		<link rel="apple-touch-icon" sizes="180x180" href="/ecopark/apple-touch-icon.png">
    	<link rel="icon" type="image/png" sizes="32x32" href="/ecopark/favicon-32x32.png">
    	<link rel="icon" type="image/png" sizes="16x16" href="/ecopark/favicon-16x16.png">
		<meta charset="utf-8" />
		<title>Ecopark Parking Invoice </title>
		<link href="../style/invoice.css" rel="stylesheet" type="text/css">


	</head>

	<body>
		<div class="invoicelayout">
			<table>
				<tr class="logo"><td>
						<table><tr><td class="title"><img src="../ecopark.png" style="width: 100%; max-width: 275px"/></td>
						<td style="font-weight: 400" >Invoice Ref: ' . $parkingRef . '<br/>'. $datePrinted .'<br/></td>
						</tr>
						</table>
					</td>
				</tr>

				<tr class="information"><td>
						<table><tr><td style="font-weight: 400" >Ecopark Parking<br/>123 Titanic Quarter<br/>Belfast, UK BT9 8AL</td>
						<td style="font-weight: 400" >'. $userdetails['fname'] .' ' . $userdetails['lname'] . ' <br/>' . $userdetails['email'] . '<br/>' . $userdetails['postcode'] . '</td>
						</tr>
						</table>
					</td>
				</tr>

				<tr class="heading"><td>Cost Breakdown</td><td>Subtotal(s)</td></tr>
				<tr class="item"><td>Base Cost of £' . $parkinghistory['baseRate'] . '/hr for '. number_format($duration,2) . ' hours.' .'</td>
				<td>' . '£' .$baseCost .'</td>
				</tr>

				<tr class="item"><td>Weekly Charge Multiplier - x' . $parkinghistory['weeklyCharge'] .'</td>
				<td>' . '+ £' . $weeklyChargeCost .'</td>
				</tr>

				<tr class="item last"> <td>CO2 Charge Mulitiplier - x' . $parkinghistory['co2Charge'] .'</td>
				<td>' . '+ £' . $co2ChargeCost .'</td>
				</tr>

				<tr class="total"><td></td>
				<td style="font-weight: 400" >Total: £' . $parkinghistory['cost'] .'</td>
				</tr>
			</table>
		</div>
	</body>
</html>';


?>