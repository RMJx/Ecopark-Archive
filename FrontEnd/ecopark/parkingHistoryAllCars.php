<?php

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno ."&function=parkinghistoryuser&API-KEY=" . $APIKey;

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

$url = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/?username=" . $studentno ."&function=userscars&API-KEY=" . $APIKey;
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
$cars = json_decode($json_raw_str, true);

$carTallys = array_fill(0, sizeof($cars), 0);
$carRegistrations = array_fill(0, sizeof($cars), "Null");

for ($x = 0; $x <= sizeof($cars)-1; $x++) {
    $carRegistrations[$x] = strtoupper($cars[$x]['id']);
    for ($y = 0; $y <= sizeof($parkinghistory)-1; $y++){
        if($parkinghistory[$y]['id'] == $cars[$x]['id']) {
            $carTallys[$x] = $carTallys[$x] + 1;
    }
    
    }
}

?>
<!DOCTYPE html>
<html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<body>

<canvas id="myChart" style="width:100%;max-width:600px"></canvas>

<script>
var registrations = <?php echo json_encode($carRegistrations); ?>;
var parkedTally = <?php echo json_encode($carTallys); ?>;

var barColors = [
  "#870fff",
  "#893bd6",
  "#b37fe7",
  "#ca7fe7",
  "#f706ff",
  "#h6c3b9"

];

new Chart("myChart", {
  type: "doughnut",
  data: {
    labels: registrations,
    datasets: [{
      backgroundColor: barColors,
      data: parkedTally
    }]
  },
  options: {
    title: {
      display: false,
      text: "Your Parking Across Cars"
    }
  }
});
</script>

</body>
</html>

